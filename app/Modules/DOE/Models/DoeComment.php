<?php

namespace App\Modules\DOE\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class DoeComment extends Model {
    protected $table = 'doe_comment';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'ref_id',
        'tracking_no',
        'sender_type',
        'COMMENT',
        'comment_date_time',
        'attachment',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

}
