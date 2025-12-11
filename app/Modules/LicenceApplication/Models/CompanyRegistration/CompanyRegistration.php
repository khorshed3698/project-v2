<?php
/**
 * Created by PhpStorm.
 * User: Shakil
 * Date: 12/12/2018
 * Time: 12:02 PM
 */

namespace App\Modules\LicenceApplication\Models\CompanyRegistration;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CompanyRegistration extends Model
{

    protected $table = 'cr_apps';

    protected $fillable = [
        'company_id',
        'company_name',
        'company_name_bn',
        'country_of_origin_id',
        'ownership_status_id',
        'business_sector_id',
        'sf_payment_id',
        'gf_payment_id',
        'account_number',
        'amount',
        'ref_no',
        'reg_no',
        'add_file_path',
        'business_sub_sector_id',
        'major_activities',
        'organization_type_id',
        'organization_status_id',
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
        'business_objective',
        'authorized_capital',
        'number_of_shares',
        'quorum_bod_meeting',
        'duration_md',
        'min_no_director',
        'max_no_director',
        'quorum_agm_egm ',
        'duration_chairman',
        'value_each_share',
        'q_shares_each',
        'q_shares_value',
        'q_shares_witness_agreement',
        'q_shares_witness_name',
        'q_shares_witness_address',
        'witnesses_name',
        'witnesses_phone',
        'witnesses_address',
        'witnesses_national_id',
        'declaration_signed_country',
        'declaration_signed_full_name',
        'declaration_signed_house',
        'declaration_signed_mobile',
        'declaration_signed_email',
        'declaration_signed_designation',
        'declaration_signed_district',
        'declaration_signed_zip_code',
        'declaration_signed_telephone',
        'declaration_signed_fax',
        'declaration_signed_momorandum',
        'declaration_signed_article'
    ];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}