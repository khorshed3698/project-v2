<?php

namespace App\Modules\DOE\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DoeAppChangeInfo extends Model {
    protected $table = 'doe_application_change_info';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'change_info_json',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

}
