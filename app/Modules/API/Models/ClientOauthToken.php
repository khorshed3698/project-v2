<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class ClientOauthToken extends Model
{
    protected $table = 'client_oauth_token';

    protected $fillable = [
        'id',
        'client_master_id',
        'oauth_token',
        'oauth_token_expire_at',
        'ip_address',
        'user_agent',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
