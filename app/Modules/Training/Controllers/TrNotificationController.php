<?php

namespace App\Modules\Training\Controllers;

use App\Libraries\ACL;
use Illuminate\Http\Request;
use yajra\Datatables\Datatables;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrCourse;
use Illuminate\Support\Facades\Validator;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\Training\Models\TrNotification;

class TrNotificationController extends Controller
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
        return view('Training::notification.index');
    }

    public function getData()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Please contact system administration for more information</h4>"
            ]);
        }
        $records = TrNotification::orderBy('id', 'DESC')->get();
        return Datatables::of($records)
            ->editColumn('course_id', function ($row) {
                return $row->trCourse->course_title;
            })
            ->editColumn('batch_id', function ($row) {
                return $row->trBatch->batch_name;
            })
            ->editColumn('session_id', function ($row) {
                return $row->trSession->session_name;
            })
            ->editColumn('subject', function ($row) {
                return $row->subject;
            })
            ->editColumn('participant_status', function ($row) {
                return $row->participant_status;
            })
            ->removeColumn('id')
            ->make(true);
    }
    public function createNotification()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }

        $trCourse = ['' => 'Select one'] + TrCourse::leftJoin('tr_schedules', 'tr_courses.id', '=', 'tr_schedules.course_id')
        ->select('tr_courses.*', 'tr_schedules.id as schedule_id')
        ->where('tr_courses.is_active', 1)
        ->where('tr_schedules.is_publish', 1)
        ->orderBy('tr_courses.id', 'DESC')
        ->lists('tr_courses.course_title', 'tr_courses.id')
        ->all();

        return view('Training::notification.create-notification', compact('trCourse'));
    }

    public function storeNotification(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information');
        }

        $rules = [
            'course_id' => 'required',
            'batch_id' => 'required',
            'session_id' => 'required',
            'participant_status' => 'required',
            'subject' => 'required',
            'description' => 'required',
            'attachment' => 'mimes:jpeg,jpg,png,pdf,doc,docx|max:2048',

        ];
        $messages = [
            'course_id.required' => 'Course is required',
            'batch_id.required' => 'Batch is required',
            'session_id.required' => 'Session is required',
            'participant_status.required' => 'Participant status is required',
            'subject.required' => 'Notification title is required',
            'description.required' => 'Notification description is required',
            'attachment.mimes' => 'Attachment must be a file of type: jpeg, jpg, png, pdf, doc, docx',
            'attachment.max' => 'Attachment may not be greater than 2MB',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            Log::error('TRSchedule : ' . $validator->errors() . ' [TRS-73]');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $schedule_id = TrBatch::where('id', $request->batch_id)->first();

        try {
            $notification = new TrNotification();
            $notification->schedule_id = $schedule_id->schedule_id;
            $notification->course_id = $request->course_id;
            $notification->batch_id = $request->batch_id;
            $notification->session_id = $request->session_id;
            $notification->participant_status = $request->participant_status;
            $notification->subject = $request->subject;
            $notification->description = $request->description;
            $notification->notify_via_sms = $request->notify_via_sms ? 1 : 0;
            $notification->notify_via_email = $request->notify_via_email ? 1 : 0;
            $notification->attachment_or_url = $request->attachmentOrUrl ? $request->attachmentOrUrl : '';

            if ($request->attachmentOrUrl == 'attachment') {
                if ($request->hasFile('attachment')) {
                    $yearMonth = date("Y") . "/" . date("m") . "/";
                    $path = 'uploads/training/notification/' . $yearMonth;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $_file_path = $request->file('attachment');
                    $file_path = trim(uniqid('TR_N-' . time() . '-', true) . $_file_path->getClientOriginalName());
                    $_file_path->move($path, $file_path);
                    $notification->attachment = $yearMonth . $file_path;
                }
            } else {
                $notification->url = $request->url ? $request->url : '';
            }

            $notification->status = 1;
            $notification->is_active = 1;
            $notification->created_by = Auth::user()->id;
            $notification->updated_by = Auth::user()->id;

            $notification->save();

            $appInfo['email_subject'] = $notification->subject;
            $appInfo['email_description'] = $notification->description;
            $appInfo['attachment'] = $notification->url;


            // $receiverInfo[] = [
            //     'user_email' => 'harun.ocpl.bd@gmail.com',
            //     'user_phone' => '+8801776967480'
            // ];


            if ($request->participant_status == 'Confirmed') {
                $receiverInfo = TrParticipant::select('email as user_email', 'moblie_no as user_phone')
                        ->where('is_paid', '1')
                        ->where('status', 'Confirmed')
                        ->where('session_id', $notification->session_id)
                        ->get();
            }
            if ($request->participant_status == 'Declined') {
                $receiverInfo = TrParticipant::select('email as user_email', 'moblie_no as user_phone')
                        ->where('is_paid', '1')
                        ->where('status', 'Declined')
                        ->where('session_id', $notification->session_id)
                        ->get();
            }
            if ($request->participant_status == 'All') {
                $receiverInfo = TrParticipant::select('email as user_email', 'moblie_no as user_phone')
                        ->where('is_paid', '1')
                        ->where('session_id', $notification->session_id)
                        ->get();
            }


            if($receiverInfo->count() > 0){
                CommonFunction::sendEmailSMS('TR_BULK_NOTIFICATION', $appInfo, $receiverInfo);
            }



            return redirect('/training/notification/list')->with('success', 'Notification sent successfully');
        } catch (\Exception $e) {
            Log::error('TRSchedule : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [TRN-125]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<attachment_typeh4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [TRN-318]' . "</attachment_typeh4>",
            ]);
        }
    }
}