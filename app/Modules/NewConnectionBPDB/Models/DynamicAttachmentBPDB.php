<?php

namespace App\Modules\NewConnectionBPDB\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicAttachmentBPDB extends Model {

    protected $table = 'dynamic_attachment_bpdb';
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
