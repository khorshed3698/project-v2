<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TaxpayerStatus extends Model {
    protected $table = 'etin_taxpayer_status';
    protected $fillable = [
        'id',
        'taxpayer_status',

        'is_approved',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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
