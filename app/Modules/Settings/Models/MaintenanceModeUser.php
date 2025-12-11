<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class MaintenanceModeUser extends Model
{

    protected $table = 'maintenance_mode_user';


    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_at = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}