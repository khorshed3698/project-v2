<?php
/**
 * Created by PhpStorm.
 * User: Sadik
 * Date: 9/4/2018
 * Time: 4:22 PM
 */

namespace App\Modules\API\Controllers\Traits;


trait Notification
{
    /**
     * Api send Notification
     * @param $tokenData
     * @param $title
     * @param $body
     * @param $notificationShow
     */
    public function apiSendNotification($tokenData, $title = "", $body = "", $notificationShow = true)
    {
        $apiAccessKey = env('API_ACCESS_KEY');

        $data = array
        (
            'notificationTitle' => $title,
            'notificationBody' => $body,
            'notificationShow' => $notificationShow,
            'data' => 'asdf'
        );

        $android = array
        (
            'ttl' => '86400s'
        );

        $webpush = array
        (
            'headers' => '{
             "TTL":"86400"
           }'
        );

        $fields = array
        (
            'to' => $tokenData,
            'data' => $data,
            'android' => $android,
            'webpush' => $webpush
        );


        $headers = array
        (
            'Authorization: key=' . $apiAccessKey,
            'Content-Type: application/json'
        );


        #Send Reponse To FireBase Server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }

}