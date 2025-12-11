<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Dhaka");

require '../cron/dbCon.php'; /* return name of current default database */
require '../cron/mail_setting.php';
require "../cron/cron_job_audit.php";





// best stored as array, so you can add more than one
$sql3 = "select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1";
$re = $mysqli->query($sql3);

$get_holiday_date = $re->fetch_assoc()['holiday_date'];
$holidays = explode(',',$get_holiday_date);
//cron log start
//cron log start
$time_index = 0;
$rowcountAudit=0;
$comment='';

        $date = new DateTime(); //this returns the current date time
        $current_date = $date->format('Y-m-d-H-i-s');


//        $type_wise_max_processing_day [1] = 3; // 1= Visa Recommendation New
//        $type_wise_max_processing_day [2] = 3; // 2= Work Permit New
//        $type_wise_max_processing_day [3] = 3; // 3= Work Permit Extension
//        $type_wise_max_processing_day [4] = 3; // 4= Work Permit Amendment
//        $type_wise_max_processing_day [5] = 3; // 5= Work Permit Cancellation
//        $type_wise_max_processing_day [6] = 3; // 6= Office Permission New
//        $type_wise_max_processing_day [7] = 3; // 7= Office Permission Extension
//        $type_wise_max_processing_day [8] = 3; // 8= Office Permission Amendment
//        $type_wise_max_processing_day [9] = 3; // 9= Office Permission Cancellation
//        $type_wise_max_processing_day [10] = 3; // 10= Visa Recommendation Amendment
//        $type_wise_max_processing_day [11] = 3; // 11= Outward Remittance Approval
//        $type_wise_max_processing_day [102] = 3; // 102= BIDA Registration
$sql = "select process_type_id,desk_from,status_from, desk_to, status_to, auto_process,auto_process_reminder_before,
        max_processing_time from process_path where auto_process=1 and status_from=5 order by process_type_id ";
$result = $mysqli->query($sql);

while($row = $result->fetch_assoc()) {
    $rowcountAudit+=1;
    $process_type_id = $row['process_type_id'];
    $max_processing_time = $row['max_processing_time'];
    $status_from = $row['status_from'];
    $status_to = $row['status_to'];
    $before_reminder_day = $row['auto_process_reminder_before'];

    $date = new DateTime(); //this returns the current date time
    $current_date = $date->format('Y-m-d-H-i-s');

    $sql6 = "SELECT process_list.id as process_id, status_id, ref_id, process_list.created_by, tracking_no,desk_id,process_type_id, process_list.submitted_at, process_list.updated_at,
                        process_type.name as process_name from process_list 
                        left join process_type on process_list.process_type_id = process_type.id 
                        WHERE process_type_id =$process_type_id
                        AND process_list.status_id = 5"; // 5 = shortfall
    $process_list_data = $mysqli->query($sql6);
    //-1=Draft, 5=ShortFall, 6=Discard/Reject, 25=Approved
    //This statuses need to keep same in Database

    if ($process_list_data->num_rows > 0) {

        $CountReminder = 0;
        $desk_ids = '';
        while ($row1 = $process_list_data->fetch_assoc()) {
            $result1 = holidayAndOffDay($row1['updated_at'], $holidays);
            echo "<br>" . $row1['tracking_no'] . '---' . $result1 . '<br>';
            //May be there is a scope for optimization... in the above line
//                echo "<br>".$row1['tracking_no'].'::::'.$result1.'<br>';
//                echo $result1;
//            $set_max_processing_day = isset($type_wise_max_processing_day[$process_type_id]) ? $type_wise_max_processing_day[$process_type_id] : '-9';
//            echo $result1 . '--max-' . $set_max_processing_day;
            if ($result1 == $max_processing_time-$before_reminder_day) { //before date dynamic
                $tracking_no = $row1['tracking_no'];
                $desk_ids .= $row1['desk_id'] . ',';
                $process_name = $row1['process_name'];
                $updated_at = $row1['updated_at'];
                $deskId = $row1['desk_id'];
                $status_id = $row1['status_id'];
                $applicant_id = $row1['created_by'];

                $email_content = '<div id="table"><div class="row head"><div class="cell">Tracking No.</div><div class="cell">Process Type</div><div class="cell">Current Status</div><div class="cell">Next Status</div><div class="cell">Last Updated</div></div>';
                $email_content .= " <div class=\"row\"><div class=\"cell\">  $tracking_no </div><div class=\"cell\">  $process_name </div><div class=\"cell\">  Shortfall </div><div class=\"cell\"> Discard </div><div class=\"cell\">  $updated_at </div> </div>";

                $email_content .= '</div>';
                $sqlTotalUsersEmails = "select user_email from users where id =  $applicant_id";
                $listOfEmailsObject = $mysqli->query($sqlTotalUsersEmails);
                $listOfEmails = $listOfEmailsObject->fetch_assoc();
//
//
//                $listOfEmailsObject = $mysqli->query($sqlTotalUsersEmails);
//                $listOfEmails =  $listOfEmailsObject->fetch_assoc();
                $next_working_date = get_next_working_date($holidays, $before_reminder_day);
                insert_email_queue($listOfEmails['user_email'], $email_content, $next_working_date, $mysqli);

                $CountReminder++;
            }

        }

        if ($CountReminder > 0) {
        } else {
            echo "data not found to Reminder mail" . '<br>';
        }
    } else {
        echo "data not found to Reminder mail" . '<br>';
    }

}





