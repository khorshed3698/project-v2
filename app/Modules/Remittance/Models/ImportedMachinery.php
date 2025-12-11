<?php

namespace App\Modules\Remittance\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class ImportedMachinery extends Model
{
    protected $table = 'ra_imported_machinery';

    protected $fillable = array(
        'id',
        'app_id',
        'import_year_from',
        'import_year_to',
        'cnf_value'
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