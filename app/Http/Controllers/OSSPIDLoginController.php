<?php

namespace App\Http\Controllers;

use App\Libraries\CommonFunction;
use App\Libraries\Encryptor;
use App\Libraries\Osspid;
use App\Libraries\OtpService;
use App\Modules\CompanyAssociation\Models\CompanyAssociation;
use App\Modules\Users\Models\Users;
//use App\Modules\Users\Models\SecurityQuestion;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\DepartmentInfo;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\AreaInfo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Encryption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Socialite;
use DB;
use Validator;


class OSSPIDLoginController extends Controller
{

    protected $osspid;

    public function __construct()
    {
        // For multi client
        if (!is_object($this->osspid)) {
            $this->osspid = new Osspid(array(
                'client_id' => config('app.osspid_client_id'),
                'client_secret_key' => config('app.osspid_client_secret_key'),
                'osspid_auth_url' => config('app.osspid_auth_url'),
                'callback_url' => config('app.project_root') . '/osspid-callback'
            ));
        }

    }

        // we have implemented keycloak so now we don't need osspid
//    public function osspidCallback(Request $request)
//    {
//        try {
//            $oauth_encrypted_data = $request->get('oauth_data');
//            $oauth_token = $request->get('oauth_token');
//            $encryptor = new Encryptor();
//            $oauth_data = json_decode($encryptor->decrypt($oauth_encrypted_data));
//
//            // In case of invalid access token
//            if ($oauth_token == '') {
////                Session::flash('error', 'Invalid token.');
////                return redirect('/');
//                Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDC101]');
//                return redirect()->to('/login');
//            }
//
//            // In case of invalid oauth data
//            if (strlen($oauth_token) == 0) {
////                Session::flash('error', 'Invalid oauth data.');
////                return redirect('/');
//                Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDC102]');
//                return redirect()->to('/login');
//            }
//
//            $user_full_name = $oauth_data->user_full_name;
//            $email = $oauth_data->user_email;
//            // Validate oauth token with server
//            $verifyOauthToken = $this->osspid->verifyOauthToken($oauth_token, $email);
//            if ($verifyOauthToken) {
//                //Function to request for increasing oAuth token expire time
//                $this->osspid->requestForIncreaseOauthTokenExpireTime($oauth_token, $email);
//                $getAlreadyUser = Users::where('user_email', $email)->first();
//
//                /*
//                 * if this is new user then go to signup page
//                 */
//                if ($getAlreadyUser == '') {
////                    $data = [
////                        'user_nid' => "",
////                        'user_email' => $email,
////                        'user_full_name' => $user_full_name,
////                        'password' => Hash::make('Google'),
////                        'is_approved' => 1,
////                        'first_login' => 1,
////                        'social_login' => 2,
////                        'security_profile_id' => 1
////                    ];
//                    Session::put('oauth_data', $oauth_data);
//                    Session::put('oauth_token', $oauth_token);
//
//                    //return redirect()->to('/osspid_signUp/');
////                    return redirect()->to('signup/identity-verify');
//
//                    //dd($oauth_token, $oauth_data, session()->get('oauth_data'), session()->get('oauth_token'));
//                    //return redirect()->route('signup.identity_verify_otp');
//
//                    //dd($oauth_data->mobile, $oauth_data);
//                    $otpService = new OtpService();
//                    $url = $otpService->generateOtpVerificationUrl($oauth_data->mobile);
//                    return redirect()->away($url);
//
//
//                } else if ($getAlreadyUser->user_status == 'rejected') {
//                    Session::put('oauth_data', $oauth_data);
//                    Session::put('oauth_token', $oauth_token);
//                    //return redirect()->to('/osspid_signUp/');
////                    return redirect()->to('signup/identity-verify');
//                    return redirect()->route('signup.identity_verify_otp');
//                } /*
//                 * if this is old user then login
//                 * check all issue like user type check, security profile check, session set etc
//                 * if everything ok then go to dashboard else back to login
//                 */
//                else {
//                    // user sign in
//                    $loggedin = Auth::loginUsingId($getAlreadyUser->id);
//                    if (!$loggedin) {
//                        Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDC103]');
//                        return redirect()->to('/login');
//                    }
//                    $loginCheck = new LoginController();
//
//
//                    // Check Maintenance Mode
//                    if ($loginCheck->checkMaintenanceModeForUser() === true) {
//                        $error_msg = session()->get('error');
//                        $this->osspidLogout();
//                        Session::flash('error', trim(preg_replace('/\s+/', ' ', $error_msg)). '[MMFU101]');
//                        return redirect()->to('/login');
//                    }
//
//
//                    // User type root activation checking
//                    $userTypeRootStatus = $loginCheck->_checkUserTypeRootActivation(Auth::user()->user_type, Auth::user()->company_ids, $is_ajax_request = true);
//                    if ($userTypeRootStatus['result'] == false) {
//                        $this->osspidLogout();
//                        Session::flash('error', $userTypeRootStatus['msg']. '[OSSPIDC105]');
//                        return redirect()->to('/login');
//                    }
//
//                    // user old session destroy and setting new session
//                    $loginCheck->killUserSession(Auth::user()->id);
//                    //Users::where('id', Auth::user()->id)->update(['login_token' => Encryption::encode(Session::getId())]);
//
//                    // if this user is approved but not active currently, then logout
//                    if (Auth::user()->is_approved == 1 && Auth::user()->user_status != 'active') {
//                        $this->osspidLogout();
//                        Session::flash('error', 'The user is not active, please contact with system admin/ <a href="/articles/support" target="_blank">Help line.</a>');
//                        return redirect()->to('/login');
//                    }
//
//                    // if this user is not verified in system then go back
//                    // if (Auth::user()->user_verification == 'no') {
//                    //     $this->osspidLogout();
//                    //     Session::flash('error', 'The user is not verified in ' . config('app.project_name') . ', please contact with system admin.');
//                    //     return redirect()->to('/login');
//                    // }
//
//                    // Check security profile of user e.g : IP, working hour etc.
//                    if (!$loginCheck->_checkSecurityProfile($request)) {
//                        $this->osspidLogout();
//                        Session::flash('error', 'Security profile does not support login from this network or time [SP2001]');
//                        return redirect()->to('/login');
//                    }
//
//
//                    //$this->_setCaption($usersModel);
//                    // put user info in session
//                    if ($loginCheck->_setSession() == false) {
//                        $this->osspidLogout();
//                        Session::flash('error', 'Session expired. Please Login again.[OSSPIDC106]');
//                        return redirect()->to('/login');
//                    }
//
//
//                    // Set delegated user id in session
//                    if (in_array(Auth::user()->user_type, ['4x404'])) {
//                        if (Auth::user()->delegate_to_user_id != 0) {
//                            Session::put('sess_delegated_user_id', Auth::user()->delegate_to_user_id);
//                        }
//                    }
//
//                    // Login user and redirect to dashboard/profile
//                    Session::put('oauth_token', $oauth_token);
//                    CommonFunction::GlobalSettings();
//                    $this->entryAccessLog();
//                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());
//
//                    $redirectPath = '/dashboard';
//                    $sessionMsg = "Logged in successfully, Welcome to " . config('app.project_name');
//
//                    if ($getAlreadyUser->first_login == 0) {
//                        $getAlreadyUser->first_login = 1;
//                        //$redirectPath = '/users/profileinfo'; // for first time login
//                        $sessionMsg = '<strong>Dear user,</strong><br><br>
//                <p>We noticed that your profile setting does not complete yet 100%.<br/>
//                    Update your <strong>User name,
//                        Profile Image, Designation, Signature and other useful information
//                    </strong>.
//                    You can not apply any type of registration without proper informational profile.
//                    <br><br>Thanks<br>' . config('app.project_name') . '</p>';
//                    }
//
//                    $getAlreadyUser->save();
//
//                    // only for applicant user
//                    // If company ids is single id then automatically set into working company id
//                    // else user need to select one company as current working company id and update this id into working company id
//
//                    if (Auth::user()->user_type == '5x505') {
//
//                        $companyIds = CommonFunction::getUserCompanyAllWithZeroWithoutEloquent();
//
//                        $user_multiple_company = 0; // flag
//
//                        if (count($companyIds) < 2) {
//                            $user_id = Auth::user()->id;
//                            $company_association_request = CommonFunction::getWorkingUserType(Auth::user()->company_ids);
//
//                            if (!empty($company_association_request)) {
//                                $working_user_type = $company_association_request->approved_user_type;
//
//                                DB::table('users')
//                                    ->where('id', $user_id)
//                                    ->update(['working_company_id' => $companyIds[0], 'working_user_type' => $working_user_type]);
//
//                            } else {
//
////                                $this->osspidLogout();
////                                Session::flash('error', 'User type not found in company association request for this company. Please contact with support team. [OSS-125]');
////                                return redirect()->to('/login');
//
//                                DB::statement("UPDATE users SET working_company_id = company_ids where id = $user_id");
//                            }
//
//                        } else {
//                            $user_multiple_company = 1;
//                            $pageTitle = 'Company selection';
//                            $last_working_company = CommonFunction::getCompanyNameById(Auth::user()->working_company_id);
//                            // No need to check company eligibility,
//                            // User will got all associated company
//                            $companyList = CompanyInfo::where('company_status', 1)
//                                ->where('is_approved', 1)
////                                ->where('is_eligible', 1)
//                                ->whereIn('id', $companyIds)
//                                ->where('is_rejected', 'no')
//                                ->get(['company_name', 'id']);
//
//                            return view('Dashboard::index', compact('user_multiple_company', 'companyList', 'last_working_company', 'pageTitle'));
//                        }
//                    }
//                    // end
//
//                    Session::flash('success', $sessionMsg);
//                    return redirect()->to($redirectPath);
//                }
//            } else {
//                Session::flash('error', 'Login failed. Please reload the page and try again.[OSSPIDL101]' . $verifyOauthToken);
//                return redirect()->to('/login');
//            }
//        } catch (\Exception $e) {
//            Auth::logout();
//            Session::flash('error', 'Login failed. Please reload the page and try again.[OSPIDC108]');
//            return redirect()->to('/login');
//        }
//    }


