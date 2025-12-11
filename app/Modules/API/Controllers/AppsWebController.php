<?php

namespace App\Modules\API\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\API\Models\OssAppMisPermission;
use App\Modules\API\Models\OssAppUser;
use App\Modules\API\Models\OssMisReport;
use App\Modules\API\Models\OssMisReportAccessLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class AppsWebController extends Controller
{
    /***
     * Mis Report View
     * @param $mis_user_id
     * @param $report_id
     * @param $unix_time
     * @return \BladeView|bool|\Illuminate\View\View
     */

    public function misReportView($report_id, $permission_id, $unix_time)
    {

        list($mis_report_id, $reg_key) = explode('||', Encryption::decode($report_id));

        $mis_permission_id = Encryption::decodeId($permission_id);
        $unix_time = Encryption::decode($unix_time);
        $currentTime = Carbon::now();
        if (($misReport = OssMisReport::where([
                'id' => $mis_report_id,
                'is_public' => 1
            ])->first(['oss_app_mis_reports.title', 'oss_app_mis_reports.query'])) == null) {

            $misReport = OssAppMisPermission::leftjoin('oss_app_mis_reports', 'oss_app_mis_reports_permission.report_id', '=', 'oss_app_mis_reports.id')
                ->where('oss_app_mis_reports_permission.id', $mis_permission_id)
                ->where('oss_app_mis_reports_permission.valid_until', '>', $currentTime)
                ->where('oss_app_mis_reports.status', 1)
                ->first(['oss_app_mis_reports.title', 'oss_app_mis_reports.query']);
        }
        if ($misReport == null) {
            return "Your Access Denied";
        }

        $clientIP = \Request::getClientIp(true);

        $userData = OssAppUser::leftjoin('users', 'users.user_email', '=', 'oss_app_users.user_id')->where('oss_app_users.reg_key', $reg_key)->first(['users.id','users.user_type']);

        OssMisReportAccessLog::create(array(
            'user_id' => $userData->id,
            'ip' => $clientIP,
            'access_time' => $currentTime
        ));

        if ($unix_time + (2 * 60 * 60) < time()) {// 2 hours
            return "Your access time over, please again go to apps and refresh.";
        }

        $title = $misReport->title;
        $sql = $misReport->query;
        $sql = preg_replace('/&gt;/', '>', $sql);
        $sql = preg_replace('/&lt;/', '<', $sql);

        // Replace dynamic parameter
        $search_for = array(0 => '{$USER_ID}', 1 => '{$USER_TYPE}');
        $replace_with = array(0 => $userData->id, 1 => $userData->user_type);
        $sql = str_replace($search_for, $replace_with, $sql);

        $sql = $this->sqlSecurityGate($sql);
        $result = null;
        try {
            $result = DB::select(DB::raw($sql));
        } catch (QueryException $e) {
            echo $e->getMessage();
        }
        $reportResult = 'Data not found';
        if ($result) {
            $result2 = array();
            foreach ($result as $value):
                $result2[] = $value;
                if (count($result2) > 999) {// Max result to view
                    break;
                }
            endforeach;
            $reportResult = createHTMLTable($result2, 45);
        }
        return view("API::MisReport.mis-report-view", compact('reportResult', 'title'));
    }

    public function sqlSecurityGate($sql)
    {
        $sql = trim($sql);
        if (strlen($sql) < 8) {
            dd('Sql is not Valid: ' . $sql);
        }
        $select_keyword = strtoupper(substr($sql, 0, 7));
        $semicolon = strpos($sql, ';');
        if (($select_keyword == 'SELECT ') AND $semicolon == '') {
            return $sql;
        } elseif ((substr($select_keyword, 0, 5) == 'SHOW ' OR $select_keyword == 'EXPLAIN' OR substr($select_keyword, 0, 5) == 'DESC ')
            AND $semicolon == '' AND (Auth::user()->user_type == '1x101' OR Auth::user()->user_type == '15x151')) {
            return $sql;
        } else {
            dd('Sql is not Valid: ' . $sql);
        }
    }

    /**
     * App Serch Templete Result
     * @param $enc_regKey
     * @param $keyword
     * @return \BladeView|bool|\Illuminate\View\View|null
     */

    public function appSearch($enc_regKey, $keyword)
    {
        $regKey = Encryption::decode($enc_regKey);
        $keyword = urldecode($keyword);
        $appUserExit = OssAppUser::where('reg_key', $regKey)
            ->where('status', 1)->first();
        if (!$appUserExit) {
            return null;
//           TODO:: For unauthrize or time over for this user
        }
        return view("API::AppSearch.app-search-view", compact('keyword'));
    }


    public function viewImage($enc_user_id)
    {
        $user_id = Encryption::decodeId($enc_user_id);
        $userInfo = User::where('id', $user_id)->first(['user_pic']);
        if (strlen($userInfo->user_pic) == 0 || $userInfo->user_pic == null || $userInfo->user_pic == '') {
            $userInfo->user_pic = 'default_profile.jpg';
        }
        $path = 'users/upload/' . $userInfo->user_pic;
        $finfo = getimagesize($path);
        $mime = $finfo['mime'];
        header("Content-Type:$mime");
        echo file_get_contents($path);
        exit;
    }
}