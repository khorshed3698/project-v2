<?php

namespace App\Modules\API\Controllers;

use App\ApiClientMaster;
use App\Http\Controllers\Controller;
use App\Services\ApiResponse;
use App\Services\ApiTokenServiceJwt;
use App\Services\CommonFunction;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class TokenController extends Controller
{
    use ApiResponse;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getToken(Request $request)
    {
        try {
            $client_id = trim($request->get('client_id'));
            $client_secret_key = trim($request->get('client_secret_key'));

            $clientData = $this->checkClient($client_id, $client_secret_key);
            if (!$clientData) {
                return $this->responseJson('Client information is not valid', 'NAID-API-TOKEN', HTTPResponse::HTTP_UNAUTHORIZED);
            }

            // check whether Client IP/ URL is authorized to request
            $isAuthorizedClientUrl = CommonFunction::isAuthorizedClientUrl($clientData);
            if ($isAuthorizedClientUrl['return_type'] === false) {
                return $this->responseJson('Request could not be processed due to unauthorized ip/url: ' . $isAuthorizedClientUrl['return_data'], 'NAID-API-TOKEN', HTTPResponse::HTTP_UNAUTHORIZED);
            }

            // JWT token generation
            $tokenService = new ApiTokenServiceJwt();
            $jwt_token_array = $tokenService->generateToken($clientData);
            if ($jwt_token_array == null) {
                return $this->responseJson('Token generation failed!', 'NAID-API-TOKEN', HTTPResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseJson('Successfully generated token', 'NAID-API-TOKEN', HTTPResponse::HTTP_OK, $jwt_token_array);
        } catch (\Exception $e) {
            return $this->responseJson('Sorry ! An internal error has occurred.', 'NAID-API-TOKEN', HTTPResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $client_id
     * @param $client_secret_key
     *
     * @return mixed
     */
    public function checkClient($client_id, $client_secret_key)
    {
        return ApiClientMaster::where([
            'client_id' => $client_id,
            'client_secret_key' => $client_secret_key,
            'status' => 1
        ])
            ->first();
    }
}
