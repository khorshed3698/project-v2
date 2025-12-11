<?php

namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class FeatureLengthDays extends Model
{
    protected $table = 'features_length_days';
    protected $fillable = array(
        'id',
        'feature_length',
        'is_active',
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


