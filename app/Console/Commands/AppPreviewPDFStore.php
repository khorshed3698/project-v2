<?php

namespace App\Console\Commands;

use App\Modules\BidaRegistration\Models\BidaRegistration;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Libraries\CommonFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\BidaRegistration\Models\BusinessClass;
use App\Modules\BidaRegistration\Models\ListOfDirectors;
use App\Modules\BidaRegistration\Models\ListOfMachineryImported;
use App\Modules\BidaRegistration\Models\ListOfMachineryLocal;
use App\Modules\BidaRegistration\Models\SourceOfFinance;
use App\Modules\BidaRegistration\Models\LaAnnualProductionCapacity;
use Mpdf\Mpdf;
use Carbon\Carbon;

class AppPreviewPDFStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:preview-pdf-store';
    protected $process_type_id = 102;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Store Preview PDF of the applications in the database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */


    public function handle()
    {
        try {
            $limit = 2; // Adjust the limit as needed

            $nonAppPreviewData = BidaRegistration::leftJoin('process_list', 'process_list.ref_id', '=', 'br_apps.id')
                ->where(function ($query) {
                    $query->whereNull('br_apps.app_preview')
                        ->orWhere('br_apps.app_preview', '');
                })
                ->whereNotNull('br_apps.certificate_link')
                ->where('process_list.status_id', 25)
                ->where('process_list.process_type_id', $this->process_type_id)
                ->select('br_apps.id as br_app_id')
                ->limit($limit)
                ->get();

            if ($nonAppPreviewData->isEmpty()) {
                $this->info("No applications found for processing.");
                return;
            }

            foreach ($nonAppPreviewData as $app) {
                $this->appFormPdf($app->br_app_id);
            }

            $this->info("All applications processed successfully.");
        } catch (\Exception $e) {
            Log::error("Error in AppPreviewPDFStore command: " . $e->getMessage());
            $this->error("Error in AppPreviewPDFStore command: " . $e->getMessage());
        }
    }

    public function appFormPdf($appId)
    {

        try {
            $decodedAppId = $appId;
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('br_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('divisional_office', 'divisional_office.id', '=', 'process_list.approval_center_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('dept_application_type as app_type', 'app_type.id', '=', 'apps.app_type_id') // app type
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'apps.organization_type_id')
                ->leftJoin('ea_organization_status', 'ea_organization_status.id', '=', 'apps.organization_status_id')
                ->leftJoin('country_info as country_of_origin', 'country_of_origin.id', '=', 'apps.country_of_origin_id')
                ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'apps.ceo_country_id')
                ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'apps.ceo_district_id')
                ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'apps.ceo_thana_id')
                ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'apps.office_division_id')
                ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'apps.office_district_id')
                ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'apps.office_thana_id')
                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'apps.factory_thana_id')
                ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'apps.ownership_status_id')
                ->leftJoin('project_status', 'project_status.id', '=', 'apps.project_status_id')
                ->leftJoin('currencies as local_land_ivst_ccy_tbl', 'local_land_ivst_ccy_tbl.id', '=', 'apps.local_land_ivst_ccy')
                ->leftJoin('currencies as local_building_ivst_ccy_tbl', 'local_building_ivst_ccy_tbl.id', '=', 'apps.local_building_ivst_ccy')
                ->leftJoin('currencies as local_machinery_ivst_ccy_tbl', 'local_machinery_ivst_ccy_tbl.id', '=', 'apps.local_machinery_ivst_ccy')
                ->leftJoin('currencies as local_others_ivst_ccy_tbl', 'local_others_ivst_ccy_tbl.id', '=', 'apps.local_others_ivst_ccy')
                ->leftJoin('currencies as local_wc_ivst_ccy_tbl', 'local_wc_ivst_ccy_tbl.id', '=', 'apps.local_wc_ivst_ccy')
                ->leftJoin('company_info', 'company_info.id', '=', 'apps.company_id')
                ->where('process_list.ref_id', $decodedAppId)
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
                    'process_list.submitted_at',
                    'process_list.user_id as app_user_id',
                    'ps.status_name',
                    'apps.*',
                    'app_type.name as app_type_name',
                    'divisional_office.office_name as divisional_office_name',
                    'divisional_office.office_address as divisional_office_address',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',

                    'process_type.form_url',
                    'company_info.company_name',
                    'company_info.company_name_bn',

                    'ea_organization_type.name as organization_type_name',
                    'ea_organization_status.name as organization_status_name',
                    'ea_ownership_status.name as ownership_status_name',
                    'country_of_origin.nicename as country_of_origin_name',

                    'ceo_country.nicename as ceo_country_name',
                    'ceo_district.area_nm as ceo_district_name',
                    'ceo_thana.area_nm as ceo_thana_name',

                    'office_division.area_nm as office_division_name',
                    'office_district.area_nm as office_district_name',
                    'office_thana.area_nm as office_thana_name',

                    'factory_district.area_nm as factory_division_name',
                    'factory_district.area_nm as factory_district_name',
                    'factory_thana.area_nm as factory_thana_name',

                    'project_status.name as project_status_name',
                    'local_land_ivst_ccy_tbl.code as local_land_ivst_ccy_code',
                    'local_building_ivst_ccy_tbl.code as local_building_ivst_ccy_code',
                    'local_machinery_ivst_ccy_tbl.code as local_machinery_ivst_ccy_code',
                    'local_others_ivst_ccy_tbl.code as local_others_ivst_ccy_code',
                    'local_wc_ivst_ccy_tbl.code as local_wc_ivst_ccy_code',
                ]);

            if (empty($appInfo)) {
                return;
            }

            // $companyIds = CommonFunction::getUserCompanyByUserId($appInfo->user_id);

            $la_annual_production_capacity = LaAnnualProductionCapacity::leftJoin('product_unit as pro_unit', 'pro_unit.id', '=', 'br_annual_production_capacity.quantity_unit')
                ->select('br_annual_production_capacity.*', 'pro_unit.name as unit_name')
                ->where('app_id', $decodedAppId)->get();

            $source_of_finance = SourceOfFinance::leftJoin('country_info', 'country_info.id', '=', 'br_source_of_finance.country_id')
                ->where('app_id', $decodedAppId)
                ->get([
                    'br_source_of_finance.equity_amount',
                    'br_source_of_finance.loan_amount',
                    'country_info.nicename as country_name',
                ]);

            //view document in pdf
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            $listOfDirectors = ListOfDirectors::leftJoin('country_info', 'country_info.id', '=', 'list_of_directors.l_director_nationality')
                ->Where('app_id', $decodedAppId)
                ->where('process_type_id', $this->process_type_id)
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->get();

            $listOfMechineryImported = ListOfMachineryImported::Where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)->where('status', 1)->get();
            $listOfMechineryLocal = ListOfMachineryLocal::Where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)->where('status', 1)->get();
            //total mechinery
            $machineryImportedTotal = ListOfMachineryImported::where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)->sum('l_machinery_imported_total_value');

            //Total machinery local value ..
            $machineryLocalTotal = ListOfMachineryLocal::where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)->sum('l_machinery_local_total_value');

            //Business Sector
            $query = DB::select("
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
            where sec_class.code = '$appInfo->class_code' limit 1;
          ");

            $business_code = json_decode(json_encode($query), true);

            $sub_class = BusinessClass::select('id', DB::raw("CONCAT(code, ' - ', name) as name"))->where('id', $appInfo->sub_class_id)->first();

            $logoPath = public_path('assets/images/bida_logo.png');
            $checkLogo = public_path('assets/images/checked.png');
            $uncheckLogo = public_path('assets/images/unchecked.png');
            $doclogo = public_path('assets/images/pdf.png');

            $autorizedPerson = public_path("users/upload/".$appInfo->auth_image);
            $directorSign = public_path("uploads/".$appInfo->g_signature);


            $contents = view(
                "BidaRegistration::app_preview_pdf",
                compact(
                    'appInfo',
                    'basicAppInfo',
                    'visa_type_id',
                    'visa_type_name',
                    'embassy_name',
                    'visa_on_arrival_sought',
                    'airports',
                    'sector',
                    'industrial_unit',
                    'visiting_service_type',
                    'travel_purpose',
                    'department',
                    'workPermitTypes',
                    'typeofIndustry',
                    'organizationType',
                    'visaTypes',
                    'paymentMethods',
                    'district_eng',
                    'hsCodes',
                    'document',
                    'la_annual_production_capacity',
                    'listOfDirectors',
                    'business_code',
                    'sub_class',
                    'listOfMechineryImported',
                    'machineryImportedTotal',
                    'listOfMechineryLocal',
                    'machineryLocalTotal',
                    'source_of_finance',
                    'logoPath',
                    'checkLogo',
                    'doclogo',
                    'uncheckLogo',
                    'autorizedPerson',
                    'directorSign'
                )
            )->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default font family
                10, // margin_left
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents(public_path('assets/stylesheets/appviewPDF.css'));
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);


            $directory = 'public/uploads/';
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

            $pdfFilePath = $directoryYearMonth . "/BR_Prev_" . Carbon::now()->timestamp . ".pdf";
            $storedPdfFilePath = str_replace('public/', '', $pdfFilePath);
            $brData = BidaRegistration::where('id', $appInfo->ref_id)->first();
            $brData->app_preview = $storedPdfFilePath;

            $mpdf->Output($pdfFilePath, 'F'); // Saving pdf *** F for Save only, I for view only.

            $brData = BidaRegistration::where('id', $appInfo->ref_id)->first();
            $brData->app_preview = $storedPdfFilePath;
            $brData->update();
        } catch (\Exception $e) {
            Log::error('BRPdfFormPrev : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BRC-369]');
            $this->error("Error in AppPreviewPDFStore command: " . $e->getMessage());
        }
    }
}
