<?php

namespace App\Modules\VipLounge\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\Airports;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\Countries;
use App\Modules\VipLounge\Models\VipLounge;
use App\Modules\VipLounge\Models\VipLonguePurpose;
use App\Modules\VipLounge\Models\ViplPassportHolderInfo;
use App\Modules\VipLounge\Models\ViplSpouseChildInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class VipLoungeController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 17;
        $this->aclName = 'VipLounge';
    }

    public function applicationForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VIPLC-1001]';
        }

        $mode = '-A-';
        $viewMode = 'off';
        $process_type_id = $this->process_type_id;

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VIPLC-971]</h4>"
            ]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [VIPLC-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        try {
            $department_id = CommonFunction::getDeptIdByCompanyId($company_id);

            // Checking the payment configuration for this service
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1,
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (empty($payment_config)) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![VIPLC-10100]</h4>"
                ]);
            }

            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            $airports = Airports::orderby('name')->lists('name', 'id');
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $vip_longue_purpose = VipLonguePurpose::where('status', 1)->where('is_archive', 0)->lists('purpose', 'id')->all();
            $spouse_child_type =
                [
                    ''          => 'Select One',
                    'Spouse'    => 'Spouse',
                    'Child'     => 'Child'
                ];
            $ref_no_types =
                [
                    'BIDA Registration' => 'BIDA Registration',
                    'Office Permission' => 'Office Permission',
                    'Incorporation'     => 'Incorporation',
                    'Others'            => 'Others',
                ];

            $public_html = strval(view("VipLounge::application-form",
                compact('process_type_id', 'company_id', 'payment_config', 'airports', 'nationality', 'viewMode', 'mode', 'department_id', 'vip_longue_purpose', 'spouse_child_type', 'ref_no_types')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNAppForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-1005]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [VIPLC-1005]' . "</h4>"
            ]);
        }
    }

    public function appStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query. [VIPLC-972]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services. [VIPLC-9991]");
            return redirect()->back();
        }

        // Checking the Service Fee Payment(SFP) configuration for this service
        $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
            'sp_payment_configuration.payment_category_id')
            ->where([
                'sp_payment_configuration.process_type_id' => $this->process_type_id,
                'sp_payment_configuration.payment_category_id' => 1,  // Submission Payment
                'sp_payment_configuration.status' => 1,
                'sp_payment_configuration.is_archive' => 0,
            ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
        if (empty($payment_config)) {
            DB::rollback();
            Session::flash('error', "Payment configuration not found [VIPLC-100]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            DB::rollback();
            Session::flash('error', "Stakeholder not found [VIPLC-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [VIPLC-105]");
            return redirect()->back()->withInput();
        }

        // Validation Rules when application submitted
        $rules = [];
        $messages = [];

        if ($request->get('actionBtn') != 'draft') {
            if (empty($request->get('investor_photo_base64'))) {
                $rules['investor_photo_name'] = 'required';
            } else {
                $rules['investor_photo_base64'] = 'required';
            }

            //previous travel history attachment
            if (isset($travel_history_docs) && count($travel_history_docs) > 0 && $request->get('th_emp_work_permit') == 'yes') {
                foreach ($travel_history_docs as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }

            $rules['emp_name'] = 'required';
            $rules['emp_designation'] = 'required';

            $rules['emp_passport_no'] = 'required';
            $rules['emp_surname'] = 'required';
            $rules['place_of_issue'] = 'required';
            $rules['emp_given_name'] = 'required';
            $rules['emp_nationality_id'] = 'required|numeric';
            $rules['emp_date_of_birth'] = 'required|date|date_format:d-M-Y';
            $rules['emp_place_of_birth'] = 'required';
            $rules['pass_issue_date'] = 'required|date|date_format:d-M-Y';
            $rules['pass_expiry_date'] = 'required|date|date_format:d-M-Y';

            $rules['accept_terms'] = 'required';

            $messages['emp_name.required'] = 'General Information: Full Name field is required';
            $messages['emp_designation.required'] = 'General Information: Position/ Designation field is required';

            $messages['emp_passport_no.required'] = 'Passport Information: Passport No field is required';
            $messages['emp_surname.required'] = 'Passport Information: Surname field is required';
            $messages['place_of_issue.required'] = 'Passport Information: Issuing authority field is required';
            $messages['emp_given_name.required'] = 'Passport Information: Given Name field is required';
            $messages['emp_nationality_id.required'] = 'Passport Information: Nationality field is required';
            $messages['emp_nationality_id.numeric'] = 'Passport Information: Nationality must be numeric.';
            $messages['emp_date_of_birth.required'] = 'Passport Information: Date of Birth field is required';
            $messages['emp_date_of_birth.date'] = 'Passport Information: Date of Birth must be date fotmat.';
            $messages['emp_place_of_birth.required'] = 'Passport Information: Place of Birth field is required';
            $messages['pass_issue_date.required'] = 'Passport Information: Date of issue field is required';
            $messages['pass_issue_date.date'] = 'Passport Information: Date of issue must be date fotmat.';
            $messages['pass_expiry_date.required'] = 'Passport Information: Date of expiry field is required';
            $messages['pass_expiry_date.date'] = 'Passport Information: Date of expiry must be date fotmat.';

        } else {
            $rules['vip_longue_purpose_id'] = 'required';

            $messages['vip_longue_purpose_id.required'] = 'Vip Longue Purpose is required';
        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = VipLounge::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new VipLounge();
                $processData = new ProcessList();
            }

            $processData->company_id = $company_id;
            $appData->vip_longue_purpose_id = $request->get('vip_longue_purpose_id');


            if ($request->has('accept_terms')) {
                $appData->accept_terms = 1;
            }

            // Company Information
            $appData->company_name = $basicInfo->company_name;
            $appData->company_name_bn = $basicInfo->company_name_bn;
            $appData->service_type = $basicInfo->service_type;
            $appData->reg_commercial_office = $basicInfo->reg_commercial_office;
            $appData->ownership_status_id = $basicInfo->ownership_status_id;
            $appData->organization_type_id = $basicInfo->organization_type_id;
            $appData->major_activities = $basicInfo->major_activities;

            //business category
            $appData->business_category = Auth::user()->company->business_category;;

            // Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            $appData->ceo_country_id = $basicInfo->ceo_country_id;
            $appData->ceo_passport_no = $basicInfo->ceo_passport_no;
            $appData->ceo_nid = $basicInfo->ceo_nid;
            $appData->ceo_full_name = $basicInfo->ceo_full_name;
            $appData->ceo_designation = $basicInfo->ceo_designation;
            $appData->ceo_mobile_no = $basicInfo->ceo_mobile_no;
            $appData->ceo_email = $basicInfo->ceo_email;
            $appData->ceo_gender = $basicInfo->ceo_gender;

            $appData->ceo_dob = $basicInfo->ceo_dob;
            $appData->ceo_district_id = $basicInfo->ceo_district_id;
            // $appData->ceo_city = $basicInfo->ceo_city;
            // $appData->ceo_state = $basicInfo->ceo_state;
            $appData->ceo_thana_id = $basicInfo->ceo_thana_id;
            $appData->ceo_post_code = $basicInfo->ceo_post_code;
            $appData->ceo_address = $basicInfo->ceo_address;
            $appData->ceo_telephone_no = $basicInfo->ceo_telephone_no;
            $appData->ceo_fax_no = $basicInfo->ceo_fax_no;
            $appData->ceo_father_name = $basicInfo->ceo_father_name;
            $appData->ceo_mother_name = $basicInfo->ceo_mother_name;
            $appData->ceo_spouse_name = $basicInfo->ceo_spouse_name;

            // Factory Address
            $appData->factory_district_id = $basicInfo->factory_district_id;
            $appData->factory_thana_id = $basicInfo->factory_thana_id;
            $appData->factory_post_office = $basicInfo->factory_post_office;
            $appData->factory_post_code = $basicInfo->factory_post_code;
            $appData->factory_address = $basicInfo->factory_address;
            $appData->factory_telephone_no = $basicInfo->factory_telephone_no;
            $appData->factory_mobile_no = $basicInfo->factory_mobile_no;
            $appData->factory_fax_no = $basicInfo->factory_fax_no;
            $appData->factory_email = $basicInfo->factory_email;
            $appData->factory_mouja = $basicInfo->factory_mouja;


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

            $appData->ref_no_type = $request->get('ref_no_type');
            $appData->reference_number = $request->get('reference_number');

            $appData->airport_id = $request->get('airport_id');
            $appData->visa_purpose = $request->get('visa_purpose');

            $appData->emp_name = $request->get('emp_name');
            $appData->emp_designation = $request->get('emp_designation');
            $appData->brief_job_description = $request->get('brief_job_description');

            // Investor Photo upload
            if (isset($request->investor_photo_base64) && $request->investor_photo_base64 != '') {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $splited = explode(',', substr($request->get('investor_photo_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage = base64_decode($base64ResizeImage);
                $file_name = trim(sprintf("%s", uniqid('VIPL_', true))) . str_replace(" ", "_", $request->get('investor_photo_name'));

                file_put_contents($path . $file_name, $base64ResizeImage);
                $appData->investor_photo = $yearMonth . $file_name;
            }

            $appData->emp_passport_no = $request->get('emp_passport_no');
            $appData->emp_personal_no = $request->get('emp_personal_no');
            $appData->emp_surname = $request->get('emp_surname');
            $appData->place_of_issue = $request->get('place_of_issue');
            $appData->emp_given_name = $request->get('emp_given_name');
            $appData->emp_nationality_id = $request->get('emp_nationality_id');
            $appData->emp_date_of_birth = (!empty($request->get('emp_date_of_birth')) ? date('Y-m-d',
                strtotime($request->get('emp_date_of_birth'))) : null);
            $appData->emp_place_of_birth = $request->get('emp_place_of_birth');
            $appData->pass_issue_date = (!empty($request->get('pass_issue_date')) ? date('Y-m-d',
                strtotime($request->get('pass_issue_date'))) : null);
            $appData->pass_expiry_date = (!empty($request->get('pass_expiry_date')) ? date('Y-m-d',
                strtotime($request->get('pass_expiry_date'))) : null);


            $appData->arrival_date = (!empty($request->get('arrival_date')) ? date('Y-m-d',
                strtotime($request->get('arrival_date'))) : null);
            $appData->arrival_time = (!empty($request->get('arrival_time')) ? date('H:i:s',
                strtotime($request->get('arrival_time'))) : '');
            $appData->arrival_flight_no = $request->get('arrival_flight_no');
            $appData->departure_date = (!empty($request->get('departure_date')) ? date('Y-m-d',
                strtotime($request->get('departure_date'))) : null);
            $appData->departure_time = (!empty($request->get('departure_time')) ? date('H:i:s',
                strtotime($request->get('departure_time'))) : '');
            $appData->departure_flight_no = $request->get('departure_flight_no');

            //Authorized Person Information
            $appData->auth_full_name = $request->get('auth_full_name');
            $appData->auth_designation = $request->get('auth_designation');
            $appData->auth_mobile_no = $request->get('auth_mobile_no');
            $appData->auth_email = $request->get('auth_email');
            $appData->auth_image = $request->get('auth_image');

            //set process list table data for application status and desk with condition basis
            if (in_array($request->get('actionBtn'), ['draft', 'submit'])) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } elseif ($request->get('actionBtn') == 'resubmit' && $processData->status_id == 5) { // For shortfall application re-submission
                $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);
                $processData->status_id = $resubmission_data['process_starting_status'];
                $processData->desk_id = $resubmission_data['process_starting_desk'];
                $processData->process_desc = 'Re-submitted from applicant'; 
            }

            $appData->save();

            // Spouse/child Information
            if (!empty($appData->id) && !empty($request->get('spouse_child_type')[0])) {
                $spouse_child_ids  = [];

                foreach ($request->get('spouse_child_type') as $key => $value) {
                    if(empty($request->get('spouse_child_type')[$key]) && empty($request->get('spouse_child_passport_per_no')[$key]) && empty($request->get('spouse_child_remarks')[$key]) && empty($request->get('spouse_child_name')[$key])) {
                        continue;
                    }
                    $spouse_child_id = $request->get('spouse_child_id')[$key];
                    $spouse_child_info = ViplSpouseChildInfo::findOrNew($spouse_child_id);
                    $spouse_child_info->app_id = $appData->id;
                    $spouse_child_info->process_type_id = $this->process_type_id;
                    $spouse_child_info->spouse_child_type = $request->get('spouse_child_type')[$key];
                    $spouse_child_info->spouse_child_name = $request->get('spouse_child_name')[$key];
                    $spouse_child_info->spouse_child_passport_per_no = $request->get('spouse_child_passport_per_no')[$key];
                    $spouse_child_info->spouse_child_remarks = $request->get('spouse_child_remarks')[$key];
                    $spouse_child_info->save();
                    $spouse_child_ids[] = $spouse_child_info->id;
                }

                if (count($spouse_child_ids) > 0) {
                    ViplSpouseChildInfo::where('app_id', $appData->id)->whereNotIn('id', $spouse_child_ids)->delete();
                }
            }

            // To whom, the p- pass will be issued
            if (!empty($appData->id) && !empty($request->get('passport_holder_name')[0])) {
                $passport_holder_ids = [];
                foreach ($request->passport_holder_name as $key => $value) {
                    $passport_holder_id = $request->get('passport_holder_id')[$key];
                    $passport_holder = ViplPassportHolderInfo::findOrNew($passport_holder_id);

                    $passport_holder->app_id = $appData->id;
                    $passport_holder->process_type_id = $this->process_type_id;
                    $passport_holder->passport_holder_name = $request->passport_holder_name[$key];
                    $passport_holder->passport_holder_designation = $request->passport_holder_designation[$key];
                    $passport_holder->passport_holder_mobile = $request->passport_holder_mobile[$key];
                    $passport_holder->passport_holder_passport_no = $request->passport_holder_passport_no[$key];

                    if (isset($request->file('passport_holder_attachment')[$key])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_passport_file_path = $request->file('passport_holder_attachment')[$key];
                        $passport_file_path = ('VIPL_' . rand(0, 9999999) . '_' . date('Ymd') . '.' . $_passport_file_path->getClientOriginalExtension());
                        $_passport_file_path->move($path, $passport_file_path);
                        $passport_holder->passport_holder_attachment = $yearMonth . $passport_file_path;
                    }else if (isset($request->file('passport_holder_attachment_path')[$key])) {
                        $passport_holder->passport_holder_attachment = $request->file('passport_holder_attachment_path')[$key];
                    }

                    $passport_holder->save();
                    $passport_holder_ids[] = $passport_holder->id;
                }

                if (count($passport_holder_ids) > 0) {
                    ViplPassportHolderInfo::where('app_id', $appData->id)
                        ->whereNotIn('id', $passport_holder_ids)
                        ->delete();
                }
            }

            // Department and Sub-department specification for application processing
            $processData->department_id = 1; // Registration & Incentives-I (Commercial)
            $processData->sub_department_id = 6; // Fascilation
            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile No.'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            $doc_row = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', 'vip_lounge')
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.id', 'attachment_list.doc_name']);

            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
                    $app_doc = AppDocuments::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_info_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_file_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->save();
                }
            }
            /* End file uploading */

            // Payment info will not be updated for resubmit
            if ($processData->status_id != 2) {

                // Store payment info
                $paymentInfo = SonaliPayment::firstOrNew([
                    'app_id' => $appData->id, 'process_type_id' => $this->process_type_id,
                    'payment_config_id' => $payment_config->id
                ]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;

                // Concat Account no of stakeholder
                $account_no = "";
                foreach ($stakeDistribution as $distribution) {
                    $account_no .= $distribution->stakeholder_ac_no . "-";
                }
                $account_numbers = rtrim($account_no, '-');
                // Concat Account no of stakeholder End

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


            /*
             * if action is submitted and application status is equal to draft
             * and have payment configuration then, generate a tracking number
             * and go to payment initiator function.
             */
            if ($request->get('actionBtn') == 'submit' && $processData->status_id == -1) {
                if(empty($processData->tracking_no)) {
                    $prefix = 'VIPL-' . date("dMY") . '-';
                    UtilFunction::generateTrackingNumber($this->process_type_id, $processData->id, $prefix);
                }
            
                DB::commit();
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }

            // Send Email notification to user on application re-submit
            if ($request->get('actionBtn') == "resubmit" && $processData->status_id == 2) {
                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=',
                    'process_list.process_type_id')
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
                    'process_type_name' => 'Visa Recommendation New',
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
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [VIPLC-1023]');
            }
            DB::commit();
            return redirect('vip-lounge/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VIPLAppStore: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[VIPLC-1011]");
            return redirect()->back()->withInput();
        }
    }

    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VIPLC-1002]';
        }

        $mode = '-E-';
        $viewMode = 'off';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VIPLC-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('vipl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('airports', 'airports.id', '=', 'apps.airport_id')
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
                    'process_type.max_processing_day',
                    'company_info.business_category',
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
                    'airports.name as air_name',
                ]);

            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $airports = Airports::orderby('name')->lists('name', 'id');
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');


            $vip_longue_purpose = VipLonguePurpose::where('status', 1)->where('is_archive', 0)->lists('purpose', 'id')->all();
            $spouse_child_type = [
                '' => 'Select One',
                'Spouse' => 'Spouse',
                'Child' => 'Child'
            ];
            $ref_no_types = [
                'BIDA Registration' => 'BIDA Registration',
                'Office Permission' => 'Office Permission',
                'Incorporation' => 'Incorporation',
                'Others' => 'Others',
            ];

            $spouse_child_info = ViplSpouseChildInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'spouse_child_type',
                    'spouse_child_name',
                    'spouse_child_passport_per_no',
                    'spouse_child_remarks'
                ]);

            $passport_holder_info = ViplPassportHolderInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'passport_holder_name',
                    'passport_holder_designation',
                    'passport_holder_mobile',
                    'passport_holder_passport_no',
                    'passport_holder_attachment'
                ]);

            $public_html = strval(view("VipLounge::application-form-edit",
                compact('process_type_id', 'appInfo', 'airports', 'countries', 'department_id', 'company_id',
                    'nationality', 'viewMode', 'mode', 'thana', 'vip_longue_purpose', 'spouse_child_type', 'ref_no_types',
                    'spouse_child_info', 'passport_holder_info')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VIPLC-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VIPLC-1003]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VIPLC-974]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('vipl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('airports', 'airports.id', '=', 'apps.airport_id')
                ->leftJoin('vip_longue_purpose', 'vip_longue_purpose.id', '=', 'apps.vip_longue_purpose_id')
                ->leftJoin('country_info as emp_nationality', 'emp_nationality.id', '=', 'apps.emp_nationality_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'apps.*',
                    'company_info.business_category',
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'airports.name as airport_name',
                    'emp_nationality.nationality as emp_nationality_name',
                    'vip_longue_purpose.purpose as vip_longue_purpose_name',
                ]);

            // Required Documents for attachment
            $document = $this->getDocument($decodedAppId, 'master');

            $spouse_child_info = ViplSpouseChildInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'spouse_child_type',
                    'spouse_child_name',
                    'spouse_child_passport_per_no',
                    'spouse_child_remarks'
                ]);

            $passport_holder_info = ViplPassportHolderInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'passport_holder_name',
                    'passport_holder_designation',
                    'passport_holder_mobile',
                    'passport_holder_passport_no',
                    'passport_holder_attachment'
                ]);

            $public_html = strval(view("VipLounge::application-form-view",
                compact('appInfo', 'document', 'mode', 'viewMode', 'spouse_child_info', 'passport_holder_info' )));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VIPLViewApp: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-1016]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VIPLC-1016]" . "</h4>"
            ]);
        }
    }

    public function preview()
    {
        return view("VipLounge::preview");
    }

    public function uploadDocument()
    {
        return View::make('VipLounge::ajaxUploadFile');
    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information. [VIPLC-975]');
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('vipl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('airports', 'airports.id', '=', 'apps.airport_id')
                ->leftJoin('vip_longue_purpose', 'vip_longue_purpose.id', '=', 'apps.vip_longue_purpose_id')
                ->leftJoin('country_info as emp_nationality', 'emp_nationality.id', '=', 'apps.emp_nationality_id')
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
                    'apps.*',
                    'company_info.business_category',
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'airports.name as airport_name',
                    'emp_nationality.nationality as emp_nationality_name',
                    'vip_longue_purpose.purpose as vip_longue_purpose_name',
                ]);


            // Required Documents for attachment
            $document = $this->getDocument($decodedAppId, 'master');

            $spouse_child_info = ViplSpouseChildInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'spouse_child_type',
                    'spouse_child_name',
                    'spouse_child_passport_per_no',
                    'spouse_child_remarks'
                ]);

            $passport_holder_info = ViplPassportHolderInfo::where('app_id', $decodedAppId)
                ->get([
                    'id',
                    'passport_holder_name',
                    'passport_holder_designation',
                    'passport_holder_mobile',
                    'passport_holder_passport_no',
                    'passport_holder_attachment'
                ]);

            $contents = view("VipLounge::application-form-pdf", compact('appInfo', 'document', 'spouse_child_info', 'passport_holder_info'))->render();

            $mpdf = new mPDF([
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
            ]);

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
            Log::error('VIPLPdfView ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VIPLC-1115]');
            return Redirect::back()->withInput();
        }
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
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [VIPLC-911]');
                    return redirect('process/vip-lounge/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application submit status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
                if (!in_array($processData->status_id, [15,32])) {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status.');
                    return redirect('process/vip-lounge/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();

            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');

            return redirect('process/vip-lounge/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VRNAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-102]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VIPLC-102]');
            return redirect('process/vip-lounge/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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

        // get users email and phone no according to working company id
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

        DB::beginTransaction();

        try {

            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                //    $processData->status_id = 1; // Submitted
                //    $processData->desk_id = 1;

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                Session::flash('success', 'Payment Confirm successfully');
            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();

            return redirect('process/vip-lounge/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VIPLAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VIPLC-103]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VIPLC-103]');
            return redirect('process/vip-lounge/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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

//        if ($payment_config->payment_category_id === 1) {
//            // For service fee payment there have no unfixed distribution.
//
//        } elseif ($payment_config->payment_category_id === 2) {
//            // Govt-Vendor-Vat-Fee
//
//        } elseif ($payment_config->payment_category_id === 3) {
//
//        }

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

    public function loadDocList(Request $request)
    {
        $app_id = !empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : "";
        $attachment_key = 'vip_lounge';
        $doc_section = $request->get('doc_section');
        $viewMode = $request->get('viewMode');

        if ($app_id != "") {
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $app_id)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_section', $doc_section)
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            if (count($document) < 1) {
                $query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.process_type_id', $this->process_type_id);
                if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
                    $query->whereIn('attachment_list.business_category', [2, 3]);
                } else {
                    $query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
                }
                $document = $query->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.*']);
            }

        } else {
            $query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.process_type_id', $this->process_type_id);
            if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
                $query->whereIn('attachment_list.business_category', [2, 3]);
            } else {
                $query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
            }
            $document = $query->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
        }

        $html = strval(view("VipLounge::documents", compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    private function getDocument($app_id, $doc_section)
    {
        return AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
            ->where('app_documents.ref_id', $app_id)
            ->where('app_documents.process_type_id', $this->process_type_id)
            ->where('app_documents.doc_section', $doc_section)
            ->get([
                'attachment_list.id',
                'attachment_list.doc_priority',
                'attachment_list.additional_field',
                'app_documents.id as document_id',
                'app_documents.doc_file_path as doc_file_path',
                'app_documents.doc_name'
            ]);
    }
}