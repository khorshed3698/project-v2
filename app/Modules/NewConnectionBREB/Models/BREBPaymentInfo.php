<?php

namespace App\Modules\NewConnectionBREB\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BREBPaymentInfo extends Model
{
    protected $table = 'breb_payment_info';
    protected $primaryKey = 'id';
    protected $guarded = [];

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
