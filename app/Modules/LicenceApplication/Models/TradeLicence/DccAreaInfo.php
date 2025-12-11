<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use Illuminate\Database\Eloquent\Model;

class DccAreaInfo extends Model
{

    protected $table = 'dcc_area_info';

    protected $fillable = [
        'dcc_id',
        'name_en',
        'name_bn',
        'pare_id',
        'area_type',
        'area_info_id',
    ];


    public static function getDccAddressId($areaInfoId, $areaType){

        $dccAreaInfo = self::where('area_info_id', $areaInfoId)->where('area_type', $areaType)->first(['dcc_id']);

        return isset($dccAreaInfo->dcc_id) ? intval($dccAreaInfo->dcc_id) : null;
    }


    public static function getDccAreaName($areaInfoId, $areaType){

        $dccAreaInfo = self::where('area_info_id', $areaInfoId)->where('area_type', $areaType)->first(['name_en']);

        return isset($dccAreaInfo->name) ? intval($dccAreaInfo->name) : null;
    }

}