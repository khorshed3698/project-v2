<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscSubmissionVerify extends Model {
    protected $table = 'nc_rjsc_verify';
    protected $fillable = [
        'id',
        'status',
        'submission_no',
        'clearence_letter_no',
        'response_office_id',
        'response_company_name',
        'response',
    ];

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
