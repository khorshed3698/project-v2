<?php namespace App\Modules\Apps\Models;

use Illuminate\Database\Eloquent\Model;
class EmailQueue extends Model {

    protected $table = 'email_queue';
    protected $fillable = array(
        'id',
        'app_id',
        'process_type_id',
        'status_id',
        'caption',
        'email_content',
        'email_from',
        'email_to',
        'email_cc',
        'email_status',
        'email_subject',
        'attachment',
        'attachment_certificate_name',
        'sms_content',
        'sms_to',
        'sms_status',
        'response',
        'sent_on',
        'cron_id',
        'no_of_try',
        'web_notification',
        'others_info',
        'email_response',
        'email_response_id',
        'sms_response',
        'sms_response_id',
        'secret_key',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    );


}
