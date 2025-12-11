<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RemittanceProjectStatus extends Model
{
    protected $table = 'ra_project_status';

    protected $fillable = array(
        'id',
        'name'
    );

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