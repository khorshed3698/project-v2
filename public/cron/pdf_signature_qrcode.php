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


//    ALTER TABLE `pdf_signature_qrcode` ADD UNIQUE `unique_index`(`app_id`, `process_type_id`);

if ($queue_list = $mysqli->query("SELECT process_type_id, app_id, signatory FROM pdf_print_requests_queue LIMIT $offset, $limit")) {

    // Check that application row is not less than 1
    if (mysqli_num_rows($queue_list) < 1) {
        $mysqli->close();
        die("Not found any row.");
    }

    // Count row
    $row_counter = 0;

    while ($queue_row = $queue_list->fetch_assoc()) {

        $row_counter++;
        $app_id  = $queue_row['app_id'];
        $process_type_id = $queue_row['process_type_id'];
        $signatory = $queue_row['signatory'];

        $qr_insert_into_select_sql = "INSERT INTO pdf_signature_qrcode (signature_type,app_id,process_type_id,signer_user_id,signer_desk,signer_name,signer_designation,signer_phone,signer_mobile,signer_email,signature_encode)
                                SELECT 'final','$app_id','$process_type_id',users.id, user_desk.desk_name, CONCAT(users.user_first_name,' ',users.user_middle_name,' ',users.user_last_name) AS user_full_name,
                                users.designation, users.user_phone, users.user_number, users.user_email, users.signature_encode FROM users LEFT JOIN user_desk ON users.desk_id = user_desk.id
                                WHERE users.id='$signatory'";

        if (!$mysqli->query($qr_insert_into_select_sql)) {
            echo "Error: ".$mysqli->error."<br>";
        }

//        $get_user_query = $mysqli->query("SELECT users.id, user_desk.desk_name, CONCAT(users.user_first_name,' ',users.user_middle_name,' ',users.user_last_name) AS user_full_name,
//                                                users.designation, users.user_phone, users.user_number, users.user_email, users.signature_encode FROM users LEFT JOIN user_desk ON users.desk_id = user_desk.id
//                                                WHERE users.id=$signatory");
//
//        $requested_user = $get_user_query->fetch_assoc();
//
//        if (empty($requested_user)) {
//
//            $signer_user_id = $requested_user['id'];
//            $signer_desk = $requested_user['user_desk'];
//            $signer_name = $requested_user['user_full_name'];
//            $signer_designation = $requested_user['designation'];
//            $signer_phone = $requested_user['user_phone'];
//            $signer_mobile = $requested_user['user_number'];
//            $signer_email = $requested_user['user_email'];
//            $signature_encode = $requested_user['signature_encode'];
//
//            $signatory_query = $mysqli->query("INSERT INTO pdf_signature_qrcode (signature_type,app_id,process_type_id,signer_user_id,signer_desk,signer_name,signer_designation,signer_phone,signer_mobile,signer_email,signature_encode)
//                                                    VALUES ('final','$app_id','$process_type_id','$signer_user_id','$signer_desk','$signer_name','$signer_designation','$signer_phone','$signer_mobile','$signer_email','$signature_encode')");
//
//        }
    }

    echo "<br/>No of processing request is : " . $row_counter . "<br/>";

} else {
    echo "Not found any row";
}

$mysqli->close();
die();