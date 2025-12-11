<?php

namespace App\Modules\Waiver\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Waiver extends Model {

    protected $table = 'waiver_apps';

    protected $fillable = array(
        'id',
        'certificate_link',
        'ref_app_tracking_no',
        'ref_app_approve_date',
        'office_type',
        'c_company_name',
        'c_origin_country_id',
        'c_country_id',
        'c_flat_apart_floor',
        'c_org_type',
        'c_house_plot_holding',
        'c_street',
        'c_post_zip_code',
        'c_telephone',
        'c_city',
        'c_email',
        'c_fax',
        'c_state_province',
        'c_major_activity_brief',
        'local_company_name',

        'ex_office_division_id', // BD office address start
        'ex_office_district_id',
        'ex_office_thana_id',
        'ex_office_post_office',
        'ex_office_post_code',
        'ex_office_address',
        'ex_office_telephone_no',
        'ex_office_mobile_no',
        'ex_office_fax_no',
        'ex_office_email', // BD office address end
        'activities_in_bd',
        'first_commencement_date',
        'operation_target_date',
        'duration_amount',
        'local_executive',
        'local_stuff',
        'local_total',
        'foreign_executive',
        'foreign_stuff',
        'foreign_total',
        'manpower_total',
        'manpower_local_ratio',
        'manpower_foreign_ratio',
        'est_initial_expenses',
        'est_monthly_expenses',
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
        'is_archive',
        'accept_terms',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'approved_date',
        'payment_date'
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
