<?php

namespace App\Modules\NewConnectionDESCO\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\NewConnectionDESCO\Models\DescoDemandPaymentInfo;
use App\Modules\NewConnectionDESCO\Models\DESCOPaymentConfirm;
use App\Modules\NewConnectionDESCO\Models\DescoPaymentInfo;
use App\Modules\NewConnectionDESCO\Models\DescoShortfallDocuments;
use App\Modules\NewConnectionDESCO\Models\DESCOSolarAttachment;
use App\Modules\NewConnectionDESCO\Models\DynamicAttachmentDESCO;
use App\Modules\NewConnectionDESCO\Models\NewConnectionDESCO;
use App\Modules\NewConnectionDESCO\Models\RequestQueueDESCO;
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


class NewConnectionDESCOController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 117;
        $this->aclName = 'NewConnectionDESCO';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionDESCO-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionDESCO-971]</h4>"]);
        }
        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $desco_service_url = Config('stackholder.DESCO_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionDESCO::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'desco_service_url')));

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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionDESCO-970]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionDESCO::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionDESCO();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
            $appData->appdata = $data;
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
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;
            //  dd($processData->submitted_at);

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
                    $app_doc = DynamicAttachmentDESCO::firstOrNew([
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


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'DESCO-' . date("dMY") . '-';
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
            $oss_tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft") {
                $this->SubmmisionjSon($appData->id, $oss_tracking_no, $processData->status_id, $request->ip());
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {

                $paymentInfo = DescoPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
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


            if ($request->get('actionBtn') == "draft") {
                return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('new-connection-desco/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }


    public function solarDocumentUpload(Request $request)
    {
        $solarDocTitles = $request->get('solarDocTitle');
        $solarInstallationDate = $request->get('solarInstallationDate');
        foreach ($solarDocTitles as $key => $solarDocTitle) {

            DESCOSolarAttachment::create([
                'ref_id' => $request->get('app_id'),
                'solarAttachment_title' => $solarDocTitles[$key],
                'solarInstall_date' => Carbon::parse($solarInstallationDate[$key])->format('Y-m-d'),
                'solarDocument_url' => $request->get('validate_field_' . ($key + 1))
            ]);
        }
        NewConnectionDESCO::where('id', $request->get('app_id'))->update(['solar_status' => -1]);
        return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));

    }

//    Store End
    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        return view("NewConnectionDESCO::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [DESCO-1002]';
        }
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
            $appInfo = ProcessList::leftJoin('desco_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
//            $alreadySubmitted = RequestQueueNESCO::Where('ref_id', $decodedAppId)->where('status', 1)->first();
//            if (count($alreadySubmitted) > 0 && $appInfo->status_id != 5) {
//                $applicationId = $appId;
//                $public_html = strval(view("NewConnectionDESCO::waiting-for-payment-whout-sidebar", compact('applicationId', 'paymentId')));
//                return response()->json(['responseCode' => 1, 'html' => $public_html]);
//            }

            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $token = $this->getToken();
            $desco_service_url = Config('stackholder.DESCO_SERVICE_API_URL');
            $public_html = strval(view("NewConnectionDESCO::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'token', 'desco_service_url', 'descriptionLoad')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('NESCOViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [NESCO-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[NESCO-1015]" . "</h4>"
            ]);
        }
    }

//     End Application Edit

    public function waitForPayment($applicationId)
    {
        //dd('ok');
        return view("NewConnectionDESCO::waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $paymentInfo = DescoPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $ServicepaymentData =ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);

        $bank_account_status = intval($paymentInfo->app_account_status);
        $app_fee_status = intval($paymentInfo->app_fee_status);

        if ($app_fee_status == 1 && $bank_account_status == 1) {
            $status = 1;
            $msg = "Your Request has been successfully verified";
        } elseif ($app_fee_status == 0 || $bank_account_status == 0) {
            $status = 0;
            $msg = "Waiting For Payment Response From DESCO";
        } elseif ($app_fee_status == 10 || $bank_account_status == 10) {
            $status = 0;
            $requestQueue = RequestQueueDESCO::where('ref_id', $application_id)->first();
            if ($requestQueue->form_submission_status != 1) {
                $requestQueue->form_submission_status == 0 ? $msg = "Submitting Form To DESCO Server." : $msg = "Failed to submit application !!.";
            } elseif ($requestQueue->meter_info_submission_status != 1 && $requestQueue->meter_info_submission_status != 10) {
                $requestQueue->meter_info_submission_status == 0 ? $msg = "Submitting Meter Information To DESCO Server." : $msg = "Failed to Meter Information !!.";
            } elseif ($requestQueue->meter_info_submission_status != 1 && $requestQueue->meter_info_submission_status != 10) {
                $requestQueue->form_submission_status == 0 ? $msg = "Submitting Meter Information To DESCO Server." : $msg = "Failed to Meter Information !!.";
            } elseif ($requestQueue->document_submission_status != 1 && $requestQueue->document_submission_status != 10) {
                $requestQueue->document_submission_status == 0 ? $msg = "Uploading Document To DESCO Server." : $msg = "Failed to Upload Document !!.";
            } else {
                $msg = "Submitting Form To DESCO Server.";
            }

        } elseif ($app_fee_status == -1 || $bank_account_status == -1) {
            $status = -1;
            $msg = "Your request is invalid. please try again";
        } else {
            $status = -3;
            $msg = "Your request could not be processed. Please contact with system admin";
        }
        if ($status == 1){
            $paymentData = json_decode($paymentInfo->app_fee_json);
            $applyPaymentfee = $paymentData->data->paymentAmount;
            $paymentInfoData =  view(
                "NewConnectionDESCO::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($paymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => $msg]);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfo->id), 'status' => 0, 'message' => $msg]);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => $msg]);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfo->id), 'status' => $status, 'message' => $msg]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfo->id), 'status' => 1, 'message' => $msg,'paymentInformation'=>$paymentInfoData]);
        }
    }

    public function descoPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');

        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionDESCO::find($appId);
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
            Session::flash('error', "Payment configuration not found [DESCO-1123]");
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

        $descoPaymentdata = DescoPaymentInfo::where('ref_id', $appId)->first();
        $descoPaymentdataAmount = json_decode($descoPaymentdata->app_fee_json);
        $descoPaymentdataAccount = json_decode($descoPaymentdata->app_fee_account_json);
        foreach ($descoPaymentdataAccount->data as $value) {
            if ($value->accountPayCategory == "Miscellaneous") {
                $appFeeAccount = $value->accountNo;
            }
            if ($value->accountPayCategory == "VAT") {
                $vatFeeAccount = $value->accountNo;
            }
        }

        $appFeeAmount = $descoPaymentdataAmount->data->paymentAmount;
        $vatFeeAmount = $descoPaymentdataAmount->data->vatAmount;

        $appFeePaymentInfo = array(
            'receiver_account_no' => $appFeeAccount,
            'amount' => $appFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );
        $stackholderMappingInfo[] = $appFeePaymentInfo;
        $vatFeePaymentInfo = array(
            'receiver_account_no' => $vatFeeAccount,
            'amount' => $vatFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $vatFeePaymentInfo;


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
        NewConnectionDESCO::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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
                NewConnectionDESCO::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                //form 1 and from 2 json generate
//                $this->DOERequestToJson($processData->ref_id);
            } else if ($paymentInfo->payment_category_id == 2) {
                NewConnectionDESCO::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
            }

            $processData->save();

//
//            $verification_response = json_decode($paymentInfo->verification_response);
//            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
//            $lopt_url = $spg_conf->value;
//            $userName = Config('payment.spg_settings_stack_holder.user_id');
//            $password = Config('payment.spg_settings_stack_holder.password');
//            $ownerCode = Config('payment.spg_settings_stack_holder.st_code');
//            $referenceDate = $paymentInfo->payment_date;
//            $requiestNo = $paymentInfo->request_id;
//
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => "$lopt_url",
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 30,
//                CURLOPT_SSL_VERIFYPEER => FALSE,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "POST",
//                CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
//                CURLOPT_HTTPHEADER => array(
//                    "cache-control: no-cache",
//                    "content-type: application/json"
//                ),
//            ));
//            $request = ['AccessUser' => $userName, "password" => $password, "OwnerCode" => $ownerCode, "ReferenceDate" => $referenceDate, "RequiestNo" => $requiestNo, "isEncPwd", true];
//
//            $response = curl_exec($curl);
//            $err = curl_error($curl);
//
//            curl_close($curl);


//            $account_num = $spg_conf->details;
//            $data1 = json_decode($response);
//
//            $data2 = json_decode($data1);
            $appData = NewConnectionDESCO::where('id', $processData->ref_id)->first();
            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                $descoPaymentdata = DescoPaymentInfo::where('ref_id', $processData->ref_id)->first();
                $descoPaymentdataAccount = json_decode($descoPaymentdata->app_fee_json);
                $confirmArray = [
                    'connectionApplicationId' => $descoPaymentdataAccount->data->connectionApplicationId,
                    'paymentType' => $descoPaymentdataAccount->data->paymentType,
                    'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                    "trackingNo" => $appData->desco_tracking_no,
                    "ossTrackingNo" => $processData->tracking_no,
                ];
                $total_amount = 0;
                foreach ($data2 as  $value) {
                    $singleResponse = json_decode($value->verification_response);
                    $rData0[] = $singleResponse;
                    $total_amount = $total_amount + $singleResponse->TranAmount;
                }
                $confirmArray['paymentAmount'] = $total_amount;
                $confirmArray['account_info'] = $rData0;

            } else if ($paymentInfo->payment_category_id == 2) {
                $descoDemandPaymentdata = DescoDemandPaymentInfo::where('ref_id', $processData->ref_id)->first();
                $descoMaterialPaymentInfo = json_decode($descoDemandPaymentdata->response_material_fee);
                $descoDepositPaymentINfo = json_decode($descoDemandPaymentdata->response_security_fee);
                $accountInfo = json_decode($descoDemandPaymentdata->response_bank);
                foreach ($accountInfo->data as $value) {
                    if ($value->accountPayCategory == "Miscellaneous") {
                        $mateiralPaymentAccount = $value->accountNo;
                    }
                    if ($value->accountPayCategory == "VAT") {
                        $vatAccount = $value->accountNo;
                    }

                    if ($value->accountPayCategory == "SecurityDeposit") {
                        $depositAccount = $value->accountNo;
                    }
                }

                $confirmArray = [
                    'connectionApplicationId' => $descoDepositPaymentINfo->data->connectionApplicationId,
                    'paymentType' => $descoDepositPaymentINfo->data->paymentType,
                    'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                    "trackingNo" => $appData->desco_tracking_no,
                    "ossTrackingNo" => $processData->tracking_no,
                ];

                $confirmArrayMaterial = [
                    'connectionApplicationId' => $descoMaterialPaymentInfo->data->connectionApplicationId,
                    'paymentType' => $descoMaterialPaymentInfo->data->paymentType,
                    'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                    "trackingNo" => $appData->desco_tracking_no,
                    "ossTrackingNo" => $processData->tracking_no,
                ];
                $rData0 = array();
                $rData1 = array();
                $total_amount_material = 0;
                $total_amount_deposit = 0;
                foreach ($data2 as $data) {
                    $singleResponse = json_decode($data->verification_response);
                    if ($singleResponse->TranAccount == $depositAccount) {
                        $rData0[] =$singleResponse;
                        $total_amount_deposit = $total_amount_deposit + $singleResponse->TranAmount;
                    } else {
                        $rData1[] = $singleResponse;
                        if ($singleResponse->TranAccount == $mateiralPaymentAccount) {
                            $total_amount_material = $total_amount_material + $singleResponse->TranAmount;
                        }
                    }
                }
                $confirmArrayMaterial['paymentAmount'] = $total_amount_material;
                $confirmArrayMaterial['account_info'] = $rData1;
                $confirmArray['paymentAmount'] = $total_amount_deposit;
                $confirmArray['account_info'] = $rData0;
            }

            if ($paymentInfo->payment_category_id == 3) {
                $descoPaymentConfirm = new DESCOPaymentConfirm();
                if ($paymentInfo->payment_category_id == 2) {
                    $descoPaymentConfirm->is_demand = 1;
                }
                $descoPaymentConfirm->request = json_encode($confirmArray);
                $descoPaymentConfirm->ref_id = $paymentInfo->app_id;
                $descoPaymentConfirm->tracking_no = $processData->tracking_no;
                $descoPaymentConfirm->save();
            }

            if ($paymentInfo->payment_category_id == 2) {
                if ($descoDepositPaymentINfo->data->paymentStatus != 'PAID') {
                    $descoPaymentConfirm = new DESCOPaymentConfirm();
                    if ($paymentInfo->payment_category_id == 2) {
                        $descoPaymentConfirm->is_demand = 1;
                    }
                    $descoPaymentConfirm->request = json_encode($confirmArray);
                    $descoPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $descoPaymentConfirm->tracking_no = $processData->tracking_no;
                    $descoPaymentConfirm->save();
                }
            }

            if ($paymentInfo->payment_category_id == 2 && $total_amount_material != 0) {
                $itemPaymentConfirm = new DESCOPaymentConfirm();
                $itemPaymentConfirm->request = json_encode($confirmArrayMaterial);
                $itemPaymentConfirm->ref_id = $paymentInfo->app_id;
                $itemPaymentConfirm->tracking_no = $processData->tracking_no;
                $itemPaymentConfirm->is_demand = 1;
                $itemPaymentConfirm->save();
            }

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
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

            DB::beginTransaction();
            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $appData = NewConnectionDESCO::where('id', $processData->ref_id)->first();
                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                    $descoPaymentdata = DescoPaymentInfo::where('ref_id', $processData->ref_id)->first();
                    $descoPaymentdataAccount = json_decode($descoPaymentdata->app_fee_json);
                    $confirmArray = [
                        'connectionApplicationId' => $descoPaymentdataAccount->data->connectionApplicationId,
                        'paymentType' => $descoPaymentdataAccount->data->paymentType,
                        'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                        "trackingNo" => $appData->desco_tracking_no,
                        "ossTrackingNo" => $processData->tracking_no,
                    ];
                    $total_amount = 0;
                    foreach ($data2 as  $value) {
                        $singleResponse = json_decode($value->verification_response);
                        $rData0[] = $singleResponse;
                        $total_amount = $total_amount + $singleResponse->TranAmount;
                    }
                    $confirmArray['paymentAmount'] = $total_amount;
                    $confirmArray['account_info'] = $rData0;

                } else if ($paymentInfo->payment_category_id == 2) {
                    $descoDemandPaymentdata = DescoDemandPaymentInfo::where('ref_id', $processData->ref_id)->first();
                    $descoMaterialPaymentInfo = json_decode($descoDemandPaymentdata->response_material_fee);
                    $descoDepositPaymentINfo = json_decode($descoDemandPaymentdata->response_security_fee);
                    $accountInfo = json_decode($descoDemandPaymentdata->response_bank);
                    foreach ($accountInfo->data as $value) {
                        if ($value->accountPayCategory == "Miscellaneous") {
                            $mateiralPaymentAccount = $value->accountNo;
                        }
                        if ($value->accountPayCategory == "VAT") {
                            $vatAccount = $value->accountNo;
                        }

                        if ($value->accountPayCategory == "SecurityDeposit") {
                            $depositAccount = $value->accountNo;
                        }
                    }

                    $confirmArray = [
                        'connectionApplicationId' => $descoDepositPaymentINfo->data->connectionApplicationId,
                        'paymentType' => $descoDepositPaymentINfo->data->paymentType,
                        'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                        "trackingNo" => $appData->desco_tracking_no,
                        "ossTrackingNo" => $processData->tracking_no,
                    ];

                    $confirmArrayMaterial = [
                        'connectionApplicationId' => $descoMaterialPaymentInfo->data->connectionApplicationId,
                        'paymentType' => $descoMaterialPaymentInfo->data->paymentType,
                        'paymentDate' => Carbon::parse($paymentInfo->payment_date)->format("d-m-Y"),
                        "trackingNo" => $appData->desco_tracking_no,
                        "ossTrackingNo" => $processData->tracking_no,
                    ];
                    $rData0 = array();
                    $rData1 = array();
                    $total_amount_material = 0;
                    $total_amount_deposit = 0;
                    foreach ($data2 as $data) {
                        $singleResponse = json_decode($data->verification_response);
                        if ($singleResponse->TranAccount == $depositAccount) {
                            $rData0[] =$singleResponse;
                            $total_amount_deposit = $total_amount_deposit + $singleResponse->TranAmount;
                        } else {
                            $rData1[] = $singleResponse;
                            if ($singleResponse->TranAccount == $mateiralPaymentAccount) {
                                $total_amount_material = $total_amount_material + $singleResponse->TranAmount;
                            }
                        }
                    }
                    $confirmArrayMaterial['paymentAmount'] = $total_amount_material;
                    $confirmArrayMaterial['account_info'] = $rData1;
                    $confirmArray['paymentAmount'] = $total_amount_deposit;
                    $confirmArray['account_info'] = $rData0;
                }

                if ($paymentInfo->payment_category_id == 3) {
                    $descoPaymentConfirm = new DESCOPaymentConfirm();
                    if ($paymentInfo->payment_category_id == 2) {
                        $descoPaymentConfirm->is_demand = 1;
                    }
                    $descoPaymentConfirm->request = json_encode($confirmArray);
                    $descoPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $descoPaymentConfirm->tracking_no = $processData->tracking_no;
                    $descoPaymentConfirm->save();
                }

                if ($paymentInfo->payment_category_id == 2) {
                    if ($descoDepositPaymentINfo->data->paymentStatus != 'PAID') {
                        $descoPaymentConfirm = new DESCOPaymentConfirm();
                        if ($paymentInfo->payment_category_id == 2) {
                            $descoPaymentConfirm->is_demand = 1;
                        }
                        $descoPaymentConfirm->request = json_encode($confirmArray);
                        $descoPaymentConfirm->ref_id = $paymentInfo->app_id;
                        $descoPaymentConfirm->tracking_no = $processData->tracking_no;
                        $descoPaymentConfirm->save();
                    }
                }

                if ($paymentInfo->payment_category_id == 2 && $total_amount_material != 0) {
                    $itemPaymentConfirm = new DESCOPaymentConfirm();
                    $itemPaymentConfirm->request = json_encode($confirmArrayMaterial);
                    $itemPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $itemPaymentConfirm->tracking_no = $processData->tracking_no;
                    $itemPaymentConfirm->is_demand = 1;
                    $itemPaymentConfirm->save();
                }

            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();


                // App Tracking ID store in Payment table
                SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
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

        $appInfo = ProcessList::leftJoin('desco_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
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
                'ps.status_name',
                'apps.*',
            ]);

        $demandInfo = DescoDemandPaymentInfo::where('bank_status', 1)
            ->where('security_fee_status', 1)
            ->where('material_fee_status', 1)
            ->where('ref_id', $decodedAppId)->first();

        $solarDocs = DESCOSolarAttachment::where('ref_id', $decodedAppId)->get();

        $appData = json_decode($appInfo->appdata);
        $company_id = $appInfo->company_id;


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


        $token = $this->getToken();
        $desco_service_url = Config('stackholder.DESCO_SERVICE_API_URL');
        $public_html = strval(view(
            "NewConnectionDESCO::application-form-view",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'demandInfo', 'mode', 'token', 'desco_service_url', 'spPaymentinformation', 'solarDocs')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function shortfallDoc($appId)
    {
        $decodedAppId = Encryption::decodeId($appId);
        $process_type_id = $this->process_type_id;
        $appInfo = NewConnectionDESCO::find($decodedAppId);
        $appData = json_decode($appInfo->appdata);
        $desco_service_url = Config('stackholder.DESCO_SERVICE_API_URL');
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $desco_service_url . "/info/attachment-doc/tariff/" . explode('@', $appData->tariff)[0] . "/category/" . explode('@', $appData->tariff_category)[0],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        $shortfall = json_decode($response);
        $shortfallarr = $shortfall->data;
        $shortfallDocumentsIds = json_decode($appInfo->shortfall_documents);
        return view(
            "NewConnectionDESCO::dynamic-shortfall-document",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'desco_service_url',
                'shortfallarr', 'shortfallDocumentsIds')
        );
    }


    public function shortfallDocSave(Request $request)
    {
        // dd($request->all());
        $docIds = $request->get('dynamicDocumentsId');
        $ref_id = Encryption::decodeId($request->get('ref_id'));
        // Start file uploading
        if (isset($docIds)) {
            foreach ($docIds as $docs) {
                $docIdName = explode('@', $docs);
                $doc_id = $docIdName[0];
                $doc_name = $docIdName[1];
                $app_doc = DescoShortfallDocuments::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $ref_id,
                    'doc_id' => $doc_id
                ]);
                $app_doc->doc_id = $doc_id;
                $app_doc->doc_name = $doc_name;
                $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                $app_doc->save();
            }
        } /* End file uploading */
        // dd($request->all());
        ProcessList::where('ref_id', $ref_id)
            ->where('process_type_id', $this->process_type_id)
            ->update(['status_id' => 2]);
        Session::flash('success', 'Successfully Application Re-Submitted !');
        return redirect('new-connection-desco/list/' . Encryption::encodeId($this->process_type_id));
    }

