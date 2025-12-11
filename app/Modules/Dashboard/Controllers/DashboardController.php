<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Settings\Models\DashBoardSlider;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\Dashboard\Models\Dashboard;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Users\Models\UserLogs;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
class DashboardController extends Controller
{
    public function index(Dashboard $dashboard, Request $request)
    {
        $log = date('H:i:s', time());
        $dbMode = Session::get('DB_MODE');
        $log .= ' - ' . date('H:i:s', time());
        $log .= ' - ' . date('H:i:s', time());
        $notice = CommonFunction::getNotice(1, 4);
        $dashboardObject = $dashboard->getDashboardObject();

        $pageTitle = 'Dashboard';
        $user_type = Auth::user()->user_type;
        $working_company_id = CommonFunction::getUserWorkingCompany();
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $userDeskIds = CommonFunction::getUserDeskIds();
        $departmentIds = CommonFunction::getUserDepartmentIds();
        $subDepartmentIds = CommonFunction::getUserSubDepartmentIds();
        $divisionIds = UtilFunction::getUserDivisionIds();
        $userId = CommonFunction::getUserId();

        // User wise permission for menu (Sidebar) and widget (Dashboard)
        CommonFunction::setAccessibleProcessTypeList();

        $services = ProcessType::where('process_type.status', '!=', 0)
            ->whereIn('process_type.id', Session::get('accessible_process'))
            ->orderBy('name', 'asc')
            ->get(['id', 'panel', 'icon', 'name', 'form_url',])->toArray();

        if ($user_type =='5x505') {
            $user_applications = DB::select("select
                COUNT(if(status_id=25,1,NULL)) as approved,
                COUNT(if(status_id=-1 || status_id=3 || status_id=5 || status_id=15 || status_id=17 || status_id=22 || status_id=32,1,NULL)) as my_desk_app,
                COUNT(if(status_id=4 ||  status_id= 6 || status_id=7,1,NULL)) as others,
                COUNT(if(status_id != -1 && status_id != 3 && status_id != 4 && status_id != 5 && status_id != 6 && status_id != 7 && status_id != 15 && status_id != 17 && status_id != 22 && status_id != 25 && status_id != 32,1,NULL)) as in_process_app
                from process_list 
                where company_id = $working_company_id and process_type_id not in (100)");

        } else { //for desk user
            $from = Carbon::now();
            $to = Carbon::now();
            $from->subMonths(3); //maximum 3 month data selection by default

            $process_wise_app_count = ProcessType::leftJoin('process_list', 'process_list.process_type_id', '=', 'process_type.id')
                ->groupBy('process_type.id')
                ->select([
                    'process_type.id',
                    'process_type.name',
                    'process_type.panel',
                    'process_type.form_url',
                    'process_type.icon',
                    DB::raw('COUNT(process_type.id) as totalApplication')
                ])
                ->where('process_type.status', '!=', 0)
                ->whereIn('process_type.id', Session::get('accessible_process'))

                ->where(function ($query1) use ($companyIds, $userDeskIds, $departmentIds, $userId, $subDepartmentIds, $from, $to, $divisionIds, $user_type) {

                    // System admin can only view the application without Draft status
                    if (in_array($user_type, ['1x101', '1x102', '2x202', '15x151', '3x308'])) {
                        $query1->whereNotIn('process_list.status_id', [-1, 3]);
                    }
                    if (in_array(CommonFunction::getUserType(), ['9x901', '9x902', '9x903', '9x904'])) {
                        $query1->whereIn('process_list.desk_id', $userDeskIds)
                            ->where(function ($query2) use ($userId) {
                                $query2->where('process_list.user_id', $userId)
                                    ->orWhere('process_list.user_id', 0);
                            })
                            ->where('process_list.desk_id', '!=', 0)
                            ->whereNotIn('process_list.status_id', [-1]);
                    }
                    // General users can only view the applications related to their company
                    if ($user_type == '5x505') {
                        $query1->whereIn('process_list.company_id', $companyIds);
                    }
                    // Desk User can only view the applications related to their desk and department
                    // and status id is not Draft or Shortfall
                    elseif ($user_type == '4x404') {
                        $query1->where(function ($query1) use ($userDeskIds, $departmentIds, $userId, $subDepartmentIds, $divisionIds) {
                            $query1->where(function ($query2) use ($userDeskIds, $departmentIds, $userId, $subDepartmentIds, $divisionIds) {
                                $query2->where(function ($query3) use ($departmentIds) {
                                    $query3->whereIn('process_list.department_id', $departmentIds)
                                        ->orWhere('process_list.department_id', 0);
                                })
                                    ->where(function ($query3) use ($subDepartmentIds) {
                                        $query3->whereIn('process_list.sub_department_id', $subDepartmentIds)
                                            ->orWhere('process_list.sub_department_id', 1);
                                    })
                                    ->where(function ($query3) use ($divisionIds) {
                                        $query3->whereIn('process_list.approval_center_id', $divisionIds)
                                            ->orWhere('process_list.approval_center_id', 0);
                                    });

                                if (!in_array(23, $userDeskIds)) { // 23 = DG desk user
                                    $query2->whereIn('process_list.desk_id', $userDeskIds)
                                        ->where(function ($query3) use ($userId) {
                                            $query3->where('process_list.user_id', $userId)->orWhere('process_list.user_id', 0);
                                        });
                                }
                            })
                                ->orWhere('process_list.user_id', $userId);
                        })
                            ->whereNotIn('process_list.status_id', [-1, 19])
                            ->whereBetween('process_list.updated_at', [$from, $to]);
                    }
                })
                ->get();

            foreach ($services as $key => $service) {
                $widget_apps = $process_wise_app_count->where('id', $service['id'])->first();
                if ($widget_apps) {
                    $services[$key]['totalApplication'] = $widget_apps->totalApplication;
                } else {
                    $services[$key]['totalApplication'] = 0;
                }
            }
        }

        $deshboardObject = [];
        if ($user_type == '1x101') {
            $deshboardObject = DB::table('dashboard_object')->where('db_obj_status', 1)->where('db_obj_type', '=', 'PIE_CHART')->get();
            $dashboardObjectBarChart = DB::table('dashboard_object')->where('db_obj_status', 1)->where('db_obj_type', 'BAR_CHART')->get();
            $dashboardObjectCanvas = DB::table('dashboard_object')->where('db_obj_status', 1)->where('db_obj_type', 'CANVAS')->get();
        }

        $last_login_time = UserLogs::JOIN('users', 'users.id', '=', 'user_logs.user_id')
            ->where('user_logs.user_id', '=', Auth::user()->id)
            ->orderBy('user_logs.id', 'desc')
            ->skip(1)->take(1)
            ->first(['user_logs.login_dt']);
        if ($last_login_time != "")
            $last_login_time = date("d-M-Y h:i", strtotime($last_login_time->login_dt));
        Session::put('last_login_time', $last_login_time);
        Session::put('user_pic', Auth::user()->user_pic);

        //  automatically processed list can access only system admin and desk user
        if (in_array($user_type, ['4x404', '1x101'])) {
            // Auto process list generation
            $holiday = DB::select(DB::raw('select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1'));;
            $holidays = explode(',', $holiday[0]->holiday_date);

            $autoProcessTypes = ProcessType::where('auto_process', 1)->get()->all();
            $autoProcessList = [];
            foreach ($autoProcessTypes as $autoProcess) {
                $autoProcessList[$autoProcess->id] = [
                    'process_type_id' => $autoProcess->id,
                    'process_type_name' => $autoProcess->name,
                    'process_by_today' => 0,
                    'today_tracking_no' => '',
                    'process_by_tomorrow' => 0,
                    'tomorrow_tracking_no' => '',
                ];
            }

            $processList = DB::select(DB::raw('select tracking_no,desk_id,process_type_id, ref_id,process_list.updated_at,
                process_type.name as process_name, process_type.max_processing_day, process_type.form_url from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                where process_type.auto_process = 1 and process_type.id=process_list.process_type_id AND process_list.status_id NOT IN(-1, 5, 6, 25)'));

            foreach ($processList as $process) {
                $result1 = $this->holidayAndOffDay($process->updated_at, $holidays);
                // Process by today
                if ($result1 == $process->max_processing_day) {
                    $autoProcessList[$process->process_type_id]['process_by_today'] += 1;
                    $autoProcessList[$process->process_type_id]['today_tracking_no'] .= $process->tracking_no . ', ';
                    $autoProcessList[$process->process_type_id]['app_details'][$process->ref_id] = [
                        'app_id' => $process->ref_id,
                        'tracking_no' => $process->tracking_no
                    ];
                }

                // Process by tomorrow
                if ($result1 + 1 == $process->max_processing_day) {
                    $autoProcessList[$process->process_type_id]['process_by_tomorrow'] += 1;
                    $autoProcessList[$process->process_type_id]['tomorrow_tracking_no'] .= $process->tracking_no . ', ';
                    $autoProcessList[$process->process_type_id]['app_details'][$process->ref_id] = [
                        'app_id' => $process->ref_id,
                        'tracking_no' => $process->tracking_no
                    ];
                }
            }

            foreach ($autoProcessList as $process) {
                $autoProcessList[$process['process_type_id']]['today_tracking_no'] = chop($process['today_tracking_no'], ', ');
                $autoProcessList[$process['process_type_id']]['tomorrow_tracking_no'] = chop($process['tomorrow_tracking_no'], ', ');
            }
        }

        //pending feedback app
        $pendingFeedbackApplication = ProcessList::where('is_feedback', '=', 0)
            ->where(function ($query1) use ($companyIds, $userDeskIds, $departmentIds, $userId, $user_type) {
                if ($user_type == '5x505') {
                    $query1->whereIn('process_list.company_id', $companyIds);
                    $query1->where('process_list.status_id', 25);
                } elseif ($user_type == '4x404') {
                    $query1->whereIn('process_list.desk_id', $userDeskIds)
                        ->where(function ($query2) use ($departmentIds) {
                            $query2->whereIn('process_list.department_id', $departmentIds)
                                ->orWhere('process_list.department_id', 0);
                        })
                        ->where(function ($query2) use ($userId) {
                            $query2->where('process_list.user_id', $userId)
                                ->orWhere('process_list.user_id', 0);
                        })
                        ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);
                }

                if (in_array($user_type, ['1x101', '2x202', '15x151', '3x308'])) {
                    $query1->whereNotIn('process_list.status_id', [-1, 5]);
                }
                if (in_array(CommonFunction::getUserType(), ['9x901', '9x902', '9x903', '9x904'])) {
                    $query1->whereIn('process_list.desk_id', $userDeskIds)
                        ->where(function ($query2) use ($userId) {
                            $query2->where('process_list.user_id', $userId)
                                ->orWhere('process_list.user_id', 0);
                        })
                        ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);
                }
            })
            ->count();

        $appInfo = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.company_id', Auth::user()->company_ids)
            ->where('process_list.status_id', '!=', 2)
            ->first([
                'ea_apps.id',
                'ea_apps.is_new_for_stakeholders',
                'ea_apps.is_existing_for_stakeholders',
                'ea_apps.is_new_for_bida',
                'ea_apps.is_existing_for_bida',
            ]);

        // 1 = show , 0 = hide
        $newStakeholder = $existingStakeholder = $newBida = $existingBida = 1;
        if (!empty($appInfo)) {
            if ($appInfo->is_new_for_bida == 1) {
                $newStakeholder = $existingStakeholder = $existingBida = 0;
            }
            if ($appInfo->is_existing_for_bida == 1) {
                $newStakeholder = $existingStakeholder = $newBida = 0;
            }

            if ($appInfo->is_new_for_stakeholders == 1) {
                $newStakeholder = 1;
                $existingStakeholder = 0;
                if ($appInfo->is_new_for_bida == 1) {
                    $existingBida = 0;
                } else if ($appInfo->is_existing_for_bida == 1) {
                    $newBida = 0;
                }
            } else if ($appInfo->is_existing_for_stakeholders == 1) {
                $newStakeholder = 0;
                $existingStakeholder = 1;
                if ($appInfo->is_new_for_bida == 1) {
                    $existingBida = 0;
                } else if ($appInfo->is_existing_for_bida == 1) {
                    $newBida = 0;
                }
            }
        }

        $stakeholder_services = $this->getStakeholderServices($working_company_id, false);
        $stakeholderServices = $this->getStakeholderServicesData($stakeholder_services);

        $featured_stakeholder_services = $this->getStakeholderServices($working_company_id, true);
        $featuredStakeholderServices = $this->getStakeholderServicesData($featured_stakeholder_services);

        //IRMS feedback initiate list
        $irms_feedback_initiate_list = UtilFunction::getIrmsFeedbackInitiateList($companyIds);

        //Irms feedback initiate deadline expire date session store
        UtilFunction::isIrmsFeedbackSubmissionDateExpired($companyIds);

        $all_services = ProcessType::where('status', 1)
            ->where(function ($query) {
                $query->whereIn('id', Session::get('accessible_process'))
                    ->orWhere('bida_service_status','2'); // stakeholder
            })
            ->orderBy('service_name', 'asc')
            ->select([
                'id',
                'form_url',
                DB::raw("CONCAT(IF (bida_service_status=1,'BIDA Service: ',''),`process_supper_name`,'- ',`process_sub_name`) AS service_name")
            ])->get();

        $dashBoardSlider = DashBoardSlider::where('is_active', 1)
            ->orderBy('order', 'ASC')
            ->take(5)
            ->get();

        $featured_services = ProcessType::where('status', 1)
            ->where('featured', 1)
            ->get(['acl_name', 'form_url', 'name', 'id'])
            ->map(function ($service) {
                $url = URL::to('process/'.$service->form_url.'/add/'.\App\Libraries\Encryption::encodeId($service->id));
                $service->url = $url;
                return $service;
            });
        $draft_applications = CommonFunction::getApplicationsByStatus(-1, $working_company_id, 0);
        $shortfall_applications = CommonFunction::getApplicationsByStatus(5, $working_company_id, 1);

        return view('Dashboard::index', compact('log', 'dbMode', 'notice', 'services', 'deshboardObject', 'dashboardObject',
            'pageTitle', 'dashboardObjectBarChart', 'dashboardObjectCanvas', 'autoProcessList', 'pendingFeedbackApplication',
            'newStakeholder', 'existingStakeholder', 'newBida', 'existingBida', 'appInfo', 'stakeholder_services', 'stakeholderServices', 'featuredStakeholderServices', 'featured_services', 'user_applications', 'irms_feedback_initiate_list', 'all_services', 'dashBoardSlider', 'draft_applications', 'shortfall_applications'));
    }

    public function getStakeholderServices($workingCompanyId, $featuredOnly = false)
    {
        $query = ProcessType::where('status', 1);

        if ($featuredOnly) {
            $processSupperName = $query->where('featured', 1)->value('process_supper_name');
    
            if ($processSupperName) {
                $query = ProcessType::where('process_supper_name', $processSupperName);
            }
        } else {
            $query->where('bida_service_status', 2);
        }

        return $query->orderBy('process_supper_name', 'asc')
            ->orderBy('process_sub_name', 'asc')
            ->select([
                'id',
                DB::raw("(select count(id) from process_list where process_list.process_type_id = process_type.id and company_id = $workingCompanyId) as total_app"),
                'logo',
                'form_url',
                'process_supper_name',
                'process_sub_name',
            ])->get();
    }

    public function getStakeholderServicesData($services)
    {
        $stakeholderServices = [];
        foreach ($services as $service) {
            $process_supper_name = $service->process_supper_name;
            $logo = $service->logo;
            $process_sub_name = $service->process_sub_name;
            if($service->total_app > 0) {
                $form_url = $service->form_url.'/list/'.Encryption::encodeId($service->id);
            } else {
                $form_url = 'process/'.$service->form_url.'/add/'.Encryption::encodeId($service->id);
            }

            if (!isset($stakeholderServices[$process_supper_name])) {
                $stakeholderServices[$process_supper_name] = [
                    'process_supper_name' => $process_supper_name,
                    'logo' => $logo,
                    'process_sub_names' => []
                ];
            }

            $stakeholderServices[$process_supper_name]['process_sub_names'][] = [
                'name' => $process_sub_name,
                'url' => $form_url,
                'id' => $service['id'],
            ];
        }

        // Convert to indexed array
        return array_values($stakeholderServices);
    }

    public function holidayAndOffDay($updated_date, $holidays)
    {
        // Last updated time of application
        $start = new DateTime(date('Y-m-d', strtotime($updated_date)));

        // eliminate first date
        $start->modify('+1 day');

        // Today's date
        $end = new DateTime(date('Y-m-d'));
        $end->modify('+1 day'); // add 1 day with today's date

        // Get interval between start date and end date
        $interval = $end->diff($start);

        // Interval in day's
        $days = $interval->days;

        // create an iterable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);


        /*
         * Checking every day of $period,
         * whether, the day is in Friday or Saturday or in Holiday list
         */
        foreach ($period as $dt) {
            $curr = $dt->format('D');
            if ($curr == 'Fri' || $curr == 'Sat') {
                $days--;
            } elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;
            }
        }

        // return $days - 1;

        return $days;

    }


