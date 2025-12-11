<?php

namespace App\Modules\CdaForm\Models;

use Illuminate\Database\Eloquent\Model;

class CdaPayment extends Model {

    protected $table = 'cda_payment';
    protected $fillable = [
        'id',
        'luc_id',
        'challan_no',
        'transaction_id',
        'transaction_amount',
        'transaction_date',
        'request',
        'response',
        'status',
        'created_at',
        'updated_at'
    ];

}
