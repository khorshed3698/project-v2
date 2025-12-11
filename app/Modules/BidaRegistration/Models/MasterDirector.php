<?php 

namespace App\Modules\BidaRegistration\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class MasterDirector extends Model
{
    protected $table = 'master_of_directors';

    protected $guarded = ['id'];

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by    = CommonFunction::getUserId();
            $post->updated_by    = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}