<?php

namespace App\Modules\SingleLicence\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\Department;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\apps\Models\Document;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\BasicInformation\Models\EA_RegistrationType;
use App\Modules\LicenceApplication\Models\BankAccount\BankAccount;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CompanyRegistration;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CrCorporateSubscriber;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CrSubscribersAgentList;
use App\Modules\LicenceApplication\Models\EA_OrganizationStatus;
use App\Modules\LicenceApplication\Models\EA_OrganizationType;
use App\Modules\LicenceApplication\Models\EA_OwnershipStatus;
use App\Modules\LicenceApplication\Models\Etin\Etin;
use App\Modules\LicenceApplication\Models\Etin\MainSourceOfIncome;
use App\Modules\LicenceApplication\Models\Etin\TaxpayerStatus;
use App\Modules\LicenceApplication\Models\LaInvestingCountries;
use App\Modules\LicenceApplication\Models\NameClearance\NameClearance;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessNature;
use App\Modules\LicenceApplication\Models\TradeLicence\TLLicenceType;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlaceOfBusiness;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlotCategory;
use App\Modules\LicenceApplication\Models\TradeLicence\TLPlotType;
use App\Modules\LicenceApplication\Models\TradeLicence\TLTypeOfActivity;
use App\Modules\LicenceApplication\Models\TradeLicence\TradeLicence;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\HsCodes;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SubSector;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\SingleLicence\Models\SingleLicence;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use mPDF;
use Validator;

class SingleLicenceController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {

        if(Session::has('lang')){
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 108;
        $this->aclName = 'SingleLicence';
    }

    public function singleLicenceApplication(){
        $process_type_id = Encryption::encodeId($this->process_type_id);
        $public_html = strval(view("SingleLicence::licence-list",compact('countries', 'colors',
            'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
            'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
            'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
            'industry_cat','data','getCompanyData','process_type_id')));
        return response()->json(['responseCode' => 1, 'html'=>$public_html]);
    }

    public function applicationForm(Request $request)
    {
//        if(!$request->ajax()){
//            return 'Sorry! this is a request without proper way.';
//        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! Your selected company is not eligible or you have no approved Basic Information application.</h4>"]);
        }

