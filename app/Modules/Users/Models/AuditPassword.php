<?php

namespace App\Modules\Users\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AuditPassword extends Model {

    protected $table = 'audit_password';
    protected $fillable = array(
        'user_id',
        'password',
        'created_at',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );

    /************************ Users Model Class ends here ****************************/
}
