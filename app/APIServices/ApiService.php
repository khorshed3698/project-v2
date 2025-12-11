<?php

namespace App\APIServices;

use App\ApiClientMaster;
use App\ClientRequestResponse;
use App\Modules\API\Models\ApiResponse;
use App\Modules\API\Models\ClientOauthToken;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class ApiService extends AbstractApiService
{
    protected $config = [];

    public function __construct()
    {
        $this->config = config('services');
    }

    public function getToken(array $clientData)
    {
        $apache_request_headers = apache_request_headers();
        $token = str_ireplace("bearer ", "", isset($apache_request_headers['Authorization']) ? $apache_request_headers['Authorization'] : '');

        if (!empty($clientData['client_id']) && !empty($clientData['client_secret_key'])){
            $clientData = ClientOauthToken::leftJoin('client_master', 'client_master.id', '=', 'client_oauth_token.client_master_id')
                            ->where(['client_oauth_token.oauth_token' => $token, 'client_id' => $clientData['client_id'], 'client_secret_key' => $clientData['client_secret_key']])
                            ->first([
                                'client_master.*',
                                'client_oauth_token.id as client_oauth_token_id',
                                'client_oauth_token.oauth_token_expire_at',
                            ]);

            if ($clientData){

                DB::beginTransaction();
                $apiInfo = new ApiResponse();
                $apiInfo->body = json_encode($request->all());
                $apiInfo->api_id = $clientData->id;
                $apiInfo->external_request_id = $request_id;
                $apiInfo->endpoint = $request->fullUrl();
                $apiInfo->method = $request->method();
                $apiInfo->created_at = $request_at;
                $apiInfo->save();
                DB::commit();

                $allowedIP = explode(',', $clientData->allowed_ips);
                if (!in_array($this->getVisitorRealIP(), $allowedIP)){
                    $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                    $message = "Request from " . $this->getVisitorRealIP() . " unauthorized. IP not allowed";
                    return $this->generateResponse($status_code, $message);
                }else{
                    return $this->generateToken($clientData, $apiInfo->id, $client_encryption_key);
                }
            }else{
                DB::rollback();
                $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                $message = "Client information not valid";
                return $this->generateResponse($status_code, $message);
            }
        }else{
            $status_code = HTTPResponse::HTTP_BAD_REQUEST;
            $message = "Bad request input";
            return $this->generateResponse($status_code, $message);
        }

    }

    private function getVisitorRealIP()
    {
        // Get real visitor IP behind CloudFlare network
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

}