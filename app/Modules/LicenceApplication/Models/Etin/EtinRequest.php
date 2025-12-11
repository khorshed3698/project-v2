<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class EtinRequest extends Model {

    protected $table = 'etin_request';

    protected $fillable = [
        'id',
        'ref_id',
        'process_type_id',
        'request',
        'response',
        'status',
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
