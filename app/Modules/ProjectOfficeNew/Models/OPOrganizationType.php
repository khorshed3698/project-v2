<?php

namespace App\Modules\ProjectOfficeNew\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class OPOrganizationType extends Model {

    protected $table = 'op_organization_type';
    protected $fillable = [
        'id',
        'name',
        'status',
        'is_archive',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
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
