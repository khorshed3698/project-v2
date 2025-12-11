<?php


namespace App\Modules\MutationLand\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Libraries\UtilFunction;
use App\Modules\FscdNocExisting\Models\FscdNocExisting;
use App\Modules\MutationLand\Models\MutationLand;
use App\Modules\MutationLand\Models\MutationLandPayment;
use App\Modules\MutationLand\Models\MutationLandPaymentConfirm;
use App\Modules\MutationLand\Models\MutationLandRequestQueue;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class MutationLandController extends Controller
{
    const ACL = 'MutationLand'; // MutationLand
    const PROCESS_TYPE = 133;

    public function appForm(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [MutationLand-1001]';
        }

        if (!ACL::getAccsessRight(self::ACL, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [MutationLand-1002]</h4>"]);
        }

        try {
            $data = [];
            $data['payment_config'] = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => self::PROCESS_TYPE,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $data['token'] = $this->getToken();
            $data['ml_service_url'] = Config('stakeholder.ml.service_url');

            $public_html = strval(view("MutationLand::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('ML : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [MutationLand-1003]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [MutationLand-1004]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight(self::ACL, !empty($request->get('app_id')) ? '-E-' : '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [ML-1006]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();

            $rules = [];
            $messages = [];
            if ($request->get('actionBtn') != 'draft') {
                $rules['owner_type'] = 'required';
                $messages['owner_type.required'] = 'Owner Type field is required';

            }
            $this->validate($request, $rules, $messages);

            if (!empty($request->applicant_photo_base64)) {
                $prefix = date('Y_');
                $path = public_path() . '/users/upload/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $splited = explode(',', substr($request->get('applicant_photo_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage = base64_decode($base64ResizeImage);

                $applicant_photo_name = trim(uniqid($prefix, true) . '.' . 'jpeg');
                file_put_contents($path . $applicant_photo_name, $base64ResizeImage);
                $request['applicant_photo'] = $applicant_photo_name;
                $request['applicant_photo_base64'] = '';
            }


            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($request->get('app_id'));
                $appData = MutationLand::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => self::PROCESS_TYPE, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new MutationLand();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
            $appData->appdata = $data;
            $appData->save();

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'];
            $view_url = $hosturl . '/process/licence-applications/mutation-land/view/'. Encryption::encodeId($appData->id) . '/' . Encryption::encodeId(self::PROCESS_TYPE);
            $appData->callback_url =  $view_url;
            $appData->save();

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            }else {
                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = -1;
                }
                $processData->desk_id = 0;
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = self::PROCESS_TYPE;
            $processData->process_desc = ''; // for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $process_type = self::PROCESS_TYPE;
                        $trackingPrefix = 'ML-' . date("dMY") . '-';
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id =$process_type and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");

                    }

                }
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);

//            if ($request->get('actionBtn') != "draft") {
//                $this->submissionJson($appData->id, $tracking_no, $processData->status_id);

//            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);

            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')->where(['api_stackholder_payment_configuration.process_type_id' => self::PROCESS_TYPE, 'api_stackholder_payment_configuration.payment_category_id' => 3, 'api_stackholder_payment_configuration.status' => 1, 'api_stackholder_payment_configuration.is_archive' => 0,])
                    ->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [ML-1012]");
                    return redirect()->back()
                        ->withInput();
                }
                $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                    ->where('is_active', 1)
                    ->where('process_type_id', self::PROCESS_TYPE)
                    ->get([
                        'receiver_account_no',
                        'amount',
                        'distribution_type',
                    ])->toArray();
                $pay_amount = 0;
                $account_no = "";
                foreach ($stackholderMappingInfo as $data) {
                    $pay_amount += $data['amount'];
                    $account_no .= $data['receiver_account_no'] . "-";
                }

                $account_numbers = rtrim($account_no, '-');

                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => self::PROCESS_TYPE, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = self::PROCESS_TYPE;
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no . "-01";
                $paymentInfo->pay_amount = $pay_amount;
                $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInsert = $paymentInfo->save();
                $appData->sf_payment_id = $paymentInfo->id;
                $appData->save();

                $sl = 1;

                StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();

                $stackholderMappingInfo =array_reverse($stackholderMappingInfo);

                foreach ($stackholderMappingInfo as $data) {
                    $paymentDetails = new StackholderSonaliPaymentDetails();
                    $paymentDetails->payment_id = $paymentInfo->id;

                    if($data['distribution_type'] == 3){
                        $paymentDetails->purpose_sbl = 'CHL';
                    }else{
                        $paymentDetails->purpose_sbl = 'TRN';
                    }

                    $paymentDetails->distribution_type = $data['distribution_type'];
                    $paymentDetails->receiver_ac_no = $data['receiver_account_no'];
                    $paymentDetails->pay_amount = $data['amount'];
                    $paymentDetails->sl_no = 1; // Always 1
                    $paymentDetails = $paymentDetails->save();

                    $sl++;
                }
            }
            DB::commit();
            /*
             * Payment Submission
            */
            if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [ML-1007]');
            }


            if ($request->get('actionBtn') == "draft") {
                return redirect('mutation-land/list/' . Encryption::encodeId(self::PROCESS_TYPE));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('mutation-land/list/' . Encryption::encodeId(self::PROCESS_TYPE));
            }
            return redirect('mutation-land/check-payment/' . Encryption::encodeId($appData->id));

        }catch(Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [LM-1008]');
            return Redirect::back()->withInput();
        }
    }

    public function appFormEdit($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [ML-1009]';
        }
        if (!ACL::getAccsessRight(self::ACL, '-E')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [ML-1010]</h4>"]);
        }
        try {
            $data = [];
            $data['applicationId'] = Encryption::decodeId($appId);
            $data['app_info'] = ProcessList::leftJoin('mutation_land_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $data['applicationId'])
                ->where('process_list.process_type_id', self::PROCESS_TYPE)
                ->first(['process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'user_desk.desk_name',
                    'process_type.max_processing_day',
                    'apps.*',
                    ]);

            $data['payment_config'] = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => self::PROCESS_TYPE,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            $data['app_data'] = json_decode($data['app_info']->appdata);
            $data['token'] = $this->getToken();
            $data['ml_service_url'] = Config('stakeholder.ml.service_url');

            $public_html = strval(view("MutationLand::application-form-edit", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('MutationLand : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ML-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ML-10012]');
            return redirect()->back();
        }
    }

    public function appFormView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [ML-1030]';
        }
        $viewMode = 'on';
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight(self::ACL, '-V-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [ML-1031]</h4>"]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        $process_type_id = self::PROCESS_TYPE;
        // get application,process info
        $appInfo = ProcessList::leftJoin('mutation_land_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'ps.status_name', 'apps.*',]);

        $app_data = json_decode($appInfo->appdata);


        $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)->where('process_type_id',self::PROCESS_TYPE)
            ->whereIn('payment_status', [1, 3])
            ->get(['id as sp_payment_id', 'contact_name as sfp_contact_name', 'contact_email as sfp_contact_email', 'contact_no as sfp_contact_phone', 'address as sfp_contact_address', 'pay_amount as sfp_pay_amount', 'vat_on_pay_amount as sfp_vat_on_pay_amount', 'transaction_charge_amount as sfp_transaction_charge_amount', 'vat_on_transaction_charge as sfp_vat_on_transaction_charge', 'total_amount as sfp_total_amount', 'payment_status as sfp_payment_status', 'pay_mode as pay_mode', 'pay_mode_code as pay_mode_code', 'ref_tran_date_time']);
//        $token = $this->getToken();
//        return  view("RajukLUCGeneral::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document'));

        $public_html = strval(view("MutationLand::application-form-view", compact('appInfo', 'app_data', 'process_type_id', 'viewMode', 'token', 'spPaymentinformation')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function submissionJson($app_id, $tracking_no, $statusId,$payment_id)
    {

        $mutationLandRequest = MutationLandRequestQueue::firstOrNew(['ref_id' => $app_id]);
        if ($statusId == 2) {
            $type = 'RESUBMISSION_REQUEST';
            $mutationLandRequest->status = 0;
        } else {
            $type = 'Submission';
            $mutationLandRequest->status = 10;
        }

        $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',3)->first();


        $challan_ver_request = $data2->verification_request;
        $spg_challan = Configuration::where('caption', 'spg_TransactionDetails_challan')->value('value');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "$spg_challan",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "$challan_ver_request",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "content-type: application/json"
            ),
        ));

        $challanResponse = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        $decodedResponse = json_decode($challanResponse);
        $decodedchallan = json_decode($decodedResponse);


        if ($mutationLandRequest->status != 1) {
            $verification_response = json_decode($data2->verification_response);
            $appData = MutationLand::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            $paramAppdata = [
                "owner_type" => !empty($masterData->owner_type) ? explode('@',$masterData->owner_type)[0]  : '',
                "is_company_to_company_transfer" => '1',
                "payment_gateway_id" => '3',
                "transaction_id" => $verification_response->TransactionId,
                "transaction_date" => $verification_response->TransactionDate,
                "ref_tracking_number" => !empty($tracking_no) ? $tracking_no : '',
                "chalan_no" => !empty($verification_response->SCRL_NO) ? $verification_response->SCRL_NO : '',
                "chalan_date" => $verification_response->ReferenceDate,
                "chalan_url" => !empty($decodedchallan->EchalUrl)? $decodedchallan->EchalUrl : '',
                "call_back_url" => $appData->callback_url,
                "ref_transaction_no" => $verification_response->ReferenceNo,
            ];
            
            $mutationLandRequest->type = 'submission';
            $mutationLandRequest->status = 0;
            $mutationLandRequest->request_json = json_encode($paramAppdata);
            $mutationLandRequest->save();
        }
    }
    public function checkStatus(Request $request)
    {

        $ml_service_url = Config('stakeholder.ml.service_url');
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $ml_service_url . "/application-status/" . $request->tracking_no,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 2,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "agent-id:  " . Config('stakeholder.agent_id'),
                "Authorization: Bearer $token",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        if($decoded_response['data']['responseCode'] == 'RSP200'){
            $this->updateProcessStatus($decoded_response);
        }

        return $decoded_response['data'];

    }

    private function updateProcessStatus($decoded_response){
        $apiResponseArray = $decoded_response['data'];
    }

    public function getToken()
    {
        // Get credentials from database
        $ml_token_api_url = Config('stakeholder.ml.token_url');
        $ml_client_id = Config('stakeholder.ml.client');
        $ml_client_secret = Config('stakeholder.ml.secret');



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $ml_client_id,
            'client_secret' => $ml_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$ml_token_api_url");
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

        return $token;
    }

    public function checkApiRequestStatus(Request $request)
    {
        $app_id = $request->app_id;
        $apiRequestData = MutationLandRequestQueue::where('ref_id', $app_id)->first();
        $appStatus = 0;
        $redirectUrl = null;
        if ($apiRequestData->status == 1) {
            $appData = MutationLand::where('id', $app_id)->first();
            if($appData->ml_submission_status == 1){
                $appStatus = 2;
            }else if (!empty($appData->ml_redirect_url)) {
                $appStatus = 1;
                $redirectUrl = $appData->ml_redirect_url;
            } else {
                $appStatus = -1;
            }
        }
        return response()->json(['responseCode' => 1, 'status' => $appStatus, 'ml_redirect_url' => $redirectUrl]);
    }

    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return \redirect()->back();
        }
        DB::beginTransaction();
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('mutation_land_apps', 'mutation_land_apps.id', '=', 'process_list.ref_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
                'process_list.*'
            ]);

        $applicantEmailPhone =UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);
        $appInfo = [
            'app_id' => $processData->ref_id,
            'status_id' => $processData->status_id,
            'process_type_id' => $processData->process_type_id,
            'tracking_no' => $processData->tracking_no,
            'process_type_name' => $processData->process_type_name,
            'process_supper_name' => $processData->process_supper_name,
            'process_sub_name' => $processData->process_sub_name,
            'remarks' => ''
        ];
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            $processData->status_id = 1;
            $processData->submitted_at = date('Y-m-d H:i:s');
            $processData->read_status = 0;
            $processData->process_desc = 'Service Fee Payment completed successfully.';
            $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
            $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
            $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
            $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;
            $appInfo['status_id'] = $processData->status_id;
