<?php

namespace App\Modules\DOE\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DOEShortfall extends Model {

    protected $table = 'doe_application_shortfall';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'request',
        'response',
        'doe_status',
        'status',
        'processing_at',
        'completed',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}
