<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\LicenceApplication\Models\NameClearance\CompanyPosition;
use App\Modules\LicenceApplication\Models\NameClearance\CompanyType;
use App\Modules\LicenceApplication\Models\NameClearance\NameClearance;
use App\Modules\LicenceApplication\Models\NameClearance\NCRecordRjsc;
use App\Modules\LicenceApplication\Models\NameClearance\NCRjsc;
use App\Modules\LicenceApplication\Models\NameClearance\NcRjscPayConfirm;
use App\Modules\LicenceApplication\Models\NameClearance\NcRjscPayment;
use App\Modules\LicenceApplication\Models\NameClearance\NcSubmissionVerification;
use App\Modules\LicenceApplication\Models\NameClearance\RjscOffice;
use App\Modules\NewReg\Models\RjscArea;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\SonaliPayment;

use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolder;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use mPDF;
use Validator;

class NameClearanceController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 107;
        $this->aclName = 'NameClearance';
        $this->apiUrl = 'http://103.219.147.21:8044';
        $this->port = '8044';
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
        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['is_accept'] = 'required';
            $rules['company_name'] = 'required';

        }

        $this->validate($request, $rules, $messages);

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
//            dd($request);
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = NameClearance::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new NameClearance();
                $processData = new ProcessList();
            }
            $appData->company_name = $request->get('company_name');
            $rjsc_office = explode('@', $request->get('rjsc_office'));
            $appData->rjsc_office = !empty($rjsc_office[0]) ? $rjsc_office[0] : '';
            $appData->rjsc_office_name = !empty($rjsc_office[1]) ? $rjsc_office[1] : '';
            $company_type = explode('@', $request->get('company_type'));
            $appData->company_type = !empty($company_type[0]) ? $company_type[0] : '';
            $appData->company_type_name = !empty($company_type[1]) ? $company_type[1] : '';
            $appData->applicant_name = $request->get('applicant_name');
            $designation_name = explode('@', $request->get('designation'));
            $appData->designation_id = !empty($designation_name[0]) ? $designation_name[0] : '';
            $appData->designation = !empty($designation_name[1]) ? $designation_name[1] : '';
            $appData->mobile_number = $request->get('mobile_number');
            $appData->email = $request->get('email');
            $appData->address = $request->get('address');
            $district = explode('@', $request->get('district'));
            $appData->district_id = !empty($district[0]) ? $district[0] : '';
            $appData->district_name = !empty($district[1]) ? $district[1] : '';
            if (isset($request->is_accept)) {
                $appData->is_accept = ($request->get('is_accept') == 'on') ? 1 : 0;
            }
            if (isset($request->is_signature)) {
                if ($request->is_signature == 'on') {
                    $appData->is_signature = 1;
                    $appData->digital_signature = $request->digital_signature;
                } else {
                    $appData->is_signature = 0;
                    $appData->digital_signature = '';
                }
            }

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
                    $processData->status_id = -1; //1 default -1 = if payment
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }
            $appData->company_id = $company_id;
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

            // Store payment info
            // Get Payment Configuration
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                DB::rollback();
                Session::flash('error', "Payment configuration not found [NC-107]");
                return redirect()->back()->withInput();
            }


            if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1 && $payment_config) {

                $processData->save();

                $processTypeId = $this->process_type_id;
                $servertype = '';
                if (env('server_type', 'local') == 'live') {
                    $servertype = '';
                }else{
                    $servertype = 'T';
                }
                $trackingPrefix = "NC$servertype-" . date("dMY") . '-';

                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");


                $trackingid = CommonFunction::getTrackingNoByProcessId($processData->id);


                $getRjscOffice = $this->getRjscOffice($request->get('rjsc_office'));
                $district_name = $this->getDistrictName($request->get('district_name'));
                $companyType =   $this->getCompanyTypeName($request->get('company_type'));
                $getApplicationPosition =   $this->getApplicationPosition($request->get('designation_name'),$request->get('company_type'));

                $requestJson = array(
                    "applicant_address" => $request->get('address'),
                    "applicant_district_name" => $appData->district_name,
                    "applicant_email" => $request->get('email'),
                    "applicant_organization" => "",
                    "applicant_phone" => $request->get('mobile_number'),
                    "applicant_position" => $appData->designation,
                    "applied_by" => $request->get('applicant_name'),
                    "company_name" => $request->get('company_name'),
                    "entity_type" => $appData->company_type_name,
                    "oss_application_id" => $trackingid,
                    "rjsc_off_dist_name" => $appData->district_name
                );
                NCRecordRjsc::create([
                    'status' => 0,
                    'request' => json_encode($requestJson),
                    'process_type_id' => $this->process_type_id,
                    "tracking_no" => $trackingid,
                    'application_id' => $appData->id
                ]);

//                return redirect('spg/initiate/'.$paymentInfo->id);
            }

            DB::commit();
            // Mail send for application submit, re-submit
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
                    'process_type_name' => 'Name Clearance',
                    'remarks' => ''
                ];

                if ($processData->status_id == 2)
                    CommonFunction::sendEmailSMS('APP_RESUBMIT', $appInfo, $applicantEmailPhone);
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            if ($request->get('actionBtn') == "draft") {
                return redirect('/licence-application/list/' . Encryption::encodeId($this->process_type_id));

            }
            return redirect('licence-applications/name-clearance/check-rjsc-status/' . Encryption::encodeId($appData->id) . '/' . Encryption::encodeId($appData->sf_payment_id));
