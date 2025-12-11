<?php

namespace App\Modules\NewRegForeign\Models;

use App\Libraries\CommonFunction;
use Illuminate\Database\Eloquent\Model;

class RjscNrDocList extends Model
{

    protected $table = 'rjsc_nr_doc_list';
    protected $fillable = [
        'doc_id',
        'name',
        'status'

    ];

}