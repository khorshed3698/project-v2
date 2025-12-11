<?php

namespace App\Modules\CityBankAccount\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SBaccount\Models\RequestQueueSBaccount;
use App\Modules\SBaccount\Models\SBaccount;
use App\Modules\Settings\Models\Attachment;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Apps\Models\AppDocumentStakeholder;
use Carbon\Carbon;


class CityBankAccountController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 136;
        $this->aclName = 'CityBankAccount';
    }// end -:- construct


    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CBAO-1001]';
        }
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [CBAO-971]</h4>"]);
        }
        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $service_url = Config('stackholder.SONALI_BANK_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $companyId = CommonFunction::getUserWorkingCompany();
            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $companyId)
                ->first(['ea_apps.*']);
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $divisions = ['' => 'Select One'] + AreaInfo::select(DB::raw('CONCAT(area_id, "@", area_nm) AS area'), 'area_nm', 'area_type')->where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area')->all();
            $district = ['' => 'Select One'] + AreaInfo::select(DB::raw('CONCAT(area_id, "@", area_nm) AS area'), 'area_nm', 'area_type')->where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area')->all();

            $public_html = strval(view("CityBankAccount::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'service_url', 'divisions', 'district', 'payment_config', 'basicAppInfo')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CTC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CTC-1064]');
            return redirect()->back();
        }
    }// end -:- appForm()

    public function appStore(Request $request)
    {
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = SBaccount::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new SBaccount();
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
                    $app_doc = AppDocumentStakeholder::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_code' => $doc_id
                    ]);
                    $app_doc->doc_code = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }
//            } /* End file uploading */


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'SBA-' . date("dMY") . '-';
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
            if ($request->get('actionBtn') != "draft") {
                $this->SubmissionJson($appData->id, $tracking_no, $processData->status_id);

            }


            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 1,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [ETIN-1123]");
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
//                if (env('server_type') != 'local') {

                if ($request->get('actionBtn') == 'Submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }

//                }

            }

            ///////////////////// stockholder Payment End//////////////////////////

            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [SBA-1023]');
            }

            return redirect('licence-applications/sb-account/list/' . Encryption::encodeId($processData->process_type_id));

        } catch
        (Exception $e) {
            //dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }// end -:- appStore()

    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [SBA-1002]';
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
        $decodedAppId = Encryption::decodeId($appId);
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [dpdc-973]</h4>"
            ]);
        }

        try {

            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('sb_account_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [SBA-346]");
                return redirect()->back()->withInput();
            }


            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $token = $this->getToken();
            $service_url = Config('stackholder.SONALI_BANK_API_URL');
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 1,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $public_html = strval(view("SBaccount::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'service_url', 'payment_config')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('SBaccountEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CTCC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[CTCC-1015]" . "</h4>"
            ]);
        }
    }// end -:- applicationViewEdit()

    public function applicationView($appId, Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [BRC-1003]';
        }
        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [CTCC-974]</h4>"
            ]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        //dd($decodedAppId);
        $process_type_id = $this->process_type_id;
        //$companyIds = CommonFunction::getUserCompanyWithZero();

        // get application,process info

        $appInfo = ProcessList::leftJoin('sb_account_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                $join->on('ps.id', '=', 'process_list.status_id');
                $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
            })
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
            ]);

        $appData = json_decode($appInfo->appdata);
