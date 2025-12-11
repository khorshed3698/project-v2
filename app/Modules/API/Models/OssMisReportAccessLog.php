<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class OssMisReportAccessLog extends Model {

    protected $table = 'oss_app_mis_report_access_log';
    public $timestamps = false;
    protected $fillable = array(
        'user_id',
        'ip',
        'access_time',
    );


}
