<?php

namespace App\Modules\API\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\API\Models\MohaApiQueue;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SecurityClearance\SecurityClearanceACL;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

//use App\Modules\API\Models\ApiTokenList;
//use App\Modules\API\Models\ApiTokenUser;
//use App\Modules\Apps\Models\PaymentMethod;
//use App\Modules\ProcessPath\Models\ProcessWiseVisaTypes;
//use App\Modules\Settings\Models\Currencies;
//use App\Modules\Users\Models\Countries;
//use App\Modules\WorkPermitNew\Models\WorkPermitNew;
//use Carbon\Carbon;
//use DB;
//use Illuminate\Http\Request;


class WorkPermitApiController extends Controller
{
    public function mohaRequest($process_type_id, $app_id)
    {
        if (!SecurityClearanceACL::getAccsessRight('E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $process_type_id = Encryption::decodeId($process_type_id);
            $app_id = Encryption::decodeId($app_id);

            $queue_list_check = MohaApiQueue::where('ref_id', $app_id)->where('status', '!=', -1)->count();
            if ($queue_list_check > 0) {
                return ['responseCode' => 0, 'data' => 'Already exist in Queue!'];
            }

            $appInfo = ProcessList::leftJoin('wp_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftjoin('company_info', 'process_list.company_id', '=', 'company_info.id')
                ->leftjoin('country_info as nationality', 'apps.emp_nationality_id', '=', 'nationality.id')
                ->leftJoin('visa_types', 'visa_types.id', '=', 'apps.work_permit_type')
                ->leftJoin('area_info as office_dist', 'office_dist.area_id', '=', 'apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'apps.office_thana_id')
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
                ->where('process_list.ref_id', $app_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'apps.*',
                    'process_list.ref_id',
                    'process_list.department_id as dept_id',
                    'process_list.process_type_id as SERVICE_ID',
                    'process_list.status_id as STATUS_ID',
                    'process_list.tracking_no as wp_tracking_no',
                    'process_list.company_id',
                    'process_list.completed_date',
                    'company_info.company_name',

                    'nationality.name as country_name',
                    'nationality.iso3 as country_iso3',
                    'nationality.nationality as nationality_name',
                    'nationality.iso3 as nationality_iso3',

                    'visa_types.type as visa_type',

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

                    'office_dist.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',
                ]);
            if (empty($appInfo)) {
                return ['responseCode' => 0, 'data' => 'Your tracking no was not found!'];
            }

            if (empty($appInfo->certificate_link)) {
                return ['responseCode' => 0, 'data' => 'The approval letter is not generated yet!'];
            }

            $request_id = $app_id . rand(101, 999);

            $dataArray = [];
            $dataArray['project_code'] = "bida-oss";
            $dataArray['request_id'] = $request_id;
            $dataArray['depertment_name'] = CommonFunction::getDepartmentNameById($appInfo->dept_id);
            $dataArray['wp_tracking_no'] = $appInfo->wp_tracking_no;
            $visa_ref_no = !empty($appInfo->ref_app_tracking_no) ? $appInfo->ref_app_tracking_no : $appInfo->manually_approved_wp_no;

            $dataArray['basic_info'] = [
                'period_validity' => $appInfo->approved_desired_duration,
                'visa_ref_no' => $visa_ref_no,
                'visa_category' => $appInfo->visa_type,
                'permit_efct_date' => !empty($appInfo->approved_duration_start_date) ? date('Y-m-d', strtotime($appInfo->approved_duration_start_date)) : '',
                'applicant_photo' => url('uploads/' . $appInfo->investor_photo),
                'forwarding_letter' => $appInfo->certificate_link,
                //'forwarding_letter' => str_replace("http://cdn-bida.eserve.org.bd:8055","https://edoc.oss.net.bd", $appInfo->certificate_link),
                //'forwarding_letter' => config('app.server_type') == 'live' ? str_replace("http://cdn-bida.eserve.org.bd:8055","https://edoc.oss.net.bd", $appInfo->certificate_link) : $appInfo->certificate_link,
            ];

            $getBasicInfo = BasicInformation::leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'ea_apps.organization_type_id')
                ->where('company_id', $appInfo->company_id)
                ->where('is_approved', 1)
                ->first([
                    'ea_apps.*',
                    'ea_organization_type.name as ea_organization_type'
                ]);

            $dataArray['a_particular_of_sponsor'] = [
                'org_name' => $appInfo->company_name,
                'org_house_no' => $appInfo->office_address,
                'org_flat_no' => "",
                'org_fax_no' => $appInfo->office_fax_no,
                'org_district' => $appInfo->office_district_name,
                'org_thana' => $appInfo->office_thana_name,
                'org_road_no' => "",
                'org_post_code' => $appInfo->office_post_code,
                'org_phone' => $appInfo->office_mobile_no,
                'org_email' => $appInfo->office_email,
                'nature_of_business' => $appInfo->nature_of_business,
                'authorized_capital' => $appInfo->auth_capital,
                'paid_up_capital' => $appInfo->paid_capital,
                'remittance_received' => $appInfo->received_remittance,
                'org_type' => $getBasicInfo->ea_organization_type,
                'industry_type' => $appInfo->business_category == 2 ? 'Government' : 'Private',
            ];

            // Data from Information of Expatriate/ Investor/ Employee
            $dataArray['b_particular_of_foreign_incumbent'] = [
                'name_of_the_foreign_national' => $appInfo->emp_name,
                'date_of_birth' => !empty($appInfo->emp_date_of_birth) ? date('Y-m-d', strtotime($appInfo->emp_date_of_birth)) : '',
                'marital_status' => '',
                'date_of_arrival' => !empty($appInfo->date_of_arrival) ? date('Y-m-d', strtotime($appInfo->date_of_arrival)) : '',

                //'country' => $appInfo->country_code,
                'country' => $appInfo->country_name,
                'country_iso3' => $appInfo->country_iso3,
                //'nationality' => $appInfo->nationality_code,
                'nationality' => $appInfo->nationality_name,
                'nationality_iso3' => $appInfo->nationality_iso3,


                'passport_no' => $appInfo->emp_passport_no,
                'passport_issue_date' => !empty($appInfo->pass_issue_date) ? date('Y-m-d', strtotime($appInfo->pass_issue_date)) : '',
                'passport_issue_place' => $appInfo->place_of_issue,
                'passport_exiry_date' => !empty($appInfo->pass_expiry_date) ? date('Y-m-d', strtotime($appInfo->pass_expiry_date)) : '',
                'house_no' => '',
                'home_country' => '',
                'flat_no' => '',
                'road_no' => '',
                'post_code' => '',
                'state' => '',
                'phone' => '',
                'fax_no' => '',
                'email' => '',
            ];

            $dataArray['c_employment_information'] = [
                "employed_designation" => $appInfo->emp_designation,
                "first_appointment_date" => !empty($appInfo->approved_duration_start_date) ? date('Y-m-d', strtotime($appInfo->approved_duration_start_date)) : '',
                "desired_effective_date" => !empty($appInfo->duration_start_date) ? date('Y-m-d', strtotime($appInfo->duration_start_date)) : '',
                "desired_end_date" => !empty($appInfo->duration_end_date) ? date('Y-m-d', strtotime($appInfo->duration_end_date)) : '',
                "visa_type" => $appInfo->visa_type_name,
                "travel_visa_cate" => "",
                "visa_validity" => $appInfo->desired_duration, // Need to discuss with quader vai and nazmul vai
                "brief_job_description" => $appInfo->brief_job_description,
                "employee_justification" => '',
            ];

            $dataArray['compensation_and_benefits'] = [
                'basic_salary' => [
                    "amount" => $appInfo->basic_local_amount,
                    "payment_type" => $appInfo->basic_payment_type_name,
                    "currency" => $appInfo->basic_currency_code,
                ], 'overseas_allowance' => [
                    "amount" => $appInfo->overseas_local_amount,
                    "payment_type" => $appInfo->overseas_payment_type_name,
                    "currency" => $appInfo->overseas_currency_code,
                ], 'house_rent' => [
                    "amount" => $appInfo->house_local_amount,
                    "payment_type" => $appInfo->house_payment_type_name,
                    "currency" => $appInfo->house_currency_code,
                ], 'conveyance_allowance' => [
                    "amount" => $appInfo->conveyance_local_amount,
                    "payment_type" => $appInfo->conveyance_payment_type_name,
                    "currency" => $appInfo->conveyance_currency_code,
                ], 'medical_allowance' => [
                    "amount" => $appInfo->medical_local_amount,
                    "payment_type" => $appInfo->medical_payment_type_name,
                    "currency" => $appInfo->medical_currency_code,
                ], 'entertainment_allowance' => [
                    "amount" => $appInfo->ent_local_amount,
                    "payment_type" => $appInfo->ent_payment_type_name,
                    "currency" => $appInfo->ent_currency_code,

                ], 'annual_bonus' => [
                    "amount" => $appInfo->bonus_local_amount,
                    "payment_type" => $appInfo->bonus_payment_type_name,
                    "currency" => $appInfo->bonus_currency_code,

                ], 'other_benefit' => $appInfo->other_benefits,
                'salary_remarks' => ''
            ];

            $dataArray['manpower_of_the_office'] = [
                'local_manpower' => [
                    "executive" => $appInfo->local_executive,
                    "supporting_staff" => $appInfo->local_stuff,
                    "total" => $appInfo->local_total

                ], 'foreign_manpower' => [
                    "executive" => $appInfo->foreign_executive,
                    "supporting_staff" => $appInfo->foreign_stuff,
                    "total" => $appInfo->foreign_total

                ],
                'grand_total' => $appInfo->manpower_total,
                'locRatio' => $appInfo->manpower_local_ratio,
                'forRatio' => $appInfo->manpower_foreign_ratio,
            ];

            $dataArray['authorized_personnel_of_the_organization'] = [
                "org_name" => $appInfo->company_name,
                "org_house_no" => $appInfo->office_address,
                "org_flat_no" => '',
                "org_road_no" => '',
                "org_thana" => $appInfo->office_thana_name,
                "org_district" => $appInfo->office_district_name,
                "org_post_office" => $appInfo->office_post_office,
                "org_mobile" => $appInfo->auth_mobile_no,
                "submission_date" => !empty($appInfo->completed_date) ? date('Y-m-d', strtotime($appInfo->completed_date)) : '',
                "expatriate_name" => $appInfo->auth_full_name,
                "expatriate_email" => $appInfo->auth_email
            ];

            $wp_docs = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $appInfo->ref_id)
                ->where('app_documents.process_type_id', 2)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'app_documents.doc_name', 'app_documents.doc_file_path'
                ]);

