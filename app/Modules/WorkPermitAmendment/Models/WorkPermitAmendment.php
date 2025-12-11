<?php

namespace App\Modules\WorkPermitAmendment\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class WorkPermitAmendment extends Model {
    protected $table = 'wpa_apps';
    protected $fillable = array(
        'id',
        'certificate_link',
        'is_approval_online',
        'ref_app_tracking_no',
        'manually_approved_wp_no',
        'emp_name',
        'emp_designation',
        'emp_passport_no',
        'emp_nationality_id',
        'p_approved_duration_start_date',
        'p_approved_duration_end_date',
        'p_approved_desired_duration',
        'approved_duration_start_date',
        'approved_duration_end_date',
        'approved_desired_duration',
        'approved_duration_amount',
        'duration_amount',
        'basic_payment_type_id',
        'basic_local_amount',
        'basic_local_currency_id',
        'overseas_payment_type_id',
        'overseas_local_amount',
        'overseas_local_currency_id',
        'house_payment_type_id',
        'house_local_amount',
        'house_local_currency_id',
        'conveyance_payment_type_id',
        'conveyance_local_amount',
        'conveyance_local_currency_id',
        'medical_payment_type_id',
        'medical_local_amount',
        'medical_local_currency_id',
        'ent_payment_type_id',
        'ent_local_amount',
        'ent_local_currency_id',
        'bonus_payment_type_id',
        'bonus_local_amount',
        'bonus_local_currency_id',
        'other_benefits',
        'n_emp_name',
        'n_emp_designation',
        'n_emp_nationality_id',
        'n_emp_passport_no',
        'n_duration_start_date',
        'n_duration_end_date',
        'n_desired_duration',
        'n_desired_amount',
        'n_basic_payment_type_id',
        'n_basic_local_amount',
        'n_basic_local_currency_id',
        'n_overseas_payment_type_id',
        'n_overseas_local_amount',
        'n_overseas_local_currency_id',
        'n_house_payment_type_id',
        'n_house_local_amount',
        'n_house_local_currency_id',
        'n_conveyance_payment_type_id',
        'n_conveyance_local_amount',
        'n_conveyance_local_currency_id',
        'n_medical_payment_type_id',
        'n_medical_local_amount',
        'n_medical_local_currency_id',
        'n_ent_payment_type_id',
        'n_ent_local_amount',
        'n_ent_local_currency_id',
        'n_bonus_payment_type_id',
        'n_bonus_local_amount',
        'n_bonus_local_currency_id',
        'n_other_benefits',
        'data',
        'expatriate_name',
        'expatriate_passport',
        'expatriate_nationality',
        'auth_name',
        'auth_email',
        'auth_cell_number',
        'shadow_file_path',
        'approved_date',
        'accept_terms',
        'is_archive',
        'gf_payment_id',
        'sf_payment_id',
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
