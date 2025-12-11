<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class Remittance extends Model
{
    protected $table = 'ra_apps';

    protected $fillable = array(
        'id',
        'certificate_link',
        'remittance_type_id',
        'company_name',
        'company_name_bn',
        'origin_country_id',
        'organization_type_id',
        'organization_status_id',
        'ownership_status_id',
        'business_sector_id',
        'business_sector_others',
        'business_sub_sector_id',
        'business_sub_sector_others',
        'major_activities',
        'ceo_country_id',
        'ceo_dob',
        'ceo_nid',
        'ceo_passport_no',
        'ceo_designation',
        'ceo_full_name',
        'ceo_district_id',
        'ceo_thana_id',
        'ceo_post_code',
        'ceo_address',
        'ceo_telephone_no',
        'ceo_mobile_no',
        'ceo_father_name',
        'ceo_email',
        'ceo_mother_name',
        'ceo_fax_no',
        'ceo_spouse_name',
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
        'organization_name',
        'organization_address',
        'property_city',
        'property_post_code',
        'property_country_id',
        'effective_agreement_date',
        'project_status_id',
        'agreement_duration_from',
        'agreement_duration_type',
        'agreement_duration_to',
        'agreement_total_duration',
        'schedule_of_payment',
        'agreement_amount_type',
        'total_agreement_amount_bdt',
        'total_agreement_amount_usd',
        'percentage_of_sales',
        'period_from',
        'period_to',
        'total_period',
        'product_name_capacity',
        'marketing_of_products_local',
        'marketing_of_products_foreign',
        'present_status_id',
        'int_property_attachment',
        'prev_sales_year_from',
        'prev_sales_year_to',
        'sales_value_bdt',
        'sales_value_usd',
        'usd_conv_rate',
        'tax_amount_bdt',
        'total_fee',
        'total_fee_percentage',
        'proposed_remittance_type',
        'proposed_amount_bdt',
        'proposed_amount_usd',
        'proposed_exp_percentage',
        'proposed_sub_total_bdt',
        'proposed_sub_total_usd',
        'other_sub_total_bdt',
        'other_sub_total_usd',
        'other_sub_total_percentage',
        'total_remittance_percentage',
        'brief_background',
        'local_bank_id',
        'local_branch',
        'local_bank_address',
        'local_bank_city',
        'local_bank_post_code',
        'local_bank_country_id',
        'accept_terms'
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