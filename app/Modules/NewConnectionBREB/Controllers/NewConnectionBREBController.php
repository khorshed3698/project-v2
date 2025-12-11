<?php

namespace App\Modules\NewConnectionBREB\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\NewConnectionBREB\Models\BREBDemandPaymentINfo;
use App\Modules\NewConnectionBREB\Models\BREBPaymentConfirm;
use App\Modules\NewConnectionBREB\Models\BREBPaymentInfo;
use App\Modules\NewConnectionBREB\Models\DynamicAttachmentBREB;
use App\Modules\NewConnectionBREB\Models\NewConnectionBREB;
use App\Modules\NewConnectionBREB\Models\RequestQueueBREB;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class NewConnectionBREBController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 115;
        $this->aclName = 'NewConnectionBREB';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionBREB-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [BREB-371]</h4>"]);
        }
        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $breb_service_url = Config('stackholder.BREB_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionBREB::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'breb_service_url')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [BREB-970]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionBREB::find($decodedId);
                //dd($appData);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionBREB();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
            //   dd($data);
            $appData->appdata = $data;
            $appData->save();
            //dd('ok');

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
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            $docIds = $request->get('dynamicDocumentsId');


            // Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = DynamicAttachmentBREB::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id,
                        'status' => 0
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }

            // Store payment info
            // Get Payment Configuration
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                \Illuminate\Support\Facades\DB::rollback();
                Session::flash('error', "Payment configuration not found [BREB-107]");
                return redirect()->back()->withInput();
            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 10 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;
                    $trackingPrefix = "BREB-" . date("dMY") . '-';

                    \Illuminate\Support\Facades\DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                }
            }

            $oss_tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft") {
                $this->submissionJson($appData->id, $oss_tracking_no, $processData->status_id, $request->ip());
            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {
                $paymentInfo = BREBPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->status = 10; // Application is not submitted yet
                $paymentInfo->save();

            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            DB::commit();

            if ($request->get('actionBtn') == "draft") {
                return redirect('new-connection-breb/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-breb/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('new-connection-breb/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

//    Store End

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        //        if (!$request->ajax()) {
        //            return 'Sorry! this is a request without proper way. [BPDB-1002]';
        //        }
        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else {
            if ($openMode == 'edit') {
                $viewMode = 'off';
                $mode = '-E-';
            }
        }

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [BREB-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('breb_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
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
                    'apps.*',
                    'process_type.max_processing_day',
                ]);
            if ($appInfo->status_id === 5) {
                $shortfallarr = json_decode($appInfo->shortfall_doc);
            } else {
                $shortfallarr = [];
            }
            $submissionStatus = RequestQueueBREB::where('ref_id', $decodedAppId)->where('status', 1)->first();

            if (isset($submissionStatus)) {
                $applicationId = $appId;
                $public_html = strval(view("NewConnectionBREB::waiting-for-payment-without-sidebar", compact('applicationId', 'paymentId')));
                return response()->json(['responseCode' => 1, 'html' => $public_html]);
            }
            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;
            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);
            $token = $this->getToken();
            $breb_service_url = Config('stackholder.BREB_SERVICE_API_URL');
            // dd($breb_service_url);
            $public_html = strval(view("NewConnectionBREB::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'token', 'breb_service_url', 'descriptionLoad', 'shortfallarr')));
            //dd($public_html);
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1015]" . "</h4>"
            ]);
        }
    }

