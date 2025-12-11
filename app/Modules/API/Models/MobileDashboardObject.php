<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class MobileDashboardObject extends Model {

    protected $table = 'oss_app_mobile_dashboard_object';
    protected $fillable = ['id', 'title', 'key','query','response','datatype','updated_at','time_limit','changed_at'];


}
