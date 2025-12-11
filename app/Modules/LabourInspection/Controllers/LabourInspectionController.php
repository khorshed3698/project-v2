<?php


namespace App\Modules\LabourInspection\Controllers;


use App\Http\Controllers\Controller;
use App\Modules\LabourInspection\Models\DifeLayoutPlanApiRequestQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\ImageProcessing;
use App\Libraries\UtilFunction;
use Carbon\Carbon;
use App\Modules\DNCC\Models\DNCC;
use App\Modules\DNCC\Models\DNCCPaymentInfo;
use App\Modules\DNCC\Models\DynamicAttachmentDNCC;
use App\Modules\MutationLand\Models\MutationLand;
use App\Modules\MutationLand\Models\MutationLandPayment;
use App\Modules\MutationLand\Models\MutationLandPaymentConfirm;
use App\Modules\MutationLand\Models\MutationLandRequestQueue;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mockery\Exception;
use App\Modules\LabourInspection\Models\DIFE;
use App\Modules\LabourInspection\Models\DIFEPaymentInfo;
use App\Modules\LabourInspection\Models\DynamicAttachmentDIFE;
use App\Modules\LabourInspection\Models\DifeBuildingInfo;
use App\Modules\LabourInspection\Models\DifeMembershipInfo;

use App\Modules\LabourInspection\Models\DifeLayoutPlan;
use App\Modules\LabourInspection\Models\DifeLayoutPlanAttachment;


use App\Modules\FscdNocExisting\Models\FscdNocExisting;
//use function redirect;


class LabourInspectionController extends Controller
{
    //Department of Inspection for Factories and Establishments
    const ACL = 'LabourInspection';
    const PROCESS_TYPE = 135;

    protected $process_type_id;
    protected $aclName;
    protected $dife_service_url;

    public function __construct()
    {
        $this->process_type_id = 135;
        $this->aclName = 'LabourInspection';
        $this->dife_service_url = 'https://testapi-k8s.oss.net.bd/api/lima/';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [LabourInspection-1001]';
        }

