<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class MainSourceOfIncome extends Model {
    protected $table = 'etin_main_source_of_income';
    protected $fillable = [
        'id',
        'main_source_income',
        'reg_juri_type_no',
        'is_approved',
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

}
