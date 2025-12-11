<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrParticularBody extends Model {
    protected $table = 'rjsc_nr_particulars_body';

    protected $fillable = [
        'id',
        'rjsc_nr_app_id',
        'serial_number',
        'name_corporation_body',
        'represented_by',
        'address',
        'district_id',
        'no_subscribed_shares',
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