    // public function featuresFeedback(Request $request)
    // {
    //     $id = Encryption::decodeId($request->get('id'));
    //     $value = $request->get('value');
    //     Feedback::create([

    //         'feedback'=>$value,
    //         'feature_id'=>$id,
    //         'type' => 2,
    //         'user_id'=> Auth::user()->id,
    //     ]);
    //     return response()->json(['ResponseCode'=>0,'status'=>'success']);
    // }
    // public function skipNextFeedback(Request $request){

    //     SurveyFeaturesConfig::create([
    //         'is_skip'=> $request->value,
    //         'user_id'=> Auth::user()->id,
    //     ]);
    //     return response()->json(['ResponseCode'=>0,'status'=>'success']);

    // }
//    public function featureShowOld(Request $request){
//        $user_id = Auth::user()->id;
//        $featureFeedback   = SurveyFeaturesConfig::where('user_id',$user_id)
//            ->orderby('created_at','desc')->first();
//        if($featureFeedback){ // already view a survey
//
//            // next desc
//            $featureNextFeedback = SurveyFeaturesConfig::where('user_id',$user_id)
//                ->where('is_skip','next')
//                ->orderby('created_at','desc')
//                ->first();
//            if($featureNextFeedback){ // for next
//                $end = Carbon::parse($featureNextFeedback->created_at);
//                $now = Carbon::now();
//                $lengthDays = FeatureLengthDays::where('is_active','next')->orderby('created_at','desc')->first();
//                if($lengthDays->feature_length <= $end->diffInDays($now)){
//                    SurveyFeaturesConfig::where('user_id',Auth::user()->id)->delete();
//                    return "show";
//                }
//            }else{// skip
//                $skipFeedback = SurveyFeaturesConfig::where('user_id',$user_id)->where('is_skip','skip')
//                    ->orderby('created_at','desc')->first();
//                $end = Carbon::parse($featureFeedback->created_at);
//                $now = Carbon::now();
//                $lengthDays = FeatureLengthDays::where('is_active','skip')->orderby('created_at','desc')->first();
//                if($lengthDays->feature_length <= $end->diffInDays($now)){
//                    SurveyFeaturesConfig::where('user_id',Auth::user()->id)->delete();
//                    return "show";
//                }
//            }
//
//        }else{
//            return "show";
//        }
//
//    }


