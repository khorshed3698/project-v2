<?php

namespace App\Modules\ETINforeigner\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ETINforeignRequestQueue extends Model
{
    protected $table = 'etin_foreigner_request_queue';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $fillable = [
        'status'
    ];

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
