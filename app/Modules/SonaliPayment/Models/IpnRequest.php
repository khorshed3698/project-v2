<?php

namespace App\Modules\SonaliPayment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class IpnRequest extends Model
{
    protected $table = 'sp_ipn_request';

    protected $fillable = [
        'id',
        'request_ip',
        'transaction_id',
        'pay_mode_code',
        'trans_time',
        'ref_tran_no',
        'ref_tran_date_time',
        'trans_status',
        'trans_amount',
        'pay_amount',
        'json_object',
        'ipn_response',
        'is_authorized_request',
        'sp_payment_id',
        'is_required_auto_recover',
        'no_of_try',
        'is_archive',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}