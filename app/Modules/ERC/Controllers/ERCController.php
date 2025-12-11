<?php

namespace App\Modules\ERC\Controllers;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocumentStakeholder;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\ERC\Models\ERC;
use App\Modules\ERC\Models\ERCChallanConfirm;
use App\Modules\ERC\Models\ERCPaymentDetails;
use App\Modules\ERC\Models\ERCShortfall;
use App\Modules\ERC\Models\ERCShortfallFieldMap;
use App\Modules\ERC\Models\RequestQueueERC;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Collection;

class ERCController extends Controller
{

    public function __construct()
    {
        $this->process_type_id = 128;
        $this->aclName = 'erc';
    }

    public function appForm(Request $request)
    {
        if (!ACL::getAccsessRight('erc', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [ERC-46]');
        }
        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = docInfo::where('process_Type_id', $this->process_type_id)->orderBy('order')->get();
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');
            $tl_issued_by=[
                '4'=>'Cantonment Board',
                '1'=>'City Corporation',
                '2'=>'Pouroshova',
                '3'=>'Union Parisod',
            ];

            $getBasicInfo = ProcessList::where('status_id', 25)
                ->where('company_id', $companyIds)
                ->first(['ref_id', 'tracking_no','completed_date']);

            $basicInfoDetials = BasicInformation::leftJoin('area_info as office_division', 'office_division.area_id', '=', 'ea_apps.office_division_id')
                ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'ea_apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'ea_apps.office_thana_id')
                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
                ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'ea_apps.ceo_district_id')
                ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'ea_apps.ceo_thana_id')
                ->leftJoin('sector_info_bbs', 'sector_info_bbs.id', '=', 'ea_apps.business_sector_id')
                ->where('ea_apps.id', $getBasicInfo->ref_id)
                ->first([
                    'ea_apps.*',
                    'office_division.area_nm as office_division_name',
                    'office_district.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',
                    'factory_district.area_nm as factory_district_name',
                    'factory_thana.area_nm as factory_thana_name',
                    'ceo_district.area_nm as ceo_district_name',
                    'ceo_thana.area_nm as ceo_thana_name',
                    'sector_info_bbs.name as sector_info_bbs_name',
                ]);


            Session::put('ercInfo', $basicInfoDetials->toArray());
            Session::put('trackNum', $getBasicInfo->tracking_no);

            $viewMode = 'off';
            $mode = '-A-';
            $agent = config('stackholder.bida-agent-id');
            $public_html = strval(view("ERC::application-form", compact('viewMode', 'mode', 'document', 'token', 'ccie_service_url','agent','tl_issued_by')));
            Session::forget("ercInfo");
            Session::forget("trackNum");
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        $company_id = Auth::user()->company_ids;
        if ($request->get('actionBtn') != 'draft') {
            $messages = [];
            $this->validate($request, $messages);
        }

        if (!ACL::getAccsessRight('erc', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.[ERC-113]');
        }

        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = ERC::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new ERC();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }
            $newJson = json_encode($data);
            //dd($newJson);
            $appData->appdata = $newJson;
            $appData->save();

            if ($request->get('actionBtn') == "draft" && $appData->status_id != 10) {
                $processData->desk_id = 0;
                $processData->status_id = -1;
            } else {
                if ($processData->status_id == 5) { //resubmit
                    $processData->status_id = 10;
                    $processData->desk_id = 0;
                    $processData->process_desc = 'Re-submitted form applicant';

                } else {

                    $processData->status_id = -1;
                    $processData->desk_id = 0;
                }

            }


            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;

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
                    $app_doc = AppDocumentStakeholder::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_code' => $doc_id
                    ]);
                    $app_doc->doc_code = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            } /* End file uploading */
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
                    $trackingPrefix = "BIDA-ERC$servertype-" . date("dMY") . '-';

                    DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                }
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $this->SubmmisionjSon($appData->id, $tracking_no);
            }

            $erc_slab = explode('@', $request->erc_slab)[0];

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {
                $paymentInfo = ERCPaymentDetails::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $tracking_no;
                $paymentInfo->district_id = $erc_slab;
                $paymentInfo->stakeholder_payment_stage = 0;
                $paymentInfo->save();
            }

            DB::commit();
            //  dd($request->get('actionBtn'));
            if ($request->get('actionBtn') == "draft") {
                return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 10) {
                return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('licence-applications/erc/check-payment/' . Encryption::encodeId($appData->id));


        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [CCIE-1025]");
            return redirect()->back()->withInput();
        }

    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
