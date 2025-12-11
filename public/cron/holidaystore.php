<?php

date_default_timezone_set('Asia/Dhaka');

// MySQLi initialization
require_once 'dbCon.php';


// Limit 50 in UAT and Live
$limit = 10; // MySQL limit value (e.g select * from table_name limit 10)
$offset = 0; // MySQL offset value (e.g SELECT * FROM table_name LIMIT 10 OFFSET 15)

// check time_index get variable is set
if (isset($_GET["time_index"]))
    $time_index = $_GET["time_index"];
else
    $time_index = 0;


if ($time_index > 15) {
    die('Undefined time index !');
} elseif ($time_index > 0) {
    $offset = ($time_index * $limit) + $limit;
}


/*
 * ---- Final status -----
 * 6 = Rejected, 25 = Approved/Issued
 */
if ($app_list = $mysqli->query("
    SELECT id, ref_id, process_type_id, status_id, submitted_at, resubmitted_at, completed_date, 
    processing_duration_1, processing_duration_2 
    FROM process_list
    WHERE process_type_id != 100 
    AND status_id in (6,25)  
    AND processing_duration_1 = 0 AND processing_duration_2 = 0
    LIMIT $offset, $limit")) {

    // Check that application row is not less than 1
    if (mysqli_num_rows($app_list) < 1) {
        $mysqli->close();
        die('Not found any row');
    }

    // Get holiday list and check that list is not empty
    $holiday_query = $mysqli->query('select group_concat(holiday_date) as holiday_date from govt_holiday where is_active = 1');
    $holiday_data = $holiday_query->fetch_assoc();
    if (empty($holiday_data['holiday_date'])) {
        $mysqli->close();
        die('Holiday not found!');
    }

    $holidays = explode(',', $holiday_data['holiday_date']);

    // Count row
    $row_counter = 0;

    // process list iteration
    while ($app_row = $app_list->fetch_assoc()) {
        $row_counter += 1;
        $process_list_id = $app_row['id'];
        $process_type_id = $app_row['process_type_id'];

        // set discard status array
        $discard_statuses = [-1, 5, 22, 6, 15, 25, 19];

        // Get application process history data
        $process_history_query = $mysqli->query("select `process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, 
                                `process_list_hist`.`updated_at`,`tracking_no`,id
                                from `process_list_hist`
                                where `process_list_hist`.`process_id`  = '$process_list_id'
                                and `process_list_hist`.`process_type` = '$process_type_id' 
                                and `process_list_hist`.`status_id` != -1 
                                group by process_list_hist.updated_at order by process_list_hist.id asc");
        // Set submitted_date as $source and $target date
        $source = $target = $process_history_query->fetch_assoc();

        // Resubmit date default null
        $completed_date = null;
        $resubmit_date = null;
        $resubmit_flag = 0;

        $submitToCompleteDuration = 0;
        $resubmitToCompleteDuration = 0;

        // Process list history iteration
        $allUpdateData = [];
        while ($history = $process_history_query->fetch_assoc()) {
            $source = $target;
            $target = $history;

            if (in_array($source['status_id'], $discard_statuses) && ($source['desk_id'] == 0 or $source['desk_id'] == 6)) {
                continue;
            }

            if ($source['status_id'] == 2) {
                $resubmit_date = $history['updated_at'];
                $resubmit_flag = 1;
                $resubmitToCompleteDuration = 0;
            }


//            $diff = dayDurationConsideringHoliday($source['updated_at'], $target['updated_at'], $holidays);
//            $submitToCompleteDuration += $diff;
//            $resubmitToCompleteDuration += $diff;
            $completed_date = $target['updated_at'];
            $allUpdateData []= date('Y-m-d', strtotime($target['updated_at']));

//            echo $source['status_id'] .' - '. $target['status_id'] .' = '. $diff . '<br>';

            if ($target['status_id'] == 25) {
                break;
            }


        }
//        dd($allUpdateData);

        $diff = dayDurationConsideringHoliday($allUpdateData, $holidays);
        $submitToCompleteDuration += $diff;
        $resubmitToCompleteDuration += $diff;
        // End Process list history iteration
//        dd($submitToCompleteDuration, $resubmitToCompleteDuration);

        /*If the application processed by same day when submitted*/
        $submitToCompleteDuration = ($submitToCompleteDuration == 0 ? 1 : $submitToCompleteDuration);
        $resubmitToCompleteDuration = ($resubmitToCompleteDuration == 0 ? 1 : $resubmitToCompleteDuration);
        if ($resubmit_flag == 0) {
            $resubmitToCompleteDuration = $submitToCompleteDuration;
        }
        /*End If the application processed by same day when submitted*/


        // Updated processing_duration_1, processing_duration_2, resubmitted_date, and completed_date fields in process_list table
        $update_sql = "UPDATE process_list SET resubmitted_at='$resubmit_date', completed_date = '$completed_date', processing_duration_1 = $submitToCompleteDuration, processing_duration_2 = $resubmitToCompleteDuration WHERE id = $process_list_id";
        $mysqli->query($update_sql);
        echo 'Process no: ' . $process_list_id . ' has been updated with processing_duration_1 = ' . $submitToCompleteDuration . ' and processing_duration_2 = ' . $resubmitToCompleteDuration . '<br>';
        // End Updated processing_duration_1, processing_duration_2, resubmitted_date, and completed_date fields in process_list table
    }
    // End process list iteration

    echo "<br/>No of application processing duration request is : " . $row_counter . "<br/>";
} else {
    echo "Not found any row";
}
$mysqli->close();
die();
function dayDurationConsideringHoliday($totalDate, $holidays)
{
    $arr = array_diff($totalDate, $holidays);
//    $arr = array('11-01-2012', '01-01-2014', '01-01-2015', '09-02-2013', '01-01-2013');
    function date_sort($a, $b)
    {
        return strtotime($a) - strtotime($b);
    }

    usort($arr, "date_sort");
    $startDate = $arr[0];
    $endDate = end($arr);
    // Last updated time of application
    $start = new DateTime(date('Y-m-d', strtotime($startDate)));
    $start->modify('+1 day');

    // completed date of application
    $end = new DateTime(date('Y-m-d', strtotime($endDate)));
    $end->modify('+1 day'); // add 1 day with today's date

    // Get interval between start date and end date
    $interval = $end->diff($start);
    $days = $interval->days;
    return $days;
}


function dayDurationConsideringHolidayOld($source_date, $target_date, $holidays)
{
    // Last updated time of application
    $start = new DateTime(date('Y-m-d', strtotime($source_date)));

    // eliminate first date
    $start->modify('+1 day');

    // completed date of application
    $end = new DateTime(date('Y-m-d', strtotime($target_date)));
    $end->modify('+1 day'); // add 1 day with today's date

    // Get interval between start date and end date
    $interval = $end->diff($start);

    // Interval in day's
    $days = $interval->days;

    // create an iterable period of date (P1D equates to 1 day)
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);


    /*
     * Checking every day of $period,
     * whether, the day is in Friday or Saturday or in Holiday list
     */
    foreach ($period as $dt) {
        $curr = $dt->format('D');
        if ($curr == 'Fri' || $curr == 'Sat') {
            $days--;
        }
        else
            if (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }
    dd($days);
    return $days;
}
