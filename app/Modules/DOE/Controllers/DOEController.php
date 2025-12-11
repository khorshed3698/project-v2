<?php

namespace App\Modules\DOE\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\apps\Models\Colors;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\apps\Models\Document;
use App\Modules\Apps\Models\IndustryCategories;
use App\Modules\DOE\Models\DOE;
use App\Modules\DOE\Models\DOEAPIRequest;
use App\Modules\DOE\Models\DoeAppChangeInfo;
use App\Modules\DOE\Models\DoeComment;
use App\Modules\DOE\Models\DoePaymentConfirm;
use App\Modules\DOE\Models\DoePaymentInfo;
use App\Modules\DOE\Models\DOEShortfall;
use App\Modules\DOE\Models\DOEVoucher;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\ProjectClearance\Models\ProjectClearance;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\HighComissions;
use App\Modules\Settings\Models\VisaCategories;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class DOEController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 108; // 18 is DOE
        $this->aclName = 'DOE';
    }

    public function appForm() {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [DOE-60]</h4>"]);
        }

        try {

            $certificateTypes = [
                'site_clearance'=>'Site Clearance',
                'environment_clearance'=>'Environment Clearance',
                'renew'=>'Renew',
                'EIA_Approval'=>'EIA Approval',
                'TOR_Approval'=>'TOR Approval',
                'Zero_discharged_Approval'=>'Zero Discharged Approval'
            ];
            $fee_category = [
                '1@Industry' =>'Industry',
                '2@Brick' =>'Brick'
            ];

            $land_unit = [
                'decimal' => 'Decimal',
                'acre' => 'Acre',
                'sq.meters' => 'Sq. Meters',
                'sq.feet' => 'Sq. Feet',
            ];

            $production__unit = [
                'kg' => 'KG',
                'liter' => 'Liter',
                'piece' => 'Piece',
                'other' => 'Other',
            ];

            $durations = [
                'daily' => 'Daily',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly',
            ];

            $water_unit = [
                'liter' => 'liter',
                'mmcf' => 'mmcf',
                'khw' => 'khw',
            ];

            $authUserId = CommonFunction::getUserId();
            $company_id = CommonFunction::getUserWorkingCompany();
            $alreadyExistApplicant = '';


            $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->pluck('nicename', 'iso');
            $countriesWithoutBD = Countries::where('country_status', 'Yes')->where('country_code', '!=', '001')->orderBy('nicename', 'asc')->pluck('nicename', 'iso');
            $nationality = Countries::orderby('nationality')->where('nationality', '!=', '')->pluck('nationality', 'iso');
            $nationalityWithoutBD = Countries::orderby('nationality')->where('nationality', '!=', '')->where('country_code', '!=', '001')->pluck('nationality', 'iso');

            $currency = ['' => 'Select One'] + Currencies::orderby('code')->where('is_active', 1)->lists('code', 'id')->all();
            $divition_eng = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();

            $high_comissions = HighComissions::select('id', DB::raw('CONCAT(high_comissions.name, ", ", high_comissions.address) AS commission'))
                ->orderBy('commission')
                ->pluck('commission', 'id');

            $payment_method = ['Monthly' => 'Monthly', 'Yearly' => 'Yearly'];
            $doe_api_url = config('stackholder.doe_api_url');
            $document = docInfo::where('process_type_id', $this->process_type_id)->orderBy('order')->get();

            if ($alreadyExistApplicant) {
                $clr_document = Document::where('process_type_id', $this->process_type_id)->where('app_id', $alreadyExistApplicant->id)->get();
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_file;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
            } else {
                $clrDocuments = [];
            }
            $logged_user_info = Users::where('id', $authUserId)->first();
            $viewMode = 'off';
            $mode = '-A-';
            $token = $this->getDOEToken();
            $public_html = strval(view("DOE::application-form", compact('countries', 'countriesWithoutBD',
                'divition_eng','certificateTypes','fee_category', 'economicZone', 'document', 'logged_user_type', 'alreadyExistApplicant',
                'logged_user_info', 'clrDocuments', 'nationality', 'nationalityWithoutBD', 'visa_types', 'high_comissions',
                'payment_method', 'currency', 'viewMode', 'projectClearanceData','industryTypes', 'colors','mode', 'pc_track_no', 'pc_applicant_name', 'alleconomicZone',
                'land_unit', 'production__unit', 'durations', 'water_unit','token','doe_api_url')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }

    public function applicationViewEdit($applicationId, $openMode=''){
        try {
            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;

            if ($openMode == 'view') {
                $viewMode = 'on';
                $mode = '-V-';
            } else if ($openMode == 'edit') {
                $viewMode = 'off';
                $mode = '-E-';
            }


            $appInfo = ProcessList::leftJoin('doe_master as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->first([
                    'process_list.id as process_list_id',
                    'process_list.desk_id',
                    'process_list.process_type_id',
                    'process_list.status_id',
                    'process_list.locked_by',
                    'process_list.locked_at',
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.company_id',
                    'process_list.process_desc',
                    'process_list.priority',
                    'process_list.submitted_at',
                    'process_list.process_desc',
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
            $spPaymentinformation = SonaliPaymentStackHolders::where('app_id',$applicationId)
                ->where('process_type_id',$this->process_type_id)
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


            $certificateTypes = [
                'site_clearance'=>'Site Clearance',
                'environment_clearance'=>'Environment Clearance',
                'renew'=>'Renew',
                'EIA_Approval'=>'EIA Approval',
                'TOR_Approval'=>'TOR Approval',
                'Zero_discharged_Approval'=>'Zero Discharged Approval'
            ];

            $fee_category = [
                '1@Industry' =>'Industry',
                '2@Brick' =>'Brick'
            ];

            $land_unit = [
                'decimal' => 'Decimal',
                'acre' => 'Acre',
                'sq.meters' => 'Sq. Meters',
                'sq.feet' => 'Sq. Feet',
            ];

            $production__unit = [
                'kg' => 'KG',
                'liter' => 'Liter',
                'piece' => 'Piece',
                'other' => 'Other',
            ];

            $durations = [
                'daily' => 'Daily',
                'monthly' => 'Monthly',
                'yearly' => 'Yearly',
            ];

            $water_unit = [
                'liter' => 'liter',
                'mmcf' => 'mmcf',
                'khw' => 'khw',
            ];

            $changeInfo = DoeAppChangeInfo::where('ref_id',$applicationId)
                ->orderBy('id','desc')
                ->first();
            $comments = DoeComment::where('ref_id',$applicationId)
                ->orderBy('id','desc')
                ->first();


            $authUserId = CommonFunction::getUserId();
            $company_id = CommonFunction::getUserWorkingCompany();

            $logged_user_info = Users::where('id', $authUserId)->first();
            $DOEVoucher = DOEVoucher::where('ref_id', $applicationId)->get();
            $token = $this->getDOEToken();
            $doe_api_url = config('stackholder.doe_api_url');

            $public_html = strval(view("DOE::application-form-edit", compact('certificateTypes','fee_category',
                'logged_user_info', 'viewMode','mode', 'appInfo', 'land_unit', 'production__unit', 'water_unit',
                'durations', 'DOEVoucher','changeInfo','comments','spPaymentinformation','token','doe_api_url')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function appStore(Request $request) {

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [DOE-295]</h4>"]);
        }
        $authUserId = CommonFunction::getUserId();
        $company_id = CommonFunction::getUserWorkingCompany();
        $statusArr = array(5, 8, 22, '-1'); // 5 is shortfall, 8 is Discard, 22 is Rejected Application and -1 is draft

        try {
            /* Transaction Start */
            DB::beginTransaction();

            $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
            if (isset($app_id) && $app_id !='') {
//                dd($app_id);
                $appData = DOE::find($app_id);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id])->first();
            } else {
                $appData = new DOE();
                $processData = new ProcessList();
            }

            $certificate_type = explode('@', $request->get('certificate_type'));
            $appData->certificate_type_id = !empty($certificate_type[0]) ? $certificate_type[0] : '';
            $appData->certificate_type_name = !empty($certificate_type[1]) ? $certificate_type[1] : '';
            $appData->certificate_type_label = !empty($certificate_type[2]) ? $certificate_type[2] : '';

            $type_industry = explode('@', $request->get('industry_id'));
            $appData->industry_id = !empty($type_industry[0]) ? $type_industry[0] : '';
            $appData->industry_name = !empty($type_industry[1]) ? $type_industry[1] : '';
            $appData->industry_other = $request->get('industry_other');

            $appData->application_type = $request->get('application_type');

            $category_id = explode('@', $request->get('category_id'));
            $appData->category_id = $category_id[0];
            $appData->category_name = isset($category_id[1])?$category_id[1]:'';
            $appData->category_color_code = isset($category_id[2])?$category_id[2]:'';
            $appData->entrepreneur_name = $request->get('entrepreneur_name');
            $appData->entrepreneur_designation = $request->get('entrepreneur_designation');
            $appData->phone = $request->get('phone_number');
            $appData->email = $request->get('email');
            $appData->mobile = $request->get('mobile');
            $appData->investment = $request->get('total_investment');
            $appData->land = $request->get('land');
            $appData->land_unit = $request->get('land_unit');
            $appData->manpower = $request->get('total_manpower');

            $fee_category = explode('@', $request->get('fee_category_id'));
            $appData->fee_category_id = !empty($fee_category[0]) ? $fee_category[0] : '';
            $appData->fee_category_name = !empty($fee_category[1]) ? $fee_category[1] : '';

            $fee = explode('@', $request->get('fee_id'));
            $appData->fee_id = !empty($fee[0]) ? $fee[0] : '';
            $appData->fee = !empty($fee[1]) ? $fee[1] : '';

            $appData->fee_type = $request->get('fee_type');
            $appData->total_fee = $request->get('total_fee');
            $appData->vat_amount = !empty($request->get('total_fee'))?($request->get('total_fee')*15)/100 :'';

            $appData->project_name = $request->get('project_name');
            $appData->product_name = $request->get('project_activity');

            $district = explode('@', $request->get('district_id'));
            $appData->district_id = !empty($district[0]) ? $district[0] : '';
            $appData->district_name = !empty($district[1]) ? $district[1] : '';

            $thana = explode('@', $request->get('thana'));
            $appData->thana_id = !empty($thana[0]) ? $thana[0] : '';
            $appData->thana_name = !empty($thana[1]) ? $thana[1] : '';

            $submitting_office = explode('@', $request->get('submitting_office'));
            $appData->submitting_office_id = !empty($submitting_office[0]) ? $submitting_office[0] : '';
            $appData->submitting_office_name = !empty($submitting_office[1]) ? $submitting_office[1] : '';

            $appData->location = $request->get('project_address');


            $appData->start_construction = !empty($request->start_construction) ? date('Y-m-d', strtotime($request->start_construction)) : '';
            $appData->completion_construction = !empty($request->completion_construction) ? date('Y-m-d', strtotime($request->completion_construction)) : '';
            $appData->trial_production = !empty($request->trial_production) ? date('Y-m-d', strtotime($request->trial_production)) : '';
            $appData->start_operation = !empty($request->start_operation) ? date('Y-m-d', strtotime($request->start_operation)) : '';
            $appData->name_production = !empty($request->name_production) ? date('Y-m-d', strtotime($request->name_production)) : '';
            $appData->estart_operation = !empty($request->estart_operation) ? date('Y-m-d', strtotime($request->estart_operation)) : '';
            $appData->etrial_production = !empty($request->etrial_production) ? date('Y-m-d', strtotime($request->etrial_production)) : '';

            $appData->name_production_quantity = $request->production_quantity;
            $appData->name_production_quantity_unit = $request->get('name_production_quantity_unit');
            $appData->name_production_quantity_duration = $request->get('name_production_quantity_duration');

            $appData->raw_materils_quantity = $request->get('raw_materials');
            $appData->raw_materils_quantity_unit = $request->get('raw_materials_unit');
            $appData->raw_materils_quantity_duration = $request->get('raw_materials_duration');

            $appData->source_raw_material = $request->get('source_raw');
            $appData->quantity_water = $request->get('quantity_water');
            $appData->quantity_water_unit = $request->get('quantity_water_unit');
            $appData->source_water = $request->get('source_of_water');
            $appData->name_of_fuel = $request->get('name_of_fuel');
            $appData->fuel_quantity = $request->get('fuel_quantity');
            $appData->fuel_quantity_unit = $request->get('fuel_quantity_unit');
            $appData->fuel_quantity_duration = $request->get('fuel_quantity_duration');
            $appData->source_fuel = $request->get('source_of_fuel');
            $appData->liquid_waste = $request->get('quantity_of_daily');
            $appData->waste_discharge = $request->get('location_waste_discharge');
            $appData->emission = $request->get('quantity_of_daily_emission');
            $appData->mode_emission = $request->get('mode_emission_gaseous');



            $appData->vat_file = $request->get('validate_field_vat_Paper');
            $appData->trade_license = $request->get('validate_field_trade_license');
//            $appData->bank_challen_no = $request->get('application_type');
            $appData->noc_file = $request->get('validate_field_noc');
            $appData->renew_old_file = $request->get('validate_field_old_trade_license');

            $appData->file_mouza_map = $request->get('validate_field_mouza_map');
            $appData->land_ownership = $request->get('validate_field_rent_agreement');
            $appData->process_flow = $request->get('validate_field_process_flow');
            $appData->file_approval_doc = $request->get('validate_field_approval_of_rajuk');
            $appData->location_map = $request->get('validate_field_location_map');
            $appData->file_etp = $request->get('validate_field_design_time');
            $appData->file_layout_plan = $request->get('validate_field_layout_plan');
            $appData->file_iee = $request->get('validate_field_iee_report');
            $appData->file_emp = $request->get('validate_field_emp_report');
            $appData->feasibility_report = $request->get('validate_field_feasibility_report');
            $appData->file_city_corporation = $request->get('validate_field_file_city_corporation');
            $appData->file_metropoliton = $request->get('validate_field_file_metropoliton');
            $appData->file_fire_service = $request->get('validate_field_file_fire_service');
            $appData->file_owasa = $request->get('validate_field_file_owasa');
            $appData->file_bidut = $request->get('validate_field_file_bidut');
            $appData->file_titas_gas = $request->get('validate_field_file_titas_gas');
            $appData->file_civil_aviation = $request->get('validate_field_file_civil_aviation');
            $appData->file_fund = $request->get('fund_allocation');
            $appData->file_area = $request->get('area_for_etp');
            $acceptTerms = (!empty($request->get('acceptTerms')) ? 1 : 0);
            $appData->acceptance_of_terms = $acceptTerms;
            $appData->shortfall_comment_file=$request->get('validate_field_shortfall');
            $appData->shortfall_comment_from_user=$request->get('shortfall_comment_from_user');

            if ($request->get('actionBtn') != "draft" && $processData->status_id == 5){
                $appData->base_api_response="";
                $appData->base2_api_response="";
                $appData->doe_file=0;
                $appData->doe_file2=0;
            }
            $appData->save();

            //DOE voucher
            if (!empty($appData->id)){
                $doe_voucherIds = [];
                foreach ($request->voucher_amount as $key => $value) {
                    $DOEVoucherId = $request->get('doe_voucher_id')[$key];
                    $DOEVoucherInfo = DOEVoucher::findOrNew($DOEVoucherId);
                    $DOEVoucherInfo->ref_id = $appData->id;
                    $DOEVoucherInfo->voucher_amount = $request->get('voucher_amount')[$key];

                    if (isset($request->file('voucher_path')[$key])) {
                        $yearMonth = date("Y") . "/" . date("m") . "/";
                        $path = 'uploads/DOE' . $yearMonth;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $_DOEVoucher_file_path = $request->file('voucher_path')[$key];
                        $reg_file_path = trim(uniqid('BIDA-DOE' . $company_id . '-',
                                true) . $_DOEVoucher_file_path->getClientOriginalName());
                        $_DOEVoucher_file_path->move($path, $reg_file_path);
                        $DOEVoucherInfo->voucher_path = $yearMonth . $reg_file_path;
                    } else {
                        $DOEVoucherInfo->voucher_path = $request->get('voucher')[$key];
                    }

                    $DOEVoucherInfo->save();
                    $doe_voucherIds[] = $DOEVoucherInfo->id;
                }
                if (count($doe_voucherIds) > 0) {
                    DOEVoucher::where('ref_id', $appData->id)
                        ->whereNotIn('id', $doe_voucherIds)
                        ->delete();
                }
            }


            if ($request->get('actionBtn') == "draft" && $appData->status_id != 10) {
                $processData->desk_id = 0;
                $processData->status_id = -1;
            }else{
                if ($processData->status_id == 5) { //resubmit
                    $processData->status_id = 10;
                    $processData->desk_id = 0;
                    $processData->process_desc = 'Re-submitted form applicant';
                    $this->updateResubmitData($appData->id);
                    $this->ShortFallData($appData->id);

                }else{

                    $processData->status_id = -1;
                    $processData->desk_id = 0;
                }

            }

            $processData->ref_id =$appData->id;
            $processData->company_id = $company_id;
            $processData->process_type_id = $this->process_type_id;
            $processData->submitted_at = Carbon::now();
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 10 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    $servertype = '';
                    if (env('server_type', 'local') == 'live') {
                        $servertype = '';
                    }elseif(env('server_type') == 'local'){
                        $servertype = 'L';
                    }elseif(env('server_type') == 'uat'){
                        $servertype = 'U';
                    }
                    elseif(env('server_type') == 'training'){
                        $servertype = 'U';
                    }else{
                        $servertype = 'L';
                    }
                    $trackingPrefix = "BIDA-DOE$servertype-" . date("dMY") . '-';
                    $processTypeId = $this->process_type_id;
                    $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");

                }
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id == 5){
//                DB::commit();
                $this->DOERequestToJsonResubmit($request,$appData->id);
            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5){
                $paymentInfo = DoePaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->district_id = $appData->submitting_office_id;
                if($appData->certificate_type_name =='EIA_Approval' || $appData->certificate_type_name =='TOR_Approval' || $appData->certificate_type_name == 'Zero_discharged_Approval'){
                    $paymentInfo->status = 1;
                }else{
                    $paymentInfo->status = 0;
                }
                $paymentInfo->save();
            }
            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 10) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }


            DB::commit();


            if ($request->get('actionBtn') == "draft") {
                return redirect('doe/list/'.Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 10){
                return redirect('doe/list/'.Encryption::encodeId($this->process_type_id));
            }
            return redirect('licence-applications/doe/check-payment/' . Encryption::encodeId($appData->id));

        } catch (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    public function waitForPayment($applicationId){
        $id = Encryption::decodeId($applicationId);
//        $trackingNo = ProcessList::where('ref_id',$id)->where('process_type_id',$this->process_type_id)->value('tracking_no');
        $appInfo = ProcessList::leftJoin('doe_master as apps', 'apps.id', '=', 'process_list.ref_id')
            ->where('process_list.ref_id', $id)
            ->where('process_list.process_type_id', $this->process_type_id)
            ->first(['process_list.tracking_no',
                'apps.*',
            ]);
        $serviceName = ProcessType::where('id',$this->process_type_id)->first(['process_supper_name','process_sub_name']);
        return view("DOE::waiting-for-payment", compact('applicationId', 'appInfo','serviceName'));
    }
    public function checkPayment(Request $request){
        $application_id = Encryption::decodeId($request->enc_app_id);

        $doePaymentInfo = DoePaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $status = intval($doePaymentInfo->status);
        if ($status == 1){
            $applyPaymentfee = DOE::where(['id' =>$application_id])->first(['total_fee','vat_amount']);
            $ServicepaymentData =ApiStackholderMapping:: where(['process_type_id'=>$this->process_type_id])->first(['amount']);
            $paymentInfo =  view(
                "DOE::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($doePaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($doePaymentInfo->id), 'status' => 0, 'message' => 'Connecting to DOE server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($doePaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($doePaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($doePaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified','paymentInformation'=>$paymentInfo]);
        }
    }

    public function doePayment(Request $request){
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = DOE::find($appId);
        $processData =ProcessList::where('ref_id',$appId)
                                ->where('process_type_id',$this->process_type_id)
                                ->first();
        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [DOE-1123]");
                return redirect()->back()->withInput();
            }

        $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                ->where('is_active', 1)
                ->where('process_type_id', $this->process_type_id)
                ->get([
                    'receiver_account_no',
                    'amount',
                    'distribution_type',
                ])->toArray();
            if(!in_array($appInfo->certificate_type_name ,['EIA_Approval','TOR_Approval','Zero_discharged_Approval'])){
                $doePaymentInfo = DoePaymentInfo::where('ref_id',$appId)->first();
                $paymentResponse = json_decode($doePaymentInfo->response);
                $doeAccount = $paymentResponse->data->challan_code;
                $doeVatAccount = $paymentResponse->data->vat_no;

                $doeAmount = $appInfo->total_fee;
                $doeVatAmount = $appInfo->vat_amount;

                $doePaymentInfo = array(
                    'receiver_account_no' => $doeAccount,
                    'amount' => $doeAmount,
                    'distribution_type' => $stackholderDistibutionType,
                    'm_category'=>'CHL'
                );

                $stackholderMappingInfo[] =$doePaymentInfo;

                $doeVatInfo = array(
                    'receiver_account_no' => $doeVatAccount,
                    'amount' => $doeVatAmount,
                    'distribution_type' => $stackholderDistibutionType,
                    'm_category'=>'CHL'
                );

                $stackholderMappingInfo[] =$doeVatInfo;
            }
            $stackholderMappingInfo =array_reverse($stackholderMappingInfo);

            $pay_amount = 0;
            $account_no = "";
            foreach ($stackholderMappingInfo as $data) {
                $pay_amount += $data['amount'];
                $account_no .= $data['receiver_account_no'] . "-";
            }

            $account_numbers = rtrim($account_no, '-');

            // Get SBL payment configuration
            $rand = str_pad($this->process_type_id.$appInfo->id, 10, "0", STR_PAD_LEFT );
            $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appInfo->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appInfo->id;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->receiver_ac_no = $account_numbers;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->ref_tran_no = $processData->tracking_no."-01";
            $paymentInfo->pay_amount = $pay_amount;
            $paymentInfo->contact_name = $request->get('sfp_contact_name');
            $paymentInfo->contact_email = $request->get('sfp_contact_email');
            $paymentInfo->contact_no = $request->get('sfp_contact_phone');
            $paymentInfo->address = $request->get('sfp_contact_address');
            $paymentInfo->sl_no = 1; // Always 1

            $paymentInsert = $paymentInfo->save();


            DOE::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
            $sl = 1;
            StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();
            foreach ($stackholderMappingInfo as $data) {
                $paymentDetails = new StackholderSonaliPaymentDetails();
                if(isset($data['m_category']) && $data['m_category'] =='CHL'){
                    $paymentDetails->purpose_sbl = 'CHL';
                }else{
                    $paymentDetails->purpose_sbl ='TRN';
                }
                $paymentDetails->payment_id = $paymentInfo->id;
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

        ///////////////////// stockholder Payment End//////////////////////////
    }

    public function DOERequestToJson($app_id)
    {
        $doeData = DOE::where('id',$app_id)->first();

        $form_1 = [
            'industryId' => $doeData->industry_id,
            'industryOther' => $doeData->industry_other,
            'certificateType' => $doeData->certificate_type_name,
            'categoryId' => $doeData->category_id,
            'applicationType' => $doeData->application_type,
            'entreprenureName' => $doeData->entrepreneur_name,
            'entreprenureDesignation' => $doeData->entrepreneur_designation,
            'phone' => $doeData->phone,
            'email' => $doeData->email,
            'mobile' => $doeData->mobile,
            'investment' => $doeData->investment,
            'land' => $doeData->land,
            'landUnit' => $doeData->land_unit,
            'manpower' => $doeData->manpower,
            'feeCategoryId' => $doeData->fee_category_id,
            'feeId' => $doeData->fee_id,
//            'feeType' => ,
            'totalFee' => $doeData->total_fee,
            'projectName' => $doeData->project_name,
            'productName' => $doeData->product_name,
            'districtId' => $doeData->district_id,
            'thanaId' => $doeData->thana_id,
            'branchId' => $doeData->submitting_office_id,
            'location' => $doeData->location,
        ];

        $form_2 = [
            'presentAddress' => $doeData->location,
            'startConstruction' => $doeData->start_construction !='0000-00-00'?date('Y-m-d', strtotime($doeData->start_construction)):'0000-00-00',
            'completionConstruction' =>$doeData->completion_construction !='0000-00-00'? date('Y-m-d', strtotime($doeData->completion_construction)):'0000-00-00',
            'trialProduction' => $doeData->trial_production !='0000-00-00'? date('Y-m-d', strtotime($doeData->trial_production)):'0000-00-00',
            'startOperation' => $doeData->start_operation !='0000-00-00'? date('Y-m-d', strtotime($doeData->start_operation)):'0000-00-00',
            'estartOperation' => ($doeData->estart_operation !='0000-00-00' ? date('Y-m-d', strtotime($doeData->estart_operation)) : '0000-00-00'),
            'etrialProduction' => ($doeData->etrial_production !='0000-00-00'? date('Y-m-d', strtotime($doeData->etrial_production)) : '0000-00-00'),
            'nameProduction' => ucwords($doeData->name_production_quantity_duration),
            'nameProductionQuantity' => $doeData->name_production_quantity,
            'nameProductionQuantityDuration' => ucwords($doeData->name_production_quantity_duration),
            'rawMaterilsQuantity' => $doeData->raw_materils_quantity,
            'rawMaterilsQuantityDuration' => ucwords($doeData->raw_materils_quantity_duration),
            'sourceRawMaterial' => $doeData->source_raw_material,
            'quantityWater' => $doeData->quantity_water,
            'sourceWater' => $doeData->source_water,
            'nameOfFuel' => $doeData->name_of_fuel,
            'fuelQuantity' => $doeData->fuel_quantity,
            'quantityWaterUnit' => $doeData->quantity_water_unit,
            'fuelQuantityUnit' => $doeData->fuel_quantity_unit,
            'fuelQuantityDuration' => ucwords($doeData->fuel_quantity_duration),
            'sourceFuel' => $doeData->source_fuel,
            'liquidWaste' => $doeData->liquid_waste,
            'wasteDischarge' => $doeData->waste_discharge,
            'emission' => $doeData->emission,
            'modeEmission' => $doeData->mode_emission,
            'nameProductionQuantityUnit' => $doeData->name_production_quantity_unit,
            'rawMaterilsQuantityUnit' => $doeData->raw_materils_quantity_unit,
            'bankName' => '',
            'branchName' => '18',
            'code' => '',
        ];

        $form_1_json = json_encode($form_1, JSON_UNESCAPED_UNICODE);
        $form_2_json = json_encode($form_2, JSON_UNESCAPED_UNICODE);

        $doe_api_request = DOEAPIRequest::firstOrNew(['ref_id' =>$app_id]);
        $doe_api_request->ref_id = $app_id;
        $doe_api_request->type = 'SUBMISSION_REQUEST';
        $doe_api_request->request_json_form_1 = $form_1_json;
        $doe_api_request->request_json_form_2 = $form_2_json;
        $doe_api_request->status_form_1 = 0;
        $doe_api_request->status_form_2 = 0;
        $doe_api_request->save();
    }

    public function DOERequestToJsonResubmit($request, $app_id)
    {
        $industry_id = explode('@', $request->get('industry_id'));
        $certificate_type = explode('@', $request->get('certificate_type'));
        $fee_category_id = explode('@', $request->get('fee_category_id'));
        $fee_id = explode('@', $request->get('fee_id'));
        $district_id = explode('@', $request->get('district_id'));
        $thana = explode('@', $request->get('thana'));
        $branch_id = explode('@', $request->get('submitting_office'));

        $form_1 = [
            'industryId' => $industry_id[0],
            'industryOther' => $request->industry_other,
            'certificateType' => $certificate_type[0],
            'categoryId' => $request->category_id,
            'applicationType' => $request->application_type,
            'entreprenureName' => $request->entrepreneur_name,
            'entreprenureDesignation' => $request->entrepreneur_designation,
            'phone' => $request->phone_number,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'investment' => $request->total_investment,
            'land' => $request->land,
            'landUnit' => $request->land_unit,
            'manpower' => $request->total_manpower,
            'feeCategoryId' => $fee_category_id[0],
            'feeId' => $fee_id[0],
//            'feeType' => ,
            'totalFee' => $request->total_fee,
            'projectName' => $request->project_name,
            'productName' => $request->project_activity,
            'districtId' => $district_id[0],
            'thanaId' => $thana[0],
            'branchId' => $branch_id[0],
            'location' => $request->project_address,
        ];

        $form_2 = [
            'presentAddress' => $request->project_address,
            'startConstruction' => date('Y-m-d', strtotime($request->start_construction)),
            'completionConstruction' => date('Y-m-d', strtotime($request->completion_construction)),
            'trialProduction' => date('Y-m-d', strtotime($request->trial_production)),
            'startOperation' => date('Y-m-d', strtotime($request->start_operation)),
            'estartOperation' => (isset($request->trial_production) ? date('Y-m-d', strtotime($request->trial_production)) : '00-00-0000'),
            'etrialProduction' => (isset($request->start_operation) ? date('Y-m-d', strtotime($request->start_operation)) : '00-00-0000'),
            'nameProduction' => $request->name_production_quantity_duration,
            'nameProductionQuantity' => $request->production_quantity,
            'nameProductionQuantityDuration' => $request->name_production_quantity_duration,
            'rawMaterilsQuantity' => $request->raw_materials,
            'rawMaterilsQuantityDuration' => $request->raw_materials_duration,
            'sourceRawMaterial' => $request->source_raw,
            'quantityWater' => $request->quantity_water,
            'sourceWater' => $request->source_of_water,
            'nameOfFuel' => $request->name_of_fuel,
            'fuelQuantity' => $request->fuel_quantity,
            'quantityWaterUnit' => $request->quantity_water_unit,
            'fuelQuantityUnit' => $request->fuel_quantity_unit,
            'fuelQuantityDuration' => $request->fuel_quantity_duration,
            'sourceFuel' => $request->source_of_fuel,
            'liquidWaste' => $request->quantity_of_daily,
            'wasteDischarge' => $request->location_waste_discharge,
            'emission' => $request->quantity_of_daily_emission,
            'modeEmission' => $request->mode_emission_gaseous,
            'nameProductionQuantityUnit' => $request->name_production_quantity_unit,
            'rawMaterilsQuantityUnit' => $request->raw_materials_unit,
            'bankName' => '',
            'branchName' => '18',
            'code' => '',
        ];

        $form_1_json = json_encode($form_1, JSON_UNESCAPED_UNICODE);
        $form_2_json = json_encode($form_2, JSON_UNESCAPED_UNICODE);

        $doe_api_request = DOEAPIRequest::firstOrNew(['ref_id' =>$app_id]);
        $doe_api_request->ref_id = $app_id;
        $doe_api_request->type = 'SUBMISSION_REQUEST';
        $doe_api_request->request_json_form_1 = $form_1_json;
        $doe_api_request->request_json_form_2 = $form_2_json;
        $doe_api_request->status_form_1 = 0;
        $doe_api_request->status_form_2 = 0;
        $doe_api_request->save();
    }

    public function getDOEDistricts(){
        $doe_api_url = config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."district",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $districts =[];
        foreach($results as $result){
            $districts += [ $result["id"]."@".$result["name"] => $result["name"] ];
        }
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    public function getDOEThanaByDistrictId(Request $request){
        $district_id = $request->get('districtId');

        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."thana/".$district_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $districts =[];
        foreach($results as $result){
            $districts += [ $result["id"]."@".$result["name"] => $result["name"] ];
        }
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }


    // Confusion
    public function getDOEFeeCategories(){
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."fee-category",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $feeCategories =[];
        foreach($results as $result){
            $feeCategories += [ $result["id"]."@".$result["name"] => $result["name"] ];
        }
        $feeCategories =['1@Industry' =>'Industry','2@Brick'=>'Brick'];
        $data = ['responseCode' => 1, 'data' => $feeCategories];
        return response()->json($data);
    }

    public function getDOEFeeByCategoryId(Request $request){
        $feeCategoryId = $request->get('feeCategoryId');

        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."fee/".$feeCategoryId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $fees =[];
        foreach($results as $result){
            $fees []= [
                'id' => $result["id"]."@".$result["from_amount"].' '.$result["to_amount"],
                'label' => $result["from_amount"].' '.$result["to_amount"],
                'fee' => $result["fee"],
                'renew_fee' => $result["renew_fee"],
            ];
        }
        $data = ['responseCode' => 1, 'data' => $fees];
        return response()->json($data);
    }

    public function getDOESubmittingOffice(Request $request){
        $districtId = $request->get('districtId');
        $thanaId = $request->get('thanaId');
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."submitting-office/".$districtId."/".$thanaId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $submitting_offices =[];
        $submitting_offices += [ $results["id"]."@".$results["name"] => $results["name"] ];
        $data = ['responseCode' => 1, 'data' => $submitting_offices];
        return response()->json($data);
    }

    public function storefirstpart(Request $request){
        return \redirect()->to('/process/doe/view/'.Encryption::encodeId(325).'/'.Encryption::encodeId($this->process_type_id));
    }

    public function get_thana_by_district_id(Request $request)
    {
        $district_id = $request->get('districtId');

        $thanas = AreaInfo::where('PARE_ID', $district_id)->orderBy('AREA_NM', 'ASC')->pluck('AREA_NM', 'AREA_ID')->toArray();
        $data = ['responseCode' => 1, 'data' => $thanas];
        return response()->json($data);
    }


    public function uploadDocument()
    {
        return View::make('DOE::ajaxUploadFile');
    }


    /*api */

    public function getApplicationType(){
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."application-type",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
//        dd($results);
        $applicationType =[];
        foreach($results as $result){
            $applicationType += [ $result["id"]."@".$result["typeId"] => $result["typeName"] ];
        }
        $data = ['responseCode' => 1, 'data' => $applicationType];
        return response()->json($data);

    }

    public function getIndustry(){
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."industry",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
//        dd($results);
        $industry =[];
        foreach($results as $result){
            $industry += [ $result["id"]."@".$result["industry_type"] => $result["industry_type"] ];
        }
        $data = ['responseCode' => 1, 'data' => $industry];
        return response()->json($data);

    }
    public function getCagegoryByindustryId(Request $request){
        $catid = $request->get('industry_id');
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."industry/category/".$catid,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];
        $industry =[];
        if ($results != null)
            $industry = $results;

        $data = ['responseCode' => 1, 'data' => $industry];
        return response()->json($data);

    }

    public function getCategories()
    {
        $doe_api_url =config('stackholder.doe_api_url');
        // Get token for API authorization
        $token = $this->getDOEToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $doe_api_url."category",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);
        $results = $decoded_response['data'];

        $categories =[];
        if ($results != null)
            $categories = $results;
        $data = ['responseCode' => 1, 'data' => $categories];
        return response()->json($data);
    }

    public function getDOEToken(){
        if (Cache::has('doe-client-token')) {
            return Cache::get('doe-client-token');
        }
        // Get credentials from env
        $doe_idp_url = config('stackholder.doe_idp_url');
        $doe_client_id = config('stackholder.doe_client_id');
        $doe_client_secret = config('stackholder.doe_client_secret');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $doe_client_id,
            'client_secret' => $doe_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$doe_idp_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if(!$result){
            $data = ['responseCode' => 0, 'msg' => 'Area API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result,true);
        $token = $decoded_json['access_token'];
        $expired_time = config('stackholder.doe_token_expired_time');
        Cache::put('doe-client-token', $token, Carbon::now()->addMinute($expired_time));

        return $token;
    }



    public function afterPayment($payment_id)
    {
        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }

            //decode and get payment info from payment_id
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);

            //get process list
            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name', 'process_type.process_sub_name',
                    'process_list.*'
                ]);

            // declear but no use
            $applicantEmailPhone = UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);

            // declear but no use
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

            //declear but no use
            $verification_response = json_decode($paymentInfo->verification_response);

            //prepare data for curl 
            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            $account_num = $spg_conf->details;
            $lopt_url = $spg_conf->value;
            $userName = Config('payment.spg_settings_stack_holder.user_id');
            $password = Config('payment.spg_settings_stack_holder.password');
            $ownerCode = Config('payment.spg_settings_stack_holder.st_code');
            $referenceDate = $paymentInfo->payment_date;
            $requiestNo = $paymentInfo->request_id;


            //curl request to get payment info from api
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

            if(empty($response)){
                $err = curl_error($curl);
                Log::error("error at DOEController@afterPayment curl:- " . json_encode($err ));
                Session::flash('error', 'Something went wrong!, no response from after payment.');
                return redirect('doe/list/' . Encryption::encodeId($this->process_type_id));
            }

            curl_close($curl);

            // $data=$response;
            $data1 = json_decode($response);
            $data2 = json_decode($data1);

            // $rData0['file_no'] = 133;
            // $rData0['reg_no'] = 33;
            // $rData0['branch_code'] = $verification_response->BrCode;

            // mapping data from api response
            foreach ($data2 as $value) {
                if ($value->TranAccount != $account_num) {
                    $rData0['account_info'][] = [
                        'account_no' => $value->TranAccount,
                        'particulars' => $value->ReferenceNo,
                        'balance' => 0,
                        'deposit' => $value->TranAmount,
                        'tran_date' => $value->TransactionDate,
                        'tran_id' => $value->TransactionId,
                        'scrl_no' => !empty($value->SCRL_NO) ? $value->SCRL_NO : null
                    ];
                }
            }

            DB::beginTransaction();

            // govt fee
            if ($paymentInfo->payment_category_id == 3) {
                $processData->status_id = 16;
                $processData->desk_id = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                DOE::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                //form 1 and from 2 json generate
                $this->DOERequestToJson($processData->ref_id);

                $processData->save();
            }

            $appData = DOE::where('id', $processData->ref_id)->first();

            // update doe payment database
            if ((!in_array($appData->certificate_type_name, ['EIA_Approval', 'TOR_Approval', 'Zero_discharged_Approval']) && $paymentInfo->payment_category_id == 3) || $paymentInfo->payment_category_id == 2) {
                $doePaymentConfirm = new DoePaymentConfirm();
                $doePaymentConfirm->request = json_encode($rData0);
                $doePaymentConfirm->ref_id = $paymentInfo->app_id;
                $doePaymentConfirm->tracking_no = $processData->tracking_no;
                $doePaymentConfirm->save();
            }

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('doe/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error at DOEController@afterPayment:- " . json_encode($e->getMessage() ));
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('doe/list/' . Encryption::encodeId($this->process_type_id));
        }
    }// end -:- afterPayment()



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
                if ($paymentInfo->payment_category_id == 3) { //govt fee

                    $processData->status_id = 16;
                    $processData->desk_id = 0;
                    $processData->read_status = 0;

                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

                    DOE::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    //form 1 and from 2 json generate
                    $this->DOERequestToJson($processData->ref_id);

                }
                $processData->process_desc = 'Counter Payment Confirm';
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $verification_response = json_decode($paymentInfo->offline_verify_response);

                SonaliPaymentStackHolders::where('id', $payment_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

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

//dd($response);

                $account_num=$spg_conf->details;
//            $data=$response;
                $data1=json_decode($response);
                $data2=json_decode($data1);
                $rData0['file_no'] = 133;
                $rData0['reg_no'] = 33;
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
                            'tran_id'=>$value->TransactionId,
                            'scrl_no'=>!empty($value->SCRL_NO)?$value->SCRL_NO:null
                        ];

                    }

                }

                $doePaymentConfirm = new DoePaymentConfirm();
                $doePaymentConfirm->request = json_encode($rData0);
                $doePaymentConfirm->ref_id = $paymentInfo->app_id;
                $doePaymentConfirm->tracking_no = $processData->tracking_no;
                $doePaymentConfirm->save();

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
            return redirect('doe/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('doe/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    public function updateResubmitData($app_id){
       $requestData = DOEAPIRequest::where('ref_id',$app_id)->first();
       $requestData->TYPE='RESUBMISSION_REQUEST';
       $requestData->response_form1="";
       $requestData->form1_processing_at="";
       $requestData->response_form2="";
       $requestData->form2_processing_at="";
       $requestData->status_form_1= 0;
       $requestData->status_form_2=0;
       $requestData->final_processing_at="";
       $requestData->final_response="";
       $requestData->final_status=0;
       $requestData->request_info1="";
       $requestData->request_info2="";
       $requestData->request_info3="";
       $requestData->save();
    }

    public function ShortFallData($app_id){
        $doedata = DOE::where('id',$app_id)->first();
        if($doedata->shortfall_comment_from_user !==null || $doedata->shortfall_comment_file !==null ){
            $shortfall = new DOEShortfall();
            $shortfall->ref_id = $app_id;
            $shortfall->request = "";
            $shortfall->response = "";
            $shortfall->status = 0;
            $shortfall->save();
        }

    }

    public function viewComments($appid){
        $applicationId = Encryption::decodeId($appid);
        $process_type_id = $this->process_type_id;
        $appInfo = ProcessList::leftJoin('doe_master as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
            ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
            ->where('process_list.ref_id', $applicationId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.locked_by',
                'process_list.locked_at',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'process_list.process_desc',
                'process_list.priority',
                'process_list.submitted_at',
                'process_list.process_desc',
                'user_desk.desk_name',
                'ps.status_name',
                'ps.color',
                'apps.*'
            ]);
        $additionalPayment = SonaliPaymentStackHolders::where('app_id',$applicationId)
            ->where('payment_category_id',2)
            ->where('process_type_id',$this->process_type_id)
            ->whereIn('payment_status', [1, 3])
            ->get([
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
//        $applicationId = 12;
        $comments = DoeComment::where('ref_id',$applicationId)
            ->orderBy('id','desc')
            ->get();
        $adminComment = [];
        $entComment = [];
        $all_comments =[];
        if(count($comments)>0){

                foreach ($comments as $key=>$value){
                    if($value->sender_type =='entreprenure'){
                        $entComment[]=$value;
                    }elseif ($value->sender_type =='admin'){
                        $adminComment []=$value;
                    }
                }
            }



        return view("DOE::comments", compact('applicationId', 'appInfo','comments','adminComment','entComment','additionalPayment'));

    }

public function storeComment(Request $request){
    $app_id = (!empty($request->get('app_id_resubmit')) ? Encryption::decodeId($request->get('app_id_resubmit')) : '');

    if ($app_id == ''){
        Session::flash('error', 'Something Wrong [DOE-102]');
        return Redirect::back()->withInput();
    }
    $appData = DOE::find($app_id);

    $appData->shortfall_comment_from_user=$request->get('shortfall_comment_from_user');
    $comments_file = $request->file('shortfall_comment_file');

    if (isset($comments_file)) {
        if ($request->hasFile('shortfall_comment_file')) {
            $yearMonth = date("Y") . "/" . date("m") . "/";
            $path = 'uploads/' . $yearMonth;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }

            $reg_file_path = trim(uniqid('BIDA-DOE-comments' . '-',
                    true) . $comments_file->getClientOriginalName());
            $comments_file->move($path, $reg_file_path);
            $appData->shortfall_comment_file = $yearMonth . $reg_file_path;
        }
    } else {
        $appData->shortfall_comment_file = null;
    }
    $appData->shortfall_request_from_user =10;

    $appData->save();
    Session::flash('success', 'Comment successfully submitted');
    return \redirect()->to('/doe/view-comments/'.Encryption::encodeId($app_id));
}

public function viewChangesInfo($appid){
    $applicationId = Encryption::decodeId($appid);
    $process_type_id = $this->process_type_id;
    $appInfo = ProcessList::leftJoin('doe_master as apps', 'apps.id', '=', 'process_list.ref_id')
        ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
        ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })
        ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.sf_payment_id')
        ->where('process_list.ref_id', $applicationId)
        ->where('process_list.process_type_id', $process_type_id)
        ->first([
            'process_list.id as process_list_id',
            'process_list.desk_id',
            'process_list.process_type_id',
            'process_list.status_id',
            'process_list.locked_by',
            'process_list.locked_at',
            'process_list.ref_id',
            'process_list.tracking_no',
            'process_list.company_id',
            'process_list.process_desc',
            'process_list.priority',
            'process_list.submitted_at',
            'process_list.process_desc',
            'user_desk.desk_name',
            'ps.status_name',
            'ps.color',
            'apps.*'
        ]);
    $changes_json = DoeAppChangeInfo::where('ref_id',$applicationId)
        ->orderBy('id','desc')
        ->value('change_info_json');
    $changes =[];
    if (isset($changes_json)){
        $changes = json_decode($changes_json);
    }
    return view("DOE::changesInfo", compact('applicationId', 'appInfo','changes','changes_json'));
}



