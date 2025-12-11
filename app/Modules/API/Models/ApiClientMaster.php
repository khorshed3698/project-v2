<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiClientMaster extends Model
{
    protected $table = 'client_master';

    protected $fillable = [
        'id',
        'client_id',
        'client_secret_key',
        'status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
