<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\LicenceApplication\Models\EA_OrganizationType;
use App\Modules\LicenceApplication\Models\Etin\Etin;
use App\Modules\LicenceApplication\Models\Etin\EtinCertificate;
use App\Modules\LicenceApplication\Models\Etin\EtinJurisdictionList;
use App\Modules\LicenceApplication\Models\Etin\EtinNbrPayment;
use App\Modules\LicenceApplication\Models\Etin\EtinRecordNbr;
use App\Modules\LicenceApplication\Models\Etin\EtinRequest;
use App\Modules\LicenceApplication\Models\Etin\MainSourceOfIncome;
use App\Modules\LicenceApplication\Models\Etin\NbrAreaInfo;
use App\Modules\LicenceApplication\Models\Etin\TaxpayerStatus;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use mPDF;
use Validator;

class EtinController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 106;
        $this->aclName = 'E-tin';
    }


    /*
     * Application store
     */
    public function appStore(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        $company_id = Auth::user()->company_ids;
        $rules = [];
        $messages = [];

        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();

            DB::beginTransaction();

            if ($request->get('app_id')) {
                $appData = Etin::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new Etin();
                $processData = new ProcessList();
            }

            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first([
                    'ea_apps.ceo_father_name',
                    'ea_apps.ceo_mother_name',
                    'ea_apps.ceo_passport_no',
                ]);

            if ($request->get('action') == 'only-get-tin') {

                $etinApiSubmit = EtinApiNBRSubmitController::formateSaveDataForApiSubmit($appData, $basicAppInfo, $this->process_type_id);

                DB::commit();

                if ($etinApiSubmit['success']) {
                    return response()->json([
                        'responseCode' => 1,
                        'app_id' => Encryption::encodeId($appData->id),
                        'message' => 'Please wait for Etin Response',
                    ]);
                } else {
                    return response()->json([
                        'responseCode' => 0,
                        'message' => $etinApiSubmit['message'],
                    ]);
                }
            }

            $appData->taxpayer_status = $request->get('taxpayer_status');
            $appData->organization_type_id = $request->get('organization_type_id');
            $appData->reg_type = $request->get('reg_type');

            if ($appData->reg_type == '1') {

                $appData->main_source_income = $request->get('main_source_income');
                $appData->company_id = $request->get('company_id');
                $appData->main_source_income_location = $request->get('main_source_income_location');
                $appData->juri_sub_list_name = $request->get('juri_sub_list_name');

            } else if ($appData->reg_type == '2') {
                $appData->existing_tin_no = $request->get('existing_tin_no');

            }

            $appData->company_name = $request->get('company_name');
            $appData->incorporation_certificate_number = $request->get('incorporation_certificate_number');
            $appData->incorporation_certificate_date = (!empty($request->get('incorporation_certificate_date')) ? CommonFunction::changeDateFormat($request->get('incorporation_certificate_date'), true) : '');
            $appData->ceo_designation = $request->get('ceo_designation');
            $appData->ceo_full_name = $request->get('ceo_full_name');
            $appData->ceo_mobile_no = $request->get('ceo_mobile_no');
            $appData->ceo_fax_no = $request->get('ceo_fax_no');
            $appData->ceo_email = $request->get('ceo_email');
            $appData->ceo_country_id = $request->get('ceo_country_id');
            $appData->ceo_thana_id = $request->get('ceo_thana_id');
            $appData->ceo_district_id = $request->get('ceo_district_id');
            $appData->ceo_post_code = $request->get('ceo_post_code');
            $appData->ceo_address = $request->get('ceo_address');
            $appData->reg_office_country_id = $request->get('reg_office_country_id');
            $appData->office_district_id = $request->get('office_district_id');
            $appData->office_thana_id = $request->get('office_thana_id');
            $appData->office_post_code = $request->get('office_post_code');
            $appData->office_address = $request->get('office_address');
            $appData->other_address_country_id = $request->get('other_address_country_id');
            $appData->other_address_thana_id = $request->get('other_address_thana_id');
            $appData->other_address_district_id = $request->get('other_address_district_id');
            $appData->other_address_post_code = $request->get('other_address_post_code');
            $appData->other_address = $request->get('other_address');


            if ($request->get('actionBtn') == "draft") {
                $appData->is_archive = 1;
            } else {
                $appData->is_archive = 0;
            }

            $processData->department_id = CommonFunction::getDeptIdByCompanyId($company_id);

            if ($request->get('actionBtn') == "draft" && $appData->status_id != 2) {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }

            $appData->save();

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->read_status = 0;

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();
            if (strlen(trim($processData->tracking_no)) == 0) {
                $trackingPrefix = 'ETIN-' . date("dMY") . '-';

                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
            }



            DB::commit();


            if ($request->get('actionBtn') == 'Submit') {

                $alreadyPayment = SonaliPaymentStackHolders::where('app_id', $appData->id)
                    ->where('process_type_id', $this->process_type_id)
                    ->where('payment_status', 1)
                    ->orderBy('id', 'DESC')
                    ->first(['id']);

                if (count($alreadyPayment) == 0) {

                    $trackingid = CommonFunction::getTrackingNoByProcessId($processData->id);

                    $requestJson = array(
                        "applicant_address" => $appData->office_address,
                        "applicant_district_name" => NbrAreaInfo::getAreaName($appData->office_district_id, 2),
                        "applicant_email" => $appData->ceo_email,
                        "applicant_organization" => $appData->company_id,
                        "applicant_phone" => $appData->ceo_mobile_no,
                        "applicant_position" => $appData->ceo_designation,
                        "applied_by" => $appData->ceo_full_name,
                        "company_name" => $appData->company_name,
                        "entity_type" => $this->getOrganizationTypeName($appData->organization_type_id),
                        "oss_application_id" => $trackingid,
                        "rjsc_off_dist_name" => NbrAreaInfo::getAreaName($appData->office_district_id, 2)
                    );

                    $etinRecordNbr = EtinRecordNbr::create([
                        'status' => 0,
                        'request' => json_encode($requestJson),
                        'process_type_id' => $this->process_type_id,
                        "tracking_no" => $trackingid,
                        'application_id' => $appData->id,
                        'payment_info' => json_encode([]),
                    ]);
                    $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
                    return $this->spPaymentSubmit($appData->id, $etinRecordNbr,$tracking_no);

                } else {

                    $etinApiSubmit = EtinApiNBRSubmitController::formateSaveDataForApiSubmit($appData, $basicAppInfo, $this->process_type_id);

                    if ($etinApiSubmit['success']) {

                        return response()->json([
                            'responseCode' => 1,
                            'app_id' => Encryption::encodeId($appData->id),
                            'message' => 'Please wait for Etin Response',
                        ]);
                    } else {
                        return response()->json([
                            'responseCode' => 0,
                            'message' => $etinApiSubmit['message'],
                        ]);

                    }
                }

            }

            if ($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {

                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_list.*'
                    ]);

                $applicantEmailPhone = Users::where('id', Auth::user()->id)
                    ->get(['user_email', 'user_phone']);

                $appInfo = [
                    'app_id' => $processData->ref_id,
                    'status_id' => $processData->status_id,
                    'process_type_id' => $processData->process_type_id,
                    'tracking_no' => $processData->tracking_no,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_sub_name' => $processData->process_sub_name,
                    'process_type_name' => 'Etin',
                    'remarks' => ''
                ];

                if ($processData->status_id == 2) CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
            }

            if ($processData->status_id == -1) {

                Session::flash('success', 'Successfully Added/Updated the Application!');
                return redirect('licence-applications/individual-licence');

            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted  !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [ETC-1061]');
//                return response()->json([
//                    'responseCode' => 2, 'type' => 'error', 'title' => 'Oops ...',
//                    'message' => 'Failed due to Application Status Conflict. Please try again later! [BI-1023]',
//                ]);
            }

            if ($request->get('actionBtn') == "draft"){
                Session::flash('success', 'Successfully Drafted the Application!');
                return redirect('licence-applications/individual-licence');
            }
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            DB::rollback();

            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1062]');
            Log::error('AppStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1062]');
            return redirect('licence-applications/individual-licence');

        }
    }


    /*
     * Application edit or view
     */

    public function getDistrictByDivision(Request $request)
    {
        try {

            $division_id = $request->get('divisionId');
            $districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
            $data = ['responseCode' => 1, 'data' => $districts];
            return response()->json($data);

        } catch (\Exception $e) {

            Log::error('GetDistrictByDivision : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1063]');

            return response()->json(['responseCode' => 0, 'data' => []]);
        }
    }


    public function appForm(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        try {

            if (!$request->ajax()) {
//                return 'Sorry! this is a request without proper way.';
            }

            $companyIds = CommonFunction::getUserCompanyWithZero();

            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {

                Session::flash('error', 'Sorry! You have no approved Basic Information application.');
                return redirect()->back();
            }


            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();

            $districts = ['' => 'Select Districts'] + NbrAreaInfo::where('area_type', 2)->orderBy('name', 'asc')->lists('name', 'nbr_id')->all();

            $thana = ['' => 'Select Thana'] + NbrAreaInfo::where('area_type', 3)->orderby('name', 'asc')->lists('name', 'nbr_id')->all();

            $viewMode = 'off';

            $mode = '-A-';

            $mainSourceIncome = ['' => 'Select One'] + MainSourceOfIncome::where('is_archive', 0)->where('is_approved', 1)->orderBy('main_source_income')->lists('main_source_income', 'reg_juri_type_no')->all();

            $taxpayerStatus = ['' => 'Select One'] + TaxpayerStatus::where('is_archive', 0)->where('is_approved', 1)->orderBy('taxpayer_status')->lists('taxpayer_status', 'id')->all();

            $registrationType = ['' => 'Select one', '1' => 'New registration'];

            $designationAsEtin = [
                '' => 'Select One',
                '1' => 'Managing Director',
                '2' => 'Chief Executive Officer',
                '3' => 'Chief Operating Officer',
                '4' => 'Chief Financial Officer ',
                '5' => 'Chief Accountant',
                '6' => 'Any Person responsible for Company Affairs',
                '13' => 'Manager',
                '14' => 'Secretary',
                '15' => 'Treasurer',
                '16' => 'Agent',
                '17' => 'Accountant',
            ];

            $public_html = strval(view("LicenceApplication::etin.application-form", compact('districts', 'thana',
                'eaOrganizationType', 'viewMode', 'mode', 'basicAppInfo', 'registrationType', 'companies', 'mainSourceIncome', 'taxpayerStatus', 'designationAsEtin')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

//            return view("LicenceApplication::etin.application-form", compact('districts', 'thana',
//                'eaOrganizationType', 'viewMode', 'mode', 'basicAppInfo', 'registrationType', 'companies', 'mainSourceIncome', 'taxpayerStatus', 'designationAsEtin'));
        } catch (\Exception $e) {

            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }


    public function appFormEditView($applicationId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
//            return 'Sorry! this is a request without proper way.';
        }

        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else if ($openMode == 'edit') {
            $viewMode = 'off';
            $mode = '-E-';
        }

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }

        try {

            $applicationId = Encryption::decodeId($applicationId);

            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('etin_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('etin_request', function ($join) use ($process_type_id) {
                    $join->on('etin_request.ref_id', '=', 'apps.id');
                    $join->on('etin_request.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.gf_payment_id')
                ->where('process_list.ref_id', $applicationId)
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
                    'sfp.id as sfp_id',
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'transaction_charge_amount as sfp_transaction_charge_amount',
                    'vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'total_amount as sfp_total_amount',
                    'sfp.payment_status as sfp_payment_status',
                    'etin_request.status as tin_request_status',
                    'apps.*',
                ]);

            $certificateId = null;

            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();

            $districts = ['' => 'Select Districts'] + NbrAreaInfo::where('area_type', 2)->orderBy('name', 'asc')->lists('name', 'nbr_id')->all();

            $thana = ['' => 'Select Thana'] + NbrAreaInfo::where('area_type', 3)->orderby('name', 'asc')->lists('name', 'nbr_id')->all();

            $companiesOnLocation = EtinJurisdictionList::where('etin_district_id', $appInfo->main_source_income_location)
                ->where('reg_juri_type_no', $appInfo->main_source_income)
                ->where('juri_select_type_no', 5)
                ->get([
                    'juri_select_list_no as id',
                    'juri_select_list_value as value',
                    'Juri_sub_list_name_status as required_status'
                ]);

            $selectedJuriDiction = EtinJurisdictionList::where('etin_district_id', $appInfo->main_source_income_location)
                ->where('reg_juri_type_no', $appInfo->main_source_income)
                ->where('juri_select_type_no', 5)
                ->where('juri_select_list_no', $appInfo->company_id)
                ->first(['Juri_sub_list_name_status']);

            $mainSourceIncome = ['' => 'Select One'] + MainSourceOfIncome::where('is_archive', 0)->where('is_approved', 1)->orderBy('main_source_income')->lists('main_source_income', 'reg_juri_type_no')->all();

            $taxpayerStatus = ['' => 'Select One'] + TaxpayerStatus::where('is_archive', 0)->where('is_approved', 1)->orderBy('taxpayer_status')->lists('taxpayer_status', 'id')->all();

            $registrationType = ['' => 'Select one', '1' => 'New registration'];

            $nbrDistricts = NbrAreaInfo::where('area_type', 2)->get(['name', 'area_info_id', 'nbr_id']);

            $designationAsEtin = [
                '' => 'Select One',
                '1' => 'Managing Director',
                '2' => 'Chief Executive Officer',
                '3' => 'Chief Operating Officer',
                '4' => 'Chief Financial Officer ',
                '5' => 'Chief Accountant',
                '6' => 'Any Person responsible for Company Affairs',
                '13' => 'Manager',
                '14' => 'Secretary',
                '15' => 'Treasurer',
                '16' => 'Agent',
                '17' => 'Accountant',
            ];


            $certificate = EtinCertificate::where('ref_id', $appInfo->id)->where('process_type_id', $process_type_id)->whereNotNull('response')->first(['id', 'response']);

            if (isset($certificate->id) && (!empty($certificate->response) && $certificate->response != null)) {
                $certificateId = $certificate->id;
            }

            $alreadyPayment = SonaliPaymentStackHolders::where('app_id', $applicationId)
                ->where('process_type_id', $this->process_type_id)
                ->where('payment_status', 1)
                ->orderBy('id', 'DESC')
                ->first(['id']);

            $alreadyPaymentCount = count($alreadyPayment);

            $etinRequest = EtinRequest::where('ref_id', $applicationId)
                ->where('process_type_id', $this->process_type_id)
                ->first(['status']);

            $etinRequestStatus = isset($etinRequest->status) ? $etinRequest->status : null;

            $redirectFromPaymentFlag = ( ($alreadyPaymentCount > 0) && ($certificateId == null) && $etinRequestStatus == 0) ? 1 : 0;

            $public_html = strval(view("LicenceApplication::etin.application-form-edit",
                compact('appInfo', 'countries', 'viewMode', 'mode', 'eaOrganizationType',
                    'districts', 'taxpayerStatus', 'mainSourceIncome', 'registrationType', 'thana',
                    'designationAsEtin', 'nbrDistricts', 'companiesOnLocation', 'selectedJuriDiction',
                    'certificateId', 'alreadyPaymentCount', 'redirectFromPaymentFlag')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {

            Log::error('AppFormEditView : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1065]');

            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1065]"]);
        }
    }

    public function appFormPdf($appId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {

            $applicationId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            $appInfo = ProcessList::leftJoin('etin_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
//                ->leftJoin('sector_info', 'sector_info.id', '=', 'apps.business_sector_id')
//                ->leftJoin('sec_sub_sector_list', 'sec_sub_sector_list.id', '=', 'apps.business_sub_sector_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->where('process_list.ref_id', $applicationId)
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
                    'apps.*'
                ]);


            $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
            $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');
            $countries = ['' => 'Select One'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();

            $companies = ['' => 'Select One'] + CompanyInfo::where('is_approved', 1)->where('company_status', 1)->orderBy('company_name')->lists('company_name', 'id')->all();
            $mainSourceIncome = ['' => 'Select One'] + MainSourceOfIncome::where('is_archive', 0)->where('is_approved', 1)->orderBy('main_source_income')->lists('main_source_income', 'id')->all();
            $taxpayerStatus = ['' => 'Select One'] + TaxpayerStatus::where('is_archive', 0)->where('is_approved', 1)->orderBy('taxpayer_status')->lists('taxpayer_status', 'id')->all();
            $registrationType = ['' => 'Select one', '1' => 'New registration'];


            $contents = view("LicenceApplication::etin.application-form-pdf",
                compact('basicAppInfo', 'appInfo', 'countries', 'viewMode',
                    'mode', 'eaOrganizationType',
                    'districts',
                    'companies', 'taxpayerStatus', 'mainSourceIncome', 'registrationType', 'thana'))->render();


            $mpdf = new mPDF(
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
            );

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
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1005]');
            return Redirect()->back()->withInput();
        }
    }

    public function UpdateAd($ref_id, $requestData)
    {
        try {

            Etin::where('id', $ref_id)->update([
                'account_number' => $requestData['acc_number'],
                'amount' => $requestData['amount']
            ]);
            return true;

        } catch (\Exception $e) {

            Log::error('Etin-UpdateAd : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1075]');
            return false;
        }

    }


    public function getCompanyList(Request $request)
    {
        try {

            $eTinJuriList = EtinJurisdictionList::where('etin_district_id', $request->main_souce_income_location)
                ->where('reg_juri_type_no', $request->main_souce_income)
                ->where('juri_select_type_no', 5)
                ->get([
                    'juri_select_list_no as id',
                    'juri_select_list_value as value',
                    'Juri_sub_list_name_status as required_status'
                ]);

            return response()->json([
                'responseCode' => 1,
                'data' => $eTinJuriList,
            ]);

        } catch (\Exception $e) {

            Log::error('GetCompanyList : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1066]');
            return response()->json([
                'responseCode' => 0,
                'data' => null,
                'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1007]",
            ]);
        }
    }


    public function checkApiRequestStatus(Request $request)
    {
        try {

            $etinId = Encryption::decodeId($request->app_id);
            $status = 0;
            $id = 0;

            $etinRequest = EtinRequest::where('ref_id', $etinId)
                ->where('process_type_id', $this->process_type_id)
                ->first(['status', 'response']);

            if($etinRequest == null){

                return response()->json([
                    'responseCode' => -2,
                    'message' => "Sorry Can not find application [ETC-1087]",
                ]);
            }
            if ($etinRequest != null && ($etinRequest->status == 0 || $etinRequest->status == -1)) {
                return response()->json([
                    'responseCode' => 0,
                    'row_id' => 0,
                    'message' => 'Application submitting to NBR'
                ]);
            }

            if ($etinRequest != null) {

                if ($etinRequest->status == 1) {
                    $etinCertificate = EtinCertificate::where('ref_id', $etinId)
                        ->where('process_type_id', $this->process_type_id)
                        ->first(['id', 'status']);

                    if ($etinCertificate != null && ($etinCertificate->status == 0 || $etinCertificate->status == -1)) {
                        return response()->json([
                            'responseCode' => 0,
                            'row_id' => 0,
                            'message' => 'Submitted ! Now waiting for certificate'
                        ]);
                    }
                    if ($etinCertificate != null) {
                        $status = ($etinCertificate->status > 0) ? 1 : -2;
                        $id = $etinCertificate->status > 0 ? Encryption::encodeId($etinCertificate->id) : Encryption::encodeId(0);
                    } else {
                        $status = -4;
                    }
                } else {
                    $status = -3;
                }
            } else {
                $status = -1;
            }

            return response()->json([
                'responseCode' => $status,
                'row_id' => $id,
                'message' => ($status != 1) ? $this->formateResponseMessage($etinRequest->response) : ''
            ]);

        } catch (\Exception $e) {

            Log::error('CheckApiRequestStatus : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1067]');
            return response()->json([
                'responseCode' => -2,
                'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1067]",
            ]);
        }
    }


    public function showCertificate($app_id, $certificate_id)
    {
        try {

            $appId = Encryption::decodeId($app_id);
            $certificateId = Encryption::decodeId($certificate_id);

            $etinCertificate = EtinCertificate::where('id', $certificateId)
                ->where('ref_id', $appId)
                ->first(['id', 'response']);

            $certificate = isset($etinCertificate->response) ? $etinCertificate->response : null;

            return view("LicenceApplication::etin.show-certificate", compact('certificate'));


        } catch (\Exception $e) {
            Log::error('ShowCertificate : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1068]');
            return response()->json([
                'responseCode' => 0,
                'data' => null,
                'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1068]",
            ]);
        }
    }


    public function spPaymentSubmit($appId, $etinRecordNbr,$tracking_no)
    {
        try {

            if (empty($etinRecordNbr)) {
                Session::flash('error', "Your Etin in NBR Record not found [ETC-1070]");
                return \redirect()->back();
            }


            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [ETC-1071]");
                return redirect()->back()->withInput();
            }

            $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                ->where('is_active', 1)
                ->where('process_type_id', $this->process_type_id)
                ->get([
                    'receiver_account_no',
                    'amount',
                    'distribution_type'

                ])->toArray();


            $pay_amount = 0;
            $account_no = "";
            foreach ($stackholderMappingInfo as $data) {
                $pay_amount += $data['amount'];
                $account_no .= $data['receiver_account_no'] . "-";
            }
            $account_numbers = rtrim($account_no, '-');

            // Get SBL payment configuration
            DB::beginTransaction();
            $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->receiver_ac_no = $account_numbers;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->ref_tran_no = $tracking_no.'-01';
            $paymentInfo->pay_amount = $pay_amount;
            $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInsert = $paymentInfo->save();

            Etin::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);
            $sl = 1;

            StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();

            foreach ($stackholderMappingInfo as $data) {

                $paymentDetails = new StackholderSonaliPaymentDetails();
                $paymentDetails->payment_id = $paymentInfo->id;
                $paymentDetails->purpose_sbl = 'TRN';
                $paymentDetails->distribution_type = $data['distribution_type'];
                $paymentDetails->receiver_ac_no = $data['receiver_account_no'];
                $paymentDetails->pay_amount = $data['amount'];
                $paymentDetails->sl_no = 1; // Always 1
                $paymentDetails = $paymentDetails->save();

                $sl++;
            }
            DB::commit();

            if ($paymentInsert) {
                return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('SpPaymentSubmit : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1069]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[ETC-1069]");
            return redirect()->back()->withInput();
        }

    }


    public function afterPayment($payment_id)
    {

        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            DB::beginTransaction();
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name', 'process_type.process_sub_name',
                    'process_list.*'
                ]);

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

            SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                ->where('process_type_id', $this->process_type_id)
                ->update(['app_tracking_no' => $appInfo['tracking_no']]);

            $etinRecordNbr = EtinRecordNbr::where('application_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first(['response', 'payment_info']);

            $verification_response = json_decode($paymentInfo->verification_response);

            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            $lopt_url = $spg_conf->value;
            $userName = env('spg_user_id');
            $password = env('spg_password');
            $ownerCode = env('st_code');
            $referenceDate = $paymentInfo->payment_date;
            $requiestNo = $paymentInfo->request_id;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$lopt_url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);


            $account_num = $spg_conf->details;
            $data1 = json_decode($response);
            $data2 = json_decode($data1);
            $rData0['nc_save_id'] = $etinRecordNbr->response;
            $rData0['nc_request_by'] = $verification_response->ApplicantName;
            $rData0['remarks'] = "";
            $rData0['branch_code'] = $verification_response->BrCode;

            foreach ($data2 as $key => $value) {
                if ($value->TranAccount != $account_num) {
                    $rData0['account_info'][] = [
                        'account_no' => $value->TranAccount,
                        'balance' => 0,
                        'deposit' => $value->TranAmount,
                        'tran_date' => $value->TransactionDate,
                        'tran_id' => $value->TransactionId
                    ];
                }
            }

            $ncRjscPyament = new EtinNbrPayment();
            $ncRjscPyament->request = json_encode($rData0);
            $ncRjscPyament->ref_id = $paymentInfo->app_id;
            $ncRjscPyament->tracking_no = $processData->tracking_no;
            $ncRjscPyament->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/licence-applications/e-tin/view/' . Encryption::encodeId($paymentInfo->app_id) . '/' . Encryption::encodeId($this->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('AfterPayment : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1072]');
            Session::flash('error', 'Something went wrong!, E-TIN application not updated after payment. [ETC-1072]');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }




    public function afterCounterPayment($payment_id)
    {
        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            DB::beginTransaction();
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_list.*'
                ]);
            $applicantEmailPhone = Users::where('id', Auth::user()->id)
                ->get(['user_email', 'user_phone']);

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

            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if($paymentInfo->is_verified == 1){
                $processData->status_id = 16;
                $processData->desk_id = 7;
                $processData->read_status = 0;
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $processData->process_desc = 'Counter Payment Confirm';
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $verification_response = json_decode($paymentInfo->offline_verify_response);

                SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);


                $NCRecordRjsc = EtinRecordNbr::where('application_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first(['response','payment_info']);
                $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
                $lopt_url = $spg_conf->value;
                $userName=  env('spg_user_id');
                $password= env('spg_password');
                $ownerCode= env('st_code');
                $referenceDate= $paymentInfo->payment_date;
                $requiestNo= $paymentInfo->request_id;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$lopt_url",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);

                $err = curl_error($curl);

                curl_close($curl);

                $account_num=$spg_conf->details;
                $data1=json_decode($response);
                $data2=json_decode($data1);
                $rData0['nc_save_id'] = $NCRecordRjsc->response;
                $rData0['nc_request_by'] = Auth::user()->user_full_name;
                $rData0['remarks'] = "";
                $rData0['branch_code'] = $verification_response->BrCode;

                foreach ($data2 as $key=>$value){

                    if($value->TranAccount!=$account_num){
                        $rData0['account_info'][] =[
                            'account_no'=>$value->TranAccount,
                            'particulars'=>$value->ReferenceNo,
                            'balance'=>0,
                            'deposit'=>$value->TranAmount,
                            'tran_date'=>$value->TransactionDate,
                            'tran_id'=>$value->TransactionId
                        ];
                    }
                }

                $ncRjscPyament = new EtinNbrPayment();
                $ncRjscPyament->request = json_encode($rData0);
                $ncRjscPyament->ref_id = $paymentInfo->app_id;
                $ncRjscPyament->tracking_no = $processData->tracking_no;
                $ncRjscPyament->save();

            }
            /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            else{
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();

                // App Tracking ID store in Payment table
                SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('process/licence-applications/e-tin/view/' . Encryption::encodeId($paymentInfo->app_id) . '/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    private function getOrganizationTypeName($organization)
    {
        try {

            $getOrganization = EA_OrganizationType::where('id', $organization)
                ->where('status', 1)
                ->first();

            return isset($getOrganization->name) ? $getOrganization->name : '';

        } catch (\Exception $e) {

            Log::error('GetOrganizationTypeName : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1073]');
            return null;
        }

    }

    public function getThanaByDistrict(Request $request)
    {
        try {

            $district_id = $request->get('districtId');

            $thanas = NbrAreaInfo::where('pare_id', $district_id)->where('area_type', 3)->orderBy('name', 'ASC')->lists('name', 'nbr_id');
            $data = ['responseCode' => 1, 'data' => $thanas];
            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('GetThanaByDistrict : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1074]');
            return response()->json(['responseCode' => 0, 'data' => array()]);
        }

    }


    private function formateResponseMessage($message)
    {
        try{

            if (CommonFunction::isJson($message)) {
                $formatedMessage = '';

                $responseMessages = json_decode($message, true);

                foreach ($responseMessages as $key=>$singleMessage) {
                    if (is_array($singleMessage)) {

                        foreach ($singleMessage as $singleSubMessage) {

                            if (is_array($singleSubMessage)) {

                                foreach ($singleSubMessage as $subMessage) {

                                    $formatedMessage .= ucfirst($key).': '.$subMessage . PHP_EOL;;
                                }
                            } else {

                                $formatedMessage .= ucfirst($key).': '.$singleSubMessage . PHP_EOL;;
                            }
                        }
                    } else {

                        $formatedMessage .= ucfirst($key).': '.$singleMessage . PHP_EOL;;
                    }
                }
                return $formatedMessage;
            }

            return $message;

        } catch (\Exception $e) {
            Log::error('FormateResponseMessage : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1088]');
            return $message;
        }

    }
}
