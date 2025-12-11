<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrFDoc extends Model
{

    protected $table = 'rjsc_nrf_doc';
    protected $fillable = [
        'ref_id',
        'response',
        'doc_name',
        'doc',
        'processing_at',
        'status',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

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