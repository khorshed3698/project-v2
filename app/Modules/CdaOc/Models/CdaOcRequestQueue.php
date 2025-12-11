<?php


namespace App\Modules\CdaOc\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CdaOcRequestQueue extends Model
{
    protected $table = 'cda_oc_api_request_queue';
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