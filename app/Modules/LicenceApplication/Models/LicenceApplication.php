<?php 

namespace App\Modules\LicenceApplication\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class LicenceApplication extends Model {
    protected $table = 'br_apps';
    protected $fillable = [
        'id',
        'company_id',
        'reg_no',
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
