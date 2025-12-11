<?php

namespace App\Modules\BasicInformation\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\Department;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BasicInformation\Models\EA_OrganizationType;
use App\Modules\BasicInformation\Models\EA_RegCommercialOffices;
use App\Modules\BasicInformation\Models\EA_RegistrationType;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\BasicInformation\Models\EA_OwnershipStatus;
use App\Modules\BasicInformation\Models\EA_Service;
use App\Modules\BasicInformation\Models\EA_CompanyWithoutVat;
use App\Modules\CompanyAssociation\Models\CompanyAssociation;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SubSector;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use App\Modules\VisaRecommendation\Models\VisaRecommendation;
use App\Modules\VisaRecommendationAmendment\Models\VisaRecommendationAmendment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use mPDF;
use yajra\Datatables\Datatables;

class BasicInformationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 100;
        $this->aclName = 'BasicInformation';
    }

    /*
     * Show application form
     */
    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BIC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [BIC-971]</h4>"]);
        }

        try {

            // Check existing application
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $statusArr = array(5, 6, '-1'); //5 is shortfall, 6 is Discard and -1 is draft
            $alreadyExistApplicant = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', $statusArr)
                ->whereIn('process_list.company_id', $companyIds)
                ->get(['process_list.tracking_no']);
            if (count($companyIds) == count($alreadyExistApplicant)) {
                return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have already submitted Basic information application! Your tracking no is : <b>" . $alreadyExistApplicant->implode('tracking_no', ', ') . "</b> [EAC-1015]</h4>"]);
            }
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
                ->get();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $eaRegistrationType = ['' => 'Select one'] + EA_RegistrationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $viewMode = 'off';
            $mode = '-A-';

            $public_html = strval(view("BasicInformation::application-form", compact('countries', 'colors',
                'code', 'eaOwnershipStatus', 'currencies', 'divisions', 'districts', 'thana', 'departmentList', 'zoneType', 'units', 'company_name',
                'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
                'eaOrganizationType', 'eaOrganizationStatus', 'eaRegistrationType', 'viewMode', 'mode',
                'industry_cat', 'data')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('ShowAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1005]');
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1005]']);
        }
    }

    /*
     * Application store
     */
    // public function appStore(Request $request)
    // {
    //     // Set permission mode and check ACL
    //     $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
    //     $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
    //     if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
    //         abort('400', 'You have no access right! Contact with system admin for more information. [BIC-972]');
    //     }

    //     // Check existing application
    //     //$company_id = $request->get('company_id');
    //     $company_id = CommonFunction::getUserWorkingCompany();
    //     $statusArr = array('-1', 6, 5); //6 is Discard, 5 is Rejected Application and -1 is draft

    //     $alreadyExistApplicant = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=',
    //         'ea_apps.id')
    //         ->where('process_list.process_type_id', $this->process_type_id)
    //         ->whereNotIn('process_list.status_id', $statusArr)
    //         ->where('process_list.company_id', $company_id)
    //         ->first(['process_list.tracking_no']);
    //     if ($alreadyExistApplicant) {
    //         Session::flash('error',
    //             "You have already submitted Basic Information application! Your tracking no is : <b>" . $alreadyExistApplicant->tracking_no . "</b>[EAC-1015]");
    //         return redirect()->back();
    //     }

    //     // Validation Rules when application submitted
    //     $rules = [];
    //     $messages = [];
    //     if ($request->get('actionBtn') != 'draft') {

    //         // Company Information panel
    //         $rules['country_of_origin_id'] = 'required|numeric';
    //         $rules['organization_type_id'] = 'required|numeric';
    //         $rules['organization_type_other'] = 'required_if:organization_type_id,0';
    //         $rules['organization_status_id'] = 'required|numeric';
    //         $rules['ownership_status_id'] = 'required|numeric';
    //         $rules['ownership_status_other'] = 'required_if:ownership_status_id,0';
    //         $rules['business_sub_sector_id'] = 'required|numeric';

    //         // Registration Information panel
    //         $rules['is_registered'] = 'required';
    //         $rules['registered_by_id'] = 'required_if:is_registered,yes|numeric';
    //         if ($request->hasFile('registration_copy')) {
    //             $rules['registration_copy'] = 'required|mimes:pdf|max:3072';
    //         }

    //         if ($request->get('is_registered') == 'yes') {
    //             $rules['registration_no'] = 'required_if:registered_by_id,1,3';
    //             $rules['registration_date'] = 'required_if:registered_by_id,1,3|date|date_format:d-M-Y';
    //         }

    //         // C. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO panel
    //         $rules['ceo_country_id'] = 'required';
    //         $rules['ceo_nid'] = 'required_if:ceo_country_id,18|bd_nid';
    //         $rules['ceo_designation'] = 'required';
    //         $rules['ceo_full_name'] = 'required';
    //         $rules['ceo_district_id'] = 'required_if:ceo_country_id,18';
    //         $rules['ceo_thana_id'] = 'required_if:ceo_country_id,18';
    //         $rules['ceo_city'] = 'required_unless:ceo_country_id,18';
    //         $rules['ceo_state'] = 'required_unless:ceo_country_id,18';
    //         $rules['ceo_post_code'] = 'required';
    //         $rules['ceo_address'] = 'required';
    //         $rules['ceo_mobile_no'] = 'required|phone_or_mobile';
    //         $rules['ceo_email'] = 'required|email';
    //         $rules['ceo_father_name'] = 'required_if:ceo_country_id,18';
    //         $rules['ceo_mother_name'] = 'required_if:ceo_country_id,18';
    //         $rules['ceo_gender'] = 'required';

    //         // Office Address panel
    //         $rules['office_division_id'] = 'required|numeric';
    //         $rules['office_district_id'] = 'required|numeric';
    //         $rules['office_thana_id'] = 'required|numeric';
    //         $rules['office_post_code'] = 'required|digits:4';
    //         $rules['office_address'] = 'required';
    //         $rules['office_mobile_no'] = 'required|phone_or_mobile';
    //         $rules['office_email'] = 'required|email';

    //         // Authorized Person Information panel
    //         $rules['auth_full_name'] = 'required';
    //         $rules['auth_designation'] = 'required';
    //         $rules['auth_mobile_no'] = 'required|phone_or_mobile';
    //         $rules['auth_email'] = 'required|email';
    //         $rules['acceptTerms'] = 'required';
    //         if ($request->hasFile('auth_letter')) {
    //             $rules['auth_letter'] = 'required|mimes:pdf|max:3072';
    //         }

    //         $messages['registration_date.required_if'] = 'The registration date is required when Registered with BIDA or Registered with RJSC';

    //         if ($request->hasFile('registration_copy')) {
    //             $rules['registration_copy'] = 'required|mimes:pdf|max:3072';
    //         }
    //         if ($request->hasFile('auth_letter')) {
    //             $rules['auth_letter'] = 'required|mimes:pdf|max:3072';
    //         }
    //     }

    //     $this->validate($request, $rules, $messages);

    //     try {
    //         DB::beginTransaction();
    //         if ($request->get('app_id')) {
    //             $appData = BasicInformation::find($app_id);
    //             $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
    //         } else {
    //             $appData = new BasicInformation();
    //             $processData = new ProcessList();
    //         }
    //         $appData->country_of_origin_id = $request->get('country_of_origin_id');
    //         $appData->ownership_status_id = $request->get('ownership_status_id');
    //         $appData->ownership_status_other = $request->get('ownership_status_other');
    //         $appData->local_executive = $request->get('local_executive');
    //         $appData->local_stuff = $request->get('local_stuff');
    //         $appData->local_total = $request->get('local_total');
    //         $appData->foreign_executive = $request->get('foreign_executive');
    //         $appData->foreign_stuff = $request->get('foreign_stuff');
    //         $appData->foreign_total = $request->get('foreign_total');
    //         $appData->manpower_total = $request->get('manpower_total');
    //         $appData->manpower_local_ratio = $request->get('manpower_local_ratio');
    //         $appData->manpower_foreign_ratio = $request->get('manpower_foreign_ratio');
    //         $appData->incorporation_certificate_number = $request->get('incorporation_certificate_number');

    //         $appData->incorporation_certificate_date = (!empty($request->get('incorporation_certificate_date')) ? date('Y-m-d', strtotime($request->get('incorporation_certificate_date'))) : null);
    //         $appData->business_sector_id = $request->get('business_sector_id');
    //         $appData->business_sector_others = $request->get('business_sector_others');

    //         $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
    //         $appData->business_sub_sector_others = $request->get('business_sub_sector_others');
    //         $appData->ceo_spouse_name = $request->get('ceo_spouse_name');

    //         $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
    //         $appData->registration_other = $request->get('registration_other');
    //         $appData->registered_by_other = $request->get('registered_by_other');
    //         $appData->is_registered = $request->get('is_registered');

    //         $appData->major_activities = $request->get('major_activities');
    //         $appData->factory_mouja = $request->get('factory_mouja');
    //         // end
    //         $appData->company_name = CommonFunction::getCompanyNameById($company_id);
    //         $appData->company_name_bn = CommonFunction::getCompanyBnNameById($company_id);
    //         $appData->organization_type_id = $request->get('organization_type_id');
    //         $appData->organization_type_other = $request->get('organization_type_other');
    //         $appData->organization_status_id = $request->get('organization_status_id');
    //         $appData->registered_by_id = $request->get('registered_by_id');
    //         $appData->registration_no = $request->get('registration_no');
    //         if ($request->get('registration_date') != '') {
    //             $appData->registration_date = CommonFunction::changeDateFormat($request->get('registration_date'), true);
    //         }
    //         $appData->ceo_full_name = $request->get('ceo_full_name');
    //         $appData->ceo_designation = $request->get('ceo_designation');
    //         $appData->ceo_country_id = $request->get('ceo_country_id');
    //         $appData->ceo_district_id = $request->get('ceo_district_id');
    //         $appData->ceo_thana_id = $request->get('ceo_thana_id');
    //         $appData->ceo_post_code = $request->get('ceo_post_code');
    //         $appData->ceo_address = $request->get('ceo_address');
    //         $appData->ceo_city = $request->get('ceo_city');
    //         $appData->ceo_state = $request->get('ceo_state');
    //         $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
    //         $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
    //         $appData->ceo_fax_no = $request->get('ceo_fax_no');
    //         $appData->ceo_email = $request->get('ceo_email');
    //         $appData->ceo_father_name = $request->get('ceo_father_name');
    //         $appData->ceo_mother_name = $request->get('ceo_mother_name');
    //         $appData->ceo_nid = $request->get('ceo_nid');
    //         $appData->ceo_passport_no = $request->get('ceo_passport_no');
    //         $appData->ceo_gender = $request->get('ceo_gender');
    //         $appData->office_division_id = $request->get('office_division_id');
    //         $appData->office_district_id = $request->get('office_district_id');
    //         $appData->office_thana_id = $request->get('office_thana_id');
    //         $appData->office_post_office = $request->get('office_post_office');
    //         $appData->office_post_code = $request->get('office_post_code');
    //         $appData->office_address = $request->get('office_address');
    //         $appData->office_telephone_no = $request->get('office_telephone_no');
    //         $appData->office_mobile_no = $request->get('office_mobile_no');
    //         $appData->office_fax_no = $request->get('office_fax_no');
    //         $appData->office_email = $request->get('office_email');
    //         $appData->factory_district_id = $request->get('factory_district_id');
    //         $appData->factory_thana_id = $request->get('factory_thana_id');
    //         $appData->factory_post_office = $request->get('factory_post_office');
    //         $appData->factory_post_code = $request->get('factory_post_code');
    //         $appData->factory_address = $request->get('factory_address');
    //         $appData->factory_telephone_no = $request->get('factory_telephone_no');
    //         $appData->factory_mobile_no = $request->get('factory_mobile_no');
    //         $appData->factory_fax_no = $request->get('factory_fax_no');
    //         $appData->factory_email = $request->get('factory_email');
    //         $appData->auth_full_name = CommonFunction::getUserFullName();
    //         $appData->auth_designation = Auth::user()->designation;
    //         $appData->auth_mobile_no = Auth::user()->user_phone;
    //         $appData->auth_email = Auth::user()->user_email;
    //         $appData->auth_image = Auth::user()->user_pic;
    //         $appData->auth_signature = Auth::user()->signature;

    //         if ($request->hasFile('registration_copy')) {
    //             $yearMonth = date("Y") . "/" . date("m") . "/";
    //             $path = 'uploads/' . $yearMonth;
    //             if (!file_exists($path)) {
    //                 mkdir($path, 0777, true);
    //             }
    //             $_registration_copy = $request->file('registration_copy');
    //             $registration_copy = trim(uniqid('BIDA_EA-' . $company_id . '-', true) . $_registration_copy->getClientOriginalName());
    //             $_registration_copy->move($path, $registration_copy);
    //             $appData->registration_copy = $yearMonth . $registration_copy;
    //         }

    //         if ($request->hasFile('auth_letter')) {
    //             $yearMonth = date("Y") . "/" . date("m") . "/";
    //             $path = 'users/upload/' . $yearMonth;
    //             $_authorization_file = $request->file('auth_letter');
    //             $full_name_concat = (trim(Auth::user()->user_first_name) . trim(Auth::user()->user_middle_name) . trim(Auth::user()->user_last_name));
    //             $full_name = str_replace(' ', '_', $full_name_concat);
    //             $authorization_file = ($full_name . '_' . rand(0, 9999999) . '_' . date('Ymd') . '.' . $_authorization_file->getClientOriginalExtension());
    //             if (!file_exists($path)) {
    //                 mkdir($path, 0777, true);
    //             }
    //             $_authorization_file->move($path, $authorization_file);
    //             $appData->auth_letter = $yearMonth . $authorization_file;

    //         } else {
    //             $appData->auth_letter = $request->get('auth_letter') ? $request->get('auth_letter') : $request->get('old_auth_letter');
    //         }
    //         $appData->acceptTerms = (!empty($request->get('acceptTerms')) ? 1 : 0);

    //         if ($request->get('actionBtn') == "draft" && $processData->status_id != 2) {
    //             $processData->status_id = -1;
    //             $processData->desk_id = 0;
    //         } else {
    //             if ($processData->status_id == 5) { // For shortfall application re-submission
    //                 $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
    //                 $processData->status_id = 2; // resubmit
    //                 $processData->desk_id = $getLastProcessInfo->desk_id;
    //                 $processData->process_desc = 'Re-submitted from applicant';
    //             } else {  // For new application submission
    //                 $processData->status_id = 1;
    //                 $processData->desk_id = 5; // 5 is Help Desk (For Basic Information Module)
    //                 $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
    //             }
    //         }
    //         $appData->company_id = $company_id;
    //         $appData->save();

    //         $processData->sub_department_id = 1; // Sub-department id default 1
    //         $processData->ref_id = $appData->id;
    //         $processData->process_type_id = $this->process_type_id;
    //         $processData->process_desc = '';// for re-submit application
    //         $processData->company_id = $company_id;
    //         $processData->read_status = 0;

    //         $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
    //         $jsonData['Applicant Email'] = Auth::user()->user_email;
    //         $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
    //         $processData['json_object'] = json_encode($jsonData);
    //         $processData->save();

    //         // Generate Tracking No for Submitted application
    //         if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
    //             $trackingPrefix = "BI-" . date("dMY") . '-';
    //             $processTypeId = $this->process_type_id;
    //             $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
    //                                                         select concat('$trackingPrefix',
    //                                                                 LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
    //                                                                       ) as tracking_no
    //                                                          from (select * from process_list ) as table2
    //                                                          where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
    //                                                     )
    //                                                   where process_list.id='$processData->id' and table2.id='$processData->id'");
    //         }

    //         //  Required Documents for attachment
    //         $doc_row = DocInfo::where('process_type_id', $this->process_type_id)->get(['id', 'doc_name']);
    //         if (isset($doc_row)) {
    //             foreach ($doc_row as $docs) {
    //                 $documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
    //                 $document_id = $docs->id;
    //                 // if this input file is new data then create
    //                 if ($request->get('document_id_' . $docs->id) == '') {
    //                     $insertArray = [
    //                         'process_type_id' => $this->process_type_id, // 1 for Space Allocation
    //                         'ref_id' => $appData->id,
    //                         'doc_info_id' => $document_id,
    //                         'doc_name' => $documentName,
    //                         'doc_file_path' => $request->get('validate_field_' . $docs->id)
    //                     ];
    //                     AppDocuments::create($insertArray);
    //                 } // if this input file is old data then update
    //                 else {
    //                     $oldDocumentId = $request->get('document_id_' . $docs->id);
    //                     $insertArray = [
    //                         'process_type_id' => $this->process_type_id, // 2 for General Form
    //                         'ref_id' => $appData->id,
    //                         'doc_info_id' => $document_id,
    //                         'doc_name' => $documentName,
    //                         'doc_file_path' => $request->get('validate_field_' . $docs->id)
    //                     ];
    //                     AppDocuments::where('id', $oldDocumentId)->update($insertArray);
    //                 }
    //             }
    //         } /* End file uploading */

    //         if ($request->get('actionBtn') != "draft" && ($processData->status_id == 1 || $processData->status_id == 2)) {
    //             $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
    //                 ->where('process_list.id', $processData->id)
    //                 ->first([
    //                     'process_type.name as process_type_name',
    //                     'process_type.process_supper_name',
    //                     'process_type.process_sub_name',
    //                     'process_list.*'
    //                 ]);

    //             //get users email and phone no according to working company id
    //             $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

    //             $appInfo = [
    //                 'app_id' => $processData->ref_id,
    //                 'status_id' => $processData->status_id,
    //                 'tracking_no' => $processData->tracking_no,
    //                 'process_type_name' => $processData->process_type_name,
    //                 'process_type_id' => $this->process_type_id,
    //                 'process_supper_name' => $processData->process_supper_name,
    //                 'process_sub_name' => $processData->process_sub_name,
    //                 'remarks' => ''
    //             ];

    //             if ($processData->status_id == 1)
    //                 CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

    //             if ($processData->status_id == 2)
    //                 CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
    //         }

    //         if ($processData->status_id == -1) {
    //             Session::flash('success', 'Successfully updated the Application!');
    //         } elseif ($processData->status_id == 1) {
    //             Session::flash('success', 'Successfully Application Submitted !');
    //         } elseif (in_array($processData->status_id, [2])) {
    //             Session::flash('success', 'Successfully Application Re-Submitted !');
    //         } else {
    //             Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BIC-1023]');
    //         }
    //         DB::commit();
    //         return redirect('basic-information/list/' . Encryption::encodeId($this->process_type_id));
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('StoreBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1020]');
    //         Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1020]');
    //         return redirect()->back()->withInput();
    //     }
    // }

    /*
     * Application edit or view
     */
    public function appFormEditView($applicationId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BIC-1002]';
        }
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
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information, [BIC-973]</h4>"]);
        }

        try {
            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('ea_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sector_info', 'sector_info.id', '=', 'apps.business_sector_id')
                ->leftJoin('sec_sub_sector_list', 'sec_sub_sector_list.id', '=', 'apps.business_sub_sector_id')
                ->leftJoin('ea_registration_type as ert', 'ert.id', '=', 'apps.registered_by_id')
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
                    'user_desk.desk_name',
                    'sector_info.name as sec_name',
                    'sec_sub_sector_list.name as sub_sec_name',
                    'ert.name as reg_t_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);

            // Last remarks attachment
            $remarks_attachment = DB::select(DB::raw("select * from
                                                `process_documents`
                                                where `process_type_id` = $this->process_type_id and `ref_id` = $appInfo->process_list_id and `status_id` = $appInfo->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$appInfo->process_list_id AND process_type_id=$this->process_type_id AND status_id=$appInfo->status_id)
                                                ORDER BY id ASC"
            ));

            $departmentList = Department::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $eaRegistrationType = ['' => 'Select one'] + EA_RegistrationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $countries = ['' => 'Select One'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();

            $document_query = AppDocuments::leftJoin('doc_info', 'doc_info.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $applicationId)
                ->where('app_documents.process_type_id', $this->process_type_id);
            if ($viewMode == 'on') {
                $document_query->where('app_documents.doc_file_path', '!=', '');
            }
            $document = $document_query->get([
                'doc_info.*', 'app_documents.id as document_id', 'app_documents.doc_file_path as doc_file_path',
                'app_documents.is_old_file'
            ]);


            $public_html = strval(view("BasicInformation::application-form-edit",
                compact('company_list', 'appInfo', 'remarks_attachment', 'countries', 'viewMode',
                    'mode', 'document', 'eaOwnershipStatus', 'sectors', 'sub_sectors', 'eaOrganizationType',
                    'eaOrganizationStatus', 'eaRegistrationType', 'divisions', 'districts', 'departmentList')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('ViewEdieBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1010]');
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[BIC-1010]"]);
        }
    }

    public function preview()
    {
        return view('BasicInformation::preview');
    }

    public function uploadDocument()
    {
        return View::make('BasicInformation::ajaxUploadFile');
    }

    public function getDistrictByDivision(Request $request)
    {
        $division_id = $request->get('divisionId');
        $districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    /*
     * Application download as PDF
     */
    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information. [BIC-974]');
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('ea_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
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
//                    'ps.color',
                    'apps.*'
                ]);
            $departmentList = Department::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $userCompanyList = CompanyInfo::where('id', [$appInfo->company_id])->get(['company_name', 'company_name_bn', 'id']);
            $eaRegistrationType = ['' => 'Select one'] + EA_RegistrationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana_eng = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
                ->get();
            $clrDocuments = [];
            $clr_document = AppDocuments::where('ref_id', $appInfo->id)->where('process_type_id', $this->process_type_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['document_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['doc_file_path'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }

            $contents = view("BasicInformation::application-form-pdf",
                compact('userCompanyList', 'appInfo', 'countries', 'clrDocuments',
                    'document', 'clr_document', 'eaOrganizationType', 'eaOrganizationStatus', 'eaOwnershipStatus',
                    'sectors', 'sub_sectors', 'eaRegistrationType', 'divisions', 'districts', 'thana_eng', 'departmentList'))->render();

            $mpdf = new mPDF(
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default font family
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            );

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');

        } catch (\Exception $e) {
            Log::error('PDFBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1015]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1115]');
            return Redirect::back()->withInput();
        }
    }


    public function loadSubSector(Request $request)
    {
        $sector_id = trim($request->get('sector_id'));
        $sub_sector = SubSector::where([
            'sector_id' => $sector_id,
            'status' => 1,
            'is_archive' => 0,
        ])->lists('name', 'id');
        $sub_sector[0] = 'Others';

        return response()->json([
            'result' => $sub_sector
        ]);
    }

    public function BiFormStakeholder($type)
    {
        $mode = '-E-';
        $viewMode = 'off';

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            Session::flash('error', 'You have no access right! Contact with system admin for more information. [BIC-975]');
            return redirect()->back();
        }

        // Check existing application
        $company_id = Auth::user()->company_ids;
        //business category
        $business_category = Auth::user()->company->business_category;

        $applicant_type = Encryption::decodeId($type);
        $applicant_type_name = '';
        if ($applicant_type == 'NCR') {
            $applicant_type_name = 'New Company Registration';
        } elseif ($applicant_type == 'ECR') {
            $applicant_type_name = 'Existing Company Registration';
        }

        try {
            $appInfo = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $company_id)
                ->first([
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'ea_apps.*'
                ]);

            $auth_letter = CompanyAssociation::where([
                'user_id' => Auth::user()->id,
                'request_type' => 'Add',
                'requested_company_id' => $company_id // Current working company id
            ])->pluck('authorization_letter');

            // Business category
            if ($business_category == 2) {  //2 = government
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [2, 3])->orderBy('name')->lists('name', 'id')->all();
            } else { //1 = private
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            }

            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaRegCommercialOffices = ['' => 'Select one'] + EA_RegCommercialOffices::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();

            return view("BasicInformation::application-form-stakeholder", compact('eaOrganizationType', 'countries', 'currencies', 'divisions', 'districts',
                'thana', 'eaOwnershipStatus', 'eaOrganizationStatus', 'eaRegCommercialOffices', 'applicant_type', 'appInfo',
                'viewMode', 'company_id', 'auth_letter', 'applicant_type_name'));
        } catch (\Exception $e) {
            Log::error('BIFormStakeholder : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1050]');
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1050]']);
        }
    }

    public function BiFormStakeholderView($type, $company = '')
    {
        $mode = '-V-';
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            Session::flash('error', 'You have no access right! Contact with system admin for more information. [BIC-979]');
            return redirect()->back();
        }

        $company_id = (!empty($company) ? Encryption::decodeId($company) : Auth::user()->company_ids);

        //application type
        $applicant_type = Encryption::decodeId($type);
        if ($applicant_type == 'NCR') {
            $applicant_type_name = 'New Company Registration';
        } elseif ($applicant_type == 'ECR') {
            $applicant_type_name = 'Existing Company Registration';
        }

        try {

            $appInfo = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
                ->leftJoin('ea_organization_status', 'ea_organization_status.id', '=', 'ea_apps.organization_status_id')
                ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'ea_apps.ownership_status_id')
                ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'ea_apps.organization_type_id')

                ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'ea_apps.ceo_country_id')
                ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'ea_apps.ceo_district_id')
                ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'ea_apps.ceo_thana_id')

                ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'ea_apps.office_division_id')
                ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'ea_apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'ea_apps.office_thana_id')

                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')

                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $company_id)
                ->first([
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'ea_apps.*',
                    'ea_organization_status.name as organization_status',
                    'ea_ownership_status.name as ownership_status',
                    'ea_organization_type.name as organization_type',

                    'ceo_country.nicename as ceo_country_name',
                    'ceo_district.area_nm as ceo_district_name',
                    'ceo_thana.area_nm as ceo_thana_name',

                    'office_division.area_nm as office_division_name',
                    'office_district.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',

                    'factory_district.area_nm as factory_district_name',
                    'factory_thana.area_nm as factory_thana_name',
                ]);

            return view("BasicInformation::application-form-stakeholder-view", compact('appInfo', 'company_id', 'mode', 'applicant_type_name'));
        } catch (\Exception $e) {
            Log::error('BIFormStakeholderView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1050]');
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1050]']);
        }
    }

    public function appStoreStakeholder(Request $request)
    {
        // Set permission mode and check ACL
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [BIC-976]');
        }

        // Check if basic info is exists
        $company_id = CommonFunction::getUserWorkingCompany();
        $biApps = ProcessList::leftJoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', $this->process_type_id)
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
//            ->where(function ($query){
//                    $query->where('is_new_for_stakeholders', 1)
//                          ->orWhere('is_existing_for_stakeholders', 1);
//                            })
            ->first();


        if (count($biApps) > 0 && ($biApps->is_new_for_stakeholders == 1 || $biApps->is_existing_for_stakeholders == 1)) {
            $alert = "Your Basic Information application already has been approved. [BIC-10000]";
            return view("BasicInformation::application-form-stakeholder", compact('alert'));
        }

        // Validation Rules when application submitted

        // A. Company Information