//         dd('ssssssss');
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
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [BPDB-973]</h4>"
            ]);
        }
        try {

            $document = docInfo::where('process_Type_id', $this->process_type_id)->orderBy('order')->get();
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            $appInfo = ProcessList::leftJoin('erc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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

            $tl_issued_by=[
                '4'=>'Cantonment Board',
                '1'=>'City Corporation',
                '2'=>'Pouroshova',
                '3'=>'Union Parisod',

            ];


            /// dd($appInfo);
            $appData = json_decode($appInfo->appdata);
            //dd($appData);
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');

            $agent = config('stackholder.bida-agent-id');

            $public_html = strval(view("ERC::application-form-edit", compact('document', 'appInfo', 'appData', 'viewMode', 'mode', 'token', 'ccie_service_url', 'appId','agent','tl_issued_by')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [BRC-974]</h4>"
            ]);
        }

        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)->where('ref_id', $decodedAppId)->get();

            $appInfo = ProcessList::leftJoin('erc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
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
                    'ps.status_name',
                    'apps.*',
                    'process_type.max_processing_day',
                ]);
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

            $is_shortfall = 0;

            $shortfalldata = ERCShortfall::where('ref_id', $appInfo->ref_id)->where('status', 1)->first();
            if ($shortfalldata) {
                $is_shortfall = 1;
            }
            $tl_issued_by=[
                '4'=>'Cantonment Board',
                '1'=>'City Corporation',
                '2'=>'Pouroshova',
                '3'=>'Union Parisod',
            ];

            /// dd($appInfo);
            $appData = json_decode($appInfo->appdata);
            //dd($appData);
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');

            $resubmittedData = ERCShortfall::where('ref_id', $decodedAppId)->where('is_submit', 1)->first();
            if ($resubmittedData){
                $shortfallData = json_decode($resubmittedData->response);
                $resubmittedData = json_decode($resubmittedData->shortfall_submission_request);
            }
            $public_html = strval(view("ERC::application-form-view", compact('document', 'appInfo','tl_issued_by', 'appData', 'viewMode', 'mode', 'token', 'ccie_service_url', 'appId', 'spPaymentinformation', 'is_shortfall','resubmittedData','shortfallData')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('BRViewForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BPDB-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[BPDB-1015]" . "</h4>"
            ]);
        }
    }

    public function appGetData($appId)
    {
        $decodedAppId = Encryption::decodeId($appId);
        $application = ERC::where('id', $decodedAppId)->first();
        $applicationData = json_decode($application->appdata);
        //dd($appData);
        return response()->json($applicationData);

    }

    public function showShortfallForm($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $data = ERCShortfall::where('ref_id', $app_id)->where('status', 1)->first();
        $field_map = ERCShortfallFieldMap::where('field_flag', 'select')->get()->toArray();
        $fieldFlagDP = ERCShortfallFieldMap::where('field_flag', 'datepicker')->get(['name','field_flag'])->toArray();
        $fieldFlagImg = ERCShortfallFieldMap::where('field_flag', 'image')->get(['name','field_flag'])->toArray();
//        dd($fieldFlagDP);
        $field_map_array= [];
        foreach ($field_map as $key=>$value){
            $field_map_array[$value['api_code']]=$value['name'];
        }
        $dp_field_map_array= [];
        foreach ($fieldFlagDP as $key=>$value){
            $dp_field_map_array[$value['name']]=$value['field_flag'];
        }

        $img_field_map_array= [];
        foreach ($fieldFlagImg as $key=>$value){
            $img_field_map_array[$value['name']]=$value['field_flag'];
        }
//        dd($field_map_array);
        $is_submit_shortfall = ERC::where('id', $app_id)->first(['is_submit_shortfall']);

        $app_info = ERCShortfall::leftJoin('erc_apps', 'erc_apps.id', '=', 'erc_shortfall.ref_id')
            ->where('erc_shortfall.ref_id', $app_id)
            ->first();
        $app_data = json_decode($app_info->appdata);

        $check_previous_shortfall =  ERC::where('id', $app_id)->value('resubmit_json');
//        dd(json_decode($check_previous_shortfall));
        if (!empty($check_previous_shortfall)){
            $prevData = json_decode($check_previous_shortfall);
            $app_data->division = $prevData->division;
            $app_data->district = $prevData->district;
            $app_data->bank_name = $prevData->bank_name;
        }
        $shortfallData = json_decode($data->response);

        $process_type_id = $this->process_type_id;
        $token = $this->getCCIEToken();
        $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');
        $agent = 2;
        return view('ERC::shortfall-form', compact('shortfallData', 'is_submit_shortfall', 'data','process_type_id', 'field_map_array','dp_field_map_array','img_field_map_array', 'token', 'ccie_service_url', 'agent', 'app_data'));

    }

    public function waitForPayment($applicationId)
    {
        return view("ERC::waiting-for-payment", compact('applicationId' ));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);
        $ercPaymentInfo = ERCPaymentDetails::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $paymentData = json_decode($ercPaymentInfo->payment_info_response);
        $status = intval($ercPaymentInfo->stakeholder_payment_stage);
        if ($status == 1) {
            $applyPaymentFeeTotal = ($paymentData->data->data[0]->primary_reg_fee+$paymentData->data->data[0]->registration_book);
            $vatAmount = ($applyPaymentFeeTotal * intval($paymentData->data->data[0]->vat)) / 100;
            $applyPaymentfee =($applyPaymentFeeTotal+$vatAmount);
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "ERC::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($ercPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($ercPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to CCI&E server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($ercPaymentInfo->id), 'status' => -1,  'message' => 'Waiting for response from CCI&E']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($ercPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($ercPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function cciePayment(Request $request)
    {
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = ERC::find($appId);
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
            Session::flash('error', "Payment configuration not found [CCI&E-1123]");
            return redirect()->back()->withInput();
        }
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
            ->where('is_active', 1)
            ->where('process_type_id', $this->process_type_id)
            ->get([
                'receiver_account_no',
                'amount',
                'distribution_type',
            ])->toArray();
        $ercPaymentInfo = ERCPaymentDetails::where('ref_id', $appId)->first();
        $paymentResponse = json_decode($ercPaymentInfo->payment_info_response);
        $ercAccount = $paymentResponse->data->challan_code;
        $ercVatAccount = $paymentResponse->data->vat_code;
        $ercAmount = $paymentResponse->data->data[0]->primary_reg_fee+$paymentResponse->data->data[0]->registration_book;
        $ercVatAmount = ($ercAmount * intval($paymentResponse->data->data[0]->vat)) / 100;


        $ercPaymentInfo = array(
            'receiver_account_no' => $ercAccount,
            'amount' => $ercAmount,
            'distribution_type' => $stackholderDistibutionType,
            'm_category' => "CHL"
        );

        $stackholderMappingInfo[] = $ercPaymentInfo;

        $ercVatInfo = array(
            'receiver_account_no' => $ercVatAccount,
            'amount' => $ercVatAmount,
            'distribution_type' => $stackholderDistibutionType,
            'm_category' => "CHL"
        );

        $stackholderMappingInfo[] = $ercVatInfo;

        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);

        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appInfo->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
        // Get SBL payment configuration
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
        ERC::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
        $sl = 1;
        StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();
        foreach ($stackholderMappingInfo as $data) {

            $paymentDetails = new StackholderSonaliPaymentDetails();
            $paymentDetails->payment_id = $paymentInfo->id;
            if (isset($data['m_category']) && $data['m_category'] == 'CHL') {
                $paymentDetails->purpose_sbl = 'CHL';
            } else {
                $paymentDetails->purpose_sbl = 'TRN';
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
                ERC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueERC::where('ref_id', $processData->ref_id)->update(['status' => 0]);

            }

            $processData->save();

            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            $ercPaymentInfo = ERCPaymentDetails::where('ref_id', $processData->ref_id)->first();
            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                $decodedPaymentInfo = json_decode($ercPaymentInfo->payment_info_response);
                foreach ($data2 as  $value) {
                    $singleResponse = json_decode($value->verification_response,true);
                    if ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->challan_code) {
                        $rData0['challan'][] = [
                            'application_type' => "A",
                            'challan_type' => "registration_fee",
                            'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                            'challan_date' => Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                            'challan_amount' => $singleResponse['TranAmount'],
                            'challan_bank_id' => "39",
                            'challan_branch_id' => "8075",
                            'challan_branch_address' => null,
                        ];
                    } elseif ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->vat_code) {
                        $rData0['challan'][] = [
                            'application_type' => "A",
                            'challan_type' => "vat",
                            'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                            'challan_date' =>  Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                            'challan_amount' => $singleResponse['TranAmount'],
                            'challan_bank_id' => "39",
                            'challan_branch_id' => "8075",
                            'challan_branch_address' => null,
                        ];
                    }

                }
            }


            $echallanJson = json_encode($rData0);
            $ercPaymentInfo->payment_confirm_request = $echallanJson;
            $ercPaymentInfo->stakeholder_payment_stage = 2; //payment confirmation request sent
            $spg_conf =  Configuration::where('caption', 'spg_TransactionDetails_challan')->first();
            $lopt_url = $spg_conf->value;
            $userName = config('payment.spg_settings_stack_holder.user_id');
            $password = config('payment.spg_settings_stack_holder.password');
            $ownerCode = config('payment.spg_settings_stack_holder.st_code');
            $referenceDate = $paymentInfo->payment_date;
            $requiestNo = $paymentInfo->request_id;
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$lopt_url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
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
            $echallanData = [
                'reference_date' => $decodedchallan->ReferenceDate,
                'challan_url' => $decodedchallan->EchalUrl,
                'request_id' => $decodedchallan->TrackingNo,
                'trn_amount' => ($decodedchallan->TranAmount - 250)

            ];
            $postdata2['e_challan_payment_verify_data'] = $echallanData;
            $ercPaymentInfo->payment_challan_request = json_encode($postdata2);
            $ercPaymentInfo->save();
            // application submission mail sending
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    public function afterCounterPayment($payment_id)
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
                if ($paymentInfo->payment_category_id == 3) { //service & govt fee

                    $processData->status_id = 16;
                    $processData->desk_id = 0;
                    $processData->read_status = 0;
                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                    ERC::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    RequestQueueERC::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                }
                $processData->process_desc = 'Counter Payment Confirm';
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
                $processData->save();

                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                $ercPaymentInfo = ERCPaymentDetails::where('ref_id', $processData->ref_id)->first();
                if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                    $decodedPaymentInfo = json_decode($ercPaymentInfo->payment_info_response);
                    foreach ($data2 as  $value) {
                        $singleResponse = json_decode($value->verification_response,true);
                        if ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->challan_code) {
                            $rData0['challan'][] = [
                                'application_type' => "A",
                                'challan_type' => "registration_fee",
                                'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                                'challan_date' => Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                                'challan_amount' => $singleResponse['TranAmount'],
                                'challan_bank_id' => "39",
                                'challan_branch_id' => "8075",
                                'challan_branch_address' => null,
                            ];
                        } elseif ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->vat_code) {
                            $rData0['challan'][] = [
                                'application_type' => "A",
                                'challan_type' => "vat",
                                'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                                'challan_date' =>  Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                                'challan_amount' => $singleResponse['TranAmount'],
                                'challan_bank_id' => "39",
                                'challan_branch_id' => "8075",
                                'challan_branch_address' => null,
                            ];
                        }

                    }
                }


                $echallanJson = json_encode($rData0);
                $ercPaymentInfo->payment_confirm_request = $echallanJson;
                $ercPaymentInfo->stakeholder_payment_stage = 2; //payment confirmation request sent
                $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
                $lopt_url = $spg_conf->value;
                $userName = config('payment.spg_settings_stack_holder.user_id');
                $password = config('payment.spg_settings_stack_holder.password');
                $ownerCode = config('payment.spg_settings_stack_holder.st_code');
                $referenceDate = $paymentInfo->payment_date;
                $requiestNo = $paymentInfo->request_id;
                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$lopt_url",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json"
                    ),
                ));

                $challanResponse = curl_exec($curl);
                $err = curl_error($curl);

                curl_close($curl);
                $account_num = $spg_conf->details;
                $decodedResponse = json_decode($challanResponse);
                $decodedchallan = json_decode($decodedResponse);
                foreach ($decodedchallan as $decodedchallanResponse) {
                    $echallanData[] = [
                        'reference_date' => $decodedchallanResponse->ReferenceDate,
                        'challan_url' => '',
                        'request_id' => $decodedchallanResponse->TrackingNo,
                        'trn_amount' => ($decodedchallanResponse->TranAmount - 250)

                    ];
                }
                $postdata2['application_id'] = $ercPaymentInfo->tracking_no;
                $postdata2['e_challan_payment_verify_data'] = $echallanData;
                $ercPaymentInfo->challan_confirm_request = json_encode($postdata2);
                $ercPaymentInfo->save();

            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            else {
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
            return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    // Get RJSC token for authorization
    public function getCCIEToken()
    {
        // Get credentials from database
        $ccie_idp_url = Config('stackholder.CCIE_TOKEN_API_URL');
        $ccie_client_id = Config('stackholder.CCIE_SERVICE_CLIENT_ID');
        $ccie_client_secret = Config('stackholder.CCIE_SERVICE_CLIENT_ SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $ccie_client_id,
            'client_secret' => $ccie_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$ccie_idp_url");
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

        $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');
        $type = $request->type;
        $app_id = $request->appId;


        // Get token for API authorization
        $token = $this->getCCIEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $ccie_service_url . "erc/ownership-wise-doc/type/".$type."/sub-service-id/55",
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
                "agent-id: ".config('stackholder.bida-agent-id')
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data']['data'];
                $clr_document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_code]['document_id'] = $documents->doc_code;
                    $clrDocuments[$documents->doc_code]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_code]['document_name_en'] = $documents->doc_name;
                }
                $html = view(
                    "ERC::documents",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function getRefreshToken()
    {
        $token = $this->getCCIEToken();
        return response($token);
    }

    public function SubmmisionjSon($app_id, $tracking_no)
    {

        $ccieRequest = RequestQueueERC::firstOrNew([
            'ref_id' => $app_id
        ]);

        $appData = ERC::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);
        $userData = $this->getuserinfo();
        $submissionData = [];

        $base64url = config('stackholder.file_base64_api_url');

        if ($userData != null) {
            $userId = $userData['id'];
        } else {
            Session::flash('error', "User Data not Valid" . ' [CCIE-1001]');
            return redirect()->back();
        }

        $submissionData['user_id'] = $userId;
        $submissionData['sub_service_id'] = "55";
        $submissionData['org_mail'] = $masterData->organization_email;
        $submissionData['org_tin_no'] = $masterData->organization_tin;
        $submissionData['salutation'] = null;
        $submissionData['organization_name_en'] = $masterData->organization_name_en;
        $submissionData['organization_name_bn'] = $masterData->organization_name_bn;
        $submissionData['org_address_bn'] = $masterData->organization_add_bn;
        $submissionData['org_address_en'] = $masterData->organization_add_bn;
        $submissionData['org_phone'] = $masterData->organization_phone;
        $submissionData['org_mobile'] = $masterData->organization_mobile;
        $submissionData['org_fax'] = $masterData->organization_fax;
        $submissionData['contact_person_name'] = $masterData->contact_person_name;
        $submissionData['contact_person_phone'] = $masterData->contact_person_2;
        $submissionData['holding_no'] = $masterData->holding_no;

        $submissionData['division_id'] = explode('@', $masterData->division)[0];
        $submissionData['district_id'] = explode('@', $masterData->district)[0];
        $submissionData['org_ps_id'] = explode('@', $masterData->police_station)[0];
        $submissionData['org_post_code'] = $masterData->organization_post_code;
        $submissionData['ow_type'] = explode('@', $masterData->organization_type)[0];
        $ownerData = [];
        if (isset($masterData->owner_name)) {

            foreach ($masterData->owner_name as $key => $owner_name) {
                $file = '';
                if($masterData->owner_photo[$key]){
                    $image_parts = explode(";base64,", $masterData->owner_photo[$key]);
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);

                    $yFolder = "uploads/" . date("Y");
                    if (!file_exists($yFolder)) {
                        mkdir($yFolder, 0777, true);
                        $myfile = fopen($yFolder . "/index.html", "w");
                        fclose($myfile);
                    }
                    $ym = date("Y") . "/" . date("m") . "/";
                    $ym1 = "uploads/" . date("Y") . "/" . date("m");
                    if (!file_exists($ym1)) {
                        mkdir($ym1, 0777, true);
                        $myfile = fopen($ym1 . "/index.html", "w");
                        fclose($myfile);
                    }

                    $path = "uploads/";
                    $file = $ym. uniqid() . '.'.$image_type;
                }
                file_put_contents($path.$file, $image_base64);
                $ownerData[$key]['ow_photo'] = !empty($file) ? $base64url.$file : null;
                $ownerData[$key]['nationality'] = $masterData->nationality[$key];
                $ownerData[$key]['ow_tin'] = $masterData->owner_tin[$key];
                $ownerData[$key]['ow_nid'] = $masterData->owner_nid_or_passport[$key];
                $ownerData[$key]['ow_name'] = $masterData->owner_name[$key];
                $ownerData[$key]['ow_designation'] = isset($masterData->designation)?explode('@', $masterData->designation[$key])[0]:"";
                $ownerData[$key]['ow_father_name'] = $masterData->owner_father_name[$key];
                $ownerData[$key]['ow_mother_name'] = $masterData->mother_name[$key];
                $ownerData[$key]['ow_office_phone'] = $masterData->phone_number_office[$key];
                $ownerData[$key]['ow_mobile'] = $masterData->mobile[$key];
                $ownerData[$key]['ow_pre_ps_id'] = $masterData->present_address[$key];
                $ownerData[$key]['ow_per_ps_id'] = $masterData->present_address[$key];
                $ownerData[$key]['ow_district_id'] = isset($masterData->district_name)?explode('@', $masterData->district_name[$key])[0]:"";
                $ownerData[$key]['ow_incorporation_number'] = $masterData->incorporation_number[$key];
                $ownerData[$key]['ow_incorporation_date'] = date("F j, Y", strtotime($masterData->incorporation_date[$key])); // Carbon::parse($masterData->incorporation_date[ $key ],'UTC')->isoFormat('MMMM D, YYYY');
                $ownerData[$key]['ow_registration_number'] = $masterData->registration_number[$key];
                $ownerData[$key]['ow_registration_date'] = date("F j, Y", strtotime($masterData->registration_date[$key]));
                $ownerData[$key]['own_passport'] = $masterData->passport_no[$key];
                $ownerData[$key]['own_passport_country'] = $masterData->country[$key];
                $ownerData[$key]['own_passport_validation'] = $masterData->passport_expired_date[$key];
            }
        }

        $submissionData['owners'] = $ownerData;
        $submissionData['share_type'] = !empty($masterData->share_type) ?explode('@', $masterData->share_type)[0]:'';
        $submissionData['foreign_share_percent'] = isset($masterData->foreign_share) ? $masterData->foreign_share : null;
        $submissionData['govt_share_percent'] = isset($masterData->domestic_share) ? $masterData->domestic_share : null;
        $submissionData['bank_id'] = isset($masterData->bank_name) ? explode('@', $masterData->bank_name)[0] : null;
        $submissionData['branch_id'] = isset($masterData->branch_no) ? explode('@', $masterData->branch_no)[0] : null;
        $submissionData['nominated_branch_address'] = isset($masterData->branch_address) ? $masterData->branch_address : null;
        $submissionData['tl_no'] = isset($masterData->trade_license_no) ? $masterData->trade_license_no : null;
        $submissionData['tl_date'] = isset($masterData->trade_license_issued_date) ? date("F j, Y", strtotime($masterData->trade_license_issued_date)) : null;
        $submissionData['tl_expire_date'] = isset($masterData->trade_license_expired_date) ? date("F j, Y", strtotime($masterData->trade_license_expired_date)) : null;
        $submissionData['tl_org'] = null;
        $submissionData['tl_address'] = isset($masterData->trade_license_address) ? $masterData->trade_license_address : null;
        $submissionData['tl_type'] = isset($masterData->business_type) ? $masterData->business_type : null;
        $submissionData['chamber_id'] = isset($masterData->association_name) ? explode('@', $masterData->association_name)[0] : null;
        $submissionData['chamber_type'] = isset($masterData->chamber_category) ? $masterData->chamber_category : null;
        $submissionData['chamber_cert_sl_no'] = isset($masterData->serial_no_chamber) ? $masterData->serial_no_chamber : null;
        $submissionData['chamber_cert_issue_date'] = isset($masterData->certificate_issue_date) ? $masterData->certificate_issue_date : null;
        $submissionData['chamber_office_phone'] = isset($masterData->association_phone) ? $masterData->association_phone : null;
        $submissionData['chamber_address'] = isset($masterData->chamber_address) ? $masterData->chamber_address : null;
        $submissionData['chamber_cert_validity_date'] = isset($masterData->validity_date) ? date("F j, Y", strtotime($masterData->validity_date)) : null;

        $submissionData['fees_id'] = isset($masterData->erc_slab) ? explode('@', $masterData->erc_slab)[0] : null;
        $submissionData['application_type'] = "A";
        $submissionData['draft'] = "0";
        $documentInfo = [];
        $appDoc = [];

        $ref_id = $app_id;
        $doc_item = DB::table('stakeholder_app_documents')->where('process_type_id', $this->process_type_id)->where('ref_id', $ref_id)->get();

        foreach ($doc_item as $key => $data) {
            if(!empty($data->doc_path)){
                $documentInfo['doc_id'] = $data->doc_code;
                $documentInfo['doc_type'] = explode('@', $masterData->organization_type)[0];
                $documentInfo['base64'] = $base64url.$data->doc_path;
                $appDoc []=$documentInfo;
            }
        }
        $submissionData['document'] = $appDoc;
        $submissionData['document_103'] =[(object)[]];
        $submissionData['document_104'] = [(object)[]];
        $submissionData['document_105'] = [(object)[]];
        $submissionData['document_107'] = [(object)[]];
        $submissionData['document_108'] = [(object)[]];
        $submissionData['document_109'] = [(object)[]];
        $submissionData['document_230'] = [(object)[]];

        $ccieRequest->ref_id = $ref_id;
        $ccieRequest->type = 'Submission';
        $ccieRequest->status = 10;
        $ccieRequest->request_json = json_encode($submissionData);
        //dd($bpdbRequest->request_json);
        $ccieRequest->save();

    }


    public function getuserinfo()
    {
        $ccie_idp_url = Config('stackholder.CCIE_SERVICE_API_URL');
        $curl = curl_init();
        $firstname = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name;
        $lastname = Auth::user()->user_last_name;
        $address = Auth::user()->road_no;
        $request_data = json_encode([
            'first_name' => $firstname,
            'last_name' => $lastname,
            'name_bn' => "testname",
            'address' => $address,
            'email' => Auth::user()->user_email,
            'mobile' => Auth::user()->user_phone]);

        $token = $this->getCCIEToken();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $ccie_idp_url . "user-info",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json",
                "agent-id: ".config('stackholder.bida-agent-id')
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);

        $result = $decoded_response['data'];
        return $result;
    }

    public function uploadDocument()
    {
        return View::make('ERC::ajaxUploadFile');
    }

    public function storeShortfall(Request $request)
    {

        $app_id = Encryption::decodeId($request->get('app_id'));
        $data = $request->all();
        $is_submit_shortfall = ERC::where('id', $app_id)->first();
        $appData = json_decode($is_submit_shortfall->appData);
        $resubmitJson = json_decode($is_submit_shortfall->resubmit_json);

        if(empty($is_submit_shortfall->resubmit_json)){
            $arrayData['division'] = isset($data['division_id@org_info@0']) ? $data['division_id@org_info@0'] : $appData->division;
            $arrayData['district'] = isset($data['district_id@org_info@0']) ? $data['district_id@org_info@0'] : $appData->district;
            $arrayData['bank_name'] = isset($data['bank_id@nominated_bank_info@0']) ? $data['bank_id@nominated_bank_info@0'] : $appData->bank_name;
        }else{
            $arrayData['division'] = isset($data['division_id@org_info@0']) ? $data['division_id@org_info@0'] : $resubmitJson->division;
            $arrayData['district'] = isset($data['district_id@org_info@0']) ? $data['district_id@org_info@0'] : $resubmitJson->district;
            $arrayData['bank_name'] = isset($data['bank_id@nominated_bank_info@0']) ? $data['bank_id@nominated_bank_info@0'] : $resubmitJson->bank_name;
        }

        $allinput = [];
        $base64url = config('stackholder.file_base64_api_url');
        DB::beginTransaction();
        foreach ($data as $key => $value) {

            $pos = strpos($key, '@');
            if ($pos == true) {
                $expData = explode('@', $key);
                $name = $expData[0];
                $fieldset = $expData[1];
                $pid = $expData[2];
                if ($name == 'ow_photo'){
                    $filedvalue = $base64url.$request->get($key);
                }else{
                    $filedvalue = $request->get($key);
                }


                $arr = array(
                    'fieldset' => $fieldset,
                    'name' => $name,
                    'pid' => $pid,
                    'value' => $filedvalue
                );
                $allinput["$fieldset"][] = $arr;
            }

        }

        $documents = $request->get('dynamicDocumentsId');
//        dd($documents);

        if(isset($documents)){
            foreach ($documents as $value) {
                $doc = $request->get('validate_field_' . $value);
                $arr = array(
                    'fieldset' => 'doc_info',
                    'pid' => 0,
                    'name' => $value,
                    'value' => $base64url.$doc
                );
                $allinput['doc_info'][] = $arr;
            }
        }


        $shortfallResponseCcie = ERCShortfall::where('ref_id', $app_id)->value('response');
        $decoDed = json_decode($shortfallResponseCcie);
        $decoDed = $decoDed->data;
        $submissionData = [
            'app_resubmit_id' => $decoDed->app_resubmit_id,
            'application_id' => $decoDed->application_id,
            'application_stage_id' => $decoDed->application_stage_id,
            'sub_service_id' => $decoDed->sub_service_id,
            'stage_id' => $decoDed->stage_id,
            'resubmit_data' => $allinput
        ];

        $shortFall = ERCShortfall::firstOrNew(['ref_id' => $app_id]);
        $shortFall->is_submit = 1;
        $shortFall->submission_status = 0;
        $shortFall->shortfall_submission_request = json_encode($submissionData);
        $shortFall->save();




        // new code for feedback
        ERC::where('id', $app_id)->update([
            'resubmit_json' => json_encode($arrayData),
            'is_submit_shortfall' => 1
        ]);
//        dd(123);
        ProcessList::where('process_type_id',$this->process_type_id)->where('ref_id',$app_id)
            ->update(['status_id'=>2]);


// new code for feedback
        DB::commit();
        Session::flash('success', 'Re-submitted successfully');
        return redirect('erc/list/' . Encryption::encodeId($this->process_type_id));

    }

}
