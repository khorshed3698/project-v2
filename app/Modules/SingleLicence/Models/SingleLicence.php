<?php 

namespace App\Modules\SingleLicence\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class SingleLicence extends Model {
    protected $table = 'sl_apps';
    protected $fillable = [
        'id',
        'company_id',
        'company_name',
        'company_name_bn',
        'country_of_origin_id',
        'organization_type_id',
        'organization_status_id',
        'ownership_status_id',
        'business_sector_id',
        'business_sub_sector_id',
        'major_activities',
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
        'ceo_father_name',
        'ceo_mother_name',
        'ceo_nid',
        'ceo_passport_no',
        'ceo_city',
        'ceo_state',
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
        'commercial_operation_date',
        'local_sales',
        'foreign_sales',
        'local_executive',
        'local_stuff',
        'local_total',
        'foreign_executive',
        'foreign_stuff',
        'foreign_total',
        'manpower_total',
        'manpower_local_ratio',
        'manpower_foreign_ratio',
        'local_fixed_ivst',
        'local_fixed_ivst_ccy',
        'foreign_fixed_ivst',
        'foreign_fixed_ivst_ccy',
        'total_fixed_ivst_single',
        'local_land_ivst',
        'local_land_ivst_ccy',
        'foreign_land_ivst',
        'foreign_land_ivst_ccy',
        'total_land_ivst',
        'local_machinery_ivst',
        'local_machinery_ivst_ccy',
        'local_building_ivst',
        'local_building_ivst_ccy',
        'foreign_machinery_ivst',
        'foreign_machinery_ivst_ccy',
        'total_machinery_ivst',
        'local_others_ivst',
        'local_others_ivst_ccy',
        'foreign_others_ivst',
        'foreign_others_ivst_ccy',
        'total_others_ivst',
        'local_wc_ivst',
        'local_wc_ivst_ccy',
        'foreign_wc_ivst',
        'foreign_wc_ivst_ccy',
        'total_wc_ivst',
        'total_fixed_ivst',
        'total_working_capital',
        'public_utility_service',
        'public_utility_service_other',
        'tin_number',
        'tin_file_path',
        'accept_terms',
        'is_draft',
        'is_approved',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'approved_date',
        'payment_date'
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
