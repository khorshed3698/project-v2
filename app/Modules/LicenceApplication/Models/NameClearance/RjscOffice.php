<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscOffice extends Model {
    protected $table = 'rjsc_office_list';
    protected $fillable = [
        'rjsc_id',
        'name',
        'address',
        'status'

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
