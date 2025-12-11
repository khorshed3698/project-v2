<?php

namespace App\Modules\IndustrialIrc\Models;

use Illuminate\Database\Eloquent\Model;

class IndustrialIrc extends Model {

    Protected $table='cci_apps';
    Protected $fillable = array(
        'id',
        'organization_name_bn',
        'organization_name_en',
        'organization_add_bn',
        'organization_add_en',
        'factory_add_bn',
        'factory_add_en',
        'organization_tin',
        'organization_email',
        'organization_mobile',
        'is_archive',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

}
