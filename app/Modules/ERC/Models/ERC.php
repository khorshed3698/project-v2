<?php

namespace App\Modules\ERC\Models;

use Illuminate\Database\Eloquent\Model;

class ERC extends Model {

    Protected $table='erc_apps';
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
