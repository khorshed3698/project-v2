<?php

namespace App\Console\Commands;

use App\Libraries\CommonFunction;
use App\Libraries\UtilFunction;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ApplicationDeadlineChecking extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically transfer applications to cancel/delete status those resend deadline has expired';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $limit = 10;   //MySQL limit value (e.g select * from table_name limit 10)
        $todays_date = date('Y-m-d');

        $bida_services = config('bida_service.active');

        $resend_app_status_list = [5, 15, 32];

        $get_expired_apps = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->whereIn('process_list.process_type_id', $bida_services)
            ->whereIn('process_list.status_id', $resend_app_status_list)
            ->where('process_list.resend_deadline', '<', $todays_date)
            ->whereNotNull('process_list.resend_deadline')
            ->take($limit)
            ->get([
                'process_list.id',
                'process_list.ref_id',
                'process_list.status_id',
                'process_list.desk_id',
                'process_list.company_id',
                'process_list.process_type_id',
                'process_list.tracking_no',
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
            ]);

        $processed_record_counting = 0;

        if (count($get_expired_apps) > 0) {

            foreach ($get_expired_apps as $app) {

                /*
                 * 5 = Work Permit Cancellation
                 * 9 = Office Permission Cancellation
                 * */
                if (in_array($app->process_type_id, [5,9]) && $app->status_id == 15) {
                    continue;
                }

                $processed_record_counting++;

                //get users email and phone no according to working company id
                $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($app->company_id);

                $appInfo = [
                    'app_id' => $app->ref_id,
                    'status_id' => $app->status_id,
                    'process_type_id' => $app->process_type_id,
                    'tracking_no' => $app->tracking_no,
                    'process_type_name' => $app->process_type_name,
                    'process_supper_name' => $app->process_supper_name,
                    'process_sub_name' => $app->process_sub_name,
                    'remarks' => '',
                ];

                // 5 Shortfall application will be deleted
                // 15 Approve for payment will be cancelled
                // 32 Condition satisfied and payment will be cancelled
                if ($app->status_id == 5) {
                    $app->status_id = 4;
                    $app->process_desc = 'The application has been deleted as it was not resubmitted within the stipulated time';
                    CommonFunction::sendEmailSMS('APP_DELETED', $appInfo, $applicantEmailPhone);

                } elseif (in_array($app->status_id, [15, 32])) {
                    $app->status_id = 7;
                    $app->process_desc = 'The application has been cancelled as it was not resubmitted within the stipulated time';
                    CommonFunction::sendEmailSMS('APP_CANCELLED', $appInfo, $applicantEmailPhone);
                }

                $app->save();
            }
            echo "$processed_record_counting application has been transferred successfully!";
        } else {
            echo "There are no applications for processing";
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

        // DB::insert("INSERT INTO cron_job_audit (file_name, full_address, record_index, no_of_record, comments, cron_run_time)
        // VALUES ('" . $file_name . "', '" . $full_address . "', '" . $record_index . "', '" . $no_of_record . "', '" . $comments . "', NOW()) 
        // ON DUPLICATE KEY UPDATE    
        // file_name='" . $file_name . "', full_address='" . $full_address . "', record_index='" . $record_index . "', 
        // no_of_record='" . $no_of_record . "', comments='" . $comments . "', cron_run_time=NOW()");

        DB::insert("
            INSERT INTO cron_job_audit 
            (file_name, full_address, record_index, no_of_record, comments, cron_run_time)
            VALUES (?, ?, ?, ?, ?, NOW()) 
            ON DUPLICATE KEY UPDATE    
            file_name = VALUES(file_name), 
            full_address = VALUES(full_address), 
            record_index = VALUES(record_index), 
            no_of_record = VALUES(no_of_record), 
            comments = VALUES(comments), 
            cron_run_time = NOW()
        ", [$file_name, $full_address, $record_index, $no_of_record, $comments]);
    }
}
