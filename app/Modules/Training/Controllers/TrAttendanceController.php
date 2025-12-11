<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\Training\Models\TrAttandence;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrCourse;
use App\Modules\Training\Models\TrParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use yajra\Datatables\Datatables;

class TrAttendanceController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 2202;
        $this->aclName = 'Training-Desk';
    }

    public function attendanceCreate(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        // $courses = TrSchedule::with('course')->where('is_active', 1)->get();
        $courses = ['' => 'Select one'] + TrCourse::leftJoin('tr_schedules', 'tr_courses.id', '=', 'tr_schedules.course_id')
            ->select('tr_courses.*', 'tr_schedules.id as schedule_id')
            ->where('tr_courses.is_active', 1)
            ->where('tr_schedules.is_publish', 1)
            ->orderBy('tr_courses.id', 'DESC')
            ->lists('tr_courses.course_title', 'tr_courses.id')
            ->all();

        return view("Training::attendance.home", compact('courses'));
    }

    public function getParticipantsBytrSessionMasterId(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $classSessionId = $request->get('classSessionId');
        $attendance_date = $request->get('attendanceDate');
        $courseId = $request->get('courseId');
        $trScheduleMasterId = $request->get('trScheduleMasterId');

        $schedule_id = TrBatch::where('id', $trScheduleMasterId)->first();

        $participants = TrParticipant::where('tr_participants.schedule_id', $schedule_id->schedule_id)
            ->where('tr_participants.batch_id', $trScheduleMasterId)
            ->where('tr_participants.session_id', $classSessionId)
            ->where('tr_participants.is_paid', 1)
            ->where('tr_participants.status', 'Confirmed')
            ->get();

        return Datatables::of($participants)
            ->editColumn('name', function ($participants) {
                return $participants->full_name;
            })
            ->editColumn('mobile_no', function ($participants) {
                return $participants->moblie_no;
            })
            ->editColumn('email', function ($participants) {
                return $participants->email;
            })
            ->editColumn('status', function ($participants) use ($attendance_date) {

                $matchingAttandance = TrAttandence::where('schedule_id', $participants->schedule_id)
                    ->where('batch_id', $participants->batch_id)
                    ->where('session_id', $participants->session_id)
                    ->where('class_date', $attendance_date)
                    ->where('participant_id', $participants->id)
                    ->first();
                if ($matchingAttandance) {
                    $activate = ' class=" btn-xs  label-' . $matchingAttandance . '" ';
                    $status_name = $matchingAttandance->is_present;
                    return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
                } else {
                    return '<span><b>Not Given</b></span>';
                }
            })
            ->addColumn('action', function ($participants) use ($attendance_date) {
                if(ACL::getAccsessRight($this->aclName, '-A-')){
                    $matchingAttandance = TrAttandence::where('schedule_id', $participants->schedule_id)
                    ->where('batch_id', $participants->batch_id)
                    ->where('session_id', $participants->session_id)
                    ->where('class_date', $attendance_date)
                    ->where('participant_id', $participants->id)
                    ->first();
                    if ($matchingAttandance) {
                        if (strcasecmp($matchingAttandance->is_present, 'Present') === 0) {
                            $button = "<button class='attend absent btn-sm btn-danger' value='Absent_(" .
                            Encryption::encodeId($participants->id) . "'  incdata=" . Encryption::encodeId($matchingAttandance->id) .
                                ">Absent</button>";
                        } else {
                            $button = "<button class='attend present btn-sm btn-success' value='Present_(" .
                            Encryption::encodeId($participants->id) . "' incdata=" . Encryption::encodeId($matchingAttandance->id) .
                                ">Present</button>";
                        }
                        return $button;
                    } else {
                        $button = "<button class='present attend btn-sm btn-success' value='Present_(" .
                        Encryption::encodeId($participants->id) . "' incdata=''>Present</button> <button class='absent btn-sm btn-danger' value='Absent_(" .
                        Encryption::encodeId($participants->id) . "'  incdata=''>Absent</button>";
                        return $button;
                    }
                }
                
            })
            ->make(true);
    }



    public function attendanceEntry(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $schedule_id = TrBatch::where('id', $request->trScheduleMasterId)->first();

        $attendance_date = $request->get('attendanceDate');
        $mysql_attendance_date = date('Y-m-d', strtotime($attendance_date));
        $trScheduleMasterId = $request->get('trScheduleMasterId');
        $classSessionId = $request->get('classSessionId');
        $participantId = Encryption::decodeId($request->get('participantId'));
        $attendance = $request->get('attendance');
        $status = $request->get('status');
        $attendId =  $request->attendId ? Encryption::decodeId($request->get('attendId')) : 0;

        $tr_attendance = TrAttandence::where('id', $attendId)->first();

        if ($tr_attendance) {
            $attendance = TrAttandence::find($tr_attendance->id);
            $attendance->is_present = $status;
            $attendance->updated_by = Auth::user()->id;
            $attendance->save();
            return response()->json(['responseCode' => 1, 'responseMessage' => 'Attendance has been updated successfully']);
        } else {
            $attendance = new TrAttandence();
            $attendance->schedule_id = $schedule_id->schedule_id;
            $attendance->batch_id = $trScheduleMasterId;
            $attendance->session_id = $classSessionId;
            $attendance->class_date = $mysql_attendance_date;
            $attendance->is_present = $status;
            $attendance->participant_id = $participantId;
            $attendance->is_active = 1;
            $attendance->created_by = Auth::user()->id;
            $attendance->updated_by = Auth::user()->id;
            $attendance->save();
            return response()->json(['responseCode' => 1, 'responseMessage' => 'Attendance has been taken successfully']);
        }
    }

    public function attendanceEntryAll(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $schedule_id = TrBatch::where('id', $request->trScheduleMasterId)->first();

        $attendanceDate = $request->get('attendanceDate');
        $trScheduleMasterId = $request->get('trScheduleMasterId');
        $classSessionId = $request->get('classSessionId');

        try {
            // Loop through the marks data and update the participant records
            foreach ($request->attendData as $attendData) {
                $participant = TrAttandence::findOrNew(Encryption::decodeId($attendData['attendId']));
                $participant->is_present = 'Present';
                $participant->schedule_id = $schedule_id->schedule_id;
                $participant->batch_id = $trScheduleMasterId;
                $participant->session_id = $classSessionId;
                $participant->participant_id = Encryption::decodeId($attendData['participantId']);
                $participant->class_date = $attendanceDate;
                $participant->is_active = 1;
                $participant->created_by = Auth::user()->id;
                $participant->updated_by = Auth::user()->id;

                $participant->save();
            }

            return response()->json([
                'responseCode' => 1,
                'responseMessage' => 'Participant Attendace saved successfully.',
            ]);
        } catch (\Exception $e) {
            // Handle any errors that occur during the process
            return response()->json([
                'responseCode' => 0,
                'responseMessage' => 'Error: ' . $e->getMessage(),
            ]);
        }

    }
}