    // public function featureShow(Request $request){
    //     $user_id = Auth::user()->id;
    //     $featureFeedback   = SurveyFeaturesConfig::where('user_id',$user_id)
    //         ->where('is_archrive',0)
    //         ->orderby('created_at','desc')
    //         ->first();
    //     if($featureFeedback){ // already view a survey
    //         $end = Carbon::parse($featureFeedback->created_at);
    //         $now = Carbon::now();
    //         $last_event = $featureFeedback->is_skip;
    //         $target_event = "next";
    //         if ($last_event == "skip"){
    //             $featureNextFeedback = SurveyFeaturesConfig::where('user_id',$user_id)
    //                 ->where('is_skip','next')
    //                 ->where('is_archrive',0)
    //                 ->orderby('created_at','desc')
    //                 ->first();
    //             if($featureNextFeedback){
    //                 $target_event = "next";
    //             }
    //             else{
    //                 $target_event = "skip";
    //             }
    //         }
    //         $lengthDays = FeatureLengthDays::where('is_active', $target_event)
    //             ->orderby('created_at','desc')
    //             ->first();
    //         if($lengthDays->feature_length <= $end->diffInDays($now)){
    //             SurveyFeaturesConfig::where('user_id',Auth::user()->id)->update([
    //                      'is_archrive' => 1,
    //              ]);
    //             return "show";
    //         }
    //     }else{
    //         return "show";
    //     }
    // }


