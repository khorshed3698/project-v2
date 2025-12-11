<?php

namespace App\Modules\RajukLUCGeneral\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocumentStakeholder;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\RajukLUCGeneral\Models\RajukLUCGeneral;
use App\Modules\RajukLUCGeneral\Models\RequestQueueRajukLUCG;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Carbon\Carbon;
use ClassPreloader\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;


class RajukLUCGeneralController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 124;
        $this->aclName = 'RajukLUCGeneral';
    }

    public function appForm(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [Rajuk LUC general-971]</h4>"]);
        }
        try {
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $service_url = Config('stackholder.RAJUK_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            /*agent id for stakeholder */
            $agentId = Config('stackholder.bida-agent-id');
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            $rajukPaymentAmount = $this->getRajukPaymentInfo();
            if (!$payment_config || !$rajukPaymentAmount) {
                Session::flash('error', "Payment configuration not found [RAJUK-1123]");
                return redirect()->to('/dashboard');
            }

            $payment_config->amount = $rajukPaymentAmount['instrument_amount']+$rajukPaymentAmount['vat_amount'] + $payment_config->amount;

            $public_html = strval(view("RajukLUCGeneral::application-form", compact('process_type_id', 'viewMode', 'payment_config', 'mode', 'token', 'agentId', 'service_url')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [RajukLUCGeneral-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RajukLUCGeneral-1064]');

            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()
                ->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [e-TIN-foreigner-96]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;

        try {

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();

            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = RajukLUCGeneral::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData
                    ->id])
                    ->first();
            } else {
                $appData = new RajukLUCGeneral();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $requestData = $request->all();

            if (empty($requestData['application_type'])) {
                $requestData['application_type'] = '';
            }
            if (empty($requestData['niddobpassporttype'])) {
                $requestData['niddobpassporttype'] = '';
            }
            if (empty($requestData['freedomfighter_status'])) {
                $requestData['freedomfighter_status'] = '';
            }

            $data = json_encode($requestData);
            $appData->appdata = $data;
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'];
            $appData->rajuk_callback_url = $hosturl.'/licence-applications/rajuk-luc-general/list/' . Encryption::encodeId($this->process_type_id);
            $appData->save();

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = -1;
                }
                $processData->desk_id = 0;
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = ''; // for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()
                ->toDateTimeString();
            $processData->read_status = 0;
            //  dd($processData->submitted_at);
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            $docIds = $request->get('dynamicDocumentsId');

            ///Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = AppDocumentStakeholder::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id, 'doc_code' => $doc_id]);
                    $app_doc->doc_code = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);

                    $app_doc->save();
                }
            } /* End file uploading */

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'RAJUKLUCG-' . date("dMY") . '-';
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-3,3) )+1,1),3,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                    }
                }
            }
            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);

