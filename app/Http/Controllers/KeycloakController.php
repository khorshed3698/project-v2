<?php

namespace App\Http\Controllers;

use App\Libraries\UtilFunction;
use Exception;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\DB;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\Users\Models\CompanyInfo;

class KeycloakController extends Controller
{
    public function callback(Request $request)
    {
        try {
            $code = $request->input('code');
            if (empty($code)) {
                Session::flash('error', 'Login failed. Authorization code is missing. [KEYCLOAK-002]');
                // Need admin access to log out using session_state
                return redirect('/');
            }

            $tokenData = $this->getTokenFromCode($code);
            if (!$tokenData) {
                Session::flash('error', 'Login failed. Failed to fetch tokens. [KEYCLOAK-003]');
                // Need admin access to log out using session_state
                return redirect('/');
            }

            $state = $request->input('state');
            if (empty($state) || $state !== session('keycloak.state')) {
                Session::flash('error', 'Login failed. Invalid state parameter. [KEYCLOAK-001]');
                return redirect()->away(UtilFunction::getKeycloakLogoutUrl($tokenData['id_token']));
            }

            $userData = $this->getUserInfo($tokenData['access_token']);
            if (!$userData) {
                Session::flash('error', 'Login failed. Failed to fetch user info [KEYCLOAK-004]');
                return redirect()->away(UtilFunction::getKeycloakLogoutUrl($tokenData['id_token']));
            }

            // temporary log for Keycloak callback request
            Log::info('Keycloak callback request:', [
                'callback data' => $request->all(),
                'userData' => $userData,
            ]);

            session()->forget('keycloak.state');

            session([
                'keycloak.access_token' => $tokenData['access_token'],
                'keycloak.refresh_token' => $tokenData['refresh_token'],
                'keycloak.id_token' => $tokenData['id_token'],
            ]);

            $oauth_data = (object)$userData;
            $oauth_data->user_full_name = $userData['name'];
            $oauth_data->user_email = $userData['email'];
            $oauth_data->mobile = $userData['mobile'];

            Session::put('oauth_token', 'oauth_token');
            $getAlreadyUser = Users::where('user_email', $oauth_data->user_email)->first();
            if (empty($getAlreadyUser)) {
                Session::put('oauth_data', $oauth_data);
                return redirect()->route('signup.identity_verify_otp');
            }

            if ($getAlreadyUser->user_status == 'rejected') {
                Session::put('oauth_data', $oauth_data);
                return redirect()->route('signup.identity_verify_otp');
            }

            // Auth logic
            $logIn = Auth::loginUsingId($getAlreadyUser->id);
            if (!$logIn) {
                Session::flash('error', 'Login failed. Please reload the page and try again. [KEYCLOAK-005]');
                return $this->logout();
            }

            $loginCheck = new LoginController();
            if ($loginCheck->checkMaintenanceModeForUser() === true) {
                Session::flash('error', session()->get('error') . ' [KEYCLOAK-006]');
                return $this->logout();
            }

            $userTypeCheck = $loginCheck->_checkUserTypeRootActivation(Auth::user()->user_type, Auth::user()->company_ids, true);
            if (!$userTypeCheck['result']) {
                Session::flash('error', $userTypeCheck['msg'] . ' [KEYCLOAK-007]');
                return $this->logout();
            }

            // empty login_token
            Users::where('id', Auth::user()->id)->update(['login_token' => '']);

            if (Auth::user()->is_approved == 1 && Auth::user()->user_status !== 'active') {
                Session::flash('error', 'User is not active. Please contact admin. [KEYCLOAK-008]');
                return $this->logout();
            }

            if (!$loginCheck->_checkSecurityProfile($request)) {
                Session::flash('error', 'Security restriction: Login blocked [KEYCLOAK-009]');
                return $this->logout();
            }

            // put user info in session
            if (!$loginCheck->_setSession()) {
                Session::flash('error', 'Session expired. Please login again. [KEYCLOAK-010]');
                return $this->logout();
            }

            // Set delegated user id in session
            if (Auth::user()->user_type == '4x404' && Auth::user()->delegate_to_user_id != 0) {
                Session::put('sess_delegated_user_id', Auth::user()->delegate_to_user_id);
            }

            // Login user and redirect to dashboard/profile
            CommonFunction::GlobalSettings();

            $this->entryAccessLog();

            $getAlreadyUser->login_token = Encryption::encode(Session::getId());

            $redirectPath = '/dashboard'; // need to remove this
            $sessionMsg = "Logged in successfully, Welcome to " . config('app.project_name');

            if ($getAlreadyUser->first_login == 0) {
                $getAlreadyUser->first_login = 1;
                $sessionMsg = '<strong>Dear user,</strong><br><br><p>We noticed that your profile setting does not complete yet 100%.<br/> Update your <strong>profile, designation, signature and other useful information </strong>. You can not apply any type of registration without proper informational profile. <br><br>Thanks<br>' . config('app.project_name') . '</p>';
            }

            $getAlreadyUser->save();

            // only for applicant user
            // If company ids is single id then automatically set into working company id
            // else user need to select one company as current working company id and update this id into working company id
            if (Auth::user()->user_type == '5x505') {
                $companyIds = CommonFunction::getUserCompanyAllWithZeroWithoutEloquent();
                $user_multiple_company = 0; // flag
                if (count($companyIds) < 2) {
                    $user_id = Auth::user()->id;
                    $company_association_request = CommonFunction::getWorkingUserType(Auth::user()->company_ids);
                    if (!empty($company_association_request)) {
                        $working_user_type = $company_association_request->approved_user_type;
                        DB::table('users')
                            ->where('id', $user_id)
                            ->update(['working_company_id' => $companyIds[0], 'working_user_type' => $working_user_type]);
                    } else {
                        DB::statement("UPDATE users SET working_company_id = company_ids where id = $user_id");
                    }
                } else {
                    $user_multiple_company = 1;
                    $pageTitle = 'Company selection';
                    $last_working_company = CommonFunction::getCompanyNameById(Auth::user()->working_company_id);
                    // No need to check company eligibility,
                    // User will get all associated company
                    $companyList = CompanyInfo::where('company_status', 1)
                        ->where('is_approved', 1)
                        ->whereIn('id', $companyIds)
                        ->where('is_rejected', 'no')
                        ->get(['company_name', 'id']);

                    return view('Dashboard::index', compact('user_multiple_company', 'companyList', 'last_working_company', 'pageTitle'));
                }
            }

            Session::flash('success', $sessionMsg);
            return redirect()->to($redirectPath);

        } catch (Exception $e) {
            Log::error("Error occurred in KeycloakController@callback ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
            return $this->logout();
        }
    }

    public function entryAccessLog()
    {
        $access_id = str_random(10);
        DB::table('user_logs')->insert([
            'user_id' => Auth::user()->id,
            'login_dt' => date('Y-m-d H:i:s'),
            'ip_address' => \Request::getClientIp(),
            'access_log_id' => $access_id
        ]);
        Session::put('access_log_id', $access_id);
    }

    public function logout()
    {
        return redirect()->away(UtilFunction::logoutFromKeyCloak());
    }

    private function getTokenFromCode($code)
    {
        $url = config('services.keycloak.base_url') . '/realms/' . config('services.keycloak.realm') . '/protocol/openid-connect/token';

        $postFields = http_build_query([
            'grant_type' => 'authorization_code',
            'client_id' => config('services.keycloak.client_id'),
            'client_secret' => config('services.keycloak.client_secret'),
            'redirect_uri' => config('services.keycloak.redirect_uri'),
            'code' => $code,
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);

        if (config('app.env') == 'local') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        }


        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $errorNo = curl_errno($ch);
            $errorMessage = curl_error($ch);
            Log::error("KeycloakController@getTokenFromCode-cURL Error: [$errorNo] - $errorMessage", [
                'endpoint' => $url
            ]);

            curl_close($ch);
            return false;
        }

        curl_close($ch);
        $tokenData = json_decode($response, true);

        if (isset($tokenData['error'])) {
            Log::error('Error from Keycloak token request: ' . json_encode($tokenData));
            return false;
        }

        return $tokenData;
    }