    public function notifications()
    {
        $notifications = EmailQueue::where('email_to', Auth::user()->user_email)
            ->where('web_notification', 0)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->orderby('created_at', 'desc')->get();

        foreach ($notifications as $key => $value) {
            $value['id'] = Encryption::encodeId($value->id);
        }

        return response()->json($notifications);
    }

    public function notificationCount()
    {
        $notificationsCount = EmailQueue::where('email_to', Auth::user()->user_email)
            ->where('web_notification', 0)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->orderby('created_at', 'desc')->get();

        return response()->json($notificationsCount);
    }


    public function notificationSingle($id)
    {
        $id = Encryption::decodeId($id);
        EmailQueue::where('id', $id)
            ->update([
                'web_notification' => 1,
            ]);

        $singleNotificInfo = EmailQueue::where('id', $id)->first();

        return view('Dashboard::singleNotificInfo', compact('singleNotificInfo'));
    }

    public function notificationAll()
    {
        EmailQueue::where('email_to', Auth::user()->user_email)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->update([
                'web_notification' => 1,
            ]);
        $notificationsAll = EmailQueue::where('email_to', Auth::user()->user_email)
            ->orWhere('email_cc', Auth::user()->user_email)
            ->orderby('created_at', 'desc')->get();
        // $roles = DB::table('email_queue')->lists('title', 'name');

        return view('Dashboard::singleNotificInfo', compact('notificationsAll'));
    }

