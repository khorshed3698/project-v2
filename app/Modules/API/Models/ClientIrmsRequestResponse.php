<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ClientIrmsRequestResponse extends Model
{
    protected $table = 'client_irms_request_response';

    protected $fillable = [
        'id',
        'client_master_id',
        'client_oauth_token_id',
        'tracking_no',
        'status_id',
        'feedback_deadline',
        'remarks',
        'request_xml',
        'response_json',
        'initiate_response',
        'callback_response',
        'irn_response',
        'request_id',
        'request_at',
        'response_id',
        'response_at',
        'created_at',
        'updated_at'
    ];
}
