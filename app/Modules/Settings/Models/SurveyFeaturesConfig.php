<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class SurveyFeaturesConfig extends Model
{
    protected $table = 'survey_features_config';
    protected $fillable = array(
        'id',
        'user_id',
        'showing_length',
        'is_skip',
        'is_archrive',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
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