//     End Application Edit

    public function waitForPayment($applicationId)
    {
        return view("NewConnectionBREB::waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $brebPaymentInfo = BREBPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $paymentData = json_decode($brebPaymentInfo->app_fee_json);
        $status = intval($brebPaymentInfo->status);
        if ($status == 10) {
            $status = 0;
        }
        $message = "Fetching payment information";
        if ($status == 1) {
            $message = "Your request has been successfully verified";
            $applyPaymentfee = $paymentData->data->paymentInfo->totalAmount;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "NewConnectionBREB::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }else{
            $queueData = RequestQueueBREB::where('ref_id', $application_id)->first();
            if ($queueData->status == 0){
                $message = "Application submitting to BREB server.";
            }elseif ($queueData->document_status ==0){
                $message = "Document uploading in progress...";
            }
        }
        if ($brebPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($brebPaymentInfo->id), 'status' => 0, 'message' => $message]);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($brebPaymentInfo->id), 'status' => -1, 'message' => 'Waiting for response from BREB']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($brebPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($brebPaymentInfo->id), 'status' => 1, 'message' => $message, 'paymentInformation' => $paymentInfo]);
        }
    }

    public function brebPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionBREB::find($appId);
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first();
        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [BREB-1123]");
            return redirect()->back()->withInput();
        }
        $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
            ->where('is_active', 1)
            ->where('process_type_id', $this->process_type_id)
            ->get([
                'receiver_account_no',
                'amount',
                'distribution_type'
            ])->toArray();

        $brebPaymentData = BREBPaymentInfo::where('ref_id', $appId)->first();
        $paymentResponse = json_decode($brebPaymentData->app_fee_json);

        $brebAccount = $paymentResponse->data->paymentInfo->accountNumber;
        $brebAmount = $paymentResponse->data->paymentInfo->totalAmount;

        if ($brebAmount > 0) {
            $brebPaymentInfo = array(
                'receiver_account_no' => $brebAccount,
                'amount' => $brebAmount,
                'distribution_type' => $stackholderDistibutionType,
            );

            $stackholderMappingInfo[] = $brebPaymentInfo;
        }

        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);

        $pay_amount = 0;
        $account_no = "";

        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }
    
        $account_numbers = rtrim($account_no, '-');

        // Get SBL payment configuration
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appInfo->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appInfo->id;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->ref_tran_no = $processData->tracking_no . "-01";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = $request->get('sfp_contact_name');
        $paymentInfo->contact_email = $request->get('sfp_contact_email');
        $paymentInfo->contact_no = $request->get('sfp_contact_phone');
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();
        NewConnectionBREB::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
        $sl = 1;
        StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();
        foreach ($stackholderMappingInfo as $data) {
            $paymentDetails = new StackholderSonaliPaymentDetails();
            $paymentDetails->payment_id = $paymentInfo->id;
            $paymentDetails->purpose_sbl = 'TRN';
            $paymentDetails->distribution_type = $data['distribution_type'];
            $paymentDetails->receiver_ac_no = $data['receiver_account_no'];
            $paymentDetails->pay_amount = $data['amount'];
            $paymentDetails->sl_no = 1; // Always 1
            $paymentDetails->save();

            $sl++;
        }

        DB::commit();
        /*
        * Payment Submission
       */
        if ($request->get('actionBtn') == 'Payment' && $paymentInsert) {
            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
        }

        ///////////////////// stockholder Payment End//////////////////////////
    }

    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            \Illuminate\Support\Facades\DB::beginTransaction();
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
            if ($paymentInfo->payment_category_id == 3) { // application fee submit
                $processData->status_id = 1;
                $processData->desk_id = 0;
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                NewConnectionBREB::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            }

            $processData->save();


            $appData = NewConnectionBREB::where('id', $processData->ref_id)->first();
            $decoded_appdata = json_decode($appData->appdata);
            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                $breb_payment_info = BREBPaymentInfo::where('ref_id', $processData->ref_id)->first();
                $decoded_breb_payment_info = json_decode($breb_payment_info->app_fee_json);
                $brebAmount = 0;
                $rData0 ['trackingId'] = $appData->breb_tracking_id;
                $rData0 ['pinCode'] = $appData->pin_code;
                $rData0 ['referenceNumber'] = $decoded_breb_payment_info->data->paymentInfo->referenceNumber;
                $rData0 ['paymentPurposeId'] = '2';

                foreach ($data2 as $key => $singleResponse) {
                    $value = json_decode($singleResponse->verification_response);
                        $rData0['transactionId'] = $value->TransactionId;
                        $tranDate = Carbon::parse($value->TransactionDate)->format('Y-m-d\TH:i:s\Z');
                        $rData0['transactionDate'] = $tranDate;
                        $paystatus = '0';
                        if ($value->StatusCode == 200) {
                            $paystatus = '1';
                        }
                        $rData0['paymentStatus'] = $paystatus;
                        $rData0['scrollNumber'] = !empty($value->SCRL_NO) ? $value->SCRL_NO : "1";
                        $rData0['pbsName'] = $decoded_breb_payment_info->data->paymentInfo->pbsName;
                        $rData0['pbsCode'] = $decoded_breb_payment_info->data->paymentInfo->pbsCode;
                        $rData0['zonalCode'] = $decoded_breb_payment_info->data->paymentInfo->zonalCode;
                        $rData0['bankName'] = $decoded_breb_payment_info->data->paymentInfo->bankName;
                        $rData0['branchName'] = $decoded_breb_payment_info->data->paymentInfo->branchName;
                        $rData0['accountNumber'] = $value->TranAccount;
                        if (Config('stackholder.server_type') == 'dev' || Config('stackholder.server_type') == 'uat') {
                            $rData0['accountNumber'] = $decoded_breb_payment_info->data->paymentInfo->accountNumber;
                        }
                        $rData0['totalAmount'] = (int)$value->TranAmount;
                        $rData0['apiResponse'] = json_encode($value);
                        $brebAmount = (int)$value->TranAmount;
                }
                if ($brebAmount > 0) {
                    $brebPaymentConfirm = new BREBPaymentConfirm();
                    $brebPaymentConfirm->request = json_encode($rData0);
                    $brebPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $brebPaymentConfirm->oss_tracking_no = $processData->tracking_no;
                    $brebPaymentConfirm->is_demand = 0;
                    $brebPaymentConfirm->status = 0;
                    $brebPaymentConfirm->save();
                }

            } else if ($paymentInfo->payment_category_id == 2) { // type 2 for demand fee payment
                $demandInfo = BREBDemandPaymentINfo::where('ref_id', $processData->ref_id)->first();
                $decoded_breb_demand_payment_info = json_decode($demandInfo->response);

                $rData0 ['trackingId'] = $appData->breb_tracking_id;
                $rData0 ['pinCode'] = $appData->pin_code;
                $rData0 ['referenceNumber'] = $decoded_breb_demand_payment_info->data->paymentInfo->referenceNumber;
                $rData0 ['paymentPurposeId'] = '3';
                foreach ($data2 as $key => $singleResponse) {
                    $value = json_decode($singleResponse->verification_response);
                        $rData0['transactionId'] = $value->TransactionId;
                        $tranDate = Carbon::parse($value->TransactionDate)->format('Y-m-d\TH:i:s\Z');
                        $rData0['transactionDate'] = $tranDate;
                        $paystatus = '0';
                        if ($value->StatusCode == 200) {
                            $paystatus = '1';
                        }
                        $rData0['paymentStatus'] = $paystatus;
                        $rData0['scrollNumber'] = !empty($value->SCRL_NO) ? $value->SCRL_NO : "1";
                        $rData0['pbsName'] = $decoded_breb_demand_payment_info->data->paymentInfo->pbsName;
                        $rData0['pbsCode'] = $decoded_breb_demand_payment_info->data->paymentInfo->pbsCode;
                        $rData0['zonalCode'] = $decoded_breb_demand_payment_info->data->paymentInfo->zonalCode;
                        $rData0['bankName'] = $decoded_breb_demand_payment_info->data->paymentInfo->bankName;
                        $rData0['branchName'] = $decoded_breb_demand_payment_info->data->paymentInfo->branchName;
                        $rData0['accountNumber'] = $value->TranAccount;
                        if (Config('stackholder.server_type') == 'dev' || Config('stackholder.server_type') == 'uat') {
                            $rData0['accountNumber'] = $decoded_breb_demand_payment_info->data->paymentInfo->accountNumber;
                        }
                        $rData0['totalAmount'] = (int)$value->TranAmount;
                        $rData0['apiResponse'] = json_encode($value);

                }
                $brebPaymentConfirm = new BREBPaymentConfirm();
                $brebPaymentConfirm->request = json_encode($rData0);
                $brebPaymentConfirm->ref_id = $paymentInfo->app_id;
                $brebPaymentConfirm->oss_tracking_no = $processData->tracking_no;
                $brebPaymentConfirm->is_demand = 1;
                $brebPaymentConfirm->status = 0;
                $brebPaymentConfirm->save();

                NewConnectionBREB::where('id', $paymentInfo->app_id)->update(['demand_status' => 1]);
            }
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-breb/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            //            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment . ');
            return redirect('new-connection-breb/ list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found . ');
                return \redirect()->back();
            }
            DB::beginTransaction();
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
            $processData = ProcessList::leftJoin('process_type', 'process_type . id', ' = ', 'process_list . process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type . name as process_type_name',
                    'process_type . process_supper_name',
                    'process_type . process_sub_name',
                    'process_list .*'
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


            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                if ($paymentInfo->payment_category_id == 3) { // for applicaiton submission

                    $processData->status_id = 1;
                    $processData->desk_id = 0;
                    $processData->read_status = 0;

                    $appInfo['payment_date'] = date('d - m - Y', strtotime($paymentInfo->payment_date));

                    NewConnectionBPDB::where('id', $processData->ref_id)->update(['is_submit' => 1]);


                } elseif ($paymentInfo->payment_category_id == 2) { //demand fee
                    $processData->status_id = 81;
                    NewConnectionBPDB::where('id', $processData->ref_id)->update(['demand_status' => 1]);
                }

                $processData->process_desc = 'Counter Payment Confirm';
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $verification_response = json_decode($paymentInfo->verification_response);




                $rData0['file_no'] = 133;
                $rData0['reg_no'] = 33;
                $rData0['branch_code'] = $verification_response->BrCode;
                //dd($data2);
                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                foreach ($data2 as $key => $singleResponse) {
                    $value = json_decode($singleResponse->verification_response);
                        $rData0['account_info'][] = [
                            'account_no' => $value->TranAccount,
                            'particulars' => $value->ReferenceNo,
                            'balance' => 0,
                            'deposit' => $value->TranAmount,
                            'tran_date' => $value->TransactionDate,
                            'tran_id' => $value->TransactionId,
                            'scrl_no' => !empty($value->SCRL_NO) ? $value->SCRL_NO : null
                        ];

                }

                $bpdbPaymentConfirm = new BPDBPaymentConfirm();
                $bpdbPaymentConfirm->request = json_encode($rData0);
                $bpdbPaymentConfirm->ref_id = $paymentInfo->app_id;
                $bpdbPaymentConfirm->tracking_no = $processData->tracking_no;
                $bpdbPaymentConfirm->save();
            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation . ';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('new-connection-breb/ list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment . Error : ' . $e->getMessage());
            return redirect('new-connection-breb/ list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function applicationView($appId, Request $request)
    {
        //        if (!$request->ajax()) {
        //            return 'Sorry!this is a request without proper way . [BRC - 1003]';
        //        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [BRC-974]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            //dd($decodedAppId);
            $process_type_id = $this->process_type_id;
            //$companyIds = CommonFunction::getUserCompanyWithZero();

            // get application,process info

            $appInfo = ProcessList::leftJoin('breb_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
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
                    'ps.status_name',
                    'apps.*',
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'transaction_charge_amount as sfp_transaction_charge_amount',
                    'vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                ]);
            //            dd($appInfo);
            $appData = json_decode($appInfo->appdata);
            $resubmitted_document = DynamicAttachmentBREB::where('ref_id', $appInfo->id)->where('is_shortfall', 1)->get();
            $company_id = $appInfo->company_id;

            $demand_view = 0;

            if ($appInfo->demand_status != 0) {
                $demand_view = 1;
            }


            $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)
                ->where('process_type_id', $this->process_type_id)
                ->whereIn('payment_status', [1, 3])
                ->get([
                    'id as sp_payment_id',
                    'contact_name as sfp_contact_name',
                    'contact_email as sfp_contact_email',
                    'contact_no as sfp_contact_phone',
                    'address as sfp_contact_address',
                    'pay_amount as sfp_pay_amount',
                    'vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'transaction_charge_amount as sfp_transaction_charge_amount',
                    'vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'total_amount as sfp_total_amount',
                    'payment_status as sfp_payment_status',
                    'pay_mode as pay_mode',
                    'pay_mode_code as pay_mode_code',
                    'ref_tran_date_time'
                ]);

            $demandInfo = BREBDemandPaymentINfo::where('status', 1)
                ->where('ref_id', $decodedAppId)->first();
            $pdf = RequestQueueBREB::where('status', 1)
                ->where('ref_id', $decodedAppId)->first(['applicants_pdf', 'demand_note_pdf']);
            $token = $this->getToken();
            $breb_service_url = Config('stackholder.BREB_SERVICE_API_URL');

            $public_html = strval(view(
                "NewConnectionBREB::application-form-view",
                compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'pdf', 'document', 'resubmitted_document', 'demand_view', 'token', 'breb_service_url', 'spPaymentinformation', 'demandInfo')
            ));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('BRViewForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BPDB-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[BPDB-1015]" . "</h4>"
            ]);
        }
    }

