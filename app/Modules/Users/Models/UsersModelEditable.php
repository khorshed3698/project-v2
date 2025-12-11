<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class UsersModelEditable extends Model {

    protected $table = 'users';
    protected $fillable = array(
        'user_type',
        'user_sub_type',
        'code',
        'password',
        'user_social_type',
        'user_social_id',
        'user_hash',
        'user_status',
        'user_verification',
        'signature_encode',
        'user_first_name',
        'user_middle_name',
        'user_last_name',
        'user_nid',
        'user_tin',
        'passport_no',
        'passport_nationality_id',
        'passport_type',
        'passport_surname',
        'passport_given_name',
        'passport_personal_no',
        'passport_date_of_expire',
        'user_DOB',
        'country',
        'country_id',
        'user_gender',
        'user_street_address',
        'user_country',
        'user_city',
        'division',
        'user_pic',
        'nationality_id',
        'signature',
        'house_no',
        'road_no',
        'post_code',
        'post_office',
        'designation',
        'user_zip',
        'user_phone',
        'user_email',
        'user_number',
        'nationality_type',
        'identity_type',
        'authorization_file',
        'user_first_login',
        'user_language',
        'security_profile_id',
        'district',
        'thana',
        'state',
        'province',
        'details',
        'user_agreement',
        'is_approved',
        'remember_token',
        'updated_by',
        'user_hash_expire_time',
        'auth_token',
        'auth_token_allow',
        'bank_branch_id'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = CommonFunction::getUserId();
                $post->updated_by = CommonFunction::getUserId();
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }


    /*     * ***************************** Users Model Class ends here ************************* */
}
