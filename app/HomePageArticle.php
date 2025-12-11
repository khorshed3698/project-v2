<?php

namespace App;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class HomePageArticle extends Model
{
    protected $table = 'home_page_articles';
    protected $fillable = array(
        'id',
        'page_name',
        'page_content',
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