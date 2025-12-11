<?php

namespace App\Console\Commands;

use App\Libraries\NotificationWebService;
use App\Modules\Apps\Models\EmailQueue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class EmailsSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email send from queue';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $get_pending_email = EmailQueue::where('email_status', '=', 0)
            ->where('email_to', '!=', '')
            ->orderBy('id', 'desc')
            ->take(5)
            ->get([
                'id',
                'app_id',
                'caption',
                'email_to',
                'email_cc',
                'email_content',
                'email_no_of_try',
                'attachment',
                'email_subject',
                'attachment_certificate_name',
            ]);

        $count_total_record = count($get_pending_email);
        $processed_record_counting = 0;
        
        if ($count_total_record > 0) {

            $NotificationWebService = new NotificationWebService();

            foreach ($get_pending_email as $row) {

                $processed_record_counting++;
                $email_record_id = $row->id;
                $email_content = $row->email_content;
                $email_subject = $row->email_subject;
                $email_to = '';
                $email_to = str_replace("'", "", $row->email_to);
                $email_cc = str_replace("'", "", $row->email_cc);
                $email_no_of_try = $row->email_no_of_try;

                /**
                 * Check that is it the mail with approval certificate
                 * if is it then need to check the certificate is available or not
                 */
                if (!empty($row->attachment_certificate_name)) {
                    $app_id = $row->app_id;
                    $attachment_content_split = explode('.', $row->attachment_certificate_name);
                    if (!empty($attachment_content_split[0]) && !empty($attachment_content_split[1])) {
                        $certificate_link = DB::table($attachment_content_split[0])
                            ->where('id', '=', $app_id)
                            ->where($attachment_content_split[1], '!=', '')
                            ->value($attachment_content_split[1]);

                        if (empty($certificate_link)) {
                            echo "For this email - $email_to certificate not generated yet. please try again! \n";
                            continue;
                        }
                        $email_content = str_replace('{$attachment}', $certificate_link, $email_content);
                    }
                }

                $email_status = 0; // email has not been sent yet
                $email_response = null;
                $email_response_id = 0;
                $email_no_of_try = $email_no_of_try + 1;
                if ($email_no_of_try > 10) {
                    $email_status = -9; // data is invalid, abort sending
                }

                $email_sending_response = $NotificationWebService->sendEmail([
                    'header_text' => config('app.project_name'),
                    'recipient' => $email_to,
                    'subject' => $email_subject,
                    'bodyText' => '',
                    'bodyHtml' => $email_content,
                    'email_cc' => $email_cc
                ]);

                $email_response = $email_sending_response['msg'];
                if ($email_sending_response['status'] === 1) {
                    $email_status = 1;
                    $email_response_id = $email_sending_response['message_id'];
                    echo "Successfully sent Email to - $email_to \n";
                } else {
                    echo "Could not send Email to - $email_to \n";
                }

                EmailQueue::where('id', $email_record_id)->update([
                    'email_status' =>  $email_status,
                    'email_response_id' =>  $email_response_id,
                    'email_response' =>  $email_response,
                    'email_no_of_try' =>  $email_no_of_try,
                ]);
            }
        } else {
            echo "No email in queue to send!\n";
        }

        $time_index = 0;
        $count_total_record = 0;
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
