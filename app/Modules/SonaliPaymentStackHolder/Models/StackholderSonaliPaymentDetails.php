<?php

namespace App\Modules\SonaliPaymentStackHolder\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class StackholderSonaliPaymentDetails extends Model
{
    protected $table = 'api_stackholder_sp_payment_details';
    protected $fillable = [
        'payment_id',
        'pay_amount',
        'purpose_sbl',
        'receiver_ac_no',
        'sl_no',
        'is_verified',
        'distribution_type',
        'verification_request',
        'verification_response',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
