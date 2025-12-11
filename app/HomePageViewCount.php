<?php

namespace App;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class HomePageViewCount extends Model
{
    protected $table = 'home_page_view_count';
    protected $fillable = array(
        'id',
        'module_or_log_key',
        'reference_id',
        'view_count',
        'yes_count',
        'no_count',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by    = CommonFunction::getUserId();
            $post->updated_by    = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
