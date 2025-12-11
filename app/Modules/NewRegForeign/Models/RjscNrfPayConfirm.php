<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrfPayConfirm extends Model {

    protected $table = 'rjsc_nrf_pay_confirm';
    protected $fillable = [
        'ref_id',
        'submission_no',
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
