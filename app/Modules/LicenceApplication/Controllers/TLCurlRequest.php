<?php

namespace App\Modules\LicenceApplication\Controllers;


use App\Modules\LicenceApplication\Models\TradeLicence\TLToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TLCurlRequest
{
    public $tradeLicenceApiUrl = 'http://116.193.218.152:5000/';
    protected $tradeLicenceApiUsername = '01755676720';
    protected $tradeLicenceApiPassword = '123456';


    public function getToken()
    {
        try {
            $token = TLToken::where('status', 1)->first();

            if ($token  != null) {
                $exp_time = strtotime($token->exp_time);
                $current_time = strtotime("+10 minutes", time());
                if ($exp_time > $current_time) {
                    return $token->token;
                }
            }

            return $this->generateToken();

        } catch (\Exception $e) {

            Log::error($e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return null;
        }
    }


    public function generateToken(){

        $url = $this->tradeLicenceApiUrl. 'getToken';
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $postdata = [
            'UserName' => $this->tradeLicenceApiUsername,
            'Password' => $this->tradeLicenceApiPassword,
            'grant_type' => 'password',
        ];

        $response = $this->curlPostRequest($url, $headers, $postdata);

        if ($response['http_code'] == 200) {

            $response = json_decode($response['data']);

            if (isset($response->access_token)) {

                $access_token = 'Bearer ' . $response->access_token;
                $expires = Carbon::now()->addSecond($response->expires_in);

                $tlTokenObj = TLToken::firstOrNew(['status' => 1]);
                $tlTokenObj->token = $access_token;
                $tlTokenObj->exp_time = $expires;
                $tlTokenObj->created_at = date('Y-m-d H:i:s', time());
                $tlTokenObj->save();

                return $tlTokenObj->token;
            } else {
                Log::info('Could not get Token (Trade Licence) for Url: "' . $url . '" and Data : ' . json_encode($postdata));
            }
        }
        return null;
    }

    public function curlPostRequest($url, $headers, $postdata)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
            $result = curl_exec($ch);

            if (!curl_errno($ch)) {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            } else {
                $http_code = 0;
            }
            curl_close($ch);
            return ['http_code' => intval($http_code), 'data' => $result];

        } catch (\Exception $e) {

            echo $e->getMessage();
            Log::error($e->getMessage());
        }
    }

    public function curlGetRequest($requested_url)
    {

        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $requested_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    "Authorization: " .$this->getToken(),
                    "cache-control: no-cache",
                    "Content-Type: application/json"
                ],
            ));

            $curlResult = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                $curlError = curl_error($curl);

                $curlResult = null;
                echo $curlError;
                Log::error($curlError);
            }

            curl_close($curl);

            return $curlResult;

        } catch (\Exception $e) {

            echo $e->getMessage();
            Log::error($e->getMessage());
        }
    }

}