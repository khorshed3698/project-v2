<?php

namespace App\Modules\CompanyRegSingleForm\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rjsc_nr_apps_certificate extends Model {
    protected $table='rjsc_cr_sf_apps_certificate';
    protected $fillable=[
        'ref_id',
        'certificate_name',
        'certificate_content'
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
