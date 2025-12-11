<?php

namespace App\Modules\BasicInformation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class EA_RegCommercialOffices extends Model {

    protected $table = 'ea_reg_commercial_offices';
    protected $fillable = [
        'id',
        'name',
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
