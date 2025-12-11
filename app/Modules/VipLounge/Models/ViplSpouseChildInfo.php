<?php

namespace App\Modules\VipLounge\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ViplSpouseChildInfo extends Model {

    protected $table = 'vipl_spouse_child_info';
    protected $fillable = [
        'id',
        'app_id',
        'process_type_id',
        'spouse_child_type',
        'spouse_child_name',
        'spouse_child_passport_per_no',
        'spouse_child_remarks',
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
