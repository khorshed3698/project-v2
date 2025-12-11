<?php

namespace App\Modules\Users\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DivisionalOffice extends Model
{

    protected $table = 'divisional_office';
    protected $fillable = array(
        'id',
        'office_name',
        'office_address',
        //'division_name',
        'status',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );


    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}