//            if ($request->get('actionBtn') != "draft") {
//                $this->submissionJson($appData->id, $tracking_no, $processData->status_id);
//
//            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')->where(['api_stackholder_payment_configuration.process_type_id' => $this->process_type_id, 'api_stackholder_payment_configuration.payment_category_id' => 1, 'api_stackholder_payment_configuration.status' => 1, 'api_stackholder_payment_configuration.is_archive' => 0,])
                    ->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [WASA-1010]");
                    return redirect()->back()
                        ->withInput();
                }
                $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                    ->where('is_active', 1)
                    ->where('process_type_id', $this->process_type_id)
                    ->get([
                        'receiver_account_no',
                        'amount',
                        'distribution_type',
                    ])->toArray();
                $rajuPaymentInfo = $this->getRajukPaymentInfo();
                if(!$rajuPaymentInfo){
                    Session::flash('error', "Payment configuration not found [RAJUK-1011]");
                    return redirect()->back()
                        ->withInput();
                }
                $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');

                $rajukFeeInfo = array(
                    'receiver_account_no' => $rajuPaymentInfo['TranAccount'],
                    'amount' => $rajuPaymentInfo['instrument_amount'],
                    'distribution_type' => $stackholderDistibutionType
                );
                $stackholderMappingInfo[] = $rajukFeeInfo;
                $rajukVatInfo = array(
                    'receiver_account_no' => $rajuPaymentInfo['TranAccountVat'],
                    'amount' => $rajuPaymentInfo['vat_amount'],
                    'distribution_type' => 5
                );

                $stackholderMappingInfo[] = $rajukVatInfo;
                $pay_amount = 0;
                $account_no = "";
                foreach ($stackholderMappingInfo as $data) {
                    $pay_amount += $data['amount'];
                    $account_no .= $data['receiver_account_no'] . "-";
                }
                $account_numbers = rtrim($account_no, '-');

                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
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

                foreach ($stackholderMappingInfo as $data) {
                    $paymentDetails = new StackholderSonaliPaymentDetails();
                    $paymentDetails->payment_id = $paymentInfo->id;
                    if(isset($data['m_category']) && $data['m_category'] =='CHL'){
                        $paymentDetails->purpose_sbl = 'CHL';
                    }else{
                        $paymentDetails->purpose_sbl ='TRN';
                    }
                    $paymentDetails->distribution_type = $data['distribution_type'];
                    $paymentDetails->receiver_ac_no = $data['receiver_account_no'];
                    $paymentDetails->pay_amount = $data['amount'];
                    $paymentDetails->sl_no = 1; // Always 1
                    $paymentDetails = $paymentDetails->save();

                    $sl++;
                }
                DB::commit();
                /*
                 * Payment Submission
                */
                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }

            }

            ///////////////////// stockholder Payment End//////////////////////////
            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($processData->process_type_id));

        } catch (Exception $e) {
            //dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()
                ->withInput();
        }
    }

    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        $token = $this->getToken();
        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else if ($openMode == 'edit') {
            $viewMode = 'off';
            $mode = '-E-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [BPDB-973]</h4>"]);
        }
        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            $appInfo = ProcessList::leftJoin('rajuk_luc_general_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'apps.*', 'process_type.max_processing_day',]);

            $appData = json_decode($appInfo->appdata);

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $rajukPaymentAmount = $this->getRajukPaymentInfo();

            if (!$payment_config || !$rajukPaymentAmount) {
                Session::flash('error', "Payment configuration not found [RAJUK-1123]");
                return redirect()->to('/dashboard');
            }

            $payment_config->amount = $rajukPaymentAmount['instrument_amount']+$rajukPaymentAmount['vat_amount'] + $payment_config->amount;


            $service_url = Config('stackholder.RAJUK_API_URL');


            $agentId = config('stackholder.bida-agent-id');

            $public_html = strval(view("RajukLUCGeneral::application-form-edit", compact('appInfo', 'appData', 'viewMode', 'mode', 'token', 'appId', 'agentId', 'service_url', 'payment_config')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"]);
        }
    }

    public function applicationView($appId, Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BRC-1003]';
        }
        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [CTCC-974]</h4>"]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        $document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)
            ->where('ref_id', $decodedAppId)->get();

        $process_type_id = $this->process_type_id;
        // get application,process info
        $appInfo = ProcessList::leftJoin('rajuk_luc_general_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'ps.status_name', 'apps.*',]);

        $appData = json_decode($appInfo->appdata);


        $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)
            ->whereIn('payment_status', [1, 3])
            ->get(['id as sp_payment_id', 'contact_name as sfp_contact_name', 'contact_email as sfp_contact_email', 'contact_no as sfp_contact_phone', 'address as sfp_contact_address', 'pay_amount as sfp_pay_amount', 'vat_on_pay_amount as sfp_vat_on_pay_amount', 'transaction_charge_amount as sfp_transaction_charge_amount', 'vat_on_transaction_charge as sfp_vat_on_transaction_charge', 'total_amount as sfp_total_amount', 'payment_status as sfp_payment_status', 'pay_mode as pay_mode', 'pay_mode_code as pay_mode_code', 'ref_tran_date_time']);
        $token = $this->getToken();
