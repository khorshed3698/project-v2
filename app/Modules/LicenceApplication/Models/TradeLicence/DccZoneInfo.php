<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use Illuminate\Database\Eloquent\Model;

class DccZoneInfo extends Model
{
    protected $table = 'dcc_zone_info';

    protected $fillable = [
        'dcc_id',
        'name_en',
        'name_bn',
        'pare_id',
        'area_type',
        'area_info_id',
    ];

}