            $att = [];
            foreach ($wp_docs as $key => $data) {
                $att[$key]['doc_name'] = $data['doc_name'];
                $att[$key]['file_public_path'] = url('uploads/' . $data['doc_file_path']);
            }
            $dataArray['document_list'] = $att;

            $insert = MohaApiQueue::firstOrNew([
                'ref_id' => $appInfo->ref_id
            ]);

            $insert->type = 'SUBMISSION_REQUEST';
            $insert->request_json = json_encode($dataArray, true);
            $insert->ref_id = $appInfo->ref_id;
            $insert->status = 0;
            $insert->response_json = '';
            $insert->status_check_response = '';
            $insert->moha_tracking_id = '';
            $insert->certificate = '';
            $insert->fl_certificate = '';

            $insert->save();

            return ['responseCode' => 1, 'data' => 'Application Send to MoHA Successfully!'];

        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage() . $e->getLine());
            return redirect()->back();
        }
    }
    public function getApplicationStatusFromMoha($process_type_id, $app_id)
    {
        $decoded_app_id = Encryption::decodeId($app_id);
        $request_id = $decoded_app_id . rand(101, 999);
        $tracking_no = processList::where('ref_id', $decoded_app_id)
            ->where('process_type_id', Encryption::decodeId($process_type_id))
            ->value('tracking_no');

        $status_check_request= [
            'project_code' => 'bida-oss',
            'request_id' => $request_id,
            'tracking_no' => $tracking_no
        ];

        $status_check_request_json = json_encode($status_check_request, true);

        MohaApiQueue::where('ref_id', $decoded_app_id)
            ->update([
                'status_check_request_json' => $status_check_request_json,
                'ready_to_check' => 1
            ]);

        return  ['responseCode' => 1, 'data' => 'Status update Successfully!'];
    }

