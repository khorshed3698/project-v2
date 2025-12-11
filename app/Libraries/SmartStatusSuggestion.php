<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SmartStatusSuggestion
{
    const VERSION = '1.0';
    private $token_server;
    private $smart_status;
    private $grant_type;
    private $clientId;
    private $clientSecret;
    private $mlBaseUrl;

    public function __construct()
    {
        $this->token_server = config('app.insightdb_oauth_token_url');
        $this->smart_status = config('app.ml_suggest_staus');
        $this->clientId = config('app.insightdb_oauth_client_id');
        $this->clientSecret = config('app.insightdb_oauth_client_secret');
        $this->grant_type = config('app.ml_grant_type');
        $this->mlBaseUrl = config('app.insightdb_api_base_url');
    }

    public function getAuthToken()
    {
        try {
            $tokenCacheKey = 'insightdb_api_token';
            if (Cache::has($tokenCacheKey)) {
                return Cache::get($tokenCacheKey);
            } else {
                $access_data = [
                    "client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret,
                    "grant_type" => $this->grant_type
                ];
                $curl_handle = curl_init();
                curl_setopt($curl_handle, CURLOPT_POST, 1);
                curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($access_data));
                curl_setopt($curl_handle, CURLOPT_URL, $this->token_server);
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

                $decoded_json = json_decode($result, true);
                Cache::put($tokenCacheKey, $decoded_json['access_token'], Carbon::now()->addSeconds(270));
                return $decoded_json['access_token'];
            }
        } catch (\Exception $e) {
            Log::error('SmartStatusSuggestion@getAuthToken: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return false;
        }
    }

    public function getStatusSuggestion($remarks)
    {
        $queryParams = http_build_query([
            "remarks" => $remarks
        ]);
        $urlWithQuery = "{$this->mlBaseUrl}{$this->smart_status}";
        return $this->curlResponse($urlWithQuery, $queryParams, 'getStatusSuggestion');
    }

    private function returnResponse($status, $statusCode, array $data = [], $message = 'Sorry, Something went wrong!')
    {
        return [
            'status' => $status,
            'responseCode' => intval($statusCode),
            'data' => $data,
            'message' => $message
        ];
    }

    private function curlResponse($api_url, $queryParams, $methodName)
    {
        $ml_auth_token = $this->getAuthToken();
        $url = $api_url . '?' . $queryParams;

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => config('app.curlopt_ssl_verifyhost'),
            CURLOPT_SSL_VERIFYPEER => config('app.curlopt_ssl_verifypeer'),
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $ml_auth_token",
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error("CURL Error in $methodName : " . curl_error($curl));
            return $this->returnResponse('error', 500, [], 'CURL Error' . curl_error($curl));
        }

        if ($response === false) {
            Log::error("CURL Error in $methodName : " . curl_error($curl));
            return $this->returnResponse('error', 500, [], 'CURL request failed');
        }

        curl_close($curl);

        $decoded_output = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON Decode Error in getRemarks() : ' . json_last_error_msg());
            return $this->returnResponse('error', 500, [], 'Invalid JSON response');
        }

        if ((isset($decoded_output['responseCode']) && $decoded_output['responseCode'] !== 200 )|| (isset($decoded_output['code']) && $decoded_output['code'] !== 200)) {
            $code = 500;
            if (isset($decoded_output['responseCode'])) {
                $code = $decoded_output['responseCode'];
            } elseif (isset($decoded_output['code'])) {
                $code = $decoded_output['code'];
            }
            Log::error('Something went wrong : ' . $decoded_output['message']);
            return $this->returnResponse('error',  $code, [], $decoded_output['message']);
        }

        return $this->returnResponse('success', 200, $decoded_output['data'], 'Data fetched successfully');
    }
}
