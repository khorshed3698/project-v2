<?php


namespace App\Modules\NewConnectionWZPDCL\Models;

use Illuminate\Database\Eloquent\Model;

class RequestQueueWZPDCL extends Model
{
    protected $table = 'wzpdcl_api_request_queue';
    protected $guarded = [];
    protected $fillable = ['status'];

}
