<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\Department;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\BasicInformation\Models\EA_RegistrationType;
use App\Modules\LicenceApplication\Models\EA_OrganizationStatus;
use App\Modules\LicenceApplication\Models\EA_OrganizationType;
use App\Modules\LicenceApplication\Models\LaAnnualProductionCapacity;
use App\Modules\LicenceApplication\Models\BankAccount\BankAccount;
use App\Modules\LicenceApplication\Models\LaInvestingCountries;
use App\Modules\LicenceApplication\Models\LicenceApplication;
use App\Modules\LicenceApplication\Models\EA_OwnershipStatus;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\Settings\Models\HsCodes;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SubSector;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
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

class BankAccountController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        if(Session::has('lang')){
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 103;
        $this->aclName = 'BankAccount';
    }



    /*
     * Application store
     */
    public function appStore(Request $request)
    {
//        $data = $request;
       // dd($request->get('app_id'));
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check existing application
        //$company_id = $request->get('company_id');
        $company_id = Auth::user()->company_ids;
        $statusArr = array('-1', 6, 5); //6 is Discard, 5 is Rejected Application and -1 is draft
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['bank_id'] = 'required';
            $rules['bank_branch_id'] = 'required';
            $rules['resolution_bank_file_name'] = 'required';
        }
        //print_r($request->all());
        //dd($rules);
        $this->validate($request, $rules, $messages);

        try {

//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = BankAccount::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);


            } else {

                $appData = new BankAccount();
                $processData = new ProcessList();
               // $annualCap = new LaAnnualProductionCapacity();
            }
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
            $appData->resolution_bank_file_name = $request->get('resolution_bank_file_name');
            $appData->art_association_file_name = $request->get('art_association_file_name');
            $appData->list_share_holder_n_director_file_name = $request->get('list_share_holder_n_director_file_name');
//*************file upload

           // $appData->accept_terms = (!empty($request->get('acceptTerms')) ? 1 : 0);
            $processData->department_id = CommonFunction::getDeptIdByCompanyId($company_id);
            if ($request->get('actionBtn') == "draft" && $appData->status_id != 2) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id','desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1; //1 default -1 = if payment
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }
            $appData->company_id = $company_id;
            $appData->save();



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

            // Generate Tracking No for Submitted application
//            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
//                $trackingPrefix = "BA-" . date("dmY");
//                $processTypeId = $this->process_type_id;
//                $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
//                                                            select concat('$trackingPrefix',
//                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
//                                                                          ) as tracking_no
//                                                             from (select * from process_list ) as table2
//                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
//                                                        )
//                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");
//            }

//            if($request->get('actionBtn') != "draft" && ($processData->status_id == 1 || $processData->status_id == 2)) {
//                $getTrackingNo = ProcessList::where('id',$processData->id)->pluck('tracking_no');
//                $appStatus = $processData->status_id == 2 ? 're-submitted' : 'submitted';
//                $email_header_inner = $processData->status_id == 2 ? 're-submission' : 'submission';
//                $body_msg = '<span text-align:justify;">';
//                $body_msg .= 'Your application <b>(' . $getTrackingNo . ')</b> for Bank account has been ' .$appStatus. ' successfully. We will provide feedback soon.If you have any questions you may contact with System Admin and mention your application Tracking Number.';
//                $body_msg .= '</span>';
//                $body_msg .= '<br/><br/><br/>Thanks<br/>';
//                $body_msg .= '<b>'.env('PROJECT_NAME').'</b>';
//                $header = 'Application '.$email_header_inner.' notification';
//                $param = $body_msg;
//                $email_content = view("Users::message", compact('header', 'param'))->render();
//                $emailQueue = new EmailQueue();
//                $emailQueue->process_type_id = $this->process_type_id; // service_id of Licence Application
//                $emailQueue->app_id = $appData->id;
//                $emailQueue->email_content = $email_content;
//                $emailQueue->email_to = Auth::user()->user_email;
//                $emailQueue->sms_content =  'Congratulations! Your application (' . $getTrackingNo . ')  for Licence Application has been ' .$appStatus. ' successfully. (' . env('PROJECT_NAME') . ')';
//                $emailQueue->sms_to =  Auth::user()->user_phone;
//                $emailQueue->email_subject = $header;
//                $emailQueue->attachment = '';
//                $emailQueue->save();
//            }
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
                Session::flash('error', "Payment configuration not found [NC-107]");
                return redirect()->back()->withInput();
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
            $paymentInfo->save();
            $appData->sf_payment_id = $paymentInfo->id;
            $appData->save();

            /*
             * Payment Submission
             */
            DB::commit();

//************
//        return redirect('basic-information/list/'.Encryption::encodeId($this->process_type_id));

