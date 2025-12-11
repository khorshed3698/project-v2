<?php

namespace App\Modules\Support\Models;

use App\Libraries\Encryption;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class NidSupport extends Model
{

    private $nid_server_address;

    public function __construct()
    {
        $this->nid_server_address = config('app.NID_SERVER');
    }


    protected $table = 'nid';
    protected $fillable = array(
        'id',
        'user_first_name'

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



    public static function getUserNidListFromMongo()
    {
            $NID =new NidSupport();
            $url = $NID->VERIFY_NID_URL('NA', 'NA', 'NA', 'list');
//            dd($url);
            $responses = @file_get_contents($url);
            $responses = str_replace('"_id"', '"id"', $responses);
            $pecah = json_decode($responses);
//            dd($pecah);
            $responseCode = isset($pecah->mongoDBRequest->responseStatus->responseCode) ? intval($pecah->mongoDBRequest->responseStatus->responseCode) : 0;
            $responsesData = $pecah->mongoDBRequest->responseStatus->responseData;
            $NidList = ($responseCode == 200) ? $responsesData : null;

        return $NidList;


    }

    public function VERIFY_NID_URL($nid, $dob, $VERIFICATION_FLAG, $flag = 'verify', $REQUEST_USER_ID = 0, $no_of_try = 0, $nid_id = '',$limit=10)
    {
        $NID_BASE_URL = env('NID_BASE_URL');
        $USER_ID = 0;
        $AUTH_TOKEN = Encryption::decode(Session::get('nidAuthToke'));


        $IS_GOVT = Session::get('management_type');
        $GROUP_CLIENTS = env('GROUP_CLIENTS');
        if ($flag == 'verify') {
            $url = $this->nid_server_address . '/api-request?param={"mongoDBRequest":{"requestData":{"nid":"' . $nid . '","dob":"' . $dob . '","user_id":"' . $USER_ID . '","verification_flag":"' . $VERIFICATION_FLAG . '","is_govt":"' . $IS_GOVT . '","auth_token":"' . $AUTH_TOKEN . '"},"requestType":"VERIFY_NID","version":"1.0"}}';
        } else if ($flag == 'update') {
                $url = $this->nid_server_address . '/api-request?param={"mongoDBRequest":{"requestData":{"nid":"' . $nid . '","dob":"' . $dob . '","user_id":"' . $USER_ID . '","verification_flag":"' . $VERIFICATION_FLAG . '","auth_token":"' . $AUTH_TOKEN . '"},"requestType":"UPDATE_NID","version":"1.0"}}';
        } else if ($flag == 'manual_update') {
            $url = $this->nid_server_address . '/api-request?param={"mongoDBRequest":{"requestData":{"nid":"' . $nid . '","dob":"' . $dob . '","user_id":"' . $USER_ID . '","verification_flag":"' . $VERIFICATION_FLAG . '","no_of_try":"' . $no_of_try . '","nid_id":"' . $nid_id . '","auth_token":"' . $AUTH_TOKEN . '"},"requestType":"MANUAL_NID_UPDATE","version":"1.0"}}';
        } else if ($flag == 'list') {
            $url = $this->nid_server_address . '/api-request?param={"mongoDBRequest":{"requestData":{"group_clients":"PRP,BIDA","nid_id":"' . $nid_id . '","dob":"' . $dob . '","user_id":"' . $USER_ID . '","request_user_id":"' . $REQUEST_USER_ID .'","nid":"'.$nid . '","auth_token":"' . $AUTH_TOKEN . '","limit":"'.$limit.'"},"requestType":"NID_LIST","version":"1.0"}}';
        }
        return $url;
    }

    public static function getNidDetailsFromMongo($nid, $dob)
    {

        return false;
    }





    /*     * ***************************** Users Model Class ends here ************************* */
}
