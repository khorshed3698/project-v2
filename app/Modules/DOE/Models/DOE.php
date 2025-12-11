<?php

namespace App\Modules\DOE\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DOE extends Model {

    protected $table = 'doe_master';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'id',
        'sf_payment_id',
        'certificate_type',
        'industry_id',
        'category_id',
        'application_type',
        'entrepreneur_name',
        'entrepreneur_designation',
        'category_name',
        'category_color_code',
        'certificate_type_label',
        'phone',
        'email',
        'mobile',
        'investment',
        'land',
        'land_unit',
        'manpower',
        'fee_category_id',
        'fee_id',
        'fee_type',
        'total_fee',
        'present_address',
        'start_construction',
        'completion_construction',
        'trial_production',
        'start_operation',
        'name_production',
        'estart_operation',
        'etrial_production',
        'project_name',
        'product_name',
        'district_id',
        'thana_id',
        'branch_id',
        'location',
        'estart_operation',
        'etrial_production',
        'name_production_quantity',
        'name_production_quantity_unit',
        'name_production_quantity_duration',
        'raw_materils_quantity',
        'raw_materils_quantity_unit',
        'raw_materils_quantity_duration',
        'source_raw_material',
        'quantity_water',
        'quantity_water_unit',
        'source_water',
        'name_of_fuel',
        'fuel_quantity',
        'fuel_quantity_unit',
        'fuel_quantity_duration',
        'source_fuel',
        'liquid_waste',
        'waste_discharge',
        'emission',
        'mode_emission',
        'bank_name',
        'branch_name',
        'code',
        'file_fund',
        'file_area',
        'trade_license',
        'bank_challen_no',
        'renew_old_file',
        'noc_file',
        'file_mouza_map',
        'land_ownership',
        'process_flow',
        'file_approval_doc',
        'location_map',
        'file_etp',
        'file_layout_plan',
        'file_iee',
        'file_emp',
        'feasibility_report',
        'file_city_corporation',
        'file_metropoliton',
        'file_fire_service',
        'file_owasa',
        'file_bidut',
        'file_titas_gas',
        'file_civil_aviation',
        'base_api_response',
        'base2_api_response',
        'doe_file',
        'doe_file2',
        'is_submit',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    );

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            if (Auth::guest()) {
                $post->created_by = 0;
                $post->updated_by = 0;
            } else {
                $post->created_by = Auth::user()->id;
                $post->updated_by = Auth::user()->id;
            }
        });

        static::updating(function($post) {
            if (Auth::guest()) {
                $post->updated_by = 0;
            } else {
                $post->updated_by = Auth::user()->id;
            }
        });
    }

}
