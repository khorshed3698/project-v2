<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiErrorLog extends Model
{
    protected $table = 'api_error_logs';

    protected $fillable = [
        'id',
        'api_id',
        'error_type',
        'error_message',
        'stack_trace',
        'request_headers',
        'request_body',
        'response_headers',
        'response_body',
        'created_at'
    ];
}
