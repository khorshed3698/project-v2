<?php

namespace App\Modules\ERC\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueERC extends Model {

    protected $table = 'erc_api_request_queue';
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
