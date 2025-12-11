<?php

namespace App\Http\Middleware;

use App\Http\Controllers\LoginController;
use App\Libraries\CommonFunction;
use App\Libraries\UtilFunction;
use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class checkAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user_type = Auth::user()->user_type;
        $user = explode("x", $user_type); // $user[0] array index stored the users level id
        $uri = $request->segment(1);

        // Temporary for MIS user
        if (in_array($user_type, ['15x151', '3x308'])) {
            Session::flash('success', 'You don\'t have access to open the application');
            return redirect('dashboard');
        }


        $security_check_time = Session::get('security_check_time');
        $current_time = Carbon::now();
        $difference_in_minute = $current_time->diffInMinutes($security_check_time);

        /*
         * Some common conditions will be checked periodically. (Ex: after every 3 minutes and after login)
         * If there is a condition that needs to be checked for each URL,
         * then it has to be given below this condition.
         */
        if ($difference_in_minute >= 3 or (Session::get('is_first_security_check') == 0)) {

            Session::put('is_first_security_check', 1);
            $security_check_time = Carbon::now();
            Session::put('security_check_time', $security_check_time);

            // check the user is approved
            if (Auth::user()->is_approved == 0) {
                return redirect()
                    ->intended('/dashboard')
                    ->with('error', 'You are not approved user ! Please contact with system admin');
            }

            // check Security Profile info
            $loginController = new LoginController;
            if (!$loginController->_checkSecurityProfile($request)) {
                Session::flash('error', 'Security profile does not support in this time for operation.');
                return redirect()->away(UtilFunction::logoutFromKeyCloak());
            }

            if ($user_type == '5x505' && CommonFunction::checkFeedbackItem() == false) {
                $maximumPendingFeedback = DB::table('configuration')->where('caption', 'FeedbackItem')->first()->value;
                Session::flash("error", "There is more than $maximumPendingFeedback feedback pending in your list. Please give your valuable feedback!  <a href='/process/list/feedback-list'>click here</a> ");
                return redirect('dashboard');
            }

            // check User's profile complete info (ex- image, signature, name etc)
            if (CommonFunction::checkCompleteProfileInfo() == false) {
                Session::flash('checkProfile', '');
                return redirect()->intended('/users/profileinfo');
            }
        }

        /*
         * Some common conditions will be checked periodically. (Ex: after every 1 minutes and after login)
         * If there is a condition that needs to be checked for each URL,
         * then it has to be given below this condition.
         */
        if ($difference_in_minute >= 1) {
            // check feedback
            if ($user_type == '5x505' && !empty(Session::get('irms_feedback_tracking_number'))) {
                return redirect('/dashboard');
            }

            // check the user is delegated
            if (Auth::user()->delegate_to_user_id != 0) {
                return redirect('users/delegate');
            }
        }

        // Company eligibility is not mandatory for Basic Information only
        // But, for others module/application it is mandatory
        if ($uri != 'basic-information' && \App\Libraries\CommonFunction::checkEligibility() != 1 and ($user_type == '5x505')) {
            Session::flash('error', 'You are not eligible for apply ! [CAM1020]');
            return redirect('dashboard');
        }

        switch (true) {
            case (in_array($uri, ['company-association', 'ipn']) && (in_array($user[0], [1, 2, 5]))):
            case (in_array($uri, [
                    'basic-information',
                    'work-permit-new',
                    'work-permit-extension',
                    'work-permit-amendment',
                    'work-permit-cancellation',
                    'remittance-new',
                    'office-permission-new',
                    'office-permission-extension',
                    'office-permission-amendment',
                    'office-permission-cancellation',
                    'project-office-new',
                    'project-office-extension',
                    'project-office-amendment',
                    'project-office-cancellation',
                    'new-reg',
                    'new-reg-foreign',
                    'new-connection-bpdb',
                    'new-connection-dpdc',
                    'new-connection-breb',
                    'new-connection-nesco',
                    'new-connection-desco',
                    'cda-lspp',
                    'cda-oc',
                    'cda-bcc',
                    'trade-license-dscc',
                    'ctcc',
                    'e-tin-foreigner',
                    'bfscd-noc-exiting',
                    'bfscd-noc-proposed',
                    'sb-account',
                    'new-connection-wzpdcl',
                    'dncc',
                    'dcci-cos',
                    'rajuk-luc-general',
                    'wasa-new-connection',
                    'wasa-dt',
                    'mutation-land',
                    'company-registration-sf',
                ]) && (in_array($user[0], [1, 2, 4, 5, 13, 14]))):
            case (in_array($uri, [
                    'visa-recommendation',
                    'visa-recommendation-amendment',
                    'waiver-condition-7',
                    'vip-lounge',
                    'bida-registration',
                    'bida-registration-amendment',
                    'irc-recommendation-new',
                    'irc-recommendation-second-adhoc',
                    'irc-recommendation-third-adhoc',
                    'irc-recommendation-regular',
                    'import-permission'
                ]) && (in_array($user[0], [1, 2, 4, 5, 14]))):
            case ($uri == 'waiver-condition-8' && in_array($user[0], [1, 2, 4, 5, 6, 14])):
            case (in_array($uri, ['doe', 'industrial-IRC', 'erc']) && (in_array($user[0], [1, 2, 3, 4, 5, 7, 8, 9, 13, 15]))):
            case (in_array($uri, ['settings','security-clearance']) && (in_array($user[0], [1, 2, 4]))):
            case (in_array($uri, ['licence-application', 'licence-applications', 'single-licence'])  and (in_array($user[0], [1, 2, 4, 5, 7, 8, 9, 13, 14]))):
            case ($uri == 'dashboard' && (in_array($user[0], [1, 2, 3, 4, 5, 6]))):
            case ($uri == 'users' && in_array($user[0], [1, 2, 3, 4, 5, 6, 13, 14])):
            case ($uri == 'process-path' && $user[0] == 1):
            case ($uri == 'spg' and (in_array($user[0], [1, 2, 4, 5, 10]))):
            case ($uri == 'meeting-form' and (in_array($user[0], [1, 4, 13]))):
            case ($uri == 'board-meting' and (in_array($user[0], [1, 2, 4, 5, 7, 8, 13]))):
            case ($uri == 'log-viewer' and (in_array($user[0], [1, 2]))):
            case ($uri == 'training' && checkUserPermissionTraining()):
                return $next($request);
                break;
            default:
                Session::flash('error', 'Invalid URL ! error code(' . $uri . '-' . $user[0] . ')    ');
                return redirect('dashboard');
        }
    }
}

