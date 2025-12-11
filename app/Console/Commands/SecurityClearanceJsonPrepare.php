<?php

namespace App\Console\Commands;

use App\Libraries\CommonFunction;
use App\Modules\SecurityClearance\Models\SecurityClearance;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Console\Command;

class SecurityClearanceJsonPrepare extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security-clearance:json-prepare';
    protected $process_type_id = 2;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prepare json from security_clearance table for sending request to MOHA';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $get_pending_application_for_prepare_json = SecurityClearance::where('status', '=', 2)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get([
                'id',
                'ref_id',
            ]);

        $count_total_record = count($get_pending_application_for_prepare_json);

        if ($count_total_record > 0) {
            foreach ($get_pending_application_for_prepare_json as $row) {
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
                    ->where('process_list.ref_id', $row->ref_id)
                    ->where('process_list.status_id', 25)
                    ->where('process_list.process_type_id', $this->process_type_id)
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
                    $this->storeProblemInData($row->id, 'Application data not found!');
                }
                if (empty($appInfo->certificate_link)) {
                    $this->storeProblemInData($row->id, $appInfo->certificate_link . ' file not found!');
                }

                $request_id = $row->ref_id . rand(101, 999);

                $dataArray = [];
                $dataArray['project_code'] = "bida-oss";
                $dataArray['request_id'] = $request_id;
                $dataArray['depertment_name'] = CommonFunction::getDepartmentNameById($appInfo->dept_id);
                $dataArray['wp_tracking_no'] = $appInfo->wp_tracking_no;
                $visa_ref_no = !empty($appInfo->ref_app_tracking_no) ? $appInfo->ref_app_tracking_no : $appInfo->manually_approved_wp_no;

                $applicant_photo_path = 'uploads/' . $appInfo->investor_photo;

                if (!file_exists($applicant_photo_path)) {
                    $this->storeProblemInData($row->id, 'applicant_photo file not found!');
                }

                $dataArray['basic_info'] = [
                    'period_validity' => $appInfo->approved_desired_duration,
                    'visa_ref_no' => $visa_ref_no,
                    'visa_category' => $appInfo->visa_type,
                    'permit_efct_date' => !empty($appInfo->approved_duration_start_date) ? date('Y-m-d', strtotime($appInfo->approved_duration_start_date)) : '',
                    'applicant_photo' => url($applicant_photo_path),
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
                    $file_path = 'uploads/' . $data['doc_file_path'];
                    if (file_exists($file_path)) {
                        $att[$key]['file_public_path'] = url($file_path);
                    }else{
                        $this->storeProblemInData($row->id, $data['doc_name'] . ' file not found!');
                    }
                }
                $dataArray['document_list'] = $att;

                $insert = SecurityClearance::findOrFail($row->id);
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
            }
        } else {
            echo "No application ready to prepare JSON for security clearance!\n";
        }
    }

    private function storeProblemInData($id, $errorMessage='')
    {
        $insert = SecurityClearance::findOrFail($id);
        $insert->status = -1;
        $insert->json_prepare_remarks = $errorMessage;
        $insert->save();
        exit();
    }
}
