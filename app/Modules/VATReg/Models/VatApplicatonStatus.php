<?php

namespace App\Modules\VATReg\Models;

use Illuminate\Database\Eloquent\Model;

class VatApplicatonStatus extends Model {

    protected $table = 'vat_application_status';
    protected $fillable = [
        'ref_id'
    ];

}
