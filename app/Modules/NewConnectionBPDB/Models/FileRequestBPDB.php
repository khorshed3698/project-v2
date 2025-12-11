<?php

namespace App\Modules\NewConnectionBPDB\Models;

use Illuminate\Database\Eloquent\Model;

class FileRequestBPDB extends Model
{
    protected $table = 'bpdb_file_request';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
