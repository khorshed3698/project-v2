<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    protected $table = 'api_responses';

    protected $fillable = [
        'id',
        'api_id',
        'external_request_id',
        'endpoint',
        'method',
        'headers',
        'body',
        'response_status_code',
        'response_headers',
        'response_body',
        'created_at',
        'updated_at'
    ];
}