        try {
//            if(!$request->ajax()){
//                return 'Sorry! this is a request without proper way.';
//            }
            // Check existing application
            $process_type_id = Encryption::encodeId($this->process_type_id);
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $getCompanyData = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if(empty($getCompanyData)){
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }

            //$getCompanyData = BasicInformation::where('company_id', $companyIds[0])->where('is_approved', 1)->first();
            // dd($getCompanyData);
            $statusArr = array(5, 6, '-1'); //5 is shortfall, 6 is Discard and -1 is draft
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
                ->get();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $currencyBDT = Currencies::orderBy('code')->whereIn('code', ['BDT'])->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
            $viewMode = 'off';
            $mode = '-A-';
            $usdValue = Currencies::where('code', 'USD')->first();

            $public_html = strval(view("SingleLicence::application-form",compact('countries', 'colors',
                'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
                'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
                'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
                'industry_cat','data','getCompanyData','process_type_id')));
            return response()->json(['responseCode' => 1, 'html'=>$public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()).' [EC-1005]']);
        }
    }



    /*
     * application store
     */
    public function appStore(Request $request) {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id))
            abort('400', "You have no access right! Please contact with system admin if you have any query.");

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
            Session::flash('error', "Sorry! Your selected company is not eligible or you have no approved Basic Information application.");
            return redirect()->back();
        }

        try{
//            dd($request->all());

            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = SingleLicence::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new SingleLicence();
                $processData = new ProcessList();
            }
            $appData->country_of_origin_id = $request->get('country_of_origin_id');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->business_sector_id = $request->get('business_sector_id');

            $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
            $appData->office_division_id = $request->get('office_division_id');
            $appData->ceo_spouse_name =$request->get('ceo_spouse_name');

            $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : '');

            $appData->major_activities = $request->get('major_activities');
            $appData->factory_mouja = $request->get('factory_mouja');
            // end
            $appData->company_name = CommonFunction::getCompanyNameById($company_id);
            $appData->company_name_bn = CommonFunction::getCompanyBnNameById($company_id);
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->organization_status_id = $request->get('organization_status_id');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->ceo_city = $request->get('ceo_city');
            $appData->ceo_state = $request->get('ceo_state');
            $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_father_name = $request->get('ceo_father_name');
            $appData->ceo_mother_name = $request->get('ceo_mother_name');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_office = $request->get('office_post_office');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->office_telephone_no = $request->get('office_telephone_no');
            $appData->office_mobile_no = $request->get('office_mobile_no');
            $appData->office_fax_no = $request->get('office_fax_no');
            $appData->office_email = $request->get('office_email');
            $appData->factory_district_id = $request->get('factory_district_id');
            $appData->factory_thana_id = $request->get('factory_thana_id');
            $appData->factory_post_office = $request->get('factory_post_office');
            $appData->factory_post_code = $request->get('factory_post_code');
            $appData->factory_address = $request->get('factory_address');
            $appData->factory_telephone_no = $request->get('factory_telephone_no');
            $appData->factory_mobile_no = $request->get('factory_mobile_no');
            $appData->factory_fax_no = $request->get('factory_fax_no');
            $appData->factory_email = $request->get('factory_email');
            $appData->commercial_operation_date = (!empty($request->get('commercial_operation_date')) ? date('Y-m-d', strtotime($request->get('commercial_operation_date'))) : '');
            $appData->save();
            Session::put('single_licence_ref_id', Encryption::encodeId($appData->id));

            if ($processData->status_id == 5) { // For shortfall application re-submission
                $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id','desc')->skip(1)->take(1)->first();
                $processData->status_id = 2; // resubmit
                $processData->desk_id = $getLastProcessInfo->desk_id;
                $processData->process_desc = 'Re-submitted from applicant';
            } else {  // For new application submission
                $processData->status_id = -1;
                $processData->desk_id = 0; // 5 is Help Desk (For Basic Information Module)
//                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->read_status = 0;
            $processData->department_id = CommonFunction::getDeptIdByCompanyId($company_id);
            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            DB::commit();
            $app_id = Encryption::encodeId($appData->id);
            $viewMode = $request->get('mode'); // off = edit mode, on = view mode
            if($viewMode == null){
                return \redirect("single-licence/app-home/$app_id");
            }else{
                return \redirect("single-licence/app-home-edit/$viewMode/$app_id");
            }

        }catch (\Exception $e){
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[VR-1001]");
            return redirect()->back()->withInput();
        }
    }

    /*
     * application view/edit
     */
    public function applicationViewEdit($appId, $openMode = '', Request $request) {
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

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        try {
//            if(!$request->ajax()){
//                return 'Sorry! this is a request without proper way.';
//            }
                // Check existing application
                $process_type_id = $this->process_type_id;
                $decodedAppId = Encryption::decodeId($appId);

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $getCompanyData = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

                $appInfo = ProcessList::leftJoin('sl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                        'ps.color',
                        'apps.*'
                    ]);
                Session::put('single_licence_ref_id', Encryption::encodeId($appInfo->ref_id));

                $process_type_id = Encryption::encodeId($this->process_type_id);
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
                $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
                    ->get();
                $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
                $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
                $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
                $currencyBDT = Currencies::orderBy('code')->whereIn('code', ['BDT'])->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
                $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
                $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
                $usdValue = Currencies::where('code', 'USD')->first();

                $public_html = strval(view("SingleLicence::application-form-edit",compact('countries', 'colors',
                    'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
                    'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
                    'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
                    'industry_cat','data','getCompanyData','process_type_id')));
                return response()->json(['responseCode' => 1, 'html'=>$public_html]);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()).' [EC-1005]']);
        }
    }

    public function appHome(){
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->whereIn('process_list.company_id', $companyIds)
            ->first(['ea_apps.*']);

        if(empty($basicAppInfo)){
            return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
        }

        //Bank Account
        $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
//        $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
//            ->get();
        $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
        $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
        $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();
        $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
        $viewMode = 'off';
        $mode = '-A-';
        $usdValue = Currencies::where('code', 'USD')->first();
        $banks = ['' => 'Select one'] + Bank::where('bank_code',101)->orderBy('name')->lists('name', 'id')->all();

        //e tin
        $mainSourceIncome = ['' => 'Select One'] +MainSourceOfIncome::where('is_archive', 0)->where('is_approved',1)->orderBy('main_source_income')->lists('main_source_income','id')->all();
        $taxpayerStatus = ['' => 'Select One'] +TaxpayerStatus::where('is_archive', 0)->where('is_approved',1)->orderBy('taxpayer_status')->lists('taxpayer_status','id')->all();
        $registrationType = [''=>'Select one','1'=>'New registration','2'=>'Re-registration'];
        $companies = ['' => 'Select One'] +CompanyInfo::where('is_approved', 1)->where('company_status', 1)->orderBy('company_name')->lists('company_name','id')->all();

        //trade licence
        $document = DocInfo::where(['process_type_id' => 105, 'ctg_id' => 0, 'is_archive' => 0])
            ->get();

        //single licence
        $documentSingleLic = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
            ->get();
        $businessNature = [''=>'Select one'] + TLBusinessNature::where('status',1)->orderBy('name')->lists('name','id')->all();
        $licenceType = [''=>'Select one'] + TLLicenceType::where('status',1)->orderBy('name')->lists('name','id')->all();
        $placeOfBusiness = [''=>'Select one'] + TLPlaceOfBusiness::where('status',1)->orderBy('name')->lists('name','id')->all();
        $plotCategory = [''=>'Select one'] + TLPlotCategory::where('status',1)->orderBy('name')->lists('name','id')->all();
        $plotType = [''=>'Select one'] + TLPlotType::where('status',1)->orderBy('name')->lists('name','id')->all();
        $typeOfActivity = [''=>'Select one'] + TLTypeOfActivity::where('status',1)->orderBy('name')->lists('name','id')->all();
        $factory = [''=>'Select Item', "Yes"=>"Yes", "No"=>"No"];
        $chemical = [''=>'Select Item', "Yes"=>"Yes", "No"=>"No"];
        //// end Bank Account
        return view("SingleLicence::app-index-add",compact('countries', 'colors',
            'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
            'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
            'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
            'industry_cat','data','basicAppInfo','banks','company_id','mainSourceIncome','taxpayerStatus','registrationType','companies',
            'businessNature','licenceType','placeOfBusiness','placeOfBusiness','plotCategory','plotType','typeOfActivity','factory','chemical',
            'documentSingleLic'
        ));
    }

    public function appHomeEdit($viewMode, $app_id){
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->whereIn('process_list.company_id', $companyIds)
            ->first(['ea_apps.*']);

        if(empty($basicAppInfo)){
            return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
        }

        $decodedAppId = Encryption::decodeId($app_id);
        $appInfoBank = BankAccount::where('single_licence_ref_id',$decodedAppId)->first();
        $appInfoCompanyReg = CompanyRegistration::where('single_licence_ref_id',$decodedAppId)->first();
        $appInfoEtin = Etin::where('single_licence_ref_id',$decodedAppId)->first();
        $appInfoTradeLi = TradeLicence::where('single_licence_ref_id',$decodedAppId)->first();

        //Bank Account
        $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
//        $document = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
//            ->get();
        $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
        $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
        $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();
        $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
        $viewMode = 'off';
        $mode = '-A-';
        $usdValue = Currencies::where('code', 'USD')->first();
        $banks = ['' => 'Select one'] + Bank::where('bank_code',101)->orderBy('name')->lists('name', 'id')->all();
        $bankBranches = ['' => 'Select One'] +BankBranch::where('bank_id',2)->orderBy('branch_name')->lists('branch_name', 'id')->all();
        //e tin
        $mainSourceIncome = ['' => 'Select One'] +MainSourceOfIncome::where('is_archive', 0)->where('is_approved',1)->orderBy('main_source_income')->lists('main_source_income','id')->all();
        $taxpayerStatus = ['' => 'Select One'] +TaxpayerStatus::where('is_archive', 0)->where('is_approved',1)->orderBy('taxpayer_status')->lists('taxpayer_status','id')->all();
        $registrationType = [''=>'Select one','1'=>'New registration','2'=>'Re-registration'];

        //company reg
        $companies = ['' => 'Select One'] +CompanyInfo::where('is_approved', 1)->where('company_status', 1)->orderBy('company_name')->lists('company_name','id')->all();
        $crCorporateSubscriber = CrCorporateSubscriber::where('app_id', $appInfoCompanyReg->id)->get();
        $subscribersAgentList = CrSubscribersAgentList::where('app_id', $appInfoCompanyReg->id)->get();
        //trade licence
        $document = DocInfo::where(['process_type_id' => 105, 'ctg_id' => 0, 'is_archive' => 0])
            ->get();

        //single licence
        $documentSingleLic = DocInfo::where(['process_type_id' => $this->process_type_id, 'ctg_id' => 0, 'is_archive' => 0])
            ->get();
        $businessNature = [''=>'Select one'] + TLBusinessNature::where('status',1)->orderBy('name')->lists('name','id')->all();
        $licenceType = [''=>'Select one'] + TLLicenceType::where('status',1)->orderBy('name')->lists('name','id')->all();
        $placeOfBusiness = [''=>'Select one'] + TLPlaceOfBusiness::where('status',1)->orderBy('name')->lists('name','id')->all();
        $plotCategory = [''=>'Select one'] + TLPlotCategory::where('status',1)->orderBy('name')->lists('name','id')->all();
        $plotType = [''=>'Select one'] + TLPlotType::where('status',1)->orderBy('name')->lists('name','id')->all();
        $typeOfActivity = [''=>'Select one'] + TLTypeOfActivity::where('status',1)->orderBy('name')->lists('name','id')->all();
        $factory = [''=>'Select Item', "Yes"=>"Yes", "No"=>"No"];
        $chemical = [''=>'Select Item', "Yes"=>"Yes", "No"=>"No"];
        //// end Bank Account
        return view("SingleLicence::app-index-edit",compact('countries', 'colors',
            'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
            'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
            'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
            'industry_cat','data','basicAppInfo','banks','company_id','mainSourceIncome','taxpayerStatus','registrationType','companies',
            'businessNature','licenceType','placeOfBusiness','placeOfBusiness','plotCategory','plotType','typeOfActivity','factory','chemical',
            'documentSingleLic','appInfoBank','appInfoCompanyReg','appInfoEtin','appInfoTradeLi','bankBranches',
            'crCorporateSubscriber','subscribersAgentList'
        ));
    }



    public function uploadDocument() {
        return View::make('VisaRecommendation::ajaxUploadFile');
    }

    public function ncAppForm()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        try {
            // Check existing application
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }

            //$getCompanyData = BasicInformation::where('company_id', $companyIds[0])->where('is_approved', 1)->first();
            // dd($getCompanyData);
            // $statusArr = array(5, 6, '-1'); //5 is shortfall, 6 is Discard and -1 is draft
            $viewMode = 'off';
            $mode = '-A-';

            return view("SingleLicence::nameClearance.application-form", compact('basicAppInfo','mode','viewMode'));
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [EC-1005]']);
        }



    }
    public function ncAppStore(Request $request)
    {

        $single_licence_ref_id = Encryption::decodeId(session::get('single_licence_ref_id'));
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        $company_id = Auth::user()->company_ids;
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['is_accept'] = 'required';
            $rules['company_name'] = 'required';

        }

        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }
//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = NameClearance::where('single_licence_ref_id', $app_id);
            } else {
                $appData = new NameClearance();
            }
            $appData->company_name = $request->get('company_name');
            $appData->applicant_name = $request->get('applicant_name');
            $appData->designation = $request->get('designation');
            $appData->mobile_number = $request->get('mobile_number');
            $appData->email = $request->get('email');
            $appData->address = $request->get('address');
            $appData->single_licence_ref_id = $single_licence_ref_id;
            if(isset($request->is_accept)){
                $appData->is_accept = ($request->get('is_accept')=='on')? 1: 0;
            }
            if(isset($request->is_signature)){
                if($request->is_signature=='on'){
                    $appData->is_signature = 1;
                    $appData->digital_signature = $request->digital_signature;
                }else{
                    $appData->is_signature = 0;
                    $appData->digital_signature = '';
                }
            }


            if ($request->get('actionBtn') == "draft") {
                $appData->is_archive = 1;
            } else {
                $appData->is_archive = 0;
            }

            $appData->company_id = $company_id;
            $appData->save();

            DB::commit();


            Session::flash('success', 'Application save successfully');
            return redirect("single-licence/app-home/#tab2");


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }

    public function baAppStore(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        $company_id = Auth::user()->company_ids;
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
//        if ($request->get('actionBtn') != 'draft') {
//            $rules['is_accept'] = 'required';
//            $rules['company_name'] = 'required';
//
//        }
//        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }
//            dd($request);
            DB::beginTransaction();
//            dd($request->get('app_id'));
            if ($request->get('app_id')) {
                $appData = BankAccount::where('single_licence_ref_id', $app_id)->first();
            } else {
                $appData = new BankAccount();
            }
            if($app_id == null){
                $app_id = Encryption::decodeId(session::get('single_licence_ref_id'));
            }
            $appData->single_licence_ref_id = $app_id;
            $appData->bank_id = $request->get('bank_id');
            $appData->bank_branch_id = $request->get('bank_branch_id');
            // $appData->company_id = $request->get('company_id');
            $appData->tin_no = $request->get('tin_no');
            $appData->trade_licence = $request->get('trade_licence');
            $appData->incorporation_no = $request->get('incorporation_no');

            $appData->country_of_origin_id = $request->get('country_of_origin_id');
            $appData->ownership_status_id = $request->get('ownership_status_id');

            $appData->business_sector_id = $request->get('business_sector_id');

            $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
            $appData->office_division_id = $request->get('office_division_id');
            $appData->ceo_spouse_name =$request->get('ceo_spouse_name');

            $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : '');

            $appData->major_activities = $request->get('major_activities');
            $appData->factory_mouja = $request->get('factory_mouja');
            // end
            $appData->company_name = CommonFunction::getCompanyNameById($company_id);
            $appData->company_name_bn = CommonFunction::getCompanyBnNameById($company_id);
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->organization_status_id = $request->get('organization_status_id');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->ceo_city = $request->get('ceo_city');
            $appData->ceo_town = $request->get('ceo_town');//add field in db
            $appData->ceo_state = $request->get('ceo_state');
            $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_father_name = $request->get('ceo_father_name');
            $appData->ceo_mother_name = $request->get('ceo_mother_name');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_office = $request->get('office_post_office');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->office_telephone_no = $request->get('office_telephone_no');
            $appData->office_mobile_no = $request->get('office_mobile_no');
            $appData->office_fax_no = $request->get('office_fax_no');
            $appData->office_email = $request->get('office_email');
            $appData->factory_district_id = $request->get('factory_district_id');
            $appData->factory_thana_id = $request->get('factory_thana_id');
            $appData->factory_post_office = $request->get('factory_post_office');
            $appData->factory_post_code = $request->get('factory_post_code');
            $appData->factory_address = $request->get('factory_address');
            $appData->factory_telephone_no = $request->get('factory_telephone_no');
            $appData->factory_mobile_no = $request->get('factory_mobile_no');
            $appData->factory_fax_no = $request->get('factory_fax_no');
            $appData->factory_email = $request->get('factory_email');

            if($request->get('actionBtn') == "draft"){
                $appData->is_archive = 1;
            } else {
                $appData->is_archive = 0;
            }