//        $rules['company_name'] = 'required';
//        $rules['organization_status_id'] = 'required';
//        $rules['ownership_status_id'] = 'required|numeric';
//        $rules['organization_type_id'] = 'required|numeric';
//        $rules['organization_type_id'] = 'required';

        // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
        $rules['ceo_country_id'] = 'required';
//        $rules['ceo_nid'] = 'required_if:ceo_country_id,18|bd_nid';
//        $rules['ceo_nid'] = 'required_if:ceo_country_id,18';
//        $rules['ceo_designation'] = 'required';
//        $rules['ceo_full_name'] = 'required';
        $rules['ceo_district_id'] = 'required_if:ceo_country_id,18';
        $rules['ceo_thana_id'] = 'required_if:ceo_country_id,18';
        $rules['ceo_city'] = 'required_unless:ceo_country_id,18';
        $rules['ceo_state'] = 'required_unless:ceo_country_id,18';
//        $rules['ceo_post_code'] = 'required';
//        $rules['ceo_address'] = 'required';
//        $rules['ceo_mobile_no'] = 'required|phone_or_mobile';
//        $rules['ceo_email'] = 'required|email';
        $rules['ceo_father_name'] = 'required_if:ceo_country_id,18';
        $rules['ceo_mother_name'] = 'required_if:ceo_country_id,18';

        // C. Office Address
