<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class NcRjscPayment extends Model {
    protected $table = 'nc_rjsc_payment';
    protected $fillable = [
        'status',
        'tracking_no',

        'request',
        'response',
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
