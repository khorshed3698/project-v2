<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class StatementOfRemittance extends Model
{
    protected $table = 'ra_statement_of_remittance';

    protected $fillable = array(
        'id',
        'app_id',
        'remittance_type_id',
        'remittance_year',
        'bida_ref_no',
        'date',
        'approval_copy',
        'amount',
        'percentage'
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