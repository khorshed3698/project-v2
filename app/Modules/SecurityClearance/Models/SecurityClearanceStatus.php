<?php

namespace App\Modules\SecurityClearance\Models;

use Illuminate\Database\Eloquent\Model;

class SecurityClearanceStatus extends Model
{
    protected $table = 'security_clearance_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
}
