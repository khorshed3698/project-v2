<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class OssAppUser extends Model {

    protected $table = 'oss_app_users';

    protected $fillable = [
        'id',
        'user_id',
        'status',
        'is_logged',
        'reg_key',
        'valid_till',
        'token',
        'created_at',
        'updated_at'
    ];

}
