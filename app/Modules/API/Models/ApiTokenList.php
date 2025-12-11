<?php

namespace App\Modules\API\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApiTokenList extends Model
{
    protected $table = 'api_token_list';
    protected $fillable = ['id', 'token_user_id', 'token','valid_till','ref_data'];

}