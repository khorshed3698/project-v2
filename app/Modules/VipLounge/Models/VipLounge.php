<?php

namespace App\Modules\VipLounge\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class VipLounge extends Model {
    protected $table = 'vipl_apps';
    protected $fillable = [
        'id',
        'certificate_link',
        'vip_longue_purpose_id',
        'agree_with_instruction',
        'ref_no_type',
        'reference_number',
        'airport_id',
        'visa_purpose',
        'investor_photo',
        'emp_name',
        'emp_designation',
        'brief_job_description',
        'emp_passport_no',
        'emp_personal_no',
        'emp_surname',
        'emp_given_name',
        'pass_issue_date',
        'place_of_issue',
        'pass_expiry_date',
        'emp_date_of_birth',
        'emp_place_of_birth',
        'emp_nationality_id',
        'office_division_id',
        'office_district_id',
        'office_thana_id',
        'office_post_office',
        'office_post_code',
        'office_address',
        'office_telephone_no',
        'office_mobile_no',
        'office_fax_no',
        'office_email',
        'nature_of_business',
        'received_remittance',
        'auth_capital',
        'paid_capital',
        'arrival_date',
        'arrival_time',
        'arrival_flight_no',
        'departure_date',
        'departure_time',
        'departure_flight_no',
        'salary_remarks',
        'auth_name',
        'auth_email',
        'auth_cell_number',
        'shadow_file_path',
        'is_archive',
        'sf_payment_id',
        'gf_payment_id',
        'accept_terms',
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
}
