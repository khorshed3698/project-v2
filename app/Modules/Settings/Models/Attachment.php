<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Attachment extends Model {

    protected $table = 'attachment_list';
    protected $fillable = array(
        'id',
        'process_type_id',
        'attachment_type_id',
        'doc_name',
        'short_note',
        'doc_priority',
        'additional_field',
        'is_multiple',
        'order',
        'status',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
    /*********************************************End of Model Class**********************************************/
}