//            dd($appData->tin_file_name);
//*************file upload
            $appData->tin_file_name = $request->get('tin_file_name');
            $appData->trade_file_name = $request->get('trade_file_name');
            $appData->incorporation_file_name = $request->get('incorporation_file_name');
            $appData->mem_association_file_name = $request->get('mem_association_file_name');
            $appData->art_association_file_name = $request->get('art_association_file_name');
            $appData->list_share_holder_n_director_file_name = $request->get('list_share_holder_n_director_file_name');
//*************file upload
            $appData->company_id = $company_id;
            $appData->save();

            DB::commit();


            Session::flash('success', 'Application save successfully');
            if(empty($request->get('app_id'))){
                $app_id = session::get('single_licence_ref_id');
                return redirect("single-licence/app-home/$app_id/#tab2");
            }else{
                $appEncodeId = $request->get('app_id');
                $viewMode = $request->get('mode');
                if($request->get('actionBtn') == 'Submit'){
                    return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab3");
                }
                return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab2");
            }


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect("single-licence/app-home/#tab2")->withInput();
        }
    }


    public function crAppStore(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        $company_id = Auth::user()->company_ids;
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
//        if ($request->get('actionBtn') != 'draft') {
//            $rules['is_accept'] = 'required';
//            $rules['company_name'] = 'required';
//
//        }
//        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }
//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = CompanyRegistration::where('single_licence_ref_id', $app_id)->first();
            } else {
                $appData = new CompanyRegistration();
            }
            if($app_id == null){
                $app_id = Encryption::decodeId(session::get('single_licence_ref_id'));
            }
            $appData->single_licence_ref_id = $app_id;
            $appData->company_name = $request->get('company_name');
            $appData->company_name_bn = $request->get('company_name_bn');
            $appData->country_of_origin_id = $request->get('country_of_origin_id');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->business_sector_id = $request->get('business_sector_id');
            $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
            $appData->major_activities = $request->get('major_activities');
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->organization_status_id = $request->get('organization_status_id');
            $appData->office_division_id = $request->get('office_division_id');
            $appData->office_post_office = $request->get('office_post_office');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->office_telephone_no = $request->get('office_telephone_no');
            $appData->office_mobile_no = $request->get('office_mobile_no');
            $appData->office_fax_no = $request->get('office_fax_no');
            $appData->office_email = $request->get('office_email');
            $appData->office_thana_id = $request->get('office_thana_id');

            $appData->business_objective = $request->get('business_objective');
            $appData->min_no_director = $request->get('min_no_director');
            $appData->authorized_capital = $request->get('authorized_capital');
            $appData->max_no_director = $request->get('max_no_director');
            $appData->number_of_shares = $request->get('number_of_shares');
            $appData->quorum_agm_egm = $request->get('quorum_agm_egm');
            $appData->quorum_bod_meeting = $request->get('quorum_bod_meeting');
            $appData->duration_chairman = $request->get('duration_chairman');
            $appData->duration_md = $request->get('duration_md');
            $appData->value_each_share = $request->get('value_each_share');
            $appData->q_shares_number = $request->get('q_shares_number');
            $appData->q_shares_value = $request->get('q_shares_value');
            $appData->q_shares_witness_agreement = $request->get('q_shares_witness_agreement');
            $appData->q_shares_witness_name = $request->get('q_shares_witness_name');
            $appData->q_shares_witness_address = $request->get('q_shares_witness_address');
            $appData->witnesses_name = $request->get('witnesses_name');
            $appData->witnesses_address = $request->get('witnesses_address');
            $appData->witnesses_phone = $request->get('witnesses_phone');
            $appData->witnesses_national_id = $request->get('witnesses_national_id');
            $appData->declaration_signed_country = $request->get('declaration_signed_country');
            $appData->declaration_signed_designation = $request->get('declaration_signed_designation');
            $appData->declaration_signed_district = $request->get('declaration_signed_district');
            $appData->declaration_signed_full_name = $request->get('declaration_signed_full_name');
            $appData->declaration_signed_zip_code = $request->get('declaration_signed_zip_code');
            $appData->declaration_signed_town = $request->get('declaration_signed_town');
            $appData->declaration_signed_house = $request->get('declaration_signed_house');
            $appData->declaration_signed_mobile = $request->get('declaration_signed_mobile');
            $appData->declaration_signed_email = $request->get('declaration_signed_email');
            $appData->declaration_signed_momorandum = $request->get('declaration_signed_momorandum');
            $appData->declaration_signed_article = $request->get('declaration_signed_article');
            $appData->save();

            if (!empty($appData->id)) {
                CrCorporateSubscriber::where('app_id', $appData->id)->delete();
                foreach ($request->cs_name as $csKey => $csData) { // cs => corporate subscriber
                    $corporateSubscriber = new CrCorporateSubscriber();
                    $corporateSubscriber->app_id = $appData->id;
                    $corporateSubscriber->cs_name = $csData;
                    $corporateSubscriber->cs_represented_by = $request->cs_represented_by[$csKey];
                    $corporateSubscriber->cs_license_app = $request->cs_license_app[$csKey];
                    $corporateSubscriber->cs_subscribed_share_no = $request->cs_subscribed_share_no[$csKey];
                    $corporateSubscriber->cs_district = $request->cs_district[$csKey];
                    $corporateSubscriber->save();
                }
                CrSubscribersAgentList::where('app_id', $appData->id)->delete();
                foreach ($request->lsa_name as $lsaKey => $lsaData) { // lsa => List of Subscribers Agent
                    $subscribersAgent = new CrSubscribersAgentList();
                    $subscribersAgent->app_id = $appData->id;
                    $subscribersAgent->lsa_name = $lsaData;
                    $subscribersAgent->lsa_position = $request->lsa_position[$lsaKey];
                    $subscribersAgent->lsa_no_subs_share = $request->lsa_no_subs_share[$lsaKey];
                    $subscribersAgent->save();
                }
            }

            DB::commit();

            Session::flash('success', 'Application save successfully');
            if(empty($request->get('app_id'))){
                return redirect("single-licence/app-home/#tab4");
            }else{
                $appEncodeId = $request->get('app_id');
                $viewMode = $request->get('mode');
                if($request->get('actionBtn') == 'Submit'){
                    return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab4");
                }
                return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab3");
            }



        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect("single-licence/app-home/#tab3")->withInput();
        }
    }

    public function etinAppStore(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }
