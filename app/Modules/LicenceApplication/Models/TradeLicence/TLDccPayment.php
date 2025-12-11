<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TLDccPayment extends Model {

    protected $table = 'tl_dcc_payment';
    protected $fillable = [
        'ref_id',
        'request',
        'tracking_no',
        'created_by',
        'status'

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