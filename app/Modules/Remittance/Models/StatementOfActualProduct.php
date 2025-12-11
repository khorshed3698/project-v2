<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class StatementOfActualProduct extends Model
{
    protected $table = 'ra_statement_of_actual_product';

    protected $fillable = array(
        'id',
        'app_id',
        'year_of_remittance',
        'year_of_remittance',
        'item_of_production',
        'quantity',
        'sales_value'

    );

    public static function boot() {
        parent::boot();
        static::creating(function($post) {
            $post->created_by = CommonFunction::getUserId();
            $post->updated_by = CommonFunction::getUserId();
        });

        static::updating(function($post) {
            $post->updated_by = CommonFunction::getUserId();
        });
    }
}