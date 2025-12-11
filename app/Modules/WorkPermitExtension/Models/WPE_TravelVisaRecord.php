<?php

namespace App\Modules\WorkPermitExtension\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class WPE_TravelVisaRecord extends Model {

    protected $table = 'wpe_travel_visa_record';
    protected $fillable = [
        'id',
        'app_id',
        'th_emp_duration_from',
        'th_emp_duration_to',
        'th_visa_type_id',
        'th_visa_type_others',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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


    /*     * *****************************End of Model Class********************************** */
}
