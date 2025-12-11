<?php

namespace App\Modules\NewReg\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rjsc_Nr_form_pdf extends Model {
    protected $table='rjsc_nr_form_pdf';
    protected $fillable=[
        'doc_id',
        'app_id',
        'file_name',
        'pdf_base64',
        'upload_pdf'
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
