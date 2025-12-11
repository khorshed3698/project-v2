<?php

namespace App\Modules\DCCICos\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentDCCICos extends Model
{

    protected $table = 'dynamic_attachment_dcci_cos';
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
