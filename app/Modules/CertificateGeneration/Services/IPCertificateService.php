<?php

namespace App\Modules\CertificateGeneration\Services;

use App\Modules\Apps\Models\pdfSignatureQrcode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ImportPermission\Models\ListOfMachineryImportedSpareParts;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Mpdf\Mpdf;

trait IPCertificateService
{
    public static function pdfGenerate($certificateData)
    {
        $docId = getDocUniqueId(11);

        $data =  self::getAppData($certificateData->process_type_id, $certificateData->app_id);
        // Barcode generator
        $dn1d = new DNS1D();
        $trackingNo = $data['appInfo']['tracking_no'];
        if (!empty($trackingNo)) {
            $barcode = $dn1d->getBarcodePNG($trackingNo, 'C39', 2, 60);
            $barCode_url = 'data:image/png;base64,' . $barcode;
        } else {
            $barCode_url = '';
        }

        // Qr code generator
        $dn2d = new DNS2D();
        if (!empty($certificateData->pdf_type) && !empty($docId)) {
            $qrcode = $dn2d->getBarcodePNG(URL::to('/').'/docs/'.$certificateData->pdf_type.'/'.$docId, 'QRCODE');
            $qrCode_url = 'data:image/png;base64,' . $qrcode;
        } else {
            $qrCode_url = '';
        }
        
        $contents = view('CertificateGeneration::ip_certificate_pdf', [
            'appInfo' => $data['appInfo'],
            'listOfMechineryImportedSpare'=> $data['listOfMechineryImportedSpare'],
            'director' => $data['director'],
            'director_signature' => $data['director_signature'],

        ])->render();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 10,
            'default_font' => 'timesnewroman',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 10,
            'setAutoTopMargin' => 'pad',
            'setAutoBottomMargin' => 'pad'
        ]);
        //static header section
        $mpdf->SetHTMLHeader('
        <div class="header">
            <div>
                <div style="float: left; width: 20.00%;">
                    <div class="logo_image">
                        <img src="assets/images/bida_logo.png" alt="" height="80px">
                    </div>
                </div>
                <div style="float: left; width: 60.00%;">
                    <div style=" text-align: center">
                        <span style="font-weight: bold">INVESTMENT IS PRIORITY</span> <br>
                        <span>The Govrnment of the People\'s Republic of Bangladesh </span> <br>
                        <span>'.trans('messages.authority_text').' </span><br>
                        <span>Bangladesh Investment Development Authority</span><br>
                        <span>Biniyog Bhaban</span><br>
                        <span>Registration & Incentives-Foreign Industry</span><br>
                    </div>
                </div>
                <div style="float: right;">
                    <div class="logo_image" style="margin-left: 50px;">
                        <img src="assets/images/bida_logo_v2.jpg" alt="" height="60px">
                    </div>
                </div>
            </div>
            
        </div>');

        // <div class="barcode" style="text-align: center;">
        //     <img src="'.$barCode_url.'" width="25%" alt="Barcode" height="30" />
        // </div>

        //if ($database_mode != 'PRODUCTION') {  
        if (config('app.server_type') != 'live') {
            $mpdf->SetWatermarkText('TEST PURPOSE ONLY');
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'timesnewroman';
            $mpdf->watermarkTextAlpha = 0.1;
        }

        $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("BIDA One Stop Service");
        $mpdf->SetSubject("IP Certificate");
        $mpdf->SetAuthor("Business Automation Limited");
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode('fullwidth');


        //static footer section
        $mpdf->SetHTMLFooter('
        <div style="margin-top:20px;">
            <table class="table" width="100%">
                <tr>
                    <td style="width: 75%">
                        <img src="'.$qrCode_url.'" width="70" alt="QR Code" height="70" />
                    </td>
                    <td style="text-align:center;" >
                    <img src="'.$data['director_signature'].'" width="70" alt="Director Signature" /><br>
                        ('.$data['director']->signer_name.')<br>
                        '.$data['director']->signer_designation.'<br>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 9px; text-align: center">
                        <br>

                        Plot # E-6/B, Agargaon, Sher-E-Bangla Nagar, Dhaka-1207. Phone : PABX 88-02-55007241-5, Fax : 88-02-55007238 <br>
                        E-mail : info@bida.gov.bd, Web : www.bida.gov.bd https://bidaquickserv.org. <br>
                        Document Generated Time: {DATE j-M-Y h:i a}<br>
                        Page {PAGENO}/{nbpg}
                    </td>
                </tr>
            </table>

        </div>
        <table width="100%">
            <tr>
                <td width="50%"><i style="font-size: 9px;">Download time: {DATE j-M-Y h:i a}</i></td>
                <td width="50%" align="right"><i style="font-size: 9px;">{PAGENO}/{nbpg}</i></td>
            </tr>
        </table>');

        $stylesheet = file_get_contents('assets/stylesheets/certificate.css');
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($contents, 2);
        $mpdf->defaultfooterfontsize = 9;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;
        $mpdf->SetCompression(true);

        $pdfFilePath = getPdfFilePath('IP', $certificateData->app_id);

        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

        //PDF print request table update
        $fullPath = config('app.project_root').'/'.$pdfFilePath;
        //$fullPath = URL::to('/').'/'.$pdfFilePath;
        $certificateData->certificate_link = $fullPath;
        $certificateData->doc_id = $docId;
        $certificateData->job_sending_status = 1;
        $certificateData->job_receiving_status = 1;
        $certificateData->no_of_try_job_sending = ($certificateData->no_of_try_job_sending + 1);
        $certificateData->save();

        // BRA master table update
        DB::table($certificateData->table_name)
            ->where('id', $certificateData->app_id)
            ->update([$certificateData->field_name => $fullPath]);
    }

    private static function getAppData($process_type_id, $app_id)
    {
        $data = [];
        $data['appInfo'] = ProcessList::leftJoin('ip_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'apps.office_division_id')
            ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'apps.office_district_id')
            ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'apps.office_thana_id')
            ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'apps.factory_district_id')
            ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'apps.factory_thana_id')
            ->where('process_list.ref_id', $app_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.process_type_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.approval_copy_remarks',
                'apps.*',
                DB::raw("CONCAT(apps.office_address,', ',apps.office_post_office, ', ',office_thana.area_nm, ', ',office_district.area_nm, ', ',office_division.area_nm, ', ',apps.office_post_code) as company_office_address"),
                DB::raw("CONCAT(apps.factory_address,', ',apps.factory_post_office, ', ',factory_thana.area_nm, ', ',factory_district.area_nm, ', ',apps.factory_post_code) as company_factory_address"),
            ]);

        $data['listOfMechineryImportedSpare'] = ListOfMachineryImportedSpareParts::leftJoin('currencies', 'ip_list_of_machinery_imported_spare_parts.total_value_ccy', '=', 'currencies.id')
            ->where('app_id', $app_id)
            ->where('process_type_id', $process_type_id)->where('status', 1)
            ->select('ip_list_of_machinery_imported_spare_parts.*', 'currencies.id as currency_id', 'currencies.code as currency_code')
            ->get();

        // Approved director information
        $data['director'] = pdfSignatureQrcode::where([
            'process_type_id' => $process_type_id,
            'app_id' => $app_id,
            'signature_type' => 'final',
        ])->first();

        if (!empty($data['director']->signature_encode)) {
            $data['director_signature'] = 'data:image/jpeg;base64,' . $data['director']->signature_encode;
        } else {
            $data['director_signature'] = "";
        }


        return $data;
    }

}