// Get RJSC token for authorization
    public
    function getToken()
    {
        // Get credentials from database
        $breb_idp_url = Config('stackholder.BREB_TOKEN_API_URL');
        $breb_client_id = Config('stackholder.BREB_SERVICE_CLIENT_ID');
        $breb_client_secret = Config('stackholder.BREB_SERVICE_CLIENT_ SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $breb_client_id,
            'client_secret' => $breb_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$breb_idp_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if (!$result) {
            $data = ['responseCode' => 0, 'msg' => 'Area API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result, true);
        $token = $decoded_json['access_token'];

        return $token;
    }

    public function shortfallStore(Request $request)
    {
        $decodedAppId = Encryption::decodeId($request->app_id);
        // Start file uploading
        $docIds = $request->get('dynamicDocumentsId');
        if (isset($docIds)) {
            foreach ($docIds as $docs) {
                $docIdName = explode('@', $docs);
                $doc_id = $docIdName[0];
                $doc_name = $docIdName[1];
                $app_doc = DynamicAttachmentBREB::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $decodedAppId,
                    'doc_id' => $doc_id,
                    'status' => 0,
                    'is_shortfall' => 1,
                ]);
                $app_doc->doc_id = $doc_id;
                $app_doc->doc_name = $doc_name;
                $app_doc->is_shortfall = 1;
                $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                $app_doc->save();
            }

        }
        $appInfo = NewConnectionBREB::find($decodedAppId);
        $resubmission = RequestQueueBREB::firstOrNew([
            'ref_id' => $decodedAppId,
            'type' => 'Resubmission'
        ]);
        $requestJson = ['trackingId' => $appInfo->breb_tracking_id, 'statusCode' => '36'];
        $resubmission->final_status = '0';
        $resubmission->document_status = '0';
        $resubmission->request_json = json_encode($requestJson);
        $resubmission->save();

        $processData = ProcessList::where('ref_id', $decodedAppId)
            ->where('process_type_id', $this->process_type_id)
            ->update(['status_id' => '2']);
//        dd($request->all());
        return redirect('new-connection-breb/list/' . Encryption::encodeId($this->process_type_id));

    }

    public function getDynamicDoc(Request $request)
    {
        $breb_service_url = Config('stackholder.BREB_SERVICE_API_URL');
        $app_id = $request->appId;
        $tariff_id = $request->tariff_id;

        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $breb_service_url . "/file-type-details",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $breb_service_url . "/document-list/" . $tariff_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $tariffwiseDoc = curl_exec($ch);
        curl_close($ch);
        $decoded_tariff_doc = json_decode($tariffwiseDoc, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200 && $decoded_tariff_doc['responseCode'] == 200) {
            if ($decoded_response['data'] != '' && $decoded_tariff_doc['data'] != '') {
                $required_documents = $decoded_tariff_doc['data']['requiredFilesList'];
                $attachment_list = [];
                $faileDetailsList = $decoded_response['data']['fileTypeDetailsList'];
                foreach ($faileDetailsList as $value) {
                    foreach ($required_documents as $value2) {
                        if ($value['fileTypeId'] == $value2['filE_TYPE_ID']) {
                            $value['is_required'] = $value2['iS_REQUIRED'];
                            $attachment_list [] = $value;
                        }
                    }

                }

                $clr_document = DynamicAttachmentBREB::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];


                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }

                $html = view(
                    "NewConnectionBREB::dynamic-document",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function getShortfallDoc(Request $request)
    {
        $breb_service_url = Config('stackholder.BREB_SERVICE_API_URL');
        $app_id = $request->appId;
        $tariff_id = $request->tariff_id;

        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $breb_service_url . "/file-type-details",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);


        $ch = curl_init();

        curl_setopt_array($ch, array(
            CURLOPT_URL => $breb_service_url . "/document-list/" . $tariff_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $tariffwiseDoc = curl_exec($ch);
        curl_close($ch);
        $decoded_tariff_doc = json_decode($tariffwiseDoc, true);
        $html = '';
        if ($decoded_response['responseCode'] == 200 && $decoded_tariff_doc['responseCode'] == 200) {
            if ($decoded_response['data'] != '' && $decoded_tariff_doc['data'] != '') {
                $required_documents = $decoded_tariff_doc['data']['requiredFilesList'];
                $attachment_list = [];
                $faileDetailsList = $decoded_response['data']['fileTypeDetailsList'];
                foreach ($faileDetailsList as $value) {
                    foreach ($required_documents as $value2) {
                        if ($value['fileTypeId'] == $value2['filE_TYPE_ID']) {
                            $curl = curl_init();
                            $body = ['trackingId' => '6701113104108628', 'fileTypeID' => $value['fileTypeId']];
                            $body = json_encode($body);
                            curl_setopt_array($curl, array(
                                CURLOPT_URL => $breb_service_url . "/file-upload-status",
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_ENCODING => "",
                                CURLOPT_MAXREDIRS => 10,
                                CURLOPT_TIMEOUT => 0,
                                CURLOPT_SSL_VERIFYHOST => 0,
                                CURLOPT_SSL_VERIFYPEER => 0,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                CURLOPT_CUSTOMREQUEST => "GET",
                                CURLOPT_POSTFIELDS => $body,
                                CURLOPT_HTTPHEADER => array(
                                    "Authorization: Bearer  $token",
                                    "Content-Type: application/json"
                                ),
                            ));

                            $response = curl_exec($curl);
                            curl_close($curl);
                            $dec = json_decode($response);
                            $value['remarks'] = $dec->data->status->statusMessage;
                            $value['is_required'] = $value2['iS_REQUIRED'];
                            $attachment_list [] = $value;

                        }

                    }

                }

                $clr_document = DynamicAttachmentBREB::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];


                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }

                $html = view(
                    "NewConnectionBREB::dynamic-document",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }


    public function deleteDynamicDoc(Request $request)
    {
        $process_type_id = $request->process_type_id;
        $ref_id = $request->ref_id;
        $doc_id = $request->doc_id;
        $res = DynamicAttachmentBPDB::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public
    function uploadDocument()
    {
        //dd('ok');
        return View::make('NewConnectionBREB::ajaxUploadFile');
    }

    public
    function submissionJson($app_id, $tracking_no, $statusid, $ip_address)
    {
        // Submission Request Data

        if ($statusid == 2) {
            $brebRequest = new RequestQueueBREB();
        } else {
            $brebRequest = RequestQueueBREB::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        if ($brebRequest->status == 0) {
            $appData = NewConnectionBREB::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';
            $hiddenDocumentIDs = [];
            $customerExtraFiles = [];

//            $dynamicDocument = $masterData->dynamicDocumentsId;
//            foreach ($dynamicDocument as $value) {
//                $id = explode('@', $value)[0];
//                $filedname = 'validate_field_' . $id;
//                $path = $hosturl . $masterData->$filedname;
//                $hiddenDocumentIDs[] = array('hiddenDocumentID' => $id);
//                $customerExtraFiles[] = array('customer_extra_files' => $path);
//            }

            $paramAppdata = [
                "ossTrackingNo" => $tracking_no,
                "ossAgentName" => "BIDA",
                "pbsName" => !empty($masterData->pbs_name) ? explode('@', $masterData->pbs_name)[1] : '',
                "pbsCode" => !empty($masterData->pbs_name) ? explode('@', $masterData->pbs_name)[0] : '',
                "zonalName" => !empty($masterData->zonal_office) ? explode('@', $masterData->zonal_office)[2] : '',
                "zonalCode" => !empty($masterData->zonal_office) ? explode('@', $masterData->zonal_office)[0] : '',
                "tarrifName" => !empty($masterData->tariff_name) ? explode('@', $masterData->tariff_name)[1] : '',
                "tarrifId" => !empty($masterData->tariff_name) ? (int)(explode('@', $masterData->tariff_name)[0]) : '',
                'serviceDropDistConsumerEng' => !empty($masterData->service_drop_dist_consumer) ? $masterData->service_drop_dist_consumer : '',
                'connectionTypeName' => !empty($masterData->connection_type) ? explode('@', $masterData->connection_type)[1] : '',
                'connectionTypeId' => !empty($masterData->connection_type) ? explode('@', (int)$masterData->connection_type)[0] : '',
                'locationRemarks' => !empty($masterData->location_remarks) ? $masterData->location_remarks : '',
            ];

            $paramAppdata['ApplicantModel'] = [
                "organizationName" => !empty($masterData->organization_name) ? $masterData->organization_name : '',
                "name" => !empty($masterData->name) ? $masterData->name : '',
                "fathersName" => !empty($masterData->fName) ? $masterData->fName : '',
                "mothersName" => !empty($masterData->mName) ? $masterData->mName : '',
                "spouseName" => !empty($masterData->sName) ? $masterData->sName : '',
                "dateOfBirth" => !empty($masterData->date_of_birth) ? Carbon::parse($masterData->date_of_birth)->format('d/m/Y') : '',
                'nationality' => !empty($masterData->nationality) ? explode('@', $masterData->nationality)[0] : '',

                'nid' => !empty($masterData->national_id) ? $masterData->national_id : '',
                'passportNum' => !empty($masterData->passport) ? $masterData->passport : '',
                'mobileNum' => !empty($masterData->mobile) ? $masterData->mobile : '',
                'phoneNum' => !empty($masterData->phone) ? $masterData->phone : '',
                'email' => !empty($masterData->email) ? $masterData->email : '',
                'gender' => !empty($masterData->gender) ? explode('@', $masterData->gender)[0] : '',
            ];

            $paramAppdata['ApplicantModel']['AddressPermanentModel'] = [
                "distId" => !empty($masterData->perm_dist) ? explode('@', $masterData->perm_dist)[0] : '',
                "upazillaId" => !empty($masterData->perm_upazilla) ? explode('@', $masterData->perm_upazilla)[0] : '',
                "thanaId" => !empty($masterData->perm_thana) ? explode('@', $masterData->perm_thana)[0] : '',
                "unionId" => !empty($masterData->perm_union) ? explode('@', $masterData->perm_union)[0] : '',
                "postOffice" => !empty($masterData->perm_post) ? $masterData->perm_post : '',
                "postCodeEng" => !empty($masterData->perm_post_code) ? $masterData->perm_post_code : '',
                'villageId' => !empty($masterData->perm_village) ? explode('@', $masterData->perm_village)[0] : '',
                'mohollaRoadNo' => !empty($masterData->perm_road_no) ? $masterData->perm_road_no : '',
                'houseHoldingNo' => !empty($masterData->perm_house_holding) ? $masterData->perm_house_holding : '',
            ];

            $paramAppdata['ElectricityConnectionLocationModel'] = [
                "distId" => !empty($masterData->cur_district) ? explode('@', $masterData->cur_district)[0] : '',
                "upazillaId" => !empty($masterData->cur_upazilla) ? explode('@', $masterData->cur_upazilla)[0] : '',
                "thanaId" => !empty($masterData->cur_thana) ? explode('@', $masterData->cur_thana)[0] : '',
                "unionId" => !empty($masterData->cur_union) ? explode('@', $masterData->cur_union)[0] : '',
                "postOffice" => !empty($masterData->cur_post) ? $masterData->cur_post : '',
                "postCodeEng" => !empty($masterData->cur_post_code) ? $masterData->cur_post_code : '',
                'villageId' => !empty($masterData->cur_village) ? explode('@', $masterData->cur_village)[0] : '',
                'paraRoadNo' => !empty($masterData->cur_road_no) ? $masterData->cur_road_no : '',
                'houseHoldingNo' => !empty($masterData->cur_house_holding) ? $masterData->cur_house_holding : '',

                'mouza' => !empty($masterData->mouja) ? $masterData->mouja : '',
                'dugNumEng' => !empty($masterData->dag_no) ? $masterData->dag_no : '',
                'khatiyanNumEng' => !empty($masterData->khotian_no) ? $masterData->khotian_no : '',
                'landOwnerTypeId' => !empty($masterData->land_owner_type) ? explode('@', $masterData->land_owner_type)[0] : '',
                'landOwenerName' => !empty($masterData->land_owner_name) ? $masterData->land_owner_name : '',
            ];
            $paramAppdata['DemandLoadModel'] = [
                "totalLOAD" => !empty($masterData->total_load_KW) ? (int)($masterData->total_load_KW) : '',
                "phaseId" => !empty($masterData->phase) ? (int)(explode('@', $masterData->phase)[0]) : '',
                "volt" => !empty($masterData->volt) ? explode('@', $masterData->volt)[1] : '',
            ];

            $brebRequest->ref_id = $appData->id;
            if ($statusid == 2) {
                $brebRequest->type = 'Resubmission';
            } else {
                $brebRequest->type = 'Submission';
            }

            $brebRequest->status = 0;   // 10 = payment not submitted
            $brebRequest->request_json = json_encode($paramAppdata);
            $brebRequest->created_at = Carbon::now();
            $brebRequest->save();
        }
    }

    public
    function getClientId()
    {
        $bpdb_api_url = Config('stackholder.BPDB_SERVICE_API_URL');
        $token = $this->getToken();

        $curl = curl_init();
        $user_name = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;

        $request_data = json_encode([
            'userName' => $user_name,
            'userEmail' => Auth::user()->user_email,
            'userPhone' => Auth::user()->user_phone,
            'clientId' => config('stackholder.oss_agent_name')
        ]);
        //oooooo
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bpdb_api_url . "/token?access_token=" . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $result = $decoded_response['data'];

        $clientid = (isset($result['data'][0]['TOKEN']) ? $result['data'][0]['TOKEN'] : '');
        return $clientid;
    }

    public
    function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public
    function additionalpayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionBREB::find($appId);
        if (!$appInfo) {
            Session::flash('error', "Application not found [BPDB-1101]");
            return redirect()->back()->withInput();
        }
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first();
        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [DOE-1123]");
            return redirect()->back()->withInput();
        }

        $stackholderMappingInfo = [];

        $brebDemandPaymentInfo = BREBDemandPaymentINfo::where('ref_id', $appId)->first();
        if (!$brebDemandPaymentInfo) {
            Session::flash('error', "Payment response not found [BPDB-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($brebDemandPaymentInfo->response);

        if ($paymentResponse->data == null) {
            Session::flash('error', "Payment data not found not found [BPDB-1101]");
            return redirect()->to('/dashboard');
        }

        $brebAccount = $paymentResponse->data->paymentInfo->accountNumber;
        $brebAmount = $paymentResponse->data->paymentInfo->totalAmount;

//        if (Config('stackholder.server_type') == 'dev' || Config('stackholder.server_type') == 'uat') {
//            $brebAccount = '';
//        }
        $brebPaymentInfo = array(
            'receiver_account_no' => $brebAccount,
            'amount' => $brebAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $brebPaymentInfo;

        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');

        // Get SBL payment configuration
        $paymentInfo = new SonaliPaymentStackHolders();
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appInfo->id;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = 2;
        $paymentInfo->ref_tran_no = $processData->tracking_no . "-02";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = $request->get('sfp_contact_name');
        $paymentInfo->contact_email = $request->get('sfp_contact_email');
        $paymentInfo->contact_no = $request->get('sfp_contact_phone');
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();

        $sl = 1;
        StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();
        foreach ($stackholderMappingInfo as $data) {
            $paymentDetails = new StackholderSonaliPaymentDetails();
            $paymentDetails->payment_id = $paymentInfo->id;
            $paymentDetails->purpose_sbl = 'TRN';
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
        return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));

        ///////////////////// stockholder Payment End//////////////////////////
    }

    public
    function BPDBRequestToJsonResubmit($request, $appDataId, $ossTrackingNo, $bpdb_traking_num)
    {
        // Re-Submission Request Data

        $bpdbResubmissionRequest = new ResubmissionRequestQueueBPDB();

        $reSubmissionData = [];
        $reSubmissionData['clientId'] = $this->getClientId();
        $reSubmissionData['ossTrackingNo'] = $ossTrackingNo;
        $reSubmissionData['ossAgentName'] = config('stackholder.oss_agent_name');
        $reSubmissionData['personalInfo']['trakingNum'] = $bpdb_traking_num;
        $reSubmissionData['personalInfo']['applicationType'] = $request->application_type;
        $reSubmissionData['personalInfo']['organizationName'] = $request->organization_name;
        $reSubmissionData['personalInfo']['ApplicantConName'] = $request->connection_name;
        $reSubmissionData['personalInfo']['nameEn'] = $request->applicant_name_english;
        $reSubmissionData['personalInfo']['nameBn'] = $request->applicant_name_bangla;
        $reSubmissionData['personalInfo']['designation'] = $request->authorized_person_designation;
        $reSubmissionData['personalInfo']['ApplicantFName'] = $request->father_name;
        $reSubmissionData['personalInfo']['ApplicantMName'] = $request->mother_name;
        $reSubmissionData['personalInfo']['ApplicantSpouse'] = $request->applicant_spouse_name;
        $reSubmissionData['personalInfo']['mobile'] = $request->applicant_mobile_no;
        $reSubmissionData['personalInfo']['nid'] = $request->nation_id;
        $reSubmissionData['personalInfo']['passport'] = $request->applicant_passport_no;
        $reSubmissionData['personalInfo']['gender'] = $request->sex;
        $reSubmissionData['personalInfo']['dob'] = date('d/m/Y', strtotime($request->date_of_birth));


        if (isset($request->validate_field_signature) && !empty($request->validate_field_signature)) {
            $signatureFilePath = env('PROJECT_ROOT') . '/uploads/' . $request->validate_field_signature;
            // $signatureData = file_get_contents($signatureFilePath);
            // $signatureBase64 = base64_encode($signatureData);
            $reSubmissionData['personalInfo']['signature'] = $signatureFilePath;
            $path_info = pathinfo($signatureFilePath);
            $reSubmissionData['personalInfo']['signType'] = $path_info['extension'];
        }


        if (isset($request->validate_field_photo) && !empty($request->validate_field_photo)) {
            $photoFilePath = env('PROJECT_ROOT') . '/uploads/' . $request->validate_field_photo;
            // $photoData = file_get_contents($photoFilePath);
            // $photoBase64 = base64_encode($photoData);
            $reSubmissionData['personalInfo']['photo'] = $photoFilePath;
            $path_info = pathinfo($photoFilePath);
            $reSubmissionData['personalInfo']['photoType'] = $path_info['extension'];
        }


        $reSubmissionData['mailingAddress']['plot'] = $request->house_no;
        $reSubmissionData['mailingAddress']['section'] = $request->union;
        $district = explode("@", $request->district);
        $reSubmissionData['mailingAddress']['district'] = $district[0];
        $thana = explode("@", $request->thana);
        $reSubmissionData['mailingAddress']['thana'] = $thana[0];
        $reSubmissionData['mailingAddress']['road'] = $request->lane_no;
        $reSubmissionData['mailingAddress']['block'] = $request->block;
        $reSubmissionData['mailingAddress']['postcode'] = $request->post_code;
        $reSubmissionData['mailingAddress']['email'] = $request->email;

        $reSubmissionData['connectionAddress']['dagNo'] = $thana[0];
        $reSubmissionData['connectionAddress']['laneNo'] = $request->connection_lane_no;
        $reSubmissionData['connectionAddress']['section'] = $request->connection_union;
        $reSubmissionData['connectionAddress']['block'] = $request->connection_block;
        $reSubmissionData['connectionAddress']['postCode'] = $request->connection_post_code;
        $bpdbzone = explode("@", $request->bpdb_zone);
        $reSubmissionData['connectionAddress']['bpdbZone'] = $bpdbzone[0];
        $esu = explode("@", $request->esu);
        $connection_area = explode("@", $request->connection_area);
        $reSubmissionData['connectionAddress']['esu'] = $connection_area[2];
        $reSubmissionData['connectionAddress']['connectionArea'] = $connection_area[0];
        $reSubmissionData['connectionAddress']['conMobile'] = $request->connection_mobile_no;
        $connectionDistrict = explode("@", $request->connection_district);
        $reSubmissionData['connectionAddress']['district'] = $connectionDistrict[0];
        $connectionThana = explode("@", $request->connection_thana);
        $reSubmissionData['connectionAddress']['thana'] = $connectionThana[0];


        $reSubmissionData['permanentAddress']['permHouseNo'] = $request->permanet_house_no;
        $reSubmissionData['permanentAddress']['permRoad'] = $request->lane_no;
        $reSubmissionData['permanentAddress']['permSection'] = $request->permanet_union;
        $reSubmissionData['permanentAddress']['permBlock'] = $request->permanet_block;
        $permanetDistrict = explode("@", $request->permanet_district);
        $reSubmissionData['permanentAddress']['permDistrict'] = $permanetDistrict[0];
        $permanetThana = explode("@", $request->permanet_thana);
        $reSubmissionData['permanentAddress']['permThana'] = $permanetThana[0];
        $reSubmissionData['permanentAddress']['permPost'] = $request->permanet_post_code;
        $reSubmissionData['permanentAddress']['permEmail'] = $request->permanet_email;


        // multiple Data
        if (isset($request->description_of_load) && !empty($request->description_of_load)) {
            foreach ($request->description_of_load as $key => $value) {
                $description = explode("@", $request->description_of_load[$key]);
                $reSubmissionData['connectionDetails']['connections'][$key]['loadItemCode'] = $description[0];
                $reSubmissionData['connectionDetails']['connections'][$key]['loadPerItemInWatt'] = $request->load_per_item[$key];
                $reSubmissionData['connectionDetails']['connections'][$key]['noOfItem'] = $request->no_of_item[$key];
                $reSubmissionData['connectionDetails']['connections'][$key]['totalLoadInWatt'] = $request->total_load[$key];
            }
        }
        //doen

        $reSubmissionData['connectionDetails']['conType']['connectionType'] = $request->connectionType;
        $category = explode("@", $request->category);
        $reSubmissionData['connectionDetails']['conType']['category'] = $category[0];
        $phase = explode("@", $request->phase);
        $reSubmissionData['connectionDetails']['conType']['phase'] = $phase[0];
        $reSubmissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';
        $reSubmissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';


        $attachments = DynamicAttachmentBPDB::where('process_type_id', $this->process_type_id)->where('ref_id', $appDataId)->get();

        if (count($attachments) > 0) {
            foreach ($attachments as $key => $attachment) {
                $attachmentFilePath = env('PROJECT_ROOT') . '/uploads/' . $attachment->doc_path;
                // $attachmentData = file_get_contents($attachmentFilePath);
                // $attachmentBase64 = base64_encode($attachmentData);
                $reSubmissionData['connectionDetails']['attachments'][$key]['stringDoc'] = $attachmentFilePath;
                $path_info = pathinfo($attachmentFilePath);
                $reSubmissionData['connectionDetails']['attachments'][$key]['docType'] = $path_info['extension'];
                $reSubmissionData['connectionDetails']['attachments'][$key]['fileName'] = $attachment->doc_name;
                $reSubmissionData['connectionDetails']['attachments'][$key]['docCode'] = $attachment->doc_id;
                $reSubmissionData['connectionDetails']['attachments'][$key]['conTypeCode'] = $request->connectionType;
                $reSubmissionData['connectionDetails']['attachments'][$key]['tarrif'] = $category[0];
                $reSubmissionData['connectionDetails']['attachments'][$key]['phaseTypeCode'] = $phase[0];
            }
        }
        $bpdbResubmissionRequest->ref_id = $appDataId;
        $bpdbResubmissionRequest->type = 'ReSubmission';
        $bpdbResubmissionRequest->status = 0;
        $bpdbResubmissionRequest->request = json_encode($reSubmissionData);
        //dd($bpdbRequest->request_json);
        $bpdbResubmissionRequest->save();


        // Submission Request Data ends
    }

    public
    function demandView($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $appInfo = ProcessList::leftJoin('bpdb_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $app_id)
            ->where('process_list.process_type_id', $this->process_type_id)
            ->first([
                'process_list.id as process_list_id',
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
                'apps.*',
                'process_type.max_processing_day',
            ]);
        return view("NewConnectionBPDB::view-demand", compact('appInfo'));
    }

    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        return view("NewConnectionBREB::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }

    public function checkDemandPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $dpdcPaymentInfo = BREBDemandPaymentINfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $status = intval($dpdcPaymentInfo->status);
        if ($status == 1) {
            $paymentResponse = json_decode($dpdcPaymentInfo->response);
            $brebAmount = $paymentResponse->data->paymentInfo->totalAmount;
            $paymentInfo = view(
                "NewConnectionBREB::paymentInfo-demand",
                compact('brebAmount'))->render();
        }

        if ($dpdcPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }
}
