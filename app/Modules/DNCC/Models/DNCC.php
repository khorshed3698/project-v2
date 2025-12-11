<?php

namespace App\Modules\DNCC\Models;
use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DNCC extends Model {

    protected $table = 'dncc_apps';
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
