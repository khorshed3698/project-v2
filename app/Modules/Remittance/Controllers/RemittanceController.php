<?php

namespace App\Modules\Remittance\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\BasicInformation\Controllers\BasicInformationController;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BasicInformation\Models\EA_OrganizationType;
use App\Modules\BasicInformation\Models\EA_OwnershipStatus;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Remittance\Models\BriefDescription;
use App\Modules\Remittance\Models\BriefStatement;
use App\Modules\Remittance\Models\RemittanceProjectStatus;
use App\Modules\Remittance\Models\ImportedMachinery;
use App\Modules\Remittance\Models\OtherInfo;
use App\Modules\Remittance\Models\Remittance;
use App\Modules\Remittance\Models\BidaRegInfo;
use App\Modules\Remittance\Models\PresentStatus;
use App\Modules\Remittance\Models\RemittanceType;
use App\Modules\Remittance\Models\StatementOfActualProduct;
use App\Modules\Remittance\Models\StatementOfExport;
use App\Modules\Remittance\Models\StatementOfRemittance;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SubSector;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
//use mPDF;
use Mpdf\Mpdf;

class RemittanceController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 11;
        $this->aclName = 'Remittance';
    }

    /*
     * Show application form
     */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function applicationForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [REM-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [REM-971]</h4>"
            ]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [REM-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [1, 4])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application. [REM-1041]</h4>"
            ]);
        }

        try {

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
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![VR-10100]</h4>"
                ]);
            }
            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            // Check existing application
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $getCompanyData = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            $project_status = ['' => 'Select one'] + RemittanceProjectStatus::where('status', 1)->where('is_archive',
                    0)->orderby('name')->lists('name', 'id')->all();
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename',
                    'asc')->lists('nicename', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive',
                    0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status',
                    1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status',
                    1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality',
                'iso');
            $banks = ['' => 'Select one'] + Bank::where('is_active', 1)->where('is_archive',
                    0)->orderBy('name')->lists('name', 'id')->all();
            $branch = ['' => 'Select one'] + BankBranch::where('is_active', 1)->where('is_archive',
                    0)->orderBy('branch_name')->lists('branch_name', 'id')->all();
            $bank_country = Countries::where('country_status', 'Yes')->where('id', 18)->orderBy('nicename',
                'asc')->lists('nicename', 'id')->all();
            $remittanceType = RemittanceType::orderby('id', 'desc')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $feesAmountRange = DB::table('pay_order_amount_setup')->where('process_type_id', 11)->get([
                'min_amount_bdt', 'max_amount_bdt', 'p_o_amount_bdt'
            ]);
            $process_type_id = $this->process_type_id;
            $viewMode = 'off';
            $mode = '-A-';

            $public_html = strval(view("Remittance::application-form",
                compact('viewMode', 'mode', 'process_type_id', 'countries', 'eaOrganizationStatus', 'eaOwnershipStatus',
                    'project_status', 'eaOrganizationType', 'sectors', 'sub_sectors', 'divisions', 'document',
                    'districts', 'thana', 'nationality', 'remittanceType', 'remittancePresentStatus', 'getCompanyData',
                    'payment_config', 'banks', 'branch', 'feesAmountRange', 'bank_country')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('REMAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1005]');
            return response()->json([
                'responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [REM-1005]'
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
                'app_documents.doc_name'
            ]);

            if (count($document) < 1) {
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

        $html = strval(view("Remittance::documents",
            compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    public function appStore(Request $request)
    {
        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services. [REM-9992]");
            return redirect()->back();
        }

        if ($request->get('searchRemittanceInfo') == 'searchRemittanceInfo') {
            Session::put('appInfo',
                $request->except([
                    'registration_copy', 'int_property_attachment', 'other_remittance_attachment', 'approval_copy'
                ])
            );

            $period_from = ($request->get('period_from') != '' ? date('Y-m-d',
                strtotime($request->get('period_from'))) : '');
            $period_to = ($request->get('period_to') != '' ? date('Y-m-d', strtotime($request->get('period_to'))) : '');
            if ($period_from != '' && $period_to != '') {
                $getRemittanceInfo = ProcessList::leftjoin('ra_apps', 'ra_apps.id', '=', 'process_list.ref_id')
                    ->where('process_list.status_id', 25)
                    ->where('process_list.company_id', $company_id)
                    ->whereBetween('ra_apps.approved_date', array($period_from, $period_to))
                    ->get([
                        'ra_apps.remittance_type_id',
                        'ra_apps.proposed_amount_bdt',
                        'ra_apps.proposed_amount_usd',
                        'ra_apps.proposed_exp_percentage',
                        'ra_apps.int_property_attachment as other_remittance_attachment',
                    ]);

                if (count($getRemittanceInfo) < 1) {
                    Session::flash('error', 'Sorry! Past remittance data not found! [REM-1081]');
                    return redirect()->back();
                }
                Session::put('remittanceInfo', $getRemittanceInfo);
                Session::flash('success', 'Successfully loaded Remittance data. Please proceed to next step');
                return redirect()->back();
            } else {
                Session::flash('error', 'Sorry! invalid date range! [REM-1039]');
                return redirect()->back();
            }
        }

        //Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("remittanceInfo");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }


        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query.");
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
            Session::flash('error', "Payment configuration not found [REM-100]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            DB::rollback();
            Session::flash('error', "Payment Stakeholder not found [REM-101]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "ram_";
        if ($request->get('remittance_type_id') == 1) {
            $attachment_key .= "others";
        } else if ($request->get('remittance_type_id') == 2) {
            $attachment_key .= "technical_know_how";
        } else if ($request->get('remittance_type_id') == 3) {
            $attachment_key .= "technical_assistance";
        } else if ($request->get('remittance_type_id') == 4) {
            $attachment_key .= "franchise";
        } else {
            $attachment_key .= "royalty";
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

            $rules['remittance_type_id'] = 'required';
            $rules['company_name'] = 'required';

            $rules['ceo_gender'] = 'required';

            // 3. Foreign collaborator's providing service/ intellectual properties Info
            $rules['organization_name'] = 'required';
            $rules['organization_address'] = 'required';
            $rules['property_city'] = 'required';
            $rules['property_post_code'] = 'required';
            $rules['property_country_id'] = 'required';

            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }

            $rules['accept_terms'] = 'required';
            $messages['remittance_type_id.required'] = 'Type of the Remittance is required.';
            $messages['int_property_attachment.required'] = 'Copy of Trade Mark Certificate/ Copy of Application for Trade Mark Certificate is required.';
            $messages['company_name.required'] = 'Name of Organization/ Company/ Industrial Project is required.';
            $messages['ceo_dob.required'] = 'Date of Birth is required.';
            $messages['ceo_dob.date'] = 'Date of Birth must be date format.';
            $messages['accept_terms.required'] = 'Accept Terms is required.';

            // 3. Foreign collaborator's providing service/ intellectual properties Info
            $messages['organization_name.required'] = 'Name of Organization is required.';
            $messages['organization_address.required'] = 'Organization address is required.';
            $messages['property_city.required'] = 'City/ State is required.';
            $messages['property_post_code.required'] = 'Post Code/ Zip code is required.';
            $messages['property_country_id.required'] = 'Country is required.';

        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = Remittance::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new Remittance();
                $processData = new ProcessList();
            }
            $processData->company_id = $company_id;

            $appData->remittance_type_id = $request->get('remittance_type_id');;
            $appData->company_name = $request->get('company_name');
            $appData->company_name_bn = $request->get('company_name_bn');
            $appData->origin_country_id = $request->get('origin_country_id');
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->organization_status_id = $request->get('organization_status_id');
            $appData->ownership_status_id = $request->get('ownership_status_id');
            $appData->business_sector_id = $request->get('business_sector_id');
            $appData->business_sector_others = $request->get('business_sector_others');
            $appData->business_sub_sector_id = $request->get('business_sub_sector_id');
            $appData->business_sub_sector_others = $request->get('business_sub_sector_others');
            $appData->major_activities = $request->get('major_activities');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d',
                strtotime($request->get('ceo_dob'))) : '');
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_father_name = $request->get('ceo_father_name');
            $appData->ceo_mother_name = $request->get('ceo_mother_name');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->ceo_spouse_name = $request->get('ceo_spouse_name');
            $appData->ceo_city = $request->get('ceo_city');
            $appData->ceo_state = $request->get('ceo_state');
            $appData->ceo_gender = $request->get('ceo_gender');
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
            $appData->organization_name = $request->get('organization_name');
            $appData->organization_address = $request->get('organization_address');
            $appData->property_city = $request->get('property_city');
            $appData->property_post_code = $request->get('property_post_code');
            $appData->property_country_id = $request->get('property_country_id');
            $appData->effective_agreement_date = (!empty($request->get('effective_agreement_date')) ? date('Y-m-d',
                strtotime($request->get('effective_agreement_date'))) : '');
            $appData->agreement_duration_from = (!empty($request->get('agreement_duration_from')) ? date('Y-m-d',
                strtotime($request->get('agreement_duration_from'))) : '');
            $appData->agreement_duration_type = $request->get('agreement_duration_type');
            $appData->agreement_duration_to = (!empty($request->get('agreement_duration_to')) ? date('Y-m-d',
                strtotime($request->get('agreement_duration_to'))) : '');
            $appData->agreement_total_duration = $request->get('agreement_total_duration');
            if ($request->hasFile('valid_contact_attachment')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('valid_contact_attachment');
                $file_path = trim(uniqid('BIDA_RA-' . $company_id . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $appData->valid_contact_attachment = $yearMonth . $file_path;
            }

            $appData->schedule_of_payment = $request->get('schedule_of_payment');
            $appData->agreement_amount_type = $request->get('agreement_amount_type');
            $appData->total_agreement_amount_bdt = $request->get('total_agreement_amount_bdt');
            $appData->total_agreement_amount_usd = $request->get('total_agreement_amount_usd');
            $appData->percentage_of_sales = $request->get('percentage_of_sales');
            $appData->period_from = (!empty($request->get('period_from')) ? date('Y-m-d',
                strtotime($request->get('period_from'))) : '');
            $appData->period_to = (!empty($request->get('period_to')) ? date('Y-m-d',
                strtotime($request->get('period_to'))) : '');
            $appData->total_period = $request->get('total_period');
            $appData->product_name_capacity = $request->get('product_name_capacity');
            $appData->marketing_of_products_local = $request->get('marketing_of_products_local');
            $appData->marketing_of_products_foreign = $request->get('marketing_of_products_foreign');
            $appData->present_status_id = $request->get('present_status_id');

            if ($request->hasFile('int_property_attachment')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('int_property_attachment');
                $file_path = trim(uniqid('BIDA_RA-' . $company_id . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $appData->int_property_attachment = $yearMonth . $file_path;
            }

            $appData->project_status_id = $request->get('project_status_id');
            $appData->prev_sales_year_from = (!empty($request->get('prev_sales_year_from')) ? date('Y-m-d',
                strtotime($request->get('prev_sales_year_from'))) : '');
            $appData->prev_sales_year_to = (!empty($request->get('prev_sales_year_to')) ? date('Y-m-d',
                strtotime($request->get('prev_sales_year_to'))) : '');
            $appData->sales_value_bdt = $request->get('sales_value_bdt');
            $appData->sales_value_usd = $request->get('sales_value_usd');
            $appData->usd_conv_rate = $request->get('usd_conv_rate');
            $appData->tax_amount_bdt = $request->get('tax_amount_bdt');
            $appData->total_fee_percentage = $request->get('total_fee_percentage');
            $appData->proposed_remittance_type = $request->get('proposed_remittance_type');
            $appData->proposed_amount_bdt = $request->get('proposed_amount_bdt');
            $appData->proposed_amount_usd = $request->get('proposed_amount_usd');
            $appData->proposed_exp_percentage = $request->get('proposed_exp_percentage');
            $appData->proposed_sub_total_exp_percentage = $request->get('proposed_sub_total_exp_percentage');
            $appData->proposed_sub_total_bdt = $request->get('proposed_sub_total_bdt');
            $appData->proposed_sub_total_usd = $request->get('proposed_sub_total_usd');
            $appData->total_fee = $request->get('total_fee');
            $appData->other_sub_total_bdt = $request->get('other_sub_total_bdt');
            $appData->other_sub_total_usd = $request->get('other_sub_total_usd');
            $appData->other_sub_total_percentage = $request->get('other_sub_total_percentage');
            $appData->total_remittance_percentage = $request->get('total_remittance_percentage');
            $appData->brief_background = $request->get('brief_background');
            $appData->local_bank_id = $request->get('local_bank_id');
            $appData->local_branch = $request->get('local_branch');
            $appData->local_bank_address = $request->get('local_bank_address');
            $appData->local_bank_city = $request->get('local_bank_city');
            $appData->local_bank_post_code = $request->get('local_bank_post_code');
            $appData->local_bank_country_id = $request->get('local_bank_country_id');

            //Authorized Person Information
            $appData->auth_full_name = $request->get('auth_full_name');
            $appData->auth_designation = $request->get('auth_designation');
            $appData->auth_mobile_no = $request->get('auth_mobile_no');
            $appData->auth_email = $request->get('auth_email');
            $appData->auth_image = $request->get('auth_image');

            if ($request->has('accept_terms')) {
                $appData->accept_terms = 1;
            }

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
//                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id',
//                        'desc')->skip(1)->take(1)->first();
//                    $processData->status_id = 2; // re-submit
//                    // if application is in observation status from Meeting Chairperson(desk 6)
//                    // then resubmit to Assistant Director(desk 1) else resubmit to previous desk
//                    $processData->desk_id = $getLastProcessInfo->desk_id;
//                    if ($getLastProcessInfo->desk_id == 6) {
//                        $processData->desk_id = 1;
//                    }

                    $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);

                    $processData->status_id = $resubmission_data['process_starting_status'];
                    $processData->desk_id = $resubmission_data['process_starting_desk'];
                    $processData->process_desc = 'Re-submitted form applicant';

                } elseif (
                    $processData->status_id == 22) {  // For resubmit from mc
//                    $processData->status_id = 2;
//                    $processData->desk_id = 1; // 1 is Assistant Desk

                    $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);

                    $processData->status_id = $resubmission_data['process_starting_status'];
                    $processData->desk_id = $resubmission_data['process_starting_desk'];
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0;
                }
            }

            $appData->save();

            //bida registraton info ........
            if (!empty($appData->id)) {
                $regInfoIds = [];
                foreach ($request->registration_no as $key => $value) {
                    $regId = $request->get('bidaRegId')[$key];
                    $bidaRegInfo = BidaRegInfo::findOrNew($regId);

                    $bidaRegInfo->app_id = $appData->id;
                    $bidaRegInfo->registration_no = $request->registration_no[$key];
                    $bidaRegInfo->registration_date = (!empty($request->registration_date[$key]) ? date('Y-m-d',
                        strtotime($request->registration_date[$key])) : '');
                    $bidaRegInfo->proposed_investment = $request->proposed_investment[$key];
                    $bidaRegInfo->actual_investment = $request->actual_investment[$key];

                    if (isset($request->file('registration_copy')[$key])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_reg_file_path = $request->file('registration_copy')[$key];
                        $reg_file_path = trim(uniqid('BIDA_RA-' . $company_id . '-',
                                true) . $_reg_file_path->getClientOriginalName());
                        $_reg_file_path->move($path, $reg_file_path);
                        $bidaRegInfo->registration_copy = $yearMonth . $reg_file_path;
                    }

                    if (isset($request->file('amendment_copy')[$key])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_reg_file_path = $request->file('amendment_copy')[$key];
                        $reg_file_path = trim(uniqid('BIDA_RA-' . $company_id . '-',
                                true) . $_reg_file_path->getClientOriginalName());
                        $_reg_file_path->move($path, $reg_file_path);
                        $bidaRegInfo->amendment_copy = $yearMonth . $reg_file_path;
                    }

                    $bidaRegInfo->save();
                    $regInfoIds[] = $bidaRegInfo->id;
                }

                if (count($regInfoIds) > 0) {
                    BidaRegInfo::where('app_id', $appData->id)
                        ->whereNotIn('id', $regInfoIds)
                        ->delete();
                }
            }

            //Brief description of technological service received .....
            if (!empty($appData->id)) {
                $briefDescIds = [];
                foreach ($request->brief_description as $key => $value) {
                    $regId = $request->get('briefDescId')[$key];
                    $briefDescription = BriefDescription::findOrNew($regId);

                    $briefDescription->app_id = $appData->id;
                    $briefDescription->brief_description = $request->brief_description[$key];

                    $briefDescription->save();
                    $briefDescIds[] = $briefDescription->id;
                }

                if (count($regInfoIds) > 0) {
                    BriefDescription::where('app_id', $appData->id)
                        ->whereNotIn('id', $briefDescIds)
                        ->delete();
                }
            }

            //remittance imported machinery section 13
            if (!empty($appData->id)) {
                $importedMachineIds = [];
                foreach ($request->cnf_value as $key => $value) {
                    $importedMachineId = $request->get('importedMachineId')[$key];
                    $importedMachinery = ImportedMachinery::findOrNew($importedMachineId);

                    $importedMachinery->app_id = $appData->id;
                    $importedMachinery->import_year_from = (!empty($request->import_year_from[$key]) ? date('Y-m-d',
                        strtotime($request->import_year_from[$key])) : '');
                    $importedMachinery->import_year_to = (!empty($request->import_year_to[$key]) ? date('Y-m-d',
                        strtotime($request->import_year_to[$key])) : '');
                    $importedMachinery->cnf_value = $request->cnf_value[$key];

                    $importedMachinery->save();
                    $importedMachineIds[] = $importedMachinery->id;
                }

                if (count($importedMachineIds) > 0) {
                    ImportedMachinery::where('app_id', $appData->id)
                        ->whereNotIn('id', $importedMachineIds)
                        ->delete();
                }
            }

            //Other remittance info section 17
            if (!empty($appData->id)) {
                $otherInfoIds = [];
                foreach ($request->other_remittance_type_id as $i => $value) {
                    $otherInfoId = $request->get('otherInfoId')[$i];
                    $otherInfo = OtherInfo::findOrNew($otherInfoId);

                    $otherInfo->app_id = $appData->id;
                    $otherInfo->remittance_type_id = $request->other_remittance_type_id[$i];
                    $otherInfo->remittance_bdt = $request->other_remittance_bdt[$i];
                    $otherInfo->remittance_usd = $request->other_remittance_usd[$i];
                    $otherInfo->remittance_percentage = $request->other_remittance_percentage[$i];

                    if (isset($request->file('other_remittance_attachment')[$i])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_reg_file_path = $request->file('other_remittance_attachment')[$i];
                        $reg_file_path = trim(uniqid('BIDA_RA-' . $company_id . '-', true) . rand(5,
                                10) . $_reg_file_path->getClientOriginalName());
                        $_reg_file_path->move($path, $reg_file_path);
                        $otherInfo->attachment = $yearMonth . $reg_file_path;
                    }

                    $otherInfo->save();
                    $otherInfoIds[] = $otherInfo->id;
                }

                if (count($otherInfoIds) > 0) {
                    OtherInfo::where('app_id', $appData->id)
                        ->whereNotIn('id', $otherInfoIds)
                        ->delete();
                }
            }

            //Brief Statement of Benefits received section 19
            if (!empty($appData->id)) {
                $briefStatementIds = [];
                foreach ($request->brief_statement as $key => $value) {
                    $briefStatementId = $request->get('briefStatementId')[$key];
                    $briefStatement = BriefStatement::findOrNew($briefStatementId);

                    $briefStatement->app_id = $appData->id;
                    $briefStatement->brief_statement = $request->brief_statement[$key];

                    $briefStatement->save();
                    $briefStatementIds[] = $briefStatement->id;
                }

                if (count($briefStatementIds) > 0) {
                    BriefStatement::where('app_id', $appData->id)
                        ->whereNotIn('id', $briefStatementIds)
                        ->delete();
                }
            }

            //Statement of Remittances section 21
            if (!empty($appData->id)) {
                $statementOfRemittanceIds = [];
                foreach ($request->statement_remittance_type_id as $sl => $value) {
                    $statementOfRemittanceId = $request->get('statementOfRemittanceId')[$sl];
                    $statementOfRemittance = StatementOfRemittance::findOrNew($statementOfRemittanceId);

                    $statementOfRemittance->app_id = $appData->id;
                    $statementOfRemittance->remittance_type_id = $request->statement_remittance_type_id[$sl];
                    $statementOfRemittance->remittance_year = $request->remittance_year[$sl];
                    $statementOfRemittance->bida_ref_no = $request->bida_ref_no[$sl];
                    $statementOfRemittance->date = (!empty($request->date[$sl]) ? date('Y-m-d',
                        strtotime($request->date[$sl])) : '');

                    if (isset($request->file('approval_copy')[$sl])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_app_file_path = $request->file('approval_copy')[$sl];
                        $app_file_path = trim(uniqid('BIDA_RA-' . $company_id . '-',
                                true) . $_app_file_path->getClientOriginalName());
                        $_app_file_path->move($path, $app_file_path);
                        $statementOfRemittance->approval_copy = $yearMonth . $app_file_path;
                    }

                    $statementOfRemittance->amount = $request->amount[$sl];
                    $statementOfRemittance->percentage = $request->percentage[$sl];

                    $statementOfRemittance->save();
                    $statementOfRemittanceIds[] = $statementOfRemittance->id;
                }

                if (count($otherInfoIds) > 0) {
                    StatementOfRemittance::where('app_id', $appData->id)
                        ->whereNotIn('id', $statementOfRemittanceIds)
                        ->delete();
                }
            }

            //Statement of Actual production section 22
            if (!empty($appData->id)) {
                $statementOfActualProdIds = [];
                foreach ($request->year_of_remittance as $key => $value) {
                    $statementOfActualProdId = $request->get('statementOfActualProdId')[$key];
                    $statementOfActualProduct = StatementOfActualProduct::findOrNew($statementOfActualProdId);

                    $statementOfActualProduct->app_id = $appData->id;
                    $statementOfActualProduct->year_of_remittance = $request->year_of_remittance[$key];
                    $statementOfActualProduct->item_of_production = $request->item_of_production[$key];
                    $statementOfActualProduct->quantity = $request->actual_quantity[$key];
                    $statementOfActualProduct->sales_value = $request->sales_value[$key];

                    $statementOfActualProduct->save();
                    $statementOfActualProdIds[] = $statementOfActualProduct->id;
                }

                if (count($statementOfActualProdIds) > 0) {
                    StatementOfActualProduct::where('app_id', $appData->id)
                        ->whereNotIn('id', $statementOfActualProdIds)
                        ->delete();
                }
            }

            //Statement of Export Earning (If any) 23
            if (!empty($appData->id)) {
                $statementOfExportIds = [];
                foreach ($request->exp_year_of_remittance as $key => $value) {
                    $statementOfExportId = $request->get('statementOfExportId')[$key];
                    $statementOfExport = StatementOfExport::findOrNew($statementOfExportId);

                    $statementOfExport->app_id = $appData->id;
                    $statementOfExport->year_of_remittance = $request->exp_year_of_remittance[$key];
                    $statementOfExport->item_of_export = $request->item_of_export[$key];
                    $statementOfExport->quantity = $request->export_quantity[$key];
                    $statementOfExport->cnf_cif_value = $request->cnf_cif_value[$key];

                    $statementOfExport->save();
                    $statementOfExportIds[] = $statementOfExport->id;
                }

                if (count($statementOfExportIds) > 0) {
                    StatementOfExport::where('app_id', $appData->id)
                        ->whereNotIn('id', $statementOfExportIds)
                        ->delete();
                }
            }

            /*
             * Department and Sub-department specification for application processing
             */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => CommonFunction::getDeptIdByCompanyId($company_id),
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
//            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
//            $jsonData['Department'] = CommonFunction::getDepartmentNameById($processData->department_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            //attachment store
            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
                    //$documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));
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
                    $account_no .= $distribution->stakeholder_ac_no . "-";
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
                    $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                    $paymentDetails->fix_status = $distribution->fix_status;
                    $paymentDetails->purpose = $distribution->purpose;
                    $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                    $paymentDetails->distribution_type = $distribution->distribution_type;
                    if ($distribution->fix_status == 1) {
                        $paymentDetails->pay_amount = $distribution->pay_amount;
                    } else {
                        $paymentDetails->pay_amount = $unfixed_amount_array['amounts'][$distribution->distribution_type];
                    }
                    $paymentDetails->save();
                }
                //Payment Details By Stakeholders End
            }

            Session::forget("remittanceInfo");
            Session::forget("appInfo");

            /*
            * if action is submitted and application status is equal to draft
            * and have payment configuration then, generate a tracking number
            * and go to payment initiator function.
            */
            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1) {

                if (empty($processData->tracking_no)) {
                    $trackingPrefix = 'RAM-' . date("dMY") . '-';
                    DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                }
                DB::commit();

                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }


            // Send Email notification to user on application re-submit
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {

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
                    'process_type_name' => 'Work Permit Amendment',
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
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [REM-1023]');
            }

            DB::commit();
            return redirect('remittance-new/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('REMAppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[REM-1011]");
            return redirect()->back()->withInput();
        }
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [REM-1002]';
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [REM-972]</h4>"
            ]);
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename',
                    'asc')->lists('nicename', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive',
                    0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status',
                    1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status',
                    1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality',
                'iso');

            $banks = ['' => 'Select one'] + Bank::where('is_active', 1)->where('is_archive',
                    0)->orderBy('name')->lists('name', 'id')->all();
            $branch = ['' => 'Select one'] + BankBranch::where('is_active', 1)->where('is_archive',
                    0)->orderBy('branch_name')->lists('branch_name', 'id')->all();
            $bank_country = Countries::where('country_status', 'Yes')->where('id', 18)->orderBy('nicename',
                'asc')->lists('nicename', 'id')->all();
            $remittanceType = RemittanceType::orderby('id', 'desc')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $project_status = ['' => 'Select one'] + RemittanceProjectStatus::where('status', 1)->where('is_archive',
                    0)->orderby('name')->lists('name', 'id')->all();

            // get application,process info
            $appInfo = ProcessList::leftJoin('ra_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
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
                    'process_type.max_processing_day',

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

            $bidaRegInfo = BidaRegInfo::where('app_id', $decodedAppId)
                ->get([
                    'registration_no',
                    'registration_date',
                    'registration_date',
                    'proposed_investment',
                    'actual_investment',
                    'registration_copy',
                    'amendment_copy',
                    'id'
                ]);
            $briefDescription = BriefDescription::where('app_id', $decodedAppId)
                ->get([
                    'brief_description',
                    'id'
                ]);
            $importedMachine = ImportedMachinery::where('app_id', $decodedAppId)
                ->get([
                    'import_year_from',
                    'import_year_to',
                    'cnf_value',
                    'id'
                ]);
            $otherRemittanceInfo = OtherInfo::where('app_id', $decodedAppId)
                ->get([
                    'remittance_type_id',
                    'remittance_bdt',
                    'remittance_usd',
                    'remittance_percentage',
                    'attachment',
                    'id'
                ]);
            $briefStatement = BriefStatement::where('app_id', $decodedAppId)
                ->get([
                    'brief_statement',
                    'id'
                ]);
            $statementOfRemittance = StatementOfRemittance::where('app_id', $decodedAppId)
                ->get([
                    'remittance_type_id',
                    'remittance_year',
                    'bida_ref_no',
                    'date',
                    'approval_copy',
                    'amount',
                    'percentage',
                    'id'
                ]);
            $statementOfActualProd = StatementOfActualProduct::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_production',
                    'quantity',
                    'sales_value',
                    'id'
                ]);
            $statementOfExport = StatementOfExport::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_export',
                    'quantity',
                    'cnf_cif_value',
                    'id'
                ]);

            if ($viewMode == 'on') {
                $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                    ->where('app_documents.ref_id', $decodedAppId)
                    ->where('app_documents.process_type_id', $this->process_type_id)
                    ->where('app_documents.doc_file_path', '!=', '')
                    ->get([
                        'attachment_list.*', 'app_documents.id as document_id', 'app_documents.doc_file_path as doc_file_path'
                    ]);
            }

            // Get Payment Configuration
            $proposed_sub_total_bdt = $appInfo->proposed_sub_total_bdt;
            $feesAmountRange = DB::table('pay_order_amount_setup')->where('process_type_id', 11)->get([
                'min_amount_bdt', 'max_amount_bdt', 'p_o_amount_bdt'
            ]);
            $totalFee = 0;
            foreach ($feesAmountRange as $value) {
                if ($value->min_amount_bdt <= $proposed_sub_total_bdt && $value->max_amount_bdt >= $proposed_sub_total_bdt) {
                    $totalFee = $value->p_o_amount_bdt;
                    break;
                }
            }
            if ($totalFee == 0 && $proposed_sub_total_bdt >= '100000001') {
                $totalFee = '500000';
            }

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);

            $public_html = strval(view("Remittance::application-form-edit",
                compact( 'process_type_id','countries', 'eaOrganizationStatus', 'eaOwnershipStatus',
                    'eaOrganizationType', 'sectors', 'sub_sectors', 'divisions', 'districts', 'thana', 'nationality',
                    'banks', 'branch',
                    'remittanceType', 'remittancePresentStatus', 'project_status', 'appInfo', 'bidaRegInfo',
                    'briefDescription', 'importedMachine',
                    'otherRemittanceInfo', 'briefStatement', 'statementOfActualProd', 'statementOfExport',
                    'statementOfRemittance',
                    'payment_config', 'document', 'viewMode', 'mode', 'feesAmountRange', 'totalFee', 'bank_country', 'metingInformation')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('REMViewEditApp : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1020]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . " [REM-1020]" . "</h4>"
            ]);
        }
    }


    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [REM-1003]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [REM-973]</h4>"
            ]);
        }


        try{
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('ra_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')

                ->leftJoin('country_info as origin_country', 'origin_country.id', '=', 'apps.origin_country_id')
                ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'apps.organization_type_id')
                ->leftJoin('ea_organization_status', 'ea_organization_status.id', '=', 'apps.organization_status_id')
                ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'apps.ownership_status_id')
                ->leftJoin('sector_info', 'sector_info.id', '=', 'apps.business_sector_id')
                ->leftJoin('sec_sub_sector_list', 'sec_sub_sector_list.id', '=', 'apps.business_sub_sector_id')

                ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'apps.ceo_country_id')
                ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'apps.ceo_district_id')
                ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'apps.ceo_thana_id')

                ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'apps.office_division_id')
                ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'apps.office_thana_id')

                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'apps.factory_thana_id')

                ->leftJoin('ra_remittance_type', 'ra_remittance_type.id', '=', 'apps.remittance_type_id')
                ->leftJoin('country_info as property_country', 'property_country.id', '=', 'apps.property_country_id')
                ->leftJoin('ra_present_status', 'ra_present_status.id', '=', 'apps.present_status_id')
                ->leftJoin('ra_project_status', 'ra_project_status.id', '=', 'apps.project_status_id')
                ->leftJoin('bank', 'bank.id', '=', 'apps.local_bank_id')
                ->leftJoin('country_info as local_bank_country', 'local_bank_country.id', '=', 'apps.local_bank_country_id')

                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'apps.*',
                    'process_type.max_processing_day',
                    'process_type.form_url',

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

                    'gfp.contact_name as gfp_contact_name',
                    'gfp.contact_email as gfp_contact_email',
                    'gfp.contact_no as gfp_contact_phone',
                    'gfp.address as gfp_contact_address',
                    'gfp.pay_amount as gfp_pay_amount',
                    'gfp.vat_on_pay_amount as gfp_vat_on_pay_amount',
                    'gfp.transaction_charge_amount as gfp_transaction_charge_amount',
                    'gfp.vat_on_transaction_charge as gfp_vat_on_transaction_charge',
                    'gfp.total_amount as gfp_total_amount',
                    'gfp.payment_status as gfp_payment_status',
                    'gfp.pay_mode as gfp_pay_mode',
                    'gfp.pay_mode_code as gfp_pay_mode_code',

                    'origin_country.nicename as origin_country_name',
                    'ea_organization_type.name as organization_type_name',
                    'ea_organization_status.name as organization_status_name',
                    'ea_ownership_status.name as ownership_status_name',
                    'sector_info.name as business_sector_name',
                    'sec_sub_sector_list.name as business_sub_sector_name',

                    'ceo_country.nicename as ceo_country_name',
                    'ceo_district.area_nm as ceo_district_name',
                    'ceo_thana.area_nm as ceo_thana_name',

                    'office_division.area_nm as office_division_name',
                    'office_district.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',

                    'factory_district.area_nm as factory_district_name',
                    'factory_thana.area_nm as factory_thana_name',

                    'ra_remittance_type.name as remittance_type_name',
                    'property_country.nicename as property_country_name',
                    'ra_present_status.name as present_status_name',
                    'ra_project_status.name as project_status_name',
                    'bank.name as bank_name',
                    'local_bank_country.nicename as local_bank_country_name',
                ]);

            if (in_array($appInfo->status_id, [15,32])) {
                $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                    'sp_payment_configuration.payment_category_id')
                    ->where([
                        'sp_payment_configuration.process_type_id' => $this->process_type_id,
                        'sp_payment_configuration.payment_category_id' => 2, //Government fee payment
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);

                if (empty($payment_config)) {
                    return response()->json([
                        'responseCode' => 1,
                        'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![REM-10100]</h4>"
                    ]);
                }

                $relevant_info_array = [
                    'proposed_sub_total_bdt' => $appInfo->proposed_sub_total_bdt,
                ];

                $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config, $relevant_info_array);
                $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
                $vatFreeAllowed = BasicInformationController::isAllowedWithOutVAT($this->process_type_id);
                $payment_config->vat_on_pay_amount = ($vatFreeAllowed === true) ? 0 : $unfixed_amount_array['total_vat_on_pay_amount'];
            }

            $bidaRegInfo = BidaRegInfo::where('app_id', $decodedAppId)
                ->get([
                    'registration_no',
                    'registration_date',
                    'registration_date',
                    'proposed_investment',
                    'actual_investment',
                    'registration_copy',
                    'amendment_copy',
                    'id'
                ]);

            $briefDescription = BriefDescription::where('app_id', $decodedAppId)
                ->get([
                    'brief_description',
                    'id'
                ]);

            $importedMachine = ImportedMachinery::where('app_id', $decodedAppId)
                ->get([
                    'import_year_from',
                    'import_year_to',
                    'cnf_value',
                    'id'
                ]);

            $otherRemittanceInfo = OtherInfo::leftJoin('ra_remittance_type', 'ra_remittance_type.id', '=', 'ra_other_info.remittance_type_id')
                ->where('app_id', $decodedAppId)
                ->get([
                    'ra_remittance_type.name',
                    'remittance_bdt',
                    'remittance_usd',
                    'remittance_percentage',
                    'attachment',
                ]);

            $briefStatement = BriefStatement::where('app_id', $decodedAppId)
                ->get([
                    'brief_statement',
                    'id'
                ]);

            $statementOfRemittance = StatementOfRemittance::leftJoin('ra_remittance_type', 'ra_remittance_type.id', '=', 'ra_statement_of_remittance.remittance_type_id')
                ->where('app_id', $decodedAppId)
                ->get([
                    'ra_remittance_type.name',
                    'remittance_year',
                    'bida_ref_no',
                    'date',
                    'approval_copy',
                    'amount',
                    'percentage',
                ]);

            $statementOfActualProd = StatementOfActualProduct::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_production',
                    'quantity',
                    'sales_value',
                    'id'
                ]);
            $statementOfExport = StatementOfExport::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_export',
                    'quantity',
                    'cnf_cif_value',
                    'id'
                ]);


            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.*', 'app_documents.id as document_id', 'app_documents.doc_file_path as doc_file_path'
                ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);
            $public_html = strval(view("Remittance::application-form-view", compact('process_type_id','appInfo', 'bidaRegInfo',
                'briefDescription', 'importedMachine', 'otherRemittanceInfo', 'briefStatement', 'statementOfActualProd', 'statementOfExport', 'statementOfRemittance',
                'payment_config', 'document', 'viewMode', 'mode', 'totalFee', '', 'metingInformation')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }catch (\Exception $e){
            Log::error('REMViewApp : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) ." [REM-1015]" . "</h4>"
            ]);
        }

    }


    public function uploadDocument()
    {
        return View::make('Remittance::ajaxUploadFile');
    }


    public function preview()
    {
        return view("Remittance::preview");
    }


    public function Payment(Request $request)
    {
        try {
            $appId = Encryption::decodeId($request->get('app_id'));
            // Get Payment Configuration
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 2,  // Government fee Payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0,
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);

            if (empty($payment_config)) {
                Session::flash('error', "Payment configuration not found [REM-1456]");
                return redirect()->back()->withInput();
            }

            // Get payment distributor list
            $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
                ->where('status', 1)
                ->where('is_archive', 0)
                ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
            if ($stakeDistribution->isEmpty()) {
                Session::flash('error', "Stakeholder not found [REM-101]");
                return redirect()->back()->withInput();
            }

            // Check the Govt. vat fee is allowed or not: boolean
            $vatFreeAllowed = BasicInformationController::isAllowedWithOutVAT($this->process_type_id);

            // Store payment info
            DB::beginTransaction();

            // Store payment info
            $paymentInfo = SonaliPayment::firstOrNew([
                'app_id' => $appId, 'process_type_id' => $this->process_type_id,
                'payment_config_id' => $payment_config->id
            ]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appId;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;

            // Concat Account no of stakeholder
            $account_no = "";
            foreach ($stakeDistribution as $distribution) {
                // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
                if ($vatFreeAllowed && in_array($distribution->distribution_type, [4,5,6])) {
                    continue;
                }

                $account_no .= $distribution->stakeholder_ac_no . "-";
            }
            $account_numbers = rtrim($account_no, '-');
            // Concat Account no of stakeholder End

            $paymentInfo->receiver_ac_no = $account_numbers;

            // Application Info
            $relevant_info_array = [
                'proposed_sub_total_bdt' => Remittance::where(['id' => $appId,])->value('proposed_sub_total_bdt')
            ];

            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config, $relevant_info_array);
            $paymentInfo->pay_amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $paymentInfo->vat_on_pay_amount = ($vatFreeAllowed === true) ? 0 : $unfixed_amount_array['total_vat_on_pay_amount'];
            $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
            $paymentInfo->contact_name = $request->get('gfp_contact_name');
            $paymentInfo->contact_email = $request->get('gfp_contact_email');
            $paymentInfo->contact_no = $request->get('gfp_contact_phone');
            $paymentInfo->address = $request->get('gfp_contact_address');
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInfo->save();

            if ($vatFreeAllowed) {
                SonaliPaymentController::vatFreeAuditStore($paymentInfo->id, $unfixed_amount_array['total_vat_on_pay_amount']);
            }
            
            //Payment Details By Stakeholders
            foreach ($stakeDistribution as $distribution) {
                // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
                if ($vatFreeAllowed && in_array($distribution->distribution_type, [4,5,6])) {
                    continue;
                }

                $paymentDetails = PaymentDetails::firstOrNew([
                    'sp_payment_id' => $paymentInfo->id, 'payment_distribution_id' => $distribution->id
                ]);
                $paymentDetails->sp_payment_id = $paymentInfo->id;
                $paymentDetails->payment_distribution_id = $distribution->id;
                $paymentDetails->pay_amount = ($distribution->fix_status == 1) ? $distribution->pay_amount : $unfixed_amount_array['amounts'][$distribution->distribution_type];
                $paymentDetails->distribution_type = $distribution->distribution_type;
                $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                $paymentDetails->purpose = $distribution->purpose;
                $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                $paymentDetails->fix_status = $distribution->fix_status;
                $paymentDetails->save();
            }
            //Payment Details By Stakeholders End

            Remittance::where('id', $appId)->update([
                'gf_payment_id' => $paymentInfo->id
            ]);

            // Payment Submission
            DB::commit();
            if ($request->get('actionBtn') == 'Submit' && $paymentInfo->id) {
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('REMPayment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1025]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[REM-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function afterPayment($payment_id)
    {
        try {
            DB::beginTransaction();
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

            // 1 = Service Fee Payment
            // tracking no generate only when payment is Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [REM-911]');
                    return redirect('process/remittance-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
                if (!in_array($processData->status_id, [15,32])) {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [REM-912]');
                    return redirect('process/remittance-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
            return redirect('process/remittance-new/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('REMAfterPayment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1051]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage(). ' [REM-1051]');
            return redirect('process/remittancet-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }


    public function afterCounterPayment($payment_id)
    {
        try {
            DB::beginTransaction();
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
//                $processData->status_id = 1; // Submitted
//                $processData->desk_id = 1;

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

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
            } /*
             * For Government Fee Payment set tracking no.
             * if payment verification status is not equal to 1
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 2) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } /*
            * if payment verification status is equal to 1
            * then transfer application to 'Payment submit' status
            * Government fee payment submit
            */
            elseif ($paymentInfo->is_verified == 1 && $paymentInfo->payment_category_id == 2) {
//                $processData->status_id = 16; // Payment submit
//                $processData->desk_id = 1; //  AD desk

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

            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/remittance-new/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('REMAfterCounterPayment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1052]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage().' [REM-1052]');
            return redirect('process/remittance-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function appFormPdf($appId)
    {

        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('ra_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')
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
                    'process_type.max_processing_day',

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

                    'gfp.contact_name as gfp_contact_name',
                    'gfp.contact_email as gfp_contact_email',
                    'gfp.contact_no as gfp_contact_phone',
                    'gfp.address as gfp_contact_address',
                    'gfp.pay_amount as gfp_pay_amount',
                    'gfp.vat_on_pay_amount as gfp_vat_on_pay_amount',
                    'gfp.transaction_charge_amount as gfp_transaction_charge_amount',
                    'gfp.vat_on_transaction_charge as gfp_vat_on_transaction_charge',
                    'gfp.total_amount as gfp_total_amount',
                    'gfp.payment_status as gfp_payment_status',
                    'gfp.pay_mode as gfp_pay_mode',
                    'gfp.pay_mode_code as gfp_pay_mode_code',
                ]);

            $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename',
                    'asc')->lists('nicename', 'id')->all();
            $eaOrganizationStatus = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive',
                    0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $eaOwnershipStatus = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status',
                    1)->orderBy('name')->lists('name', 'id')->all();
            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status',
                    1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            $sectors = ['' => 'Select one'] + Sector::orderBy('name')->lists('name', 'id')->all() + [0 => 'Others'];
            $sub_sectors = ['' => 'Select one'] + SubSector::orderBy('name')->lists('name', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality',
                'iso');
            $banks = ['' => 'Select one'] + Bank::where('is_active', 1)->where('is_archive',
                    0)->orderBy('name')->lists('name', 'id')->all();
            $branch = ['' => 'Select one'] + BankBranch::where('is_active', 1)->where('is_archive',
                    0)->orderBy('branch_name')->lists('branch_name', 'id')->all();
            $remittanceType = RemittanceType::orderby('id', 'desc')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive',
                0)->lists('name', 'id');
            $project_status = ['' => 'Select one'] + RemittanceProjectStatus::where('status', 1)->where('is_archive',
                    0)->orderby('name')->lists('name', 'id')->all();
            $userCompanyList = CompanyInfo::where('id', [$appInfo->company_id])->get([
                'company_name', 'company_name_bn', 'id'
            ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);

            //document view
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.*', 'app_documents.id as document_id', 'app_documents.doc_file_path as doc_file_path'
                ]);

            $bidaRegInfo = BidaRegInfo::where('app_id', $decodedAppId)
                ->get([
                    'registration_no',
                    'registration_date',
                    'proposed_investment',
                    'actual_investment',
                    'amendment_copy',
                    'registration_copy',
                    'id'
                ]);

            $briefDescription = BriefDescription::where('app_id', $decodedAppId)
                ->get([
                    'brief_description',
                    'id'
                ]);

            $importedMachine = ImportedMachinery::where('app_id', $decodedAppId)
                ->get([
                    'import_year_from',
                    'import_year_to',
                    'cnf_value',
                    'id'
                ]);

            $otherRemittanceInfo = OtherInfo::where('app_id', $decodedAppId)
                ->get([
                    'remittance_type_id',
                    'remittance_bdt',
                    'remittance_usd',
                    'remittance_percentage',
                    'attachment',
                    'id'
                ]);

            $briefStatement = BriefStatement::where('app_id', $decodedAppId)
                ->get([
                    'brief_statement',
                    'id'
                ]);

            $statementOfRemittance = StatementOfRemittance::where('app_id', $decodedAppId)
                ->get([
                    'remittance_type_id',
                    'remittance_year',
                    'bida_ref_no',
                    'date',
                    'approval_copy',
                    'amount',
                    'percentage',
                    'id'
                ]);

            $statementOfActualProd = StatementOfActualProduct::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_production',
                    'quantity',
                    'sales_value',
                    'id'
                ]);

            $statementOfExport = StatementOfExport::where('app_id', $decodedAppId)
                ->get([
                    'year_of_remittance',
                    'item_of_export',
                    'quantity',
                    'cnf_cif_value',
                    'id'
                ]);


            $contents = view("Remittance::application-form-pdf",
                compact('userCompanyList', 'appInfo', 'countries', 'eaOrganizationType', 'eaOrganizationStatus',
                    'eaOwnershipStatus',
                    'sectors', 'sub_sectors', 'divisions', 'districts', 'thana', 'nationality', 'banks', 'branch',
                    'remittanceType', 'metingInformation',
                    'remittancePresentStatus', 'project_status', 'userCompanyList', 'bidaRegInfo', 'briefDescription',
                    'project_status',
                    'importedMachine', 'otherRemittanceInfo', 'briefStatement', 'statementOfRemittance',
                    'statementOfActualProd',
                    'statementOfExport', 'document'))->render();

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
            Log::error('REMPdfView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [REM-1115]');
            return Redirect::back()->withInput();
        }


    }


    public function getBranckBybankId(Request $request)
    {

        $bank_id = $request->get('local_bank_id');
        $branch = BankBranch::where('is_active', 1)->where('is_archive', 0)->where('bank_id',
            $bank_id)->orderBy('branch_name')->lists('branch_name', 'id')->all();

        $data = ['responseCode' => 1, 'data' => $branch];
        return response()->json($data);

        // return $bank_id;
    }

    public function getNewReg()
    {
        return view("Remittance::new-reg");
    }

    public function LoadFiscalYear(Request $request)
    {
        $company_id = CommonFunction::getUserWorkingCompany();

        $period_from = ($request->get('period_from') != '' ? date('Y-m-d',
            strtotime($request->get('period_from'))) : '');
        $period_to = ($request->get('period_to') != '' ? date('Y-m-d', strtotime($request->get('period_to'))) : '');
        $viewMode = $request->get('viewMode');
        $getRemittanceInfo = ProcessList::leftjoin('ra_apps', 'ra_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
            ->whereBetween('ra_apps.approved_date', array($period_from, $period_to))
            ->get([
                'ra_apps.remittance_type_id',
                'ra_apps.proposed_amount_bdt',
                'ra_apps.proposed_amount_usd',
                'ra_apps.proposed_exp_percentage',
                'ra_apps.int_property_attachment as other_remittance_attachment',
            ]);

        $remittanceType = RemittanceType::orderby('id', 'desc')->where('status', 1)->where('is_archive',
            0)->lists('name', 'id');
        $html = strval(view("Remittance::fiscal-year", compact('remittanceType', 'getRemittanceInfo', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    public function conditionalApproveStore(Request $request)
    {
        // Validation
        $rules['conditional_approved_file'] = 'required';
        $messages['conditional_approved_file'] = 'Attachment file is required';
        $this->validate($request, $rules, $messages);

        try {

            DB::beginTransaction();
            $appId = Encryption::decodeId($request->get('app_id'));

            if ($request->hasFile('conditional_approved_file')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('conditional_approved_file');
                $file_path = trim(uniqid('BIDA_RAM-' . $appId . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $conditional_approved_file = $yearMonth . $file_path;
            }

            Remittance::where('id', $appId)->update([
                'conditional_approved_file'     => isset($conditional_approved_file) ? $conditional_approved_file : '',
                'conditional_approved_remarks'  => $request->get('conditional_approved_remarks')
            ]);

            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $appId)
                ->where('process_type_id', $this->process_type_id)
                ->first([
                    'process_list.*'
                ]);

            if (!in_array($processData->status_id, [17, 31])) {
                Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [REM-913]');
                return redirect('process/remittance-new/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
            }

            $conditional_submission_process_data = CommonFunction::getConditionFulfillSubmission($this->process_type_id);
            $processData->status_id = $conditional_submission_process_data['process_starting_status'];
            $processData->desk_id = $conditional_submission_process_data['process_starting_desk'];

            $processData->read_status = 0;
            // Applicant conditional remarks
            $processData->process_desc = $request->get('conditional_approved_remarks');
            $processData->save();

            DB::commit();
            Session::flash('success', 'Condition fulfilled successfully');
            return redirect('process/remittance-new/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('REMConditionalApproveStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [REM-1026]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[REM-1026]");
            return redirect()->back()->withInput();
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

        if ($payment_config->payment_category_id === 1) {

            // For service fee payment there have no unfixed distribution.

        } elseif ($payment_config->payment_category_id === 2) {
            // Govt-Vendor-Vat-Fee
            $vat_percentage = SonaliPaymentController::getGovtVendorVatPercentage();
            if (empty($vat_percentage)) {
                abort('Please, configure the value for VAT.');
            }

            $feesAmountRange = DB::table('pay_order_amount_setup')
                ->where('process_type_id', $this->process_type_id)
                ->get(['min_amount_bdt', 'max_amount_bdt', 'p_o_amount_bdt']);
            if (empty($feesAmountRange)) {
                abort('Please, pay order amount not setup.');
            }

            $totalFee = 0;
            foreach ($feesAmountRange as $value) {
                if ($value->min_amount_bdt <= $relevant_info_array['proposed_sub_total_bdt'] && $value->max_amount_bdt >= $relevant_info_array['proposed_sub_total_bdt']) {
                    $totalFee = $value->p_o_amount_bdt;
                    break;
                }
            }
            if ($totalFee == 0 && $relevant_info_array['proposed_sub_total_bdt'] >= '100000001') {
                $totalFee = '500000';
            }

            $unfixed_amount_array[3] = $totalFee;
            $unfixed_amount_array[5] = ($totalFee / 100) * $vat_percentage;

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