<?php

namespace App\Modules\NewConnectionBPDB\Models;

use Illuminate\Database\Eloquent\Model;

class BPDBApplicationStatus extends Model
{
    protected $table = 'bpdb_application_status';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
