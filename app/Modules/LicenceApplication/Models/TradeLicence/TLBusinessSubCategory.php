<?php

namespace App\Modules\LicenceApplication\Models\TradeLicence;

use Illuminate\Database\Eloquent\Model;

class TLBusinessSubCategory extends Model
{
    protected $table = 'tl_business_sub_category';

    protected $fillable = [
        'tl_cat_id',
        'dcc_cat_id',
        'dcc_sub_cat_id',
        'name_bn',
        'name_en',
        'status',
        'dcc_response_code',
    ];


}