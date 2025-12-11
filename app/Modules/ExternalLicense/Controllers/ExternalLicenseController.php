<?php

namespace App\Modules\ExternalLicense\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\ExternalLicense\Models\ExternalLicense;
use App\Modules\ExternalLicense\Models\ExternalServiceAttachment;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Libraries\CommonFunctionStakeholder;

class ExternalLicenseController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    protected $tokenDetails;

    public function __construct()
    {
        $this->aclName = 'RajukLUCGeneral';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function appForm(Request $request)
    {

        $token = $this->getToken();
        $this->process_type_id = $request->process_type_id;
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [EXS-1001]</h4>"]);
        }
        try {

            $viewMode = 'off';
            $mode = '-A-';

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>Payment configuration not found [EXS-1002]</h4>"]);
            }
            $process_type_id = $this->process_type_id;
            $serviceInfo = ProcessType::find($process_type_id);
            $serviceConfiguration = json_decode($serviceInfo->external_service_config);
            $selectedArray = [];
            $amountDetails = [];
            if (!empty($serviceConfiguration->vendor_payment) && empty($serviceConfiguration->payment_parameter)) {

                $serverConfig = $serviceConfiguration->server_configuration;
                $vendorPayment = $this->getVendorPayment($serverConfig);
                if ($vendorPayment->responseCode == 200) {
                    $totalFee = 0;
                    $amountDetails['oss_fee'] = $payment_config->amount;
                    foreach ($vendorPayment->data as $key => $value) {
                        $amountDetails[$value->PaymentType] = $value->amount;
                        $totalFee += $value->amount;
                    }
                    $payment_config->amount += $totalFee;
                }
            }

            foreach ($serviceConfiguration->data as $key => $value) {
                if ($value != '') {
                    $defaultView = substr($value, 0, 1);
                    if ($defaultView == '@') {
                        $value = str_replace('@', '', $value);
                        $selectedArray[] = $value . ' as ' . $value;
                    }

                }

            }
//            dd($selectedArray);

            $fileIds = [];
            $docsArraty = [];
            $others = [];
            if (!empty($serviceConfiguration->files)) {
                foreach ($serviceConfiguration->files as $key => $value) {
                    if ($value != '') {
                        if ($key != 'other_attachment') {
                            $files = substr($value, 0, 6);
                            $docs = substr($value, 0, 5);
                            if ($files == 'files.') {
                                $fileIds ["$key"] = str_replace($files, '', $value);
                            } elseif ($docs == 'docs.') {
                                $docsArraty ["$key"] = str_replace($docs, '', $value);
                            }
                        } else {
                            foreach ($value as $otherDoc) {
                                $otherKey = !empty($otherDoc->File_Key) ? $otherDoc->File_Key : $otherDoc->Title;
                                $others[$otherKey] = ['label' => $otherDoc->Title, 'is_required' => !empty($otherDoc->mandatory) ? 1 : 0];
                            }
                        }
                    }
                }

            }


            array_push($selectedArray, 'users.*', 'factory_district.area_nm as ea_apps.factory_district', 'factory_thana.area_nm as ea_apps.factory_thana');

            $info = User::leftJoin('ea_apps', 'ea_apps.company_id', '=', 'users.working_company_id')
                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
                ->where('users.id', Auth::user()->id)
                ->first($selectedArray);
            if (!empty($serviceConfiguration->files)) {
                $document = $this->getDocList($fileIds, $docsArraty, $others);
            } else {
                $document = [];
            }

            if (!empty($serviceConfiguration->server_configuration->get_list_base_url)) {
                $getListBaseUrl = $serviceConfiguration->server_configuration->get_list_base_url;
            } else {
                $getListBaseUrl = config('stackholder.external_service_get_list_base_url');
            }

            $public_html = strval(view("ExternalLicense::application-form", compact('process_type_id', 'getListBaseUrl', 'viewMode', 'payment_config', 'mode', 'serviceConfiguration', 'info', 'token', 'document', 'amountDetails')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [External-1064]');
            $mesage = 'AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [External-1064]';
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 150px;text-align: center;'>$mesage</h4>"]);
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EXS-1064]');
            return redirect()->back();
        }
    }

    /**
     * Store a newly created resource in storage and update a specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()
                ->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [e-TIN-foreigner-96]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;

        try {
            $this->process_type_id = Encryption::decodeId($request->process_type_id);

            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();

            $data = $request->all();

            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = ExternalLicense::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData
                    ->id])
                    ->first();
            } else {
                $appData = new ExternalLicense();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
                $appData->process_type_id = $this->process_type_id;
            }


            $removeformRequest = ['actionBtn', '_token', 'sfp_total_amount', 'sfp_pay_amount', 'sfp_contact_name',
                'sfp_vat_on_pay_amount', 'sfp_contact_address', 'sfp_contact_phone', 'sfp_contact_email',
                'accept_terms', 'process_type_id', 'dynamicDocumentsId', 'selected_file', 'validateFieldName', 'isRequired', 'app_id'];

            $requestData = $request->all();
            $requestForVendor = $request->except($removeformRequest);

            $documentsArray = [];
            foreach ($requestForVendor as $key => $value) {
                if (substr($key, 0, 9) == 'doc_name_') {
                    Arr::forget($requestForVendor, $key);
                }
                $searchValue = substr($key, 0, 15);
                if ($searchValue == 'validate_field_') {
                    $dockey = str_replace($searchValue, '', $key);
                    if (substr($value, 0, 4) == 'http') {
                        $documentsArray[$dockey] = $value;
                    } else {
                        if ($value != "") {
                            $documentsArray[$dockey] = url() . '/uploads/' . $value;
                        } else {
                            $documentsArray[$dockey] = "";
                        }

                    }
                    Arr::forget($requestForVendor, $key);
                }

            }
            if (count($documentsArray) > 0) {
                $requestForVendor ['attachments'] = $documentsArray;
            }
            
            if(!empty($request->APP_DATA)){
                $default_app_data = $this->parseAppDataInfo($request->except(['APP_DATA']));
                $requestData['APP_DATA'] = (object)$default_app_data;
                $requestForVendor['APP_DATA'] = (object)$default_app_data;
            }

            $data = json_encode($requestData);
            if ($request->get('actionBtn') != "draft") {
                $appData->external_submission_json = json_encode($requestForVendor);
                $appData->uid = uniqid('', true);
            }
            
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

            // Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = ExternalServiceAttachment::firstOrNew([
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
            $processType = ProcessType::where('id', $this->process_type_id)->value('external_service_config');
            $processConfig = json_decode($processType);
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {

                        if (isset($processType)) {
                            $trackingPrefix = $processConfig->tracking_no_prefix . '-' . date("dMY") . '-';
                        } else {
                            $trackingPrefix = 'EXTS-' . date("dMY") . '-';
                        }
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-3,3) )+1,1),3,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                    }
                }
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')->where(['api_stackholder_payment_configuration.process_type_id' => $this->process_type_id, 'api_stackholder_payment_configuration.payment_category_id' => 1, 'api_stackholder_payment_configuration.status' => 1, 'api_stackholder_payment_configuration.is_archive' => 0,])
                    ->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [WASA-1010]");
                    return redirect()->back()
                        ->withInput();
                }
                $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
                $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
                    ->where('is_active', 1)
                    ->where('process_type_id', $this->process_type_id)
                    ->get([
                        'receiver_account_no',
                        'amount',
                        'distribution_type',
                    ])->toArray();

                if (!empty($processConfig->vendor_payment)) {
                    $param = [];
                    if (!empty($processConfig->payment_parameter)) {
                        $parameters = explode(',', $processConfig->payment_parameter);
                        foreach ($parameters as $value) {
                            $param[$value] = explode('@', $request->$value)[0];
                        }
                    }
                    $vendorPayment = $this->getVendorPayment($processConfig->server_configuration, $param);
                    $appData->vendor_payment_response = json_encode($vendorPayment); // return by decode
                    if(!empty($vendorPayment->responseCode) && $vendorPayment->responseCode == '500'){
                        Session::flash('error', 'Invaid data! [EXSV1-1001]');
                        return redirect()->back()->withInput();
                    }
                    foreach ($vendorPayment->data as $value) {
                        if ($value->TranAmountFee > 0) {
                            $paymentData = array(
                                'receiver_account_no' => $value->TranAccount,
                                'amount' => $value->TranAmountFee,
                                'distribution_type' => $stackholderDistibutionType,
                                'payment_type' => $value->paymentType
                            );
                            $stackholderMappingInfo[] = $paymentData;
                        }
                    }
                }
                // vendor payment condition


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
                    if (isset($data['m_category']) && $data['m_category'] == 'CHL') {
                        $paymentDetails->purpose_sbl = 'CHL';
                    } else {
                        $paymentDetails->purpose_sbl = 'TRN';
                    }
                    $paymentDetails->distribution_type = $data['distribution_type'];
                    $paymentDetails->receiver_ac_no = $data['receiver_account_no'];
                    $paymentDetails->payment_type = isset($data['payment_type']) ? $data['payment_type'] : '';
                    $paymentDetails->pay_amount = $data['amount'];
                    $paymentDetails->sl_no = $sl; // Always 1
                    $paymentDetails = $paymentDetails->save();

                    $sl++;
                }
                DB::commit();
                // In local uncomment & other comment
//                $response = $this->callApi($appData, $processConfig,$paymentInfo->id);
//                dd($response);
                /*
                 * Payment Submission
                */
                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }
            }

            ///////////////////// stockholder Payment End //////////////////////////
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

            return redirect('licence-applications/wasa-new-connection/list/' . Encryption::encodeId($processData->process_type_id));

        } catch (\Exception $e) {
            //dd($e->getMessage());
            DB::rollback();
            if(!empty($processConfig->vendor_payment)){
                Session::flash('error', 'Invalid Data For Vendor Payment !' . ' [SPE0302]');
            }else{
                Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            }
            Log::error('An error occurred in the appStore() : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ExternalLicense-1001]');
            return redirect()->back()->withInput();
        }
    }// end -:- appStore();

    private function parseAppDataInfo( $data){
        $processType = ProcessType::where('id', $this->process_type_id)->value('external_service_config');
        $processType = json_decode($processType);
        $default_data = $processType->default_data;
        $app_data = [];

        foreach ($default_data->APP_DATA as $key => $value) {
            try {
                $appDataAfterReplacePlaceholder = $this->replacePlaceholders($value , $data);
                $query = $this->queryDataFromString($appDataAfterReplacePlaceholder);
                $result = DB::table(trim($query['table']))->whereRaw($query['condition'])->select($query['selectedItem'])->get() ;

                $this->mappingAppData($query['table'],  $result,  $app_data);       
            } catch (\Exception $ex) {
                Log::debug(json_encode($ex->getMessage()));
                $app_data[$key] = null;
            }
        }
        
        return $app_data;
    }

    private function mappingAppData($table ,$result , &$app_data){
        if(!empty($result) && count($result) > 1){
            $alias = explode("as" , $table);
            $app_data[!empty($alias[1]) ? trim($alias[1]) : trim($alias[0])] = $result;
        }elseif(!empty($result) && count($result) == 1){
            foreach ($result[0] as $resultKey => $value) {
                $app_data[$resultKey] = $value;
            }
        }
    }
    private function queryDataFromString($data){
        $data = explode("|" ,$data);
        $table = explode(":" ,$data[0]);
        $tableName = explode(' ' , trim($table[0]));
           
        return [
            'selectedItem' => array_map('trim' , explode("," , $this->sanitizeSql($data[1]))),
            'table' => !empty($tableName[1]) ? $tableName[0] . " as " . $tableName[1] : $tableName[0],
            'condition' => $this->sanitizeSql($table[1])
        ];
    }

    private function sanitizeSql($sql)
    {
        $forbiddenKeywords = ['delete', 'insert', 'update', 'drop', 'alter', 'truncate' , 'password'];
        return trim(str_replace($forbiddenKeywords, '', $sql));
    }

    private function replacePlaceholders($appData , $data){
        foreach ($data as $key => $value) {
            $placeholder = '$.' . $key;
            $appData = str_replace($placeholder, $value, $appData);
        }
        return $appData;
    }

    public function getPayment(Request $request)
    {
        try {
            $this->process_type_id = $request->process_type_id;
            $serviceInfo = ProcessType::find($this->process_type_id);
            $serviceConfiguration = json_decode($serviceInfo->external_service_config);

            $params = $request->except('process_type_id');
            foreach ($params as $key => $value) {
                $params[$key] = explode('@', $value)[0];
            }
            $amountDetails = [];
            $paymentData = $this->getVendorPayment($serviceConfiguration->server_configuration, $params);
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            if ($paymentData->responseCode == 200) {
                $totalFee = 0;
                $amountDetails['oss_fee'] = $payment_config->amount;
                foreach ($paymentData->data as $key => $value) {
                    if ($value->TranAmountFee > 0) {
                        $amountDetails[$value->paymentType] = $value->TranAmountFee;
                        $totalFee += $value->TranAmountFee;
                    }
                }
                $payment_config->amount += $totalFee;
            }else{
                throw new \Exception("Payment Error");
            }
            $public_html = strval(view("ExternalLicense::payment-details", compact('', 'payment_config', 'amountDetails')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        }catch (\Exception $e) {
            return response()->json(['responseCode' => 2, 'message' => "Cant process Your request."]);
        }

    }

    private function getVendorPayment($serverConfig, $paymentParam = [])
    {


        $vendorToken = CommonFunction::getTokenV2($serverConfig);

        $vendorPayment = $this->curlGetRequest($serverConfig->payment_info_url, $vendorToken, json_encode($paymentParam));
//        $vendorPayment = '{"success":true,"responseCode":200,"message":null,"data":[{"TranAccount":"0002601020870","amount":1000,"PaymentType":"appFee"},{"TranAccount":"0002601020864","amount":150,"PaymentType":"vat"}]}';
        $vendorPayment = json_decode($vendorPayment['data']);

        return $vendorPayment;
    }// end -:- getVendorPayment()

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function applicationView($appId, Request $request)
    {
        $this->process_type_id = $request->process_type_id;
        $viewMode = 'on';
        $mode = '-V-';
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [CTCC-974]</h4>"]);
        }
        $process_type_id = $this->process_type_id;
        $decodedAppId = Encryption::decodeId($appId);
        $uid = ExternalLicense::where('id', $decodedAppId)->value('uid');


        $serviceInfo = ProcessType::find($process_type_id);
        $serviceConfiguration = json_decode($serviceInfo->external_service_config);

        // get application,process info

        $processData = ProcessList::where('process_list.ref_id', $decodedAppId)->where('process_type_id', $process_type_id)
            ->first();


        if(!in_array($processData->status_id,[6,25])){
            $this->statusRequest($decodedAppId, $uid);
        }

        $appInfo = ProcessList::leftJoin('external_service_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)
            ->first(['process_list.id as process_list_id', 'process_list.desk_id',
                'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id',
                'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no',
                'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name',
                'ps.status_name', 'apps.*',]);

        $appData = json_decode($appInfo->external_submission_json);


        $spPaymentinformation = SonaliPaymentStackHolders::where('app_id', $decodedAppId)->where('process_type_id', $this->process_type_id)
            ->whereIn('payment_status', [1, 3])
            ->get(['id as sp_payment_id', 'contact_name as sfp_contact_name', 'contact_email as sfp_contact_email', 'contact_no as sfp_contact_phone', 'address as sfp_contact_address', 'pay_amount as sfp_pay_amount', 'vat_on_pay_amount as sfp_vat_on_pay_amount', 'transaction_charge_amount as sfp_transaction_charge_amount', 'vat_on_transaction_charge as sfp_vat_on_transaction_charge', 'total_amount as sfp_total_amount', 'payment_status as sfp_payment_status', 'pay_mode as pay_mode', 'pay_mode_code as pay_mode_code', 'ref_tran_date_time']);
        $token = $this->getToken();

//        return  view("ExternalLicense::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'document','serviceConfiguration'));
        $public_html = strval(view("ExternalLicense::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'spPaymentinformation', 'serviceConfiguration')));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }// end -:- applicationView()

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function applicationEdit($appId, Request $request)
    {
        $token = $this->getToken();
        $this->process_type_id = $request->process_type_id;
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [EXS-1001]</h4>"]);
        }
        try {

            $viewMode = 'off';
            $mode = '-A-';
            $decodedAppId = Encryption::decodeId($appId);

            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>Payment configuration not found [EXS-1002]</h4>"]);
            }
            $process_type_id = $this->process_type_id;
            $serviceInfo = ProcessType::find($process_type_id);
            $serviceConfiguration = json_decode($serviceInfo->external_service_config);
            $selectedArray = [];
            $amountDetails = [];
            if (!empty($serviceConfiguration->vendor_payment) && empty($serviceConfiguration->payment_parameter)) {
                $serverConfig = $serviceConfiguration->server_configuration;
                $vendorPayment = $this->getVendorPayment($serverConfig);
                if ($vendorPayment->responseCode == 200) {
                    $totalFee = 0;
                    $amountDetails['oss_fee'] = $payment_config->amount;
                    foreach ($vendorPayment->data as $key => $value) {
                        $amountDetails[$value->PaymentType] = $value->amount;
                        $totalFee += $value->amount;
                    }
                    $payment_config->amount += $totalFee;
                }
            }
            foreach ($serviceConfiguration->data as $key => $value) {

                if ($value != '') {
                    $defaultView = substr($value, 0, 1);
                    if ($defaultView == '@') {
                        $value = str_replace('@', '', $value);
                        $selectedArray[] = $value . ' as ' . $value;
                    }
                }

            }


            $fileIds = [];
            $docsArraty = [];
            $others = [];
            if (!empty($serviceConfiguration->files)) {
                foreach ($serviceConfiguration->files as $key => $value) {
                    if ($value != '') {
                        if ($key != 'other_attachment') {
                            $files = substr($value, 0, 6);
                            $docs = substr($value, 0, 5);
                            if ($files == 'files.') {
                                $fileIds ["$key"] = str_replace($files, '', $value);
                            } elseif ($docs == 'docs.') {
                                $docsArraty ["$key"] = str_replace($docs, '', $value);
                            }
                        } else {
                            foreach ($value as $otherDoc) {
                                $otherKey = !empty($otherDoc->File_Key) ? $otherDoc->File_Key : $otherDoc->Title;
                                $others[$otherKey] = ['label' => $otherDoc->Title, 'is_required' => !empty($otherDoc->mandatory) ? 1 : 0];

                            }

                        }
                    }
                }
            }


            array_push($selectedArray, 'users.*', 'factory_district.area_nm as ea_apps.factory_district', 'factory_thana.area_nm as ea_apps.factory_thana');
            $info = User::leftJoin('ea_apps', 'ea_apps.company_id', '=', 'users.working_company_id')
                ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
                ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
                ->where('users.id', Auth::user()->id)
                ->first($selectedArray);
            $appInfo = ProcessList::leftJoin('external_service_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'apps.*', 'process_type.max_processing_day',]);
            $appData = json_decode($appInfo->appdata);
//            dd(Auth::user()->id);
            if (!empty($serviceConfiguration->files)) {
                $document = $this->getDocList($fileIds, $docsArraty, $others, $decodedAppId);
            } else {
                $document = [];
            }


//            return view("ExternalLicense::application-form-edit", compact('process_type_id', 'viewMode', 'payment_config', 'mode', 'serviceConfiguration', 'info', 'appInfo', 'appData', 'token', 'document','amountDetails'));

            $getListBaseUrl = config('stackholder.external_service_get_list_base_url');
            $public_html = strval(view("ExternalLicense::application-form-edit", compact('process_type_id', 'getListBaseUrl', 'viewMode', 'payment_config', 'mode', 'serviceConfiguration', 'info', 'appInfo', 'appData', 'token', 'document', 'amountDetails')));
            
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [RajukLUCGeneral-1064]');
            $mesage = 'AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [External-1064]';
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 150px;text-align: center;'>$mesage</h4>"]);
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EXS-1064]');

            return redirect()->back();
        }
    }


    public function getToken()
    {
        // Get credentials from database

        $idp_url = config('stackholder.external_service_get_list_token_url');
        $client_id = config('stackholder.external_service_get_list_client_id');
        $client_secret = config('stackholder.external_service_get_list_client_secret');
//        dd(CommonFunction::getToken($idp_url, $client_id, $client_secret));

        return CommonFunction::getToken($idp_url, $client_id, $client_secret);
    }// end -:- getToken()

    private function getDocList($fileIds, $docArray, $otherDoc, $app_id = '')
    {
        if ($app_id == '') {
            $ids = array_values($fileIds);
            $tableNames = [];
            $fieldNames = [];
            foreach ($docArray as $doc) {
                $tableNames[] = explode('.', $doc)[0];
                $fieldNames[] = explode('.', $doc)[1];
            }
            $processData = ProcessList::leftjoin('app_documents as ad', function ($join) {
                $join->on('ad.process_type_id', '=', 'process_list.process_type_id')
                    ->on('ad.ref_id', '=', 'process_list.ref_id');
            })
                ->where('company_id', Auth::user()->working_company_id)
                ->whereIn('doc_info_id', $ids)
                ->whereIn('process_list.process_type_id', [102])
                ->where('status_id', 25)
                ->orderBy('completed_date', 'desc')
                ->groupBy('ad.process_type_id')
                ->get(
                    [
                        'process_list.tracking_no',
                        'process_list.created_at',
                        'ad.*'
                    ]);

            $processList = ProcessList::where('company_id', Auth::user()->working_company_id)
                ->leftjoin('pdf_print_requests_queue as pq', function ($join) {
                    $join->on('pq.process_type_id', '=', 'process_list.process_type_id')
                        ->on('pq.app_id', '=', 'process_list.ref_id');
                })
                ->whereIn('table_name', $tableNames)
                ->whereIn('field_name', $fieldNames)
                ->groupBy('pq.process_type_id')
                ->get([
                    'process_list.tracking_no',
                    'process_list.created_at',
                    'pq.process_type_id',
                    'pq.app_id',
                    'table_name',
                    'field_name',
                    'certificate_link'
                ]);
        }


        foreach ($fileIds as $key => $file) {
            $attachment_list [$key]['doc_id'] = $key;
            $attachment_list [$key]['name_en'] = $key;
            $attachment_list [$key]['is_required'] = 0;
        }

        foreach ($docArray as $key => $file) {
            $keyReplaceSpace = str_replace(' ', '', $key);
            $attachment_list [$keyReplaceSpace]['doc_id'] = $keyReplaceSpace;
            $attachment_list [$keyReplaceSpace]['name_en'] = $key;
            $attachment_list [$keyReplaceSpace]['is_required'] = 0;
        }
        foreach ($otherDoc as $key => $file) {
            $keyReplaceSpace = str_replace(' ', '', $key);
            $attachment_list [$keyReplaceSpace]['doc_id'] = $keyReplaceSpace;
            $attachment_list [$keyReplaceSpace]['name_en'] = $file['label'];
            $attachment_list [$keyReplaceSpace]['is_required'] = $file['is_required'];
            $attachment_list [$keyReplaceSpace]['is_upload'] = 1;
        }
        $clrDocuments = [];
        if ($app_id != '') {
            $clr_document = ExternalServiceAttachment::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
            }
        } else {
            foreach ($docArray as $docKey => $docFile) {
                $clrDocuments [$docKey]['document_id'] = $docKey;
                foreach ($processList as $documents) {
                    $documentDey = $documents->table_name . '.' . $documents->field_name;
                    $clrDocuments[$docKey]['document_id'] = $docKey;
                    $clrDocuments [$docKey]['document_name_en'] = $docKey;
                    if ($documentDey == $docFile) {
                        $clrDocuments[$docKey]['tracking_no'] = $documents->tracking_no;
                        $clrDocuments[$docKey]['submission_date'] = CommonFunction::formateDate($documents->created_at);
                        $clrDocuments[$docKey]['file'] = $documents->certificate_link;
                    }
                }
            }
            foreach ($fileIds as $key => $file) {
                $clrDocuments [$key]['document_id'] = $key;
                foreach ($processData as $documents) {
                    $clrDocuments[$key]['document_id'] = $key;
                    $clrDocuments [$key]['document_name_en'] = $key;

                    if ($documents->doc_info_id == $file) {
                        $clrDocuments[$key]['tracking_no'] = $documents->tracking_no;
                        $clrDocuments[$key]['submission_date'] = CommonFunction::formateDate($documents->created_at);
                        $clrDocuments[$key]['file'] = $documents->doc_file_path;
                    }
                }
            }
        }
        return ['attachment_list' => $attachment_list, 'clrDocuments' => $clrDocuments];
    }

    public function getDynamicDoc(Request $request)
    {
        $this->process_type_id = $request->process_type_id;
        $processType = ProcessType::where('id', $this->process_type_id)->value('external_service_config');
        $processConfig = json_decode($processType);
        $serverConfiguration = $processConfig->server_configuration;
        $docApi = $serverConfiguration->document_api_url;
//        $token = CommonFunction::getTokenV2($serverConfiguration);
        $dependentField = $request->doc_dependent_field;
        $app_id = $request->appId;

        $curl = curl_init();
        $secret = 'A35be@1c3$cef*5acb2573X92e94c7E5bc4c8c7f!1d9c4135c415Ca#c3e198e3';
//        $dependentField = 757;
        curl_setopt_array($curl, array(
//            CURLOPT_URL => $docApi ."/". $dependentField,
            CURLOPT_URL => $docApi,
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
                "X-Api-Secret: $secret",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);
        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = [];
                foreach ($decoded_response['data'] as $key => $file) {
                    if ($dependentField == $file['serviceId']) {
                        $attachment_list [$key]['doc_id'] = $file['id'];
                        $attachment_list [$key]['name_en'] = $file['typeName'];
                        $attachment_list [$key]['is_required'] = $file['mandatory'];
                        $attachment_list [$key]['is_upload'] = 1;
                    }
                }
                $clr_document = ExternalServiceAttachment::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                    $clrDocuments[$documents->doc_id]['is_upload'] = $documents->is_uploaded;
                }
                $document = [
                    'attachment_list' => $attachment_list,
                    'clrDocuments' => $clrDocuments
                ];
                $html = view(
                    "ExternalLicense::documents",
                    compact('document', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }


    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function callApi($data, $processConfig, $payment_id)
    {
        try {
            $serverConfiguration = $processConfig->server_configuration;
            $decodedRequest = json_decode($data->external_submission_json, true);
            $skipFields = ['attachments', 'APP_DATA'];
            foreach ($decodedRequest as $key => $value){
                if(is_array($value)){
                    if(in_array($key, $skipFields)){
                        continue;
                    }
                   
                    $allSelected = '';
                    foreach ($value as $value2){
                        $allSelected .= explode('@', $value2)[0] . ',';
                    }   
                  
                    $decodedRequest[$key] = rtrim($allSelected,',');
                }else{
                    $mailformat = '/^\w+([\.-]?\w+)*@.+([\.-]?\w+)*(\.\w{2,3})+$/';
                    $listData = '/^\w+(\s?\w+[\.-]?\w+)*@.+$/';
                    if (preg_match($mailformat, $value)) {
                        $noting = '';
                    }elseif (preg_match($listData, $value)) {
                        $decodedRequest[$key] =explode('@', $value)[0];
                    }
                }
            }

            if(!empty($processConfig->form)){
                $decodedRequest['Form'] = $processConfig->form;
            }

            $decodedRequest['bida_oss_id'] = $data->uid;



            if(!empty($processConfig->vendor_payment)){
                $paymentDetails = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->whereIn('distribution_type',[10])->get();
                $paymentMasterData = SonaliPaymentStackHolders::where('id',$payment_id)->first();
                $decodedRequest['master_trans_id'] = $paymentMasterData->transaction_id;

                $paymentRequest = [];
                foreach ($paymentDetails as $detailsValue){
                    if(!empty($detailsValue->verification_response)){
                        $decodedPaymentResponse = json_decode($detailsValue->verification_response);
                        $paymentData['TransactionId'] = $decodedPaymentResponse->TransactionId;
                        $paymentData['TransactionDate'] = $decodedPaymentResponse->TransactionDate;
                        $paymentData['ReferenceDate'] = $decodedPaymentResponse->ReferenceDate;
                        $paymentData['ReferenceNo'] = $decodedPaymentResponse->ReferenceNo;
                        $paymentData['PaymentStatus'] = 200;
                        $paymentData['paymentType'] = $detailsValue->payment_type;
                        $paymentData['TranAccount'] = $decodedPaymentResponse->TranAccount;
                        $paymentData['TranAmountFee'] = $decodedPaymentResponse->TranAmount;
                        $paymentRequest[] = $paymentData;
                    }
                }
                $decodedRequest['payment_info'] = $paymentRequest;
            }

            $url = $serverConfiguration->submission_url;
            $encodedRequest = json_encode($decodedRequest);
            $data->request_body = $encodedRequest;
            $data->save();


            if(!empty($processConfig->submission_request_type) && $processConfig->submission_request_type == 'manual'){
                $token = CommonFunction::getTokenV2($serverConfiguration);
                $result = $this->curlPostRequst($url, $encodedRequest, 'token: web-'.$token);
                if($result['http_code'] == 200){
                    return CommonFunctionStakeholder::externalServiceSubmissionRequest($result['result']);
                }else{
                    return $result;
                }
            }elseif (!empty($processConfig->submission_request_type) && $processConfig->submission_request_type == 'customize'){
                DB::table('external_service_custom_submission')->insert([
                    'request' => $data->request_body,
                    'app_id' => $data->id,
                    'process_type_id' => $this->process_type_id,
                ]);
                return ['custom_submission'=>'yes'];
            }
            $token = CommonFunction::getTokenV2($serverConfiguration);
            return $this->curlPostRequst($url, $encodedRequest, 'Authorization: Bearer '.$token);
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Application Submission Failed [ES-201]');
            return redirect()->back();
        }


    }// end -:- callApi()


    public function curlPostRequst($url, $encodedRequest, $token)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            $token

        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedRequest);
        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        } else {
            $http_code = 0;
        }
        $error = curl_error($ch);
        curl_close($ch);
        //dd($error,$result);
        return ['http_code' => $http_code, 'result' => $result];
    }// end -:- curlPostRequst()


    public function getStatus(Request $request)
    {
        $appId = $request->app_id;
        $processTypeId = $request->process_type_id;
        $this->process_type_id = Encryption::decodeId($processTypeId);
        $decodedAppId = Encryption::decodeId($appId);
        $process_type_id = $this->process_type_id;

        $appInfo = ProcessList::leftJoin('external_service_apps as apps', 'apps.id', '=', 'process_list.ref_id')->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
            $join->on('ps.id', '=', 'process_list.status_id');
            $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
        })->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $decodedAppId)->where('process_list.process_type_id', $process_type_id)->first(['process_list.id as process_list_id', 'process_list.desk_id', 'process_list.department_id', 'process_list.process_type_id', 'process_list.status_id', 'process_list.locked_by', 'process_list.locked_at', 'process_list.ref_id', 'process_list.tracking_no', 'process_list.company_id', 'process_list.process_desc', 'process_list.submitted_at', 'user_desk.desk_name', 'ps.status_name', 'apps.*',]);

        $result = $this->statusRequest($decodedAppId, $appInfo->uid);
        return response()->json($result);

    }// end -:- getStatus()

    private function statusRequest($appId, $uid)
    {
        $processType = ProcessType::where('id', $this->process_type_id)->value('external_service_config');
        $processConfig = json_decode($processType);
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first();
        $serverConfiguration = $processConfig->server_configuration;
        $token = CommonFunction::getTokenV2($serverConfiguration);
        $url = str_replace('$.bida_oss_id', $uid, $processConfig->server_configuration->status_api_url);
        $encodedRequest = json_encode([
            'bida_oss_id' => $uid
        ]);


        $requestMethod = !empty($processConfig->server_configuration->status_api_method) ? $processConfig->server_configuration->status_api_method : 'GET';
        $authorization_type = !empty($processConfig->server_configuration->authorization_type) ? $processConfig->server_configuration->authorization_type . $token : 'Authorization: Bearer ' . $token;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $requestMethod,
            CURLOPT_HTTPHEADER => [],
        ));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedRequest);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Content-Type: application/json',
            $authorization_type
        ));

        $result = curl_exec($curl);
        $statusResponse = $result;
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (curl_errno($curl)) {
            $curlError = curl_error($curl);
            Log::info($curlError);
            $curlResult = null;
            echo $curlError;
        }
        curl_close($curl);

        if ($code == 200 && !empty($processConfig->submission_request_type) && $processConfig->submission_request_type == 'manual') {
            $result = CommonFunctionStakeholder::externalServiceSubmissionRequest($result);
            if (is_array($result) && !empty($result['result'])) {
                $result = $result['result'];
            }
        }
        if ($code == 200) {
            $result = json_decode($result);
        }
        if (!empty($result->responseCode) && $result->responseCode == 200) {
            $appData = ExternalLicense::find($appId);
            $appData->redirect_url = !empty($result->application_url) ? $result->application_url : '';
            if (!empty($result->message)) {
                $processData->process_desc = $result->message;
                $processData->save();
            }
            $appData->certificate_url = !empty($result->certificate_url) ? $result->certificate_url : '';
            $appData->stakeholder_status_code = !empty($result->status_code) ? $result->status_code : '';
            $appData->stakeholder_status_name = !empty($result->status) ? $result->status : '';

            if (!empty($result->othersInfo)) {
                $encodeData = json_encode($result->othersInfo);
                if(!empty($result->certificate_field_name)){
                    $storedInfo = $this->storeCertificate($result);
                    $encodeData = json_encode($storedInfo);
                }
                $appData->others_info = $encodeData;
            }
            $appData->status_check_response = json_encode($statusResponse);
            $appData->save();
            $this->updateProcessStatus($appData->id,$result);
        } else {
            $appData = ExternalLicense::find($appId);
            $appData->status_check_response = json_encode($result);
            $appData->save();
        }

        return ['http_code' => $code, 'responseCode' => 1, 'result' => $result];

    }// end -:- statusRequest()

    public function afterPayment($payment_id)
    {
        try {
            if (empty($payment_id)) {
                Session::flash('error', 'Something went wrong!, payment id not found.');
                return \redirect()->back();
            }
            $payment_id = Encryption::decodeId($payment_id);
            $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
            $this->process_type_id = $paymentInfo->process_type_id;


            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $this->process_type_id)
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
            }

            $processData->save();
            $processType = ProcessType::where('id', $paymentInfo->process_type_id)->first();
            $processConfig = json_decode($processType->external_service_config);
            $appData = ExternalLicense::find($processData->ref_id);
            $response = $this->callApi($appData, $processConfig, $payment_id);
            DB::beginTransaction();

            if (isset($response['http_code']) && $response['http_code'] == 200) {
                $appData->external_submission_response = $response['result'];
                $decoded_response = json_decode($response['result']);
                if (!empty($decoded_response->application_url)) {
                    $appData->redirect_url = $decoded_response->application_url;
                    if (!empty($decoded_response->message)) {
                        $processData->process_desc = $decoded_response->message;
                        $processData->save();
                    }
                }
            } elseif (isset($response['custom_submission'])) {
                $processData->process_desc = 'Submission Processing';
                $processData->save();
            } else {
                $appData->external_submission_response = $response['result'];
                $processData->status_id = -1;
                $appData->save();
                $processData->save();
                DB::commit();
                Session::flash('error', "Submission Failed please try again or contact with our <a style='font-weight: bold' target='_blank' href='/articles/support'>Support</a> team");
                $redirectUrl = '/process/' . $processType->form_url . '/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($this->process_type_id);
                return redirect()->to($redirectUrl);
            }
            $appData->save();
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect($processType->form_url . '/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.' . $e->getMessage() . $e->getLine());
            $processType = ProcessType::where('id', $this->process_type_id)->first();
            return redirect($processType->form_url . '/list/' . Encryption::encodeId($this->process_type_id));
        }
    }// end -:- afterPayment()

    public function curlGetRequest($requested_url, $token, $param = [])
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "$requested_url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_POSTFIELDS => $param,
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ),
            ));
            $curlResult = curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (curl_errno($curl)) {
                $curlError = curl_error($curl);
                Log::info($curlError);
                $curlResult = null;
                echo $curlError;
            }
            curl_close($curl);
            return ['http_code' => intval($code), 'data' => $curlResult];
        } catch (\Exception $e) {
            Log::error($e->getMessage() . '. File : ' . $e->getFile() . ' [Line : ' . $e->getLine() . ']');
        }
    }// end -:- curlGetRequest()


    private function storeCertificate($statusResponse)
    {
        $certificateFieldNames = explode(',',$statusResponse->certificate_field_name);
        $replacedOthersInfo = [];
        foreach ($statusResponse->othersInfo as $key =>$value){
            if (in_array($key,$certificateFieldNames)){
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'uploads/' . $yearMonth;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $pdf = base64_decode($value);
                $file_name = trim(sprintf("%s", uniqid('certificate', true))).'.pdf';
                file_put_contents($path . $file_name, $pdf);
                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                    $link = "https";
                else
                    $link = "http";

                $link .= "://";
                $currentDomain = $link . $_SERVER['HTTP_HOST'];
                $replacedOthersInfo[$key]  = $currentDomain.'/'.$path . $file_name;
            }else{
                $replacedOthersInfo[$key] = $value;
            }
        }
        return (object) $replacedOthersInfo;

    }

    private function updateProcessStatus($app_id, $decoded_response)
    {
        if(!empty($decoded_response->statusCode)){
            $statusMapping = DB::table('stakeholder_status_mapping')
            ->where('process_type_id', $this->process_type_id)
            ->where('stakeholder_status_code', $decoded_response->statusCode)
            ->first();
            
            if(!empty($statusMapping)){
                $processList  = ProcessList::where('process_type_id', $this->process_type_id)
                    ->where('ref_id', $app_id)
                    ->first();
                $processList->status_id =  $statusMapping->oss_status_id;
                $processList->save();
            }
        }
    }// end -:- updateProcessStatus


    public function previewIntroduction($processId)
    {
        $viewMode = 'off';
        $mode = '-A-';
        $decodedProcessId = Encryption::decodeId($processId);
        $processType = ProcessType::find($decodedProcessId);
        $serviceConfiguration = json_decode($processType->external_service_config);
        return view("ExternalLicense::preview-introduction",compact('serviceConfiguration','mode'));

    }
    public function previewGuideline($processId)
    {
        $viewMode = 'off';
        $mode = '-A-';
        $decodedProcessId = Encryption::decodeId($processId);
        $processType = ProcessType::find($decodedProcessId);
        $serviceConfiguration = json_decode($processType->external_service_config);
        return view("ExternalLicense::preview-guideline",compact('serviceConfiguration','mode'));

    }

}// end -:- ExternalLicenseController