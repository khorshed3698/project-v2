<?php

namespace App\Modules\BoardMeting\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\PaymentMethod;
use App\Modules\Apps\Models\VisaTypes;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BoardMeting\Models\Agenda;
use App\Modules\BoardMeting\Models\AgendaMapping;
use App\Modules\BoardMeting\Models\BoardMeetingDoc;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\MeetingMinutesMapping;
use App\Modules\BoardMeting\Models\MeetingType;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\OfficePermissionCancellation\Models\OfficePermissionCancellation;
use App\Modules\OfficePermissionExtension\Models\OfficePermissionExtension;
use App\Modules\OfficePermissionNew\Models\OfficePermissionNew;
use App\Modules\ProjectOfficeNew\Models\ProjectOfficeNew;
use App\Modules\ProcessPath\Controllers\ProcessPathController;
use App\Modules\Remittance\Models\PresentStatus;
use App\Modules\Remittance\Models\RemittanceType;
use App\Modules\Settings\Models\SubSector;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;

use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\Notice;
use App\Modules\Users\Models\Nationality;
use App\Modules\Users\Models\Users;
use App\Modules\WorkPermitAmendment\Models\WorkPermitAmendment;
use App\Modules\WorkPermitCancellation\Models\WorkPermitCancellation;
use App\Modules\WorkPermitExtension\Models\WorkPermitExtension;
use App\Modules\WorkPermitNew\Models\WorkPermitNew;
use App\Modules\WorkPermitNew\Models\WP_VisaTypes;
use Illuminate\Http\Request;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Exception;
//use mPDF;

use Mpdf\Mpdf;
//use Mpdf\Mpdf;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\View;
use Validator;