//        $rules['office_division_id'] = 'required|numeric';
//        $rules['office_division_id'] = 'required';
//        $rules['office_district_id'] = 'required|numeric';
//        $rules['office_district_id'] = 'required';
//        $rules['office_thana_id'] = 'required|numeric';
//        $rules['office_thana_id'] = 'required';
//        $rules['office_post_office'] = 'required';
//        $rules['office_post_code'] = 'required|digits:4';
//        $rules['office_post_code'] = 'required';
//        $rules['office_address'] = 'required';
//        $rules['office_mobile_no'] = 'required|phone_or_mobile';
//        $rules['office_email'] = 'required|email';

        // D. Factory Address optional for stake holder
//        $rules['factory_district_id'] = 'required_if:service_type,1,2,3|numeric';
//        $rules['factory_thana_id'] = 'required_if:service_type,1,2,3|numeric';
//        $rules['factory_post_office'] = 'required_if:service_type,1,2,3';
//        $rules['factory_post_code'] = 'required_if:service_type,1,2,3';
//        $rules['factory_address'] = 'required_if:service_type,1,2,3';
//        $rules['factory_mobile_no'] = 'required_if:service_type,1,2,3';
//        $rules['factory_email'] = 'required_if:service_type,1,2,3';

        // Authorized Person Information
