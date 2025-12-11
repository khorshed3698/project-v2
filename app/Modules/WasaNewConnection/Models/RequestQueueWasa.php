<?php

namespace App\Modules\WasaNewConnection\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueWasa extends Model {

    protected $table = 'dwasa_api_request_queue';
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
