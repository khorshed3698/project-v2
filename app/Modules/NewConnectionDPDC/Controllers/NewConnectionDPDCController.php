<?php

namespace App\Modules\NewConnectionDPDC\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\NewConnectionDPDC\Models\DemandPaymentInfo;
use App\Modules\NewConnectionDPDC\Models\DpdcDocumentShortfall;
use App\Modules\NewConnectionDPDC\Models\DPDCPaymentConfirm;
use App\Modules\NewConnectionDPDC\Models\DPDCPaymentInfo;
use App\Modules\NewConnectionDPDC\Models\DynamicAttachmentDPDC;
use App\Modules\NewConnectionDPDC\Models\DynamicShortfallAttachmentDPDC;
use App\Modules\NewConnectionDPDC\Models\NewConnectionDPDC;
use App\Modules\NewConnectionDPDC\Models\RequestQueuedpdc;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;



class NewConnectionDPDCController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 114;
        $this->aclName = 'NewConnectionDPDC';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionDPDC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionDPDC-971]</h4>"]);
        }
        try {


            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getdpdcToken();
            $dpdc_service_url = Config('stackholder.DPDC_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionDPDC::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'dpdc_service_url')));

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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionDPDC-970]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionDPDC::find($decodedId);
                //dd($appData);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionDPDC();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
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
            $processData->submitted_at = Carbon::now()->toDateTimeString();
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
                    $app_doc = DynamicAttachmentDPDC::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            } /* End file uploading */

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
                Session::flash('error', "Payment configuration not found [DPDC-107]");
                return redirect()->back()->withInput();
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 10 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;
                    $servertype = '';
                    if (env('server_type', 'local') == 'live') {
                        $servertype = '';
                    } elseif (env('server_type') == 'local') {
                        $servertype = 'L';
                    } elseif (env('server_type') == 'uat') {
                        $servertype = 'U';
                    } elseif (env('server_type') == 'training') {
                        $servertype = 'U';
                    } else {
                        $servertype = 'L';
                    }
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'DPDC-' . date("dMY") . '-';
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                    }
                }
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $this->SubmmisionjSon($appData->id, $tracking_no, $request->ip());
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {

                $paymentInfo = DPDCPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->app_fee_status = 10; // application not yet submitted
                $paymentInfo->app_account_status = 10; //application not yet submitted
                $paymentInfo->save();
            }

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


            DB::commit();


            if ($request->get('actionBtn') == "draft") {
                return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('new-connection-dpdc/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

//    Store End
    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        $appData = NewConnectionDPDC::find($app_id);
        $demanddata = DemandPaymentInfo::where('ref_id', $app_id)->first();
        return view("NewConnectionDPDC::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        //        if (!$request->ajax()) {
        //            return 'Sorry! this is a request without proper way. [dpdc-1002]';
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
        $decodedAppId = Encryption::decodeId($appId);
        $alreadySubmitted = RequestQueuedpdc::Where('ref_id', $decodedAppId)->where('status', 1)->first();
        if (count($alreadySubmitted) > 0) {
            $applicationId = $appId;
            $public_html = strval(view("NewConnectionDPDC::waiting-for-payment-whout-sidebar", compact('applicationId', 'paymentId')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }


        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [dpdc-973]</h4>"
            ]);
        }

        try {

            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('dpdc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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


            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $token = $this->getdpdcToken();
            $dpdc_service_url = Config('stackholder.DPDC_SERVICE_API_URL');
            $public_html = strval(view("NewConnectionDPDC::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'token', 'dpdc_service_url', 'descriptionLoad')));
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
        //dd('ok');
        return view("NewConnectionDPDC::waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $dpdcPaymentInfo = DPDCPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $paymentData = json_decode($dpdcPaymentInfo->app_fee_json);
        $bank_account_status = intval($dpdcPaymentInfo->app_account_status);
        $app_fee_status = intval($dpdcPaymentInfo->app_fee_status);

        if ($app_fee_status == 1 && $bank_account_status == 1) {
            $status = 1;
        } elseif ($app_fee_status == 0 || $bank_account_status == 0) {
            $status = 0;
        } elseif ($app_fee_status == -1 || $bank_account_status == -1) {
            $status = -1;
        } elseif ($app_fee_status == 10 && $app_fee_status == 10) {
            $status = 0;
        } else {
            $status = -3;
        }
        if ($status == 1){
            $applyPaymentfee = $paymentData->result->FEE;
            $ServicepaymentData =ApiStackholderMapping:: where('process_type_id',$this->process_type_id)->first(['amount']);
            $paymentInfo =  view(
                "NewConnectionDPDC::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($dpdcPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to DPDC server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DPDC']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified','paymentInformation'=>$paymentInfo]);
        }
    }

    public function dpdcPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionDPDC::find($appId);
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
            Session::flash('error', "Payment configuration not found [DPDC-1123]");
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
        $dpdcPaymentInfo = DPDCPaymentInfo::where('ref_id', $appId)->first();
        $account_data = json_decode($dpdcPaymentInfo->app_fee_account_json);
        $amount_data = json_decode($dpdcPaymentInfo->app_fee_json);
        $appFeeAmount = $amount_data->result->FEE;

        $dpdcPaymentInfo = array(
            'receiver_account_no' => $account_data->result->ACCOUNT_NO,
            'amount' => $appFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );


        $stackholderMappingInfo[] = $dpdcPaymentInfo;

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
        NewConnectionDPDC::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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

            if ($paymentInfo->payment_category_id == 3) { //govt fee
                $processData->status_id = 1;
                $processData->desk_id = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                NewConnectionDPDC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                //form 1 and from 2 json generate
//                $this->DOERequestToJson($processData->ref_id);
            } else if ($paymentInfo->payment_category_id == 2) {
                NewConnectionDPDC::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
            }

            $processData->save();

            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();

            $appData = NewConnectionDPDC::where('id', $processData->ref_id)->first();

            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee

                foreach ($data2 as  $singleResponse) {
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
            } else if ($paymentInfo->payment_category_id == 2) { // type 2 for demand fee payment
                $demandInfo = DemandPaymentInfo::where('ref_id', $appData->id)->first(['response']);

                $demandFeeResponse = json_decode($demandInfo->response);
                //dd($demandFeeResponse);
                $paymentserial = 0;
                foreach ($data2 as $key => $singleResponse) {
                    $value = json_decode($singleResponse->verification_response);
                    $rData0['account_info'][] = [
                        'account_no' => $value->TranAccount,
                        'particulars' => $value->ReferenceNo,
                        'balance' => 0,
                        'deposit' => $value->TranAmount,
                        'tran_date' => $value->TransactionDate,
                        'tran_id' => $value->TransactionId,
                        'scrl_no' => !empty($value->SCRL_NO) ? $value->SCRL_NO : null,
                        'payPurCode' => $demandFeeResponse->result[$paymentserial]->BILL_TYPE_CODE,
                        'invoiceNumber' => $demandFeeResponse->result[$paymentserial]->INVOICE_NUM
                    ];
                    $paymentserial++;
                }
            }
//            dd($rData0);


            $doePaymentConfirm = new DPDCPaymentConfirm();
            if ($paymentInfo->payment_category_id == 2) {
                $doePaymentConfirm->is_demand = 1;
            }
            $doePaymentConfirm->request = json_encode($rData0);
            $doePaymentConfirm->ref_id = $paymentInfo->app_id;
            $doePaymentConfirm->tracking_no = $processData->tracking_no;
//            dd($doePaymentConfirm);
            $doePaymentConfirm->save();
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
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


            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                if ($paymentInfo->payment_category_id == 3) { // for applicaiton submission

                    $processData->status_id = 1;
                    $processData->desk_id = 0;
                    $processData->read_status = 0;

                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

                    NewConnectionDPDC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    //form 1 and from 2 json generate
                    //                    $this->DOERequestToJson($processData->ref_id);

                } elseif ($paymentInfo->payment_category_id == 2) { //demand fee
                    NewConnectionDPDC::where('id', $processData->ref_id)->update(['demand_status' => 1]);

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
                $demandInfo = DemandPaymentInfo::where('ref_id', $appInfo->id)->first(['response']);

                $demandFeeResponse = json_decode($demandInfo->response);
                foreach ($data2 as $key => $singleResponse) {
                    $value = json_decode($singleResponse->verification_response);
                        $rData0['account_info'][] = [
                            'account_no' => $value->TranAccount,
                            'particulars' => $value->ReferenceNo,
                            'balance' => 0,
                            'deposit' => $value->TranAmount,
                            'tran_date' => $value->TransactionDate,
                            'tran_id' => $value->TransactionId,
                            'scrl_no' => !empty($value->SCRL_NO) ? $value->SCRL_NO : null,
                            'payPurCode' => $demandFeeResponse->result[$paymentserial]->BILL_TYPE_CODE,
                            'invoiceNumber' => $demandFeeResponse->result[$paymentserial]->INVOICE_NUM
                        ];

                        $paymentserial++;
                    }



                $dpdcPaymentConfirm = new dpdcPaymentConfirm();
                if ($paymentInfo->payment_category_id == 2) {
                    $dpdcPaymentConfirm->is_demand = 1;
                }
                $dpdcPaymentConfirm->request = json_encode($rData0);
                $dpdcPaymentConfirm->ref_id = $paymentInfo->app_id;
                $dpdcPaymentConfirm->tracking_no = $processData->tracking_no;
                $dpdcPaymentConfirm->save();
            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('new-connection-dpdc/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public
    function applicationView($appId, Request $request)
    {
        //        if (!$request->ajax()) {
        //            return 'Sorry! this is a request without proper way. [BRC-1003]';
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

        $decodedAppId = Encryption::decodeId($appId);
        //dd($decodedAppId);
        $process_type_id = $this->process_type_id;
        //$companyIds = CommonFunction::getUserCompanyWithZero();

        // get application,process info

        $appInfo = ProcessList::leftJoin('dpdc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
        $shortfallarr = [];

        if ($appInfo->status_id === 50) {
            $shortFallData = DpdcDocumentShortfall::where('ref_id', $appInfo->id)->where('status', 1)->first();
            if ($shortFallData) {
                $response = json_decode($shortFallData->response);
                $shortfallarr = $response->result;
            }
        }
        $appData = json_decode($appInfo->appdata);
        //            dd($appData);
        $dynamic_shortfall = DynamicShortfallAttachmentDPDC::where('ref_id', $appInfo->ref_id)->get();
        // dd($dynamic_shortfall);
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


        $token = $this->getdpdcToken();
        $dpdc_service_url = Config('stackholder.DPDC_SERVICE_API_URL');

        $public_html = strval(view(
            "NewConnectionDPDC::application-form-view",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'demand_view', 'token', 'dpdc_service_url', 'spPaymentinformation', 'shortfallarr', 'dynamic_shortfall')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function shortfallDoc(Request $request)
    {
        //dd($request->all());
        $docIds = $request->get('dynamicDocumentsId');
        $remarks = $request->get('remarks');

        $submission_url = "https://onlineapplication.dpdc.org.bd/api/rest/sfall_doc_upload.php";
        if (isset($docIds)) {
            foreach ($docIds as $key => $docs) {
                $docIdName = explode('@', $docs);
                $doc_id = $docIdName[0];
                $doc_name = $docIdName[1];
                $app_doc = DynamicShortfallAttachmentDPDC::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $request->get('ref_id'),
                    'doc_id' => $doc_id
                ]);
                $app_doc->remarks = $remarks[$key];
                $app_doc->submission_url = $submission_url;
                $app_doc->doc_id = $doc_id;
                $app_doc->doc_name = $doc_name;
                $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                $app_doc->response = "";
                $app_doc->request_info = "";
                $app_doc->status = 0;
                $app_doc->save();
            }
            NewConnectionDPDC::where('id', $request->get('ref_id'))->update(['is_submit_shortfall' => 1]);
        } /* End file uploading */
        // dd($request->all());
        return redirect()->back();
    }

// Get RJSC token for authorization
    public function getdpdcToken()
    {
        // Get credentials from database
        $dpdc_idp_url = Config('stackholder.DPDC_TOKEN_API_URL');
        $dpdc_client_id = Config('stackholder.DPDC_SERVICE_CLIENT_ID');
        $dpdc_client_secret = Config('stackholder.DPDC_SERVICE_CLIENT_ SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $dpdc_client_id,
            'client_secret' => $dpdc_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$dpdc_idp_url");
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

    public function getbidaecToken()
    {
        // Get credentials from database
        $bidaec_idp_url = Config('stackholder.BIDAEC_TOKEN_API_URL');
        $bidaec_client_id = Config('stackholder.BIDAEC_SERVICE_CLIENT_ID');
        $bidaec_client_secret = Config('stackholder.BIDAEC_SERVICE_CLIENT_ SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $bidaec_client_id,
            'client_secret' => $bidaec_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$bidaec_idp_url");
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


    public function getDynamicDoc(Request $request)
    {
        $dpdc_service_url = Config('stackholder.DPDC_SERVICE_API_URL');
        $categoryId = $request->categoryId;
        $app_id = $request->appId;

        // Get token for API authorization
        $token = $this->getdpdcToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $dpdc_service_url . "/document?tariff=" . $categoryId,
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
        //dd($response);

        $decoded_response = json_decode($response, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data'];

                $clr_document = DynamicAttachmentDPDC::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }

                $html = view(
                    "NewConnectionDPDC::dynamic-document",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }


    public function validateNID(Request $request)
    {
        $bidaec_service_url = Config('stackholder.BIDAEC_SERVICE_API_URL');
        $nid_number = $request->nid_number;
        $dob = $request->dob;
        $date = date('Y-m-d', strtotime($dob));
        $nid_ln = strlen($nid_number);
        $postData = '';
        if ($nid_ln == 10) {
            $postData = '{"dateOfBirth": "' . $date . '","nid10Digit": ' . $nid_number . ',"nid17Digit": null}';
        } else if ($nid_ln == 17) {
            $postData = '{"dateOfBirth": "' . $date . '","nid10Digit": null ,"nid17Digit": "' . $nid_number . '"}';
        }
        //dd($postData);
        // Get token for API authorization
        $token = $this->getbidaecToken();
        //dump($token);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $bidaec_service_url . "/nid/details",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $verify_response = json_decode($response, true);
        return response()->json(['responseCode' => 1, 'verify_nid' => $verify_response]);
    }

    public function deleteDynamicDoc(Request $request)
    {
        $process_type_id = $request->process_type_id;
        $ref_id = $request->ref_id;
        $doc_id = $request->doc_id;
        $res = DynamicAttachmentdpdc::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public function uploadDocument()
    {
        return View::make('NewConnectionDPDC::ajaxUploadFile');
    }


    public function SubmmisionjSon($app_id, $tracking_no, $ip_address)
    {
        // Submission Request Data

        $dpdcRequest = RequestQueuedpdc::firstOrNew([
            'ref_id' => $app_id
        ]);
        $appData = NewConnectionDPDC::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);

        $submissionData = [];
        $submissionData['ossTrackingNo'] = $tracking_no;
        $submissionData['ossAgentName'] = "bida";
//        dd($masterData);
        if (empty($masterData->applicant_gender)) {
            $gender = "";
        } elseif ($masterData->applicant_gender == "female") {
            $gender = "M";
        } else {
            $gender = "F";
        }
        if (empty($masterData->applicant_gender)) {
            $multipleOwner = "N";
        } elseif ($masterData->applicant_gender == 1) {
            $multipleOwner = "Y";
        } else {
            $multipleOwner = "N";
        }


        if ($masterData->identity == "passport") {
            $docType = "p";
        } else {
            $docType = "n";
        }
        $pinCode = sprintf("%06d", mt_rand(1, 999999));
        $param = [
            'ossTrackingNo' => $tracking_no,
            'consumerType' => $masterData->consumer_type,
            'custName' => !empty($masterData->applicant_name) ? $masterData->applicant_name : $masterData->organization_name,
            'spouse' => !empty($masterData->applicant_spouse_name) ? $masterData->applicant_spouse_name : "",
            'father' => !empty($masterData->applicant_father_name) ? $masterData->applicant_father_name : "",
            'mother' => !empty($masterData->applicant_mother_name) ? $masterData->applicant_mother_name : "",
            'dob' => !empty($masterData->pr_date_of_birth) ? Carbon::parse($masterData->pr_date_of_birth)->format('Y-m-d') : Carbon::parse($masterData->date_of_birth)->format('Y-m-d'),
            'gender' => $gender,
            'nidPp' => !empty($masterData->nid_number) ? $masterData->nid_number : $masterData->passport,
            'docType' => $docType,
            'mHouseNo' => !empty($masterData->house_no) ? $masterData->house_no : "",
            'mRoadNo' => !empty($masterData->lane_no) ? $masterData->lane_no : "",
            'mSection' => !empty($masterData->section) ? $masterData->section : "",
            'mBlock' => !empty($masterData->block) ? $masterData->block : "",
            'mDistrict' => !empty($masterData->district) ? explode("@", $masterData->district)[0] : "",
            'mThana' => !empty($masterData->thana) ? explode("@", $masterData->thana)[0] : "",
            'mPostCode' => !empty($masterData->post_code) ? $masterData->post_code : "",
            'mEmail' => !empty($masterData->email) ? $masterData->email : "",
            'mTelephone' => !empty($masterData->telephone) ? $masterData->telephone : "",
            'mMobile' => !empty($masterData->mobile_no) ? $masterData->mobile_no : "",
            'cHouseNo' => !empty($masterData->connection_house_no) ? $masterData->connection_house_no : "",
            'cRoadNo' => !empty($masterData->connection_lane_no) ? $masterData->connection_lane_no : "",
            'cSection' => !empty($masterData->connection_section) ? $masterData->connection_section : "",
            'cBlock' => !empty($masterData->connection_block) ? $masterData->connection_block : "",
            'cDistrict' => !empty($masterData->connection_district) ? explode("@", $masterData->connection_district)[0] : "",
            'cThana' => !empty($masterData->connection_thana) ? explode("@", $masterData->connection_thana)[0] : "",
            'cPostCode' => !empty($masterData->connection_post_code) ? $masterData->connection_post_code : "",
            'cEmail' => !empty($masterData->connection_email) ? $masterData->connection_email : "",
            'cTelephone' => !empty($masterData->connection_telephone) ? $masterData->connection_telephone : "",
            'cMobile' => !empty($masterData->connection_mobile) ? $masterData->connection_mobile : "",
            'cArea' => !empty($masterData->connection_area) ? explode("@", $masterData->connection_area)[0] : "",
            'cDivision' => !empty($masterData->connection_division) ? explode("@", $masterData->connection_division)[0] : "",
            'connType' => !empty($masterData->connectionType) ? $masterData->connectionType : "",
            'phaseNo' => !empty($masterData->phase) ? $masterData->phase : "",
            'tariff' => explode("@", $masterData->category)[0],
            'appliedMeter' => !empty($masterData->demand_meter) ? $masterData->demand_meter : "",
            'appliedLoad' => !empty($masterData->demand_load) ? $masterData->demand_load : "",
            'existingMeter' => !empty($masterData->existing_meter) ? $masterData->existing_meter : 0,
            'existingLoad' => !empty($masterData->existing_load) ? $masterData->existing_load : 0,
            'multipleOwner' => $multipleOwner,
            "pinCode" => $pinCode,
            "propName" => !empty($masterData->proprietor_name) ? $masterData->proprietor_name : "",
            'ipAddress' => "$ip_address",
            'remark' => "",
        ];
        $requestBody = ['api' => "newApplication",
            "param" => $param];
        $submissionData['requestBody'] = $requestBody;


        $dpdcRequest->ref_id = $appData->id;
        $dpdcRequest->type = 'Submission';
        $dpdcRequest->status = 0;   // 10 = payment not submitted
        $dpdcRequest->request_json = json_encode($submissionData);
        //dd($dpdcRequest->request_json);
        $dpdcRequest->save();
        // Submission Request Data ends
    }


    public function getClientId()
    {
        $dpdc_api_url = Config('stackholder.DPDC_SERVICE_API_URL');
        $token = $this->getdpdcToken();

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
            CURLOPT_URL => $dpdc_api_url . "/token?access_token=" . $token,
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

    public function getRefreshToken()
    {
        $token = $this->getdpdcToken();
        return response($token);
    }

    public function additionalpayment(Request $request)
    {

        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionDPDC::find($appId);

        if (!$appInfo) {
            Session::flash('error', "Application not found [dpdc-1101]");
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

        $dpdcDemandPaymentInfo = DemandPaymentInfo::where('ref_id', $appId)->first();

        if (!$dpdcDemandPaymentInfo) {
            Session::flash('error', "Payment response not found [dpdc-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($dpdcDemandPaymentInfo->response);

//
//        if ($paymentResponse->response == null) {
//            Session::flash('error', "Payment data not found not found [dpdc-1101]");
//            return redirect()->to('/dashboard');
//        }

        foreach ($paymentResponse->result as $dpdcdemandfeedata) {
            $account_no = $dpdcdemandfeedata->ACCOUNT_NO;
            $paymentdata = array(
                'receiver_account_no' => $account_no,
                'amount' => $dpdcdemandfeedata->INVOICE_AMT
            );
            $stackholderMappingInfo[] = $paymentdata;
        }

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

    public function checkDemandPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $dpdcPaymentInfo = DemandPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $status = intval($dpdcPaymentInfo->status);

        if ($dpdcPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }
    }

    public
    function dpdcRequestToJsonResubmit($request, $appDataId, $ossTrackingNo, $dpdc_traking_num)
    {
        // Re-Submission Request Data

        $dpdcResubmissionRequest = new ResubmissionRequestQueuedpdc();
        $reSubmissionData = [];
        $reSubmissionData['clientId'] = $this->getClientId();
        $reSubmissionData['ossTrackingNo'] = $ossTrackingNo;
        $reSubmissionData['ossAgentName'] = config('stackholder.oss_agent_name');
        $reSubmissionData['personalInfo']['trakingNum'] = $dpdc_traking_num;
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
        $dpdczone = explode("@", $request->dpdc_zone);
        $reSubmissionData['connectionAddress']['dpdcZone'] = $dpdczone[0];
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


        $attachments = DynamicAttachmentdpdc::where('process_type_id', $this->process_type_id)->where('ref_id', $appDataId)->get();

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
        $dpdcResubmissionRequest->ref_id = $appDataId;
        $dpdcResubmissionRequest->type = 'ReSubmission';
        $dpdcResubmissionRequest->status = 0;
        $dpdcResubmissionRequest->request = json_encode($reSubmissionData);
        //dd($dpdcRequest->request_json);
        $dpdcResubmissionRequest->save();


        // Submission Request Data ends
    }

    public
    function demandView($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $appInfo = ProcessList::leftJoin('dpdc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
        return view("NewConnectionDPDC::view-demand", compact('appInfo'));
    }

}
