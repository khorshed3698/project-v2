<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrFPaymentInfo extends Model {

    protected $table = 'rjsc_nrf_payment_info';
    protected $fillable = [
        'rjsc_id',
        'submission_no',
        'tracking_no',
        'status'

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
