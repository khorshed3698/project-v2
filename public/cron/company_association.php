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


if ($time_index > 0) {
    $offset = ($time_index * $limit) - $limit;
}
//var_dump($limit, $offset);
//exit();
/*
 *
 */
if ($user_list = $mysqli->query("SELECT id, company_ids from users where user_type = '5x505' LIMIT $offset, $limit")) {

    // Check that application row is not less than 1
    if (mysqli_num_rows($user_list) < 1) {
        $mysqli->close();
        die("Not found any row.");
    }

    // Count row
    $row_counter = 0;

    // process list iteration
    while ($user_row = $user_list->fetch_assoc()) {

        $row_counter++;
        $user_id = $user_row['id'];
        $company_id = $user_row['company_ids'];

        $requested_company_query = $mysqli->query("SELECT * FROM `company_association_request` 
                                      WHERE `user_id` = '$user_id' AND `requested_company_id` = '$company_id' LIMIT 1");

        $requested_company = $requested_company_query->fetch_assoc();
        $current_date_time = date('Y-m-d H:i:s');

        if (empty($requested_company)) {

            $company_association_query = $mysqli->query("INSERT INTO `company_association_request` (
                        `user_id`, `current_company_ids`, `requested_company_id`, `approved_user_type`, `request_type`, `desk_remarks`, 
                        `user_remarks`, `application_date`, `status_id`, `status`, `is_archive`, `created_at`, `created_by`, `updated_at`, `updated_by`)
                        VALUES ('$user_id', '0', '$company_id', 1, 1, NULL, 'Request from CRON', '$current_date_time', '25', '1', 
                        '0', '$current_date_time', '0', '$current_date_time', '0')");
        }

    }

    echo "<br/>No of processing request is : " . $row_counter . "<br/>";

} else {
    echo "Not found any row";
}
$mysqli->close();
die();