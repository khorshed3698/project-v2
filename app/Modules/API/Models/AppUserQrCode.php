<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class AppUserQrCode extends Model {

    protected $table = 'oss_app_user_qr_code';
    protected $fillable = ['user_id', 'uuid', 'valid_till'];

}
