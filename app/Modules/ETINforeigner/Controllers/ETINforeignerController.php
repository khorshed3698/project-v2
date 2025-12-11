<?php

namespace App\Modules\ETINforeigner\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\ETINforeigner\Models\ETINforeignRequestQueue;
use App\Modules\ETINforeigner\Models\DynamicAttachmentETINforeigner;
use App\Modules\ETINforeigner\Models\ETINforeigner;
use App\Modules\ETINforeigner\Models\ETINforeignerPaymentInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use App\Modules\VATReg\Models\VATReg;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ETINforeignerController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 127;
        $this->aclName = 'eTINforeigner';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [e-TIN-foreigner-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [e-TIN-foreigner-971]</h4>"]);
        }
        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id')->all();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $service_url = Config('stackholder.NBR_TIN_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $mainSourceIncome =[
                '1'=>'Service',
                '2'=>'Profession',
                '3'=>'Business (Individual/Firm)',
                '4'=>'Other',
            ];
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->back()->withInput();
            }
            $public_html = strval(view("ETINforeigner::application-form", compact('process_type_id', 'token','viewMode', 'mode', 'document','service_url' ,'payment_config','countries','mainSourceIncome')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CTC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CTC-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [e-TIN-foreigner-96]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            if ($request->get('searchWPNinfo') == 'searchWPNinfo') {
                if ($request->get('is_approval_online') == 'yes' && $request->has('ref_app_tracking_no')) {
                    $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));

                    $getWpApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
                        ->where('status_id', 25)->where('company_id', $company_id)
                        ->whereIn('process_type_id', [2, 3]) //2 = Work Permit New, 3 = Work Permit Extension
                        ->first(['ref_id','tracking_no']);

                    if (empty($getWpApprovedRefId)) {
                        Session::flash('error', 'Sorry! approved work permit reference no. is not found! [WPAC-111]');
                        return redirect()->back();
                    }

                    //Get data from WPCommonPool
                    $wpInfo = UtilFunction::checkWpCommonPoolData($getWpApprovedRefId->tracking_no, $getWpApprovedRefId->ref_id);

                    if (empty($wpInfo)) {
                        Session::flash('error', 'Sorry! Work permit reference number not found by tracking no! [WPA-1081]');
                        return redirect()->back();
                    }

                    Session::put('wpneInfo', $wpInfo->toArray());
                    Session::put('wpneInfo.is_approval_online', $request->get('is_approval_online'));
                    Session::put('wpneInfo.ref_app_tracking_no', $refAppTrackingNo);
                    Session::flash('success', 'Successfully loaded work permit data. Please proceed to next step');
                    return redirect()->back();
                }
            }

            // Clean session data
            if ($request->get('actionBtn') == 'clean_load_data') {
                Session::forget("wpneInfo");
                Session::flash('success', 'Successfully cleaned data.');
                return redirect()->back();
            }
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = ETINforeigner::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new ETINforeigner();
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
                    $app_doc = DynamicAttachmentETINforeigner::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }
//            } /* End file uploading */




            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'NBRF-' . date("dMY") . '-';
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
            if ($request->get('actionBtn') != "draft") {
                $this->SubmissionJson($appData->id, $tracking_no, $processData->status_id);

            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 3,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [ETIN-1123]");
                    return redirect()->back()->withInput();
                }
                $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                    ->where('is_active', 1)
                    ->where('process_type_id', $this->process_type_id)
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

                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no."-01" ;
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
                if (env('server_type') != 'local') {

                        if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                        }

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

            return redirect('licence-applications/e-tin-foreigner/list/' . Encryption::encodeId($processData->process_type_id));

        } catch
        (Exception $e) {
            //dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }
    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [E-TIN-1002]';
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
            $appInfo = ProcessList::leftJoin('etin_foreigner_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->back()->withInput();
            }


            $appData = json_decode($appInfo->appdata);
//            dd($appData);
            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $token = $this->getToken();
            $service_url = Config('stackholder.NBR_TIN_SERVICE_API_URL');

            $public_html = strval(view("ETINforeigner::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'service_url','payment_config')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('E-TINViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CTCC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[CTCC-1015]" . "</h4>"
            ]);
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [CTCC-974]</h4>"
            ]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        //dd($decodedAppId);
        $process_type_id = $this->process_type_id;
        //$companyIds = CommonFunction::getUserCompanyWithZero();

        // get application,process info

        $appInfo = ProcessList::leftJoin('etin_foreigner_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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

        $alreadySubmitted = 0;
        $submissionJsonQueue = ETINforeignRequestQueue::where('ref_id',$decodedAppId)->where('status',1)->first();
        if ($submissionJsonQueue) {
            $alreadySubmitted = 1;
        }

        $appData = json_decode($appInfo->appdata);
        //            dd($appData);
//        $dynamic_shortfall = DynamicShortfallAttachmentDPDC::where('ref_id', $appInfo->ref_id)->get();
        // dd($dynamic_shortfall);
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
        $service_url = Config('stackholder.NBR_TIN_SERVICE_API_URL');

        $public_html = strval(view(
            "ETINforeigner::application-form-view",
            compact('appInfo', 'appData', 'process_type_id','alreadySubmitted','viewMode', 'mode', 'token', 'service_url', 'spPaymentinformation')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function sendEmailToUser(){

        $appData = ETINforeigner::where('user_info_status',0)->get();

        if(count($appData)>0){
            foreach ($appData as $appInfo){
                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('ref_id', $appInfo->id)
                    ->where('process_type_id', $this->process_type_id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_type.form_id',
                        'process_list.*'
                    ]);

                //get users email and phone no according to working company id
                $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

                $mailData = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_type_name' => $processData->process_type_name,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_sub_name' => $processData->process_sub_name,
                    'username' => $appInfo->nbr_user_id,
                    'password' => $appInfo->nbr_user_pasword,
                    'remarks' => ""
                ];
                CommonFunction::sendEmailSMS('APP_STAKEHOLDER_NOTIFICATION', $mailData, $applicantEmailPhone);
                $appInfo->user_info_status = 1;
                $appInfo->save();
            }
        }else{
            echo "No data Found";
        }


    }
    public function reGenerateSubmissionJson($id){
        $appId = Encryption::decodeId($id);
        $processInfo = ProcessList::where('process_type_id',$this->process_type_id)->where('ref_id',$appId)->first();
        $queueData = ETINforeignRequestQueue::where('ref_id',$appId)->where('status',1)->first();
        if ($queueData){
            Session::flash('error', 'Sorry! Submission Json already generated!');
            return redirect()->back();
        }else{
            $this->submissionJson($appId,$processInfo->tracking_no,-1,1);
            Session::flash('success', 'Successfully Generated');
            return redirect()->back();
        }
    }
    public function SubmissionJson($app_id, $tracking_no, $statusid,$isRegen =0)
    {
        // Submission Request Data
        $tldsccRequest = ETINforeignRequestQueue::firstOrNew([
            'ref_id' => $app_id
        ]);
        if ($tldsccRequest->status !=1) {
            $appData = ETINforeigner::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            $documentInfo = [];

            $doc_item = DB::table('dynamic_attachment_etin_foreigner')->where('ref_id', $app_id)->get();
            $appDoc =[];
            foreach ($doc_item as $key => $data) {
                if(!empty($data->doc_path)){
                    $documentInfo['uploadTypeNo'] = $data->doc_id;
                    $documentInfo['attachedFile'] = config('stackholder.file_base64_api_url').$data->doc_path;
                    $appDoc []=$documentInfo;
                }

            }

            $other= null;

            if(!empty($masterData->other_country)){
            $other = [
                "AddrTypeNo"=>2,
                "Addr"=>!empty($masterData->address_line1_o) ? $masterData->address_line1_o : '',
                "Addr1"=>!empty($masterData->address_line2_o) ? $masterData->address_line2_o : '',
                "CountNo"=>!empty($masterData->other_country) ? (int)explode('@', $masterData->other_country)[0] :'',
                "DistNo"=>!empty($masterData->other_district) ? explode('@', $masterData->other_district)[0] :'',
                "UpzaNo"=>'',
                "ThanaNo"=>!empty($masterData->other_thana) ? explode('@', $masterData->other_thana)[0] :'',
                "PostCode"=>!empty($masterData->other_post_code) ? $masterData->other_post_code : '',
                "City"=>'',
                "State"=>!empty($masterData->other_state) ? $masterData->other_state : '',
                "ZipCode"=> '',
            ];
            }else{
                $other= null;
            }

            $paramAppdata = [
                "photo"=> [
                    config('stackholder.file_base64_api_url').$masterData->validate_field_photo,
                    ],
                "attachDocuments" => $appDoc,
                "ossTrackingNo" => $tracking_no,
                "relevantTIN" =>!empty($masterData->company_tin) ? $masterData->company_tin : '',
                "orgID" =>  !empty($masterData->authority_name) ? (int)explode('@', $masterData->authority_name)[0]: '',
                "BOINumber" => !empty($masterData->registration_number) ? $masterData->registration_number : '',
                "BOIDate" =>  !empty($masterData->registration_date)?date('Y/m/d', strtotime($masterData->registration_date)):'',
                "RegTypeMastNo" => !empty($masterData->taxpayer_status) ? (int)explode('@', $masterData->taxpayer_status)[0]: '',
                "RegTypeNo" =>!empty($masterData->taxpayer_status_b) ? (int)explode('@', $masterData->taxpayer_status_b)[0]: '',
                "IsOldTin" => false,
                "RegJuriTypeNo" => !empty($masterData->main_source_income) ? (int)explode('@', $masterData->main_source_income)[0]: '',
                "DistNo" =>!empty($masterData->localtion_main_source_income) ? (int)explode('@', $masterData->localtion_main_source_income)[0]: '',
                "JuriSelectTypeNo" =>  1,
                "JuriSelectListNo" =>  !empty($masterData->juri_select_list_no) ? (int)explode('@', $masterData->juri_select_list_no)[0]: '',
                "JuriSubListName" =>  !empty($masterData->juri_sub_list_name) ?$masterData->juri_sub_list_name: '',
                "JuriSubListNo" => !empty($masterData->juri_sub_list_no) ?(int)explode('@', $masterData->juri_sub_list_no)[0]: '',
                "JuriListName" =>  '',
                "SubListName" =>  '',
                "JuriTypeNo" =>  '',

                "CountryNo" => !empty($masterData->country_id) ? (int)explode('@', $masterData->country_id)[0] : '',
                "AssesName" => !empty($masterData->taxpayer_name) ? $masterData->taxpayer_name : '',
                "Gender" => !empty($masterData->gender) ? (int)explode('@', $masterData->gender)[1] : '',
                "DOB" => !empty($masterData->date_of_birth)?date('Y/m/d', strtotime($masterData->date_of_birth)):'',
                "FatherName" => !empty($masterData->father_name) ? $masterData->father_name : '',
                "MotherName" => !empty($masterData->mother_name) ? $masterData->mother_name : '',
                "PassportNo" => !empty($masterData->passport_number) ? $masterData->passport_number : '',
                "PassportIssueDate" => !empty($masterData->passport_issue_date) ?date('Y/m/d', strtotime($masterData->passport_issue_date)):'',
                "PassportExpiryDate" => !empty($masterData->passport_expiry_date) ?date('Y/m/d', strtotime($masterData->passport_expiry_date)):'',
                "visaNumber" => !empty($masterData->visa_number) ? $masterData->visa_number : '',
                "visaIssueDate" =>!empty($masterData->visa_issue_date)?(date('Y/m/d', strtotime($masterData->visa_issue_date))):'',
//                "IncorpNumber" => '',
//                "IncorpDate" =>  '',
                "DesigNo" =>  '',
                "RelevantName" => '',
                "ContactTelephone" => !empty($masterData->mobile_number) ? $masterData->mobile_number : '',
                "ContactFax" => !empty($masterData->facsimile) ? $masterData->facsimile : '',
                "contactemailaddr" => !empty($masterData->email) ? $masterData->email : '',
                "RjscName" => '',
                "CurrentAddress"=>[
                    "AddrTypeNo"=>1,
                    "Addr"=>!empty($masterData->address_line1_p) ? $masterData->address_line1_p : '',
                    "Addr1"=>!empty($masterData->address_line2_p) ? $masterData->address_line2_p : '',
                    "CountNo"=>!empty($masterData->present_country) ? (int)explode('@', $masterData->present_country)[0] : '',
                    "DistNo"=>!empty($masterData->present_district) ? explode('@', $masterData->present_district)[0] : '',
                    "UpzaNo"=>'',
                    "ThanaNo"=>!empty($masterData->present_thana) ? explode('@', $masterData->present_thana)[0] : '',
                    "PostCode"=>!empty($masterData->present_post_code) ? $masterData->present_post_code : '',
                    "City"=>'',
                    "State"=>!empty($masterData->present_state) ? $masterData->present_state : '',
                    "ZipCode"=> '',
                ] ,
            "PermanentAddress"=>[
                    "AddrTypeNo"=>2,
                    "Addr"=>!empty($masterData->address_line1_per) ? $masterData->address_line1_per : '',
                    "Addr1"=>!empty($masterData->address_line2_per) ? $masterData->address_line2_per : '',
                    "CountNo"=>!empty($masterData->permanent_country) ? (int)explode('@', $masterData->permanent_country)[0] :'',
                    "DistNo"=>!empty($masterData->permanent_district) ? explode('@', $masterData->permanent_district)[0] :'',
                    "UpzaNo"=>'',
                    "ThanaNo"=>!empty($masterData->permanent_thana) ? explode('@', $masterData->permanent_thana)[0] :'',
                    "PostCode"=>!empty($masterData->permanent_post_code) ? $masterData->permanent_post_code : '',
                    "City"=>'',
                    "State"=>!empty($masterData->permanent_state) ? $masterData->permanent_state : '',
                    "ZipCode"=> '',
                ],
                "OtherAddress"=>$other,

            ];
//            dd(json_encode($paramAppdata));
        }
//        else{
//            return redirect('ctcc/check-payment/' . Encryption::encodeId($appData->id));
//        }

        $tldsccRequest->ref_id = $appData->id;
        if($statusid == 2){
            $tldsccRequest->status = 0;
        }else {
            if($isRegen == 1){
                $tldsccRequest->status = 0;   // 10 = payment not submitted
            }else{
                $tldsccRequest->status = 10;   // 10 = payment not submitted
            }
        }
        $tldsccRequest->request_json = json_encode($paramAppdata);
//        dd($dpdcRequest->request_json);
        $tldsccRequest->save();

        // Submission Request Data ends
    }
    public function getToken()
    {
        // Get credentials from database
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        return CommonFunction::getToken($idp_url,$client_id,$client_secret);
    }

    public function getDynamicDoc(Request $request)
    {

        $foreign_tin_service_url = Config('stackholder.NBR_TIN_SERVICE_API_URL');
        $type = $request->type;
        $app_id = $request->appId;


        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $foreign_tin_service_url . "/info/upload-type",
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
                "Content-Type: application/json",
                "agent-id: 3"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        //dd($decoded_response);
        $html = '';

        if ($decoded_response['responseCode'] == 200) if ($decoded_response['data'] != '') {
            $attachment_list = $decoded_response['data'];
            $clr_document = DynamicAttachmentETINforeigner::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
//            dd($clr_document);
                $clrDocuments = [];

            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_id]['upload_id'] = $documents->doc_id;
                $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
            }
            $html = view("ETINforeigner::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id')
            )->render();
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }
    public function uploadDocument()
    {
        return View::make('ETINforeigner::ajaxUploadFile');
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

            $processData->status_id = 1;
            $processData->desk_id = 0;

            ETINforeigner::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            $processData->save();

            ETINforeignRequestQueue::where('ref_id', $processData->ref_id)->update([
                'status' => 0
            ]);

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/e-tin-foreigner/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('e-tin-foreigner/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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


                ETINforeigner::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                ETINforeignRequestQueue::where('ref_id', $paymentInfo->app_id)->update([
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
            return redirect('e-tin-foreigner/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('e-tin-foreigner/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function deleteDynamicDoc(Request $request)
    {
        $process_type_id = $request->process_type_id;
        $ref_id = $request->ref_id;
        $doc_id = $request->doc_id;
        $res = DynamicAttachmentETINforeigner::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

}
