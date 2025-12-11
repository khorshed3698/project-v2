<?php

namespace App\Modules\IndustrialIrc\Controllers;

use App\BRCommonPool;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\BidaRegistration\Models\BidaRegistration;
use App\Modules\IndustrialIrc\Models\CcieApplicationStatus;
use App\Modules\IndustrialIrc\Models\CCIEChallanConfirm;
use App\Modules\IndustrialIrc\Models\CciePaymentConfirm;
use App\Modules\IndustrialIrc\Models\CciePaymentInfo;
use App\Modules\IndustrialIrc\Models\CCIEShortfall;
use App\Modules\IndustrialIrc\Models\CCIEShortfallFieldMap;
use App\Modules\IndustrialIrc\Models\DynamicAttachmentCCIE;
use App\Modules\IndustrialIrc\Models\IndustrialIrc;
use App\Modules\IndustrialIrc\Models\RequestQueueCCIE;
use App\Modules\IrcRecommendationNew\Models\BusinessClass;
use App\Modules\IrcRecommendationNew\Models\IrcInspection;
use App\Modules\IrcRecommendationNew\Models\IrcRecommendationNew;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class IndustrialIrcController extends Controller
{
    public function __construct()
    {
        $this->process_type_id = 113;
        $this->aclName = 'industrialIRC';
    }

    public function appForm(Request $request)
    {

        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = docInfo::where('process_Type_id', $this->process_type_id)->orderBy('order')->get();
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');
            $tl_issued_by = [
                '4' => 'Cantonment Board',
                '1' => 'City Corporation',
                '2' => 'Pouroshova',
                '3' => 'Union Parisod',

            ];
            $viewMode = 'off';
            $mode = '-A-';
            $agent = config('stackholder.bida-agent-id');

            $half_yearly_import_total =  ProcessList::leftJoin('irc_apps', 'process_list.ref_id', '=', 'irc_apps.id')
                ->leftJoin('irc_inspection', function ($join){
                    $join->on('irc_inspection.app_id', '=', 'irc_apps.id')
                        ->where('irc_inspection.ins_approved_status', '=', 1);
                })
                ->where('process_list.process_type_id', '=', 13)
                ->where('process_list.company_id', '=', Auth::user()->working_company_id)
                ->first([
                    'irc_inspection.apc_half_yearly_import_total',
                    'process_list.tracking_no',
                    'process_list.company_id'
                ]);

            $public_html = strval(view("IndustrialIrc::application-form", compact('viewMode', 'mode', 'document', 'token', 'ccie_service_url', 'agent', 'tl_issued_by', 'half_yearly_import_total')));
            Session::forget("ircInfo");
            Session::forget("trackNum");
            Session::forget("subClass");
            Session::forget("ircInspectionInfo");
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            dd('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }


    public function reGenerateSubmissionJson($id){
        $appId = Encryption::decodeId($id);
        $processInfo = ProcessList::where('process_type_id',$this->process_type_id)->where('ref_id',$appId)->first();
        $queueData = RequestQueueCCIE::where('ref_id',$appId)->first();
        if ($queueData){
            Session::flash('error', 'Sorry! Submission Json already generated!');
            return redirect()->back();
        }else{
            $this->submissionJson($appId,$processInfo->tracking_no);
            Session::flash('success', 'Successfully Generated');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        $company_id = Auth::user()->company_ids;

        // get work permit new or extension info & set session
        if ($request->get('searchIRCinfo') == 'searchIRCinfo') {
            if ($request->get('is_approval_online') == 'yes' && $request->has('ref_app_tracking_no')) {
                $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));

                $getIRCApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
                    ->where('status_id', 25)
                    ->where('company_id', $company_id)
                    ->first(['ref_id', 'tracking_no', 'completed_date']);

                if (empty($getIRCApprovedRefId)) {
                    Session::flash('error', 'Sorry! approved IRC reference no. is not found!');
                    return redirect()->back();
                }

                $ircInfo = IrcRecommendationNew::leftJoin('area_info as office_division', 'office_division.area_id', '=', 'irc_apps.office_division_id')
                    ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'irc_apps.office_district_id')
                    ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'irc_apps.office_thana_id')
                    ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'irc_apps.factory_district_id')
                    ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'irc_apps.factory_thana_id')
                    ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'irc_apps.ceo_district_id')
                    ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'irc_apps.ceo_thana_id')
                    ->where('irc_apps.id', $getIRCApprovedRefId->ref_id)
                    ->first([
                        'irc_apps.*',
                        'office_division.area_nm as office_division_name',
                        'office_district.area_nm as office_district_name',
                        'office_thana.area_nm as office_thana_name',
                        'factory_district.area_nm as factory_district_name',
                        'factory_thana.area_nm as factory_thana_name',
                        'ceo_district.area_nm as ceo_district_name',
                        'ceo_thana.area_nm as ceo_thana_name',
                    ]);


                $ircInspectionInfo = IrcInspection::where('app_id', $getIRCApprovedRefId->ref_id)
                    ->leftJoin('bank', 'bank.id', '=', 'irc_inspection.bank_id')
                    ->leftJoin('bank_branches', 'bank_branches.id', '=', 'irc_inspection.branch_id')
                    ->where('irc_inspection.ins_approved_status', 1)
                    ->orderBy('id', 'DESC')
                    ->first(['irc_inspection.*', 'bank.name as bank_name', 'bank_branches.branch_name as branch_name']);
                if (empty($ircInfo)) {
                    Session::flash('error', 'Sorry!IRC reference number not found by tracking no!');
                    return redirect()->back();
                }

                if (empty($ircInspectionInfo)) {
                    Session::flash('error', 'Sorry!IRC inspection information not found');
                    return redirect()->back();
                }
                $sub_class = BusinessClass::select('id', DB::raw("CONCAT(code, ' - ', name) as name"))->where('id', $ircInfo->sub_class_id)->first();


                Session::put('ircInfo', $ircInfo->toArray());
                Session::put('trackNum', $refAppTrackingNo);
                Session::put('completed_date', $getIRCApprovedRefId->completed_date);
                Session::put('ircInspectionInfo', $ircInspectionInfo->toArray());
                if (isset($sub_class)) {
                    Session::put('subClass', $sub_class->toArray());
                }

                Session::put('ircInfo.is_approval_online', $request->get('is_approval_online'));
                Session::put('ircInfo.ref_app_tracking_no', $refAppTrackingNo);
                Session::flash('success', 'Successfully loaded IRC  data.');
                return redirect()->back();
            } else {
                Session::flash('error', 'Sorry! approved IRC reference no. is not found!');
                return redirect()->back();
            }
        }

        // Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("ircInfo");
            Session::forget("trackNum");
            Session::forget("subClass");
            Session::forget("ircInspectionInfo");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }

        if ($request->get('actionBtn') != 'draft') {
            $rules = [
//                'permit_type_id' => 'required',
//                'acceptTerms' => 'required'
            ];

            $messages = [];

            $this->validate($request, $rules, $messages);
        }

        if (!ACL::getAccsessRight('IndustrialIrc', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = IndustrialIrc::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new IndustrialIrc();
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

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            $docIds = $request->get('dynamicDocumentsId');

            ///Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = DynamicAttachmentCCIE::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    if (!empty($request->get('is_uploaded_' . $doc_id))) {
                        $app_doc->is_uploaded = $request->get('is_uploaded_' . $doc_id);
                    } else {
                        $app_doc->is_uploaded = 1;
                    }
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            } /* End file uploading */
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 10 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    $processTypeId = $this->process_type_id;
                    $trackingPrefix = "BIDA-IRC-" . date("dMY") . '-';
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

            $irc_slab = explode('@', $request->irc_slab)[0];

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {
                $paymentInfo = CciePaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->district_id = $irc_slab;
                $paymentInfo->status = 0;
                $paymentInfo->save();
            }
            DB::commit();

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $this->SubmissionJson($appData->id, $tracking_no);
            }
            //  dd($request->get('actionBtn'));
            if ($request->get('actionBtn') == "draft") {
                return redirect('industrial-IRC/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 10) {
                return redirect('industrial-IRC/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('licence-applications/ccie/check-payment/' . Encryption::encodeId($appData->id));

        } catch (\Exception $e) {
            dd('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
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

            $appInfo = ProcessList::leftJoin('cci_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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

            $tl_issued_by = [
                '4' => 'Cantonment Board',
                '1' => 'City Corporation',
                '2' => 'Pouroshova',
                '3' => 'Union Parisod',

            ];


            /// dd($appInfo);
            $appData = json_decode($appInfo->appdata);
            //dd($appData);
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');

            $agent = config('stackholder.bida-agent-id');

            $half_yearly_import_total =  ProcessList::leftJoin('irc_apps', 'process_list.ref_id', '=', 'irc_apps.id')
                ->leftJoin('irc_inspection', function ($join){
                    $join->on('irc_inspection.app_id', '=', 'irc_apps.id')
                        ->where('irc_inspection.ins_approved_status', '=', 1);
                })
                ->where('process_list.process_type_id', '=', 13)
                ->where('process_list.company_id', '=', Auth::user()->working_company_id)
                ->first([
                    'irc_inspection.apc_half_yearly_import_total',
                    'process_list.tracking_no',
                    'process_list.company_id'
                ]);

            $public_html = strval(view("IndustrialIrc::application-form-edit", compact('document', 'appInfo', 'appData', 'viewMode', 'mode', 'token', 'ccie_service_url', 'appId', 'agent', 'tl_issued_by', 'half_yearly_import_total')));
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
            $process_type_id = $this->process_type_id;
            $document = DynamicAttachmentCCIE::where('process_type_id', $this->process_type_id)->where('ref_id', $decodedAppId)->get();

            $appInfo = ProcessList::leftJoin('cci_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'transaction_id',
                    'ref_tran_date_time'
                ]);

            $spg_challan_base_url = Configuration::where('caption', 'spg_challan_base_url')->value('value');
            $is_shortfall = 0;

            $shortfalldata = CCIEShortfall::where('ref_id', $appInfo->ref_id)->where('status', 1)->first();
            if ($shortfalldata) {
                $is_shortfall = 1;
            }
            $tl_issued_by = [
                '4' => 'Cantonment Board',
                '1' => 'City Corporation',
                '2' => 'Pouroshova',
                '3' => 'Union Parisod',
            ];

            /// dd($appInfo);
            $appData = json_decode($appInfo->appdata);
            //dd($appData);
            $token = $this->getCCIEToken();
            $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');

            $resubmittedData = CCIEShortfall::where('ref_id', $decodedAppId)->where('is_submit', 1)->first();
            if ($resubmittedData) {
                $shortfallData = json_decode($resubmittedData->response);
                $resubmittedData = json_decode($resubmittedData->shortfall_submission_request);
            }
            $alreadySubmitted = 0;
            $submissionJsonQueue = RequestQueueCCIE::where('ref_id',$decodedAppId)->first();
            if ($submissionJsonQueue) {
                $alreadySubmitted = 1;
            }

            $public_html = strval(view("IndustrialIrc::application-form-view", compact('document', 'tl_issued_by', 'appInfo', 'appData', 'viewMode', 'mode', 'token', 'ccie_service_url', 'appId', 'spPaymentinformation', 'is_shortfall', 'resubmittedData', 'shortfallData','alreadySubmitted','spg_challan_base_url')));
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
        $application = IndustrialIrc::where('id', $decodedAppId)->first();
        $applicationData = json_decode($application->appdata);
        //dd($appData);
        return response()->json($applicationData);

    }

    public function showShortfallForm($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $data = CCIEShortfall::where('ref_id', $app_id)->where('status', 1)->first();
        $field_map = CCIEShortfallFieldMap::where('field_flag', 'select')->get()->toArray();
        $fieldFlagDP = CCIEShortfallFieldMap::where('field_flag', 'datepicker')->get(['name', 'field_flag'])->toArray();
        $fieldFlagImg = CCIEShortfallFieldMap::where('field_flag', 'image')->get(['name', 'field_flag'])->toArray();
//        dd($fieldFlagDP);
        $field_map_array = [];
        foreach ($field_map as $key => $value) {
            $field_map_array[$value['api_code']] = $value['name'];
        }
        $dp_field_map_array = [];
        foreach ($fieldFlagDP as $key => $value) {
            $dp_field_map_array[$value['name']] = $value['field_flag'];
        }

        $img_field_map_array = [];
        foreach ($fieldFlagImg as $key => $value) {
            $img_field_map_array[$value['name']] = $value['field_flag'];
        }
//        dd($field_map_array);
        $is_submit_shortfall = IndustrialIrc::where('id', $app_id)->first(['is_submit_shortfall']);

        $app_info = CCIEShortfall::leftJoin('cci_apps', 'cci_apps.id', '=', 'ccie_shortfall.ref_id')
            ->where('ccie_shortfall.ref_id', $app_id)
            ->first();
        $app_data = json_decode($app_info->appdata);

        $check_previous_shortfall = IndustrialIrc::where('id', $app_id)->value('resubmit_json');
//        dd(json_decode($check_previous_shortfall));
        if (!empty($check_previous_shortfall)) {
            $prevData = json_decode($check_previous_shortfall);
            $app_data->division = $prevData->division;
            $app_data->district = $prevData->district;
            $app_data->bank_name = $prevData->bank_name;
        }
        $shortfallData = json_decode($data->response);



        $process_type_id = $this->process_type_id;
        $token = $this->getCCIEToken();
        $ccie_service_url = Config('stackholder.CCIE_SERVICE_API_URL');
        $agent = config('stackholder.bida-agent-id');
        return view('IndustrialIrc::shortfall-form', compact('shortfallData', 'is_submit_shortfall', 'data', 'process_type_id', 'field_map_array', 'dp_field_map_array', 'img_field_map_array', 'token', 'ccie_service_url', 'agent', 'app_data'));

    }

    public function waitForPayment($applicationId)
    {
        return view("IndustrialIrc::waiting-for-payment", compact('applicationId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $cciePaymentInfo = CciePaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $paymentData = json_decode($cciePaymentInfo->response);
        $status = intval($cciePaymentInfo->status);
        if ($status == 1) {
            $applyPaymentFeeTotal = ($paymentData->data->data[0]->primary_reg_fee + $paymentData->data->data[0]->registration_book);
            $vatAmount = ($applyPaymentFeeTotal * intval($paymentData->data->data[0]->vat)) / 100;
            $applyPaymentfee = ($applyPaymentFeeTotal + $vatAmount);
            $appInfo = IndustrialIrc::find($application_id);
            $appData = json_decode($appInfo->appdata);
            $ServicepaymentData = ApiStackholderMapping:: where(['stackholder_id' => 8])->first(['amount']);

            $paymentInfo = view(
                "IndustrialIrc::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData','appData'))->render();
        }
        if ($cciePaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($cciePaymentInfo->id), 'status' => 0, 'message' => 'Connecting to CCIE server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($cciePaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from CCIE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($cciePaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($cciePaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function cciePayment(Request $request)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = IndustrialIrc::find($appId);
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
            Session::flash('error', "Payment configuration not found [CCIE-1123]");
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
        $cciePaymentInfo = CciePaymentInfo::where('ref_id', $appId)->first();
        $paymentResponse = json_decode($cciePaymentInfo->response);
        $ccieAccount = $paymentResponse->data->challan_code;
        $ccieVatAccount = $paymentResponse->data->vat_code;
        $ccieAmount = $paymentResponse->data->data[0]->primary_reg_fee + $paymentResponse->data->data[0]->registration_book;
        $ccieVatAmount = ($ccieAmount * intval($paymentResponse->data->data[0]->vat)) / 100;


        $cciePaymentInfo = array(
            'receiver_account_no' => $ccieAccount,
            'amount' => $ccieAmount,
            'distribution_type' => $stackholderDistibutionType,
            'm_category' => "CHL"
        );

        $stackholderMappingInfo[] = $cciePaymentInfo;

        $ccieVatInfo = array(
            'receiver_account_no' => $ccieVatAccount,
            'amount' => $ccieVatAmount,
            'distribution_type' => $stackholderDistibutionType,
            'm_category' => "CHL"
        );

        $stackholderMappingInfo[] = $ccieVatInfo;

        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);

        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');
        $appData = json_decode($appInfo->appdata);
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
        $paymentInfo->contact_name = $appData->organization_name_en;
        $paymentInfo->contact_email = $appData->organization_email;
        $paymentInfo->contact_no = $appData->organization_mobile;
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();
        IndustrialIrc::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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
                IndustrialIrc::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueCCIE::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                $queueMessage = json_encode(["cmd"=>'ccie:formsubmission']);
//                CommonFunction::publishToQueue($queueMessage);

                /*update submission details to irc bida service*/

                $industrialIrc = IndustrialIrc::where('id', $processData->ref_id)->first();
                $decodedAppdata = json_decode($industrialIrc->appdata);
                if (!empty($decodedAppdata->recommendation_number)) {
                    UtilFunction::IrcCcieSubmitted($decodedAppdata->recommendation_number, 1);
                }
                /*update end*/
            }

            $processData->save();
            $data2 = StackholderSonaliPaymentDetails::where('payment_id', $payment_id)->where('distribution_type', $stackholderDistibutionType)->get();
            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            $cciePaymentInfo = CciePaymentInfo::where('ref_id', $paymentInfo->app_id)->first();

            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                $decodedPaymentInfo = json_decode($cciePaymentInfo->response);
                foreach ($data2 as $value) {
                    $singleResponse = json_decode($value->verification_response, true);
                    if ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->challan_code) {
                        $rData0['challan'][] = [
                            'application_type' => "AH",
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
                            'application_type' => "AH",
                            'challan_type' => "vat",
                            'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                            'challan_date' => Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                            'challan_amount' => $singleResponse['TranAmount'],
                            'challan_bank_id' => "39",
                            'challan_branch_id' => "8075",
                            'challan_branch_address' => null,
                        ];
                    }

                }
            }


            $echallanJson = json_encode($rData0);
            $doePaymentConfirm = new CciePaymentConfirm();
            $doePaymentConfirm->request = $echallanJson;
            $doePaymentConfirm->ref_id = $paymentInfo->app_id;
            $doePaymentConfirm->status = 0; //application not subted
            $doePaymentConfirm->tracking_no = $processData->tracking_no;
            $doePaymentConfirm->save();
            $spg_challan = Configuration::where('caption', 'spg_challan_base_url')->value('value');

            $echallanData = [
                'reference_date' => $paymentInfo->payment_date,
                'challan_url' => $spg_challan.$paymentInfo->transaction_id,
                'request_id' => $paymentInfo->transaction_id,
                'trn_amount' => ($paymentInfo->pay_amount - 250)
            ];

            $challanConfirm = new CCIEChallanConfirm();
            $challanConfirm->request_spg = json_encode($echallanData);
            $challanConfirm->ref_id = $paymentInfo->app_id;
            $challanConfirm->status = 10; //application not subted
            $challanConfirm->tracking_no = $processData->tracking_no;
            $challanConfirm->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/ccie/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-applications/ccie/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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
            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            if ($paymentInfo->is_verified == 1) {
                if ($paymentInfo->payment_category_id == 3) { //govt fee
                    $processData->status_id = 1;
                    $processData->desk_id = 0;
                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                    IndustrialIrc::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    RequestQueueCCIE::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                    $queueMessage = json_encode(["cmd"=>'ccie:formsubmission']);
//                    CommonFunction::publishToQueue($queueMessage);

                    /*update submission details to irc bida service*/

                    $industrialIrc = IndustrialIrc::where('id', $processData->ref_id)->first();
                    $decodedAppdata = json_decode($industrialIrc->appdata);
                    if (!empty($decodedAppdata->recommendation_number)) {
                        UtilFunction::IrcCcieSubmitted($decodedAppdata->recommendation_number, 1);
                    }
                    /*update end*/
                }
                $processData->process_desc = 'Counter Payment Confirm';
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
                $processData->save();
                $data2 = StackholderSonaliPaymentDetails::where('payment_id', $payment_id)->where('distribution_type', $stackholderDistibutionType)->get();
                $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
                $account_num = $spg_conf->details;
                $cciePaymentInfo = CciePaymentInfo::where('ref_id', $paymentInfo->app_id)->first();
                if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                    $decodedPaymentInfo = json_decode($cciePaymentInfo->response);
                    foreach ($data2 as $value) {
                        $singleResponse = json_decode($value->verification_response, true);
                        if ($singleResponse['TranAccount'] == $decodedPaymentInfo->data->challan_code) {
                            $rData0['challan'][] = [
                                'application_type' => "AH",
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
                                'application_type' => "AH",
                                'challan_type' => "vat",
                                'challan_no' => !empty($singleResponse['SCRL_NO']) ? $singleResponse['SCRL_NO'] : null,
                                'challan_date' => Carbon::parse($singleResponse['ReferenceDate'])->format('M d, Y'),
                                'challan_amount' => $singleResponse['TranAmount'],
                                'challan_bank_id' => "39",
                                'challan_branch_id' => "8075",
                                'challan_branch_address' => null,
                            ];
                        }

                    }
                }


                $echallanJson = json_encode($rData0);
                $doePaymentConfirm = new CciePaymentConfirm();
                $doePaymentConfirm->request = $echallanJson;
                $doePaymentConfirm->ref_id = $paymentInfo->app_id;
                $doePaymentConfirm->status = 0; //application not submitted
                $doePaymentConfirm->tracking_no = $processData->tracking_no;
                $doePaymentConfirm->save();
                $spg_challan = Configuration::where('caption', 'spg_challan_base_url')->value('value');

                $echallanData = [
                    'reference_date' => $paymentInfo->payment_date,
                    'challan_url' => $spg_challan.$paymentInfo->transaction_id,
                    'request_id' => $paymentInfo->transaction_id,
                    'trn_amount' => ($paymentInfo->pay_amount - 250)
                ];

                $challanConfirm = new CCIEChallanConfirm();
                $challanConfirm->request_spg = json_encode($echallanData);
                $challanConfirm->ref_id = $paymentInfo->app_id;
                $challanConfirm->status = 10; //application not submitted
                $challanConfirm->tracking_no = $processData->tracking_no;
                $challanConfirm->save();
            } else {
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
            return redirect('licence-applications/ccie/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-applications/ccie/list' . Encryption::encodeId($this->process_type_id));
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


    public
    function getDocList(Request $request)
    {
        $attachment_key = $request->get('attachment_key');
        $viewMode = $request->get('viewMode');
        $app_id = ($request->has('app_id') ? Encryption::decodeId($request->get('app_id')) : 0);

        if (!empty($app_id)) {
            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $app_id)
                ->where('app_documents.process_type_id', $this->process_type_id);
//            if ($viewMode == 'on') {
//                $document_query->where('app_documents.doc_file_path', '!=', '');
//            } else {
//                $document_query->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
//                    ->where('attachment_type.key', $attachment_key);
//            }

            $document_query->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key);

            $document = $document_query->get([
                'attachment_list.id',
                'attachment_list.doc_priority',
                'attachment_list.additional_field',
                'app_documents.id as document_id',
                'app_documents.doc_file_path as doc_file_path',
                'app_documents.doc_name',
            ]);


            if (count($document) < 1) {
                $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.*']);
            }
        } else {
            $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
        }


        $html = strval(view("OfficePermissionAmendment::documents",
            compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
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
            CURLOPT_URL => $ccie_service_url . "/info/ownership-wise-doc/" . $type,
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
                "agent-id: " . config('stackholder.bida-agent-id')
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data']['data'];
                $clr_document = DynamicAttachmentCCIE::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();

                if (count($clr_document) == 0 && !empty($request->ref_app)) {
                    $company_id = Auth::user()->company_ids;

                    $getIRCApprovedRefId = ProcessList::where('tracking_no', $request->ref_app)
                        ->leftJoin('irc_apps', 'irc_apps.id', '=', 'process_list.ref_id')
                        ->where('process_list.status_id', 25)
                        ->where('process_list.company_id', $company_id)
                        ->first(['ref_id', 'tracking_no', 'certificate_link as irc_certificate', 'ref_app_tracking_no as bida_reg_track_no']);
                    if (!empty($getIRCApprovedRefId->irc_certificate)) {

                        $certificate_path = $getIRCApprovedRefId->irc_certificate;

                        $response = file_get_contents($certificate_path);
                        $dir = 'uploads/' . date("Y") . "/" . date("m");
                        if (!file_exists($dir)) {
                            mkdir($dir, 0777, true);
                            $myfile = fopen($dir . "/index.html", "w");
                            fclose($myfile);
                        }
                        $file = date("Y") . "/" . date("m") . '/irc_cr_' . uniqid() . '.pdf';
                        file_put_contents(public_path().'/uploads/'.$file, $response);
                        $clr_document = [(object)['doc_id' => 232, 'doc_path' => $certificate_path, "doc_name" => "Registration Certificate for Industrial Project issued by the sponsoring authority (In the case of Ad hoc IRC)", 'is_uploaded' => 0]];
                        if (!empty($getIRCApprovedRefId->bida_reg_track_no)) {
                            $bidaRegistration = ProcessList::where('tracking_no', $getIRCApprovedRefId->bida_reg_track_no)
                                ->leftjoin('br_apps', 'process_list.ref_id', '=', 'br_apps.id')
                                ->first(['br_apps.certificate_link as br_certificate']);
                            if (isset($bidaRegistration)) {
                                if (!empty($bidaRegistration->br_certificate)) {

                                    $br_certificate = file_get_contents($bidaRegistration->br_certificate);
                                    $file2 = date("Y") . "/" . date("m") . '/br_cr_' . uniqid() . '.pdf';
                                    file_put_contents(public_path().'/uploads/'.$file2, $br_certificate);
                                    array_push($clr_document, (object)['doc_id' => 743, 'doc_path' => $bidaRegistration->br_certificate, "doc_name" => "Registration Certificate for Industrial Project issued by the sponsoring authority (In the case of Ad hoc IRC)", 'is_uploaded' => 0]);
                                }
                            }
                        }

                    }

//                    $clr_document = ;
                }
                $clrDocuments = [];
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                    $clrDocuments[$documents->doc_id]['is_upload'] = $documents->is_uploaded;
                }
                $html = view(
                    "IndustrialIrc::documents",
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

// ajax call for organisation list from API
//    public function getOrgList(){
//
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://insightba.oss.net.bd/api/ccie-service/get_org_list/",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 30,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_HTTPHEADER => array(
//                "cache-control: no-cache",
//                "postman-token: 199b04eb-9bb4-f1ab-a905-99008d5b88f4"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//        $err = curl_error($curl);
//
//        curl_close($curl);
//
//        dd($response);
//
//        $r = json_decode($response);
//        $result = $r->data;
//
//        $data = ['responseCode' => 1, 'data' => $result];
//        return response()->json($data);
//
//    }


    public function SubmissionJson($app_id, $tracking_no)
    {

        try {
            $ccieRequest = RequestQueueCCIE::firstOrNew([
                'ref_id' => $app_id
            ]);

            $appData = IndustrialIrc::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            $userData = $this->getuserinfo($masterData->organization_email,$masterData->organization_mobile);
            $submissionData = [];

            $base64url = config('stackholder.bida_base64_api');

            if ($userData != null) {
                $userId = $userData['id'];
            } else {
                Session::flash('error', "User Data not Valid" . ' [CCIE-1001]');
                return redirect()->back();
            }

            $submissionData['user_id'] = $userId;
            $submissionData['sub_service_id'] = "10";
            $submissionData['third_party_app_code'] = $tracking_no;
            $submissionData['org_mail'] = $masterData->organization_email;
            $submissionData['salutation'] = null;
            $submissionData['organization_name_en'] = $masterData->organization_name_en;
            $submissionData['organization_name_bn'] = $masterData->organization_name_bn;
            $submissionData['org_address_bn'] = $masterData->organization_add_bn;
            $submissionData['org_address_en'] = $masterData->organization_add_bn;
            $submissionData['org_factory_address_bn'] = $masterData->factory_add_bn;
            $submissionData['org_factory_address_en'] = $masterData->factory_add_en;
            $submissionData['org_phone'] = $masterData->organization_phone;
            $submissionData['org_mobile'] = $masterData->organization_mobile;
            $submissionData['org_fax'] = $masterData->organization_fax;
            $submissionData['contact_person_name'] = $masterData->contact_person_name;
            $submissionData['contact_person_phone'] = $masterData->contact_person_2;
            $submissionData['holding_no'] = $masterData->holding_no;

            $submissionData['division_id'] = !empty($masterData->division) ? explode('@', $masterData->division)[0] : '';
            $submissionData['district_id'] = !empty($masterData->district) ? explode('@', $masterData->district)[0] : '';
            $submissionData['org_ps_id'] = !empty($masterData->police_station) ? explode('@', $masterData->police_station)[0] : '';
            $submissionData['org_post_code'] = $masterData->organization_post_code;
            $submissionData['org_tin_no'] = $masterData->organization_tin;
            $submissionData['ow_type'] = !empty($masterData->organization_type) ? explode('@', $masterData->organization_type)[0] : '';
            $ownerData = [];
            if (isset($masterData->owner_name)) {

                foreach ($masterData->owner_name as $key => $owner_name) {
                    $file = '';
                    if ($masterData->owner_photo[$key]) {
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
                        $file = $ym . uniqid() . '.' . $image_type;
                    }
                    file_put_contents($path . $file, $image_base64);
                    $ownerData[$key]['ow_photo'] = !empty($file) ? $base64url . $file : null;
                    $ownerData[$key]['nationality'] = $masterData->nationality[$key];
                    $ownerData[$key]['ow_tin'] = $masterData->owner_tin[$key];
                    $ownerData[$key]['ow_nid'] = $masterData->owner_nid_or_passport[$key];
                    $ownerData[$key]['ow_name'] = $masterData->owner_name[$key];
                    $ownerData[$key]['ow_designation'] = isset($masterData->designation) ? explode('@', $masterData->designation[$key])[0] : "";
                    $ownerData[$key]['ow_father_name'] = $masterData->owner_father_name[$key];
                    $ownerData[$key]['ow_mother_name'] = $masterData->mother_name[$key];
                    $ownerData[$key]['ow_office_phone'] = $masterData->phone_number_office[$key];
                    $ownerData[$key]['ow_mobile'] = $masterData->mobile[$key];
                    $ownerData[$key]['ow_pre_ps_id'] = $masterData->present_address[$key];
                    $ownerData[$key]['ow_per_ps_id'] = $masterData->present_address[$key];
                    $ownerData[$key]['ow_district_id'] = !empty($masterData->district_name) ? explode('@', $masterData->district_name[$key])[0] : "";
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
            $submissionData['share_type'] = !empty($masterData->share_type) ? explode('@', $masterData->share_type)[0] : '';
            $submissionData['foreign_share_percent'] = isset($masterData->foreign_share) ? $masterData->foreign_share : null;
            $submissionData['govt_share_percent'] = isset($masterData->domestic_share) ? $masterData->domestic_share : null;
            $submissionData['adhoc_type'] = 1;
            $submissionData['ah_industrial_name'] = isset($masterData->industrial_sector_name) ? $masterData->industrial_sector_name : null;
            $submissionData['ah_import_right'] = isset($masterData->half_yearly_import) ? $masterData->half_yearly_import : null;
            $submissionData['ah_yearly_import_right'] = null;
            $submissionData['ah_fire_cer_no'] = isset($masterData->fire_license_number) ? $masterData->fire_license_number : null;
            $submissionData['ah_fire_license_date'] = isset($masterData->fire_license_date) ? date("F j, Y", strtotime($masterData->fire_license_date)) : null;
            $submissionData['ah_env_cer_no'] = isset($masterData->environment_license_number) ? $masterData->environment_license_number : null;
            $submissionData['ah_env_cer_date'] = isset($masterData->environment_license_date) ? date("F j, Y", strtotime($masterData->environment_license_date)) : null;
//		$submissionData['ah_incorporation_no']        = isset( $masterData->registration_number1 ) ? $masterData->registration_number1 : null;
            $submissionData['ah_incorporation_date'] = isset($masterData->registration_date1) ? date("F j, Y", strtotime($masterData->registration_date1)) : null;
            $submissionData['ah_bond_license'] = isset($masterData->bond_license_number) ? $masterData->bond_license_number : null;
            $submissionData['ah_bond_date'] = isset($masterData->bond_license_date) ? $masterData->bond_license_date : null;
            $submissionData['ah_recomendation_no'] = isset($masterData->recommendation_number) ? $masterData->recommendation_number : null;
            $submissionData['ah_recomendation_date'] = isset($masterData->recommendation_date) ? date("F j, Y", strtotime($masterData->recommendation_date)) : null;
            $submissionData['ah_sponsor_reg_no'] = isset($masterData->industrial_sponsor_rg_no) ? $masterData->industrial_sponsor_rg_no : null;
            $submissionData['ah_sponsor_reg_date'] = isset($masterData->industrial_sponsor_rg_date) ? date("F j, Y", strtotime($masterData->industrial_sponsor_rg_date)) : null;
            $submissionData['ah_sponsor_name'] = isset($masterData->sponsor_name) ? $masterData->sponsor_name : null;
            $submissionData['adhoc_yearly_proc_capacity'] = isset($masterData->yearly_production_capacity) ? $masterData->yearly_production_capacity : null;
            $submissionData['aypc_unit'] = isset($masterData->ypc_unit) ? explode('@', $masterData->ypc_unit)[0] : null;
            $submissionData['adhoc_prod_capacity_desc'] = isset($masterData->yearly_production_capacity) ? $masterData->yearly_production_capacity : null;
            $submissionData['ah_total_labour'] = isset($masterData->total_number_of_labour) ? $masterData->total_number_of_labour : null;
            $submissionData['ah_mach_import'] = isset($masterData->imported_spare_parts) ? $masterData->imported_spare_parts : null;
            $submissionData['ah_prod_start_date'] = isset($masterData->production_start_date) ? date("F j, Y", strtotime($masterData->production_start_date)) : null;
            $submissionData['ah_prod_insp_name'] = isset($masterData->inspector_name) ? $masterData->inspector_name : null;
            $submissionData['ah_prod_insp_date'] = isset($masterData->inspection_date) ? date("F j, Y", strtotime($masterData->inspection_date)) : null;
            $submissionData['ah_raw_price_perc'] = isset($masterData->raw_material_percentage) ? $masterData->raw_material_percentage : null;
            $submissionData['adhoc_apply_production'] = isset($masterData->half_yearly_production_demand) ? $masterData->half_yearly_production_demand : null;
            $submissionData['aap_unit'] = isset($masterData->hypc_unit) ? explode('@', $masterData->hypc_unit)[0] : null;
            $submissionData['ah_prod_price'] = isset($masterData->half_yearly_production_capacity) ? $masterData->half_yearly_production_capacity : null;

            $adhoc_items = [];
            if (isset($masterData->item_type)) {
                foreach ($masterData->item_type as $key => $data) {
                    $adhoc_items[$key]['item_type'] = $masterData->item_type[$key];
                    $adhoc_items[$key]['description_of_item'] = $masterData->description_item[$key];
                    $adhoc_items[$key]['issue_date'] = date("F j, Y", strtotime($masterData->issue_date[$key]));
                    $adhoc_items[$key]['hs_code'] = $masterData->hs_code[$key];
                    $adhoc_items[$key]['currency_code'] = $masterData->unit_price[$key];
                    $adhoc_items[$key]['unit_price'] = $masterData->unit_price_no[$key];
                    $adhoc_items[$key]['unit_code'] = $masterData->quantity_type[$key];
                    $adhoc_items[$key]['quantity'] = $masterData->quantity[$key];
                    $adhoc_items[$key]['value'] = $masterData->item_value[$key];
                }
            }


            $submissionData['adhoc_items'] = $adhoc_items;

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

            $submissionData['fees_id'] = isset($masterData->irc_slab) ? explode('@', $masterData->irc_slab)[0] : null;
            $submissionData['application_type'] = "AH";
            $submissionData['draft'] = "0";

            $documentInfo = [];
            $appDoc = [];


            $ref_id = $app_id;
            $doc_item = DB::table('dynamic_attachment_ccie')->where('ref_id', $ref_id)->get();

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hostUrl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';
            $base64url = Config('stackholder.bida_base64_api');

            foreach ($doc_item as $key => $data) {
                if (!empty($data->doc_path)) {
                    $documentInfo['doc_id'] = $data->doc_id;
                    $documentInfo['doc_type'] = explode('@', $masterData->organization_type)[0];
                    if ($data->is_uploaded == 0) {
                        $documentInfo['base64'] = $base64url . $data->doc_path;
                    } else {
                        $documentInfo['base64'] = $base64url . $hostUrl . $data->doc_path;
                    }
                    $appDoc [] = $documentInfo;
                }
            }
            $submissionData['document'] = $appDoc;

            $ccieRequest->ref_id = $ref_id;
            $ccieRequest->type = 'Submission';
            $ccieRequest->status = 10;
            $ccieRequest->request_json = json_encode($submissionData);

            $ccieRequest->save();
            $queueMessage = json_encode(["cmd"=>'ccie:getchallancode']);
//        CommonFunction::publishToQueue($queueMessage);
        }catch (\Exception $e){
            dd('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
        }

    }


    public function getuserinfo($userEmail,$uerPhone)
    {
        try {
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
                'email' => $userEmail,
                'mobile' => $uerPhone]);

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
                    "agent-id: " . config('stackholder.bida-agent-id')
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $decoded_response = json_decode($response, true);

            $result = $decoded_response['data'];
            return $result;
        }catch (\Exception $e){
            Session::flash('error', 'Something Wrong [CCIE-U-1001]');
            return redirect('industrial-IRC/list/' . Encryption::encodeId($this->process_type_id));
        }

    }

    public function uploadDocument()
    {
        return View::make('IndustrialIrc::ajaxUploadFile');
    }

    public function storeShortfall(Request $request)
    {

        $app_id = Encryption::decodeId($request->get('app_id'));
        $data = $request->all();
        $is_submit_shortfall = IndustrialIrc::where('id', $app_id)->first();
        $appData = json_decode($is_submit_shortfall->appdata);
        $resubmitJson = json_decode($is_submit_shortfall->resubmit_json);

        if (empty($is_submit_shortfall->resubmit_json)) {
            $arrayData['division'] = isset($data['division_id@org_info@0']) ? $data['division_id@org_info@0'] : $appData->division;
            $arrayData['district'] = isset($data['district_id@org_info@0']) ? $data['district_id@org_info@0'] : $appData->district;
            $arrayData['bank_name'] = isset($data['bank_id@nominated_bank_info@0']) ? $data['bank_id@nominated_bank_info@0'] : $appData->bank_name;
        } else {
            $arrayData['division'] = isset($data['division_id@org_info@0']) ? $data['division_id@org_info@0'] : $resubmitJson->division;
            $arrayData['district'] = isset($data['district_id@org_info@0']) ? $data['district_id@org_info@0'] : $resubmitJson->district;
            $arrayData['bank_name'] = isset($data['bank_id@nominated_bank_info@0']) ? $data['bank_id@nominated_bank_info@0'] : $resubmitJson->bank_name;
        }

        $allinput = [];
        $base64url = config('stackholder.bida_base64_api');
        DB::beginTransaction();
        foreach ($data as $key => $value) {

            $pos = strpos($key, '@');
            if ($pos == true) {
                $expData = explode('@', $key);
                $name = $expData[0];
                $fieldset = $expData[1];
                $pid = $expData[2];
                if ($name == 'ow_photo') {
                    $filedvalue = $base64url . $request->get($key);
                } else {
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

        if (isset($documents)) {
            foreach ($documents as $value) {
                $doc = $request->get('validate_field_' . $value);
                $arr = array(
                    'fieldset' => 'doc_info',
                    'pid' => 0,
                    'name' => $value,
                    'value' => $base64url . $doc,
                    'doc_name' => $request->get('doc_title_' . $value)
                );
                $allinput['doc_info'][] = $arr;
            }
        }


        $shortfallResponseCcie = CCIEShortfall::where('ref_id', $app_id)->value('response');
        $decoDed = json_decode($shortfallResponseCcie);
        $decoDed = $decoDed->data;
        $submissionData = [
            'app_resubmit_id' => $decoDed->app_resubmit_id,
            'application_id' => $decoDed->application_id,
            'application_stage_id' => $decoDed->application_stage_id,
            'sub_service_id' => '',
            'stage_id' => $decoDed->stage_id,
            'resubmit_data' => $allinput
        ];

        $shortFall = CCIEShortfall::firstOrNew(['ref_id' => $app_id]);
        $shortFall->is_submit = 1;
        $shortFall->submission_status = 0;
        $shortFall->shortfall_submission_request = json_encode($submissionData);
        $shortFall->save();


        // new code for feedback
        IndustrialIrc::where('id', $app_id)->update([
            'resubmit_json' => json_encode($arrayData),
            'is_submit_shortfall' => 1
        ]);

        ProcessList::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)
            ->update(['status_id' => 2]);


// new code for feedback
        DB::commit();
        Session::flash('success', 'Re-submitted successfully');
        return redirect('industrial-IRC/list/' . Encryption::encodeId($this->process_type_id));

    }

}
