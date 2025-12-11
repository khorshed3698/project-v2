<?php

namespace App\Modules\SonaliPaymentStackHolder\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApiStackholderMapping extends Model
{
    protected $table = 'api_stackholder_mapping';
    protected $fillable = [
        'stackholder_id',
        'receiver_account_no',
        'amount',
        'category',
        'process_type_id',
        'is_active',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
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
