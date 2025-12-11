<?php namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrCourse extends Model
{

    protected $table = 'tr_courses';

    protected $guarded = ['id'];

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
     
    public function category()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrCategory', 'category_id');
    }

    public function schedule()
    {
        return $this->hasMany('App\Modules\Training\Models\TrSchedule', 'course_id');
    }
}
