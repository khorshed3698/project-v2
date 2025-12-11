<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Etin extends Model {
    protected $table = 'etin_apps';
    protected $fillable = [
        'id',
        'taxpayer_status',
        'organization_type_id',
        'single_licence_ref_id',
        'reg_type',
        'existing_tin_no',
        'ref_no',
        'etin_number',
        'account_number',
        'amount',
        'gf_payment_id',
        'sf_payment_id',
        'add_file_path',
        'main_source_income',
        'main_source_income_location',
        'company_id',
        'company_name',
        'incorporation_certificate_number',
        'incorporation_certificate_date',
        'ceo_designation',
        'ceo_full_name',
        'ceo_email',
        'ceo_country_id',
        'ceo_mobile_no',
        'ceo_fax_no',
        'ceo_thana_id',
        'ceo_district_id',
        'ceo_post_code',
        'ceo_address',
        'reg_office_country_id',
        'office_thana_id',
        'office_district_id',
        'office_post_code',
        'office_address',
        'other_address_country_id',
        'other_address_thana_id',
        'other_address_district_id',
        'other_address_post_code',
        'other_address',
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
