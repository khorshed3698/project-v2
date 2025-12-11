<?php 

namespace App\Modules\LicenceApplication\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class LaInvestingCountries extends Model {
    protected $table = 'la_investing_countries';
    protected $fillable = [
        'id',
        'app_id',
        'invt_country_id',
        'invt_country_amount',
        'invt_country_equity',
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
