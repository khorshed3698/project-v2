<?php

namespace App\Modules\OfficePermissionCancellation\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\OfficePermissionCancellation\Models\OfficePermissionCancellation;
use App\Modules\OfficePermissionNew\Models\OPOfficeType;
use App\Modules\OfficePermissionNew\Models\OPOrganizationType;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
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
use Mpdf\Mpdf;

class OfficePermissionCancellationController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 9;
        $this->aclName = 'OfficePermissionCancellation';
    }

    public function applicationForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [OPCC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [OPCC-971]</h4>"
            ]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [OPCC-9991]]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application. [OPCC-1041]</h4>"
            ]);
        }

        try {

            // Checking the Government Fee Payment(GFP) configuration for this service
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1, // Submission fee payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (empty($payment_config)) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![OPC-10100]</h4>"
                ]);
            }

            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            $process_type_id = $this->process_type_id;
            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = OPOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            $viewMode = 'off';
            $mode = '-A-';

            $public_html = strval(view("OfficePermissionCancellation::application-form",
                compact('process_type_id', 'viewMode', 'mode', 'company_id', 'officeType', 'countries', 'district_eng', 'thana_eng',
                    'organizationTypes', 'divisions', 'payment_config')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('OPCAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1005]');
            return response()->json([
                'responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()).' [OPCC-1005]'
            ]);
        }
    }

    public function getDocList(Request $request)
    {
        $attachment_key = $request->get('attachment_key');
        $viewMode = $request->get('viewMode');
        $app_id = ($request->has('app_id') ? Encryption::decodeId($request->get('app_id')) : 0);

        if (!empty($app_id)) {
            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $app_id)
                ->where('app_documents.process_type_id', $this->process_type_id);
//            if ($viewMode == 'on') {
//                $document_query->where('app_documents.doc_file_path', '!=', '');
//            } else {
//                $document_query->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
//                    ->where('attachment_type.key', $attachment_key);
//            }

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

            if(count($document) < 1) {
                $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.*']);
            }
        } else {
            $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
        }

        $html = strval(view("OfficePermissionCancellation::documents",
            compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }


    public function preview()
    {
        return view("OfficePermissionCancellation::preview");
    }

    public function uploadDocument()
    {
        return View::make('OfficePermissionCancellation::ajaxUploadFile');
    }


    public function appStore(Request $request)
    {
        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error',"Sorry! You have no approved Basic Information application for BIDA services. [OPCC-9992]");
            return redirect()->back();
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            Session::flash('error', "Sorry! The department is not allowed to apply to this application. [OPCC-1042]");
            return redirect()->back();
        }

        // get op new info & set session
        if ($request->get('searchOPinfo') == 'searchOPinfo') {

            if ($request->get('is_approval_online') == 'yes' && $request->has('ref_app_tracking_no')) {
                $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));

                $getOPNEApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
                    ->where('status_id', 25)
                    ->where('company_id', $company_id)
                    ->whereIn('process_type_id', [6, 7])
                    ->first(['ref_id','tracking_no']);

                if (empty($getOPNEApprovedRefId)) {
                    Session::flash('error', 'Sorry! approved office permission reference no. is not found or not allowed! [OPCC-1081]');
                    return redirect()->back();
                }

                //Get data from WPCommonPool
                $getOPNEinfo = UtilFunction::checkOpCommonPoolData($getOPNEApprovedRefId->tracking_no, $getOPNEApprovedRefId->ref_id);

                if (empty($getOPNEinfo)) {
                    Session::flash('error', 'Sorry! office permission reference number not found by tracking no!  [OPCC-1081].'.'<br/>'.Session::get('error'));
                    return redirect()->back();
                }

                Session::put('opcInfo', $getOPNEinfo->toArray());
                Session::put('opcInfo.is_approval_online', $request->get('is_approval_online'));
                Session::put('opcInfo.ref_app_tracking_no', $request->get('ref_app_tracking_no'));

                Session::flash('success', 'Successfully loaded office permission data. Please proceed to next step');
                return redirect()->back();
            }
        }

        // Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("opcInfo");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query. [OPCC-974]");
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
        if (!$payment_config) {
            DB::rollback();
            Session::flash('error', "Payment configuration not found [OPCC-2050]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            DB::rollback();
            Session::flash('error', "Payment Stakeholder not found [OPCC-2051]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [OPCC-105]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "opc_";
        if ($request->get('office_type') == 1) {
            $attachment_key .= "branch";
        } else if ($request->get('office_type') == 2) {
            $attachment_key .= "liaison";
        } else {
            $attachment_key .= "representative";
        }
        $doc_row = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->where('attachment_type.key', $attachment_key)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.id', 'attachment_list.doc_name', 'attachment_list.doc_priority']);

        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['date_of_office_permission'] ='date|date_format:d-M-Y';
            $rules['office_type'] = 'required';
            $rules['effect_date'] = 'required|date|date_format:d-M-Y';
            $rules['c_company_name'] = 'required';
            $rules['c_origin_country_id'] = 'required';
//          $rules['c_country_id'] = 'required';
            $rules['c_org_type'] = 'required';
            $rules['authorized_capital'] = 'required';
            $rules['paid_up_capital'] = 'required';
            $rules['local_company_name'] = 'required';

            //BD address start
            $rules['ex_office_division_id'] = 'required|numeric';
            $rules['ex_office_district_id'] = 'required|numeric';
            $rules['ex_office_thana_id'] = 'required|numeric';
            $rules['ex_office_post_code'] = 'required|digits:4';
            $rules['ex_office_address'] = 'required';
            $rules['ex_office_mobile_no'] = 'required|phone_or_mobile';
            $rules['ex_office_email'] = 'required|email';
            // BD address end
            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }

            $rules['first_commencement_date'] = 'required';
            $rules['operation_target_date'] = 'required';
            $rules['period_start_date'] = 'required';
            $rules['period_end_date'] = 'required';
            $rules['period_validity'] = 'required';

            $rules['auth_full_name'] = 'required';
            $rules['auth_designation'] = 'required';
            $rules['auth_email'] = 'required|email';
            $rules['auth_mobile_no'] = 'required';
            $rules['auth_image'] = 'required';
            $rules['accept_terms'] = 'required';

            $messages['c_company_name.required'] = 'Name of the principal company required.';

            $messages['ex_office_division_id.required'] = 'Local address of the principal company Division field is required';
            $messages['ex_office_district_id.required'] = 'Local address of the principal company District field is required';
            $messages['ex_office_thana_id.required'] = 'Local address of the principal company Police Station field is required';
            $messages['ex_office_post_code.required'] = 'Local address of the principal company Post Code field is required';
            $messages['ex_office_address.required'] = 'Local address of the principal company House, Flat/ Apartment, Road field is required';
            $messages['ex_office_mobile_no.required'] = 'Local address of the principal company Mobile No. field is required';
            $messages['ex_office_email.required'] = 'Local address of the principal company Email field is required';
        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = OfficePermissionCancellation::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new OfficePermissionCancellation();
                $processData = new ProcessList();
            }
            $processData->company_id = $company_id;
            $appData->is_approval_online = $request->get('is_approval_online');

            if ($request->get('is_approval_online') == 'yes') {
                $appData->ref_app_tracking_no = trim($request->get('ref_app_tracking_no'));
                $appData->ref_app_approve_date = (!empty($request->get('ref_app_approve_date')) ? date('Y-m-d', strtotime($request->get('ref_app_approve_date'))) : null);
            } else {
                $appData->manually_approved_op_no = $request->get('manually_approved_op_no');
            }

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

            $appData->date_of_office_permission = (!empty($request->get('date_of_office_permission')) ? date('Y-m-d',
                strtotime($request->get('date_of_office_permission'))) : null);
            $appData->office_type = $request->get('office_type');
            $appData->applicant_remarks = $request->get('applicant_remarks');
            $appData->c_company_name = $request->get('c_company_name');
            $appData->c_origin_country_id = $request->get('c_origin_country_id');
//            $appData->c_country_id = $request->get('c_country_id');
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

            $appData->authorized_capital = $request->get('authorized_capital');
            $appData->paid_up_capital = $request->get('paid_up_capital');
            $appData->local_company_name = $request->get('local_company_name');
//          $appData->local_company_name_bn = $request->get('local_company_name_bn');

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
            $appData->first_commencement_date = (!empty($request->get('first_commencement_date')) ? date('Y-m-d',
                strtotime($request->get('first_commencement_date'))) : null);
            $appData->operation_target_date = (!empty($request->get('operation_target_date')) ? date('Y-m-d',
                strtotime($request->get('operation_target_date'))) : null);
            $appData->period_start_date = (!empty($request->get('period_start_date')) ? date('Y-m-d',
                strtotime($request->get('period_start_date'))) : null);
            $appData->period_end_date = (!empty($request->get('period_end_date')) ? date('Y-m-d',
                strtotime($request->get('period_end_date'))) : null);

            $appData->period_validity = $request->get('period_validity');
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

            $appData->effect_date = (!empty($request->get('effect_date')) ? date('Y-m-d',strtotime($request->get('effect_date'))) : null);
            $appData->approved_effect_date = (!empty($request->get('effect_date')) ? date('Y-m-d',strtotime($request->get('effect_date'))) : null);

            if ($request->has('accept_terms')) {
                $appData->accept_terms = 1;
            }

            //set process list table data for application status and desk with condition basis
            if (in_array($request->get('actionBtn'), ['draft', 'submit'])) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } elseif ($request->get('actionBtn') == 'resubmit' && in_array($processData->status_id, [5, 22])) {
                $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);
                $processData->status_id = $resubmission_data['process_starting_status'];
                $processData->desk_id = $resubmission_data['process_starting_desk'];
                // For shortfall application re-submission
                if ($processData->status_id == 5) {
                    $processData->process_desc = 'Re-submitted form applicant';
                }
            }

            $appData->save();

            /*
             * Department and Sub-department specification for application processing
             */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => $department_id,
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->read_status = 0;
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $jsonData['Type'] = CommonFunction::getOfficeTypeById($request->get('office_type'));
//            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
//            $jsonData['Department'] = CommonFunction::getDepartmentNameById($processData->department_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();
            //Store attachment
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

                //Concat account no of all stakeholder
                $account_no = "";
                foreach ($stakeDistribution as $distribution) {
                    $account_no .= $distribution->stakeholder_ac_no."-";
                }
                $account_numbers = rtrim($account_no, '-');
                //Concat account no of all stakeholder End

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
            Session::forget("opcInfo");


            /*
             * if action is submitted and application status is equal to draft
             * and have payment configuration then, generate a tracking number
             * and go to payment initiator function.
             */
            if ($request->get('actionBtn') == 'submit' && $processData->status_id == -1) {
                if (empty($processData->tracking_no)) {
                    // Tracking id update
                    $prefix = 'OPC-'.date("dMY").'-';
                    UtilFunction::generateTrackingNumber($this->process_type_id, $processData->id, $prefix);
                }

                DB::commit();
                return redirect('spg/initiate-multiple/'.Encryption::encodeId($paymentInfo->id));
            }


            // Send Email notification to user on application submit & re-submit
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
                    'process_type_name' => 'Office Permission Cancellation',
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
                    'Failed due to Application Status Conflict. Please try again later! [OPCC-1023]');
            }
            DB::commit();
            return redirect('office-permission-cancellation/list/'.Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPCAppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[OPCC-1011]");
            return redirect()->back()->withInput();
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [OPCC-1002]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [OPCC-972]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('opc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })

                // Reference application
                ->leftJoin('process_list as ref_process', 'ref_process.tracking_no', '=', 'apps.ref_app_tracking_no')
                ->leftJoin('process_type as ref_process_type', 'ref_process_type.id', '=', 'ref_process.process_type_id')

                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('op_office_type', 'op_office_type.id', '=', 'apps.office_type')
                ->leftJoin('country_info as origin_country', 'origin_country.id', '=', 'apps.c_origin_country_id')
                ->leftJoin('op_organization_type', 'op_organization_type.id', '=', 'apps.c_org_type')
                ->leftJoin('area_info as ex_divisions', 'ex_divisions.area_id', '=', 'apps.ex_office_division_id')
                ->leftJoin('area_info as ex_districts', 'ex_districts.area_id', '=', 'apps.ex_office_district_id')
                ->leftJoin('area_info as ex_thana', 'ex_thana.area_id', '=', 'apps.ex_office_thana_id')

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
                    'apps.*',

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
                    // 'sfp.pay_mode_code as pay_mode_code',

                    'op_office_type.name as office_type_name',
                    'origin_country.nicename as origin_country_name',
                    'op_organization_type.name as organization_type_name',

                    'ex_divisions.area_nm as ex_division_name',
                    'ex_districts.area_nm as ex_district_name',
                    'ex_thana.area_nm as ex_thana_name',

                    // Reference application
                    'ref_process.ref_id as ref_application_ref_id',
                    'ref_process.process_type_id as ref_application_process_type_id',
                    'ref_process_type.type_key as ref_process_type_key',
                ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            //Attachment
            $attachment_key = "opc_";
            if ($appInfo->office_type == 1) {
                $attachment_key .= "branch";
            } else if ($appInfo->office_type == 2) {
                $attachment_key .= "liaison";
            } else {
                $attachment_key .= "representative";
            }

            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            $data['ref_app_url'] = '#';
            if (!empty($appInfo->ref_app_tracking_no)) {
                $data['ref_app_url'] = url('process/'.$appInfo->ref_process_type_key.'/view-app/'.Encryption::encodeId($appInfo->ref_application_ref_id) . '/' . Encryption::encodeId($appInfo->ref_application_process_type_id));
            }

            $public_html = strval(view("OfficePermissionCancellation::application-form-view",
                compact('process_type_id', 'appInfo', 'document', 'viewMode', 'mode', 'metingInformation', 'data')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('OPCViewApp : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [OPCC-1115]');
            return Redirect::back()->withInput();
        }

    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [OPCC-1003]';
        }

        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else {
            if ($openMode == 'edit') {
                $viewMode = 'off';
                $mode = '-E-';
            }
        }

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [OPCC-973]</h4>"
            ]);
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('opc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                ]);

            // Last remarks attachment
            $remarks_attachment = DB::select(DB::raw("select * from
                                                `process_documents`
                                                where `process_type_id` = $this->process_type_id and `ref_id` = $appInfo->process_list_id and `status_id` = $appInfo->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$appInfo->process_list_id AND process_type_id=$this->process_type_id AND status_id=$appInfo->status_id)
                                                ORDER BY id ASC"
            ));

            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = OPOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            // Document need for viewmode doc-tab
            if ($viewMode == 'on') {
                $attachment_key = "opc_";
                if ($appInfo->office_type == 1) {
                    $attachment_key .= "branch";
                } else if ($appInfo->office_type == 2) {
                    $attachment_key .= "liaison";
                } else {
                    $attachment_key .= "representative";
                }

                $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                    ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('app_documents.ref_id', $decodedAppId)
                    ->where('app_documents.process_type_id', $this->process_type_id)
                    ->where('app_documents.doc_file_path', '!=', '')
                    ->get([
                        'attachment_list.*', 'app_documents.id as document_id', 'app_documents.doc_file_path as doc_file_path'
                    ]);
            }

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            // Get application basic company information
            $company_id = $appInfo->company_id;
            $basic_company_info = CommonFunction::getBasicCompanyInfo($company_id);


            /*
             * This function used for section wise shortfall.
             * When we need to use section wise shortfall then uncomment this function.
             */
            //$shortfall_readonly_sections = json_encode($this->getShortfallReviewSections($appInfo));

            $public_html = strval(view("OfficePermissionCancellation::application-form-edit",
                compact('process_type_id', 'appInfo', 'remarks_attachment', 'document', 'officeType', 'company_id',
                    'countries', 'organizationTypes', 'district_eng', 'divisions', 'thana_eng', 'viewMode', 'mode', 'metingInformation', 'basic_company_info', 'shortfall_readonly_sections')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('OPCViewEditForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>".CommonFunction::showErrorPublic($e->getMessage())."[OPCC-1015]"."</h4>"
            ]);
        }
    }

    /*
     * This function used for "section wise shortfall".
     * When we need to use "section wise shortfall" then uncomment this function.
     */

