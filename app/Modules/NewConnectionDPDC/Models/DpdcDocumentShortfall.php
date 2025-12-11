<?php

namespace App\Modules\NewConnectionDPDC\Models;

use Illuminate\Database\Eloquent\Model;

class DpdcDocumentShortfall extends Model
{
    protected $table = 'dpdc_document_shortfall';
    protected $primaryKey = 'id';
    protected $fillable = array(
        'ref_id',
    );


}
