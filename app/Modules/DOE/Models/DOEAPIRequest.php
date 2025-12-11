<?php

namespace App\Modules\DOE\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DOEAPIRequest extends Model {

    protected $table = 'doe_api_request';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'type',
        'request_json_form_1',
        'request_json_form_2',
        'status_form_1',
        'status_form_2',
        'final_status',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}
