<?php

namespace App\Modules\IndustrialIrc\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CcieApplicationStatus extends Model
{
    protected $table = 'ccie_application_status';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'completed',
    );


}
