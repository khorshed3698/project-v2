<?php

namespace App\Modules\NewReg\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrSubmitForms extends Model
{
       protected $table = 'rjsc_nr_submit_forms';

    protected $fillable = [
                'id',
                'ref_id',
                'app_id',
                'form_name',
                'file',
               'doc',
                'status',
               'is_deleted',
               'created_at',
                'created_by',
                'updated_at',
              'updated_by'
            ];

    public static function boot()
    {
                parent::boot();
               static::creating(function ($post) {
                        $post->created_by = CommonFunction::getUserId();
                        $post->updated_by = CommonFunction::getUserId();
                    });

                static::updating(function ($post) {
                       $post->updated_by = CommonFunction::getUserId();
                    });
           }
}