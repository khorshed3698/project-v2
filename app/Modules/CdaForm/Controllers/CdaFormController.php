<?php

namespace App\Modules\CdaForm\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\CdaForm\Models\CdaForm;
use App\Modules\CdaForm\Models\CdaPayment;
use App\Modules\CdaForm\Models\CdaRequestQueue;
use App\Modules\CdaForm\Models\CdaResubmitApp;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Log;
use Mockery\Exception;

class CdaFormController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 110;
        $this->aclName = 'CdaForm';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CDA-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CDA-971]</h4>"]);
        }
        try {
            $token = $this->getCdaToken();
            $cda_api_url = Config('stackholder.cda_api_url');
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
//            $public_html = strval(view("CdaForm::application-form",compact('document', 'landUserList', 'cityList', 'thanaList', 'mouzaList', 'blockList', 'sitList', 'wordList', 'sectorList')));
            $public_html = strval(view("CdaForm::application-form", compact('document','token','cda_api_url')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function getDocList(Request $request)
    {
        $attachment_key = $request->get('attachment_key');
        $viewMode = $request->get('viewMode');

        $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->where('attachment_type.key', $attachment_key)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.*']);

        if ($request->has('app_id') && $request->get('app_id') != '') {
            $clr_document = AppDocuments::where('process_type_id', $this->process_type_id)->where('ref_id', Encryption::decodeId($request->get('app_id')))->get();
//                dd($clr_document);
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['doucument_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }
        } else {
            $clrDocuments = [];
        }

        $html = strval(view("CdaForm::documents",
            compact('document', 'viewMode', 'clrDocuments')));
        return response()->json(['html' => $html]);
    }


    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CDA-99]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        if ($request->get('actionBtn') != 'draft') {
            $rules = [
//                's' => 'required'
            ];

            $messages = [];

            $this->validate($request, $rules, $messages);
        }

        if (!ACL::getAccsessRight('CdaForm', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }


        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = CdaForm::find($decodedId);
//                dd($appData->id);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new CdaForm();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $landUse = explode('@', $request->get('land_use_category_id'));
            $appData->land_use_category_id = !empty($landUse[0]) ? $landUse[0] : '';
            $appData->land_type_name = !empty($landUse[1]) ? $landUse[1] : '';

            $landUseDetails = $request->land_use_sub_cat_id;
            $land_use_sub_cat_ids = '';
            $land_use_sub_type_names = '';
            if (count($landUseDetails)) {
                foreach ($landUseDetails as $landUseDetail) {
                    $land_use_sub_cat_ids .= explode('^', $landUseDetail)[0] . '^';
                    $land_use_sub_type_names .= explode('^', $landUseDetail)[1] . '^';
                }
                $land_use_sub_cat_ids = rtrim($land_use_sub_cat_ids, '^');
                $land_use_sub_type_names = rtrim($land_use_sub_type_names, '^');
            }

            $appData->document_type = $request->get('document_type');
            $appData->land_use_sub_cat_id = $land_use_sub_cat_ids;
            $appData->land_sub_type_name = $land_use_sub_type_names;

//            $landUseDetails = explode('@', $request->get('land_use_sub_cat_id'));
//            $appData->land_use_sub_cat_id = !empty($landUseDetails[0]) ? $landUseDetails[0] : '';
//            $appData->land_sub_type_name = !empty($landUseDetails[1]) ? $landUseDetails[1] : '';

            $cityCorporation = explode('@', $request->get('city_corporation_id'));
            $appData->city_corporation_id = !empty($cityCorporation[0]) ? $cityCorporation[0] : '';
            $appData->city_corporation_name = !empty($cityCorporation[1]) ? $cityCorporation[1] : '';

            $mouza = explode('@', $request->get('mouza_id'));
            $appData->mouza_id = !empty($mouza[0]) ? $mouza[0] : '';
            $appData->mouza_name = !empty($mouza[1]) ? $mouza[1] : '';

            $thana = explode('@', $request->get('thana_id'));
            $appData->thana_id = !empty($thana[0]) ? $thana[0] : '';
            $appData->thana_name = !empty($thana[1]) ? $thana[1] : '';

            $block = explode('@', $request->get('block_id'));
            $appData->block_id = !empty($block[0]) ? $block[0] : '';
            $appData->block_no = !empty($block[1]) ? $block[1] : '';

            $seat = explode('@', $request->get('seat_id'));
            $appData->seat_id = !empty($seat[0]) ? $seat[0] : '';
            $appData->seat_no = !empty($seat[1]) ? $seat[1] : '';

            $ward = explode('@', $request->get('ward_id'));
            $appData->ward_id = !empty($ward[0]) ? $ward[0] : '';
            $appData->ward_no = !empty($ward[1]) ? $ward[1] : '';

            $sector = explode('@', $request->get('sector_id'));
            $appData->sector_id = !empty($sector[0]) ? $sector[0] : '';
            $appData->sector_no = !empty($sector[1]) ? $sector[1] : '';

            $appData->applicant_name = $request->get('applicant_name');
            $appData->applicant_father_name = $request->get('applicant_father_name');
            $appData->applicant_tin_no = $request->get('applicant_tin_no');
            $appData->applicant_nid_no = $request->get('applicant_nid_no');
            $appData->applicant_mobile_no = $request->get('applicant_mobile_no');
            $appData->applicant_email = $request->get('applicant_email');
            $appData->applicant_present_address = $request->get('applicant_present_address');
            $appData->suggested_use_land_plot = $request->get('suggested_use_land_plot');

            $appData->bs = $request->get('bs');
            $appData->rs = $request->get('rs');

            $appData->road_name = $request->get('road_name');
            $appData->arm_size_land_plot_amount = $request->get('arm_size_land_plot_amount');
            $appData->existing_house_plot_land_details = $request->get('existing_house_plot_land_details');
            $appData->plot_ownership_type = $request->get('plot_ownership_type');
            $appData->plot_source_date = $request->get('plot_source_date');
            if ($request->plot_source_date != '') {
                $appData->plot_source_date = CommonFunction::changeDateFormat($request->plot_source_date, true);
            }
            $appData->plot_ownership_source = $request->get('plot_ownership_source');
            if ($request->registration_date != '') {
                $appData->registration_date = CommonFunction::changeDateFormat($request->registration_date, true);
            } else {
                $appData->registration_date = '';
            }

            $appData->record_no = $request->get('record_no');
            $appData->pre_land_use = $request->get('pre_land_use');
            $appData->pre_land_use_radius_250m = $request->get('pre_land_use_radius_250m');
            $appData->plot_nearest_road_name = $request->get('plot_nearest_road_name');
            $appData->nearest_road_amplitude = $request->get('nearest_road_amplitude');
            $appData->plot_connecting_road_name = $request->get('plot_connecting_road_name');
            $appData->connecting_road_amplitude = $request->get('connecting_road_amplitude');
            $appData->{'250m_main_road'} = $request->get('250m_main_road');
            $appData->{'250m_hat_bazaar'} = $request->get('250m_hat_bazaar');
            $appData->{'250m_railway_station'} = $request->get('250m_railway_station');
            $appData->{'250m_river_port'} = $request->get('250m_river_port');
            $appData->{'250m_airport'} = $request->get('250m_airport');
            $appData->{'250m_pond'} = $request->get('250m_pond');
            $appData->{'250m_wetland'} = $request->get('250m_wetland');
            $appData->{'250m_natural_waterway'} = $request->get('250m_natural_waterway');
            $appData->{'250m_flood_control_stream'} = $request->get('250m_flood_control_stream');
            $appData->{'250m_forest'} = $request->get('250m_forest');
            $appData->{'250m_park_playground'} = $request->get('250m_park_playground');
            $appData->{'250m_hill'} = $request->get('250m_hill');
            $appData->{'250m_slope'} = $request->get('250m_slope');
            $appData->{'250m_historical_imp_site'} = $request->get('250m_historical_imp_site');
            $appData->{'250m_military_installation'} = $request->get('250m_military_installation');
            $appData->{'250m_key_point_installation'} = $request->get('250m_key_point_installation');
            $appData->{'250m_limited_dev_area'} = $request->get('250m_limited_dev_area');
            $appData->{'25m_special_area'} = $request->get('25m_special_area');
            $appData->plot_condition_by_adjacent_road = $request->get('plot_condition_by_adjacent_road');
            $appData->land_use_north = $request->get('land_use_north');
            $appData->land_use_south = $request->get('land_use_south');
            $appData->land_use_east = $request->get('land_use_east');
            $appData->land_use_west = $request->get('land_use_west');
            $appData->accept_terms = $request->get('accept_terms');
            $appData->other_necessary_info = $request->get('other_necessary_info');

//dd($appData);
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
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();


            $document_type = $request->get('document_type');
            $attachment_key = "cda_";
            if ($document_type == 1) {
                $attachment_key .= "mp";
            } else if ($document_type == 2) {
                $attachment_key .= "pw";
            } else if ($document_type == 3) {
                $attachment_key .= "ap";
            }

            if (isset($document_type)) {
                $doc_row = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.id', 'attachment_list.doc_name']);

                ///Start file uploading

                if (count($doc_row)>0) {
                    foreach ($doc_row as $docs) {
                        $app_doc = AppDocuments::firstOrNew([
                            'process_type_id' => $this->process_type_id,
                            'ref_id' => $appData->id,
                            'doc_info_id' => $docs->id
                        ]);
                        $app_doc->doc_name = $docs->doc_name;
                        $app_doc->doc_file_path = $request->get('validate_field_' . $docs->id);
                        $app_doc->save();
                        $cda_docids[] = $docs->id;
                    }

                    if (count($cda_docids) > 0) {
//                        $aa = AppDocuments::where('ref_id', $appData->id)
//                            ->where('process_type_id',$this->process_type_id)
//                            ->where('ref_id',$appData->id)
//                            ->whereNotIn('id', $cda_docids)
//                            ->get();
//                        dd($aa);
                    }
                } /* End file uploading */

            }


            // Generate Tracking No for Submitted application
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                $trackingPrefix = 'CDA-' . date("dMY") . '-';
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
            $ownership = [3 => 'ব্যক্তি', 2 => 'যৌথ', 1 => 'আম মোক্তার'];

            if ($request->get('actionBtn') != "draft") {
                $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                    ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('app_documents.ref_id', $appData->id)
                    ->where('app_documents.process_type_id', $this->process_type_id)
                    ->where('app_documents.doc_file_path', '!=', '')
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

                $data = array();
                $reqData = array(
                    'landTypID' => $appData->land_use_category_id,
                    'occupancyType' => $appData->land_use_sub_cat_id,
                    'applicantName' => $appData->applicant_name,
                    'applicantFatherName' => $appData->applicant_father_name,
                    'applicantMobile' => $appData->applicant_mobile_no,
                    'applicantEmail' => $appData->applicant_email,
                    'applicantAddress' => $appData->applicant_present_address,
                    'buildTypeId' => $appData->suggested_use_land_plot,
                    'CityCorpName' => $appData->city_corporation_id,
                    'BsName' => $appData->bs,
                    'RsName' => $appData->rs,
                    'ThanaName' => $appData->thana_id,
                    'MouzaName' => $appData->mouza_id,
                    'BlockNo' => $appData->block_id,
                    'SitNo' => $appData->seat_id,
                    'WordNo' => $appData->ward_id,
                    'SectorNo' => $appData->sector_id,
                    'RoadName' => $appData->road_name,
                    'PlotMeasure' => $appData->arm_size_land_plot_amount,
                    'PlotDetails' => $appData->existing_house_plot_land_details,
                    'Ownership' => isset($appData->plot_ownership_type) ? $ownership[$appData->plot_ownership_type] : '',  // Will be changed to text
                    'OwnershipDetails' => $appData->plot_ownership_source,
                    'OwnershipDate' => $appData->plot_source_date,
                    'RegDate' => $appData->registration_date,
                    'RegRecord' => $appData->record_no,
                    'CurrentLanduse' => $appData->pre_land_use,
                    'IncludedRadius' => $appData->pre_land_use_radius_250m,
                    'MainroadName' => $appData->plot_nearest_road_name,
                    'PlotroadName' => $appData->plot_connecting_road_name,
                    'mainRoadWidth' => $appData->nearest_road_amplitude,
                    'plotRoadWidth' => $appData->connecting_road_amplitude,
                    'Mainroad' => $appData->{'250m_main_road'},
                    'Market' => $appData->{'250m_hat_bazaar'},
                    'RailStation' => $appData->{'250m_railway_station'},
                    'RiverPort' => $appData->{'250m_river_port'},
                    'AirPort' => $appData->{'250m_airport'},
                    'Pond' => $appData->{'250m_pond'},
                    'Swamp' => $appData->{'250m_wetland'},
                    'NaturalWaterways' => $appData->{'250m_natural_waterway'},
                    'FloodControl' => $appData->{'250m_flood_control_stream'},
                    'Forests' => $appData->{'250m_forest'},
                    'Park' => $appData->{'250m_park_playground'},
                    'Hill' => $appData->{'250m_hill'},
                    'Shield' => $appData->{'250m_slope'},
                    'HistoricalSites' => $appData->{'250m_historical_imp_site'},
                    'Military' => $appData->{'250m_military_installation'},
                    'KeyPoint' => $appData->{'250m_key_point_installation'},
                    'DevZones' => $appData->{'250m_limited_dev_area'},
                    'SpecialArea' => $appData->{'25m_special_area'},
                    'AdjacentRoad' => $appData->plot_condition_by_adjacent_road,
                    'PlotNorthSide' => $appData->land_use_north,
                    'PlotSouthSide' => $appData->land_use_south,
                    'PlotEastSide' => $appData->land_use_east,
                    'PlotWestSide' => $appData->land_use_west,
                    'OtherInformation' => $appData->other_necessary_info,
                    'formSubmitDate' => date('Y-m-d', strtotime($appData->created_at)),
                    'MemberID' => $this->getMemberId(), // Need to clarify
                    "applicantTIN" => $appData->applicant_tin_no,
                    "applicantNID" => $appData->applicant_nid_no,
                    "attachedType" => $appData->document_type,
                );


                $requestData = array_merge($reqData, $documents);
                if ($processData->status_id == 2) {
                    $resubmitRequreData = array(
                        'LucID' => "$appData->luc_id",
                        'incomeingType' => "10014",
                        'incomeingReason' => "Incoming Reason Here"
                    );
                    $requestData = array_merge($requestData, $resubmitRequreData);
                }

                $memberID = $this->getMemberId();

                $data['trackingNo'] = $tracking_no;

                if ($processData->status_id == 2) {
                    $data['name'] = 'lucShortFallApplication1010Incoming';
                } else {
                    $data['name'] = 'addLucForm';
                }
                $data['param'] = $requestData;
                if ($processData->status_id == 2) {
                    $submission_type = "RESUBMISSION_REQUEST";
                } else {
                    $submission_type = "SUBMISSION_REQUEST";
                }
//                dd($data);
                if ($processData->status_id == 2) {
                    $CDARequest = new CdaRequestQueue();
                } else {
                    $CDARequest = CdaRequestQueue::firstOrNew([
                        'ref_id' => $appData->id
                    ]);
                }
                if ($CDARequest->status != 1) {
                    $CDARequest->type = $submission_type;
                    $CDARequest->ref_id = $appData->id;
                    $CDARequest->member_id = $memberID;
                    $CDARequest->request_json = json_encode($data);
                    $CDARequest->save();
                }
            }
//dd($requestData);

            DB::commit();
            if ($request->get('actionBtn') != "draft") {
                return redirect('cda-form/check-payment/' . Encryption::encodeId($processData->ref_id));
            }
            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
                return redirect('process/licence-applications/cda-form/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [IP-1023]');
            }
            // return redirect('licence-applications/individual-licence');
            return redirect('licence-applications/cda-form/list/' . Encryption::encodeId($processData->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . '@' . $e->getMessage());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [IP-1025]");
            return redirect()->back()->withInput();
        }

    }

    public function getMemberId()
    {
        $cda_api_url = env('cda_api_url');
        $token = $this->getCdaToken();

        $curl = curl_init();
        $user_name = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;

        $request_data = json_encode([
            'userName' => $user_name,
            'userEmail' => Auth::user()->user_email,
            'userPhone' => Auth::user()->user_phone]);

        $token = $this->getCdaToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-memberid",
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
        if (!ACL::getAccsessRight('CdaForm', $mode)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($appId);

//            $landUseList = $this->getLandUseList();
//            $cityList = $this->getCityList();
//            $thanaList = $this->getThanaList();
//            $mouzaList = $this->getMouzaList();
//            $blockList = $this->getBlockList();
//            $sitList = $this->getSitList();
//            $wardList = $this->getWardList();
//            $sectorList = $this->getSectorList();

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftjoin('cda_apps', 'cda_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'cda_apps.sf_payment_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $applicationId)
//                ->whereIn('process_list.company_id', $companyIds)
                ->first([
                    'cda_apps.*',
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

            $attachment_key = "cda_";
            if ($appInfo->document_type == 1) {
                $attachment_key .= "mp";
            } else if ($appInfo->document_type == 2) {
                $appInfo->document_type .= "pw";
            } else if ($appInfo->document_type == 3) {
                $attachment_key .= "ap";
            }
            $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
            if ($appInfo) {
                $clr_document = AppDocuments::where('process_type_id', $this->process_type_id)->where('ref_id', $appInfo->id)->get();
//                dd($clr_document);
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_info_id]['doucument_id'] = $documents->id;
                    $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
                    $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
                }
            } else {
                $clrDocuments = [];
            }
//            dd($clrDocuments);

            // Resubmission add form Information
            $shortfallAttachments = "";
            if ($appInfo->status_id == 27) {
                $shortfallAttachments = $this->getShortfallAttachments('incoming');
            }

            // Resubmission view form Information
            $resubmissionInfo = "";
            if ($appInfo->status_id == 2) {
                $resubmissionInfo = CdaResubmitApp::where('ref_id', $applicationId)->first();
            }


            $token = $this->getCdaToken();
            $cda_api_url = Config('stackholder.cda_api_url');
            $public_html = strval(view("CdaForm::application-form-edit", compact('document', 'appInfo',
                'viewMode', 'clrDocuments', 'shortfallAttachments', 'resubmissionInfo', 'cda_api_url', 'token')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"
            ]);
        }
    }


    public function getShortfallAttachments($category = 'incoming')
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization

        $token = $this->getCdaToken();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-shortfall-attachmentlist",
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
//        dd($results);
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
//        dd($request->all());
        try {
            $app_id = Encryption::decodeId($request->app_id);
            $master_info = CdaForm::where('id', $app_id)->first(['luc_id']);

            DB::beginTransaction();

            $cda_resubmit_app = CdaResubmitApp::where('ref_id', $app_id)->first();
            if ($cda_resubmit_app == null) {
                $cda_resubmit_app = new CdaResubmitApp();
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
            $request_data['param']['LucID'] = $master_info->luc_id;
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
                $process_data->status_id = 22;
                $process_data->save();
            }

            DB::commit();
            Session::flash('success', 'Application Resubmitted successfully!');
            // return redirect('licence-applications/individual-licence');
            return redirect('licence-applications/cda-form/list/' . Encryption::encodeId($this->process_type_id));

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [CDA-1025]");
            return redirect()->back()->withInput();
        }
    }

    // Get token for authorization
    public function getCdaToken()
    {
        // Get credentials from env
        $cda_idp_url = env('cda_idp_url');
        $cda_client_id = env('cda_client_id');
        $cda_client_secret = env('cda_client_secret');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $cda_client_id,
            'client_secret' => $cda_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$cda_idp_url");
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

    // Get Land List From API
    public function getLandUseList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getLandUseList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
//        dd($results);
        $landUseList = [];
        foreach ($results as $result) {
            $landUseList += [$result["land_type_id"] . "@" . $result["land_type_name"] => $result["land_type_name"]];
        }
        $data = ['responseCode' => 1, 'data' => $landUseList];
        return response()->json($data);
    }

    // Get Land List From API
    public function getLandUseDetailList($landCategory)
    {
//        dd($landCategory);
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-landusedetaillist",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n    \"land_type_id\":$landCategory\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result']['data'];
//        dd($results);
        $landUseDetailsList = [];
        foreach ($results as $result) {
            $landUseDetailsList += [$result["land_sub_id"] . "^" . $result["land_sub_name"] . ':' . $result["land_use_detail"] => $result["land_sub_name"] . ':' . $result["land_use_detail"]];
        }
        $data = ['responseCode' => 1, 'data' => $landUseDetailsList];

        return response()->json($data);
    }

    public function cdapayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));

        $cdadata = CdaForm::leftJoin('process_list', 'process_list.ref_id', '=', 'cda_apps.id')
            ->where('cda_apps.id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first([
                'cda_apps.*',
                'process_list.tracking_no',
                'process_list.status_id',
            ]);
        if (empty($cdadata)) {
            Session::flash('error', "Your CDA Record not found [CDA-1125]");
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
            Session::flash('error', "Payment configuration not found [CDA-1123]");
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

        $cdaAccout =  $cdadata->cda_payment_account_no;

        $cdaPaymentInfo = array(
            'receiver_account_no' => $cdaAccout,
            'amount' => $cdadata->cda_processing_fee,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $cdaPaymentInfo;

        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');//        dd($pay_amount);


        // Get SBL payment configuration
        DB::beginTransaction();
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appId;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->app_tracking_no = $cdadata->tracking_no;
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->ref_tran_no = $cdadata->tracking_no . "-01";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = CommonFunction::getUserFullName();
        $paymentInfo->contact_email = Auth::user()->user_email;
        $paymentInfo->contact_no = Auth::user()->user_phone;
        $paymentInfo->address = Auth::user()->road_no;
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();
        CdaForm::where('id', $appId)->update(['sf_payment_id' => $paymentInfo->id]);


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
        echo $cdadata->sf_payment_id . '' . $request->get('actionBtn');
        if (empty($cdadata)) {
            Session::flash('error', "Your CDA application not found [CDA-1125]");
            return \redirect()->back();
        }
        if ($request->get('actionBtn') == 'Payment' && $paymentInfo->id) {
            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
        }
    }

    // Get Land List From API
    public function getSectorList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getSectorList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $sectorList = [];
        foreach ($results as $result) {
            $sectorList += [$result["sector_id"] . "@" . $result["sector_no"] => $result["sector_no"]];
        }
        $data = ['responseCode' => 1, 'data' => $sectorList];
        return response()->json($data);
    }


    // Get Land List From API
    public function getCityList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getCityList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $cityList = [];
        foreach ($results as $result) {
            $cityList += [$result["city_corp_id"] . "@" . $result["city_corp_name"] => $result["city_corp_name"]];
        }
        $data = ['responseCode' => 1, 'data' => $cityList];
        return response()->json($data);
    }


    // Get Land List From API
    public function getThanaList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getThanaList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $thanaList = [];
        foreach ($results as $result) {
            $thanaList += [$result["thana_id"] . "@" . $result["thana_name"] => $result["thana_name"]];
        }
        $data = ['responseCode' => 1, 'data' => $thanaList];
        return response()->json($data);
    }


    // Get Land List From API
    public function getMouzaList($thanaId)
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-mouzalist",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\n    \"thana_id\":$thanaId\n}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result']['data'];
//        dd($results);
        $mouzaList = [];
        foreach ($results as $result) {
            $mouzaList += [$result["mouza_id"] . "@" . $result["mouza_name"] => $result["mouza_name"]];
        }
        $data = ['responseCode' => 1, 'data' => $mouzaList];
        return response()->json($data);
    }

    // Get Land List From API
    public function getBlockList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getBlockList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $blockList = [];
        foreach ($results as $result) {
            $blockList += [$result["block_id"] . "@" . $result["block_no"] => $result["block_no"]];
        }
        $data = ['responseCode' => 1, 'data' => $blockList];
        return response()->json($data);
    }

    // Get Land List From API
    public function getSitList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getSitList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $sitList = [];
        foreach ($results as $result) {
            $sitList += [$result["sit_id"] . "@" . $result["sit_no"] => $result["sit_no"]];
        }
        $data = ['responseCode' => 1, 'data' => $sitList];
        return response()->json($data);
    }

    // Get Land List From API
    public function getWardList()
    {
        $cda_api_url = env('cda_api_url');

        // Get token for API authorization
        $token = $this->getCdaToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_api_url . "get-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"name\":\"getWardList\"}",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data']['resonse']['result'];
        $wardList = [];
        foreach ($results as $result) {
            $wardList += [$result["word_id"] . "@" . $result["word_name"] => $result["word_name"]];
        }
        $data = ['responseCode' => 1, 'data' => $wardList];
        return response()->json($data);
    }

    public function uploadDocument()
    {
        return View::make('CdaForm::ajaxUploadFile');
    }

    public function checkstatus($app_id)
    {

        return view("CdaForm::wait-for-payment", compact('app_id'));

    }

    public function afterPayment($payment_id)
    {
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return \redirect()->back();
        }
        DB::beginTransaction();
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('cda_apps', 'cda_apps.id', '=', 'process_list.ref_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'cda_apps.luc_id',
                'cda_apps.cda_processing_fee',
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
            $cdapayment = new CdaPayment();
            $cdapayment->ref_id = $processData->ref_id;
            $cdapayment->luc_id = $processData->luc_id;
            $cdapayment->transaction_id = $paymentInfo->transaction_id;
            $cdapayment->challan_no = $paymentInfo->transaction_id;
//            $cdapayment->transaction_amount =  $processData->cda_processing_fee;
            $cdapayment->transaction_amount = $paymentInfo->pay_amount;
            $cdapayment->transaction_date = $paymentInfo->payment_date;
            $cdapayment->save();
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/licence-applications/cda-form/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDAPAYMENT: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CDA-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1021]');
            return redirect('process/licence-applications/cda-form/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }


    public function afterCounterPayment($payment_id)
    {

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
                // TODO:: Needed to sent mail to user
                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }
            $processData->save();
            DB::commit();
            return redirect('process/licence-applications/cda-form/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDACOUNTERPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CDA-1022]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1022]');
            return redirect('process/licence-applications/cda-form/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }


    public function applicationstatus(Request $request)
    {
        $app_id = Encryption::decodeId($request->appid);
        $queuedata = CdaRequestQueue::where('ref_id', $app_id)->first();
        if ($queuedata == null) {
            return response()->json(['responseCode' => 1, 'status' => -1, 'message' => 'Your request is invalid. please try again']);
        } elseif ($queuedata->status == 1) {
            $cdadata = CdaForm::where('id', $app_id)->first();
            $lucid = intval($cdadata->luc_id);
            $accountno = $cdadata->cda_payment_account_no;
            if ($cdadata == null) {
                return response()->json(['responseCode' => 1, 'status' => -1, 'message' => 'Your request is invalid. please try again']);
            } elseif (($lucid == 0 || $lucid == null) && ($accountno != '' || $accountno != null)) {
                return response()->json(['responseCode' => 1, 'status' => 0, 'message' => 'Your request has been locked on verify']);
            } elseif (($lucid != 0 || $lucid != null) && ($accountno != '' || $accountno != null)) {
                return response()->json(['responseCode' => 1, 'status' => 1, 'message' => 'Your request submitted  successfully']);
            }
        } elseif ($queuedata->status == -1) {
            return response()->json(['responseCode' => 1, 'status' => -1, 'message' => 'Application Failed to submit.', 'cdaresponse' => $queuedata->response_json]);
        } elseif ($queuedata->status == 0) {
            return response()->json(['responseCode' => 1, 'status' => 0, 'message' => 'Your request is waiting for submission']);
        }

    }

    public function waitForPayment($applicationId)
    {
        return view("CdaForm::wait-for-payment", compact('applicationId', 'paymentId'));
    }

    public function getRefreshToken()
    {
        $token = $this->getCdaToken();
        return response($token);
    }

}
