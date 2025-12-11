<?php

namespace App\Modules\VATReg\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class  HsCodeService extends Model {


    protected $table = 'hs_code_demo';
    protected $fillable = [
        'id',
        'service_code',
        'service_name',
        'hs_type',
        'start_date',
        'end_date',
        'is_active',
        'created_at',
        'updated_at',
    ];

//    public static function boot() {
//        parent::boot();
//        // Before update
//        static::creating(function($post) {
//            $post->created_by = CommonFunction::getUserId();
//            $post->updated_by = CommonFunction::getUserId();
//        });
//
//        static::updating(function($post) {
//            $post->updated_by = CommonFunction::getUserId();
//        });
//    }

}
