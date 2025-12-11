<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrCourse;
use App\Modules\Training\Models\TrEvaluation;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\Training\Models\TrSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use yajra\Datatables\Datatables;

class TrEvaluationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 2202;
        $this->aclName = 'Training-Desk';
    }

    public function index()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        return view('Training::evaluation.index');
    }

    public function evaluationCreate(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        // $courses = TrSchedule::with('course')->where('is_active', 1)->get();
        $courses = ['' => 'Select one'] + TrCourse::leftJoin('tr_schedules', 'tr_courses.id', '=', 'tr_schedules.course_id')
            ->select('tr_courses.*', 'tr_schedules.id as schedule_id')
            ->where('tr_courses.is_active', 1)
            ->where('tr_schedules.is_publish', 1)
            ->where('tr_schedules.course_evaluation', 'yes')
            ->where('tr_schedules.pass_marks', '>' , 0)
            ->orderBy('tr_courses.id', 'DESC')
            ->lists('tr_courses.course_title', 'tr_courses.id')
            ->all();

        return view("Training::evaluation.home", compact('courses'));
    }

    public function participantsMarks(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $course_id = $request->courseId;
        $batch_id = $request->batchId;
        $session_id = $request->sessionId;
        $attendanceDate = $request->attendanceDate;
        $mysql_attendance_date = date('Y-m-d', strtotime($attendanceDate));
        $evaluation_type = $request->evaluation_type;

        $schedule_id = TrBatch::where('id', $batch_id)->first();

        $participants = TrParticipant::where('tr_participants.schedule_id', $schedule_id->schedule_id)
            ->where('tr_participants.batch_id', $batch_id)
            ->where('tr_participants.session_id', $session_id)
            ->where('tr_participants.is_paid', 1)
            ->where('tr_participants.status', 'Confirmed')
            ->get();

        return Datatables::of($participants)
            ->editColumn('name', function ($participant) {
                return $participant->full_name;
            })
            ->editColumn('moblie_no', function ($participant) {
                return $participant->moblie_no;
            })
            ->editColumn('email', function ($participant) {
                return $participant->email;
            })
            ->editColumn('marks', function ($participant) use($mysql_attendance_date, $evaluation_type) {

                $matchingEvaluation = TrEvaluation::where('schedule_id', $participant->schedule_id)
                ->where('batch_id', $participant->batch_id)
                ->where('session_id', $participant->session_id)
                // ->where('evaluation_date', $mysql_attendance_date)
                ->where('evaluation_type', $evaluation_type)
                ->where('participant_id', $participant->id)
                ->first();
                
                if ($matchingEvaluation) {
                    return '<input type="number" class="form-control marks" name="marks" value="' . $matchingEvaluation->marks . '" max="100" min="0" data-id="' . Encryption::encodeId($participant->id) . '" id="' . Encryption::encodeId($matchingEvaluation->id) . '" />';
                } else {
                    return '<input type="number" class="form-control marks" name="marks" value="0" max="100" min="0" data-id="' . Encryption::encodeId($participant->id) . '" />';
                }
            })
            ->make(true);
    }

    public function storeParticipantMarksBulk(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }

        $schedule_id = TrBatch::where('id', $request->batchId)->first();
        $scheduleMarks = TrSchedule::where('id', $schedule_id->schedule_id)->first(['pass_marks']);

        try {
            // Loop through the marks data and update the participant records
            foreach ($request->marksData as $markData) {
                if($markData['marks'] > $scheduleMarks->pass_marks){
                    return response()->json([
                        'responseCode' => 0,
                        'responseMessage' => "Marks can not be greater than pass marks.". $scheduleMarks->pass_marks,
                    ]); 
                }

                $participant = TrEvaluation::findOrNew(Encryption::decodeId($markData['evaluationId']));
                $participant->marks = $markData['marks'];
                $participant->schedule_id = $schedule_id->schedule_id;
                $participant->batch_id = $request->batchId;
                $participant->session_id = $request->sessionId;
                $participant->participant_id = Encryption::decodeId($markData['participantId']);
                $participant->evaluation_date = $request->evaluationDate;
                $participant->evaluation_type = $request->evaluationType;
                $participant->is_active = 1;
                $participant->created_by = Auth::user()->id;
                $participant->updated_by = Auth::user()->id;

                $participant->save();
            }

            return response()->json([
                'responseCode' => 1,
                'responseMessage' => 'Participant marks saved successfully.',
            ]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json([
                'responseCode' => 0,
                'responseMessage' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
    public function getData()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $records = TrEvaluation::orderBy('id', 'DESC')->get();
        return Datatables::of($records)
            ->editColumn('course_id', function ($row) {
                return $row->trCourse->course->course_title;
            })
            ->editColumn('batch_id', function ($row) {
                return $row->trBatch->batch_name;
            })
            ->editColumn('session_id', function ($row) {
                return $row->trSession->session_name;
            })
            ->editColumn('type', function ($row) {
                return ucfirst($row->evaluation_type);
            })
            
            ->removeColumn('id')
            ->make(true);
    }

}
