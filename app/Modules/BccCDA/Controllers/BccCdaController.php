<?php

namespace App\Modules\BccCDA\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\BccCDA\Models\BccCDA;
use App\Modules\BccCDA\Models\BccCDAPaymentConfirm;
use App\Modules\BccCDA\Models\BccCDAResubmitApp;
use App\Modules\BccCDA\Models\DynamicAttachmentBccCDA;
use App\Modules\BccCDA\Models\RequestQueueBccCDA;
use App\Modules\LsppCDA\Controllers\LsppCdaController;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Log;
use Mockery\Exception;

class BccCdaController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 121;
        $this->aclName = 'BccCDA';
    }

    public function appForm(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CDA BCC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionDPDC-971]</h4>"]);
        }
        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $cda_bcc_service_url = Config('stackholder.CDA_BCC_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("BccCDA::application-form", compact('process_type_id', 'viewMode', 'mode', 'token', 'cda_bcc_service_url')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [BccCDA-75]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($request->get('app_id'));
                $appData = BccCDA::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new BccCDA();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
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
            //  dd($processData->submitted_at);

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
                    $app_doc = DynamicAttachmentBccCDA::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_priority = $docs->doc_priority;
                    $app_doc->short_note = $docs->short_note;
                    $app_doc->attachment_type_id = $docs->attachment_type_id;
                    $app_doc->doc_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->save();
                    $cda_docids[] = $docs->id;
                }
            }
            /* End file uploading */

            // Store payment info
            // Get Payment Configuration