function holidayAndOffDay($updated_date, $holidays){

    $newDate = date("Y-m-d", strtotime($updated_date));
    $start = new DateTime($newDate );
    $today_date = date('Y-m-d');
    $end = new DateTime($today_date);
    // otherwise the  end date is excluded (bug?)
    $end->modify('+1 day');
    $interval = $end->diff($start);

    // total days
    $days = $interval->days;
    // create an iterable period of date (P1D equates to 1 day)
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);


//    $holidays = array('2018-07-17', '2018-07-19');
//    here is checking today off or not
    foreach($period as $dt) {

        $curr = $dt->format('D');
        // substract if Saturday or Fri
        if ($curr == 'Fri' || $curr == 'Sat') {
            $days--;
        }
        // (optional) for the updated question
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }

    return $days-1;

}

function get_next_working_date($holidays, $before_reminder_day){
        $default_off_day = ['Sat','Fri'];
        $processing_time = $before_reminder_day - 1; //define one day but cronjob set after office hours for next day
        $working_days = 0;
        $current_date = new \DateTime();
        $no_of_days_next_date = 0;
        while ($working_days  <= $processing_time){
            $current_date = $current_date->modify('+1 day');
            $no_of_days_next_date ++;
            if (in_array($current_date->format('d'),$holidays) == false && in_array($current_date->format('D'),$default_off_day) == false){
                $working_days++;
            }
        }
        $expcted_date = new \DateTime();
        $expcted_date->modify($no_of_days_next_date.' day');
        return $expcted_date->format('d-M-Y');

}



function insert_email_queue($user_emails, $email_content='',$next_working_date, $mysqli){
    $subject = "Pending application";
    $date= new DateTime(); //this returns the current date time
    $current_date = $date->format('Y-m-d-H-i-s');
    $created_at =$current_date;
    $updated_at =$current_date;
    $html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\"><html xmlns=\"http://www.w3.org/1999/xhtml\"><head><style>

    #table {display:table;
    width:500px;
        border-collapse: collapse;
        text-align: center;
    }
    .row {display:table-row;
        width:250px;
        }
    .cell{display:table-cell;
        border:1px solid gray;
        padding:5px;
    }
    .head{
        border:1px solid gray;
    }

    .head .cell{
        border:2px solid gray;
        font-size: larger;
        font-weight:bolder;
    }

</style>
<title>Application process</title><link href=\"https://fonts.googleapis.com/css?family=Vollkorn\" rel=\"stylesheet\" type=\"text/css\"><style type=\"text/css\">*{font-family: Vollkorn;}</style>
    <table width=\"80%\" style=\"background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;\"><thead> <tr><td style=\"padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);\"><img style=\"margin-left: auto; margin-right: auto; display: block;\" src=\"http://uat-bida.eserve.org.bd/assets/images/bida_logo.png\" width=\"80px\" alt=\"OSS Framework\"/><h4 style=\"text-align:center\"></h4></td></tr></thead>
    <tbody>
    <tr>
    <td style=\"margin-top: 20px; padding: 15px;\">
     Dear Sir,<br/><br/><span style=\"color:#000;text-align:justify;\">
     We would like to inform you that the following pending application from your list will be reject automatically after two working day at $next_working_date. <br><br>List of application as in Table
     $email_content </br></br></br></span>Thanks<br/><b>OSS Framework Team</b><br/><br/>
         </td>
     </tr>
         <tr style=\"margin-top: 15px;\">
         <td style=\"padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);\">
         <h5 style=\"text-align:center\">All right reserved by OSS Framework 2019.</h5>
         </td>
         </tr> 
     </tbody>
     </table></html>";

    $sql5 = "INSERT INTO email_queue (email_to, email_subject,created_at,updated_at, email_content, others_info)
            VALUES ('$user_emails', '$subject','$created_at', '$updated_at', '$html','application  shortfall reminder')";

    if ($mysqli->query($sql5) === TRUE) {
        echo "Email save to email queue<br>";
    } else {
        echo "Error: " . $sql5 . "<br>" . $mysqli->error;
    }
    return true;


}

//cron log start
cronAuditSave($time_index, $rowcountAudit, $comment, $mysqli);
//cron log end
?>
