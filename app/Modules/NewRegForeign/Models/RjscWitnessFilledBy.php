<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscWitnessFilledBy extends Model {
    protected $table = 'rjsc_nrfr_doc_filled_by';

    protected $fillable = [
        'id',
        'rjsc_nr_app_id',
        'name',
        'address',
        'district_id',
        'position_id',
        'organization',
        'witness_flag',
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
