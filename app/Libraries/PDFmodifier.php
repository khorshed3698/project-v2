<?php


namespace App\Libraries;

use Illuminate\Support\Facades\Log;


class PDFmodifier
{

    private $server_address;
    private $token_server;

    public function __construct()
    {
        $this->server_address = 'https://pdf-modifier.oss.net.bd/get-url';
        $this->token_server = 'https://pdf-modifier.oss.net.bd/get-token';
    }


    public function getAuthToken()
    {
        try {
            $username = config('app.pdf_modifier_username');
            $password = config('app.pdf_modifier_password');
            $clientId = config('app.pdf_modifier_client_id');
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->token_server,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "username=$username&password=$password&client_id=$clientId",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            ));
            
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $data = ['status' => "error", 'message' => 'Curl error to get auth token', 'data' => ''];
                curl_close($curl);
                return json_encode($data);
            }
            curl_close($curl);

            $decoded_json = json_decode($response, true);
            if (!isset($decoded_json['data']['token'])){
                return json_encode([
                    'status' => "error",
                    'data' => '',
                    'message' => 'Auth token not found! Please try again'
                ]);
            }
            
            return json_encode([
                'status' => "success",
                'data' => $decoded_json['data']['token'],
                'message' => 'Auth token generated'
            ]);

        } catch (\Exception $e) {
            Log::error("PDFmodifier getAuthToken ({$e->getFile()} => {$e->getLine()}): {$e->getMessage()}");
            return json_encode(['status' => "error", 'message' => 'Auth token not found', 'data' => '']);
        }
    }

    public function getUrl($pdf, $auth_token)
    {
     
        try {
            $callback_url = url('process/pdf-modifier-callback');
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $this->server_address,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'callback_url' => $callback_url,
                'pdf'=> $pdf,
            ),
            CURLOPT_HTTPHEADER => array(
                "APIAuthorization: bearer $auth_token"
            ),
            ));

            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                $data = ['status' => "error", 'message' => 'curl error to get url', 'data' => ''];
                curl_close($curl);
                return json_encode($data);
            }
            curl_close($curl);

            $decoded_json = json_decode($response, true);
            if(!isset($decoded_json['data'])){
                return json_encode([
                    'status' => "error",
                    'data' => '',
                    'message' => 'PDF modifier url not found! Please try again'
                ]);
            }

            return json_encode([
                'status' => "success",
                'data' => $decoded_json['data'],
                'message' => 'PDF modifier url generated'
            ]);

        } catch (\Exception $e) {
            Log::error("PDFmodifier getUrl ({$e->getFile()} => {$e->getLine()}): {$e->getMessage()}");
            return json_encode(['status' => "error", 'message' => 'PDF modifier url not found', 'data' => '']);
        }
    }


    public function initiateUrl($pdf_path)
    {
        try {
            // $pdf_file = new \CURLFILE('/home/harun/Documents/BIDA/100KB.pdf');
            $pdf = new \CURLFILE(public_path($pdf_path));

            // Token API
            $auth_token = $this->getAuthToken();

            $auth_token_data = json_decode($auth_token);
            if ($auth_token_data->status == "error") {
                return $auth_token;
            }

            // PDF Modifier Url generation API
            $pdf_modifier_url = $this->getUrl($pdf, $auth_token_data->data);

            return $pdf_modifier_url;

        } catch (\Exception $e) {
            Log::error('PDF Modifier Initiate : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return json_encode([
                'status' => "error",
                'data' => '',
                'message' => 'PDF modifier initiate not found! Please try again'
            ]);
        }
    }


}