<?php


namespace App\Modules\FscdNocProposed\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class FscdNocProposedAppStatus extends Model
{
    Protected $table='fnoc_proposed_app_status';
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();
        // Before update
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}