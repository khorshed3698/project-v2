<?php

namespace App\Modules\FscdNocExisting\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueuefscdNocEx extends Model {

    protected $table = 'fnoc_existing_api_request_queue';
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
