<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class NameClearance extends Model {
    protected $table = 'nc_apps';
    protected $fillable = [
        'id',
        'company_id',
        'single_licence_ref_id',
        'company_name',
        'applicant_name',
        'sf_payment_id',
        'gf_payment_id',
        'organization_name_id',
        'rjsc_office',
        'district_id',
        'ref_no',
        'add_file_path',
        'incorporation_number',
        'designation',
        'mobile_number',
        'email',

        'cert_no',
        'cert_issue_date',
        'cert_applicant_name',
        'cert_registered_address',
        'cert_application_no',
        'cert_application_date',
        'cert_entity_name',
        'cert_valid_until',

        'address',
        'is_signature',
        'is_accept',
        'digital_signature',
        'shadow_file_path',
        'is_approved',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'approved_date',
        'account_number',
        'amount',
        'payment_date'
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
