<?php

require 'dbCon.php'; /* return name of current default database */
require 'mail_setting.php';
require "cron_job_audit.php";


$time_index = 0;
$rowcountAudit = 0;
$comment = '';

$data = array();
if ($pdfQueue = $mysqli->query("SELECT pprq.id, pprq.signatory, pprq.app_id, pprq.certificate_name, pprq.prepared_json, psi.sql, psi.pdf_type, psi.reg_key,psi.pdf_server_url FROM pdf_print_requests_queue pprq LEFT JOIN pdf_service_info psi on pprq.certificate_name=psi.certificate_name where pprq.prepared_json=0 limit 10")) {
    $requestCounter = 0;
    $rowcountAudit = mysqli_num_rows($pdfQueue);

    $setGroupConcateLimit = $mysqli->query("SET SESSION group_concat_max_len = 1200000");

    while ($rowForPdfQueue = $pdfQueue->fetch_assoc()) {
        $requestCounter += 1;
        $pdf_req_info_id = $rowForPdfQueue['id'];
        $pdf_req_app_id = $rowForPdfQueue['app_id'];
        $signatory = $rowForPdfQueue['signatory'];
        $certificate_name = $rowForPdfQueue['certificate_name'];
        $final_encoded_data = '';
        $requested_sql = str_replace("{app_id}", "$pdf_req_app_id", $rowForPdfQueue['sql']);

        $queryForReqSQL = $mysqli->query("$requested_sql");


        if ($queryForReqSQL) {
            $x = array();
            while ($rowForReqSQL = mysqli_fetch_assoc($queryForReqSQL)) {
                $rowcount = mysqli_num_rows($rowForReqSQL);
                $data['data']['json'] = $rowForReqSQL;
            }

            $data['data']['reg_key'] = $rowForPdfQueue['reg_key'];
            $data['data']['pdf_type'] = $rowForPdfQueue['pdf_type'];
            $data['data']['ref_id'] = $pdf_req_app_id;

            $b_urlimg = ($data['data']['json']['b_urlimg']) ? $data['data']['json']['b_urlimg'] : $signatory;

            if ($signatory != 0) {
                $data['data']['json']['a_urlimg'] = env('PROJECT_ROOT_IP', 'PROJECT_ROOT_IP') . "/cron/signature_api/rest/user?signature_id=$signatory";

                $data['data']['json']['b_urlimg'] = env('PROJECT_ROOT_IP', 'PROJECT_ROOT_IP') . "/cron/signature_api/rest/user?signature_id=$b_urlimg";
                // $data['data']['json']['a_urlimg'] = "https://192.168.151.219"."/cron/signature_api/rest/user?signature_id=$signatory";
            }
            $data['data']['param']['app_id'] = $pdf_req_app_id;
            // echo '<pre>';
            // print_r($data);
            // exit;
            $jsonAllData = json_encode($data, JSON_UNESCAPED_UNICODE);
//             dd($jsonAllData);

            $prepared_json = 1;
            if ($jsonAllData == false) {
                $prepared_json = "-1"; // SQL or DATA ERROR
            }
            $encoded_data = str_replace("\\", "", $jsonAllData);
            $encoded_data2 = str_replace("\"[", "[", $encoded_data);
            $encoded_data3 = str_replace("]\"", "]", $encoded_data2);
            $encoded_data4 = str_replace("'", "’", $encoded_data3); // Replace single quotes (') by (’) from json data, for footer text quotes issue
            // Single quotation has been replaced but, Double quotation (") can not be possible to replace due to outer json quotation (")
            $final_encoded_data = $encoded_data4;
        } else {
            echo "something wrong your sql query!";
            $prepared_json = "-9";
        }
        $sql3 = "UPDATE pdf_print_requests_queue SET prepared_json=$prepared_json, job_sending_status=0, no_of_try_job_sending=0, job_receiving_status=0,  no_of_try_job_receving=0,  certificate_link='', url_requests='$final_encoded_data' WHERE id='$pdf_req_info_id'";
        $mysqli->query($sql3);
        print_r($final_encoded_data);

    }
    if ($requestCounter == 0) {
        echo "Not found any row";
    }
    $pdfQueue->close();
} else {
    echo "Not found any row";
}

cronAuditSave($time_index, $rowcountAudit, $comment, $mysqli);