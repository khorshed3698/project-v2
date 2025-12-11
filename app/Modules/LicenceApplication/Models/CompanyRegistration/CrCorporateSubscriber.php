<?php

namespace App\Modules\LicenceApplication\Models\CompanyRegistration;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CrCorporateSubscriber extends Model
{

    protected $table = 'cr_corporate_subscriber';

    protected $fillable = [
        'app_id',
        'cs_name',
        'cs_represented_by',
        'cs_license_app',
        'cs_subscribed_share_no',
        'cs_district',
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