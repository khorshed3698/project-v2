<?php

namespace App\Modules\API\Controllers\IRMS\V1;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\API\Models\ClientIrmsRequestResponse;
use App\Modules\API\Services\IrmsPortalLogin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class IrmsPortalLoginController extends Controller
{
    public function irmsPortalLogin($tracking_no) {
        try {
            $tracking_no = Encryption::decode($tracking_no);
            $data = [];

            $irmsService = new IrmsPortalLogin();
            $token_response = json_decode($irmsService->getToken());
            if ($token_response->responseCode == 0) {
                return ['status' => 0, 'msg' => 'We are not processing the request, please wait a bit and try again later.'];
            }

            $data['access_token'] = $token_response->data;
            $data['login_url'] = config('app.IRMS_BASE_URL') . '/portal-login';
            $data['request_id'] = time() .'-'. generateUniqueId();
            $data['user_type'] = Auth::user()->user_type;
            $data['user_name'] = Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;
            $data['user_email'] = Auth::user()->user_email;
            $data['back_url'] = '/irms/api/v1/callback';
            $data['tracking_no'] = $tracking_no;

            $body = "<html>
<head></head>
<body onload='document.forms[0].submit()'>
<form name='PostForm' method='POST' action=" . $data['login_url'] . ">
<textarea name='datarequest' id='datarequest' rows='15' style='width:100%; display:none;'>
<?xml version='1.0' encoding='UTF-8'?>
<IrmsRequestFromBida>
    <RequestInformation>
        <Authentication>
            <AccessToken>".$data['access_token']."</AccessToken>
        </Authentication>
        <ReferenceInfo>
            <TrackingNumber>".$data['tracking_no']."</TrackingNumber>
            <UserType>".$data['user_type']."</UserType>
            <UserName>".$data['user_name']."</UserName>
            <UserEmail>".$data['user_email']."</UserEmail>
            <RequestId>".$data['request_id']."</RequestId>
            <BackUrl>".$data['back_url']."</BackUrl>
        </ReferenceInfo>
    </RequestInformation>
</IrmsRequestFromBida>
</textarea>
</form>
</body>
</html>";

            $api_request_response = ClientIrmsRequestResponse::firstOrNew(['tracking_no' => $data['tracking_no']]);
            $api_request_response->request_xml = $body;
            $api_request_response->request_id = $data['request_id'];
            $api_request_response->request_at = date("Y-m-d H:i:s");
            $api_request_response->save();

            return view('API::Irms.portal-login', compact('data'));
        } catch (\Exception $e) {
            Session::flash('error', "Sorry ! An internal error has occurred");
            return redirect()->back();
        }
    }
}