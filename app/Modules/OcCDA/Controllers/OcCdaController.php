<?php


namespace App\Modules\OcCDA\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\LsppCDA\Models\DynamicAttachmentLSPPCDA;
use App\Modules\OcCDA\Models\DynamicAttachmentOcCDA;
use App\Modules\OcCDA\Models\OcCDA;
use App\Modules\OcCDA\Models\RequestQueueOcCDA;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class OcCdaController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 132;
        $this->aclName = 'OcCDA';
    }

    public function appForm(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CDA OC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [OCCDA-971]</h4>"]);
        }
        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;

            $token = $this->getToken();

            $cda_oc_service_url = Config('stackholder.CDA_OC_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $city_corporation = ['0' => 'চট্টগ্রাম সিটি কর্পোরেশন (চসিক)'];
            $client_id = Config('stackholder.CDA_OC_CLIENT_ID');
            $bida_agent_id = Config('stackholder.bida-agent-id');
            $public_html = strval(view("OcCDA::application-form", compact('process_type_id', 'viewMode', 'mode', 'token', 'cda_oc_service_url','city_corporation', 'client_id', 'bida_agent_id')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else if ($openMode == 'edit') {
            $viewMode = 'off';
            $mode = '-E-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($appId);
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftjoin('oc_cda_apps', 'oc_cda_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'oc_cda_apps.sf_payment_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $applicationId)
                ->whereIn('process_list.company_id', $companyIds)
                ->first([
                    'oc_cda_apps.*',
                    'ps.status_name',
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
            $city_corporation = ['0' => 'চট্টগ্রাম সিটি কর্পোরেশন (চসিক)'];
            $client_id = Config('stackholder.CDA_OC_CLIENT_ID');
            $bida_agent_id = Config('stackholder.bida-agent-id');
            $appData = json_decode($appInfo->appdata);
            if ($appInfo->status_id == 1) {

                $app_id = Encryption::encodeId($applicationId);
                $public_html = strval(view("OcCDA::wait-for-payment",
                    compact('app_id')));
                return response()->json(['responseCode' => 1, 'html' => $public_html]);
                //return redirect('licence-applications/name-clearance/submission-response/'.Encryption::encodeId($verifyid));

            }
            if ($appInfo->status_id == 1) {
                return redirect('cda-oc/check-submission/' . Encryption::encodeId($appInfo->id));
            }

            // Start file uploading
            $document = Attachment::where('attachment_list.status', 1)
                ->where('attachment_list.process_type_id', $this->process_type_id)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
            if ($appInfo) {
                $clr_document = DynamicAttachmentOcCDA::where('ref_id', $appInfo->id)->get();
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
            } else {
                $clrDocuments = [];
            }
            $token = $this->getToken();
            $cda_oc_service_url = Config('stackholder.CDA_OC_SERVICE_API_URL');
//            return view("OcCDA::application-form-edit", compact('document', 'appInfo', 'city_corporation', 'appData',
//                'viewMode', 'clrDocuments', 'cda_oc_service_url', 'client_id', 'bida_agent_id', 'token', 'mode'));
            $public_html = strval(view("OcCDA::application-form-edit", compact('document', 'appInfo', 'city_corporation', 'appData',
                'viewMode', 'clrDocuments', 'cda_oc_service_url', 'client_id', 'bida_agent_id', 'token', 'mode')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"
            ]);
        }
    }

    // Get CDA OC token for authorization
    public function getToken()
    {
        // Get credentials from database
        $cda_oc_idp_url = Config('stackholder.CDA_OC_TOKEN_API_URL');
        $cda_oc_client_id = Config('stackholder.CDA_OC_CLIENT_ID');
        $cda_oc_client_secret = Config('stackholder.CDA_OC_CLIENT_SECRET');


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $cda_oc_client_id,
            'client_secret' => $cda_oc_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$cda_oc_idp_url");
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

    public function checkstatus($app_id)
    {

        return view("OcCDA::wait-for-payment", compact('app_id'));

    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [OCCDA-970]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($request->get('app_id'));
                $appData = OcCDA::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new OcCDA();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
//            dd($request->all(), $data);
            $appData->appdata = $data;
            $member_id = $this->getMemberId();
            $appData->member_id = $member_id;
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

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            // Start file uploading
            $doc_row = Attachment::where('attachment_list.status', 1)
                ->where('attachment_list.process_type_id', $this->process_type_id)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);

            //Start file uploading
            if (isset($doc_row)) {
                foreach ($doc_row as $docs) {
                    $app_doc = DynamicAttachmentOcCDA::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_priority = $docs->doc_priority;
                    $app_doc->attachment_type_id = $docs->attachment_type_id;
                    $app_doc->short_note = $docs->short_note;
                    $app_doc->doc_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->save();
                    $cda_docids[] = $docs->id;
                }
            }
            /* End file uploading */

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'CDA-OC-' . date("dMY") . '-';
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
                $this->submissionJson($appData->id, $tracking_no, $processData->status_id, $request->ip());
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
                return redirect('cda-oc/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('cda-oc/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('cda-oc/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            // dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    public function getMemberId()
    {
        $oc_cda_api_url = Config('stackholder.CDA_OC_SERVICE_API_URL');
        $token = $this->getToken();

        $curl = curl_init();
        $user_name = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;

        $request_data = json_encode([
            'userName' => $user_name,
            'userEmail' => Auth::user()->user_email,
            'userPhone' => Auth::user()->user_phone]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $oc_cda_api_url . "/get-memberid",
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
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $result = $decoded_response['data']['resonse']['result'];
        $member_id = (isset($result['data']['memberId']) ? $result['data']['memberId'] : '');
        return $member_id;
    }

    public function submissionJson($app_id, $tracking_no, $statusid, $ip_address)
    {
        if ($statusid == 2) {
            $ocCDARequest = new RequestQueueOcCDA();
        } else {
            $ocCDARequest = RequestQueueOcCDA::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        if ($ocCDARequest->status != 1) {

            $appData = OcCDA::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);

            $document_query = DynamicAttachmentOcCDA::where('ref_id', $appData->id)
                ->where('process_type_id', $this->process_type_id)
                ->where('doc_path', '!=', '')
                ->get();

            // Here I have used short note column to identify documents
            $documents = array();
            $docBaseUrl = url('uploads');
            $docMap = [
                'tin' => "attachedDoc3",
                'nid' => "attachedDoc4",
                'doc_type_1_1' => "attachedDoc5",
                'doc_type_1_2' => "attachedDoc6",
                'doc_type_2_1' => "attachedDoc7",
                'doc_type_2_2' => "attachedDoc8",
                'doc_type_3_1' => "attachedDoc9",
                'doc_type_3_2' => "attachedDoc10",
                'doc_type_3_3' => "attachedDoc11",
                'main_amk' => "attachedDoc12",
                'main_lease' => "attachedDoc13",
                'mouza_map' => "attachedDoc14",
                'loc_map' => "attachedDoc15"
            ];
            foreach ($document_query as $document) {

                switch ($document->short_note) {
                    case "tin":
                        $documents['attachedDoc3'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "nid":
                        $documents['attachedDoc4'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "main_amk":
                        $documents['attachedDoc12'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "main_lease":
                        $documents['attachedDoc13'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "mouza_map":
                        $documents['attachedDoc14'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "loc_map":
                        $documents['attachedDoc15'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_1_1":
                        $documents['attachedDoc5'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_1_2":
                        $documents['attachedDoc6'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_2_1":
                        $documents['attachedDoc7'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_2_2":
                        $documents['attachedDoc8'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_3_1":
                        $documents['attachedDoc9'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_3_2":
                        $documents['attachedDoc10'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                    case "doc_type_3_3":
                        $documents['attachedDoc11'] = $docBaseUrl . '/' . $document->doc_file_path;
                        break;
                }
            }
            foreach ($docMap as $value) {
                if (!array_key_exists($value, $documents)) {
                    $documents["$value"] = "";
                }
            }
            //dd($documents);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';

            $paramAppdata['name'] = "addLucForm";
            $paramAppdata['trackingNo'] = $tracking_no;
            $param = [
                "landTypID" => !empty($masterData->land_use_category_id) ? explode('@', $masterData->land_use_category_id)[1] : '',
                "occupancyType" => '1^2^5',
                "applicantName" => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
                "applicantMobile" => !empty($masterData->applicant_mobile_no) ? $masterData->applicant_mobile_no : '',
                "applicantEmail" => !empty($masterData->applicant_email) ? $masterData->applicant_email : '',
                "applicantAddress" => !empty($masterData->applicant_present_address) ? $masterData->applicant_present_address : '',
                "ProposedUse" => !empty($masterData->suggested_use_land_plot) ? $masterData->suggested_use_land_plot : '',
                "CityCorpName" => !empty($masterData->city_corporation_id) ? explode('@', $masterData->city_corporation_id)[0] : '',
                "BsName" => !empty($masterData->bs_code) ? $masterData->bs_code : '',
                "RsName" => !empty($masterData->rs_code) ? $masterData->rs_code : '',
                "ThanaName" => !empty($masterData->thana_name) ? explode('@', $masterData->thana_name)[1] : '',
                "MouzaName" => !empty($masterData->mouza_name) ? explode('@', $masterData->mouza_name)[2] : '',
                "BlockNo" => !empty($masterData->block_no) ? explode('@', $masterData->block_no)[0] : '',
                "SitNo" => !empty($masterData->seat_no) ? explode('@', $masterData->seat_no)[0] : '',
                "WordNo" => !empty($masterData->ward_no) ? explode('@', $masterData->ward_no)[1] : '',
                "SectorNo" => !empty($masterData->sector_no) ? explode('@', $masterData->sector_no)[0] : '',
                "RoadName" => !empty($masterData->road_name) ? $masterData->road_name : '',
                "PlotMeasure" => !empty($masterData->arm_size_land_plot_amount) ? $masterData->arm_size_land_plot_amount : '',
                "PlotDetails" => !empty($masterData->existing_house_plot_land_details) ? $masterData->existing_house_plot_land_details : '',
                "LucNo" => !empty($masterData->suggested_use_land_plot) ? $masterData->suggested_use_land_plot : '',
                "ProposedDevelopment" => !empty($masterData->proposed_dev_work_type) ? $masterData->proposed_dev_work_type : '',
                "LandArea" => !empty($masterData->land_area) ? $masterData->land_area : '',
                "MaxFloorArea" => !empty($masterData->max_floor_area) ? $masterData->max_floor_area : '',
                "TotalFloorArea" => !empty($masterData->total_floor_area) ? $masterData->total_floor_area : '',
                "TotalFloorNo" => !empty($masterData->total_plinth_floor) ? $masterData->total_plinth_floor : '',
                "BasementNo" => !empty($masterData->basement_floor_no) ? $masterData->basement_floor_no : '',
                "ResidentBuilding" => !empty($masterData->total_residential_flat_no) ? $masterData->total_residential_flat_no : '',
                "FloorSize1" => !empty($masterData->other_usage_1) ? $masterData->other_usage_1 : '',
                "FloorSize2" => !empty($masterData->other_usage_2) ? $masterData->other_usage_2 : '',
                "FloorSize3" => !empty($masterData->other_usage_3) ? $masterData->other_usage_3 : '',
                "FloorSize4" => !empty($masterData->other_usage_4) ? $masterData->other_usage_4 : '',
                "FloorSize5" => !empty($masterData->other_usage_5) ? $masterData->other_usage_5 : '',
                "RailStation" => !empty($masterData->site_side_main_road) ? $masterData->site_side_main_road : '',
                "FontRoadMeasure" => !empty($masterData->front_road_area) ? $masterData->front_road_area : '',
                "BackRoadMeasure" => !empty($masterData->back_road_area) ? $masterData->back_road_area : '',
                "LeftRoadMeasure" => !empty($masterData->left_road_area) ? $masterData->left_road_area : '',
                "RightRoadMeasure" => !empty($masterData->right_road_area) ? $masterData->right_road_area : '',
                "Forest" => !empty($masterData->natural_forrest) ? $masterData->natural_forrest : '',
                "Hill" => !empty($masterData->mountain) ? $masterData->mountain : '',
                "Slope" => !empty($masterData->slope) ? $masterData->slope : '',
                "Pond" => !empty($masterData->pond) ? $masterData->pond : '',
                "Wetlands" => !empty($masterData->natural_wetlands) ? $masterData->natural_wetlands : '',
                "Building" => !empty($masterData->building) ? $masterData->building : '',
                "HistoricalBuilding" => !empty($masterData->historic_building) ? $masterData->historic_building : '',
                "Lake" => !empty($masterData->site_side_lake) ? $masterData->site_side_lake : '',
                "BackTwist" => !empty($masterData->site_side_park) ? $masterData->site_side_park : '',
                "FeaturedArea" => !empty($masterData->site_in_visually_characteristics_area) ? $masterData->site_in_visually_characteristics_area : '',
                "Airport" => !empty($masterData->airport) ? $masterData->airport : '',
                "Railway" => !empty($masterData->railway_station) ? $masterData->railway_station : '',
                "BusTerminal" => !empty($masterData->bus_terminal) ? $masterData->bus_terminal : '',
                "RiverPort" => !empty($masterData->river_port) ? $masterData->river_port : '',
                "FloodArea" => !empty($masterData->flood_prone_area) ? $masterData->flood_prone_area : '',
                "RoadArea" => !empty($masterData->road_center_to_site) ? $masterData->road_center_to_site : '',
                "RoadAreaMeter" => !empty($masterData->road_center_to_site_meter) ? $masterData->road_center_to_site_meter : '',
                "ProposedCurrBuilding" => !empty($masterData->road_center_to_site_meter) ? $masterData->road_center_to_site_meter : '',
                "CurrBuildMeter" => !empty($masterData->buildings_total_floor_area) ? $masterData->buildings_total_floor_area : '',
                "Electricity" => !empty($masterData->total_electricity_demand) ? $masterData->total_electricity_demand : '',
                "Water" => !empty($masterData->total_water_demand) ? $masterData->total_water_demand : '',
                "DevelopmentWork" => !empty($masterData->total_development_time_in_month) ? $masterData->total_development_time_in_month : '',
                "WorkDuration1" => !empty($masterData->total_development_stage) ? $masterData->total_development_stage : '',
                "WorkDuration2" => !empty($masterData->total_development_stage_month) ? $masterData->total_development_stage_month : '',
                "Basement1" => !empty($masterData->usage_1[0]) ? $masterData->usage_1[0] : '',
                "Basement2" => !empty($masterData->usage_2[0]) ? $masterData->usage_2[0] : '',
                "Basement3" => !empty($masterData->usage_3[0]) ? $masterData->usage_3[0] : '',
                "Basement4" => !empty($masterData->total_floor[0]) ? $masterData->total_floor[0] : '',
                "GroundFloor1" => !empty($masterData->usage_1[1]) ? $masterData->usage_1[1] : '',
                "GroundFloor2" => !empty($masterData->usage_2[1]) ? $masterData->usage_2[1] : '',
                "GroundFloor3" => !empty($masterData->usage_3[1]) ? $masterData->usage_3[1] : '',
                "GroundFloor4" => !empty($masterData->total_floor[1]) ? $masterData->total_floor[1] : '',
                "FirstFloor1" => !empty($masterData->usage_1[2]) ? $masterData->usage_1[2] : '',
                "FirstFloor2" => !empty($masterData->usage_2[2]) ? $masterData->usage_2[2] : '',
                "FirstFloor3" => !empty($masterData->usage_3[2]) ? $masterData->usage_3[2] : '',
                "FirstFloor4" => !empty($masterData->total_floor[2]) ? $masterData->total_floor[2] : '',
                "SecFloor1" => !empty($masterData->usage_1[3]) ? $masterData->usage_1[3] : '',
                "SecFloor2" => !empty($masterData->usage_2[3]) ? $masterData->usage_2[3] : '',
                "SecFloor3" => !empty($masterData->usage_3[3]) ? $masterData->usage_3[3] : '',
                "SecFloor4" => !empty($masterData->total_floor[3]) ? $masterData->total_floor[3] : '',
                "ThirdFloor1" => !empty($masterData->usage_1[4]) ? $masterData->usage_1[4] : '',
                "ThirdFloor2" => !empty($masterData->usage_2[4]) ? $masterData->usage_2[4] : '',
                "ThirdFloor3" => !empty($masterData->usage_3[4]) ? $masterData->usage_3[4] : '',
                "ThirdFloor4" => !empty($masterData->total_floor[4]) ? $masterData->total_floor[4] : '',
                "ForthFloor1" => !empty($masterData->usage_1[5]) ? $masterData->usage_1[5] : '',
                "ForthFloor2" => !empty($masterData->usage_2[5]) ? $masterData->usage_2[5] : '',
                "ForthFloor3" => !empty($masterData->usage_3[5]) ? $masterData->usage_3[5] : '',
                "ForthFloor4" => !empty($masterData->total_floor[5]) ? $masterData->total_floor[5] : '',
                "FifthFloor1" => !empty($masterData->usage_1[6]) ? $masterData->usage_1[6] : '',
                "FifthFloor2" => !empty($masterData->usage_2[6]) ? $masterData->usage_2[6] : '',
                "FifthFloor3" => !empty($masterData->usage_3[6]) ? $masterData->usage_3[6] : '',
                "FifthFloor4" => !empty($masterData->total_floor[6]) ? $masterData->total_floor[6] : '',
                "SixthFloor1" => !empty($masterData->usage_1[7]) ? $masterData->usage_1[7] : '',
                "SixthFloor2" => !empty($masterData->usage_2[7]) ? $masterData->usage_2[7] : '',
                "SixthFloor3" => !empty($masterData->usage_3[7]) ? $masterData->usage_3[7] : '',
                "SixthFloor4" => !empty($masterData->total_floor[7]) ? $masterData->total_floor[7] : '',
                "PropLeaseDeed" => !empty($masterData->owner_purchase_deed) ? $masterData->owner_purchase_deed : '',
                "LandAllot" => !empty($masterData->govt_assigned_land_deed) ? $masterData->govt_assigned_land_deed : '',
                "CertificateFee" => !empty($masterData->paid_fee_and_prove) ? $masterData->paid_fee_and_prove : '',
                "LandClearance" => !empty($masterData->land_usage_exemption) ? $masterData->land_usage_exemption : '',
                "FARCalculation" => !empty($masterData->far_calculation) ? $masterData->far_calculation : '',
                "DesignRule" => !empty($masterData->all_design_and_documents_detail) ? $masterData->all_design_and_documents_detail : '',
                "ApplicantSign" => !empty($masterData->validate_field_applicantSignature) ? $hosturl . $masterData->validate_field_applicantSignature : '',
                "ApplicantName" => !empty($masterData->applicant_name_2) ? $masterData->applicant_name_2 : '',
                "ApplicantAddress" => !empty($masterData->applicant_address) ? $masterData->applicant_address : '',
                "TechPersonName" => !empty($masterData->technical_person_name) ? $masterData->technical_person_name : '',
                "ArchEngrSign" => !empty($masterData->validate_field_architectSignature) ? $hosturl . $masterData->validate_field_architectSignature : '',
                "CivilEngrSign" => !empty($masterData->validate_field_civilEngineerSignature) ? $hosturl . $masterData->validate_field_civilEngineerSignature : '',
                "ArchEngrName" => !empty($masterData->architect_name) ? $masterData->architect_name : '',
                "CivilEngrName" => !empty($masterData->civil_engineer_name) ? $masterData->civil_engineer_name : '',
                "ArchEngrAddress" => !empty($masterData->architect_address) ? $masterData->architect_address : '',
                "CivilEngrAddress" => !empty($masterData->civil_engineer_address) ? $masterData->civil_engineer_address : '',
                "ArchEngrMobile" => !empty($masterData->architect_phone) ? $masterData->architect_phone : '',
                "CivilEngrMobile" => !empty($masterData->civil_engineer_phone) ? $masterData->civil_engineer_phone : '',
                "ArchEngrRegNo" => !empty($masterData->registration_no) ? $masterData->registration_no : '',
                "CivilEngrRegNo" => !empty($masterData->civil_engineer_registration_no) ? $masterData->civil_engineer_registration_no : '',
                "MemberId" => !empty($appData->member_id) ? (string)$appData->member_id : '',
                "CreateDate" => Carbon::now()->format('Y-m-d'),
            ];

            $paramAppdata['param'] = array_merge($param, $documents);
//            if ($processData->status_id == 2) {
//                $resubmitRequreData = array(
//                    'LucID' => "$appData->luc_id",
//                    'incomeingType' => "10014",
//                    'incomeingReason' => "Incoming Reason Here"
//                );
//                $requestData = array_merge($requestData, $resubmitRequreData);
//            }
            $ocCDARequest->ref_id = $appData->id;
            if ($statusid == 2) {
                $ocCDARequest->type = 'Resubmission';
            } else {
                $ocCDARequest->type = 'Submission';
            }
            $ocCDARequest->status = 0;   // 10 = payment not submitted
            $ocCDARequest->request_json = json_encode($paramAppdata);
            $ocCDARequest->save();
        }

    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function waitForPayment($applicationId)
    {
        return view("OcCDA::wait-for-payment", compact('applicationId', 'paymentId'));
    }
    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $paymentInfoData = RequestQueueOcCDA::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $decodedResponse = json_decode($paymentInfoData->response_json);
        $status = intval($paymentInfoData->status);
        if ($status == 1) {
            $applyPaymentfee = $decodedResponse->data->resonse->result->SpCaseProcessingFee;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "OcCDA::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($paymentInfoData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 0, 'message' => 'Connecting to CDA server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from CDA']);
        } elseif ($status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == -2) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => $decodedResponse]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }


}