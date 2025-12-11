<?php

namespace App\Modules\CdaOc\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CdaOcDynamicAttachment extends Model
{
    protected $table = 'cda_oc_dynamic_attachment';
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