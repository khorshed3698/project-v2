<?php namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrSchedule extends Model
{

    protected $table = 'tr_schedules';

    protected $fillable = array(
        'id',
        'tr_course_id',
        'tr_batch_id',
        'duration',
        'amount',
        'status',
        'is_publish',
        'fees_type',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    );

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

    public function course()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrCourse', 'course_id');
    }

    public function scheduleSessions()
    {
        return $this->hasMany('App\Modules\Training\Models\TrScheduleSession', 'app_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrCategory', 'category_id');
    }

    public function batch()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrBatch', 'batch_id');
    }

    public function scheduleParticipants()
    {
        return $this->hasMany('App\Modules\Training\Models\TrParticipant', 'schedule_id', 'id');
    }

}
