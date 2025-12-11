<?php

require_once 'dbCon.php'; /* return name of current default database */

/*
 * Sending SMS from email_queue
 */
$count = 0;
if ($get_pending_sms = $mysqli->query("SELECT id, sms_content, sms_to FROM email_queue
                                WHERE sms_status=0 AND sms_to!='' ORDER BY id DESC  limit 10")) {
    while ($sms_data = $get_pending_sms->fetch_assoc()) {
        $id = $sms_data['id'];
        $sms_body = $sms_data['sms_content'];
        $sms_body = str_replace(" ", "+", $sms_body);
        $mobile_number = $sms_data['sms_to'];
        $mobile_number = str_replace("+88", "", "$mobile_number");

        $access_token_url = "https://idp.oss.net.bd:803/auth/realms/dev/protocol/openid-connect/token";
        $sms_api_url = "https://uat-insightba.oss.net.bd/api/broker-service/sms/send_sms";
        $access_data = [
            "client_id" => "bida-client",
            "client_secret" => "453e84e7-3b5c-4268-ad08-4f7e64bf7615",
            "grant_type" => "client_credentials"
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($access_data));
        curl_setopt($curl, CURLOPT_URL, $access_token_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);

        if(!$result){
            $data = ['responseCode' => 0, 'msg' => 'SMS API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);

        $decoded_json = json_decode($result,true);
        $token = $decoded_json['access_token'];

        $curl = curl_init();
        curl_setopt_array($curl, array(
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
            CURLOPT_POSTFIELDS =>"{\n\t    \"msg\": \"$sms_body\",\n\t    \"destination\": \"$mobile_number\"\n\t\n}\n",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json",
                "Content-Type: text/plain"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
            $status_update_query = "UPDATE email_queue SET sms_status=-1, response='" . $response . "' WHERE id=$id";
            $mysqli->query($status_update_query);
            curl_close($curl);
            exit();
        } else {
            echo 'ok';
            //echo $response;
        }
        curl_close($curl);

        $decodeResponse = json_decode($response);
        $count++;
        if ($decodeResponse->status == 200) {
            $status_update_query = "UPDATE email_queue SET sms_status=1, response='" . $response . "' WHERE id=$id";
        } else {
            echo $status_update_query = "UPDATE email_queue SET sms_status=-1, response='" . $response . "' WHERE id=$id";
        }
        $mysqli->query($status_update_query);
    }
}
/* End of sending SMS from email_queue */

// Close DB connection for Security purpose
$mysqli->close();

if ($count == 0) {
    echo '<br/>No SMS in queue to send! ' . date("j F, Y, g:i a");
}
die();
