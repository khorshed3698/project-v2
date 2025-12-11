<?php

namespace App\Modules\API\Models;

use Illuminate\Database\Eloquent\Model;

class MohaApiQueue extends Model {

    protected $table = 'moha_api_request_queue';
    protected $fillable = [
        'id',
        'type',
        'ref_id',
        'request_json',
        'response_json',
        'sub_req_time',
        'sub_res_time',
        'status',
        'ready_to_check',
        'status_check_request_json',
        'status_check_response',
        'status_check_req_time',
        'status_check_res_time',
        'moha_tracking_id',
        'certificate',
        'fl_certificate',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];


    
    // public static function boot() {
    //     parent::boot();
    //     static::creating(function($post) {
    //         $post->created_by = CommonFunction::getUserId();
    //         $post->updated_by = CommonFunction::getUserId();
    //     });

    //     static::updating(function($post) {
    //         $post->updated_by = CommonFunction::getUserId();
    //     });
    // }

}
