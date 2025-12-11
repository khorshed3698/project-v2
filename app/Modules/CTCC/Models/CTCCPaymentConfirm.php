<?php

namespace App\Modules\CTCC\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CTCCPaymentConfirm extends Model
{

    protected $table = 'ctcc_payment_confirm';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'oss_tracking_no',
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
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
