<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ForcefullyDataUpdate extends Model
{
    protected $table = 'forcefully_data_update';

    protected $fillable = [
        'tracking_no',
        'table_name',
        'update_type',
        'user_id',
        'company_id',
        'row_id',
        'data',
        'status_id',
        'is_archive',
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