class BoardMetingController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view("BoardMeting::list");
    }

    /*
     * user's list for system admin
     */
    public function lists()
    {
        /*$shareDoc = BoardMeetingDoc::where('is_active',1)
            ->where('ctg_id', 2)
            ->orderBy('id','DESC')
            ->get(['id','doc_name','tag', 'created_at']);*/

        /*$notice = Notice::where('is_active',1)
            ->where('prefix','=', 'board-meeting')
            ->orderBy('id','DESC')
            ->get();*/

        return view('BoardMeting::list');  /*,compact('shareDoc','notice')*/
    }

    /*
     *Board Meting details information by ajax request
     */
    public function getRowDetailsData()
    {
        try {
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeting::getList();
        return Datatables::of($boardMeting)
            ->editColumn('meting_type', function ($boardMeting) use ($mode) {
                return $boardMeting->meting_type;
            })
            ->editColumn('meting_number', function ($boardMeting) use ($mode) {
                return $boardMeting->meting_number . "<sup>th</sup>";
            })
            ->editColumn('meting_date', function ($boardMeting) use ($mode) {

                return date("d M Y", strtotime($boardMeting->meting_date));
            })
            ->editColumn('status', function ($boardMeting) {
                $activate = ' class=" btn-xs  label-' . $boardMeting->panel . '" ';
                $status_name = $boardMeting->status_name;

                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->addColumn('action', function ($boardMeting) use ($mode) {
                if ($mode) {
                    $button = '<a href="' . url('/board-meting/edit/' . Encryption::encodeId($boardMeting->id)) . '"  class="btn btn-xs btn-info "><i class="fa fa-edit"></i> Edit </a> ';
                    $button .= '<a href="' . url('/board-meting/agenda/list/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-success "><i class="fa fa-folder-open"></i> Open </a><br> ';
                    return $button;
                } else {
                    return '';
                }
            })


//                $upComing = BoardMeting::where('meting_date', '>', date("Y-m-d"))
//                    ->orderBy('meting_date')
//                    ->first();
//                if (!empty($upComing) && $upComing->id == $boardMeting->id)
//                {
//                    $newItem = '<img src="/assets/images/upcoming.png" style="margin-top: 0px;width: 100px" alt=" " class="img-responsive">';
//                }else{
//                    $newItem = '';
//                }
//
//                $html = '<a href="' . url('board-meting/agenda/list/' . Encryption::encodeId($boardMeting->id)) . '" class="hover-item" style="text-decoration: none">
//
//                    <div class="panel  hover-item" style="margin-top: 10px; border: 1px solid #86bb86">
//                        <div class="panel-heading" >
//                            <div class="row">
//                                <div class="col-xs-2">
//                                    <div class="h5" style="margin-top:0;margin-bottom:0;font-size: 15px;text-align:right">
//                                       </div>
//                                         <div style="position: absolute">
//                        ' . $newItem . '
//                    </div>
//                                </div>
//                                <div class="col-xs-10 text-right">
//                                     Meeting No. '.$boardMeting->meting_number.'
//                                </div>
//                            </div>
//                            <div class="row">
//                                <div class="col-xs-12 text-right">
//                                    <div style="font-size: 12px;color: gray">
//                                        <br>
//                                      Location. '.$boardMeting->area_nm.'
//                                    </div>
//                                </div>
//                            </div>
//                        </div>
//                            <div class="panel-footer" style="padding:  5px; background: linear-gradient(to bottom, #eeeeee 0%,#cccccc 100%);">
//
//                                <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> '.date("d M Y h:i a", strtotime($boardMeting->meting_date)).'</span>
//                                <span class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
//                                <div class="clearfix"></div>
//                            </div>
//
//                    </div>
//
//                </a>';
//                return $html;
//
//            })
//            ->editColumn('agenda_info', function ($boardMeting) {
//
//                $agenda_data = explode("##",$boardMeting->agenda_info);
//                $userType = CommonFunction::getUserType();
//                $button = '';
////                if($userType == '13x303' && empty($boardMeting->agenda_info)) {
////                   // $button.= "<a href='/board-meting/agenda/create-new-agenda/.".Encryption::encodeId($boardMeting->id)." 'style='margin-top: 10px; ' class='btn btn-md btn-default btn-block'><i class='fa fa-plus'></i> Add Agenda</a> ";
////                }
//                $i=0;
//                foreach ($agenda_data as $value) {
//                    $i++;
//                    $row = explode(",", $value);
//                    if (!empty($row[0])) {
//
////                        if($row[3] == 0 && $row[4] == 0){
////                            $newItem = '<img src="/assets/images/newitem.png" style="width: 13%; margin-top: -3px" alt=" " class="img-responsive">';
////                        }else{
////                            $newItem = '';
////                        }
//                        $newItem = '';
//
//                        $button.= '
//                <a class="hover-item" style="text-decoration: none" href="#">
//                 <div class="panel panel-default hover-item" style="
//                    margin-top: 2px; border: 1px solid #86bb86">
//                    <div style="position: absolute">
//                        ' . $newItem . '
//                    </div>
//                    <div>
//                    <div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
//                    <div class="panel-heading" style="border-left: 5px solid #31708f">
//                        <div class="col-md-offset-2"><span style="margin-top: 20px;">' . $row[0] . '</span><br>&nbsp;</div>
//                    </div>
//
//                    </div>
//
//                 </div>
//                </a>';
//
//                    }
//                }
//                return  $button ;
//
//            })

            ->removeColumn('id')
            ->make(true);
        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@getRowDetailsData : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return response()->json([
                'error' => true,
                'message' => 'Failed to load board meeting data.[BOMC-1001]',
            ], 500);
        }    
    }

    public function view($board_meting_id)
    {
        return view('BoardMeting::agenda-list')->with('board_meting_id', $board_meting_id);
    }

    public function newBoardMeting()
    {
        $departmentIds = Auth::user()->department_id;
        if ($departmentIds == 1) {
            $meetingType = MeetingType::where('is_active', 1)
                ->where('id', 1) //Commercial
                ->lists('name', 'id');
        } else {
            $meetingType = MeetingType::where('is_active', 1)
                ->where('id', 2)  //Industrial
                ->lists('name', 'id');
        }
        return view('BoardMeting::create-board-meting', compact('meetingType'));
    }

    public function editBM($bm_id)
    {

        $bm_data = BoardMeting::find(Encryption::decodeId($bm_id));
        $departmentIds = Auth::user()->department_id;
        if ($departmentIds == 1) {
            $meetingType = MeetingType::where('is_active', 1)
                ->where('id', 1) //Commercial
                ->lists('name', 'id');
        } else {
            $meetingType = MeetingType::where('is_active', 1)
                ->where('id', 2)  //Industrial
                ->lists('name', 'id');
        }
        return view('BoardMeting::edit-board-meting', compact('bm_data', 'meetingType'));
    }

    private function _dateTimeConvartFromDateTimePicker($requestDateTime)
    {
//        list($day, $month, $year) = preg_split('/[\/\s:]+/', $requestDateTime);
//        $convertDateTime =  $d1me = $year . '-' . $month. '-' .  $day;
//        $ConvertMysqlFormat =  date('Y-m-d', strtotime($convertDateTime));
//        return $ConvertMysqlFormat; // dataType in DB is datetime

        list($day, $month, $year, $hour, $minute, $dayType) = preg_split('/[\/\s:]+/', $requestDateTime);
        if ($hour == 12 && $dayType == "pm") {
            $dayType = "am"; // for 12 PM
        } elseif ($hour == 12 && $dayType == "am") {
            $hour = "00";
//                $dayType = "pm";
            // for 12 AM
        }
        $convertDateTime = $d1me = $year . '-' . $month . '-' . $day . ' ' . ($dayType == "pm" ? $hour + 12 : $hour) . ":" . $minute . ":00";
        $time = explode(" ", $convertDateTime);
        $ConvertMysqlFormat = date('Y-m-d', strtotime($convertDateTime)) . " " . $time[1];
        return $ConvertMysqlFormat; // dataType in DB is datetime
    }

    public function storeMeeting(Request $request)
    {

//        $rules = [];
//        $rules['meting_date'] = 'required|unique:board_meting,meting_date';
//        $messages['meting_date.min'] = 'Meeting date must me unique.';
//        $this->validate($request, $rules, $messages);
        $new_meeting = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_date'));
//        $check_active_metting = BoardMeting::where('meting_date', $new_meeting)
//            ->where('meting_type', $request->get('meeting_type'))
//            ->first();
//        if (!empty($check_active_metting)) {
//            Session::flash('error','Duplicate meeting date in the same time');
//            return \redirect()->back()->withInput();
//        }

        try {
            DB::beginTransaction();
            $boardMeeting = new BoardMeting();
            $boardMeeting->meting_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_date'));
            $boardMeeting->meting_subject = $request->get('meeting_subject');
            $boardMeeting->meting_number = $request->get('meting_number');
            $boardMeeting->meting_type = $request->get('meeting_type');
            $boardMeeting->location = $request->get('location');
            $boardMeeting->org_name = $request->get('organization');
            $boardMeeting->org_address = $request->get('organization_address');
            $boardMeeting->notice_details = $request->get('notice_details');
            $boardMeeting->sequence_no = 2; //for first step
            $boardMeeting->is_active = 1;
            $boardMeeting->status = 6;
            $boardMeeting->save();

//            $agendaInfo = new Agenda();
//            $agendaInfo->board_meting_id = $boardMeeting->id;
//            $agendaInfo->is_active = 1;
//            $agendaInfo->save();

            /*$Committee = new Committee();
            $Committee->board_meeting_id = $boardMeeting->id;
            $Committee->user_name = CommonFunction::getUserFullName();
            $Committee->user_email = Auth::user()->user_email;
            $Committee->user_mobile = Auth::user()->user_phone;
            $Committee->designation = Auth::user()->designation;
            $Committee->type = 'No';
            $Committee->save();*/
            DB::commit();

            Session::flash('success', 'Board Meeting Successfully Added!');
            return redirect('/board-meting/committee/' . Encryption::encodeId($boardMeeting->id));
        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@storeMeeting : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [UC5102]');
            return Redirect::back()->withInput();
        }

    }

    public function updateMeeting(Request $request)
    {
        try {
        DB::beginTransaction();
        $boardMeeting = BoardMeting::findOrNew(Encryption::decodeId($request->get('bm_id')));
        $boardMeeting->meting_date = $this->_dateTimeConvartFromDateTimePicker($request->get('meting_date'));
        $boardMeeting->meting_subject = $request->get('meeting_subject');
        $boardMeeting->meting_number = $request->get('meting_number');
        $boardMeeting->meting_number = $request->get('meting_number');
        $boardMeeting->meting_type = $request->get('meeting_type');
        $boardMeeting->location = $request->get('location');
        $boardMeeting->location = $request->get('location');
        $boardMeeting->org_name = $request->get('organization');
        $boardMeeting->org_address = $request->get('organization_address');
        $boardMeeting->notice_details = $request->get('notice_details');
        $boardMeeting->is_active = 1;
        $boardMeeting->status = 6;
        $boardMeeting->sequence_no = 2;
        $boardMeeting->save();

        $alreadyEx = BoardMeting::where('meting_type', $request->get('meeting_type'))
            ->where('meting_number', $request->get('meting_number'))
            ->count();
        if ($alreadyEx > 1) {
            DB::rollback();
            Session::flash('error', 'Meeting number already exist');
            return \redirect()->back()->withInput();
        }

        DB::commit();
        Session::flash('success', 'Board Meeting Successfully Update!');
        return redirect('/board-meting/committee/' . $request->get('bm_id'));
//        return redirect('/board-meting/committee/member-edit/'.$request->get('bm_id'));
//        return redirect('/board-meting/lists/');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error occurred in BoardMetingController@updateMeeting : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', "An unexpected error occurred.[BOMC-1002]");
            return redirect()->back()->withInput();
        }

    }

    public function checkNumber(Request $request)
    {
        try {
            $meeting_number = $request->get('meeting_number');
            $meeting_type = $request->get('meeting_type');
            $if_existed_number = BoardMeting::where('meting_number', $meeting_number)
                ->where('meting_type', $meeting_type)
                ->count();

            $if_existed_number;
            
            // if ($if_existed_number > 0) {
            //     return $if_existed_number;
            // } else {
            //     return $if_existed_number;
            // }

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@checkNumber : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [UC5102]');
            return Redirect::back()->withInput();
        }
    }

    public function createShareDocument()
    {
        return view('BoardMeting::create-share-document');
    }

    public function storeShareDocument(Request $request)
    {

        $this->validate($request, [
            'doc_name' => 'required',
            'attachment' => 'required',
            'tag' => 'required',
        ]);
        try {
        $attach_file = $request->file('attachment');
        if ($request->hasFile('attachment')) {
            $fileType = $attach_file->getClientOriginalExtension();
            $getSize = $attach_file->getSize();
            if ($getSize > (1024 * 1024 * 3)) {
                Session::flash('error', 'File size max 3 MB');
                return redirect()->back();
            }
            $support_type = array('pdf', 'xls', 'xlsx', 'ppt', 'pptx', 'docx', 'doc');
            if (!in_array($fileType, $support_type)) {
                Session::flash('error', 'File type must be xls,xlsx,ppt,pptx,pdf,doc,docx format');
                return redirect()->back();
            }

            $original_file = $attach_file->getClientOriginalName();
            $attach_file->move('uploads/boardMeeting/', time() . $original_file);
        }

        BoardMeetingDoc::create([
            'doc_name' => $request->get('doc_name'),
            'file' => 'uploads/boardMeeting/' . time() . $original_file,
            'tag' => $request->get('tag'),
            'ctg_id' => 2,
            'is_active' => 1,
        ]);
        Session::flash('success', 'Share Document Successfully Added!');
        return Redirect::back();

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@storeShareDocument : ' . $e->getMessage() . '. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', 'An unexpected error occurred while uploading the document. Please try again.[BOMC-1003]');
            return redirect()->back();
        }
    }

    public function getShareDocument()
    {
        try{
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeetingDoc::getList();

        return Datatables::of($boardMeting)
            ->make(true);

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@getShareDocument : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return response()->json([
                'error' => true,
                'message' => 'Failed to load board meeting data.[BOMC-1004]',
            ], 500);
        }
    }

    public function viewShareDocument($id)
    {
        $shareDocumentId = Encryption::decodeId($id);
        $doc = BoardMeetingDoc::where('id', $shareDocumentId)->first();
        return view('BoardMeting::view-share-document', compact('doc'));
    }

    public function viewNews($id)
    {
        $news_id = Encryption::decodeId($id);
        $news = Notice::where('id', $news_id)->first();
        return view('BoardMeting::view-news', compact('news'));
    }

    public function fixedMeeting(Request $request)
    {
        try{
        $board_id = Encryption::decodeId($request->get('board_meeting_id'));
        BoardMeting::where('id', $board_id)
            ->update([
                'status' => 5 //5= fixed board-meeting status and ty
            ]);

        return response()->json(['responseCode' => 1, 'status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@fixedMeeting : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return response()->json([
                'responseCode' => 0,
                'status' => 'error',
                'message' => 'An unexpected error occurred.[BOMC-1005]',
            ], 500);
        }
    }


    public function completeMeeting($board_meeting_id)
    {
        try {

            $updateMeetingInfo = ProcessListBoardMeting::where('board_meeting_id', $board_meeting_id)
                ->whereIn('bm_status_id',['',0])
                ->where('pl_agenda_name','!=','')
                ->get();

            foreach ($updateMeetingInfo as $data) {

                $appInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('process_list.id', $data->process_id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_list.*'
                    ]);
                $this->processWiseDesiredDurationUpdate($appInfo, $data);

                $appInfoArray = [
                    'app_id' => $appInfo->ref_id,
                    'status_id' => $appInfo->status_id,
                    'process_type_id' => $appInfo->process_type_id,
                    'tracking_no' => $appInfo->tracking_no,
                    'process_type_name' => $appInfo->process_type_name,
                    'process_supper_name' => $appInfo->process_supper_name,
                    'process_sub_name' => $appInfo->process_sub_name,
                    'remarks' => $data->bm_remarks, // Board meeting remarks
                    'approved_duration_start_date' => $data->duration_start_date_from_dd,
                    'approved_duration_end_date' => $data->duration_end_date_from_dd,
                ];

                //  govt fees calculation WPN, WPE, OPN, OPE
                if (in_array($appInfo->process_type_id, [2, 3, 6, 7, 22])) {
                    $durationData = commonFunction::getDesiredDurationDiffDate($appInfoArray);
                    $appInfoArray['approve_duration_year'] = (int)$durationData['approve_duration_year'];
                }
                // end of gov fees calculation

                // get users email and phone no according to working company id
                $getUserInfo = UtilFunction::geCompanyUsersEmailPhone($appInfo->company_id);

//                $statusName = BoardMeetingProcessStatus::where('id',$data->bm_status_id)->first(['status_name']);
                $desk_id = 1; //default desk id ad desk
                $user_id = 0;
                if ($data->bm_status_id == 8) { // board meeting process status 8=rejected
                    $status = 6; //process status
                    $desk_id = 0;
                    CommonFunction::sendEmailSMS('MC_REJECT', $appInfoArray, $getUserInfo);
                } elseif ($data->bm_status_id == 7) { // board meeting process status 7=Approved
                    $desk_id = 0;
                    $status = 15;//process status
                    if (in_array($appInfo->process_type_id, [5, 9])) { //WPC, OPC
                        $desk_id = 1;
                        CommonFunction::sendEmailSMS('MC_APP_APPROVE', $appInfoArray, $getUserInfo);
                    } elseif (in_array($appInfo->process_type_id, [2, 3, 4, 6, 7, 8, 11, 22])) { // WPN, WPE, WPA, OPN, OPE, OPA, RM
                        $appInfoArray['govt_fees'] = CommonFunction::getGovtFees($appInfoArray);

                        // default resend deadline set for approved application
                        $processPath = new ProcessPathController();
                        $resend_deadline = $processPath->getResendDeadline();
                        $appInfoArray['resend_deadline'] = date('d-M-Y', strtotime($resend_deadline));

                        CommonFunction::sendEmailSMS('MC_APP_APPROVE_AND_PAYMENT', $appInfoArray, $getUserInfo);
                    }
                    // End of code for board meeting approved
                } elseif ($data->bm_status_id == 12) { // board meeting process status 12=Deferred
                    $status = 21;//process status
                    $user_id = 0;
                    //CommonFunction::sendEmailSMS('MC_DEFFER', $appInfoArray, $getUserInfo);
                } elseif ($data->bm_status_id == 13) { // board meeting process status 13=Observation
                    $status = 22;//process status
                    $desk_id = 0;
                    CommonFunction::sendEmailSMS('MC_OBSERVATION', $appInfoArray, $getUserInfo);
                } elseif ($data->bm_status_id == 17) { //board meeting process status 17 = conditional approved
                    $status = 17;//process status
                    $desk_id = 1; //ad desk
                    if (in_array($appInfo->process_type_id, [2, 3, 4, 6, 7, 8, 11, 22])) { // WPN, WPE, WPA, OPN, OPE, OPA, RM
                        $desk_id = 0; //applicant desk for
                        $appInfoArray['govt_fees'] = CommonFunction::getGovtFees($appInfoArray);
                        CommonFunction::sendEmailSMS('MC_APP_CONDITIONAL_APPROVED', $appInfoArray, $getUserInfo);
                    }
                }

                $ProcessList = ProcessList::where('id', $data->process_id)->first();
                $ProcessList->process_desc = $data->bm_remarks;
                $ProcessList->status_id = $status;
                $ProcessList->desk_id = $desk_id;
                $ProcessList->user_id = $user_id;

                /*
                 * Set default resend deadline
                 * status (15) = Approved for Payment
                 * process_type_id (5, 9) = WPC, OPC
                 * */
                if ($status == 15 && !in_array($appInfo->process_type_id, [5, 9]) && !empty($resend_deadline)) {
                    $ProcessList->resend_deadline = $resend_deadline;
                }

                $ProcessList->save();
            }

            // If no pending application then complete the meeting
            $pendingAgendaCount = ProcessListBoardMeting::where('board_meeting_id', $board_meeting_id)
                ->whereIn('bm_status_id',['',0])
                ->where('pl_agenda_name','!=','')
                ->count();

            if ($pendingAgendaCount == 0) {
                $meeting = BoardMeting::where('id', $board_meeting_id)->first();
                $meeting->status = 10;
                $meeting->sequence_no = 6;
                $meeting->reference_no = '';
                $meeting->save();

                // Only download and update the path of agenda meeting minutes
                // $this->downloadAgendaMinutes($board_meeting_id);
            }

            Session::flash('success', 'Your board meeting approved successfully!!');
            return true;

        } catch (Exception $e) {
            Log::error('BoardMetingController@completeMeeting' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return false;
        }
    }

    public function downloadAgendaMinutes($meeting_id)
    {
        try {
        $board_meeting_data = BoardMeting::find($meeting_id);
        $sql1 = "SELECT 'Work Permit' `Module_Name`,
                  SUM(IF(process_type_id=2,1,0)) `New`,
                  SUM(IF(process_type_id=3,1,0)) `Extension`,
                  SUM(IF(process_type_id=4,1,0)) `Amendment`,
                  SUM(IF(process_type_id=5,1,0)) `Cancellation` 
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id 
                  where plbm.board_meeting_id = $meeting_id 
                  and agenda.process_type_id in (2,3,4,5)";

        $wpAppNew = \DB::select(DB::raw($sql1))[0];

        $projectOfficeSql = "SELECT 'Project Office' `Module_Name`,
                  SUM(IF(process_type_id=22,1,0)) `New`,
                  SUM(IF(process_type_id=23,1,0)) `Extension`,
                  SUM(IF(process_type_id=24,1,0)) `Amendment`,
                  SUM(IF(process_type_id=25,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (22, 23, 24, 25) limit 1";
        $projectOfficeApp = \DB::select(DB::raw($projectOfficeSql))[0];

        $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id', $meeting_id)->get();

        $branchNew = 0;
        $liaison_officeNew = 0;
        $representative_officeNew = 0;

        $branchExt = 0;
        $liaison_officeExt = 0;
        $representative_officeExt = 0;

        $branchAme = 0;
        $liaison_officeAme = 0;
        $representative_officeAme = 0;

        $branchCan = 0;
        $liaison_officeCan = 0;
        $representative_officeCan = 0;
        $meetingName = '';
        foreach ($totalApplication as $total) {
            //office permission new
            $sql2 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 6  
                        and `opn_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 3 ) representative_office";
            $typeWiseApplication = \DB::select(DB::raw($sql2))[0];
            $branchNew += $typeWiseApplication->branch_officer;
            $liaison_officeNew += $typeWiseApplication->liaison_office;
            $representative_officeNew += $typeWiseApplication->representative_office;

            //office permission ext
            $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7  
                        and `ope_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7
                        and `ope_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7 
                        and `ope_apps`.`office_type` = 3 ) representative_office";
            $typeWiseApplicationExt = \DB::select(DB::raw($sql3))[0];
            $branchExt += $typeWiseApplicationExt->branch_officer;
            $liaison_officeExt += $typeWiseApplicationExt->liaison_office;
            $representative_officeExt += $typeWiseApplicationExt->representative_office;

            //office permission Amendment
            $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 8 
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 3 ) representative_office";
            $typeWiseApplicationAme = \DB::select(DB::raw($sql3))[0];
            $branchAme += $typeWiseApplicationAme->branch_officer;
            $liaison_officeAme += $typeWiseApplicationAme->liaison_office;
            $representative_officeAme += $typeWiseApplicationAme->representative_office;

            //office permission Cancellation
            $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 3 ) representative_office";
            $typeWiseApplicationCan = \DB::select(DB::raw($sql3))[0];
            $branchCan += $typeWiseApplicationCan->branch_officer;
            $liaison_officeCan += $typeWiseApplicationCan->liaison_office;
            $representative_officeCan += $typeWiseApplicationCan->representative_office;
        }


        $countAllApplication = $branchNew + $branchExt + $branchAme + $branchCan + $liaison_officeNew + $liaison_officeExt +
            $liaison_officeAme + $liaison_officeCan + $representative_officeNew + $representative_officeExt +
            $representative_officeAme + $representative_officeCan + $wpAppNew->New + $wpAppNew->Extension +
            $wpAppNew->Amendment + $wpAppNew->Cancellation+
            $projectOfficeApp->New;


        $BoardMeetingWiseAllAgenda = Agenda::where('board_meting_id', $meeting_id)
            ->orderBy('name')
            ->groupBy('name')
            ->groupBy('agenda_type')
            ->get();

        $arrayData = [];
        foreach ($BoardMeetingWiseAllAgenda as $data) {

            $array1 = MeetingMinutesMapping::where('agenda_name', $data->name)
                ->where('type', $data->agenda_type)
                ->orderBy('agenda_name')
                ->first(['agenda_name', 'type', 'agenda_heading_title', 'table_heading_json_format']);
            $array1['process_type_id'] = $data->process_type_id;
            $arrayData[] = $array1;
        }
//        dd($arrayData);
        $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();

        $WP_visaTypes = VisaTypes::where('status', 1)->where('is_archive', 0)->orderBy('type', 'asc')->lists('type', 'id');
        $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');
        $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
        $paymentType = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
        $ms = 0;

        if ($board_meeting_data->meting_type == 2) { //
            $totalApplication = ProcessListBoardMeting::leftJoin('board_meeting_process_status', 'process_list_board_meeting.bm_status_id', '=', 'board_meeting_process_status.id')
                ->where('process_list_board_meeting.board_meeting_id', $meeting_id)
                ->select('process_list_board_meeting.*', 'board_meeting_process_status.status_name')
                ->get();

//            $applicatonData = ProcessList::leftJoin('ra_apps', 'process_list.ref_id', '=', 'ra_apps.id')
//                ->where('process_list.process_type_id', 11)
//                ->where('process_list.id',$totalApplication[0]->process_id)
//                ->first(['ra_apps.*']);
//
//            $ra_bida_reg_info = BidaRegInfo::where('app_id', $applicatonData->id)->get();
            $SubSector = SubSector::orderBy('name')->lists('name', 'id')->all();
            $EA_OrganizationStatus = EA_OrganizationStatus::orderBy('name')->lists('name', 'id')->all();
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');
//            $briefDescription= BriefDescription::where('app_id', $applicatonData->id)
//                ->get(['brief_description']);


            $sql1 = "SELECT 'Remittance' `Module_Name`,
                  SUM(IF(process_type_id=11,1,0)) `New`,
                  SUM(IF(process_type_id=12,1,0)) `Extension`,
                  SUM(IF(process_type_id=13,1,0)) `Amendment`,
                  SUM(IF(process_type_id=14,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (11,12,13,14) limit 1";
            $remittance = \DB::select(DB::raw($sql1))[0];
            $remittanceType = RemittanceType::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');

            $contents = view::make('BoardMeting::agenda.meeting-minutes-remittance', compact("meetingInfo", "board_meeting_data", "getChairperson", "processWiseTotalApplication",
                "arrayData", "processWiseTotalApplication", "meeting_id", "nationality", "divisions", "districts", "thana",
                "countAllApplication", "currencies", "WP_visaTypes", "countries", "ms", "SubSector", "EA_OrganizationStatus", "remittancePresentStatus", "remittance", "totalApplication", "remittanceType"))->render();
            $meetingName = "Executive Council of BIDA";

        } else {
            $contents = view::make('BoardMeting::agenda.meeting-minutes', compact("meetingInfo", "board_meeting_data", "getChairperson", "processWiseTotalApplication",
                "wpAppNew", "arrayData", "processWiseTotalApplication", "meeting_id", "nationality", "divisions", "districts", "thana",
                "branchNew", "liaison_officeNew", "representative_officeNew",
                "branchExt", "liaison_officeExt", "representative_officeExt",
                "branchAme", "liaison_officeAme", "representative_officeAme",
                "branchCan", "liaison_officeCan", "representative_officeCan",
                "countAllApplication", "currencies", "WP_visaTypes", "countries", "ms", "paymentType", "projectOfficeApp"))->render();
            $meetingName = "Inter-ministerial meeting minutes";
        }

        // exit();
        // return $contents;

//            $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
//            $fontDirs = $defaultConfig['fontDir'];
//
//            $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
//            $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new mPDF(
//            'utf-8', // mode - default ''
//            'A4', // format - A4, for example, default ''
//            12, // font size - default 0
//            'Times New Roman"', // default font family
//            10, // margin_left
//            10, // margin right
//            10, // margin top
//            15, // margin bottom
//            10, // margin header
//            9, // margin footer
//            'P'
        );
        $mpdf->AddPage('L'); // Adds a new page in Landscape orientation

        $mpdf->Bookmark('Start of the document');
        $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("Bangladesh Investment Development Authority (BIDA)");
        $mpdf->SetSubject("Subject");
        $mpdf->SetAuthor("Business Automation Limited");
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->SetHTMLFooter('
            <table width="100%" border="0">
             <tr>
            <td width="33%">' . $board_meeting_data->meting_number . ' ' . $meetingName . '</td>
            <td width="33%" align="center">Holding Date: ' . $newDate = date("d.m.Y", strtotime($board_meeting_data->meting_date)) . '</td>
            <td width="33%" style="text-align: right;">{PAGENO}/{nbpg}</td>
             </tr>
            </table>');
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode('fullwidth');
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $stylesheet = file_get_contents('assets/css/pdf_download_check_v1.css');
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($contents, 2);

        $mpdf->defaultfooterfontsize = 10;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;

        $mpdf->SetCompression(true);

        $baseURL = "uploads/";
        $directoryName = $baseURL . date("Y/m");
        $directoryNameYear = $baseURL . date("Y");

        if (!file_exists($directoryName)) {
            
            mkdir($directoryName, 0755, true);
            
            $f = fopen($directoryName . "/index.html", "w");
            fclose($f);
            if (!file_exists($directoryNameYear . "/index.html")) {
                $f = fopen($directoryNameYear . "/index.html", "w");
                fclose($f);
            }
        }
        $certificateName = 'Meeting_Minutes_'.$meeting_id.'_'. date("Y_m_d");
        $pdfFilePath = $directoryName . "/" . $certificateName . '.pdf';
        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

        $meeting = BoardMeting::where('id', $meeting_id)->first();
//        $meeting->status = 10;
//        $meeting->sequence_no = 6;
//        $meeting->reference_no = '';
        $meeting->meeting_minutes_path = $pdfFilePath;
        $meeting->save();
//        BoardMeting::where('id',$meeting_id)
//            ->update([
//                'status'=> 10, //10= complete board-meeting status
//                'sequence_no'=> 6,
//                'reference_no'=> '',
//                'meeting_minutes_path' =>$pdfFilePath,
//
//            ]);
        return true;

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@downloadAgendaMinutes : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return false;
        }
    }

    public function generateMeetingMinutesDoc($board_meeting_id)
    {
        try {

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
        $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id', $meeting_id)->get();
        $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
        $paymentType = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');

        $BoardMeetingWiseAllAgenda = Agenda::where('board_meting_id', $meeting_id)
            ->orderBy('name')
            ->groupBy('name')
            ->groupBy('agenda_type')
            ->get();
        $arrayData = [];
        foreach ($BoardMeetingWiseAllAgenda as $data) {

            $array1 = MeetingMinutesMapping::where('agenda_name', $data->name)
                ->where('type', $data->agenda_type)
                ->orderBy('agenda_name')
                ->first(['agenda_name', 'type', 'agenda_heading_title', 'table_heading_json_format']);
            $array1['process_type_id'] = $data->process_type_id;
            $arrayData[] = $array1;
        }

        if ($board_meeting_data->meting_type == 2) { // Executive Council Meeting
            $SubSector = SubSector::orderBy('name')->lists('name', 'id')->all();
            $EA_OrganizationStatus = EA_OrganizationStatus::orderBy('name')->lists('name', 'id')->all();
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $remittanceType = RemittanceType::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');

            $sql1 = "SELECT 'Remittance' `Module_Name`,
                  SUM(IF(process_type_id=11,1,0)) `New`,
                  SUM(IF(process_type_id=12,1,0)) `Extension`,
                  SUM(IF(process_type_id=13,1,0)) `Amendment`,
                  SUM(IF(process_type_id=14,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (11,12,13,14) limit 1";
            $remittance = \DB::select(DB::raw($sql1))[0];
            $ms = 1;
            $contents = view('BoardMeting::agenda.agenda-download-remittance', compact(
                "meeting_id", 'board_meeting_data', "nationality", 'arrayData',
                "divisions", 'ms', "countAllApplication", "districts", "thana", "countries", "remittance", "applicatonData", "ra_bida_reg_info",
                "SubSector", "EA_OrganizationStatus", "remittancePresentStatus", "totalApplication", "remittanceType"
            ))->render();


        } else {

            $sql1 = "SELECT 'Work Permit' `Module_Name`,
                  SUM(IF(process_type_id=2,1,0)) `New`,
                  SUM(IF(process_type_id=3,1,0)) `Extension`,
                  SUM(IF(process_type_id=4,1,0)) `Amendment`,
                  SUM(IF(process_type_id=5,1,0)) `Cancellation` 
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id 
                  where plbm.board_meeting_id = $meeting_id 
                  and agenda.process_type_id in (2,3,4,5)";

            $wpAppNew = \DB::select(DB::raw($sql1))[0];

            $projectOfficeSql = "SELECT 'Project Office' `Module_Name`,
                  SUM(IF(process_type_id=22,1,0)) `New`,
                  SUM(IF(process_type_id=23,1,0)) `Extension`,
                  SUM(IF(process_type_id=24,1,0)) `Amendment`,
                  SUM(IF(process_type_id=25,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (22, 23, 24, 25) limit 1";
            $projectOfficeApp = \DB::select(DB::raw($projectOfficeSql))[0];

//            $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id', $meeting_id)->get();

            $branchNew = 0;
            $liaison_officeNew = 0;
            $representative_officeNew = 0;

            $branchExt = 0;
            $liaison_officeExt = 0;
            $representative_officeExt = 0;

            $branchAme = 0;
            $liaison_officeAme = 0;
            $representative_officeAme = 0;

            $branchCan = 0;
            $liaison_officeCan = 0;
            $representative_officeCan = 0;

            foreach ($totalApplication as $total) {
                //office permission new
                $sql2 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 6  
                        and `opn_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplication = \DB::select(DB::raw($sql2))[0];
                $branchNew += $typeWiseApplication->branch_officer;
                $liaison_officeNew += $typeWiseApplication->liaison_office;
                $representative_officeNew += $typeWiseApplication->representative_office;

                //office permission ext
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7  
                        and `ope_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7
                        and `ope_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7 
                        and `ope_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationExt = \DB::select(DB::raw($sql3))[0];
                $branchExt += $typeWiseApplicationExt->branch_officer;
                $liaison_officeExt += $typeWiseApplicationExt->liaison_office;
                $representative_officeExt += $typeWiseApplicationExt->representative_office;

                //office permission Amendment
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 8 
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationAme = \DB::select(DB::raw($sql3))[0];
                $branchAme += $typeWiseApplicationAme->branch_officer;
                $liaison_officeAme += $typeWiseApplicationAme->liaison_office;
                $representative_officeAme += $typeWiseApplicationAme->representative_office;

                //office permission Cancellation
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationCan = \DB::select(DB::raw($sql3))[0];
                $branchCan += $typeWiseApplicationCan->branch_officer;
                $liaison_officeCan += $typeWiseApplicationCan->liaison_office;
                $representative_officeCan += $typeWiseApplicationCan->representative_office;
            }


            $countAllApplication = $branchNew + $branchExt + $branchAme + $branchCan + $liaison_officeNew + $liaison_officeExt +
                $liaison_officeAme + $liaison_officeCan + $representative_officeNew + $representative_officeExt +
                $representative_officeAme + $representative_officeCan + $wpAppNew->New + $wpAppNew->Extension +
                $wpAppNew->Amendment + $wpAppNew->Cancellation+
                $projectOfficeApp->New;

            $WP_visaTypes = VisaTypes::where('status', 1)->where('is_archive', 0)->orderBy('type', 'asc')->lists('type', 'id');
            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');

            $ms = 1;
            $contents = view::make('BoardMeting::agenda.meeting-minutes-doc', compact("meetingInfo", "board_meeting_data", "getChairperson", "processWiseTotalApplication",
                "wpAppNew", "arrayData", "processWiseTotalApplication", "meeting_id", "nationality", "divisions", "districts", "thana",
                "branchNew", "liaison_officeNew", "representative_officeNew",
                "branchExt", "liaison_officeExt", "representative_officeExt",
                "branchAme", "liaison_officeAme", "representative_officeAme",
                "branchCan", "liaison_officeCan", "representative_officeCan",
                "countAllApplication", "currencies", "WP_visaTypes", "countries", "ms", "paymentType","projectOfficeApp"))->render();
        }
        $headers = array(
            "Content-type" => "application/vnd.doc",
            "Expires" => "0",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Content-Disposition" => "attachment;filename=minutes_" . $meeting_id . ".doc",
        );

        return response()->make($contents, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@generateMeetingMinutesDoc : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', 'An unexpected error occurred.[BOMC-1007]');
            return redirect()->back();
        }

    }

    public function generateMeetingMinutesPdf($encodedBoardMeetingId)
    {
        try {
            $board_meeting_id = Encryption::decodeId($encodedBoardMeetingId);
            $boardMeetingData = BoardMeting::where('id', $board_meeting_id)->first(['meeting_minutes_path']);

            if (!$boardMeetingData) {
                Session::flash('error', 'Board meeting not found.');
                return redirect()->back();
            }
    
            $filePath = $boardMeetingData->meeting_minutes_path;
            if ($filePath) {
                $fullPath = public_path($filePath);
                if (file_exists($fullPath)) {
                    return response()->download($fullPath);
                }
            }
    
            // If file does not exist, generate minutes and show message
            $is_generated = $this->downloadAgendaMinutes($board_meeting_id);
            
            if ($is_generated) {
                // Try to fetch the newly generated file
                $boardMeetingNewData = BoardMeting::where('id', $board_meeting_id)->first(['meeting_minutes_path']);
                $filePath = $boardMeetingNewData->meeting_minutes_path;
                if ($filePath) {
                    $fullPath = public_path($filePath);
                    if (file_exists($fullPath)) {
                        return response()->download($fullPath);
                    }
                }
            }
            
            Session::flash('error', 'Something went wrong while generating the Meeting Minutes PDF. Please try again.');
            return redirect()->back();
            
        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@generateMeetingMinutesPdf : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', 'An unexpected error occurred while generating the Meeting Minutes PDF.');
            return redirect()->back();
        }
        
    } // end generateMeetingMinutesPdf()


    public function downloadAgendaAsExcel($board_meeting_id)
    {
        try {

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);

        $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id',$meeting_id)
            ->get([
                'process_id'
            ]);

        $BoardMeetingWiseAllAgenda = Agenda::where('board_meting_id',$meeting_id)
            ->orderBy('name')
            ->groupBy('name')
            ->groupBy('agenda_type')
            ->get([
                'name',
                'agenda_type',
                'process_type_id',
            ]);

        $arrayData = [];
        foreach ($BoardMeetingWiseAllAgenda as $data) {
            $array1 = AgendaMapping::where('agenda_name',$data->name)
                ->where('type',$data->agenda_type)
                ->orderBy('agenda_name')
                ->first([
                    'agenda_name',
                    'type',
                    'agenda_heading_title',
                    'table_heading_json_format'
                ]);
            $array1['process_type_id'] = $data->process_type_id;
            $arrayData[] = $array1;
        }

        $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();

        if ($board_meeting_data->meting_type == 1) { // Inter-Ministerial Committee Meeting
            $sql1 = "SELECT 'Work Permit' `Module_Name`,
                  SUM(IF(process_type_id=2,1,0)) `New`,
                  SUM(IF(process_type_id=3,1,0)) `Extension`,
                  SUM(IF(process_type_id=4,1,0)) `Amendment`,
                  SUM(IF(process_type_id=5,1,0)) `Cancellation` 
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id 
                  where plbm.board_meeting_id = $meeting_id 
                  and agenda.process_type_id in (2,3,4,5) limit 1";

            $wpAppNew = \DB::select(DB::raw($sql1))[0];

            $projectOfficeSql = "SELECT 'Project Office' `Module_Name`,
                  SUM(IF(process_type_id=22,1,0)) `New`,
                  SUM(IF(process_type_id=23,1,0)) `Extension`,
                  SUM(IF(process_type_id=24,1,0)) `Amendment`,
                  SUM(IF(process_type_id=25,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (22, 23, 24, 25) limit 1";
            $projectOfficeApp = \DB::select(DB::raw($projectOfficeSql))[0];

            $branchNew = 0;
            $liaison_officeNew = 0;
            $representative_officeNew = 0;

            $branchExt = 0;
            $liaison_officeExt = 0;
            $representative_officeExt = 0;

            $branchAme = 0;
            $liaison_officeAme = 0;
            $representative_officeAme = 0;

            $branchCan = 0;
            $liaison_officeCan = 0;
            $representative_officeCan = 0;

            foreach ($totalApplication as $total) {
                //office permission new
                $sql2 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 6  
                        and `opn_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplication = \DB::select(DB::raw($sql2))[0];
                $branchNew += $typeWiseApplication->branch_officer;
                $liaison_officeNew += $typeWiseApplication->liaison_office;
                $representative_officeNew += $typeWiseApplication->representative_office;

                //office permission ext
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7  
                        and `ope_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7
                        and `ope_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7 
                        and `ope_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationExt = \DB::select(DB::raw($sql3))[0];
                $branchExt += $typeWiseApplicationExt->branch_officer;
                $liaison_officeExt += $typeWiseApplicationExt->liaison_office;
                $representative_officeExt += $typeWiseApplicationExt->representative_office;

                //office permission Amendment
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 8 
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationAme = \DB::select(DB::raw($sql3))[0];
                $branchAme += $typeWiseApplicationAme->branch_officer;
                $liaison_officeAme += $typeWiseApplicationAme->liaison_office;
                $representative_officeAme += $typeWiseApplicationAme->representative_office;

                //office permission Cancellation
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationCan = \DB::select(DB::raw($sql3))[0];
                $branchCan += $typeWiseApplicationCan->branch_officer;
                $liaison_officeCan += $typeWiseApplicationCan->liaison_office;
                $representative_officeCan += $typeWiseApplicationCan->representative_office;
            }

            $countAllApplication = $branchNew + $branchExt + $branchAme + $branchCan + $liaison_officeNew + $liaison_officeExt +
                $liaison_officeAme + $liaison_officeCan + $representative_officeNew + $representative_officeExt +
                $representative_officeAme + $representative_officeCan + $wpAppNew->New + $wpAppNew->Extension +
                $wpAppNew->Amendment + $wpAppNew->Cancellation+
                $projectOfficeApp->New;

            $paymentType = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $WP_visaTypes = VisaTypes::where('status', 1)->where('is_archive', 0)->orderBy('type', 'asc')->lists('type', 'id');
            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');

            $ms = 1;
            $contents = view('BoardMeting::agenda.agenda-download-excel', compact("meetingInfo", "board_meeting_data",
                "wpAppNew", "BoardMeetingWiseAllAgenda", "arrayData", "meeting_id", "nationality",
                "branchNew", "liaison_officeNew", "representative_officeNew",
                "branchExt", "liaison_officeExt", "representative_officeExt",
                "branchAme", "liaison_officeAme", "representative_officeAme",
                "branchCan", "liaison_officeCan", "representative_officeCan", "paymentType",
                "countAllApplication", "WP_visaTypes", "currencies", "ms", "divisions", "districts", "thana", "countries", "projectOfficeApp"))->render();
            $headers = array(
                "Content-type" => "application/xls",
                "Expires" => "10",
                "font-size" => 13,
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition" => "attachment;filename=Agenda" . $meeting_id . "_" . date("Y/m") . ".xls",
            );

            return response()->make($contents, 200, $headers);
        }
        elseif ($board_meeting_data->meting_type == 2) { // Executive Council of BIDA
            $SubSector = SubSector::orderBy('name')->lists('name', 'id')->all();
            $EA_OrganizationStatus = EA_OrganizationStatus::orderBy('name')->lists('name', 'id')->all();
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $remittanceType = RemittanceType::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');

            $sql1 = "SELECT 'Remittance' `Module_Name`,
                  SUM(IF(process_type_id=11,1,0)) `New`,
                  SUM(IF(process_type_id=12,1,0)) `Extension`,
                  SUM(IF(process_type_id=13,1,0)) `Amendment`,
                  SUM(IF(process_type_id=14,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (11,12,13,14) limit 1";
            $remittance = \DB::select(DB::raw($sql1))[0];
            $ms = 1;
            $contents = view('BoardMeting::agenda.agenda-download-remittance', compact(
                "meeting_id", 'board_meeting_data', "nationality", 'arrayData',
                "divisions", 'ms', "countAllApplication", "districts", "thana", "countries", "remittance", "applicatonData", "ra_bida_reg_info",
                "SubSector", "EA_OrganizationStatus", "remittancePresentStatus", "totalApplication", "remittanceType"
            ))->render();

            $headers = array(
                "Content-type" => "application/vnd.doc",
                "Expires" => "0",
                "font-size" => 6,
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition" => "attachment;filename=Agenda" . $meeting_id . "_" . date("Y/m") . ".doc",
            );

            return response()->make($contents, 200, $headers);
        }
        else {
            Session::flash('error', "Sorry! No meeting type found!");
            return redirect()->back();
        }

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@downloadAgendaAsExcel : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', "An unexpected error occurred.[BOMC-1008]");
            return redirect()->back();
        }
    }

    public function downloadAgendaAsDoc($board_meeting_id)
    {
        try {

        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);

        $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id',$meeting_id)
            ->get([
                'process_id'
            ]);

        $BoardMeetingWiseAllAgenda = Agenda::where('board_meting_id',$meeting_id)
            ->orderBy('name')
            ->groupBy('name')
            ->groupBy('agenda_type')
            ->get([
                'name',
                'agenda_type',
                'process_type_id',
            ]);

        $arrayData = [];
        foreach ($BoardMeetingWiseAllAgenda as $data) {
            $array1 = AgendaMapping::where('agenda_name', $data->name)
                ->where('type', $data->agenda_type)
                ->orderBy('agenda_name')
                ->first([
                    'agenda_name',
                    'type',
                    'agenda_heading_title',
                    'table_heading_json_format'
                ]);

            $array1['process_type_id'] = $data->process_type_id;
            $arrayData[] = $array1;
        }

        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');

        if ($board_meeting_data->meting_type == 1) { // Inter-Ministerial Committee Meeting
            $sql1 = "SELECT 'Work Permit' `Module_Name`,
                  SUM(IF(process_type_id=2,1,0)) `New`,
                  SUM(IF(process_type_id=3,1,0)) `Extension`,
                  SUM(IF(process_type_id=4,1,0)) `Amendment`,
                  SUM(IF(process_type_id=5,1,0)) `Cancellation` 
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id 
                  where plbm.board_meeting_id = $meeting_id 
                  and agenda.process_type_id in (2,3,4,5) limit 1";

            $wpAppNew = \DB::select(DB::raw($sql1))[0];

            $projectOfficeSql = "SELECT 'Project Office' `Module_Name`,
                  SUM(IF(process_type_id=22,1,0)) `New`,
                  SUM(IF(process_type_id=23,1,0)) `Extension`,
                  SUM(IF(process_type_id=24,1,0)) `Amendment`,
                  SUM(IF(process_type_id=25,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (22, 23, 24, 25) limit 1";
            $projectOfficeApp = \DB::select(DB::raw($projectOfficeSql))[0];

            $branchNew = 0;
            $liaison_officeNew = 0;
            $representative_officeNew = 0;

            $branchExt = 0;
            $liaison_officeExt = 0;
            $representative_officeExt = 0;

            $branchAme = 0;
            $liaison_officeAme = 0;
            $representative_officeAme = 0;

            $branchCan = 0;
            $liaison_officeCan = 0;
            $representative_officeCan = 0;

            foreach ($totalApplication as $total) {
                // office permission new
                $sql2 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 6  
                        and `opn_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplication = \DB::select(DB::raw($sql2))[0];
                $branchNew += $typeWiseApplication->branch_officer;
                $liaison_officeNew += $typeWiseApplication->liaison_office;
                $representative_officeNew += $typeWiseApplication->representative_office;

                // office permission extension
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7  
                        and `ope_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7
                        and `ope_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7 
                        and `ope_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationExt = \DB::select(DB::raw($sql3))[0];
                $branchExt += $typeWiseApplicationExt->branch_officer;
                $liaison_officeExt += $typeWiseApplicationExt->liaison_office;
                $representative_officeExt += $typeWiseApplicationExt->representative_office;

                // office permission Amendment
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 8 
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationAme = \DB::select(DB::raw($sql3))[0];
                $branchAme += $typeWiseApplicationAme->branch_officer;
                $liaison_officeAme += $typeWiseApplicationAme->liaison_office;
                $representative_officeAme += $typeWiseApplicationAme->representative_office;

                // office permission cancellation
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 1 ) branch_officer,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationCan = \DB::select(DB::raw($sql3))[0];
                $branchCan += $typeWiseApplicationCan->branch_officer;
                $liaison_officeCan += $typeWiseApplicationCan->liaison_office;
                $representative_officeCan += $typeWiseApplicationCan->representative_office;
            }

            $countAllApplication = $branchNew + $branchExt + $branchAme + $branchCan + $liaison_officeNew + $liaison_officeExt +
                $liaison_officeAme + $liaison_officeCan + $representative_officeNew + $representative_officeExt +
                $representative_officeAme + $representative_officeCan + $wpAppNew->New + $wpAppNew->Extension +
                $wpAppNew->Amendment + $wpAppNew->Cancellation+
                $projectOfficeApp->New;

            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $WP_visaTypes = VisaTypes::where('status', 1)->where('is_archive', 0)->orderBy('type', 'asc')->lists('type', 'id');
            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');
            $paymentType = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');

            $ms = 1;
            $contents = view('BoardMeting::agenda.agenda-download-doc', compact("meetingInfo", "board_meeting_data",
                "wpAppNew", "BoardMeetingWiseAllAgenda", "arrayData", "meeting_id", "nationality",
                "branchNew", "liaison_officeNew", "representative_officeNew",
                "branchExt", "liaison_officeExt", "representative_officeExt",
                "branchAme", "liaison_officeAme", "representative_officeAme",
                "branchCan", "liaison_officeCan", "representative_officeCan", "paymentType",
                "countAllApplication", "WP_visaTypes", "currencies", "ms", "divisions", "districts", "thana", "countries", "projectOfficeApp"))->render();
            $headers = array(
                "Content-type" => "application/vnd.doc",
                "Expires" => "0",
                "font-size" => 6,
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition" => "attachment;filename=Agenda_" . $meeting_id . "_" . date("Y/m/d") . ".doc",
            );

            return response()->make($contents, 200, $headers);
        }
        elseif ($board_meeting_data->meting_type == 2) { // Executive Council of BIDA
            $SubSector = SubSector::orderBy('name')->lists('name', 'id')->all();
            $EA_OrganizationStatus = EA_OrganizationStatus::orderBy('name')->lists('name', 'id')->all();
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $remittanceType = RemittanceType::orderby('name')->where('status', 1)->where('is_archive', 0)->lists('name', 'id');

            $sql1 = "SELECT 'Remittance' `Module_Name`,
                  SUM(IF(process_type_id=11,1,0)) `New`,
                  SUM(IF(process_type_id=12,1,0)) `Extension`,
                  SUM(IF(process_type_id=13,1,0)) `Amendment`,
                  SUM(IF(process_type_id=14,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (11,12,13,14) limit 1";
            $remittance = \DB::select(DB::raw($sql1))[0];
            $ms = 1;
            $contents = view('BoardMeting::agenda.agenda-download-remittance', compact(
                "meeting_id", 'board_meeting_data', "nationality", 'arrayData',
                "divisions", 'ms', "countAllApplication", "districts", "thana", "countries", "remittance", "applicatonData", "ra_bida_reg_info",
                "SubSector", "EA_OrganizationStatus", "remittancePresentStatus", "totalApplication", "remittanceType"
            ))->render();

            $headers = array(
                "Content-type" => "application/vnd.doc",
                "Expires" => "0",
                "font-size" => 6,
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Content-Disposition" => "attachment;filename=Agenda" . $meeting_id . "_" . date("Y/m") . ".doc",
            );

            return response()->make($contents, 200, $headers);
        }
        else {
            Session::flash('error', "Sorry! No meeting type found!");
            return redirect()->back();
        }

        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@downloadAgendaAsDoc : ' . $e->getMessage() . '. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', "An unexpected error occurred.[BOMC-1009]");
            return redirect()->back();
        }
    }

    public function getCompleteMeeting()
    {
        try {
       
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $boardMeting = BoardMeting::getCompleteList();

        return Datatables::of($boardMeting)
            ->addColumn('action', function ($boardMeting) use ($mode) {
                if ($mode) {
                    $button = '';
                    $getChairpersonEmail = CommonFunction::checkChairperson($boardMeting->id);
//                    if (CommonFunction::getUserType() == '13x303' && $boardMeting->board_meeting_status == 10 && $boardMeting->meeting_minutes_path !=''){ //13X303= board admin
//                            $button.= ' <button  class="btn btn-xs btn-primary publish_complete_meeting" value="'.Encryption::encodeId($boardMeting->id).'" ><i class="fa fa-bell-o"></i> Publish </button> ';
//                    }
                    if (in_array($boardMeting->board_meeting_status, [5, 10, 11])) {

                        $button .= '<a href="' . url('/board-meting/agenda/download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-warning "><i class="fa fa-download"></i>  Download Agenda PDF </a><br> ';
//                            $button.= '<a href="' . url($boardMeting->meeting_agenda_path) . '"  download="" class="btn btn-xs btn-warning "><i class="fa fa-download"></i> Download Agenda PDF</a><br> ';
                        $button .= '<a href="' . url('/board-meting/agenda/doc-download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-danger "><i class="fa fa-download" aria-hidden="true"></i></i> Download Agenda DOC </a><br> ';

                        $button .= '<a href="' . url($boardMeting->meeting_minutes_path) . '"  download="" class="btn btn-xs btn-info "><i class="fa fa-download"></i> Meeting minutes download pdf </a><br> ';
                        $button .= '<a href="' . url('/board-meting/minutes/doc-download/' . Encryption::encodeId($boardMeting->id)) . '" class="btn btn-xs btn-success "><i class="fa fa-download"></i> meeting minutes download doc </a><br> ';
                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->editColumn('meting_date', function ($boardMeting) use ($mode) {
                $html = '<a href="' . url('board-meting/agenda/list/' . Encryption::encodeId($boardMeting->id)) . '" class="hover-item" style="text-decoration: none">

                    <div class="panel  hover-item" style="margin-top: 10px; border: 1px solid #86bb86">
                        <div class="panel-heading" >
                            <div class="row">
                                <div class="col-xs-2">
                                    <div class="h5" style="margin-top:0;margin-bottom:0;font-size: 15px;text-align:right">
                                       </div>
                                         <div style="position: absolute">

                    </div>
                                </div>
                                <div class="col-xs-10 text-right">
                                     Meeting No. ' . $boardMeting->meting_number . '
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 text-right">
                                    <div style="font-size: 12px;color: gray">
                                        <br>
                                      Location. ' . $boardMeting->area_nm . '
                                    </div>
                                </div>
                            </div>
                        </div>
                            <div class="panel-footer" style="padding:  5px; background: linear-gradient(to bottom, #eeeeee 0%,#cccccc 100%);">

                                <span class="text-center">&nbsp;&nbsp;&nbsp;&nbsp; <i class="fa fa-calendar" aria-hidden="true"></i> ' . date("d M Y h:i a", strtotime($boardMeting->meting_date)) . '</span>
                                <span class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                <div class="clearfix"></div>
                            </div>

                    </div>

                </a>';
                return $html;

            })
            ->editColumn('agenda_info', function ($boardMeting) {

                $agenda_data = explode("##", $boardMeting->agenda_info);
                $button = "";
                $i = 0;
                foreach ($agenda_data as $value) {
                    $i++;
                    $row = explode(",", $value);
                    if (!empty($row[0])) {
                        $button .= '
                <a class="hover-item" style="text-decoration: none" href="javascript:void(0)">
                 <div class="panel panel-default hover-item" style="
                    margin-top: 2px; border: 1px solid #86bb86">
                    <div style="position: absolute">
                    </div>
                    <div>
                    <div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right" aria-hidden="true"></i></div>
                    <div class="panel-heading" style="border-left: 5px solid #31708f">
                        <div class="col-md-offset-2"><span style="margin-top: 20px;">' . $row[0] . '</span><br>&nbsp;</div>
                    </div>

                    </div>

                 </div>
                </a>';

                    }
                }
                return $button;

            })
            ->editColumn('status', function ($boardMeting) {
                $activate = 'style="color:white;" class="  btn-xs  label-' . $boardMeting->panel . '" ';
                $status_name = $boardMeting->status_name;

                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@getCompleteMeeting : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            return response()->json([
                'error' => true,
                'message' => 'Failed to load board meeting data.[BOMC-1010]',
            ], 500);
        }
    }

    public function completeMeetingPublish(Request $request)
    {
        exit;
        BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))
            ->update(['status' => 11]);
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    protected function processWiseDesiredDurationUpdate($appInfo, $boardMeetingProcessInfo)
    {
        switch ($appInfo->process_type_id) {

            case 2: // Work Permit New

                $updateDesiredDurationInfo = WorkPermitNew::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 3: // Work Permit Extension

                $updateDesiredDurationInfo = WorkPermitExtension::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 4: // Work Permit Amendment
                $updateDesiredDurationInfo = WorkPermitAmendment::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 5: // Work Permit Cancellation
                $updateDesiredDurationInfo = WorkPermitCancellation::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_effect_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 6: // Office Permission
                $updateDesiredDurationInfo = OfficePermissionNew::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 7: // Office Permission Extension

                $updateDesiredDurationInfo = OfficePermissionExtension::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            case 8: // Office Permission Amendment (8)
                // start date, end date not till now
                return true;
                break;
            case 9: // Office Permission Cancellation

                $updateDesiredDurationInfo = OfficePermissionCancellation::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_effect_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                $updateDesiredDurationInfo->save();

                return true;
                break;

            case 11: // Remittance
                // start date, end date not till now
                return true;
                break;

            case 22: // Project Offcie New
                $updateDesiredDurationInfo = ProjectOfficeNew::where('id', $appInfo->ref_id)->first();
                if (!empty($boardMeetingProcessInfo->duration_start_date_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_start_date = $boardMeetingProcessInfo->duration_start_date_from_dd;
                }
                if ($boardMeetingProcessInfo->duration_end_date_from_dd) {
                    $updateDesiredDurationInfo->approved_duration_end_date = $boardMeetingProcessInfo->duration_end_date_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->desired_duration_from_dd)) {
                    $updateDesiredDurationInfo->approved_desired_duration = $boardMeetingProcessInfo->desired_duration_from_dd;
                }
                if (!empty($boardMeetingProcessInfo->duration_amount_from_dd)) {
                    $updateDesiredDurationInfo->approved_duration_amount = $boardMeetingProcessInfo->duration_amount_from_dd;
                }
                $updateDesiredDurationInfo->save();
                return true;
                break;

            default:
                \Session::flash('error', 'Unknown process type for meeting.[BM-1200]');
                return false;
                break;
        }// ending of switch case

    }

    public function individualCompleteAction($board_meeting_id, $data)
    {
        try {
            $appInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.id', $data->process_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_list.*'
                ]);
            $this->processWiseDesiredDurationUpdate($appInfo, $data);

            $appInfoArray = [
                'app_id' => $appInfo->ref_id,
                'status_id' => $appInfo->status_id,
                'process_type_id' => $appInfo->process_type_id,
                'tracking_no' => $appInfo->tracking_no,
                'process_type_name' => $appInfo->process_type_name,
                'process_supper_name' => $appInfo->process_supper_name,
                'process_sub_name' => $appInfo->process_sub_name,
                'remarks' => $data->bm_remarks, // Board meeting remarks
                'approved_duration_start_date' => $data->duration_start_date_from_dd,
                'approved_duration_end_date' => $data->duration_end_date_from_dd,
            ];

            //  govt fees calculation WPN, WPE, OPN, OPE, PON
            if (in_array($appInfo->process_type_id, [2, 3, 6, 7, 22])) {
                $durationData = commonFunction::getDesiredDurationDiffDate($appInfoArray);
                $appInfoArray['approve_duration_year'] = (int)$durationData['approve_duration_year'];
            }
            // end of gov fees calculation

            // get users email and phone no according to working company id
            $getUserInfo = UtilFunction::geCompanyUsersEmailPhone($appInfo->company_id);

//                $statusName = BoardMeetingProcessStatus::where('id',$data->bm_status_id)->first(['status_name']);
            $desk_id = 1; //default desk id ad desk
            $user_id = 0;
            $status = 19; // default
            if ($data->bm_status_id == 8) { // board meeting process status 8=rejected
                $status = 6; //process status
                $desk_id = 0;
                CommonFunction::sendEmailSMS('MC_REJECT', $appInfoArray, $getUserInfo);
            } elseif ($data->bm_status_id == 7) { // board meeting process status 7=Approved
                $desk_id = 0;
                $status = 15;//process status

                if (in_array($appInfo->process_type_id, [5, 9])) { // WPC, OPC
                    $desk_id = 1;
                    CommonFunction::sendEmailSMS('MC_APP_APPROVE', $appInfoArray, $getUserInfo);
                } elseif (in_array($appInfo->process_type_id, [2, 3, 4, 6, 7, 8, 11, 22])) { // WPN, WPE, WPA, OPN, OPE, OPA, RM, PON
                    $appInfoArray['govt_fees'] = CommonFunction::getGovtFees($appInfoArray);

                    // default resend deadline set for approved application
                    $processPath = new ProcessPathController();
                    $resend_deadline = $processPath->getResendDeadline();
                    $appInfoArray['resend_deadline'] = date('d-M-Y', strtotime($resend_deadline));

                    CommonFunction::sendEmailSMS('MC_APP_APPROVE_AND_PAYMENT', $appInfoArray, $getUserInfo);
                }
                // End of code for board meeting approved
            } elseif ($data->bm_status_id == 12) { // board meeting process status 12=Deferred
                $status = 21;//process status
                $user_id = 0;
                //CommonFunction::sendEmailSMS('MC_DEFFER', $appInfoArray, $getUserInfo);
            } elseif ($data->bm_status_id == 13) { // board meeting process status 13=Observation
                $status = 22;//process status
                $desk_id = 0;
                CommonFunction::sendEmailSMS('MC_OBSERVATION', $appInfoArray, $getUserInfo);
            } elseif ($data->bm_status_id == 17) { //board meeting process status 17 = conditional approved
                $status = 17;//process status
                $desk_id = 1; //ad desk
                if (in_array($appInfo->process_type_id, [2, 3, 4, 6, 7, 8, 11, 22])) { // WPN, WPE, WPA, OPN, OPE, OPA, RM, PON
                    $desk_id = 0; //applicant desk for
                    $appInfoArray['govt_fees'] = CommonFunction::getGovtFees($appInfoArray);
                    CommonFunction::sendEmailSMS('MC_APP_CONDITIONAL_APPROVED', $appInfoArray, $getUserInfo);
                }
            }

            $ProcessList = ProcessList::where('id', $data->process_id)->first();
            $ProcessList->process_desc = $data->bm_remarks;
            $ProcessList->status_id = $status;
            $ProcessList->desk_id = $desk_id;
            $ProcessList->user_id = $user_id;

            /*
             * Set default resend deadline
             * status (15) = Approved for Payment
             * process_type_id (5, 9) = WPC, OPC
             * */
            if ($status == 15 && !in_array($appInfo->process_type_id, [5, 9]) && !empty($resend_deadline)) {
                $ProcessList->resend_deadline = $resend_deadline;
            }

            $ProcessList->save();
            return true;
        } catch (\Exception $e) {
            Log::error('Error occurred in BoardMetingController@individualCompleteAction : ' . $e->getMessage() .'. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[BOMC-1011]");
            return false;
        }
    }

    public function generateDocx()
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();


        $section = $phpWord->addSection();
        $header = $section->addHeader();
//
        $header->addText('This document has a header with just one image.', array('align' => 'right'));
//        $table->addText('This is the header.');
//        $table->addCell(4500)->addImage('_earth.jpg', array('width'=>50, 'height'=>50, 'align'=>'right'));
// Add footer
        $footer = $section->createFooter();
        $footer->addPreserveText('Page {PAGE} of {NUMPAGES}.', array('align' => 'center'));
        // Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(20);
        $section->addImage(config('app.board_meeting_img'));
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);
        $sectionStyle = $section->getStyle();
        $sectionStyle->setMarginRight(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(2));
        $header = $section->addHeader();


// Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');
        try {
            $objWriter->save(storage_path('helloWorld.docx'));
        } catch (\Exception $e) {
        }


        return response()->download(storage_path('helloWorld.docx'));

    }

}