//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = Etin::where('single_licence_ref_id', $app_id)->first();
            } else {
                $appData = new Etin();
            }

            if($app_id == null){
                $app_id = Encryption::decodeId(session::get('single_licence_ref_id'));
            }
            $appData->single_licence_ref_id = $app_id;
            $appData->taxpayer_status = $request->get('taxpayer_status');
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->reg_type = $request->get('reg_type');
            if($appData->reg_type == '2' ){
                $appData->existing_tin_no = $request->get('existing_tin_no');

            }
            if($appData->reg_type == '1' ){
                $appData->main_source_income = $request->get('main_source_income');
                $appData->company_id = $request->get('company_id');
                $appData->main_source_income_location = $request->get('main_source_income_location');
            }
            $appData->company_name = $request->get('company_name');
            $appData->incorporation_certificate_number = $request->get('incorporation_certificate_number');
            $appData->incorporation_certificate_date =(!empty($request->get('incorporation_certificate_date')) ? CommonFunction::changeDateFormat($request->get('incorporation_certificate_date'), true) : '');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->reg_office_country_id = $request->get('reg_office_country_id');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->other_address_country_id = $request->get('other_address_country_id');
            $appData->other_address_thana_id = $request->get('other_address_thana_id');
            $appData->other_address_district_id = $request->get('other_address_district_id');
            $appData->other_address_post_code = $request->get('other_address_post_code');
            $appData->other_address = $request->get('other_address');


            if($request->get('actionBtn') == "draft"){
                $appData->is_archive = 1;
            } else {
                $appData->is_archive = 0;
            }
            $appData->save();

            DB::commit();


            Session::flash('success', 'Application save successfully');
            if(empty($request->get('app_id'))){
                return redirect("single-licence/app-home/#tab5");
            }else{
                $appEncodeId = $request->get('app_id');
                $viewMode = $request->get('mode');
                if($request->get('actionBtn') == 'Submit'){
                    return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab5");
                }
                return \redirect("single-licence/app-home-edit/$viewMode/$appEncodeId/#tab4");
            }


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect("single-licence/app-home/#tab4")->withInput();
        }
    }

    public function tlAppStore(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        $company_id = Auth::user()->company_ids;
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
//        if ($request->get('actionBtn') != 'draft') {
//            $rules['is_accept'] = 'required';
//            $rules['company_name'] = 'required';
//
//        }
//        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }
//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = TradeLicence::where('single_licence_ref_id', $app_id);
            } else {
                $appData = new TradeLicence();
            }

            $single_licence_ref_id = Encryption::decodeId(session::get('single_licence_ref_id'));
            $appData->single_licence_ref_id = $single_licence_ref_id;
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

            $appData->business_zone = $request->get('business_zone') != '' ? $request->get('business_zone') : null ;
            $appData->business_zone_value = $request->get('business_zone') != '' ? $request->get('business_zone_value') : null ;

            $appData->business_ward = $request->get('business_ward') != '' ? $request->get('business_ward') : null;
            $appData->business_ward_value = $request->get('business_ward') != '' ? $request->get('business_ward_value') : null;

            $appData->business_area = $request->get('business_area') != '' ? $request->get('business_area') : null;
            $appData->business_area_value = $request->get('business_area') != '' ? $request->get('business_area_value') : null;

            $appData->business_shop = $request->get('business_shop');
            $appData->business_floor = $request->get('business_floor');
            $appData->business_nature = $request->get('business_nature');
            $appData->business_start_date = (!empty($request->get('business_start_date')) ? date('Y-m-d', strtotime($request->get('business_start_date'))) : '');

            $appData->business_category = $request->get('business_category') != '' ? $request->get('business_category') : null;
            $appData->business_category_value = $request->get('business_category') != '' ? $request->get('business_category_value') : null;

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


