<?php

namespace App\Modules\LicenceApplication\Models;

use Illuminate\Database\Eloquent\Model;

class TradeLicence extends Model
{

    protected $table = 'tl_apps';


    protected $primaryKey = 'id';


    protected $fillable = [
        'country',
        'organization_name',
        'spouse_name',
        'applicant_name',
        'sf_payment_id',
        'ref_no',
        'tl_number',
        'add_file_path',
        'applicant_pic',
        'applicant_email',
        'applicant_father',
        'applicant_mother',
        'applicant_license_type',
        'applicant_dob',
        'business_name',
        'business_details',
        'business_holding',
        'business_address',
        'business_road',
        'business_ward',
        'business_market_name',
        'business_zone',
        'business_area',
        'business_shop',
        'business_floor',
        'business_nature',
        'business_start_date',
        'authorised_capital',
        'paidup_capital',
        'business_category_value',
        'business_sub_category',
        'business_sub_category_value',
        'business_category',
        'business_signboard_height',
        'business_signboard_width',
        'business_factory',
        'business_chemical',
        'business_plot_type',
        'business_plot_category',
        'business_place',
        'business_activity_type'
    ];


}
