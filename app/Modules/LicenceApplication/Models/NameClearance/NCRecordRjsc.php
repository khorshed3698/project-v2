<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class NCRecordRjsc extends Model {
    protected $table = 'nc_record_rjsc';
    protected $fillable = [
        'status',
        'request',
        'response',
        'payment_info',
        'application_id',
        'process_type_id',
        'tracking_no',

        'application_id'
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