//        $rules['auth_full_name'] = 'required';
//        $rules['auth_designation'] = 'required';
//        $rules['auth_mobile_no'] = 'required';
//        $rules['auth_email'] = 'required|email';
//        $rules['acceptTerms'] = 'required';

        if ($request->hasFile('auth_letter'))
            $rules['auth_letter'] = 'required|mimes:pdf|max:3072';

        $messages['ceo_state.required_unless'] = 'State/ Province is required';
        $messages['ceo_city.required_unless'] = 'District/ City/ State is required';
        $messages['ceo_thana_id.required_if'] = 'Police Station/ Town is required';
        $rules['ceo_gender'] = 'required';
        $rules['ceo_dob'] = 'required';

        // D. Factory Address
        $messages['factory_district_id.required_if'] = 'Factory address district is required';
        $messages['factory_thana_id.required_if'] = 'Factory address police station is required';
        $messages['factory_post_office.required_if'] = 'Factory address post office is required';
        $messages['factory_post_code.required_if'] = 'Factory address post code is required';
        $messages['factory_address.required_if'] = 'Factory address house, flat/ apartment, road is required';
        $messages['factory_mobile_no.required_if'] = 'Factory address mobile no. is required';
        $messages['factory_email.required_if'] = 'Factory address email is required';

        $this->validate($request, $rules, $messages);
        try {
            DB::beginTransaction();
            if (count($biApps) > 0) {
                $processData = ProcessList::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $biApps->ref_id,
                    'company_id' => $biApps->company_id,
                ]);
                $appData = BasicInformation::find($biApps->ref_id);
            } else {

                $processData = new ProcessList();
                $appData = new BasicInformation();
            }

            // NUBS = New user, EUBS = Existing user, NCR = New company, ECR = Existing company
            $applicant_type = Encryption::decodeId($request->get('applicant_type'));

            if ($applicant_type == 'NCR') {
                $appData->applicant_type = 'New Company Registration';
                $appData->is_new_for_stakeholders = 1;
            } else if ($applicant_type == 'ECR') {
                $appData->applicant_type = 'Existing Company Registration';
                $appData->is_existing_for_stakeholders = 1;
            }

            // A. Company Information
            $appData->company_name = $request->get('company_name');
            $appData->company_name_bn = $request->get('company_name_bn');
            $appData->organization_status_id = $request->get('organization_status_id');
//            $appData->reg_commercial_office = $request->get('reg_commercial_office');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->major_activities = $request->get('major_activities');

            // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_city = $request->get('ceo_city');
            $appData->ceo_state = $request->get('ceo_state');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_father_name = $request->get('ceo_father_name');
            $appData->ceo_mother_name = $request->get('ceo_mother_name');
            $appData->ceo_spouse_name = $request->get('ceo_spouse_name');
            $appData->ceo_gender = $request->get('ceo_gender');

            // C. Office Address
            $appData->office_division_id = $request->get('office_division_id');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_office = $request->get('office_post_office');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->office_telephone_no = $request->get('office_telephone_no');
            $appData->office_mobile_no = $request->get('office_mobile_no');
            $appData->office_fax_no = $request->get('office_fax_no');
            $appData->office_email = $request->get('office_email');

            // D. Factory Address
            $appData->factory_district_id = $request->get('factory_district_id');
            $appData->factory_thana_id = $request->get('factory_thana_id');
            $appData->factory_post_office = $request->get('factory_post_office');
            $appData->factory_post_code = $request->get('factory_post_code');
            $appData->factory_address = $request->get('factory_address');
            $appData->factory_telephone_no = $request->get('factory_telephone_no');
            $appData->factory_mobile_no = $request->get('factory_mobile_no');
            $appData->factory_fax_no = $request->get('factory_fax_no');
            $appData->factory_email = $request->get('factory_email');
            $appData->factory_mouja = $request->get('factory_mouja');

            // Authorized Person Information
            $appData->auth_full_name = CommonFunction::getUserFullName();
            $appData->auth_designation = Auth::user()->designation;
            $appData->auth_mobile_no = Auth::user()->user_phone;
            $appData->auth_email = Auth::user()->user_email;
            $appData->auth_image = Auth::user()->user_pic;

            if ($request->hasFile('auth_letter')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $_authorization_file = $request->file('auth_letter');

                $full_name_concat = trim(CommonFunction::getUserFullName());
                $full_name = str_replace(' ', '_', $full_name_concat);
                $authorization_file = ($company_id . '_' . $full_name . '_' . rand(0, 9999999) . '.' . $_authorization_file->getClientOriginalExtension());
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_authorization_file->move($path, $authorization_file);
                $appData->auth_letter = $yearMonth . $authorization_file;

                // Update auth letter into Company Association table
                $this->updateAuthLetterIntoAssociation($company_id, $appData->auth_letter);
                // End Update auth letter into Company Association table

            } else {
                $appData->auth_letter = $request->get('auth_letter') ? $request->get('auth_letter') : $request->get('old_auth_letter');
            }

            $appData->acceptTerms = (!empty($request->get('acceptTerms')) ? 1 : 0);
            $appData->company_id = $company_id;
            $appData->approved_date = date('Y-m-d H:i:s');
            $appData->is_approved = 1;
            $appData->save();

            /*
             * Department and Sub-department specification
             */
