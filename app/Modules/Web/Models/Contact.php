<?php

namespace App\Modules\Web\Models;

use App\Modules\Users\Models\Users;
use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model 
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'contacts';
    protected $guarded = ['id'];

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

    public function user()
    {
        return $this->belongsTo(Users::class, 'updated_by', 'id')->withDefault();
    }

}