        if (!ACL::getAccsessRight(self::ACL, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [LabourInspection-1002]</h4>"]);
        }

        try {
            $data = [];
            $data['payment_config'] = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $data['token'] = $this->getToken();
            $data['ml_service_url'] = 'https://testapi-k8s.oss.net.bd/api/lima/';


            $public_html = strval(view("LabourInspection::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('ML : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [LabourInspection-1003]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [LabourInspection-1004]');
            return redirect()->back();
        }
    }


    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CCC-105]</h4>"
            ]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = DifeLayoutPlan::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new DifeLayoutPlan();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }
            $data = json_encode($request->all());
            $appData->appdata = $data;
            $appData->save();

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall
                    $processData->status_id = 2;
                } else {
                    $processData->status_id = -1;
                }
                $processData->desk_id = 0;
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = ''; // for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            # Get Uploaded Files
            $docIds = $request->get('dynamicDocumentsId');
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = DifeLayoutPlanAttachment::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    $processTypeId = $this->process_type_id;
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'DIFE-' . date("dMY") . '-';
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                    }
                }
            }
            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft") {
                // do it later
                //$this->SubmissionJson($appData->id, $tracking_no, $processData->status_id, $request->ip());
            }



            # Sonali Bank Payment Configuration Checking
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 1,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [DIFE-LP-1123]");
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
                $pay_amount = 0;
                $account_no = "";
                foreach ($stackholderMappingInfo as $data) {
                    $pay_amount += $data['amount'];
                    $account_no .= $data['receiver_account_no'] . "-";
                }

                $account_numbers = rtrim($account_no, '-');
                // Get SBL payment configuration
                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = $tracking_no;
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no . "-01";
                $paymentInfo->pay_amount = $pay_amount;
                $paymentInfo->contact_name = $request->get('sfp_contact_name');
                $paymentInfo->contact_email = $request->get('sfp_contact_email');
                $paymentInfo->contact_no = $request->get('sfp_contact_phone');
                $paymentInfo->address = $request->get('sfp_contact_address');
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInsert = $paymentInfo->save();
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


                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }


            }

            if($processData->status_id == 2){
                $this->submissionJson($appData->id, $tracking_no, $processData->status_id);
            }
            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }
            return redirect('licence-applications/labour-inspection-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (Exception $e) {
            dd($e->getMessage(),$e->getLine());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return redirect('licence-applications/labour-inspection-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }// end -:- appStore()


    public function appFormEdit($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [LP-1009]';
        }
        if (!ACL::getAccsessRight(self::ACL, '-E')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [LP-1010]</h4>"]);
        }
        try {
            $data = [];
            $applicationId = Encryption::decodeId($appId);
            $data['applicationId'] = $applicationId;

            $data['app_info'] = ProcessList::leftJoin('dife_layout_plan_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', self::PROCESS_TYPE)
                ->first(['process_list.id as process_list_id',
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
                    'process_type.max_processing_day',
                    'apps.*',
                ]);

            $data['app_data'] = json_decode($data['app_info']->appdata);
            $data['difeBuildingInfo'] = DifeBuildingInfo::where('app_id', $applicationId)->get();
            $data['difeMembershipInfo'] = DifeMembershipInfo::where('app_id', $applicationId)->get();




            $data['payment_config'] = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $data['token'] = $this->getToken();
            $data['ml_service_url'] = $this->dife_service_url;
            $public_html = strval(view("LabourInspection::application-form-edit", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            dd($e->getMessage(),$e->getLine());
            Log::error('LabourInspection : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [LI-1011]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [LI-10012]');
            return redirect()->back();
        }
    }// end -:- appFormEdit()

    public function appFormView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [LP-1030]';
        }
        $viewMode = 'on';
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight(self::ACL, '-V-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [LP-1031]</h4>"]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        $process_type_id = self::PROCESS_TYPE;
        // get application,process info
        $appInfo = ProcessList::leftJoin('dife_layout_plan_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'ps.status_name', 'apps.*',]);

        $app_data = json_decode($appInfo->appdata);

        $document = DifeLayoutPlanAttachment::where('ref_id', $decodedAppId)
            ->where('process_type_id', $process_type_id)
            //->where('status', 1)
            ->get();
        $token = '';
        $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)->where('process_type_id', self::PROCESS_TYPE)
            ->whereIn('payment_status', [1, 3])
            ->get(['id as sp_payment_id', 'contact_name as sfp_contact_name', 'contact_email as sfp_contact_email', 'contact_no as sfp_contact_phone', 'address as sfp_contact_address', 'pay_amount as sfp_pay_amount', 'vat_on_pay_amount as sfp_vat_on_pay_amount', 'transaction_charge_amount as sfp_transaction_charge_amount', 'vat_on_transaction_charge as sfp_vat_on_transaction_charge', 'total_amount as sfp_total_amount', 'payment_status as sfp_payment_status', 'pay_mode as pay_mode', 'pay_mode_code as pay_mode_code', 'ref_tran_date_time']);
//        $token = $this->getToken();
//        return  view("RajukLUCGeneral::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document'));

        $public_html = strval(view("LabourInspection::application-form-view", compact('appInfo', 'app_data', 'process_type_id', 'viewMode', 'token', 'spPaymentinformation', 'document')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }// end -:- appFormView()

    public function submissionJson($app_id, $tracking_no, $statusId)
    {

        $mutationLandRequest = DifeLayoutPlanApiRequestQueue::firstOrNew(['ref_id' => $app_id]);
        if ($statusId == 2) {
            $type = 'RESUBMISSION_REQUEST';
            $mutationLandRequest->status = 0;
        } else {
            $type = 'Submission';
            $mutationLandRequest->status = 10;
        }

        if ($mutationLandRequest->status != 1) {
            $appData = DifeLayoutPlan::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            $paramAppdata = [
                "industry_id" => !empty($masterData->industry_id) ? explode('@', $masterData->industry_id)[0] : '',
                "factory_name" => !empty($masterData->factory_name_en) ? $masterData->factory_name_en  : '',
                "factory_name_bn" => !empty($masterData->factory_name_bn) ? $masterData->factory_name_bn  : '',
                "layout_factory_head_office_address" => !empty($masterData->factory_head_office_address) ? $masterData->factory_head_office_address  : '',
                "layout_factory_mail_address" => !empty($masterData->layout_factory_mail_address) ? $masterData->layout_factory_mail_address  : '',
                "layout_factory_owner" => !empty($masterData->owner_name) ? $masterData->owner_name  : '',
                "layout_factory_owner_present_address" => !empty($masterData->owner_present_address) ? $masterData->owner_present_address  : '',
                "layout_factory_owner_permanent_address" => !empty($masterData->owner_permanent_address) ? $masterData->owner_permanent_address  : '',
                "division_id" => !empty($masterData->division_id) ? explode('@', $masterData->division_id)[0] : '',
                "district_id" => !empty($masterData->district_id) ? explode('@', $masterData->district_id)[0] : '',
                "thana_upazila_id" => !empty($masterData->upazilla_id) ? explode('@', $masterData->upazilla_id)[0] : '',
                "post_office" => !empty($masterData->post_office) ? explode('@', $masterData->post_office)[0] : '',
                "road_number" => !empty($masterData->road_no_en) ? $masterData->road_no_en  : '',
                "road_number_bn" => !empty($masterData->road_no_bn) ? $masterData->road_no_bn  : '',
                "holding_number" => !empty($masterData->holding_name_en) ? $masterData->holding_name_en  : '',
                "holding_number_bn" => !empty($masterData->holding_name_bn) ? $masterData->holding_name_bn  : '',
                "layout_factory_nearby_station" => !empty($masterData->nearest_railway_steamer_launch) ? $masterData->nearest_railway_steamer_launch  : '',
                "layout_factory_nearby_busstop" => !empty($masterData->nearest_bus_stop) ? $masterData->nearest_bus_stop  : '',
                "layout_building_plan_approver" => !empty($masterData->notify_authority_name) ? $masterData->notify_authority_name  : '',
                "layout_building_plan_approval_date" => !empty($masterData->building_plan_approval_date) ? date('d-m-Y', strtotime($masterData->building_plan_approval_date))  : '',
                "layout_building_plan_approval_reference" => !empty($masterData->building_plan_reference_no) ? $masterData->building_plan_reference_no  : '',
                "layout_load_bearing_capacity" => !empty($masterData->layout_load_bearing_capacity) ? $masterData->layout_load_bearing_capacity  : '',
                "application_status" => 'draft',

            ];
            foreach ($masterData->factory_machine_type as $key => $value){
                $paramAppdata ['machines_data'][] = [
                    "type_of_machine" => !empty($masterData->factory_machine_type[$key]) ? $masterData->factory_machine_type[$key]:'',
                    "machine_number" => !empty($masterData->factory_machine_measurement[$key]) ? $masterData->factory_machine_measurement[$key]:'',
                    "machine_location" => !empty($masterData->factory_machine_location[$key]) ? $masterData->factory_machine_location[$key]:'',
                    "machine_load" => !empty($masterData->factory_machine_amount[$key]) ? $masterData->factory_machine_amount[$key]:'',
                ];
            }
            $difeLayoutPlanAttachment = DifeLayoutPlanAttachment::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
            foreach ($difeLayoutPlanAttachment as $att){
                if(!empty($att->doc_path)) {
                    $paramAppdata ['attachments'][] = [
                        "attachment_type_id" => $att->doc_id,
                        "attachment_path" => url('uploads') .'/'.$att->doc_path,
                    ];
                }
            }
            $mutationLandRequest->type = 'submission';
            $mutationLandRequest->status = 0;
            $mutationLandRequest->request_json = json_encode($paramAppdata);
            $mutationLandRequest->save();
        }
    }// end -:- submissionJson()

    public function checkStatus(Request $request)
    {

        $ml_service_url = Config('stakeholder.ml.service_url');
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $ml_service_url . "/application-status/" . $request->tracking_no,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 2,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "agent-id:  " . Config('stakeholder.agent_id'),
                "Authorization: Bearer $token",
                "Content-Type: application/json",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        if ($decoded_response['data']['responseCode'] == 'RSP200') {
            $this->updateProcessStatus($decoded_response);
        }

        return $decoded_response['data'];

    }

    private function updateProcessStatus($decoded_response)
    {
        $apiResponseArray = $decoded_response['data'];
    }



    public function checkApiRequestStatus(Request $request)
    {
        $app_id = $request->app_id;
        $apiRequestData = MutationLandRequestQueue::where('ref_id', $app_id)->first();
        $appStatus = 0;
        $redirectUrl = null;
        if ($apiRequestData->status == 1) {
            $appData = MutationLand::where('id', $app_id)->first();
            if ($appData->ml_submission_status == 1) {
                $appStatus = 2;
            } else if (!empty($appData->ml_redirect_url)) {
                $appStatus = 1;
                $redirectUrl = $appData->ml_redirect_url;
            } else {
                $appStatus = -1;
            }
        }
        return response()->json(['responseCode' => 1, 'status' => $appStatus, 'ml_redirect_url' => $redirectUrl]);
    }

    public function afterPayment($payment_id)
    {

        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return redirect()->back();
        }
        DB::beginTransaction();
        $payment_id = Encryption::decodeId($payment_id);
//        $payment_id = $payment_id;
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('mutation_land_apps', 'mutation_land_apps.id', '=', 'process_list.ref_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
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
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            $processData->status_id = 1;
            $processData->submitted_at = date('Y-m-d H:i:s');
            $processData->read_status = 0;
            $processData->process_desc = 'Service Fee Payment completed successfully.';
            $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
            $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
            $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
            $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;
            $appInfo['status_id'] = $processData->status_id;
//            dd($appInfo);
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            $processData->save();

            $appData = DifeLayoutPlan::where('id', $processData->ref_id)->first();

//            if ($paymentInfo->payment_category_id == 2) {  //type 3 for application feee
            $rData0 = [];

            $this->submissionJson($appData->id, $paymentInfo->app_tracking_no, $processData->status_id);

            $request_data = json_encode($rData0);
            $paymentConfirm = new MutationLandPaymentConfirm();
            $paymentConfirm->request = $request_data;
            $paymentConfirm->ref_id = $paymentInfo->app_id;
            $paymentConfirm->oss_tracking_no = $processData->tracking_no;
            $paymentConfirm->save();

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('CDAPAYMENT: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [LP-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [LP-1022]');
            return redirect('process/licence-applications/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
//        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.[LP-1035]');
            return redirect()->back();
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
                'process_type.form_id',
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
        $redirect_path = CommonFunction::getAppRedirectPathByJson($processData->form_id);

        try {
            DB::beginTransaction();

            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->status_id = 16; // Submitted
                $processData->submitted_at = date('Y-m-d H:i:s');
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                Session::flash('success', 'Payment Confirm successfully');
                $mlPayment = new MutationLandPayment();
                $mlPayment->ref_id = $processData->ref_id;
                $mlPayment->ml_id = $processData->luc_id;
                $mlPayment->transaction_id = $paymentInfo->transaction_id;
                $mlPayment->challan_no = $paymentInfo->transaction_id;
//                $mlPayment->transaction_amount =  $processData->cda_processing_fee;
                $mlPayment->transaction_amount = $paymentInfo->pay_amount;
                $mlPayment->transaction_date = $paymentInfo->payment_date;
                $mlPayment->save();
            }
            /*
            * Government payment submit
            * */
            /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 3) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user
                Session::flash('success', 'Application is waiting for Payment Confirmation');
            } elseif ($paymentInfo->is_verified == 0 && $paymentInfo->payment_category_id == 2) {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }
            $paymentInfo->save();
            $processData->save();
            DB::commit();
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['view'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('CDACOUNTERPayment: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [LP-1037]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA-1022]');
            return redirect('process/licence-applications/mutation-land/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
        }
    }

    public function managingAuthorityModal(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [LabourInspection]';
        }
        try {
            $modal_token      = $this->getToken();
            $modal_service_url= $this->dife_service_url;
            $html = view("LabourInspection::managing-authority.create", compact('modal_token', 'modal_service_url'))->render();
            return response()->json([
                'responseCode' => 1,
                'message' => '',
                'html' => $html
            ]);
        } catch (\Exception $e) {
            //Log::error('ML : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [MutationLand-1003]');
            //Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [MutationLand-1004]');
            //return redirect()->back();
            return response()->json([
                'responseCode' => 0,
                'message' => $e->getMessage(),
                'html' => ''
            ]);
        }

    }// end -:- managingAuthorityModal()

    public function managingAuthorityForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [LabourInspection]';
        }
        try {
            $managementAuthorityInfo = [];
            $managementAuthorityInfo['residency_type'] = $request->residency_type;
            $managementAuthorityInfo['factory_owners_name'] = $request->factory_owners_name;
            $managementAuthorityInfo['factory_cc_owner_designation_id'] = $request->factory_cc_owner_designation_id;
            $managementAuthorityInfo['factory_owners_father'] = $request->factory_owners_father;
            $managementAuthorityInfo['factory_owners_mother'] = $request->factory_owners_mother;
            $managementAuthorityInfo['factory_owners_phone'] = $request->factory_owners_phone;
            $managementAuthorityInfo['factory_owners_address'] = $request->factory_owners_address;
            $managementAuthorityInfo['factory_owners_nid'] = $request->factory_owners_nid;
            $managementAuthorityInfo['factory_owners_passport'] = $request->factory_owners_passport;

            return response()->json([
                'responseCode' => 1,
                'message' => '',
                'data' => $managementAuthorityInfo,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'responseCode' => 0,
                'message' => $e->getMessage(),
                'data' => ''
            ]);
        }
    }// end -:- managingAuthorityForm()

    /**
     * Name : getToken
     * Description : Access token Method for DIFE. Access from inner class.
     **/
    public function getToken()
    {
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');
        return CommonFunction::getToken($idp_url, $client_id, $client_secret);
    }// end -:- getToken()

    public function getDynamicDoc(Request $request)
    {
        try {
            //$tl_dncc_service_url = Config('stackholder.DNCC_SERVICE_API_URL');
            $bida_agent_id = Config('stackholder.bida-agent-id');


            $type = $request->type;
            $app_id = $request->appId;

            // Get token for API authorization
            $token = $this->getToken();

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $this->dife_service_url."attachment-types",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "agent-id: ".$bida_agent_id,
                    "Authorization: Bearer  $token",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            $decoded_response = json_decode($response, true);
            $html = '';

            if ($decoded_response['responseCode'] == 200) {
                if ($decoded_response['data'] != '') {
                    $attachment_list = $decoded_response['data'];
                    $clr_document = DifeLayoutPlanAttachment::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                    $clrDocuments = [];
                    foreach ($clr_document as $documents) {
                        $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                        $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                        $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                    }
                    $html = view("LabourInspection::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id')
                    )->render();
                }
            }
            return response()->json(['responseCode' => 1, 'data' => $html,  'message'=> '']);
        }catch (\Exception $e){
            return response()->json(['responseCode' => 0, 'data' => '', 'message'=> $e->getMessage()]);
        }
    }// end -:- getDynamicDoc()

    public function uploadDocument()
    {
        return View::make('LabourInspection::ajaxUploadFile');
    }// end -:- uploadDocument()
}// end -:- LabourInspectionController
