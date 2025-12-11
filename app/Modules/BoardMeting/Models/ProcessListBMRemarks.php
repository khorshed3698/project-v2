<?php

namespace App\Modules\BoardMeting\Models;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Support\Facades\Auth;

class ProcessListBMRemarks extends Model {

    protected $table = 'process_list_bm_remarks';
    protected $fillable = array(
        'id',
        'bm_process_id',
        'user_id',
        'chairman',
        'remarks',
    );


    public static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function ($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
    /*     * ***************************** Users Model Class ends here ************************* */
}
