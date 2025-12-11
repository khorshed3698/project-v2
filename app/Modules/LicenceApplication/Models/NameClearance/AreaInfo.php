<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class AreaInfo extends Model {

    protected $table = 'area_info';
    protected $fillable = array(
        'area_id',
        'area_nm',
        'area_type',
        'pare_id',
        'dist_type',
        'area_nm_ban',
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
