<?php
namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryptor;
use App\Libraries\Osspid;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Socialite;
use DB;


class GoogleLoginController extends Controller
{

    protected $osspid;

    public function __construct()
    {
//        // For multi client
//        if (!is_object($this->osspid)) {
//            $this->osspid = new Osspid(array(
//                'client_id' => '4aeb98892163ad03904251d3a49410dd0fa5f055',
//                'client_secret_key' => '493af875dd2f359edfd212f86df3c247d687c5e2',
//                'callback_url' => env('PROJECT_ROOT').'/osspid-callback'
//            ));
//        }

    }

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
//    public function redirectToProvider()
//    {
//        return Socialite::driver('google')->redirect();
//    }


    /**
     * @throws \Exception
     */


    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
//    public function handleProviderCallback()
//    {
//        try{
//            $user = Socialite::driver('google')->user();
//
//            $data = [
//                //	'user_type' => '12x432',
//                'user_nid' => $user->getId(),
//                'user_email' => $user->getEmail(),
//                'user_full_name' => $user->getName(),
//                'user_pic' => $user->avatar_original,
//                'password' => Hash::make('Google'),
//                'is_approved' => 1,
//                'first_login' => 1,
//                'social_login' => 1,
//                'security_profile_id' => 1
//            ];
//            $getAlreadyUser = Users::where('user_email', $user->getEmail())->first();
//
//            if($getAlreadyUser==''){
//                $users = Users::firstOrCreate($data);
//                Auth::loginUsingId($users->id);
//                $users->login_token = Encryption::encode(Session::getId());
//                $users->save();
//
//                return redirect()->to('/google_signUp');
//            } else {
//                if ($getAlreadyUser->user_status == 'active'){
//                    Auth::loginUsingId($getAlreadyUser->id);
//                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());
//                    $getAlreadyUser->save();
//                    $this->entryAccessLog();
//                    return redirect()->to('/dashboard');
//                }
//                else{
//                    Session::flash('error',"User not activated!");
//                    return redirect()->to('/login');
//                }
//            }
//
//
//        }
//        catch(\Exception $e)
//        {
////            dd($e->getMessage());
//            Auth::logout();
//            return redirect()->to('/login');
//        }
//    }
//    public function osspidCallback(Request $request)
//    {
//        try{
//
//            $oauth_encrypted_data = $request->get('oauth_data');
//            $oauth_token = $request->get('oauth_token');
//            $encryptor = new Encryptor();
//            $oauth_data = json_decode($encryptor->decrypt($oauth_encrypted_data));
//
//            // In case of invalid access token
//            if ($oauth_token == '') {
//                Session::flash('error', 'Invalid token.');
//                //return redirect('/');
//                dd("<h1>Invalid token.</h1>");
//            }
//
//            // In case of invalid oauth data
//            if (strlen($oauth_token) == 0) {
//                Session::flash('error', 'Invalid oauth data.');
//                //return redirect('/');
//                dd("<h1>Invalid oauth data.</h1>");
//            }
//
//            $user_full_name = $oauth_data->user_full_name;
//            $email = $oauth_data->user_email;
//            // Validate oauth token with server
//            $verifyOauthToken = $this->osspid->verifyOauthToken($oauth_token, $email);
//
//            if ($verifyOauthToken) {
//                //Function to request for increasing oAuth token expire time
//                $this->osspid->requestForIncreaseOauthTokenExpireTime($oauth_token, $email);
//                $getAlreadyUser = Users::where('user_email', $email)->first();
//
//                if($getAlreadyUser==''){
//                    $data = [
//                        'user_nid' => "",
//                        'user_email' => $email,
//                        'user_full_name' => $user_full_name,
//                        'password' => Hash::make('Google'),
//                        'is_approved' => 1,
//                        'first_login' => 1,
//                        'social_login' => 2,
//                        'security_profile_id' => 1
//                    ];
//                    $users = Users::firstOrCreate($data);
//                    Auth::loginUsingId($users->id);
//                    $users->login_token = Encryption::encode(Session::getId());
//                    $users->save();
//
//                    return redirect()->to('/osspid_signUp');
//                } else {
//                    if ($getAlreadyUser->user_status == 'active'){
//                        Auth::loginUsingId($getAlreadyUser->id);
//                        Session::put('oauth_token', $oauth_token);
//                        $getAlreadyUser->login_token = Encryption::encode(Session::getId());
//                        $getAlreadyUser->save();
//                        $this->entryAccessLog();
//                        return redirect()->to('/dashboard');
//                    }
//                    else{
//                        Session::flash('error',"User not activated!");
//                        return redirect()->to('/login');
//                    }
//                }
//            } else {
//                dd("<h1>Invalid oauth token.</h1>", $verifyOauthToken);
//            }
//
//
//
//        }
//        catch(\Exception $e)
//        {
//            dd($e->getMessage());
//            Auth::logout();
//            return redirect()->to('/login');
//        }
//    }
//    public function entryAccessLog()
//    {
//        // access_log table.
//        $str_random = str_random(10);
//        $insert_id = DB::table('user_logs')->insertGetId(
//            array(
//                'user_id' => Auth::user()->id,
//                'login_dt' => date('Y-m-d H:i:s'),
//                'ip_address' => \Request::getClientIp(),
//                'access_log_id' => $str_random
//            )
//        );
//
//        Session::put('access_log_id', $str_random);
//    }




