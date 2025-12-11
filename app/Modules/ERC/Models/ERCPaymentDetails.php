<?php

namespace App\Modules\ERC\Models;

use Illuminate\Database\Eloquent\Model;

class ERCPaymentDetails extends Model {

    Protected $table='erc_payment_details';
    Protected $fillable = array(
        'id',
        'payment_category',
        'ref_id',
        'tracking_no',
        'payment_info_request',
        'payment_info_response',
        'payment_confirm_request',
        'payment_confirm_response',
        'stakeholder_payment_stage',
        'processing_at',
        'request_time',
        'response_time',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

}
