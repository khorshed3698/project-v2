<?php

namespace App;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{

    protected $table = 'audit_log';
    protected $fillable = array(
        'id',
        'remote_ip',
        'module_or_log_key',
        'details',
        'user_type',
        'user_sub_type',
        'is_helpful',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by    = CommonFunction::getUserId();
            $post->updated_by    = CommonFunction::getUserId();
            $post->user_type     = CommonFunction::getUserTypeWithZero();
            //$post->company_ids = CommonFunction::getUserCompanyWithZero();
        });


        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}
