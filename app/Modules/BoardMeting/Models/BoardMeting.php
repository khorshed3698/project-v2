<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class BoardMeting extends Model {

    protected $table = 'board_meting';
    protected $fillable = array(
        'id',
        'meting_number',
        'meting_subject',
        'meeting_type',
        'reference_no',
        'sequence_no',
        'meting_date',
        'agenda_ending_date',
        'meeting_agenda_path',
        'meeting_minutes_path',
        'location',
        'org_name',
        'org_address',
        'notice_details',
        'meting_notice',
        'is_active',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function getList(){

        $userType = CommonFunction::getUserType();
        $userDepartmentIds = CommonFunction::getUserDepartmentIds();

        if ($userType != '13x303') {
            if(in_array($userType, ['1x101' , '1x102'])){
                $boardMeeting = BoardMeting::leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                    ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
                    ->leftJoin('meeting_type', 'board_meting.meting_type', '=', 'meeting_type.id')
                    ->where('board_meting.is_active', 1)
                    ->groupBy('board_meting.id')
                    ->orderBy('board_meting.id', 'DESC')
                    ->select('board_meting.id','meeting_type.name as meting_type ', 'board_meting.meting_number', 'board_meting.meting_date',
                        'board_meting.is_active as status',
                        'bms.status_name','bms.panel');
                return $boardMeeting;
            }
            $departmentIds = Auth::user()->department_id;
            $boardMeeting = MeetingType::where('is_active', 0)->get(); //sample alowys return null
            if($departmentIds == 1){ //Commercial
                $boardMeeting = BoardMeting::leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                    ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
                    ->leftJoin('meeting_type', 'board_meting.meting_type', '=', 'meeting_type.id')
                    ->where('board_meting.is_active', 1)
//                ->whereNotIn('board_meting.status', [10, 11])//10 = complete status 11=
//                    ->where('bmc.user_email', Auth::user()->user_email) //access for the users
                    ->where('board_meting.meting_type', 1) //Commercial
                    ->groupBy('board_meting.id')
                    ->orderBy('board_meting.id', 'DESC')
                    ->select('board_meting.id','meeting_type.name as meting_type ', 'board_meting.meting_number', 'board_meting.meting_date',
                        'board_meting.is_active as status',
                        'bms.status_name','bms.panel');
            }
            elseif($departmentIds == 2){ //Industrial
                $boardMeeting = BoardMeting::leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                    ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
                    ->leftJoin('meeting_type', 'board_meting.meting_type', '=', 'meeting_type.id')
                    ->where('board_meting.is_active', 1)
                    ->where('board_meting.meting_type', 2) //Industrial
//                ->whereNotIn('board_meting.status', [10, 11])//10 = complete status 11=
//                    ->where('bmc.user_email', Auth::user()->user_email) //access for the users
                    ->groupBy('board_meting.id')
                    ->orderBy('board_meting.id', 'DESC')
                    ->select('board_meting.id','meeting_type.name as meting_type ', 'board_meting.meting_number', 'board_meting.meting_date',
                        'board_meting.is_active as status',
                        'bms.status_name','bms.panel');
            }


            return $boardMeeting;
        }
//        else{ // for board meeting admin
//            $boardMeeting = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
//                ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
//                ->where('board_meting.is_active', 1)
//                ->whereNotIn('board_meting.status', [10, 11])//10 = complete status 11=
//                ->groupBy('board_meting.id')
////                ->groupBy('agenda.name')
//                ->orderBy('board_meting.id', 'DESC')
//                ->select([DB::raw('GROUP_CONCAT(DISTINCT agenda.name separator "##") AS agenda_info'),
////                        DB::raw('GROUP_CONCAT(DISTINCT agenda.id separator "##") AS agendaId'),
//                    'board_meting.id', 'board_meting.meting_number', 'board_meting.meting_date',
//                    'board_meting.location as area_nm', 'board_meting.is_active as status', 'board_meting.created_at',
//                    'bms.status_name', 'bms.panel'])
//                ->get();
//        }

    }
    public static function getCompleteList(){

        $userType = CommonFunction::getUserType();
        if ($userType == '13x303') { //board meeting admin
            $boardMeetingComplete = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
                ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                ->where('board_meting.is_active', 1)
                ->whereIn('board_meting.status', [10, 11])//10 = complete status 11=
                ->groupBy('board_meting.id')
                ->orderBy('board_meting.id', 'DESC')
                ->select([DB::raw('GROUP_CONCAT(DISTINCT agenda.name separator "##") AS agenda_info'),
                    'board_meting.id', 'board_meting.meting_number', 'board_meting.meting_date',
                    'board_meting.id','board_meting.meeting_minutes_path','board_meting.meeting_agenda_path','board_meting.status as board_meeting_status','board_meting.meting_number','board_meting.meting_date',
                    'board_meting.location as area_nm', 'board_meting.is_active as status', 'board_meting.created_at',
                    'bms.status_name', 'bms.panel'])
                ->get();
        }else{ //other user type
            $boardMeetingComplete = BoardMeting::leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
                ->leftJoin('board_meeting_process_status as bms', 'board_meting.status', '=', 'bms.id')
                ->leftJoin('boared_meeting_committee as bmc', 'board_meting.id', '=', 'bmc.board_meeting_id')
                ->where('board_meting.is_active',1)
                ->whereIn('board_meting.status',[10,11]) //10 = complete
                ->where('bmc.user_email', Auth::user()->user_email) //access for the users
                ->groupBy('board_meting.id')
                ->orderBy('board_meting.id','DESC')
                ->select([DB::raw('GROUP_CONCAT(DISTINCT agenda.name separator "##") AS agenda_info'),
                    'board_meting.id','board_meting.meeting_minutes_path','board_meting.meeting_agenda_path',
                    'board_meting.status as board_meeting_status','board_meting.meting_number','board_meting.meting_date',
                    'board_meting.location as area_nm','board_meting.is_active as status',
                    'board_meting.created_at','bms.status_name','bms.panel','bmc.user_email'])
                ->get();

        }

        return $boardMeetingComplete;
    }



    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
    /*     * ***************************** Users Model Class ends here ************************* */
}
