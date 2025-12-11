<?php

namespace App\Modules\SonaliPayment\Models;
use Illuminate\Database\Eloquent\Model;

class IpnRequestHistory extends Model
{
    protected $table = 'sp_ipn_request_history';
    protected $guarded=[];
}