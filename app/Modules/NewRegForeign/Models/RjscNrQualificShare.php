<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrQualificShare extends Model {
    protected $table = 'rjsc_nr_qualific_shares';

    protected $fillable = [
        'id',
        'rjsc_nr_app_id',
        'no_qualific_share',
        'value_of_each_share',
        'agreement_witness_name',
        'agreement_witness_address',
        'district_id',
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
