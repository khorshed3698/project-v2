<?php

namespace App\Modules\SonaliPaymentStackHolder\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class StakeholderPaymentCategory extends Model
{
    protected $table = 'api_stackholder_sp_payment_category';
    protected $fillable = array(
        'id',
        'name',
        'status',
        'amount',
        'is_archive',
        'created_by',
        'updated_by'
    );

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

    /*     * ******************End of Model Class***************** */
}