    public function applicationWiseFeedbackStorage(Request $request)
    {
        try {
            DB::beginTransaction();
            $feedback = ProcessList::where('id', Encryption::decodeId($request->process_id))->first();
            $feedback->rating = $request->get('ratevalue');
            $feedback->is_feedback = 1;
            $comment = $request->get('feedback_content');
            if ($comment != "" || $comment != null) {
                $feedback->comment = $request->get('feedback_content');
            }
            $feedback->save();
            DB::commit();
            return response()->json(['responseCode' => 1, 'status' => true]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['responseCode' => 0, 'status' => false, 'messages' => CommonFunction::showErrorPublic($e->getMessage())]);
        }
    }

    public function feedbackCheck(Request $request)
    {
        $process = ProcessList::where('id', Encryption::decodeId($request->get('process_id')))
            ->where('status_id', 25)
            ->where('is_feedback', 1)
            ->count();
        if ($process >= 1) {
            $arr = ['status' => false, 'message' => 'feedback already exist', 'responseCode' => 1];
        } else {
            $arr = ['status' => true, 'message' => 'ave', 'responseCode' => 0];
        }
        return response()->json($arr);
    }

    public function getActivateSummery()
    {
        $sql1 = "SELECT 'Feedback' `Feedback`,
                  SUM(IF(rating=1,1,0)) `very_poor`,
                  SUM(IF(rating=2,1,0)) `poor`,
                  SUM(IF(rating=3,1,0)) `neither_satisfied`,
                  SUM(IF(rating=4,1,0)) `satisfied`,
                  SUM(IF(rating=5,1,0)) `very_satisfied`
                  from process_list
                  where process_list.is_feedback = 1
                  and rating in (1,2,3,4,5) limit 1";

        $getActivate = \DB::select(DB::raw($sql1))[0];
        return $getActivate;
    }

