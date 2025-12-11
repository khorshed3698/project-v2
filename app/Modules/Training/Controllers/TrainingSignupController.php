<?php

namespace App\Modules\Training\Controllers;

use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\DB;
use App\Modules\Users\Models\Users;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use App\Modules\Signup\Controllers\UserVerificationDataController;

class TrainingSignupController extends Controller
{
    
    public function identityVerifyConfirm(Request $request)
    {

        $rules = [];
        $messages = [];

        $rules['user_name'] = 'required';
        $rules['user_email'] = 'required';
        $rules['user_mobile_no'] = 'required';
        $rules['user_gender'] = 'required';
        $rules['g-recaptcha-response'] = 'required';

        $this->validate($request, $rules, $messages);

        try {

            DB::beginTransaction();
            $user_email = Session::get('oauth_data')->user_email;
            $user_mobile_no = Session::get('oauth_data')->mobile;
            $user_name = Session::get('oauth_data')->user_full_name;
            $user = Users::firstOrNew(['user_email' => $user_email]);
            $user->user_first_name = $user_name;
            $user->user_middle_name = '';
            $user->user_last_name = '';
            $user->user_type = traineeUserType();
            $user->user_email = $user_email;
            $user->working_user_type = 'Trainee';
            $user->designation = 'Trainee';
            $user->identity_type = 'none';
            $user->nationality_type = 'Bangladeshi';
            $user->user_phone = $user_mobile_no;
            $user->user_gender = Session::get('oauth_data')->gender ? ucfirst(Session::get('oauth_data')->gender) : 'Not defined';

            $user->user_agreement = 1;
            $user->user_verification = 'yes';
            $user->first_login = 0;
            $user->social_login = 0;
            $user->company_ids = 0;
            $user->working_company_id = 0;
            $user->is_approved = 1; // 1 = auto approved
            $user->user_status = 'active';
            $user->save();
            

            // User login
            Auth::loginUsingId($user->id);
            CommonFunction::GlobalSettings();
            $user->login_token = Encryption::encode(Session::getId());
            $user->save();

            // Update user verification data
            $UserVerificationDataController = new UserVerificationDataController();
            $UserVerificationDataController->updateNidTinPassportUserVerificationData($user_email);


            DB::commit();

            // Forget session data

            Session::flash('success', 'Successfully Registered! You can start training now.');
            return Redirect::to('training/upcoming-course');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('TrainingSignupController : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TSC-125]');
            Session::flash('error', $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            // Session::flash('error', 'Something went wrong [TSC-125]');
            return Redirect::back()->withInput();
        }
    }

}
