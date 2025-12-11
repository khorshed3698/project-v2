<?php

date_default_timezone_set('Asia/Dhaka');

// MySQLi initialization
require_once 'dbCon.php';

// ****** demo url: 'cron/attachment_migration.php?time_index=1&table=wp_apps' ******

// Limit 50 in UAT and Live
$limit = 50; // MySQL limit value (e.g select * from table_name limit 10)
$offset = 0; // MySQL offset value (e.g SELECT * FROM table_name LIMIT 10 OFFSET 15)

// check time_index get variable is set
if (isset($_GET["time_index"]))
    $time_index = $_GET["time_index"];
else
    $time_index = 0;


if ($time_index > 0) {
    $offset = ($time_index * $limit) - $limit;
}

// check db table & get process info start
if (!isset($_GET['table'])) {
    die("Please set table name!");
}
$table = $_GET['table'];
if ($table_info = $mysqli->query("Select id from process_type where table_name = '$table'")) {

    // Check that application row is not less than 1
    if (mysqli_num_rows($table_info) != 1) {
        $mysqli->close();
        die("Invalid table name!");
    }
    $process_type_id = $table_info->fetch_assoc()['id'];
}

// doc info name start
$doc_info['doc_name'] = [
    'Copy of the first work permit',
    'Copy of the release order/termination letter/No objection certificate',
    'Copy of the Resignation letter',
    'Copy of the last extension (if applicable)',
    'Copy of the cancellation of the last work permit',
    'Copy of the income tax certificate for the last assessment year of the previous stay'
];
$doc_info['short_name'] = [
    'th_first_work_permit',
    'th_release_order',
    'th_resignation_letter',
    'th_last_extension',
    'th_last_work_permit',
    'th_income_tax'
];
// doc info name end

// insert attachment_list start
$key = substr($table, 0, 2) . 'n_travel_history';
$sql_1 = "Select atl.id as doc_info_id, atl.doc_name, att.id as attachment_type_id 
                                              from attachment_list atl left join attachment_type att on atl.attachment_type_id = att.id and atl.process_type_id = att.process_type_id
                                              where att.process_type_id = $process_type_id and att.key = '$key'";
if ($attachment_list = $mysqli->query($sql_1)) {

    if (mysqli_num_rows($attachment_list) < 1) {

        // get attachment type id start
        if ($attachment_info = $mysqli->query("Select id from attachment_type where attachment_type.key = '$key'")) {
            if (mysqli_num_rows($attachment_info) != 1) {
                $mysqli->close();
                die("Invalid attachment key!.");
            }
            $attachment_type_id = $attachment_info->fetch_assoc()['id'];
        } // check db table & get process info end

        foreach ($doc_info['doc_name'] as $key => $doc) {
            $short_name = $doc_info['short_name'][$key];

            $sql_2 = "INSERT INTO attachment_list (process_type_id, attachment_type_id, doc_name, short_note, doc_priority, additional_field, is_multiple, attachment_list.order, business_category, status, is_archive) 
                             VALUES ('$process_type_id', '$attachment_type_id', '$doc', '$short_name', '0', '0', '0', '0', '1', '1', '0')";
            if ($mysqli->query($sql_2) === false) {
                die("Attachment list insertion error!");
            }
        }
        $attachment_list = $mysqli->query($sql_1);
    }
}// insert attachment_list end

// Count row
$success_counter = 0;
$error_counter = 0;

$sql_3 = "Select id, th_first_work_permit, th_resignation_letter, th_release_order, th_last_extension, th_last_work_permit, th_income_tax from $table 
                                        where th_first_work_permit <> '' or th_resignation_letter <> '' or th_release_order <> '' or th_last_extension <> '' or th_last_work_permit <> '' or th_income_tax <> '' 
                                        LIMIT $offset, $limit";
if ($app_list = $mysqli->query($sql_3)) {

    // Check that application row is not less than 1
    if (mysqli_num_rows($app_list) < 1) {
        $mysqli->close();
        die("Not found any row.");
    }

    // store app_document start
    while ($app = $app_list->fetch_assoc()) {
        $index = 0;
        $ref_id = $app['id'];

        while ($list = $attachment_list->fetch_assoc()) {

            $doc_info_id = $list['doc_info_id'];
            $attachment_type_id = $list['attachment_type_id'];
            $doc_name = $list['doc_name'];
            $doc_file_path = $app[$doc_info['short_name'][$index]];

            $sql_4 = "INSERT INTO app_documents (process_type_id, ref_id, doc_info_id, doc_name, doc_file_path, is_old_file, doc_section, is_archive)
                             VALUES ('$process_type_id', '$ref_id', '$doc_info_id', '$doc_name', '$doc_file_path', 0, 'type2', 0)";

            if ($mysqli->query($sql_4) === false) {
                echo $mysqli->error . " - (Process Type Id: $process_type_id " . ", Ref Id: $ref_id" . ", Doc Info Id: $doc_info_id)" . "<br/>";
                $error_counter++;
            } else {
                $success_counter++;
            }
            $index++;
        }
        $attachment_list->data_seek(0);
    } // store app_documents end

    echo "<br/>No of attachment successfully migrate: " . $success_counter . "<br/>";
    echo "No of attachment migration error : " . $error_counter . "<br/>";

} else {
    echo "Not found any row.";
}
$mysqli->close();
die();