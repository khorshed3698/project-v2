<?php

namespace App\Modules\NewConnectionBREB\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueBREB extends Model
{

    protected $table = 'breb_api_request_queue';
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
