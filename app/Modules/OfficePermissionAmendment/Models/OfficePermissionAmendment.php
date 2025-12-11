<?php

namespace App\Modules\OfficePermissionAmendment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class OfficePermissionAmendment extends Model {

    protected $table = 'opa_apps';

    protected $fillable = array(
        'id',
        'certificate_link',
        'is_approval_online',
        'ref_app_tracking_no',
        'ref_app_approve_date',
        'date_of_office_permission',
        'manually_approved_op_no',
        

        'effective_date',
        'approved_effective_date',
        'office_type',
        'local_company_name',
        'ex_office_division_id',
        'ex_office_district_id',
        'ex_office_thana_id',
        'ex_office_post_office',
        'ex_office_post_code',
        'ex_office_address',
        'ex_office_telephone_no',
        'ex_office_mobile_no',
        'ex_office_fax_no',
        'ex_office_email',
        'activities_in_bd',

        'n_office_type',
        'n_local_company_name',
        'n_ex_office_division_id',
        'n_ex_office_district_id',
        'n_ex_office_thana_id',
        'n_ex_office_post_office',
        'n_ex_office_post_code',
        'n_ex_office_address',
        'n_ex_office_telephone_no',
        'n_ex_office_mobile_no',
        'n_ex_office_fax_no',
        'n_ex_office_email',
        'n_activities_in_bd',

        'company_name', //basic info data start
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
        'factory_mouja', //basic info data end

        'auth_full_name',
        'auth_designation',
        'auth_email',
        'auth_mobile_no',
        'auth_image',
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
