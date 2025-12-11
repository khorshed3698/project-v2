<?php

namespace App\Modules\ERC\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ErcApplicationStatus extends Model
{
    protected $table = 'erc_application_status';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'completed',
    );


}
