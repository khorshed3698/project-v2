<?php

namespace App\Http\Middleware;



use Closure;
use Carbon\Carbon;
use App\Libraries\CommonFunction;
use App\Modules\API\Models\ClientOauthToken;
use App\Modules\API\Models\ClientRequestResponse;
use App\Modules\API\Services\ApiTokenServiceJwt;

use App\ImeiData;

use App\Http\Controllers\NtmcApiController;

//use HttpResponse;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Illuminate\Http\Request;

class RequestValidityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $message = '';
        $response_type = 'NAID-IMEI-DATA';
        $status_code = '';
        $data = '';

        $apache_request_headers = apache_request_headers();
        $bearer_token = isset($apache_request_headers['Authorization']) ? $apache_request_headers['Authorization'] : '';
        $token = str_ireplace("bearer ", "", $bearer_token);

        $request_data = [
            'ip' => CommonFunction::getVisitorRealIP(),
            'client_id' => $request->get('client_id'),
            'client_secret_key' => $request->get('client_secret_key'),
            'token' => $token,
            'search_type' => $request->get('search_type'),
            'search_key' => $request->get('search_key'),
            'request_id' => $request->get('request_id'),
        ];

        try {
            $api_request_response = new ClientRequestResponse();
            $api_request_response->request_json = json_encode($request_data);
            $api_request_response->request_id = preg_replace('/\s+/', '', $request->get('request_id'));
            $api_request_response->value1 = $request->get('search_type');
            $api_request_response->value2 = $request->get('search_key');
            $api_request_response->request_at = Carbon::now()->format('Y-m-d H:i:s.u');

            // check whether bearer token exists in the request header
            if (!empty($token)) {
                $clientData = ClientOauthToken::leftJoin('client_master', 'client_master.id', '=', 'client_oauth_token.client_master_id')
                    ->where('client_oauth_token.oauth_token', $token)
                    ->orderBy('client_oauth_token.id', 'desc')
                    ->first([
                        'client_master.*',
                        'client_oauth_token.id as client_oauth_token_id',
                        'client_oauth_token.oauth_token_expire_at',
                    ]);
                $api_request_response->client_master_id = $clientData->id;
                $api_request_response->client_oauth_token_id = $clientData->client_oauth_token_id;
                $api_request_response->save();

                $checkAlphaNumeric = ctype_alnum($request->get('client_secret_key'));

                if ($checkAlphaNumeric === true) {

                    if ($clientData && ($clientData->client_id == $request->get('client_id') && $clientData->client_secret_key == $request->get('client_secret_key'))) {

                        if (date('Y-m-d H:i:s') < $clientData->oauth_token_expire_at) {

                            // Check token validity
                            $tokenService = new ApiTokenServiceJwt();
                            $tokenValidate = $tokenService->checkTokenValidity($token, $clientData->encryption_key);
                            if ($tokenValidate) {

                                // check whether Client IP/ URL is authorized to request
                                $isAuthorizedClientUrl = CommonFunction::isAuthorizedClientUrl($clientData);
                                if ($isAuthorizedClientUrl['return_type'] === true) {
                                    $request->merge(array("apiRequestResponse" => $api_request_response));
                                    return $next($request);

                                } else {
                                    $message = 'Request could not be processed due to unauthorized ip/url: ' . $isAuthorizedClientUrl['return_data'];
                                    $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                                }
                            } else {
                                $message = 'Token is not valid';
                                $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                            }
                        } else {
                            $message = 'Client Token has been expired';
                            $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                        }
                    } else {
                        $message = 'Client information is not valid';
                        $status_code = HTTPResponse::HTTP_UNAUTHORIZED;
                    }
                } else {
                    $message = 'Client secret key must be alpha-numeric';
                    $status_code = HTTPResponse::HTTP_BAD_REQUEST;
                }
            } else {
                $message = 'Token not found in request header';
                $status_code = HTTPResponse::HTTP_BAD_REQUEST;
            }

            $api_request_response->response_at = Carbon::now()->format('Y-m-d H:i:s.u');
            $api_request_response->response_id = str_replace(' ', '', $api_request_response->response_at) . '-' . $api_request_response->id . '-' . $request->get('search_key');
            $responseEncode = CommonFunction::imeiTacDataResponse($message, $response_type, $status_code, $data, $api_request_response->response_id, $api_request_response->response_at);
            $api_request_response->response_json = json_encode($responseEncode, JSON_UNESCAPED_UNICODE);
            $api_request_response->save();

            return response()->json($responseEncode);

        } catch (\Exception $e) {
            $message = 'Sorry ! An internal error has occurred';
            $status_code = HTTPResponse::HTTP_INTERNAL_SERVER_ERROR;
            $responseEncode = CommonFunction::imeiTacDataResponse($message, $response_type, $status_code, $data, $api_request_response->response_id, $api_request_response->response_at);

            return response()->json($responseEncode);
        }
    }
}
