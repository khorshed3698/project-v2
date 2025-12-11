<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class MLVerification
{

    const VERSION = '1.0';
    private $ml_suggest_remarks;
    private $ml_auto_complete;
    private $ml_token_server;
    private $ml_client_id;
    private $ml_client_secret;
    private $grant_type;

    public function __construct()
    {
        $this->ml_token_server = config('app.ML_TOKEN_SERVER');
        $this->ml_client_id = config('app.ML_CLIENT_ID');
        $this->ml_client_secret = config('app.ML_CLIENT_SECRET');
        $this->grant_type = config('app.NID_GRANT_TYPE');
        $this->ml_suggest_remarks = config('app.ML_SUGGEST_REMARKS');
        $this->ml_auto_complete = config('app.ML_AUTO_COMPLETE');
    }

    public function getAuthToken()
    {
        try {
            if (Session::has('access_token')) {
                return Session::get('access_token');
            }
            $access_data = [
                "client_id" => $this->ml_client_id,
                "client_secret" => $this->ml_client_secret,
                "grant_type" => $this->grant_type
            ];
            $curl_handle = curl_init();
            curl_setopt($curl_handle, CURLOPT_POST, 1);
            curl_setopt($curl_handle, CURLOPT_POSTFIELDS, http_build_query($access_data));
            curl_setopt($curl_handle, CURLOPT_URL, $this->ml_token_server);
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
            Session::put('access_token', $decoded_json['access_token']);
            return Session::get('access_token');
        } catch (\Exception $e) {
            return false;
        }
    }
    public function getRemarks($app_ref_id, $process_type_id, $status_id)
    {
        if (empty($app_ref_id) || empty($process_type_id) || empty($status_id)) {
            return $this->returnResponse('error', 400, [], 'Given data is not valid. Please make request with valid data');
        }

        try {
            $queryParams = http_build_query([
                "app_ref_id" => $app_ref_id,
                "process_type_id" => $process_type_id,
                "status_id" => $status_id
            ]);

            return $this->curlResponse($this->ml_suggest_remarks, $queryParams, 'getRemarks');

            
        } catch (\Exception $e) {
            Log::error("Exception in getRemarks ({$e->getFile()} => {$e->getLine()}): {$e->getMessage()}");
            return $this->returnResponse('error', $e->getCode(), [], $e->getMessage());
        }
    }

    public function getAutoCompleteSuggestion($input_text)
    {
        if (empty($input_text)) {
            return $this->returnResponse('error', 400, [], 'Given data is not valid. Please make request with valid data');
        }

        try {
            $queryParams = http_build_query([
                "input_text" => $input_text,
            ]);

            return $this->curlResponse($this->ml_auto_complete, $queryParams, 'getAutoCompleteSuggestion');

        } catch (\Exception $e) {
            Log::error("Exception in getAutoCompleteSuggestion ({$e->getFile()} => {$e->getLine()}): {$e->getMessage()}");
            return $this->returnResponse('error', $e->getCode(), [], $e->getMessage());
        }
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
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

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

        if ($decoded_output['responseCode'] !== 200) {
            return $this->returnResponse('error', $decoded_output['responseCode'], [], $decoded_output['message']);
        }

        return $this->returnResponse('success', 200, $decoded_output['data'], 'Data fetched successfully');
    }
}
