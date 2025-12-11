<?php

namespace App\Modules\Training\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Training\Models\TrBatch;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\Training\Models\TrSchedule;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Milon\Barcode\DNS2D;
use Mpdf\Mpdf;

class TrCertificateController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 700;
        $this->aclName = 'Training';
    }

    // route checked
    public function participantCertificate($part_id, $course_id)
    {
        try {
            $course_id = Encryption::decodeId($course_id);
            $part_id = Encryption::decodeId($part_id);

            // Qr code generator
            $dn2d = new DNS2D();
            $qrcode = $dn2d->getBarcodePNG(URL::to('/') . '/docs/Training/' . '110', 'QRCODE');
            $qrCode_url = 'data:image/png;base64,' . $qrcode;

            $md_signature = public_path('assets/images/md1.png');

            $data['participants'] = TrParticipant::where('id', $part_id)->first();
            $data['course'] = TrSchedule::leftJoin('tr_courses', 'tr_schedules.course_id', '=', 'tr_courses.id')
                ->where('tr_schedules.id', $course_id)
                ->select('tr_courses.course_title', 'tr_schedules.batch_id', 'tr_schedules.course_duration_start', 'tr_schedules.course_duration_end', 'tr_schedules.created_by')
                ->first();

            $director_data = Users::where('id', $data['course']->created_by)->select('signature_encode', 'user_full_name', 'designation')->first();
            $director_signature = 'data:image/jpeg;base64,' . $director_data->signature_encode;
            $user_full_name = CommonFunction::getUserFullnameById($data['course']->created_by);
            $designation = $director_data->designation;

            $batch_name = TrBatch::where('schedule_id', $course_id)->value('batch_name');
            $certificate_no = generateCertificateNumber($batch_name, date('Y'));

            $contents = view('Training::certificate.create-certificate', [
                'participants' => $data['participants'],
                'course' => $data['course'],
                'certificate_no' => $certificate_no,
                'qrCode_url' => $qrCode_url,
                'md_signature' => $md_signature,
                'director_signature' => $director_signature,
                'user_full_name' => $user_full_name,
                'designation' => $designation,

            ])->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font_size' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
                'setAutoTopMargin' => 'pad',
                'setAutoBottomMargin' => 'pad',
                'fontDir' => [
                    public_path('assets/fonts'), // Add your font directory paths here
                ],
                'fontdata' => [
                    'timesnewroman' => [
                        'R' => 'OpenSans-Regular.ttf',
                    ],
                    'solaimanlipi' => [
                        'R' => 'OLDENGL.TTF',
                    ],
                ],
                'default_font' => 'timesnewroman',
            ]);

            // Check for production mode
            if (config('app.server_type') != 'live') {
                $mpdf->showWatermarkText = true;
                $mpdf->SetWatermarkText('TEST PURPOSE ONLY');
                $mpdf->watermark_font = 'timesnewroman';
                $mpdf->watermarkTextAlpha = 0.1;
            }

            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('background', "url('assets/images/BIDA_CERTIFICATE.jpg')");
            $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Training Certificate");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');

            //static footer section
            $mpdf->SetHTMLFooter('
            <div style="margin-top:20px; background-color: transparent;">
            <table class="table" width="100%">
                <tr>
                    <td style="text-align:center; width: 2%; background-color: transparent;"></td>
                    <td style="width: 32%; text-align: center; background-color: transparent;">
                        <img src="' . public_path('assets/images/bida_logo.png') . '" style="float:left; width: 80px;" />
                    </td>
                    <td style="text-align:center; width: 32%;  background-color: transparent;">
                        <img src="' . public_path('assets/images/bida_logo_v2.jpg') . '" style="float:center; width: 40px;" />
                    </td>
                    <td style="text-align:right; width: 32%; background-color: transparent;">
                        <img src="' . public_path('assets/images/business_automation.png') . '" style="float:right; width: 60px;" />
                    </td>
                    <td style="text-align:center; width: 2%; background-color: transparent;"></td>
                </tr>
            </table>
        </div>');

            $stylesheet = file_get_contents('assets/stylesheets/certificate.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);
            $mpdf->defaultfooterfontsize = 9;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;
            $mpdf->SetCompression(true);

            $directory = 'certificate/';
            $directoryYear = $directory . date('Y');
            $directoryYearMonth = $directory . date('Y/m');

            if (!file_exists($directoryYearMonth)) {
                mkdir($directoryYearMonth, 0777, true);
                touch($directoryYearMonth . '/index.html');

                if (!file_exists($directoryYear . '/index.html')) {
                    touch($directoryYear . '/index.html');

                    if (!file_exists($directory . '/index.html')) {
                        touch($directory . '/index.html');
                    }
                }
            }

            $pdfFilePath = $directoryYearMonth . "/TRM_" . Carbon::now()->timestamp . "_" . \App\Libraries\Encryption::encodeId(110) . ".pdf";

            $participants = TrParticipant::where('id', $part_id)->first();
            $participants->certificate = $pdfFilePath;

            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

            $participants = TrParticipant::where('id', $part_id)->first();
            $participants->certificate = $pdfFilePath;
            $participants->certificate_no = $certificate_no;
            $participants->update();

            $mpdf->Output($pdfFilePath, 'I');

            return redirect()->back()->with('success', 'Certificate created successfully');
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 0,
                'responseMessage' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function  regenerateCertificate($part_id, $course_id){
        try {
            $course_id = Encryption::decodeId($course_id);
            $part_id = Encryption::decodeId($part_id);

            // Qr code generator
            $dn2d = new DNS2D();
            $qrcode = $dn2d->getBarcodePNG(URL::to('/') . '/docs/Training/' . '110', 'QRCODE');
            $qrCode_url = 'data:image/png;base64,' . $qrcode;

            $md_signature = public_path('assets/images/md1.png');

            $data['participants'] = TrParticipant::where('id', $part_id)->first();
            $data['course'] = TrSchedule::leftJoin('tr_courses', 'tr_schedules.course_id', '=', 'tr_courses.id')
                ->where('tr_schedules.id', $course_id)
                ->select('tr_courses.course_title', 'tr_schedules.batch_id', 'tr_schedules.course_duration_start', 'tr_schedules.course_duration_end', 'tr_schedules.created_by')
                ->first();

            $director_data = Users::where('id', $data['course']->created_by)->select('signature_encode', 'user_full_name', 'designation')->first();
            $director_signature = 'data:image/jpeg;base64,' . $director_data->signature_encode;
            $user_full_name = CommonFunction::getUserFullnameById($data['course']->created_by);
            $designation = $director_data->designation;

            $batch_name = TrBatch::where('schedule_id', $course_id)->value('batch_name');
            $certificate_no = generateCertificateNumber($batch_name, date('Y'));

            $contents = view('Training::certificate.create-certificate', [
                'participants' => $data['participants'],
                'course' => $data['course'],
                'certificate_no' => $certificate_no,
                'qrCode_url' => $qrCode_url,
                'md_signature' => $md_signature,
                'director_signature' => $director_signature,
                'user_full_name' => $user_full_name,
                'designation' => $designation,

            ])->render();

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4-L',
                'default_font_size' => 10,
                'margin_left' => 15,
                'margin_right' => 15,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 10,
                'margin_footer' => 10,
                'setAutoTopMargin' => 'pad',
                'setAutoBottomMargin' => 'pad',
                'fontDir' => [
                    public_path('assets/fonts'), // Add your font directory paths here
                ],
                'fontdata' => [
                    'timesnewroman' => [
                        'R' => 'OpenSans-Regular.ttf',
                    ],
                    'solaimanlipi' => [
                        'R' => 'OLDENGL.TTF',
                    ],
                ],
                'default_font' => 'timesnewroman',
            ]);

            // Check for production mode
            if (config('app.server_type') != 'live') {
                $mpdf->showWatermarkText = true;
                $mpdf->SetWatermarkText('TEST PURPOSE ONLY');
                $mpdf->watermark_font = 'timesnewroman';
                $mpdf->watermarkTextAlpha = 0.1;
            }

            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('background', "url('assets/images/BIDA_CERTIFICATE.jpg')");
            $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Training Certificate");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');

            //static footer section
            $mpdf->SetHTMLFooter('
            <div style="margin-top:20px; background-color: transparent;">
            <table class="table" width="100%">
                <tr>
                    <td style="text-align:center; width: 2%; background-color: transparent;"></td>
                    <td style="width: 32%; text-align: center; background-color: transparent;">
                        <img src="' . public_path('assets/images/bida_logo.png') . '" style="float:left; width: 80px;" />
                    </td>
                    <td style="text-align:center; width: 32%; background-color: transparent;">
                        <img src="' . public_path('assets/images/bida_logo_v2.jpg') . '" style="float:center; width: 40px;" />
                    </td>
                    <td style="text-align:right; width: 32%; background-color: transparent;">
                        <img src="' . public_path('assets/images/business_automation.png') . '" style="float:right; width: 60px;" />
                    </td>
                    <td style="text-align:center; width: 2%; background-color: transparent;"></td>
                </tr>
            </table>
        </div>');

            $stylesheet = file_get_contents('assets/stylesheets/certificate.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($contents, 2);
            $mpdf->defaultfooterfontsize = 9;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;
            $mpdf->SetCompression(true);

            $directory = 'certificate/';
            $directoryYear = $directory . date('Y');
            $directoryYearMonth = $directory . date('Y/m');

            if (!file_exists($directoryYearMonth)) {
                mkdir($directoryYearMonth, 0777, true);
                touch($directoryYearMonth . '/index.html');

                if (!file_exists($directoryYear . '/index.html')) {
                    touch($directoryYear . '/index.html');

                    if (!file_exists($directory . '/index.html')) {
                        touch($directory . '/index.html');
                    }
                }
            }

            $pdfFilePath = $directoryYearMonth . "/TRM_" . Carbon::now()->timestamp . "_" . \App\Libraries\Encryption::encodeId(110) . ".pdf";

            $participants = TrParticipant::where('id', $part_id)->first();
            $participants->certificate = $pdfFilePath;

            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

            $participants = TrParticipant::where('id', $part_id)->first();
            $participants->certificate = $pdfFilePath;
            $participants->certificate_no = $certificate_no;
            $participants->update();

            return redirect()->back()->with('success', 'Certificate Regenerate successfully');
        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 0,
                'responseMessage' => 'Error: ' . $e->getMessage(),
            ]);
        } 
    }

}
