<?php
namespace App\Modules\WasaNewConnection\Controllers;

use App\Libraries\ACL;
use App\Modules\Apps\Models\AppDocumentStakeholder;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Modules\WasaNewConnection\Models\RequestQueueWasa;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\WasaNewConnection\Models\WasaNewConnection;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;

class WasaNewConnectionController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 131;
        $this->aclName = 'WasaNewConnection';
    }

    public function appForm(Request $request)
    {

        if (!$request->ajax())
        {
            return 'Sorry! this is a request without proper way. [DWASAN-1001]';
        }
        if (!ACL::getAccsessRight($this->aclName, '-A-'))
        {
            return response()
                ->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [BREB-371]</h4>"]);
        }
        try
        {
            $token = $this->getToken();
            $wasa_service_url = Config('stackholder.WASA_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->to('/dashboard');
            }
            $public_html = strval(view("WasaNewConnection::application-form", compact('viewMode', 'mode','payment_config', 'wasa_service_url', 'token')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }
        catch(\Exception $e)
        {

            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WASA-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [WASA-1064]');
            return redirect()
                ->back();
        }
    }

    public function appStore(Request $request)
    {

        if (!ACL::getAccsessRight($this->aclName, '-A-'))
        {
            return response()
                ->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [e-TIN-foreigner-96]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;

        try
        {

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();

            $data = $request->all();

            if ($request->get('app_id'))
            {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = WasaNewConnection::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData
                    ->id])
                    ->first();
            }
            else
            {
                $appData = new WasaNewConnection();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $requestData = $request->all();

            if (empty($requestData['application_type']))
            {
                $requestData['application_type'] = '';
            }
            if (empty($requestData['niddobpassporttype']))
            {
                $requestData['niddobpassporttype'] = '';
            }
            if (empty($requestData['freedomfighter_status']))
            {
                $requestData['freedomfighter_status'] = '';
            }

            $data = json_encode($requestData);
            $appData->appdata = $data;
            $appData->save();

            if ($request->get('actionBtn') == "draft")
            {
                $processData->status_id = - 1;
                $processData->desk_id = 0;
            }
            else
            {
                if ($processData->status_id == 5)
                { // For shortfall
                    $processData->status_id = 2;
                }
                else
                {
                    $processData->status_id = - 1;
                }
                $processData->desk_id = 0;
            }

            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = ''; // for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()
                ->toDateTimeString();
            $processData->read_status = 0;
            //  dd($processData->submitted_at);
            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            $docIds = $request->get('dynamicDocumentsId');

            ///Start file uploading
            if (isset($docIds))
            {
                foreach ($docIds as $docs)
                {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = AppDocumentStakeholder::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id, 'doc_code' => $doc_id]);
                    $app_doc->doc_code = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);

                    $app_doc->save();
                }
            } /* End file uploading */

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0)
            { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no))
                {

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0)
                    {
                        $trackingPrefix = 'DWASA-' . date("dMY") . '-';
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

            if ($request->get('actionBtn') != "draft")
            {
                $this->submissionJson($appData->id, $tracking_no, $processData->status_id);

            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2)
            {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')->where(['api_stackholder_payment_configuration.process_type_id' => $this->process_type_id, 'api_stackholder_payment_configuration.payment_category_id' => 1, 'api_stackholder_payment_configuration.status' => 1, 'api_stackholder_payment_configuration.is_archive' => 0, ])
                    ->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config)
                {
                    Session::flash('error', "Payment configuration not found [WASA-1010]");
                    return redirect()->back()
                        ->withInput();
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

                $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appData->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
                $paymentInfo->payment_config_id = $payment_config->id;
                $paymentInfo->app_id = $appData->id;
                $paymentInfo->process_type_id = $this->process_type_id;
                $paymentInfo->app_tracking_no = '';
                $paymentInfo->receiver_ac_no = $account_numbers;
                $paymentInfo->payment_category_id = $payment_config->payment_category_id;
                $paymentInfo->ref_tran_no = $tracking_no."-01" ;
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
                if ($request->get('actionBtn') == 'Submit' && $paymentInsert)
                {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }

            }

            ///////////////////// stockholder Payment End//////////////////////////
            DB::commit();

            if ($processData->status_id == - 1)
            {
                Session::flash('success', 'Successfully updated the Application!');
            }
            elseif ($processData->status_id == 1)
            {
                Session::flash('success', 'Successfully Application Submitted !');
            }
            elseif ($processData->status_id == 2)
            {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            }
            else
            {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($processData->process_type_id));

        }
        catch(Exception $e)
        {
            //dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()
                ->withInput();
        }
    }

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        $token = $this->getToken();
        $wasa_service_url = Config('stackholder.WASA_SERVICE_API_URL');
        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view')
        {
            $viewMode = 'on';
            $mode = '-V-';
        }
        else if ($openMode == 'edit')
        {
            $viewMode = 'off';
            $mode = '-E-';
        }
        if (!ACL::getAccsessRight($this->aclName, $mode))
        {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [BPDB-973]</h4>"]);
        }
        try
        {

            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;

            $appInfo = ProcessList::leftJoin('dwasa_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'apps.*', 'process_type.max_processing_day', ]);

            $tl_issued_by = ['4' => 'Cantonment Board', '1' => 'City Corporation', '2' => 'Pouroshova', '3' => 'Union Parisod',

            ];
            $appData = json_decode($appInfo->appdata);

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
//            dd($payment_config);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
                return redirect()->to('/dashboard');
            }


            $agent = config('stackholder.bida-agent-id');

            $public_html = strval(view("WasaNewConnection::application-form-edit", compact('appInfo', 'appData', 'viewMode', 'mode', 'token', 'appId', 'agent', 'tl_issued_by', 'wasa_service_url','payment_config')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }
        catch(\Exception $e)
        {

            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRN-1015]" . "</h4>"]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax())
        {
            return 'Sorry! this is a request without proper way. [BRC-1003]';
        }
        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode))
        {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [CTCC-974]</h4>"]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        $document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)
            ->where('ref_id', $decodedAppId)->get();

        $process_type_id = $this->process_type_id;
        // get application,process info
        $appInfo = ProcessList::leftJoin('dwasa_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id)
        {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'ps.status_name', 'apps.*', ]);

        $appData = json_decode($appInfo->appdata);


        $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)
            ->whereIn('payment_status', [1, 3])
            ->get(['id as sp_payment_id', 'contact_name as sfp_contact_name', 'contact_email as sfp_contact_email', 'contact_no as sfp_contact_phone', 'address as sfp_contact_address', 'pay_amount as sfp_pay_amount', 'vat_on_pay_amount as sfp_vat_on_pay_amount', 'transaction_charge_amount as sfp_transaction_charge_amount', 'vat_on_transaction_charge as sfp_vat_on_transaction_charge', 'total_amount as sfp_total_amount', 'payment_status as sfp_payment_status', 'pay_mode as pay_mode', 'pay_mode_code as pay_mode_code', 'ref_tran_date_time']);

        $token = $this->getToken();
        $wasa_service_url = Config('stackholder.WASA_SERVICE_API_URL');
        $agent = config('stackholder.bida-agent-id');

        $public_html = strval(view("WasaNewConnection::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document', 'wasa_service_url', 'agent')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function uploadDocument()
    {
        return View::make('WasaNewConnection::ajaxUploadFile');
    }

    // Get WASA token for authorization
    public function getToken()
    {
        // Get credentials from database
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        return CommonFunction::getToken($idp_url, $client_id, $client_secret);
    }

    //GET Refresh token
    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    //    Get dynamic documents from database with api
    public function getDynamicDoc(Request $request)
    {

        $wasa_conncection_service_url = Config('stackholder.WASA_SERVICE_API_URL');
        $agent_id = Config('stackholder.bida-agent-id');

        $app_id = $request->appId;
        $freedomFighter = 0;
        if($request->connectionType == 'Yes'){
            $freedomFighter = 1;
        }
        $connectionType = $request->connectionType;

        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $wasa_conncection_service_url . "/info/required-docs/conn-type/$connectionType/freedom-fighter/$freedomFighter",
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
                "Authorization: Bearer  $token",
                "Content-Type: application/json",
                "agent-id:$agent_id"
            ) ,
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $html = '';
        if ($decoded_response['responseCode'] == 200)
        {
            if ($decoded_response['data'] != '')
            {
                $attachment_list = $decoded_response['data'];

                $clr_document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)
                    ->where('ref_id', $app_id)->get();
                $clrDocuments = [];

                foreach ($clr_document as $documents)
                {
                    $clrDocuments[$documents->doc_code]['document_id'] = $documents->doc_code;
                    $clrDocuments[$documents->doc_code]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_code]['document_name_en'] = $documents->doc_name;

                }
                $html = view("WasaNewConnection::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id'))->render();
            }
        }
        return response()
            ->json(['responseCode' => 1, 'data' => $html]);
    }

    public function submissionJson($app_id, $tracking_no,$statusId)
    {

        $wasaRequest = RequestQueueWasa::firstOrNew(['ref_id' => $app_id]);
        if($statusId == 2){
            $type = 'RESUBMISSION_REQUEST';
            $wasaRequest->status                                = 0;
        }else{
            $type = 'Submission';
            $wasaRequest->status                                = 10;
        }

        $appData = WasaNewConnection::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);
        $submissionData = [];

        $submissionData['ossTrackingNo']                    = $tracking_no;
        $submissionData['applicationType']                  = !empty($masterData->application_type) ? explode('@', $masterData->application_type) [0] : null;
        $submissionData['appType']                          = !empty($masterData->application_category) ? explode('@', $masterData->application_category) [0] : null;
        $submissionData['connectionType']                   = !empty($masterData->conn_type) ? explode('@', $masterData->conn_type) [0] : null;
        $submissionData['wasaZone']                         = !empty($masterData->wasa_zone) ? explode('@', $masterData->wasa_zone) [0] : null;
        $submissionData['waterPipeDiameter']                = !empty($masterData->water_connection_size) ? explode('@', $masterData->water_connection_size) [0] : null;
        $submissionData['applicantName']                    = $masterData->applicant_name;
        $submissionData['mobile']                           = $masterData->mobile_number;
        $submissionData['email']                            = $masterData->email;
        $submissionData['onBehalfOf']                       = null;

        $submissionData['father']                           = $masterData->father_name;
        $submissionData['mother']                           = $masterData->mother_name;
        $submissionData['spouse']                           = $masterData->spouse_name;
        $submissionData['landPhone']                        = $masterData->telephone;
        $submissionData['nid']                              = $masterData->niddobpassport;
        $submissionData['gender']                           = !empty($masterData->gender) ? explode('@', $masterData->gender) [0] : null;
        $submissionData['freedomFighter']                   = $masterData->freedomfighter_status;
        $submissionData['dateOfBirth']                      = !empty($masterData->date_of_birth) ? date('d/m/Y') : null;
        $submissionData['orgName']                          = $masterData->institute_name;

        $submissionData['connectionAddress1']               = $masterData->conn_address;
        $submissionData['connectionAddress2']               = $masterData->conn_address;
        $submissionData['presentAddressOther']              = $masterData->present_address;
        $submissionData['presentAddress1']                  = $masterData->present_address;
        $submissionData['presentAddress2']                  = $masterData->present_address;

        $submissionData['landSize']                         = $masterData->landsize;
        $submissionData['houseArea']                        = $masterData->house_area;
        $submissionData['previusConnectionNumber']          = $masterData->number_of_ex_conn;
        $submissionData['floorNo']                          = $masterData->number_of_floor;
        $submissionData['flatNo']                           = $masterData->no_of_flat;
        $submissionData['kitchenNo']                        = $masterData->no_of_kitchen;
        $submissionData['toiletNo']                         = $masterData->number_of_toilet;
        $submissionData['totalUser']                        = $masterData->number_of_user;
        $submissionData['underWaterReserves']               = $masterData->water_res_capacity;
        $submissionData['topWaterReserve']                  = $masterData->roof_water_res_capacity;
        $submissionData['sewerPipeDiameter']                = !empty($masterData->sewer_pipe_diameter) ? $masterData->sewer_pipe_diameter : null;
        $submissionData['sewerStatus']                      = !empty($masterData->sewer_line_status) ? explode('@', $masterData->sewer_line_status) [0] : null;
        $submissionData['prevAccounts']                     = !empty($masterData->prev_account) ? $masterData->prev_account : null;
        $submissionData['otherConnInfo']                    = (!empty($masterData->other_conn_info) ? $masterData->other_conn_info : null);
        $submissionData['enlgEnlgSize']                     = (!empty($masterData->enlg_size) ? $masterData->enlg_size : null);
        $submissionData['enlgExistingSize']                 = (!empty($masterData->enlg_exist_size) ? $masterData->enlg_exist_size : null);
        $submissionData['enlgExistingMeterNo']              = (!empty($masterData->enlg_exist_meterno) ? $masterData->enlg_exist_meterno : null);
        $submissionData['enlgLastBillDate']                 = (!empty($masterData->enlg_last_bill_date) ? $masterData->enlg_last_bill_date : null);
        $submissionData['trackingNo']                       = !empty($masterData->tracking_no) ? $masterData->tracking_no : null;
        $submissionData['enlgExistingAccountNo']            = !empty($masterData->enlgExistingAccountNo) ? $masterData->enlgExistingAccountNo : null;
        $submissionData['pin']                              = !empty($masterData->pin) ? $masterData->pin : null;
        $submissionData['houseStructure']                   = !empty($masterData->structure_of_home) ? explode('@', $masterData->structure_of_home) [0] : null;
        $submissionData['existingTracking']                 = !empty($masterData->exist_tracking) ? $masterData->exist_tracking : null;

        $wasaRequest->ref_id                                = $app_id;
        $wasaRequest->type                                  = $type;

        $wasaRequest->request_json                          = json_encode($submissionData);
        $wasaRequest->save();

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

            $processData->status_id = 1;
            $processData->desk_id = 0;

            WasaNewConnection::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            $processData->save();

            RequestQueueWasa::where('ref_id', $paymentInfo->app_id)->update([
                'status' => '0'
            ]);

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($this->process_type_id));
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



        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->read_status = 0;

                $processData->status_id = 1;
                $processData->desk_id = 0;
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date


                WasaNewConnection::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueWasa::where('ref_id', $paymentInfo->app_id)->update([
                    'status' => '0'
                ]);


            } /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */ else {
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';


                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

}

