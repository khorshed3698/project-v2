<?php
/**
 * Created by PhpStorm.
 * User: mehedi
 * Date: 3/16/19
 * Time: 12:46 PM
 */

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class TLRecordDCC extends Model {

    protected $table = 'tl_record_dcc';
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