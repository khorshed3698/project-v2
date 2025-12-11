<?php


namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RegulatoryAgencyDetails extends Model
{
    protected $table = 'regulatory_agencies_details';
    protected $fillable = array(
        'id',
        'regulatory_agencies_id',
        'service_name',
        'is_online',
        'method_of_recv_service',
        'who_get_service',
        'documents',
        'fees',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_by',
        'created_by',
    );

    public static function boot()
    {
        parent::boot();
        static::creating(function($post)
        {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post)
        {
            $post->updated_by = CommonFunction::getUserId();
        });

    }
}