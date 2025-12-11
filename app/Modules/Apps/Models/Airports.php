<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class Airports extends Model
{

    protected $table = 'airports';
    protected $fillable = [
        'id',
        'code',
        'name',
        'email',
        'phone',
        'city_code',
        'city_name',
        'country_code',
        'country_name',
        'time_zone',
        'lat',
        'lon',
        'status',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
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
