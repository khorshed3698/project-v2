<?php


namespace App\Modules\OcCDA\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentOcCDA extends Model
{
    protected $table = 'dynamic_attachment_oc_cda';
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