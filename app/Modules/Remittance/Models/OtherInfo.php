<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class OtherInfo extends Model
{
    protected $table = 'ra_other_info';

    protected $fillable = array(
        'id',
        'app_id',
        'remittance_type_id',
        'remittance_bdt',
        'remittance_usd',
        'remittance_percentage',
        'attachment',

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