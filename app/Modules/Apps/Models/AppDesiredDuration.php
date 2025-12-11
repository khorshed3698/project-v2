<?php

namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class AppDesiredDuration extends Model
{

    protected $table = 'applicaion_desired_duration';
    protected $fillable = [
        'id',
        'process_type_id',
        'duration',
        'email',
        'duration_type',
        'status',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
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
