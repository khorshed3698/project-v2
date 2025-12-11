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

$sql = "select id as process_type_id, auto_process,max_processing_day,final_status from process_type where auto_process=1";
$result = $mysqli->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $process_type_id = $row['process_type_id'];
        $max_processing_time = $row['max_processing_day'];
        $final_status = $row['final_status'];

        $sql0 = "select tracking_no,desk_id,process_type_id, process_list.updated_at,submitted_at,
                process_type.name as process_name from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                where process_type_id='$process_type_id' AND process_list.status_id NOT IN(-1, 5, 6, 25)";
        $process_list_data = $mysqli->query($sql0);

        if ($process_list_data->num_rows > 0) {

            $CountReminder = 0;
            $desk_ids = '';
            while($row1 = $process_list_data->fetch_assoc()) {
                $result1 = holidayAndOffDay($row1['submitted_at'], $holidays);
//                echo "<br>".$row1['tracking_no'].'---'.$result1.'<br>';
                if($result1 == $max_processing_time){ //today
                    $desk_ids.= $row1['desk_id'].',';
                    echo "<br>".$row1['tracking_no'].'---'.$result1.'<br>';
                    $CountReminder++;
                }

            }


            if($CountReminder > 0){

                //approval desk
                $sql01 = "SELECT desk_from as approval_desk from process_path 
                WHERE status_to = '$final_status' 
                AND process_type_id = '$process_type_id'";
                $getResult = $mysqli->query($sql01);
                $getDesk = $getResult->fetch_assoc();
                $approvalDesk = $getDesk['approval_desk'];
                $desk_ids .= $approvalDesk; // add the approval desk id

                $desk_idssArr2 = explode(",",$desk_ids);
                sort($desk_idssArr2);
                echo $desk_idssArr = implode(",", $desk_idssArr2);
                $desk_idssArr = ltrim($desk_idssArr,',');
                echo "<br>";

                //get approval user,current user, delegations user from desk
                $sql07 = "select group_concat(id separator ',') as user_ids,group_concat(user_email separator ',')  
                as list_of_user_id from users where 
                (desk_id in ($desk_idssArr) or reverse(desk_id) in ($desk_idssArr))";
                $getDelegateUserId = $mysqli->query($sql07);
                $list_of_user_id =  $getDelegateUserId->fetch_assoc()['user_ids'];

                //get approval user,current user, delegations user from users table
                $sqlTotalUsersPhones = "select group_concat(user_phone) user_phones from 
                (select group_concat(id), group_concat(user_phone) as user_phone 
                from users where id in ($list_of_user_id) and delegate_to_user_id = 0 
                union
                select group_concat(u2.id), group_concat(u2.user_phone) as user_phones 
                from users u2 left join users u1 on u1.delegate_to_user_id = u2.id
                where u1.id in($list_of_user_id) and u1.delegate_to_user_id !=0) t";


                $listOfPhonesObject = $mysqli->query($sqlTotalUsersPhones);
                $listOfPhones =  $listOfPhonesObject->fetch_assoc();
                $phoneExp = explode(",", $listOfPhones['user_phones']);
                foreach ($phoneExp as $number){
                    insert_email_queue($number, $CountReminder,$mysqli);
                }


            }else{
                echo "Data not found to Reminder SMS".'<br>';
            }
        }else{
            echo "Data not found to Reminder SMS".'<br>';
        }

    }
}else{
    echo 'null';
}
$result->close();



function getDelegateUser($getDelegateUserIds, $mysqli)
{
    $sql4 = "select user_email, delegate_to_user_id from users where id in ($getDelegateUserIds)";
    $getDelegateEmail = $mysqli->query($sql4);
    $userString = '';
    while ($row = $getDelegateEmail->fetch_assoc()){
        $userString .= $row['user_email'].',';
    }
    return rtrim($userString,',');


}

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

function insert_email_queue($userNumber, $totalApplication='',$mysqli){

    $date= new DateTime(); //this returns the current date time
    $current_date = $date->format('Y-m-d-H-i-s');
    $created_at =$current_date;
    $updated_at =$current_date;
    $content = "To be informed you that $totalApplication application(s) will be processed 
    by today if you don’t take any action \n Thanks \n BIDA”";

    $sql5 = "INSERT INTO email_queue (sms_to,created_at,updated_at, sms_content, others_info)
            VALUES ('$userNumber','$created_at', '$updated_at', '$content','module_wise_auto_reminder_sms')";

    if ($mysqli->query($sql5) === TRUE) {
        echo "SMS save to email queue".'<BR>';
    } else {
        echo "Error: " . $sql5 . "<br>" . $mysqli->error;
    }
    return true;


}


?>
