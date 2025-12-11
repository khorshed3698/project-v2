<?php

namespace App\Modules\NewReg\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class AoaInfo extends Model {
    protected $table = 'rjsc_nr_aoa_info';
    protected $fillable = [
        'id',
        'rjsc_nr_app_id',
        'clause_title_id',
        'clause',
        'clause_for_rjsc',
        'sequence'
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
