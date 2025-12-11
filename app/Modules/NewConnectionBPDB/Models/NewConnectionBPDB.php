<?php

namespace App\Modules\NewConnectionBPDB\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class NewConnectionBPDB extends Model {

    protected $table = 'bpdb_apps';
    protected $fillable = [
        'id',
        'appdata'
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
