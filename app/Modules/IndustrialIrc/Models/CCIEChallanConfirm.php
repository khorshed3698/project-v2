<?php

namespace App\Modules\IndustrialIrc\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CCIEChallanConfirm extends Model {
    protected $table = 'ccie_challan_confirm';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'tracking_no',
        'request_ccie',
        'response_ccie',
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
