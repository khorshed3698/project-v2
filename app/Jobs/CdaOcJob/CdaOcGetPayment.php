<?php

namespace App\Jobs\CdaOcJob;

use App\Jobs\Job;
use App\Modules\API\Models\ApiTokenList;
use App\Modules\CdaOc\Models\CdaOcPaymentConfirmGetPayment;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;



class CdaOcGetPayment extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $ref_id = '';

    public function __construct($ref_id)
    {
        $this->ref_id = $ref_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $token = $this->getToken();
        if ($token != '') {
            DB::beginTransaction();
            $payment_confirm_get_payment = CdaOcPaymentConfirmGetPayment::firstOrNew(['ref_id' =>  $this->ref_id]);

            $url = config('stakeholder.cda.oc.service_url') . '/info/payment-info';
            $response = $this->curlGetRequest($url, $token);
            $payment_confirm_get_payment->processing_at = Carbon::now();
            $decoded_response = json_decode($response['data']);
            if($response['http_code'] == 200){
                if(isset($decoded_response->responseCode) && $decoded_response->responseCode == '200') {
                    $payment_confirm_get_payment->response_get_payment = json_encode($decoded_response);
                    $payment_confirm_get_payment->status = 0;
                }else{
                    $payment_confirm_get_payment->response_get_payment = $response['data'];
                    $payment_confirm_get_payment->status = -1;
                }
            }else{
                $payment_confirm_get_payment->response_get_payment = $response;
                $payment_confirm_get_payment->status = -2;
            }
            $payment_confirm_get_payment->save();
            DB::commit();
        }
    }

    public function curlPostRequest($url, $headers, $postdata)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
            $result = curl_exec($ch);

            if (!curl_errno($ch)) {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            } else {
                $http_code = 0;
            }
            curl_close($ch);
            return ['http_code' => intval($http_code), 'data' => $result];
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public  function curlGetRequest($requested_url, $token)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $requested_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => [
                    'agent-id:'.config('stakeholder.agent_id'),
                    "authorization: Bearer " . $token
                ],
            ));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($curl, CURLOPT_TIMEOUT, 30);

            $curlResult = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (curl_errno($curl)) {
                $curlError = curl_error($curl);
                Log::info($curlError);
                $curlResult = null;
                echo $curlError;
            }
            curl_close($curl);
            return ['http_code' => intval($code), 'data' => $curlResult];
        } catch (\Exception $e) {
            Log::error($e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
        }
    }

    public function curlGetResponse($url, $headers)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $http_code = 0;
        }
        curl_close($ch);
        return ['http_code' => intval($http_code), 'data' => $result];

    }

    public function getToken()
    {
        $processTypeId = 132;
        $tokenInfo =  ApiTokenList::where('process_type_id',$processTypeId)->first();
        if ($tokenInfo !=null) {
            $stored_token = $tokenInfo->token;
            $exp_time = strtotime($tokenInfo->valid_till);
            $current_time = strtotime(Carbon::now());

            if($exp_time > $current_time){
                return $stored_token;
            }
        }
        // Get credentials from env
        $token_url = config('stakeholder.constant.bida_token_url');
        $client_id = config('stakeholder.constant.bida_client_id');
        $client_secret = config('stakeholder.constant.bida_client_secret');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$token_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if (!$result) {
            $data = ['responseCode' => 0, 'msg' => 'API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result, true);
        $token = $decoded_json['access_token'];
        $expired_time = config('constant.token_expired_time');
        $tokenInfo = ApiTokenList::firstOrNew(['process_type_id' => $processTypeId]);
        $tokenInfo->token = $token;
        $tokenInfo->valid_till = Carbon::now()->addMinute($expired_time);
        $tokenInfo->save();
        return $token;
    }
}
