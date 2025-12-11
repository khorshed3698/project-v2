<?php

namespace App\Modules\NewConnectionBPDB\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BPDBPaymentInfo extends Model {
    protected $table = 'bpdb_payment_info';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'tracking_no',
        'app_fee_json',
        'app_fee_account_json',
        'app_fee_status',
        'app_account_status',
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
