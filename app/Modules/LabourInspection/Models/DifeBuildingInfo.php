<?php

namespace App\Modules\LabourInspection\Models;

use Illuminate\Database\Eloquent\Model;

class DifeBuildingInfo extends Model
{

    protected $table = 'dife_building_info';
    protected $fillable = [
        'id',
        'app_id',
        'factory_building_structure_type',
        'factories_in_building',
        'factory_building_area',
        'factory_floor_area',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
