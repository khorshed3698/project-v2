<?php
date_default_timezone_set("Asia/Dhaka");


require '../cron/dbCon.php'; /* return name of current default database */
require '../cron/mail_setting.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// best stored as array, so you can add more than one
$sql3 = "select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1";
$re = $mysqli->query($sql3);
$get_holiday_date = $re->fetch_assoc()['holiday_date'];
$holidays = explode(',',$get_holiday_date);

$sql = "select id as process_type_id, auto_process,max_processing_day,auto_process_functions 
from process_type where auto_process=1";
$result = $mysqli->query($sql);
if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
        $process_type_id = $row['process_type_id'];
        $max_processing_day = $row['max_processing_day'];
        $date = new DateTime(); //this returns the current date time
        $current_date = $date->format('Y-m-d-H-i-s');

        $sql6="SELECT process_list.id as process_id, ref_id, process_list.company_id, process_list.created_by, tracking_no,desk_id,process_type_id, process_list.submitted_at, process_list.updated_at,
                process_type.name as process_name from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                WHERE process_type_id='$process_type_id' 
                AND process_list.status_id NOT IN(-1, 5, 6, 25)";
        $process_list_data1 = $mysqli->query($sql6);


        if ($process_list_data1->num_rows > 0) {
            //update desk,status when run cron
            while($row6 = $process_list_data1->fetch_assoc()) {

                $exit_holiday = holidayAndOffDay($row6['submitted_at'], $holidays);
                echo "<br>".$row6['tracking_no'].'---'.$exit_holiday.'<br>';
                if($exit_holiday == $max_processing_day){ //today's auto process

                    $process_id = $row6['process_id'];
                    $ref_id = $row6['ref_id'];
                    $company_id = $row6['company_id'];
                    $created_by = $row6['created_by'];
                    $tracking_no = $row6['tracking_no'];
                    $process_type_id = $row6['process_type_id'];

                    if($row['auto_process_functions']!='' || $row['auto_process_functions']!=null){ //only work for basc info module
                        $FullProcess = "Full_Process";
                        $objData = json_decode($row['auto_process_functions']);
//                        $s= $row['auto_process_functions'][$FullProcess];
                        if(isset($objData->$FullProcess)) {
                            //json extract
                            //A distinguished function will be called based on Database value
                            //See the full_process from Json value in DB
                            $jsonFunction = $objData->$FullProcess;
                            $dept_id = $jsonFunction($ref_id, $mysqli);
                            $sql8 = "UPDATE process_list SET desk_id='0', user_id='0',  
                            status_id='25', process_desc='Automatically processed', updated_by='1',on_behalf_of_user='1',
                            updated_at ='$current_date',department_id ='$dept_id'   
                            WHERE id=$process_id";
                            $mysqli->query($sql8);
                        }
                    }else{
                        $sql8 = "UPDATE process_list SET desk_id='0', user_id='0',  
                        status_id='25', process_desc='Automatically processed from system', updated_by='-1',on_behalf_of_user='0',updated_at ='$current_date'   
                        WHERE id=$process_id";
                        $mysqli->query($sql8);
                        //Here updated_by = -1 means from System
                    }

                    // Update company, users and ea_apps for Basic Information Module
                    if($process_type_id == 100){
                        $updateCompany = "UPDATE company_info SET is_approved=1, is_rejected='no', company_status=1, is_eligible=1 
                                WHERE id=$company_id";
                        $mysqli->query($updateCompany);

                        $approveUser = "UPDATE users SET user_status='active', is_approved=1 
                                WHERE id=$created_by";
                        $mysqli->query($approveUser);

                        $today = date('Y-m-d');
                        $approveBIapps = "UPDATE ea_apps SET approve_date=$today, is_approved=1 
                                WHERE id=$ref_id";
                        $mysqli->query($approveBIapps);
                    }

                    echo "Auto Process Successfully".'<br>';
                }else{
                    echo "date not found today's ".'<br>';
                }

            }
        }else{
            echo "Data not found to auto process".'<br>';
        }
    }
}else{
    echo 'Not auto process for any module';
}
$result->close();

function setDeptAndOthers($ref_id, $mysqli) // It will call from Additional different functions
{
    $sql9 = "SELECT registered_by_id from ea_apps WHERE id = $ref_id ";
    $result5 = $mysqli->query($sql9);
    $data = $result5->fetch_assoc();
    $id = $data['registered_by_id'];
    $sql10 = "SELECT dept_id from ea_registration_type WHERE id = $id ";
    $result6 = $mysqli->query($sql10);
    $dept_ID = $result6->fetch_assoc();
    return $dept_ID['dept_id'];
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



?>
