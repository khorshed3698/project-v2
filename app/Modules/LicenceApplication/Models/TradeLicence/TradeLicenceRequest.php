<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TradeLicenceRequest extends Model
{

    protected $table = 'tl_request';

    protected $fillable = [
        'ref_id',
        'response',
        'request',
        'processing_at',
        'status',
        'is_deleted',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
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