//    public function apiRequest(Request $request)
//    {
//        $ip = $_SERVER['REMOTE_ADDR'];
////        if(isset($_SERVER['SERVER_ADDR'])){
////            $ip = $_SERVER['SERVER_ADDR'];
////        }
//        $IP = DB::table('api_request_access')->where('ip', $ip)->first();
//
//        if (!empty($IP)) {
//            $tracking_no = $request->get('TRACK_NO');
//            $process_type_id = 2;
//            $appInfo = ProcessList::leftJoin('wp_apps as apps', 'apps.id', '=', 'process_list.ref_id')
//                ->leftjoin('company_info', 'process_list.company_id', '=', 'company_info.id')
//                ->where('process_list.tracking_no', $tracking_no)
//                ->where('process_list.status_id', 25)
//                ->where('process_list.process_type_id', $process_type_id)
//                ->first([
//                    'process_list.department_id as DEPT_ID',
//                    'process_list.process_type_id as SERVICE_ID',
//                    'process_list.status_id as STATUS_ID',
//
//                    'process_list.company_id',
//                    'company_info.company_name as ORG_NAME',
//                    'apps.duration_start_date as PERIOD_VALIDITY',
//                    'apps.ref_app_tracking_no as VISA_REF_NO',
//                    'apps.approved_duration_start_date as PERMIT_EFCT_DATE',
//                    'apps.last_vr as VISA_PERMIT',
//                    'apps.id as application_id',
//                    'apps.ref_app_tracking_no as VISA_REF_NO_ONLINE',
//                    'apps.manually_approved_wp_no as VISA_REF_NO',
//                    'apps.certificate_link as json_cert_arr',
//                    'apps.nature_of_business as ORG_ACTIVITY',
//                    'apps.auth_capital as AUTH_CAPITAL',
//                    'apps.paid_capital as PAID_CAPITAL',
//                    'apps.received_remittance as REMITTANCE',
//                    'apps.date_of_arrival as ARRIVAL_DATE',
//                    'apps.emp_designation as POST_EMP_NAME',
//                    'apps.emp_name as EXP_NAME',
//                    'apps.emp_nationality_id',
//                    'apps.emp_passport_no as PASSPORT_NO',
//                    'apps.pass_issue_date as ISSUE_DATE',
//                    'apps.place_of_issue as ISSUE_PLACE',
//                    'apps.pass_expiry_date as EXPR_DATE',
//                    'apps.approved_duration_start_date as FIRST_APPOINT_DATE',
//                    'apps.duration_start_date as EFCT_START_DATE',
//                    'apps.duration_end_date as EFCT_END_DATE',
//                    'apps.desired_duration as VISA_VALIDITY',
//                    'apps.brief_job_description as POST_EMP_JOB_DESC',
//                    'apps.approved_desired_duration as PERIOD_VALIDITY',
//                    'apps.basic_payment_type_id',
//                    'apps.basic_local_amount as BASIC_SALARY',
//                    'apps.basic_local_currency_id',
//                    'apps.overseas_payment_type_id',
//                    'apps.overseas_local_amount as OVERSEAS_ALLOWANCE',
//                    'apps.overseas_local_currency_id',
//                    'apps.house_payment_type_id',
//                    'apps.house_local_amount as HOUSE_RENT',
//                    'apps.house_local_currency_id',
//                    'apps.work_permit_type',
//
//                    ///
//                    'apps.conveyance_payment_type_id',
//                    'apps.conveyance_local_amount as CONVEYANCE_ALLOWANCE',
//                    'apps.conveyance_local_currency_id',
//
//                    'apps.medical_payment_type_id',
//                    'apps.medical_local_amount as MEDICAL_ALLOWANCE',
//                    'apps.medical_local_currency_id',
//
//
//                    'apps.ent_payment_type_id',
//                    'apps.ent_local_amount as ENTERTAINMENT_ALLOWANCE',
//                    'apps.ent_local_currency_id',
//
//                    'apps.bonus_payment_type_id',
//                    'apps.bonus_local_amount as ANNUAL_BONUS',
//                    'apps.bonus_local_currency_id',
//                    'apps.other_benefits as OTHER_BENEFIT',
//
//
//                    'apps.investor_photo as AUTH_IMAGE',
//
//
//                    'apps.certificate_link as json_cert_arr',
//                    'apps.local_executive as MP_LOC_EXECUTIVE',
//                    'apps.local_total as MP_LOC_STAFF',
//                    'apps.foreign_executive as FOR_LOC_EXECUTIVE',
//                    'apps.foreign_stuff as FOR_LOC_STAFF',
//                    'apps.foreign_total as FOR_LOC_TOTAL',
//                    'apps.manpower_total as GRAND_TOTAL',
//                    'apps.manpower_local_ratio as locRatio',
//                    'apps.manpower_foreign_ratio as forRatio',
//
//                ]);
//            if ($appInfo == null) {
//                $arr = [
//                    'status' => false,
//                    'response' => "Your tracking no was not found.  "
//                ];
//                echo json_encode($arr);
//                exit;
//            }
//            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');
//            $paymentMethods = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
//
//            $WP_visaTypes = ProcessWiseVisaTypes::leftJoin('visa_types', 'visa_types.id', '=',
//                'process_wise_visa_type.visa_type_id')
//                ->where([
//                    'process_wise_visa_type.process_type_id' => 2,
//                    'process_wise_visa_type.other_significant_id' => 1,
//                    'process_wise_visa_type.status' => 1,
//                    'process_wise_visa_type.is_archive' => 0
//                ])
//                ->orderBy('process_wise_visa_type.id', 'asc')
//                ->select('visa_types.type', 'visa_types.id')
//                ->lists('visa_types.type', 'visa_types.id');
//
//
//            $getBasicInfo = BasicInformation::where('company_id', $appInfo->company_id)->where('is_approved', 1)->first();
//            $fileNames = explode('/', $appInfo->json_cert_arr);
//
//            $fileFathCertificate = '';
//            for ($i = 0; $i < count($fileNames); $i++) {
//                if ($i > 2) { //for cdn certificate
//                    $fileFathCertificate .= '/' . $fileNames[$i];
//                }
//            }
//            $countryCode = Countries::where('id', $appInfo->emp_nationality_id)->first();
//            $appInfo->NATIONALITY = isset($countryCode) ? $countryCode->mh_country_code : "0000";
//            $appInfo->json_cert_arr1 = $fileFathCertificate;
//            $appInfo->ORG_NAME = $getBasicInfo->company_name;
//            $appInfo->ORG_HOUSE_NO = $getBasicInfo->office_address;
//            $appInfo->ORG_FLAT_NO = '';
//            $appInfo->ORG_ROAD_NO = '';
////            $appInfo->ORG_DISTRICT = $getBasicInfo->office_district_id;
//            $appInfo->ORG_THANA = $getBasicInfo->office_thana_id;
//            $appInfo->ORG_DISTRICT = '';
//            $appInfo->ORG_THANA = '';
//            $appInfo->ORG_POST_CODE = $getBasicInfo->office_post_code;
//            $appInfo->ORG_PHONE = $getBasicInfo->office_telephone_no;
//            $appInfo->ORG_EMAIL = $getBasicInfo->office_email;
//            $appInfo->INDUSTRY_TYPE = $getBasicInfo->business_sub_sector_id;
//            $appInfo->INDUSTRY_TYPE = $getBasicInfo->organization_type_id;
//            $appInfo->ORG_TYPE = $getBasicInfo->organization_type_id;
////            dd($paymentMethods[$appInfo->basic_payment_type_id]);
//            $appInfo->VISA_TYPE = isset($WP_visaTypes[$appInfo->work_permit_type]) ? $WP_visaTypes[$appInfo->work_permit_type] : '';
//
//            $appInfo->PLOC1 = isset($paymentMethods[$appInfo->basic_payment_type_id]) ? $paymentMethods[$appInfo->basic_payment_type_id] : '';
//            $appInfo->PLOC2 = isset($paymentMethods[$appInfo->overseas_payment_type_id]) ? $paymentMethods[$appInfo->overseas_payment_type_id] : '';
//            $appInfo->PLOC3 = isset($paymentMethods[$appInfo->house_payment_type_id]) ? $paymentMethods[$appInfo->house_payment_type_id] : '';
//            $appInfo->PLOC4 = isset($paymentMethods[$appInfo->conveyance_payment_type_id]) ? $paymentMethods[$appInfo->conveyance_payment_type_id] : '';
//            $appInfo->PLOC5 = isset($paymentMethods[$appInfo->medical_payment_type_id]) ? $paymentMethods[$appInfo->medical_payment_type_id] : '';
//            $appInfo->PLOC6 = isset($paymentMethods[$appInfo->ent_payment_type_id]) ? $paymentMethods[$appInfo->ent_payment_type_id] : '';
//            $appInfo->PLOC7 = isset($paymentMethods[$appInfo->bonus_payment_type_id]) ? $paymentMethods[$appInfo->bonus_payment_type_id] : '';
//
//            $appInfo->CURRENCY1 = isset($currencies[$appInfo->basic_local_currency_id]) ? $currencies[$appInfo->basic_local_currency_id] : '';
//            $appInfo->CURRENCY2 = isset($currencies[$appInfo->overseas_local_currency_id]) ? $currencies[$appInfo->overseas_local_currency_id] : '';
//            $appInfo->CURRENCY3 = isset($currencies[$appInfo->house_local_currency_id]) ? $currencies[$appInfo->house_local_currency_id] : '';
//
//            $appInfo->CURRENCY4 = isset($currencies[$appInfo->conveyance_local_currency_id]) ? $currencies[$appInfo->conveyance_local_currency_id] : '';
//            $appInfo->CURRENCY5 = isset($currencies[$appInfo->medical_local_currency_id]) ? $currencies[$appInfo->medical_local_currency_id] : '';
//            $appInfo->CURRENCY6 = isset($currencies[$appInfo->ent_local_currency_id]) ? $currencies[$appInfo->ent_local_currency_id] : '';
//            $appInfo->CURRENCY7 = isset($currencies[$appInfo->bonus_local_currency_id]) ? $currencies[$appInfo->bonus_local_currency_id] : '';
//
//
//            $appInfo->ORG_NAMES = $getBasicInfo->company_name;
//            $appInfo->ORG_HOUSE_NOS = $getBasicInfo->office_address;
////            $appInfo->ORG_DISTRICTS = $getBasicInfo->office_district_id;
////            $appInfo->ORG_THANAS = $getBasicInfo->office_thana_id;
//
//            $appInfo->ORG_DISTRICTS = '';
//            $appInfo->ORG_THANAS = '';
//
//            $appInfo->ORG_POST_OFFICE = $getBasicInfo->office_post_office;
//            $appInfo->ORG_FAX_NO = $getBasicInfo->office_fax_no;
//            $appInfo->AUTH_MOBILE = $getBasicInfo->auth_mobile_no;
//            $appInfo->ENTRY_DT = $getBasicInfo->approved_date;
//            $appInfo->AUTH_FULL_NAME = $getBasicInfo->auth_full_name;
//            $appInfo->AUTH_EMAIL = $getBasicInfo->auth_email;
//            if ($appInfo->VISA_REF_NO == null) {
//                $appInfo->VISA_REF_NO = $appInfo->VISA_REF_NO_ONLINE;
//            }
//
////            $appInfo->MP_LOC_EXECUTIVE = $getBasicInfo->local_executive;
////            $appInfo->MP_LOC_STAFF = $getBasicInfo->local_stuff;
////            $appInfo->MP_LOC_TOTAL = $getBasicInfo->local_total;
////            $appInfo->FOR_LOC_EXECUTIVE = $getBasicInfo->foreign_executive;
////            $appInfo->FOR_LOC_STAFF = $getBasicInfo->foreign_stuff;
////            $appInfo->FOR_LOC_TOTAL = $getBasicInfo->foreign_total;
////            $appInfo->GRAND_TOTAL = $getBasicInfo->manpower_total;
////            $appInfo->locRatio = $getBasicInfo->manpower_local_ratio;
////            $appInfo->forRatio = $getBasicInfo->manpower_foreign_ratio;
//
//
//            $appInfo->EXP_HOME_COUNTRY = '';
//            $appInfo->EXP_HOUSE_NO = '';
//            $appInfo->EXP_HOUSE_NO = '';
//            $appInfo->EXP_FLAT_NO = '';
//            $appInfo->EXP_ROAD_NO = '';
//            $appInfo->EXP_POST_CODE = '';
//            $appInfo->EXP_POST_OFFICE = '';
//            $appInfo->EXP_PHONE = '';
//            $appInfo->EXP_DISTRICT = '';
//            $appInfo->EXP_FAX_NO = '';
//            $appInfo->EXP_EMAIL = '';
//            $appInfo->DOB = '';
//            $appInfo->MARITAL_STATUS = '';
//            $appInfo->TRAVEL_VISA_CATE = '';
//            $appInfo->EMP_JUSTIFICATION = '';
//            $appInfo->ABM1 = '';
//            $appInfo->ABC1 = '';
//            $appInfo->SALARY_REMARKS = '';
//            $appInfo->ORG_FLAT_NOS = '';
//            $appInfo->ORG_ROAD_NOS = '';
////            $appInfo->ORG_POST_OFFICES = '';
//
//            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
//                ->where('app_documents.ref_id', $appInfo->application_id)
//                ->where('app_documents.process_type_id', 2)
//                ->where('app_documents.doc_file_path', '!=', '')
//                ->get([
//                    'app_documents.id as document_id', 'app_documents.doc_name as DOC_NAME', 'app_documents.doc_file_path as FILE_NAME'
//                ]);
//            $appInfo->json_doc_arr = $document_query;
//
//            header('content-type: application/json; charset=utf-8');
//            header("access-control-allow-origin: *");
//            echo json_encode($appInfo, true);
//
////            dd($appInfo->toarray());
//        } else {
//            $arr = [
//                'status' => false,
//                'response' => "Your $ip  is not allow for the API. please contact with system admin"
//            ];
//            echo json_encode($arr);
//        }
//
////        return response()->json(['status'=> true,'resutl'=> 1]);
//    }


