<?php

namespace App\Modules\ProjectOfficeNew\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ProjectOfficeNew extends Model {

    protected $table = 'pon_apps';

    protected $fillable = [
        'id',
        'certificate_link',
        'project_name',
        'poa_co_division_id',
        'poa_co_district_id',
        'poa_co_thana_id',
        'poa_co_post_office',
        'poa_co_post_code',
        'poa_co_address',
        'poa_co_telephone_no',
        'poa_co_mobile_no',
        'poa_co_fax_no',
        'poa_co_email',
        'project_amount',
        'period_start_date',
        'period_end_date',
        'period_validity',
        'duration_amount',
        'approved_duration_start_date',
        'approved_duration_end_date',
        'approved_desired_duration',
        'approved_duration_amount',
        'authorized_name',
        'authorized_designation',
        'authorized_org_dep',
        'authorized_address',
        'authorized_mobile_no',
        'authorized_email',
        'authorized_letter',
        'ministry_name',
        'ministry_address',
        'local_technical',
        'local_general',
        'local_total',
        'foreign_technical',
        'foreign_general',
        'foreign_total',
        'manpower_total',
        'auth_full_name',
        'auth_designation',
        'auth_email',
        'auth_mobile_no',
        'auth_image',
        'company_name',
        'company_name_bn',
        'service_type',
        'reg_commercial_office',
        'ownership_status_id',
        'organization_type_id',
        'major_activities',
        'ceo_full_name',
        'ceo_dob',
        'ceo_spouse_name',
        'ceo_designation',
        'ceo_country_id',
        'ceo_district_id',
        'ceo_thana_id',
        'ceo_post_code',
        'ceo_city',
        'ceo_state',
        'ceo_address',
        'ceo_telephone_no',
        'ceo_mobile_no',
        'ceo_fax_no',
        'ceo_email',
        'ceo_father_name',
        'ceo_mother_name',
        'ceo_nid',
        'ceo_passport_no',
        'ceo_gender',
        'office_division_id',
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_code',
        'office_address',
        'office_telephone_no',
        'office_mobile_no',
        'office_fax_no',
        'office_email',
        'factory_district_id',
        'factory_thana_id',
        'factory_post_office',
        'factory_post_code',
        'factory_address',
        'factory_telephone_no',
        'factory_mobile_no',
        'factory_fax_no',
        'factory_email',
        'factory_mouja',
        'shadow_file_path',
        'conditional_approved_file',
        'conditional_approved_remarks',
        'gf_payment_id',
        'sf_payment_id',
        'approved_date',
        'payment_date',
        'accept_terms',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

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
