<?php

namespace App\Modules\CdaForm\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class CdaForm extends Model {

    protected $table = 'cda_apps';
    protected $fillable = [
        'id',
        'sf_payment_id',
        'luc_id',
        'land_use_category_id',
        'land_type_name',
        'land_use_sub_cat_id',
        'land_sub_type_name',
        'applicant_name',
        'applicant_father_name',
        'applicant_tin_no',
        'applicant_nid_no',
        'applicant_mobile_no',
        'applicant_email',
        'applicant_present_address',
        'suggested_use_land_plot',
        'city_corporation_id',
        'city_corporation_name',
        'bs',
        'rs',
        'mouza_id',
        'mouza_name',
        'thana_id',
        'thana_name',
        'block_id',
        'block_no',
        'seat_id',
        'seat_no',
        'ward_id',
        'ward_no',
        'sector_id',
        'sector_no',
        'road_name',
        'arm_size_land_plot_amount',
        'existing_house_plot_land_details',
        'plot_ownership_type',
        'plot_ownership_source',
        'plot_source_date',
        'registration_date',
        'pre_land_use',
        'pre_land_use_radius_250m',
        'plot_nearest_road_name',
        'nearest_road_amplitude',
        'plot_connecting_road_name',
        'connecting_road_amplitude',
        '250m_main_road',
        '250m_hat_bazaar',
        '250m_railway_station',
        '250m_river_port',
        '250m_airport',
        '250m_pond',
        '250m_wetland',
        '250m_natural_waterway',
        '250m_flood_control_stream',
        '250m_forest',
        '250m_park_playground',
        '250m_hill',
        '250m_slope',
        '250m_historical_imp_site',
        '250m_military_installation',
        '250m_key_point_installation',
        '250m_limited_dev_area',
        '25m_special_area',
        'plot_condition_by_adjacent_road',
        'land_use_north',
        'land_use_south',
        'land_use_east',
        'land_use_west',
        'accept_terms',
        'other_necessary_info',
        'created_at',
        'updated_at'
    ];

    public static function boot() {
        parent::boot();
        // Before update
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }

}
