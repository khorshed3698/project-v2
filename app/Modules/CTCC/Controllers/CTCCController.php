<?php

namespace App\Modules\CTCC\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\CTCC\Models\CTCC;
use App\Modules\CTCC\Models\CTCCPaymentConfirm;
use App\Modules\CTCC\Models\CTCCPaymentInfo;
use App\Modules\CTCC\Models\DynamicAttachmentCTCC;
use App\Modules\CTCC\Models\RequestQueueCTCC;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class CTCCController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 125;
        $this->aclName = 'CTCC';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function appForm(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CTCC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CCC-57]</h4>"]);
        }
        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $service_url = Config('stackholder.CTCC_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->back()->withInput();
            }

            $ceoNationality = '';
            if (!empty($basicAppInfo->ceo_country_id)){
                $ceoNationality = Countries::where('id',$basicAppInfo->ceo_country_id)->value('nationality');
            }

            $divisions = ['' => 'Select One'] + AreaInfo::select(DB::raw('CONCAT(area_id, "@", area_nm) AS area'), 'area_nm', 'area_type')->where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area')->all();
            $public_html = strval(view("CTCC::application-form", compact('process_type_id', 'viewMode','payment_config', 'mode', 'document', 'token', 'service_url', 'divisions','basicAppInfo','ceoNationality')));

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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CCC-105]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = CTCC::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new CTCC();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
            //dd($data);
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
                    $app_doc = DynamicAttachmentCTCC::firstOrNew([
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
                        $trackingPrefix = 'CCCTL-' . date("dMY") . '-';
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
                $this->SubmissionJson($appData->id, $tracking_no, $processData->status_id, $request->ip());
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {
                $paymentInfo = CTCCPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                // $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->app_fee_status = 10; // application not yet submitted
                $paymentInfo->app_account_status = 10; //application not yet submitted
                $paymentInfo->save();
            }

            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 1,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [CTCC-1123]");
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
                // Get SBL payment configuration
                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = $tracking_no;
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no . "-01";
                $paymentInfo->pay_amount = $pay_amount;
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInsert = $paymentInfo->save();
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
//                if (env('server_type') != 'local') {

                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }



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

            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (Exception $e) {
            //dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    public function getToken()
    {
        // Get credentials from database
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        return CommonFunction::getToken($idp_url,$client_id,$client_secret);
    }
    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CTCC-1002]';
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [CTCC-973]</h4>"
            ]);
        }

        try {

            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('ctcc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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


            $token = $this->getToken();
            $service_url = Config('stackholder.CTCC_SERVICE_API_URL');
            $divisions = ['' => 'Select One'] + AreaInfo::select(DB::raw('CONCAT(area_id, "@", area_nm) AS area'), 'area_nm', 'area_type')->where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area')->all();

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->back()->withInput();
            }
            $public_html = strval(view("CTCC::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'payment_config','viewMode', 'mode', 'token', 'service_url', 'divisions')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('CTCCViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CTCC-1015]');
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

        $appInfo = ProcessList::leftJoin('ctcc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                'vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                'transaction_charge_amount as sfp_transaction_charge_amount',
                'vat_on_pay_amount as sfp_vat_on_pay_amount',
                'total_amount as sfp_total_amount',
                'payment_status as sfp_payment_status',
                'pay_mode as pay_mode',
                'pay_mode_code as pay_mode_code',
                'ref_tran_date_time'
            ]);


        $token = $this->getToken();
        $tl_dscc_service_url = Config('stackholder.TL_DSCC_SERVICE_API_URL');

        $public_html = strval(view(
            "CTCC::application-form-view",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'tl_dscc_service_url', 'spPaymentinformation')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

//    Store End



    public function waitForPayment($applicationId)
    {
        //dd('ok');
        return view("CTCC::waiting-for-payment", compact('applicationId','paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $paymentInfoData = CTCCPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $appData= CTCC::find($application_id);
        $decodedResponse = json_decode($appData->appdata);
        $app_account_status = intval($paymentInfoData->app_account_status);

        if ($app_account_status == 1) {
            $applyPaymentfee = $decodedResponse->total_price;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "CTCC::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
            $status = 1;
        } elseif ($app_account_status == 0) {
            $status = 0;
        } elseif ($app_account_status == -1) {
            $status = -1;
        } elseif ($app_account_status == 10) {
            $status = 0;
        } else {
            $status = -3;
        }
        //   dd($status);

        if ($paymentInfoData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 0, 'message' => 'Connecting to CTCC server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => -1, 'message' => 'Waiting for response from CCC']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function ctccPayment(Request $request)
    {
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = CTCC::find($appId);
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appData = json_decode($appInfo->appdata);
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first();
        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 1,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

        $stackholderMappingInfo = [];
        $paymentData = CTCCPaymentInfo::where('ref_id', $appId)->first();
        $paymentData = json_decode($paymentData->app_fee_account_json);

        $appFeeAccount = $paymentData->data[0]->account_no;
        $appFeeAmount = $appData->total_price;
        $appFeePaymentInfo = array(
            'receiver_account_no' => $appFeeAccount,
            'amount' => $appFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );
        $stackholderMappingInfo[] = $appFeePaymentInfo;


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
        CTCC::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
        $sl = 1;
        StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();

        foreach ($stackholderMappingInfo as $data) {
//            $paymentDetails = StackholderSonaliPaymentDetails::firstOrNew([
//                'payment_id' => $paymentInfo->id,'distribution_type'=> $data['distribution_type']
//            ]);
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

            if ($paymentInfo->payment_category_id == 1) { // service fee
                $processData->status_id = 1;
                $processData->desk_id = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                CTCC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueCTCC::where('ref_id',$processData->ref_id)->update(['status'=>0]);
            } else if ($paymentInfo->payment_category_id == 2) {// Govt fee
                CTCC::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
                $data1 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                foreach ($data1 as $data2){
                    $totalAmount = 0;
                    $singleResponse = json_decode($data2->verification_response);
                    $totalAmount = $totalAmount + $singleResponse->TranAmount;
                }

                $paymentArray = array(
                    'refno' => $processData->tracking_no,
                    'pstatus' => 'Y',
                    'amt' => (string) $totalAmount
                );
                $cccPaymentConfirm = new CTCCPaymentConfirm();

                $cccPaymentConfirm->request = json_encode($paymentArray);
                $cccPaymentConfirm->ref_id = $paymentInfo->app_id;
                $cccPaymentConfirm->oss_tracking_no = $processData->tracking_no;
                $cccPaymentConfirm->save();
                $processData->status_id = 31;
            }

            $processData->save();

            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
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
            DB::beginTransaction();
            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                if ($paymentInfo->payment_category_id == 1) { //service fee
                    $processData->status_id = 1;
                    $processData->desk_id = 0;

                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                    CTCC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                } else if ($paymentInfo->payment_category_id == 2) { //Govt. fee
                    $processData->read_status = 0;

                    $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                    $processData->status_id = $general_submission_process_data['process_starting_status'];
                    $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                    $processData->process_desc = 'Counter Payment Confirm';
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date


                    CTCC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    $paymentInfo->payment_status = 1;
                    $paymentInfo->save();

                    // Application status_id for email queue
                    $appInfo['status_id'] = $processData->status_id;

                    // application submission mail sending
                    CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                    $data1 = StackholderSonaliPaymentDetails::where('payment_id', $payment_id)->where('distribution_type', $stackholderDistibutionType)->get();
                    foreach ($data1 as $data2) {
                        $totalAmount = 0;
                        $singleResponse = json_decode($data2->verification_response);
                        $totalAmount = $totalAmount + $singleResponse->TranAmount;
                    }
                    $paymentArray = array(
                        'refno' => $processData->tracking_no,
                        'pstatus' => 'Y',
                        'amt' => (string)$totalAmount
                    );
                    $cccPaymentConfirm = new CTCCPaymentConfirm();

                    $cccPaymentConfirm->request = json_encode($paymentArray);
                    $cccPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $cccPaymentConfirm->oss_tracking_no = $processData->tracking_no;
                    $cccPaymentConfirm->save();

                }
            }
            /*
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
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        }
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
            NewConnectionDESCO::where('id', $request->get('ref_id'))->update(['is_submit_shortfall' => 1]);
        } /* End file uploading */
        // dd($request->all());
        return redirect()->back();
    }


    public function getDynamicDoc(Request $request)
    {

        $tl_dscc_service_url = Config('stackholder.CTCC_SERVICE_API_URL');
        $type = $request->type;
        $app_id = $request->appId;


        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $tl_dscc_service_url . "/info/attachments",
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

        $decoded_response = json_decode($response, true);

        //dd($decoded_response);
        $html = '';

        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data'];

                $clr_document = DynamicAttachmentCTCC::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                //dd($clr_document);
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                }
                $html = view("CTCC::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id')
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
        $res = DynamicAttachmentCTCC::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public function uploadDocument()
    {
        return View::make('CTCC::ajaxUploadFile');
    }


    public function SubmissionJson($app_id, $tracking_no, $statusid, $ip_address)
    {
        // Submission Request Data
        if ($statusid == 2) {
            $tlcccRequest = new RequestQueueCTCC();
        } else {
            $tlcccRequest = RequestQueueCTCC::firstOrNew([
                'ref_id' => $app_id
            ]);
        }
        $appData = CTCC::where('id', $app_id)->first();
        if ($tlcccRequest->status == 0 || $tlcccRequest->status == 10) {
            $masterData = json_decode($appData->appdata);
            //dd($masterData);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';

            $dynamicDocument = $masterData->dynamicDocumentsId;
            foreach ($dynamicDocument as $value) {
                $id = explode('@', $value)[0];
                $filedname = 'validate_field_' . $id;
                $path = $hosturl . $masterData->$filedname;
                $hiddenDocumentIDs[] = array('hiddenDocumentID' => $id);
                $customerExtraFiles[] = array('customer_extra_files' => $path);
            }
            $capitalAmount = 0;
            $paidCapital = !empty($masterData->paid_capital) ?explode('@', $masterData->paid_capital)[2] : '';
            if ($paidCapital != ''){
                $paidCapitalValue = explode('-',$paidCapital);
                if ($paidCapitalValue[1] == 'INFINITY'){
                    $capitalAmount = $paidCapitalValue[0];
                }else{
                    $capitalAmount = $paidCapitalValue[1];
                }
            }

            $paramAppdata = [
                "NameOfBusiness" => !empty($masterData->business_org_name) ? $masterData->business_org_name : '',
                "natureofbusiness" => !empty($masterData->business_org_nature) ? explode('@', $masterData->business_org_nature)[0] : '',
                "Paidupcapital" =>$capitalAmount,
                "ApplicantsName" => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
                "areadescription" => 'Test',
                "FathersName" => !empty($masterData->applicant_fathers_name) ? $masterData->applicant_fathers_name : '',
                "Spouce_Name" => !empty($masterData->spouse_name) ? $masterData->spouse_name : '',
                "Relationship_with_business" => !empty($masterData->applicant_relation_org) ? $masterData->applicant_relation_org : '',
                "MothersName" => !empty($masterData->applicant_mothers_name) ? $masterData->applicant_mothers_name : '',
                "Village_permanent" => !empty($masterData->permanent_village_or_mahalla) ? $masterData->permanent_village_or_mahalla : '',
                "po_permanent" => !empty($masterData->permanent_post_code) ? $masterData->permanent_post_code : '',
                "ps_permanent" => !empty($masterData->permanent_police_station) ? explode('@', $masterData->permanent_police_station)[1] : '',
                "dist_permanent" => !empty($masterData->permanent_district) ? explode('@', $masterData->permanent_district)[1] : '',
                "holdingno_permanent" => !empty($masterData->permanent_holding_no) ? $masterData->permanent_holding_no : '',
                "road_permanent" => !empty($masterData->permanent_road_no) ? $masterData->permanent_road_no : '',
                "section_permanent" => !empty($masterData->permanent_division) ? explode('@', $masterData->permanent_division)[1] : '',
                "applicantslocaladdress" => !empty($masterData->residential_address) ? $masterData->residential_address : '',
                "Village_present" => !empty($masterData->current_village_or_mahalla) ? $masterData->current_village_or_mahalla : '',
                "po_present" => !empty($masterData->current_post_code) ? $masterData->current_post_code : '',
                "ps_present" => !empty($masterData->police_station) ? explode('@', $masterData->police_station)[1] : '',
                "dist_present" => !empty($masterData->current_district) ? explode('@', $masterData->current_district)[1] : '',
                "holdingno_present" => !empty($masterData->current_holding_no) ? $masterData->current_holding_no : '',
                "road_present" => !empty($masterData->current_road_no) ? $masterData->current_road_no : '',
                "section_present" => !empty($masterData->current_division) ? explode('@', $masterData->current_division)[1] : '',
                "applicantsbusinessaddress" => !empty($masterData->proposed_business_address) ? $masterData->proposed_business_address : '',
                "nationalidentificationno" => !empty($masterData->nid_number) ? $masterData->nid_number : '',
                "nationality" => !empty($masterData->nationality) ? $masterData->nationality : '',
                "passport" => !empty($masterData->passport) ? $masterData->passport : '',
                "birthregno" => !empty($masterData->birth_reg_no) ? $masterData->birth_reg_no : '',
                "binno" => !empty($masterData->bin_no) ? $masterData->bin_no : '',
                "businesscommencementdate" => !empty($masterData->business_start_date) ? Carbon::parse($masterData->business_start_date)->format('d-M-Y') : '',//--------------------------------XXXXXXXXXXXXX
                "businesscapital" => !empty($masterData->business_capital) ? $masterData->business_capital : '',
                "tinno" => !empty($masterData->tin_number) ? $masterData->tin_number : '',
                "businessplaceownorrent" => !empty($masterData->place_of_business) ? explode('@', $masterData->place_of_business)[1] : '',
                "taxrashidyesno" => !empty($masterData->business_shop_rent) ? explode('@', $masterData->business_shop_rent)[0] : '',
                "signboardexists" => !empty($masterData->sign_board) ? explode('@', $masterData->sign_board)[0] : '',
                "landpurchasedorgovt" => !empty($masterData->proposed_place) ? explode('@', $masterData->proposed_place)[0] : '',
                "shopfloor" => !empty($masterData->shop_floor) ? explode('@', $masterData->shop_floor)[0] : '',
                "mobileno" => !empty($masterData->mobile_no) ? $masterData->mobile_no : '',
                "emailid" => !empty($masterData->email) ? $masterData->email : '',
                "anyotheridcard" => !empty($masterData->other_identity) ? $masterData->other_identity : '',
                "bida_area" => !empty($masterData->area_or_block) ? explode('@', $masterData->area_or_block)[0] : '',
                "bida_road" => !empty($masterData->road) ? explode('@',$masterData->road)[0] : '',
                "bida_sector" => !empty($masterData->sector_or_section) ? explode('@',$masterData->sector_or_section)[0] : '',
                "bida_ward" => !empty($masterData->ward) ? explode('@',$masterData->ward)[0] : '',
                "bida_zone" => !empty($masterData->zone) ? explode('@',$masterData->zone)[0] : '',
                "ossTrackingNo" =>  $tracking_no,
                "plot_holdingno" => !empty($masterData->plot_or_holding_no) ? $masterData->plot_or_holding_no : '',
                "shopno" => !empty($masterData->shop_no) ? $masterData->shop_no : '',
                "licensefee" => !empty($masterData->license_fee) ? $masterData->license_fee : '',
                "signboardarea" => !empty($masterData->sign_board_sqft) ? $masterData->sign_board_sqft : '',
                "bida_area_description" => !empty($masterData->zone) ? 'Zone-'.(explode('@', $masterData->zone)[1] . ','
                        . 'Ward-'.explode('@', $masterData->ward)[1]
//                    . ',Sector-'. (isset($masterData->sector_or_section)?explode('@',$masterData->sector_or_section)[1]:'')
//                    .',Area-'.(isset($masterData->area_or_block)?explode('@', $masterData->area_or_block)[1]:'')
//                    .',Road-'.!empty($masterData->road)?explode('@', $masterData->road)[1]:''
                    ): '',//--------------------------------XXXXXXXXXXXXX
            ];
            //dd($paramAppdata);
        }else{
            return redirect('ctcc/check-payment/' . Encryption::encodeId($appData->id));
        }

        $tlcccRequest->ref_id = $appData->id;
        $tlcccRequest->tracking_no = $tracking_no;
        $tlcccRequest->status = 10;   // 10 = payment not submitted
        $tlcccRequest->request_json = json_encode($paramAppdata);
        //dd($dpdcRequest->request_json);
        $tlcccRequest->save();

        // Submission Request Data ends
    }


    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }
    public function TestBase64(){
        $url = 'http://dev-bida.eserve.org.bd/uploads/2021/09/TRADE_LICENSE_DSCC_OPN_613484fce4b761.56033689.pdf';
        $image = file_get_contents($url);
        if ($image !== false){
            return base64_encode($image);
        }


    }

    public function CCCtermsCondition($applicationId)
    {
        try {
            $app_Id = Encryption::decodeId($applicationId);
            $cccInfo = CTCC::where('id',$app_Id)->value('certificate_link');
            $contentsData = file_get_contents($cccInfo);
            $contents = view("CTCC::ccc-certificate")->render();
            $contentdata=str_replace('</form>', $contents , $contentsData);

            return view("CTCC::ccc-termsConditions",compact('contentdata'));

        } catch (\Exception $e) {
                dd($e->getMessage() .$e->getLine());
               Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPC-114]');
              return redirect()->back()->withInput();
        }
    }

}
