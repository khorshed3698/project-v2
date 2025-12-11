<?php

namespace App\Modules\Apps\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ManualPayment extends Model {
    protected $table = 'manual_payment';
    protected $fillable = [
        'app_id',
        'process_type_id',
        'app_tracking_no',
        'payment_category_id',
        'pay_amount',
        'transaction_charge_amount',
        'vat_amount',
        'total_amount',
        'contact_name',
        'contact_email',
        'contact_no',
        'address',
        'payment_status',
        'ref_tran_no',
        'invoice_copy',
        'payment_date',
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