//            return redirect('/licence-application/list/'.Encryption::encodeId($this->process_type_id));


        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }

    /*
     * Application edit or view
     */


    public function appForm(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        try {

                if(!$request->ajax()){
                    return 'Sorry! this is a request without proper way.';
                }

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            if (empty($basicAppInfo)) {
                return response()->json(['responseCode' => 1, 'html' => "<center><h4 style='color: red;margin-top: 250px;margin-left: 70px;'>Sorry! You have no approved Basic Information application.</h4></center>"]);
            }
            $userdistrict_name = '';
            if($basicAppInfo->ceo_district_id !=0){
                $userdistrict_name =  AreaInfo::where('area_id', Auth::user()->district)->value('area_nm');
            }
            $alreadyExist = ProcessList::where('process_list.process_type_id', $this->process_type_id)
                ->leftJoin('nc_apps', 'nc_apps.id', '=', 'process_list.ref_id')
                ->whereNotIn('process_list.status_id', ['-1',6])
                ->whereIn('process_list.company_id', $companyIds)
                ->orderBy('id','desc')
                ->first(['process_list.*','nc_apps.cert_valid_until']);

            $is_valid = 0;
            if (count($alreadyExist) > 0 && $alreadyExist->cert_valid_until != '')
            {
                $validuntill =\Carbon\Carbon::parse($alreadyExist->cert_valid_until)->format('d M Y H:i:m');

                $currentDate = \Carbon\Carbon::now()->format('d M Y H:i:m');
                if($currentDate > $validuntill ){
                    $is_valid = 0;
                }else{
                    $is_valid = 1;
                }
            }
            if (count($alreadyExist) > 0 && $is_valid == 1 ) {
                return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 150px;text-align: center'>Your Application Already Exist! Your tracking no is: " . $alreadyExist->tracking_no."</h4>"]);
            }
            $rjscOffice = ['' => 'Select One'] + RjscOffice::select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                ->lists('name', 'rjsc_id')->all();
            $rjscCompanyType =CompanyType::whereIn('rjsc_id',[1,2])->select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                    ->lists('name', 'rjsc_id')->all();
            $thana = ['' => 'Select One'] + AreaInfo::where('area_type', 3)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            $districts = ['' => 'Select One'] + RjscArea::where('area_type', 2)->orderBy('name', 'asc')->select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                    ->lists('name', 'rjsc_id')->all();
            $districts_basic_info = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            $viewMode = 'off';
            $mode = '-A-';
            $token = $this->getRjscToken();
            $rjsc_nc_api_url = Config('stackholder.RJSC_NC_API_URL');
//            return view("LicenceApplication::nameClearance.application-form", compact('rjscCompanyType','districts','rjscOffice','basicAppInfo', 'mode', 'viewMode','thana','districts_basic_info','token','rjsc_nc_api_url'));
            $public_html = strval(view("LicenceApplication::nameClearance.application-form", compact('rjscCompanyType','districts','rjscOffice','basicAppInfo', 'mode', 'viewMode','thana','districts_basic_info','token','rjsc_nc_api_url','userdistrict_name')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . ' [EC-1005]']);
        }
    }

    public function uploadDocument()
    {
        return View::make('LicenceApplication::nameClearance.ajaxUploadFile');
    }

    public function appFormEditView($applicationId, $openMode = '', Request $request)
    {

        if(!$request->ajax()){
            return 'Sorry! this is a request without proper way.';
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

            if ($openMode == 'edit') {
            if($this->checkSubmissionVerification($applicationId) != false){
                $verifyid = $this->checkSubmissionVerification($applicationId);
                $public_html = strval(view("LicenceApplication::nameClearance.wating-for-submission-verificaion",
                    compact('verifyid','mode','viewMode')));
                return response()->json(['responseCode' => 1, 'html' => $public_html]);
                //return redirect('licence-applications/name-clearance/submission-response/'.Encryption::encodeId($verifyid));

            }
            }


            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('nc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
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
                    'apps.*'
                ]);

            //dd($appInfo);
            $rjscOffice = ['' => 'Select One'] + RjscOffice::select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                    ->lists('name', 'rjsc_id')->all();
            $rjscCompanyType =  CompanyType::whereIn('rjsc_id',[1,2])->select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                ->lists('name', 'rjsc_id')->all();
            $thana = ['' => 'Select One'] + RjscArea::where('area_type', 3)->orderBy('name', 'asc')->lists('name', 'rjsc_id')->all();
            $districts = ['' => 'Select One'] + RjscArea::where('area_type', 2)->orderBy('name', 'asc')->select(DB::raw("CONCAT(rjsc_id,'@',name) AS rjsc_id"),'name')
                    ->lists('name', 'rjsc_id')->all();
            $nameClearance = NameClearance::find($applicationId);

            $token = $this->getRjscToken();
            $rjsc_nc_api_url = Config('stackholder.RJSC_NC_API_URL');
            $public_html = strval(view("LicenceApplication::nameClearance.application-form-edit",
                compact('appInfo', 'viewMode',
                    'mode','rjscOffice','rjscCompanyType','districts','nameClearance','thana','token','rjsc_nc_api_url')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[PR-1010]"]);
        }
    }

    public function appFormView($applicationId, $openMode = '', Request $request)
    {
        $viewMode = 'on';
        $mode = '-V-';

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }

        try {
            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('nc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.gf_payment_id')
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
//                    'process_list.department_id',
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
                    'sfp.pay_mode as pay_mode',
                    'sfp.pay_mode_code as pay_mode_code',
                    'apps.*'
                ]);
            $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $applicationId)
                ->where('process_type_id', $this->process_type_id)
                ->whereIn('payment_status', [1, 3])
                ->get([
                    'id as sp_payment_id',
                    'contact_name as sfp_contact_name',
                    'contact_email as sfp_contact_email',
                    'contact_no as sfp_contact_phone',
                    'address as sfp_contact_address',
                    'pay_amount as sfp_pay_amount',
                    'vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'transaction_charge_amount as sfp_transaction_charge_amount',
                    'vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'total_amount as sfp_total_amount',
                    'payment_status as sfp_payment_status',
                    'pay_mode as pay_mode',
                    'pay_mode_code as pay_mode_code',
                    'ref_tran_date_time'
                ]);
            $public_html = strval(view("LicenceApplication::nameClearance.application-form-view", compact('appInfo', 'viewMode', 'mode','spPaymentinformation')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getMessage()) . "[PR-1010]"]);
        }
    }

    public function checkSubmissionVerification($app_id){
        $rjscRecorData = NCRecordRjsc::where('application_id',$app_id)
            ->where('process_type_id',$this->process_type_id)
            ->where('status',1)
            ->orderBy('id', 'DESC')
            ->first();
        if (count($rjscRecorData) >0){
                $NcSubmissionVeiry = NcSubmissionVerification::firstOrNew(['ref_id' => $app_id]);
                $NcSubmissionVeiry->ref_id = $rjscRecorData->application_id;
                $NcSubmissionVeiry->process_type_id = $rjscRecorData->process_type_id;
                $NcSubmissionVeiry->licence_application_id = $rjscRecorData->response;
                $NcSubmissionVeiry->tracking_no = $rjscRecorData->tracking_no;
                $NcSubmissionVeiry->status = 0;
                $NcSubmissionVeiry->save();
               return Encryption::encodeId($NcSubmissionVeiry->id);
        }else{
            return false;
        }

    }

    public function verificationResponse(Request $request){
        $submission_verification_id = Encryption::decodeId($request->verification_id);

        $rjscData = NcSubmissionVerification::find($submission_verification_id);
       // dd($rjscData);

        $status = intval($rjscData->status);

        if ($rjscData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => $rjscData->response]);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
        } elseif ($status == -2) {
                NCRecordRjsc::where('application_id', $rjscData->ref_id)
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['status' =>-3]);
//            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -2, 'message' => 'আপনার রিকোয়েস্ট টি প্রসেসিং করা সম্ভহব হয় নাই। দয়া করে কল সেন্টার এ যোগাযোগ করুন']);
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -2, 'message' => $rjscData->response]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'app_id' => Encryption::encodeId($rjscData->ref_id),'payment_id'=>Encryption::encodeId(0),'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }else{
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => $status, 'message' => $rjscData->response]);
        }

    }

    public  function waitforresfonse($veirfyid){
        $mode = '-E-';
        return view("LicenceApplication::nameClearance.wating-for-submission-verificaion",
            compact('veirfyid','$mode'));
    }

    public function rjscList()
    {
//R
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => $this->port,
            CURLOPT_URL => "$this->apiUrl/rjsc/api-request?param={\"rjsc\":{\"requestData\":{\"ref_id\":\"0\"},\"requestType\":\"RJSC_OFFICE_LIST\",\"version\":\"1.0\"}}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);


        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }

        $decoded_response = json_decode($response, true);
        $decoded_response = $decoded_response['rjsc']['responseStatus']['responseData'];
        $rjscList = [];
        if ($decoded_response) {
            $rjscList = [];
            foreach ($decoded_response as $value) {
//                $rjscList[$value['officeId']] = $value['name'];
                $rjscList[$value['name']] = $value['name'];
            }
        }

