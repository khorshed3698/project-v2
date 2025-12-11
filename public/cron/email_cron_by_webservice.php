<?php

require_once 'dbCon.php'; /* return name of current default database */
require_once 'mail_setting.php';
require_once "cron_job_audit.php";
/*
 * Sending emails from email_queue
 */
$time_index = 0;
$count_total_record = 0;
$comment = 'Email Sending';

$count = 0;
$mysqli->query("SET NAMES 'utf8'");

if ($get_pending_email = $mysqli->query("SELECT id,app_id,caption,email_to,email_cc,email_content,no_of_try,attachment,email_subject,attachment_certificate_name 
                                                            FROM email_queue WHERE email_status=0 ORDER BY id DESC LIMIT 5")) {

    $count_total_record = mysqli_num_rows($get_pending_email);
    $access_token = '';
    while ($row = $get_pending_email->fetch_assoc()) {

        $email_to = '';
        $id = $row['id'];
        $email_content = $row['email_content'];
        $email_subject = $row['email_subject'];
        $email_to = str_replace("'", "", $row['email_to']);
        $email_cc = str_replace("'", "", $row['email_cc']);
        $email_cc_exp = explode(',', $email_cc);
        $attachment = $row['attachment'];
        $no_of_try = $row['no_of_try'];
        $count++;

        // Check that is it the mail with approval certificate,
        // if is it then need to check the certificate is available or not
        if (!empty($row['attachment_certificate_name'])) {
            $app_id = $row['app_id'];
            $attachment_content_split = explode('.', $row['attachment_certificate_name']);
            if (!empty($attachment_content_split[0]) && !empty($attachment_content_split[1])) {
                $certificate_link_query = $mysqli->query("select $attachment_content_split[1] from $attachment_content_split[0] where id =$app_id and $attachment_content_split[1] != ''");
                $certificate_link = $certificate_link_query->fetch_assoc();
                if (empty($certificate_link)) {
                    echo 'Attachment data not found for this email.';
                    continue;
                }
                $email_content = str_replace('{$attachment}', $certificate_link[$attachment_content_split[1]], $email_content);
            }
        }

        if (empty($access_token)) {
            $token_response = json_decode(getToken($mysqli));
            if ($token_response->responseCode == 0) {
                echo $token_response->msg;
                continue;
            }
            $access_token = $token_response->data;
        }

        $sms_api_url_for_token = env('EMAIL_API_URL_FOR_SEND', 'https://api-k8s.oss.net.bd/api/broker-service/email/send_email');
        $base_email_for_api = env('EMAIL_FROM_FOR_EMAIL_API', 'oss@bida.gov.bd');
        $email_from_for_email_api = ($email_subject) ? $email_subject . ' <' . $base_email_for_api . '>' : $base_email_for_api;

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query(array(
            'sender' => $base_email_for_api,
            'receipant' => $email_to,
            'subject' => $email_subject,
            'bodyText' => '',
            'bodyHtml' => $email_content,
            'cc' => $email_cc
        )));
        curl_setopt($curl_handle, CURLOPT_URL, "$sms_api_url_for_token");
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $access_token",
            "Content-Type: application/x-www-form-urlencoded"
        ));
        $result = curl_exec($curl_handle);
        if (curl_errno($curl_handle)) {
            echo "cURL Error #:" . curl_error($curl_handle);
            $status_update_query = "UPDATE email_queue SET email_status=-1, email_response='" . $result . "' WHERE id=$id";
            $mysqli->query($status_update_query);
            curl_close($curl_handle);
            continue;
        }
        curl_close($curl_handle);
        $decoded_json = json_decode($result, true);

        $email_response_id = 0;
        $email_status = 0; // email has not been sent yet
        $no_of_try = $no_of_try + 1;
        if ($no_of_try > 10) {
            $email_status = -9; // data is invalid, abort sending
            echo "Could not send Email to - <b> $email_to </b><br/>";
        }
        if (isset($decoded_json['status']) and $decoded_json['status'] == 200) {
            $email_status = 1;
            $email_response_id = $decoded_json['data']['id'];
            echo "Successfully sent Email to - <b> $email_to </b><br/>";
        }

        $status_update_query = "UPDATE email_queue SET email_status=$email_status,email_response_id='$email_response_id',email_response='" . $result . "', no_of_try=$no_of_try WHERE id=$id";
        $mysqli->query($status_update_query);
    }
}
/* End of sending emails from email_queue */


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

    $sms_api_url_for_token = env('SMS_API_URL_FOR_TOKEN', 'https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token');
    $sms_client_id = env('SMS_CLIENT_ID', 'bida-client');
    $sms_client_secret = env('SMS_CLIENT_SECRET', '453e84e7-3b5c-4268-ad08-4f7e64bf7615');
    $sms_grant_type = env('SMS_GRANT_TYPE', 'client_credentials');

    try {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $sms_client_id,
            'client_secret' => $sms_client_secret,
            'grant_type' => $sms_grant_type
        )));
        curl_setopt($curl, CURLOPT_URL, "$sms_api_url_for_token");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $data = ['responseCode' => 0, 'msg' => curl_error($curl), 'data' => ''];
            curl_close($curl);
            return json_encode($data);
        }
        curl_close($curl);

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

//  save to cron job audit table for auditing related information.
cronAuditSave($time_index, $count_total_record, $comment, $mysqli);

$mysqli->close();
if ($count == 0) {
    echo '<br/>No email in queue to send! ' . date("j F, Y, g:i a");
}
die();
