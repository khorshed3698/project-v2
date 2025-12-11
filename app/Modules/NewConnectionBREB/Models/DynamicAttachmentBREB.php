<?php

namespace App\Modules\NewConnectionBREB\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentBREB extends Model
{
    protected $table = 'dynamic_attachment_breb';
    protected $primaryKey = 'id';
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

}
