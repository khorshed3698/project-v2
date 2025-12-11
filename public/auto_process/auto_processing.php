<?php
date_default_timezone_set("Asia/Dhaka");
require '../cron/dbCon.php'; /* return name of current default database */
require '../cron/mail_setting.php';
// Check connection
//echo "Connected successfully";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// best stored as array, so you can add more than one
$sql3 = "select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1";
$re = $mysqli->query($sql3);
$get_holiday_date = $re->fetch_assoc()['holiday_date'];
$holidays = explode(',',$get_holiday_date);

$sql = "select process_type_id,desk_from,status_from, desk_to, status_to, auto_process, auto_process_functions,
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
        $auto_process_functions = $row['auto_process_functions'];
        $date = new DateTime(); //this returns the current date time
        $current_date = $date->format('Y-m-d-H-i-s');

        $sql6="select process_list.id as process_id,process_list.ref_id, tracking_no,desk_id,process_type_id, process_list.updated_at,
                process_type.name as process_name 
                from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                where process_type_id='$process_type_id' and status_id = '$status_from' and desk_id='$desk_id' order by process_list.id desc ";
        $process_list_data1 = $mysqli->query($sql6);

        if ($process_list_data1->num_rows > 0) {
            //update desk,status when run cron
            $sql7 = "INSERT INTO auto_processing 
                (from_desk, from_status, process_type_id,auto_process, created_at,updated_at)
                VALUES ('$desk_id', '$status_from','$process_type_id',1,'$current_date', '$current_date')";
            $mysqli->query($sql7);
            $last_auto_processing_id = $mysqli->insert_id;

            while($row6 = $process_list_data1->fetch_assoc()) {
                $exit_holiday = holidayAndOffDay($row6['updated_at'], $holidays);
                echo "<br>".$row6['tracking_no'].'--- countDay:'.$exit_holiday." maxAutoPDate: " .$max_processing_time.'<br>';
//                echo $max_processing_time;
                if($exit_holiday == (int)$max_processing_time ){
                    $tracking_no = $row6['tracking_no'];
                    $process_id = $row6['process_id'];
                    $ref_id = $row6['ref_id'];
                    $process_type_id1 = $row6['process_type_id'];
//                6. Set current_desk = next desk, current_status = next_status, Remarks = "Automatically processed", Updated_by = "***".
                    $sql8 = "UPDATE process_list SET desk_id='$desk_to', status_id='$status_to',
                             process_desc='Automatically processed', updated_at='$current_date',
                             updated_by='100000',on_behalf_of_user='100000'  WHERE id=$process_id";
                    $mysqli->query($sql8);
                    sleep(1);

                    if($auto_process_functions !='' || $auto_process_functions !=null){
                        $auto_certificate = "auto_certificate";
                        $objData = json_decode($row['auto_process_functions']);
                        if(isset($objData->$auto_certificate)) {
                            $jsonFunction = $objData->$auto_certificate;
                            $certificate = $jsonFunction($ref_id, $process_type_id1, $mysqli); //call to certificate function
                        }
                    }
                    $sql9 = "UPDATE auto_processing SET auto_process='2' WHERE id=$last_auto_processing_id";
                    $mysqli->query($sql9);
                    echo "Auto Process Successfully".'<br>';
                }
            }
        }else{
            echo "Data not found to auto process".'<br>';
        }
    }
}else{
    echo 'sorry process path not define';
}
$result->close();

function holidayAndOffDay($updated_date, $holidays){

    $newDate = date("Y-m-d", strtotime($updated_date));
    $start = new DateTime($newDate );
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
    return $days;
}

function certificateGenerate($app_id, $process_type_id, $mysql)
{
    if($process_type_id == 1) {
        echo  $sql6 = "select app_type.id as app_type_id 
                from process_list 
                left join vr_apps as app on app.id = process_list.ref_id
                left join dept_process_app_type_mapping  on dept_process_app_type_mapping.id = app.app_type_mapping_id
                left join dept_application_type as app_type on app_type.id = dept_process_app_type_mapping.app_type_id 
                where process_list.process_type_id='$process_type_id' 
                and process_list.ref_id = '$app_id' limit 1 ";

        $visaData = $mysql->query($sql6);
        $appInfo = $visaData->fetch_assoc();
        
        if (empty($appInfo['app_type_id']))
            return false;

        if ($appInfo['app_type_id'] == 5) {
            $sql7 = "select pdf_server_url, reg_key, pdf_type, certificate_name, `table_name`, field_name
                 from pdf_service_info  
                 where certificate_name='vr_certificate_on_arrival' 
                 limit 1 ";
            $pdf_info1 = $mysql->query($sql7);
            $pdf_info = $pdf_info1->fetch_assoc();
        } else {

            $sql7 = "select pdf_server_url, reg_key, pdf_type, certificate_name, `table_name`, field_name
                 from pdf_service_info  
                 where certificate_name='vr_certificate_others' 
                 limit 1 ";
            $pdf_info1 = $mysql->query($sql7);
            $pdf_info = $pdf_info1->fetch_assoc();
        }

        $tableName = $pdf_info['table_name'];
        $fieldName = $pdf_info['field_name'];

    }elseif ($process_type_id == 10){
        $sql99 = "select pdf_server_url, reg_key, pdf_type, certificate_name, `table_name`, field_name
                 from pdf_service_info  
                 where certificate_name='vra_certificate' 
                 limit 1 ";
        $pdf_info12 = $mysql->query($sql99);
        $pdf_info = $pdf_info12->fetch_assoc();
        if(empty($pdf_info)){
            return false;
        }
        $tableName = $pdf_info['table_name'];
        $fieldName = $pdf_info['field_name'];

    }

    $sql33 = "SELECT id from users where desk_id = 3";
    $sqlQ = $mysql->query($sql33);
    $user_id = $sqlQ->fetch_assoc();
    $date = new DateTime(); //this returns the current date time
    $current_date = $date->format('Y-m-d-H-i-s');

    $pdf_server_url = $pdf_info['pdf_server_url'];
    $reg_key = $pdf_info['reg_key'];
    $pdf_type = $pdf_info['pdf_type'];
    $certificate_name = $pdf_info['certificate_name'];
    $signatory = $user_id['id'];

    $sql7 = "INSERT INTO pdf_print_requests_queue 
                (process_type_id, app_id, pdf_server_url,reg_key, pdf_type,certificate_name,
                 prepared_json,`table_name`,field_name, signatory, created_at,updated_at)
                VALUES ('$process_type_id', '$app_id','$pdf_server_url','$reg_key','$pdf_type',
                '$certificate_name','0','$tableName','$fieldName','$signatory',
                '$current_date', '$current_date')";
    $mysql->query($sql7);
    echo "okkkkkk";
}



?>
