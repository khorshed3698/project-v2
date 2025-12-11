<?php
namespace App\Modules\Settings\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RegulatoryAgency extends Model {

    protected $table = 'regulatory_agencies';
    protected $fillable = array(
        'id',
        'name',
        'contact_name',
        'designation',
        'mobile',
        'phone',
        'email',
        'description',
        'url',
        'agency_type',
        'order',
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

    /*     * ******************End of Model Class***************** */
}
