<?php namespace App\Modules\AutoProcessApp\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use yajra\Datatables\Datatables;

class AutoProcessAppController extends Controller {

    public function applist($id = '', $processStatus = null){
        $process_type_id = $id != '' ? Encryption::decodeId($id) : 0;

        if (!session()->has('active_process_list')) {
            session()->set('active_process_list', $process_type_id);
        }
        $userType = Auth::user()->user_type;
        $ProcessType = ProcessType::whereStatus(1)
            ->where(function ($query) use ($userType) {
                $query->where('active_menu_for', 'like', "%$userType%");
            })
            ->where('auto_process', 1)
            ->orderBy('name')
            ->lists('name', 'id')
            ->all();

        $process_info = ProcessType::where('id', $process_type_id)->first(['acl_name', 'form_url', 'name', 'id']);
        // $processStatus = null;
        return view("AutoProcessApp::list", compact('ProcessType', 'processStatus', 'process_type_id', 'process_info'));
    }

    public function getList(Request $request, $status = '', $desk = '')
    {
        $process_type_id = Encryption::decodeId($request->get('process_type_id')); //new process type get by javascript session
        $status == '-1000' ? '' : $status;
        $applicationlist = DB::select(DB::raw("select process_list.id, process_list.ref_id, process_list.tracking_no, json_object,
            process_list.desk_id, process_list.process_type_id, process_list.status_id, process_list.priority,
            process_list.process_desc, process_list.updated_at, process_list.updated_by, process_list.locked_by,
            process_list.locked_at, process_list.created_by, process_list.read_status,process_list.updated_at,
            user_desk.desk_name, process_status.status_name,
                process_type.name as process_name, process_type.max_processing_day, process_type.form_url from process_list 
                left join process_type on process_list.process_type_id = process_type.id 
                left join user_desk on process_list.desk_id = user_desk.id
                left join process_status on (process_list.status_id = process_status.id and process_list.process_type_id=process_status.process_type_id)
                where process_type.auto_process = 1 and process_type.id=$process_type_id AND process_list.status_id NOT IN(-1, 5, 6, 25)"));

        // Auto process list generation
        $holiday = DB::select(DB::raw('select group_concat(holiday_date) as holiday_date from govt_holiday where is_active =1'));;
        $holidays = explode(',', $holiday[0]->holiday_date);


        if($desk == 'processByToday'){
            $todayProcess = [];
            foreach ($applicationlist as $process){
                $result1 = $this->holidayAndOffDay($process->updated_at, $holidays);
                // Process by today
                if($result1 == $process->max_processing_day){
                    $todayProcess[] = $process;
                }
            }
            $list = collect($todayProcess);
        }elseif ($desk == 'processByTomorrow'){
            $tomorrowProcess = [];
            foreach ($applicationlist as $process){
                $result1 = $this->holidayAndOffDay($process->updated_at, $holidays);
                // Process by tomorrow
                if($result1+1 == $process->max_processing_day){
                    $tomorrowProcess[] = $process;
                }
            }
            $list = collect($tomorrowProcess);
        }

        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($status) {
                $html = '<a target="_blank" href="' . url('process/' . $list->form_url . '/view/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)) . '" class="btn btn-xs btn-primary button-color" style="color: white"> <i class="fa fa-folder-open"></i> Open</a>  &nbsp;';
                return $html;
            })
            ->addColumn('desk', function ($list) {
                return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
            })
            ->make(true);
    }

    public function holidayAndOffDay($updated_date, $holidays){

        $newDate = date("Y-m-d", strtotime($updated_date));
        $start = new DateTime($newDate );
        $today_date = date('Y-m-d');
        $end = new DateTime($today_date);
        // otherwise the  end date is excluded (bug?)
        $end->modify('+1 day');
        $interval = $end->diff($start);

        // total days
        $days = $interval->days;
        // create an iterable period of date (P1D equates to 1 day)
        $period = new DatePeriod($start, new DateInterval('P1D'), $end);


//    $holidays = array('2018-07-17', '2018-07-19');
//    here is checking today off or not
        foreach($period as $dt) {

            $curr = $dt->format('D');
            // substract if Saturday or Fri
            if ($curr == 'Fri' || $curr == 'Sat') {
                $days--;
            }
            // (optional) for the updated question
            elseif (in_array($dt->format('Y-m-d'), $holidays)) {
                $days--;
            }
        }

        return $days-1;

    }

}
