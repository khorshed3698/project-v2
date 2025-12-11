<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CompanyRegistration;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CrCorporateSubscriber;
use App\Modules\LicenceApplication\Models\CompanyRegistration\CrSubscribersAgentList;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BasicInformation\Models\EA_OrganizationType;
use App\Modules\BasicInformation\Models\EA_OwnershipStatus;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SubSector;

use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use mPDF;

class CompanyRegistrationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 104;
        $this->aclName = 'CompanyRegistration';
    }

    public function createRegistration()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->whereIn('process_list.company_id', $companyIds)
            ->first(['ea_apps.*']);
        if(empty($basicAppInfo)){
            return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
        }

        $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
            ->whereNotIn('process_list.status_id', ['-1',6])
            ->whereIn('process_list.company_id', $companyIds)
            ->count();
        if($alreadyExist > 0){
            return \redirect("licence-applications/individual-licence")->with("error","Your Application Already Exist");
        }

        $company_id = Auth::user()->company_ids;
        $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
        $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
        return view("LicenceApplication::company-registration.company-registration-add-form", compact('company_id', 'countries', 'divisions', 'districts', 'thana',
            'eaOrganizationType','eaOrganizationStatus','eaOwnershipStatus','sectors','sub_sectors','basicAppInfo'));
    }


    public function storeRegistration(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        // Check existing application
        $company_id = Auth::user()->company_ids;

        $messages = [];
        if($request->actionBtn != 'draft'){
            $rules = $this->getValudateRulesArr();
        }else{
            $rules = [];
        }

        $this->validate($request, $rules, $messages);

        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = CompanyRegistration::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
                // $annualCap = LaAnnualProductionCapacity::firstOrNew(['la_apps_id' => $app_id]);
            } else {
                $appData = new CompanyRegistration();
                $processData = new ProcessList();
                // $annualCap = new LaAnnualProductionCapacity();
            }

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
            $processData->department_id = CommonFunction::getDeptIdByCompanyId($company_id);
            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            }else{
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
            $appData->save();
            if (!empty($appData->id)) {
                CrCorporateSubscriber::where('app_id', $appData->id)->delete();
                foreach ($request->cs_name as $csKey => $csData) { // cs => corporate subscriber
                    $corporateSubscriber = new CrCorporateSubscriber();
                    $corporateSubscriber->app_id = $appData->id;
                    $corporateSubscriber->cs_name = $csData;
                    $corporateSubscriber->cs_represented_by = $request->cs_represented_by[$csKey];
                    $corporateSubscriber->cs_license_app ='';
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


            // Store payment info
            // Get Payment Configuration
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'sp_payment_configuration.id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1,  // Submission Payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0,
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if(!$payment_config){
                DB::rollback();
                Session::flash('error', "Payment configuration not found [BR-100]");
                return redirect()->back()->withInput();
            }

            $subTotal = $request->get('authorized_capital');
            $total = DB::table('pay_order_amount_setup')->get(['min_amount_bdt','max_amount_bdt','p_o_amount_bdt']);

            $totalFee = 0;
            foreach ($total as $value){
                if($value->min_amount_bdt <= $subTotal && $value->max_amount_bdt >= $subTotal){
                    $totalFee = $value->p_o_amount_bdt;
                    break;
                }
            }
            if( $totalFee == 0 && $subTotal > '1000000000000000'){
                $totalFee = '100000';
            }

            // Get SBL payment configuration
            $spg_config = config('payment.spg_settings');

            $paymentInfo = SonaliPayment::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appData->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->request_id = $spg_config['request_id_prefix'] . rand(1000000, 9999999); // Will be change later
            $paymentInfo->payment_date = date('Y-m-d');
            $paymentInfo->ref_tran_no = rand(100000000, 999999999); // This is unique on same Request Id
            $paymentInfo->ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify
            $paymentInfo->pay_amount = $totalFee+$payment_config->amount;

            $charge_amount = ($payment_config->trans_charge_percent / 100) * $payment_config->amount;
            if($charge_amount < 30){
                $charge_amount = 30;
            }
            if($charge_amount > 500){
                $charge_amount = 500;
            }
            $paymentInfo->transaction_charge_amount = $charge_amount;
            $paymentInfo->vat_amount = ($payment_config->vat_tax_percent / 100) * $payment_config->amount;
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInfo->save();


            $appData->sf_payment_id = $paymentInfo->id;
            $appData->save();

            /*
             * Payment Submission
             */
            DB::commit();

            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1 && $payment_config) {
                return redirect('spg/initiate/'.$paymentInfo->id);
            }

            if($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {
                $processData = ProcessList::find($processData->id); // Used for getting tracking number at submit
                $applicantEmailPhone = Users::where('id', Auth::user()->id)
                    ->get(['user_email', 'user_phone']);
                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_type_name' => 'Company Registration',
                    'process_supper_name' => 'Company Registration',
                    'process_sub_name' => '',
                    'remarks'=> ''
                ];

                if($processData->status_id == 2)
                    CommonFunction::sendEmailSMS('APP_RESUBMIT',$appInfo, $applicantEmailPhone);
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id ==  2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            return redirect('/licence-application/list/'.Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CRC-1060]');
            return redirect()->back()->withInput();
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
            $appInfo = ProcessList::leftJoin('cr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $crCorporateSubscriber = CrCorporateSubscriber::where('app_id', $applicationId)->get();
            $subscribersAgentList = CrSubscribersAgentList::where('app_id', $applicationId)->get();
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = ['' => 'Select One'] + AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();


            $public_html = strval(view("LicenceApplication::company-registration.company-registration-edit-form",
                compact('appInfo', 'countries', 'viewMode',
                    'mode', 'crCorporateSubscriber', 'subscribersAgentList',
                    'divisions', 'districts', 'thana','eaOrganizationType','eaOrganizationStatus','eaOwnershipStatus','sectors','sub_sectors')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[CRE-1010]"]);
        }
    }


    public function uploadDocument() {
        return View::make('LicenceApplication::ajaxUploadFile');
    }

    private function getValudateRulesArr()
    {
        $rules = [
            'business_objective' => 'required',
            "min_no_director" => 'required',
            "authorized_capital" => 'required',
            "max_no_director" => 'required',
            "number_of_shares" => 'required',
            "quorum_agm_egm" => 'required',
            "quorum_bod_meeting" => 'required',
            "duration_chairman" => 'required',
            "duration_md" => 'required',
            "value_each_share" => 'required',
            "q_shares_number" => 'required',
            "q_shares_value" => 'required',
            "q_shares_witness_agreement" => 'required',
            "q_shares_witness_name" => 'required',
            "q_shares_witness_address" => 'required',
            "witnesses_name" => 'required',
            "witnesses_address" => 'required',
            "witnesses_phone" => 'required',
            "witnesses_national_id" => 'required',
            "declaration_signed_country" => 'required',
            "declaration_signed_designation" => 'required',
            "declaration_signed_district" => 'required',
            "declaration_signed_full_name" => 'required',
            "declaration_signed_zip_code" => 'required',
            "declaration_signed_town" => 'required',
            "declaration_signed_house" => 'required',
            "declaration_signed_mobile" => 'required',
            "declaration_signed_email" => 'required',
            "declaration_signed_momorandum" => 'required',
            "declaration_signed_article" => 'required',
        ];
        return $rules;
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
                    'process_type.process_supper_name','process_type.process_sub_name',
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
                $processData->desk_id = 17;
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Tracking id update
                $trackingPrefix = 'CR-'.date("dMY").'-';
                $processTypeId = $this->process_type_id;
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");


                $appInfo['tracking_no'] = CommonFunction::getTrackingNoByProcessId($processData->id);


                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT',$appInfo, $applicantEmailPhone);
            }elseif ($paymentInfo->payment_category_id == 2){ //govt fee
                $processData->status_id = 16;
                $processData->desk_id = 7;
                $processData->read_status = 0;
                $appInfo['payment_date'] = date('d-m-Y',strtotime($paymentInfo->payment_date));
                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT',$appInfo, $applicantEmailPhone);
            }
            $processData->save();
            $appInfo['tracking_no'] = CommonFunction::getTrackingNoByProcessId($processData->id);

            // App Tracking ID store in Payment table
            SonaliPayment::where('app_id', $appInfo['app_id'])
                ->where('process_type_id', $processTypeId)
                ->update(['app_tracking_no' => $appInfo['tracking_no']]);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }catch (\Exception $e){
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }
    public function appFormPdf($appId)
    {
//dd($appId);
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {

            $applicationId =Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $companyIds =CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            $appInfo = ProcessList::leftJoin('cr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('sector_info', 'sector_info.id', '=', 'apps.business_sector_id')
                ->leftJoin('sec_sub_sector_list', 'sec_sub_sector_list.id', '=', 'apps.business_sub_sector_id')
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
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all();
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $crCorporateSubscriber = CrCorporateSubscriber::where('app_id', $applicationId)->get();
            $subscribersAgentList = CrSubscribersAgentList::where('app_id', $applicationId)->get();
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = ['' => 'Select One'] + AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();


            $contents = view("LicenceApplication::company-registration.company-registration-pdf-form",
                compact('basicAppInfo','appInfo', 'countries', 'viewMode',
                    'mode', 'crCorporateSubscriber', 'subscribersAgentList',
                    'divisions', 'districts', 'thana','eaOrganizationType','eaOrganizationStatus','eaOwnershipStatus','sectors','sub_sectors'))->render();

//           return $contents;
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
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function Payment(Request $request){
        try{

            DB::beginTransaction();

            $appId = Encryption::decodeId($request->get('app_id'));
            // Application Info
            $appInfo = ProcessList::where([
                'process_type_id' => $this->process_type_id,
                'ref_id' => $appId,
            ])->first(['tracking_no']);

            // Store payment info
            // Get Payment Configuration
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'sp_payment_configuration.id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 2,  // Government fee Payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0,
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if(!$payment_config){
                DB::rollback();
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->back()->withInput();
            }
            // Get SBL payment configuration
            $spg_config = config('payment.spg_settings');

            $paymentInfo = SonaliPayment::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appId;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = $appInfo->tracking_no;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->request_id = $spg_config['request_id_prefix'] . rand(1000000, 9999999); // Will be change later
            $paymentInfo->payment_date = date('Y-m-d');
            $paymentInfo->ref_tran_no = rand(100000000, 999999999); // This is unique on same Request Id
            $paymentInfo->ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify
            $paymentInfo->pay_amount = $payment_config->amount;

            $charge_amount = ($payment_config->trans_charge_percent / 100) * $payment_config->amount;
            if($charge_amount < 30){
                $charge_amount = 30;
            }
            if($charge_amount > 500){
                $charge_amount = 500;
            }
            $paymentInfo->transaction_charge_amount = $charge_amount;
            $paymentInfo->vat_amount = ($payment_config->vat_tax_percent / 100) * $payment_config->amount;
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInsert = $paymentInfo->save();

            CompanyRegistration::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);
            /*
            * Payment Submission
           */
            DB::commit();
            if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                //SonaliPaymentController::AppSubmissionPayment($this->process_type_id, $appData->id);
                //return \redirect('spg/application_submission_payment/'.$this->process_type_id.'/'.$appData->id);

                return redirect('spg/initiate/'.$paymentInfo->id);
            }

        }catch (\Exception $e){
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[cr-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function UpdateAd($ref_id, $requestData)
    {
        CompanyRegistration::where('id', $ref_id)->update([
            'account_number' => $requestData['acc_number'],
            'amount' => $requestData['amount']
        ]);
        return true;
    }
}
