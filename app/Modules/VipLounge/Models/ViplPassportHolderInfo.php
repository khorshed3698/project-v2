<?php

namespace App\Modules\VipLounge\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ViplPassportHolderInfo extends Model {

    protected $table = 'vipl_passport_holder_info';
    protected $fillable = [
        'id',
        'app_id',
        'process_type_id',
        'passport_holder_name',
        'passport_holder_designation',
        'passport_holder_mobile',
        'passport_holder_passport_no',
        'passport_holder_attachment',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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


    /*     * *****************************End of Model Class********************************** */
}
