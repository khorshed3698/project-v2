<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ProcessListBoardMeting extends Model {

    protected $table = 'process_list_board_meeting';
    protected $fillable = array(
        'id',
        'process_id',
        'agenda_id',
        'bm_status_id',
        'bm_remarks',
        'basic_salary_from_dd',
        'duration_amount_from_dd',
        'desired_duration_from_dd',
        'duration_end_date_from_dd',
        'duration_start_date_from_dd',
        'board_meeting_id',
        'pl_agenda_name',
        'conversation_textarea',
        'decision_textarea',
        'process_desc_from_dd',
        'is_active',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public static function getList(){
        $boardMeeting = ProcessListBoardMeting::
        leftJoin('agenda', 'board_meting.id', '=', 'agenda.board_meting_id')
            ->where('board_meting.is_active',1)
            ->get([DB::raw('GROUP_CONCAT(agenda.name, ", ", agenda.id separator "##") AS agenda_info'),'board_meting.id','board_meting.meting_date','board_meting.location as area_nm','board_meting.is_active as status','board_meting.created_at']);
//        dd($boardMeeting);
        return $boardMeeting;
    }
    public static function getBoardMeetingList($process_type_id = 0, $status = 0, $request, $desk)
    {
        $userType = CommonFunction::getUserType();
        $company_id = Auth::user()->user_sub_type;
        $userDeskIds = CommonFunction::getUserDeskIds();
        $user_id = CommonFunction::getUserId();
        $boardMeting = ProcessListBoardMeting::where('is_archive', 0)->get(['process_id']);
        $arrayData = [];
        foreach ($boardMeting as $getData){
            $arrayData[] = $getData->process_id;
        }

        $query = ProcessList::leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
            ->leftjoin('process_status', function ($on) {
                $on->on('process_list.status_id', '=', 'process_status.id')
                    ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
            })
            ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
            ->where('process_type.active_menu_for', 'like', "%$userType%")
            ->whereNotIn('process_list.id', $arrayData);

        if ($userType == '1x101' || $userType == '2x202') { // System Admin
            $query->whereNotIn('process_list.status_id', [-1, 5]);

        } elseif ($userType == '5x505') { // General User
            $query->whereIn('process_list.company_id', explode(',', $company_id));
        } else {
            if ($desk == 'my-desk') { //Condition applied for my-desk data only

                $query->whereNotIn('process_list.status_id', [-1, 5]);
            }

        }
//        if ($process_type_id) {
//            $query->where('process_list.process_type_id', $process_type_id);
//        }
        $query->where('process_list.process_type_id', $request->get('process_type_id'));


        $query->orderBy('process_list.priority', 'desc');
        $query->orderBy('process_list.created_at', 'desc')->distinct();
        return $query->select([
            'process_list.id',
            'process_list.ref_id',
            'process_list.tracking_no',
            'json_object',
            'process_list.desk_id',
            'process_list.process_type_id',
            'process_list.status_id',
            'process_list.priority',
            'process_list.process_desc',
            'process_list.updated_at',
            'process_list.updated_by',
            'process_list.locked_by',
            'process_list.locked_at',
            'user_desk.desk_name',
            'process_status.status_name',
            'process_type.name as process_name',
            'process_type.form_url'
        ]);

    }

    public static function getBoardMeetingNew($process_type_id = 0, $status = 0, $request, $desk)
    {
        $userType = CommonFunction::getUserType();
        $company_id = Auth::user()->user_sub_type;
        $userDeskIds = CommonFunction::getUserDeskIds();

        $query = ProcessListBoardMeting::leftJoin('process_list', 'process_list_board_meeting.process_id', '=', 'process_list.id')
            ->leftJoin('board_meeting_process_status', 'process_list_board_meeting.bm_status_id', '=', 'board_meeting_process_status.id')
            ->leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
            ->leftjoin('process_status', function ($on) {
                $on->on('process_list.status_id', '=', 'process_status.id')
                    ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
            })
            ->leftJoin('company_info', 'process_list.company_id', '=', 'company_info.id')
            ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
//            ->leftJoin('agenda', 'agenda.id', '=', 'process_list_board_meeting.agenda_id')
//            ->where('process_list_board_meeting.agenda_id',Encryption::decodeId($request->get('agenda_id')))
            ->where('process_list_board_meeting.board_meeting_id',Encryption::decodeId($request->get('board_meeting_id')));
//            ->where('process_list_board_meeting.pl_agenda_name', $request->get('agenda_name'))
            //->where('process_list_board_meeting.is_archive', 0);

//        if ($userType == '1x101' || $userType == '2x202') { // System Admin
//            $query->whereNotIn('process_list.status_id', [-1, 5]);
//        } elseif ($userType == '5x505') { // General User
//            $query->whereIn('process_list.company_id', explode(',', $company_id));
//        } else {
//            if ($desk == 'boardMeting') { //Condition applied for my-desk data only
//                $query->where(function ($query1) use ($userDeskIds){
//                    $query1->whereNotIn('process_list.status_id', [-1, 5]);
//                    //   $query1->where('process_list.desk_id', '=', $userDeskIds);
//                });
//            }
//
//        }

        if ($request->get('process_type_id') != null) {
            $query->where('process_list.process_type_id', $request->get('process_type_id'));
        }

        $query->orderBy('process_list_board_meeting.bm_status_id','asc');
//        $query->groupBy('process_list_board_meeting.id');
//        $query->orderBy('process_list.created_at', 'desc')->distinct();
//        $query->groupBy('process_list_board_meeting.id');
        return $query->select([
            'process_list.id',
            'process_list.ref_id',
            'process_list.tracking_no',
            'process_list.desk_id',
            'process_list.process_type_id',
            'process_list.status_id',
            'process_list.process_desc',
            'process_list.company_id',
            'company_info.company_name',
            'user_desk.desk_name',
            'process_status.status_name',
            'process_type.name as process_name',
            'process_type.form_url',
            'process_list_board_meeting.id as process_list_board_id',
            'process_list_board_meeting.board_meeting_id as pr_board_meeting_id',
            'process_list_board_meeting.bm_remarks',
            'process_list_board_meeting.bm_status_id',
            'process_list_board_meeting.duration_amount_from_dd',
            'process_list_board_meeting.desired_duration_from_dd',
            'process_list_board_meeting.duration_end_date_from_dd',
            'process_list_board_meeting.duration_start_date_from_dd',
            'board_meeting_process_status.status_name as bm_status',
            'board_meeting_process_status.panel',
        ]);
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