//*********
            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1 && $payment_config) {
                //SonaliPaymentController::AppSubmissionPayment($this->process_type_id, $appData->id);
                //return \redirect('spg/application_submission_payment/'.$this->process_type_id.'/'.$appData->id);
                return redirect('spg/initiate/'.$paymentInfo->id);
            }

            // Mail send for application submit, re-submit
            if($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {
                //$processData = ProcessList::find($processData->id); // Used for getting tracking number at submit

                $processData = ProcessList::leftJoin('process_type','process_type.id','=','process_list.process_type_id')
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
                    'process_supper_name' => $processData->process_supper_name,
                    'process_sub_name' => $processData->process_sub_name,
                    'process_type_name' => 'Bank Account',
                    'remarks'=> ''
                ];

                if($processData->status_id == 2)
                    CommonFunction::sendEmailSMS('APP_RESUBMIT',$appInfo, $applicantEmailPhone);
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
            return redirect('/licence-application/list/'.Encryption::encodeId($this->process_type_id));
//            return redirect('licence-application/app-home/' . Encryption::encodeId($this->process_type_id));
//            return redirect('licence-applications/individual-licence');
//            return redirect('basic-information/list/'.Encryption::encodeId($this->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
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
                $processData->desk_id = 18;
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Tracking id update
                $trackingPrefix = 'BA-'.date("dMY").'-';
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

                // App Tracking ID store in Payment table
//                SonaliPayment::where('app_id', $appInfo['app_id'])
//                    ->where('process_type_id', $processTypeId)
//                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT',$appInfo, $applicantEmailPhone);
            }elseif ($paymentInfo->payment_category_id == 2){ //govt fee
                $processData->status_id = 16;
                $processData->desk_id = 15;
                $processData->read_status = 0;
                $appInfo['payment_date'] = date('d-m-Y',strtotime($paymentInfo->payment_date));
                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT',$appInfo, $applicantEmailPhone);
            }

            $processData->save();

            $appInfo['tracking_no'] = CommonFunction::getTrackingNoByProcessId($processData->id);

            // App Tracking ID store in Payment table
            SonaliPayment::where('app_id', $appInfo['app_id'])
                ->where('process_type_id', $this->process_type_id)
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
    /*
     * Application edit or view
     */

    public function getDistrictByDivision(Request $request) {
        $division_id = $request->get('divisionId');
        $districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }


    public function appForm()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        try {
//            if(!$request->ajax()){
//                return 'Sorry! this is a request without proper way.';
//            }
            // Check existing application
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
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso');
            $viewMode = 'off';
            $mode = '-A-';
            $usdValue = Currencies::where('code', 'USD')->first();
            $banks = ['' => 'Select one'] + Bank::where('is_active',1)->orderBy('name')->lists('name', 'id')->all();

//            $public_html = strval(view("LicenceApplication::application-form",compact('countries', 'colors',
//                'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
//                'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
//                'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
//                'industry_cat','data','getCompanyData')));
//            return response()->json(['responseCode' => 1, 'html'=>$public_html]);
            return view("LicenceApplication::bankAccount.application-form",compact('countries', 'colors',
                'code', 'eaOwnershipStatus', 'currencies','divisions', 'districts', 'thana', 'departmentList','zoneType', 'units', 'company_name',
                'businessIndustryServices', 'sectors', 'sub_sectors', 'typeofOrganizations', 'typeofIndustry', 'document',
                'eaOrganizationType', 'eaOrganizationStatus', 'nationality', 'viewMode', 'mode','usdValue',
                'industry_cat','data','basicAppInfo','banks'));
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()).' [EC-1005]']);
        }
    }
    public function getBankBranch(Request $request){
        $bankBranches = BankBranch::where('bank_id',$request->bank_id)->orderBy('branch_name')->get(['branch_name', 'id'])->toArray();
        $data = ['responseCode' => 1, 'data' => $bankBranches];
        return response()->json($data);

    }

    public function uploadDocument() {
        return View::make('LicenceApplication::bankAccount.ajaxUploadFile');
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
            $appInfo = ProcessList::leftJoin('ba_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $banks = ['' => 'Select One'] +Bank::where('is_active',1)->orderBy('name')->lists('name', 'id')->all();
            $bankBranches = ['' => 'Select One'] +BankBranch::orderBy('branch_name')->lists('branch_name', 'id')->all();
                        //dd($appInfo);
//
           $public_html = strval(view("LicenceApplication::bankAccount.application-form-edit",
                compact( 'appInfo','countries', 'viewMode',
                    'mode',  'eaOwnershipStatus', 'sectors', 'sub_sectors', 'eaOrganizationType',
                    'eaOrganizationStatus', 'eaRegistrationType', 'divisions', 'districts', 'departmentList','currencies','usdValue','banks','bankBranches')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[PR-1010]"]);
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

            $appInfo = ProcessList::leftJoin('ba_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $banks = ['' => 'Select One'] +Bank::where('is_active',1)->orderBy('name')->lists('name', 'id')->all();
            $bankBranches = ['' => 'Select One'] +BankBranch::orderBy('branch_name')->lists('branch_name', 'id')->all();
            $thana = ['' => 'Select One'] + AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
//dd($appInfo);
            $contents = view("LicenceApplication::bankAccount.application-form-pdf",
                compact('basicAppInfo','appInfo','countries', 'viewMode',
                    'mode',  'eaOwnershipStatus', 'sectors', 'sub_sectors', 'eaOrganizationType','thana',
                    'eaOrganizationStatus', 'eaRegistrationType', 'divisions', 'districts', 'departmentList','currencies','usdValue','banks','bankBranches'))->render();

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
            $paymentInfo->app_tracking_no = '';
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

            BankAccount::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);
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
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[VRA-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function UpdateAd($ref_id, $requestData)
    {
        BankAccount::where('id', $ref_id)->update([
            'account_number' => $requestData['acc_number'],
            'amount' => $requestData['amount']
        ]);
        return true;
    }
}
