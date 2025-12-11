<?php

namespace App\Modules\TradeLicenseDSCC\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TradeLicenseDSCC extends Model
{

    protected $table = 'tl_dscc_apps';
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
