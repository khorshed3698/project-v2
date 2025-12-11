<?php

namespace App\Modules\InputPermit\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Settings\Models\Currencies;
use App\Modules\WaiverCondition7\Models\WaiverCondition7;
use App\Modules\OfficePermissionNew\Models\OPOfficeType;
use App\Modules\OfficePermissionNew\Models\OPOrganizationType;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class InputPermitController extends Controller
{
    private static $processTypeId = 70;
    private static $aclName = "InputPermit";

    public function applicationForm(Request $request)
    {
        $requestValidationCheck = $this->validateRequestAccess($request, '-A-', 'WAIVER-1001', 'WAIVER-971');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        $data = [];

        // Check whether the applicant company is eligible and have approved basic information application
        $data['company_id'] = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($data['company_id']) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services.  [WAIVER-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        if (in_array(CommonFunction::getDeptIdByCompanyId($data['company_id']), [2, 4])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application.  [WAIVER-1041]</h4>"
            ]);
        }

        try {

            $data['mode'] = '-A-';
            $data['viewMode'] = 'off';
            $data['process_type_id'] = self::$processTypeId;
            
            $data['payment_config'] = $this->getPaymentInfo(1); // Submission fee payment
            
            if (empty($data['payment_config'])) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![WAIVER-10100]</h4>"
                ]);
            }
            $unfixed_amount_array = $this->unfixedAmountsForPayment($data['payment_config']);
            $data['payment_config']->amount = $unfixed_amount_array['total_unfixed_amount'] + $data['payment_config']->amount;
            $data['payment_config']->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

    
            $data['officeType'] = $this->getData('officeType');
            $data['countries'] = $this->getData('countries');
            $data['organizationTypes'] = $this->getData('organizationTypes');
            $data['divisions'] = $this->getData('divisions');
            $data['district_eng'] = $this->getData('district_eng');
            $data['thana_eng'] = $this->getData('thana_eng');
            $data['currencies'] = $this->getData('currencies');

            $public_html = strval(view("WaiverCondition7::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WAIVERAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1005]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [WAIVER-1005]' . "</h4>"
            ]);
        }
    }

    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        $data['mode'] = '-E-';
        $data['viewMode'] = 'off';

        $requestValidationCheck = $this->validateRequestAccess($request, $data['mode'], 'WAIVER-1002', 'WAIVER-972');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            
            $process_type_id = self::$processTypeId;

            $data['appInfo'] = $this->getAppEditInfo($process_type_id, $decodedAppId);

            $data['officeType'] = $this->getData('officeType');
            $data['countries'] = $this->getData('countries');
            $data['organizationTypes'] = $this->getData('organizationTypes');
            $data['divisions'] = $this->getData('divisions');
            $data['district_eng'] = $this->getData('district_eng');
            $data['thana_eng'] = $this->getData('thana_eng');

            // Get application basic company information
            $data['company_id'] = $data['appInfo']->company_id;
            $data['process_type_id'] = $process_type_id;
            $data['basic_company_info'] = CommonFunction::getBasicCompanyInfo($data['company_id']);
            $data['currencies'] = $this->getData('currencies');

            $public_html = strval(view("WaiverCondition7::application-form-edit", $data));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WaiverViewEditForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[WAIVER-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        $data['viewMode'] = 'on';
        $data['mode'] = '-V-';

        $requestValidationCheck = $this->validateRequestAccess($request, $data['mode'], 'WAIVER-1003', 'WAIVER-973');
        if ($requestValidationCheck !== true) {
            return $requestValidationCheck;
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = self::$processTypeId;

            // get application,process info
            $data['appInfo'] = $this->getAppViewInfo($process_type_id, $decodedAppId);

            // Attachment
            $attachment_key = "waiver7";

            $data['document'] = $this->getDocument( $attachment_key, $decodedAppId);

            $data['ref_app_url'] = '#';
            if (!empty($data['appInfo']->ref_app_tracking_no)) {
                $data['ref_app_url'] = url('process/' . $data['appInfo']->ref_process_type_key . '/view-app/' . Encryption::encodeId($data['appInfo']->ref_application_ref_id) . '/' . Encryption::encodeId($data['appInfo']->ref_application_process_type_id));
            }

            $data['process_type_id'] = $process_type_id;

            $public_html = strval(view("WaiverCondition7::application-form-view", $data));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('WAIVERViewApp : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [WAIVER-1115]');
            return Redirect::back()->withInput();
        }
    }

    public function applicationStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight(self::$aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query.  [WAIVER-974]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services.  [WAIVER-9992]");
            return redirect()->back();
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            Session::flash('error', "Sorry! The department is not allowed to apply to this application.  [WAIVER-1042]");
            return redirect()->back();
        }

        // get op new and extension info & set session
        if ($request->get('searchWaiverinfo') == 'searchWaiverinfo') {
            return self::searchWaiverInfo($request, $company_id);
        }

        // Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("waiver");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }

        $payment_config = $this->getPaymentInfo(1); // Submission fee payment
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [WAIVER-2050]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            Session::flash('error', "Stakeholder not found [WAIVER-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [WAIVER-105]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "waiver7";

        $doc_row = $this->getAttachment($attachment_key);

        // Validation Rules when application submitted
        $validation = $this->getApplicationValidationRules($request, $doc_row);
        $this->validate($request, $validation['rules'], $validation['messages']);
        

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = WaiverCondition7::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => self::$processTypeId, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new WaiverCondition7();
                $processData = new ProcessList();
            }
            $processData->company_id = $company_id;

            $appData->ref_app_tracking_no = trim($request->get('ref_app_tracking_no'));
            $appData->ref_app_approve_date = (!empty($request->get('ref_app_approve_date')) ? date('Y-m-d', strtotime($request->get('ref_app_approve_date'))) : null);


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

            $appData->office_type = $request->get('office_type');

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

            if ($request->get('actionBtn') != "draft" && $processData->status_id == 5) { // For shortfall application re-submission
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
            $jsonData['Type'] = CommonFunction::getOfficeTypeById($request->get('office_type'));

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

                $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);

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
            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1) {

                if (empty($processData->tracking_no)) {
                    // Tracking id update
                    CommonFunction::trackingNoGenerator('WVR-', self::$processTypeId, $processData->id);
                }

                DB::commit();
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }


            // Send Email notification to user on application submit & re-submit
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {

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
            } elseif (in_array($processData->status_id, [2, 8, 9])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error',
                    'Failed due to Application Status Conflict. Please try again later! [WAIVER-1023]');
            }
            DB::commit();
            return redirect('waiver-condition-7/list/' . Encryption::encodeId(self::$processTypeId));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WAIVERAppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[WAIVER-1011]");
            return redirect()->back()->withInput();
        }
    }

    public function getDocList(Request $request)
    {
        $attachment_key = 'waiver7';
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
                $document = $this->getAttachment($attachment_key);
            }
        } else {
            $document = $this->getAttachment($attachment_key);
        }

        $html = strval(view("WaiverCondition7::documents",
            compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    public function uploadDocument()
    {
        return View::make('WaiverCondition7::ajaxUploadFile');
    }

    public function preview()
    {
        return view("WaiverCondition7::preview");
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
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [WAIVER-1053]');
                    return redirect('process/waiver-condition-7/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
            return redirect('process/waiver-condition-7/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPEAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1051]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [WAIVER-1051]');
            return redirect('process/waiver-condition-7/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
            return redirect('process/waiver-condition-7/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPEAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WAIVER-1052]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . '[WAIVER-1052]');
            return redirect('process/waiver-condition-7/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function unfixedAmountsForPayment($payment_config, $relevant_info_array = [])
    {
        /**
         * DB Table Name: sp_payment_category
         * Payment Categories:
         * 1 = Service Fee Payment
         * 2 = Government Fee Payment
         * 3 = Government & Service Fee Payment
         * 4 = Manual Service Fee Payment
         * 5 = Manual Government Fee Payment
         * 6 = Manual Government & Service Fee Payment
         */

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => 0, // Govt-Vat-Fee
            6 => 0, // Govt-Vendor-Vat-Fee
        ];

        $unfixed_amount_total = 0;
        $vat_on_pay_amount_total = 0;
        foreach ($unfixed_amount_array as $key => $amount) {
            // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
            if (in_array($key, [4, 5, 6])) {
                $vat_on_pay_amount_total += $amount;
            } else {
                $unfixed_amount_total += $amount;
            }
        }

        return [
            'amounts' => $unfixed_amount_array,
            'total_unfixed_amount' => $unfixed_amount_total,
            'total_vat_on_pay_amount' => $vat_on_pay_amount_total,
        ];
    }

    public static function searchWaiverInfo($request, $company_id)
    {
        if (!$request->has('ref_app_tracking_no')) {
            Session::flash('error', 'Missing required parameter: ref_app_tracking_no [WAIVER-1080]');
            return redirect()->back();
        }

        $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));

        $getOPNEApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
            ->where('status_id', 25)
            ->where('company_id', $company_id)
            ->whereIn('process_type_id', [6, 7])
            ->first(['ref_id', 'tracking_no']);

        if (empty($getOPNEApprovedRefId)) {
            Session::flash('error', 'Sorry! approved office permission reference no. is not found or not allowed! [WAIVER-1081]');
            return redirect()->back();
        }

        //Get data from OPCommonPool
        $getOPNEinfo = UtilFunction::checkOpCommonPoolData($getOPNEApprovedRefId->tracking_no, $getOPNEApprovedRefId->ref_id);

        if (empty($getOPNEinfo)) {
            Session::flash('error', 'Sorry! office permission reference number not found by tracking no!  [WAIVER-1081].' . '<br/>' . Session::get('error'));
            return redirect()->back();
        }

        if ($getOPNEinfo->office_type != 1) {
            Session::flash('error', 'Sorry! office permission office type is not branch office  [WAIVER-108101].' . '<br/>' . Session::get('error'));
            return redirect()->back();
        }

        Session::put('waiver', $getOPNEinfo->toArray());
        Session::put('waiver.ref_app_tracking_no', $request->get('ref_app_tracking_no'));

        Session::flash('success', 'Successfully loaded office permission data. Please proceed to next step');
        return redirect()->back();
    }

    public function validateRequestAccess($request, $mode, $ajaxErrNo, $aclErrNo)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. ' . [$ajaxErrNo];
        }

        if (!ACL::getAccsessRight(self::$aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [$aclErrNo]</h4>"
            ]);
        }
        
        return true;
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

    public function getPaymentInfo($payment_category_id) {

        return PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                    'sp_payment_configuration.payment_category_id')
                    ->where([
                        'sp_payment_configuration.process_type_id' => self::$processTypeId,
                        'sp_payment_configuration.payment_category_id' => $payment_category_id, 
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
    
    }

    public function getData($dataType) {
        switch ($dataType) {
            case 'countries':
                $data = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
                break;
            case 'organizationTypes':
                $data = OPOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name', 'id');
                break;
            case 'divisions':
                $data = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'district_eng':
                $data = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'thana_eng':
                $data = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'officeType':
                $data = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name', 'id');
                break;
            case 'currencies':
                $data = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
                break;
            default:
                $data = '';
        }
            return $data;
    }

    public function getDocument($attachment_key, $decodedAppId)
    {
        return AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', self::$processTypeId)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);
    }

    public function getAttachment($attachment_key)
    {
        return Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->where('attachment_type.key', $attachment_key)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.*']);
    }

    public function getAppEditInfo($process_type_id, $decodedAppId)
    {
        return ProcessList::leftJoin('waiver_con_7_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
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
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                ]);
    }

    public function getAppViewInfo($process_type_id, $decodedAppId)
    {
        return ProcessList::leftJoin('waiver_con_7_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })

                // Reference application
                ->leftJoin('process_list as ref_process', 'ref_process.tracking_no', '=', 'apps.ref_app_tracking_no')
                ->leftJoin('process_type as ref_process_type', 'ref_process_type.id', '=', 'ref_process.process_type_id')

                ->leftJoin('op_office_type', 'op_office_type.id', '=', 'apps.office_type')
                ->leftJoin('country_info as principle_office', 'principle_office.id', '=', 'apps.c_origin_country_id')
                ->leftJoin('op_organization_type', 'op_organization_type.id', '=', 'apps.c_org_type')
                ->leftJoin('area_info as ex_office_division', 'ex_office_division.area_id', '=', 'apps.ex_office_division_id')
                ->leftJoin('area_info as ex_office_district', 'ex_office_district.area_id', '=', 'apps.ex_office_district_id')
                ->leftJoin('area_info as ex_office_thana', 'ex_office_thana.area_id', '=', 'apps.ex_office_thana_id')
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
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*',

                    'process_type.form_url',

                    'op_office_type.name as office_type_name',
                    'principle_office.nicename as principle_office_name',
                    'op_organization_type.name as op_org_name',

                    'ex_office_division.area_nm as ex_office_division_name',
                    'ex_office_district.area_nm as ex_office_district_name',
                    'ex_office_thana.area_nm as ex_office_thana_name',

                    // Reference application
                    'ref_process.ref_id as ref_application_ref_id',
                    'ref_process.process_type_id as ref_application_process_type_id',
                    'ref_process_type.type_key as ref_process_type_key'

            ]);
    }

    

    
}
