<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ApiUserToken extends Model
{
    protected $table = 'api_user_tokens';

    protected $fillable = [
        'id',
        'api_user_id',
        'token',
        'expires_at',
        'created_at'
    ];
}
