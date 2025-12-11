<?php

namespace App\Modules\SecurityClearance\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityClearance extends Model {

    protected $table = 'security_clearance_request_queue';
    protected $fillable = [
        'id',
        'type',
        'ref_id',
        'request_json',
        'response_json',
        'sub_req_time',
        'sub_res_time',
        'status',
        'ready_to_check',
        'status_check_request_json',
        'status_check_response',
        'status_check_req_time',
        'status_check_res_time',
        'moha_tracking_id',
        'certificate',
        'fl_certificate',
        'json_prepare_remarks',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}
