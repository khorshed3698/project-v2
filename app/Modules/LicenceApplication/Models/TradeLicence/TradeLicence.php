<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class TradeLicence extends Model
{

    protected $table = 'tl_apps';



    protected $fillable = [
        'country',
        'organization_name',
        'single_licence_ref_id',
        'account_number',
        'amount',
        'spouse_name',
        'applicant_name',
        'gf_payment_id',
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
        'business_sub_category',
        'business_category',
        'business_signboard_height',
        'business_signboard_width',
        'business_factory',
        'business_chemical',
        'business_plot_type',
        'business_plot_category',
        'business_place',
        'business_activity_type',
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
