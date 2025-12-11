<?php

namespace App\Console\Commands;

use App\Libraries\NotificationWebService;
use App\Modules\Apps\Models\EmailQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SMSSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'SMS send from queue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $hour = 48;
        $limit = 15;   //MySQL limit value (e.g select * from table_name limit 10)

      $get_pending_sms = EmailQueue::where('sms_to', '!=', '')
          ->where('sms_no_of_try', '<', 3)
          ->whereRaw('(sms_status = 0 OR (sms_status=-1 AND ADDDATE(sent_on, INTERVAL 180 SECOND) < NOW()))')
          ->whereRaw("created_at >= DATE_SUB(NOW(), INTERVAL '$hour' HOUR)")
          ->orderBy('id', 'DESC')
          ->take($limit)->get([
              'id',
              'sms_content',
              'sms_to',
              'sms_status',
              'sent_on',
              'sms_no_of_try'
          ]);

        $processed_record_counting = 0;

      if (count($get_pending_sms) > 0) {
          $NotificationWebService = new NotificationWebService();

          foreach ($get_pending_sms as $row) {
              $processed_record_counting++;
              $id = $row->id;
              //$sms_body = $row->sms_content;
              $sms_body = preg_replace("/(\r\n)|\r|\n/", "", $row->sms_content);
              $mobile_number = str_replace("+88", "", $row->sms_to);
              $sms_no_of_try = $row->sms_no_of_try;
              $sms_status = $row->sms_status;
              $sent_on = $row->sent_on;

              $update_current_record = EmailQueue::where('id', $id)->where('sms_status', $sms_status)
                  ->whereRaw("(sent_on IS NULL OR sent_on='$sent_on')")
                  ->update([
                      'sms_status' => -1,
                      'sent_on' => date("Y-m-d H:i:s"),
                      'sms_no_of_try' => $sms_no_of_try + 1,
                      'cron_id' => ''
                  ]);

              if (!$update_current_record) {
                  echo "Something went wrong during record update before SMS sending to <b> $mobile_number </b><br/>";
                  continue;
              }

              $sms_sending_response = $NotificationWebService->sendSms($mobile_number, $sms_body);
              $sms_response = $sms_sending_response['msg'];

              if ($sms_sending_response['status'] === 1) {
                  EmailQueue::where('id', $id)->update([
                      'sms_status' => 1,
                      'sms_response_id' => $sms_sending_response['message_id'],
                      'sms_response' => $sms_response,
                      'sent_on' => date("Y-m-d H:i:s"),
                  ]);
                  echo "Successfully sent SMS to - <b> $mobile_number </b><br/>";
              } else {
                  EmailQueue::where('id', $id)->update([
                      'sms_status' => -1,
                      'sms_response' => $sms_response,
                  ]);
                  echo "Could not send SMS to - <b> $mobile_number </b><br/>";
              }
          }
      } else {
          echo "No sms in sms queue to send";
      }

        $time_index = 0;
        $comment = $this->description;
        $this->storeCronJobAuditInfo($time_index, $processed_record_counting, $comment);
    }

    private function storeCronJobAuditInfo($time_index, $rowcount, $comment)
    {
        $path = dirname(__FILE__);

        $link = $_SERVER['PHP_SELF'];
        $link_array = explode('/', $link);
        $page = end($link_array);

        // $file_name =  $page;
        $file_name =  $this->signature;
        $full_address = rawurlencode($path . '\\' . $page);
        $record_index = $time_index;
        $no_of_record = $rowcount;
        $comments = $comment;

        DB::insert("INSERT INTO cron_job_audit (file_name, full_address, record_index, no_of_record, comments, cron_run_time)
        VALUES ('" . $file_name . "', '" . $full_address . "', '" . $record_index . "', '" . $no_of_record . "', '" . $comments . "', NOW()) 
        ON DUPLICATE KEY UPDATE    
        file_name='" . $file_name . "', full_address='" . $full_address . "', record_index='" . $record_index . "', 
        no_of_record='" . $no_of_record . "', comments='" . $comments . "', cron_run_time=NOW()");
    }
}