<?php 

namespace App\Modules\BasicInformation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BasicInformation extends Model {
    protected $table = 'ea_apps';
    protected $fillable = [
        'id',
        'company_id',
        'company_name',
        'company_name_bn',
        'service_type',
        'applicant_type',
        'reg_commercial_office',
        'organization_type_id',
        'organization_type_other',
        'organization_status_id',
        'is_registered',
        'registered_by_id',
        'registered_by_other',
        'registration_other',
        'registration_copy',
        'registration_no',
        'registration_date',
        'ceo_full_name',
        'ceo_dob',
        'ceo_spouse_name',
        'ceo_designation',
        'ceo_country_id',
        'ceo_district_id',
        'ceo_thana_id',
        'ceo_post_code',
        'ceo_address',
        'ceo_telephone_no',
        'ceo_mobile_no',
        'ceo_fax_no',
        'ceo_email',
        'office_division_id', // BD office address start
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_code',
        'office_address',
        'office_telephone_no',
        'office_mobile_no',
        'office_fax_no',
        'office_email', // BD office address start
        'factory_district_id',
        'factory_thana_id',
        'factory_post_office',
        'factory_post_code',
        'factory_address',
        'factory_telephone_no',
        'factory_mobile_no',
        'factory_fax_no',
        'factory_email',
        'auth_full_name',
        'auth_designation',
        'auth_mobile_no',
        'auth_email',
        'auth_letter',
        'auth_image',
        'auth_signature',
        'acceptTerms',
        'application_date',
        'country_of_origin_id',
        'ownership_status_id',
        'ownership_status_other',
        'local_executive',
        'local_stuff',
        'local_total',
        'foreign_executive',
        'foreign_stuff',
        'foreign_total',
        'manpower_total',
        'manpower_local_ratio',
        'manpower_foreign_ratio',
        'incorporation_certificate_number',
        'incorporation_certificate_date',
        'business_sector_id',
        'business_sector_others',
        'business_sub_sector_id',
        'business_sub_sector_others',
        'major_activities',
        'factory_mouja',
        'ceo_city',
        'ceo_state',
        'ceo_father_name',
        'ceo_mother_name',
        'ceo_nid',
        'ceo_passport_no',
        'approve_date',
        'change_dept_reason',
        'is_draft',
        'is_locked',
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
