<?php


namespace App\Modules\MutationLand\Models;


use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class MutationLand extends Model
{
    protected $table = 'mutation_land_apps';
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