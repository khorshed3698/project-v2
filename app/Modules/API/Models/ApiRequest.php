<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequest extends Model
{
    protected $table = 'api_requests';

    protected $fillable = [
        'id',
        'api_id',
        'request_id',
        'endpoint',
        'method',
        'headers',
        'body',
        'response_status_code',
        'response_headers',
        'response_body',
        'operation_status',
        'created_at',
        'updated_at'
    ];
}
