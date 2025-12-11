<?php

namespace App\Modules\VisaRecommendation\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\Airports;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\Department;
use App\Modules\Apps\Models\PaymentMethod;
use App\Modules\Apps\Models\VisaTypes;
use App\Modules\ProcessPath\Models\DeptApplicationTypes;
use App\Modules\ProcessPath\Models\DeptProcessAppTypeMapping;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessWiseVisaTypes;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\HighComissions;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\VisaRecommendation\Models\TravelVisaRecord;
use App\Modules\VisaRecommendation\Models\VisaRecommendation;
use App\Modules\VisaRecommendation\Models\VR_OnArrivalSought;
use App\Modules\VisaRecommendation\Models\VR_TravelPurpose;
use App\Modules\VisaRecommendation\Models\VR_VisitingServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;

class VisaRecommendationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 1;
        $this->aclName = 'VisaRecommendation';
    }

    /*
     * application form
     */
    public function applicationForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VRNC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VRNC-971]</h4>"
            ]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [VRNC-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        try {
            $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
            /*
             * Visa Recommendation New module has category-based (Visa type based) application
             * [Like- PI type, A Type, Visa on arrival]
             */
            $app_category = DeptApplicationTypes::whereIn('id', [1, 2, 3, 4, 5])
                ->where('status', 1)
                ->where('is_archive', 0)
                ->get([
                    'id', 'name', 'attachment_key', 'certificate_text', 'app_instruction'
                ]);
            if ($app_category->isEmpty()) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'>Sorry! Visa type not available right now</h4>"
                ]);
            }

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
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![VRNC-10100]</h4>"
                ]);
            }
            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            $process_type_id = $this->process_type_id;
            $travelVisaType = ProcessWiseVisaTypes::leftJoin('visa_types', 'visa_types.id', '=',
                'process_wise_visa_type.visa_type_id')
                ->where([
                    'process_wise_visa_type.process_type_id' => $this->process_type_id,
                    'process_wise_visa_type.other_significant_id' => 1,
                    'process_wise_visa_type.status' => 1,
                    'process_wise_visa_type.is_archive' => 0
                ])
                ->orderBy('process_wise_visa_type.id', 'asc')
                ->select('visa_types.type', 'visa_types.id')
                ->lists('visa_types.type', 'visa_types.id');

            $travel_purpose = VR_TravelPurpose::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $visiting_service_type = VR_VisitingServiceType::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $visa_on_arrival_sought = VR_OnArrivalSought::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $paymentMethods = ['' => 'Select One'] + PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id')->all();
            $airports = Airports::orderby('name')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id')->all();
            $countriesWithoutBD = Countries::where('country_status', 'Yes')->where('id', '!=', '18')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $currencies = ['' => 'Select One'] + Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)
                ->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');

            $business_category = Auth::user()->company->business_category;
            $viewMode = 'off';
            $mode = '-A-';

            $public_html = strval(view("VisaRecommendation::application-form",
                compact('process_type_id', 'app_category', 'travel_purpose', 'visiting_service_type',
                    'visa_on_arrival_sought', 'divisions', 'company_id',
                    'paymentMethods', 'countries', 'countriesWithoutBD', 'currencies', 'travelVisaType', 'payment_config',
                    'airports', 'districts', 'nationality', 'business_category', 'viewMode', 'mode', 'department_id', 'thana')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNAppForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1005]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [VRNC-1005]' . "</h4>"
            ]);
        }
    }

    public function getEmbassyByCountry(Request $request)
    {
        $country_id = $request->get('country_id');
        $highCommission = HighComissions::where('country_id', $country_id)->where('is_active', 1)
            ->where('is_archive', 0)
            ->select('id', DB::raw('CONCAT(high_comissions.name, ", ", high_comissions.address) AS commission'))
            ->lists('commission', 'id');
        if (count($highCommission) > 0) {
            $data = ['responseCode' => 1, 'data' => $highCommission];
        } else {
            $data = ['responseCode' => 0, 'data' => ''];
        }
        return response()->json($data);
    }

    public function getServicewiseType(Request $request)
    {
        $dept_id = $request->get('DEPT_ID');
        $process_type_id = $request->get('SERVICE_ID');
        $serviceData = DeptProcessAppTypeMapping::where(['SERVICE_ID' => $process_type_id, 'DEPT_ID' => $dept_id])
            ->whereNotIn('id', [16, 17, 21, 22])->orderBy('TYPE_CTG_NAME', 'ASC')->lists('TYPE_CTG_NAME', 'id');
        return response()->json($serviceData);
    }

    /*
     * application store
     */
    public function appStore(Request $request)
    {
        try{
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query. [VRNC-972]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        $dept_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services. [VRNC-9991]");
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
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [VR-100]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            Session::flash('error', "Stakeholder not found [VRNC-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [VRNC-105]");
            return redirect()->back()->withInput();
        }

        // Check application category is valid or not
        $getAppType = DeptApplicationTypes::find($request->get('app_type_id'));
        if (empty($getAppType)) {
            Session::flash('error', "Unknown Visa type! [VRNC-1211]");
            return redirect()->back();
        }

        $business_category = Auth::user()->company->business_category;

        if ($request->get('th_emp_work_permit') == 'yes') {
            $travel_history_docs = $this->getDocList('vrn_travel_history', 'type2');
        }

        //  Required Documents for attachment
        $attachment_key = "vrn" . $getAppType->attachment_key;
        if ($dept_id == 1) {
            $attachment_key .= "cml";
        } else if ($dept_id == 2) {
            $attachment_key .= "i";
        } else {
            $attachment_key .= "comm";
        }
        $doc_row = $this->getDocList($attachment_key, 'master');

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

            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }

            if ($getAppType->id != 5) {
                $rules['mission_country_id'] = 'required|numeric';
                $rules['high_commision_id'] = 'required|numeric';
                $rules['brief_job_description'] = 'required';

                $messages['mission_country_id.required'] = 'Basic Information: Select desired country field is required';
                $messages['mission_country_id.numeric'] = 'Basic Information: Select desired country must be numeric.';
                $messages['high_commision_id.required'] = 'Basic Information: Embassy/ High Commission field is required';
                $messages['high_commision_id.numeric'] = 'Basic Information: Embassy/ High Commission must be numeric.';
                $messages['brief_job_description.required'] = 'General Information: Brief job description field is required';

            }

            if ($getAppType->id == 5) {
                $rules['airport_id'] = 'required|numeric';
                $rules['visa_purpose_id'] = 'required|numeric';
                $rules['visa_purpose_others'] = 'required_if:visa_purpose_id,3';
                $rules['arrival_date'] = 'required|date|date_format:d-M-Y';
                $rules['arrival_time'] = 'required';
                $rules['arrival_flight_no'] = 'required';
                // departure date must be same as like arrival date or after of arrival date
                $rules['departure_date'] = 'required|date|date_format:d-M-Y|after:' . date('d-M-Y',
                        strtotime("-1 day", strtotime($request->get('arrival_date'))));
                $rules['departure_time'] = 'required';
                $rules['departure_flight_no'] = 'required';
                $rules['visiting_service_id'] = 'required|numeric';
                $rules['visa_on_arrival_sought_id'] = 'required|numeric';

                $messages['airport_id.required'] = 'The Desired Airport field is required when visa type is On Arrival.';
                $messages['airport_id.numeric'] = 'The Desired Airport field must be numeric.';
                $messages['visa_purpose_id.required'] = 'Purpose of visit field is required when visa type is On Arrival.';
                $messages['visa_purpose_id.numeric'] = 'Purpose of visit field must be numeric.';
                $messages['visa_purpose_others.required_if'] = 'If you select Airport Info section Purpose of visit "Others" then Specify others purpose is required.';
                $messages['arrival_date.required'] = 'The arrival date field is required when visa type is On Arrival.';
                $messages['arrival_time.required'] = 'The arrival time field is required when visa type is On Arrival.';
                $messages['arrival_flight_no.required'] = 'The arrival flight no field is required when visa type is On Arrival.';
                $messages['departure_date.required'] = 'The departure date field is required when visa type is On Arrival.';
                $messages['departure_date.after'] = 'The departure date must be a date after arrival date.';
                $messages['departure_time.required'] = 'The departure time field is required when visa type is On Arrival.';
                $messages['departure_flight_no.required'] = 'The departure flight no field is required when visa type is On Arrival.';
                $messages['visiting_service_id.required'] = 'The Type the services required for the visiting expatriate field is required when visa type is On Arrival.';
                $messages['visiting_service_id.numeric'] = 'The Type the services required for the visiting expatriate must be numeric when visa type is On Arrival.';
                $messages['visa_on_arrival_sought_id.required'] = 'The visa arrival sought field is required when visa type is On Arrival.';
                $messages['visa_on_arrival_sought_id.numeric'] = 'The visa arrival sought must be numeric when visa type is On Arrival.';
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

//            $rules['pass_issue_date'] = 'required|date|date_format:d-M-Y|before:' . date('Y-m-d', strtotime("+1 day"));
//            $rules['pass_expiry_date'] = 'required|date|date_format:d-M-Y|after:' . date('Y-m-d');

            $rules['ex_office_division_id'] = 'required|numeric';
            $rules['ex_office_district_id'] = 'required|numeric';
            $rules['ex_office_thana_id'] = 'required|numeric';
            $rules['ex_office_post_code'] = 'required|digits:4';
            $rules['ex_office_address'] = 'required';
            $rules['ex_office_mobile_no'] = 'required|phone_or_mobile';
            $rules['ex_office_email'] = 'required|email';

            if (!($getAppType->id == 3 || $getAppType->id == 5)) {
                $rules['basic_payment_type_id'] = 'required';
                $rules['basic_local_amount'] = 'numeric|required|min:0.01';
                $rules['basic_local_currency_id'] = 'required';

                $messages['basic_payment_type_id.required'] = 'Compensation and Benefit Basic salary/ Honorarium Payment field is required.';
                $messages['basic_local_amount.required'] = 'Compensation and Benefit Basic salary/ Honorarium Amount field is required.';
                $messages['basic_local_amount.numeric'] = 'Compensation and Benefit Basic salary/ Honorarium Amount must be numeric.';
                $messages['basic_local_amount.min'] = 'Compensation and Benefit Basic salary/ Honorarium Amount greater than zero(0).';
                $messages['basic_local_currency_id.required'] = 'Compensation and Benefit Basic salary/ Honorarium Currency field is required.';
            }

            if ($business_category == 2){
                $rules['emp_marital_status'] = 'required';
            }

            //manpower section
            if ($business_category == 1 && $getAppType->id != 5) {
                $rules['local_executive'] = 'required';
                $rules['local_stuff'] = 'required';
                $rules['local_total'] = 'required';
                $rules['foreign_executive'] = 'required';
                $rules['foreign_stuff'] = 'required';
                $rules['foreign_total'] = 'required';
                $rules['manpower_total'] = 'required';
                $rules['manpower_local_ratio'] = 'required';
                $rules['manpower_foreign_ratio'] = 'required';
            }

            // Travel history start
            if ($getAppType->id != 5) {
                $rules['travel_history'] = 'required';
            }
            $rules['th_visa_type_id'] = 'required_if:travel_history,yes';
            $rules['th_visit_with_emp_visa'] = 'required_if:th_visa_type_id,7,8,10';
            $rules['th_emp_work_permit'] = 'required_if:th_visit_with_emp_visa,yes';

            $rules['th_emp_tin_no'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_emp_wp_no'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_emp_org_name'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_emp_org_address'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_district_id'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_thana_id'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_post_office'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_post_code'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_telephone_no'] = 'required_if:th_emp_work_permit,yes';
            $rules['th_org_email'] = 'required_if:th_emp_work_permit,yes';


            // Travel history end

            $rules['accept_terms'] = 'required';
            $rules['agree_with_instruction'] = 'required';

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

            $messages['ex_office_division_id.required'] = 'Contact address of the expatriate in Bangladesh Division field is required';
            $messages['ex_office_district_id.required'] = 'Contact address of the expatriate in Bangladesh District field is required';
            $messages['ex_office_thana_id.required'] = 'Contact address of the expatriate in Bangladesh Police Station field is required';
            $messages['ex_office_post_code.required'] = 'Contact address of the expatriate in Bangladesh Post Code field is required';
            $messages['ex_office_address.required'] = 'Contact address of the expatriate in Bangladesh House, Flat/ Apartment, Road field is required';
            $messages['ex_office_mobile_no.required'] = 'Contact address of the expatriate in Bangladesh Mobile No. field is required';
            $messages['ex_office_email.required'] = 'Contact address of the expatriate in Bangladesh Email field is required';

            $messages['th_visa_type_id.required_if'] = 'Type of visa availed is required when you have visited to Bangladesh previously.';
            $messages['th_visit_with_emp_visa.required_if'] = 'The visited to Bangladesh with Employment Visa field is required when PI, A3, E type of visa availed.';
            $messages['th_emp_work_permit.required_if'] = 'Have you received a work permit from Bangladesh? is required when you have visited Bangladesh with Employment Visa.';
            $messages['th_emp_tin_no.required_if'] = 'TIN Number is required of Previous work permit information in Bangladesh.';
            $messages['th_emp_wp_no.required_if'] = 'Last Work Permit Ref No is required of Previous work permit information in Bangladesh.';
            $messages['th_emp_org_name.required_if'] = 'Name of the employer organization is required of Previous work permit information in Bangladesh.';
            $messages['th_emp_org_address.required_if'] = 'Address of the organization is required of Previous work permit information in Bangladesh.';
            $messages['th_org_district_id.required_if'] = 'City/ District is required of Previous work permit information in Bangladesh.';
            $messages['th_org_thana_id.required_if'] = 'Thana/ Upazilla is required of Previous work permit information in Bangladesh.';
            $messages['th_org_post_office.required_if'] = 'Post Office is required of Previous work permit information in Bangladesh.';
            $messages['th_org_post_code.required_if'] = 'Post Code is required of Previous work permit information in Bangladesh.';
            $messages['th_org_telephone_no.required_if'] = 'Contact Number is required of Previous work permit information in Bangladesh.';
            $messages['th_org_email.required_if'] = 'Email is required of Previous work permit information in Bangladesh.';
        } else {
            $rules['app_type_id'] = 'required';

            $messages['app_type_id.required'] = 'Visa Type is required';
        }

        $this->validate($request, $rules, $messages);

        // try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = VisaRecommendation::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new VisaRecommendation();
                $processData = new ProcessList();
            }
            $processData->company_id = $company_id;
            $appData->app_type_id = $request->get('app_type_id');

            if ($request->has('agree_with_instruction')) {
                $appData->agree_with_instruction = 1;
            }
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

            if ($business_category == 2 && $basicInfo->organization_type_id == 14) {
                $appData->organization_type_other = $basicInfo->organization_type_other;
            }

            //business category
            $appData->business_category = $business_category;

            // Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            // Information of Responsible Person
            $appData->ceo_country_id = $basicInfo->ceo_country_id;
            $appData->ceo_passport_no = $basicInfo->ceo_passport_no;
            $appData->ceo_nid = $basicInfo->ceo_nid;
            $appData->ceo_full_name = $basicInfo->ceo_full_name;
            $appData->ceo_designation = $basicInfo->ceo_designation;
            $appData->ceo_mobile_no = $basicInfo->ceo_mobile_no;
            $appData->ceo_email = $basicInfo->ceo_email;
            $appData->ceo_gender = $basicInfo->ceo_gender;

            if ($business_category == 2) {
                $appData->ceo_auth_letter = $basicInfo->ceo_auth_letter;
            }

            if ($business_category == 1){
                $appData->ceo_dob = $basicInfo->ceo_dob;
                $appData->ceo_district_id = $basicInfo->ceo_district_id;
                $appData->ceo_city = $basicInfo->ceo_city;
                $appData->ceo_state = $basicInfo->ceo_state;
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
            }

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

            $appData->visa_purpose_id = $request->get('visa_purpose_id');
            $appData->visa_purpose_others = $request->get('visa_purpose_others');
            $appData->mission_country_id = $request->get('mission_country_id');
            $appData->high_commision_id = $request->get('high_commision_id');

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
                $f = finfo_open();
                $file_name = trim(sprintf("%s", uniqid('BIDA_VR_', true))) . str_replace(" ", "_", $request->get('investor_photo_name'));

                file_put_contents($path . $file_name, $base64ResizeImage);
                $appData->investor_photo = $yearMonth . $file_name;
            }

            $appData->emp_name = $request->get('emp_name');
            $appData->emp_designation = $request->get('emp_designation');
            $appData->brief_job_description = $request->get('brief_job_description');
            $appData->emp_passport_no = $request->get('emp_passport_no');
            $appData->emp_personal_no = $request->get('emp_personal_no');
            $appData->emp_surname = $request->get('emp_surname');
            $appData->emp_given_name = $request->get('emp_given_name');
            $appData->pass_issue_date = (!empty($request->get('pass_issue_date')) ? date('Y-m-d',
                strtotime($request->get('pass_issue_date'))) : null);
            $appData->place_of_issue = $request->get('place_of_issue');
            $appData->pass_expiry_date = (!empty($request->get('pass_expiry_date')) ? date('Y-m-d',
                strtotime($request->get('pass_expiry_date'))) : null);
            $appData->emp_date_of_birth = (!empty($request->get('emp_date_of_birth')) ? date('Y-m-d',
                strtotime($request->get('emp_date_of_birth'))) : null);
            $appData->emp_place_of_birth = $request->get('emp_place_of_birth');
            $appData->emp_nationality_id = $request->get('emp_nationality_id');
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

            if ($business_category == 1) {
                $appData->nature_of_business = $request->get('nature_of_business');
                $appData->received_remittance = $request->get('received_remittance');
                $appData->auth_capital = $request->get('auth_capital');
                $appData->paid_capital = $request->get('paid_capital');
            }

            //Spouse Information
            if ($business_category == 2){
                $appData->emp_marital_status = $request->get('emp_marital_status');
                $appData->emp_spouse_name = $request->get('emp_spouse_name');
                $appData->emp_spouse_passport_no = $request->get('emp_spouse_passport_no');
                $appData->emp_spouse_nationality = $request->get('emp_spouse_nationality');
                $appData->emp_spouse_work_status = $request->get('emp_spouse_work_status');
                $appData->emp_spouse_org_name = $request->get('emp_spouse_org_name');
            }

            // Travel history start
            $appData->travel_history = $request->get('travel_history');
            $appData->th_visit_with_emp_visa = $request->get('th_visit_with_emp_visa');
            $appData->th_emp_work_permit = $request->get('th_emp_work_permit');
            $appData->th_emp_tin_no = $request->get('th_emp_tin_no');
            $appData->th_emp_wp_no = $request->get('th_emp_wp_no');
            $appData->th_emp_org_name = $request->get('th_emp_org_name');
            $appData->th_emp_org_address = $request->get('th_emp_org_address');
            $appData->th_org_post_code = $request->get('th_org_post_code');
            $appData->th_org_post_office = $request->get('th_org_post_office');
            $appData->th_org_telephone_no = $request->get('th_org_telephone_no');
            $appData->th_org_district_id = $request->get('th_org_district_id');
            $appData->th_org_thana_id = $request->get('th_org_thana_id');
            $appData->th_org_email = $request->get('th_org_email');

            //manpower section
            if ($business_category == 1) {
                $appData->local_executive = $request->get('local_executive');
                $appData->local_stuff = $request->get('local_stuff');
                $appData->local_total = $request->get('local_total');
                $appData->foreign_executive = $request->get('foreign_executive');
                $appData->foreign_stuff = $request->get('foreign_stuff');
                $appData->foreign_total = $request->get('foreign_total');
                $appData->manpower_total = $request->get('manpower_total');
                $appData->manpower_local_ratio = $request->get('manpower_local_ratio');
                $appData->manpower_foreign_ratio = $request->get('manpower_foreign_ratio');
            }

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
            $appData->visiting_service_id = $request->get('visiting_service_id');
            $appData->visa_on_arrival_sought_id = $request->get('visa_on_arrival_sought_id');
            $appData->visa_on_arrival_sought_other = $request->get('visa_on_arrival_sought_other');
            $appData->airport_id = $request->get('airport_id');
            $appData->basic_payment_type_id = $request->get('basic_payment_type_id');
            $appData->basic_local_amount = empty($request->get('basic_local_amount')) ? null : $request->get('basic_local_amount');
            $appData->basic_local_currency_id = $request->get('basic_local_currency_id');
            $appData->overseas_payment_type_id = $request->get('overseas_payment_type_id');
            $appData->overseas_local_amount = empty($request->get('overseas_local_amount')) ? null : $request->get('overseas_local_amount');
            $appData->overseas_local_currency_id = $request->get('overseas_local_currency_id');
            $appData->house_payment_type_id = $request->get('house_payment_type_id');
            $appData->house_local_amount = empty($request->get('house_local_amount')) ? null : $request->get('house_local_amount');
            $appData->house_local_currency_id = $request->get('house_local_currency_id');
            $appData->conveyance_payment_type_id = $request->get('conveyance_payment_type_id');
            $appData->conveyance_local_amount = empty($request->get('conveyance_local_amount')) ? null : $request->get('conveyance_local_amount');
            $appData->conveyance_local_currency_id = $request->get('conveyance_local_currency_id');
            $appData->medical_payment_type_id = $request->get('medical_payment_type_id');
            $appData->medical_local_amount = empty($request->get('medical_local_amount')) ? null : $request->get('medical_local_amount');
            $appData->medical_local_currency_id = $request->get('medical_local_currency_id');
            $appData->ent_payment_type_id = $request->get('ent_payment_type_id');
            $appData->ent_local_amount = empty($request->get('ent_local_amount')) ? null : $request->get('ent_local_amount');
            $appData->ent_local_currency_id = $request->get('ent_local_currency_id');
            $appData->bonus_payment_type_id = $request->get('bonus_payment_type_id');
            $appData->bonus_local_amount = empty($request->get('bonus_local_amount')) ? null : $request->get('bonus_local_amount');
            $appData->bonus_local_currency_id = $request->get('bonus_local_currency_id');
            $appData->other_benefits = $request->get('other_benefits');

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

            // Department and Sub-department specification for application processing
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => $dept_id,
                'app_type' => $request->get('app_type_id')
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            //$processData->read_status = 0;
            // $processData->approval_center_id = UtilFunction::getApprovalCenterId($company_id);
            $processData->approval_center_id = $processData->department_id == 1 ? 1 : UtilFunction::getApprovalCenterId($company_id);
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile No.'] = Auth::user()->user_phone;
            $jsonData['Visa Type'] = CommonFunction::getVisaTypeByAppTypeId($request->get('app_type_id'));
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            //  Previous travel history attachments store start
            if ($request->get('th_emp_work_permit') == 'yes') {
                if (count($travel_history_docs) > 0) {
                    foreach ($travel_history_docs as $travel_history_doc) {
                        $history_doc = AppDocuments::firstOrNew([
                            'process_type_id' => $this->process_type_id,
                            'ref_id' => $appData->id,
                            'doc_info_id' => $travel_history_doc->id
                        ]);
                        $history_doc->doc_name = $travel_history_doc->doc_name;
                        $history_doc->doc_file_path = $request->get('validate_field_' . $travel_history_doc->id);
                        $history_doc->doc_section = 'type2';
                        $history_doc->save();
                    }
                }
            }
            //  Previous travel history attachments store end

            //  Required Documents for attachment
            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
                    $app_doc = AppDocuments::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_info_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_file_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->doc_section = 'master';
                    $app_doc->save();
                }
            }

            // Visa Record Entry
            if ($request->has('travel_history') and $request->get('travel_history') == 'yes') {
                $visaRecordIds = [];
                foreach ($request->get('th_emp_duration_from') as $key => $value) {
                    if (empty($request->get('travel_visa_record_id')[$key])) {
                        $visaRecord = new TravelVisaRecord();
                        $visaRecord->app_id = $appData->id;
                    } else {
                        $recordId = $request->get('travel_visa_record_id')[$key];
                        $visaRecord = TravelVisaRecord::where('id', $recordId)->first();
                    }
                    $visaRecord->th_emp_duration_from = (!empty($request->get('th_emp_duration_from')[$key]) ? date('Y-m-d',
                        strtotime($request->get('th_emp_duration_from')[$key])) : null);
                    $visaRecord->th_emp_duration_to = (!empty($request->get('th_emp_duration_to')[$key]) ? date('Y-m-d',
                        strtotime($request->get('th_emp_duration_to')[$key])) : null);
                    $visaRecord->th_visa_type_id = $request->get('th_visa_type_id')[$key];
                    $visaRecord->th_visa_type_others = $request->get('th_visa_type_others')[$key];
                    $visaRecord->save();
                    $visaRecordIds[] = $visaRecord->id;
                }
                if (!empty($visaRecordIds)) {
                    TravelVisaRecord::where('app_id', $appData->id)->whereNotIn('id', $visaRecordIds)->delete();
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
                if (empty($processData->tracking_no)) {
                    $prefix = 'VR-' . date("dMY") . '-';
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
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }
            DB::commit();
            return redirect('visa-recommendation/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VRNAppStore: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1011]");
            return redirect()->back()->withInput();
        }
    }

    /*
     * application view/edit
     */
    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VRNC-1002]';
        }

        $mode = '-E-';
        $viewMode = 'off';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VRNC-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('vr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('high_comissions as hc', 'hc.id', '=', 'apps.high_commision_id')
                ->leftJoin('airports', 'airports.id', '=', 'apps.airport_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
//                    'process_list.locked_by',
//                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    DB::raw('CONCAT(hc.name, ", ", hc.address) AS high_commission'),
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

            /*
             * Visa Recommendation New module has category-based (Visa type based) application
             * [Like- PI type, A Type, Visa on arrival]
             */
            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);
            $app_category = DeptApplicationTypes::whereIn('id', [1, 2, 3, 4, 5])
                ->where('status', 1)
                ->where('is_archive', 0)
                ->get([
                    'id', 'name', 'attachment_key', 'certificate_text', 'app_instruction'
                ]);

            //$attachment_key = DeptApplicationTypes::Where('id', $appInfo->app_type_id)->first(['id', 'attachment_key']);

            if ($app_category->isEmpty()) {
                return response()->json(['responseCode' => 1, 'html' => "Sorry! no Visa type available right now"]);
            }

            // Last remarks attachment
            $remarks_attachment = DB::select(DB::raw("select * from
                                                `process_documents`
                                                where `process_type_id` = $this->process_type_id and `ref_id` = $appInfo->process_list_id and `status_id` = $appInfo->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$appInfo->process_list_id AND process_type_id=$this->process_type_id AND status_id=$appInfo->status_id)
                                                ORDER BY id ASC"
            ));

            $visaRecords = [];
            if ($appInfo->travel_history == 'yes') {
                $visaRecords = TravelVisaRecord::where('app_id', $appInfo->id)->where('status', 1)->get();
            }
            $travelVisaType = ProcessWiseVisaTypes::leftJoin('visa_types', 'visa_types.id', '=',
                'process_wise_visa_type.visa_type_id')
                ->where([
                    'process_wise_visa_type.process_type_id' => $this->process_type_id,
                    'process_wise_visa_type.other_significant_id' => 1,
                    'process_wise_visa_type.status' => 1,
                    'process_wise_visa_type.is_archive' => 0
                ])
                ->orderBy('process_wise_visa_type.id', 'asc')
                ->select('visa_types.type', 'visa_types.id')
                ->lists('visa_types.type', 'visa_types.id');

            $travel_purpose = VR_TravelPurpose::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $visiting_service_type = VR_VisitingServiceType::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $visa_on_arrival_sought = VR_OnArrivalSought::where('status', 1)->where('is_archive', 0)->orderBy('id', 'asc')->lists('name', 'id');
            $paymentMethods = ['' => 'Select One'] + PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id')->all();
            $airports = Airports::orderby('name')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $countriesWithoutBD = Countries::where('country_status', 'Yes')->where('id', '!=', '18')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
            $currencies = ['' => 'Select One'] + Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

            $public_html = strval(view("VisaRecommendation::application-form-edit",
                compact('process_type_id', 'appInfo', 'visaRecords', 'travel_purpose', 'visiting_service_type',
                    'divisions', 'visa_on_arrival_sought', 'airports', 'remarks_attachment', 'app_category', 'travelVisaType',
                    'paymentMethods', 'countries', 'countriesWithoutBD', 'currencies', 'department_id', 'company_id',
                    'districts', 'nationality', 'viewMode', 'mode', 'thana')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VRNC-1003]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VRNC-974]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('vr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('dept_application_type as app_type', 'app_type.id', '=', 'apps.app_type_id')// visa type
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('divisional_office', 'divisional_office.id', '=', 'process_list.approval_center_id')
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('airports', 'airports.id', '=', 'apps.airport_id')
                ->leftJoin('vr_travel_purpose', 'vr_travel_purpose.id', '=', 'apps.visa_purpose_id')
                ->leftJoin('country_info as mission_country', 'mission_country.id', '=', 'apps.mission_country_id')
                ->leftJoin('country_info as emp_nationality', 'emp_nationality.id', '=', 'apps.emp_nationality_id')
                ->leftJoin('country_info as spouse_nationality', 'spouse_nationality.id', '=', 'apps.emp_spouse_nationality')
                ->leftJoin('area_info as ex_office_division', 'ex_office_division.area_id', '=', 'apps.ex_office_division_id')
                ->leftJoin('area_info as ex_office_district', 'ex_office_district.area_id', '=', 'apps.ex_office_district_id')
                ->leftJoin('area_info as ex_office_thana', 'ex_office_thana.area_id', '=', 'apps.ex_office_thana_id')

                //Compensation and Benefit
                ->leftJoin('payment_methods as basic_payment', 'basic_payment.id', '=', 'apps.basic_payment_type_id')
                ->leftJoin('currencies as basic_currency', 'basic_currency.id', '=', 'apps.basic_local_currency_id')
                ->leftJoin('payment_methods as overseas_payment', 'overseas_payment.id', '=', 'apps.overseas_payment_type_id')
                ->leftJoin('currencies as overseas_currency', 'overseas_currency.id', '=', 'apps.overseas_local_currency_id')
                ->leftJoin('payment_methods as house_payment', 'house_payment.id', '=', 'apps.house_payment_type_id')
                ->leftJoin('currencies as house_currency', 'house_currency.id', '=', 'apps.house_local_currency_id')
                ->leftJoin('payment_methods as conveyance_payment', 'conveyance_payment.id', '=', 'apps.conveyance_payment_type_id')
                ->leftJoin('currencies as conveyance_currency', 'conveyance_currency.id', '=', 'apps.conveyance_local_currency_id')
                ->leftJoin('payment_methods as medical_payment', 'medical_payment.id', '=', 'apps.medical_payment_type_id')
                ->leftJoin('currencies as medical_currency', 'medical_currency.id', '=', 'apps.medical_local_currency_id')
                ->leftJoin('payment_methods as ent_payment', 'ent_payment.id', '=', 'apps.ent_payment_type_id')
                ->leftJoin('currencies as ent_currency', 'ent_currency.id', '=', 'apps.ent_local_currency_id')
                ->leftJoin('payment_methods as bonus_payment', 'bonus_payment.id', '=', 'apps.bonus_payment_type_id')
                ->leftJoin('currencies as bonus_currency', 'bonus_currency.id', '=', 'apps.bonus_local_currency_id')

                ->leftJoin('area_info as th_org_district', 'th_org_district.area_id', '=', 'apps.th_org_district_id')
                ->leftJoin('area_info as th_org_thana', 'th_org_thana.area_id', '=', 'apps.th_org_thana_id')
                ->leftJoin('vr_visiting_service_type', 'vr_visiting_service_type.id', '=', 'apps.visiting_service_id')
                ->leftJoin('vr_on_arrival_sought', 'vr_on_arrival_sought.id', '=', 'apps.visa_on_arrival_sought_id')
                ->leftJoin('high_comissions', 'high_comissions.id', '=', 'apps.high_commision_id')
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

                    'app_type.id as app_type_id',
                    'app_type.name as app_type_name',

                    'divisional_office.office_name as divisional_office_name',
                    'divisional_office.office_address as divisional_office_address',

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
                    'vr_travel_purpose.name as visa_purpose_name',
                    'mission_country.nicename as mission_country_name',
                    'emp_nationality.nationality as emp_nationality_name',

                    'spouse_nationality.nationality as spouse_nationality_name',

                    'ex_office_division.area_nm as ex_office_division_name',
                    'ex_office_district.area_nm as ex_office_district_name',
                    'ex_office_thana.area_nm as ex_office_thana_name',

                    //Compensation and Benefit
                    'basic_payment.name as basic_payment_type_name',
                    'basic_currency.code as basic_currency_code',
                    'overseas_payment.name as overseas_payment_type_name',
                    'overseas_currency.code as overseas_currency_code',
                    'house_payment.name as house_payment_type_name',
                    'house_currency.code as house_currency_code',
                    'conveyance_payment.name as conveyance_payment_type_name',
                    'conveyance_currency.code as conveyance_currency_code',
                    'medical_payment.name as medical_payment_type_name',
                    'medical_currency.code as medical_currency_code',
                    'ent_payment.name as ent_payment_type_name',
                    'ent_currency.code as ent_currency_code',
                    'bonus_payment.name as bonus_payment_type_name',
                    'bonus_currency.code as bonus_currency_code',

                    'th_org_district.area_nm as th_org_district_name',
                    'th_org_thana.area_nm as th_org_thana_name',

                    'vr_visiting_service_type.name as visiting_service_name',
                    'vr_on_arrival_sought.id as visa_on_arrival_sought_id',
                    'vr_on_arrival_sought.name as visa_on_arrival_sought_name',
                    'high_comissions.name as high_commision_name',
                    'high_comissions.address as high_commision_address',
                ]);

            $previous_travel_history = TravelVisaRecord::leftJoin('visa_types', 'visa_types.id', '=', 'vr_travel_visa_record.th_visa_type_id')
                ->where('vr_travel_visa_record.app_id', $appInfo->id)
                ->get([
                    'vr_travel_visa_record.th_emp_duration_from',
                    'vr_travel_visa_record.th_emp_duration_to',
                    'vr_travel_visa_record.th_visa_type_others',
                    'visa_types.type'
                ]);

            //  Previous travel history attachments
            $travel_history_document = $this->getDocument($decodedAppId, 'type2');

            // Required Documents for attachment
            $document = $this->getDocument($decodedAppId, 'master');

            $public_html = strval(view("VisaRecommendation::application-form-view",
                compact('appInfo','travel_history_document', 'document', 'previous_travel_history', 'mode', 'viewMode')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewApp: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1016]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1016]" . "</h4>"
            ]);
        }
    }

    public function preview()
    {
        return view("VisaRecommendation::preview");
    }

    public function uploadDocument()
    {
        return View::make('VisaRecommendation::ajaxUploadFile');
    }

    /*
     * Application PDF
     */
    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information. [VRNC-975]');
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('vr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('dept_application_type as app_type', 'app_type.id', '=', 'apps.app_type_id')// visa type
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
//                    'process_list.locked_by',
//                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'apps.*',
                    'company_info.business_category',
                    'app_type.id as app_type_id',
                    'app_type.name as app_type_name',

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
                ]);
            $visaRecords = [];
            if ($appInfo->travel_history == 'yes') {
                $visaRecords = TravelVisaRecord::where('app_id', $appInfo->id)->where('status', 1)->get();
            }
            $embassy_name = HighComissions::where('id', $appInfo->high_commision_id)->first(['name', 'address']);
            if ($appInfo->app_type_id == 5) {
                $visa_on_arrival_sought = VR_OnArrivalSought::where('status', 1)->where('is_archive', 0)->orderBy('id',
                    'asc')->lists('name', 'id');
                $visiting_service_type = VR_VisitingServiceType::where('status', 1)->where('is_archive',
                    0)->orderBy('id', 'asc')->lists('name', 'id');
                $airports = Airports::orderby('name')->lists('name', 'id');
            }

            if ($appInfo->app_type_id != 5) {
                $paymentMethods = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
                $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code',
                    'id');
            }

            if ($appInfo->app_type_id != 5) {
                $visaTypes = VisaTypes::where('is_archive', 0)->orderBy('id', 'asc')->lists('type',
                    'id');
            }
            if ($appInfo->travel_history == 'yes' || $appInfo->app_type_id == 5) {
                $travel_purpose = VR_TravelPurpose::where('status', 1)->where('is_archive', 0)->orderBy('id',
                    'asc')->lists('name', 'id');
            }

            $department = Department::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name',
                'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');

            //  Previous travel history attachments
            $travel_history_document = $this->getDocument($decodedAppId, 'type2');

            // Required Documents for attachment
            $document = $this->getDocument($decodedAppId, 'master');

            $contents = view("VisaRecommendation::application-form-pdf",
                compact('appInfo', 'visaRecords',  'embassy_name', 'visa_on_arrival_sought',
                    'airports', 'visiting_service_type', 'travel_purpose', 'department',
                    'divisions', 'visaTypes', 'paymentMethods', 'countries', 'currencies', 'thana_eng',
                    'districts', '', 'nationality', 'travel_history_document', 'document'))->render();

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
            Log::error('VRNPdfView ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VRNC-1115]');
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
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [VRNC-911]');
                    return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
                    return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                //$processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = (!empty($paymentInfo->payment_date) ? date('d-m-Y', strtotime($paymentInfo->payment_date)) : null);
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();

            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');

            return redirect('process/visa-recommendation/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VRNAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-102]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VRNC-102]');
            return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function afterPaymenthabib($payment_id)
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

        $processArray = [];

        try {

            DB::beginTransaction();
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {

                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [VRNC-911]');
                    return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }
//                $processData->status_id = 1;
//                $processData->desk_id = 1;

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

//                $processData->status_id = $general_submission_process_data['process_starting_status'];
//                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processArray['status_id']=$general_submission_process_data['process_starting_status'];
                $processArray['desk_id']=$general_submission_process_data['process_starting_desk'];

                $processArray['process_desc']='Service Fee Payment completed successfully.';
                $processArray['submitted_at']=date('Y-m-d H:i:s'); // application submitted Date

//                $processData->process_desc = 'Service Fee Payment completed successfully.';
//                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application submit status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
//                $processData->status_id = 16;
//                $processData->desk_id = 1;

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);

