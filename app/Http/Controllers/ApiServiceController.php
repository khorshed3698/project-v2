<?php

namespace App\Http\Controllers;

use App\APIServices\ApiService;
use App\ClientRequestResponse;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\WorkPermit\Models\WPMaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Session;


class ApiServiceController extends Controller
{

    public function generateToken(Request $request)
    {
        try {
            if(empty($request)){
                $response_data = [
                    'responseCode' => 405,
                    'message' => "Request method not allowed"
                ];

                return response()->json($response_data);
            }

            $clientData = [
                'client_id' => $request->client_id,
                'client_secret_key' => $request->client_secret_key,
                'client_encryption_key' => config('services.api.encryption_key')
            ];

            $token = new ApiService();
            return $token->getToken($clientData);

        }catch (\Exception $e) {
            $response_data = [
                'responseCode' => 500,
                'message' => "Internal Server Error"
            ];

            return response()->json($response_data);
        }


    }

    public function updateStatus(Request $request)
    {
        try {
            $apache_request_headers = apache_request_headers();
            $token = new ApiService();
            $clientCheck = $token->validateToken($apache_request_headers, config('services.api.encryption_key'));

            if (!isset($clientCheck['check_client'])){
                return response()->json($clientCheck);
            }

            $this->response_data = [
                'responseCode' => 200,
                'message' => "Success!"
            ];

            DB::beginTransaction();
            if($request){
                if ($request->status_id == 14) {
                    WPMaster::where('wp_tracking_no', $request->app_tracking_id)
                        ->update([
                            'moha_attestation_letter' => $request->certificate
                        ]);
                    ProcessList::where('tracking_no', $request->app_tracking_id)->update(['status_id' => 102]); // Send to SB/NSI

                    DB::commit();
                } else if ($request->status_id == 15){
                    WPMaster::where('tracking_no', $request->app_tracking_id)
                        ->update([
                            'moha_forwarding_letter' => $request->fl_certificate
                        ]);
                    ProcessList::where('tracking_no', $request->app_tracking_id)->update(['status_id' => 103]); // Posting

                    DB::commit();
                }


                $apiInfo = ClientRequestResponse::where('id', $clientCheck['api_info_id'])->first();
                $apiInfo->guest_request_id = $request->request_id;
                $apiInfo->purpose = 'change_status';
                $apiInfo->request_json = json_encode($request->all());
                $apiInfo->response_json = json_encode($this->response_data);
                $apiInfo->operation_status = 1;
                $apiInfo->save();
            }

            DB::commit();

            return response()->json($this->response_data);
        }catch (\Exception $e) {
            DB::rollback();
            $this->response_data = [
                'responseCode' => 500,
                'message' => "Internal Server Error"
            ];

            return response()->json($this->response_data);
        }
    }

}
