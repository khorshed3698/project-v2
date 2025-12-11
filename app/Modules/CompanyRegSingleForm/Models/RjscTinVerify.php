<?php

namespace App\Modules\CompanyRegSingleForm\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscTinVerify extends Model {
    protected $table = 'rjsc_nr_tin_verify';
    protected $fillable = [
        'id',
        'status',
        'nr_subscribers_id',
        'tin',
        'ref_id',
        'response',
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