public function additionalpayment(Request $request){
    $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
    $appId = Encryption::decodeId($request->get('app_id'));
    $appInfo = DOE::find($appId);
    $processData =ProcessList::where('ref_id',$appId)
        ->where('process_type_id',$this->process_type_id)
        ->first();
    $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
        ->where([
            'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
            'api_stackholder_payment_configuration.payment_category_id' => 3,
            'api_stackholder_payment_configuration.status' => 1,
            'api_stackholder_payment_configuration.is_archive' => 0,
        ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
    if (!$payment_config) {
        Session::flash('error', "Payment configuration not found [DOE-1123]");
        return redirect()->back()->withInput();
    }

    $stackholderMappingInfo = [];

        $doePaymentInfo = DoePaymentInfo::where('ref_id',$appId)->first();
        if (!$doePaymentInfo) {
            Session::flash('error', "Payment response not found [DOE-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($doePaymentInfo->response);
          $doeAccount = $paymentResponse->data->challan_code;
          $doeVatAccount = $paymentResponse->data->vat_no;

        $doeAmount = $request->get('payamount');
        $doeVatAmount = ($doeAmount*0.15);

        $doePaymentInfo = array(
            'receiver_account_no' => $doeAccount,
            'amount' => $doeAmount,
            'm_category'=>'CHL',
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $doePaymentInfo;

        $doeVatInfo = array(
            'receiver_account_no' => $doeVatAccount,
            'amount' => $doeVatAmount,
            'm_category'=>'CHL',
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $doeVatInfo;

    $pay_amount = 0;
    $account_no = "";
    foreach ($stackholderMappingInfo as $data) {
        $pay_amount += $data['amount'];
        $account_no .= $data['receiver_account_no'] . "-";
    }

    $account_numbers = rtrim($account_no, '-');

    // Get SBL payment configuration
    $paymentInfo = new SonaliPaymentStackHolders();
    $paymentInfo->payment_config_id = $payment_config->id;
    $paymentInfo->app_id = $appInfo->id;
    $paymentInfo->process_type_id = $this->process_type_id;
    $paymentInfo->app_tracking_no = '';
    $paymentInfo->receiver_ac_no = $account_numbers;
    $paymentInfo->payment_category_id = 2;
    $paymentInfo->ref_tran_no = $processData->tracking_no."-02";
    $paymentInfo->pay_amount = $pay_amount;
    $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
    $paymentInfo->contact_name = $request->get('sfp_contact_name');
    $paymentInfo->contact_email = $request->get('sfp_contact_email');
    $paymentInfo->contact_no = $request->get('sfp_contact_phone');
    $paymentInfo->address = $request->get('sfp_contact_address');
    $paymentInfo->sl_no = 1; // Always 1
    $paymentInsert = $paymentInfo->save();

    $sl = 1;
    StackholderSonaliPaymentDetails::where('payment_id', $paymentInfo->id)->delete();
    foreach ($stackholderMappingInfo as $data) {
        $paymentDetails = new StackholderSonaliPaymentDetails();
        $paymentDetails->payment_id = $paymentInfo->id;
        if(isset($data['m_category']) && $data['m_category'] =='CHL'){
            $paymentDetails->purpose_sbl = 'CHL';
        }else{
            $paymentDetails->purpose_sbl ='TRN';
        }
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
    if ($request->get('actionBtn') == 'Submit Payment' && $paymentInsert) {
        return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
    }else{
        Session::flash('Error', 'Additional payment error DOE-221!');
        return \redirect()->back();
    }

    ///////////////////// stockholder Payment End//////////////////////////
    }
    public function getRefreshToken()
    {
        $token = $this->getDOEToken();
        return response($token);
    }

}
