<?php

namespace App\Modules\LabourInspection\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DifeRequestQueue extends Model
{
    protected $table = 'dife_api_request_queue';
    protected $primaryKey = 'id';
    protected $guarded = [];

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
