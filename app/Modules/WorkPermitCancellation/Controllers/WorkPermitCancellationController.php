<?php

namespace App\Modules\WorkPermitCancellation\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\PaymentMethod;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Currencies;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDetails;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\WorkPermitCancellation\Models\WorkPermitCancellation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
//use mPDF;
use Mpdf\Mpdf;

class WorkPermitCancellationController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 5;
        $this->aclName = 'WorkPermitCancellation';
    }

    public function applicationForm(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [WPC-971]</h4>"
            ]);
        }

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = CommonFunction::getUserWorkingCompany();
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<div class='btn-center'><h4 class='custom-err-msg'>Sorry! You have no approved Basic Information application for BIDA services. [WPC-9991]</h4> <br/> <a href='/dashboard' class='btn btn-primary btn-sm'>Apply for Basic Information</a></div>"
            ]);
        }

        // Check whether the applicant company's department will get this service
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);
        if ($department_id == 4) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>Sorry! The department is not allowed to apply to this application. [WPC-1041]</h4>"
            ]);
        }

        try {
            $process_type_id = $this->process_type_id;
            // Checking the Government Fee Payment(GFP) configuration for this service
            $payment_config = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                'sp_payment_configuration.payment_category_id')
                ->where([
                    'sp_payment_configuration.process_type_id' => $this->process_type_id,
                    'sp_payment_configuration.payment_category_id' => 1, // Submission fee payment
                    'sp_payment_configuration.status' => 1,
                    'sp_payment_configuration.is_archive' => 0
                ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
            if (empty($payment_config)) {
                return response()->json([
                    'responseCode' => 1,
                    'html' => "<h4 class='custom-err-msg'> Payment Configuration not found ![WPC-10100]</h4>"
                ]);
            }
            $unfixed_amount_array = $this->unfixedAmountsForPayment($payment_config);
            $payment_config->amount = $unfixed_amount_array['total_unfixed_amount'] + $payment_config->amount;
            $payment_config->vat_on_pay_amount = $unfixed_amount_array['total_vat_on_pay_amount'];

            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $paymentMethods = ['' => 'Select One'] + PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id')->all();
//            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code',
//                'id');
            $currencies = ['' => 'Select One'] + Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code','id')->all();

            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            $attachment_key = "wpc_";
            if ($department_id == 1) {
                $attachment_key .= "cml";
            } else if ($department_id == 2) {
                $attachment_key .= "i";
            } else {
                $attachment_key .= "comm";
            }
            $query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key);
            if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
                $query->whereIn('attachment_list.business_category', [2, 3]);
            } else {
                $query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
            }
            $document = $query->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);

            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("WorkPermitCancellation::application-form",
                compact('process_type_id', 'viewMode', 'mode', 'thana_eng', 'divisions', 'district_eng', 'nationality',
                    'paymentMethods', 'currencies', 'department_id', 'document', 'payment_config', 'company_id')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WPCAppForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1005]');
            return response()->json([
                'responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()).' [WPC-1005]'
            ]);
        }
    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [WPC-1001]';
        }

        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else {
            if ($openMode == 'edit') {
                $viewMode = 'off';
                $mode = '-E-';
            }
        }

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information.  [WPC-972]</h4>"
            ]);
        }
        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('wpc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
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
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                ]);

            $company_id = $appInfo->company_id;
            // Last remarks attachment
            $remarks_attachment = DB::select(DB::raw("select * from
                                                `process_documents`
                                                where `process_type_id` = $this->process_type_id and `ref_id` = $appInfo->process_list_id and `status_id` = $appInfo->status_id
                                                and `process_hist_id` = (SELECT MAX(process_hist_id) FROM process_documents WHERE ref_id=$appInfo->process_list_id AND process_type_id=$this->process_type_id AND status_id=$appInfo->status_id)
                                                ORDER BY id ASC"
            ));

            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $paymentMethods = ['' => 'Select One'] + PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id')->all();

            $currencies = ['' => 'Select One'] + Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code','id')->all();

            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $district_eng = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm',
                'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            //  Required Documents for attachment
            $attachment_key = "wpc_";
            if ($appInfo->department_id == 1) {
                $attachment_key .= "cml";
            } else if ($appInfo->department_id == 2) {
                $attachment_key .= "i";
            } else {
                $attachment_key .= "comm";
            }

            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->join('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('attachment_type.key', $attachment_key)
                //->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            // Get application basic company information
            $basic_company_info = CommonFunction::getBasicCompanyInfo($appInfo->company_id);

            $public_html = strval(view("WorkPermitCancellation::application-form-edit",
                compact('process_type_id', 'appInfo', 'remarks_attachment', 'document', 'district_eng', 'thana_eng', 'company_id',
                    'paymentMethods', 'currencies', 'divisions', 'nationality', 'viewMode', 'mode', 'metingInformation', 'basic_company_info')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WPCViewEditApp: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>".CommonFunction::showErrorPublic($e->getMessage())."[WPC-1015]"."</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information.  [WPC-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('wpc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('divisional_office', 'divisional_office.id', '=', 'process_list.approval_center_id')
                // Reference application
                ->leftJoin('process_list as ref_process', 'ref_process.tracking_no', '=', 'apps.ref_app_tracking_no')
                ->leftJoin('process_type as ref_process_type', 'ref_process_type.id', '=', 'ref_process.process_type_id')

                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')

                ->leftJoin('country_info as app_nationality', 'app_nationality.id', '=', 'apps.applicant_nationality')
                ->leftJoin('area_info as ex_office_division', 'ex_office_division.area_id', '=', 'apps.ex_office_division_id')
                ->leftJoin('area_info as ex_office_district', 'ex_office_district.area_id', '=', 'apps.ex_office_district_id')
                ->leftJoin('area_info as ex_office_thana', 'ex_office_thana.area_id', '=', 'apps.ex_office_thana_id')

                //Compensation and Benefit
                ->leftJoin('payment_methods as basic_payment', 'basic_payment.id', '=', 'apps.basic_payment_type_id')
                ->leftJoin('currencies as basic_currency', 'basic_currency.id', '=', 'apps.basic_local_currency_id')
                ->leftJoin('payment_methods as overseas_payment', 'overseas_payment.id', '=', 'apps.overseas_payment_type_id')
                ->leftJoin('currencies as overseas_currency', 'overseas_currency.id', '=', 'apps.overseas_local_currency_id')
                ->leftJoin('payment_methods as house_payment', 'house_payment.id', '=', 'apps.house_payment_type_id')
                ->leftJoin('currencies as house_currency', 'house_currency.id', '=', 'apps.house_local_currency_id')
                ->leftJoin('payment_methods as conveyance_payment', 'conveyance_payment.id', '=', 'apps.conveyance_payment_type_id')
                ->leftJoin('currencies as conveyance_currency', 'conveyance_currency.id', '=', 'apps.conveyance_local_currency_id')
                ->leftJoin('payment_methods as medical_payment', 'medical_payment.id', '=', 'apps.medical_payment_type_id')
                ->leftJoin('currencies as medical_currency', 'medical_currency.id', '=', 'apps.medical_local_currency_id')
                ->leftJoin('payment_methods as ent_payment', 'ent_payment.id', '=', 'apps.ent_payment_type_id')
                ->leftJoin('currencies as ent_currency', 'ent_currency.id', '=', 'apps.ent_local_currency_id')
                ->leftJoin('payment_methods as bonus_payment', 'bonus_payment.id', '=', 'apps.bonus_payment_type_id')
                ->leftJoin('currencies as bonus_currency', 'bonus_currency.id', '=', 'apps.bonus_local_currency_id')
                //end

                ->where('process_list.ref_id', $decodedAppId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.department_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.submitted_at',
                    'user_desk.desk_name',
                    'ps.status_name',
                    'apps.*',

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

                    'app_nationality.nationality as applicant_nationality_name',
                    'ex_office_division.area_nm as ex_office_division_name',
                    'ex_office_district.area_nm as ex_office_district_name',
                    'ex_office_thana.area_nm as ex_office_thana_name',

                    //Compensation and Benefit
                    'basic_payment.name as basic_payment_type_name',
                    'basic_currency.code as basic_currency_code',
                    'overseas_payment.name as overseas_payment_type_name',
                    'overseas_currency.code as overseas_currency_code',
                    'house_payment.name as house_payment_type_name',
                    'house_currency.code as house_currency_code',
                    'conveyance_payment.name as conveyance_payment_type_name',
                    'conveyance_currency.code as conveyance_currency_code',
                    'medical_payment.name as medical_payment_type_name',
                    'medical_currency.code as medical_currency_code',
                    'ent_payment.name as ent_payment_type_name',
                    'ent_currency.code as ent_currency_code',
                    'bonus_payment.name as bonus_payment_type_name',
                    'bonus_currency.code as bonus_currency_code',
                    //end

                    // Reference application
                    'ref_process.ref_id as ref_application_ref_id',
                    'ref_process.process_type_id as ref_application_process_type_id',
                    'ref_process_type.type_key as ref_process_type_key',

                ]);

            //  Required Documents for attachment
            $attachment_key = "wpc_";
            if ($appInfo->department_id == 1) {
                $attachment_key .= "cml";
            } else if ($appInfo->department_id == 2) {
                $attachment_key .= "i";
            } else {
                $attachment_key .= "comm";
            }
            //document
            $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->join('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('app_documents.ref_id', $decodedAppId)
                ->where('app_documents.process_type_id', $this->process_type_id)
                ->where('attachment_type.key', $attachment_key)
                //->where('app_documents.doc_file_path', '!=', '')
                ->get([
                    'attachment_list.id',
                    'attachment_list.doc_priority',
                    'attachment_list.additional_field',
                    'app_documents.id as document_id',
                    'app_documents.doc_file_path as doc_file_path',
                    'app_documents.doc_name',
                ]);

            //get meting no and meting date ...
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            // Get application basic company information
            $basic_company_info = CommonFunction::getBasicCompanyInfo($appInfo->company_id);

            $data['ref_app_url'] = '#';
            if (!empty($appInfo->ref_app_tracking_no)) {
                $data['ref_app_url'] = url('process/'.$appInfo->ref_process_type_key.'/view-app/'.Encryption::encodeId($appInfo->ref_application_ref_id) . '/' . Encryption::encodeId($appInfo->ref_application_process_type_id));
            }

            $public_html = view("WorkPermitCancellation::application-form-view",
                compact( 'appInfo', 'document', 'viewMode', 'mode', 'metingInformation', 'basic_company_info', 'data'))->render();

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WPCViewApp ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1016]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[WPC-1016]" . "</h4>"
            ]);
        }
    }

    public function uploadDocument()
    {
        return View::make('WorkPermitCancellation::ajaxUploadFile');
    }

    public function appStore(Request $request)
    {
        //get company id form users table
        $company_id = CommonFunction::getUserWorkingCompany();

        //get department id
        $department_id = CommonFunction::getDeptIdByCompanyId($company_id);

        // get work permit new info & set session
        if ($request->get('searchWPNinfo') == 'searchWPNinfo') {
            if ($request->get('last_work_permit') == 'yes' && $request->has('ref_app_tracking_no')) {
                $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));
                $getWpApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
                    ->where('status_id', 25)->where('company_id', $company_id)
                    ->whereIn('process_type_id', [2, 3]) //2 = Work Permit New, 3 = Work Permit Extension
                    ->first(['ref_id','tracking_no']);

                if (empty($getWpApprovedRefId)) {
                    Session::flash('error', 'Sorry! approved work permit reference no. is not found! [WPNC-111]');
                    return redirect()->back();
                }
                //Get data from WPCommonPool
                $getWPNinfo = UtilFunction::checkWpCommonPoolData($getWpApprovedRefId->tracking_no, $getWpApprovedRefId->ref_id);

                if (empty($getWPNinfo)) {
                    Session::flash('error', 'Sorry! Work permit reference number not found by tracking no! [WPC-1081]');
                    return redirect()->back();
                }

                Session::put('wpneaInfo', $getWPNinfo->toArray());
                Session::put('wpneaInfo.last_work_permit', $request->get('last_work_permit'));
                Session::put('wpneaInfo.ref_app_tracking_no', $refAppTrackingNo);

                Session::flash('success', 'Successfully loaded work permit data. Please proceed to next step');
                return redirect()->back();
            }
        }

        // Clean session data
        if ($request->get('actionBtn') == 'clean_load_data') {
            Session::forget("wpneaInfo");
            Session::flash('success', 'Successfully cleaned data.');
            return redirect()->back();
        }

        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', "You have no access right! Please contact with system admin if you have any query. [WPC-974]");
        }

        // Check whether the applicant company is eligible and have approved basic information application
        if (CommonFunction::checkEligibilityAndBiApps($company_id) != 1) {
            Session::flash('error',"Sorry! You have no approved Basic Information application for BIDA services. [WPC-9992");
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
            DB::rollback();
            Session::flash('error', "Payment configuration not found [WPC-100]");
            return redirect()->back()->withInput();
        }

        // Checking the payment distributor under payment configuration
        $stakeDistribution = PaymentDistribution::where('sp_pay_config_id', $payment_config->id)
            ->where('status', 1)
            ->where('is_archive', 0)
            ->get(['id', 'stakeholder_ac_no', 'pay_amount', 'fix_status', 'purpose', 'purpose_sbl', 'distribution_type']);
        if ($stakeDistribution->isEmpty()) {
            DB::rollback();
            Session::flash('error', "Stakeholder not found [WPC-101]");
            return redirect()->back()->withInput();
        }

        // Get basic information
        $basicInfo = CommonFunction::getBasicInformationByCompanyId($company_id);
        if (empty($basicInfo)) {
            Session::flash('error', "Basic information data is not found! [WPC-105]");
            return redirect()->back()->withInput();
        }

        //  Required Documents for attachment
        $attachment_key = "wpc_";
        if ($department_id == 1) {
            $attachment_key .= "cml";
        } else if ($department_id == 2) {
            $attachment_key .= "i";
        } else {
            $attachment_key .= "comm";
        }
        $query = Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->where('attachment_type.key', $attachment_key);
        if (Auth::user()->company->business_category == 2) { // 2=government; 3=both
            $query->whereIn('attachment_list.business_category', [2, 3]);
        } else {
            $query->whereIn('attachment_list.business_category', [1, 3]); // 1=private; 3=both
        }
        $doc_row = $query->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.id', 'attachment_list.doc_name', 'attachment_list.doc_priority']);


        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['last_work_permit'] = 'required';
            $rules['manually_approved_wp_no'] = 'required_if:last_work_permit,no';
            $rules['applicant_name'] = 'required';
            $rules['applicant_nationality'] = 'required';
            $rules['applicant_pass_no'] = 'required';
            $rules['applicant_position'] = 'required';
            $rules['expiry_date_of_op'] = 'date|date_format:d-M-Y';
            $rules['issue_date_of_last_wp'] = 'date|date_format:d-M-Y';
            $rules['date_of_cancellation'] = 'required|date|date_format:d-M-Y';
            $rules['ex_office_division_id'] = 'required';
            $rules['ex_office_district_id'] = 'required';
            $rules['ex_office_thana_id'] = 'required';
            $rules['ex_office_post_code'] = 'required';
            $rules['ex_office_address'] = 'required';
            $rules['ex_office_mobile_no'] = 'required';
            $rules['ex_office_email'] = 'required';

            // attachment validation check
            if (count($doc_row) > 0) {
                foreach ($doc_row as $value) {
                    if ($value->doc_priority == 1){
                        $rules['validate_field_'.$value->id] = 'required';
                        $messages['validate_field_'.$value->id.'.required'] = $value->doc_name.', this file is required.';
                    }
                }
            }

            $rules['basic_payment_type_id'] = 'required';
            $rules['basic_local_amount'] = 'numeric|required|min:0.01';
            $rules['basic_local_currency_id'] = 'required';
            // $rules['overseas_payment_type_id'] = 'required';
            // $rules['overseas_local_amount'] = 'numeric|required';
            // $rules['house_payment_type_id'] = 'required';
            // $rules['house_local_amount'] = 'numeric|required';
            // $rules['conveyance_payment_type_id'] = 'required';
            // $rules['conveyance_local_amount'] = 'numeric|required';
            // $rules['medical_payment_type_id'] = 'required';
            // $rules['medical_local_amount'] = 'numeric|required';
            // $rules['ent_payment_type_id'] = 'required';
            // $rules['ent_local_amount'] = 'numeric|required';
            // $rules['bonus_payment_type_id'] = 'required';
            // $rules['bonus_local_amount'] = 'numeric|required';
            $rules['accept_terms'] = 'required';

            $messages['manually_approved_wp_no.required_if'] = 'Please give your manually approved work permit reference No field is required';
            $messages['applicant_name.required'] = 'Basic Instructions Name field is required';
            $messages['applicant_nationality.required'] = 'Basic Instructions Passport Number field is required';
            $messages['applicant_pass_no.required'] = 'Basic Instructions Passport Number field is required';
            $messages['applicant_position.required'] = 'Basic Instructions Position/ Designation field is required';
            $messages['expiry_date_of_op.date'] = 'Basic Instructions Expiry Date of Office Permission must be date format.';
            $messages['issue_date_of_last_wp.date'] = 'Basic Instructions Issue date of last Work Permit must be date format.';
            $messages['date_of_cancellation.required'] = 'Basic Instructions Date Of cancellation field is required';
            $messages['date_of_cancellation.date'] = 'Basic Instructions Date Of cancellation must be date format.';

            $messages['ex_office_division_id.required'] = 'Contact address of the expatriate in Bangladesh Division field is required';
            $messages['ex_office_district_id.required'] = 'Contact address of the expatriate in Bangladesh District field is required';
            $messages['ex_office_thana_id.required'] = 'Contact address of the expatriate in Bangladesh Police Station field is required';
            $messages['ex_office_post_code.required'] = 'Contact address of the expatriate in Bangladesh Post Code field is required';
            $messages['ex_office_address.required'] = 'Contact address of the expatriate in Bangladesh House, Flat/ Apartment, Road field is required';
            $messages['ex_office_mobile_no.required'] = 'Contact address of the expatriate in Bangladesh Mobile No. field is required';
            $messages['ex_office_email.required'] = 'Contact address of the expatriate in Bangladesh Email field is required';

            $messages['basic_local_amount.min'] = 'Basic salary/ Honorarium amount field is required and greater than zero(0).';

            $messages['accept_terms.required'] = 'I do here by declare that the information given above is true to the best of my knowledge and I shall be liable for any false information/ statement is given. field is required';


        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = WorkPermitCancellation::find($app_id);
                $processData = ProcessList::where([
                    'process_type_id' => $this->process_type_id, 'ref_id' => $appData->id
                ])->first();
            } else {
                $appData = new WorkPermitCancellation();
                $processData = new ProcessList();
            }
            $processData->company_id = $company_id;
            $appData->last_work_permit = $request->get('last_work_permit');

            // Company Information
            $appData->company_name = $basicInfo->company_name;
            $appData->company_name_bn = $basicInfo->company_name_bn;
            $appData->service_type = $basicInfo->service_type;
            $appData->reg_commercial_office = $basicInfo->reg_commercial_office;
            $appData->ownership_status_id = $basicInfo->ownership_status_id;
            $appData->organization_type_id = $basicInfo->organization_type_id;
            $appData->major_activities = $basicInfo->major_activities;

            // Information of Principal Promoter/ Chairman/ Managing Director/ State CEO
            $appData->ceo_country_id = $basicInfo->ceo_country_id;
            $appData->ceo_dob = $basicInfo->ceo_dob;
            $appData->ceo_passport_no = $basicInfo->ceo_passport_no;
            $appData->ceo_nid = $basicInfo->ceo_nid;
            $appData->ceo_full_name = $basicInfo->ceo_full_name;
            $appData->ceo_designation = $basicInfo->ceo_designation;
            $appData->ceo_district_id = $basicInfo->ceo_district_id;
            $appData->ceo_city = $basicInfo->ceo_city;
            $appData->ceo_state = $basicInfo->ceo_state;
            $appData->ceo_thana_id = $basicInfo->ceo_thana_id;
            $appData->ceo_post_code = $basicInfo->ceo_post_code;
            $appData->ceo_address = $basicInfo->ceo_address;
            $appData->ceo_telephone_no = $basicInfo->ceo_telephone_no;
            $appData->ceo_mobile_no = $basicInfo->ceo_mobile_no;
            $appData->ceo_fax_no = $basicInfo->ceo_fax_no;
            $appData->ceo_email = $basicInfo->ceo_email;
            $appData->ceo_father_name = $basicInfo->ceo_father_name;
            $appData->ceo_mother_name = $basicInfo->ceo_mother_name;
            $appData->ceo_spouse_name = $basicInfo->ceo_spouse_name;
            $appData->ceo_gender = $basicInfo->ceo_gender;

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

            if ($request->get('last_work_permit') == 'yes') {
                $appData->ref_app_tracking_no = trim($request->get('ref_app_tracking_no'));
                $appData->ref_app_approve_date = (!empty($request->get('ref_app_approve_date')) ? date('Y-m-d', strtotime($request->get('ref_app_approve_date'))) : null);
            } else {
                $appData->manually_approved_wp_no = $request->get('manually_approved_wp_no');
            }

            $appData->issue_date_of_last_wp = (!empty($request->get('issue_date_of_last_wp')) ? date('Y-m-d',
                strtotime($request->get('issue_date_of_last_wp'))) : null);
            $appData->applicant_name = $request->get('applicant_name');
            $appData->applicant_position = $request->get('applicant_position');
            $appData->applicant_nationality = $request->get('applicant_nationality');
            $appData->applicant_pass_no = $request->get('applicant_pass_no');
            $appData->date_of_cancellation = (!empty($request->get('date_of_cancellation')) ? date('Y-m-d',
                strtotime($request->get('date_of_cancellation'))) : null);

            //insert aslo approved desired duration for desk user (process)
            $appData->approved_effect_date = (!empty($request->get('date_of_cancellation')) ? date('Y-m-d',
                strtotime($request->get('date_of_cancellation'))) : null);
            //end
            $appData->expiry_date_of_op = (!empty($request->get('expiry_date_of_op')) ? date('Y-m-d',
                strtotime($request->get('expiry_date_of_op'))) : null);
            $appData->applicant_remarks = $request->get('applicant_remarks');

            $appData->ex_office_division_id = $request->get('ex_office_division_id');
            $appData->ex_office_district_id = $request->get('ex_office_district_id');
            $appData->ex_office_thana_id = $request->get('ex_office_thana_id');
            $appData->ex_office_post_office = $request->get('ex_office_post_office');
            $appData->ex_office_post_code = $request->get('ex_office_post_code');
            $appData->ex_office_address = $request->get('ex_office_address');
            $appData->ex_office_telephone_no = $request->get('ex_office_telephone_no');
            $appData->ex_office_mobile_no = $request->get('ex_office_mobile_no');
            $appData->ex_office_fax_no = $request->get('ex_office_fax_no');
            $appData->ex_office_email = $request->get('ex_office_email');

            $appData->basic_payment_type_id = $request->get('basic_payment_type_id');
            $appData->basic_local_amount = empty($request->get('basic_local_amount')) ? null : $request->get('basic_local_amount');
            $appData->basic_local_currency_id = $request->get('basic_local_currency_id');
            $appData->overseas_payment_type_id = $request->get('overseas_payment_type_id');
            $appData->overseas_local_amount = empty($request->get('overseas_local_amount')) ? null : $request->get('overseas_local_amount');
            $appData->overseas_local_currency_id = $request->get('overseas_local_currency_id');
            $appData->house_payment_type_id = $request->get('house_payment_type_id');
            $appData->house_local_amount = empty($request->get('house_local_amount')) ? null : $request->get('house_local_amount');
            $appData->house_local_currency_id = $request->get('house_local_currency_id');
            $appData->conveyance_payment_type_id = $request->get('conveyance_payment_type_id');
            $appData->conveyance_local_amount = empty($request->get('conveyance_local_amount')) ? null : $request->get('conveyance_local_amount');
            $appData->conveyance_local_currency_id = $request->get('conveyance_local_currency_id');
            $appData->medical_payment_type_id = $request->get('medical_payment_type_id');
            $appData->medical_local_amount = empty($request->get('medical_local_amount')) ? null : $request->get('medical_local_amount');
            $appData->medical_local_currency_id = $request->get('medical_local_currency_id');
            $appData->ent_payment_type_id = $request->get('ent_payment_type_id');
            $appData->ent_local_amount = empty($request->get('ent_local_amount')) ? null : $request->get('ent_local_amount');
            $appData->ent_local_currency_id = $request->get('ent_local_currency_id');
            $appData->bonus_payment_type_id = $request->get('bonus_payment_type_id');
            $appData->bonus_local_amount = empty($request->get('bonus_local_amount')) ? null : $request->get('bonus_local_amount');
            $appData->bonus_local_currency_id = $request->get('bonus_local_currency_id');
            $appData->other_benefits = $request->get('other_benefits');

            //Authorized Person Information
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
            } else {
                $resubmission_data = CommonFunction::getReSubmissionJson($this->process_type_id, $app_id);
                $processData->status_id = $resubmission_data['process_starting_status'];
                $processData->desk_id = $resubmission_data['process_starting_desk'];
                $processData->resend_deadline = null;
                // For shortfall application re-submission
                if ($processData->status_id == 5) { 
                    $processData->process_desc = 'Re-submitted form applicant';
                } 
            }

            $appData->save();

            /*
             * Department and Sub-department specification for application processing
             */
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => $department_id,
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            //$processData->read_status = 0;
            $processData->approval_center_id = UtilFunction::getApprovalCenterId($company_id);

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
//            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
//            $jsonData['Department'] = CommonFunction::getDepartmentNameById($processData->department_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            if (count($doc_row) > 0) {
                foreach ($doc_row as $docs) {
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
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;

                //Concat Act & Payment
                $account_no = "";
                foreach ($stakeDistribution as $distribution) {
                    $account_no .= $distribution->stakeholder_ac_no."-";
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

            Session::forget("wpneaInfo");

            if ($request->get('actionBtn') == 'submit' && $processData->status_id == -1) {
                if (empty($processData->tracking_no)) {
                    $prefix = 'WPC-'.date("dMY").'-';
                    UtilFunction::generateTrackingNumber($this->process_type_id, $processData->id, $prefix);
                }
                DB::commit();
                return redirect('spg/initiate-multiple/'.Encryption::encodeId($paymentInfo->id));
            }

            // Send Email notification to user on application re-submit
            if ($request->get('actionBtn') == "resubmit" && $processData->status_id == 2) {

                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=',
                    'process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_list.*'
                    ]);

                //get users email and phone no according to working company id
                $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($company_id);

                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_sub_name' => $processData->process_sub_name,
                    'process_type_name' => 'Work Permit Cancellation',
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
                    'Failed due to Application Status Conflict. Please try again later! [WPC-1023]');
            }
            DB::commit();
            return redirect('work-permit-cancellation/list/'.Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WPCAppStore: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage())."[WPC-1011]");
            return redirect()->back()->withInput();
        }
    }

    public function preview()
    {
        return view("WorkPermitCancellation::preview");
    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.  [WPC-975]');
        }

        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            // get application,process info
            $appInfo = ProcessList::leftJoin('wpc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
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
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as sfp_pay_mode',
                    'sfp.pay_mode_code as sfp_pay_mode_code',
                    'sfp.total_amount as sfp_total_amount',
                ]);

            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')
                ->where('iso', '!=', 'BD')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $paymentMethods = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code',
                'id');
            $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm',
                    'asc')->lists('area_nm', 'area_id')->all();
            $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana_eng = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $document = DocInfo::where('process_type_id', $this->process_type_id)->where('ctg_id',
                0)->orderBy('order')->get();

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
            $metingInformation = CommonFunction::getMeetingInfo( $appInfo->process_list_id);

            $contents = view("WorkPermitCancellation::application-form-pdf",
                compact('process_type_id', 'appInfo', 'divisions', 'district_eng', 'nationality', 'districts',
                    'currencies', 'thana_eng', 'thana_eng', 'metingInformation',
                    'paymentMethods', 'document', 'viewMode', 'mode'))->render();

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
            $mpdf->Output($appInfo->tracking_no.'.pdf', 'I');

        } catch (\Exception $e) {
            Log::error('WPCPdfView: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1115]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [WPC-1115]');
            return Redirect::back()->withInput();
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
            'process_type_id' => $processData->process_type_id,
            'tracking_no' => $processData->tracking_no,
            'process_type_name' => $processData->process_type_name,
            'process_supper_name' => $processData->process_supper_name,
            'process_sub_name' => $processData->process_sub_name,
            'remarks' => ''
        ];

        try {
            DB::beginTransaction();
            // 1 = Service Fee Payment
            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [WPC-912]');
                    return redirect('process/work-permit-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
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
                if ($processData->status_id != '15') {
                    Session::flash('error', 'This is an invalid status, it\'s not possible to get the next status. [WPC-913]');
                    return redirect('process/work-permit-cancellation/view-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
                }

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            }
            $processData->save();
            DB::commit();
            Session::flash('success', 'Your application has been successfully submitted after payment. You will receive a confirmation email soon.');
            return redirect('process/work-permit-cancellation/view-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WPCAfterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1051]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : '.$e->getMessage().' [WPC-1051]');
            return redirect('process/work-permit-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
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
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
//                $processData->status_id = 1; // Submitted
//                $processData->desk_id = 1;

                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                Session::flash('success', 'Payment Confirm successfully');
            } /*
             * if payment status is not equal '1'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('process/work-permit-cancellation/view-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('WPCAfterCounterPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPC-1052]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : '.$e->getMessage().' [WPC-1052]');
            return redirect('process/work-permit-cancellation/edit-app/'.Encryption::encodeId($processData->ref_id).'/'.Encryption::encodeId($processData->process_type_id));
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
        ];

        if ($payment_config->payment_category_id === 1) {

            // For service fee payment there have no unfixed distribution.

        } elseif ($payment_config->payment_category_id === 2) {
            // Govt-Vendor-Vat-Fee
            $vat_percentage = SonaliPaymentController::getGovtVendorVatPercentage();
            if (empty($vat_percentage)) {
                abort('Please, configure the value for VAT.');
            }
            $unfixed_amount_array[5] = ($payment_config->amount / 100) * $vat_percentage;

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
        ];
    }
}
