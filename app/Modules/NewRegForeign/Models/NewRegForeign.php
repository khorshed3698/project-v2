<?php

namespace App\Modules\NewRegForeign\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class NewRegForeign extends Model {
    protected $table='rjsc_nrf_apps';
    protected $fillable=[
        'address_entity',
        'main_business_objective',
        'business_sector_id',
        'business_sector_id',
        'business_sub_sector_id',
        'sequence',
        'is_additional_attachment',
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