//            if($request->get('actionBtn') == "draft"){
//                $appData->is_archive = 1;
//            } else {
//                $appData->is_archive = 0;
//            }
            $appData->save();

            DB::commit();


            Session::flash('success', 'Application save successfully');
            return redirect("single-licence/app-home/#tab6");


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect("single-licence/app-home/#tab5")->withInput();
        }
    }

    public function attachment(Request $request)
    {

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->count();
            if($alreadyExist > 0){
                return \redirect()->back()->with("error","Your Application Already Exist");
            }

            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = AppDocuments::where('single_licence_ref_id', $app_id);
            } else {
                $appData = new AppDocuments();
            }
            $single_licence_ref_id = Encryption::decodeId(session::get('single_licence_ref_id'));

            //  Required Documents for attachment
            $doc_row = DocInfo::where(['process_type_id' => $this->process_type_id, 'is_archive' => 0])
                ->get(['id', 'doc_name']);
            if (count($doc_row)>0) {
                foreach ($doc_row as $docs) {
                    $documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
                    $document_id = $docs->id;

                    // if this input file is new data then create
                    if ($request->get('document_id_' . $docs->id) == '') {
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $single_licence_ref_id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id),
                            'is_old_file' => $request->get('is_old_file_' . $docs->id)
                        ];
                        AppDocuments::create($insertArray);
                    } // if this input file is old data then update
                    else {
                        $oldDocumentId = $request->get('document_id_' . $docs->id);
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id)
                        ];
                        AppDocuments::where('id', $oldDocumentId)->update($insertArray);
                    }
                }
            }


            DB::commit();


            Session::flash('success', 'Document save successfully');
            return redirect("single-licence/app-home/#tab7");


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect("single-licence/app-home/#tab6")->withInput();
        }
    }


    public function afterPayment($payment_id){
        try{
            if(empty($payment_id)){
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            DB::beginTransaction();
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPayment::find($payment_id);
            $processData = ProcessList::leftJoin('process_type','process_type.id','=','process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_list.*'
                ]);

            $applicantEmailPhone = Users::where('id', Auth::user()->id)
                ->get(['user_email', 'user_phone']);

            $appInfo = [
                'app_id' => $processData->ref_id,
                'status_id' => $processData->status_id,
                'process_type_id' => $processData->process_type_id,
                'tracking_no' => $processData->tracking_no,
                'process_type_name' => $processData->process_type_name,
                'process_supper_name' => $processData->process_supper_name,
                'process_sub_name' => $processData->process_sub_name,
                'remarks'=> ''
            ];


            if($paymentInfo->payment_category_id == 1){
                $processData->status_id = 1;
                $processData->desk_id = 1;
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Tracking id update
                $trackingPrefix = 'VR-'.date("dMY").'-';
                $processTypeId = $this->process_type_id;
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");

                // application submission mail sending
                $appInfo['tracking_no'] = CommonFunction::getTrackingNoByProcessId($processData->id);
                CommonFunction::sendEmailSMS('APP_SUBMIT',$appInfo, $applicantEmailPhone);
            }elseif ($paymentInfo->payment_category_id == 2){
                $processData->status_id = 16;
                $processData->desk_id = 1;
                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y',strtotime($paymentInfo->payment_date));
                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT',$appInfo, $applicantEmailPhone);
            }
            $processData->save();
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/visa-recommendation/view/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        }catch (\Exception $e){
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect()->back();
        }
    }

}
