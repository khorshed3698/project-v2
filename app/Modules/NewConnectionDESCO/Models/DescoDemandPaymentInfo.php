<?php

namespace App\Modules\NewConnectionDESCO\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DescoDemandPaymentInfo extends Model
{
    protected $table = 'desco_demand_payment_info';

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