//            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
//                ->where([
//                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
//                    'api_stackholder_payment_configuration.payment_category_id' => 3,
//                    'api_stackholder_payment_configuration.status' => 1,
//                    'api_stackholder_payment_configuration.is_archive' => 0,
//                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            if (!$payment_config) {
//                \Illuminate\Support\Facades\DB::rollback();
//                Session::flash('error', "Payment configuration not found [DPDC-107]");
//                return redirect()->back()->withInput();
//            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'CDA-BCC-' . date("dMY") . '-';
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
                return redirect('cda-bcc/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('cda-bcc/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('cda-bcc/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    public function getMemberId()
    {
        $bcc_cda_api_url = Config('stackholder.CDA_BCC_SERVICE_API_URL');
        $token = $this->getToken();

        $curl = curl_init();
        $user_name = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;

        $request_data = json_encode([
            'userName' => $user_name,
            'userEmail' => Auth::user()->user_email,
            'userPhone' => Auth::user()->user_phone]);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bcc_cda_api_url . "/get-memberid",
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
            $bccCDARequest = new RequestQueueBccCDA();
        } else {
            $bccCDARequest = RequestQueueBccCDA::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        if ($bccCDARequest->status != 1) {
            $appData = BccCDA::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);

            $document_query = DynamicAttachmentBccCDA::where('ref_id', $appData->id)
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

            $paramAppdata['trackingNo'] = $tracking_no;
            $paramAppdata['name'] = "addLucForm";
            $param = [
                "applicantName" => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
                "applicantMobile" => !empty($masterData->applicant_mobile_no) ? $masterData->applicant_mobile_no : '',
                "applicantEmail" => !empty($masterData->applicant_email) ? $masterData->applicant_email : '',
                "applicantAddress" => !empty($masterData->applicant_present_address) ? $masterData->applicant_present_address : '',
                "buildTypeId" => "3",
                "occupancyTypeID" => "3",
                "CityCorpName" => !empty($masterData->city_corporation) ? explode('@', $masterData->city_corporation)[1] : '',
                "BsName" => !empty($masterData->bs_code) ? $masterData->bs_code : '',
                "RsName" => !empty($masterData->rs_code) ? $masterData->rs_code : '',
                "ThanaName" => !empty($masterData->thana_name) ? explode('@', $masterData->thana_name)[1] : '',
                "MouzaName" => !empty($masterData->mouza_name) ? explode('@', $masterData->mouza_name)[2] : '',
                "BlockNo" => !empty($masterData->block_no) ? explode('@', $masterData->block_no)[1] : '',
                "SitNo" => !empty($masterData->seat_no) ? explode('@', $masterData->seat_no)[1] : '',
                "WordNo" => !empty($masterData->ward_no) ? explode('@', $masterData->ward_no)[1] : '',
                "SectorNo" => !empty($masterData->sector_no) ? explode('@', $masterData->sector_no)[1] : '',
                "RoadName" => !empty($masterData->road_name) ? $masterData->road_name : '',
                "PlotMeasure" => !empty($masterData->arm_size_land_plot_amount) ? $masterData->arm_size_land_plot_amount : '',
                "PlotDetails" => !empty($masterData->existing_house_plot_land_details) ? $masterData->existing_house_plot_land_details : '',
                "buildWorkType" => !empty($masterData->proposed_dev_work_type) ? $masterData->proposed_dev_work_type : '',
                "plotArea" => !empty($masterData->total_land_area) ? $masterData->total_land_area : '',
                "armMeasureSouth" => !empty($masterData->arm_size_south) ? $masterData->arm_size_south : '',
                "armMeasureNorth" => !empty($masterData->arm_size_north) ? $masterData->arm_size_north : '',
                "armMeasureEast" => !empty($masterData->arm_size_east) ? $masterData->arm_size_east : '',
                "armMeasureWest" => !empty($masterData->arm_size_west) ? $masterData->arm_size_west : '',
                "floorMeasure" => !empty($masterData->projects_total_area) ? $masterData->projects_total_area : '',
                "flatNumber" => !empty($masterData->flat_per_floor) ? $masterData->flat_per_floor : '',
                "totalFlatNumber" => !empty($masterData->total_accommodation_unit) ? $masterData->total_accommodation_unit : '',
                "totalGroundArea" => !empty($masterData->total_ground_area) ? $masterData->total_ground_area : '',
                "percentGroundArea" => !empty($masterData->percent_ground_area) ? $masterData->percent_ground_area : '',
                "numberofFloor" => !empty($masterData->plinth_total_floor) ? $masterData->plinth_total_floor : '',
                "numberofBasement" => !empty($masterData->basement_total_floor) ? $masterData->basement_total_floor : '',
                "floorUse1" => !empty($masterData->other_usage_1) ? $masterData->other_usage_1 : '',
                "floorUse2" => !empty($masterData->other_usage_2) ? $masterData->other_usage_2 : '',
                "floorUse3" => !empty($masterData->other_usage_3) ? $masterData->other_usage_3 : '',
                "floorUse4" => !empty($masterData->other_usage_4) ? $masterData->other_usage_4 : '',
                "floorUse5" => !empty($masterData->other_usage_5) ? $masterData->other_usage_5 : '',
                "roadInfront" => !empty($masterData->front_road_area) ? $masterData->front_road_area : '',
                "roadInBack" => !empty($masterData->back_road_area) ? $masterData->back_road_area : '',
                "roadInleft" => !empty($masterData->left_road_area) ? $masterData->left_road_area : '',
                "roadInright" => !empty($masterData->right_road_area) ? $masterData->right_road_area : '',
                "previousBuildNumber" => !empty($masterData->previous_build_number) ? $masterData->previous_build_number : '',
                "newBuildNumber" => !empty($masterData->new_build_number) ? $masterData->new_build_number : '',
                "openSpaceFront" => !empty($masterData->open_space_front) ? $masterData->open_space_front : '',
                "openSpaceBack" => !empty($masterData->open_space_back) ? $masterData->open_space_back : '',
                "openSpaceLeft" => !empty($masterData->open_space_left) ? $masterData->open_space_left : '',
                "openSpaceRight" => !empty($masterData->open_space_right) ? $masterData->open_space_right : '',
                "useBasement1" => !empty($masterData->usage_1[0]) ? $masterData->usage_1[0] : '',
                "useBasement2" => !empty($masterData->usage_2[0]) ? $masterData->usage_2[0] : '',
                "useBasement3" => !empty($masterData->usage_3[0]) ? $masterData->usage_3[0] : '',
                "useBasement4" => !empty($masterData->total_floor[0]) ? $masterData->total_floor[0] : '',
                "useGround1" => !empty($masterData->usage_1[1]) ? $masterData->usage_1[1] : '',
                "useGround2" => !empty($masterData->usage_2[1]) ? $masterData->usage_2[1] : '',
                "useGround3" => !empty($masterData->usage_3[1]) ? $masterData->usage_3[1] : '',
                "useGround4" => !empty($masterData->total_floor[1]) ? $masterData->total_floor[1] : '',
                "use2floor1" => !empty($masterData->usage_1[2]) ? $masterData->usage_1[2] : '',
                "use2floor2" => !empty($masterData->usage_2[2]) ? $masterData->usage_2[2] : '',
                "use2floor3" => !empty($masterData->usage_3[2]) ? $masterData->usage_3[2] : '',
                "use2floor4" => !empty($masterData->total_floor[2]) ? $masterData->total_floor[2] : '',
                "use3floor1" => !empty($masterData->usage_1[3]) ? $masterData->usage_1[3] : '',
                "use3floor2" => !empty($masterData->usage_2[3]) ? $masterData->usage_2[3] : '',
                "use3floor3" => !empty($masterData->usage_3[3]) ? $masterData->usage_3[3] : '',
                "use3floor4" => !empty($masterData->total_floor[3]) ? $masterData->total_floor[3] : '',
                "use4floor1" => !empty($masterData->usage_1[4]) ? $masterData->usage_1[4] : '',
                "use4floor2" => !empty($masterData->usage_2[4]) ? $masterData->usage_2[4] : '',
                "use4floor3" => !empty($masterData->usage_3[4]) ? $masterData->usage_3[4] : '',
                "use4floor4" => !empty($masterData->total_floor[4]) ? $masterData->total_floor[4] : '',
                "use5floor1" => !empty($masterData->usage_1[5]) ? $masterData->usage_1[5] : '',
                "use5floor2" => !empty($masterData->usage_2[5]) ? $masterData->usage_2[5] : '',
                "use5floor3" => !empty($masterData->usage_3[5]) ? $masterData->usage_3[5] : '',
                "use5floor4" => !empty($masterData->total_floor[5]) ? $masterData->total_floor[5] : '',
                "use6floor1" => !empty($masterData->usage_1[6]) ? $masterData->usage_1[6] : '',
                "use6floor2" => !empty($masterData->usage_2[6]) ? $masterData->usage_2[6] : '',
                "use6floor3" => !empty($masterData->usage_3[6]) ? $masterData->usage_3[6] : '',
                "use6floor4" => !empty($masterData->total_floor[6]) ? $masterData->total_floor[6] : '',
                "useOtherFloor1" => !empty($masterData->usage_1[7]) ? $masterData->usage_1[7] : '',
                "useOtherFloor2" => !empty($masterData->usage_2[7]) ? $masterData->usage_2[7] : '',
                "useOtherFloor3" => !empty($masterData->usage_3[7]) ? $masterData->usage_3[7] : '',
                "useOtherFloor4" => !empty($masterData->total_floor[7]) ? $masterData->total_floor[7] : '',
                "totalFloorSize" => !empty($masterData->total) ? $masterData->total : '',
                "izaraDolil" => !empty($masterData->owner_documents) ? $masterData->owner_documents : '',
                "govApprovalForm" => !empty($masterData->gov_allocated_land_doc) ? $masterData->gov_allocated_land_doc : '',
                "rosidNumber" => !empty($masterData->fee_receipt) ? $masterData->fee_receipt : '',
                "landClearance" => !empty($masterData->land_usage_exemption) ? $masterData->land_usage_exemption : '',
                "specialProjectCerti" => !empty($masterData->special_project_exemption) ? $masterData->special_project_exemption : '',
                "indemnityBand" => !empty($masterData->indempty_bond) ? $masterData->indempty_bond : '',
                "SoilTestReport" => !empty($masterData->soil_test_report) ? $masterData->soil_test_report : '',
                "farCalculation" => !empty($masterData->far_calculation) ? $masterData->far_calculation : '',
                "otherDEsign" => !empty($masterData->all_designs) ? $masterData->all_designs : '',
                "condition10" => !empty($masterData->action_taken) ? $masterData->action_taken : '',
                "nocOtherDept" => !empty($masterData->authority_exmption) ? $masterData->authority_exmption : '',
                "formSubmitDate" => Carbon::now()->format('Y-m-d'),
                "MemberID" => !empty($appData->member_id) ? $appData->member_id : '',
            ];

//            $paramAppdata['param'] = array_merge($param, $documents);
            $paramAppdata['param'] = $param;

            $bccCDARequest->ref_id = $appData->id;
            if ($statusid == 2) {
                $bccCDARequest->type = 'Resubmission';
            } else {
                $bccCDARequest->type = 'Submission';
            }

            $bccCDARequest->status = 0;   // 10 = payment not submitted
            $bccCDARequest->request_json = json_encode($paramAppdata);
            $bccCDARequest->save();
        }

    }

    public function getToken()
    {
        // Get credentials from database
        $cda_bcc_idp_url = Config('stackholder.CDA_BCC_TOKEN_API_URL');
        $cda_bcc_client_id = Config('stackholder.CDA_BCC_CLIENT_ID');
        $cda_bcc_client_secret = Config('stackholder.CDA_BCC_CLIENT_SECRET');


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $cda_bcc_client_id,
            'client_secret' => $cda_bcc_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$cda_bcc_idp_url");
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

    public function waitForPayment($applicationId)
    {
        return view("BccCDA::wait-for-payment", compact('applicationId', 'paymentId'));
    }

    public function getDocList(Request $request)
    {
        $document = Attachment::where('attachment_list.process_type_id', $this->process_type_id)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.*']);

        $clrDocuments = [];
        if ($request->has('app_id') && $request->get('app_id') != '') {
            $clr_document = DynamicAttachmentBccCDA::where('process_type_id', $this->process_type_id)->where('ref_id', Encryption::decodeId($request->get('app_id')))->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
                $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
            }
        }
        $html = strval(view("BccCDA::documents", compact('document', 'clrDocuments')));
        return response()->json(['html' => $html]);
    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else if ($openMode == 'edit') {
            $viewMode = 'off';
            $mode = '-E-';
        }
        if (!ACL::getAccsessRight('BccCDA', $mode)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($appId);

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftjoin('bcc_cda_apps', 'bcc_cda_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'bcc_cda_apps.sf_payment_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $applicationId)
//                ->whereIn('process_list.company_id', $companyIds)
                ->first([
                    'bcc_cda_apps.*',
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

            $appData = json_decode($appInfo->appdata);


            if ($appInfo->status_id == 1) {

                $app_id = Encryption::encodeId($applicationId);
                $public_html = strval(view("BccCDA::wait-for-payment",
                    compact('app_id')));
                return response()->json(['responseCode' => 1, 'html' => $public_html]);
                //return redirect('licence-applications/name-clearance/submission-response/'.Encryption::encodeId($verifyid));

            }
            if ($appInfo->status_id == 1) {
                return redirect('cda-bcc/check-submission/' . Encryption::encodeId($appInfo->id));
            }
            $token = $this->getToken();
            $cda_bcc_service_url = Config('stackholder.CDA_BCC_SERVICE_API_URL');

            $document = Attachment::where('attachment_list.status', 1)
                ->where('attachment_list.process_type_id', $this->process_type_id)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
            if ($appInfo) {
                $clr_document = DynamicAttachmentBccCDA::where('ref_id', $appInfo->id)->get();
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
            } else {
                $clrDocuments = [];
            }

            $public_html = strval(view("BccCDA::application-form-edit", compact('document', 'appInfo', 'appData', 'viewMode', 'cda_bcc_service_url', 'clrDocuments', 'token', 'mode')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[CDA BCC-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, $openMode = '', Request $request)
    {
        $viewMode = 'on';
        $mode = '-V-';

        if (!ACL::getAccsessRight('BccCDA', $mode)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($appId);
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftjoin('bcc_cda_apps', 'bcc_cda_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'bcc_cda_apps.sf_payment_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $applicationId)
                ->first([
                    'bcc_cda_apps.*',
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

            $appData = json_decode($appInfo->appdata);

            // Resubmission add form Information
            $shortfallAttachments = "";
            if ($appInfo->status_id == 27) {
                $shortfallAttachments = $this->getShortfallAttachments('incoming');
            }

            // Resubmission view form Information
            $resubmissionInfo = "";
            if ($appInfo->status_id == 32) {
                $resubmissionInfo = BccCDAResubmitApp::where('ref_id', $applicationId)->first();
            }

            $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $applicationId)
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
            $cda_bcc_service_url = Config('stackholder.CDA_BCC_SERVICE_API_URL');
            $public_html = strval(view("BccCDA::application-form-view", compact('appInfo', 'appData',
                'viewMode', 'shortfallAttachments', 'resubmissionInfo', 'spPaymentinformation', 'cda_bcc_service_url', 'token', 'mode')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getLine()) . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"
            ]);
        }
    }

    public function getShortfallAttachments($category = 'incoming')
    {
        $bcc_cda_api_url = Config('stackholder.CDA_BCC_SERVICE_API_URL');

        // Get token for API authorization
        $token = $this->getToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $bcc_cda_api_url . "/get-shortfall-attachmentlist",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"category\":\"$category\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result']['data'];
        $attachmentLists = [];
        if ($results != null) {
            foreach ($results as $result) {
                $attachmentLists += [$result["code"] . "@" . $result["description"] => $result["description"]];
            }
        }
        $data = ['responseCode' => 1, 'data' => $attachmentLists];
        return $attachmentLists;
    }

    // store resubmission data
    public function storeResubmitInfo(Request $request)
    {
        try {
            $app_id = Encryption::decodeId($request->app_id);
            $master_info = BccCDA::where('id', $app_id)->first(['bc_id']);

            DB::beginTransaction();

            $cda_resubmit_app = BccCDAResubmitApp::where('ref_id', $app_id)->first();
            if ($cda_resubmit_app == null) {
                $cda_resubmit_app = new BccCDAResubmitApp();
            }
            $cda_resubmit_app->ref_id = $app_id;

            $incoming_type = explode('@', $request->get('incoming_type'));
            $cda_resubmit_app->incoming_type = !empty($incoming_type[0]) ? $incoming_type[0] : '';
            $cda_resubmit_app->incoming_type_desc = !empty($incoming_type[1]) ? $incoming_type[1] : '';

            $cda_resubmit_app->incoming_reason = $request->get('remarks');


            $cda_resubmit_app->file_title_1 = $request->get('file_title_1');
            $cda_resubmit_app->file_title_2 = $request->get('file_title_2');
            $cda_resubmit_app->file_title_3 = $request->get('file_title_3');
            $cda_resubmit_app->file_title_4 = $request->get('file_title_4');
            $cda_resubmit_app->file_title_5 = $request->get('file_title_5');

            // file upload
            $prefix = date('Y_');
            $docBaseUrl = url('uploads');

            // file 1
            $_file = $request->file('file_link_1');
            if ($request->hasFile('file_link_1')) {
                $i = strripos($_file->getClientOriginalName(), '.');
                $ext = strtolower(substr($_file->getClientOriginalName(), $i + 1));
                $original_file = trim(sprintf("%s", uniqid($prefix) . "_cda_resubmit_attachment" . "." . $ext));
                $file_type = $_file->getClientMimeType();
                if ($file_type != 'application/pdf') {
                    Session::flash('error', 'Attachment file must be in PDF format');
                    return redirect()->back()->withInput();
                }
                $authoFileUrl = $original_file;
                $_file->move('uploads', $authoFileUrl);

                $cda_resubmit_app->file_link_1 = $docBaseUrl . "/" . $authoFileUrl;
            } else {
                $cda_resubmit_app->file_link_1 = "";
            }
            // end file 1

            // file 1
            $_file = $request->file('file_link_2');
            if ($request->hasFile('file_link_2')) {
                $i = strripos($_file->getClientOriginalName(), '.');
                $ext = strtolower(substr($_file->getClientOriginalName(), $i + 1));
                $original_file = trim(sprintf("%s", uniqid($prefix) . "_cda_resubmit_attachment" . "." . $ext));
                $file_type = $_file->getClientMimeType();
                if ($file_type != 'application/pdf') {
                    Session::flash('error', 'Attachment file must be in PDF format');
                    return redirect()->back()->withInput();
                }
                $authoFileUrl = $original_file;
                $_file->move('uploads', $authoFileUrl);

                $cda_resubmit_app->file_link_2 = $docBaseUrl . "/" . $authoFileUrl;
            } else {
                $cda_resubmit_app->file_link_2 = "";
            }
            // end file 1

            // file 1
            $_file = $request->file('file_link_3');
            if ($request->hasFile('file_link_3')) {
                $i = strripos($_file->getClientOriginalName(), '.');
                $ext = strtolower(substr($_file->getClientOriginalName(), $i + 1));
                $original_file = trim(sprintf("%s", uniqid($prefix) . "_cda_resubmit_attachment" . "." . $ext));
                $file_type = $_file->getClientMimeType();
                if ($file_type != 'application/pdf') {
                    Session::flash('error', 'Attachment file must be in PDF format');
                    return redirect()->back()->withInput();
                }
                $authoFileUrl = $original_file;
                $_file->move('uploads', $authoFileUrl);

                $cda_resubmit_app->file_link_3 = $docBaseUrl . "/" . $authoFileUrl;
            } else {
                $cda_resubmit_app->file_link_3 = "";
            }
            // end file 1

            // file 1
            $_file = $request->file('file_link_4');
            if ($request->hasFile('file_link_4')) {
                $i = strripos($_file->getClientOriginalName(), '.');
                $ext = strtolower(substr($_file->getClientOriginalName(), $i + 1));
                $original_file = trim(sprintf("%s", uniqid($prefix) . "_cda_resubmit_attachment" . "." . $ext));
                $file_type = $_file->getClientMimeType();
                if ($file_type != 'application/pdf') {
                    Session::flash('error', 'Attachment file must be in PDF format');
                    return redirect()->back()->withInput();
                }
                $authoFileUrl = $original_file;
                $_file->move('uploads', $authoFileUrl);

                $cda_resubmit_app->file_link_4 = $docBaseUrl . "/" . $authoFileUrl;
            } else {
                $cda_resubmit_app->file_link_4 = "";
            }
            // end file 1

            // file 1
            $_file = $request->file('file_link_5');
            if ($request->hasFile('file_link_5')) {
                $i = strripos($_file->getClientOriginalName(), '.');
                $ext = strtolower(substr($_file->getClientOriginalName(), $i + 1));
                $original_file = trim(sprintf("%s", uniqid($prefix) . "_cda_resubmit_attachment" . "." . $ext));
                $file_type = $_file->getClientMimeType();
                if ($file_type != 'application/pdf') {
                    Session::flash('error', 'Attachment file must be in PDF format');
                    return redirect()->back()->withInput();
                }
                $authoFileUrl = $original_file;
                $_file->move('uploads', $authoFileUrl);

                $cda_resubmit_app->file_link_5 = $docBaseUrl . "/" . $authoFileUrl;
            } else {
                $cda_resubmit_app->file_link_5 = "";
            }
            // end file 1

            //  end file upload

            // request json
            $request_data = [];
            $request_data['name'] = 'lucShortFallIncoming';
            $request_data['param']['bccID'] = $master_info->bc_id;
            $request_data['param']['incomeingType'] = $cda_resubmit_app->incoming_type;
            $request_data['param']['incomeingReason'] = $cda_resubmit_app->incoming_reason;
            $request_data['param']['fileTitle1'] = $cda_resubmit_app->file_title_1 ? $cda_resubmit_app->file_title_1 : "";
            $request_data['param']['fileLink1'] = $cda_resubmit_app->file_link_1 ? $cda_resubmit_app->file_link_1 : "";
            $request_data['param']['fileTitle2'] = $cda_resubmit_app->file_title_2 ? $cda_resubmit_app->file_title_2 : "";
            $request_data['param']['fileLink2'] = $cda_resubmit_app->file_link_2 ? $cda_resubmit_app->file_link_2 : "";
            $request_data['param']['fileTitle3'] = $cda_resubmit_app->file_title_3 ? $cda_resubmit_app->file_title_3 : "";
            $request_data['param']['fileLink3'] = $cda_resubmit_app->file_link_3 ? $cda_resubmit_app->file_link_3 : "";
            $request_data['param']['fileTitle4'] = $cda_resubmit_app->file_title_4 ? $cda_resubmit_app->file_title_4 : "";
            $request_data['param']['fileLink4'] = $cda_resubmit_app->file_link_4 ? $cda_resubmit_app->file_link_4 : "";
            $request_data['param']['fileTitle5'] = $cda_resubmit_app->file_title_5 ? $cda_resubmit_app->file_title_5 : "";
            $request_data['param']['fileLink5'] = $cda_resubmit_app->file_link_5 ? $cda_resubmit_app->file_link_5 : "";

            $cda_resubmit_app->request = json_encode($request_data);
            //end request json
            $cda_resubmit_app->status = 0;
            $cda_resubmit_app->save();

            $process_data = ProcessList::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->first();
            if ($process_data != null) {
                $process_data->status_id = 32;
                $process_data->save();
            }

            DB::commit();
            Session::flash('success', 'Application Resubmitted successfully!');
            return redirect('licence-applications/cda-bcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [CDA-BCC-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function cdaBCCpayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));

        $cdadata = BccCDA::leftJoin('process_list', 'process_list.ref_id', '=', 'bcc_cda_apps.id')
            ->where('bcc_cda_apps.id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first([
                'bcc_cda_apps.*',
                'process_list.tracking_no',
                'process_list.status_id',
            ]);
        if (empty($cdadata)) {
            Session::flash('error', "Your CDA BCC Record not found [BCC-CDA-1125]");
            return \redirect()->back();
        }

        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [CDA-BCC-1123]");
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
        //        $cdaAccout = $cdadata->cda_processing_fee;
        $cdaAccout =  $cdadata->cda_payment_account_no;

        $cdaPaymentInfo = array(
            'receiver_account_no' => $cdaAccout,
            'amount' => $cdadata->processing_fee,
            'distribution_type' => $stackholderDistibutionType,
        );
        $stackholderMappingInfo[] = $cdaPaymentInfo;

        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');

        // Get SBL payment configuration
        DB::beginTransaction();
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appId;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = $cdadata->tracking_no;
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->ref_tran_no = $cdadata->tracking_no . "-01";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = $request->get('sfp_contact_name');
        $paymentInfo->contact_email = $request->get('sfp_contact_email');
        $paymentInfo->contact_no = $request->get('sfp_contact_phone');
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInfo->save();
        BccCDA::where('id', $appId)->update(['sf_payment_id' => $paymentInfo->id]);
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
//        DB::commit();
        DB::commit();
        echo $cdadata->sf_payment_id . '' . $request->get('actionBtn');
        if (empty($cdadata)) {
            Session::flash('error', "Your CDA BCC application not found [CDA-BCC-1125]");
            return \redirect()->back();
        }
        if ($request->get('actionBtn') == 'Payment' && $paymentInfo->id) {
            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
        }
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);
        $paymentInfoData = RequestQueueBccCDA::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $decodedResponse = json_decode($paymentInfoData->response_json);
        $status = intval($paymentInfoData->status);
        if ($status == 1) {
            $applyPaymentfee = $decodedResponse->data->resonse->result->ProcessingFee;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "BccCDA::paymentInfo",
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

    public function uploadDocument()
    {
        return View::make('BccCDA::ajaxUploadFile');
    }

    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return \redirect()->back();
        }
        DB::beginTransaction();
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('bcc_cda_apps', 'bcc_cda_apps.id', '=', 'process_list.ref_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
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
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            $processData->status_id = 1;
            $processData->submitted_at = date('Y-m-d H:i:s');
            $processData->read_status = 0;
            $processData->process_desc = 'Service Fee Payment completed successfully.';
            $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
            $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
            $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
            $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;
            $appInfo['status_id'] = $processData->status_id;
//            dd($appInfo);
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            $processData->save();

            $appData = BccCDA::where('id', $processData->ref_id)->first();

//            if ($paymentInfo->payment_category_id == 2) {  //type 3 for application feee
            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            foreach ($data2 as  $value) {
                $singleResponse = json_decode($value->verification_response);
                $rData0['name'] = "updateLucPayment";
                $rData0['param']['bccID'] = $appData->bc_id;
                $rData0['param']['ChallanNo'] = $singleResponse->TransactionId;
                $rData0['param']['TxnID'] = $singleResponse->TransactionId;
                $rData0['param']['TxnAmount'] = $singleResponse->TranAmount;
                $rData0['param']['TxnDate'] = Carbon::parse($singleResponse->TransactionDate)->format('Y-m-d');


            }
            $request_data = json_encode($rData0);
            $paymentConfirm = new BccCDAPaymentConfirm();
            $paymentConfirm->request = $request_data;
            $paymentConfirm->ref_id = $paymentInfo->app_id;
            $paymentConfirm->oss_tracking_no = $processData->tracking_no;
            $paymentConfirm->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('cda-bcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDAPAYMENT: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CDA-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1021]');
            return redirect('process/licence-applications/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
                'process_type.form_id',
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
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            DB::beginTransaction();

            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->status_id = 16; // Submitted
                $processData->submitted_at = date('Y-m-d H:i:s');
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                Session::flash('success', 'Payment Confirm successfully');
                $cdapayment = new CdaPayment();
                $cdapayment->ref_id = $processData->ref_id;
                $cdapayment->luc_id = $processData->luc_id;
                $cdapayment->transaction_id = $paymentInfo->transaction_id;
                $cdapayment->challan_no = $paymentInfo->transaction_id;
//                $cdapayment->transaction_amount =  $processData->cda_processing_fee;
                $cdapayment->transaction_amount = $paymentInfo->pay_amount;
                $cdapayment->transaction_date = $paymentInfo->payment_date;
                $cdapayment->save();
            }
            /*
            * Government payment submit
            * */
            /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 3) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user
                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 2) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }
            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/licence-applications/cda-bcc/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDACOUNTERPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CDA-1022]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1022]');
            return redirect('process/licence-applications/cda-bcc/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

}