// Get RJSC token for authorization
    public function getToken()
    {
        // Get credentials from database
        $desco_idp_url = Config('stackholder.DESCO_TOKEN_API_URL');
        $desco_client_id = Config('stackholder.DESCO_SERVICE_CLIENT_ID');
        $desco_client_secret = Config('stackholder.DESCO_SERVICE_CLIENT_SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $desco_client_id,
            'client_secret' => $desco_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$desco_idp_url");
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


    public function getDynamicDoc(Request $request)
    {
        $desco_service_url = Config('stackholder.DESCO_SERVICE_API_URL');
        $app_id = $request->appId;
        // dd($app_id);
        $tariff_category_id = $request->tariff_category_id;
        $tariff_id = $request->tariff_id;
        // Get token for API authorization
        $token = $this->getToken();
        $requested_url = $desco_service_url . "/info/attachment-doc/tariff/" . $tariff_id . "/category/" . $tariff_category_id;
        //dd($requested_url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $requested_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . $token,
                "cache-control: no-cache",
                "Content-Type: application/json",
            ],
        ));


        $responseJson = curl_exec($curl);

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (curl_errno($curl)) {
            echo curl_error($curl);
            $curlResponse = null;

        } else {
            curl_close($curl);
            $curlResponse = json_decode($responseJson);
        }

        $html = '';
        if ($curlResponse->responseCode == 200) {
            if ($curlResponse->data != '') {
                $attachment_list = $curlResponse->data;

                $clr_document = DynamicAttachmentDESCO::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                // dd($clr_document);
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
//                dd($clrDocuments);

                $html = view(
                    "NewConnectionDESCO::dynamic-document",
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
        $res = DynamicAttachmentDESCO::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public function uploadDocument()
    {
        return View::make('NewConnectionDESCO::ajaxUploadFile');
    }

    public function solarDocumentView($id)
    {
        $application_id = Encryption::decodeId($id);
        return view('NewConnectionDESCO::solarDocuments', compact('application_id'));
    }


    public function SubmmisionjSon($app_id, $tracking_no, $statusid, $ip_address)
    {
        // Submission Request Data

        if ($statusid == 2) {
            $nescoRequest = new RequestQueueDESCO();
        } else {
            $nescoRequest = RequestQueueDESCO::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        if ($nescoRequest->status == 0) {
            $appData = NewConnectionDESCO::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
//            dd($masterData);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';
            $hiddenDocumentIDs = [];
            $customerExtraFiles = [];

            $dynamicDocument = $masterData->dynamicDocumentsId;
            foreach ($dynamicDocument as $value) {
                $id = explode('@', $value)[0];
                $filedname = 'validate_field_' . $id;
                $path = $hosturl . $masterData->$filedname;
                $hiddenDocumentIDs[] = array('hiddenDocumentID' => $id);
                $customerExtraFiles[] = array('customer_extra_files' => $path);
            }

            $paramAppdata = [
                "ossClientId" => "NGU5OWFiNjQ5YmQwNGY3YTdmZTEyNzQ3YzQ1YSA",
                "ossTrackingNo" => $tracking_no,
                "ossAgentName" => "BIDA",
                "applicationId" => $statusid == 2 ? $appData->desco_application_id : "",
                "idType" => "NID",
                "idNumber" => !empty($masterData->nid_number) ? $masterData->nid_number : '',
                "userNidName" => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
                "applicantName" => !empty($masterData->title_of_connection) ? $masterData->title_of_connection : '',
                "fatherName" => !empty($masterData->father_name) ? $masterData->father_name : '',
                "motherName" => !empty($masterData->mother_name) ? $masterData->mother_name : '',
                "gender" => !empty($masterData->gender) ? explode('@', $masterData->gender)[1] : '',
                "dateOfBirth" => !empty($masterData->applicant_dob) ? Carbon::parse($masterData->applicant_dob)->format('d-m-Y') : '',
                'phoneNo' => !empty($masterData->mobile) ? $masterData->mobile : '',
                'applicantPhoto' => !empty($masterData->validate_field_photo) ? $hosturl . $masterData->validate_field_photo : '',
                'applicantCategory' => !empty($masterData->application_type) ? explode('@', $masterData->application_type)[1] : '',
                'houseNumber' => !empty($masterData->house_dag_no) ? $masterData->house_dag_no : '',
                'plotNumber' => !empty($masterData->plot_number) ? $masterData->plot_number : '',
                'roadNumber' => !empty($masterData->av_lane_number) ? $masterData->av_lane_number : '',
                'blockNumber' => !empty($masterData->block_number) ? $masterData->block_number : '',
                'thanaId' => !empty($masterData->thana) ? explode('@', $masterData->thana)[0] : '',
                'section' => !empty($masterData->section) ? $masterData->section : '',
                'areaId' => !empty($masterData->area) ? explode('@', $masterData->area)[0] : '',
                'sndId' => !empty($masterData->snd) ? explode('@', $masterData->snd)[0] : '',
                'wiringInspectorId' => !empty($masterData->wiring_inspector) ? explode('@', $masterData->wiring_inspector)[0] : '',
                'postOffice' => !empty($masterData->post_office) ? $masterData->post_office : '',
                'landOwnerName' => !empty($masterData->land_owner_name) ? $masterData->land_owner_name : '',
                'landOwnerFatherName' => !empty($masterData->land_owner_father_name) ? $masterData->land_owner_father_name : '',
                'termsOfService' => !empty($masterData->accept_terms) ? "true" : "false",
                'email' => !empty($masterData->email) ? $masterData->email : '',
                'authorizedPerson' => !empty($masterData->authorized_person) ? $masterData->authorized_person : '',
                'organizationName' => !empty($masterData->organization_name) ? $masterData->organization_name : '',
                'organizationType' => !empty($masterData->organization_type) ? explode('@', $masterData->organization_type)[0] : '',
                'ministryId' => !empty($masterData->ministry) ? explode('@', $masterData->ministry)[0] : '',
            ];

            $paramMeterData = [
                "connectionMeterId" => $statusid == 2 ? $appData->desco_meter_app_id : "",
                "meterConnectionType" => !empty($masterData->application_type) ? explode('@', $masterData->application_type)[1] : '',
                'organizationType' => !empty($masterData->organization_type) ? explode('@', $masterData->organization_type)[0] : '',
                "eTin" => !empty($masterData->etin) ? $masterData->etin : '',
                "bin" => !empty($masterData->bin) ? $masterData->bin : '',
                "tradeLicenseNo" => !empty($masterData->trade_license) ? $masterData->trade_license : '',
                "connectionType" => !empty($masterData->conn_type) ? explode('@', $masterData->conn_type)[0] : '',
                "load" => !empty($masterData->load) ? $masterData->load : '',
                "phase" => !empty($masterData->phase) ? explode('@', $masterData->phase)[0] : '',
                "volt" => !empty($masterData->voltage) ? explode('@', $masterData->voltage)[0] : '',
                "tariffCategory" => !empty($masterData->tariff_category) ? explode('@', $masterData->tariff_category)[0] : '',
                "tariff" => !empty($masterData->tariff) ? explode('@', $masterData->tariff)[0] : '',
                "tariffSubCategory" => !empty($masterData->tariff_subcategory) ? explode('@', $masterData->tariff_subcategory)[0] : '',
                "specialClass" => !empty($masterData->special_class) ? explode('@', $masterData->special_class)[0] : '',
                'applicantSignature' => !empty($masterData->validate_field_signature) ? $hosturl . $masterData->validate_field_signature : '',
            ];

        }


        $nescoRequest->ref_id = $appData->id;
        if ($statusid == 2) {
            $nescoRequest->type = 'Resubmission';
        } else {
            $nescoRequest->type = 'Submission';
        }

        $nescoRequest->status = 0;   // 10 = payment not submitted
        $nescoRequest->request_form_json = json_encode($paramAppdata);
        $nescoRequest->form_submission_status = 0;
        $nescoRequest->request_meter_info_json = json_encode($paramMeterData);
        $nescoRequest->meter_info_submission_status = 10;
        $nescoRequest->document_submission_status = 10;
        //dd($dpdcRequest->request_json);
        $nescoRequest->save();
        // Submission Request Data ends
    }


    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function additionalpayment(Request $request)
    {

        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionDESCO::find($appId);

        if (!$appInfo) {
            Session::flash('error', "Application not found [DESCO-1101]");
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
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [DESCO-1123]");
            return redirect()->back()->withInput();
        }

        $stackholderMappingInfo = [];

        $DemandPaymentInfo = DescoDemandPaymentInfo::where('ref_id', $appId)->first();

        if (!$DemandPaymentInfo) {
            Session::flash('error', "Payment response not found [desco-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($DemandPaymentInfo->response_material_fee);

//
        if ($paymentResponse->data == null) {
            Session::flash('error', "Payment data not found not found [dpdc-1101]");
            return redirect()->to('/dashboard');
        }

        $materialInfoJson = json_decode($DemandPaymentInfo->response_material_fee);
        $securityDepositJson = json_decode($DemandPaymentInfo->response_security_fee);
        $descoPaymentdataAccount = json_decode($DemandPaymentInfo->response_bank);

        foreach ($descoPaymentdataAccount->data as $value) {
            if ($value->accountPayCategory == "Miscellaneous") {
                $mateiralPaymentAccount = $value->accountNo;

            }
            if ($value->accountPayCategory == "VAT") {
                $vatAccount = $value->accountNo;

            }

            if ($value->accountPayCategory == "SecurityDeposit") {
                $depositAccount = $value->accountNo;
                $depositAccount = '0002634313655';
            }
        }
        $materialAmount = 0;
        if ($materialInfoJson->data->paymentStatus != 'PAID') {
            $materialAmount = $materialAmount + $materialInfoJson->data->paymentAmount;
            $vatAmount = ($materialInfoJson->data->vatAmount + $securityDepositJson->data->vatAmount);
            $paymentdataVat = array(
                'receiver_account_no' => $vatAccount,
                'amount' => $vatAmount
            );
            $stackholderMappingInfo[] = $paymentdataVat;
        }


        if ($securityDepositJson->data->paymentStatus != 'PAID') {
            $depositAmount = $securityDepositJson->data->paymentAmount;
            $paymentdataDeposit = array(
                'receiver_account_no' => $depositAccount,
                'amount' => $depositAmount
            );
            $stackholderMappingInfo[] = $paymentdataDeposit;
        }
        if ($materialAmount != 0) {
            $paymentDataMaterial = array(
                'receiver_account_no' => $mateiralPaymentAccount,
                'amount' => $materialAmount
            );

            $stackholderMappingInfo[] = $paymentDataMaterial;
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
    }

    public function checkDemandPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $dpdcPaymentInfo = DescoDemandPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $status = intval($dpdcPaymentInfo->bank_status);

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
        return view("NewConnectionDESCO::view-demand", compact('appInfo'));
    }

    /*nid verification start*/

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
//        dd($postData);
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

        $verify_response = json_decode($response);
        $responseStatus = !empty($verify_response->data->data->status) ? $verify_response->data->data->status : 'Failed';
        if ($responseStatus == 'OK') {
            $responseCode = 1;
            $data = [
                'name' => $verify_response->data->data->success->data->nameEn,
                'nid' => $verify_response->data->data->success->data->nationalId,
                'father' => $verify_response->data->data->success->data->father,
                'mother' => $verify_response->data->data->success->data->mother,
            ];
        } else {
            $responseCode = -1;
            $data = [];
        }
        return response()->json(['responseCode' => $responseCode, 'verify_nid' => $data]);
    }

}
