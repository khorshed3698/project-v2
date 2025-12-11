<?php

namespace App\Modules\FscdNocProposed\Controllers;

use App\Jobs\FscdNocProposedJob\ApplicantRegistration;
use App\Modules\Apps\Models\AppDocumentStakeholder;
use App\Modules\FscdNocProposed\Models\FscdNocProposedAppStatus;
use App\Modules\FscdNocProposed\Models\FscdNocProposed;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class FscdNocProposedController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 129; // BFSCD Proposed
        $this->aclName = 'BfscdNocProposed';
    }

    public function appForm() {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [DOE-60]</h4>"]);
        }

        try {

            $authUserId = CommonFunction::getUserId();
            $company_id = CommonFunction::getUserWorkingCompany();
            $data = [];
            $data['payment_config'] = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $data['token'] = $this->getToken();
            $data['bfscd_proposed_service_url'] = config('stakeholder.bfcdc.proposed.service_url');
            $data['agent_id'] = Config('stakeholder.agent_id');
            $data['client_id'] = Config('stakeholder.cda.oc.client');

            $data['logged_user_info'] = Users::where('id', $authUserId)->first();

            $data['viewMode'] = 'off';
            $data['mode'] = '-A-';
//            $token = $this->getDOEToken();
//            return view("FscdNocProposed::application-form", $data);
            $public_html = strval(view("FscdNocProposed::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());

            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight('BfscdNocProposed', '-A-')) {
            abort('400', 'You have no access right! Contact with system admin for more information.[FSCD NOC Proposed-113]');
        }
        $rules = [];
        $messages = [];

        if($request->get('actionBtn') != "draft"){

            $rules['engineer_reg_no'] = 'required';
            $messages['engineer_reg_no.required'] = 'Engineers registration number field is required';
            $rules['nearby_district'] = 'required';
            $messages['nearby_district.required'] = 'Nearby Fire station District field is required';
            $rules['owner_thana'] = 'required';
            $messages['owner_thana.required'] = 'Details of the owner of the proposed Building / Institution section Thana/Upozila field is required';
            $rules['main_road_width'] = 'required';
            $messages['main_road_width.required'] = 'Width of main road adjacent to the plot (m) field is required';
            $rules['nearby_tahan'] = 'required';
            $messages['nearby_tahan.required'] = 'Nearby Fire station Thana field is required';
            $rules['engineer_name'] = 'required';
            $messages['engineer_name.required'] = 'Name and address of the engineer field is required';
            $rules["south_side"] = "required";
            $messages["south_side.required"] = "Whats in the South ? field is required";
            $rules["recommended_road"] = "required";
            $messages["recommended_road.required"] = "Recommended road length inside the plot (m) field is required";
            $rules["plot_connecting"] = "required";
            $messages["plot_connecting.required"] = "Plot connection road width (m.) field is required";
            $rules["west_side"] = "required";
            $messages["west_side.required"] = "Whats in the West ? field is required";
            $rules["city_corporation"] = "required_if:council,city_corporation@সিটি কর্পোরেশন";
            $messages["city_corporation.required_if"] = "City Corporation field is required";
            $rules["council"] = "required";
            $messages["council.required"] = "City Corporation / Municipality / Union Parishad field is required";
            $rules["number_of_building"] = "required";
            $messages["number_of_building.required"] = "Number of buildings field is required";
            $rules["proposed_building_district"] = "required";
            $messages["proposed_building_district.required"] = "District of the proposed building field is required";
            $rules["north_side"] = "required";
            $messages["north_side.required"] = "Whats in the North ? field is required";
            $rules["owner_district"] = "required";
            $messages["owner_district.required"] = "Owner District field is required";
            $rules["proposed_building_thana"] = "required";
            $messages["proposed_building_thana.required"] = "Thana/Upozila of the proposed building field is required";
            $rules["architect_name"] = "required";
            $messages["architect_name.required"] = "Name and address of the architect field is required";
            $rules["owner_name"] = "required";
            $messages["owner_name.required"] = "Owner Name field is required";
            $rules["main_street_name"] = "required";
            $messages["main_street_name.required"] = "Name of the main street adjacent to the plot field is required";
            $rules["nearby_fire_station"] = "required";
            $messages["nearby_fire_station.required"] = "Fire station field is required";
            $rules["architect_reg_no"] = "required";
            $messages["architect_reg_no.required"] = "Architects registration number field is required";
            $rules["user_email"] = "required";
            $messages["user_email.required"] = "Contact email field is required";
            $rules["owner_address"] = "required";
            $messages["owner_address.required"] = "Owner Address field is required";
            $rules["length_connection"] = "required";
            $messages["length_connection.required"] = "Length of plot connecting road (m) field is required";
            $rules["proposed_building_division"] = "required";
            $messages["proposed_building_division.required"] = "Division of the Proposed Building field is required";
            $rules["east_side"] = "required";
            $messages["east_side.required"] = "Whats in the East ? field is required";
            $rules["proposed_building_address"] = "required";
            $messages["proposed_building_address.required"] = "Plot Location Address field is required";

            $rules["nearby_division"] = "required";
            $messages["nearby_division.required"] = "Nearby Fire station Division field is required";
            $rules["owner_division"] = "required";
            $messages["owner_division.required"] = "Owner of the proposed building / institution Division field is required";
            $rules["owner_email"] = "required";
            $messages["owner_email.required"] = "Owner Email field is required";


            $rules["sub_station_location"] = "required_if:electrical_station,1";
            $messages["sub_station_location.required_if"] = "Location of sub station field is required";
            $rules["sub_station_room_size"] = "required_if:electrical_station,1";
            $messages["sub_station_room_size.required_if"] = "Sub station room size field is required";
            $rules["number_of_substation"] = "required_if:electrical_station,1";
            $messages["number_of_substation.required_if"] = "Number of sub stations field is required";
            if(isset($request->floor_sub_station_check) && $request->floor_sub_station_check == 1){
                $rules['floor_sub_station'] = "required_if:electrical_station,1";
                $messages['floor_sub_station.required_if'] = 'How many floors will the electric sub station be placed on? field is required';
            }
            $rules["electric_sub_station_kVA"] = "required_if:electrical_station,1";
            $messages["electric_sub_station_kVA.required_if"] = "How many kVA is the electric sub station field is required";
            $rules['adequate_electtrical'] = "required_if:electrical_station,1";
            $messages['adequate_electtrical.required_if'] = 'Whether there is adequate ventilation in the interior of the electrical sub-station room field is required';
            $rules['safety_bestney'] = "required_if:electrical_station,1";
            $messages['safety_bestney.required_if'] = 'Whether the safety bestney is protected by a 4 feet high steel net around the transformer field is required';
            $rules["rain_likely"] = "required_if:electrical_station,1";
            $messages["rain_likely.required_if"] = "Whether flood / rain water is likely to enter inside the electrical substation field is required";
            $rules["properly_fire_rated"] = "required_if:electrical_station,1";
            $messages["properly_fire_rated.required_if"] = "Whether the doors and walls of the electrical substation room are properly fire rated field is required";
            $rules["rubber_mats"] = "required_if:electrical_station,1";
            $messages["rubber_mats.required_if"] = "Whether rubber mats have been properly installed inside the electrical substation room field is required";
            $rules["gas_fire_system"] = "required_if:electrical_station,1";
            $messages["gas_fire_system.required_if"] = "In case of installation of electrical substation inside the building “Inert Gas Fire Suppression System” Whether there are installation arrangements field is required";
            $rules["safety_legend"] = "required_if:electrical_station,1";
            $messages["safety_legend.required_if"] = "Whether the design of the electrical substation is displayed on a separate page with blowup / enlarge and safety legend field is required";





            $rules["owner_phone"] = "required";
            $messages["owner_phone.required"] = "Owner Phone no field is required";


            $rules["number_of_basement"] = "required";
            $messages["number_of_basement.required"] = "Number of Basement Floors field is required";




            foreach ($request->building_construction as $k => $val) {
                $rules["total_flats_number.$k"] = 'required|between:0,9';
                $messages["total_flats_number.$k.required"] = 'Total number of residences / apartments and flats in case of residential building field is required';
                $messages["total_flats_number.$k.between"] = 'Total number of residences / apartments and flats in case of residential building field value must be 0 to 9';
                $rules["description.$k"] = 'required';
                $messages["description.$k.required"] = 'A description of the floors to be used by each use class in the case of mixed class use field is required';
                $rules["building_height.$k"] = 'required';
                $messages["building_height.$k.required"] = 'Building height (m) [If the height of the building is high] field is required';
                $rules["building_use_type.$k"] = 'required';
                $messages["building_use_type.$k.required"] = 'Type of building use field is required';
                $rules["electric_line.$k"] = 'required';
                $messages["electric_line.$k.required"] = 'Whether there are electric high voltage lines on the proposed plot field is required';
                $rules["area_of_eash_floor.$k"] = 'required';
                $messages["area_of_eash_floor.$k.required"] = 'Area of Each Floor (sq. M.) field is required';
                $rules["total_floor_area.$k"] = 'required';
                $messages["total_floor_area.$k.required"] = 'Total floor area (sq. M.) field is required';
                $rules["building_construction.$k"] = 'required';
                $messages["building_construction.$k.required"] = 'Building construction class field is required';
                $rules["number_stairs.$k"] = 'required';
                $messages["number_stairs.$k.required"] = 'Number of Stairs field is required';
                $rules["electric_line_distance.$k"] = 'required';
                $messages["electric_line_distance.$k.required"] = 'The distance of the high voltage line from the plot is horizontal and vertical distance field is required';
                $rules["floor.$k"] = 'required';
                $messages["floor.$k.required"] = 'Floor field is required';
                $rules["building_use.$k"] = 'required';
                $messages["building_use.$k.required"] = 'Class of Building Use field is required';
                $rules["floor_number.$k"] = 'required';
                $messages["floor_number.$k.required"] = 'Number of Floors field is required';
            }

        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            $company_id = CommonFunction::getUserWorkingCompany();
            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = FscdNocProposed::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new FscdNocProposed();
                $processData = new ProcessList();
                $processData->company_id = $company_id;
            }
            $newJson = json_encode($data);

            $appData->appdata = $newJson;
            $appData->save();


            if ($request->get('actionBtn') == "draft" && $appData->status_id != 10) {

                $processData->desk_id = 0;
                $processData->status_id = -1;
            } else {

                if ($processData->status_id == 5) { //resubmit
                    $processData->status_id = 10;
                    $processData->desk_id = 0;
                    $processData->process_desc = 'Re-submitted form applicant';

                } else {

                    $processData->status_id = -1;
                    $processData->desk_id = 0;
                }

            }


            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 10 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;
                    $trackingPrefix = "BIDA-E-NOC-" . date("dMY") . '-';

                    DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-3,3) )+1,1),3,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                }
            }
            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft") {
                $password = rand(1000000000,9999999999);
                $applicant_reg_json = [
                    'bn_name' => Auth::user()->user_first_name,
                    'email' => Auth::user()->user_email,
                    'username' => explode("@",Auth::user()->user_email)[0],
                    'mobile' => Auth::user()->user_phone,
                    'address' => $request->get('user_address'),
                    'password' => $password,
                    'password_confirmation' => $password,
                    'oss_tracking_no' => $tracking_no,
                ];

                $appData->password = $password;
                $appData->applicant_registration_json = json_encode($applicant_reg_json);
                $appData->save();

                $this->submissionJson($appData->id,$tracking_no, $processData->status_id);
            }

            /*stackholder payment start*/
            if ($processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 1,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [Fire Proposed-1123]");
                    return redirect()->back()->withInput();
                }
                $stackholderMappingInfo = ApiStackholderMapping::where('process_type_id', $this->process_type_id)
                    ->where('is_active', 1)
                    ->get([
                        'receiver_account_no',
                        'amount',
                        'distribution_type'
                    ])->toArray();

                $pay_amount = 0;
                $account_no = "";
                $distribution_type = "";
                foreach ($stackholderMappingInfo as $data) {
                    $pay_amount += $data['amount'];
                    $account_no .= $data['receiver_account_no'] . "-";
                    $distribution_type .= $data['distribution_type'];
                }

                $account_numbers = rtrim($account_no, '-');

                // Get SBL payment configuration
                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no . "-01";
                $paymentInfo->pay_amount = $pay_amount;
                $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInsert = $paymentInfo->save();

                $appData->sf_payment_id = $paymentInfo->id;
                $appData->save();

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
                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }
            }
            DB::commit();
            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [FSCD NOC Proposed-1023]');
            }
            return redirect('bfscd-noc-proposed/list/' . Encryption::encodeId($this->process_type_id));

        } catch (\Exception $e) {
//            dd($e->getFile(), $e->getMessage(), $e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [FSCD NOC Proposed-1025]");
            return redirect()->back()->withInput();
        }

    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {

        $data = [];
        $data['mode'] = 'SecurityBreak';
        $data['viewMode'] = 'SecurityBreak';
        if ($openMode == 'view') {
            $data['viewMode'] = 'on';
            $data['mode'] = '-V-';
        } else if ($openMode == 'edit') {
            $data['viewMode'] = 'off';
            $data['mode'] = '-E-';
        }
        if (!ACL::getAccsessRight($this->aclName,  $data['mode'])) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [FSCD NOC Proposed-973]</h4>"
            ]);
        }
        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            $data['appInfo'] = ProcessList::leftJoin('fnoc_proposed_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
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
                    'apps.*',
                    'process_type.max_processing_day',
                ]);

            $data['appData'] = json_decode( $data['appInfo']->appdata);
            $data['token'] = $this->getToken();
            $data['bfscd_proposed_service_url'] = config('stakeholder.bfcdc.proposed.service_url');
            $data['agent_id'] = Config('stakeholder.agent_id');
            $data['client_id'] = Config('stakeholder.cda.oc.client');


            $public_html = strval(view("FscdNocProposed::application-form-edit", $data));
