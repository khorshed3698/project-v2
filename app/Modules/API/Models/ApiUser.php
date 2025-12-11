<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUser extends Model
{
    protected $table = 'api_users';

    protected $fillable = [
        'id',
        'name',
        'key',
        'allowed_endpoint',
        'status',
        'details',
        'created_at'
    ];
}