//        return  view("RajukLUCGeneral::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document'));

        $public_html = strval(view("RajukLUCGeneral::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function checkApiRequestStatus(Request $request)
    {
        $app_id = $request->app_id;
        $apiRequestData = RequestQueueRajukLUCG::where('ref_id', $app_id)->first();
        $appStatus = 0;
        $redirectUrl = null;
        if ($apiRequestData->status == 1) {
            $appData = RajukLUCGeneral::where('id', $app_id)->first();
            if (!empty($appData->rajuk_redirect_url)) {
                $appStatus = 1;
                $redirectUrl = $appData->rajuk_redirect_url;
            } else {
                $appStatus = -1;
            }

        }

        return response()->json(['responseCode' => 1, 'status' => $appStatus, 'rajuk_redirect_url' => $redirectUrl]);
    }

    public function submissionJson($app_id, $tracking_no, $statusId,$payment_id)
    {

        $rajukLucGRequest = RequestQueueRajukLUCG::firstOrNew(['ref_id' => $app_id]);
        if ($statusId == 2) {
            $type = 'RESUBMISSION_REQUEST';
            $rajukLucGRequest->status = 0;
        } else {
            $type = 'Submission';
            $rajukLucGRequest->status = 10;
        }


        $appData = RajukLUCGeneral::where('id', $app_id)->first();

        $masterData = json_decode($appData->appdata);
        $submissionData = [];
        $suboccId = '';
        if (!empty($masterData->land_use_sub_occupancy)) {
            foreach ($masterData->land_use_sub_occupancy as $item) {
                $suboccId .= explode('@', $item)[0] . ',';
            }
        }
        $passwprd = "123456a@";
        $userEmail = Auth::user()->user_email;
        $statusId = rtrim($suboccId, ',');

        $submissionData['ossTrackingNo'] = $tracking_no;
        $submissionData['SubOccId'] = $statusId;
        $submissionData['email'] = $userEmail;
        $submissionData['FirstName'] = Auth::user()->user_first_name;
        $submissionData['LastName'] = Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;
        $phone = Auth::user()->user_phone;
        $number_with_code = substr($phone, 0, 3);
        if ($number_with_code == '+88') {
            $phone = substr($phone, 3);
        }

        $submissionData['MobileNo'] = $phone;
        $submissionData['BanglaName'] = !empty($masterData->applicant_name_bn) ? $masterData->applicant_name_bn : '';
        $submissionData['password'] = $passwprd;
        $submissionData['ApplicantEngName'] = !empty($masterData->applicant_name_en) ? $masterData->applicant_name_en : '';
        $submissionData['ApplicantBanglaName'] = !empty($masterData->applicant_name_bn) ? $masterData->applicant_name_bn : '';
        $submissionData['LandOwnerMobile'] = !empty($masterData->land_owner_mobile) ? $masterData->land_owner_mobile : '';
        $submissionData['LandOwnerMail'] = !empty($masterData->land_owner_email) ? $masterData->land_owner_email : '';
        $submissionData['PresentAddress'] = !empty($masterData->present_address) ? $masterData->present_address : '';
        $submissionData['NidOrPassNo'] = !empty($masterData->nid_passport) ? $masterData->nid_passport : '';
        $submissionData['ThanaId'] = !empty($masterData->thana_name) ? explode('@', $masterData->thana_name) [0] : null;
        $submissionData['RequestForm'] = 'luc';
        $submissionData['HoldingNo'] = !empty($masterData->holding_no) ? $masterData->holding_no : '';
        $submissionData['bida_id'] = $tracking_no;
        $submissionData['url'] = !empty($appData->rajuk_callback_url) ? $appData->rajuk_callback_url : '';
        $appData->rajuk_user_email = $userEmail;
        $appData->rajuk_password = $passwprd;
        $paymentDetails = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->whereIn('distribution_type',[10,5])->get();
        $rajukPaymentRequest = [];
        $totalAmount = 0;

        foreach ($paymentDetails as $detailsValue){
            if(!empty($detailsValue->verification_response)){
                $decodedPaymentResponse = json_decode($detailsValue->verification_response);
                if($detailsValue->distribution_type == 10){
                    $rajukPaymentRequest ['TransactionId'] = $decodedPaymentResponse->TransactionId;
                    $rajukPaymentRequest ['TransactionDate'] = $decodedPaymentResponse->TransactionDate;
                    $rajukPaymentRequest ['ReferenceDate'] = $decodedPaymentResponse->ReferenceDate;
                    $rajukPaymentRequest ['ReferenceNo'] = $decodedPaymentResponse->ReferenceNo;
                    $rajukPaymentRequest ['PaymentStatus'] = 200;
                    $rajukPaymentRequest ['TranAccount'] = $decodedPaymentResponse->TranAccount;
                    $rajukPaymentRequest ['TranAmountFee'] = $decodedPaymentResponse->TranAmount;
                    $totalAmount  += $decodedPaymentResponse->TranAmount;
                }elseif ($detailsValue->distribution_type == 5){
                    $rajukPaymentRequest ['TranAmountVat'] = $decodedPaymentResponse->TranAmount;
                    $rajukPaymentRequest ['TranAccountVat'] = $decodedPaymentResponse->TranAccount;
                    $totalAmount  += $decodedPaymentResponse->TranAmount;
                }
            }
        }
        $rajukPaymentRequest['TotalAmount'] = $totalAmount;
        $submissionData['payment_info'] = (object)$rajukPaymentRequest;

        $rajukLucGRequest->ref_id = $app_id;
        $rajukLucGRequest->type = $type;

        $rajukLucGRequest->request_json = json_encode($submissionData);
        $rajukLucGRequest->save();
        $appData->save();

    }

    public function afterPayment($payment_id)
    {

        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            DB::beginTransaction();
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);

            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name', 'process_type.process_sub_name',
                    'process_list.*'
                ]);
            $this->submissionJson($processData->ref_id, $processData->tracking_no, $processData->status_id,$payment_id);
            $processData->status_id = 1;
            $processData->desk_id = 0;


            RajukLUCGeneral::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            $processData->save();

            RequestQueueRajukLUCG::where('ref_id', $paymentInfo->app_id)->update([
                'status' => '0'
            ]);

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/rajuk-luc-general/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-applications/rajuk-luc-general/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
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
                'process_list.*'
            ]);

        $applicantEmailPhone = UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);
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


        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->read_status = 0;
                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date


                RajukLUCGeneral::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueRajukLUCG::where('ref_id', $paymentInfo->app_id)->update([
                    'status' => '0'
                ]);


                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);


            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';


                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('licence-applications/rajuk-luc-general/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-applications/rajuk-luc-general/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function getToken()
    {
        // Get credentials from database
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        return CommonFunction::getToken($idp_url, $client_id, $client_secret);
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    private function getRajukPaymentInfo(){
        try {
            $rajuk_service_url = Config('stackholder.RAJUK_API_URL');
            $token = $this->getToken();
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $rajuk_service_url . "/info/payment-info",
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
            if (!curl_errno($curl)) {
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            } else {
                $http_code = 0;
            }
            curl_close($curl);
            $decoded_response = json_decode($response, true);
            return  $decoded_response['data'][0];
        }catch (\Exception $e){
            return  false;
        }

    }
}