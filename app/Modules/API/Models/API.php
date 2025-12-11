<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class API extends Model
{
    protected $table = 'apis';

    protected $fillable = [
        'id',
        'api_user_id',
        'name',
        'activity',
        'status',
        'created_at',
        'updated_at'
    ];
}
