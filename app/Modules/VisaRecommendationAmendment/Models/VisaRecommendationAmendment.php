<?php

namespace App\Modules\VisaRecommendationAmendment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class VisaRecommendationAmendment extends Model {
    protected $table = 'vra_apps';

    protected $fillable = array(
        'id',
        'certificate_link',
        'is_approval_online',
        'ref_app_tracking_no',
        'manually_approved_vr_no',

        'app_type_id',
        'app_type_mapping_id',
        'emp_name',
        'emp_designation',
        'emp_passport_no',
        'emp_nationality_id',
        'mission_country_id',
        'high_commision_id',
        'airport_id',
        'visa_purpose_id',
        'visa_purpose_others',
        'arrival_date',
        'arrival_time',
        'arrival_flight_no',
        'departure_date',
        'departure_time',
        'departure_flight_no',

        'n_visa_type_id',
        'n_emp_name',
        'n_emp_designation',
        'n_emp_nationality_id',
        'n_emp_passport_no',
        'n_mission_country_id',
        'n_high_commision_id',
        'n_airport_id',
        'n_visa_purpose_id',
        'n_visa_purpose_others',
        'n_arrival_date',
        'n_arrival_time',
        'n_arrival_flight_no',
        'n_departure_date',
        'n_departure_time',
        'n_departure_flight_no',
        'data',

        'auth_name',
        'auth_email',
        'auth_cell_number',
        'shadow_file_path',
        'approved_date',
        'is_archive',
        'accept_terms',
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
