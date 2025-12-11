<?php

namespace App\Modules\LsppCDA\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class LsppCDAPaymentConfirm extends Model
{

    protected $table = 'lspp_cda_payment_confirm';
    protected $guarded = [];

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
