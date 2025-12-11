<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class MohaApiQueueHist extends Model {

    protected $table = 'moha_api_request_queue_hist';
    protected $fillable = [
        'id',
        'action_type',
        'action_datetime',
        'moha_api_request_queue_id',
        'ref_id',
        'type',
        'request_json',
        'response_json',
        'status',
        'status_check_response',
        'moha_tracking_id',
        'certificate',
        'fl_certificate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];


}
