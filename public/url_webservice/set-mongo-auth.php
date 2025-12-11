<?php
/**
 * Created by PhpStorm.
 * User: shahin
 * Date: 4/4/18
 * Time: 2:58 PM
 */

if (env('mongo_audit_log')){
    $url = ''.env('web_service_url').'?param={"mongoDBRequest":{"requestData":{"reg_key":" '. env('web_service_url_reg_key').' ","clientId":"BEZA","user_id":" '.Auth::user()->id.'"},"requestType":"LOGIN_REQUEST","version":"1.0"}}';


    $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $url);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 150);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo curl_error($ch);
        echo "\n<br />";
        $response = '';
        dd(111);
    } else {
        curl_close($ch);
    }

    if (!is_string($response) || !strlen($response)) {
        echo "Failed to get contents.";
        $response = '';
    }
    $dataResponse = json_decode($response);

    if($dataResponse != null && $dataResponse->mongoDBRequest->responseStatus->responseCode == 1){

        Session()->put('MongoAuthToken', $dataResponse->mongoDBRequest->responseStatus->responseData->auth_token);
    }


//$response = file_get_contents($url);

}