    // For First Client
//    public function osspidLogout()
//    {
//        $oauth_token = Session::get('oauth_token');
//        $is_logged_out = $this->osspid->logoutFromOsspid($oauth_token, Auth::user()->user_email);
//        Session::getHandler()->destroy(Session::getId());
//        Session::flush();
//        Auth::logout();
//        return redirect('/');
//    }


//    public function login_from_others_system(){
//        // Validate oauth token with server
//        $verifyOauthToken = $this->osspid->verifyOauthToken($oauth_token, $email);
//
//        if ($verifyOauthToken) {
//            //Function to request for increasing oAuth token expire time
//            $this->osspid->requestForIncreaseOauthTokenExpireTime($oauth_token, $email);
//
//            $user = DB::table('users')->where('user_email', trim($email))->first();
////                if (count($user) == 1) {
////
////                    // In case of valid user
////                    if (Auth::loginUsingId($user->id)) {
////                        Session::put('oauth_token', $oauth_token);
////                        return redirect('/welcome');
////                    }
////                } else {
////                    //Need to insert user and also set the user as auth.
////                    dd("<h1>This user is not exist in client system.</h1>");
////                }
//
//
//
//
//            $getAlreadyUser = Users::where('user_email', $email)->first();
//
//            if($getAlreadyUser==''){
//                $data = [
//                    //	'user_type' => '12x432',
//                    'user_nid' => "",
//                    'user_email' => $email,
//                    'user_full_name' => $user_full_name,
////				'user_pic' => $user->avatar_original,
//                    'password' => Hash::make('Google'),
//                    'is_approved' => 1,
//                    'first_login' => 1,
//                    'social_login' => 2,
//                    'security_profile_id' => 1
//                ];
//                $users = Users::firstOrCreate($data);
//                Auth::loginUsingId($users->id);
//                $users->login_token = Encryption::encode(Session::getId());
//                $users->save();
//
//                return redirect()->to('/google_signUp');
//            } else {
//                if ($getAlreadyUser->user_status == 'active'){
//                    Auth::loginUsingId($getAlreadyUser->id);
//                    Session::put('oauth_token', $oauth_token);
//                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());
//                    $getAlreadyUser->save();
//                    $this->entryAccessLog();
//                    return redirect()->to('/dashboard');
//                }
//                else{
//                    Session::flash('error',"User not activated!");
//                    return redirect()->to('/login');
//                }
//            }
//        } else {
//            dd("<h1>Invalid oauth token.</h1>", $verifyOauthToken);
//        }
//    }


//    public function getOSSPIDUserSession(Request $request) {
//        $params = $request->get('params');
//
//        $decoded_data = json_decode($params,true);
//        dd($decoded_data);
//
//        return response()->json($data);
//    }
//}
}