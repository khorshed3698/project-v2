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
$time_index = 0;
$rowcountAudit=0;
$comment='';

//        $type_wise_max_processing_day [1]=5;
//        $type_wise_max_processing_day [2]=5;
//        $type_wise_max_processing_day [3]=5;
//        $type_wise_max_processing_day [4]=5;
//        $type_wise_max_processing_day [5]=5;
//        $type_wise_max_processing_day [6]=5;
//        $type_wise_max_processing_day [7]=5;
//        $type_wise_max_processing_day [8]=5;
//        $type_wise_max_processing_day [9]=5;
//        $type_wise_max_processing_day [10]=5;
//        $type_wise_max_processing_day [11]=5;
//        $type_wise_max_processing_day [102]=5;


$sql = "select process_type_id,desk_from,status_from, desk_to, status_to, auto_process,
        max_processing_time from process_path where auto_process=1 and status_from=5 order by process_type_id ";
$result = $mysqli->query($sql);
    while($row = $result->fetch_assoc()) {
        $rowcountAudit+=1;
        $process_type_id = $row['process_type_id'];
        $max_processing_time = $row['max_processing_time'];
        $status_from = $row['status_from'];
        $status_to = $row['status_to'];


        $date = new DateTime(); //this returns the current date time
        $current_date = $date->format('Y-m-d-H-i-s');

        $sql6="SELECT process_list.id as process_id, status_id, ref_id, process_list.created_by, tracking_no,desk_id,process_type_id, process_list.submitted_at, process_list.updated_at,
                process_type.name as process_name from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                WHERE process_type_id =$process_type_id
                AND process_list.status_id = 5"; // 5 = shortfall
        $process_list_data1 = $mysqli->query($sql6);


        if ($process_list_data1->num_rows > 0) {
            //update desk,status when run cron
            while($row6 = $process_list_data1->fetch_assoc()) {

                $exit_holiday = holidayAndOffDay($row6['updated_at'], $holidays);
                echo "<br>".$row6['tracking_no'].'---'.$exit_holiday.'<br>';
//                dd($exit_holiday."==".$max_processing_time);


//                $set_max_processing_day =  isset($type_wise_max_processing_day[$process_type_id])?$type_wise_max_processing_day[$process_type_id]:'-9';
//                echo '@@'.$set_max_processing_day.'-----';
                if($exit_holiday == $max_processing_time){ //today's auto process
                    $process_id = $row6['process_id'];
                    $ref_id = $row6['ref_id'];
                    $created_by = $row6['created_by'];
                    $tracking_no = $row6['tracking_no'];

                    $sql8 = "UPDATE process_list SET desk_id='0', user_id='0',
                        status_id='6', process_desc='Automatically processed from system', updated_by='-1',on_behalf_of_user='0',updated_at ='$current_date'
                        WHERE id=$process_id";
                    $mysqli->query($sql8);

                    // Update company, users and ea_apps for Basic Information Module

                    echo "Auto Process Successfully".'<br>';
                }else{
                    echo "date not found today's ".'<br>';
                }

            }
        }else{
            echo "Data not found to auto process".'<br>';
        }
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

//cron log start
cronAuditSave($time_index, $rowcountAudit, $comment, $mysqli);
//cron log end

?>