//    public function getToken(Request $request)
//    {
//
//        try {
//
//            $user = null;
//            $password = null;
//
//            if (!empty($request->user)) {
//                $user = $request->user;
//            } else {
//                return $this->errorReturn('400', 'user');
//            }
//
//            if (!empty($request->password)) {
//                $password = $request->password;
//            } else {
//                return $this->errorReturn('400', 'password', $user);
//            }
//
//            $tokenUser = ApiTokenUser::where('user', $user)->where('password', md5($password))->first();
//
//            if (count($tokenUser) > 0) {
//
//                $tokenList = ApiTokenList::create([
//                    'token_user_id' => $tokenUser->id,
//                    'token' => $this->generateToken(),
//                    'valid_till' => Carbon::now()->addDay(1)->toDateTimeString(),
//                    'ref_data' => '',
//                ]);
//
//                if (!empty($tokenList->token)) {
//
//                    return response()->json([
//                        'status' => 'success',
//                        'status_code' => 200,
//                        'message' => "Token Generated",
//                        'token' => $tokenList->token,
//                        'validity_till' => $tokenList->valid_till,
//                        'data' => [],
//                    ]);
//
//                } else {
//
//                    return $this->errorReturn('500');
//                }
//
//
//            } else {
//
//                return $this->errorReturn('401', '', $user);
//            }
//
//        } catch (\Exception $e) {
//
//            return $this->errorReturn($e->getCode(), '', '', $e->getMessage());
//        }
//    }


