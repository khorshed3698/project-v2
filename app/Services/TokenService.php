<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class TokenService
{
    /**
     * Get a Bearer token using dynamic client_id and client_secret.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @return string
     * @throws \Exception
     */
    public function getToken($clientId, $clientSecret)
    {
        $access_data = [
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            "grant_type" => 'client_credentials'
        ];

        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($access_data));
        curl_setopt($curl_handle, CURLOPT_URL, config('app.insightdb_oauth_token_url'));
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
            $data = ['responseCode' => 0, 'msg' => 'API connection failed!', 'data' => ''];
            return json_encode($data);
        }

        $responseBody = json_decode($result, true);

        return $responseBody['access_token'];

//        if (isset($responseBody['access_token'])) {
//            $token = $responseBody['access_token'];
//
//            // Calculate token expiry time
//            $expiresIn = isset($responseBody['expires_in']) ? $responseBody['expires_in'] - 120 : 1500; // Default 28 minutes
//            $expiryTime = \Carbon\Carbon::now()->addSeconds($expiresIn);
//
//            session([
//                'landingPageInsightDbApiToken' => $token,
//                'landingPageInsightDbTokenExpiry' => $expiryTime
//            ]);
//
//            return $token;
//        }

        //throw new \Exception('Failed to retrieve Bearer token');
    }
}
