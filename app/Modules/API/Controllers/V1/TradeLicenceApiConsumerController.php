<?php

namespace App\Modules\API\Controllers\V1;


use App\Http\Controllers\Controller;
use App\Modules\API\Models\ApiTokenList;
use App\Modules\LicenceApplication\Models\TradeLicence\DccAreaInfo;
use App\Modules\LicenceApplication\Models\TradeLicence\DccZoneInfo;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessCategory;
use App\Modules\LicenceApplication\Models\TradeLicence\TLBusinessSubCategory;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TradeLicenceApiConsumerController extends Controller
{

    const BASE_URL = 'http://116.193.218.152:5000/';

    protected $token;

    protected $apiTokenCredential = [
        'UserName' => '01755676720',
        'Password' => '123456',
        'grant_type' => 'password'
    ];


    public function __construct()
    {
        if (Session::has('trade_license_api_token') && $this->checkTokenExpire(Session::get('trade_license_api_token_expire'))) {

            $this->token = Session::get('trade_license_api_token');

        } else {

            $this->token = $this->getApiToken();
        }

    }


    private function getApiToken()
    {

        try {
            $postField = http_build_query($this->apiTokenCredential);

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, self::BASE_URL.'getToken');
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postField);
            $responseJson = curl_exec($curl);

            if (curl_errno($curl)) {
                echo curl_error($curl);
                $response = null;
            } else {
                curl_close($curl);
                $response = json_decode($responseJson);
            }


//            $response = json_decode('{"access_token":"-GADyjuVSjh_HL9n8DhtNAJPhJ1xPHbSQYPx6jZdrsHp-yS6oRZn4fhP0-JXGvA-8FfIDszvhN9t9BLKlyDOaqeZOH5XCUAKnLsnicppxcnE6uamIiibWkuyw2cwH2gIs7jLp_cRhf9PuFwk8RwWEsGYigMbp6D0PwVEzAcaKZ8do6sk7kPDEztu6OFe3RDSGu3Snlua7nGaa-NwuKdbg2xVJx4aguLNMtK1Eg2VgKL8MD5pJTyo3NAmZNv1yJ41UXE9oF1J9LEEt62HU1h3HE8hLcqT9n-2QyTvSOkdme5r_vLBasn5i5Xs_pWsz6Zi","token_type":"bearer","expires_in":1209599,"userName":"01755676720",".issued":"Thu, 31 Jan 2019 08:45:23 GMT",".expires":"Thu, 14 Feb 2019 08:45:23 GMT"}');

            if (!is_null($response)) {

                $expireDate = Carbon::parse($response->{'.expires'});

                $responseToken = 'Bearer ' . $response->access_token;
                Session::put('trade_license_api_token', $responseToken);
                Session::put('trade_license_api_token_expire', $expireDate->toDateTimeString());
                return Session::get('trade_license_api_token');
            }

            return null;

        } catch (\Exception $e) {
            return null;
        }
    }

    public function checkTokenExpire($tokenExpireDate)
    {

        return Carbon::parse($tokenExpireDate) > Carbon::now();

    }


    public function mapZoneWardArea()
    {
        try {

            $zoneRequestedUrl = self::BASE_URL . 'api/license/get_zone_ward_area?pid=0&type=0';

            $zoneResponse = $this->curlGetRequest($zoneRequestedUrl);

            $zoneWardArea = [];

            if ($zoneResponse->code == 200) {

                foreach ($zoneResponse->data as $zone) {

                    $zoneWardArea[] = [
                        'dcc_id' => intval($zone->id),
                        'name_en' => $zone->loc_name,
                        'name_bn' => $zone->loc_name_bn,
                        'pare_id' => 0,
                        'area_type' => 1,
                        'dcc_response_code' => $zone->responseCode,
                    ];

                    $this->mapWard($zone->id, $zoneWardArea);
                }

                DccZoneInfo::query()->truncate();
                DccZoneInfo::insert($zoneWardArea);

                return response()->json(['responseCode' => 1, 'message' => 'dcc_Area_info Upload Done to Database',]);
            }

            return response()->json(['responseCode' => 0, 'message' => 'Sorry there is an error',]);

        } catch (\Exception $e) {

            return response()->json(['responseCode' => 0, 'message' =>CommonFunction::showErrorPublic($e->getMessage())]);
        }

    }


    public function mapWard($zoneId, &$zoneWardArea)
    {
        $wardRequestedUrl = self::BASE_URL . 'api/license/get_zone_ward_area?pid=' . $zoneId . '&type=1';

        $zoneResponse = $this->curlGetRequest($wardRequestedUrl);

        if ($zoneResponse->code == 200) {

            foreach ($zoneResponse->data as $ward) {

                $zoneWardArea[] = [
                    'dcc_id' => intval($ward->id),
                    'name_en' => $ward->loc_name,
                    'name_bn' => $ward->loc_name_bn,
                    'pare_id' => intval($zoneId),
                    'area_type' => 2,
                    'dcc_response_code' => $ward->responseCode,
                ];

                $this->mapArea($ward->id, $zoneWardArea);
            }
        }
    }


    public function mapArea($zoneId, &$zoneWardArea)
    {
        $areaRequestedUrl = self::BASE_URL . 'api/license/get_zone_ward_area?pid=' . $zoneId . '&type=3';

        $areaResponse = $this->curlGetRequest($areaRequestedUrl);

        if ($areaResponse->code == 200) {

            foreach ($areaResponse->data as $area) {

                $zoneWardArea[] = [
                    'dcc_id' => intval($area->id),
                    'name_en' => $area->loc_name,
                    'name_bn' => $area->loc_name_bn,
                    'pare_id' => intval($zoneId),
                    'area_type' => 3,
                    'dcc_response_code' => $area->responseCode,
                ];
            }
        }

    }

    public function mapDivisionDistrictThana()
    {
        try {

            $divisionRequestedUrl = self::BASE_URL . 'api/license/get_location?pid=0&type=0';

            $divisionResponse = $this->curlGetRequest($divisionRequestedUrl);

            $districtDivisionThana = [];

            if ($divisionResponse->code == 200) {

                foreach ($divisionResponse->data as $division) {

                    $districtDivisionThana[] = [
                        'dcc_id' => intval($division->id),
                        'name_en' => $division->loc_name,
                        'name_bn' => $division->loc_name_bn,
                        'pare_id' => 0,
                        'area_type' => 1,
                        'dcc_response_code' => $division->responseCode,
                    ];

                    $this->mapDistrict($division->id, $districtDivisionThana);
                }

                DccAreaInfo::query()->truncate();
                DccAreaInfo::insert($districtDivisionThana);

                return response()->json(['responseCode' => 1, 'message' => 'dcc_Area_info Upload Done to Database',]);
            }

            return response()->json(['responseCode' => 0, 'message' => 'Sorry there is an error',]);

        } catch (\Exception $e) {

            return response()->json(['responseCode' => 0, 'message' => CommonFunction::showErrorPublic($e->getMessage())]);
        }

    }


    public function mapDistrict($divisionId, &$districtDivisionThana)
    {
        $districtRequestedUrl = self::BASE_URL . 'api/license/get_location?pid=' . $divisionId . '&type=1';

        $divisionResponse = $this->curlGetRequest($districtRequestedUrl);

        if ($divisionResponse->code == 200) {

            foreach ($divisionResponse->data as $district) {

                $districtDivisionThana[] = [
                    'dcc_id' => intval($district->id),
                    'name_en' => $district->loc_name,
                    'name_bn' => $district->loc_name_bn,
                    'pare_id' => intval($divisionId),
                    'area_type' => 2,
                    'dcc_response_code' => $district->responseCode,
                ];

                $this->mapThana($district->id, $districtDivisionThana);
            }
        }
    }


    public function mapThana($districtId, &$districtDivisionThana)
    {
        $thanaRequestedUrl = self::BASE_URL . 'api/license/get_location?pid=' . $districtId . '&type=3';

        $thanaResponse = $this->curlGetRequest($thanaRequestedUrl);

        if ($thanaResponse->code == 200) {

            foreach ($thanaResponse->data as $thana) {

                $districtDivisionThana[] = [
                    'dcc_id' => intval($thana->id),
                    'name_en' => $thana->loc_name,
                    'name_bn' => $thana->loc_name_bn,
                    'pare_id' => intval($districtId),
                    'area_type' => 3,
                    'dcc_response_code' => $thana->responseCode,
                ];
            }
        }

    }


    public function mapBusinessCategory()
    {

        try {

            $requestedUrl = self::BASE_URL . 'api/license/get_business_cat';

            $response = $this->curlGetRequest($requestedUrl);

            $categoryData = [];


            $subCategoryData = [];

            if ($response->code == 200) {

                foreach ($response->data as $category) {

                    $categoryData[] = [
                        'dcc_cat_id' => intval($category->CAT_ID),
                        'name_bn' => $category->NAME_BN,
                        'name_en' => $category->NAME_EN,
                        'dcc_response_code' => $category->responseCode,
                        'status' => 1,
                    ];
                }

                TLBusinessCategory::query()->truncate();
                TLBusinessCategory::insert($categoryData);


                return response()->json(['responseCode' => 1, 'message' => 'Category Upload Done to Database',]);
            }

            return response()->json(['responseCode' => 0, 'message' => 'Sorry there is an error',]);

        } catch (\Exception $e) {

            return response()->json(['responseCode' => 0, 'message' => CommonFunction::showErrorPublic($e->getMessage())]);
        }
    }

    public function mapBusinessSubCategory()
    {
        $categories = TLBusinessCategory::orderBy('dcc_cat_id')->get(['id', 'dcc_cat_id', 'name_en']);

        $subCategoryData = [];

        $methodResponse = [];

        foreach ($categories as $category) {

            $requestedUrl = self::BASE_URL . 'api/license/get_business_sub_cat?cat_id=' . $category->dcc_cat_id . '&cat_grp_id=0';

            $response = $this->curlGetRequest($requestedUrl);

            if ($response->code == 200) {

                foreach ($response->data as $subCategory) {

                    $subCategoryData[] = [
                        'tl_cat_id' => $category->id,
                        'dcc_cat_id' => $category->dcc_cat_id,
                        'dcc_sub_cat_id' => intval($subCategory->SCAT_ID),
                        'name_bn' => $subCategory->NAME_BN,
                        'name_en' => $subCategory->NAME_EN,
                        'dcc_response_code' => $subCategory->responseCode,
                        'status' => 1,
                    ];
                }

                $methodResponse[] = 'For Category ' . $category->name_en . '(' . $category->dcc_cat_id . ') total row ' . count($response->data) . ' ';

            }

        }

        TLBusinessSubCategory::query()->truncate();
        TLBusinessSubCategory::insert($subCategoryData);

        return response()->json(['responseCode' => 1, 'message' => 'Category Upload Done to Database', 'details' => json_encode($methodResponse)]);
    }


    public function curlGetRequest($requested_url)
    {
        $curlResponseObject = new \stdClass(); // a new object

        $curlResponseObject->data = [];
        $curlResponseObject->code = null;
        $curlResponseObject->message = null;

        if (isset($this->token) && !empty($this->token)) {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $requested_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: " . $this->token,
                    "cache-control: no-cache"
                ),
            ));

            $responseJson = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if (curl_errno($curl)) {
                echo curl_error($curl);
                $curlResponse = null;

            } else {
                curl_close($curl);
                $curlResponse = json_decode($responseJson);
            }

            $curlResponseObject->data = $curlResponse;
            $curlResponseObject->code = $code;

            return $curlResponseObject;

        } else {

            $curlResponseObject->code = 500;
            $curlResponseObject->message = 'Can Not Update The Token. Maybe Server Refused to Give Token';

            return $curlResponseObject;
        }
    }


    public function mapAreaTable()
    {

        try {

            $this->divisionDccAreaMap();
            $this->districtDccAreaMap();
            $this->thanaDccAreaMap();


        } catch (\Exception $e) {
            dd($e);
        }

    }

    private function divisionDccAreaMap(){

        $dccAreaInfoArr = [];
        $dccAreaInfoNotMatched = [];

        $dccAreaDivisions = DccAreaInfo::where('area_type', 1)->where('pare_id', 0)->get();

        foreach ($dccAreaDivisions as $dccDivision) {

            $areaData = AreaInfo::whereRaw("soundex(area_nm) = soundex('".$dccDivision->name_en."')")
                ->where('area_type', 1)
                ->where('pare_id', 0)
                ->orderBy('area_nm')
                ->first();

            if ($areaData != null) {

                $dccDivision->area_info_id = $areaData->area_id;
                $dccDivision->save();

                $dccAreaInfoArr[] = [
                    'dcc_id' => $dccDivision->id,
                    'name' => $dccDivision->name_en,
                    'area_id' => $areaData->area_id,
                    'area_name' => $areaData->area_nm,
                    'area_info_id' => $dccDivision->area_info_id,
                ];

            } else {
                $dccAreaInfoNotMatched[] = [
                    'dcc_id' => $dccDivision->id,
                    'dcc_name' => $dccDivision->name_en,
                    'area_info_id' => $dccDivision->area_info_id,
                ];

            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'data successfully updated',
            'not_matched' => ($dccAreaInfoNotMatched),
            'matched' => ($dccAreaInfoArr)
        ]);
    }



    private function districtDccAreaMap(){

        $dccAreaInfoArr = [];
        $dccAreaInfoNotMatched = [];

        $dccAreaDistricts = DccAreaInfo::where('area_type', 2)->get();

        foreach ($dccAreaDistricts as $dccDistrict) {

//            $areaData = AreaInfo::whereRaw("soundex(area_nm) = soundex('".$dccDistrict->name_en."')")
            $areaData = AreaInfo::where('area_nm', 'like', '%' . $dccDistrict->name_en . '%')
                ->where('area_type', 3)
                ->orderBy('area_nm')
                ->first();

            if ($areaData != null) {

//                $dccDistrict->area_info_id = $areaData->area_id;
//                $dccDistrict->save();

                $dccAreaInfoArr[] = [
                    'dcc_id' => $dccDistrict->id,
                    'name' => $dccDistrict->name_en,
                    'area_id' => $areaData->area_id,
                    'area_name' => $areaData->area_nm,
                    'area_info_id' => $dccDistrict->area_info_id,
                ];

            } else {
                $dccAreaInfoNotMatched[] = [
                    'dcc_id' => $dccDistrict->id,
                    'dcc_name' => $dccDistrict->name_en,
                    'area_info_id' => $dccDistrict->area_info_id,
                ];

            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'data successfully updated',
            'not_matched' => ($dccAreaInfoNotMatched),
            'matched' => ($dccAreaInfoArr)
        ]);
    }

    private function thanaDccAreaMap(){

        $dccAreaInfoArr = [];
        $dccAreaInfoNotMatched = [];

        $dccAreaThanas = DccAreaInfo::where('area_type', 3)->whereNull('area_info_id')->get();

        foreach ($dccAreaThanas as $dccThana) {

            $areaData = AreaInfo::whereRaw("soundex(area_nm) = soundex('".$dccThana->name_en."')")
//            $areaData = AreaInfo::where('area_nm_ban', 'like', '%' . $dccThana->name_bn . '%')
                ->where('area_type', 3)
                ->orderBy('area_nm')
                ->first();

            if ($areaData != null) {

//                $dccThana->area_info_id = $areaData->area_id;
//                $dccThana->save();

                $dccAreaInfoArr[] = [
                    'dcc_id' => $dccThana->id,
                    'name' => $dccThana->name_en,
                    'area_id' => $areaData->area_id,
                    'area_name' => $areaData->area_nm,
                    'area_info_id' => $dccThana->area_info_id,
                ];

            } else {
                $dccAreaInfoNotMatched[] = [
                    'dcc_id' => $dccThana->id,
                    'dcc_name' => $dccThana->name_en,
                    'area_info_id' => $dccThana->area_info_id,
                ];

            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'data successfully updated',
            'not_matched' => ($dccAreaInfoNotMatched),
            'matched' => ($dccAreaInfoArr)
        ]);
    }
}