//    private function errorReturn($statusCode, $fieldName = '', $ref_data = '', $message = 'Sorry, There is a error')
//    {
//
//        switch ($statusCode) {
//            case '400' :
//                $message = 'The request is invalid as without authentication info.';
//                if ($fieldName == 'user') {
//                    $message = 'The request is invalid for empty user field';
//                }
//                if ($fieldName == 'password') {
//                    $message = 'The request is invalid for empty password for user : ' . $ref_data;
//                }
//                break;
//
//            case '401' :
//                $message = 'Authorization Required, user and password not matched for this user :' . $ref_data;
//                break;
//
//            case '404' :
//                $message = 'The URI requested is invalid ';
//                break;
//
//            case '500' :
//                $message = 'There is a internal failure . Please try again';
//                break;
//
//            default :
//                break;
//        }
//
//        return response()->json([
//            'status' => 'error',
//            'status_code' => $statusCode,
//            'message' => $message,
//        ]);
//
//    }


//    private function generateToken()
//    {
//
//        return hash('sha256', sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x', mt_rand(10, 0xffff), mt_rand(0, 0xffff), mt_rand(11, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)));
//    }

    // BIDA MOHA Integration
//    public function mohaRequestBk($process_type_id, $app_id)
//    {
//        if (!SecurityClearanceACL::getAccsessRight('E')) {
//            die('You have no access right! Please contact system administration for more information.');
//        }
//        try {
////        $process_type_id = Encryption::decodeId($process_type_id);
//            $process_type_id = 2;
//            $app_id = Encryption::decodeId($app_id);
//
//            $queue_list_check = MohaApiQueue::where('ref_id', $app_id)->where('status', '!=', -1)->count();
//            if ($queue_list_check > 0) {
////            Session::flash('error', 'Already exist in Queue!');
////            return redirect()->back();
//                $result = 'Already exist in Queue!';
//                $data = ['responseCode' => 0, 'data' => $result];
//                return $data;
//            }
//            DB::beginTransaction();
////            DB::connection()->enableQueryLog();
//
//
//            $appInfo = ProcessList::leftJoin('wp_apps as apps', 'apps.id', '=', 'process_list.ref_id')
//                ->leftjoin('company_info', 'process_list.company_id', '=', 'company_info.id')
//                ->where('process_list.ref_id', $app_id)
//                ->where('process_list.status_id', 25)
//                ->where('process_list.process_type_id', $process_type_id)
//                ->first([
//                    'process_list.department_id as DEPT_ID',
//                    'process_list.process_type_id as SERVICE_ID',
//                    'process_list.status_id as STATUS_ID',
//                    'process_list.tracking_no as TRACKING_NO',
//
//                    'process_list.company_id',
//                    'company_info.company_name as ORG_NAME',
//                    'apps.duration_start_date as PERIOD_VALIDITY',
//                    'apps.ref_app_tracking_no as VISA_REF_NO',
//                    'apps.approved_duration_start_date as PERMIT_EFCT_DATE',
//                    'apps.last_vr as VISA_PERMIT',
//                    'apps.id as application_id',
//                    'apps.ref_app_tracking_no as VISA_REF_NO_ONLINE',
//                    'apps.manually_approved_wp_no as VISA_REF_NO',
//                    'apps.certificate_link as json_cert_arr',
//                    'apps.nature_of_business as ORG_ACTIVITY',
//                    'apps.auth_capital as AUTH_CAPITAL',
//                    'apps.paid_capital as PAID_CAPITAL',
//                    'apps.received_remittance as REMITTANCE',
//                    'apps.emp_date_of_birth',
//                    'apps.date_of_arrival as ARRIVAL_DATE',
//                    'apps.emp_designation as POST_EMP_NAME',
//                    'apps.emp_name as EXP_NAME',
//                    'apps.emp_nationality_id',
//                    'apps.emp_passport_no as PASSPORT_NO',
//                    'apps.pass_issue_date as ISSUE_DATE',
//                    'apps.place_of_issue as ISSUE_PLACE',
//                    'apps.pass_expiry_date as EXPR_DATE',
//                    'apps.approved_duration_start_date as FIRST_APPOINT_DATE',
//                    'apps.duration_start_date as EFCT_START_DATE',
//                    'apps.duration_end_date as EFCT_END_DATE',
//                    'apps.desired_duration as VISA_VALIDITY',
//                    'apps.brief_job_description as POST_EMP_JOB_DESC',
//                    'apps.approved_desired_duration as PERIOD_VALIDITY',
//                    'apps.basic_payment_type_id',
//                    'apps.basic_local_amount as BASIC_SALARY',
//                    'apps.basic_local_currency_id',
//                    'apps.overseas_payment_type_id',
//                    'apps.overseas_local_amount as OVERSEAS_ALLOWANCE',
//                    'apps.overseas_local_currency_id',
//                    'apps.house_payment_type_id',
//                    'apps.house_local_amount as HOUSE_RENT',
//                    'apps.house_local_currency_id',
//                    'apps.work_permit_type',
//
//                    ///
//                    'apps.conveyance_payment_type_id',
//                    'apps.conveyance_local_amount as CONVEYANCE_ALLOWANCE',
//                    'apps.conveyance_local_currency_id',
//
//                    'apps.medical_payment_type_id',
//                    'apps.medical_local_amount as MEDICAL_ALLOWANCE',
//                    'apps.medical_local_currency_id',
//
//
//                    'apps.ent_payment_type_id',
//                    'apps.ent_local_amount as ENTERTAINMENT_ALLOWANCE',
//                    'apps.ent_local_currency_id',
//
//                    'apps.bonus_payment_type_id',
//                    'apps.bonus_local_amount as ANNUAL_BONUS',
//                    'apps.bonus_local_currency_id',
//                    'apps.other_benefits as OTHER_BENEFIT',
//
//                    'apps.investor_photo as AUTH_IMAGE',
//
//
//                    'apps.certificate_link as json_cert_arr',
//                    'apps.local_executive as MP_LOC_EXECUTIVE',
//                    'apps.local_stuff as MP_LOC_STAFF',
//                    'apps.local_total as MP_LOC_TOTAL',
//                    'apps.foreign_executive as FOR_LOC_EXECUTIVE',
//                    'apps.foreign_stuff as FOR_LOC_STAFF',
//                    'apps.foreign_total as FOR_LOC_TOTAL',
//                    'apps.manpower_total as GRAND_TOTAL',
//                    'apps.manpower_local_ratio as locRatio',
//                    'apps.manpower_foreign_ratio as forRatio',
//
//                ]);
////            $queries = \DB::getQueryLog();
//            if ($appInfo == null) {
//                $arr = [
//                    'status' => false,
//                    'response' => "Your tracking no was not found.  "
//                ];
//                echo json_encode($arr);
//                exit;
//            }
//            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');
//            $paymentMethods = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
//
//            $WP_visaTypes = ProcessWiseVisaTypes::leftJoin('visa_types', 'visa_types.id', '=',
//                'process_wise_visa_type.visa_type_id')
//                ->where([
//                    'process_wise_visa_type.process_type_id' => 2,
//                    'process_wise_visa_type.other_significant_id' => 1,
//                    'process_wise_visa_type.status' => 1,
//                    'process_wise_visa_type.is_archive' => 0
//                ])
//                ->orderBy('process_wise_visa_type.id', 'asc')
//                ->select('visa_types.type', 'visa_types.id')
//                ->lists('visa_types.type', 'visa_types.id');
//
//
//            $getBasicInfo = BasicInformation::where('company_id', $appInfo->company_id)->where('is_approved', 1)->first();
//            $fileNames = explode('/', $appInfo->json_cert_arr);
//
//            $fileFathCertificate = '';
//            for ($i = 0; $i < count($fileNames); $i++) {
//                if ($i > 2) { //for cdn certificate
//                    $fileFathCertificate .= '/' . $fileNames[$i];
//                }
//            }
//            $countryCode = Countries::where('id', $appInfo->emp_nationality_id)->first();
//
//            $mohaCountryInfo = $this->getMohaCountryInfo($countryCode->iso);
//
//            $appInfo->NATIONALITY = isset($mohaCountryInfo) ? $mohaCountryInfo["code"] : "0000";
//            $appInfo->json_cert_arr1 = $fileFathCertificate;
//            $appInfo->ORG_NAME = $getBasicInfo->company_name;
//            $appInfo->ORG_HOUSE_NO = $getBasicInfo->office_address;
//            $appInfo->ORG_FLAT_NO = '';
//            $appInfo->ORG_ROAD_NO = '';
////            $appInfo->ORG_DISTRICT = $getBasicInfo->office_district_id;
//            $appInfo->ORG_THANA = $getBasicInfo->office_thana_id;
//            $appInfo->ORG_DISTRICT = '';
//            $appInfo->ORG_THANA = '';
//            $appInfo->ORG_POST_CODE = $getBasicInfo->office_post_code;
//            $appInfo->ORG_PHONE = $getBasicInfo->office_telephone_no;
//            $appInfo->ORG_EMAIL = $getBasicInfo->office_email;
//            $appInfo->INDUSTRY_TYPE = $getBasicInfo->business_sub_sector_id;
//            $appInfo->INDUSTRY_TYPE = $getBasicInfo->organization_type_id;
//            $appInfo->ORG_TYPE = $getBasicInfo->organization_type_id;
////            dd($paymentMethods[$appInfo->basic_payment_type_id]);
//            $appInfo->VISA_TYPE = isset($WP_visaTypes[$appInfo->work_permit_type]) ? $WP_visaTypes[$appInfo->work_permit_type] : '';
//
//            if ($appInfo->DEPT_ID == 1) {
//                $appInfo->VISA_CATEGORY = 18;
//            } elseif ($appInfo->DEPT_ID == 2) {
//                $appInfo->VISA_CATEGORY = 14;
//            } else {
//                $appInfo->VISA_CATEGORY = '';
//            }
//
//
//            $appInfo->PLOC1 = isset($paymentMethods[$appInfo->basic_payment_type_id]) ? $paymentMethods[$appInfo->basic_payment_type_id] : '';
//            $appInfo->PLOC2 = isset($paymentMethods[$appInfo->overseas_payment_type_id]) ? $paymentMethods[$appInfo->overseas_payment_type_id] : '';
//            $appInfo->PLOC3 = isset($paymentMethods[$appInfo->house_payment_type_id]) ? $paymentMethods[$appInfo->house_payment_type_id] : '';
//            $appInfo->PLOC4 = isset($paymentMethods[$appInfo->conveyance_payment_type_id]) ? $paymentMethods[$appInfo->conveyance_payment_type_id] : '';
//            $appInfo->PLOC5 = isset($paymentMethods[$appInfo->medical_payment_type_id]) ? $paymentMethods[$appInfo->medical_payment_type_id] : '';
//            $appInfo->PLOC6 = isset($paymentMethods[$appInfo->ent_payment_type_id]) ? $paymentMethods[$appInfo->ent_payment_type_id] : '';
//            $appInfo->PLOC7 = isset($paymentMethods[$appInfo->bonus_payment_type_id]) ? $paymentMethods[$appInfo->bonus_payment_type_id] : '';
//
//            $appInfo->CURRENCY1 = isset($currencies[$appInfo->basic_local_currency_id]) ? $currencies[$appInfo->basic_local_currency_id] : '';
//            $appInfo->CURRENCY2 = isset($currencies[$appInfo->overseas_local_currency_id]) ? $currencies[$appInfo->overseas_local_currency_id] : '';
//            $appInfo->CURRENCY3 = isset($currencies[$appInfo->house_local_currency_id]) ? $currencies[$appInfo->house_local_currency_id] : '';
//
//            $appInfo->CURRENCY4 = isset($currencies[$appInfo->conveyance_local_currency_id]) ? $currencies[$appInfo->conveyance_local_currency_id] : '';
//            $appInfo->CURRENCY5 = isset($currencies[$appInfo->medical_local_currency_id]) ? $currencies[$appInfo->medical_local_currency_id] : '';
//            $appInfo->CURRENCY6 = isset($currencies[$appInfo->ent_local_currency_id]) ? $currencies[$appInfo->ent_local_currency_id] : '';
//            $appInfo->CURRENCY7 = isset($currencies[$appInfo->bonus_local_currency_id]) ? $currencies[$appInfo->bonus_local_currency_id] : '';
//
//
//            $appInfo->ORG_NAMES = $getBasicInfo->company_name;
//            $appInfo->ORG_HOUSE_NOS = $getBasicInfo->office_address;
////            $appInfo->ORG_DISTRICTS = $getBasicInfo->office_district_id;
////            $appInfo->ORG_THANAS = $getBasicInfo->office_thana_id;
//
//            $appInfo->ORG_DISTRICTS = '';
//            $appInfo->ORG_THANAS = '';
//
//            $appInfo->ORG_POST_OFFICE = $getBasicInfo->office_post_office;
//            $appInfo->ORG_FAX_NO = $getBasicInfo->office_fax_no;
//            $appInfo->AUTH_MOBILE = $getBasicInfo->auth_mobile_no;
//            $appInfo->ENTRY_DT = $getBasicInfo->approved_date;
//            $appInfo->AUTH_FULL_NAME = $getBasicInfo->auth_full_name;
//            $appInfo->AUTH_EMAIL = $getBasicInfo->auth_email;
//            if ($appInfo->VISA_REF_NO == null) {
//                $appInfo->VISA_REF_NO = $appInfo->VISA_REF_NO_ONLINE;
//            }
//
////            $appInfo->MP_LOC_EXECUTIVE = $getBasicInfo->local_executive;
////            $appInfo->MP_LOC_STAFF = $getBasicInfo->local_stuff;
////            $appInfo->MP_LOC_TOTAL = $getBasicInfo->local_total;
////            $appInfo->FOR_LOC_EXECUTIVE = $getBasicInfo->foreign_executive;
////            $appInfo->FOR_LOC_STAFF = $getBasicInfo->foreign_stuff;
////            $appInfo->FOR_LOC_TOTAL = $getBasicInfo->foreign_total;
////            $appInfo->GRAND_TOTAL = $getBasicInfo->manpower_total;
////            $appInfo->locRatio = $getBasicInfo->manpower_local_ratio;
////            $appInfo->forRatio = $getBasicInfo->manpower_foreign_ratio;
//
//
//            $appInfo->EXP_HOME_COUNTRY = '';
//            $appInfo->EXP_HOUSE_NO = '';
//            $appInfo->EXP_HOUSE_NO = '';
//            $appInfo->EXP_FLAT_NO = '';
//            $appInfo->EXP_ROAD_NO = '';
//            $appInfo->EXP_POST_CODE = '';
//            $appInfo->EXP_POST_OFFICE = '';
//            $appInfo->EXP_PHONE = '';
//            $appInfo->EXP_DISTRICT = '';
//            $appInfo->EXP_FAX_NO = '';
//            $appInfo->EXP_EMAIL = '';
//            $appInfo->DOB = $appInfo->emp_date_of_birth;
//            $appInfo->MARITAL_STATUS = '';
//            $appInfo->TRAVEL_VISA_CATE = '';
//            $appInfo->EMP_JUSTIFICATION = '';
//            $appInfo->ABM1 = '';
//            $appInfo->ABC1 = '';
//            $appInfo->SALARY_REMARKS = '';
//            $appInfo->ORG_FLAT_NOS = '';
//            $appInfo->ORG_ROAD_NOS = '';
////            $appInfo->ORG_POST_OFFICES = '';
//
//            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
//                ->where('app_documents.ref_id', $appInfo->application_id)
//                ->where('app_documents.process_type_id', 2)
//                ->where('app_documents.doc_file_path', '!=', '')
//                ->get([
//                    'app_documents.id as document_id', 'app_documents.doc_name as DOC_NAME', 'app_documents.doc_file_path as FILE_NAME'
//                ]);
//            $appInfo->json_doc_arr = $document_query;
//
//            header('content-type: application/json; charset=utf-8');
//            header("access-control-allow-origin: *");
//
//            $request_data['trackingNumber'] = $appInfo->TRACKING_NO;
//            $request_data['data'] = $appInfo;
//
//            // $insert = MohaApiQueue::create([
//            //     'type' => 'SUBMISSION_REQUEST',
//            //     'ref_id' => $appInfo->application_id,
//            //     'request_json' => json_encode($request_data)
//            // ]);
//            $insert = MohaApiQueue::firstOrNew([
//                'ref_id' => $appInfo->application_id
//            ]);
//
//            $insert->type = 'SUBMISSION_REQUEST';
//            $insert->request_json = json_encode($request_data);
//
//            $insert->ref_id = $appInfo->application_id;
//            $insert->status = 0;
//            $insert->response_json = '';
//            $insert->status_check_response = '';
//            $insert->moha_tracking_id = '';
//            $insert->certificate = '';
//            $insert->fl_certificate = '';
//
//
//            $insert->save();
//
//            DB::commit();
//            $result = 'Application Send to MoHA Successfully!';
//            $data = ['responseCode' => 1, 'data' => $result];
//            return $data;
////        Session::flash('success', 'Store in Queue Successfully!');
////        return redirect()->back();
//        } catch (\Exception $e) {
//            Session::flash('error', $e->getMessage() . $e->getLine());
//            return redirect()->back();
//        }
//    }


    // Get token for authorization
