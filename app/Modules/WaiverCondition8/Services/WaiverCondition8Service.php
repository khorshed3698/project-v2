<?php

namespace App\Modules\WaiverCondition8\Services;

use App\Libraries\ACL;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\Users\Models\Countries;
use App\Modules\OfficePermissionNew\Models\OPOrganizationType;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\OfficePermissionNew\Models\OPOfficeType;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Currencies;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Modules\WaiverCondition7\Models\WaiverCondition7;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\Settings\Models\Attachment;




class WaiverCondition8Service
{
    protected $process_type_id = 20;
    protected $aclName = 'WaiverCondition8';

    public function validateRequestAccess($request, $mode, $ajaxErrNo, $aclErrNo)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. ' . [$ajaxErrNo];
        }

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [$aclErrNo]</h4>"
            ]);
        }
        
        return true;
    }

    public function getPaymentInfo($payment_category_id) {

        return PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
                    'sp_payment_configuration.payment_category_id')
                    ->where([
                        'sp_payment_configuration.process_type_id' => $this->process_type_id,
                        'sp_payment_configuration.payment_category_id' => $payment_category_id, 
                        'sp_payment_configuration.status' => 1,
                        'sp_payment_configuration.is_archive' => 0
                    ])->first(['sp_payment_configuration.*', 'sp_payment_category.name']);
    
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

    public static function getData($dataType) {
        switch ($dataType) {
            case 'countries':
                $data = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
                break;
            case 'organizationTypes':
                $data = OPOrganizationType::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name', 'id');
                break;
            case 'divisions':
                $data = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'district_eng':
                $data = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'thana_eng':
                $data = AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                break;
            case 'officeType':
                $data = OPOfficeType::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name', 'id');
                break;
            case 'currencies':
                $data = Currencies::orderBy('code')->where('is_archive', 0)->where('is_active', 1)->lists('code', 'id');
                break;
            default:
                $data = '';
        }
            return $data;
    }


    public function getAppEditInfo($process_type_id, $decodedAppId)
    {
        return ProcessList::leftJoin('waiver_con_8_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                ]);
    }

    public function getAppViewInfo($process_type_id, $decodedAppId)
    {
        return ProcessList::leftJoin('waiver_con_8_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })

                // Reference application
                ->leftJoin('process_list as ref_process', 'ref_process.tracking_no', '=', 'apps.ref_app_tracking_no')
                ->leftJoin('process_type as ref_process_type', 'ref_process_type.id', '=', 'ref_process.process_type_id')

                ->leftJoin('op_office_type', 'op_office_type.id', '=', 'apps.office_type')
                ->leftJoin('country_info as principle_office', 'principle_office.id', '=', 'apps.c_origin_country_id')
                ->leftJoin('op_organization_type', 'op_organization_type.id', '=', 'apps.c_org_type')
                ->leftJoin('sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->leftJoin('area_info as ex_office_division', 'ex_office_division.area_id', '=', 'apps.ex_office_division_id')
                ->leftJoin('area_info as ex_office_district', 'ex_office_district.area_id', '=', 'apps.ex_office_district_id')
                ->leftJoin('area_info as ex_office_thana', 'ex_office_thana.area_id', '=', 'apps.ex_office_thana_id')
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

                    'op_office_type.name as office_type_name',
                    'principle_office.nicename as principle_office_name',
                    'op_organization_type.name as op_org_name',

                    'ex_office_division.area_nm as ex_office_division_name',
                    'ex_office_district.area_nm as ex_office_district_name',
                    'ex_office_thana.area_nm as ex_office_thana_name',
                    // payment info
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

                    // Reference application
                    'ref_process.ref_id as ref_application_ref_id',
                    'ref_process.process_type_id as ref_application_process_type_id',
                    'ref_process_type.type_key as ref_process_type_key'
            ]);
    }

    public static function searchWaiverInfo($request, $company_id)
    {
        if (!$request->has('ref_app_tracking_no')) {
            Session::flash('error', 'Missing required parameter: ref_app_tracking_no [WAIVER8-1080]');
            return redirect()->back();
        }

        $refAppTrackingNo = trim($request->get('ref_app_tracking_no'));

        $getWVRApprovedRefId = ProcessList::where('tracking_no', $refAppTrackingNo)
            ->where('status_id', 25)
            ->where('company_id', $company_id)
            ->whereIn('process_type_id', [19])
            ->first(['ref_id']);

        if (empty($getWVRApprovedRefId)) {
            Session::flash('error', 'Sorry! approved waiver condition 7 permission reference no. is not found or not allowed! [WAIVER8-1081]');
            return redirect()->back();
        }

        //Get data from Waiver Condition 7 Permission
        $getWVRinfo = WaiverCondition7::where('id', $getWVRApprovedRefId->ref_id)->first();

        if (empty($getWVRinfo)) {
            Session::flash('error', 'Sorry! waiver condition 7 permission reference number not found by tracking no!  [WAIVER8-1081].' . '<br/>' . Session::get('error'));
            return redirect()->back();
        }

        // Attachment
        $waiver7Doc = AppDocuments::where('ref_id', $getWVRinfo->id)
            ->where('process_type_id', 19)
            ->get();

        Session::put('waiver', $getWVRinfo->toArray());
        Session::put('waiver7Doc', $waiver7Doc);
        Session::put('waiver.ref_wvr_app_tracking_no', $request->get('ref_app_tracking_no'));
        Session::put('waiver.ref_office_app_approved_date', $getWVRinfo->ref_app_approve_date);

        Session::flash('success', 'Successfully loaded waiver condition 7 permission data. Please proceed to next step');
        return redirect()->back();
    }

    public function getAttachment($attachment_key)
    {
        return Attachment::leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
                ->where('attachment_list.status', 1)
                ->where('attachment_list.is_archive', 0)
                ->orderBy('attachment_list.order')
                ->get(['attachment_list.*']);
    }
    
    public function getDocument($attachment_key, $decodedAppId)
    {
        return AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
                ->where('attachment_type.key', $attachment_key)
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
    }
    
    public function getApprovedWaiver7Documents($trackingNo) {
        $getWVR7ApprovedRefId = ProcessList::where('tracking_no', $trackingNo)
                                        ->where('status_id', 25)
                                        ->whereIn('process_type_id', [19])
                                        ->first(['ref_id']);
    
        if ($getWVR7ApprovedRefId) {
            return AppDocuments::where('ref_id', $getWVR7ApprovedRefId->ref_id)
                                ->where('process_type_id', 19)
                                ->get();
        }
    
        return null;
    }

    
}