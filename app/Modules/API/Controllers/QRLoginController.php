<?php

namespace App\Modules\API\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\API\Controllers\Traits\OssQRLogin;
use App\Modules\API\Models\AppUserQrCode;
use App\Modules\API\Models\OssAppUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRLoginController extends Controller
{
use OssQRLogin;
    /**
     * Show Qr Code
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function showQrCode(Request $request){

        $data = [
            'responseCode' => 0,
            'qrCode' => 0,
            'message' => 'Something Went wrong'
        ];

        $uuid = $this->generateUUID();
        $user_email = Auth::user()->user_email;
        $email_uuid = $user_email .'||'.$uuid;
        $email_uuid_enc = Encryption::encode( $email_uuid);
        $valid_till = Carbon::now()->addMinutes(3);

        $objAppUserQrCode = AppUserQrCode::firstOrNew(['user_id' => $user_email ]);
        $objAppUserQrCode->user_id = $user_email;
        $objAppUserQrCode->uuid = $uuid;
        $objAppUserQrCode->valid_till = $valid_till;
        $storeUserInfo = $objAppUserQrCode->save();

        if($storeUserInfo){
            $project_code = env('OSS_CODE');
            $qrCodeBase = base64_encode($email_uuid_enc.'||'.$project_code);
            $qr_code = QrCode::size(300)->generate($qrCodeBase);
            $data = [
                'responseCode' => 1,
                'qrCode' => $qr_code,
                'message' => 'Successfully generated QR code'
            ];
        }

        return response()->json($data);

    }

    /**
     * get uuid base 64 bit
     * @return string
     */

    private  function generateUUID()
    {
        $uuid = strtoupper(hash_hmac('ripemd256', sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x', mt_rand(10, 0xffff), mt_rand(0, 0xffff), mt_rand(11, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)) , 'Oyeztowz'));
        return $uuid;
    }

    public function QRCodeLogin()
    {

        return '<div class="col-xs-12 col-md-5 col-sm-5 col-sm-offset-1">
                   <div class="well well-sm">
                       <div class="row">
                            <div class="col-md-12">
                                <span class="col-md-2 col-md-offset-5 btn btn-info" id="reload_icon"><i class="fa fa-refresh"></i></span>
                            </div>
                           <div class="col-md-12">
                               <div class="col-md-10 col-md-offset-1" id="qr_code_text" style="display: none">
                                     <h4 class="text-center">Scan Qr Code to login in OSSAPP</h4>
                                </div>
                                <div class="col-md-10 col-md-offset-1 text-right" id="qrCodeShow">
                                     <img src="' . url('assets/images/dual-ring-loader.gif') . '"/>               
                                </div>
                                <div class="col-md-10 col-md-offset-1" id="loginStatus" style="display: none">
                                     <h4 class="text-center">OSSAPP login status</h4>
                                     <h6 id="last_login_info" class="text-center"></h6>
                                </div>
                                <div class="col-md-10 col-md-offset-1" id="logInOut">
                   
                                </div>
                           </div>
                       </div>
                   </div>
                </div>';

    }

    /**
     *
     * QR Code Log In Check
     * @return \Illuminate\Http\JsonResponse
     */

    public function qrLoginCheck(){
        $user_id = Auth::user()->user_email;
        $userInfo = OssAppUser::where('user_id',$user_id)->first();
        $data = [
            'responseCode' => 0,
            'loggedIn' => 0,
        ];
       if($userInfo && ($userInfo->is_logged == 1)){
          $data = [
              'responseCode' => 1,
              'loggedIn' => 1,
              'last_login' => $userInfo->updated_at->diffForHumans()
          ];
       }
        return response()->json($data);
    }

    /**
     * Oss Qr Logout
     * @return \Illuminate\Http\JsonResponse
     */

    public function qrLogout(){
        $user_id = Auth::user()->user_email;
        $regKey = OssAppUser::where('user_id',$user_id)->first(['reg_key']);

        $requestData =  array(
             'osspidRequest' => array(
                'requestType' => 'OSS_QR_LOGOUT',
                 'deviceId' => '353412070719570',
                 'version'  => 1.0,
                 'requestData' => array(
                     'regKey' => $regKey->reg_key
                 )
            )
        );
        $response = (new APIController())->ossQRLogout($requestData);
        return response()->json($response);
    }
}
