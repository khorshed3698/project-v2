<?php

namespace App\Modules\NewConnectionWZPDCL\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class WzpdclDemandPaymentInfo extends Model
{
    protected $table = 'wzpdcl_demand_payment_info';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'wzpdcl_tracking_no',
        'request',
        'response',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

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
