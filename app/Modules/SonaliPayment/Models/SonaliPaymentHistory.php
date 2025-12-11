<?php

namespace App\Modules\SonaliPayment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class SonaliPaymentHistory extends Model
{
    protected $table = 'sp_payment_history';
    protected $guarded = ['id'];

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
