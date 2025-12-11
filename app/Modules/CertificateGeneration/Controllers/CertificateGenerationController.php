<?php

namespace App\Modules\CertificateGeneration\Controllers;

use App\Modules\CertificateGeneration\Services\BRACertificateService;
use App\Modules\CertificateGeneration\Services\IRCRegularCertificateService;
use App\Modules\CertificateGeneration\Services\VIPLCertificateService;
use App\Modules\CertificateGeneration\Services\IPCertificateService;
use App\Modules\CertificateGeneration\Services\PONCertificateService;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Exception;

class CertificateGenerationController extends Controller
{
    public function generateCertificate()
    {
        $getCertificateData = PdfPrintRequestQueue::where("job_sending_status", 0)
            ->whereIn('process_type_id', [12, 17, 21, 16, 22]) // BRA, VIPL, IP, IRCR, PON
            ->where("no_of_try_job_sending", '<', 3)
            ->take(2)
            ->get();

        if ($getCertificateData->isEmpty()) {
            echo "No certificate in queue to send! " . date("j F, Y, g:i a");
            exit();
        }

        foreach ($getCertificateData as $certificateData) {
            try {
                switch ($certificateData->process_type_id) {
                    case 12: // BRA
                        BRACertificateService::pdfGenerate($certificateData);
                        break;
                    case 17: // VIPL
                        VIPLCertificateService::pdfGenerate($certificateData);
                        break;
                    case 21: // Import Permission
                        IPCertificateService::pdfGenerate($certificateData);
                        break;
                    case 16: // IRC Regular
                        IRCRegularCertificateService::pdfGenerate($certificateData);
                        break;
                    case 22: // Project Office
                        PONCertificateService::pdfGenerate($certificateData);
                        break;

                    default:
                        $certificateData->job_sending_status = 0;
                        $certificateData->no_of_try_job_sending = ($certificateData->no_of_try_job_sending + 1);
                        $certificateData->job_receiving_response = 'The process type was not found!';
                        $certificateData->save();
                        echo "Process type is not found! <br/>";
                        break;
                }
            } catch (Exception $e) {
                $certificateData->job_sending_status = 0;
                $certificateData->no_of_try_job_sending = ($certificateData->no_of_try_job_sending + 1);
                $certificateData->job_receiving_response = $e->getFile() . $e->getMessage() . ', line: ' . $e->getLine();
                $certificateData->save();

                Log::error("Error occurred in CertificateGenerationController@generateCertificate ({$e->getFile()} : {$e->getLine()} : {$e->getMessage()})");
                echo $e->getFile().' '.$e->getMessage().' '.$e->getLine();
            }
        }

        echo "Certificate generate successfully.";
        exit();
    }
}
