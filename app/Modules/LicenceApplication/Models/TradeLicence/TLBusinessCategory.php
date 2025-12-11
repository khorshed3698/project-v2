<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\CommonFunction;

class TLBusinessCategory extends Model
{
    protected $table = 'tl_business_category';

    protected $fillable = [
        'dcc_cat_id',
        'name_bn',
        'name_en',
        'status',
        'dcc_response_code',
    ];

}