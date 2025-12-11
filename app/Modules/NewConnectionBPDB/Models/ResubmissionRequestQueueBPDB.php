<?php

namespace App\Modules\NewConnectionBPDB\Models;

use Illuminate\Database\Eloquent\Model;

class ResubmissionRequestQueueBPDB extends Model
{

    protected $table = 'bpdb_api_request_resubmission_queue';
    protected $fillable = [
        'id',
        'ref_id',
        'type',
        'client_id',
        'request',
        'response',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];
}
