<?php

namespace App\Modules\LicenceApplication\Models\CompanyRegistration;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CrSubscribersAgentList extends Model
{
    protected $table = 'cr_subscribers_agent_list';

    protected $fillable = [
        'app_id',
        'lsa_name',
        'lsa_position',
        'lsa_no_subs_share',
    ];

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
}