    public function osspid_signUp()
    {
        $user_types = ['' => 'Select One'] + UserTypes::orderBy('type_name')
                ->where('is_registarable', 0)->orWhere('is_registarable', -1)->orderBy('type_name', 'ASC')->lists('type_name', 'id')->all();

        $company_infos = ['' => 'Select One'] + CompanyInfo::where('is_approved', 1)->where('is_rejected', 'no')->where('company_status', 1)->orderBy('company_name', 'ASC')->lists('company_name', 'id')->all();

        $departments = ['' => 'Select One'] + DepartmentInfo::lists('name', 'id')->all();
        $desks = ['' => 'Select One'] + UserDesk::lists('desk_name', 'id')->all();
        //$security_questions = ['' => 'Select One'] + SecurityQuestion::lists('name', 'id')->all();
        $countries = Countries::orderby('name')->lists('nicename', 'iso');
        //$country_codes = Countries::orderby('name')->get();
        $nationalities = Countries::orderby('nationality')->where('nationality', '!=', '')->lists('nationality', 'iso', 'phonecode');
        $districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');
        return view("public_home.osspid_signup", compact("user_types", 'departments', 'country_codes', 'desks', 'security_questions', 'nationalities', 'countries', 'districts', 'company_infos'));
    }

    public function OsspidStore(Request $request)
    {
        $rules = [
            'user_first_name' => 'required',
//            'user_middle_name' => 'required',
//            'user_last_name' => 'required',
            'user_gender' => 'required|in:Male,Female',
            'user_DOB' => 'required|date|date_format:d-M-Y',
            'nationality' => 'required|alpha',

            'company_type' => 'required|in:1,2',
            'company_name' => 'required_if:company_type,2',
//            'company_name_bn' => 'required_if:company_type,2',
            'company_info' => 'required_if:company_type,1|numeric',
//            'department' => 'required_if:user_type,4x404',
//            'desk' => 'required_if:user_type,4x404',
            'user_nid' => 'required_if:nationality,==,BD|numeric|bd_nid',
            'passport_no' => 'required_unless:nationality,BD|passport',
//            'passport_no' => 'required_unless:nationality,BD|passport',
//            'passport_personal_no' => 'required_unless:nationality,BD',
//            'passport_surname' => 'required_unless:nationality,BD',
//            'passport_issuing_authority' => 'required_unless:nationality,BD',
//            'passport_given_name' => 'required_unless:nationality,BD',
            'passport_nationality' => 'required_unless:nationality,BD|alpha',
            'passport_DOB' => 'required_unless:nationality,BD|date|date_format:d-M-Y',
            'passport_place_of_birth' => 'required_unless:nationality,BD',
            'passport_date_of_issue' => 'required_unless:nationality,BD|date|date_format:d-M-Y|before:' . date('Y-m-d', strtotime("+1 day")),
            'passport_date_of_expire' => 'required_unless:nationality,BD|date|date_format:d-M-Y',
            'email' => 'required|email',
            'user_phone' => 'required|phone_or_mobile',
            'country' => 'required_if:nationality,==,BD|alpha',
            'district' => 'required_if:nationality,==,BD|numeric',
            'thana' => 'required_if:nationality,==,BD|numeric',
            'post_code' => 'required_if:nationality,==,BD|digits:4',
            'post_office' => 'required_if:nationality,==,BD',
            'road_no' => 'required_if:nationality,==,BD',
            'country_abroad' => 'required_unless:country,BD',
            'post_code_abroad' => 'required_unless:country,BD',
            'road_no_abroad' => 'required_unless:country,BD',
//            'state' => 'required_unless:country,BD',
//            'province' => 'required_unless:country,BD',
            'authorization_file' => 'required|mimes:pdf|max:3072',
//            'security_question_id' => 'required',
//            'security_answer' => 'required',
            'g-recaptcha-response' => 'required'
        ];

        $messages = [
            'user_DOB.required' => 'Date of birth is required.',
            'user_DOB.date_format' => 'The user d o b does not match the format d-M-Y (ex. 10-Jan-1991).',
            'passport_DOB.required' => 'Passport Date of birth is required.',
        ];

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

            $user_email = $request->get('email');
            $user = Users::firstOrNew(['user_email' => $user_email]);

            $user->user_first_name = $request->get('user_first_name');
            $user->user_middle_name = $request->get('user_middle_name');
            $user->user_last_name = $request->get('user_last_name');
            $user->user_gender = $request->get('user_gender');
            $user->user_DOB = CommonFunction::changeDateFormat($request->get('user_DOB'), true);
            $user->user_type = '5x505';
            $user->nationality = $request->get('nationality');
            $user->passport_no = $request->get('passport_no');
            $user->passport_personal_no = $request->get('passport_personal_no');
            $user->passport_surname = $request->get('passport_surname');
            $user->passport_issuing_authority = $request->get('passport_issuing_authority');
            $user->passport_given_name = $request->get('passport_given_name');
            $user->passport_nationality = $request->get('passport_nationality');
            $user->passport_DOB = $request->get('passport_DOB');
            $user->passport_place_of_birth = $request->get('passport_place_of_birth');
            $user->passport_date_of_issue = $request->get('passport_date_of_issue');
            $user->passport_date_of_expire = $request->get('passport_date_of_expire');
            $user->user_nid = $request->get('user_nid');
            $user->user_email = $request->get('email');
            $user->user_phone = $request->get('user_phone');
            $user->user_home_phone = $request->get('user_home_phone');
            $user->user_office_phone = $request->get('user_office_phone');
            $user->division = $request->get('division');
            $user->district = $request->get('district');
            $user->thana = $request->get('thana');
            $user->state = $request->get('state');
            $user->province = $request->get('province');
            $user->security_question_id = $request->get('security_question_id');
            $user->security_answer = $request->get('security_answer');
            $user->user_agreement = 1;
            $user->user_verification = 'yes';
            $user->first_login = 0;
            $user->social_login = 1;
            $user->user_status = 'inactive';

            if ($request->get('nationality') == 'BD') {
                $user->country = $request->get('country');
                $user->road_no = $request->get('road_no');
                $user->post_code = $request->get('post_code');
                $user->post_office = $request->get('post_office');
            } elseif ($request->get('nationality') != 'BD') {
                $user->country = $request->get('country_abroad');
                $user->road_no = $request->get('road_no_abroad');
                $user->post_code = $request->get('post_code_abroad');
            }

            // Company store
            if ($request->get('company_type') == 1) { // existing Company
                $user->company_ids = $request->get('company_info');
            } elseif ($request->get('company_type') == 2) {
                $companyData = CompanyInfo::where('company_name', $request->get('company_name'))
//                    ->orWhere('company_name_bn', $request->get('company_name_bn'))
                    ->orWhere(function ($query) use ($request) {
                        $query->where('company_name_bn', $request->get('company_name_bn'))
                            ->where('company_name_bn', '!=', '')
                            ->whereNotNull('company_name_bn');
                    })
                    ->first();
                if ($companyData) {
                    if ($companyData->is_rejected == 'no') {
                        DB::rollback();
                        Session::flash('error', 'Your company name is duplicate! Please give a unique name.');
                        return Redirect::back()->withInput();
                    } else {
                        $companyData->company_name = trim($request->get('company_name'));
                        $companyData->company_name_bn = trim($request->get('company_name_bn'));
                        $companyData->created_by = '';
                        $companyData->created_at = Carbon::now();
                        $companyData->is_rejected = 'no'; // again reset the rejected status and is_approved
                        $companyData->is_approved = 1; // when user is auto approved then 1
                        $companyData->company_status = 1; // company auto approved 1
                        $companyData->save();
                        $companyId = $companyData->id;
                    }
                } else {
                    $companyId = DB::table('company_info')->insertGetId([
                        'company_name' => trim($request->get('company_name')),
                        'company_name_bn' => trim($request->get('company_name_bn')),
                        'is_approved' => 1, // 1 = auto approved
                        'company_status' => 1, // 1 = auto approved
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => ''
                    ]);
                }
                $user->company_ids = $companyId;
                $user->working_company_id = $companyId;
                $user->is_approved = 1; // 1 = auto approved
                $user->user_status = 'active';
            }

            if ($request->hasFile('authorization_file')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $_authorization_file = $request->file('authorization_file');
                $full_name_concat = trim($user->user_first_name . ' ' . $user->user_middle_name . ' ' . $user->user_last_name);
                $full_name = str_replace(' ', '_', $full_name_concat);

                $authorization_file = ($companyId . '_' . $full_name . '_' . rand(0, 9999999) . '.' . $_authorization_file->getClientOriginalExtension());
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_authorization_file->move($path, $authorization_file);
                $authorization_file_path = $yearMonth . $authorization_file;
            }
            //$user->authorization_file = $authorization_file_path ? $authorization_file_path : null;
            $user->save();

            // Store company association request
            $company_association = new CompanyAssociation();
            if ($request->get('company_type') == 1)
            {
                $company_association->company_type = 'existing';
            } else {
                $company_association->company_type = 'new';
            }
            $company_association->user_id = $user->id;
            $company_association->company_name_en = trim($request->get('company_name'));
            $company_association->company_name_bn = trim($request->get('company_name_bn'));
            $company_association->authorization_letter = $authorization_file_path;
            $company_association->current_company_ids = 0;
            $company_association->requested_company_id = $user->company_ids;
            $company_association->approved_user_type = 'Employee';
            $company_association->request_type = 'Add';
            $company_association->user_remarks = 'Request from signup';
            $company_association->application_date = date('Y-m-d H:i:s');
            $company_association->status_id = 25;
            $company_association->status = 1;
            $company_association->save();
            // End Store company association request

            DB::commit();

//            if(in_array($request->get('user_type'), ['4x404', '14x141', '1x101'])){
//                Session::flash('success', 'Successfully Registered! To login, Wait until approval from System.');
//            }else{
            Session::flash('success', 'Successfully Registered! You can login to system.');
//            }
            return Redirect::to('/');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong[OSS_SIGNUP-001122]');
            return Redirect::back()->withInput();
        }
    }


    public function entryAccessLog()
    {
        // access_log table.
        $str_random = str_random(10);
        DB::table('user_logs')->insertGetId(
            array(
                'user_id' => Auth::user()->id,
                'login_dt' => date('Y-m-d H:i:s'),
                'ip_address' => \Request::getClientIp(),
                'access_log_id' => $str_random
            )
        );
        Session::put('access_log_id', $str_random);
    }

    /*
     * Entry access for Logout
     * update logout time in user_logs table
     */
    public function entryAccessLogout()
    {
        $access_log_id = Session::get('access_log_id');
        DB::table('user_logs')->where('access_log_id', $access_log_id)->update(['logout_dt' => date('Y-m-d H:i:s')]);
    }


    // For First Client
