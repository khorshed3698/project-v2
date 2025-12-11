<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class CronJobAudit extends Model
{
    protected $table = 'cron_job_audit';
    protected $guarded=[];
}