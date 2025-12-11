<?php

namespace App\Modules\NewReg\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ListSubscriber extends Model {
    protected $table='nr_subscribers_individual_info';
    protected $fillable=[
        'app_id',
        'serial_number',
        'corporation_body_name',
        'representative_name',
        'former_individual_name',
        'father_name',
        'mother_name',
        'usual_residential_address',
        'usual_residential_district_id',
        'permanent_address',
        'permanent_address_district_id',
        'mobile',
        'is_director',
        'email',
        'present_nationality_id',
        'original_nationality_id',
        'dob',
        'tin_no',
        'is_tin',
        'position',
        'signing_qualification_share_agreement',
        'nominating_entity_id',
        'appointment_date',
        'other_occupation',
        'directorship_in_other_company',
        'no_of_subscribed_shares',
        'digital_signature',
        'subscriber_photo',
        'national_id_passport_no'
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
