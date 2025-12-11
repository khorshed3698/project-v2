<?php

namespace App\Modules\LicenceApplication\Models\BankAccount;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model {
    protected $table = 'ba_apps';
    protected $fillable = [
        'id',
        'bank_id',
        'single_licence_ref_id',
        'bank_branch_id',
        'sf_payment_id',
        'gf_payment_id',
        'account_number',
        'amount',
        'company_id',
        'company_name',
        'ref_no',
        'add_file_path',
        'acc_no',
        'branch_name',
        'company_name_bn',
        'organization_type_id',
        'organization_status_id',
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
        'office_division_id',
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_co',
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
        'acceptTerms',
        'country_of_origin_id',
        'ownership_status_id',
        'business_sector_id',
        'business_sub_sector_id',
        'major_activities',
        'factory_mouja',
        'tin_no',
        'trade_licence',
        'incorporation_no',
        'tin_file_name',
        'trade_file_name',
        'incorporation_file_name',
        'mem_association_file_name',
        'resolution_bank_file_name',
        'art_association_file_name',
        'list_share_holder_n_director_file_name',
        'ceo_city',
        'ceo_town',
        'ceo_state',
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