//    public function getMohaToken()
//    {
//        // Get credentials from env
//        $moha_idp_url = env('moha_idp_url');
//        $moha_client_id = env('moha_client_id');
//        $moha_client_secret = env('moha_client_secret');
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
//            'client_id' => $moha_client_id,
//            'client_secret' => $moha_client_secret,
//            'grant_type' => 'client_credentials'
//        )));
//        curl_setopt($curl, CURLOPT_URL, "$moha_idp_url");
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        $result = curl_exec($curl);
//        if (!$result) {
//            $data = ['responseCode' => 0, 'msg' => 'Area API connection failed!'];
//            return response()->json($data);
//        }
//        curl_close($curl);
//        $decoded_json = json_decode($result, true);
//        $token = $decoded_json['access_token'];
//
//        return $token;
//    }

    // Get Moha country info using iso
//    public function getMohaCountryInfo($countryIso)
//    {
//        $moha_api_url = env('moha_api_url');
//
//        // Get token for API authorization
//        $token = $this->getMohaToken();
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => $moha_api_url . $countryIso . "/moha",
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_SSL_VERIFYHOST => 0,
//            CURLOPT_SSL_VERIFYPEER => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "GET",
//            CURLOPT_HTTPHEADER => array(
//                "Authorization: Bearer $token",
//                "Content-Type: application/json"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
//        $decoded_response = json_decode($response, true);
//        return $decoded_response["data"];
//    }


