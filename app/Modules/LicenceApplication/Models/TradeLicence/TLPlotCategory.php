<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TLPlotCategory extends Model
{

    protected $table = 'tl_plot_category';


    protected $primaryKey = 'id';


    protected $fillable = [
        'id',
        'name',
        'status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
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
