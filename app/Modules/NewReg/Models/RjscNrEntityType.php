<?php

namespace App\Modules\NewReg\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrEntityType extends Model {

    protected $table = 'rjsc_nr_entity_type';
    protected $fillable = [
        'rjsc_id',
        'name',
        'address',
        'status'

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
