<?php

namespace App\Jobs\FscdNocProposedJob;

use App\Jobs\Job;
use App\Modules\FscdNocProposed\Models\FscdNocProposed;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\DispatchesJobs;



class ApplicantRegistration extends Job implements SelfHandling, ShouldQueue
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
        $token =  ApplicationCreate::getToken();
        if ($token != '') {
            DB::beginTransaction();
            $request_data = FscdNocProposed::where('id',  $this->ref_id)->first();
            $postdata = $request_data->applicant_registration_json;
            $url = config('stakeholder.bfcdc.proposed.service_url') . '/applicant-registration';
            $headers = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
                "agent-id:  " . config('stakeholder.agent_id'),
            );
            $response = $this->curlPostRequest($url,$headers, $postdata);
            $request_data->applicant_registration_response = json_encode($response);
            $request_data->save();
            DB::commit();
            if($response['http_code'] == 200){
                if(isset($response->responseCode) && $response->responseCode == '200') {
                    $this->dispatch(new ApplicationCreate($request_data->id));
                }else{
                    $this->dispatch(new ApplicationCreate($request_data->id));
                }
            }else{
                $this->dispatch(new ApplicationCreate($request_data->id));
            }
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
                    'agent-id:'.config('constant.bida-agent-id'),
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
}
