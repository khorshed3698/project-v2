<?php

namespace App\Modules\WaiverCondition8\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Settings\Models\Currencies;
use App\Modules\WaiverCondition8\Models\WaiverCondition8;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\WaiverCondition8\Services\WaiverCondition8Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class WaiverCondition8Controller extends Controller
{
    private static $processTypeId = 20;
    private static $aclName = "WaiverCondition8";
    protected $waiverCondition8Service;

    public function __construct(WaiverCondition8Service $waiverCondition8Service)
    {
        $this->waiverCondition8Service = $waiverCondition8Service;
    }

    public function applicationForm(Request $request)
    {
        $requestValidationCheck = $this->waiverCondition8Service->validateRequestAccess($request, '-A-', 'WAIVER8-1001', 'WAIVER8-971');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        $data = [];

        // Check whether the applicant company is eligible and have approved basic information application
        $data['company_id'] = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($data['company_id']) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services.  [WAIVER8-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        if (in_array(CommonFunction::getDeptIdByCompanyId($data['company_id']), [2, 4])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application.  [WAIVER8-1041]</h4>"
            ]);
        }

        try {

            $data['mode'] = '-A-';
            $data['viewMode'] = 'off';
            $data['process_type_id'] = self::$processTypeId;
            
            $data['payment_config'] = $this->waiverCondition8Service->getPaymentInfo(1); // Submission fee payment
            
            if (empty($data['payment_config'])) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![WAIVER8-10100]</h4>"
                ]);
            }
            $unfixed_amount_array = $this->waiverCondition8Service->unfixedAmountsForPayment($data['payment_config']);
            $data['payment_config']->amount = $unfixed_amount_array['total_unfixed_amount'] + $data['payment_config']->amount;
            $data['payment_config']->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

    
            $data['officeType'] = $this->waiverCondition8Service->getData('officeType');
            $data['countries'] = $this->waiverCondition8Service->getData('countries');
            $data['organizationTypes'] = $this->waiverCondition8Service->getData('organizationTypes');
            $data['divisions'] = $this->waiverCondition8Service->getData('divisions');
            $data['district_eng'] = $this->waiverCondition8Service->getData('district_eng');
            $data['thana_eng'] = $this->waiverCondition8Service->getData('thana_eng');
            $data['currencies'] = $this->waiverCondition8Service->getData('currencies');

            $public_html = strval(view("WaiverCondition8::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WAIVERAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1005]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [WAIVER8-1005]' . "</h4>"
            ]);
        }
    }

    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        $data['mode'] = '-E-';
        $data['viewMode'] = 'off';

        $requestValidationCheck = $this->waiverCondition8Service->validateRequestAccess($request, $data['mode'], 'WAIVER8-1002', 'WAIVER8-972');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            
            $process_type_id = self::$processTypeId;

            $data['appInfo'] =  $this->waiverCondition8Service->getAppEditInfo($process_type_id, $decodedAppId);

            $data['officeType'] =  $this->waiverCondition8Service->getData('officeType');
            $data['countries'] =  $this->waiverCondition8Service->getData('countries');
            $data['organizationTypes'] =  $this->waiverCondition8Service->getData('organizationTypes');
            $data['divisions'] =  $this->waiverCondition8Service->getData('divisions');
            $data['district_eng'] =  $this->waiverCondition8Service->getData('district_eng');
            $data['thana_eng'] =  $this->waiverCondition8Service->getData('thana_eng');

            // Get application basic company information
            $data['company_id'] = $data['appInfo']->company_id;
            $data['process_type_id'] = $process_type_id;
            $data['basic_company_info'] = CommonFunction::getBasicCompanyInfo($data['company_id']);
            $data['currencies'] =  $this->waiverCondition8Service->getData('currencies');

            $ref_app_tracking_no = $data['appInfo']->ref_app_tracking_no;
            $data['waiver7Doc'] = $this->waiverCondition8Service->getApprovedWaiver7Documents($ref_app_tracking_no);
            
            $public_html = strval(view("WaiverCondition8::application-form-edit", $data));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WaiverViewEditForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[WAIVER8-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        $data['viewMode'] = 'on';
        $data['mode'] = '-V-';

        $requestValidationCheck = $this->waiverCondition8Service->validateRequestAccess($request, $data['mode'], 'WAIVER8-1003', 'WAIVER8-973');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = self::$processTypeId;

            $data['appInfo'] = $this->waiverCondition8Service->getAppViewInfo($process_type_id, $decodedAppId);

            // Attachment
            $attachment_key = "waiver8";

            $data['document'] = $this->waiverCondition8Service->getDocument( $attachment_key, $decodedAppId);
            $data['waiver7Doc'] = AppDocuments::where('ref_id', $decodedAppId)->where('process_type_id', 19)->get();

            $ref_app_tracking_no = $data['appInfo']->ref_app_tracking_no;
            $data['waiver7Doc'] = $this->waiverCondition8Service->getApprovedWaiver7Documents($ref_app_tracking_no);

            $data['ref_app_url'] = '#';
            if (!empty($data['appInfo']->ref_app_tracking_no)) {
                $data['ref_app_url'] = url('process/' . $data['appInfo']->ref_process_type_key . '/view-app/' . Encryption::encodeId($data['appInfo']->ref_application_ref_id) . '/' . Encryption::encodeId($data['appInfo']->ref_application_process_type_id));
            }

            $data['process_type_id'] = $process_type_id;

            $public_html = strval(view("WaiverCondition8::application-form-view", $data));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('WAIVER8ViewApp : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [WAIVER8-1115]');
            return Redirect::back()->withInput();
        }
    }

    public function applicationStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight(self::$aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query.  [WAIVER8-974]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services.  [WAIVER8-9992]");
            return redirect()->back();
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            Session::flash('error', "Sorry! The department is not allowed to apply to this application.  [WAIVER8-1042]");
            return redirect()->back();
        }

        // get op new and extension info & set session
        if ($request->get('searchWaiverinfo') == 'searchWaiverinfo') {
            return waiverCondition8Service::searchWaiverInfo($request, $company_id);
        }

        // Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("waiver");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }

        $payment_config = $this->waiverCondition8Service->getPaymentInfo(1); // Submission fee payment
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [WAIVER8-2050]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            Session::flash('error', "Stakeholder not found [WAIVER8-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [WAIVER8-105]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "waiver8";

        $doc_row =  $this->waiverCondition8Service->getAttachment($attachment_key);

        // Validation Rules when application submitted
        $validation = $this->getApplicationValidationRules($request, $doc_row);
        $this->validate($request, $validation['rules'], $validation['messages']);
        

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = WaiverCondition8::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => self::$processTypeId, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new WaiverCondition8();
                $processData = new ProcessList();
            }

            $processData->company_id = $company_id;

            $appData->ref_app_tracking_no = trim($request->get('ref_app_tracking_no'));
            $appData->ref_app_approve_date = (!empty($request->get('ref_app_approve_date')) ? date('Y-m-d', strtotime($request->get('ref_app_approve_date'))) : null);

            // Office Information
            $appData->ref_office_app_tracking_no = trim($request->get('ref_office_app_tracking_no'));
            $appData->ref_office_app_approved_date = (!empty($request->get('ref_office_app_approved_date')) ? date('Y-m-d', strtotime($request->get('ref_office_app_approved_date'))) : null);
            $appData->office_type = $request->get('office_type');

            // Company Information
            $appData->company_name = $basicInfo->company_name;
            $appData->company_name_bn = $basicInfo->company_name_bn;
            $appData->service_type = $basicInfo->service_type;
            $appData->reg_commercial_office = $basicInfo->reg_commercial_office;
            $appData->ownership_status_id = $basicInfo->ownership_status_id;
            $appData->organization_type_id = $basicInfo->organization_type_id;
            $appData->major_activities = $basicInfo->major_activities;

            // Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            $appData->ceo_country_id = $basicInfo->ceo_country_id;
            $appData->ceo_dob = $basicInfo->ceo_dob;
            $appData->ceo_passport_no = $basicInfo->ceo_passport_no;
            $appData->ceo_nid = $basicInfo->ceo_nid;
            $appData->ceo_full_name = $basicInfo->ceo_full_name;
            $appData->ceo_designation = $basicInfo->ceo_designation;
            $appData->ceo_district_id = $basicInfo->ceo_district_id;
            $appData->ceo_city = $basicInfo->ceo_city;
            $appData->ceo_state = $basicInfo->ceo_state;
            $appData->ceo_thana_id = $basicInfo->ceo_thana_id;
            $appData->ceo_post_code = $basicInfo->ceo_post_code;
            $appData->ceo_address = $basicInfo->ceo_address;
            $appData->ceo_telephone_no = $basicInfo->ceo_telephone_no;
            $appData->ceo_mobile_no = $basicInfo->ceo_mobile_no;
            $appData->ceo_fax_no = $basicInfo->ceo_fax_no;
            $appData->ceo_email = $basicInfo->ceo_email;
            $appData->ceo_father_name = $basicInfo->ceo_father_name;
            $appData->ceo_mother_name = $basicInfo->ceo_mother_name;
            $appData->ceo_spouse_name = $basicInfo->ceo_spouse_name;
            $appData->ceo_gender = $basicInfo->ceo_gender;

            // Office Address
            $appData->office_division_id = $basicInfo->office_division_id;
            $appData->office_district_id = $basicInfo->office_district_id;
            $appData->office_thana_id = $basicInfo->office_thana_id;
            $appData->office_post_office = $basicInfo->office_post_office;
            $appData->office_post_code = $basicInfo->office_post_code;
            $appData->office_address = $basicInfo->office_address;
            $appData->office_telephone_no = $basicInfo->office_telephone_no;
            $appData->office_mobile_no = $basicInfo->office_mobile_no;
            $appData->office_fax_no = $basicInfo->office_fax_no;
            $appData->office_email = $basicInfo->office_email;

            $appData->approved_permission_start_date = $request->get('approved_permission_start_date') ? date("Y-m-d", strtotime($request->get('approved_permission_start_date'))) : null;
            $appData->approved_permission_end_date = $request->get('approved_permission_end_date') ? date("Y-m-d", strtotime($request->get('approved_permission_end_date'))) : null;
            $appData->approved_permission_duration = $request->get('approved_permission_duration');
            $appData->approved_permission_duration_amount = $request->get('approved_permission_duration_amount');

            $appData->c_company_name = $request->get('c_company_name');
            $appData->c_origin_country_id = $request->get('c_origin_country_id');
            $appData->c_flat_apart_floor = $request->get('c_flat_apart_floor');
            $appData->c_house_plot_holding = $request->get('c_house_plot_holding');
            $appData->c_post_zip_code = $request->get('c_post_zip_code');
            $appData->c_street = $request->get('c_street');
            $appData->c_email = $request->get('c_email');
            $appData->c_city = $request->get('c_city');
            $appData->c_telephone = $request->get('c_telephone');
            $appData->c_state_province = $request->get('c_state_province');
            $appData->c_fax = $request->get('c_fax');
            $appData->c_org_type = $request->get('c_org_type');
            $appData->c_major_activity_brief = $request->get('c_major_activity_brief');

            $appData->local_company_name = trim($request->get('local_company_name'));
            $appData->local_company_name_bn = $request->get('local_company_name_bn');

            //BD address start
            $appData->ex_office_division_id = $request->get('ex_office_division_id');
            $appData->ex_office_district_id = $request->get('ex_office_district_id');
            $appData->ex_office_thana_id = $request->get('ex_office_thana_id');
            $appData->ex_office_post_office = $request->get('ex_office_post_office');
            $appData->ex_office_post_code = $request->get('ex_office_post_code');
            $appData->ex_office_address = $request->get('ex_office_address');
            $appData->ex_office_telephone_no = $request->get('ex_office_telephone_no');
            $appData->ex_office_mobile_no = $request->get('ex_office_mobile_no');
            $appData->ex_office_fax_no = $request->get('ex_office_fax_no');
            $appData->ex_office_email = $request->get('ex_office_email');
            //BD address end

            $appData->activities_in_bd = $request->get('activities_in_bd');

            $appData->comprehensive_income_start_date = $request->get('comprehensive_income_start_date') ? date('Y-m-d', strtotime($request->get('comprehensive_income_start_date'))) : null;
            $appData->comprehensive_income_end_date = $request->get('comprehensive_income_end_date') ? date('Y-m-d', strtotime($request->get('comprehensive_income_end_date'))) : null;
            $appData->comprehensive_income_duration = $request->get('comprehensive_income_duration');

            $appData->total_revenue = $request->get('total_revenue');
            $appData->total_expense = $request->get('total_expense');
            $appData->total_comprehensive_income = $request->get('total_comprehensive_income');
            $appData->fixed_assets = $request->get('fixed_assets');
            $appData->current_assets = $request->get('current_assets');
            $appData->bank_balance = $request->get('bank_balance');
            $appData->cash_balance = $request->get('cash_balance');
            $appData->fixed_liabilities = $request->get('fixed_liabilities');
            $appData->current_liabilities = $request->get('current_liabilities');
            $appData->equility = $request->get('equility');
            $appData->acc_profit_loss = $request->get('acc_profit_loss');

            $appData->local_executive = $request->get('local_executive');
            $appData->local_stuff = $request->get('local_stuff');
            $appData->local_total = $request->get('local_total');
            $appData->foreign_executive = $request->get('foreign_executive');
            $appData->foreign_stuff = $request->get('foreign_stuff');
            $appData->foreign_total = $request->get('foreign_total');
            $appData->manpower_total = $request->get('manpower_total');
            $appData->manpower_local_ratio = $request->get('manpower_local_ratio');
            $appData->manpower_foreign_ratio = $request->get('manpower_foreign_ratio');
            $appData->est_initial_expenses = $request->get('est_initial_expenses');
            $appData->est_monthly_expenses = $request->get('est_monthly_expenses');

            //Authorized Person Information
            $appData->auth_full_name = $request->get('auth_full_name');
            $appData->auth_designation = $request->get('auth_designation');
            $appData->auth_mobile_no = $request->get('auth_mobile_no');
            $appData->auth_email = $request->get('auth_email');
            $appData->auth_image = $request->get('auth_image');
            $appData->profit_loss = $request->get('profit_loss');


            if ($request->has('accept_terms')) {
                $appData->accept_terms = 1;
            }

            $status_id = -1;
            $desk_id = 0;
            $process_desc = '';

            if ($request->get('actionBtn') == "resubmit" && $processData->status_id == 5) { // For shortfall application re-submission
                $resubmission_data = CommonFunction::getReSubmissionJson(self::$processTypeId, $app_id);
                $status_id = $resubmission_data['process_starting_status'];
                $desk_id = $resubmission_data['process_starting_desk'];
                $process_desc = 'Re-submitted form applicant';
            }

            $processData->status_id = $status_id;
            $processData->desk_id = $desk_id;
            $processData->process_desc = $process_desc;

            $appData->save();

            /*
             * Department and Sub-department specification for application processing
             */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => $department_id,
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification(self::$processTypeId, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $processData->ref_id = $appData->id;
            $processData->process_type_id = self::$processTypeId;
            $processData->process_desc = '';// for re-submit application
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            // $jsonData['Type'] = CommonFunction::getOfficeTypeById($request->get('office_type'));

            $processData['json_object'] = json_encode($jsonData);
            $processData->save();
            //Store attachment

            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
                    $app_doc = AppDocuments::firstOrNew([
                        'process_type_id' => self::$processTypeId,
                        'ref_id' => $appData->id,
                        'doc_info_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_file_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->save();
                }
            }

            // Payment info will not be updated for resubmit
            if ($processData->status_id != 2) {

                // Store payment info
                $paymentInfo = SonaliPayment::firstOrNew([
                    'app_id' => $appData->id, 
                    'process_type_id' => self::$processTypeId,
                    'payment_config_id' => $payment_config->id
                ]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = self::$processTypeId;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;

                //Concat Act & Payment
                $account_no = "";
                foreach ($stakeDistribution as $distribution) {
                    $account_no .= $distribution->stakeholder_ac_no . "-";
                }
                $account_numbers = rtrim($account_no, '-');
                //Concat Act & Payment End

                $paymentInfo->receiver_ac_no = $account_numbers;

                $unfixed_amount_array =  $this->waiverCondition8Service->unfixedAmountsForPayment($payment_config);

                $paymentInfo->pay_amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
                $paymentInfo->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];
                $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInfo->save();

                $appData->sf_payment_id = $paymentInfo->id;
                $appData->save();

                //Payment Details By Stakeholders
                foreach ($stakeDistribution as $distribution) {
                    $paymentDetails = PaymentDetails::firstOrNew([
                        'sp_payment_id' => $paymentInfo->id, 'payment_distribution_id' => $distribution->id
                    ]);
                    $paymentDetails->sp_payment_id = $paymentInfo->id;
                    $paymentDetails->payment_distribution_id = $distribution->id;

                    if ($distribution->fix_status == 1) {
                        $paymentDetails->pay_amount = $distribution->pay_amount;
                    } else {
                        $paymentDetails->pay_amount = $unfixed_amount_array['amounts'][$distribution->distribution_type];
                    }
                    $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                    $paymentDetails->purpose = $distribution->purpose;
                    $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                    $paymentDetails->fix_status = $distribution->fix_status;
                    $paymentDetails->distribution_type = $distribution->distribution_type;
                    $paymentDetails->save();
                }
                //Payment Details By Stakeholders End
            }

            // Clean session data
            Session::forget("waiver");

            /*
             * if action is submitted and application status is equal to draft
             * and have payment configuration then, generate a tracking number
             * and go to payment initiator function.
             */
            if ($request->get('actionBtn') == 'submit' && $processData->status_id == -1) {
                if(empty($processData->tracking_no)) {
                    $prefix = 'WVR8-' . date("dMY") . '-';
                    UtilFunction::generateTrackingNumber(self::$processTypeId, $processData->id, $prefix);
                }

                DB::commit();
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }


            // Send Email notification to user on application submit & re-submit
            if ($request->get('actionBtn') == "resubmit" && $processData->status_id == 2) {

                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_list.*'
                    ]);

                //get users email and phone no according to working company id
                $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_sub_name' => $processData->process_sub_name,
                    'process_type_name' => 'WAIVER',
                    'remarks' => ''
                ];

                CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
            }


            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error',
                    'Failed due to Application Status Conflict. Please try again later! [WAIVER8-1023]');
            }
            DB::commit();
            return redirect('waiver-condition-8/list/' . Encryption::encodeId(self::$processTypeId));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WAIVER8AppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[WAIVER8-1011]");
            return redirect()->back()->withInput();
        }
    }

    private function getApplicationValidationRules($request, $doc_row) {
        $rules = [];
        $messages = [];
    
        if ($request->get('actionBtn') != 'draft') {
            $rules['office_type'] = 'required';
            $rules['c_company_name'] = 'required';
            $rules['c_origin_country_id'] = 'required';
            $rules['local_company_name'] = 'required';
            $rules['ex_office_division_id'] = 'required|numeric';
            $rules['ex_office_district_id'] = 'required|numeric';
            $rules['ex_office_thana_id'] = 'required|numeric';
            $rules['ex_office_post_code'] = 'required|digits:4';
            $rules['ex_office_address'] = 'required';
            $rules['ex_office_mobile_no'] = 'required|phone_or_mobile';
            $rules['ex_office_email'] = 'required|email';
    
            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1) {
                        $rules['validate_field_' . $value->id] = 'required';
                        $messages['validate_field_' . $value->id . '.required'] = $value->doc_name . ', this file is required.';
                    }
                }
            }
    
            $rules['auth_full_name'] = 'required';
            $rules['auth_designation'] = 'required';
            $rules['auth_email'] = 'required|email';
            $rules['auth_mobile_no'] = 'required';
            $rules['accept_terms'] = 'required';
            $rules['comprehensive_income_start_date'] = 'required';
            $rules['comprehensive_income_end_date'] = 'required';
    
            $messages['c_company_name.required'] = 'Name of the principal company required.';
            $messages['ex_office_division_id.required'] = 'Local address of the principal company Division field is required';
            $messages['ex_office_district_id.required'] = 'Local address of the principal company District field is required';
            $messages['ex_office_thana_id.required'] = 'Local address of the principal company Police Station field is required';
            $messages['ex_office_post_code.required'] = 'Local address of the principal company Post Code field is required';
            $messages['ex_office_address.required'] = 'Local address of the principal company House, Flat/ Apartment, Road field is required';
            $messages['ex_office_mobile_no.required'] = 'Local address of the principal company Mobile No. field is required';
            $messages['ex_office_email.required'] = 'Local address of the principal company Email field is required';
            $messages['comprehensive_income_start_date.required'] = 'Comprehensive income for the Period start date field is required';
            $messages['comprehensive_income_end_date.required'] = 'Comprehensive income for the Period end date field is required';
        }
    
        return [
            'rules' => $rules,
            'messages' => $messages
        ];
    }

    public function getDocList(Request $request)
    {
        $attachment_key = 'waiver8';
        $viewMode = $request->get('viewMode');
        $app_id = ($request->has('app_id') ? Encryption::decodeId($request->get('app_id')) : 0);

        if (!empty($app_id)) {
            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $app_id)
                ->where('app_documents.process_type_id', self::$processTypeId);

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
                $document =  $this->waiverCondition8Service->getAttachment($attachment_key);
            }
        } else {
            $document =  $this->waiverCondition8Service->getAttachment($attachment_key);
        }

        $html = strval(view("WaiverCondition8::documents", compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    public function uploadDocument()
    {
        return View::make('WaiverCondition8::ajaxUploadFile');
    }

    public function preview()
    {
        return view("WaiverCondition8::preview");
    }

    public function afterPayment($payment_id)
    {
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPayment::find($payment_id);

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

        //get users email and phone no according to working company id
        $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

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

        try {
            DB::beginTransaction();
            // 1 = Service Fee Payment
            // tracking no generate only when payment is Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [WAIVER8-1053]');
                    return redirect('process/waiver-condition-8/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission(self::$processTypeId);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();
            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');
            return redirect('process/waiver-condition-8/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPEAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1051]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [WAIVER8-1051]');
            return redirect('process/waiver-condition-8/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPayment::find($payment_id);

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

        //get users email and phone no according to working company id
        $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

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

        try {
            DB::beginTransaction();
            /*
             * For Service Fee Payment set tracking no.
             * if payment verification status is not equal to 1
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            if ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 1) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            elseif ($paymentInfo->is_verified == 1 && $paymentInfo->payment_category_id == 1) {

                $general_submission_process_data = CommonFunction::getGeneralSubmission(self::$processTypeId);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $paymentInfo->payment_status = 1;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                Session::flash('success', 'Payment confirmation successful');
            }

            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/waiver-condition-8/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPEAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER8-1052]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . '[WAIVER8-1052]');
            return redirect('process/waiver-condition-8/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

}
