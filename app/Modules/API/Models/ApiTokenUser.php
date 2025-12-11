<?php
/**
 * Created by PhpStorm.
 * User: mehedi
 * Date: 1/8/19
 * Time: 1:14 PM
 */

namespace App\Modules\API\Models;
use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ApiTokenUser extends Model
{
    protected $table = 'api_token_user';
    protected $fillable = ['id', 'user', 'password','ref_id'];

}