//        echo "<pre>";
//        print_r($rjscList);
//        exit();
        return json_encode($rjscList);
    }

    public function companyList()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "$this->port",
            CURLOPT_URL => "$this->apiUrl/rjsc/api-request?param={\"rjsc\":{\"requestData\":{\"ref_id\":\"0\"},\"requestType\":\"COMPANY_TYPE\",\"version\":\"1.0\"}}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);


        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }

        $decoded_response = json_decode($response, true);
        $decoded_response = $decoded_response['rjsc']['responseStatus']['responseData'];
        $companyList = [];
        if ($decoded_response) {
            foreach ($decoded_response as $value) {

                $companyList[$value['id']] = $value['name'];
//                $companyList[$value['name']] = $value['name'];

            }
        }
        return json_encode($companyList);
    }

    public function designationList($id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "$this->port",
            CURLOPT_URL => "$this->apiUrl/rjsc/api-request?param={\"rjsc\":{\"requestData\":{\"ref_id\":\"$id\"},\"requestType\":\"POSITION_BY_COM_TYPE\",\"version\":\"1.0\"}}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);


        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }

        $decoded_response = json_decode($response, true);
        $decoded_response = $decoded_response['rjsc']['responseStatus']['responseData'];
        $designationList = [];
        if ($decoded_response) {
            foreach ($decoded_response as $value) {
//                $designationList[$value['positionId']] = $value['positionTitle'];
                $designationList[$value['positionTitle']] = $value['positionTitle'];
            }
        }

        return json_encode($designationList);
    }

    public function organizationList()
    {
        $organizationList = ["1" => "software company", "2" => "software company one", "3" => "software company tow", "4" => "software company three"];
        return json_encode($organizationList);
    }

    public function districtList()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_PORT => "$this->port",
            CURLOPT_URL => "$this->apiUrl/rjsc/api-request?param={%22rjsc%22:{%22requestData%22:{%22ref_id%22:%220%22},%22requestType%22:%22DISTRICT%22,%22version%22:%221.0%22}}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);


        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
