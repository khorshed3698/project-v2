<?php

namespace App\Modules\WorkPermitExtension\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class WorkPermitExtension extends Model {
    protected $table = 'wpe_apps';
    protected $fillable = array(
        'id',
        'certificate_link',
        'app_type_id',
        'app_type_mapping_id',
        'work_permit_type',
        'last_work_permit',
        'ref_app_tracking_no',
        'ref_app_approve_date',
        'manually_approved_wp_no',
        'issue_date_of_first_wp',
        'duration_start_date',
        'approved_duration_start_date',
        'duration_end_date',
        'approved_duration_end_date',
        'desired_duration',
        'approved_desired_duration',
        'duration_amount',
        'nature_of_business',
        'received_remittance',
        'auth_capital',
        'paid_capital',
//        'travel_history',
//        'th_visit_with_emp_visa',
//        'th_emp_work_permit',
//        'th_emp_tin_no',
//        'th_emp_wp_no',
//        'th_emp_org_name',
//        'th_emp_org_address',
//        'th_org_district_id',
//        'th_org_thana_id',
//        'th_org_post_office',
//        'th_org_post_code',
//        'th_org_telephone_no',
//        'th_org_email',
//        'th_first_work_permit',
//        'th_resignation_letter',
//        'th_release_order',
//        'th_last_extension',
//        'th_last_work_permit',
//        'th_income_tax',

        'local_executive',
        'local_stuff',
        'local_total',
        'foreign_executive',
        'foreign_stuff',
        'foreign_total',
        'manpower_total',
        'manpower_local_ratio',
        'manpower_foreign_ratio',

        'courtesy_service',
        'courtesy_service_reason',
        'office_division_id', // BD office address start
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_code',
        'office_address',
        'office_telephone_no',
        'office_mobile_no',
        'office_fax_no',
        'office_email', // BD office address end
        'emp_passport_no',
        'emp_personal_no',
        'emp_surname',
        'emp_name',
        'emp_designation',
        'brief_job_description',
        'emp_given_name',
        'emp_nationality_id',
        'investor_photo',
        'emp_date_of_birth',
        'emp_place_of_birth',
        'pass_issue_date',
        'pass_expiry_date',
        'place_of_issue',
        'basic_payment_type_id',
        'basic_local_amount',
        'basic_local_currency_id',
        'overseas_payment_type_id',
        'overseas_local_amount',
        'overseas_local_currency_id',
        'house_payment_type_id',
        'house_local_amount',
        'house_local_currency_id',
        'conveyance_payment_type_id',
        'conveyance_local_amount',
        'conveyance_local_currency_id',
        'medical_payment_type_id',
        'medical_local_amount',
        'medical_local_currency_id',
        'ent_payment_type_id',
        'ent_local_amount',
        'ent_local_currency_id',
        'bonus_payment_type_id',
        'bonus_local_amount',
        'bonus_local_currency_id',
        'other_benefits',
        'cb_list',
        'auth_name',
        'desired_start_date',
        'desired_end_date',
        'tin_number',
        'auth_email',
        'auth_cell_number',
        'application_date',
        'approve_date',
        'accept_terms',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
