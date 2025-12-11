<?php


namespace App\APIServices;


use App\ApiClientMaster;
use App\Libraries\Encryption;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class AbstractApiService implements InterfaceApiService
{

    protected $token_data = [];
    protected $response_data = [];

    /**
     * @param array $clientData
     * @param int $api_info_id
     * @param string $client_encryption_key
     * @return JsonResponse
     */
    public function generateToken($clientData, int $api_info_id, string $client_encryption_key)
    {
        try {

            $this->setParams($clientData, $api_info_id);

            $jwt_token = JWT::encode($this->token_data, $client_encryption_key, 'HS256');

            $token_response['token_type'] = 'bearer';
            $token_response['token'] = $jwt_token;
            $token_response['expire_on'] = date("Y-m-d H:i:s", strtotime("+" . $this->config['api']['token_expiry_time_in_sec'] . " sec"));

            $status_code = HTTPResponse::HTTP_OK;
            $message = "Token Generated Successfully";
            return $this->generateResponse($status_code, $message, $token_response);

        }catch (\Exception $e) {
            $status_code = HTTPResponse::HTTP_INTERNAL_SERVER_ERROR;
            $message = "Internal server error";
            return $this->generateResponse($status_code, $message);
        }

    }

    private function setParams($clientData, $apiInfoId)
    {
        $this->token_data = [
            'api_info_id' => Encryption::encodeId($apiInfoId),
            'client_id' => Encryption::encode($clientData->client_id),
            'client_secret_key' => Encryption::encode($clientData->client_secret_key),
            "exp" => $this->setExpTime()
        ];
    }

    private function setExpTime()
    {
        return time() + (int)$this->config['api']['token_expiry_time_in_sec'];
    }

    public function validateToken(array $apache_request_headers, string $encryption_key)
    {
        try {
            if(!$apache_request_headers){
                $status_code = HTTPResponse::HTTP_BAD_REQUEST;
                $message = "Token not found";

                return $this->generateResponse($status_code, $message);
            }

            $bearer_token = $apache_request_headers['Authorization'] ?? '';
            $token = str_ireplace("bearer ", "", $bearer_token);

            if($token){
                $decoded_token = JWT::decode($token, new Key($encryption_key, 'HS256'));
                $api_info_id = Encryption::decodeId($decoded_token->api_info_id);
                $client_id = Encryption::decode($decoded_token->client_id);
                $client_secret = Encryption::decode($decoded_token->client_secret_key);
                $client = ApiClientMaster::where('client_id', $client_id)
                    ->where('client_secret_key', $client_secret)
                    ->where('status', 1)
                    ->exists();

                if (!$client){
                    return [
                        'status_code'=>HTTPResponse::HTTP_UNAUTHORIZED,
                        'message'=>"Request unauthorized"
                    ];
                }

                return [
                    'check_client'=>$client,
                    'api_info_id'=>$api_info_id
                ];
            }else{
                return [
                    'status_code'=>HTTPResponse::HTTP_BAD_REQUEST,
                    'message'=>"Token not found"
                ];
            }
        }catch (\Exception $e) {
            return [
                'status_code'=>HTTPResponse::HTTP_NOT_ACCEPTABLE,
                'message'=>$e->getMessage()
            ];
        }

    }

    protected function generateResponse($status_code, $message = '', $data = []): JsonResponse
    {

        if ($status_code == 200){
            $this->response_data = [
                'responseCode' => $status_code,
                'responseData' => $data,
                'message' => $message
            ];
        }else{
            $this->response_data = [
                'responseCode' => $status_code,
                'message' => $message
            ];
        }


        return response()->json($this->response_data);
    }

}