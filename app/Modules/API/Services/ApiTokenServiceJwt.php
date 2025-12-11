<?php

namespace App\Modules\API\Services;

use App\Modules\API\Models\ApiClientMaster;
use App\Modules\API\Models\ClientOauthToken;
use Firebase\JWT\JWT;

class ApiTokenServiceJwt
{
    /**
     * @param $clientData
     * @return array|null
     */
    public function generateToken($clientData)
    {
        try {
            $token_encode_data = [
                'client_id' => $clientData->client_id,
                'client_secret_key' => $clientData->client_secret_key,
                "exp" => time() + 3600
            ];

            $jwt_token = JWT::encode($token_encode_data, $clientData->encryption_key, 'HS256');

            $token_response['token_type'] = 'bearer';
            $token_response['token'] = $jwt_token;
            $token_response['expire_in'] = date("Y-m-d H:i:s", strtotime("+1 hours"));
            $this->storeToken($clientData->id, $token_response['token'], $token_response['expire_in']);

            return $token_response;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param $client_master_id
     * @param $token
     * @param $token_expire_in
     */
    private function storeToken($client_master_id, $token, $token_expire_in)
    {
        $client_oauth_token = new ClientOauthToken();
        $client_oauth_token->client_master_id = $client_master_id;
        $client_oauth_token->oauth_token = $token;
        $client_oauth_token->oauth_token_expire_at = $token_expire_in;
        $client_oauth_token->ip_address = getVisitorRealIP();
        $client_oauth_token->user_agent = getVisitorUserAGent();
        $client_oauth_token->save();
    }

    /**
     * @param $token
     * @param $encryption_key
     * @return bool
     */
    public function checkTokenValidity($token, $encryption_key)
    {
        try {

            /**
             * if there have an expire time in your payload while encoding JWT,
             * the JWT decode function() will automatically check the expire time.
             * No need to do extra checking.
             */
            $decoded_token = JWT::decode($token, $encryption_key, ['HS256']);
            $client_id = $decoded_token->client_id;
            $client_secret_key = $decoded_token->client_secret_key;

            // Check valid credential
            $valid_client_for_token = ApiClientMaster::where([
                'client_id' => $client_id,
                'client_secret_key' => $client_secret_key
            ])->count();
            if ($valid_client_for_token == 0) {
                return false;
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