//            dd($appInfo);
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            $processData->save();

            $appData = MutationLand::where('id', $processData->ref_id)->first();

//            if ($paymentInfo->payment_category_id == 2) {  //type 3 for application feee
            $rData0 = [];

            $this->submissionJson($appData->id, $paymentInfo->app_tracking_no, $processData->status_id,$payment_id);

            $request_data = json_encode($rData0);
            $paymentConfirm = new MutationLandPaymentConfirm();
            $paymentConfirm->request = $request_data;
            $paymentConfirm->ref_id = $paymentInfo->app_id;
            $paymentConfirm->oss_tracking_no = $processData->tracking_no;
            $paymentConfirm->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDAPAYMENT: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ML-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [ML-1022]');
            return redirect('process/licence-applications/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
//        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.[ML-1035]');
            return \redirect()->back();
        }
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);

        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
                'process_list.*'
            ]);
        $applicantEmailPhone =UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);

        $appInfo = [
            'app_id' => $processData->ref_id,
            'status_id' => $processData->status_id,
            'process_type_id' => $processData->process_type_id,
            'tracking_no' => $processData->tracking_no,
            'process_type_name' => $processData->process_type_name,
            'process_supper_name' => $processData->process_supper_name,
            'process_sub_name' => $processData->process_sub_name,
            'remarks' => ''
        ];
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            DB::beginTransaction();

            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->status_id = 16; // Submitted
                $processData->submitted_at = date('Y-m-d H:i:s');
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                Session::flash('success', 'Payment Confirm successfully');
                $mlPayment = new MutationLandPayment();
                $mlPayment->ref_id = $processData->ref_id;
                $mlPayment->ml_id = $processData->luc_id;
                $mlPayment->transaction_id = $paymentInfo->transaction_id;
                $mlPayment->challan_no = $paymentInfo->transaction_id;
//                $mlPayment->transaction_amount =  $processData->cda_processing_fee;
                $mlPayment->transaction_amount = $paymentInfo->pay_amount;
                $mlPayment->transaction_date = $paymentInfo->payment_date;
                $mlPayment->save();
            }
            /*
            * Government payment submit
            * */
            /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 3) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user
                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 2) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }
            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDACOUNTERPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ML-1037]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1022]');
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }


}
