<?php

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class NcSubmissionVerification extends Model {
    protected $table = 'nc_submission_verification';
    protected $fillable = [
        'ref_id',
        'process_type_id',
        'licence_application_id',
        'response',
        'processing_at',
        'tracking_no',
        'status'
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
