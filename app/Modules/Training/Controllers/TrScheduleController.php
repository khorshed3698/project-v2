<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use yajra\Datatables\Datatables;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrCourse;
use Illuminate\Support\Facades\Validator;
use App\Modules\Training\Models\TrCategory;
use App\Modules\Training\Models\TrSchedule;
use App\Modules\Training\Models\TrEvaluation;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\Training\Models\TrScheduleSession;

class TrScheduleController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 700;
        $this->aclName = 'Training-Desk';
    }

    // route checked
    public function checkBatchName(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-') || !ACL::getAccsessRight($this->aclName, '-E-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $batch_name = $request->get('batch_id');
        // check batch name length between 2 to 20
        if (strlen($batch_name) < 2 || strlen($batch_name) > 20) {
            return response()->json(['responseCode' => 1, 'messages' => 'Batch name length should be between 2 to 20']);
        }
        // check batch name should not contains with whitespace
        if (preg_match('/\s/', $batch_name)) {
            return response()->json(['responseCode' => 1, 'messages' => 'Batch name should not contains with whitespace']);
        }
        $batch = TrBatch::where('batch_name', $batch_name)->where('course_id', $request->get('course_id'))->where('is_active', 1)->first();
        if ($batch) {
            return response()->json(['responseCode' => 1, 'messages' => 'Batch name already exists']);
        } else {
            return response()->json(['responseCode' => 0]);
        }
    }

    // route checked
    public function index()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-') && !ACL::getAccsessRight($this->aclName, '-DV-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        return view('Training::tr_schedule.index');
    }

    // route checked
    public function createSchedule()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        $trCourse = ['' => 'Select one'] + TrCourse::where('is_active', 1)->orderBy('id', 'DESC')->lists('course_title', 'id')->all();
        $trCategory = ['' => 'Select one'] + TrCategory::where('is_active', 1)->lists('category_name', 'id')->all();

        return view('Training::tr_schedule.create', compact('trCourse', 'trCategory'));
    }

    // route checked
    public function getData()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-') && !ACL::getAccsessRight($this->aclName, '-DV-')) {
            die('You have no access right! Please contact system administration for more information');
        }
        if (checkTrainingCoordinator()) {
            $trainingData = TrSchedule::where('created_by', Auth::user()->id)->orderBy('id', 'DESC')->get();
        }elseif(checkTrainingDirector()){
            $trainingData = TrSchedule::where('is_publish', (int)request()->status)->where('is_active', 1)->orderBy('id', 'DESC')->get();
        }
        return Datatables::of($trainingData)
            ->editColumn('course', function ($training) {
                return $training->course->course_title;
            })
            ->editColumn('batch', function ($training) {
                return $training->batch->batch_name;
            })
            ->editColumn('is_publish', function ($training) {
                if ($training->is_publish == 1) {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->editColumn('status', function ($training) {
                $activate = ' class=" btn-xs  label-' . $training->status . '" ';
                $status_name = $training->status;
                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->editColumn('start_time', function ($training) {
                return $training->course_duration_start;
            })
            ->addColumn('action', function ($training) {

                if(ACL::getAccsessRight('Training-Desk','-V-') || ACL::getAccsessRight('Training-Desk','-DV-') ) {
                    $button = '<a href="' . url('/training/view-schedule-details/' . Encryption::encodeId($training->id)) . '"  class="btn btn-xs btn-info "><i class="fa fa-eye"></i> Open </a> ';
                }
                
                if(($training->is_publish == 0 && ACL::getAccsessRight('Training-Desk','-DE-')) || ACL::getAccsessRight('Training-Desk','-E-') ) {
                    $button .= '<a href="' . url('/training/edit-schedule/' . Encryption::encodeId($training->id)) . '"  class="btn btn-xs btn-primary "><i class="fa fa-pencil"></i> Edit </a> ';
                }

                if($training->is_publish == 0  && checkTrainingDirector() && ACL::getAccsessRight('Training-Desk','-DE-')) {
                    $button .= '<a href="' . url('/training/schedule-update/'.Encryption::encodeId($training->id)) . '"  class="btn btn-xs btn-info "><i class="fas fa-check"> Approve </a>';
                }

                return $button;
            })
            ->removeColumn('id')
            ->make(true);
    }

    // route checked
    public function storeSchedule(Request $request)
    {
        // dd($request->all());
        if(!empty($request->get('app_id'))){
            if(checkTrainingCoordinator()){
                $mode = '-E-';
            }
            else{
                $mode = '-DE-';
            }
        }
        else{
            $mode = '-A-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            abort('400', "You have no access right! Please contact system administration for more information");
        }
        
        $rules = [
            'session_name' => 'required',
            'course_id' => 'required',
            'is_active' => 'required',
            'fees_type' => 'required',
            'category_id' => 'required',
            'course_duration_start' => 'required|date|date_format:Y-m-d',
            'course_duration_end' => 'required|date|date_format:Y-m-d',
            'duration' => 'required',
            'duration_unit' => 'required',
            'enroll_deadline' => 'required',
            'no_of_class' => 'required|numeric',
            'total_hours' => 'required|numeric',
            'venue' => 'required',
            'training_office' => 'required',
            'training_center' => 'required',
            'course_evaluation' => 'required',
            'objectives' => 'required',
            'course_contents' => 'required',
            'necessary_qualification_experience' => 'required',
            'batch_id' => 'required',
            'is_featured' => 'required',
        ];

        // if($request->fees_type == 'paid' )
        // {
        //     $rules['amount'] = 'required|numeric';
        // }
        if($request->fees_type == 'paid' )
        {
            $rules['amount'] = 'numeric';
        }
        if($request->course_evaluation == 'yes' )
        {
            $rules['pass_marks'] = 'required';
        }
        
        if(empty($request->get('app_id'))){
            if((!$request->course_thumbnail_base64 && !$request->course_thumbnail_base642 && !$request->course_thumbnail_base643)){
                $rules['course_thumbnail_base64'] = 'required';
            }
            
        }
        else{
            $rules['batch_id'] = 'required';
            $rules['status'] = 'required';
        }

        $messages = [
            'course_id.required' => 'Course is required',
            'is_active.required' => 'Status is required',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount should be numeric',
            'batch_id.required' => 'Batch is required',
            'fees_type.required' => 'Fees type is required',
            'category_id.required' => 'Course Category is required',
            'status.required' => 'Course Status is required',
            'course_duration_start.required' => 'Course duration start is required',            
            'course_duration_end.required' => 'Course duration end is required',
            'duration.required' => 'Duration is required',
            'duration_unit.required' => 'Duration unit is required',
            'enroll_deadline.required' => 'Enroll deadline is required',
            'no_of_class.required' => 'No of class is required',
            'total_hours.required' => 'Total hours is required',
            'venue.required' => 'Venue is required',
            'training_office.required' => 'Training office is required',
            'training_center.required' => 'Training center is required',
            'course_evaluation.required' => 'Course evaluation is required',
            'pass_marks.required' => 'Pass marks is required',
            'objectives.required' => 'Course Goal is required',
            'course_contents.required' => 'Course Outline is required',
            'necessary_qualification_experience.required' => 'Necessary Qualification is required',
        ];
        foreach ($request->input('session_name') as $index => $value) {
            $rules['session_name.' . $index] = 'required|string|max:255';
            $rules['session_start_time.' . $index] = 'required';
            $rules['session_end_time.' . $index] = 'required';
            $rules['day.' . $index] = 'required';
    
            // Custom messages
            $messages['session_name.' . $index . '.required'] = 'The session name at row ' . ($index + 1) . ' is required.';
            $messages['session_start_time.' . $index . '.required'] = 'The session start time at row ' . ($index + 1) . ' is required.';
            $messages['session_end_time.' . $index . '.required'] = 'The session end time at row ' . ($index + 1) . ' is required.';
            $messages['session_end_time.' . $index . '.after'] = 'The session end time must be after the start time at row ' . ($index + 1) . '.';
            $messages['day.' . $index . '.required'] = 'Please select at least one day at row ' . ($index + 1) . '.';
            $messages['seat_capacity.' . $index . '.numeric'] = 'The seat capacity at row ' . ($index + 1) . ' must be a number.';
        }
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Log::error('TRSchedule : ' . $validator->errors() . ' [TRS-684]');
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            if ($request->get('app_id')) {
                $appId = Encryption::decodeId($request->get('app_id'));
                $trSchedule = TrSchedule::find($appId);
                $trSchedule->status = $request->status;
            }else {
                $trSchedule = new TrSchedule();
                $trSchedule->created_by = Auth::user()->id;
            }
            $trSchedule->course_id = $request->course_id;
            $trSchedule->is_active = $request->is_active;
            $trSchedule->fees_type = $request->fees_type;
            $trSchedule->amount = $request->amount ? $request->amount : 0;
            $trSchedule->course_duration_start = date('Y-m-d', strtotime($request->course_duration_start));
            $trSchedule->course_duration_end = date('Y-m-d', strtotime($request->course_duration_end));
            $trSchedule->duration = $request->duration;
            $trSchedule->duration_unit = $request->duration_unit;
            $trSchedule->enroll_deadline = $request->enroll_deadline;
            $trSchedule->no_of_class = $request->no_of_class;
            $trSchedule->total_hours = $request->total_hours;
            $trSchedule->category_id = $request->category_id;
            $trSchedule->venue = $request->venue;
            $trSchedule->training_office = $request->training_office;
            $trSchedule->training_center = $request->training_center;
            $trSchedule->course_evaluation = $request->course_evaluation;
            $trSchedule->pass_marks = $request->pass_marks;
            $trSchedule->objectives = $request->objectives;
            $trSchedule->course_contents = $request->course_contents;
            $trSchedule->necessary_qualification_experience = $request->necessary_qualification_experience;
            $trSchedule->is_featured = $request->is_featured;
            $trSchedule->updated_by = Auth::user()->id;
            if (isset($request->course_thumbnail_base64)) {
                $trSchedule->course_thumbnail_path = $request->course_thumbnail_base64;
                $trSchedule->course_image_no = 1;
            } elseif (isset($request->course_thumbnail_base642)) {
                $trSchedule->course_thumbnail_path = $request->course_thumbnail_base642;
                $trSchedule->course_image_no = 2;
            } elseif (isset($request->course_thumbnail_base643)) {
                $trSchedule->course_thumbnail_path = $request->course_thumbnail_base643;
                $trSchedule->course_image_no = 3;
            }

            $trSchedule->save();

            if (!empty($request->batch_id)) {

                if ($request->get('app_id')) {
                    $batch = TrBatch::find($request->batch);
                }else {
                    $batch = new TrBatch();
                }
                $batch->batch_name = $request->batch_id;
                $batch->schedule_id = $trSchedule->id;
                $batch->course_id = $trSchedule->course_id;
                $batch->is_active = 1;

                $batch->save();
            }
            $trSchedule->batch_id = $batch->id;
            $trSchedule->update();

            if (!empty($request->session_start_time[0])) {
                $session_ids = [];
                foreach ($request->session_start_time as $key => $data) {

                    if ($request->get('app_id') && $request->get('tr_session_id')[$key] != '') {
                        $trSessionIid = $request->get('tr_session_id')[$key];
                        $trSession = TrScheduleSession::find($trSessionIid);
                    }else {
                        $trSession = new TrScheduleSession();
                    }
                    $trSession->session_name = $request->session_name[$key];
                    $trSession->app_id = $trSchedule->id;
                    $trSession->session_start_time = date("H:i", strtotime($request->session_start_time[$key]));
                    $trSession->session_end_time = date("H:i", strtotime($request->session_end_time[$key]));
                    $sessionDaysArray = $request->day[$key];
                    $days = implode(',', $sessionDaysArray);
                    $trSession->session_days = $days;
                    if ($request->get('app_id')) {
                        $trSession->applicant_limit = $request->applicant_limit[$key];
                    }else {
                        $applicant_limit = implode(',', $request->applicant_limit[$key]);
                        $trSession->applicant_limit = $applicant_limit;
                    }
                    $trSession->seat_capacity = $request->seat_capacity[$key] ? $request->seat_capacity[$key] : '';
                    $trSession->is_active = 1;
                    $trSession->save();

                    $session_ids[] = $trSession->id;

                }
                if (count($session_ids) > 0) {
                    TrScheduleSession::where('app_id', $request->app_id)->whereNotIn('id', $session_ids)->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error('TRSchedule : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRS-73]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<attachment_typeh4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [TRS-73]' . "</attachment_typeh4>",
            ]);
        }
        if ($request->get('app_id')) {
            return redirect('training/schedule/list')->with('success', 'Schedule updated successfully');

        }else {
            return redirect('training/schedule/list')->with('success', 'Schedule created successfully');

        }
    }

    // route checked
    public function editSchedule($id)
    {
        if(checkTrainingCoordinator()){
            $mode = '-E-';
        }
        else{
            $mode = '-DE-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            die('You have no access right! Please contact system administration for more information');
        }

        $tr_data = TrSchedule::find(Encryption::decodeId($id));
        $trCourse = ['' => 'Select one'] + TrCourse::where('is_active', 1)->orderBy('id', 'DESC')->lists('course_title', 'id')->all();
        $trCategory = ['' => 'Select one'] + TrCategory::where('is_active', 1)->lists('category_name', 'id')->all();
        $trSessionData = TrScheduleSession::where('app_id', $tr_data->id)->get();
        return view('Training::tr_schedule.edit', compact('tr_data', 'id', 'trCourse', 'trCategory', 'trSessionData'));
    }

    
    public function scheduleUpdate($id){

        if (!ACL::getAccsessRight($this->aclName, '-DE-')) {
            die('You have no access right! Please contact system administration for more information');
        }

        if(checkTrainingDirector()){
            $decodeId = Encryption::decodeId($id);
            $course = TrSchedule::where('id', $decodeId)->first();  
            $course->is_publish = 1;
            $course->is_active = 1;
            $course->update();

            return redirect()->back()->with('success', 'Schedule Approve successfully');
        }
        
        return redirect()->back()->with('error', 'You Do not have permission to update this schedule');

    }

    // route checked
    public function training(Request $request)
    {
        $course = TrSchedule::where('is_active', 1)->where('is_publish', 1)->orderBy('id', 'DESC')->get();
        $total_category = TrCategory::where('is_active', 1)->count();
        $total_participants = TrParticipant::where('is_active', 1)->count();

        return view('Training::web.training_list', compact('course', 'total_category', 'total_participants'));
    }

    // route checked
    public function trainingDetailsNew($id)
    {
        $decodeId = Encryption::decodeId($id);
        $course = TrSchedule::where('id', $decodeId)->first();
        $scheduleSession = TrScheduleSession::where('app_id', $decodeId)->get();
        $courseList = TrSchedule::where('is_active', 1)->where('is_publish', 1)->where('category_id', $course->category_id)->get();
        $course_url = url("training/course-details/$id");
        //put session course url
        Session::put('training_course_url', $course_url);
        if (Auth::check()) {
            $redirect_url = $course_url;
        }else{
            $redirect_url = CommonFunction::getOssPidRedirectUrl();
        }
        return view('Training::web.training_details', compact('course', 'scheduleSession', 'courseList', 'redirect_url'));
    }

    // route checked
    public function trainingDetails($id)
    {
        $course = TrSchedule::where('id', $id)->first();
        return view("Training::course-details", compact('course'));
    }

    // route checked
    public function trainingCategoryGetImageByCategory(Request $request)
    {
        if(checkTrainingCoordinator()){
            $mode = '-E-';
        }
        else{
            $mode = '-DE-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            die('You have no access right! Please contact system administration for more information');
        }
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way [Ajax-1001]';
        }
        try {
            $tr_course_id = trim($request->get('course_id'));
            $course = TrCourse::where('id', $tr_course_id)->first();
            $category = TrCategory::where('id', $course->category_id)->first();
            return response()->json(
                [
                    'responseCode' => 1,
                    'img_path' => asset('/uploads/training/course/' . $course->course_image),
                    'img_value' => !empty($course->course_image) ? $course->course_image : '',
                    'img_path2' => asset('/uploads/training/course/' . $course->course_image2),
                    'img_value2' => !empty($course->course_image2) ? $course->course_image2 : '',
                    'img_path3' => asset('/uploads/training/course/' . $course->course_image3),
                    'img_value3' => !empty($course->course_image3) ? $course->course_image3 : '',
                    'category_id' => $category->id,
                    'message' => 'course category and image found.',
                ]

            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'responseCode' => 0,
                    'img_path' => asset('assets/images/no-image.png'),
                    'img_value' => '',
                    'category_id' => '',
                    'message' => 'Something in wrong in course image and category !',
                ]
            );
        }
    } // end -:- trainingCategoryGetImageByCategory()

    // route checked
    public function upcomingCourse()
    {
        $course = TrSchedule::where('is_active', 1)
                    ->where('is_publish', 1)
                    ->where('status', 'upcoming')
                    ->orderBy('id', 'DESC')
                    ->get();

        return view('Training::upcoming-course', compact('course'));
    }

    // route checked
    public function purchaseCourse()
    {

        $course = TrParticipant::leftJoin('tr_schedules', 'tr_schedules.id', '=', 'tr_participants.schedule_id')
            ->leftJoin('tr_courses', 'tr_courses.id', '=', 'tr_schedules.course_id')
            ->leftJoin('tr_evaluations', 'tr_evaluations.participant_id', '=', 'tr_participants.id')
            ->where('tr_participants.is_active', 1)
            ->where('tr_participants.is_paid', 1)
            ->where('tr_participants.created_by', Auth::user()->id)
            ->get(['tr_participants.*', 'tr_courses.course_title', 'tr_schedules.*']);


        return view('Training::upcoming-course-user', compact('course'));
    }

    // route checked
    public function uploadDocument()
    {
        return view('Training::ajaxUploadFile');
    }

    // route checked
    public function scheduleDetails($id)
    {
        if(!ACL::getAccsessRight($this->aclName, '-V-') && !ACL::getAccsessRight($this->aclName, '-DV-')){
            die('You have no access right! Please contact system administration for more information');
        }
        $decodeId = Encryption::decodeId($id);
        $course = TrSchedule::where('id', $decodeId)->first();
        $perticipants = TrParticipant::where('schedule_id', $decodeId)->get();
        return view('Training::tr_schedule.training_schedule', compact('course', 'perticipants'));
    }

    // route checked
    public function getTrainingData(Request $request)
    {
        $status = $request->status;
        if($status == 'allCourse'){
            $course = TrSchedule::where('is_active', 1)->where('is_publish', 1)->orderBy('id', 'DESC')->get();
        }
        else{
            $course = TrSchedule::where('is_active', 1)->where('is_publish', 1)->where('status', $status)->orderBy('id', 'DESC')->get();
        }
        return view('Training::web.training_list_status', compact('course'));
    }

    // route checked
    public function getTrainingFilterData(Request $request)
    {
        $name = $request->txtSearch;
        $course = TrSchedule::where('is_active', 1)->where('is_publish', 1)
            ->whereHas('course', function ($query) use ($name) {
                $query->where('course_title', 'like', '%' . $name . '%');
            })
            ->get();
        return view('Training::web.training_list_status', compact('course'));
    }

    // route checked
    public function trainingDashboard(Request $request)
    {
        $upcoming = TrSchedule::where('status', 'upcoming')->count();
        $ongoing = TrSchedule::where('status', 'ongoing')->count();
        $completed = TrSchedule::where('status', 'completed')->count();

        return view('Training::dashboard.tr_dashboard', compact('upcoming', 'ongoing', 'completed'));
    }

}
