<?php

namespace App\Modules\Reports\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ReportRequestList extends Model
{
    protected $table = 'report_request_list';

    protected $fillable = [
        'id',
        'report_id',
        'user_id',
        'pdf_url',
        'user_type',
        'is_archive',
        'search_keys',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];


    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }
}