//                $processData->status_id = $general_submission_process_data['process_starting_status'];
//                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processArray['status_id']=$general_submission_process_data['process_starting_status'];
                $processArray['desk_id']=$general_submission_process_data['process_starting_desk'];

                //$processArray['read_status']=0;
                $processArray['process_desc']='Government Fee Payment completed successfully.';


//                $processData->read_status = 0;
//                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $isVerifyComplete=Session::get('payment.verifyComplete');
            if($isVerifyComplete){
                $processArray['updated_by']=$paymentInfo->created_by;

            }else{
                $processArray['updated_by']=CommonFunction::getUserId();
            }
            Session::forget('payment.verifyComplete');
            $processArray['updated_at']=Carbon::now();

            $updateProccess=ProcessList::where('id', $processData->id)
                ->update($processArray);

//            $processData->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');

            return redirect('process/visa-recommendation/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VRNAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-102]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VRNC-102]');
            return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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

            return redirect('process/visa-recommendation/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('VRNAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-103]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [VRNC-103]');
            return redirect('process/visa-recommendation/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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

    public function loadDocList(Request $request)
    {
        $app_id = !empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : "";
        $attachment_key = $request->get('attachment_key');
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

        $html = strval(view("VisaRecommendation::documents", compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    private function getDocList($attachment_key = '', $doc_section = 'master', $app_id = '')
    {
        if ($app_id != '') {
            $attachment = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
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

            if (count($attachment) < 1) {
                $attachment_query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.process_type_id', $this->process_type_id);
                if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
                    $attachment_query->whereIn('attachment_list.business_category', [2, 3]);
                } else {
                    $attachment_query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
                }
                $attachment = $attachment_query->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.*']);
            }

        } else {
            $attachment_query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.process_type_id', $this->process_type_id);
            if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
                $attachment_query->whereIn('attachment_list.business_category', [2, 3]);
            } else {
                $attachment_query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
            }
            $attachment = $attachment_query->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
        }

        return $attachment;
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