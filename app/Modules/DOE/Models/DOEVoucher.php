<?php

namespace App\Modules\DOE\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DOEVoucher extends Model {

    protected $table = 'doe_voucher';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'voucher_amount',
        'voucher_path',
        'is_active',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
