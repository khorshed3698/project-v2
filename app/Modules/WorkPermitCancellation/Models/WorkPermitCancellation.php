<?php

namespace App\Modules\WorkPermitCancellation\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class WorkPermitCancellation extends Model {
    protected $table = 'wpc_apps';
    protected $fillable = array(
        'id',
        'last_work_permit',
        'ref_app_tracking_no',
        'ref_app_approve_date',
        'manually_approved_wp_no',
        'issue_date_of_last_wp',
        'approved_effect_date',
        'applicant_name',
        'applicant_position',
        'applicant_nationality',
        'applicant_pass_no',
        'date_of_cancellation',
        'applicant_remarks',
        'office_division_id',
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_code',
        'office_address',
        'office_telephone_no',
        'office_mobile_no',
        'office_email',
        'office_fax_no',
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
        'auth_name',
        'auth_email',
        'auth_cell_number',
        'approve_date',
        'application_date',
        'accept_terms',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
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
