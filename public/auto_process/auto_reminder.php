<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set("Asia/Dhaka");

//$servername = "103.219.147.21";
//$username = "ocpl";
//$password = "Ocpl@2017";
//$dbname = "dev-bida";
//
//// Create connection
//$conn = new mysqli($servername, $username, $password, $dbname);
//
//// Check connection
//if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
//}
//
//// Check connection
//if (!$conn) {
//    die("Connection failed: " . mysqli_connect_error());
//}
////echo "Connected successfully";
require '../cron/dbCon.php'; /* return name of current default database */
require '../cron/mail_setting.php';



// best stored as array, so you can add more than one
$sql3 = "select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1";
$re = $mysqli->query($sql3);

$get_holiday_date = $re->fetch_assoc()['holiday_date'];
$holidays = explode(',',$get_holiday_date);

$sql = "select process_type_id,desk_from,status_from, desk_to, status_to, auto_process,max_processing_time from process_path where auto_process=1";
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

        $sql0 = "select tracking_no,desk_id,process_type_id, process_list.updated_at,
                process_type.name as process_name from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                where process_type_id='$process_type_id' and status_id = '$status_from' and desk_id='$desk_id' ";
        $process_list_data = $mysqli->query($sql0);
        if ($process_list_data->num_rows > 0) {
            $email_content = '<div id="table"><div class="row head"><div class="cell">Tracking No.</div><div class="cell">Process Type</div><div class="cell">Last Updated</div></div>';

             $sql1 = "INSERT INTO auto_process_reminder 
                (from_desk, from_status, process_type_id,process_reminder,created_at,updated_at)
                VALUES ('$desk_id', '$status_from','$process_type_id',1,'$current_date', '$current_date')";
            $mysqli->query($sql1);
            $last_id = $mysqli->insert_id;

            $CountReminder = 0;
            while($row1 = $process_list_data->fetch_assoc()) {

                $result1 = holidayAndOffDay($row1['updated_at'], $holidays);
                if($result1 == $max_processing_time - 1){
                    $tracking_no = $row1['tracking_no'];
                    $process_name = $row1['process_name'];
                    $updated_at = $row1['updated_at'];
                    $email_content .= " <div class=\"row\"><div class=\"cell\">  $tracking_no </div><div class=\"cell\">  $process_name </div><div class=\"cell\">  $updated_at </div> </div>";
                    $CountReminder++;
                }

            }

//            exit();
            $email_content .= '</div>';

            if($CountReminder > 0){
                $sql4 = "select group_concat(user_email separator ',') as user_emails from users where desk_id REGEXP '^([0-9]*[,]+)*$desk_id([,]+[,0-9]*)*$'";
                $getEmail = $mysqli->query($sql4);

                $sql5 = "UPDATE auto_process_reminder SET Process_reminder='2' WHERE id=$last_id";
                $mysqli->query($sql5);
                $getAllEmail = $getEmail->fetch_assoc();
                insert_email_queue($getAllEmail['user_emails'], $email_content,$mysqli);

            }else{
                echo "data not found to Reminder mail".'<br>';
            }
        }else{
            echo "data not found to Reminder mail".'<br>';
        }

    }
}else{
    echo '';
}
$result->close();





function holidayAndOffDay($updated_date, $holidays){

    $newDate = date("Y-m-d", strtotime($updated_date));
    $start = new DateTime($newDate );
    $today_date = date('Y-m-d');
    $end = new DateTime($today_date);
    // otherwise the  end date is excluded (bug?)
//    $end->modify('+1 day');
    $interval = $end->diff($start);

    // total days
    $days = $interval->days;
//   exit();
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
    <table width=\"80%\" style=\"background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;\"><thead> <tr><td style=\"padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);\"><img style=\"margin-left: auto; margin-right: auto; display: block;\" src=\"http://localhost:8000/assets/images/basis_log_new.jpg\" width=\"80px\" alt=\"OSS Framework\"/><h4 style=\"text-align:center\"></h4></td></tr></thead>
    <tbody>
    <tr>
    <td style=\"margin-top: 20px; padding: 15px;\">
     Dear Sir,<br/><br/><span style=\"color:#000;text-align:justify;\">
     We would like to inform you that the following pending applications from your desk will be processed automatically in the next working day:<br><br>List of applications as in Table
     $email_content </br></br></br></span>Thanks<br/><b>OSS Framework</b><br/><br/>
         </td>
     </tr>
         <tr style=\"margin-top: 15px;\">
         <td style=\"padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);\">
         <h5 style=\"text-align:center\">All right reserved by OSS Framework 2018.</h5>
         </td>
         </tr> 
     </tbody>
     </table></html>";



    $sql5 = "INSERT INTO email_queue (email_to, email_subject,created_at,updated_at, email_content)
            VALUES ('$user_emails', '$subject','$created_at', '$updated_at', '$html')";

    if ($mysqli->query($sql5) === TRUE) {
        echo "Email save to email queue";
    } else {
        echo "Error: " . $sql5 . "<br>" . $mysqli->error;
    }
    return true;


}


?>