//    public function osspidLogout()
//    {
//        $oauth_token = Session::get('oauth_token');
//
//        if (!empty($oauth_token)) {
//            // Logout from the OSS-PID
//            if (Auth::user()) {
//                $this->osspid->logoutFromOsspid($oauth_token, Auth::user()->user_email);
//            }
//        }
//
//        if (Auth::user()) {
//            UsersModel::where('id', Auth::user()->id)->update(['login_token' => '']);
//        }
//
//        $this->entryAccessLogout();
//
//        Session::getHandler()->destroy(Session::getId());
//        Session::flush();
//        Auth::logout();
//        return redirect('/');
//    }


//    public function login_from_others_system()
//    {
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
//            $getAlreadyUser = Users::where('user_email', $email)->first();
//
//            if ($getAlreadyUser == '') {
//                $data = [
//                    //	'user_type' => '12x432',
//                    'user_nid' => "",
//                    'user_email' => "",
//                    'user_full_name' => "",
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
//                if ($getAlreadyUser->user_status == 'active') {
//                    Auth::loginUsingId($getAlreadyUser->id);
//                    Session::put('oauth_token', $oauth_token);
//                    $getAlreadyUser->login_token = Encryption::encode(Session::getId());
//                    $getAlreadyUser->save();
//                    $this->entryAccessLog();
//                    return redirect()->to('/dashboard');
//                } else {
//                    Session::flash('error', "User not activated!");
//                    return redirect()->to('/login');
//                }
//            }
//        } else {
//            dd("<h1>Invalid oauth token.</h1>", $verifyOauthToken);
//        }
//    }
}