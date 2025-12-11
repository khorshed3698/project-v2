<?php

namespace App\Modules\API\Controllers\Traits;


trait ApiRequestManager
{
    use OssAppApi, OssQRLogin;

    public function manageRequestType($requestType, $requestData)
    {

        switch ($requestType) {

            case 'APP_RESOURCE':
                $response = $this->appResource($requestData);
                break;

            case 'OSS_QR_VERIFY':
                $response = $this->verifyQRcode($requestData);
                break;
            case 'OSS_QR_LOGOUT':
                $response = $this->ossQRLogout($requestData);
                break;
            case 'OSS_DASHBOARD':
                $response = $this->ossMobileDashboard($requestData);
                break;
             case 'OSS_SEARCH':
                $response = $this->ossAppSearch($requestData);
                break;

            default:
                // In case of invalid request format
                $response['osspidResponse'] = [
                    'responseTime' => date('Y-m-d h:m:s'),
                    'responseType' => '',
                    'responseCode' => '400',
                    'responseData' => [],
                    'message' => 'Bad request format.'
                ];
                $response = response()->json($response);
                break;

        }
        return $response;
    }

}