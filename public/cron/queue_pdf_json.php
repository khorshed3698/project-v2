<?php

require_once 'dbCon.php'; /* return name of current default database */
require_once 'mail_setting.php';
require_once "cron_job_audit.php";


$time_index = 0;
$rowcountAudit = 0;
$comment = '';

$data = array();
if ($pdfQueue = $mysqli->query("SELECT pprq.id, pprq.process_type_id, pprq.app_id, pprq.certificate_name, pprq.prepared_json, psi.sql, psi.pdf_type, psi.reg_key,psi.pdf_server_url FROM pdf_print_requests_queue pprq LEFT JOIN pdf_service_info psi on pprq.certificate_name=psi.certificate_name where pprq.prepared_json=0 AND pprq.process_type_id NOT IN (12,16,17,21, 22) limit 10")) {
    $requestCounter = 0;
    $rowcountAudit = mysqli_num_rows($pdfQueue);

    $setGroupConcateLimit = $mysqli->query("SET SESSION group_concat_max_len = 1200000");

    while ($rowForPdfQueue = $pdfQueue->fetch_assoc()) {
        $requestCounter += 1;
        $pdf_req_info_id = $rowForPdfQueue['id'];
        $pdf_req_app_id = $rowForPdfQueue['app_id'];
        $pdf_req_process_type_id = $rowForPdfQueue['process_type_id'];
        //$signatory = $rowForPdfQueue['signatory'];
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

            $project_root_ip = "http://10.10.100.203";

            $qrcode_query = $mysqli->query("SELECT id, signature_type FROM pdf_signature_qrcode WHERE process_type_id='$pdf_req_process_type_id' AND app_id='$pdf_req_app_id'");
            while ($rowForQcCode = $qrcode_query->fetch_assoc()) {
                $qr_id = $rowForQcCode['id'];
                $qr_signature_type = $rowForQcCode['signature_type'];
                if ($qr_signature_type == 'final') {
                    $data['data']['json']['a_urlimg'] = $project_root_ip . "/cron/signature_api/rest/api.php?function=signature&signature_id=$qr_id";
                } elseif ($qr_signature_type == 'first') {
                    $data['data']['json']['b_urlimg'] = $project_root_ip . "/cron/signature_api/rest/api.php?function=signature&signature_id=$qr_id";

                }
            }

            //$b_urlimg = (isset($data['data']['json']['b_urlimg'])) ? $data['data']['json']['b_urlimg'] : $signatory;

            //if ($signatory != 0) {
                // Live Server IP will used as default
                //$project_root_ip = env('PROJECT_ROOT_IP', 'https://192.168.151.219');
                // signature = pdf_signature_qrcode table
                // user = users table
                //$data['data']['json']['a_urlimg'] = $project_root_ip . "/cron/signature_api/rest/signature?signature_id=$signatory";
                //$data['data']['json']['b_urlimg'] = $project_root_ip . "/cron/signature_api/rest/signature?signature_id=$b_urlimg";
                // $data['data']['json']['a_urlimg'] = "https://192.168.151.219"."/cron/signature_api/rest/user?signature_id=$signatory";
            //}


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
            $encoded_data1 = str_replace(array("\\r\\n","\\n","\\r"), " ", $jsonAllData); // \r\n Carriage Return and Line Feed (Windows), \n Line Feed (Linux, MAC OSX), \r Carriage Return (MAC pre-OSX)
            $encoded_data2 = str_replace("\\", "", $encoded_data1);
            $encoded_data3 = str_replace("\"[", "[", $encoded_data2);
            $encoded_data4 = str_replace("]\"", "]", $encoded_data3);
            //$encoded_data5 = str_replace("'", "â€™", $encoded_data4); // Replace single quotes (') by (â€™) from json data, for footer text quotes issue
            $encoded_data5 = str_replace("'", "’", $encoded_data4); // Replace single quotes (') by Right Single Quotation Mark Unicode Character (’) (U+2019) from json data

            // Single quotation has been replaced but, Double quotation (") can not be possible to replace due to outer json quotation (")
            $final_encoded_data = $encoded_data5;
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