<?php

namespace App\Modules\API\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;




use App\Modules\API\Controllers\Traits\Notification;

use App\Http\Controllers\Controller;
use App\Modules\API\Controllers\Traits\ApiRequestManager;
//use HttpResponse;

class IrmsApiController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImeiData(Request $request)
    {
        $message = '';
        $response_type = 'NAID-IMEI-DATA';
        $status_code = '';
        $data = '';

        try {
            $api_request_response = $request->get('apiRequestResponse');
            $search_type = $api_request_response->value1;
            $search_key = $api_request_response->value2;
            $request_id = $api_request_response->request_id;

            $checkRequestIdValidity = CommonFunction::checkRequestIdValidity($request_id);

            if ($checkRequestIdValidity != true) {
                $status_code = HTTPResponse::HTTP_UNPROCESSABLE_ENTITY;
                $message = 'Invalid request id';
            }else{
                if ($search_type == 'imei' || $search_type == 'IMEI') {
                    $numLength = strlen($search_key);
                    if ($numLength == 15) {
                        $data = ImeiData::where('imei1', $search_key)
                            ->orWhere('imei2', $search_key)
                            ->orWhere('imei3', $search_key)
                            ->orWhere('imei4', $search_key)
                            ->orderBy('id', 'desc')
                            ->first([
                                'brand', 'model_name', 'color', 'device_type',
                                'country_of_origin', 'manufatuter', 'no_of_sim',
                                'radio_interface', 'os', 'marketing_period', 'tac1',
                                'tac2', 'tac3', 'tac4', 'imei1', 'imei2', 'imei3', 'imei4', 'serial_no',
                            ]);
                        if ($data) {
                            $status_code = HTTPResponse::HTTP_OK;
                            $message = 'IMEI data sent successfully';
                        } else {
                            $status_code = HTTPResponse::HTTP_NOT_FOUND;
                            $message = 'IMEI data not found';
                        }
                    } else {
                        $status_code = HTTPResponse::HTTP_UNPROCESSABLE_ENTITY;
                        $message = 'IMEI number must be 15 digit';
                    }

                } elseif ($search_type == 'tac' || $search_type == 'TAC') {
                    $numLength = strlen($search_key);
                    if ($numLength == 8) {
                        $data = ImeiData::where('tac1', $search_key)
                            ->orWhere('tac2', $search_key)
                            ->orWhere('tac3', $search_key)
                            ->orWhere('tac4', $search_key)
                            ->orderBy('id', 'desc')
                            ->first([
                                'brand', 'model_name', 'color', 'device_type',
                                'country_of_origin', 'manufatuter', 'no_of_sim',
                                'radio_interface', 'os', 'marketing_period', 'tac1',
                                'tac2', 'tac3', 'tac4'
                            ]);
                        if ($data) {
                            $status_code = HTTPResponse::HTTP_OK;
                            $message = 'TAC data sent successfully';
                        } else {
                            $status_code = HTTPResponse::HTTP_NOT_FOUND;
                            $message = 'TAC data not found';
                        }
                    } else {
                        $status_code = HTTPResponse::HTTP_UNPROCESSABLE_ENTITY;
                        $message = 'TAC number must be 8 digit';
                    }
                } else {
                    $status_code = HTTPResponse::HTTP_BAD_REQUEST;
                    $message = 'Search type is not valid; Please use tac or imei';
                }
            }

            $api_request_response->response_at = Carbon::now()->format('Y-m-d H:i:s.u');
            $api_request_response->response_id = str_replace(' ', '', $api_request_response->response_at) . '-' . $api_request_response->id . '-' . $search_type;
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
