<?php

namespace App\Modules\IndustrialIrc\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueCCIE extends Model {

    protected $table = 'ccie_api_request_queue';
    protected $fillable = [
        'id',
        'ref_id',
        'type',
        'client_id',
        'request_json',
        'response_json',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
