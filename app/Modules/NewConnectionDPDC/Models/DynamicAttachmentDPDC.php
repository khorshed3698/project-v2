<?php

namespace App\Modules\NewConnectionDPDC\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentDPDC extends Model
{

    protected $table = 'dynamic_attachment_dpdc';
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
