<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\LicenceApplication\Models\Etin\NbrAreaInfo;
use App\Modules\LicenceApplication\Models\TradeLicence\DccZoneInfo;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessCategory;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessSubCategory;
use App\Modules\LicenceApplication\Models\TradeLicence\TLCertificate;
use App\Modules\LicenceApplication\Models\TradeLicence\TLDccPayment;
use App\Modules\LicenceApplication\Models\TradeLicence\TLRecordDCC;
use App\Modules\LicenceApplication\Models\TradeLicence\TLStackholderMapping;
use App\Modules\LicenceApplication\Models\TradeLicence\TradeLicence;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessNature;
use App\Modules\LicenceApplication\Models\TradeLicence\TLLicenceType;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlaceOfBusiness;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlotCategory;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlotType;
use App\Modules\LicenceApplication\Models\TradeLicence\TLTypeOfActivity;
use App\Modules\LicenceApplication\Models\TradeLicence\TradeLicenceRequest;
use App\Modules\LicenceApplication\Models\EA_OrganizationType;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Illuminate\Http\Request;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Countries;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class TradeLicenceController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 105;
        $this->aclName = 'TradeLicence';
    }


    public function appForm()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $basicInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->whereIn('process_list.company_id', $companyIds)
            ->first(['ea_apps.*']);

        if (empty($basicInfo)) {
            return \redirect("licence-applications/individual-licence")->with("error", "Your Basic Info data not exist");
        }


        $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])->get();

        $businessNature = ['' => 'Select one'] + TLBusinessNature::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $licenceType = ['' => 'Select one'] + TLLicenceType::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $placeOfBusiness = ['' => 'Select one'] + TLPlaceOfBusiness::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $plotCategory = ['' => 'Select one'] + TLPlotCategory::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $plotType = ['' => 'Select one'] + TLPlotType::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $typeOfActivity = ['' => 'Select one'] + TLTypeOfActivity::where('status', 1)->orderBy('name')->lists('name', 'id')->all();

        $factory = ['' => 'Select Item', "Yes" => "Yes", "No" => "No"];

        $chemical = ['' => 'Select Item', "Yes" => "Yes", "No" => "No"];

        $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();

        $zones = ['' => 'Select one'] + DccZoneInfo::where('area_type', 1)->orderBy('name_en')->lists('name_en', 'dcc_id')->all();

        $categories = ['' => 'Select one'] + TLBusinessCategory::where('status', 1)->orderBy('name_en')->lists('name_en', 'dcc_cat_id')->all();

        return view("LicenceApplication::trade-licence.trade-licence-add-form", compact('countries', 'basicInfo', 'document', 'businessNature', 'licenceType',
            'placeOfBusiness', 'plotCategory', 'plotType', 'typeOfActivity', 'factory', 'chemical', 'zones', 'categories'));
    }

    public function uploadDocument()
    {
        return View::make('LicenceApplication::trade-licence.ajaxUploadFile');
    }


    public function appStore(Request $request)
    {

        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        $company_id = Auth::user()->company_ids;

        $messages = [];
        if ($request->actionBtn != 'draft') {
            $rules = $this->getValidateRulesArr();
        } else {
            $rules = [];
        }

        $this->validate($request, $rules);

        try {

            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = TradeLicence::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new TradeLicence();
                $processData = new ProcessList();
            }


            if ($request->get('action') == 'only-get-tl') {

                $tlApiSubmit = TLApiFormatJsonSaveController::formateJsonSaveDataForApiSubmit($appData, $this->process_type_id);;
                DB::commit();

                if ($tlApiSubmit['success']) {
                    return response()->json([
                        'responseCode' => 1,
                        'app_id' => Encryption::encodeId($appData->id),
                        'message' => 'Please wait for Etin Response',
                    ]);
                } else {
                    return response()->json([
                        'responseCode' => 0,
                        'message' => $tlApiSubmit['message'],
                    ]);
                }
            }

            $appData->country = $request->get('country');
            $appData->organization_name = $request->get('organization_name');
            $appData->spouse_name = $request->get('spouse_name');
            $appData->applicant_name = $request->get('applicant_name');
            $appData->applicant_pic = $request->get('applicant_pic');
            $appData->applicant_email = $request->get('applicant_email');
            $appData->applicant_father = $request->get('applicant_father');
            $appData->applicant_mother = $request->get('applicant_mother');
            $appData->applicant_license_type = $request->get('applicant_license_type');
            $appData->applicant_dob = (!empty($request->get('applicant_dob')) ? date('Y-m-d', strtotime($request->get('applicant_dob'))) : '');
            $appData->business_name = $request->get('business_name');
            $appData->business_details = $request->get('business_details');
            $appData->business_holding = $request->get('business_holding');
            $appData->business_address = $request->get('business_address');
            $appData->business_road = $request->get('business_road');
            $appData->business_market_name = $request->get('business_market_name');

            $appData->business_zone = $request->get('business_zone') != '' ? $request->get('business_zone') : null;
            $appData->business_zone_value = $request->get('business_zone') != '' ? $request->get('business_zone_value') : null;

            $appData->business_ward = $request->get('business_ward') != '' ? $request->get('business_ward') : null;
            $appData->business_ward_value = $request->get('business_ward') != '' ? $request->get('business_ward_value') : null;

            $appData->business_area = $request->get('business_area') != '' ? $request->get('business_area') : null;
            $appData->business_area_value = $request->get('business_area') != '' ? $request->get('business_area_value') : null;

            $appData->business_shop = $request->get('business_shop');
            $appData->business_floor = $request->get('business_floor');
            $appData->business_nature = $request->get('business_nature');
            $appData->business_start_date = (!empty($request->get('business_start_date')) ? date('Y-m-d', strtotime($request->get('business_start_date'))) : '');

            $appData->authorised_capital = $request->get('authorised_capital');
            $appData->paidup_capital = $request->get('paidup_capital');

            $appData->business_category = $request->get('business_category') != '' ? $request->get('business_category') : null;
            $appData->business_category_value = $request->get('business_category_value') != '' ? $request->get('business_category_value') : null;

            $appData->business_sub_category = $request->get('business_sub_category') != '' ? $request->get('business_sub_category') : null;
            $appData->business_sub_category_value = $request->get('business_sub_category') != '' ? $request->get('business_sub_category_value') : null;

            $appData->business_signboard_height = $request->get('business_signboard_height');
            $appData->business_signboard_width = $request->get('business_signboard_width');
            $appData->business_factory = $request->get('business_factory');
            $appData->business_chemical = $request->get('business_chemical');
            $appData->business_plot_type = $request->get('business_plot_type');
            $appData->business_plot_category = $request->get('business_plot_category');
            $appData->business_place = $request->get('business_place');
            $appData->business_activity_type = $request->get('business_activity_type');

            $getFeesDcc = $this->getFeeByApi($request->paidup_capital, $request->business_category, $request->business_signboard_height, $request->business_signboard_width);

            $appData->fees = $getFeesDcc['fees'];
            $appData->vat = $getFeesDcc['vat'];
            $appData->tax = $getFeesDcc['tax'];
            $appData->total_fee = $getFeesDcc['total_fees'];

            $processData->department_id = CommonFunction::getDeptIdByCompanyId($company_id);
            if ($request->get('actionBtn') == "draft") {

                $processData->status_id = -1;
                $processData->desk_id = 0;

            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }

            if ($request->hasFile('applicant_pic')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $_applicantPic = $request->file('applicant_pic');
                $applicantPhoto = trim(uniqid('BIDA_TL-' . $company_id . '-', true) . $_applicantPic->getClientOriginalName());
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_applicantPic->move($path, $applicantPhoto);
                $appData->applicant_pic = $yearMonth . $applicantPhoto;
            }

            $appData->save();

            // document will be migrated from temporary doc table to application doc table
            $doc_row = DocInfo::where('process_type_id', $this->process_type_id)->get(['id', 'doc_name']);

            if (isset($doc_row)) {
                foreach ($doc_row as $docs) {
                    if (empty($request->get('validate_field_' . $docs->id))) {
                        continue;
                    }
                    $documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
                    $document_id = $docs->id;
                    // if this input file is new data then

                    if ($request->get('document_id_' . $docs->id) == '') {
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id),
                            'is_archive' => 2,
                        ];
                        AppDocuments::create($insertArray);
                    } else {
                        $oldDocumentId = $request->get('document_id_' . $docs->id);
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 2 for General Form
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id),
                            'is_archive' => 2,
                        ];
                        AppDocuments::where('id', $oldDocumentId)->update($insertArray);
                    }
                }
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->read_status = 0;

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            if ($request->get('actionBtn') == 'Submit') {

                $alreadyPayment = SonaliPaymentStackHolders::where('app_id', $app_id)
                    ->where('process_type_id', $this->process_type_id)
                    ->where('payment_status', 1)
                    ->orderBy('id', 'DESC')
                    ->first(['id']);

                if (count($alreadyPayment) == 0) {

                    $companyIds = CommonFunction::getUserCompanyWithZero();
                    $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                        ->where('process_list.process_type_id', 100)
                        ->where('process_list.status_id', 25)
                        ->whereIn('process_list.company_id', $companyIds)
                        ->first(['ea_apps.*']);

                    $trackingid = CommonFunction::getTrackingNoByProcessId($processData->id);

                    $requestJson = array(
                        "applicant_address" => $basicAppInfo->office_address,
                        "applicant_district_name" => NbrAreaInfo::getAreaName($basicAppInfo->office_district_id, 2),
                        "applicant_email" => $basicAppInfo->ceo_email,
                        "applicant_organization" => $basicAppInfo->company_name,
                        "applicant_phone" => $basicAppInfo->ceo_mobile_no,
                        "applicant_position" => $basicAppInfo->ceo_designation,
                        "applied_by" => $basicAppInfo->ceo_full_name,
                        "company_name" => $basicAppInfo->company_name,
                        "entity_type" => $this->getOrganizationTypeName($basicAppInfo->organization_type_id),
                        "oss_application_id" => $trackingid,
                        "rjsc_off_dist_name" => "",
                    );

                    // Tracking id update
                    $trackingPrefix = 'TL-' . date("dMY") . '-';
                    $processTypeId = $this->process_type_id;
                    DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");

                    $tlRecordDcc = TLRecordDCC::create([
                        'status' => 0,
                        'request' => json_encode($requestJson),
                        'process_type_id' => $this->process_type_id,
                        "tracking_no" => $trackingid,
                        'application_id' => $appData->id,
                        'payment_info' => json_encode([]),
                    ]);

                    DB::commit();

                    return $this->spPaymentSubmit($appData, $tlRecordDcc);

                } else {

                    $tlApiSubmit = TLApiFormatJsonSaveController::formateJsonSaveDataForApiSubmit($appData, $this->process_type_id);

                    DB::commit();

                    if ($tlApiSubmit['success']) {

                        return response()->json([
                            'responseCode' => 1,
                            'app_id' => Encryption::encodeId($appData->id),
                            'message' => 'Please wait for City corporation Response',
                        ]);
                    } else {
                        return response()->json([
                            'responseCode' => 0,
                            'message' => $tlApiSubmit['message'],
                        ]);
                    }
                }
            }

            if ($request->get('actionBtn') == "draft"){
                Session::flash('success', 'Successfully Drafted the Application!');
                return redirect('licence-applications/individual-licence');
            }

            if (!$request->ajax()) {
                return redirect()->back()->withInput();
            } else {
                return response()->json([
                    'responseCode' => 1,
                    'message' => 'Trade Licence Submitted successfully ',
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('AppStore-catch: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TLC-1052]');

            if (!$request->ajax()) {
                Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [TLC-1050]');
                return redirect()->back()->withInput();
            } else {
                return response()->json([
                    'responseCode' => 0,
                    'message' => CommonFunction::showErrorPublic($e->getMessage()) . ' [TLC-1050]',
                ]);
            }

        }
    }

    private function getOrganizationTypeName($organization)
    {
        try {

            $getOrganization = EA_OrganizationType::where('id', $organization)
                ->where('status', 1)
                ->first();

            return isset($getOrganization->name) ? $getOrganization->name : '';

        } catch (\Exception $e) {

            Log::error('GetOrganizationTypeName : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1073]');
            return null;
        }

    }

    public function appFormEditView($applicationId, $openMode = '', Request $request)
    {
//        if(!$request->ajax()){
//            return 'Sorry! this is a request without proper way.';
//        }
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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }

        try {

            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('tl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('sector_info', 'sector_info.id', '=', 'apps.business_category')
                ->leftJoin('sec_sub_sector_list', 'sec_sub_sector_list.id', '=', 'apps.business_sub_category')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->where('process_list.ref_id', $applicationId)
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
                    'sector_info.name as sec_name',
                    'sec_sub_sector_list.name as sub_sec_name',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);
            $businessNature = ['' => 'Select one'] + TLBusinessNature::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $licenceType = ['' => 'Select one'] + TLLicenceType::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $placeOfBusiness = ['' => 'Select one'] + TLPlaceOfBusiness::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $plotCategory = ['' => 'Select one'] + TLPlotCategory::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $plotType = ['' => 'Select one'] + TLPlotType::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $typeOfActivity = ['' => 'Select one'] + TLTypeOfActivity::where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $factory = ['' => 'Select Item', "Yes" => "Yes", "No" => "No"];
            $chemical = ['' => 'Select Item', "Yes" => "Yes", "No" => "No"];
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $document = DocInfo::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $clrDocuments = [];

            $zones = ['' => 'Select one'] + DccZoneInfo::where('area_type', 1)->orderBy('name_en')->lists('name_en', 'dcc_id')->all();
            $categories = ['' => 'Select one'] + TLBusinessCategory::where('status', 1)->orderBy('name_en')->lists('name_en', 'dcc_cat_id')->all();
            $subCategories = TLBusinessSubCategory::where('dcc_cat_id', $appInfo->business_category)->orderBy('name_en', 'ASC')->lists('name_en', 'dcc_sub_cat_id');

            $wards = DccZoneInfo::where('pare_id', $appInfo->business_zone)->where('area_type', 2)->orderBy('name_en', 'ASC')->lists('name_en', 'dcc_id')->all();
            $areas = DccZoneInfo::where('pare_id', $appInfo->business_zone)->where('area_type', 3)->orderBy('name_en', 'ASC')->lists('name_en', 'dcc_id')->all();

            $clr_document = AppDocuments::where('ref_id', $applicationId)->where('process_type_id', $this->process_type_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['document_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }


            $certificate = TLCertificate::where('ref_id', $appInfo->id)->where('process_type_id', $process_type_id)->whereNotNull('response')->first(['id', 'response']);
            $hasCertificate = TLCertificate::where('ref_id', $appInfo->id)->where('process_type_id', $process_type_id)->whereNotNull('response')->count();
            $getCertificate = TLCertificate::where('ref_id', $appInfo->id)->where('process_type_id', $process_type_id)->whereNotNull('response')->first(['response']);

            $certificateId = null;

            if (isset($certificate->id) && (!empty($certificate->response) && $certificate->response != null)) {
                $certificateId = $certificate->id;
            }

            $alreadyPayment = SonaliPaymentStackHolders::where('app_id', $applicationId)
                ->where('process_type_id', $this->process_type_id)
                ->where('payment_status', 1)
                ->orderBy('id', 'DESC')
                ->first(['id']);

            $alreadyPaymentCount = count($alreadyPayment);

            $TradeLicenceRequest = TradeLicenceRequest::where('ref_id', $applicationId)
                ->where('process_type_id', $this->process_type_id)
                ->first(['status', 'file_submit_status','has_certificate']);

            $tradeLicenceRequestStatus = isset($TradeLicenceRequest->status) ? $TradeLicenceRequest->status : null;
            $tradeLicenceRequestFileSubmitStatus = isset($TradeLicenceRequest->file_submit_status) ? $TradeLicenceRequest->file_submit_status : null;
            $tradeLicenceRequestHasCertificateStatus = isset($TradeLicenceRequest->has_certificate) ? $TradeLicenceRequest->has_certificate : null;

            $redirectFromPaymentFlag = (($alreadyPaymentCount > 0) && ($certificateId == null) && (intval($tradeLicenceRequestStatus) == 0 && intval($tradeLicenceRequestFileSubmitStatus) == 0  && intval($tradeLicenceRequestHasCertificateStatus) == 0) ) ? 1 : 0;

            $public_html = strval(view("LicenceApplication::trade-licence.trade-licence-edit-form",
                compact('appInfo', 'countries', 'viewMode', 'mode', 'clrDocuments', 'document', 'businessNature',
                    'licenceType', 'placeOfBusiness', 'plotCategory', 'plotType', 'typeOfActivity', 'factory', 'chemical',
                    'zones', 'categories', 'wards', 'areas', 'subCategories', 'redirectFromPaymentFlag', 'alreadyPaymentCount', 'hasCertificate', 'getCertificate')));


            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[TLE-1010]"]);
        }
    }


    public function spPaymentSubmit($TradeLicence, $tlRecordDcc)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        try {
            if (empty($tlRecordDcc)) {
                Session::flash('error', "Your Application in DCC not found [TLC-1070]");
                return \redirect()->back();
            }

            $appId = $TradeLicence->id;

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [TLC-1071]");
                return redirect()->back()->withInput();
            }

            // from ApiStackholderMapping get details of 250 taka in business automation account which is fixed.
            $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                ->where('is_active', 1)
                ->where('process_type_id', $this->process_type_id)
                ->get([
                    'receiver_account_no',
                    'amount',
                    'distribution_type'
                ])->toArray();

            // from TLStackholderMapping dynamic amount(comes from fees API ) in fixed account.
            $tlStackholderMappingInfo = TLStackholderMapping::where('is_active', 1)
                ->get([
                    'receiver_account_no',
                    'category',
                ]);

            // pushing dynamic amount in to $stackholderMappingInfo[] array
            foreach ($tlStackholderMappingInfo as $singleData) {
                if ($singleData->category == "VAT") {
                    $stackholderMappingInfo[] = [
                        'receiver_account_no' => $singleData->receiver_account_no,
                        'amount' => $TradeLicence->vat,
                        'distribution_type' => $stackholderDistibutionType,
                    ];
                }
                if ($singleData->category == "TAX") {
                    $stackholderMappingInfo[] = [
                        'receiver_account_no' => $singleData->receiver_account_no,
                        'amount' => $TradeLicence->tax,
                        'distribution_type' => $stackholderDistibutionType,
                    ];
                }
                if ($singleData->category == "TL") {
                    $stackholderMappingInfo[] = [
                        'receiver_account_no' => $singleData->receiver_account_no,
                        'amount' => $TradeLicence->fees,
                        'distribution_type' => $stackholderDistibutionType,
                    ];
                }
            }

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
            $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->receiver_ac_no = $account_numbers;

            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
//            $paymentInfo->request_id = "010" . rand(1000000, 9999999); // Will be change later
//            $paymentInfo->payment_date = date('Y-m-d');
            $paymentInfo->ref_tran_no = rand(1000000, 9999999);
//            $paymentInfo->ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify
            $paymentInfo->pay_amount = $pay_amount;
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1



            TradeLicence::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);
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
                $paymentInsert = $paymentDetails->save();
                $sl++;
            }
            DB::commit();

            if ($paymentInsert) {
                return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('SpPaymentSubmit : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TLC-1069]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[TLC-1069]");
            return redirect()->back()->withInput();
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


            $tlRecordDcc = TLRecordDCC::where('application_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first(['response', 'payment_info']);

            $verification_response = json_decode($paymentInfo->verification_response);


            $rData0['nc_save_id'] = $tlRecordDcc->response;
            $rData0['nc_request_by'] = $verification_response->ApplicantName;
            $rData0['remarks'] = "";
            $rData0['branch_code'] = $verification_response->BrCode;
            $data = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            foreach ($data as $singleResponse){
                $value = json_decode($singleResponse->verification_response);
                    $rData0['account_info'][] = [
                        'account_no' => $value->TranAccount,
                        'balance' => 0,
                        'deposit' => $value->TranAmount,
                        'tran_date' => $value->TransactionDate,
                        'tran_id' => $value->TransactionId
                    ];
            }

            $tlDccPayment = new TLDccPayment();
            $tlDccPayment->request = json_encode($rData0);
            $tlDccPayment->ref_id = $paymentInfo->app_id;
            $tlDccPayment->tracking_no = $processData->tracking_no;
            $tlDccPayment->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/licence-applications/trade-licence/view/' . Encryption::encodeId($paymentInfo->app_id) . '/' . Encryption::encodeId($this->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('AfterPayment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1072]');
            Session::flash('error', 'Something went wrong!, E-TIN application not updated after payment. [ETC-1072]');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    public function UpdateAd($ref_id, $requestData)
    {
        TradeLicence::where('id', $ref_id)->update([
            'account_number' => $requestData['acc_number'],
            'amount' => $requestData['amount']
        ]);
        return true;
    }


    public function getDivisionDistrictThana(Request $request)
    {

        $type = !empty($request->get('type')) ? $request->get('type') : 0;
        $pid = !empty($request->get('pid')) ? $request->get('pid') : 0;

        $requested_url = 'http://116.193.218.152:5000/api/license/get_location?pid=' . $pid . '&type=' . $type;

        $response = $this->curlGetRequest($requested_url);

        if ($response['code'] != 200) {

            $this->updateApiToken();

            $response = $this->curlGetRequest($requested_url);
        }

        return $response['data'];
    }

    public function getZoneWardArea(Request $request)
    {
        $zoneId = !empty($request->get('zone')) ? $request->get('zone') : 0;
        $areaType = !empty($request->get('area_type')) ? $request->get('area_type') : 0;

        $areaWards = DccZoneInfo::where('pare_id', $zoneId)
            ->where('area_type', $areaType)
            ->orderBy('name_en', 'ASC')
            ->lists('name_en', 'dcc_id');

        return response()->json([
            'responseCode' => 1,
            'data' => $areaWards,
        ]);
    }


    public function getSubCategory(Request $request)
    {
        $catId = !empty($request->get('cat_id')) ? $request->get('cat_id') : 0;

        $subCategory = TLBusinessSubCategory::where('dcc_cat_id', $catId)
            ->orderBy('name_en', 'ASC')
            ->lists('name_en', 'dcc_sub_cat_id');

        return response()->json([
            'responseCode' => 1,
            'data' => $subCategory,
        ]);
    }

    public function getFeeByApi($capital, $cat_id, $sbHEight, $sbWidth)
    {
        $tradeLicenceCurl = new TLCurlRequest();
        $baseUrl = $tradeLicenceCurl->tradeLicenceApiUrl . 'api/license/get_fees_chart?';
        $postdata = [
            'comptype' => 1,
            'capital' => $capital,
            'cat_id' => $cat_id,
            'instid' => 2,
            'SB_Height' => $sbHEight,
            'SB_Width' => $sbWidth,
        ];
        $checkFeeApiUrl = $baseUrl . http_build_query($postdata);
        $fees = $tradeLicenceCurl->curlGetRequest($checkFeeApiUrl);
        $feesArray = (CommonFunction::isJson($fees) ? json_decode($fees, true) : []);

        return [
            'fees' => isset($feesArray['fees']) ? $feesArray['fees'] : 0,
            'vat' => isset($feesArray['VAT']) ? $feesArray['VAT'] : 0,
            'tax' => isset($feesArray['Tax']) ? $feesArray['Tax'] : 0,
            'total_fees' => isset($feesArray['Total_fees']) ? $feesArray['Total_fees'] : 0,
        ];

    }

    public function getFees(Request $request)
    {
        $capital = $request->capital;
        $catId = $request->cat_id;
        $sbHeight = $request->signboard_height;
        $sbWidth = $request->signboard_width;
        if (!empty($capital) && !empty($catId) && !empty($sbHeight) && !empty($sbWidth)) {

            $fees = $this->getFeeByApi($capital, $catId, $sbHeight, $sbWidth);

            return response()->json([
                'responseCode' => 1,
                'fees' => 'Total Fee : '. $fees['total_fees'].' Taka',
            ]);

        } else {
            return response()->json([
                'responseCode' => 1,
                'fees' => 'Provide Category, Paid Up Capital and Signboard height & width',
            ]);
        }
    }


    /**
     * In DCC Server, responseCode = 1 mean application submitted,
     *                and responseCode = 0 mean some kind of error
     *
     * In BIDA Project, responseCode = 1 mean application submitted to DCC by cron (bida-api project),
     *                  responseCode = 0 mean application not submit yet by cron
     *                  responseCode = -1 mean application submitting by cron
     *                  responseCode = -2 mean some kind of error from DCC
     *
     * BIDA get the status of the application status of DCC from TradeLicenceRequest Model (table: tl_request)
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkApiRequestStatus(Request $request)
    {
        try {

            $tlId = Encryption::decodeId($request->app_id);
            $status = 0;
            $id = 0;

            $TLRequest = TradeLicenceRequest::where('ref_id', $tlId)
                ->where('process_type_id', $this->process_type_id)
                ->first(['status', 'response']);

            if ($TLRequest == null) {

                return response()->json([
                    'responseCode' => -2,
                    'message' => "Sorry Can not find application [TLC-1087]",
                ]);

            } else {

                if ($TLRequest->status == 0 || $TLRequest->status == -1) {
                    return response()->json([
                        'responseCode' => 0,
                        'row_id' => 0,
                        'message' => 'Application submitting to City Corporation'
                    ]);
                }

                if ($TLRequest->status == 1) {

                    $TLCertificate = TLCertificate::where('ref_id', $tlId)
                        ->where('process_type_id', $this->process_type_id)
                        ->first(['id', 'status']);

                    if ($TLCertificate != null) {

                        if (($TLCertificate->status == 0 || $TLCertificate->status == -1)) {

                            return response()->json([
                                'responseCode' => 0,
                                'row_id' => 0,
                                'message' => 'Submitted ! Now waiting for certificate'
                            ]);

                        } else {

                            $status = ($TLCertificate->status > 0) ? 1 : -2;
                            $id = $TLCertificate->status > 0 ? Encryption::encodeId($TLCertificate->id) : Encryption::encodeId(0);
                        }

                    } else {
                        $status = -4;
                    }
                } else {
                    $status = -3;
                }
            }

            return response()->json([
                'responseCode' => $status,
                'row_id' => $id,
                'message' => ($status != 1) ? $this->formateResponseMessage($TLRequest->response) : ''
            ]);

        } catch (\Exception $e) {

            Log::error('CheckApiRequestStatus : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1067]');
            return response()->json([
                'responseCode' => -2,
                'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1067]",
            ]);
        }
    }


    private function formateResponseMessage($message)
    {
        try {

            if (CommonFunction::isJson($message)) {
                $formatedMessage = '';

                $responseMessages = json_decode($message, true);

                foreach ($responseMessages as $key => $singleMessage) {
                    if (is_array($singleMessage)) {

                        foreach ($singleMessage as $singleSubMessage) {

                            if (is_array($singleSubMessage)) {

                                foreach ($singleSubMessage as $subMessage) {

                                    $formatedMessage .= ucfirst($key) . ': ' . $subMessage . PHP_EOL;;
                                }
                            } else {

                                $formatedMessage .= ucfirst($key) . ': ' . $singleSubMessage . PHP_EOL;;
                            }
                        }
                    } else {

                        $formatedMessage .= ucfirst($key) . ': ' . $singleMessage . PHP_EOL;;
                    }
                }
                return $formatedMessage;
            }

            return $message;

        } catch (\Exception $e) {
            Log::error('FormateResponseMessage : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1088]');
            return $message;
        }

    }


    private function getValidateRulesArr()
    {
        $rules = [
            'country' => 'required',
            'organization_name' => 'required',
            'applicant_name' => 'required',
            'applicant_email' => 'required',
            'applicant_license_type' => 'required',
            'business_name' => 'required',
            'business_details' => 'required',
            'business_holding' => 'required',
            'business_address' => 'required',
            'business_road' => 'required',
            'business_zone' => 'required',
            'business_area' => 'required',
            'business_ward' => 'required',
            'authorised_capital' => 'required',
            'business_category' => 'required',
            'business_start_date' => 'required',
            'business_signboard_height' => 'required',
            'business_factory' => 'required',
            'business_plot_type' => 'required',
            'business_place' => 'required',
            'business_market_name' => 'required',
            'business_floor' => 'required',
            'business_shop' => 'required',
            'business_nature' => 'required',
            'paidup_capital' => 'required',
            'business_sub_category' => 'required',
            'business_signboard_width' => 'required',
            'business_chemical' => 'required',
            'business_plot_category' => 'required',
            'business_activity_type' => 'required',

        ];
        return $rules;
    }
}