//            $deptAndSubDept = CommonFunction::basicInfoDepSubDepSet($request->service_type);
//            $processData->department_id = $deptAndSubDept['department_id'];
//            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];


            // Process list
            $processData->ref_id = $appData->id;
            $processData->company_id = $company_id;
            $processData->process_type_id = $this->process_type_id;
            $processData->completed_date = date('Y-m-d H:i:s');
            $processData->status_id = 25; // Auto approved

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);

            $processData->save();

            if (empty($processData->tracking_no) || $processData->tracking_no == '') {
                // Generate Tracking No for Submitted application
                $trackingPrefix = "BI-" . date("dMY") . '-';
                $processTypeId = $this->process_type_id;
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");


            }

            /*
             * Enable company eligibility for other service/ process
             * without eligibility, user can't access any service except Basic Information
             */
            CompanyInfo::where('id', $company_id)->update([
                'is_eligible' => 1
            ]);


            // Send email
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.id', $processData->id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_list.*'
                ]);

            //get users email and phone no according to working company id
            $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

            $appInfo = [
                'app_id' => $processData->ref_id,
                'status_id' => $processData->status_id,
                'tracking_no' => $processData->tracking_no,
                'process_type_name' => $processData->process_type_name,
                'process_type_id' => $this->process_type_id,
                'process_supper_name' => $processData->process_supper_name,
                'process_sub_name' => $appData->applicant_type,
                'remarks' => ''
            ];

            CommonFunction::sendEmailSMS('BI_AUTO_APPROVE', $appInfo, $applicantEmailPhone);

            // User wise permission for menu (Sidebar) and widget (Dashboard)
            Session::forget('accessible_process');
            CommonFunction::setAccessibleProcessTypeList();

            DB::commit();
            Session::flash('success', 'Successfully Application Submitted!');
            return redirect('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('AppStoreStakeholder : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1061]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()). ' [BIC-1061]');
            return redirect()->back()->withInput();
        }
    }

    public function BiFormBIDA($type)
    {
        $mode = '-E-';
        $viewMode = 'off';
        $isExitForStakeholder = 'NO';

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            Session::flash('error', 'You have no access right! Contact with system admin for more information. [BIC-977]');
            return redirect()->back();
        }

        $company_id = Auth::user()->company_ids;

        // NUBS = New user, EUBS = Existing user, NCR = New company, ECR = Existing company
        $applicant_type = Encryption::decodeId($type);
        if ($applicant_type == 'NUBS') {
            $applicant_type_name = 'New User for BIDA\'s Services';
        } elseif ($applicant_type == 'EUBS') {
            $applicant_type_name = 'Existing User for BIDA\'s Services';
        }

        $business_category = Auth::user()->company->business_category;

        try {

            $appInfo = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $company_id)
                ->first([
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.department_id',
                    'ea_apps.*'
                ]);

            $auth_letter = CompanyAssociation::where([
                'user_id' => Auth::user()->id,
                'request_type' => 'Add',
                'requested_company_id' => $company_id // Current working company id
            ])->pluck('authorization_letter');

            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();

            // Business category
            if ($business_category == 2) {  //2 = government
                $eaService = ['' => 'Select one'] + EA_Service::where('is_archive', 0)->where('status', 1)->whereIn('type',[2,3])->orderBy('name')->lists('name', 'id')->all();
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [2, 3])->orderBy('name')->lists('name', 'id')->all();
            } else { //1 = private
                $eaService = ['' => 'Select one'] + EA_Service::where('is_archive', 0)->where('status', 1)->whereIn('type',[1,3])->orderBy('name')->lists('name', 'id')->all();
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            }

            $eaRegCommercialOffices = ['' => 'Select one'] + EA_RegCommercialOffices::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $departmentList = Department::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();

            return view("BasicInformation::application-form-bida", compact('eaOrganizationType', 'countries', 'currencies', 'divisions', 'districts',
                'thana', 'eaOwnershipStatus', 'eaService', 'eaRegCommercialOffices', 'applicant_type', 'appInfo', 'mode',
                'departmentList', 'viewMode', 'company_id', 'isExitForStakeholder', 'business_category',
                'count_approved_app', 'auth_letter', 'applicant_type_name'));
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1060]']);
        }
    }

    public function BiFormBIDAView($type, $company)
    {
        $data = [];
        $data['mode'] = '-V-';
        if (!ACL::getAccsessRight($this->aclName, $data['mode'])) {
            Session::flash('error', 'You have no access right! Contact with system admin for more information. [BIC-978]');
            return redirect()->back();
        }

        $company_id = (!empty($company) ? Encryption::decodeId($company) : Auth::user()->company_ids);
        $data['company_id'] = $company_id;

        // NUBS = New user, EUBS = Existing user, NCR = New company, ECR = Existing company
        $applicant_type = Encryption::decodeId($type);
        $applicant_type_name = '';
        if ($applicant_type == 'NUBS') {
            $applicant_type_name = 'New User for BIDA\'s Services';
        } elseif ($applicant_type == 'EUBS') {
            $applicant_type_name = 'Existing User for BIDA\'s Services';
        }
        $data['applicant_type_name'] = $applicant_type_name;

        try {

            $data['appInfo'] = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
                ->leftJoin('department', 'department.id', '=', 'process_list.department_id')
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('ea_service', 'ea_service.id', '=', 'ea_apps.service_type')
                ->leftJoin('ea_reg_commercial_offices', 'ea_reg_commercial_offices.id', '=', 'ea_apps.reg_commercial_office')
                ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'ea_apps.ownership_status_id')
                ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'ea_apps.organization_type_id')
                ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'ea_apps.ceo_country_id')
                ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'ea_apps.ceo_district_id')
                ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'ea_apps.ceo_thana_id')
                ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'ea_apps.office_division_id')
                ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'ea_apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'ea_apps.office_thana_id')
                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $company_id)
                ->first([
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.department_id',
                    'department.name as department',
                    'company_info.business_category',
                    'ea_apps.*',
                    'ea_service.name as service_name',
                    'ceo_country.nicename as ceo_country_name',
                    'ea_reg_commercial_offices.name as reg_commercial_office_name',
                    'ea_ownership_status.name as ownership_status',
                    'ea_organization_type.id as organization_type_id',
                    'ea_organization_type.name as organization_type',
                    'ceo_district.area_nm as ceo_district_name',
                    'ceo_thana.area_nm as ceo_thana_name',
                    'office_division.area_nm as office_division_name',
                    'office_district.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',
                    'factory_district.area_nm as factory_district_name',
                    'factory_thana.area_nm as factory_thana_name',
                ]);
            if (empty($data['appInfo'])){
                Session::flash('error', 'This company has not submitted the basic information yet.');
                return redirect()->back();
            }

            $data['without_govt_vat_services'] =  EA_CompanyWithoutVat::leftJoin('process_type', 'process_type.id', '=', 'ea_company_without_vat.process_type_id')
                ->where([
                    'ea_company_without_vat.company_id' => $company_id,
                    'ea_company_without_vat.status' => 1,
                    'ea_company_without_vat.is_archive' => 0,
                ])->orderBy('process_type.name','asc')
                ->get([
                    'ea_company_without_vat.process_type_id',
                    'process_type.name as service_name',
                    'process_type.form_url',
                ]);

            $data['service_list'] = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('company_id', $company_id)
                ->where('status_id', 25)
                ->where('process_type_id', '!=', 100)
                ->orderBy('process_list.submitted_at','DESC')
                ->limit(10)
                ->get([
                    'ref_id',
                    'tracking_no',
                    'process_type_id',
                    'completed_date as approved_date',
                    'process_type.name as service_name',
                    'process_type.form_url',
                    'process_type.form_id',
                ]);

            $data['company_user_list'] = Users::leftJoin('area_info', 'users.district', '=', 'area_info.area_id')
                ->whereRaw("FIND_IN_SET($company_id, company_ids)")
                ->where('user_type', '5x505')
                ->where('user_status', "active")
                ->where('is_approved', 1)
                ->limit(10)
                ->get([
                    'users.id',
                    DB::raw("CONCAT(user_first_name,' ',user_middle_name, ' ',user_last_name) as user_full_name"),
                    'user_email',
                    'user_status',
                    'working_user_type',
                    'designation',
                    'area_info.area_nm as users_district',
                    'users.created_at'
                ]);

            $data['count_approved_app'] = ProcessList::where([
                'company_id' => $company_id,
                'status_id' => 25
            ])->whereIn('process_type_id', config('bida_service.active'))
                ->count();

            return view("BasicInformation::application-form-bida-view", $data);

        } catch (\Exception $e) {
            Log::error('BIFormView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1060]');
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1060]']);
        }
    }

    public function appStoreBIDA(Request $request)
    {
        // Set permission mode and check ACL
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information. [BIC-981]');
        }

        $company_id = CommonFunction::getUserWorkingCompany();
        $biApps = ProcessList::leftJoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', $this->process_type_id)
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
            ->first();

        if (count($biApps) > 0 && ($biApps->is_new_for_bida == 1 || $biApps->is_existing_for_bida == 1)) {
            $alert = "Your Basic Information application already has been approved.";
            return view("BasicInformation::application-form-bida", compact('alert'));
        }

        $business_category = Auth::user()->company->business_category;

        // Validation Rules when application submitted

        // A. Company Information
        $rules['company_name'] = 'required';
        $rules['service_type'] = 'required';
        $rules['organization_type_id'] = 'required';
        if ($request->get('organization_type_id') == 14) { //14 = Others
            $rules['organization_type_other'] = 'required';
        }

        // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
        // B. Information of Responsible Person
        $rules['ceo_country_id'] = 'required';
        $rules['ceo_nid'] = 'required_if:ceo_country_id,18';
        $rules['ceo_designation'] = 'required';
        $rules['ceo_full_name'] = 'required';
        $rules['ceo_mobile_no'] = 'required';
        $rules['ceo_email'] = 'required|email';
        $rules['ceo_gender'] = 'required';

        // Start business category
        if ($business_category == 2) { //2 = government
            $rules['ceo_auth_letter'] = 'required|mimes:pdf|max:3072';
        } else { //1 = private
            $rules['ownership_status_id'] = 'required';
            $rules['ceo_dob'] = 'required';
            $rules['ceo_district_id'] = 'required_if:ceo_country_id,18';
            $rules['ceo_thana_id'] = 'required_if:ceo_country_id,18';
            $rules['ceo_city'] = 'required_unless:ceo_country_id,18';
            $rules['ceo_state'] = 'required_unless:ceo_country_id,18';
            $rules['ceo_post_code'] = 'required';
            $rules['ceo_address'] = 'required';
            $rules['ceo_father_name'] = 'required_if:ceo_country_id,18';
            $rules['ceo_mother_name'] = 'required_if:ceo_country_id,18';

            // D. Factory Address
            $rules['factory_district_id'] = 'required_if:service_type,1,2,3|numeric';
            $rules['factory_thana_id'] = 'required_if:service_type,1,2,3|numeric';
            $rules['factory_post_office'] = 'required_if:service_type,1,2,3';
            $rules['factory_post_code'] = 'required_if:service_type,1,2,3';
            $rules['factory_address'] = 'required_if:service_type,1,2,3';
            $rules['factory_mobile_no'] = 'required_if:service_type,1,2,3';
            $rules['factory_email'] = 'required_if:service_type,1,2,3';
        }


        // End business category

        // C. Office Address
        $rules['office_division_id'] = 'required|numeric';
        $rules['office_district_id'] = 'required|numeric';
        $rules['office_thana_id'] = 'required|numeric';
        $rules['office_post_office'] = 'required';
        $rules['office_post_code'] = 'required|digits:4';
        $rules['office_address'] = 'required';
        $rules['office_mobile_no'] = 'required|phone_or_mobile';
        $rules['office_email'] = 'required|email';



        // Authorized Person Information
