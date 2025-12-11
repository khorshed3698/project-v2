<?php
/**
 * Created by PhpStorm.
 * User: Milon
 * Date: 9/4/2018
 * Time: 4:22 PM
 */

namespace App\Modules\API\Controllers\Traits;

use App\Libraries\Encryption;
use App\Modules\API\Models\MobileDashboardObject;
use App\Modules\API\Models\OssAppUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait OssAppApi
{
    public function appResource($requestData)
    {
        $resourceName = $requestData['osspidRequest']['requestData']['resourceName'];
        switch ($resourceName) {

            case 'APP_MIS':
                $response = $this->appMisReport($requestData);
                break;

            default:
                $response = null;
        }
        return $response;

    }

    /**
     * Get MIS report
     * @param $requestData
     * @return array
     */
    public function appMisReport($requestData)
    {
        $message = '';
        $deviceId = $requestData['osspidRequest']['deviceId'];
        $version = $requestData['osspidRequest']['version'];
        $regKey = $requestData['osspidRequest']['regKey'];
        $userName = $requestData['osspidRequest']['userName'];

        if (!isset($requestData['osspidRequest']['isOsspid'])) {
            $isOsspid = 0; // General Log in
        } else {
            $isOsspid = $requestData['osspidRequest']['isOsspid'];
        }


        if ($isOsspid == 1) {

            $ifUserExits = $this->ossPidUserExits($regKey, $userName, $deviceId, $version);
            $ifUserExits = json_decode($ifUserExits);
            $responseCode = $ifUserExits->osspidResponse->responseCode;

            if ($responseCode != '200') {
                if ($responseCode == '401') {
                    $message = 'User Unauthorized';
                    $response = $this->prepareResponse('APP_RESOURCE_RESPONSE', $version, array(), '401', $message);
                    return $response;
                }
                if ($responseCode == '400') {
                    $message = 'Bad Request Format';
                    $response = $this->prepareResponse('APP_RESOURCE_RESPONSE', $version, array(), '400', $message);
                    return $response;
                }
            }
        }

        $ossAppReports = $this->getMisReportTemplate($regKey, $userName);

        if ($ossAppReports == null) {
            $message = 'Resource access time over';
            $response = $this->prepareResponse('APP_RESOURCE_RESPONSE', $version, array(), '412', $message);
            return $response;
        }

        $content = $ossAppReports;
        $responseData = array(
            'contentType' => 'JSON',
            'content' => $content,
            'native' => 'YES'
        );
        $response = $this->prepareResponse('APP_RESOURCE_RESPONSE', $version, $responseData, '200', $message);
        return $response;
    }

    /**
     * Get mis report html template
     * @param $deviceId
     * @return null|array
     */
    public function getMisReportTemplate($regKey, $userName)
    {

        $unix_time = Carbon::now()->getTimestamp();

        $reportResult = [];

        $currentTime = Carbon::now();

        $allReports = DB::select(' SELECT * FROM   (
 
                              SELECT     `oss_app_mis_reports`.`title`         AS `title`,
                                         `oss_app_mis_reports`.`id`            AS `report_id`,
                                         `oss_app_mis_reports_permission`.`id` AS `permission_id`
                              FROM       `oss_app_users`
                              
                              LEFT JOIN  `users`
                                ON  `oss_app_users`.`user_id` = `users`.`user_email`
                                
                              INNER JOIN `oss_app_mis_reports_permission`
                                ON  (`oss_app_mis_reports_permission`.`user_id` = `users`.`id` 
                                 AND `oss_app_mis_reports_permission`.`user_type` = 0 )
                                OR  ( `oss_app_mis_reports_permission`.`user_type` = `users`.`user_type` 
                                 AND `oss_app_mis_reports_permission`.`user_id` = 0 )
                                 
                              LEFT JOIN  `oss_app_mis_reports`
                                ON  `oss_app_mis_reports_permission`.`report_id` = `oss_app_mis_reports`.`id` 
                                
                              WHERE  `oss_app_users`.`reg_key` = "' . $regKey . '" AND `oss_app_users`.`status` = 1                              
                                   AND `oss_app_mis_reports_permission`.`valid_until` > "' . $currentTime . '"

                            union

                            select title as title, id AS report_id, "0" as permission_id from oss_app_mis_reports where is_public=1 and status = 1                                  

                          ) AS uniontable

 GROUP  BY report_id  ');
        if (count($allReports) > 0) {
            foreach ($allReports as $misreport) {
                $reportResult[] = [
                    'title' => $misreport->title,
                    'url' => url('/web/view-mis-reports/' . encode($misreport->report_id . '||' . $regKey) . '/' . encodeId($misreport->permission_id) . '/' . encode($unix_time)),
                ];
            }

            return $reportResult;
        }
        return null;
    }

    /**
     * Make APi Response
     * @param $requestType
     * @param $version
     * @param $responseData
     * @param $responseCode
     * @param $message
     * @return array
     */
    private function prepareResponse($requestType, $version, $responseData, $responseCode, $message)
    {
        $response = [];
        $response['osspidResponse'] = [
            'responseTime' => Carbon::now()->timestamp,
            'responseType' => $requestType,
            'responseData' => $responseData,
            'version' => $version,
            'responseCode' => $responseCode,
            'message' => $message
        ];
        return response()->json($response);
    }

    /**
     * Oss pid User Exits or Not
     * @param $regKey
     * @param $userName
     * @param $deviceId
     * @param $version
     * @return mixed|string
     */
    public function ossPidUserExits($regKey, $userName, $deviceId, $version)
    {
        $ossPidRequestData = array(
            'osspidRequest' => array(
                'deviceId' => $deviceId,
                'version' => $version,
                'requestType' => 'VERIFY_USER',
                'requestData' => array(
                    'regKey' => $regKey,
                    'userName' => $userName,
                    'projectCode' => config('app.oss_code')
                )
            )
        );

        $encodedOssPidRequestData = urlencode(json_encode($ossPidRequestData));
        $url = config('app.osspid_base_url') . "/osspid/api?param=$encodedOssPidRequestData";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        $response = curl_exec($ch);


        if (curl_errno($ch)) {
            $response = '';
        } else {
            curl_close($ch);
        }

        return $response;

    }

    /**
     * Json data part
     * @param $string
     * @param int $Deflength
     * @param int $version
     * @return array
     */
    function detailsArray($string, $Deflength = 5000, $version = 0)
    {
        if ($version > 3 and strlen($string) <= 256) {
            if (strpos($string, 'http') == 3) {
                $string = substr($string, 3, strlen($string) - 4);
            }
        }
        $returnArr = array();
        while (strlen($string) > 0) {
            $length = $Deflength;
            $str = substr($string, 0, $length);
            while ($length > ($Deflength * .8) and substr($str, $length - 1, 1) != ' ') {
                $length--;
            }
            $returnArr[]['part'] = base64_encode(substr($string, 0, $length));
            $string = substr($string, $length);
        }
        return $returnArr;
    }

    /**
     * Oss Mobile Dashboard
     * @param $requestData
     * @return array
     */

    public function ossMobileDashboard($requestData)
    {
        $regKey = $requestData['osspidRequest']['requestData']['regKey'];
        $version = $requestData['osspidRequest']['version'];

        $mobileDashboardData = DB::select(' SELECT * FROM (
                         SELECT     `oss_app_mobile_dashboard_object`.* 
                         FROM       `oss_app_users` 
                         LEFT JOIN  `users` 
                         ON         `oss_app_users`.`user_id` = `users`.`user_email` 
                         INNER JOIN `oss_app_mdo_permission` 
                         ON         ( 
                                               `oss_app_mdo_permission`.`user_id` = `users`.`id` 
                                    AND        `oss_app_mdo_permission`.`user_type` = 0 ) 
                         or         ( 
                                               `oss_app_mdo_permission`.`user_type` = `users`.`user_type` 
                                    AND        `oss_app_mdo_permission`.`user_id` = 0 ) 
                         LEFT JOIN  `oss_app_mobile_dashboard_object` 
                         ON         `oss_app_mobile_dashboard_object`.`id` = `oss_app_mdo_permission`.`mdo_id` 
                         WHERE      `oss_app_users`.`reg_key` = "' . $regKey . '"
                         AND        `oss_app_users`.`status` = 1 
                         AND        `oss_app_mobile_dashboard_object`.`state` = 1 
                         GROUP BY   `oss_app_mobile_dashboard_object`.`id`
                        UNION 
                        
                         SELECT   * 
                         FROM     `oss_app_mobile_dashboard_object` 
                         WHERE    `is_public` = 1 
                         AND        `oss_app_mobile_dashboard_object`.`state` = 1 
                        ) AS uniontables order by uniontables.order asc');


        $userInfo = OssAppUser::where('reg_key', $regKey)
            ->leftjoin('users', 'oss_app_users.user_id', '=', 'users.user_email')
            ->first(['users.id as user_id', 'users.user_type']);

        if (count($mobileDashboardData) == 0) {
            $message = 'Data not available';
            $responseData = 'Data not found';
            $response = $this->prepareResponse('OSS_DASHBOARD_RESPONSE', $version, $responseData, '404', $message);
            return $response;
        }

        $requestDataArray = array();
        foreach ($mobileDashboardData as $dashboardData) {
            $dashboardData->user_id = $userInfo->user_id;
            $dashboardData->user_type = $userInfo->user_type;
            switch ($dashboardData->data_type) {
                case 'HTML':
                    $response = $this->getMobileDashboardHTML($dashboardData, []);
                    break;
                case 'PIE_CHART':
                case 'BAR_CHART':
                    $response = $this->getMobileDashboardJSON($dashboardData, []);
                    break;
                case 'INFO_TEXT':
                    $response = $this->getMobileDashboardInfoText($dashboardData, []);
                    break;

                default:
                    $response = $this->getMobileDashboardJSON($dashboardData, []);
                    break;
            }

            $reportResult = $response;

            if ($reportResult == null) {
                continue;
            }

            if ($dashboardData->data_type != 'HTML') {
                $reportResult = json_decode($response);
            }

            $requestDataArray[] = [
                'type' => $dashboardData->data_type,
                'title' => $dashboardData->title,
                'iconUrl' => $dashboardData->icon_url,
                'imageUrl' => $dashboardData->image_url,
                'linkUrl' => $dashboardData->link_url,
                "is_collapse" => $dashboardData->is_collapse == 1 ? "YES" : "NO",
                'data' => $reportResult
            ];
        }

        $responseData = $requestDataArray;
        $message = 'APP DASHBOARD JSON';
        $response = $this->prepareResponse('OSS_DASHBOARD_RESPONSE', $version, $responseData, '200', $message);
        return $response;

    }


    /**
     * Get Dashboard Query Result
     * @param $dashboardData
     * @param $input
     * @return false|null|string
     */

    public function getMobileDashboardJSON($dashboardData, $input = array())
    {
        try {
            // Replace dynamic parameter
            $query = $dashboardData->query;
            $search_for = array(0 => '{$USER_ID}', 1 => '{$USER_TYPE}');
            $replace_with = array(0 => $dashboardData->user_id, 1 => $dashboardData->user_type);
            $query = str_replace($search_for, $replace_with, $query);

            $extended_time = Carbon::now();
            if ($dashboardData->updated_at && $dashboardData->updated_at > '0') {
                $extended_time = Carbon::parse($dashboardData->updated_at)->addSeconds($dashboardData->time_limit);
            }

            if ($extended_time <= Carbon::now() && $query) { // limited time over
                // Configure for convert parameter
                if (count($input) > 0) {
                    $query = $this->ConvParaEx($query, $input);
                }

                $response = null;
                $response = DB::select(DB::raw($query));
                if (count($response) > 0) {
                    $queryResult = array();
                    for ($i = 0; $i < count($response); $i++) {
                        $keys = array_keys((array)$response[$i]);
                        $label = $keys[0];
                        $value = $keys[1];
                        $queryResult[$response[$i]->$label] = $response[$i]->$value;
                    }

                    $response = json_encode($queryResult);
                    if ($dashboardData->time_limit > 0) {
                        MobileDashboardObject::where('id', $dashboardData->id)->update([
                            'response' => $response,
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }


            } else {
                // limited time is not over
                $response = $dashboardData->response;
            };
        } catch (\Exception $e) {
            $response = null;
        }
        return $response;
    }

    /**
     * Get Dashboard Query Result
     * @param $dashboardData
     * @param $input
     * @return false|null|string
     */

    public function getMobileDashboardInfoText($dashboardData, $input = array())
    {
        try {
            $query = $dashboardData->query;
            // Replace dynamic parameter
            $search_for = array(0 => '{$USER_ID}', 1 => '{$USER_TYPE}');
            $replace_with = array(0 => $dashboardData->user_id, 1 => $dashboardData->user_type);
            $query = str_replace($search_for, $replace_with, $query);

            $extended_time = Carbon::now();
            if ($dashboardData->updated_at && $dashboardData->updated_at > '0') {
                $extended_time = Carbon::parse($dashboardData->updated_at)->addSeconds($dashboardData->time_limit);
            }

            if ($extended_time <= Carbon::now() && $query) { // limited time over

                // Configure for convert parameter
                if (count($input) > 0) {
                    $query = $this->ConvParaEx($query, $input);
                }

                $response = null;
                $response = DB::select(DB::raw($query));
                if (count($response) > 0) {

                    $response = json_encode($response);
                    if ($dashboardData->time_limit > 0) {
                        MobileDashboardObject::where('id', $dashboardData->id)->update([
                            'response' => $response,
                            'updated_at' => Carbon::now()
                        ]);
                    }
                }

            } else {
                // limited time is not over
                $response = $dashboardData->response;
            };
        } catch (\Exception $e) {
            $response = null;
        }
        return $response;
    }


    public function getMobileDashboardHTML($dashboardData, $input = array())
    {
        return base64_encode($dashboardData->response);
    }

    /***
     * Convert Parameter SQl
     * @param $sql
     * @param $data
     * @param string $sm
     * @param string $em
     * @param bool $optional
     * @return string
     */


    private function ConvParaEx($sql, $data, $sm = '{$', $em = '}', $optional = false)
    {
        $sql = ' ' . $sql;
        $start = strpos($sql, $sm);
        $i = 0;
        while ($start > 0) {
            if ($i++ > 20) {
                return $sql;
            }
            $end = strpos($sql, $em, $start);
            if ($end > $start) {
                $filed = substr($sql, $start + 2, $end - $start - 2);
                if (strtolower(substr($filed, 0, 8)) == 'optional') {
                    $optionalCond = $this->ConvParaEx(substr($filed, 9), $data, '[$', ']', true);
                    $sql = substr($sql, 0, $start) . $optionalCond . substr($sql, $end + 1);
                } else {
                    $inputData = $this->getData($filed, $data, substr($sql, 0, $start));
                    if ($optional && (($inputData == '') || ($inputData == "''"))) {
                        $sql = '';
                        break;
                    } else {
                        $sql = substr($sql, 0, $start) . $inputData . substr($sql, $end + 1);
                    }
                }
            }
            $start = strpos($sql, $sm);
        }
        return trim($sql);
    }

    /***
     * Sql Get Data
     * @param $filed
     * @param $data
     * @param null $prefix
     * @return string
     */


    private function getData($filed, $data, $prefix = null)
    {
        $filedKey = explode('|', $filed);
        $val = trim($data[$filedKey[0]]);
        if (!is_numeric($val)) {
            if ($prefix) {
                $prefix = strtoupper(trim($prefix));
                if (substr($prefix, strlen($prefix) - 3) == 'IN(') {
                    $vals = explode(',', $val);
                    $val = '';
                    for ($i = 0; $i < count($vals); $i++) {
                        if (is_numeric($vals[$i])) {
                            $val .= (strlen($val) > 0 ? ',' : '') . $vals[$i];
                        } else {
                            $val .= (strlen($val) > 0 ? ',' : '') . "'" . $vals[$i] . "'";
                        }
                    }
                } elseif (!(substr($prefix, strlen($prefix) - 1) == "'" || substr($prefix, strlen($prefix) - 1) == "%")) {
                    $val = "'" . $val . "'";
                }
            }
        }
        if ($val == '') $val = "''";
        return $val;
    }

    public function ossAppSearch($requestData)
    {
        $regKey = $requestData['osspidRequest']['requestData']['regKey'];
        $keyword = $requestData['osspidRequest']['requestData']['keyword'];
        $version = $requestData['osspidRequest']['version'];

        $appUserExit = OssAppUser::where('reg_key', $regKey)
            ->where('status', 1)->first();

        if (!$appUserExit) {
            $message = 'User Unauthorized';
            $responseData = 'User Not Found';
            $response = $this->prepareResponse('OSS_SEARCH_RESPONSE', $version, $responseData, '401', $message);
            return $response;
        }

        $enc_regKey = Encryption::encode($regKey);
        $keyword = urlencode($keyword);
        $searchUrl = url('/web/search/' . $enc_regKey . '/' . $keyword);
        $message = 'Search Result';
        $responseData['searchResult'] = $searchUrl;
        $response = $this->prepareResponse('OSS_SEARCH_RESPONSE', $version, $responseData, '200', $message);
        return $response;

    }


}