<?php

namespace App\Console;

use App\Console\Commands\ApplicationDeadlineChecking;
//use App\Console\Commands\AppPreviewPDFStore;
//use App\Console\Commands\EmailsSend;
//use App\Console\Commands\IncompletePaymentProcess;
//use App\Console\Commands\SMSSend;
//use App\Console\Commands\EtinCertificate;
//use App\Console\Commands\ShadowFile;
//use App\Console\Commands\LogDataTransfer;
//use App\Console\Commands\SecurityClearanceJsonPrepare;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //ShadowFile::class,
        //EtinCertificate::class,
        //IncompletePaymentProcess::class
        //EmailsSend::class,
        //SMSSend::class,
        ApplicationDeadlineChecking::class,
//        LogDataTransfer::class,
//        SecurityClearanceJsonPrepare::class,
//        AppPreviewPDFStore::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('shadow:demo')->everyMinute();
        //$schedule->command('etin')->everyMinute();
        //$schedule->command('ipn:incomplete-payment-process')->everyTenMinutes();
        //$schedule->command('emails:send')->everyMinute();
        //$schedule->command('sms:send')->everyMinute()->withoutOverlapping();

        // At every 5th minute past every hour from 0 through 4.
        $schedule->command('app:deadline')->cron('*/5 0-4 * * *')->withoutOverlapping();
//        $schedule->command('app:preview-pdf-store')->cron('*/5 0-4 * * *')->withoutOverlapping();
        /*
         * Automatically transfer applications log data to another server
         * Need delete permission for the below tables
         * */
        // At every 2nd minute past every hour from 0 through 8. Every day: (240x100=24000) data will be transferred
//        $schedule->command('log-data:transfer action_info 100 90')->cron('*/2 0-8 * * *')->withoutOverlapping();

        //At every 3rd minute past every hour from 0 through 8. Every day: (160x100=16000) data will be transferred
//        $schedule->command('log-data:transfer url_info 100 90')->cron('*/3 0-8 * * *')->withoutOverlapping();

        // At every 5th minute past every hour from 0 through 8. Every day: (96x50=4800) data will be transferred
//        $schedule->command('log-data:transfer email_queue 50 180')->cron('*/5 0-8 * * *')->withoutOverlapping();

        // At every 8th minute past every hour from 0 through 8. Every day: (60x50=3000) data will be transferred
//        $schedule->command('log-data:transfer user_logs 50 90')->cron('*/8 0-8 * * *')->withoutOverlapping();

        // At every 10th minute past every hour from 0 through 8. Every day: (48x50=2400) data will be transferred
//        $schedule->command('log-data:transfer pdf_print_requests_queue_history 50 180')->cron('*/10 0-8 * * *')->withoutOverlapping();

        // At every 12th minute past every hour from 0 through 8. Every day: (40x50=2000) data will be transferred
//        $schedule->command('log-data:transfer users_history 50 90')->cron('*/12 0-8 * * *')->withoutOverlapping();

        // At every 5th minute for security clearance json prepare
//        $schedule->command('security-clearance:json-prepare')->everyFiveMinutes();
    }
}