//            echo $response;
        }

        $decoded_response = json_decode($response, true);
        $decoded_response = $decoded_response['rjsc']['responseStatus']['responseData'];
        $districtList = [];
        if ($decoded_response) {
            $districtList = [];

            foreach ($decoded_response as $value) {
//               $districtList[$value['id']] = $value['name'];
                $districtList[$value['name']] = $value['name'];
            }

        }


        return json_encode($districtList);
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


            if ($paymentInfo->payment_category_id == 3) { //govt and service fee
                $processData->status_id = 1;
                $processData->desk_id = 7;
                $processData->read_status = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
            }
            $processData->save();


            $NCRecordRjsc = NCRecordRjsc::where('application_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first(['response','payment_info']);


            $verification_response = json_decode($paymentInfo->verification_response);

            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            $lopt_url = $spg_conf->value;
            $userName = Config('payment.spg_settings_stack_holder.user_id');
            $password = Config('payment.spg_settings_stack_holder.password');
            $ownerCode = Config('payment.spg_settings_stack_holder.st_code');
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
            $rData0['nc_request_by'] = $verification_response->ApplicantName;
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

            $ncRjscPyament = new NcRjscPayment();
            $ncRjscPyament->request = json_encode($rData0);
            $ncRjscPyament->ref_id = $paymentInfo->app_id;
            $ncRjscPyament->tracking_no = $processData->tracking_no;
            $ncRjscPyament->save();

            DB::commit();
//            dd(json_encode($rData0));
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
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
            if($paymentInfo->is_verified == 1)
            {

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


                $NCRecordRjsc = NCRecordRjsc::where('application_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first(['response','payment_info']);
                $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
                $lopt_url = $spg_conf->value;
                $userName = Config('payment.spg_settings_stack_holder.user_id');
                $password = Config('payment.spg_settings_stack_holder.password');
                $ownerCode = Config('payment.spg_settings_stack_holder.st_code');
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

//dd($response);

                $account_num=$spg_conf->details;
//            $data=$response;
                $data1=json_decode($response);
                $data2=json_decode($data1);
                $rData0['nc_save_id'] = $NCRecordRjsc->response;
                $rData0['nc_request_by'] = Auth::user()->user_full_name;
                $rData0['remarks'] = "";
                $rData0['branch_code'] = $verification_response->BrCode;
//dd($data2);

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



                $ncRjscPyament = new NcRjscPayment();
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
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }



    public function appFormPdf($appId)
    {
//dd($appId);
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

            $appInfo = ProcessList::leftJoin('nc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('rjsc_office_list as rjsc', 'rjsc.id', '=', 'apps.rjsc_office')
                ->leftJoin('rjsc_company_positions as rjscposition', 'rjscposition.rjsc_id', '=', 'apps.designation')
                ->leftJoin('rjsc_company_type as rjsctype', 'rjsctype.rjsc_id', '=', 'apps.company_type')
                ->leftJoin('rjsc_area_info as rjsc_area_info', 'rjsc_area_info.rjsc_id', '=', 'apps.district_id')
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
                    'apps.*',
                    'rjsc.name as rjsc_office_name',
                    'rjscposition.title as rjsc_position_name',
                    'rjsctype.name as rjsc_type_name',
                    'rjsc_area_info.name as rjsc_dis_name'
                ]);
//            $rjscOfficePdf = ['' => 'Select One'] + RjscOffice::lists('name', 'rjsc_id')->all();
            //dd($appInfo);
            $contents = view("LicenceApplication::nameClearance.application-form-pdf",
                compact('basicAppInfo', 'appInfo'))->render();

//           return $contents;
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
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }


    public function UpdateAd($ref_id, $requestData)
    {
        NameClearance::where('id', $ref_id)->update([
            'account_number' => $requestData['acc_number'],
            'amount' => $requestData['amount']
        ]);
        return true;
    }


    public function searchCompanyName(Request $request)
    {
        try {
            $company_name = trim($request->company_name);

            $rules = [
                'company_name' => 'required'
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['responseCode' => 0, 'message' => 'Name field is required.']);
            }
            $alreadyExists = NCRjsc::where('name',$company_name)->orderBy('id','desc')->first();
            // check company name already exist or not
            if($alreadyExists){
                $alreadyExists->status = 0;
                $alreadyExists->response = "";
                $alreadyExists->created_by = Auth::user()->id;
                $alreadyExists->save();

                $rjsc = $alreadyExists;
            }else{
                $rjsc = NCRjsc::create([
                    'name' => $company_name,
                    'entity_name' => 'Private Company',
                    'status' => 0
                ]);
            }




            $message = 'Your request has been locked on verify';
            $responseCode = 1;

            return response()->json(['responseCode' => $responseCode, 'message' => $message, 'enc_id' => Encryption::encodeId($rjsc->id), 'enc_status' => Encryption::encodeId(0)]);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $responseCode = 0;
        }
        return response()->json(['responseCode' => $responseCode, 'message' => $message, 'enc_status' => Encryption::encodeId(0)]);
    }

    public function rjscResponse(Request $request)
    {

        $rjsc_request_id = Encryption::decodeId($request->enc_id);

        $rjscData = NCRjsc::find($rjsc_request_id);

        $status = intval($rjscData->status);

        if ($rjscData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => $rjscData->response]);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
        } elseif ($status == -2 || $status == -3 || $status == -4) {
//            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -2, 'message' => 'আপনার রিকোয়েস্ট টি প্রসেসিং করা সম্ভহব হয় নাই। দয়া করে কল সেন্টার এ যোগাযোগ করুন']);
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -2, 'message' => $rjscData->response]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'name' => $rjscData->name]);
        }else{
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => $status, 'message' => $rjscData->response]);
        }
    }

    public function checkRjscStatus($applicationId = null, $paymentId = null)
    {
        return view("LicenceApplication::nameClearance.waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function getRjscStatus(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $rjscData = NCRecordRjsc::where(['application_id' => $application_id, 'process_type_id' => $this->process_type_id])->orderBy('id', 'desc')->first();
        $status = intval($rjscData->status);
        if ($status == 1) {
            $applyPaymentfee = json_decode($rjscData->payment_info);
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' =>  $this->process_type_id])->first(['amount']);

            $paymentInfo = view(
                "LicenceApplication::nameClearance.paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($rjscData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified','paymentInformation' => $paymentInfo]);
        }
    }

    public function ncPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');

        try {

        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $NCRecordRjsc = NCRecordRjsc::where('application_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->first();

        if (empty($NCRecordRjsc)) {
            Session::flash('error', "Your Nc Record not found [Nc-1125]");
            return \redirect()->back();
        }


        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [VRA-1123]");
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



        $jsonData = json_decode($NCRecordRjsc->payment_info);


        $rjscPayAccount1 = array(
            'receiver_account_no' => $jsonData->rjsc_fees_ac_no,
            'amount' => $jsonData->rjsc_fee,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $rjscPayAccount1;


        $rjscVatAccount2 = array(
            'receiver_account_no' => $jsonData->rjsc_vat_ac_no,
            'amount' => $jsonData->rjsc_vat,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $rjscVatAccount2;


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
        $paymentInfo->app_id = $appId;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->payment_date = date('Y-m-d');
        $paymentInfo->ref_tran_no = $NCRecordRjsc->response."/1/01";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->contact_name = CommonFunction::getUserFullName();
        $paymentInfo->contact_email = Auth::user()->user_email;
        $paymentInfo->contact_no = Auth::user()->user_phone;
        $paymentInfo->address = Auth::user()->road_no;
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();

        NameClearance::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);

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
        /*
        * Payment Submission
       */
        if ($request->get('actionBtn') == 'Payment' && $paymentInsert) {
            return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
        }

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getLine()) . "[VRA-1025]");
            return redirect()->back()->withInput();
        }
    }

    public function updateRjscFinalStatus()
    {
        DB::beginTransaction();
        $getApprovedData = NcRjscPayConfirm::where('status', 1)
            ->where('nc_update_status', 0)->get();
        $nc_update_status = -1;
        foreach ($getApprovedData as $value) {

            if ($value->status = 1) {
                $jsonResponse = $value->response;
                $decodedResponse = json_decode($jsonResponse);

                $ref_id = $value->ref_id;
                if ($ref_id > 0) {
                    $ncData = NameClearance::find($ref_id);
                    $ncData->cert_no = $decodedResponse->cert_no;
                    $ncData->cert_issue_date = $decodedResponse->issue_date;
                    $ncData->cert_applicant_name = $decodedResponse->applicant_name;
                    $ncData->cert_registered_address = $decodedResponse->registered_address;
                    $ncData->cert_application_no = $decodedResponse->application_no;
                    $ncData->cert_application_date = $decodedResponse->application_date;
                    $ncData->cert_entity_name = $decodedResponse->entity_name;
                    $ncData->cert_valid_until = $decodedResponse->valid_until;
                    $ncData->save();
                    $nc_update_status = 1;
                }



                $pdf_info = PdfServiceInfo::where('certificate_name', 'name_clearance')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);

                $url_store = PdfPrintRequestQueue::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'app_id' => $ref_id
                ]);

                $url_store->process_type_id =  $this->process_type_id;
                $url_store->app_id = $ref_id;
                $url_store->pdf_server_url = $pdf_info->pdf_server_url;
                $url_store->reg_key = $pdf_info->reg_key;
                $url_store->pdf_type = $pdf_info->pdf_type;
                $url_store->certificate_name = $pdf_info->certificate_name;
                $url_store->prepared_json = 0;
                $url_store->table_name = $pdf_info->table_name;
                $url_store->field_name = $pdf_info->field_name;
                $url_store->signatory = 0;
                $url_store->updated_at = date('Y-m-d H:i:s');
                $url_store->save();

            }
            NcRjscPayConfirm::where('id', $value->id)->update([
                'nc_update_status' => $nc_update_status
            ]);
        }
        DB::commit();
    }

    public function getCompanyType(Request $request){
        $companyType = $request->get('company_type');
//        dd($companyType);
        $designation = CompanyPosition::where('rjsc_company_type_rjsc_id', $companyType)
            ->where('status',1)
            ->orderBy('title', 'ASC')
            ->lists('title','rjsc_id');
//        dd($designation);

        $data = ['responseCode' => 1, 'data' => $designation];
        return response()->json($data);
    }

    public function getDistrictByRjscOffice($rjscOfficeId){

        $districts = RjscArea::where('rjsc_office_id', $rjscOfficeId)
            ->where('status',1)
            ->orderBy('name', 'ASC')
            ->lists('name','rjsc_id');

        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    private function getDistrictName($district_id)
    {
        $district_name = RjscArea::where('rjsc_id',$district_id)->first(['name']);
        if($district_name){
            return $district_name->name;
        }
    }

    private function getCompanyTypeName($company_type)
    {
        $getCompanyPosition = CompanyType::where('rjsc_id', $company_type)
            ->where('status',1)
            ->first();
        if($getCompanyPosition){
            return $getCompanyPosition->name;
        }
    }

    private function getRjscOffice($officeId)
    {
        $getRjscOffice = RjscOffice::where('rjsc_id', $officeId)
            ->where('status',1)
            ->first();
        if($getRjscOffice){
            return $getRjscOffice->name;
        }
    }

    private function getApplicationPosition($designation_id,$companyType)
    {
        $getCompanyPosition = CompanyPosition::where('rjsc_id', $designation_id)
            ->where('status',1)
            ->where('rjsc_company_type_rjsc_id',$companyType)
            ->first();
        if($getCompanyPosition){
            return $getCompanyPosition->title;
        }
    }

    // Get RJSC token for authorization
    public function getRjscToken()
    {
        $rjsc_nc_idp_url = Config('stackholder.RJSC_NC_IDP_URL');
        $rjsc_nc_client_id = Config('stackholder.RJSC_NC_CLIENT_ID');
        $rjsc_nc_client_secret = Config('stackholder.RJSC_NC_CLIENT_SECRET');
        $token = CommonFunction::getToken($rjsc_nc_idp_url, $rjsc_nc_client_id, $rjsc_nc_client_secret);
        return $token;
    }

    public function getRefreshToken()
    {
        $token = $this->getRjscToken();
        return response($token);
    }

}