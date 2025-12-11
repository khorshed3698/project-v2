<?php

namespace App\Modules\ExternalLicense\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ExternalLicense extends Model {

    protected $table = 'external_service_apps';
    protected $guarded = ['id'];
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
