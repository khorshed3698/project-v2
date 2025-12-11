<?php

namespace App\Modules\API\Controllers;

use App\Modules\API\Controllers\Traits\Notification;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Modules\API\Controllers\Traits\ApiRequestManager;


class APIController extends Controller
{
    use ApiRequestManager, Notification;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function apiRequest()
    {
        $response = array();
        try {
            $paramValue = str_replace('\"', '"', $_REQUEST['param']);
            $requestData = $this->getParamValue($paramValue);
            $requestType = $requestData['osspidRequest']['requestType'];
            $response = $this->manageRequestType($requestType, $requestData);

        } catch (\Exception $e) {
            // In case of invalid request format
            $response['osspidResponse'] = [
                'responseTime' => Carbon::now()->timestamp,
                'responseType' => '',
                'responseCode' => '400',
                'responseData' => [],
                'message' => 'Bad request format.'.CommonFunction::showErrorPublic($e->getMessage())
            ];
            $response = response()->json($response);
        }
        return $response;
    }

    /**
     * Get Parameter as JSON decoded
     * @param $getParam
     * @return mixed
     */
    public function getParamValue($getParam)
    {
        $this->writeLog("Request", $getParam);
        return $returnArray = json_decode($getParam, true);
    }

    /**
     * Write Log in Local File
     * @param $type
     * @param $log
     */
    public function writeLog($type, $log)
    {
        date_default_timezone_set('Asia/Dhaka');
        $fileName = storage_path() . '/logs/' . date("Ymd") . ".txt";
        //echo $fileName;die();
        $file = fopen($fileName, "a");
        if ($type == "Request") {
            fwrite($file, "\r### " . date("H:i:s") . "\t" . $type . ":" . $log);
        } else {
            fwrite($file, "\r###" . $type . ":" . $log);
        }
        fclose($file);
    }
}
