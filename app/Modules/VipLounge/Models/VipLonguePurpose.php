<?php

namespace App\Modules\VipLounge\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class VipLonguePurpose extends Model {

    protected $table = 'vip_longue_purpose';
    protected $fillable = [
        'id',
        'purpose',
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


    /*     * *****************************End of Model Class********************************** */
}