    private function getUserInfo($accessToken)
    {
        $url = config('services.keycloak.base_url') . '/realms/' . config('services.keycloak.realm') . '/protocol/openid-connect/userinfo';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (config('app.env') == 'local') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {

            $errorNo = curl_errno($ch);
            $errorMessage = curl_error($ch);
            Log::error("KeycloakController@getUserInfo-cURL Error: [$errorNo] - $errorMessage", [
                'endpoint' => $url
            ]);

            curl_close($ch);
            return false;
        }

        curl_close($ch);

        return json_decode($response, true);
    }

//    public function refreshAccessToken()
//    {
//        $accessToken = session('keycloak.access_token');
//        $refreshToken = session('keycloak.refresh_token');
//
//        if (!$accessToken || !$refreshToken) {
//            Session::flash('error', 'Login failed. Invalid state parameter. [KEYCLOAK-013]');
//            return $this->logout();
//        }
//
//        $url = config('services.keycloak.base_url') . '/realms/' . config('services.keycloak.realm') . '/protocol/openid-connect/token';
//
//        $postFields = http_build_query([
//            'grant_type' => 'refresh_token',
//            'client_id' => config('services.keycloak.client_id'),
//            'client_secret' => config('services.keycloak.client_secret'),
//            'refresh_token' => $refreshToken,
//        ]);
//
//        $ch = curl_init($url);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
//
//        $response = curl_exec($ch);
//
//        if (curl_errno($ch)) {
//            Log::error('Error refreshing token from Keycloak: ' . curl_error($ch));
//            curl_close($ch);
//            return false;
//        }
//
//        curl_close($ch);
//        $tokenData = json_decode($response, true);
//
//        if (isset($tokenData['error'])) {
//            Log::error('Error from Keycloak token refresh: ' . json_encode($tokenData));
//            return false;
//        }
//
//        // Store the new tokens in session
//        session([
//            'keycloak.access_token' => $tokenData['access_token'],
//            'keycloak.refresh_token' => $tokenData['refresh_token'],
//        ]);
//
//        return true;
//    }
}