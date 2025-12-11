<?php

namespace App\Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\Auth;

class DashBoardSlider extends Model
{
    protected $table = 'dashboard_slider';
    protected $fillable = array(
        'id',
        'title',
        'description',
        'image',
        'is_active',
        'created_at',
        'is_archive',
        'order',
        'created_by',
        'updated_by',
        'is_active',
        'url',
    );
    public static function boot()
    {
        parent::boot();
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

    /*     * ******************End of Model Class***************** */
}
