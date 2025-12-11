<?php 

namespace App\Modules\LicenceApplication\Models\NameClearance;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CompanyPosition extends Model {
    protected $table = 'rjsc_company_positions';
    protected $fillable = [
        'rjsc_company_type_id',
        'rjsc_company_type_rjsc_id',
        'rjsc_id',
        'title',
        'is_director',
        'rjsc_status',
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
