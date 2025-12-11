<?php

namespace App\Modules\SonaliPayment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class PaymentDetails extends Model
{
    protected $table = 'sp_payment_details';
    protected $fillable = [
        'sp_payment_id',
        'payment_distribution_id',
        'pay_amount',
        'receiver_ac_no',
        'purpose',
        'purpose_sbl',
        'fix_status',
        'confirm_amount_sbl',
        'is_verified',
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
