<?php

namespace App\Console\Commands;

use App\Modules\Settings\Models\PdfPrintRequestQueue;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use Illuminate\Support\Facades\File;

class QueuePdfJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pdf:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $data = array();

            $pdfQueue = PdfPrintRequestQueue::leftJoin('pdf_service_info as psi', 'pdf_print_requests_queue.certificate_name', '=', 'psi.certificate_name')
                ->where('pdf_print_requests_queue.prepared_json', 1)->limit(10)
                ->get([
                    'pdf_print_requests_queue.id',
                    'pdf_print_requests_queue.process_type_id',
                    'pdf_print_requests_queue.app_id',
                    'pdf_print_requests_queue.certificate_name',
                    'pdf_print_requests_queue.prepared_json',
                    'psi.sql',
                    'psi.pdf_type',
                    'psi.reg_key',
                    'psi.pdf_server_url'
                ])->toArray();

            if ($pdfQueue) {
                $requestCounter = 0;

                foreach ($pdfQueue as $rowForPdfQueue) {
                    $requestCounter += 1;
                    $pdf_req_info_id = $rowForPdfQueue['id'];
                    $pdf_req_app_id = $rowForPdfQueue['app_id'];
                    $pdf_req_process_type_id = $rowForPdfQueue['process_type_id'];
                    $final_encoded_data = '';
                    $requested_sql = str_replace("{app_id}", "$pdf_req_app_id", $rowForPdfQueue['sql']);

                    $queryForReqSQL = DB::select(DB::raw("$requested_sql"));

                    if ($queryForReqSQL) {
                        foreach ($queryForReqSQL as $rowForReqSQL) {
                            $data['data']['json'] = $rowForReqSQL;
                        }

                        $data['data']['reg_key'] = $rowForPdfQueue['reg_key'];
                        $data['data']['pdf_type'] = $rowForPdfQueue['pdf_type'];
                        $data['data']['ref_id'] = $pdf_req_app_id;

                        $project_root_ip = config('app.project_root_ip');
                        $qrcode_query = DB::select(DB::raw("SELECT id, signature_type FROM pdf_signature_qrcode WHERE process_type_id='$pdf_req_process_type_id' AND app_id='$pdf_req_app_id'"));
                        foreach ($qrcode_query as $rowForQcCode) {
                            $qr_id = $rowForQcCode['id'];
                            $qr_signature_type = $rowForQcCode['signature_type'];
                            if ($qr_signature_type == 'final') {
                                // $data['data']['json']['a_urlimg'] = $project_root_ip . "/cron/signature_api/rest/signature?signature_id=$qr_id";
                                $data['data']['json']['a_urlimg'] = $project_root_ip . "/cron/signature_api/rest/api.php?function=signature?signature_id=$qr_id"; // live
                            } elseif ($qr_signature_type == 'first') {
                                // $data['data']['json']['b_urlimg'] = $project_root_ip . "/cron/signature_api/rest/signature?signature_id=$qr_id";
                                $data['data']['json']['b_urlimg'] = $project_root_ip . "/cron/signature_api/rest/api.php?function=signature?signature_id=$qr_id"; // live
                            }
                        }


                        $data['data']['param']['app_id'] = $pdf_req_app_id;
                        $jsonAllData = json_encode($data, JSON_UNESCAPED_UNICODE);

                        $prepared_json = 1;
                        if ($jsonAllData == false) {
                            $prepared_json = "-1"; // SQL or DATA ERROR
                        }
                        $encoded_data1 = str_replace(array("\\r\\n","\\n","\\r"), " ", $jsonAllData); // \r\n Carriage Return and Line Feed (Windows), \n Line Feed (Linux, MAC OSX), \r Carriage Return (MAC pre-OSX)
                        $encoded_data2 = str_replace("\\", "", $encoded_data1);
                        $encoded_data3 = str_replace("\"[", "[", $encoded_data2);
                        $encoded_data4 = str_replace("]\"", "]", $encoded_data3);
                        $encoded_data5 = str_replace("'", "’", $encoded_data4); // Replace single quotes (') by Right Single Quotation Mark Unicode Character (’) (U+2019) from json data
                        $encoded_data6 = preg_replace('/([^{,:])"(?![},:])/', "$1".'\''."$2",$encoded_data5); // Replace double quotes (") by single quotes character (') from json data
                        $encoded_data7 = str_replace("'", "~~", $encoded_data6); // Replace single quotes (') by character (~~) from json data

                        // Single quotation has been replaced but, Double quotation (") can not be possible to replace due to outer json quotation (")
                        $final_encoded_data = $encoded_data7;
                    } else {
                        echo "something wrong your sql query!";
                        $prepared_json = "-9";
                    }

                    DB::beginTransaction();

                    PdfPrintRequestQueue::where('id', $pdf_req_info_id)
                        ->update([
                            'prepared_json'=>$prepared_json,
                            'job_sending_status'=> 0,
                            'no_of_try_job_sending'=> 0,
                            'job_receiving_status'=> 0,
                            'no_of_try_job_receving'=> 0,
                            'certificate_link'=> '',
                            'url_requests'=> $final_encoded_data
                        ]);

                    DB::commit();

                    print_r($final_encoded_data);

                }
                if ($requestCounter == 0) {
                    echo "Not found any row";
                }
                $pdfQueue->close();
            } else {
                echo "Not found any row";
            }

        } catch (Exception $e) {
            DB::rollback();
            echo 'Something went wrong !!!';
            echo "\nMessage : " . $e->getMessage() . "\n";
            echo "\nLine : " . $e->getLine() . "\n";
            echo "\nFile : " . $e->getFile() . "\n";
            exit;
        }
    }
}
