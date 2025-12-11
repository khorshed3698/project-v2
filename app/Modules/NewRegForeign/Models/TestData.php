<?php

namespace App\Modules\NewRegForeign\Models;

use Illuminate\Database\Eloquent\Model;

class TestData extends Model {
    protected $table = 'test_datas';
    protected $fillable = [
        'id',
        'name',
    ];
}
