<?php

namespace App\Modules\DCCICos\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DCCICos extends Model
{

    protected $table = 'dcci_cos_apps';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}
