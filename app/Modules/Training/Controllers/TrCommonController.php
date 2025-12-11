<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use App\Http\Controllers\Controller;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrScheduleSession;
use Illuminate\Http\Request;


class TrCommonController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 700;
        $this->aclName = 'Training-Desk';
    }

    // route checked
    public function getBatchByCourseId(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-') || !ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $course_id = $request->courseId;
        // $batches = TrBatch::where('schedule_id', $course_id)->where('is_publish', 1)->get();
        if(isset($request->type)){
            if($request->type == 'evaluation'){
                $batches = TrBatch::leftJoin('tr_schedules', 'tr_schedules.id', '=', 'tr_batches.schedule_id')
                    ->where('tr_schedules.course_id', $course_id)
                    ->where('tr_batches.is_active', 1)
                    ->where('tr_schedules.is_publish', 1)
                    ->where('tr_schedules.course_evaluation', 'yes')
                    ->where('tr_schedules.pass_marks', '>' , 0)
                    ->select('tr_batches.*')
                    ->get();
                return response()->json(['responseCode' => 1, 'data' => $batches]); 
            }
        }
        $batches = TrBatch::leftJoin('tr_schedules', 'tr_schedules.id', '=', 'tr_batches.schedule_id')
            ->where('tr_schedules.course_id', $course_id)
            ->where('tr_batches.is_active', 1)
            ->where('tr_schedules.is_publish', 1)
            ->select('tr_batches.*')
            ->get();
        return response()->json(['responseCode' => 1, 'data' => $batches]);
    }

    // route checked
    public function getSessionBytrScheduleMasterId(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-') || !ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $course_id = $request->courseId;
        $trScheduleMasterId = $request->trScheduleMasterId;
        $schedules = TrScheduleSession::leftJoin('tr_schedules', 'tr_schedules.id', '=', 'tr_schedule_sessions.app_id')
            ->where('tr_schedules.course_id', $course_id)
            ->where('tr_schedules.batch_id', $trScheduleMasterId)
            ->where('tr_schedules.is_publish', 1)
            ->where('tr_schedule_sessions.is_active', 1)
            ->select('tr_schedule_sessions.*')
            ->get();
        return response()->json(['responseCode' => 1, 'data' => $schedules]);
    }

}
