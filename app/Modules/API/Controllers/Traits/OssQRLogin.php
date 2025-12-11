<?php
/**
 * Created by PhpStorm.
 * User: Milon
 * Date: 9/4/2018
 * Time: 4:22 PM
 */

namespace App\Modules\API\Controllers\Traits;


use App\Libraries\Encryption;
use App\Modules\API\Models\AppUserQrCode;
use App\Modules\API\Models\OssAppUser;
use App\User;
use Carbon\Carbon;

trait OssQRLogin
{

    /**
     * Verify QR Code
     * @param $requestData
     * @return mixed
     */

    public function verifyQRcode($requestData)
    {
        $hashData = $requestData['osspidRequest']['requestData']['hashData'];
        $token = $requestData['osspidRequest']['requestData']['token'];
        $decodedData = base64_decode($hashData);
        $userIdUuid = Encryption::decode(substr($decodedData, 0, (strpos($decodedData, '||')))); //User Email of OSS Framework || Unique ID
        $userId = substr($userIdUuid, 0, (strpos($userIdUuid, '||'))); //User Email of OSS Framework
        $uuId = substr($userIdUuid, (strpos($userIdUuid, '||') + 2)); //Unique ID(UUID)
        $isVerify = AppUserQrCode::where(['user_id'=> $userId, 'uuid' => $uuId])
                                    ->where('valid_till', '>', Carbon::now())
                                  ->count();
        if ($isVerify == 0) {
            $message = 'Unauthorized User';
            $response = $this->prepareResponse('OSS_QR_VERIFY_RESPONSE', '1.0', '', '401', $message);
            return $response;
        }

        $objOssAppUser = OssAppUser::firstOrNew(['user_id'=>$userId]);

        if (!isset($objOssAppUser->id)) {
            $objOssAppUser->reg_key = $this->generateRegKey();
            $objOssAppUser->user_id = $userId;
            $objOssAppUser->status = 1;

        }
        $objOssAppUser->is_logged = 1;
        $objOssAppUser->valid_till = Carbon::now()->addDay(1);
        $objOssAppUser->token = $token;
        $objOssAppUser->save();

        $responseData['regKey'] = $objOssAppUser->reg_key;
        $responseData['userId'] = $userId;

        $userInfo = User::where('user_email',$userId)->first(['id']);
        $profileUrl = url('/web/view-image/'.Encryption::encodeId($userInfo->id));
        $responseData['profileUrl'] = $profileUrl;
        $message = 'Verified Successfully';
        $response = $this->prepareResponse('OSS_QR_VERIFY_RESPONSE', 1.0, $responseData, '200', $message);
        return $response;
    }

    /**
     * Generate Reg key
     * @return string
     */

    private function generateRegKey()
    {
        return strtoupper(sprintf('%05x-%05x-%05x-%05x',
            mt_rand(0, 0xfffff),
            mt_rand(0, 0xfffff),
            mt_rand(0, 0xfffff),
            mt_rand(0, 0xfffff)
        ));
    }

    /**
     * QR Log out
     * @param $requestData
     * @return mixed
     */

    public function ossQRLogout($requestData){

        $regKey = $requestData['osspidRequest']['requestData']['regKey'];
        $User = OssAppUser::where('reg_key',$regKey)->first();
        $token = $User->token;
        $updateUser = $User->update([
            'is_logged' => 0,
            'token' => ''
        ]);
        if(!$updateUser){
            $message = 'Something Went Wrong';
            $response = $this->prepareResponse('OSS_QR_LOGOUT', 1.0, '', '600', $message);
            return $response;
        }
        $message = 'Logged out successfully';
        $response = $this->prepareResponse('OSS_QR_LOGOUT', 1.0, '', '200', $message);

        $this->apiSendNotification($token,$message,'logout', false);
        return $response;
    }
}