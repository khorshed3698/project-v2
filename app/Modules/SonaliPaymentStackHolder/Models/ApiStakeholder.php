<?php

namespace App\Modules\SonaliPaymentStackHolder\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApiStakeholder extends Model
{
    protected $table = 'api_stackholder';
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

    /*     * ******************End of Model Class***************** */
}