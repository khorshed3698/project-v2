<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Dhaka");

require '../cron/dbCon.php'; /* return name of current default database */
require '../cron/mail_setting.php';

// best stored as array, so you can add more than one
$sql3 = "select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1";
$re = $mysqli->query($sql3);

$get_holiday_date = $re->fetch_assoc()['holiday_date'];
$holidays = explode(',',$get_holiday_date);

//get process wise data
$sql = "select process_type_id,desk_from,status_from, desk_to, status_to, auto_process,
        max_processing_time from process_path where auto_process=1 order by process_type_id ";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $process_type_id = $row['process_type_id'];
        $max_processing_time = $row['max_processing_time'];
        $desk_id = $row['desk_from'];
        $status_from = $row['status_from'];
        $desk_to = $row['desk_to'];
        $status_to = $row['status_to'];
        $date = new DateTime(); //this returns the current date time
        $current_date = $date->format('Y-m-d-H-i-s');


        $sql6="select process_list.id as process_id, tracking_no,desk_id,process_type_id, process_list.updated_at,
                process_type.name as process_name 
                from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                where process_type_id='$process_type_id' and status_id = '$status_from' and desk_id='$desk_id' ";
        $process_list_data = $mysqli->query($sql6);


        if ($process_list_data->num_rows > 0) {
            $email_content = '<div id="table"><div class="row head"><div class="cell">Tracking No.</div><div class="cell">Process Type</div><div class="cell">Last Updated</div></div>';

            $CountReminder = 0;
            $desk_ids = '';
            while($row1 = $process_list_data->fetch_assoc()) {

                $result1 = holidayAndOffDay($row1['updated_at'], $holidays);
                //May be there is a scope for optimization... in the above line
//                echo "<br>".$row1['tracking_no'].'--- countDay:'.$result1." maxAutoPDate: " .$max_processing_time.'<br>';
//                echo $max_processing_time;
//                echo $result1;
                dump($row1['tracking_no']);
                if($result1 == $max_processing_time){ //before one day
//                    dump($row1['tracking_no']);
                    echo "<br>".$row1['tracking_no'].'---'.$result1.'<br>';
                    $tracking_no = $row1['tracking_no'];
                    $desk_ids.= $row1['desk_id'].',';
                    $process_name = $row1['process_name'];
                    $updated_at = $row1['updated_at'];
                    $email_content .= " <div class=\"row\"><div class=\"cell\">  $tracking_no </div><div class=\"cell\">  $process_name </div><div class=\"cell\">  $updated_at </div> </div>";
                    $CountReminder++;
                }

            }

            $email_content .= '</div>';

            if($CountReminder > 0){

                //approval desk
                $sql01 = "SELECT desk_from as approval_desk from process_path 
                WHERE status_to = '25'  
                AND process_type_id = '$process_type_id'";
                $getResult = $mysqli->query($sql01);
                $getDesk = $getResult->fetch_assoc();
                $approvalDesk = $getDesk['approval_desk'];
                $desk_ids .= $approvalDesk; // add the approval desk id

                $desk_idssArr2 = explode(",",$desk_ids);
                sort($desk_idssArr2);
                $desk_idssArr = implode(",", $desk_idssArr2);
//                echo "<br>";
                $desk_idssArr = ltrim($desk_idssArr,',');
                //get approval user,current user, delegations user from desk
                $sql07 = "select group_concat(id separator ',') as user_ids,group_concat(user_email separator ',')  
                as list_of_user_id from users where 
                (desk_id in ($desk_idssArr) or reverse(desk_id) in ($desk_idssArr))";
                $getDelegateUserId = $mysqli->query($sql07);
                $list_of_user_id =  $getDelegateUserId->fetch_assoc()['user_ids'];

                //get approval user,current user, delegations user from users table
                $sqlTotalUsersEmails = "select group_concat(emails) user_emails from 
                (select group_concat(id), group_concat(user_email) as emails 
                from users where id in ($list_of_user_id) and delegate_to_user_id = 0 
                union
                select group_concat(u2.id), group_concat(u2.user_email) as emails 
                from users u2 left join users u1 on u1.delegate_to_user_id = u2.id
                where u1.id in($list_of_user_id) and u1.delegate_to_user_id !=0) t";


                $listOfEmailsObject = $mysqli->query($sqlTotalUsersEmails);
                $listOfEmails =  $listOfEmailsObject->fetch_assoc();
//                dd($listOfEmails);
                insert_email_queue($listOfEmails['user_emails'], $email_content,$mysqli);

            }else{
                echo "data not found to Reminder mail".'<br>';
            }
        }else{
            echo "data not found to Reminder mail".'<br>';
        }

    }
}else{
    echo 'auto process not define';
}
$result->close();


function holidayAndOffDay($submitted_date, $holidays){

    $newDate = date("Y-m-d", strtotime($submitted_date));
    $start = new DateTime($newDate);
    $start->modify('+1 day');  /// Cross first date

    $today_date = date('Y-m-d');
    $end = new DateTime($today_date);
    // otherwise the  end date is excluded (bug?)
    $end->modify('+1 day');
    $interval = $end->diff($start);
    // total days
    $days = $interval->days;
    // create an iterateable period of date (P1D equates to 1 day)
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

//    $holidays = array('2018-07-17', '2018-07-19');
    foreach($period as $dt) {
        $curr = $dt->format('D');
        // substract if Saturday or Sunday
        if ($curr == 'Fri' || $curr == 'Sat') {
            $days--;
        }
        // (optional) for the updated question
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }
//    dd($days);
//    return $days-1;
    return $days;

}

function insert_email_queue($user_emails, $email_content='',$mysqli){

    $subject = "Pending applications";
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
    <table width=\"80%\" style=\"background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;\"><thead> <tr><td style=\"padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);\"><img style=\"margin-left: auto; margin-right: auto; display: block;\" src=\"/assets/images/bida_logo.png\" width=\"80px\" alt=\"OSS Framework\"/><h4 style=\"text-align:center\"></h4></td></tr></thead>
    <tbody>
    <tr>
    <td style=\"margin-top: 20px; padding: 15px;\">
     Dear Sir,<br/><br/><span style=\"color:#000;text-align:justify;\">
     We would like to inform you that the following pending applications from your desk will be processed automatically in the next working day:<br><br>List of applications as in Table
     $email_content </br></br></br></span>Thanks<br/><b>BIDA</b><br/><br/>
         </td>
     </tr>
         <tr style=\"margin-top: 15px;\">
         <td style=\"padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);\">
         <h5 style=\"text-align:center\">All right reserved by BIDA 2018.</h5>
         </td>
         </tr> 
     </tbody>
     </table></html>";

    $sql5 = "INSERT INTO email_queue (email_to, email_subject,created_at,updated_at, email_content, others_info)
            VALUES ('$user_emails', '$subject','$created_at', '$updated_at', '$html','module_wise_auto_reminder')";

    if ($mysqli->query($sql5) === TRUE) {
        echo "Email save to email queue<br>";
    } else {
        echo "Error: " . $sql5 . "<br>" . $mysqli->error;
    }
    return true;


}


?>
