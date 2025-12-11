<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Modules\LicenceApplication\Models\LicenceApplication;
use App\Modules\LicenceApplication\Models\NameClearance\NameClearance;
use App\Modules\ProcessPath\Models\ProcessType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class LicenceApplicationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 102;
        $this->aclName = 'LicenceApplication';
    }

    public function appHome()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;
        return view("LicenceApplication::app-index", compact('company_id'));
    }

    public function licenceList()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;
        return view("LicenceApplication::licence-list", compact('company_id'));
    }

    public function individualLicence()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;

        $sql2 = "SELECT 
                        (select  concat(count(process_list.id),'@', max(nc_apps.id),'@',IFNULL(nc_apps.cert_valid_until,''),'@', process_list.process_type_id) from `process_list` 
                        left join `nc_apps` on `process_list`.`ref_id` = `nc_apps`.`id`                        
                        where `process_list`.`process_type_id` = 107
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) nc_application,
                        
                        (select concat(count(process_list.id),'@', ba_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `ba_apps` on `process_list`.`ref_id` = `ba_apps`.`id`                        
                        where `process_list`.`process_type_id` = 103
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) ba_application,
                        
                        (select  concat(count(process_list.id),'@', rjsc_nr_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `rjsc_nr_apps` on `process_list`.`ref_id` = `rjsc_nr_apps`.`id`                        
                        where `process_list`.`process_type_id` = 104
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) cr_application,
                        
                        (select  concat(count(process_list.id),'@', etin_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `etin_apps` on `process_list`.`ref_id` = `etin_apps`.`id`                        
                        where `process_list`.`process_type_id` = 106
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) etin_application,
                        
                        (select  concat(count(process_list.id),'@', tl_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `tl_apps` on `process_list`.`ref_id` = `tl_apps`.`id`                        
                        where `process_list`.`process_type_id` = 105
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) tl_application,
                        
                        (select  concat(count(process_list.id),'@', doe_master.id,'@', process_list.process_type_id) from `process_list` 
                        left join `doe_master` on `process_list`.`ref_id` = `doe_master`.`id`                        
                        where `process_list`.`process_type_id` = 108
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) doe_application,
                        
                        
                        (select  concat(count(process_list.id),'@', bpdb_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `bpdb_apps` on `process_list`.`ref_id` = `bpdb_apps`.`id`                        
                        where `process_list`.`process_type_id` = 109
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) newcon_bpdb,
                        
                        (select  concat(count(process_list.id),'@', cda_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `cda_apps` on `process_list`.`ref_id` = `cda_apps`.`id`                        
                        where `process_list`.`process_type_id` = 110
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) cda_app,
       
                        (select  concat(count(process_list.id),'@', cci_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `cci_apps` on `process_list`.`ref_id` = `cci_apps`.`id`                        
                        where `process_list`.`process_type_id` = 113
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) cci_apps,
                        
                        (select  concat(count(process_list.id),'@', dpdc_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `dpdc_apps` on `process_list`.`ref_id` = `dpdc_apps`.`id`                        
                        where `process_list`.`process_type_id` = 114
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) dpdc_apps,
       
                        (select  concat(count(process_list.id),'@', bpdb_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `bpdb_apps` on `process_list`.`ref_id` = `bpdb_apps`.`id`                        
                        where `process_list`.`process_type_id` = 109
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) bpdb_apps,
       
                         (select  concat(count(process_list.id),'@', breb_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `breb_apps` on `process_list`.`ref_id` = `breb_apps`.`id`                        
                        where `process_list`.`process_type_id` = 115
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) breb_apps,
                        
                        (select  concat(count(process_list.id),'@', vat_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `vat_apps` on `process_list`.`ref_id` = `vat_apps`.`id`                        
                        where `process_list`.`process_type_id` = 112
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) vat_application,
       
                        (select  concat(count(process_list.id),'@', nesco_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `nesco_apps` on `process_list`.`ref_id` = `nesco_apps`.`id`                        
                        where `process_list`.`process_type_id` = 116
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) nesco_apps,
                        
                        (select  concat(count(process_list.id),'@', desco_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `desco_apps` on `process_list`.`ref_id` = `desco_apps`.`id`                        
                        where `process_list`.`process_type_id` = 117
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) desco_apps,
       
                        (select  concat(count(process_list.id),'@', lspp_cda_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `lspp_cda_apps` on `process_list`.`ref_id` = `lspp_cda_apps`.`id`                        
                        where `process_list`.`process_type_id` = 118
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) lspp_cda_apps,
       
                        (select  concat(count(process_list.id),'@', bcc_cda_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `bcc_cda_apps` on `process_list`.`ref_id` = `bcc_cda_apps`.`id`                        
                        where `process_list`.`process_type_id` = 121
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) bcc_cda_apps,
                        
                        (select  concat(count(process_list.id),'@', tl_dscc_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `tl_dscc_apps` on `process_list`.`ref_id` = `tl_dscc_apps`.`id`                        
                        where `process_list`.`process_type_id` = 119
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) tl_dscc_apps,
       
                        (select  concat(count(process_list . id), '@', wzpdcl_apps . id, '@', process_list . process_type_id) from `process_list`
                        left join `wzpdcl_apps` on `process_list` . `ref_id` = `wzpdcl_apps` . `id`
                        where `process_list` . `process_type_id` = 120
                        and process_list . company_id in($company_id)
                        and `process_list` . `status_id` not in(6) ) wzpdcl_apps,
                        
                        (select  concat(count(process_list.id),'@', tl_dscc_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `tl_dscc_apps` on `process_list`.`ref_id` = `tl_dscc_apps`.`id`                        
                        where `process_list`.`process_type_id` = 122
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) dncc_apps,
                        
                        (select  concat(count(process_list.id),'@', dcci_cos_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `dcci_cos_apps` on `process_list`.`ref_id` = `dcci_cos_apps`.`id`                        
                        where `process_list`.`process_type_id` = 123
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) dcci_cos_apps,
                        
                        (select  concat(count(process_list.id),'@', rajuk_luc_general_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `rajuk_luc_general_apps` on `process_list`.`ref_id` = `rajuk_luc_general_apps`.`id`                        
                        where `process_list`.`process_type_id` = 123
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) rajuk_luc_general_apps, 
                        
                        (select  concat(count(process_list.id),'@', sb_account_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `sb_account_apps` on `process_list`.`ref_id` = `sb_account_apps`.`id`                        
                        where `process_list`.`process_type_id` = 126
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) sb_account_apps,
                        
                        (select  concat(count(process_list.id),'@', etin_foreigner_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `etin_foreigner_apps` on `process_list`.`ref_id` = `etin_foreigner_apps`.`id`                        
                        where `process_list`.`process_type_id` = 127
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) etin_foreigner_apps, 
                        
                        (select  concat(count(process_list.id),'@', erc_apps.id,'@', process_list.process_type_id) from `process_list` 
                        left join `erc_apps` on `process_list`.`ref_id` = `erc_apps`.`id`                        
                        where `process_list`.`process_type_id` = 128
                        and process_list.company_id in ($company_id) 
                        and `process_list`.`status_id` not in (6) ) erc_apps,

                        (select  concat(count(process_list.id),'@', ctcc_apps.id,'@', process_list.process_type_id) from `process_list`
                        left join `ctcc_apps` on `process_list`.`ref_id` = `ctcc_apps`.`id`
                        where `process_list`.`process_type_id` = 125
                        and process_list.company_id in ($company_id)
                        and `process_list`.`status_id` not in (6) ) ctcc_apps";

        $licenseApplications = \DB::select(DB::raw($sql2))[0];

        return view("LicenceApplication::individual-licence", compact('company_id', 'licenseApplications'));
    }

    /*
     * Application edit or view
     */
    public function appLicenceForm(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => " < h4 style = 'color: red;margin-top: 250px;margin-left: 70px;' > You have no access right!Contact with system admin for more information </h4 > "]);
        }
        try {
//            if(!$request->ajax()){
//                return 'Sorry! this is a request without proper way.';
//            }
            // Check existing application
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $getCompanyData = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);
            if (empty($getCompanyData)) {
                return response()->json(['responseCode' => 1, 'html' => "<center ><h4 style = 'color: red;margin-top: 250px;margin-left: 70px;' > Sorry!You have no approved Basic Information application .</h4 ></center > "]);
            }

            //$getCompanyData = BasicInformation::where('company_id', $companyIds[0])->where('is_approved', 1)->first();
            //dd($getCompanyData);
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

            $public_html = strval(view("LicenceApplication::application - form", compact('countries', 'colors',
                'code', 'eaOwnershipStatus', 'currencies', 'divisions', 'districts', 'thana', 'departmentList', 'zoneType', 'units', 'company_name',
                'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
                'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode', 'usdValue',
                'industry_cat', 'data', 'getCompanyData', 'currencyBDT')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [EC-1005]']);
        }
    }

    /*
     * Application store
     */
    public function appLicenceFormStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        // Check existing application
        //$company_id = $request->get('company_id');
        $company_id = Auth::user()->company_ids;
        $companyIds = CommonFunction::getUserCompanyWithZero();

        // check this company have approved basic information application
        $basic_info = ProcessList::where('process_type_id', 100)
            ->where('status_id', 25)
            ->whereIn('company_id', $companyIds)
            ->first();

        if (empty($basic_info)) {
            DB::rollback();
            Session::flash('error', 'Sorry! You have no approved Basic Information application.');
            return redirect()->back()->withInput();
        }
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            //$rules['country_of_origin_id'] = 'required';
            //$rules['business_sub_sector_id'] = 'required';
            //$rules['ownership_status_id'] = 'required';
            //$rules['organization_type_id'] = 'required';
            //$rules['organization_type_id'] = 'required';
            //$rules['organization_status_id'] = 'required';
            //$rules['is_registered'] = 'required';
            //$rules['registration_no'] = 'required_if:registered_by_id,1,3';
            //$rules['registration_date'] = 'required_if:registered_by_id,1,3|date|date_format:d-M-Y';
            //$rules['ceo_full_name'] = 'required';
            //$rules['ceo_designation'] = 'required';
            // $rules['ceo_country_id'] = 'required';
            //$rules['ceo_district_id'] = 'required';
            //$rules['ceo_thana_id'] = 'required';
            // $rules['ceo_post_code'] = 'required';
            // $rules['ceo_address'] = 'required';
            // $rules['ceo_mobile_no'] = 'required';
            // $rules['ceo_email'] = 'required';
            // $rules['ceo_father_name'] = 'required_if:ceo_country_id,18';
            // $rules['ceo_mother_name'] = 'required_if:ceo_country_id,18';
            //$rules['ceo_nid'] = 'required_if:ceo_country_id,18';
            //$rules['ceo_passport_no'] = 'required_unless:ceo_country_id,18';

            //$rules['office_district_id'] = 'required';
            //$rules['office_thana_id'] = 'required';
            // $rules['office_post_office'] = 'required';
            // $rules['office_post_code'] = 'required';
            // $rules['office_address'] = 'required';
            // $rules['office_mobile_no'] = 'required';
            // $rules['office_email'] = 'required';
        } else {
            if ($request->hasFile('tin_file_path'))
                $rules['tin_file_path'] = 'required|mimes:pdf|max:3072';
        }
        //print_r($request->all());
        //dd($rules);
        //  $this->validate($request, $rules, $messages);

        try {
            //  dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = LicenceApplication::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new LicenceApplication();
                $processData = new ProcessList();
            }

            $appData->country_of_origin_id = $request->get('country_of_origin_id');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->local_executive = $request->get('local_executive');
            $appData->local_stuff = $request->get('local_stuff');
            $appData->local_total = $request->get('local_total');
            $appData->foreign_executive = $request->get('foreign_executive');
            $appData->foreign_stuff = $request->get('foreign_stuff');
            $appData->foreign_total = $request->get('foreign_total');
            $appData->manpower_total = $request->get('manpower_total');
            $appData->manpower_local_ratio = $request->get('manpower_local_ratio');
            $appData->manpower_foreign_ratio = $request->get('manpower_foreign_ratio');

            $appData->business_sector_id = $request->get('business_sector_id');

            $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
            $appData->office_division_id = $request->get('office_division_id');
            $appData->ceo_spouse_name = $request->get('ceo_spouse_name');

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

            $appData->local_sales = $request->get('local_sales');
            $appData->foreign_sales = $request->get('foreign_sales');

//            $appData->local_fixed_ivst = $request->get('local_fixed_ivst');
//            $appData->local_fixed_ivst_ccy = $request->get('local_fixed_ivst_ccy');
//            $appData->foreign_fixed_ivst = $request->get('foreign_fixed_ivst');
//            $appData->foreign_fixed_ivst_ccy = $request->get('foreign_fixed_ivst_ccy');
//            $appData->total_fixed_ivst_single = $request->get('total_fixed_ivst_single');

            $appData->local_land_ivst = $request->get('local_land_ivst');
            //database can't take more fields
            $appData->local_land_ivst_ccy = $request->get('local_land_ivst_ccy');
//            $appData->foreign_land_ivst = $request->get('foreign_land_ivst');
//            $appData->foreign_land_ivst_ccy = $request->get('foreign_land_ivst_ccy');
//            $appData->total_land_ivst = $request->get('total_land_ivst');

            $appData->local_machinery_ivst = $request->get('local_machinery_ivst');
            $appData->local_machinery_ivst_ccy = $request->get('local_machinery_ivst_ccy');
//            $appData->foreign_machinery_ivst = $request->get('foreign_machinery_ivst');
//            $appData->foreign_machinery_ivst_ccy = $request->get('foreign_machinery_ivst_ccy');
//            $appData->total_machinery_ivst = $request->get('total_machinery_ivst');
            $appData->local_building_ivst = $request->get('local_building_ivst');
            $appData->local_building_ivst_ccy = $request->get('local_building_ivst_ccy');

            $appData->local_others_ivst = $request->get('local_others_ivst');
            $appData->local_others_ivst_ccy = $request->get('local_others_ivst_ccy');
//            $appData->foreign_others_ivst = $request->get('foreign_others_ivst');
//            $appData->foreign_others_ivst_ccy = $request->get('foreign_others_ivst_ccy');
//            $appData->total_others_ivst = $request->get('total_others_ivst');

            $appData->local_wc_ivst = $request->get('local_wc_ivst');
            $appData->local_wc_ivst_ccy = $request->get('local_wc_ivst_ccy');
//            $appData->foreign_wc_ivst = $request->get('foreign_wc_ivst');
//            $appData->foreign_wc_ivst_ccy = $request->get('foreign_wc_ivst_ccy');
//            $appData->total_wc_ivst = $request->get('total_wc_ivst');

            $appData->total_fixed_ivst = $request->get('total_fixed_ivst');
            $appData->total_working_capital = $request->get('total_working_capital');

            $appData->finance_src_loc_equity_1 = $request->get('finance_src_loc_equity_1');
            $appData->finance_src_loc_equity_2 = $request->get('finance_src_loc_equity_2');

            $appData->finance_src_foreign_equity_1 = $request->get('finance_src_foreign_equity_1');
            $appData->finance_src_foreign_equity_2 = $request->get('finance_src_foreign_equity_2');

            $appData->finance_src_loc_total_equity_1 = $request->get('finance_src_loc_total_equity_1');

            $appData->finance_src_loc_loan_1 = $request->get('finance_src_loc_loan_1');

            $appData->finance_src_total_loan = $request->get('finance_src_total_loan');
            $appData->finance_src_foreign_loan_1 = $request->get('finance_src_foreign_loan_1');
            $appData->finance_src_loc_total_financing_1 = $request->get('finance_src_loc_total_financing_1');
            $appData->finance_src_loc_total_financing_2 = "";
            $appData->public_land = isset($request->public_land) ? 1 : 0;
            $appData->public_electricity = isset($request->public_electricity) ? 1 : 0;
            $appData->public_gas = isset($request->public_gas) ? 1 : 0;
            $appData->public_telephone = isset($request->public_telephone) ? 1 : 0;
            $appData->public_road = isset($request->public_road) ? 1 : 0;
            $appData->public_water = isset($request->public_water) ? 1 : 0;
            $appData->public_drainage = isset($request->public_drainage) ? 1 : 0;
            $appData->public_others = isset($request->public_others) ? 1 : 0;

            if ($request->get('actionBtn') == "draft") {
                $appData->is_archive = 1;
            } else {
                $appData->is_archive = 0;
            }

            $appData->tin_number = $request->get('tin_number');

            if ($request->hasFile('tin_file_path')) {
                $yearMonth = date("Y") . " / " . date("m") . " / ";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_tin_file_path = $request->file('tin_file_path');
                $tin_file_path = trim(uniqid('BIDA_EA-' . $company_id . '-', true) . $_tin_file_path->getClientOriginalName());
                $_tin_file_path->move($path, $tin_file_path);
                $appData->tin_file_path = $yearMonth . $tin_file_path;
            }

            $appData->accept_terms = (!empty($request->get('acceptTerms')) ? 1 : 0);
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
            $appData->company_id = $company_id;
            $appData->save();

            if (!empty($appData->id)) {
                //$productName = $request->get('product_name');
                foreach ($request->apc_product_name as $proKey => $proData) {
                    $annualCap = '';
                    $annualCap->app_id = $appData->id;
                    $annualCap->product_name = $proData;
                    $annualCap->hs_code = $request->apc_hs_code[$proKey];
                    //$annualCap->hs_code = $hsCode[$proKey];
                    //  $quantity = $request->get('quantity');
                    $annualCap->quantity = $request->apc_quantity[$proKey];
                    //$price_usd = $request->get('price_usd');
                    $annualCap->price_usd = $request->apc_price_usd[$proKey];
                    // $price_taka = $request->get('price_taka');
                    $annualCap->price_taka = $request->apc_value_taka[$proKey];
                    $annualCap->save();
                }
//                foreach ($request->get('invt_country_id') as $key=>$value){
//                    if($value=='' && $request->invt_country_amount[$key]=='' && $request->invt_country_equity[$key]==''){
//                        continue;
//                    }
//                    $la_invt_countries = new LaInvestingCountries();
//                    $la_invt_countries->app_id = $appData->id;
//                    $la_invt_countries->invt_country_id = $value;
//                    $la_invt_countries->invt_country_amount = $request->invt_country_amount[$key];
//                    $la_invt_countries->invt_country_equity = $request->invt_country_equity[$key];
//                    $la_invt_countries->save();
//
//                }
            }
            /*
            * Department and Sub-department specification for application processing
            */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => CommonFunction::getDeptIdByCompanyId($company_id),
                'app_type' => $request->get('organization_status_id'),
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];
            $processData->department_id = $basic_info->department_id;
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


            //  Required Documents for attachment
            $doc_row = DocInfo::where('process_type_id', $this->process_type_id)->get(['id', 'doc_name']);
            if (isset($doc_row)) {
                foreach ($doc_row as $docs) {
                    $documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
                    $document_id = $docs->id;
                    // if this input file is new data then create
                    if ($request->get('document_id_' . $docs->id) == '') {
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 1 for Space Allocation
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id)
                        ];
                        AppDocuments::create($insertArray);
                    } // if this input file is old data then update
                    else {
                        $oldDocumentId = $request->get('document_id_' . $docs->id);
                        $insertArray = [
                            'process_type_id' => $this->process_type_id, // 2 for General Form
                            'ref_id' => $appData->id,
                            'doc_info_id' => $document_id,
                            'doc_name' => $documentName,
                            'doc_file_path' => $request->get('validate_field_' . $docs->id)
                        ];
                        AppDocuments::where('id', $oldDocumentId)->update($insertArray);
                    }
                }
            } /* End file uploading */

            // Store payment info
            // Get Payment Configuration
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'sp_payment_configuration.id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1,  // Submission Payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0,
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                DB::rollback();
                Session::flash('error', "Payment configuration not found [BR - 100]");
                return redirect()->back()->withInput();
            }

            $subTotal = $request->get('total_fixed_ivst');
            $total = DB::table('pay_order_amount_setup')->get(['min_amount_bdt', 'max_amount_bdt', 'p_o_amount_bdt']);

            $totalFee = 0;
            foreach ($total as $value) {
                if ($value->min_amount_bdt <= $subTotal && $value->max_amount_bdt >= $subTotal) {
                    $totalFee = $value->p_o_amount_bdt;
                    break;
                }
            }
            if ($totalFee == 0 && $subTotal > '1000000000000000') {
                $totalFee = '100000';
            }

            // Get SBL payment configuration
            $spg_config = config('payment.spg_settings');

            $paymentInfo = SonaliPayment::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appData->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->tracking_no = '';
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->request_id = $spg_config['request_id_prefix'] . rand(1000000, 9999999); // Will be change later
            $paymentInfo->payment_date = date('Y-m-d');
            $paymentInfo->ref_tran_no = rand(100000000, 999999999); // This is unique on same Request Id
            $paymentInfo->ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify
            $paymentInfo->pay_amount = $totalFee + $payment_config->amount;
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInfo->save();


            $appData->sf_payment_id = $paymentInfo->id;
            $appData->save();

            if ($processData->status_id == 0) {
                dd('Application status not found!');
            }

            /*
             * Payment Submission
             */
            DB::commit();
            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1 && $payment_config) {
                return redirect('spg/initiate/' . $paymentInfo->id);
            }


            if ($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {
                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
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
                    'process_sub_name' => $processData->process_sub_name,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_type_name' => $processData->process_type_name,
                    'remarks' => ''
                ];

                if ($processData->status_id == 2)
                    CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }
//            return redirect('licence-application/app-home/' . Encryption::encodeId($this->process_type_id));
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
            //return redirect('licence-application/app-home');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }

    /*
     * Application edit or view
     */
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
            return response()->json(['responseCode' => 1, 'html' => " < h4 style = 'color: red;margin-top: 250px;margin-left: 70px;' > You have no access right!Contact with system admin for more information </h4 > "]);
        }

        try {

            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('br_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
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
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*'
                ]);
            $laAnnualProductionCapacity = '';
            $departmentList = Department::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $eaRegistrationType = ['' => 'Select one'];
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $countries = ['' => 'Select One'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $usdValue = Currencies::where('code', 'USD')->first();
            $document = DocInfo::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $clrDocuments = [];

            $clr_document = AppDocuments::where('ref_id', $applicationId)->where('process_type_id', $this->process_type_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['document_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['file'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }
            $public_html = strval(view("LicenceApplication::application - form - edit",
                compact('appInfo', 'countries', 'viewMode', 'clrDocuments', 'document',
                    'mode', 'eaOwnershipStatus', 'sectors', 'sub_sectors', 'eaOrganizationType',
                    'eaOrganizationStatus', 'eaRegistrationType', 'divisions', 'districts', 'departmentList', 'currencies', 'laAnnualProductionCapacity', 'usdValue')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[PR - 1010]"]);
        }
    }

    public function getDistrictByDivision(Request $request)
    {
        $division_id = $request->get('divisionId');
        $districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $companyIds = CommonFunction::getUserCompanyWithZero();

//            dd($basicAppInfo);

            // get application,process info
            $appInfo = ProcessList::leftJoin('br_apps as apps', 'apps.id', '=', 'process_list.ref_id')
//                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
//                ->leftJoin('visa_types as visa_type', 'visa_type.id', '=', 'visa_cat.visa_type_id') // visa type
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
//                    'user_desk.desk_name',
                    'ps.status_name',
//                    'ps.color',
                    'apps.*'
                ]);
            $currencies = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
            $departmentList = Department::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $userCompanyList = CompanyInfo::where('id', [$appInfo->company_id])->get(['company_name', 'company_name_bn', 'id']);
            $eaRegistrationType = ['' => 'Select one'] + EA_RegistrationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana_eng = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $la_annual_production_capacity ='';
            $document = DocInfo::where(['process_type_id' => $process_type_id, 'is_archive' => 0])->get();
            $clrDocuments = [];
            $clr_document = AppDocuments::where('ref_id', $appInfo->id)->where('process_type_id', $this->process_type_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_info_id]['document_id'] = $documents->id;
                $clrDocuments[$documents->doc_info_id]['doc_file_path'] = $documents->doc_file_path;
                $clrDocuments[$documents->doc_info_id]['doc_name'] = $documents->doc_name;
            }


            $contents = view("LicenceApplication::application - form - pdf",
                compact('appInfo',  'countries', 'currencies', 'thana_eng',
                     'divisions', 'districts', 'userCompanyList', 'departmentList',
                    'eaRegistrationType', 'eaOrganizationStatus', 'eaOrganizationType', 'document', 'clrDocuments',
                    'eaOwnershipStatus', 'sectors', 'sub_sectors', 'la_annual_production_capacity'
                ))->render();
//return $contents;
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
                    <table width="100 % ">
                        <tr>
                            <td width="50 % "><i style="font - size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50 % " align="right"><i style="font - size: 10px;">{PAGENO}/{nbpg}</i></td>
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
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [LA-1115]');
            return Redirect::back()->withInput();
        }
    }

    public function getHsList(Request $request)
    {
        $results = HsCodes::where('hs_code', 'LIKE', '%' . $request->get('q') . '%')->get(['hs_code', 'product_name', 'id']);

        $data = array();
        foreach ($results as $key => $value) {
            $data[] = array(
                'value' => $value->hs_code,
                'product' => $value->product_name,
                'id' => $value->id);
        }

        return json_encode($data);
    }

    public function uploadDocument()
    {
        return View::make('LicenceApplication::ajaxUploadFile');
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
            $paymentInfo = SonaliPayment::find($payment_id);
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name', 'process_type.process_sub_name',
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
                'remarks' => ''
            ];

            if ($paymentInfo->payment_status == 1) {
                $processData->status_id = 1;
                $processData->desk_id = 1;
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Tracking id update
                $trackingPrefix = 'BR-' . date("dMY") . '-';
                $processTypeId = $this->process_type_id;
                DB::statement("update  process_list, process_list as table2  SET process_list . tracking_no = (
    select concat('$trackingPrefix',
        LPAD(IFNULL(MAX(SUBSTR(table2 . tracking_no, -5, 5)) + 1, 1), 5, '0')
    ) as tracking_no
                             from(select * from process_list ) as table2
                             where table2 . process_type_id = '$processTypeId' and table2 . id != '$processData->id' and table2 . tracking_no like '$trackingPrefix%'
                        )
                      where process_list . id = '$processData->id' and table2 . id = '$processData->id'");

                $appInfo['tracking_no'] = CommonFunction::getTrackingNoByProcessId($processData->id);

                // App Tracking ID store in Payment table
                SonaliPayment::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $processTypeId)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect()->back();
        }
    }

    public function RegNoGenerate($app_id)
    {
        $appInfo = LicenceApplication::where('id', $app_id)->first();
        if ($appInfo->reg_no == null) {
            $prefix = '';
            if ($appInfo->organization_status_id == 1) {  //1 = Joint Venture
                $prefix = 'J';
            } elseif ($appInfo->organization_status_id == 2) { //2= Foreign
                $prefix = 'F';
            } elseif ($appInfo->organization_status_id == 3) { // 3= Local
                $prefix = 'L';
            }
            $regNo = $prefix . " - " . date("Ymd") . '00' . $app_id . " - H";
            $appInfo->reg_no = $regNo;
            $appInfo->save();
        }


    }

    public static function getNcvalidationdata($app_id)
    {
        $ncdatabyid = NameClearance::where('id', $app_id)->pluck('cert_valid_until');
        return $ncdatabyid;
    }

    public function individualLicenceNEw(){
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $company_id = Auth::user()->company_ids;
        $stakeholder_services = ProcessType::leftjoin('api_stackholder as stakeholder','stakeholder.process_type_id','=','process_type.id')
            ->where('bida_service_status',2)
            ->where('status', 1)
            ->orderBy('stakeholder.order_seq', 'asc')
            ->orderBy('process_supper_name', 'asc')
            ->select([
                'stakeholder.id as statkeid',
                'process_type.id as id',
                'stakeholder.service_url',
                DB::raw("(select count(id) from process_list where process_list.process_type_id = process_type.id and company_id=$company_id) as total_app"),
                'logo',
                'form_url',
                'process_supper_name',
                'process_sub_name',
                'stakeholder.order_seq'

            ])->get();

        return view("LicenceApplication::individual-licence-new", compact( 'stakeholder_services'));

    }
    public function preview(Request $request)
    {
        if(!empty($request->get('form'))){
            $getData = explode('@',$request->get('form'));
            $formId = $getData[0];
            $formStep = $getData[1];

        }
        return view("LicenceApplication::preview",compact('formId','formStep'));
    }

}
