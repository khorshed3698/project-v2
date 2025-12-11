<?php

namespace App\Modules\API\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Modules\LicenceApplication\Models\Etin\EtinDistrictInfo;
use App\Modules\LicenceApplication\Models\Etin\EtinJurisdictionList;
use App\Modules\LicenceApplication\Models\Etin\NbrAreaInfo;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ETinApiController extends Controller
{

    public function processJsonFile()
    {

        $json = Storage::disk('local')->get('public/jurisdictionPostManResponse.json');
        $jurisdictionJsonData = json_decode($json);

        $juriSelectListArr = [];

        $newRegistration = $jurisdictionJsonData[0]->next[0]->next[0]->next;

        //RegTypeNo => IsOldTin(false) => RegJuriTypeNo
        foreach ($newRegistration as $regJuriTypeNo) {


            if (isset($regJuriTypeNo->next)) {

                $individualOrLimitedBusiness = $regJuriTypeNo->fieldId;

                //RegTypeNo => IsOldTin(false) => RegJuriTypeNo => DistNo
                foreach ($regJuriTypeNo->next as $distNo) {

                    if (isset($distNo->next)) {

                        //RegTypeNo => IsOldTin(false) => RegJuriTypeNo => DistNo => JuriSelectTypeNo
                        foreach ($distNo->next as $juriSelectType) {

                            if (isset($juriSelectType->next)) {

                                //RegTypeNo => IsOldTin(false) => RegJuriTypeNo => DistNo => JuriSelectTypeNo => JuriSelectListNo
                                foreach ($juriSelectType->next as $juriSelectList) {

                                    $juriSelectListArr[] = [
                                        'etin_district_id' => $distNo->fieldId,
                                        'reg_juri_type_no' => $individualOrLimitedBusiness, // source of income Individual or Ltd. Company
                                        'juri_select_type_no' => $juriSelectType->fieldId,
                                        'juri_select_type_value' => $juriSelectType->fieldValue,
                                        'juri_select_list_no' => $juriSelectList->fieldId, //JuriSelectTypeNo
                                        'juri_select_list_value' => $juriSelectList->fieldValue,
                                        'Juri_sub_list_name_status' => is_null($juriSelectList->next) ? 0 : 1,
                                        'status' => 1,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

//        EtinJurisdictionList::query()->truncate();
//
//        EtinJurisdictionList::insert($juriSelectListArr);

//        return response()->json([
//            'status' => 1,
//            'message' => 'data successfully updated',
//        ]);
    }

    public function mapAreaTable()
    {

        try {

            $nbrAreaInfoArr = [];
            $nbrAreaInfoNotMatched = [];

            $nbrAreaDistricts = NbrAreaInfo::where('area_type', 2)->where('pare_id', 1)->get();

            $nbrAreaThanas = NbrAreaInfo::where('area_type', 3)
                ->whereNull('area_info_id')
                ->orderBy('name')
                ->get();


            foreach ($nbrAreaThanas as $nbrAreaThana) {

//                $areaData = AreaInfo::where('area_nm', 'like', '%' . $nbrAreaThana->name . '%')->first();
//                $areaData = AreaInfo::whereRaw("soundex(area_nm) = soundex('".$nbrAreaThana->name."')")
                $areaData = AreaInfo::where('area_nm', 'like', '%' . substr($nbrAreaThana->name,0,3) . '%')
                    ->where('area_type', 3)
                    ->orderBy('area_nm')
                    ->first();

                //Jaintapur , Jajira, Khulsi Magura Mirzagonj Modhukhali Moheshkhali Manpura Paikgacha PanChari Pirgacha Rowmari Ullapara

                if($areaData != null){

//                    $nbrAreaThana->area_info_id = $areaData->area_id;
//                    $nbrAreaThana->save();
//
                    $nbrAreaInfoArr[] = [
                        'nbr_id' => $nbrAreaThana->id,
                        'name' => $nbrAreaThana->name,
                        'area_id' => $areaData->area_id,
                        'area_name' => $areaData->area_nm,
                        'area_info_id' => $nbrAreaThana->area_info_id,
                    ];

                }else{
                    $nbrAreaInfoNotMatched[] = [
                        'nbr_id' => $nbrAreaThana->id,
                        'nbr_name' => $nbrAreaThana->name,
                        'area_info_id' => $nbrAreaThana->area_info_id,
                    ];

                }
            }

            return response()->json([
                'status' => 1,
                'message' => 'data successfully updated',
                'not_matched' => ($nbrAreaInfoNotMatched),
                'matched' => ($nbrAreaInfoArr)
            ]);


        } catch (\Exception $e) {
            dd($e);
        }

    }

}