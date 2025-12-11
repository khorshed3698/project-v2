<?php

date_default_timezone_set('Asia/Dhaka');

// MySQLi initialization
require_once 'dbCon.php';
require_once "cron_job_audit.php";

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

// 6, 25, 26, 27 are the final status

// cron log start
$rowCountAudit = 0;
$comment = '';
// cron log end


// Get holiday list and check that list is not empty
$holiday_query = $mysqli->query("select holiday_date from govt_holiday where is_active = 1 order by holiday_date");


$i = 1;
$holidays = [];
while ($holiday_data = $holiday_query->fetch_assoc()) {
    $holidays[$i++] = $holiday_data['holiday_date'];
}

if (empty($holidays)) {
    $mysqli->close();
    die('Holiday not found!');
}

if ($app_list = $mysqli->query("
    SELECT id, ref_id, process_type_id, status_id, submitted_at, resubmitted_at, completed_date, processing_duration_1  
    FROM process_list
    WHERE process_type_id != 100 
    AND status_id in (6, 25, 26, 27)  
    AND processing_duration_1 = 0
    LIMIT $offset, $limit")) {

    // cron log start
    $rowCountAudit = mysqli_num_rows($app_list);
    cronAuditSave($time_index, $rowCountAudit, $comment, $mysqli);
    // cron log end

    // Check that application row is not less than 1
    if ($rowCountAudit < 1) {
        $mysqli->close();
        die('Not found any row');
    }

    /**
     * The following statuses of every process type will come from the applicant user's desk.
     * Therefore, the time it takes for an application to reach the following statuses from the applicant will not be counted.

     * $user_given_statuses[process_type_id] = [process_status]
     * 1 = Submitted
     * 2 = Re-Submitted
     * 16 = Payment submit
     * 30 = Condition Fulfilled
     */
    $user_given_statuses[1] = [1, 2, 16, 30]; // Visa Recommendation New
    $user_given_statuses[2] = [1, 2, 16, 30]; // Work Permit New
    $user_given_statuses[3] = [1, 2, 16, 30]; // Work Permit Extension
    $user_given_statuses[4] = [1, 2, 16, 30]; // Work Permit Amendment
    $user_given_statuses[5] = [1, 2, 16, 30]; // Work Permit Cancellation
    $user_given_statuses[6] = [1, 2, 16, 30]; // Office Permission New
    $user_given_statuses[7] = [1, 2, 16, 30]; // Office Permission Extension
    $user_given_statuses[8] = [1, 2, 16, 30]; // Office Permission Amendment
    $user_given_statuses[9] = [1, 2, 16, 30]; // Office Permission Cancellation
    $user_given_statuses[10] = [1, 2, 16, 30]; // Visa Recommendation Amendment
    $user_given_statuses[11] = [1, 2, 16, 30]; // Outward Remittance Approval
    $user_given_statuses[12] = [1, 2, 16, 30]; // BIDA Registration Amendment
    $user_given_statuses[13] = [1, 2, 16, 30]; // IRC Recommendation New
    $user_given_statuses[14] = [1, 2, 16, 30]; // IRC Recommendation Second Adhoc
    $user_given_statuses[15] = [1, 2, 16, 30]; // IRC Recommendation Third Adhoc
    $user_given_statuses[16] = [1, 2, 16, 30]; // IRC Recommendation Regular
    $user_given_statuses[17] = [1, 2, 16, 30]; // VIP Lounge
    $user_given_statuses[18] = [1, 2, 16, 30]; // VIP Lounge Amendment
    $user_given_statuses[19] = [1, 2, 16, 30]; // Waiver Condition 8
    $user_given_statuses[101] = [1, 2, 16];
    $user_given_statuses[102] = [1, 2, 16, 30]; // BIDA Registration
    $user_given_statuses[103] = [1, 2, 16];
    $user_given_statuses[104] = [1, 2, 16];
    $user_given_statuses[105] = [1, 2, 16];
    $user_given_statuses[106] = [1, 2, 16];
    $user_given_statuses[107] = [1, 2, 16];
    $user_given_statuses[108] = [1, 2, 16];
    $user_given_statuses[109] = [1, 2, 16];

    // Count row
    $row_counter = 0;

    // process list iteration
    while ($app_row = $app_list->fetch_assoc()) {
        $row_counter += 1;
        $process_list_id = $app_row['id'];
        $process_type_id = $app_row['process_type_id'];

        echo "<br/> In process list table: <br/>process_type_id : " . $process_type_id . "<br/>";
        echo "And, process_id : " . $process_list_id . "<br/>";

        // Get application process history data for the $process_list_id
        $process_history_query = $mysqli->query("select `process_list_hist`.`desk_id`, `process_list_hist`.`status_id`, 
                                `process_list_hist`.`updated_at`,`tracking_no`,id
                                from `process_list_hist`
                                where `process_list_hist`.`process_id`  = '$process_list_id'
                                and `process_list_hist`.`process_type` = '$process_type_id' 
                                and `process_list_hist`.`status_id` not in (3,-1)
                                order by process_list_hist.id asc");

        // Set the first record as source process/ starting point of this application 
        $source_process = $target_process = $process_history_query->fetch_assoc();

        $allUpdateData = [];
        $allDaysArray = []; // To hold all the days in Y-m-d format in an array
        $daysBetweenStatusArray = [];
        $dayCount = 0;

        // Process list history iteration
        $lastConsideredDate = "";

        // Default Resubmit date
        $resubmittedDate = null;

        /**
         * Each record of the process history will rotate in a loop.
         * the first record has been set as source.
         * The next record will be counted as the target and
         * the days between the source and the target will be counted.
         *
         * In the next loop, the days between the source and the target will be counted,
         * with the previous target as the current source and the new record as the current target.
         */
        while ($process = $process_history_query->fetch_assoc()) {

            $source_process = $target_process;
            $target_process = $process;
            $daysBetweenStatusArray = [];

            echo "<br/> Source status = $source_process[status_id] and target $target_process[status_id] <br/>";

            // counting the number of days between source and target will depend on this flag
            $is_middle_days_countable = true;

            /**
             * If source status is on the applicant user's desk and target status is on Officer's desk
             * then no need to count the days between source and target.
             * Because the applicant user is liable for those days.
             */
            if (in_array($target_process['status_id'], $user_given_statuses[$process_type_id])) {
                $is_middle_days_countable = false;
                echo "<br/> Ignored: Application coming from User. So this day will not be considered for final duration calculation. Here target status:".$target_process['status_id']. "<br/>";
            }

            /**
             * If an application is shortfall many times, the duration calculation will start from the last resubmission.
             * Therefor this variable will be reset whenever the target status is shortfall.
             * Also, the resubmitted data will be updated to the process_list table resubmitted_at column
             */
            if ($target_process['status_id'] == 2) { // Resubmit
                $resubmittedDate = $target_process['updated_at'];
                $allDaysArray = [];
            }

            // Set the process starting date
            $process_start_date =  date('Y-m-d', strtotime($source_process['updated_at']));
            echo "<br/> date_from $process_start_date ==== ";

            /**
             * If the application came from the applicant's desk then one day will be added
             * The processing period will start from the day after submission of the application
             */
            if (
                in_array($source_process['status_id'], $user_given_statuses[$process_type_id]) &&
                $source_process['updated_at'] > $process_start_date . " 00:00:00"
            ) {
                $lastConsideredDate = date('Y-m-d', strtotime($source_process['updated_at']));
                $process_start_date = date('Y-m-d', strtotime($source_process['updated_at'] . ' +1 day'));
                echo "<br/> date_from_after_one_day_add $process_start_date ==== ";
            }

            // Convert process_start_date to UNIX timestamp
            // Process source
            $duration_count_from = strtotime($process_start_date);

            // Set the process ending date
            // Process target
            $process_end_date = date('Y-m-d', strtotime($target_process['updated_at']));

            // Convert process_end_date to UNIX timestamp
            $duration_count_to = strtotime($process_end_date);

            /**
             * if process_start_date and process_end_date is equal then no need to calculate this process
             */
            if ($process_start_date == $process_end_date) {
                echo "<br/>Source and target dates are same. No need to calculate duration. <br/>###################################<br/><br/>";
                continue;
            }

            // Loop from the duration_count_from to duration_count_to and output all dates in between
            for ($i = $duration_count_from; $i <= $duration_count_to; $i += 86400) {
                if ($lastConsideredDate == date("Y-m-d", $i)) {
                    echo "<br/> $lastConsideredDate already considered <br/>";
                    continue;
                }

                $dayCount++;

                if ($is_middle_days_countable) {
                    $allDaysArray[$dayCount] = date("Y-m-d", $i); // Get all the days between source and target
                }

                $daysBetweenStatusArray[$dayCount] = date("Y-m-d", $i);
                $lastConsideredDate = $daysBetweenStatusArray[$dayCount];
            }

            echo "<br/ > inBetweenDays of 2 statuses: <pre>";
            print_r($daysBetweenStatusArray);

            $inBetweenDaysExceptHolidays = array_diff($daysBetweenStatusArray, $holidays);
            echo "<br/ > inBetween after Holidays: ";
            print_r($inBetweenDaysExceptHolidays);

            $inBetweenTotDays = count($inBetweenDaysExceptHolidays);
            echo "<br/ > inBetween after Holidays Total Days: $inBetweenTotDays";
        }

        echo "<br/> Days for full processing: ";
        print_r($allDaysArray);

        $daysInFullProcessing = array_diff($allDaysArray, $holidays);
        echo "<br/> Dates for full processing without Holidays: ";
        print_r($daysInFullProcessing);

        $daysCounting = count($daysInFullProcessing);

        // if the total processing duration found 0 day, make it 1 day
        if ($daysCounting == 0) {
            $daysCounting = 1;
        }

        $processListUpdatedSql = "UPDATE process_list SET resubmitted_at = '$resubmittedDate', processing_duration_1 = $daysCounting WHERE id = '$process_list_id'";
        $mysqli->query($processListUpdatedSql);

        echo "<br/>" . $processListUpdatedSql . "<br/>*************************** New calculation start (if any)::: ******************<br/> ";
    }
} else {
    echo "Not found any row";
    // cron log start
    cronAuditSave($time_index, $rowCountAudit, $comment, $mysqli);
    // cron log end
}

$mysqli->close();