<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicationRollback extends Model
{
    protected $table = 'application_rollback_list';

    protected $fillable = [
        'tracking_no',
        'app_tracking_no',
        'data',
        'remarks',
        'status_id',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];



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

    public static function applicationRollbackList()
    {
        $userType = CommonFunction::getUserType();
        $user_id = CommonFunction::getUserId();

        DB::statement(DB::raw('set @rownum=0'));

        $query = ApplicationRollback::leftJoin('users', 'users.id', '=', 'application_rollback_list.updated_by')
            ->where('application_rollback_list.is_archive', 0)
            ->orderBy('application_rollback_list.id', 'desc');

        if ($userType == '4x404') {
            $query->where('application_rollback_list.created_by', $user_id);
        }

        return $query->select([
            'application_rollback_list.id',
            'application_rollback_list.tracking_no',
            'application_rollback_list.app_tracking_no',
            'application_rollback_list.status_id',
            'application_rollback_list.updated_at',
            'application_rollback_list.updated_by',
            DB::raw("CONCAT(users.user_first_name, ' ', users.user_middle_name, ' ', users.user_last_name) as modified_user"),
            DB::raw('@rownum  := @rownum  + 1 AS rownum')
        ]);
    }
}