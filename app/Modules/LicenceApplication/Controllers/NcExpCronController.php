<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\MonitorLog;
use App\Modules\LicenceApplication\Models\NameClearance\NameClearance;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NcExpCronController extends Controller
{
    /*
    * File full path
    */
    private $file_path = '';


    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        $this->file_path = dirname(__FILE__)."/".basename(__FILE__);
        $this->file_path = str_replace('\\', '/', $this->file_path);
    }

    // Sending Notification
    public function expirednotification(){
        $count = 0;
        $currnet_date = Carbon::now()->format('Y-m-d').'T'.Carbon::now()->format('H:i:s');
        $expired_date = Carbon::now()->addDays(30);

        $expired_date = $expired_date->format('Y-m-d').'T'.$expired_date->format('H:i:s');
//        dd($expired_date);
        $ncdata = ProcessList::join('nc_apps as nc',function ($join) use ($currnet_date,$expired_date){
            $join->on('nc.id','=','process_list.ref_id');
            $join->on('nc.before_last_day_email','=',DB::raw(0));
//            $join ->where('cert_valid_until','>',"'".$currnet_date."'");
//            $join ->where('cert_valid_until','<',"'".$expired_date."'");
        })

            ->whereBetween('nc.cert_valid_until', [$currnet_date, $expired_date])
            ->where('status_id', 1)
            ->where('process_type_id',107)
            ->limit(20)->get([
                'nc.*',
                'process_list.tracking_no'
            ]);
        foreach($ncdata as $ncinfo){
            $applicantEmailPhone = Users::where('id', $ncinfo->created_by)
                ->get(['user_email', 'user_phone']);
            if (count($applicantEmailPhone)>0 && $ncinfo->cert_valid_until != ''){
                $all_info = [];
                foreach ($applicantEmailPhone as $value){
                    $receiver_info = array(
                        'user_email' => $value->user_email,
                        'user_phone' => $value->user_phone,
                    );
                    array_push($all_info,$receiver_info);
                }

                $app_info =array(
                    'tracking_no' => $ncinfo->tracking_no,
                    'exp_date' => $ncinfo->cert_valid_until
                );

                $now = time();
                $your_date = strtotime($ncinfo->cert_valid_until);
                $date_diff = $your_date - $now;
                $diffDays = round($date_diff / (60 * 60 * 24));
                $ncforupdate = NameClearance::find($ncinfo->id);
                if($diffDays < 30 AND $diffDays > 0){
                    CommonFunction::sendEmailSMS('nc_expired_date_notification',$app_info,$all_info);
                    $ncforupdate->before_last_day_email = 1;
                    $ncforupdate->update();
                    $count++;
                }else if($diffDays < 0){
                    $ncforupdate->before_last_day_email = -1;
                    $ncforupdate->update();
                }
            }

        }
        if($count == 0){
            echo "No Data Found";
        }else{
            MonitorLog::cronAuditSave($this->file_path, 0, $count, 'nc-expired-date-notification[NC-1]');
            echo $count. " Row inserted successfully";
        }
    }
}