    public function feedbackList()
    {
        $companyIds = CommonFunction::getUserCompanyWithZero();
        $userDeskIds = CommonFunction::getUserDeskIds();
        $departmentIds = CommonFunction::getUserDepartmentIds();
        $userId = CommonFunction::getUserId();
        //pending feedback app
        $pendingFeedbackApplication = ProcessList::where('is_feedback', '=', 0)
            ->where(function ($query1) use ($companyIds, $userDeskIds, $departmentIds, $userId) {
                if (Auth::user()->user_type == '5x505') {
                    $query1->whereIn('process_list.company_id', $companyIds);
                    $query1->where('process_list.status_id', '!=', -1);
                } elseif (Auth::user()->user_type == '4x404') {
                    $query1->whereIn('process_list.desk_id', $userDeskIds)
                        ->where(function ($query2) use ($departmentIds) {
                            $query2->whereIn('process_list.department_id', $departmentIds)
                                ->orWhere('process_list.department_id', 0);
                        })
                        ->where(function ($query2) use ($userId) {
                            $query2->where('process_list.user_id', $userId)
                                ->orWhere('process_list.user_id', 0);
                        })
                        ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);
                }

                if (in_array(Auth::user()->user_type, ['1x101', '2x202'])) {
                    $query1->whereNotIn('process_list.status_id', [-1, 5]);
                }
                if (in_array(CommonFunction::getUserType(), ['9x901', '9x902', '9x903', '9x904'])) {
                    $query1->whereIn('process_list.desk_id', $userDeskIds)
                        ->where(function ($query2) use ($userId) {
                            $query2->where('process_list.user_id', $userId)
                                ->orWhere('process_list.user_id', 0);
                        })
                        ->where('process_list.desk_id', '!=', 0)
                        ->whereNotIn('process_list.status_id', [-1, 5]);
                }
            })
            ->count();
        $getActivateFeedback = $this->getActivateSummery();
        return view("Dashboard::feedback-list", compact('getActivateFeedback', 'pendingFeedbackApplication'));
    }

    public function applyNewApplication()
    {
        $services = ProcessType::where('status', 1)
            ->where(function ($query) {
                $query->whereIn('id', Session::get('accessible_process'))
                    ->orWhere('bida_service_status','2'); // stakeholder
            })
            ->orderBy('service_name', 'asc')
            ->select([
                'id',
                'form_url',
                DB::raw("CONCAT(IF (bida_service_status=1,'BIDA Service: ',''),`process_supper_name`,'- ',`process_sub_name`) AS service_name")
            ])->get();

        return view('Dashboard::apply_new_application', compact('services'));
    }

    public function applyNewService(Dashboard $dashboard)
    {
        $user_type = Auth::user()->user_type;
        $type = explode('x', $user_type);
        $working_company_id = CommonFunction::getUserWorkingCompany();
        $user_desk_ids = \App\Libraries\CommonFunction::getUserDeskIds();

        $accessible_process = [];
        if (\Illuminate\Support\Facades\Session::has('accessible_process')) {
            $accessible_process = \Illuminate\Support\Facades\Session::get('accessible_process');
        }

        if ($user_type =='5x505') {
            $user_applications = DB::select("select
                COUNT(if(status_id=25,1,NULL)) as approved,
                COUNT(if(status_id=-1 || status_id=3 || status_id=5 || status_id=15 || status_id=17 || status_id=22 || status_id=32,1,NULL)) as my_desk_app,
                COUNT(if(status_id=4 ||  status_id= 6 || status_id=7,1,NULL)) as others,
                COUNT(if(status_id != -1 && status_id != 3 && status_id != 4 && status_id != 5 && status_id != 6 && status_id != 7 && status_id != 15 && status_id != 17 && status_id != 22 && status_id != 25 && status_id != 32,1,NULL)) as in_process_app
                from process_list 
                where company_id = $working_company_id and process_type_id not in (100)");

        }

        $primaryServices = ProcessType::where('status', 1)
            ->where(function ($query) {
                $query->whereIn('id', Session::get('accessible_process'))
                    ->orWhere('bida_service_status','2'); // stakeholder
            })
            ->orderBy('service_name', 'asc')
            ->select([
                'id',
                'form_url',
                DB::raw("CONCAT(IF (bida_service_status=1,'BIDA Service: ',''),`process_supper_name`,'- ',`process_sub_name`) AS service_name")
            ])->get();

        $featured_stakeholder_services = $this->getStakeholderServices($working_company_id, true);
        $featuredStakeholderServices = $this->getStakeholderServicesData($featured_stakeholder_services);

        $draft_applications = CommonFunction::getApplicationsByStatus(-1, $working_company_id, 0);
        $shortfall_applications = CommonFunction::getApplicationsByStatus(5, $working_company_id, 1);

        return view('Dashboard::apply_new_service', compact('primaryServices', 'user_applications','user_type', 'accessible_process', 'type', 'featuredStakeholderServices', 'user_desk_ids','draft_applications', 'shortfall_applications'));
    }
}