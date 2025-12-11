<?php

require_once 'dbCon.php'; /* return name of current default database */
require_once "cron_job_audit.php";

/*
 * Sending SMS from email_queue
 */
$time_index = 0;
$comment = 'SMS Sending';
$count_total_record = 0;

$mysqli->query("SET NAMES 'utf8'");
if ($get_pending_sms = $mysqli->query("SELECT id, sms_content, sms_to, no_of_try FROM email_queue
                                WHERE sms_status=0 AND sms_to!='' ORDER BY id DESC  limit 5")) {

    $access_token = '';
    $count_total_record = mysqli_num_rows($get_pending_sms);

    while ($sms_data = $get_pending_sms->fetch_assoc()) {
        $id = $sms_data['id'];
        $sms_body = $sms_data['sms_content'];
        //$sms_body = str_replace(" ", "+", $sms_body);
        $mobile_number = $sms_data['sms_to'];
        $mobile_number = str_replace("+88", "", "$mobile_number");
        $no_of_try = $sms_data['no_of_try'];

        // Get Token from SMS API Portal
        if (empty($access_token)) {
            $token_response = json_decode(getToken($mysqli));
            if ($token_response->responseCode == 0) {
                echo $token_response->msg;
                continue;
            }
            $access_token = $token_response->data;
        }
        // End of Get Token from SMS API Portal

        // Send SMS via API Portal
        $sms_api_url = env('SMS_API_URL_FOR_SEND', 'https://api-k8s.oss.net.bd/api/broker-service/sms/send_sms');
        $curl_handle = curl_init();
        curl_setopt_array($curl_handle, array(
            CURLOPT_URL => "$sms_api_url",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => config('app.curlopt_ssl_verifyhost'),
            CURLOPT_SSL_VERIFYPEER => config('app.curlopt_ssl_verifypeer'),
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n\t    \"msg\": \"$sms_body\",\n\t    \"destination\": \"$mobile_number\"\n\t\n}\n",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $access_token",
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));
        $response = curl_exec($curl_handle);

        if (curl_errno($curl_handle)) {
            echo "cURL Error #:" . curl_error($curl_handle);
            $status_update_query = "UPDATE email_queue SET sms_status=-1, sms_response='" . $response . "' WHERE id=$id";
            $mysqli->query($status_update_query);
            curl_close($curl_handle);
            continue;
        }
        curl_close($curl_handle);
        // End of Send SMS via API Portal

        $decodeResponse = json_decode($response);

        $sms_response_id = 0;
        $no_of_try = $no_of_try + 1;
        if ($no_of_try > 10) {
            $sms_status = -9; // data is invalid, abort sending
        }

        if ($decodeResponse->status == 200) {
            $sms_status = 1;
            $sms_response_id = $decodeResponse->data->id;
            echo "Successfully sent SMS to - <b> $mobile_number </b><br/>";
        } else {
            $sms_status = -1;
            echo "Could not send SMS to - <b> $mobile_number </b><br/>";
        }
        $status_update_query = "UPDATE email_queue SET sms_status=$sms_status,sms_response_id='$sms_response_id', sms_response='" . $response . "', no_of_try=$no_of_try WHERE id=$id";
        $mysqli->query($status_update_query);
    }
}
/* End of sending SMS from email_queue */


/**
 * @return bool|string
 */
function getToken($mysqli)
{
    $api_token_query = $mysqli->query("select * from configuration where caption ='email_sms_api_token'");
    $api_token = $api_token_query->fetch_assoc();
    if (isset($api_token['value2']) && $api_token['value2'] > time()) {
        $data = [
            'responseCode' => 1,
            'data' => $api_token['value']
        ];

        return json_encode($data);
    }

    $access_token_url = env('SMS_API_URL_FOR_TOKEN', 'https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token');
    $access_data = [
        "client_id" => env('SMS_CLIENT_ID', 'bida-client'),
        "client_secret" => env('SMS_CLIENT_SECRET', '453e84e7-3b5c-4268-ad08-4f7e64bf7615'),
        "grant_type" => env('SMS_GRANT_TYPE', 'client_credentials')
    ];
    try {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($access_data));
        curl_setopt($curl_handle, CURLOPT_URL, $access_token_url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl_handle);
        if (curl_errno($curl_handle)) {
            $data = ['responseCode' => 0, 'msg' => curl_error($curl_handle), 'data' => ''];
            curl_close($curl_handle);
            return json_encode($data);
        }
        curl_close($curl_handle);

        if (!$result || !property_exists(json_decode($result), 'access_token')) {
            $data = ['responseCode' => 0, 'msg' => 'SMS API connection failed!', 'data' => ''];
            return json_encode($data);
        }

        $decoded_json = json_decode($result, true);
        $data = [
            'responseCode' => 1,
            'data' => $decoded_json['access_token'],
        ];

        // updating token
        $token = $decoded_json['access_token'];
        $token_expire = (time() + $decoded_json['expires_in']) - 60;
        $token_record_query = $mysqli->query("select exists(select * from configuration where caption='email_sms_api_token') as token_exists");
        $token_record = $token_record_query->fetch_assoc();
        if ($token_record['token_exists']) {
            $token_update_query = "UPDATE configuration SET value='$token',value2='$token_expire' WHERE caption='email_sms_api_token'";
        } else {
            $token_update_query = "INSERT INTO configuration (caption, value, value2) VALUES ('email_sms_api_token', '$token', '$token_expire')";
        }
        $mysqli->query($token_update_query);

    } catch (Exception $e) {
        $data = ['responseCode' => 0, 'msg' => $e->getMessage() . $e->getFile() . $e->getLine(), 'data' => ''];
    }

    return json_encode($data);
}

/**
 * save to cron job audit table for auditing related information.
 */
cronAuditSave($time_index, $count_total_record, $comment, $mysqli);

// Close DB connection for Security purpose
$mysqli->close();

if ($count_total_record == 0) {
    echo '<br/>No SMS in queue to send! ' . date("j F, Y, g:i a");
}
die();