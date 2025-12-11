<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
class Templates extends Model {

    protected $table = 'templates';
    protected $fillable = array(
        'id',
        'caption',
        'email_subject',
        'email_content',
        'email_status',
        'sms_content',
        'sms_status',
        'is_archive',
        'created_at',
        'created_by',
        'updated_by'
    );


}
