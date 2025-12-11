<?php

namespace App\Modules\CdaForm\Models;

use Illuminate\Database\Eloquent\Model;

    class CdaRequestQueue extends Model {

    protected $table = 'cda_api_request_queue';
    protected $fillable = [
        'id',
        'type',
        'ref_id',
        'member_id',
        'request_json',
        'response_json',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

}
