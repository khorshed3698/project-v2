<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class NbrAreaInfo extends Model
{
    protected $table = 'nbr_area_info';

    protected $fillable = [
        'nbr_id',
        'name',
        'pare_id',
        'area_type',
    ];


    public static function getAreaName($nbrAreaId, $areaType)
    {
        $nbrAreaInfo = self::where('nbr_id', $nbrAreaId)->where('area_type', $areaType)->first();

        return isset($nbrAreaInfo->name) ? $nbrAreaInfo->name : null;
    }

    public static function getNbrAddressId($areaInfoId, $areaType)
    {

        try {
            $nbrAreaInfo = self::where('area_info_id', $areaInfoId)->where('area_type', $areaType)->first();

            return isset($nbrAreaInfo->nbr_id) ? intval($nbrAreaInfo->nbr_id) : null;

        } catch (\Exception $e) {

            Log::error('GetNbrAddressId : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1076]');

            return null;
        }
    }

    public static function getNbrCountryId($countryInfoId)
    {

        try {

            $nbrAreaInfo = self::where('country_info_id', $countryInfoId)
                ->where('pare_id', 0)
                ->where('area_type', 0)
                ->first();
            return isset($nbrAreaInfo->nbr_id) ? intval($nbrAreaInfo->nbr_id) : null;

        } catch (\Exception $e) {

            Log::error('GetNbrCountryId : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1077]');

            return null;
        }
    }
}