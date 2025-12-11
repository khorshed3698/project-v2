<?php namespace App\Modules\Training\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TrEvaluation extends Model
{

    protected $table = 'tr_evaluations';

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

    // belongsTo relation with TrCourse model
    public function trCourse()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrSchedule', 'schedule_id');
    }

    // belongsTo relation with TrBatch model
    public function trBatch()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrBatch', 'batch_id');
    }

    // belongsTo relation with TrSession model
    public function trSession()
    {
        return $this->belongsTo('App\Modules\Training\Models\TrScheduleSession', 'session_id');
    }
}
