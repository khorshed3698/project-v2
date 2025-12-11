<?php

namespace App\Modules\CertificateGeneration\Services;

use App\Modules\Apps\Models\pdfSignatureQrcode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\BidaRegistrationAmendment\Models\BusinessClass;
use App\Modules\BidaRegistrationAmendment\Models\AnnualProductionCapacityAmendment;
use App\Modules\Settings\Models\Currencies;
use App\Modules\BidaRegistrationAmendment\Models\SourceOfFinanceAmendment;
use App\Modules\BidaRegistrationAmendment\Models\ExistingBRA;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Mpdf\Mpdf;

trait BRACertificateService
{
    public static function pdfGenerate($certificateData)
    {
        $currencyBDT = Currencies::orderBy('code')->whereIn('code', ['BDT'])->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
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
        
        $contents = view('CertificateGeneration::bra_certificate_pdf', [
            'appInfo' => $data['appInfo'],
            'busness_code' => $data['busness_code'],
            'sub_class' => $data['sub_class'],
            'n_busness_code' => $data['n_busness_code'],
            'n_sub_class' => $data['n_sub_class'],
            'annualProductionCapacity'=> $data['annualProductionCapacity'],
            'currencyBDT' => $currencyBDT,
            'sourceOfFinance' => $data['sourceOfFinance'],
            'company_md' => $data['company_md'],
            'director' => $data['director'],
            'director_signature' => $data['director_signature'],
            'company_office' => $data['company_office'],
            'bra_memo_no' => $data['bra_memo_no'],
        ])->render();

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4',
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
                    'solaimanlipi' => [
                        'R' => 'SolaimanLipi.ttf',
                        'I' => 'SolaimanLipi.ttf',
                        'useOTL' => 0xFF,
                        'useKashida' => 75,
                    ],
                ],
            'default_font' => 'solaimanlipi',
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
        $mpdf->SetSubject("BRA Certificate");
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
                        Phone: '.$data['director']->signer_phone.'<br>
                        Email: '.$data['director']->signer_email.'<br>
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

        $pdfFilePath = getPdfFilePath('BRA', $certificateData->app_id);

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
        $data['appInfo'] = ProcessList::leftJoin('bra_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('divisional_office', 'divisional_office.id', '=', 'process_list.approval_center_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id){
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('project_status', 'project_status.id', '=', 'apps.project_status_id')
            ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'apps.organization_type_id')
            ->leftJoin('ea_organization_type as n_ea_organization_type', 'n_ea_organization_type.id', '=', 'apps.n_organization_type_id')

