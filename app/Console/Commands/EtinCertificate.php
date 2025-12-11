<?php

namespace App\Console\Commands;

use App\Modules\LicenceApplication\Models\Etin\EtinRequest;
use Exception;
use Illuminate\Console\Command;

class EtinCertificate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etin';

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
            $authorization= $this->auth();
            dd($authorization);
            $log= EtinRequest::where('is_execute', 0)
                ->get();

            $curl = curl_init();

            foreach ($log as $log){
                curl_setopt_array($curl, array(
                    CURLOPT_PORT => "80",
                    CURLOPT_URL => config('app.ETIN_SERVER')."/api/TIN",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $log->details,
                    CURLOPT_HTTPHEADER => array(
                        //"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1bmlxdWVfbmFtZSI6ImFkbWluQGJpZGEuY29tIiwibmJmIjoxNTQ3OTU4OTc1LCJleHAiOjE1NDc5NjI1NzUsImlhdCI6MTU0Nzk1ODk3NSwiaXNzIjoiaHR0cDovL2xvY2FsaG9zdDo0NDM2Ny8iLCJhdWQiOiJCSURBIn0.pLd6c9ZOhbYgfOFyWyg5GflFpJl1EUkjM6S31mSgcKQ",
                        '"Authorization: Bearer '.$authorization.'"',
                        "Content-Type: application/json",
                        "cache-control: no-cache"
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);

                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                    $logData= new EtinRequest();
                    $logData->response_from=0;
                    $logData->response=$response;
                    $logData->is_execute=1;
                }
            }
            curl_close($curl);
        } catch (Exception $e) {
            echo "Something went wrong";
        }
    }

    public function auth(){

        $cred= "{\n\"UserName\": \"admin@bida.com\",\n\"Password\": \"123\"\n}";

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "80",
            CURLOPT_URL => config('app.ETIN_SERVER')."/api/Auth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $cred,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
