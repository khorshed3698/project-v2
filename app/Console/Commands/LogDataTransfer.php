<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LogDataTransfer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     * @param array
     * @param int
     */
    protected $signature = 'log-data:transfer {table_name} {limit} {day}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically transfer applications log data to another server.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    /**
     * Default limit set
     */
    private $limit = 50;

    /**
     * Default 90 days (03 month)
     */
    private $day = 90;

    public function handle()
    {
        $table = $this->argument('table_name') ? $this->argument('table_name') : ''; // from 1st argument
        $limit = $this->argument('limit') ? $this->argument('limit') : $this->limit; // from 2nd argument
        $day = $this->argument('day') ? $this->argument('day') : $this->day; // from 3rd argument

        $date = Carbon::now()->subDays($day);

        $file_name = "log-data:transfer $table $limit $day";

        $processed_record_counting = 0;
        $table_data_store = false;
        $table_data_in_progress = 0;

        if (!empty($table)) {

            /**
             * 0 = Not transferred
             * 1 = In progress
             * 2 = Failed
             * 3 = Data transferred and need to delete from this DB
             * */
            $table_data = DB::table($table)->where('created_at', '<=', $date)
                ->where('dt_flag', 0)
                ->limit($limit)->orderBy('id', 'asc')->get();
            if (empty($table_data)) {
                $this->info("$table has no data \n");
                return false;
            }

            $table_data_array = [];
            $table_data_array = collect($table_data)->map(function ($data) {
                return (array) $data;
            })->toArray();

            $table_id_array = [];
            $table_id_array = collect($table_data)->map(function ($data) {
                return $data->id;
            })->toArray();

            $processed_record_counting = count($table_id_array);

            try {

                // make data transfer flag (dt_flag) in progress (1)
                $table_data_in_progress = DB::table($table)->whereIn('id', $table_id_array)->update(['dt_flag' => 1]);

                // Connect new database and insert table data
                $table_data_store = DB::connection('logData')->table($table)->insert($table_data_array);
                //$this->info("$table data stored " . date("j F, Y, g:i a")."\n");

                if ($table_data_store) {
                    DB::table($table)->whereIn('id', $table_id_array)->delete();
                    //$this->info("$table data deleted " . date("j F, Y, g:i a")."\n");
                }

                $this->info("$table data successfully transferred " . date("j F, Y, g:i a"));

                $record_index = array_slice($table_data_array, ($limit-1))[0]['id'];
                $this->storeCronJobAuditInfo($record_index, $processed_record_counting, $file_name);

            } catch (\Exception $e) {

                if ($table_data_in_progress > 0 && $table_data_store == true) {
                    DB::table($table)->whereIn('id', $table_id_array)->update(['dt_flag' => 3]); // Data transferred and need to delete from this DB
                } else {
                    DB::table($table)->whereIn('id', $table_id_array)->update(['dt_flag' => 2]); // Failed
                }

                $this->error( $table . "\t" . $e->getLine()."@@".$e->getMessage());
            }

        } else {
            $this->info("There are no tables for processing ". date("j F, Y, g:i a"));
        }
    }

    private function storeCronJobAuditInfo($record_index, $rowcount, $file_name)
    {
        $path = dirname(__FILE__);
        $link = $_SERVER['PHP_SELF'];
        $link_array = explode('/', $link);
        $page = end($link_array);
        $full_address = rawurlencode($path . '\\' . $page);
        $no_of_record = $rowcount;
        $comments = $this->description;

        DB::insert("INSERT INTO cron_job_audit (file_name, full_address, record_index, no_of_record, comments, cron_run_time)
        VALUES ('" . $file_name . "', '" . $full_address . "', '" . $record_index . "', '" . $no_of_record . "', '" . $comments . "', NOW()) 
        ON DUPLICATE KEY UPDATE    
        file_name='" . $file_name . "', full_address='" . $full_address . "', record_index='" . $record_index . "', 
        no_of_record='" . $no_of_record . "', comments='" . $comments . "', cron_run_time=NOW()");
    }
}