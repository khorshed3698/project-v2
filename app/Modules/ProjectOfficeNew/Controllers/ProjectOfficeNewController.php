<?php

namespace App\Modules\ProjectOfficeNew\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\BasicInformation\Controllers\BasicInformationController;
use App\Modules\ProjectOfficeNew\Models\ProjectOfficeNew;
use App\Modules\ProjectOfficeNew\Models\OPOfficeType;
use App\Modules\ProjectOfficeNew\Models\POOrganizationType;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mpdf\Mpdf;
use App\Modules\ProjectOfficeNew\Models\ProjectOfficeNewCompanyOffice;
use App\Modules\ProjectOfficeNew\Models\ProjectOfficeNewSiteOffice;
use App\Modules\ProjectOfficeNew\Models\ProjectOfficeNewForeignDetail;
use App\Modules\OfficePermissionNew\Models\OfficePermissionNew;
use App\Modules\OfficePermissionExtension\Models\OfficePermissionExtension;

class ProjectOfficeNewController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 22;
        $this->aclName = 'ProjectOfficeNew';
    }

    public function applicationForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [PONC-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [PONC-971]</h4>"
            ]);
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [PONC-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application. [PONC-1041]</h4>"
            ]);
        }

        try {
            // Checking the payment configuration for this service
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1,
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (empty($payment_config)) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![PONC-10100]</h4>"
                ]);
            }

            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            $process_type_id = $this->process_type_id;
            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = POOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $company_info = CompanyInfo::where('id', $company_id)->first(['company_name']);
            $viewMode = 'off';
            $mode = '-A-';

            $public_html = strval(view("ProjectOfficeNew::application-form",
                compact('process_type_id', 'viewMode', 'mode', 'officeType', 'countries', 'company_info',
                    'organizationTypes', 'district_eng', 'payment_config', 'divisions', 'company_id')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('OPNAppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1005]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . ' [PONC-1005]' . "</h4>"
            ]);
        }
    }

    public function getDocList(Request $request)
    {
        $attachment_key = $request->get('attachment_key');
        $viewMode = $request->get('viewMode');
        $app_id = ($request->has('app_id') ? Encryption::decodeId($request->get('app_id')) : 0);

        if (!empty($app_id)) {
            $document_query = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->where('app_documents.ref_id', $app_id)
                ->where('app_documents.process_type_id', $this->process_type_id);


            $document_query->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key);
            
            $document = $document_query->get([
                'attachment_list.id',
                'attachment_list.doc_priority',
                'attachment_list.additional_field',
                'app_documents.id as document_id',
                'app_documents.doc_file_path as doc_file_path',
                'app_documents.doc_name',
            ]);
            
            if (count($document) < 1) {
                $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                    ->where('attachment_type.key', $attachment_key)
                    ->where('attachment_list.status', 1)
                    ->where('attachment_list.is_archive', 0)
                    ->orderBy('attachment_list.order')
                    ->get(['attachment_list.*']);
            }
        } else {
            $document = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
        }

        $html = strval(view("ProjectOfficeNew::documents",
            compact('document', 'viewMode')));
        return response()->json(['html' => $html]);
    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [PONC-1002]';
        }
        
        $viewMode = 'off';
        $mode = '-E-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [PONC-972]</h4>"
            ]);
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('pon_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')
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
                    'process_list.resend_deadline',

                    'user_desk.desk_name',

                    'ps.status_name',
                    'ps.color',
                    
                    'apps.*',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                ]);

            $ponCompanyOfficeList = ProjectOfficeNewCompanyOffice::where('app_id', $decodedAppId)->get();
            $ponSiteOfficeList = ProjectOfficeNewSiteOffice::where('app_id', $decodedAppId)->get();
            $ponForeignDetailList = ProjectOfficeNewForeignDetail::where('app_id', $decodedAppId)->get();
            

            // Last remarks attachment
            $remarks_attachment = DB::select(DB::raw("select * from
                                                `process_documents`
                                                where `process_type_id` = $this->process_type_id and `ref_id` = $appInfo->process_list_id and `status_id` = $appInfo->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$appInfo->process_list_id AND process_type_id=$this->process_type_id AND status_id=$appInfo->status_id)
                                                ORDER BY id ASC"
            ));

            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = POOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);

            // Get application basic company information
            $company_id = $appInfo->company_id;
            $basic_company_info = CommonFunction::getBasicCompanyInfo($company_id);

            $public_html = strval(view("ProjectOfficeNew::application-form-edit",
                compact('process_type_id', 'appInfo', 'officeType', 'document',
                    'countries', 'organizationTypes', 'remarks_attachment', 'district_eng', 'divisions',
                    'viewMode', 'mode', 'metingInformation', 'basic_company_info', 'company_id',
                    'ponCompanyOfficeList', 'ponSiteOfficeList', 'ponForeignDetailList', 'ponForeignDetailList')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('OPNViewEditForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[PONC-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [PONC-1003]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information.  [PONC-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('pon_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('area_info as poa_co_division', 'poa_co_division.area_id', '=', 'apps.poa_co_division_id')
                ->leftJoin('area_info as poa_co_district', 'poa_co_district.area_id', '=', 'apps.poa_co_district_id')
                ->leftJoin('area_info as poa_co_thana', 'poa_co_thana.area_id', '=', 'apps.poa_co_thana_id')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')
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
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*',

                    'process_type.form_url',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',

                    'gfp.contact_name as gfp_contact_name',
                    'gfp.contact_email as gfp_contact_email',
                    'gfp.contact_no as gfp_contact_phone',
                    'gfp.address as gfp_contact_address',
                    'gfp.pay_amount as gfp_pay_amount',
                    'gfp.tds_amount as gfp_tds_amount',
                    'gfp.vat_on_pay_amount as gfp_vat_on_pay_amount',
                    'gfp.transaction_charge_amount as gfp_transaction_charge_amount',
                    'gfp.vat_on_transaction_charge as gfp_vat_on_transaction_charge',
                    'gfp.total_amount as gfp_total_amount',
                    'gfp.payment_status as gfp_payment_status',
                    'gfp.pay_mode as gfp_pay_mode',
                    'gfp.pay_mode_code as gfp_pay_mode_code',

                    'poa_co_division.area_nm as poa_co_division_name',
                    'poa_co_district.area_nm as poa_co_district_name',
                    'poa_co_thana.area_nm as poa_co_thana_name',
                ]);
                
                $ponCompanyOfficeList = ProjectOfficeNewCompanyOffice::leftJoin('po_organization_type', 'pon_companies_offices.c_org_type', '=', 'po_organization_type.id')
                ->leftJoin('country_info', 'pon_companies_offices.c_origin_country_id', '=', 'country_info.id')
                ->leftJoin('area_info as c_district', 'c_district.area_id', '=', 'pon_companies_offices.c_district_id')
                ->leftJoin('area_info as c_thana', 'c_thana.area_id', '=', 'pon_companies_offices.c_thana_id')
                ->where('app_id', $decodedAppId)
                ->get([
                    'pon_companies_offices.*',
                    'po_organization_type.name as c_org_type_name',
                    'country_info.nicename as c_origin_country_name',
                    'c_district.area_nm as c_district_name',
                    'c_thana.area_nm as c_thana_name'
                ]);
            $ponSiteOfficeList = ProjectOfficeNewSiteOffice::leftJoin('area_info as poa_so_division', 'poa_so_division.area_id', '=', 'pon_site_offices.poa_so_division_id')
            ->leftJoin('area_info as poa_so_district', 'poa_so_district.area_id', '=', 'pon_site_offices.poa_so_district_id')
            ->leftJoin('area_info as poa_so_thana', 'poa_so_thana.area_id', '=', 'pon_site_offices.poa_so_thana_id')
            ->where('app_id', $decodedAppId)
            ->get([
                'pon_site_offices.*',
                'poa_so_division.area_nm as poa_so_division_name',
                'poa_so_district.area_nm as poa_so_district_name',
                'poa_so_thana.area_nm as poa_so_thana_name'
            ]);
            $ponForeignDetailList = ProjectOfficeNewForeignDetail::where('app_id', $decodedAppId)->get();

            // Checking the Government Fee Payment(GFP) configuration for this service
            if (in_array($appInfo->status_id, [15,32])) {
                $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                    'sp_payment_configuration.payment_category_id')
                    ->where([
                        'sp_payment_configuration.process_type_id' => $this->process_type_id,
                        'sp_payment_configuration.payment_category_id' => 2, //Government fee payment
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);

                if (empty($payment_config)) {
                    return response()->json([
                        'responseCode' => 1,
                        'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![PONC-10100]</h4>"
                    ]);
                }


                $relevant_info_array = [
                    'approved_duration_start_date' => $appInfo->approved_duration_start_date,
                    'approved_duration_end_date' => $appInfo->approved_duration_end_date,
                    'process_type_id' => $this->process_type_id,
                ];
                $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config, $relevant_info_array);
                $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'];

                // TODO : application dependent fee need to separate from payment configuration
                //$payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
                $vatFreeAllowed = BasicInformationController::isAllowedWithOutVAT($this->process_type_id);
                $payment_config->vat_on_pay_amount = ($vatFreeAllowed === true) ? 0 : $unfixed_amount_array['total_vat_on_pay_amount'];
            }

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);

            // Attachment
            $attachment_key = "pon_branch";
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                //->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            $public_html = strval(view("ProjectOfficeNew::application-form-view",
                compact('process_type_id', 'appInfo', 'payment_config', 'document', 'viewMode', 'mode', 'metingInformation', 'ponCompanyOfficeList', 'ponSiteOfficeList', 'ponForeignDetailList')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('OPNAppView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [PONC-1115]');
            return Redirect::back()->withInput();
        }

    }

    public function appStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query.  [PONC-974]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error', "Sorry! You have no approved Basic Information application for BIDA services.  [PONC-9992]");
            return redirect()->back();
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if (in_array($department_id, [2, 4])) {
            Session::flash('error', "Sorry! The department is not allowed to apply to this application.  [PONC-1042]");
            return redirect()->back();
        }

        // Checking the Service Fee Payment(SFP) configuration for this service
        $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
            'sp_payment_configuration.payment_category_id')
            ->where([
                'sp_payment_configuration.process_type_id' => $this->process_type_id,
                'sp_payment_configuration.payment_category_id' => 1,  // Submission Payment
                'sp_payment_configuration.status' => 1,
                'sp_payment_configuration.is_archive' => 0,
            ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [PONC-100]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            Session::flash('error', "Stakeholder not found [PONC-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [PONC-105]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "pon_branch";
        
        $doc_row = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->where('attachment_type.key', $attachment_key)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.id', 'attachment_list.doc_name', 'attachment_list.doc_priority']);
        
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }
            
            $rules['ceo_country_id'] = 'required|numeric';
            // Conditional validation for ceo_country_id == 18
            if ($request->get('ceo_country_id') == 18) {
                $rules['ceo_nid'] = 'required';
                $rules['ceo_district_id'] = 'required|numeric';
                $rules['ceo_thana_id'] = 'required|numeric';
            }else{
                $rules['ceo_passport_no'] = 'required';
                $rules['ceo_state'] = 'required';
                $rules['ceo_city'] = 'required';
            }
            $rules['ceo_dob'] = 'required|date|date_format:d-M-Y';
            $rules['ceo_designation'] = 'required';
            $rules['ceo_full_name'] = 'required';
            $rules['ceo_post_code'] = 'required|digits:4';
            $rules['ceo_address'] = 'required';
            $rules['ceo_telephone_no'] = 'required|phone_or_mobile';
            $rules['ceo_mobile_no'] = 'required|phone_or_mobile';
            $rules['ceo_father_name'] = 'required|string';
            $rules['ceo_mother_name'] = 'required|string';
            $rules['ceo_spouse_name'] = 'required|string';
            $rules['ceo_email'] = 'required|email';
            $rules['ceo_fax_no'] = 'required';
            $rules['ceo_gender'] = 'required';
            $rules['project_name'] = 'required|string';
            $rules['project_major_activities'] = 'required|string';
            $rules['project_major_details'] = 'required|string';
            $rules['poa_co_division_id'] = 'required|numeric';
            $rules['poa_co_district_id'] = 'required|numeric';
            $rules['poa_co_thana_id'] = 'required|numeric';
            $rules['poa_co_post_office'] = 'required';
            $rules['poa_co_post_code'] = 'required';
            $rules['poa_co_address'] = 'required';
            $rules['poa_co_mobile_no'] = 'required|phone_or_mobile';
            $rules['poa_co_email'] = 'required|email';
            $rules['poa_co_telephone_no'] = 'phone_or_mobile';
            $rules['project_amount'] = 'required|numeric';
            $rules['period_start_date'] = 'required|date|date_format:d-M-Y';
            $rules['period_end_date'] = 'required|date|date_format:d-M-Y';
            $rules['period_validity'] = 'required';
            $rules['duration_amount'] = 'required';
            $rules['authorized_name'] = 'required';
            $rules['authorized_designation'] = 'required';
            $rules['authorized_org_dep'] = 'required';
            $rules['authorized_address'] = 'required';
            $rules['authorized_mobile_no'] = 'required|phone_or_mobile';
            $rules['authorized_email'] = 'required|email';
            $rules['ministry_name'] = 'required';
            $rules['ministry_address'] = 'required';
            $rules['contract_signing_date'] = 'required|date|date_format:d-M-Y';
            $rules['local_technical'] = 'required|numeric';
            $rules['local_general'] = 'required|numeric';
            $rules['local_total'] = 'required|numeric';
            $rules['foreign_technical'] = 'required|numeric';
            $rules['foreign_general'] = 'required|numeric';
            $rules['foreign_total'] = 'required|numeric';
            $rules['manpower_total'] = 'required|numeric';
            $rules['auth_full_name'] = 'required';
            $rules['auth_designation'] = 'required';
            $rules['auth_email'] = 'required|email';
            $rules['auth_mobile_no'] = 'required';
            $rules['auth_image'] = 'required';
            $rules['accept_terms'] = 'required';

            $messages['ceo_passport_no.required'] = 'CEO Passport Number is required when the country is not Bangladesh.';
            $messages['ceo_state.required'] = 'CEO State is required when the country is not Bangladesh.';
            $messages['ceo_city.required'] = 'CEO City is required when the country is not Bangladesh.';
            $messages['ceo_post_code.required'] = 'CEO Post Code field is required and must be 4 digits.';
            $messages['ceo_address.required'] = 'CEO Address field is required.';
            $messages['ceo_telephone_no.required'] = 'CEO Telephone Number is required and must be a valid phone number.';
            $messages['ceo_mobile_no.required'] = 'CEO Mobile Number is required and must be a valid phone number.';
            $messages['ceo_father_name.required'] = 'CEO Father’s Name field is required.';
            $messages['ceo_mother_name.required'] = 'CEO Mother’s Name field is required.';
            $messages['ceo_spouse_name.required'] = 'CEO Spouse’s Name field is required.';
            $messages['ceo_email.required'] = 'CEO Email field is required and must be a valid email address.';
            $messages['ceo_fax_no.required'] = 'CEO Fax Number field is required.';
            $messages['ceo_gender.required'] = 'CEO Gender field is required.';
            $messages['project_amount.required'] = 'Project Amount field is required and must be numeric.';
            $messages['period_start_date.required'] = 'Project Start Date field is required and must be in the format DD-MMM-YYYY.';
            $messages['period_end_date.required'] = 'Project End Date field is required and must be in the format DD-MMM-YYYY.';
            $messages['period_validity.required'] = 'Project Validity field is required.';
            $messages['duration_amount.required'] = 'Project Duration field is required.';
            $messages['authorized_name.required'] = 'Authorized Person’s Name field is required.';
            $messages['authorized_designation.required'] = 'Authorized Person’s Designation field is required.';
            $messages['authorized_org_dep.required'] = 'Authorized Organization/Department field is required.';
            $messages['authorized_address.required'] = 'Authorized Address field is required.';
            $messages['authorized_mobile_no.required'] = 'Authorized Mobile Number is required and must be a valid phone number.';
            $messages['authorized_email.required'] = 'Authorized Email field is required and must be a valid email address.';
            $messages['ministry_name.required'] = 'Ministry Name field is required.';
            $messages['ministry_address.required'] = 'Ministry Address field is required.';
            $messages['local_technical.required'] = 'Local Technical Manpower field is required and must be numeric.';
            $messages['local_general.required'] = 'Local General Manpower field is required and must be numeric.';
            $messages['local_total.required'] = 'Total Local Manpower field is required and must be numeric.';
            $messages['foreign_technical.required'] = 'Foreign Technical Manpower field is required and must be numeric.';
            $messages['foreign_general.required'] = 'Foreign General Manpower field is required and must be numeric.';
            $messages['foreign_total.required'] = 'Total Foreign Manpower field is required and must be numeric.';
            $messages['manpower_total.required'] = 'Total Manpower field is required and must be numeric.';
            $messages['auth_full_name.required'] = 'Authorized Representative’s Full Name field is required.';
            $messages['auth_designation.required'] = 'Authorized Representative’s Designation field is required.';
            $messages['auth_email.required'] = 'Authorized Representative’s Email field is required and must be a valid email address.';
            $messages['auth_mobile_no.required'] = 'Authorized Representative’s Mobile Number is required.';
            $messages['auth_image.required'] = 'Authorized Representative’s Image is required.';
            $messages['accept_terms.required'] = 'You must accept the terms and conditions.';
        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = ProjectOfficeNew::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new ProjectOfficeNew();
                $processData = new ProcessList();
            }

            // Company Information
            $appData->company_name = $basicInfo->company_name;
            $appData->company_name_bn = $basicInfo->company_name_bn;
            $appData->service_type = $basicInfo->service_type;
            $appData->reg_commercial_office = $basicInfo->reg_commercial_office;
            $appData->ownership_status_id = $basicInfo->ownership_status_id;
            $appData->organization_type_id = $basicInfo->organization_type_id;
            $appData->major_activities = $basicInfo->major_activities;
            
            // Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_dob = (!empty($request->get('ceo_dob')) ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
            $appData->ceo_passport_no = $request->get('ceo_passport_no');
            $appData->ceo_nid = $request->get('ceo_nid');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_city = $request->get('ceo_city');
            $appData->ceo_state = $request->get('ceo_state');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->ceo_telephone_no = $request->get('ceo_telephone_no');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_father_name = $request->get('ceo_father_name');
            $appData->ceo_mother_name = $request->get('ceo_mother_name');
            $appData->ceo_spouse_name = $request->get('ceo_spouse_name');
            $appData->ceo_gender = $request->get('ceo_gender');

            // Office Address
            $appData->office_division_id = $basicInfo->office_division_id;
            $appData->office_district_id = $basicInfo->office_district_id;
            $appData->office_thana_id = $basicInfo->office_thana_id;
            $appData->office_post_office = $basicInfo->office_post_office;
            $appData->office_post_code = $basicInfo->office_post_code;
            $appData->office_address = $basicInfo->office_address;
            $appData->office_telephone_no = $basicInfo->office_telephone_no;
            $appData->office_mobile_no = $basicInfo->office_mobile_no;
            $appData->office_fax_no = $basicInfo->office_fax_no;
            $appData->office_email = $basicInfo->office_email;

            // Factory Address
            $appData->factory_district_id = $basicInfo->factory_district_id;
            $appData->factory_thana_id = $basicInfo->factory_thana_id;
            $appData->factory_post_office = $basicInfo->factory_post_office;
            $appData->factory_post_code = $basicInfo->factory_post_code;
            $appData->factory_address = $basicInfo->factory_address;
            $appData->factory_telephone_no = $basicInfo->factory_telephone_no;
            $appData->factory_mobile_no = $basicInfo->factory_mobile_no;
            $appData->factory_fax_no = $basicInfo->factory_fax_no;
            $appData->factory_email = $basicInfo->factory_email;
            $appData->factory_mouja = $basicInfo->factory_mouja;

            $processData->company_id = $company_id;

            // 1. Name of the Project
            $appData->project_name = $request->get('project_name');
            $appData->project_major_activities = $request->get('project_major_activities');
            $appData->project_major_details = $request->get('project_major_details');

            // 3. Project Office Address (corporate office)
            $appData->poa_co_division_id = $request->get('poa_co_division_id');
            $appData->poa_co_district_id = $request->get('poa_co_district_id');
            $appData->poa_co_thana_id = $request->get('poa_co_thana_id');
            $appData->poa_co_post_office = $request->get('poa_co_post_office');
            $appData->poa_co_post_code = $request->get('poa_co_post_code');
            $appData->poa_co_address = $request->get('poa_co_address');
            $appData->poa_co_telephone_no = $request->get('poa_co_telephone_no');
            $appData->poa_co_mobile_no = $request->get('poa_co_mobile_no');
            $appData->poa_co_fax_no = $request->get('poa_co_fax_no');
            $appData->poa_co_email = $request->get('poa_co_email');

            // 5. The contact Amount of the Project (in US $)
            $appData->project_amount = $request->get('project_amount');

            // 6. Proposed Project Duration (as per contract)
            $appData->period_start_date = (!empty($request->get('period_start_date')) ? date('Y-m-d',
                strtotime($request->get('period_start_date'))) : null);
            $appData->period_end_date = (!empty($request->get('period_end_date')) ? date('Y-m-d',
                strtotime($request->get('period_end_date'))) : null);
            $appData->period_validity = $request->get('period_validity');
            $appData->duration_amount = $request->get('duration_amount');
            // Insert aslo approved office permission desired duration for desk user (process)
            $appData->approved_duration_start_date = (!empty($request->get('period_start_date')) ? date('Y-m-d',
                strtotime($request->get('period_start_date'))) : null);
            $appData->approved_duration_end_date = (!empty($request->get('period_end_date')) ? date('Y-m-d',
                strtotime($request->get('period_end_date'))) : null);
            $appData->approved_desired_duration = $request->get('period_validity');
            $appData->approved_duration_amount = $request->get('duration_amount');

           

            // 7. Authorized Person of Procurement Entity
            $appData->authorized_name = $request->get('authorized_name');
            $appData->authorized_designation = $request->get('authorized_designation');
            $appData->authorized_org_dep = $request->get('authorized_org_dep');
            $appData->authorized_address = $request->get('authorized_address');
            $appData->authorized_mobile_no = $request->get('authorized_mobile_no');
            $appData->authorized_email = $request->get('authorized_email');
            if ($request->hasFile("authorized_letter")) {
                $appData->authorized_letter =  $this->uploadFile('authorized_letter_', $request->file("authorized_letter"));
            }
            

            // 8. Ministry/Department/Organization of the project to be implemented
            $appData->ministry_name = $request->get('ministry_name');
            $appData->ministry_address = $request->get('ministry_address');
            $appData->contract_signing_date = (!empty($request->get('contract_signing_date')) ? date('Y-m-d', strtotime($request->get('contract_signing_date'))) : null);

            // 9. Proposed organizational set up of the project Office with expatriate and local man power
            $appData->local_technical = $request->get('local_technical');
            $appData->local_general = $request->get('local_general');
            $appData->local_total = $request->get('local_total');
            $appData->foreign_technical = $request->get('foreign_technical');
            $appData->foreign_general = $request->get('foreign_general');
            $appData->foreign_total = $request->get('foreign_total');
            $appData->manpower_total = $request->get('manpower_total');

            // Authorized person of the organization
            $appData->auth_full_name = $request->get('auth_full_name');
            $appData->auth_designation = $request->get('auth_designation');
            $appData->auth_mobile_no = $request->get('auth_mobile_no');
            $appData->auth_email = $request->get('auth_email');
            $appData->auth_image = $request->get('auth_image');

            
            if ($request->has('accept_terms')) {
                $appData->accept_terms = 1;
            }

            //set process list table data for application status and desk with condition basis
            if (in_array($request->get('actionBtn'), ['draft', 'submit'])) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } elseif ($request->get('actionBtn') == 'resubmit' && in_array($processData->status_id, [5, 22])) {
                $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);
                $processData->status_id = $resubmission_data['process_starting_status'];
                $processData->desk_id = $resubmission_data['process_starting_desk'];
                // For shortfall application re-submission
                if($processData->status_id == 5) {
                    $processData->process_desc = 'Re-submitted form applicant';
                }
            }

            $appData->save();

            /*
             * Department and Sub-department specification for application processing
             */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => CommonFunction::getDeptIdByCompanyId($company_id),
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->read_status = 0;
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $jsonData['Type'] = CommonFunction::getOfficeTypeById($request->get('office_type'));

            $processData['json_object'] = json_encode($jsonData);
            $processData->save();
            //Store attachment
            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
                    //$documentName = (!empty($request->get('other_doc_name_' . $docs->id)) ? $request->get('other_doc_name_' . $docs->id) : $request->get('doc_name_' . $docs->id));

                    $app_doc = AppDocuments::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_info_id' => $docs->id
                    ]);
                    $app_doc->doc_name = $docs->doc_name;
                    $app_doc->doc_file_path = $request->get('validate_field_' . $docs->id);
                    $app_doc->save();
                }
            }

             // 2. Information of the company(s) composing JV/ Consortium/ association office
             if ($request->has('company_office_approved') && !empty(array_filter($request->company_office_approved))) {
                $ponCompanyOfficeIds = [];
                foreach ($request->get('company_office_approved') as $key => $value) {
                    if (empty($request->get('pon_company_office_record_id')[$key])) {
                        $ponCompanyOfficeData = new ProjectOfficeNewCompanyOffice();
                        $ponCompanyOfficeData->app_id = $appData->id;
                    }else{
                        $recordId = $request->get('pon_company_office_record_id')[$key];
                        $ponCompanyOfficeData = ProjectOfficeNewCompanyOffice::where('id', $recordId)->first();
                    }
                    $ponCompanyOfficeData->company_office_approved = isset($request->get('company_office_approved')[$key]) ? $request->get('company_office_approved')[$key] : null;
                    $ponCompanyOfficeData->is_approval_online = isset($request->get('is_approval_online')[$key]) ? $request->get('is_approval_online')[$key] : null;
                    if($ponCompanyOfficeData->is_approval_online == 'yes'){
                        $ponCompanyOfficeData->ref_app_tracking_no = $request->get('ref_app_tracking_no')[$key];
                        $ponCompanyOfficeData->ref_app_approve_date = (!empty($request->get('ref_app_approve_date')[$key]) ? date('Y-m-d', strtotime($request->get('ref_app_approve_date')[$key])) : null);
                    }elseif($ponCompanyOfficeData->is_approval_online == 'no'){
                        $ponCompanyOfficeData->manually_approved_op_no = $request->get('manually_approved_op_no')[$key];
                        if ($request->hasFile("approval_copy.$key")) {
                            $ponCompanyOfficeData->approval_copy = $this->uploadFile('approval_copy_', $request->file("approval_copy")[$key]);
                        }
                        $ponCompanyOfficeData->manually_approved_br_date = (!empty($request->get('manually_approved_br_date')[$key]) ? date('Y-m-d', strtotime($request->get('manually_approved_br_date')[$key])) : null);
                    }
                    $ponCompanyOfficeData->c_company_name = $request->get('c_company_name')[$key];
                    $ponCompanyOfficeData->c_origin_country_id = $request->get('c_origin_country_id')[$key];
                    $ponCompanyOfficeData->c_org_type = $request->get('c_org_type')[$key];
                    $ponCompanyOfficeData->c_flat_apart_floor = $request->get('c_flat_apart_floor')[$key];
                    $ponCompanyOfficeData->c_house_plot_holding	 = $request->get('c_house_plot_holding')[$key];
                    $ponCompanyOfficeData->c_post_zip_code	 = $request->get('c_post_zip_code')[$key];
                    $ponCompanyOfficeData->c_street	= $request->get('c_street')[$key];
                    $ponCompanyOfficeData->c_email = $request->get('c_email')[$key];
                    $ponCompanyOfficeData->c_city = $request->get('c_city')[$key];
                    $ponCompanyOfficeData->c_mobile_no = $request->get('c_mobile_no')[$key];
                    $ponCompanyOfficeData->c_state_province	 = $request->get('c_state_province')[$key];
                    $ponCompanyOfficeData->c_shareholder_percentage	= $request->get('c_shareholder_percentage')[$key];
                    $ponCompanyOfficeData->c_major_activity_brief = $request->get('c_major_activity_brief')[$key];
                    $ponCompanyOfficeData->c_district_id = isset($request->get('c_district_id')[$key])?$request->get('c_district_id')[$key]:'';
                    $ponCompanyOfficeData->c_thana_id = isset($request->get('c_thana_id')[$key])?$request->get('c_thana_id')[$key]:'';
                    $ponCompanyOfficeData->save();
                    $ponCompanyOfficeIds[] = $ponCompanyOfficeData->id;
                }
                if (!empty($ponCompanyOfficeIds)) {
                    ProjectOfficeNewCompanyOffice::where('app_id', $appData->id)->whereNotIn('id', $ponCompanyOfficeIds)->delete();
                }
            }

            // 4. Project Office Address (site office)
            if ($request->has('poa_so_division_id') && !empty(array_filter($request->poa_so_division_id))) {
                $ponSiteOfficeIds = [];
                foreach ($request->get('poa_so_division_id') as $key => $value) {
                    if (empty($request->get('pon_site_office_record_id')[$key])) {
                        $ponSiteOffice = new ProjectOfficeNewSiteOffice();
                        $ponSiteOffice->app_id = $appData->id;
                    }else{
                        $recordId = $request->get('pon_site_office_record_id')[$key];
                        $ponSiteOffice = ProjectOfficeNewSiteOffice::where('id', $recordId)->first();
                    }
                    $ponSiteOffice->poa_so_division_id = $request->get('poa_so_division_id')[$key];
                    $ponSiteOffice->poa_so_district_id = $request->get('poa_so_district_id')[$key];
                    $ponSiteOffice->poa_so_thana_id = $request->get('poa_so_thana_id')[$key];
                    $ponSiteOffice->poa_so_post_office = $request->get('poa_so_post_office')[$key];
                    $ponSiteOffice->poa_so_post_code = $request->get('poa_so_post_code')[$key];
                    $ponSiteOffice->poa_so_address = $request->get('poa_so_address')[$key];
                    $ponSiteOffice->poa_so_telephone_no = $request->get('poa_so_telephone_no')[$key];
                    $ponSiteOffice->poa_so_mobile_no = $request->get('poa_so_mobile_no')[$key];
                    $ponSiteOffice->poa_so_fax_no = $request->get('poa_so_fax_no')[$key];
                    $ponSiteOffice->poa_so_email = $request->get('poa_so_email')[$key];
                    $ponSiteOffice->site_office_name = $request->get('site_office_name')[$key];
                    $ponSiteOffice->site_office_designation = $request->get('site_office_designation')[$key];
                    $ponSiteOffice->site_office_mobile_no = $request->get('site_office_mobile_no')[$key];
                    $ponSiteOffice->site_office_email = $request->get('site_office_email')[$key];
                    if ($request->hasFile("site_office_authorize_letter.$key")) {
                        $ponSiteOffice->site_office_authorize_letter = $this->uploadFile('site_office_authorize_letter_', $request->file("site_office_authorize_letter")[$key]);
                    }
                    $ponSiteOffice->save();
                    $ponSiteOfficeIds[] = $ponSiteOffice->id;
                }
                if (!empty($ponSiteOfficeIds)) {
                    ProjectOfficeNewSiteOffice::where('app_id', $appData->id)->whereNotIn('id', $ponSiteOfficeIds)->delete();
                }
            }

            // Foreign Technical & General Details:
            if ($request->has('foreign_number') && !empty(array_filter($request->foreign_number))) {
                $ponForeignDetailIds = [];
                foreach ($request->get('foreign_number') as $key => $value) {
                    $ponForeignDetail = new ProjectOfficeNewForeignDetail();
                    $ponForeignDetail->app_id = $appData->id;
                    $ponForeignDetail->foreign_number = $request->get('foreign_number')[$key];
                    $ponForeignDetail->foreign_designation = $request->get('foreign_designation')[$key];
                    $ponForeignDetail->foreign_duration = $request->get('foreign_duration')[$key];
                    $ponForeignDetail->save();
                    $ponForeignDetailIds[] = $ponForeignDetail->id;
                }
                if (!empty($ponForeignDetailIds)) {
                    ProjectOfficeNewForeignDetail::where('app_id', $appData->id)->whereNotIn('id', $ponForeignDetailIds)->delete();
                }
            }

            // Payment info will not be updated for resubmit
            if ($processData->status_id != 2) {

                // Store payment info
                $paymentInfo = SonaliPayment::firstOrNew([
                    'app_id' => $appData->id, 'process_type_id' => $this->process_type_id,
                    'payment_config_id' => $payment_config->id
                ]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;

                //Concat Act & Payment
                $account_no = "";
                foreach ($stakeDistribution as $distribution) {
                    $account_no .= $distribution->stakeholder_ac_no . "-";
                }
                $account_numbers = rtrim($account_no, '-');
                //Concat Act & Payment End

                $paymentInfo->receiver_ac_no = $account_numbers;

                $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);

                $paymentInfo->pay_amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
                $paymentInfo->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];
                $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInfo->save();

                $appData->sf_payment_id = $paymentInfo->id;
                $appData->save();

                //Payment Details By Stakeholders
                foreach ($stakeDistribution as $distribution) {
                    $paymentDetails = PaymentDetails::firstOrNew([
                        'sp_payment_id' => $paymentInfo->id, 'payment_distribution_id' => $distribution->id
                    ]);
                    $paymentDetails->sp_payment_id = $paymentInfo->id;
                    $paymentDetails->payment_distribution_id = $distribution->id;

                    if ($distribution->fix_status == 1) {
                        $paymentDetails->pay_amount = $distribution->pay_amount;
                    } else {
                        $paymentDetails->pay_amount = $unfixed_amount_array['amounts'][$distribution->distribution_type];
                    }
                    $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                    $paymentDetails->purpose = $distribution->purpose;
                    $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                    $paymentDetails->fix_status = $distribution->fix_status;
                    $paymentDetails->distribution_type = $distribution->distribution_type;
                    $paymentDetails->save();
                }
                //Payment Details By Stakeholders End
            }

            // check company is exist
            $checkExitingCompany = CommonFunction::findCompanyNameWithoutWorkingID($request->get('local_company_name'), $company_id);
            if ($checkExitingCompany == false) {
                DB::commit();
                Session::flash('error', 'Sorry! Name of the Local company: "'.$request->get('local_company_name').'" is already exist!'.'  [PONC-1118]');

                $process_type = ProcessType::where('id', $this->process_type_id)->first(['form_id']);
                return redirect('process/office-permission-new/edit-app/'. Encryption::encodeId($appData->id).'/'.Encryption::encodeId($this->process_type_id));
            }

            /*
             * if action is submitted and application status is equal to draft
             * and have payment configuration then, generate a tracking number
             * and go to payment initiator function.
             */
            if ($request->get('actionBtn') == 'submit' && $processData->status_id == -1) {
                if(empty($processData->tracking_no)){
                    // Tracking id update
                    $prefix = 'PON-' . date("dMY") . '-';
                    UtilFunction::generateTrackingNumber($this->process_type_id, $processData->id, $prefix);
                }

                DB::commit();
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }

            // Send Email notification to user on application re-submit
            if ($request->get('actionBtn') == "resubmit" && $processData->status_id == 2) {

                //get users email and phone no according to working company id
                $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_type_name' => 'Office Permission New',
                    'process_sub_name' => $processData->process_sub_name,
                    'remarks' => ''
                ];
                CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
            }


            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error',
                    'Failed due to Application Status Conflict. Please try again later! [PONC-1023]');
            }
            DB::commit();
            return redirect('office-permission-new/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            Log::error('OPNAppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1011]');
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[PONC-1011]");
            return redirect()->back()->withInput();
        }
    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information. [PONC-975]');
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('pon_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('area_info as poa_co_division', 'poa_co_division.area_id', '=', 'apps.poa_co_division_id')
            ->leftJoin('area_info as poa_co_district', 'poa_co_district.area_id', '=', 'apps.poa_co_district_id')
            ->leftJoin('area_info as poa_co_thana', 'poa_co_thana.area_id', '=', 'apps.poa_co_thana_id')
            ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
            ->leftJoin('sp_payment as gfp', 'gfp.id', '=', 'apps.gf_payment_id')
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
                    'process_list.resend_deadline',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'ps.color',
                    'apps.*',

                    'process_type.form_url',

                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',

                    'gfp.contact_name as gfp_contact_name',
                    'gfp.contact_email as gfp_contact_email',
                    'gfp.contact_no as gfp_contact_phone',
                    'gfp.address as gfp_contact_address',
                    'gfp.pay_amount as gfp_pay_amount',
                    'gfp.tds_amount as gfp_tds_amount',
                    'gfp.vat_on_pay_amount as gfp_vat_on_pay_amount',
                    'gfp.transaction_charge_amount as gfp_transaction_charge_amount',
                    'gfp.vat_on_transaction_charge as gfp_vat_on_transaction_charge',
                    'gfp.total_amount as gfp_total_amount',
                    'gfp.payment_status as gfp_payment_status',
                    'gfp.pay_mode as gfp_pay_mode',
                    'gfp.pay_mode_code as gfp_pay_mode_code',

                    'poa_co_division.area_nm as poa_co_division_name',
                    'poa_co_district.area_nm as poa_co_district_name',
                    'poa_co_thana.area_nm as poa_co_thana_name',
            ]);

            $officeType = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $organizationTypes = POOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name',
                'asc')->lists('name', 'id');
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            // document view for pdf
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
            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo($appInfo->process_list_id);

            $ponCompanyOfficeList = ProjectOfficeNewCompanyOffice::leftJoin('po_organization_type', 'pon_companies_offices.c_org_type', '=', 'po_organization_type.id')
            ->leftJoin('country_info', 'pon_companies_offices.c_origin_country_id', '=', 'country_info.id')
            ->leftJoin('area_info as c_district', 'c_district.area_id', '=', 'pon_companies_offices.c_district_id')
            ->leftJoin('area_info as c_thana', 'c_thana.area_id', '=', 'pon_companies_offices.c_thana_id')
            ->where('app_id', $decodedAppId)
            ->get([
                'pon_companies_offices.*',
                'po_organization_type.name as c_org_type_name',
                'country_info.nicename as c_origin_country_name',
                'c_district.area_nm as c_district_name',
                'c_thana.area_nm as c_thana_name'
            ]);
            $ponSiteOfficeList = ProjectOfficeNewSiteOffice::leftJoin('area_info as poa_so_division', 'poa_so_division.area_id', '=', 'pon_site_offices.poa_so_division_id')
            ->leftJoin('area_info as poa_so_district', 'poa_so_district.area_id', '=', 'pon_site_offices.poa_so_district_id')
            ->leftJoin('area_info as poa_so_thana', 'poa_so_thana.area_id', '=', 'pon_site_offices.poa_so_thana_id')
            ->where('app_id', $decodedAppId)
            ->get([
                'pon_site_offices.*',
                'poa_so_division.area_nm as poa_so_division_name',
                'poa_so_district.area_nm as poa_so_district_name',
                'poa_so_thana.area_nm as poa_so_thana_name'
            ]);
            $ponForeignDetailList = ProjectOfficeNewForeignDetail::where('app_id', $decodedAppId)->get();

            $contents = view("ProjectOfficeNew::application-form-pdf",
                compact('process_type_id', 'appInfo', 'officeType', 'district_eng', 'countries', 'organizationTypes',
                    'thana_eng', 'metingInformation',
                    'document', 'viewMode', 'mode', 'divisions', 'ponCompanyOfficeList', 'ponSiteOfficeList', 'ponForeignDetailList'))->render();

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
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');

        } catch (\Exception $e) {
            dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            Log::error('OPNPdfForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [PONC-1115]');
            return Redirect::back()->withInput();
        }
    }

    public function uploadDocument()
    {
        return View::make('ProjectOfficeNew::ajaxUploadFile');
    }

    public function preview()
    {
        return view("ProjectOfficeNew::preview");
    }

    public function Payment(Request $request)
    {
        try {
            $appId = Encryption::decodeId($request->get('app_id'));

            // Get Payment Configuration
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 2,  // Government fee Payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0,
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);

            if (empty($payment_config)) {
                Session::flash('error', "Payment configuration not found [PONC-1456]");
                return redirect()->back()->withInput();
            }

            // Get payment distributor list
            $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
                ->where('status', 1)
                ->where('is_archive', 0)
                ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
            if ($stakeDistribution->isEmpty()) {
                Session::flash('error', "Stakeholder not found [PONC-101]");
                return redirect()->back()->withInput();
            }

            // Application Info
            $appInfo = ProcessList::leftJoin('pon_apps', 'process_list.ref_id', '=', 'pon_apps.id')->where([
                'process_list.process_type_id' => $this->process_type_id,
                'pon_apps.id' => $appId,
            ])->first(['process_list.tracking_no', 'process_list.process_type_id', 'pon_apps.approved_duration_start_date', 'pon_apps.approved_duration_end_date']);

            // Check the Govt. vat fee is allowed or not: boolean
            $vatFreeAllowed = BasicInformationController::isAllowedWithOutVAT($this->process_type_id);

            // Store payment info
            DB::beginTransaction();

            // Get SBL payment configuration
            $paymentInfo = SonaliPayment::firstOrNew([
                'app_id' => $appId, 'process_type_id' => $this->process_type_id,
                'payment_config_id' => $payment_config->id
            ]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appId;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;

            //Concat Act & Payment
            $account_no = "";
            foreach ($stakeDistribution as $distribution) {

                // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
                if ($vatFreeAllowed && in_array($distribution->distribution_type, [4,5,6])) {
                    continue;
                }

                $account_no .= $distribution->stakeholder_ac_no . "-";
            }
            $account_numbers = rtrim($account_no, '-');
            //Concat Act & Payment End

            $paymentInfo->receiver_ac_no = $account_numbers;

            $relevant_info_array = [
                'approved_duration_start_date' => $appInfo->approved_duration_start_date,
                'approved_duration_end_date' => $appInfo->approved_duration_end_date,
                'process_type_id' => $this->process_type_id,
            ];
            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config, $relevant_info_array);

            $paymentInfo->tds_amount = $unfixed_amount_array['total_tds_on_pay_amount'];
            $paymentInfo->pay_amount = ($unfixed_amount_array['total_unfixed_amount'] - $paymentInfo->tds_amount);
            // TODO : application dependent fee need to separate from payment configuration
            //$paymentInfo->pay_amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;

            $paymentInfo->vat_on_pay_amount = ($vatFreeAllowed === true) ? 0 : $unfixed_amount_array['total_vat_on_pay_amount'];
            $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->tds_amount + $paymentInfo->vat_on_pay_amount);
            $paymentInfo->contact_name = $request->get('gfp_contact_name');
            $paymentInfo->contact_email = $request->get('gfp_contact_email');
            $paymentInfo->contact_no = $request->get('gfp_contact_phone');
            $paymentInfo->address = $request->get('gfp_contact_address');
            $paymentInfo->sl_no = 1; // Always 1

            $paymentInsert = $paymentInfo->save();

            ProjectOfficeNew::where('id', $appId)->update([
                'gf_payment_id' => $paymentInfo->id
            ]);

            if ($vatFreeAllowed) {
                SonaliPaymentController::vatFreeAuditStore($paymentInfo->id, $unfixed_amount_array['total_vat_on_pay_amount']);
            }

            //Payment Details By Stakeholders
            foreach ($stakeDistribution as $distribution) {

                // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
                if ($vatFreeAllowed && in_array($distribution->distribution_type, [4,5,6])) {
                    continue;
                }

                $paymentDetails = PaymentDetails::firstOrNew([
                    'sp_payment_id' => $paymentInfo->id, 'payment_distribution_id' => $distribution->id
                ]);
                $paymentDetails->sp_payment_id = $paymentInfo->id;
                $paymentDetails->payment_distribution_id = $distribution->id;
                $paymentDetails->pay_amount = ($distribution->fix_status == 1) ? $distribution->pay_amount : $unfixed_amount_array['amounts'][$distribution->distribution_type];
                $paymentDetails->distribution_type = $distribution->distribution_type;
                $paymentDetails->receiver_ac_no = $distribution->stakeholder_ac_no;
                $paymentDetails->purpose = $distribution->purpose;
                $paymentDetails->purpose_sbl = $distribution->purpose_sbl;
                $paymentDetails->fix_status = $distribution->fix_status;
                $paymentDetails->save();
            }
            //Payment Details By Stakeholders End


            DB::commit();
            if ($request->get('actionBtn') == 'submit' && $paymentInsert) {
                return redirect('spg/initiate-multiple/' . Encryption::encodeId($paymentInfo->id));
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPNPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1025]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[PONC-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function afterPayment($payment_id)
    {
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPayment::find($payment_id);

        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
                'process_list.*'
            ]);

        //get users email and phone no according to working company id
        $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

        $appInfo = [
            'app_id' => $processData->ref_id,
            'status_id' => $processData->status_id,
            'tracking_no' => $processData->tracking_no,
            'process_type_name' => $processData->process_type_name,
            'process_type_id' => $processData->process_type_id,
            'process_supper_name' => $processData->process_supper_name,
            'process_sub_name' => $processData->process_sub_name,
            'remarks' => ''
        ];

        try {
            DB::beginTransaction();

            // 1 = Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status.'. ' [PONC-1191]');
                    return redirect('process/office-permission-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
                if (!in_array($processData->status_id, [15,32])) {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status.'. 'PONC-1192');
                    return redirect('process/office-permission-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount + $paymentInfo->tds_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount + $paymentInfo->tds_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();
            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');
            return redirect('process/project-office-new/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPNAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1181]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error :' . $e->getMessage(). '[PONC-1181]');
            return redirect('process/project-office-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPayment::find($payment_id);

        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
                'process_list.*'
            ]);

        //get users email and phone no according to working company id
        $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

        $appInfo = [
            'app_id' => $processData->ref_id,
            'status_id' => $processData->status_id,
            'process_type_id' => $processData->process_type_id,
            'tracking_no' => $processData->tracking_no,
            'process_type_name' => $processData->process_type_name,
            'process_supper_name' => $processData->process_supper_name,
            'process_sub_name' => $processData->process_sub_name,
            'remarks' => ''
        ];

        try {
            DB::beginTransaction();

            /*
             * For Service Fee Payment set tracking no.
             * if payment verification status is not equal to 1
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            if ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 1) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            elseif ($paymentInfo->is_verified == 1 && $paymentInfo->payment_category_id == 1) {

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);
                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $paymentInfo->payment_status = 1;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                Session::flash('success', 'Payment confirmation successful');
            } /*
             * For Government Fee Payment set tracking no.
             * if payment verification status is not equal to 1
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 2) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } /*
            * if payment verification status is equal to 1
            * then transfer application to 'Payment submit' status
            * Government fee payment submit
            */
            elseif ($paymentInfo->is_verified == 1 && $paymentInfo->payment_category_id == 2) {
                $paymentInfo->payment_status = 1;
                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);
                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount + $paymentInfo->tds_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount + $paymentInfo->tds_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }

            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/office-permission-new/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('OPNAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1182]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage().' [PONC-1182]');
            return redirect('process/office-permission-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function conditionalApproveStore(Request $request)
    {
        // Validation
        $rules['conditional_approved_file'] = 'required';
        $messages['conditional_approved_file'] = 'Attachment file is required';
        $this->validate($request, $rules, $messages);

        try {

            DB::beginTransaction();
            $appId = Encryption::decodeId($request->get('app_id'));

            if ($request->hasFile('conditional_approved_file')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_file_path = $request->file('conditional_approved_file');
                $file_path = trim(uniqid('BIDA_OPN-' . $appId . '-', true) . $_file_path->getClientOriginalName());
                $_file_path->move($path, $file_path);
                $conditional_approved_file = $yearMonth . $file_path;
            }

            ProjectOfficeNew::where('id', $appId)->update([
                'conditional_approved_file'     => isset($conditional_approved_file) ? $conditional_approved_file : '',
                'conditional_approved_remarks'  => $request->get('conditional_approved_remarks')
            ]);

            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $appId)
                ->where('process_type_id', $this->process_type_id)
                ->first([
                    'process_type.form_id',
                    'process_list.*'
                ]);

            if (!in_array($processData->status_id, [17, 31])) {
                Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status.'. '[PONC-1193]');
                return redirect('process/office-permission-new/edit-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
            }

            $conditional_submission_process_data = CommonFunction::getConditionFulfillSubmission($this->process_type_id);
            $processData->status_id = $conditional_submission_process_data['process_starting_status'];
            $processData->desk_id = $conditional_submission_process_data['process_starting_desk'];

            $processData->read_status = 0;
            // Applicant conditional remarks
            $processData->process_desc = $request->get('conditional_approved_remarks');
            $processData->save();

            DB::commit();
            Session::flash('success', 'Condition fulfilled successfully');
            return redirect('process/office-permission-new/view-app/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('PONConditionalApproveStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1026]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[PONC-1026]");
            return redirect()->back()->withInput();
        }
    }

    public function unfixedAmountsForPayment($payment_config, $relevant_info_array = [])
    {
        /**
         * DB Table Name: sp_payment_category
         * Payment Categories:
         * 1 = Service Fee Payment
         * 2 = Government Fee Payment
         * 3 = Government & Service Fee Payment
         * 4 = Manual Service Fee Payment
         * 5 = Manual Government Fee Payment
         * 6 = Manual Government & Service Fee Payment
         */

        $unfixed_amount_array = [
            1 => 0, // Vendor-Service-Fee
            2 => 0, // Govt-Service-Fee
            3 => 0, // Govt. Application Fee
            4 => 0, // Vendor-Vat-Fee
            5 => 0, // Govt-Vat-Fee
            6 => 0, // Govt-Vendor-Vat-Fee
            7 => 0, // TDS-Fee
        ];

        if ($payment_config->payment_category_id === 1) {

            // For service fee payment there have no unfixed distribution.

        } elseif ($payment_config->payment_category_id === 2) {
            // Govt-Vendor-Vat-Fee
            $vat_percentage = SonaliPaymentController::getGovtVendorVatPercentage();
            if (empty($vat_percentage)) {
                abort('Please, configure the value for VAT.');
            }

            // Approve Duration calculation
            $applicationInfo['approved_duration_start_date'] = $relevant_info_array['approved_duration_start_date'];
            $applicationInfo['approved_duration_end_date'] = $relevant_info_array['approved_duration_end_date'];
            $applicationInfo['process_type_id'] = $relevant_info_array['process_type_id'];

            $govt_application_fee = (int)commonFunction::getGovtFeesAmount($applicationInfo);

            $get_tds_percentage = SonaliPaymentController::getTDSpercentage();
            $total_tds_on_pay_amount = ($govt_application_fee / 100) * $get_tds_percentage;

            $unfixed_amount_array[3] = $govt_application_fee - $total_tds_on_pay_amount;
            $unfixed_amount_array[5] = ($govt_application_fee / 100) * $vat_percentage;
            $unfixed_amount_array[7] = $total_tds_on_pay_amount;

        } elseif ($payment_config->payment_category_id === 3) {

        }

        $unfixed_amount_total = 0;
        $vat_on_pay_amount_total = 0;
        foreach ($unfixed_amount_array as $key => $amount) {
            // 4 = Vendor-Vat-Fee, 5 = Govt-Vat-Fee, 6 = Govt-Vendor-Vat-Fee
            if (in_array($key, [4, 5, 6])) {
                $vat_on_pay_amount_total += $amount;
            } else {
                $unfixed_amount_total += $amount;
            }
        }

        return [
            'amounts' => $unfixed_amount_array,
            'total_unfixed_amount' => $unfixed_amount_total,
            'total_vat_on_pay_amount' => $vat_on_pay_amount_total,
            'total_tds_on_pay_amount' => $unfixed_amount_array[7],
        ];
    }

    private function uploadFile($filePrefix, $file)
    {
        if (!$file || !$file->isValid()) {
            return null;
        }
        $path = 'uploads/office_permission_new';
        $destinationPath = public_path($path);
        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        $filename = $filePrefix . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $uploaded = $file->move($destinationPath, $filename);
        if($uploaded){
            return $path.'/'. $filename;
        }
        return null;
    }

    public function loadOfficePermissionData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [PONC-1027]';
        }
        try {
            $ref_app_tracking_no = $request->ref_app_tracking_no;
            
            $processInfoData = ProcessList::where('status_id', 25)
            ->where('process_list.tracking_no', $ref_app_tracking_no)
            ->first([
                'ref_id',
                'process_type_id',
                'completed_date'
            ]);

            if(empty($processInfoData)){
                return response()->json([
                    'success' => false,
                    'message' => 'Tracking No. ['.$ref_app_tracking_no.'] was not found or has not been approved.',
                    'data' => []
                ]);
            }

            $data = [];
            if($processInfoData->process_type_id == 7){
                $offcieData = OfficePermissionExtension::find($processInfoData->ref_id);
            }else{
                $offcieData = OfficePermissionNew::find($processInfoData->ref_id);
            }

            if(empty($offcieData)){
                return response()->json([
                    'success' => false,
                    'message' => 'Office permission data not found!',
                    'data' => []
                ]);
            }


            $data['c_company_name'] = $offcieData->c_company_name;
            $data['c_origin_country_id'] = $offcieData->c_origin_country_id;
            $data['c_org_type'] = $offcieData->c_org_type;
            $data['c_flat_apart_floor'] = $offcieData->c_flat_apart_floor;
            $data['c_house_plot_holding'] = $offcieData->c_house_plot_holding;
            $data['c_post_zip_code'] = $offcieData->c_post_zip_code;
            $data['c_street'] = $offcieData->c_street;
            $data['c_email'] = $offcieData->c_email;
            $data['c_city'] = $offcieData->c_city;
            //$data['c_mobile_no'] = $offcieData->c_mobile_no;
            $data['c_state_province'] = $offcieData->c_state_province;
            $data['c_major_activity_brief'] = $offcieData->c_major_activity_brief;
            $data['ref_app_approve_date'] = !empty($processInfoData->completed_date) ? date('Y-m-d', strtotime($processInfoData->completed_date)) : '';

            return response()->json([
                'success' => true,
                'message' => 'Data load successfully.',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('ProjectOfficeNewController@loadOfficePermissionData : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [PONC-1028]');
            return response()->json([
                'success' => false,
                'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[PONC-1028]",
                'data' => []
            ]);
        }
    }

    public static function getProjectOfficeOtherData($process_type_id, $appId)
    {
        $data = [];
        if($process_type_id == 22){
            $data ['companiesOffice'] = DB::table('pon_companies_offices')
            ->leftJoin('po_organization_type', 'pon_companies_offices.c_org_type', '=', 'po_organization_type.id')
            ->leftJoin('country_info', 'pon_companies_offices.c_origin_country_id', '=', 'country_info.id')
            ->leftJoin('area_info as c_district', 'c_district.area_id', '=', 'pon_companies_offices.c_district_id')
            ->leftJoin('area_info as c_thana', 'c_thana.area_id', '=', 'pon_companies_offices.c_thana_id')
            ->where('pon_companies_offices.app_id', $appId)
            ->get([
                'pon_companies_offices.*',
                'po_organization_type.name as c_org_type_name',
                'country_info.nicename as c_origin_country_name',
                'c_district.area_nm as c_district_name',
                'c_thana.area_nm as c_thana_name'
            ]);
        }
        return $data;
    }

}// End of ProjectOfficeNewController