// Get token for authorization
//    public function getMohaCommnToken()
//    {
//        // Get credentials from env
//        $moha_idp_url = env('moha_commn_idp_url');
//        $moha_client_id = env('moha_commn_client');
//        $moha_client_secret = env('moha_commn_secret');
//
//        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
//            'client_id' => $moha_client_id,
//            'client_secret' => $moha_client_secret,
//            'grant_type' => 'client_credentials'
//        )));
//        curl_setopt($curl, CURLOPT_URL, "$moha_idp_url");
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
//        $result = curl_exec($curl);
//        if (!$result) {
//            $data = ['responseCode' => 0, 'msg' => 'MoHA Commn Token connection failed!'];
//            return response()->json($data);
//        }
//        curl_close($curl);
//        $decoded_json = json_decode($result, true);
//        $token = $decoded_json['access_token'];
//
//        return $token;
//    }

//    public function getApplicationStatusFromMohaBk($process_type_id, $app_id){
//        $process_type_id = Encryption::decodeId($process_type_id);
//        $app_id = Encryption::decodeId($app_id);
//
//        $queue_list_check = MohaApiQueue::where('ref_id', $app_id)->first();
//        $tracking_no = json_decode($queue_list_check->request_json);
//        $tracking_no = $tracking_no->data->TRACKING_NO;
//
//
//        $token = $this->getMohaCommnToken();
//
//
//        $moha_commn_api_url = env('moha_commn_api_url') . 'application-status';
//
//        $curl = curl_init();
//
//        curl_setopt_array($curl, array(
//            CURLOPT_URL => $moha_commn_api_url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_ENCODING => "",
//            CURLOPT_MAXREDIRS => 10,
//            CURLOPT_TIMEOUT => 0,
//            CURLOPT_FOLLOWLOCATION => true,
//            CURLOPT_SSL_VERIFYHOST => 0,
//            CURLOPT_SSL_VERIFYPEER => 0,
//            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST => "POST",
//            CURLOPT_POSTFIELDS => "{\n    \"tracking_no\":\"$tracking_no\"\n}",
//            CURLOPT_HTTPHEADER => array(
//                "Authorization: Bearer $token",
//                "Content-Type: application/json"
//            ),
//        ));
//
//        $response = curl_exec($curl);
//
//        curl_close($curl);
//
//
//        $decoded_json = json_decode($response);
////print_r($decoded_json);
//        if (isset($decoded_json->data->data->resonse->error)) {
//            $queue_list_check->status_check_response = json_encode($decoded_json->data->data->resonse->error);
//            $queue_list_check->moha_tracking_id = '';
//            $queue_list_check->certificate = '';
//            $queue_list_check->fl_certificate = '';
//// Session::flash('error', 'Error in the response: '. json_encode($decoded_json->data->data->resonse->error));
//            $result = 'Error in the response: ' . json_encode($decoded_json->data->data->resonse->error);
//            $queue_list_check->save();
//            $data = ['responseCode' => 0, 'data' => $result];
//
//            return $data;
//
//        } else {
//
//            $queue_list_check->status_check_response = json_encode($decoded_json->data->data->resonse->result);
//            $queue_list_check->moha_tracking_id = $decoded_json->data->data->resonse->result->moha_tracking_id;
//            $queue_list_check->certificate = $decoded_json->data->data->resonse->result->certificate;
//            $queue_list_check->fl_certificate = $decoded_json->data->data->resonse->result->fl_certificate;
//
//            $status_id = DB::select(DB::raw('select id from security_clearance_status where process_type_id=2 and mh_status_id = ' . $decoded_json->data->data->resonse->result->status_id));
//
//            if (count($status_id) > 0) {
//                $queue_list_check->status = $status_id[0]->id;
//            }
////Session::flash('success', 'Status update Successfully! Response/n: '. json_encode($decoded_json->data->data->resonse->result)."");
//            $result = 'Status update Successfully! Response/n: ' . json_encode($decoded_json->data->data->resonse->result);
//            $queue_list_check->save();
//            $data = ['responseCode' => 1, 'data' => $result];
//            return $data;
//
//        }
//        $queue_list_check->save();
//
////return redirect()->back();
//
//// ================== status response end ===========================
//
//
//    }



}