//                    dd($appData);
//        $dynamic_shortfall = DynamicShortfallAttachmentDPDC::where('ref_id', $appInfo->ref_id)->get();
        // dd($dynamic_shortfall);
        $company_id = $appInfo->company_id;


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
            ]);;
        $token = $this->getToken();
        $service_url = Config('stackholder.SONALI_BANK_API_URL');

        $public_html = strval(view(
            "SBaccount::application-form-view",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'service_url', 'spPaymentinformation')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function getToken()
    {
        // Get credentials from database
        $idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $client_id = Config('stackholder.BIDA_CLIENT_ID');
        $client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        return CommonFunction::getToken($idp_url, $client_id, $client_secret);
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function preview()
    {
        return view("SBaccount::preview");
    }

    public function uploadDocument()
    {
        return View::make('SBaccount::ajaxUploadFile');
    }

    public function getDynamicDoc(Request $request)
    {

        $sb_ac_service_url = Config('stackholder.SONALI_BANK_API_URL');
        $type = $request->type;
        $app_id = $request->appId;


        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $sb_ac_service_url . "/info/document-type",
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
                "agent-id: 3"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);
//        dd($decoded_response);
        $html = '';

        if ($decoded_response['responseCode'] == 200) if ($decoded_response['data'] != '') {
            $attachment_list = $decoded_response['data'];
//            dd($attachment_list);
            $clr_document = AppDocumentStakeholder::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
//            dd($clr_document);
            $clrDocuments = [];

            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_id]['code'] = $documents->doc_id;
                $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                $clrDocuments[$documents->doc_id]['type'] = $documents->doc_name;
            }
            $html = view("SBaccount::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id')
            )->render();
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function deleteDynamicDoc(Request $request)
    {
        $process_type_id = $request->process_type_id;
        $ref_id = $request->ref_id;
        $doc_id = $request->doc_id;
        $res = AppDocumentStakeholder::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public function SubmissionJson($app_id, $tracking_no, $statusid)
    {
        // Submission Request Data
        if ($statusid == 2) {
            $SBaccountRequest = new RequestQueueSBaccount();
        } else {
            $SBaccountRequest = RequestQueueSBaccount::firstOrNew([
                'ref_id' => $app_id
            ]);
        }
        $refNo = str_replace('SBA', 'BIDA', $tracking_no);
        if ($SBaccountRequest->status == 0 || $SBaccountRequest->status == 10) {
            $appData = SBaccount::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
//dd($masterData);
            $OrgInfo = [
                "CustSectorCode" => !empty($masterData->account_type) ? explode('@', $masterData->account_type)[2] : '',
                "CustSubCategoryCode" => !empty($masterData->customer_sub_category) ? explode('@', $masterData->customer_sub_category)[0] : '',
                "ConstitutionCode" => !empty($masterData->entity_type) ? explode('@', $masterData->entity_type)[0] : '',
                "TotEmployees" => !empty($masterData->human_resource) ? $masterData->human_resource : '',
                "TradeLicense" => [
                    "licenseNo" => !empty($masterData->tl_no) ? $masterData->tl_no : '',
                    "ExpiryDate" => !empty($masterData->expiry_date) ? date('Y-m-d', strtotime($masterData->expiry_date)) : '',
                    "IssueDate" => !empty($masterData->tl_date) ? date('Y-m-d', strtotime($masterData->tl_date)) : '',
                    "IssueAuth" => !empty($masterData->issue_authority) ? $masterData->issue_authority : '',
                ],
                "ResidentStatus" => !empty($masterData->resident) ? explode('@', $masterData->resident)[0] : '',
                "NetWorth" => !empty($masterData->net_of_org) ? $masterData->net_of_org : '',
                "Addresses" => [
                    [
                        "PostOfficeName" => !empty($masterData->present_post) ? $masterData->present_post : '',
                        "MobileNumber" => !empty($masterData->present_phone) ? $masterData->present_phone : '',
                        "Address" => !empty($masterData->present_road) ? $masterData->present_road : '',
                        "AddrType" => "1",
                        "CurrAddr" => "true",
                        "PostOfficeCode" => !empty($masterData->present_post) ? $masterData->present_post : '',
                        "PoliceStationCode" => !empty($masterData->present_thana_code) ? $masterData->present_thana_code : '',
                        "PermAddr" => "false",
                        "CountryCode" => !empty($masterData->present_country) ? explode('@', $masterData->present_country)[0] : '',
                        "DivisionCode" => !empty($masterData->present_division) ? explode('@', $masterData->present_division)[0] : '',
                        "CommAddr" => "true",
                        "DistrictCode" => !empty($masterData->present_dsp_code) ? $masterData->present_dsp_code : '',
                    ],
                    [
                        "PostOfficeName" => !empty($masterData->permanent_road) ? $masterData->permanent_road : '',
                        "MobileNumber" => !empty($masterData->permanent_post) ? $masterData->permanent_post : '',
                        "Address" => !empty($masterData->permanent_road) ? $masterData->permanent_road : '',
                        "AddrType" => "2",
                        "CurrAddr" => "false",
                        "PostOfficeCode" => !empty($masterData->permanent_post) ? $masterData->permanent_post : '',
                        "PoliceStationCode" => !empty($masterData->permanent_thana_code) ? $masterData->permanent_thana_code : '',
                        "PermAddr" => "true",
                        "CountryCode" => !empty($masterData->permanent_country) ? explode('@', $masterData->permanent_country)[0] : '',
                        "DivisionCode" => !empty($masterData->permanent_division) ? explode('@', $masterData->permanent_division)[0] : '',
                        "CommAddr" => "false",
                        "DistrictCode" => !empty($masterData->permanent_dsp_code) ? explode('@', $masterData->permanent_dsp_code)[0] : '',
                    ]
                ],
                "CompanyName" => !empty($masterData->organization_name_en) ? $masterData->organization_name_en : '',
                "OrgType" => !empty($masterData->nature_of_organization) ? explode('@', $masterData->nature_of_organization)[1] : '',
                "BizType" => !empty($masterData->type_of_business) ? explode('@', $masterData->type_of_business)[1] : '',
                "BizDtls" => !empty($masterData->nature_of_bus) ? explode('@', $masterData->nature_of_bus)[1] : '',
                "Registration" => [
                    "ExpiryDate" => !empty($masterData->reg_expiry_date) ? date('Y-m-d', strtotime($masterData->reg_expiry_date)) : '',
                    "Address" => !empty($masterData->registration_address) ? $masterData->registration_address : '',
                    "IssueDate" => !empty($masterData->registration_date) ? date('Y-m-d', strtotime($masterData->registration_date)) : '',
                    "IssueAuth" => !empty($masterData->registration_authority) ? $masterData->registration_authority : '',
                    "CountryCode" => !empty($masterData->registration_country) ? explode('@', $masterData->registration_country)[0] : '',
                    "registrationNo" => !empty($masterData->registration_no) ? $masterData->registration_no : '',
                ],
                "Tin" => !empty($masterData->tax_no) ? $masterData->tax_no : '',
                "Vin" => !empty($masterData->vat_reg_no) ? $masterData->vat_reg_no : '',
                "YearlyTurnOver" => !empty($masterData->yearly_turnover) ? $masterData->yearly_turnover : '',
                "CustCategoryCode" => !empty($masterData->customer_category) ? explode('@', $masterData->customer_category)[0] : '',


            ];
            $currAddress = '';
            if (!empty($masterData->present_road)) {
                $currAddress .= $masterData->present_road;
            }
            if (!empty($masterData->present_thana)) {
                $currAddress .= ',' . $masterData->present_thana;
            }
            if (!empty($masterData->present_dsp)) {
                $currAddress .= ',' . $masterData->present_dsp;
            }
            $currAddress = trim($currAddress, ',');

            $permanentAddress = '';
            if (!empty($masterData->permanent_road)) {
                $permanentAddress .= $masterData->permanent_road;
            }
            if (!empty($masterData->permanent_thana)) {
                $permanentAddress .= ',' . $masterData->permanent_thana;
            }
            if (!empty($masterData->permanent_dsp)) {
                $permanentAddress .= ',' . $masterData->permanent_dsp;
            }
            $permanentAddress = trim($currAddress, ',');

            $AccountInfo = [
                "AccountCurrencyCode" => !empty($masterData->currency) ? explode('@', $masterData->currency)[0] : '',
                "DebitAllowed" => false,
                "ConnRoles" => [
                    [
                        "connRoleType" => !empty($masterData->account_operation) ? explode('@', $masterData->account_operation)[0] : '',
                        "Email" => "thejoyoflife@gmail.com",
                        "RoleType" => !empty($masterData->account_operation) ? explode('@', $masterData->account_operation)[0] : '',
                        "PidDocs" => [
                            [
                                "PidNum" => !empty($masterData->identification_doc_no) ? $masterData->identification_doc_no : '',
                                "ExpiryDate" => !empty($masterData->identification_doc_date_exp) ? date('Y-m-d', strtotime($masterData->identification_doc_date_exp)) : '',
                                "IssueDate" => !empty($masterData->identification_doc_date_issue) ? date('Y-m-d', strtotime($masterData->identification_doc_date_issue)) : '',
//                        "countryCode"=> !empty($masterData->identification_country)?explode('@', $masterData->identification_country)[0] :'',
                                "AddrProof" => false,
                                "IdentityCheck" => false,
                                "PidType" => !empty($masterData->identification_doc) ? explode('@', $masterData->identification_doc)[0] : '',
                            ]
                        ],
                        "Gender" => !empty($masterData->sex) ? explode('@', $masterData->sex)[0] : '',
                        "OccupationCode" => !empty($masterData->occupation_code) ? explode('@', $masterData->occupation_code)[0] : '',
                        "SpouseName" => !empty($masterData->spouse_name) ? $masterData->spouse_name : '',
                        "Nationality" => !empty($masterData->nationality_personal) ? explode('@', $masterData->nationality_personal)[0] : '',
                        "MobileNumber" => !empty($masterData->present_phone) ? $masterData->present_phone : '',
                        "RelationshipInfo" => !empty($masterData->relation_with_org) ? $masterData->relation_with_org : '',
                        "MotherName" => !empty($masterData->mothers_name) ? $masterData->mothers_name : '',
                        "FullName" => !empty($masterData->account_oper_person_en) ? $masterData->account_oper_person_en : '',
                        "CurrAddress" => $currAddress,
                        "IncomeSrc" => !empty($masterData->source_of_fund) ? $masterData->source_of_fund : '',
                        "Notes" => "I soley hold this company account",
                        "MonthlyIncome" => !empty($masterData->monthly_income) ? $masterData->monthly_income : '',
                        "BirthDate" => !empty($masterData->date_of_birth) ? date('Y-m-d', strtotime($masterData->date_of_birth)) : '',
                        "FatherName" => !empty($masterData->father_name) ? $masterData->father_name : '',
                        "PermAddress" => $permanentAddress,
                    ]

                ],
                "ProdCode" => !empty($masterData->account_type) ? explode('@', $masterData->account_type)[3] : '',
                "AccountType" => !empty($masterData->ac_nature) ? explode('@', $masterData->ac_nature)[0] : '',
                "BranchCode" => !empty($masterData->bank_branch) ? explode('@', $masterData->bank_branch)[0] : '',
                "CreditAllowed" => true
            ];
            $ortherInfo = [
                'PurposeOfAccount' => !empty($masterData->purpose_account) ? $masterData->purpose_account : '',
                'Authorization' => !empty($masterData->authorization_info) ? $masterData->authorization_info : '',
                "NameOfConcernAuth" => "BIDA"
            ];

            $paramAppdata["ossTrackingNo"] = $tracking_no;
            $paramAppdata["OrgInfo"] = $OrgInfo;
            $paramAppdata["ReferenceNo"] = $refNo;
            $paramAppdata["AccountInfo"] = $AccountInfo;
            $paramAppdata["OtherInfo"] = $ortherInfo;
        }

        $SBaccountRequest->sb_ref_no = $refNo;
        $SBaccountRequest->ref_id = $appData->id;
        $SBaccountRequest->status = 10;   // 10 = payment not submitted
        $SBaccountRequest->request_json = json_encode($paramAppdata);
        $SBaccountRequest->save();
        // Submission Request Data ends
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

            SBaccount::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            $processData->save();

            RequestQueueSBaccount::where('ref_id', $paymentInfo->app_id)->update([
                'status' => '0'
            ]);

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/sb-account/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('e-tin-foreigner/list/' . Encryption::encodeId($this->process_type_id));
        }
    }
}// end -:- CityBankAccountController
