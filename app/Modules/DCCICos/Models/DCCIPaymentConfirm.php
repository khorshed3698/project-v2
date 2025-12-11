<?php

namespace App\Modules\DCCICos\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DCCIPaymentConfirm extends Model
{

    protected $table = 'dcci_cos_payment_confirm';
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
