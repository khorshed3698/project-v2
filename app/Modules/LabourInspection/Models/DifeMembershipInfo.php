<?php

namespace App\Modules\LabourInspection\Models;

use Illuminate\Database\Eloquent\Model;

class DifeMembershipInfo extends Model
{

    protected $table = 'dife_membership_info';
    protected $fillable = [
        'id',
        'app_id',
        'factory_organization_id',
        'factory_organization_reg_no',
        'factory_organization_reg_date',
        'factory_organization_renew_date',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
