<?php

namespace App\Modules\LabourInspection\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentDIFE extends Model
{

    protected $table = 'dife_dynamic_attachment';
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
