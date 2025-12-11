<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ClientRequestResponse extends Model
{
    protected $table = 'client_request_response';

    protected $fillable = [
        'id',
        'client_master_id',
        'client_oauth_token_id',
        'request_json',
        'response_json',
        'value1',
        'value2',
        'request_id',
        'request_at',
        'response_id',
        'response_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
