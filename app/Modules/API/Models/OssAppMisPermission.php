<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class OssAppMisPermission extends Model {

    protected $table = 'oss_app_mis_reports_permission';

    protected $fillable = [
        'id',
        'report_id',
        'user_type',
        'user_id',
        'valid_till',
        'created_at',
        'updated_at'
    ];

}