//    private function getShortfallReviewSections($appInfo) {
//        $data = [];
//
//        if ($appInfo->status_id == 5) {
//            if ($appInfo->basic_instruction_review == 0) {
//                $data[] = 'basic_instruction_review';
//            }
//            if ($appInfo->office_type_review == 0) {
//                $data[] = 'office_type_review';
//            }
//            if ($appInfo->company_info_review == 0) {
//                $data[] = 'company_info_review';
//            }
//            if ($appInfo->capital_of_company_review == 0) {
//                $data[] = 'capital_of_company_review';
//            }
//            if ($appInfo->local_address_review == 0) {
//                $data[] = 'local_address_review';
//            }
//            if ($appInfo->activities_in_bd_review == 0) {
//                $data[] = 'activities_in_bd_review';
//            }
//            if ($appInfo->period_of_permission_review == 0) {
//                $data[] = 'period_of_permission_review';
//            }
//            if ($appInfo->organizational_set_up_review == 0) {
//                $data[] = 'organizational_set_up_review';
//            }
//            if ($appInfo->expenses_review == 0) {
//                $data[] = 'expenses_review';
//            }
//            if ($appInfo->attachment_review == 0) {
//                $data[] = 'attachment_review';
//            }
//            if ($appInfo->declaration_review == 0) {
//                $data[] = 'declaration_review';
//            }
//        }
//        return $data;
//    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information. [OPCC-975]');
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('opc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                ]);
            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = OPOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();


            //document view for pdf
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            $contents = view("OfficePermissionCancellation::application-form-pdf",
                compact('process_type_id', 'appInfo', 'officeType', 'district_eng', 'countries', 'organizationTypes', 'metingInformation',
                    'thana_eng',
                    'document', 'viewMode', 'mode', 'divisions'))->render();

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
                'P']);
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
            $mpdf->Output($appInfo->tracking_no.'.pdf', 'I');

        } catch (\Exception $e) {
            Log::error('OPCPdfView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1125]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [OPCC-1125]');
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

            // 1 = Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [OPCC-912]');
                    return redirect('process/office-permission-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // App Tracking ID store in Payment table
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
                if ($processData->status_id != '15') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [OPCC-913]');
                    return redirect('process/office-permission-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
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
            return redirect('process/office-permission-cancellation/view-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPCAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1091]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : '.$e->getMessage().' [OPCC-1091]');
            return redirect('process/office-permission-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
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
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
//                $processData->status_id = 1; // Submitted
//                $processData->desk_id = 1;

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
            return redirect('process/office-permission-cancellation/view-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPCAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [OPCC-1092]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : '.$e->getMessage().' [OPCC-1092]');
            return redirect('process/office-permission-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        }
    }

    public function unfixedAmountsForPayment($payment_config, $relevant_info_array = [])
    {
        /**
         * DB Table Name: sp_payment_category
         * Payment Categories:
         * 1 = Service Fee Payment
         * 2 = Gove rnment Fee Payment
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

        if ($payment_config->payment_category_id === 1) {

            // For service fee payment there have no unfixed distribution.

        } elseif ($payment_config->payment_category_id === 2) {
            // Govt-Vendor-Vat-Fee
            $vat_percentage = SonaliPaymentController::getGovtVendorVatPercentage();
            if (empty($vat_percentage)) {
                abort('Please, configure the value for VAT.');
            }

            // Approve Duration calculation
            $applicationInfo['approved_duration_start_date'] = $relevant_info_array['approved_duration_start_date'];
            $applicationInfo['approved_duration_end_date'] = $relevant_info_array['approved_duration_end_date'];
            $applicationInfo['process_type_id'] = $relevant_info_array['process_type_id'];

            $govt_application_fee = (int)commonFunction::getGovtFeesAmount($applicationInfo);

            $unfixed_amount_array[3] = $govt_application_fee;
            $unfixed_amount_array[5] = ($govt_application_fee / 100) * $vat_percentage;

        } elseif ($payment_config->payment_category_id === 3) {

        }

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
}
