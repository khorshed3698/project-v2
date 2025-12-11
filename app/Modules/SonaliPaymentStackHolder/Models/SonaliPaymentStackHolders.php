<?php

namespace App\Modules\SonaliPaymentStackHolder\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class SonaliPaymentStackHolders extends Model
{
    protected $table = 'api_stackholder_sp_payment';
    protected $fillable = [
        'payment_config_id',
        'app_id',
        'process_type_id',
        'app_tracking_no',
        'payment_category_id',
        'pay_mode',
        'pay_mode_code',
        'transaction_id',
        'request_id',
        'payment_date',
        'ref_tran_no',
        'spg_redirect_no',
        'ref_tran_date_time',
        'pay_amount',
        'transaction_charge_amount',
        'vat_on_pay_amount',
        'vat_on_transaction_charge',
        'total_amount',
        'receiver_ac_no',
        'sender_ac_no',
        'contact_name',
        'contact_email',
        'contact_no',
        'payer_id',
        'address',
        'sl_no',
        'status_code',
        'payment_status',
        'is_verified',
        'payment_request_xml',
        'payment_request',
        'payment_response_xml',
        'payment_response',
        'verification_request_xml',
        'verification_request',
        'verification_response_xml',
        'offline_verify_request_xml',
        'offline_verify_request',
        'offline_verify_response_xml',
        'offline_verify_response',
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
