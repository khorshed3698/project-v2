<?php

namespace App\Modules\LicenceApplication\Models\Etin;

use Illuminate\Database\Eloquent\Model;

class EtinJurisdictionList extends Model
{
    protected $table = 'etin_jurisdiction_list';

    protected $fillable = [
        'etin_district_id',
        'reg_juri_type_no',
        'juri_select_type_no',
        'juri_select_type_value',
        'juri_select_list_no',
        'juri_select_list_value',
        'status',
    ];

}