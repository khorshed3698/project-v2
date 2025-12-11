<?php

namespace App\Modules\VATReg\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueVat extends Model {

    protected $table = 'vat_api_request_queue';
    protected $fillable = [
        'id',
        'ref_id',
        'type',
        'request_json',
        'response_json',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
