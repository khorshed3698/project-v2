<?php

namespace App;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class IRCCommonPool extends Model
{

    protected $table = 'irc_common_pool';
    protected $guarded = ['id'];

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
