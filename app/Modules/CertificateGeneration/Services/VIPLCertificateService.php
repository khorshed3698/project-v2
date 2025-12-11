<?php

namespace App\Modules\CertificateGeneration\Services;

use App\Modules\Apps\Models\pdfSignatureQrcode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\VipLounge\Models\ViplPassportHolderInfo;
use App\Modules\VipLounge\Models\ViplSpouseChildInfo;
use App\Modules\Settings\Models\Currencies;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Mpdf\Mpdf;

trait VIPLCertificateService
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

        //GET DATABASE_MODE
        //$database_mode = DB::table('configuration')->where('caption', 'DATABASE_MODE')->first()->value;
        
        $contents = view('CertificateGeneration::vipl_certificate_pdf', $data)->render();

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
        $mpdf->SetHTMLHeader('<div class="header">
        <div class="logo_image" style="float: left; width: 140px">
           <img src="assets/images/bida_logo.png" alt="" height="80px">
         </div>
         <div style="text-align: right;">
           <span style="font-size: 18px;  float: right; font-weight: bold; color: #170280;">Bangladesh Investment Development Authority </span>
           <span style="font-size: 18px;  float: right; font-weight: bold; color: #170280;">(BIDA)</span><br>
           <span style="font-size: 13px; font-weight: bold">'.trans('messages.authority_text').'</span>
         </div><br>
         <div class="barcode" style="text-align: center;">
             <img src="'.$barCode_url.'" width="25%" alt="Barcode" height="30" />
         </div>
        </div>');

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
        $mpdf->SetSubject("VIPL Certificate");
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
                        <br><br>
                        <div style="text-align: right;">
                            <span style="margin-top: 50px;">Please scan for verification  </span>
                        </div>
                        <br><br>
                    </td>
                    <td style="text-align:center;" >
                    Sincerly Yours <br>
                    <img src="'.$data['director_signature'].'" width="70" alt="Director Signature" /><br>
                        ('.$data['director']->signer_name.')<br>
                        '.$data['director']->signer_designation.'<br>
                        Phone: '.$data['director']->signer_phone.'<br>
                        Email: '.$data['director']->signer_email.'<br>
                        Date: '.$data['appInfo']->approved_date.'<br>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="font-size: 9px; text-align: center">
                        <br>
                        Bangladesh Investment Development Authority, '.trans('messages.authority_text').', Plot #E-6/B, Agargaon, Sher-E-Bangla Nagar, Dhaka-1207.<br>
                        Phone: PABX 88-02-55007241-5, Fax: 88-02-55007238, Email: info@bida.gov.bd, Web: www.bida.gov.bd<br>
                        <i>To verify the authenticity of the approval copy, please scan the QR & log on to https://bidaquickserv.org.</i>
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

        $pdfFilePath = getPdfFilePath('VIPL', $certificateData->app_id);

        $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.
        //PDF print request table update
        $fullPath = config('app.project_root').'/'.$pdfFilePath;
        $certificateData->certificate_link = $fullPath;
        $certificateData->doc_id = $docId;
        $certificateData->job_sending_status = 1;
        $certificateData->job_receiving_status = 1;
        $certificateData->no_of_try_job_sending = ($certificateData->no_of_try_job_sending + 1);
        $certificateData->save();

        // VIPL master table update
        DB::table($certificateData->table_name)
            ->where('id', $certificateData->app_id)
            ->update([$certificateData->field_name => $fullPath]);
    }

    private static function getAppData($process_type_id, $app_id)
    {
        $data = [];
        $data['appInfo'] = ProcessList::leftJoin('vipl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('airports as a', 'a.id', '=', 'apps.airport_id')
            ->leftJoin('ea_service as s', 's.id', '=', 'apps.service_type')
            ->leftJoin('area_info as ot', 'apps.office_thana_id', '=', 'ot.area_id')
            ->leftJoin('area_info as odis', 'apps.office_district_id', '=', 'odis.area_id')
            ->leftJoin('area_info as odiv', 'apps.office_division_id', '=', 'odiv.area_id')
            ->leftJoin('vip_longue_purpose as lp', 'lp.id', '=', 'apps.vip_longue_purpose_id')
            ->leftJoin('country_info as emp_nationality', 'emp_nationality.id', '=', 'apps.emp_nationality_id')

            ->where('process_list.ref_id', $app_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->where('process_list.status_id', 25)
            ->first([
                'process_list.tracking_no',
                'process_list.submitted_at',
                'process_list.approval_copy_remarks',
                'a.id as AirId',
                'a.name as AirName',
                'a.executive_designation as AirExecutiveDesignation',
                'a.email as AirEmail',
                'a.phone as AirPhone',
                'a.Fax as AirFax',
                'a.city_name as AirCityName',
                'a.location as AirLocation',
                'a.country_name as AirCountryName',
                'apps.company_name as company_name',
                'apps.ref_no_type',
                'apps.reference_number',
                'apps.vip_longue_purpose_id',
                'apps.emp_name as DelegateName',
                'apps.emp_designation as DelegateDesignation',
                'apps.emp_passport_no as DelegatePassport',
                'emp_nationality.nationality as DelegateNationality',
                'apps.visa_purpose',
                'apps.ceo_designation as ceo_designation',
                's.name as type_of_industry',

                DB::raw("CONCAT(apps.office_address,', ',apps.office_post_office, ', ',ot.area_nm, ', ',odis.area_nm, ', ',odiv.area_nm, ', ',apps.office_post_code) as company_office_address"),
                DB::raw("CONCAT('Flight No: ', apps.arrival_flight_no, ', Arrival Date Time:', DATE_FORMAT(apps.arrival_date,'%d-%b-%Y'), ' ',TIME_FORMAT(apps.arrival_time,'%H:%i')) ArrivalInfo"),
                DB::raw("CONCAT('Flight No: ', apps.departure_flight_no, ', Departure Date Time:', DATE_FORMAT(apps.departure_date,'%d-%b-%Y'), ' ',TIME_FORMAT(apps.departure_time,'%H:%i')) DepartureInfo"),
                DB::raw("CONCAT(DATE_FORMAT(process_list.submitted_at,'%d.%m.%Y')) submit_date"),
                DB::raw("CONCAT(DATE_FORMAT(apps.approved_date,'%d %M, %Y')) approved_date"),
                
            ]);

        $data['viplPassportHolderInfo'] = ViplPassportHolderInfo::where('app_id', $app_id)->where('process_type_id', $process_type_id)
            ->get([
                'id',
                'passport_holder_name',
                'passport_holder_designation',
                'passport_holder_mobile',
                'passport_holder_passport_no',
                'passport_holder_attachment'
            ]);

        $data['spouseChildInfo'] = ViplSpouseChildInfo::where('app_id', $app_id)->where('process_type_id', $process_type_id)
            ->get([
                'id',
                'spouse_child_type',
                'spouse_child_name',
                'spouse_child_passport_per_no',
                'spouse_child_remarks'
            ]);

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