<?php

namespace App\Modules\NewReg\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscMoaDefaultClause extends Model
{

    protected $table = 'rjsc_nr_default_clause_list';
    protected $fillable = [
        'default_clause_id',
        'name',
        'status',
    ];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}