//            return view("FscdNocProposed::application-form-edit",$data);
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
//            dd($e->getFile(), $e->getLine(), $e->getMessage());
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"
            ]);
        }
    }


    public function applicationView($appId, Request $request)
    {
//        if (!$request->ajax()) {
//            return 'Sorry! this is a request without proper way. [FSCD NOC Proposed-1003]';
//        }

        $viewMode = 'on';
        $mode = '-V-';
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [FSCD NOC Proposed-974]</h4>"
            ]);
        }

        try {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            $document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)->where('ref_id', $decodedAppId)->get();

            $appInfo = ProcessList::leftJoin('fnoc_proposed_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
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
                    'apps.*',
                    'process_type.max_processing_day',
                ]);
            $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)
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

            $appData = json_decode($appInfo->appdata);

//            dd($appData);
//            return view("FscdNocProposed::application-form-view", compact('document', 'appInfo', 'appData', 'viewMode', 'mode', 'appId', 'spPaymentinformation'));

            $public_html = strval(view("FscdNocProposed::application-form-view", compact('document', 'appInfo','tl_issued_by', 'appData', 'viewMode', 'mode', 'token', 'ccie_service_url', 'appId', 'spPaymentinformation', 'is_shortfall','resubmittedData','shortfallData')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
//            dd($e->getFile(), $e->getLine(), $e->getMessage());

            \Illuminate\Support\Facades\Log::error('BRViewForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BPDB-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[BPDB-1015]" . "</h4>"
            ]);
        }
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function getToken()
    {
        // Get credentials from database
        $bfcdc_proposed_token_api_url = Config('stakeholder.bfcdc.proposed.token_url');
        $bfcdc_proposed_client_id = Config('stakeholder.bfcdc.proposed.client');
        $bfcdc_proposed_client_secret = Config('stakeholder.bfcdc.proposed.secret');



        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $bfcdc_proposed_client_id,
            'client_secret' => $bfcdc_proposed_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$bfcdc_proposed_token_api_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if (!$result) {
            $data = ['responseCode' => 0, 'msg' => 'API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result, true);
        $token = $decoded_json['access_token'];

        return $token;
    }

    public function submissionJson($app_id, $tracking_no, $status_id)
    {
        $BfsdcRequest = FscdNocProposedAppStatus::firstOrNew([
            'ref_id' => $app_id
        ]);

        if ($BfsdcRequest->status != 1) {
            $appData = FscdNocProposed::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $link .= "://";
            $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';

            $param_1 = [
                "userEmail" => !empty($masterData->user_email) ? $masterData->user_email : '',
                "type" => 'proposed',
                "ownerName" => !empty($masterData->owner_name) ? $masterData->owner_name : '',
                "ownerMobile" => !empty($masterData->owner_phone) ? $masterData->owner_phone : '',
                "ownerEmail" => !empty($masterData->owner_email) ? $masterData->owner_email : '',
                "ownerAddressDivisionId" => !empty($masterData->owner_division) ? explode('@', $masterData->owner_division)[0] : '',
                "ownerAddressDistrictId" => !empty($masterData->owner_district) ? explode('@', $masterData->owner_district)[0] : '',
                "ownerAddressThanaId" => !empty($masterData->owner_thana) ? explode('@', $masterData->owner_thana)[0] : '',
                "ownerAddressDetails" => !empty($masterData->owner_address) ? $masterData->owner_address : '',
                "buildingDivisionId" => !empty($masterData->proposed_building_division) ? explode('@', $masterData->proposed_building_division)[0] : '',
                "buildingDistrictId" => !empty($masterData->proposed_building_district) ? explode('@', $masterData->proposed_building_district)[0] : '',
                "buildingThanaId" => !empty($masterData->proposed_building_thana) ? explode('@', $masterData->proposed_building_thana)[0] : '',
                "buildingNo" => !empty($masterData->buliding_no) ? $masterData->buliding_no : '',
                "roadNo" => !empty($masterData->road_no) ? $masterData->road_no : '',
                "council" => !empty($masterData->council) ? explode('@', $masterData->council)[0] : '',
                "address" => !empty($masterData->proposed_building_address) ? $masterData->proposed_building_address : '',
                "buildings" => !empty($masterData->number_of_building) ? $masterData->number_of_building : '',
            ];

            //council dependent fields
            $param_2 = [];
            if(!empty($masterData->council) && explode('@', $masterData->council)[0] == 'city_corporation'){
                $param_2 = [
                    "cityCorporationId" => !empty($masterData->city_corporation) ? explode('@', $masterData->city_corporation)[0] : '',
                ];
            }elseif(explode('@', $masterData->council)[0] == 'town_council'){
                $param_2 = [
                    "townCouncil" => !empty($masterData->town_council) ? $masterData->town_council : '',
                ];
            }elseif(explode('@', $masterData->council)[0] == 'union_council'){
                $param_2 = [
                    "unionCouncil" => !empty($masterData->union_council) ? $masterData->union_council : '',
                ];
            }

            $buildings = [];
            foreach($masterData->building_construction as $key => $building_construction){
                $buildings["building$key" . "BuildingCreatingTypeId"]  = isset($masterData->building_construction[$key]) ? explode('@', $masterData->building_construction[$key])[0] : '';
                $buildings["building$key" . "BuildingUsageId"] = isset($masterData->building_use[$key]) ? explode('@', $masterData->building_use[$key])[0] : '';
                $buildings["building$key" . "BuildingClassId"] = isset($masterData->building_use_type[$key]) ? explode('@', $masterData->building_use_type[$key])[0] : '';
                $buildings["building$key" . "Height"] = isset($masterData->building_height[$key]) ? $masterData->building_height[$key] : '';
                $buildings["building$key" . "FloorId"] = isset($masterData->floor[$key]) ? explode('@', $masterData->floor[$key])[0] : '';
                $buildings["building$key" . "Floors"] = isset($masterData->floor_number[$key]) ? $masterData->floor_number[$key] : '';
                $buildings["building$key" . "Stairs"] = isset($masterData->number_stairs[$key]) ? $masterData->number_stairs[$key] : '';
                $buildings["building$key" . "BasementFloors"] = array_key_exists("number_of_basement", $masterData ) ? (array_key_exists($key, $masterData->number_of_basement) ? $masterData->number_of_basement[$key] : '') : '';
                $buildings["building$key" . "MezzanineFloors"] = isset($masterData->number_of_mezzanine[$key]) ? $masterData->number_of_mezzanine[$key] : '';
                $buildings["building$key" . "SemiBasementFloors"] = isset($masterData->number_of_simi_basement[$key]) ? $masterData->number_of_simi_basement[$key] : '';
                $buildings["building$key" . "FloorVolume"] = isset($masterData->area_of_eash_floor[$key]) ? $masterData->area_of_eash_floor[$key] : '';
                $buildings["building$key" . "BasementFloorVolume"] = isset($masterData->size_of_each_basement[$key]) ? $masterData->size_of_each_basement[$key] : '';
                $buildings["building$key" . "MezzanineFloorVolume"] = isset($masterData->vol_each_mezzainine[$key]) ? $masterData->vol_each_mezzainine[$key] : '';
                $buildings["building$key" . "SemiBasementFloorVolume"] = isset($masterData->size_of_each_simi_basement[$key]) ? $masterData->size_of_each_simi_basement[$key] : '';
                $buildings["building$key" . "TotalFloorVolume"] = isset($masterData->total_floor_area[$key]) ? $masterData->total_floor_area[$key] : '';
                $buildings["building$key" . "OverheadElectricLine"] = isset($masterData->electric_line[$key]) ? explode('@', $masterData->electric_line[$key])[0] : '';
                $buildings["building$key" . "OverheadDistance"] = isset($masterData->electric_line_distance[$key]) ? $masterData->electric_line_distance[$key] : '';
                $buildings["building$key" . "TotalFlats"] = isset($masterData->total_flats_number[$key]) ? $masterData->total_flats_number[$key] : '';
                $buildings["building$key" . "MixedResidenceDetails"] = isset($masterData->description[$key]) ? $masterData->description[$key] : '';
                 }

            $param_3 = [
                "mainRoadName" => !empty($masterData->main_street_name) ? $masterData->main_street_name : '',
                "mainRoadWidth" => !empty($masterData->main_road_width) ? $masterData->main_road_width : '',
                "linkRoadLength" => !empty($masterData->length_connection) ? $masterData->length_connection : '',
                "linkRoadWidth" => !empty($masterData->plot_connecting) ? $masterData->plot_connecting : '',
                "proposedRoadLength" => !empty($masterData->recommended_road) ? $masterData->recommended_road : '',
                "northDetails" => !empty($masterData->north_side) ? $masterData->north_side : '',
                "southDetails" => !empty($masterData->south_side) ? $masterData->south_side : '',
                "eastDetails" => !empty($masterData->east_side) ? $masterData->east_side : '',
                "westDetails" => !empty($masterData->west_side) ? $masterData->west_side : '',
                "nearbyFireStationDivisionId" => !empty($masterData->nearby_division) ? explode('@', $masterData->nearby_division)[0] : '',
                "nearbyFireStationDistrictId" => !empty($masterData->nearby_district) ? explode('@', $masterData->nearby_district)[0] : '',
                "nearbyFireStationThanaId" => !empty($masterData->nearby_tahan) ? explode('@', $masterData->nearby_tahan)[0] : '',
                "nearbyFireStationId" => !empty($masterData->nearby_fire_station) ? explode('@', $masterData->nearby_fire_station)[0] : '',
                "builderEngineer" => !empty($masterData->engineer_name) ? $masterData->engineer_name : '',
                "builderEngineerNumber" => !empty($masterData->engineer_reg_no) ? $masterData->engineer_reg_no : '',
                "builderArchitect" => !empty($masterData->architect_name) ? $masterData->architect_name : '',
                "builderArchitectNumber" => !empty($masterData->architect_reg_no) ? $masterData->architect_reg_no : '',
                "hasSubStation" => !empty($masterData->electrical_station) ? (($masterData->electrical_station == 1) ? 'yes' : 'no') : 'no',
                "consent" => !empty($masterData->acceptTerms) ? (($masterData->acceptTerms == 'on') ? 'yes' : 'no') : '',
                "ossTrackingNo" => !empty($tracking_no) ? $tracking_no : '',
                ];

            $param_4 = [];
            if(!empty($masterData->electrical_station) && $masterData->electrical_station == 1) {
                $param_4 = [
                    "subStationLocation" => !empty($masterData->sub_station_location) ? $masterData->sub_station_location : '',
                    "subStationRoomVolume" => !empty($masterData->sub_station_room_size) ? $masterData->sub_station_room_size : '',
                    "subStations" => !empty($masterData->number_of_substation) ? $masterData->number_of_substation : '',
                    "subStationFloor" => !empty($masterData->floor_sub_station) ? $masterData->floor_sub_station : '',
                    "transformerKva" => !empty($masterData->electric_sub_station_kVA) ? $masterData->electric_sub_station_kVA : '',
                    "subStationVentilation" => !empty($masterData->adequate_electtrical) ? $masterData->adequate_electtrical : '',
                    "subStationSteelNet" => !empty($masterData->safety_bestney) ? $masterData->safety_bestney : '',
                    "subStationRainWater" => !empty($masterData->rain_likely) ? $masterData->rain_likely : '',
                    "subStationFireRated" => !empty($masterData->properly_fire_rated) ? $masterData->properly_fire_rated : '',
                    "subStationRubberMat" => !empty($masterData->rubber_mats) ? $masterData->rubber_mats : '',
                    "subStationIgfss" => !empty($masterData->gas_fire_system) ? $masterData->gas_fire_system : '',
                    "subStationBlowup" => !empty($masterData->safety_legend) ? $masterData->safety_legend : '',
                ];
            }

            $paramAppdata = array_merge($param_1,$param_2, $buildings, $param_3,$param_4);

            $BfsdcRequest->ref_id = $appData->id;
            $BfsdcRequest->app_create_request_type = 'submission';
            $BfsdcRequest->status = 0;   // 10 = payment not submitted
            $BfsdcRequest->app_create_request = json_encode($paramAppdata);
            $BfsdcRequest->save();
        }
        return true;
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
            $applicantEmailPhone = UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);

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

            if ($paymentInfo->payment_category_id == 1) { // service fee
                $processData->status_id = 1;
                $processData->desk_id = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                FscdNocProposed::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                FscdNocProposedAppStatus::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                $this->dispatch(new ApplicantRegistration($processData->ref_id));

            }
            $processData->save();

            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('bfscd-noc-proposed/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('bfscd-noc-proposed/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return \redirect()->back();
        }

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

        $applicantEmailPhone = UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);
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
                if ($paymentInfo->payment_category_id == 1) { //service fee
                    $processData->status_id = 1;
                    $processData->desk_id = 0;

                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                    FscdNocProposed::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    FscdNocProposedAppStatus::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                    $this->dispatch(new ApplicantRegistration($processData->ref_id));

                }
            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('bfscd-noc-proposed/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('bfscd-noc-proposed/list/' . Encryption::encodeId($this->process_type_id));
        }
    }
}
