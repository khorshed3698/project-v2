<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class BidaRegInfo extends Model
{
    protected $table = 'ra_bida_reg_info';

    protected $fillable = array(
        'id',
        'app_id',
        'registration_no',
        'registration_date',
        'proposed_investment',
        'actual_investment',
        'registration_copy',
        'amendment_copy'

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