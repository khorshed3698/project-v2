<?php

namespace App\Modules\CompanyRegSingleForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CompanyRegSingleForm extends Model {
    protected $table='rjsc_cr_sf_apps';
    protected $fillable=[
        'liability_type_id',
        'address_entity',
        'entity_email_address',
        'main_business_objective',
        'business_sector_id',
        'business_sector_id',
        'business_sub_sector_id',
        'authorize_capital',
        'sequence',
        'number_shares',
        'is_additional_attachment',
        'value_of_each_share',
        'minimum_no_of_directors',
        'maximum_no_of_directors',
        'quorum_agm_egm_num',
        'q_directors_meeting_num',
        'duration_of_chairmanship',
        'duration_managing_directorship',
        'gf_payment_id',
        'no_of_qualification_share',
        'value_of_qualification_share',
        'agreement_witness_name',
        'agreement_witness_address',
        'agreement_witness_district_id'
    ];


    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }

}
