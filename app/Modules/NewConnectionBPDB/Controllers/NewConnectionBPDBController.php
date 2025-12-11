<?php

namespace App\Modules\NewConnectionBPDB\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\NewConnectionBPDB\Models\BPDBPaymentConfirm;
use App\Modules\NewConnectionBPDB\Models\BPDBPaymentInfo;
use App\Modules\NewConnectionBPDB\Models\DemandPaymentInfo;
use App\Modules\NewConnectionBPDB\Models\DynamicAttachmentBPDB;
use App\Modules\NewConnectionBPDB\Models\RequestQueueBPDB;
use App\Modules\NewConnectionBPDB\Models\ResubmissionRequestQueueBPDB;
use App\Modules\NewConnectionBPDB\Models\BPDBApplicationStatus;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Modules\NewConnectionBPDB\Models\NewConnectionBPDB;
use Illuminate\Support\Facades\View;
use Log;
use DB;

class NewConnectionBPDBController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 109;
        $this->aclName = 'NewConectionBPDB';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionBPDB-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionBPDB-971]</h4>"]);
        }
        try {


            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $descriptionLoad = [
                '1@Rectifier' => 'Rectifier',
                '2@Washing Machine' => 'Washing Machine',
                '3@Grinder' => 'Grinder',
                '4@Base Station' => 'Base Station',
                '5@Auto Charger' => 'Auto Charger',
                '6@Water Heater' => 'Water Heater',
            ];
            $token = $this->getBPDBToken();
            $bpdb_service_url = Config('stackholder.BPDB_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionBPDB::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'bpdb_service_url', 'descriptionLoad')));

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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionBPDB-971]</h4>"]);
        }

        $company_id = Auth::user()->company_ids;
        if ($request->get('actionBtn') != 'draft') {
            $rules = [
                //                's' => 'required'
            ];

            $messages = [];

            $this->validate($request, $rules, $messages);
        }

        //        if (!ACL::getAccsessRight('NewConnectionBPDB', '-A-')) {
        //            abort('400', 'You have no access right! Contact with system admin for more information.');
        //        }
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $requestData = $request->all();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionBPDB::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionBPDB();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            //            $appData->other_necessary_info = $request->get('other_necessary_info');
            $data = json_encode($request->all());
            $appData->appdata = $data;
            $appData->save();
            if (!empty($request->photo)) {
                $prefix ='BEZA_VR_' . $company_id;
                $appData->applicant_pic = CommonFunction::base64Imagepath($request->get('company_logo_base64'),$prefix,'');
            }else{
                $appData->applicant_pic = $request->get('applicant_pic');
            }




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


            //            $doc_row = Attachment::where('process_type_id', $this->process_type_id)->where('is_archive', 0)->get(['id', 'doc_name']);

            ///Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = DynamicAttachmentBPDB::firstOrNew([
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


            // Generate Tracking No for Submitted application
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                $trackingPrefix = 'BPDB-' . date("dMY") . '-';
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);


            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {

                if( $this->BPDBRequestToJsonResubmit($request, $appData->id, $tracking_no, $appData->bpdb_tracking_no) == false){
                    Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
                    return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
                }
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                dd($this->SubmmisionjSon($appData->id, $tracking_no));
                if( $this->SubmmisionjSon($appData->id, $tracking_no) == false){
                    Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
                    return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
                }
            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {

                $paymentInfo = BPDBPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->app_fee_status = 0;
                $paymentInfo->app_account_status = 0;
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


            \Illuminate\Support\Facades\DB::commit();


            if ($request->get('actionBtn') == "draft") {
                return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('new-connection-bpdb/check-payment/' . Encryption::encodeId($appData->id));
        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    //    Store End

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BPDB-1002]';
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

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [BPDB-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('bpdb_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;
            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);
            $descriptionLoad = [
                '1@Rectifier' => 'Rectifier',
                '2@Washing Machine' => 'Washing Machine',
                '3@Grinder' => 'Grinder',
                '4@Base Station' => 'Base Station',
                '5@Auto Charger' => 'Auto Charger',
                '6@Water Heater' => 'Water Heater',
            ];
            $token = $this->getBPDBToken();
            $bpdb_service_url = Config('stackholder.BPDB_SERVICE_API_URL');

            $public_html = strval(view("NewConnectionBPDB::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'bpdb_service_url', 'descriptionLoad', 'shortfallarr')));
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
        return view("NewConnectionBPDB::waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        $appData = NewConnectionBPDB::find($app_id);
        $demanddata = DemandPaymentInfo::where('ref_id', $app_id)->first();
        if (count($demanddata) == 0) {
            $demanddata = new DemandPaymentInfo();
            $demanddata->bpdb_tracking_no = $appData->bpdb_tracking_no;
            $demanddata->ref_id = $appData->id;
            $demanddata->status = 0;
            $demanddata->save();
        }
        return view("NewConnectionBPDB::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $bpdbPaymentInfo = BPDBPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $bank_account_status = intval($bpdbPaymentInfo->app_account_status);
        $app_fee_status = intval($bpdbPaymentInfo->app_fee_status);
        $paymentData = json_decode($bpdbPaymentInfo->app_fee_json);
        $applyPaymentfee = $paymentData->data->FEE;
        $ServicepaymentData = ApiStackholderMapping:: where(['stackholder_id' => 7])->first(['amount']);

        if ($app_fee_status == 1 && $bank_account_status == 1) {
            $status = 1;
        } elseif ($app_fee_status == 0 || $bank_account_status == 0) {
            $status = 0;
        } elseif ($app_fee_status == -1 || $bank_account_status == -1) {
            $status = -1;
        } else {
            $status = -3;
        }
        if ($status == 1) {
            $paymentInfo = view(
                "NewConnectionBPDB::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($bpdbPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function checkDemandPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $bpdbPaymentInfo = DemandPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $status = intval($bpdbPaymentInfo->status);

        if ($bpdbPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($bpdbPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }
    }

    public function BPDBPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));

        $appInfo = NewConnectionBPDB::find($appId);


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
            Session::flash('error', "Payment configuration not found [BPDB-1123]");
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

        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);

        $bpdbPaymentInfo = BPDBPaymentInfo::where('ref_id', $appId)->first();

        $account_data = json_decode($bpdbPaymentInfo->app_fee_account_json);
        $appFeeAccount = $account_data->result->ACCOUNT_NO;

        $amount_data = json_decode($bpdbPaymentInfo->app_fee_json);
        $appFeeAmount = $amount_data->data->FEE;

        $BpdbFeeInfo = array(
            'receiver_account_no' => $appFeeAccount,
            'amount' => $appFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $BpdbFeeInfo;
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

        NewConnectionBPDB::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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

        \Illuminate\Support\Facades\DB::commit();
        /*
        * Payment Submission
       */
        if ($request->get('actionBtn') == 'Payment' && $paymentInsert) {
            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
        }
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
            if ($paymentInfo->payment_category_id == 3) { // application fee submit
                $processData->status_id = 1;
                $processData->desk_id = 0;
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                NewConnectionBPDB::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            } elseif ($paymentInfo->payment_category_id == 2) { //demand fee
                $processData->status_id = 81;
            }
            $processData->save();

            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            $appData = NewConnectionBPDB::where('id', $processData->ref_id)->first();

            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee

                $bpdbRequest = RequestQueueBPDB::where('ref_id', $appData->id)->first();
                $bpdbRequest->status = 0;
                $bpdbRequest->save();

                foreach ($data2 as $value) {
                    $singleResponse = json_decode($value->verification_response);
                        $rData0['account_info'][] = [
                            'account_no' => $singleResponse->TranAccount,
                            'particulars' => $singleResponse->ReferenceNo,
                            'balance' => 0,
                            'deposit' => $singleResponse->TranAmount,
                            'tran_date' => $singleResponse->TransactionDate,
                            'tran_id' => $singleResponse->TransactionId,
                            'scrl_no' => !empty($singleResponse->SCRL_NO) ? $singleResponse->SCRL_NO : null
                        ];

                }
                $bpdbPaymentConfirm = new BPDBPaymentConfirm();
                $bpdbPaymentConfirm->request = json_encode($rData0);
                $bpdbPaymentConfirm->ref_id = $paymentInfo->app_id;
                $bpdbPaymentConfirm->tracking_no = $processData->tracking_no;
                $bpdbPaymentConfirm->pay_pur_code = 1;
                $bpdbPaymentConfirm->is_demand = 0;
                $bpdbPaymentConfirm->status = 10;
                $bpdbPaymentConfirm->save();
            } else if ($paymentInfo->payment_category_id == 2) { // type 2 for demand fee payment
                $demandInfo = DemandPaymentInfo::where('ref_id', $appData->id)->first(['response']);
                $demandFeeResponse = json_decode($demandInfo->response);

                $paymentserial = 0;
                foreach ($data2 as $key => $value) {
                    $singleResponse = json_decode($value->verification_response);
                    $rData0['account_info'][] = [
                        'account_no' => $singleResponse->TranAccount,
                        'particulars' => $singleResponse->ReferenceNo,
                        'balance' => 0,
                        'deposit' => $singleResponse->TranAmount,
                        'tran_date' => $singleResponse->TransactionDate,
                        'tran_id' => $singleResponse->TransactionId,
                        'scrl_no' => !empty($singleResponse->SCRL_NO) ? $singleResponse->SCRL_NO : null,
                        'payPurCode' => $demandFeeResponse->data[$paymentserial]->PAY_PUR_CODE,
                        'invoiceNumber' => $demandFeeResponse->data[$paymentserial]->INVOICE_NUM
                    ];
                    $paymentserial++;
                }

                $bpdbPaymentConfirm = new BPDBPaymentConfirm();
                $bpdbPaymentConfirm->request = json_encode($rData0);
                $bpdbPaymentConfirm->ref_id = $paymentInfo->app_id;
                $bpdbPaymentConfirm->tracking_no = $processData->tracking_no;
                $bpdbPaymentConfirm->is_demand = 1;
                $bpdbPaymentConfirm->status = 0;
                $bpdbPaymentConfirm->save();

                NewConnectionBPDB::where('id', $paymentInfo->app_id)->update(['demand_status' => 2]);
            }
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            //            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
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

                    NewConnectionBPDB::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    //form 1 and from 2 json generate
                    //                    $this->DOERequestToJson($processData->ref_id);

                } elseif ($paymentInfo->payment_category_id == 2) { //demand fee
                    $processData->status_id = 81;
                    NewConnectionBPDB::where('id', $processData->ref_id)->update(['demand_status' => 1]);
                }
                $processData->process_desc = 'Counter Payment Confirm';

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $verification_response = json_decode($paymentInfo->verification_response);

                $rData0['file_no'] = 133;
                $rData0['reg_no'] = 33;
                $rData0['branch_code'] = $verification_response->BrCode;
                //dd($data2);
                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                foreach ($data2 as  $value) {
                    $singleResponse = json_decode($value->verification_response);
                        $rData0['account_info'][] = [
                            'account_no' => $singleResponse->TranAccount,
                            'particulars' => $singleResponse->ReferenceNo,
                            'balance' => 0,
                            'deposit' => $singleResponse->TranAmount,
                            'tran_date' => $singleResponse->TransactionDate,
                            'tran_id' => $singleResponse->TransactionId,
                            'scrl_no' => !empty($singleResponse->SCRL_NO) ? $singleResponse->SCRL_NO : null
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
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();


                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('new-connection-bpdb/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function applicationView($appId, Request $request)
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

        try {
            $decodedAppId = Encryption::decodeId($appId);
            //dd($decodedAppId);
            $process_type_id = $this->process_type_id;
            //$companyIds = CommonFunction::getUserCompanyWithZero();

            // get application,process info

            $appInfo = ProcessList::leftJoin('bpdb_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            //            dd($appData);
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


            $token = $this->getBPDBToken();
            $bpdb_service_url = Config('stackholder.BPDB_SERVICE_API_URL');

            $public_html = strval(view(
                "NewConnectionBPDB::application-form-view",
                compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'demand_view', 'token', 'bpdb_service_url', 'spPaymentinformation')
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
    public function getBPDBToken()
    {
        // Get credentials from database
        $bpdb_idp_url = Config('stackholder.BPDB_TOKEN_API_URL');
        $bpdb_client_id = Config('stackholder.BPDB_SERVICE_CLIENT_ID');
        $bpdb_client_secret = Config('stackholder.BPDB_SERVICE_CLIENT_ SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $bpdb_client_id,
            'client_secret' => $bpdb_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$bpdb_idp_url");
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
        $bpdb_service_url = Config('stackholder.BPDB_SERVICE_API_URL');
        $conType = $request->connectionType;
        $phaseType = $request->phaseType;
        $categoryId = $request->categoryId;
        $app_id = $request->appId;

        // Get token for API authorization
        $token = $this->getBPDBToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $bpdb_service_url . "/document",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n    \"CONNECTION_TYPE\": \"$conType\",\n    \"PHASE_TYPE\": \"$phaseType\",\n    \"CATEGORY\": \"$categoryId\"\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data'];

                $clr_document = DynamicAttachmentBPDB::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }

                $html = view(
                    "NewConnectionBPDB::dynamic-document",
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

    public function uploadDocument()
    {
        return View::make('NewConnectionBPDB::ajaxUploadFile');
    }


    public function SubmmisionjSon($app_id, $tracking_no)
    {
        // Submission Request Data

        $bpdbRequest = RequestQueueBPDB::firstOrNew([
            'ref_id' => $app_id
        ]);
        $appData = NewConnectionBPDB::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);

        $clientID = $this->getClientId();
        if($clientID == false){
            return false;
        }

        $submissionData = [];
        $submissionData['clientId'] = $clientID;
        $submissionData['ossTrackingNo'] = $tracking_no;
        $submissionData['ossAgentName'] = config('stackholder.oss_agent_name');
        $submissionData['personalInfo']['applicationType'] = $masterData->application_type;
        $submissionData['personalInfo']['organizationName'] = $masterData->organization_name;
        $submissionData['personalInfo']['ApplicantConName'] = $masterData->connection_name;
        $submissionData['personalInfo']['nameEn'] = $masterData->applicant_name_english;
        $submissionData['personalInfo']['nameBn'] = $masterData->applicant_name_bangla;
        $submissionData['personalInfo']['designation'] = $masterData->authorized_person_designation;
        $submissionData['personalInfo']['ApplicantFName'] = $masterData->father_name;
        $submissionData['personalInfo']['ApplicantMName'] = $masterData->mother_name;
        $submissionData['personalInfo']['ApplicantSpouse'] = $masterData->applicant_spouse_name;
        $submissionData['personalInfo']['mobile'] = $masterData->applicant_mobile_no;
        $submissionData['personalInfo']['nid'] = $masterData->nation_id;
        $submissionData['personalInfo']['passport'] = $masterData->applicant_passport_no;
        $submissionData['personalInfo']['gender'] = $masterData->sex;
        $submissionData['personalInfo']['dob'] = date('d/m/Y', strtotime($masterData->date_of_birth));


        if (isset($masterData->validate_field_signature) && !empty($masterData->validate_field_signature)) {
            $signatureFilePath = env('PROJECT_ROOT') . '/uploads/' . $masterData->validate_field_signature;
            // $signatureData = file_get_contents($signatureFilePath);
            // $signatureBase64 = base64_encode($signatureData);
            $submissionData['personalInfo']['signature'] = $signatureFilePath;
            $path_info = pathinfo($signatureFilePath);
            $submissionData['personalInfo']['signType'] = $path_info['extension'];
        }


        if (isset($masterData->validate_field_photo) && !empty($masterData->validate_field_photo)) {
            $photoFilePath = env('PROJECT_ROOT') . '/uploads/' . $masterData->validate_field_photo;
            // $photoData = file_get_contents($photoFilePath);
            // $photoBase64 = base64_encode($photoData);
            $submissionData['personalInfo']['photo'] = $photoFilePath;
            $path_info = pathinfo($photoFilePath);
            $submissionData['personalInfo']['photoType'] = $path_info['extension'];
        }


        $submissionData['mailingAddress']['plot'] = $masterData->house_no;
        $submissionData['mailingAddress']['section'] = $masterData->union;
        $district = explode("@", $masterData->district);
        $submissionData['mailingAddress']['district'] = $district[0];
        $thana = explode("@", $masterData->thana);
        $submissionData['mailingAddress']['thana'] = $thana[0];
        $submissionData['mailingAddress']['road'] = $masterData->lane_no;
        $submissionData['mailingAddress']['block'] = $masterData->block;
        $submissionData['mailingAddress']['postcode'] = $masterData->post_code;
        $submissionData['mailingAddress']['email'] = $masterData->email;

        $submissionData['connectionAddress']['dagNo'] = $thana[0];
        $submissionData['connectionAddress']['laneNo'] = $masterData->connection_lane_no;
        $submissionData['connectionAddress']['section'] = $masterData->connection_union;
        $submissionData['connectionAddress']['block'] = $masterData->connection_block;
        $submissionData['connectionAddress']['postCode'] = $masterData->connection_post_code;
        $bpdbzone = explode("@", $masterData->bpdb_zone);
        $submissionData['connectionAddress']['bpdbZone'] = $bpdbzone[0];
        $esu = explode("@", $masterData->esu);
        $connection_area = explode("@", $masterData->connection_area);
        $submissionData['connectionAddress']['esu'] = $connection_area[2];
        $submissionData['connectionAddress']['connectionArea'] = $connection_area[0];
        $submissionData['connectionAddress']['conMobile'] = $masterData->connection_mobile_no;
        $connectionDistrict = explode("@", $masterData->connection_district);
        $submissionData['connectionAddress']['district'] = $connectionDistrict[0];
        $connectionThana = explode("@", $masterData->connection_thana);
        $submissionData['connectionAddress']['thana'] = $connectionThana[0];


        $submissionData['permanentAddress']['permHouseNo'] = $masterData->permanet_house_no;
        $submissionData['permanentAddress']['permRoad'] = $masterData->lane_no;
        $submissionData['permanentAddress']['permSection'] = $masterData->permanet_union;
        $submissionData['permanentAddress']['permBlock'] = $masterData->permanet_block;
        $permanetDistrict = explode("@", $masterData->permanet_district);
        $submissionData['permanentAddress']['permDistrict'] = $permanetDistrict[0];
        $permanetThana = explode("@", $masterData->permanet_thana);
        $submissionData['permanentAddress']['permThana'] = $permanetThana[0];
        $submissionData['permanentAddress']['permPost'] = $masterData->permanet_post_code;
        $submissionData['permanentAddress']['permEmail'] = $masterData->permanet_email;


        // multiple Data
        if (isset($masterData->description_of_load) && !empty($masterData->description_of_load)) {
            foreach ($masterData->description_of_load as $key => $value) {
                $description = explode("@", $masterData->description_of_load[$key]);
                $submissionData['connectionDetails']['connections'][$key]['loadItemCode'] = $description[0];
                $submissionData['connectionDetails']['connections'][$key]['loadPerItemInWatt'] = $masterData->load_per_item[$key];
                $submissionData['connectionDetails']['connections'][$key]['noOfItem'] = $masterData->no_of_item[$key];
                $submissionData['connectionDetails']['connections'][$key]['totalLoadInWatt'] = $masterData->total_load[$key];
            }
        }
        //doen

        $submissionData['connectionDetails']['conType']['connectionType'] = $masterData->connectionType;
        $category = explode("@", $masterData->category);
        $submissionData['connectionDetails']['conType']['category'] = $category[0];
        $phase = explode("@", $masterData->phase);
        $submissionData['connectionDetails']['conType']['phase'] = $phase[0];
        $submissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';
        $submissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';


        $attachments = DynamicAttachmentBPDB::where('process_type_id', $this->process_type_id)->where('ref_id', $appData->id)->get();

        if (count($attachments) > 0) {
            foreach ($attachments as $key => $attachment) {
                if (!empty($attachment->doc_path)) {
                    $attachmentFilePath = env('PROJECT_ROOT') . '/uploads/' . $attachment->doc_path;
                    // $attachmentData = file_get_contents($attachmentFilePath);
                    // $attachmentBase64 = base64_encode($attachmentData);
                    $submissionData['connectionDetails']['attachments'][$key]['stringDoc'] = $attachmentFilePath;
                    $path_info = pathinfo($attachmentFilePath);
                    $submissionData['connectionDetails']['attachments'][$key]['docType'] = $path_info['extension'];
                    $submissionData['connectionDetails']['attachments'][$key]['fileName'] = $attachment->doc_name;
                    $submissionData['connectionDetails']['attachments'][$key]['docCode'] = $attachment->doc_id;
                    $submissionData['connectionDetails']['attachments'][$key]['conTypeCode'] = $masterData->connectionType;
                    $submissionData['connectionDetails']['attachments'][$key]['tarrif'] = $category[0];
                    $submissionData['connectionDetails']['attachments'][$key]['phaseTypeCode'] = $phase[0];
                }
            }
        }
        $bpdbRequest->ref_id = $appData->id;
        $bpdbRequest->type = 'Submission';
        $bpdbRequest->status = 10;   // 10 = payment not submitted
        $bpdbRequest->request_json = json_encode($submissionData);
        //dd($bpdbRequest->request_json);
        $bpdbRequest->save();


        // Submission Request Data ends
    }


    public function getClientId()
    {
        $bpdb_api_url = Config('stackholder.BPDB_SERVICE_API_URL');
        $token = $this->getBPDBToken();
        dd($token);

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
        dd($decoded_response);
        if(isset($decoded_response['data'])){
            $result = $decoded_response['data'];
        }else{
            return false;
        }

        $clientid = (isset($result['data'][0]['TOKEN']) ? $result['data'][0]['TOKEN'] : '');
        return $clientid;
    }

    public function getRefreshToken()
    {
        $token = $this->getBPDBToken();
        return response($token);
    }

    public function additionalpayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionBPDB::find($appId);
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

        $bpdbDemandPaymentInfo = DemandPaymentInfo::where('ref_id', $appId)->first();
        if (!$bpdbDemandPaymentInfo) {
            Session::flash('error', "Payment response not found [BPDB-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($bpdbDemandPaymentInfo->response);

        if ($paymentResponse->data == null) {
            Session::flash('error', "Payment data not found not found [BPDB-1101]");
            return redirect()->to('/dashboard');
        }
        foreach ($paymentResponse->data as $bpdbdemandfeedata) {
            $account_no = $bpdbdemandfeedata->ACCOUNT_NO;
            $paymentdata = array(
                'receiver_account_no' => $account_no,
                'amount' => $bpdbdemandfeedata->INVOICE_AMT,
                'distribution_type' => $stackholderDistibutionType,
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

    public function BPDBRequestToJsonResubmit($request, $appDataId, $ossTrackingNo, $bpdb_traking_num)
    {
        // Re-Submission Request Data

        $bpdbResubmissionRequest = new ResubmissionRequestQueueBPDB();
        $clientID = $this->getClientId();
        if($clientID == false){
            Session::flash('error', "Something Wrong! Please contact with System-admin [BPDB-1120]");
            return redirect()->back()->withInput();
        }

        $reSubmissionData = [];
        $reSubmissionData['clientId'] =$clientID;
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

    public function demandView($app_id)
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
}
