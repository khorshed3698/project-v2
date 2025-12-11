<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class Users extends Model
{

    protected $table = 'users';
    protected $fillable = array(
        'id',
        'user_first_name',
        'user_middle_name',
        'user_last_name',
        'user_full_name',
        'security_question_id',
        'passport_personal_no',
        'passport_type',
        'passport_surname',
        'passport_issuing_authority',
        'passport_given_name',
        'passport_nationality',
        'passport_nationality_id',
        'passport_DOB',
        'passport_place_of_birth',
        'passport_copy',
        'passport_date_of_issue',
        'passport_date_of_expire',
        'security_answer',
        'company_ids',
        'working_company_id',
        'user_gender',
        'department_id',
        'designation',
        'user_DOB',
        'user_phone',
        'user_number',
        'user_email',
        'user_hash',
        'user_hash_expire_time',
        'social_login',
        'nationality_type',
        'identity_type',
        'user_type',
        'country',
        'country_id',
        'nationality',
        'nationality_id',
        'desk_id',
        'division_id',
        'identity_type',
        'passport_no',
        'user_nid',
        'user_tin',
        'division',
        'district',
        'thana',
        'state',
        'province',
        'road_no',
        'house_no',
        'user_pic',
        'post_code',
        'post_office',
        'user_fax',
        'is_approved',
        'user_status',
        'authorization_file',
        'user_agreement',
        'first_login',
        'user_verification',
        'delegate_to_user_id',
        'delegate_by_user_id',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function ($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }

    public function chekced_verified($TOKEN_NO, $data)
    {
        DB::table($this->table)
            ->where('user_hash', $TOKEN_NO)
            ->update($data);
    }

    public function profile_update($table, $field, $check, $value)
    {
        return DB::table($table)->where($field, $check)->update($value);
    }

    public function getUserList()
    {
        $user_list_query = Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
//                            ->leftJoin('user_desk as ud', 'ud.desk_id', '=', 'users.desk_id')
            ->leftJoin('area_info', 'users.district', '=', 'area_info.area_id')
            ->leftJoin('area_info as ai', 'users.thana', '=', 'ai.area_id')
            ->leftJoin('company_info as ci', 'users.company_ids', '=', 'ci.id')// will be applied only in case of applicant users
            ->orderBy('users.id', 'desc')
            ->orderBy('users.created_at', 'desc')
//                            ->where('users.user_agreement', '!=', 0)
            ->where('users.user_status', '!=', 'rejected');
//                            ->where('users.user_type', '!=', Auth::user()->user_type)

        if (Auth::user()->user_type == '5x505') {
            $user_company_id = Auth::user()->company_ids;
            // $user_company_id contain only working_company_id from user table
            $user_list_query->whereRaw("FIND_IN_SET($user_company_id, users.company_ids)");
        }

        $user_list = $user_list_query->get([
            'users.id',
            DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name) as user_full_name"),
            'users.created_at',
            'users.company_ids',
            'users.user_email',
            'users.user_status',
            'users.is_approved',
            'users.login_token',
            'users.user_first_login',
            'users.user_type',
            'ai.area_nm as thana',
            'area_info.area_nm as users_district',
            'mty.type_name',
            DB::raw("CONCAT(ci.company_name,'  (',ci.company_name_bn, ')') as company_name")
        ]);
        return $user_list;
    }

    public function getHistory($email)
    {
        $users_type = Auth::user()->user_type;
        $type = explode('x', $users_type)[0];
        if ($type == 1) { // 1x101 for Super Admin
            return DB::table('failed_login_history')->where('user_email', $email)->get(['user_email', 'remote_address', 'created_at']);
//                            ->where('users.user_type', '!=', Auth::user()->user_type
        }
    }


    public function getUserRow($user_id)
    {
        return Users::leftJoin('user_types as mty', 'mty.id', '=', 'users.user_type')
            ->leftJoin('department as dpt', 'dpt.id', '=', 'users.department_id')
            ->where('users.id', $user_id)
            ->first(['users.*', 'dpt.id as ezid', 'dpt.name as ez_name', 'mty.type_name', 'mty.id as type_id']);
    }

    public function checkEmailAndGetMemId($user_email)
    {
        return DB::table($this->table)
            ->where('user_email', $user_email)
            ->pluck('id');
    }

    public static function setLanguage($lang)
    {
        Users::find(Auth::user()->id)->update(['user_language' => $lang]);
    }

    /**
     * @param $users object of logged in user
     * @return array
     */
    public static function getUserSpecialFields($users)
    {
        $additional_info = [];
        $user_type = explode('x', $users->user_type)[0];

        switch ($user_type) {

            case 4:  //SB
                $additional_info = [
                    [
                        'caption' => 'District',
                        'value' => $users->district != 0 ? AreaInfo::where('area_id', $users->district)->pluck('area_nm') : '',
                        'caption_thana' => 'Thana',
                        'value_thana' => $users->thana != 0 ? AreaInfo::where('area_id', $users->thana)->pluck('area_nm') : ''
                    ]
                ];
                break;
        }
        return $additional_info;
    }

    /*     * ***************************** Users Model Class ends here ************************* */
}