//        $rules['auth_full_name'] = 'required';
//        $rules['auth_designation'] = 'required';
//        $rules['auth_mobile_no'] = 'required';
//        $rules['auth_email'] = 'required|email';
//        $rules['acceptTerms'] = 'required';

        if ($request->hasFile('auth_letter'))
            $rules['auth_letter'] = 'required|mimes:pdf|max:3072';

        $messages['ceo_state.required_unless'] = 'State/ Province is required';
        $messages['ceo_city.required_unless'] = 'District/ City/ State is required';
        $messages['ceo_thana_id.required_if'] = 'Police Station/ Town is required';

        // D. Factory Address
        $messages['factory_district_id.required_if'] = 'Factory address district is required';
        $messages['factory_thana_id.required_if'] = 'Factory address police station is required';
        $messages['factory_post_office.required_if'] = 'Factory address post office is required';
        $messages['factory_post_code.required_if'] = 'Factory address post code is required';
        $messages['factory_address.required_if'] = 'Factory address house, flat/ apartment, road is required';
        $messages['factory_mobile_no.required_if'] = 'Factory address mobile no. is required';
        $messages['factory_email.required_if'] = 'Factory address email is required';

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

            if (count($biApps) > 0) {
                $processData = ProcessList::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $biApps->ref_id,
                    'company_id' => $biApps->company_id,
                ]);

                $appData = BasicInformation::find($biApps->ref_id);
            } else {

                $processData = new ProcessList();
                $appData = new BasicInformation();
            }

            $applicant_type = Encryption::decodeId($request->get('applicant_type'));

            if ($applicant_type == 'NUBS') {
                $appData->applicant_type = 'New User for BIDA Services';
                $appData->is_new_for_bida = 1;
            } else if ($applicant_type == 'EUBS') {
                $appData->applicant_type = 'Existing User for BIDA services';
                $appData->is_existing_for_bida = 1;
            }

            // A. Company Information
            $appData->company_name = $request->get('company_name');
            $appData->company_name_bn = $request->get('company_name_bn');
            $appData->service_type = $request->get('service_type');
            $appData->reg_commercial_office = $request->get('reg_commercial_office');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->organization_type_id = $request->get('organization_type_id');

            if ($request->get('organization_type_id') == 14 && $business_category == 2){ //14 = Others
                $appData->organization_type_other = $request->get('organization_type_other');
            }

            $appData->major_activities = $request->get('major_activities');

            // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            // B. Information of Responsible Person
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_gender = $request->get('ceo_gender');

            // Start business category
            if ($business_category == 2) {
                //responsible person authorization latter
                if ($request->hasFile('ceo_auth_letter')) {
                    $yearMonth = date("Y") . "/" . date("m") . "/";
                    $path = 'users/upload/' . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $_file_path = $request->file('ceo_auth_letter');
                    $file_path = trim(uniqid('responsible_person' . rand(0, 9999999) . '-', true) . $_file_path->getClientOriginalName());
                    $_file_path->move($path, $file_path);
                    $appData->ceo_auth_letter = $yearMonth . $file_path;
                }
            } else{
                $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
                $appData->ceo_district_id = $request->get('ceo_district_id');
                $appData->ceo_city = $request->get('ceo_city');
                $appData->ceo_state = $request->get('ceo_state');
                $appData->ceo_thana_id = $request->get('ceo_thana_id');
                $appData->ceo_post_code = $request->get('ceo_post_code');
                $appData->ceo_address = $request->get('ceo_address');
                $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
                $appData->ceo_fax_no = $request->get('ceo_fax_no');
                $appData->ceo_father_name = $request->get('ceo_father_name');
                $appData->ceo_mother_name = $request->get('ceo_mother_name');
                $appData->ceo_spouse_name = $request->get('ceo_spouse_name');

                // D. Factory Address
                $appData->factory_district_id = $request->get('factory_district_id');
                $appData->factory_thana_id = $request->get('factory_thana_id');
                $appData->factory_post_office = $request->get('factory_post_office');
                $appData->factory_post_code = $request->get('factory_post_code');
                $appData->factory_address = $request->get('factory_address');
                $appData->factory_telephone_no = $request->get('factory_telephone_no');
                $appData->factory_mobile_no = $request->get('factory_mobile_no');
                $appData->factory_fax_no = $request->get('factory_fax_no');
                $appData->factory_email = $request->get('factory_email');
                $appData->factory_mouja = $request->get('factory_mouja');

                // Authorized Person Information
                $appData->auth_full_name = CommonFunction::getUserFullName();
                $appData->auth_designation = Auth::user()->designation;
                $appData->auth_mobile_no = Auth::user()->user_phone;
                $appData->auth_email = Auth::user()->user_email;
                $appData->auth_image = Auth::user()->user_pic;
            }
            // End business category

            // C. Office Address
            $appData->office_division_id = $request->get('office_division_id');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_office = $request->get('office_post_office');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->office_telephone_no = $request->get('office_telephone_no');
            $appData->office_mobile_no = $request->get('office_mobile_no');
            $appData->office_fax_no = $request->get('office_fax_no');
            $appData->office_email = $request->get('office_email');

            if ($request->hasFile('auth_letter')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $_authorization_file = $request->file('auth_letter');
                $full_name_concat = trim(CommonFunction::getUserFullName());
                $full_name = str_replace(' ', '_', $full_name_concat);
                $authorization_file = ($company_id . '_' . $full_name . '_' . rand(0, 9999999) . '.' . $_authorization_file->getClientOriginalExtension());
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_authorization_file->move($path, $authorization_file);
                $appData->auth_letter = $yearMonth . $authorization_file;

                // Update auth letter into Company Association table
                $this->updateAuthLetterIntoAssociation($company_id, $appData->auth_letter);
                // End Update auth letter into Company Association table

            } else {
                $appData->auth_letter = $request->get('auth_letter') ? $request->get('auth_letter') : $request->get('old_auth_letter');
            }

            $appData->acceptTerms = (!empty($request->get('acceptTerms')) ? 1 : 0);
            $appData->company_id = $company_id;
            $appData->approved_date = date('Y-m-d H:i:s');
            $appData->is_approved = 1;
            $appData->save();

            // Process list
            $processData->ref_id = $appData->id;

            $processData->company_id = $company_id;
            $processData->process_type_id = $this->process_type_id;
            $processData->completed_date = date('Y-m-d H:i:s');
            $processData->status_id = 25; // Auto approved
            /*
             * Department and Sub-department specification
             */
            $deptAndSubDept = CommonFunction::basicInfoDepSubDepSet($request->service_type);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            // Generate Tracking No for Submitted application

            if (empty($processData->tracking_no) || $processData->tracking_no == '') {
                // Generate Tracking No for Submitted application
                $trackingPrefix = "BI-" . date("dMY") . '-';
                $processTypeId = $this->process_type_id;
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");


            }


            /*
             * Enable company eligibility for other service/ process
             * without eligibility, user can't access any service except Basic Information
             */
            CompanyInfo::where('id', $company_id)->update([
                'is_eligible' => 1
            ]);


            // Send email
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.id', $processData->id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_list.*'
                ]);
            //get users email and phone no according to working company id
            $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

            $appInfo = [
                'app_id' => $processData->ref_id,
                'status_id' => $processData->status_id,
                'tracking_no' => $processData->tracking_no,
                'process_type_name' => $processData->process_type_name,
                'process_type_id' => $this->process_type_id,
                'process_supper_name' => $processData->process_supper_name,
                'process_sub_name' => $appData->applicant_type,
                'remarks' => ''
            ];

            CommonFunction::sendEmailSMS('BI_AUTO_APPROVE', $appInfo, $applicantEmailPhone);

            // User wise permission for menu (Sidebar) and widget (Dashboard)
            Session::forget('accessible_process');
            CommonFunction::setAccessibleProcessTypeList();

            DB::commit();
            Session::flash('success', 'Successfully Application Submitted!');
            return redirect('dashboard');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('AppStoreBIDA : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-62]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [BIC-1062]');
            return redirect()->back()->withInput();
        }
    }

    public function changeDeptModal($app_id, $company_id)
    {
        $aclName = $this->aclName;
        $mode = '-CD-';
        $eaService = ['' => 'Select one'] + EA_Service::where('is_archive', 0)->where('status', 1)->lists('name', 'id')->all();
        $eaRegCommercialOffices = ['' => 'Select one'] + EA_RegCommercialOffices::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        return view('BasicInformation::change-dept-modal', compact('aclName', 'mode', 'eaService', 'app_id', 'company_id', 'eaRegCommercialOffices'));
    }

    public function storeChangeDept(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-CD-')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Contact with system admin for more information. [BIC-983]'
            ]);
        }

        $rules = [
            'service_type' => 'required'
        ];

        $messages = [
            'service_type.required' => 'Service type field is required'
        ];

        $validation = \Illuminate\Support\Facades\Validator::make(Input::all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors()
            ]);
        }

        try {
            $app_id = Encryption::decodeId($request->get('app_id'));
            $company_id = Encryption::decodeId($request->get('company_id'));
            $bi_app_data = BasicInformation::where([
                'id' => $app_id,
                'company_id' => $company_id,
            ])
                ->first([
                    'id',
                    'company_id',
                    'is_new_for_bida',
                    'is_existing_for_bida'
                ]);

            // Check the application type is bida or not
            if (!($bi_app_data->is_new_for_bida == 1 || $bi_app_data->is_existing_for_bida == 1)) {
                return response()->json([
                    'error' => true,
                    'status' => 'This application can not be updated because of stakeholder type.  [BIC-1040]'
                ]);
            }


            // Check does exist any approved app of this company
            $count_approved_app = ProcessList::where([
                'company_id' => $bi_app_data->company_id,
                'status_id' => 25
            ])->whereIn('process_type_id', config('bida_service.active'))
                ->count();


            if ($count_approved_app) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry! there have approved applications of this company.  [BIC-1045]'
                ]);
            }

            DB::beginTransaction();

            $bi_process_data = ProcessList::where([
                'company_id' => $bi_app_data->company_id,
                'process_type_id' => 100,
                'ref_id' => $bi_app_data->id
            ])->first();


            $deptAndSubDept = CommonFunction::basicInfoDepSubDepSet($request->service_type);

            $bi_app_data->service_type = $request->get('service_type');
            $bi_app_data->reg_commercial_office = $request->get('reg_commercial_office');
            $bi_app_data->change_dept_reason = $request->get('change_dept_reason');
            $bi_app_data->save();

            // if the new department and sub-department are equal to the old department and sub-department,
            // then update Basic App data only, no need to proceed for the next execution.
            if ($deptAndSubDept['department_id'] == $bi_process_data->department_id && $deptAndSubDept['sub_department_id'] == $bi_process_data->sub_department_id) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'status' => 'Basic Information has been updated successfully',
                ]);
            }

            $bi_process_data->department_id = $deptAndSubDept['department_id'];
            $bi_process_data->sub_department_id = $deptAndSubDept['sub_department_id'];
            $bi_process_data->save();


            // Get all applications of this company without discard and approved status
            // to transfer all application into shortfall status and reset attachments
            $all_applications = ProcessList::where('company_id', $bi_app_data->company_id)
                ->whereIn('process_type_id', config('bida_service.active'))
                ->whereNotIn('status_id', [6, 25])
                ->get([
                    'id',
                    'ref_id',
                    'process_type_id'
                ]);

            // If application list is not empty then proceed
            if (!$all_applications->isEmpty()) {
                $application_ids_array = $all_applications->pluck('id')->toArray();


                // Send all application (without draft and waiting for payment) to shortfall status
                // N.B. The draft application will be reset while applicant submitting
                // department and sub-department will be reset at the time of appStore of each module.

                foreach ($application_ids_array as $row_id){
                    $process_data = ProcessList::where([
                        'id' => $row_id,
                        'company_id' => $company_id,
                    ])->whereNotIn('status_id', [-1, 3])->first();

                    $process_data->status_id = 5;
                    $process_data->desk_id = 0;
                    $process_data->user_id = 0;
                    $process_data->process_desc = 'Application has been sent to shortfall due to department change';
                    $process_data->save();
                }

                // Application attachment update
                $attachment_process_apps = $all_applications->filter(function ($value) {
                    // Return only those applications for which the attachment should be processed
                    return in_array($value->process_type_id, [1, 10, 2, 3, 4, 5]);
                });

                if (!$attachment_process_apps->isEmpty()) {

                    // Backup all attachment
                    $current_date_time = date('Y-m-d H:i:s');
                    $sql_cond = '';
                    $i = 1;
                    $count_apps = count($attachment_process_apps);
                    foreach ($attachment_process_apps as $key => $app) {
                        $sql_cond .= "(`process_type_id` = $app->process_type_id and `ref_id` = $app->ref_id)";
                        if ($i != $count_apps) {
                            $sql_cond .= ' or ';
                        }
                        $i++;
                    }
                    DB::statement(DB::raw("INSERT INTO
app_documents_backup
(app_documents_id, process_type_id, ref_id, doc_info_id, doc_name, doc_file_path, is_old_file, is_archive, created_at, created_by, updated_at, updated_by, backup_created_at)
select *, '$current_date_time' from `app_documents` where ($sql_cond)"));
                    // End Backup all attachment


                    // Remove old attachment
                    AppDocuments::where(function ($query) use ($attachment_process_apps) {
                        $i = 0;
                        foreach ($attachment_process_apps as $app) {
                            if ($i == 0) {
                                $query->where([
                                    'process_type_id' => $app->process_type_id,
                                    'ref_id' => $app->ref_id
                                ]);
                            } else {
                                $query->orWhere([
                                    'process_type_id' => $app->process_type_id,
                                    'ref_id' => $app->ref_id
                                ]);
                            }
                            $i++;
                        }

                    })->delete();
                    // End Remove old attachment


                    // Entry new attachment
                    foreach ($attachment_process_apps as $app) {

                        $attachment_key = $this->getAttachmentTypeKey($app->process_type_id, $app->ref_id, $bi_process_data->department_id);

                        $documents = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                            ->where('attachment_type.key', $attachment_key)
                            ->where('attachment_list.status', 1)
                            ->where('attachment_list.is_archive', 0)
                            ->orderBy('attachment_list.order')
                            ->get([
                                'attachment_list.id',
                                'attachment_list.process_type_id',
                                'attachment_list.doc_name'
                            ]);

                        $this->insertNewAttachment($documents, $app->ref_id);
                    }
                    // End Entry new attachment
                }
                // End Application attachment update
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Department has been changed successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('UpdateDepartment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1070]');
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()). ' [BIC-1070]'
            ]);
        }
    }

    public function getAttachmentTypeKey($process_type_id, $ref_id, $department_id)
    {
        $attachment_type = '';
        if ($process_type_id == 1) {
            $attachment_type = VisaRecommendation::leftJoin('dept_application_type', 'dept_application_type.id', '=', 'vr_apps.app_type_id')
                ->where('vr_apps.id', $ref_id)
                ->select('dept_application_type.attachment_key as attachment_key')
                ->pluck('attachment_key');
            $attachment_type = 'vrn' . $attachment_type;
            if ($department_id == 1) {
                $attachment_type = $attachment_type . "cml";
            } else if ($department_id == 2) {
                $attachment_type = $attachment_type . "i";
            } else {
                $attachment_type = $attachment_type . "comm";
            }
        } elseif ($process_type_id == 10) {
            $attachment_type = VisaRecommendationAmendment::leftJoin('dept_application_type', 'dept_application_type.id', '=', 'vra_apps.app_type_id')
                ->where('vra_apps.id', $ref_id)
                ->select('dept_application_type.attachment_key as attachment_key')
                ->pluck('attachment_key');
            $attachment_type = 'vra' . $attachment_type;
            if ($department_id == 1) {
                $attachment_type = $attachment_type . "cml";
            } else if ($department_id == 2) {
                $attachment_type = $attachment_type . "i";
            } else {
                $attachment_type = $attachment_type . "comm";
            }
        } elseif (in_array($process_type_id, [2, 3, 4, 5])) {
            $attachment_type = "wpn_";
            if ($department_id == 1) {
                $attachment_type = $attachment_type . "cml";
            } else if ($department_id == 2) {
                $attachment_type = $attachment_type . "i";
            } else {
                $attachment_type = $attachment_type . "comm";
            }
        }
        return $attachment_type;
    }

    public function insertNewAttachment($documents, $app_id)
    {
        if (!$documents->isEmpty()) {
            $app_documents = [];
            foreach ($documents as $document) {
                $app_documents[] = [
                    'process_type_id' => $document->process_type_id,
                    'ref_id' => $app_id,
                    'doc_info_id' => $document->id,
                    'doc_name' => $document->doc_name,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->id,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::user()->id
                ];
            }
            // Here, multiple insert using eloquent does not work perfectly.
            // so, we use query builder
            //AppDocuments::insert($app_documents);
            DB::table('app_documents')->insert($app_documents);
        }
    }

    public function updateAuthLetterIntoAssociation($company_id, $auth_letter)
    {
        // Update auth letter into Company Association table
        $company_association_data = CompanyAssociation::where([
            'user_id' => Auth::user()->id,
            'request_type' => 'Add',
            'requested_company_id' => $company_id // Current working company id
        ])->first();
        $company_association_data->authorization_letter = $auth_letter;
        $company_association_data->save();
        // End Update auth letter into Company Association table
    }

    public function uploadAuthLetter()
    {
        return View::make('BasicInformation::upload-auth-letter');
    }

    /*
     * @request get
     * @param company_id
     * @view service-list page
     *
     */

    public function showAllService($company_id)
    {
        $mode = '-V-';
        return view('BasicInformation::service-list',compact('mode', 'company_id'));
    }

    /*
     * show all services
     * @request ajax
     * @param company_id
    */

    public function getServiceList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BIC-1003]';
        }

        $company_id = Encryption::decodeId($request->get('company_id'));

        DB::statement(DB::raw('set @rownum=0'));
        $serviceList = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('company_id', $company_id)
            ->where('status_id', 25)
            ->where('process_type_id', '!=', 100)
            ->get([DB::raw('@rownum := @rownum+1 AS sl'),
                'ref_id',
                'tracking_no',
                'process_type_id',
                'completed_date as approved_date',
                'process_type.name as service_name',
                'process_type.form_url',
                'process_type.form_id'
            ]);

        return Datatables::of($serviceList)
            ->addColumn('action', function ($serviceList) {
                return '<a target="_blank" href="'.url('process/'.$serviceList->form_url.'/view-app/'.Encryption::encodeId($serviceList->ref_id) .'/'. Encryption::encodeId($serviceList->process_type_id)).
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';

            })
            ->make(true);
    }

    /*
     * @request get
     * @param company_jid
     * @view company-list page
     */

    public function showAllCompany($company_id)
    {
        $mode = '-V-';
        return view('BasicInformation::company-list',compact('mode', 'company_id'));
    }

    /*
     * show all company
     * @request ajax
     * @param company_id
     */

    public function getCompanyList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BIC-1004]';
        }

        $company_id = Encryption::decodeId($request->get('company_id'));

        DB::statement(DB::raw('set @rownum=0'));
        $company_user_list = Users::leftJoin('area_info', 'users.district', '=', 'area_info.area_id')
            ->whereRaw("FIND_IN_SET($company_id, company_ids)")
            ->where('user_type', '5x505')
            ->where('user_status', "active")
            ->where('is_approved', 1)
            ->limit(10)
            ->get(
                [DB::raw('@rownum := @rownum+1 AS sl'),
                    'users.id',
                    DB::raw("CONCAT(user_first_name,' ',user_middle_name, ' ',user_last_name) as user_full_name"),
                    'user_email',
                    'user_status',
                    'working_user_type',
                    'designation',
                    'area_info.area_nm as users_district',
                    'users.created_at'
                ]);

        return Datatables::of($company_user_list)
            ->addColumn('action', function ($company_user_list) {
                return '<a target="_blank" href="'.url('users/view/'.Encryption::encodeId($company_user_list->id)).
                    '"class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';

            })
            ->make(true);
    }

    public function DeptMoreInfoModal()
    {
        return \view('BasicInformation::department-more-info');
    }

    public static function isAllowedWithOutVAT($process_type_id=0)
    {
        $company_id = CommonFunction::getUserWorkingCompany();
        $is_allowed =  EA_CompanyWithoutVat::where([
            'company_id' => $company_id,
            'process_type_id' => $process_type_id,
            'status' => 1,
            'is_archive' => 0,
        ])->count();

        if ($is_allowed) {
            return true;
        }

        return false;
    }


    public function changeBasicInfoModal($app_id, $company_id)
    {
        $company_id = (!empty($company_id) ? Encryption::decodeId($company_id) : Auth::user()->company_ids);

        $appInfo = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
            ->leftJoin('department', 'department.id', '=', 'process_list.department_id')
            ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
            ->leftJoin('ea_service', 'ea_service.id', '=', 'ea_apps.service_type')
            ->leftJoin('ea_reg_commercial_offices', 'ea_reg_commercial_offices.id', '=', 'ea_apps.reg_commercial_office')
            ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'ea_apps.ownership_status_id')
            ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'ea_apps.organization_type_id')
            ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'ea_apps.ceo_country_id')
            ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'ea_apps.ceo_district_id')
            ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'ea_apps.ceo_thana_id')
            ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'ea_apps.office_division_id')
            ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'ea_apps.office_district_id')
            ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'ea_apps.office_thana_id')
            ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
            ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
            ->where('process_list.process_type_id', $this->process_type_id)
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
            ->first([
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.department_id',
                'department.name as department',
                'company_info.business_category',
                'ea_apps.*',
                'ea_service.name as service_name',
                'ea_reg_commercial_offices.name as reg_commercial_office_name',
                'ea_ownership_status.name as ownership_status',
                'ea_organization_type.id as organization_type_id',
                'ea_organization_type.name as organization_type',
                'ceo_country.nicename as ceo_country_name',
                'ceo_district.area_nm as ceo_district_name',
                'ceo_thana.area_nm as ceo_thana_name',
                'office_division.area_nm as office_division_name',
                'office_district.area_nm as office_district_name',
                'office_thana.area_nm as office_thana_name',
                'factory_district.area_nm as factory_district_name',
                'factory_thana.area_nm as factory_thana_name',
            ]);

        $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->whereIn('type', [1, 3])->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

        return view('BasicInformation::change-basic-info', compact('app_id', 'company_id', 'appInfo', 'countries', 'divisions', 'districts', 'thana', 'eaOrganizationType', 'eaOrganizationStatus', 'eaOwnershipStatus'));

    }



    // public function storeChangeBasicInfo(Request $request)
    // {
    //     $company_id = (!empty($request->get('company_id')) ? Encryption::decodeId($request->get('company_id')) : '');

    //     $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');

    //     try {
    //         DB::beginTransaction();

    //         $appData = BasicInformation::find($app_id);

    //         if (empty($appData)) {
    //             return response()->json([
    //                 'error' => true,
    //                 'status' => 'Sorry! No information found.'
    //             ]);
    //         }

    //         $data = [];
    //     $caption = $request->get('caption');





    //     $keys = $request->get('toggleCheck');

    //     // dd($keys, 'ok');
    //     if (count($keys)) {
    //         $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=','')
    //             ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');
    //         $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
    //         $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
    //         $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

    //         foreach ($keys as $key => $value) {
    //             $data1 = [];
    //             $data2 = [];
    //             $data1['caption'] = (isset($caption[$key]) ? $caption[$key] : '');

    //             if ($key == 'department' || $key == 'n_department') {
    //                 $data2['caption'] = (isset($caption[$key]) ? $caption[$key] : '');
    //                 $data2['old'] = ($request->has(substr($key, 2)) ? $request->get(substr($key,2)) : '');
    //                 $data2['new'] = ($request->has($key) ? $request->get($key) : '');
    //             }elseif($key == 'n_emp_nationality_id' || $key == 'n_ceo_country_id') {
    //                 $data1['old'] = ($request->has(substr($key, 2)) ? $nationality[$request->get(substr($key,2))] : '');
    //                 $data1['new'] = ($request->has($key) ? $nationality[$request->get($key)] : '');
    //             } elseif ($key == 'n_office_division_id'){
    //                 $data1['old'] = ($request->has(substr($key, 2)) ? $divisions[$request->get(substr($key,2))] : '');
    //                 $data1['new'] = ($request->has($key) ? $divisions[$request->get($key)] : '');
    //             } elseif ($key == 'n_office_district_id' || $key == 'n_factory_district_id' || $key == 'n_ceo_district_id'){
    //                 $data1['old'] = ($request->has(substr($key, 2)) ? $districts[$request->get(substr($key,2))] : '');
    //                 $data1['new'] = ($request->has($key) ? $districts[$request->get($key)] : '');
    //             } elseif ($key == 'n_office_thana_id' || $key == 'n_factory_thana_id' || $key == 'n_ceo_thana_id'){
    //                 $data1['old'] = ($request->has(substr($key, 2)) ? $thana[$request->get(substr($key,2))] : '');
    //                 $data1['new'] = ($request->has($key) ? $thana[$request->get($key)] : '');
    //             } else {
    //                 $data1['old'] = ($request->has(substr($key, 2)) ? $request->get(substr($key, 2)) : '');
    //                 $data1['new'] = ($request->has($key) ? $request->get($key) : '');
    //             }
    //             $data[] = $data1;
    //             $data[] = $data2;
    //         }
    //     }
    //     // dd($data);

    //     DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('UpdateBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1071]');
    //         return response()->json([
    //             'error' => true,
    //             'status' => CommonFunction::showErrorPublic($e->getMessage()). '[BIC-1071]'
    //         ]);
    //     }
    // }

    // public function storeChangeBasicInfo(Request $request)
    // {
    //     dd($request->caption);
    //     if (!$request->ajax()) {
    //         return 'Sorry! this is a request without proper way.';
    //     }

    //     $company_id = (!empty($request->get('company_id')) ? Encryption::decodeId($request->get('company_id')) : '');
    //     $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');

    //     if ($company_id != Auth::user()->company_ids || !ACL::getAccsessRight($this->aclName, '-E-')) {
    //         return response()->json([
    //             'error' => true,
    //             'status' => 'Sorry! You are not authorized to update the information.'
    //         ]);
    //     }

    //     // Validation Rules when application submitted
    //     $rules = [];
    //     $messages = [];

    //     // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
    //     $rules['ceo_country_id'] = 'required';
    //     $rules['ceo_nid'] = 'required_if:ceo_country_id,18';
    //     $rules['ceo_designation'] = 'required';
    //     $rules['ceo_full_name'] = 'required';
    //     $rules['ceo_district_id'] = 'required_if:ceo_country_id,18';
    //     $rules['ceo_thana_id'] = 'required_if:ceo_country_id,18';
    //     $rules['ceo_city'] = 'required_unless:ceo_country_id,18';
    //     $rules['ceo_state'] = 'required_unless:ceo_country_id,18';
    //     $rules['ceo_post_code'] = 'required';
    //     $rules['ceo_address'] = 'required';
    //     $rules['ceo_mobile_no'] = 'required';
    //     $rules['ceo_email'] = 'required|email';
    //     $rules['ceo_father_name'] = 'required_if:ceo_country_id,18';
    //     $rules['ceo_mother_name'] = 'required_if:ceo_country_id,18';
    //     $rules['ceo_gender'] = 'required';

    //     // C. Office Address
    //     $rules['office_division_id'] = 'required|numeric';
    //     $rules['office_district_id'] = 'required|numeric';
    //     $rules['office_thana_id'] = 'required|numeric';
    //     $rules['office_post_office'] = 'required';
    //     $rules['office_post_code'] = 'required|digits:4';
    //     $rules['office_address'] = 'required';
    //     $rules['office_mobile_no'] = 'required|phone_or_mobile';
    //     $rules['office_email'] = 'required|email';

    //     $validation = \Illuminate\Support\Facades\Validator::make(Input::all(), $rules, $messages);
    //     if ($validation->fails()) {
    //         return response()->json([
    //             'success' => false,
    //             'error' => $validation->errors()
    //         ]);
    //     }

    //     try {
    //         DB::beginTransaction();

    //         $appData = BasicInformation::find($app_id);

    //         if (empty($appData)) {
    //             return response()->json([
    //                 'error' => true,
    //                 'status' => 'Sorry! No information found.'
    //             ]);
    //         }

    //         // A. Company major activities
    //         $appData->major_activities = $request->get('major_activities');

    //         // B. Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
    //         $appData->ceo_country_id = $request->get('ceo_country_id');
    //         $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
    //         $appData->ceo_passport_no = $request->get('ceo_passport_no');
    //         $appData->ceo_nid = $request->get('ceo_nid');
    //         $appData->ceo_full_name = $request->get('ceo_full_name');
    //         $appData->ceo_designation = $request->get('ceo_designation');
    //         $appData->ceo_district_id = $request->get('ceo_district_id');
    //         $appData->ceo_city = $request->get('ceo_city');
    //         $appData->ceo_state = $request->get('ceo_state');
    //         $appData->ceo_thana_id = $request->get('ceo_thana_id');
    //         $appData->ceo_post_code = $request->get('ceo_post_code');
    //         $appData->ceo_address = $request->get('ceo_address');
    //         $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
    //         $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
    //         $appData->ceo_fax_no = $request->get('ceo_fax_no');
    //         $appData->ceo_email = $request->get('ceo_email');
    //         $appData->ceo_father_name = $request->get('ceo_father_name');
    //         $appData->ceo_mother_name = $request->get('ceo_mother_name');
    //         $appData->ceo_spouse_name = $request->get('ceo_spouse_name');
    //         $appData->ceo_gender = $request->get('ceo_gender');

    //         // C. Office Address
    //         $appData->office_division_id = $request->get('office_division_id');
    //         $appData->office_district_id = $request->get('office_district_id');
    //         $appData->office_thana_id = $request->get('office_thana_id');
    //         $appData->office_post_office = $request->get('office_post_office');
    //         $appData->office_post_code = $request->get('office_post_code');
    //         $appData->office_address = $request->get('office_address');
    //         $appData->office_telephone_no = $request->get('office_telephone_no');
    //         $appData->office_mobile_no = $request->get('office_mobile_no');
    //         $appData->office_fax_no = $request->get('office_fax_no');
    //         $appData->office_email = $request->get('office_email');

    //         // D. Factory Address
    //         $appData->factory_district_id = $request->get('factory_district_id');
    //         $appData->factory_thana_id = $request->get('factory_thana_id');
    //         $appData->factory_post_office = $request->get('factory_post_office');
    //         $appData->factory_post_code = $request->get('factory_post_code');
    //         $appData->factory_address = $request->get('factory_address');
    //         $appData->factory_telephone_no = $request->get('factory_telephone_no');
    //         $appData->factory_mobile_no = $request->get('factory_mobile_no');
    //         $appData->factory_fax_no = $request->get('factory_fax_no');
    //         $appData->factory_email = $request->get('factory_email');
    //         $appData->factory_mouja = $request->get('factory_mouja');

    //         $appData->save();

    //         DB::commit();

    //         return response()->json([
    //             'success' => true,
    //             'status' => 'Data has been saved successfully',
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         Log::error('UpdateBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1071]');
    //         return response()->json([
    //             'error' => true,
    //             'status' => CommonFunction::showErrorPublic($e->getMessage()). '[BIC-1071]'
    //         ]);
    //     }
    // }

}
