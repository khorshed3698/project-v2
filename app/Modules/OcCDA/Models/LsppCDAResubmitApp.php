<?php

namespace App\Modules\LsppCDA\Models;

use Illuminate\Database\Eloquent\Model;

class LsppCDAResubmitApp extends Model
{

    protected $table = 'lspp_cda_resubmit_application';
    protected $fillable = [
        'id',
        'ref_id',
        'incoming_type',
        'incoming_type_desc',
        'incoming_reason',
        'file_title_1',
        'file_title_1_desc',
        'file_link_1',
        'file_title_2',
        'file_title_2_desc',
        'file_link_2',
        'file_title_3',
        'file_title_3_desc',
        'file_link_3',
        'file_title_4',
        'file_title_4_desc',
        'file_link_4',
        'file_title_5',
        'file_title_5_desc',
        'file_link_5',
        'request',
        'response',
        'status',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

}
