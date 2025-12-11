<?php

namespace App\Modules\VATReg\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentVAT extends Model {

    protected $table = 'dynamic_attachment_vat';
    protected $fillable = [
        'id',
        'ref_id',
        'process_type_id',
        'doc_id',
        'doc_name',
        'doc_path',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

}