            ->leftJoin('ea_organization_status', 'ea_organization_status.id', '=', 'apps.organization_status_id')
            ->leftJoin('ea_organization_status as n_ea_organization_status', 'n_ea_organization_status.id', '=', 'apps.n_organization_status_id')

            ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'apps.ownership_status_id')
            ->leftJoin('ea_ownership_status as n_ea_ownership_status', 'n_ea_ownership_status.id', '=', 'apps.n_ownership_status_id')

            ->leftJoin('country_info as country_of_origin', 'country_of_origin.id', '=', 'apps.country_of_origin_id')
            ->leftJoin('country_info as n_country_of_origin', 'n_country_of_origin.id', '=', 'apps.n_country_of_origin_id')

            ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'apps.ceo_country_id')
            ->leftJoin('country_info as n_ceo_country', 'n_ceo_country.id', '=', 'apps.n_ceo_country_id')

            ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'apps.ceo_district_id')
            ->leftJoin('area_info as n_ceo_district', 'n_ceo_district.area_id', '=', 'apps.n_ceo_district_id')

            ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'apps.ceo_thana_id')
            ->leftJoin('area_info as n_ceo_thana', 'n_ceo_thana.area_id', '=', 'apps.n_ceo_thana_id')

            ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'apps.office_division_id')
            ->leftJoin('area_info as n_office_division', 'n_office_division.area_id', '=', 'apps.n_office_division_id')

            ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'apps.office_district_id')
            ->leftJoin('area_info as n_office_district', 'n_office_district.area_id', '=', 'apps.n_office_district_id')

            ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'apps.office_thana_id')
            ->leftJoin('area_info as n_office_thana', 'n_office_thana.area_id', '=', 'apps.n_office_thana_id')

            ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'apps.factory_district_id')
            ->leftJoin('area_info as n_factory_district', 'n_factory_district.area_id', '=', 'apps.n_factory_district_id')

            ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'apps.factory_thana_id')
            ->leftJoin('area_info as n_factory_thana', 'n_factory_thana.area_id', '=', 'apps.n_factory_thana_id')

            ->leftJoin('project_status as n_project_status', 'n_project_status.id', '=', 'apps.n_project_status_id')

            ->where('process_list.ref_id', $app_id)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.department_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.approval_copy_remarks',
                'process_list.submitted_at',
                'user_desk.desk_name',
                'ps.status_name',
                'ps.color',
                'project_status.name as project_status_name',
                'apps.*',

                'divisional_office.office_name as divisional_office_name',
                'divisional_office.office_address as divisional_office_address',
                'ea_organization_type.name as organization_type_name',
                'n_ea_organization_type.name as n_organization_type_name',

                'ea_organization_status.name as organization_status_name',
                'n_ea_organization_status.name as n_organization_status_name',

                'ea_ownership_status.name as ownership_status_name',
                'n_ea_ownership_status.name as n_ownership_status_name',

                'country_of_origin.nicename as country_of_origin_name',
                'n_country_of_origin.nicename as n_country_of_origin_name',

                'ceo_country.nicename as ceo_country_name',
                'n_ceo_country.nicename as n_ceo_country_name',

                'ceo_district.area_nm as ceo_district_name',
                'n_ceo_district.area_nm as n_ceo_district_name',

                'ceo_thana.area_nm as ceo_thana_name',
                'n_ceo_thana.area_nm as n_ceo_thana_name',

                'office_division.area_nm as office_division_name',
                'n_office_division.area_nm as n_office_division_name',

                'office_district.area_nm as office_district_name',
                'n_office_district.area_nm as n_office_district_name',

                'office_thana.area_nm as office_thana_name',
                'n_office_thana.area_nm as n_office_thana_name',

                'factory_district.area_nm as factory_district_name',
                'n_factory_district.area_nm as n_factory_district_name',

                'factory_thana.area_nm as factory_thana_name',
                'n_factory_thana.area_nm as n_factory_thana_name',

                'n_project_status.name as n_project_status_name',

                'process_type.form_url',
            ]);


        $data['company_office'] = [
            'address' => empty($data['appInfo']->n_office_address) ? $data['appInfo']->office_address : $data['appInfo']->n_office_address,
            'post_office' => empty($data['appInfo']->n_office_post_office) ? $data['appInfo']->office_post_office : $data['appInfo']->n_office_post_office,
            'police_station' => empty($data['appInfo']->n_office_thana_name) ? $data['appInfo']->office_thana_name : $data['appInfo']->n_office_thana_name,
            'district' => empty($data['appInfo']->n_office_district_name) ? $data['appInfo']->office_district_name : $data['appInfo']->n_office_district_name,
            'post_code' => empty($data['appInfo']->n_office_post_code) ? $data['appInfo']->office_post_code : $data['appInfo']->n_office_post_code,
        ];

        //Business Sector for existing information
        $existingQuery = DB::select("
                Select 
                sec_class.id, 
                sec_class.code, 
                sec_class.name, 
                sec_group.id as group_id,
                sec_group.code as group_code,
                sec_group.name as group_name,
                sec_division.id as division_id,
                sec_division.code as division_code,
                sec_division.name as division_name,
                sec_section.id as section_id,
                sec_section.code as section_code,
                sec_section.name as section_name
                from (select * from sector_info_bbs where type = 4) sec_class
                left join sector_info_bbs sec_group on sec_class.pare_id = sec_group.id 
                left join sector_info_bbs sec_division on sec_group.pare_id = sec_division.id 
                left join sector_info_bbs sec_section on sec_division.pare_id = sec_section.id 
                where sec_class.code = '".$data['appInfo']['class_code']."' limit 1;
            ");

        $data['busness_code'] = json_decode(json_encode($existingQuery), true);
        $data['sub_class'] = BusinessClass::select('id', DB::raw("CONCAT(code, ' - ', name) as name"))->where('id', $data['appInfo']['sub_class_id'])->first();
        //end business sector

        //Business Sector for proposed information
        $proposedQuery = DB::select("
                Select 
                sec_class.id, 
                sec_class.code, 
                sec_class.name, 
                sec_group.id as group_id,
                sec_group.code as group_code,
                sec_group.name as group_name,
                sec_division.id as division_id,
                sec_division.code as division_code,
                sec_division.name as division_name,
                sec_section.id as section_id,
                sec_section.code as section_code,
                sec_section.name as section_name
                from (select * from sector_info_bbs where type = 4) sec_class
                left join sector_info_bbs sec_group on sec_class.pare_id = sec_group.id 
                left join sector_info_bbs sec_division on sec_group.pare_id = sec_division.id 
                left join sector_info_bbs sec_section on sec_division.pare_id = sec_section.id 
                where sec_class.code = '".$data['appInfo']['n_class_code']."' limit 1;
            ");

        $data['n_busness_code'] = json_decode(json_encode($proposedQuery), true);
        $data['n_sub_class'] = BusinessClass::select('id', DB::raw("CONCAT(code, ' - ', name) as name"))->where('id', $data['appInfo']['n_sub_class_id'])->first();
        //end business sector

        $data['annualProductionCapacity'] = AnnualProductionCapacityAmendment::leftJoin('product_unit', 'product_unit.id', '=', 'annual_production_capacity_amendment.quantity_unit')
            ->leftJoin('product_unit as n_product_unit', 'n_product_unit.id', '=', 'annual_production_capacity_amendment.n_quantity_unit')
            ->where('app_id', $app_id)
            ->where('amendment_type', '!=', 'delete')
            ->get([
                'annual_production_capacity_amendment.product_name',
                'product_unit.name as unit_name',
                'annual_production_capacity_amendment.quantity',
                'annual_production_capacity_amendment.price_usd',
                'annual_production_capacity_amendment.price_taka',

                'annual_production_capacity_amendment.n_product_name',
                'n_product_unit.name as n_unit_name',
                'annual_production_capacity_amendment.n_quantity',
                'annual_production_capacity_amendment.n_price_usd',
                'annual_production_capacity_amendment.n_price_taka',
                'annual_production_capacity_amendment.amendment_type',
                'amendment_type'
            ]);

        $data['sourceOfFinance'] = SourceOfFinanceAmendment::leftJoin('country_info', 'country_info.id', '=', 'source_of_finance_amendment.country_id')
            ->leftJoin('country_info as n_country_info', 'n_country_info.id', '=', 'source_of_finance_amendment.n_country_id')
            ->where('app_id', $app_id)->where('process_type_id', $process_type_id)
            ->get([
                'country_info.nicename as country_name',
                'source_of_finance_amendment.equity_amount',
                'source_of_finance_amendment.loan_amount',

                'n_country_info.nicename as n_country_name',
                'source_of_finance_amendment.n_equity_amount',
                'source_of_finance_amendment.n_loan_amount',
            ]);

        // Company Information of (Chairman/ Managing Director/ Or Equivalent)
        $data['company_md']['name'] = empty($data['appInfo']['n_g_full_name']) ? $data['appInfo']['g_full_name'] : $data['appInfo']['n_g_full_name'];
        $data['company_md']['designation'] = empty($data['appInfo']['n_g_designation']) ? $data['appInfo']['g_designation'] : $data['appInfo']['n_g_designation'];
        $company_md_signature = empty($data['appInfo']['n_g_signature']) ? $data['appInfo']['g_signature'] : $data['appInfo']['n_g_signature'];
        if (file_exists("users/upload/" . $company_md_signature)) {
            $data['company_md']['signature'] =  '<img src="users/upload/' . $company_md_signature . '" alt="" height="60">';
        } else {
            $data['company_md']['signature']  = '';
        }

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

        $data['bra_memo_no'] = ExistingBRA::select(DB::raw("GROUP_CONCAT(CONCAT('Ref No.: ', bra_memo_no,', ', 'Dated: ', DATE_FORMAT(bra_approved_date,'%d-%b-%Y'))) bra_ref_no"))->where('app_id', $app_id)->first();

        return $data;
    }

}