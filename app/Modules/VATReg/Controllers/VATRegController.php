<?php

namespace App\Modules\VATReg\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\AppDocuments;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\VATReg\Models\DynamicAttachmentVAT;
use App\Modules\VATReg\Models\HsCodeService;
use App\Modules\VATReg\Models\RequestQueueVat;
use App\Modules\VATReg\Models\VatApplicatonStatus;
use App\Modules\VATReg\Models\VATReg;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Log;
use yajra\Datatables\Datatables;

class VATRegController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 112;
        $this->aclName = 'VATReg';
    }

    public function appForm(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [VATReg-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [VATReg-971]</h4>"]);
        }
        try {
            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $token = $this->getToken();
            $vat_service_url = Config('stackholder.VATReg_SERVICE_API_URL');
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
            $public_html = strval(view("VATReg::application-form", compact('document', 'token', 'vat_service_url','payment_config')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getLine());
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function getToken()
    {
        // Get credentials from database
        $vatReg_idp_url = Config('stackholder.VATReg_TOKEN_API_URL');
        $vatReg_client_id = Config('stackholder.VATReg_SERVICE_CLIENT_ID');
        $vatReg_client_secret = Config('stackholder.VATReg_SERVICE_CLIENT_SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $vatReg_client_id,
            'client_secret' => $vatReg_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$vatReg_idp_url");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $result = curl_exec($curl);
        if (!$result) {
            $data = ['responseCode' => 0, 'msg' => 'Area API connection failed!'];
            return response()->json($data);
        }
        curl_close($curl);
        $decoded_json = json_decode($result, true);
        $token = $decoded_json['access_token'];

        return $token;

    }


    public function appStore(Request $request)
    {

        $company_id = Auth::user()->company_ids;
        if ($request->get('actionBtn') != 'draft') {
            $rules = [
                //                's' => 'required'
            ];

            $messages = [];

            $this->validate($request, $rules, $messages);
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [VATReg-970]</h4>"]);
        }
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = VATReg::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new VATReg();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            //            $appData->other_necessary_info = $request->get('other_necessary_info');

            $data['auth_name'] = "old index data replace"; // replace an existing data
            $data['auth_name2'] = "new index"; //add a new index and data

            $appData->appdata = json_encode($data);
            //dump($request->all());
            //dd(json_encode($data));
            $appData->save();


            //dd($request->get('actionBtn'));
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
            $processData->read_status = 0;
            $processData->submitted_at = Carbon::now()->toDateTimeString();

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();


            $docIds = $request->get('dynamicDocumentsId');


            ///Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];
                    $app_doc = DynamicAttachmentVAT::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            } /* End file uploading */


            // Generate Tracking No for Submitted application
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                $trackingPrefix = 'VAT-' . date("dMY") . '-';
                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$this->process_type_id' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
            }

            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            if ($request->get('actionBtn') != "draft") {
                $this->submissionJson($appData->id, $processData->status_id);
            }

            /*stackholder payment start*/
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 3,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [DPDC-1123]");
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
                $distribution_type="";
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
                $paymentInfo->ref_tran_no =  $tracking_no . "-01";
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
                if ($request->get('actionBtn') == 'submit' && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }

            }

            ///////////////////// stockholder Payment End//////////////////////////

            DB::commit();


            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
                return redirect('process/licence-applications/vat-registration/view/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
            } elseif ($processData->status_id == 2) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [IP-1023]');
            }
            // return redirect('licence-applications/individual-licence');
            return redirect('licence-applications/vat-registration/list/' . Encryption::encodeId($processData->process_type_id));
        } catch (\Exception $e) {
            dd($e->getLine() . $e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . " [IP-1025]");
            return redirect()->back()->withInput();
        }
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        //        if (!$request->ajax()) {
        //            return 'Sorry! this is a request without proper way. [VAT-1002]';
        //        }
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VAT-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('vat_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;

            /*
             * Visa Recommendation New module has category-based (Visa type based) application
             * [Like- PI type, A Type, Visa on arrival]
             */
            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            if ($viewMode == 'on') {
                $document = AppDocuments::leftJoin('attachment_list', 'attachment_list.id', '=', 'app_documents.doc_info_id')
                    ->where('app_documents.ref_id', $decodedAppId)
                    ->where('app_documents.process_type_id', $this->process_type_id)
                    //->where('app_documents.doc_file_path', '!=', '')
                    ->get([
                        'attachment_list.id',
                        'attachment_list.doc_priority',
                        'attachment_list.additional_field',
                        'app_documents.id as document_id',
                        'app_documents.doc_file_path as doc_file_path',
                        'app_documents.doc_name',
                    ]);
            }
            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $token = $this->getToken();
            $vat_service_url = Config('stackholder.VATReg_SERVICE_API_URL');
            $public_html = strval(view("VATReg::application-form-edit", compact('token', 'vat_service_url', 'process_type_id', 'appInfo', 'appData','document', 'payment_config','viewMode')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1015]" . "</h4>"
            ]);
        }
    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function applicationView($appId, $openMode = '', Request $request)
    {
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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [VAT-973]</h4>"
            ]);
        }

        try {
            $decodedAppId = Encryption::decodeId($appId);
            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('vat_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
                    'process_type.max_processing_day',
                ]);
            $appData = json_decode($appInfo->appdata);

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

            /*
             * Visa Recommendation New module has category-based (Visa type based) application
             * [Like- PI type, A Type, Visa on arrival]
             */
            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $document = DynamicAttachmentVAT::where('process_type_id', $this->process_type_id)->where('ref_id', $decodedAppId)->get();
            $token = $this->getToken();
            $vat_service_url = Config('stackholder.VATReg_SERVICE_API_URL');
//            dd($appData);
//            return view("VATReg::application-form-edit", compact('token', 'vat_service_url', 'process_type_id', 'appInfo', 'appData', 'identity_category', 'purpose', 'authorized_identity_category', 'physical_condition', 'document'));
            $public_html = strval(view("VATReg::application-form-view", compact('token', 'vat_service_url', 'process_type_id', 'appInfo', 'appData', 'identity_category', 'purpose', 'authorized_identity_category', 'physical_condition', 'document', 'viewMode', 'spPaymentinformation')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('VRNViewForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [VRNC-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[VRNC-1015]" . "</h4>"
            ]);
        }
    }


    public function serviceHsCode(Request $request)
    {
        $hsType = $request->hsType;
        $section = $request->section;

        $functionName = '';
        if ($section == 'SECTION_I') {
            $functionName = 'SelectHSCodeI';
        } else if ($section == 'SECTION_L6') {
            $functionName = 'SelectHSCodeL6';
        } else if ($section == 'SECTION_L7_1') {
            $functionName = 'SelectHSCodeL71';
        } else if ($section == 'SECTION_L7_2') {
            $functionName = 'SelectHSCodeL72';
        }

        if ($hsType == 'service') {
            $hsCode = HsCodeService::where('hs_type', 1)->orderBy('id', 'asc')->get(['service_code', 'service_name', 'hs_type', 'start_date', 'end_date']);
        } else {
            $hsCode = HsCodeService::where('hs_type', 2)->orderBy('id', 'asc')->get(['service_code', 'service_name', 'hs_type', 'start_date', 'end_date']);
        }

        $mode = ACL::getAccsessRight('VATReg', 'E');

        return Datatables::of($hsCode)
            ->addColumn('action', function ($hsCode) use ($functionName) {
                if ($hsCode) {
                    return '<a href="javascript:void(0)" data-subclass="' . $hsCode->service_code . '@' . $hsCode->service_name . '" class="btn btn-xs btn-primary" onclick="' . $functionName . '(this)">Select</a>';
                }
            })
            ->make(true);
    }


    public function getDocList(Request $request)
    {
        $vat_service_url = Config('stackholder.VATReg_SERVICE_API_URL');
        $app_id = $request->appId;


        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $vat_service_url . "/required-documents",
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

                $clr_document = DynamicAttachmentVAT::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                }
                $html = view(
                    "VATReg::documents",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function uploadDocument()
    {
        return View::make('VATReg::ajaxUploadFile');
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

            VATReg::where('id', $processData->ref_id)->update(['is_submit' => 1]);
            $processData->save();

            RequestQueueVat::where('ref_id', $paymentInfo->app_id)->update([
                'status' => '0'
            ]);

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-applications/vat-registration/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-applications/vat-registration/list/' . Encryption::encodeId($this->process_type_id));
        }
    }
    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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
            \Illuminate\Support\Facades\DB::beginTransaction();
            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                $processData->read_status = 0;
                $general_submission_process_data = CommonFunction::getGeneralSubmission($this->process_type_id);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date


                VATReg::where('id', $processData->ref_id)->update(['is_submit' => 1]);


                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);



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
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('ctcc/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    private function convertToArray($data)
    {
        $defaultArray = [];
        foreach ($data as $value) {
            array_push($defaultArray, explode('@', $value)[0]);
        }

        return $defaultArray;
    }

    public function submissionJson($app_id, $status)
    {

        $appData = VATReg::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);

        $submissionData = [];
        $ownershipType = [];
        $economicActivity = [];
        $areaOfServices = [];
        $areaOfManufacturing = [];
        if (isset($masterData->ownership_type)) {
            $ownershipType = $this->convertToArray($masterData->ownership_type);
        }
        if (isset($masterData->economic_activity)) {
            $economicActivity = $this->convertToArray($masterData->economic_activity);
        }

        if (isset($masterData->economic_area)) {
            $areaOfManufacturing = $this->convertToArray($masterData->economic_area);
        }

        if (isset($masterData->area_service)) {
            $areaOfServices = $this->convertToArray($masterData->area_service);
        }
        $selectedvalue = "x";
        $mainform = [
            'A_A_REGIS_CATE_R1' => isset($masterData->reg_category) ? explode('@', $masterData->reg_category)[0] : "",
            'A_A_OLD_BIN' => isset($masterData->old_bin) ? $masterData->old_bin : "",
            'A_A_COMP_NAME' => isset($masterData->company_name) ? $masterData->company_name : "",
            'A_B_PROPRIETORSHIP' => isset($ownershipType) ? in_array('01', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_PARTNERSHIP' => isset($ownershipType) ? in_array('02', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_PRI_LTD' => isset($ownershipType) ? in_array('03', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_PUB_LTD' => isset($ownershipType) ? in_array('04', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_INTER_ORG' => isset($ownershipType) ? in_array('05', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_DIPLO_MISSION' => isset($ownershipType) ? in_array('06', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_GOVERNMENT' => isset($ownershipType) ? in_array('07', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_NGO' => isset($ownershipType) ? in_array('08', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_EDU_INSTITUTE' => isset($ownershipType) ? in_array('09', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_OTHER' => isset($ownershipType) ? in_array('10', $ownershipType) ? $selectedvalue : "" : "",
            'A_B_OTHER_TEXT' => isset($masterData->please_specify) ? $masterData->please_specify : "",
            'A_B_WITHOLDING_ENTITY_R1' => isset($masterData->withholding_entity) ? explode('@', $masterData->withholding_entity)[0] : "",
            'A_C_TRADE_LICENSE_NUM' => isset($masterData->tl_number) ? $masterData->tl_number : "",
            'A_C_TRADE_ISSUE_DATS' => isset($masterData->tl_issue_date) ? Carbon::parse($masterData->tl_issue_date)->format('d/m/Y') : "",
            'A_C_RJSC_INCOR_NUM' => isset($masterData->rjsc_inc_number) ? $masterData->rjsc_inc_number : "",
            'A_C_RJSC_ISSUE_DATS' => isset($masterData->rjsc_inc_issue_date) ? Carbon::parse($masterData->rjsc_inc_issue_date)->format('d.m.Y') : "",
            'A_C_ETIN' => isset($masterData->etin) ? $masterData->etin : "",
            'A_C_ETIN_COMP_NAME' => isset($masterData->etin_entity_name) ? $masterData->etin_entity_name : "",
            'A_C_OTHER_COMP_NAME' => isset($masterData->entity_name) ? $masterData->entity_name : "",
            'A_C_TRADE_BRAN_NAME' => isset($masterData->trading_brand_name) ? $masterData->trading_brand_name : "",
            'A_C_REGIS_TYPE_R1' => isset($masterData->registration_type) ? explode('@', $masterData->registration_type)[0] : "",
            'A_C_EQUITY_INFO_R1' => isset($masterData->equity_info) ? explode('@', $masterData->equity_info)[0] : "",
            'A_C_LOCAL_SHARE' => isset($masterData->local_share) ? $masterData->local_share : "",
            'A_C_BIDA_REGIS_NUM' => isset($masterData->bida_reg_number) ? $masterData->bida_reg_number : "",
            'A_D_FACTOR_ADDR' => isset($masterData->factory_address) ? $masterData->factory_address : "",
            'A_D_DISTRICT' => isset($masterData->district) ? explode('@', $masterData->district)[0] : "",
            'A_D_POLICESTATION' => isset($masterData->police_station) ? explode('@', $masterData->police_station)[0] : "",
            'A_D_POSTALCODE' => isset($masterData->post_code) ? explode('@', $masterData->post_code)[0] : "",
            'A_D_LAND_TELE_NUM' => isset($masterData->land_telephone) ? $masterData->land_telephone : "",
            'A_D_MOBILE_TELE_NUM' => isset($masterData->mobile_telephone) ? $masterData->mobile_telephone : "",
            'A_D_EMAIL' => isset($masterData->email) ? $masterData->email : "",
            'A_D_FAX_NUM' => isset($masterData->fax) ? $masterData->fax : "",
            'A_D_WEB_ADDR' => isset($masterData->web_address) ? $masterData->web_address : "",
            'A_D_REGIS_HQ_ADDR' => isset($masterData->headquarter_address) ? $masterData->headquarter_address : "",
            'A_D_CHECK_SAME_ADDR' => isset($masterData->same_as_factory) ? $masterData->same_as_factory : "",
            'A_D_REGIS_HQ_ADDR_OUTSIDE_BD' => isset($masterData->headquarter_address_outside) ? $masterData->headquarter_address_outside : "",
            'A_F_MANUFACTURING' => isset($economicActivity) ? in_array('01', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_SERVICES' => isset($economicActivity) ? in_array('02', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_RETAIL_WHOLESALE_TRADING' => isset($economicActivity) ? in_array('03', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_IMPORTS' => isset($economicActivity) ? in_array('04', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_IRC_NUM' => isset($masterData->support_document_imports) ? $masterData->support_document_imports : "",
            'A_F_EXPORTS' => isset($economicActivity) ? in_array('05', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_ERC_NUM' => isset($masterData->support_document_exports) ? $masterData->support_document_exports : "",
            'A_F_OTHER' => isset($economicActivity) ? in_array('06', $economicActivity) ? $selectedvalue : "" : "",
            'A_F_OTHER_TEXT' => isset($masterData->please_specify_f) ? $masterData->please_specify_f : "",
            'A_G_AGRI_FORE_FISH' => isset($areaOfManufacturing) ? in_array('01', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_EDIBLE_OIL' => isset($areaOfManufacturing) ? in_array('02', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_FOOD_BEVERAGE' => isset($areaOfManufacturing) ? in_array('03', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_TOBACCO' => isset($areaOfManufacturing) ? in_array('04', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_ORES_MINERALS' => isset($areaOfManufacturing) ? in_array('05', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_CHEMICAL_PROD' => isset($areaOfManufacturing) ? in_array('06', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_PLAS_RUBB_PROD' => isset($areaOfManufacturing) ? in_array('07', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_LEATHER_PROD' => isset($areaOfManufacturing) ? in_array('08', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_WOOD_FURNITURE' => isset($areaOfManufacturing) ? in_array('09', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_PAPER_PROD' => isset($areaOfManufacturing) ? in_array('10', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_TEXTILES_APPARELS' => isset($areaOfManufacturing) ? in_array('11', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_GLASS_CERA_STON_ARTI' => isset($areaOfManufacturing) ? in_array('12', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_JEWELRY' => isset($areaOfManufacturing) ? in_array('13', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_IRON_STEEL_OTHER_PROD' => isset($areaOfManufacturing) ? in_array('14', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_MACHINERY_EQUIPMENT' => isset($areaOfManufacturing) ? in_array('15', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_ELECTRICAL_ELECTRONICS' => isset($areaOfManufacturing) ? in_array('16', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_AUTOMOBILES' => isset($areaOfManufacturing) ? in_array('17', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_CYCLES_MOTORCYCLES' => isset($areaOfManufacturing) ? in_array('18', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_WATERCRAFT' => isset($areaOfManufacturing) ? in_array('19', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_AVIATION' => isset($areaOfManufacturing) ? in_array('20', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_OPTICAL_INSTRUMENTS' => isset($areaOfManufacturing) ? in_array('21', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_OTHER' => isset($areaOfManufacturing) ? in_array('22', $areaOfManufacturing) ? $selectedvalue : "" : "",
            'A_G_OTHER_TEXT' => isset($masterData->please_specify_g) ? $masterData->please_specify_g : "",
            'A_H_CONSTRUCTION' => isset($areaOfServices) ? in_array('01', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_TRADING_INCLD_ECOMM' => isset($areaOfServices) ? in_array('02', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_REAL_ESTATE' => isset($areaOfServices) ? in_array('03', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_TRANSPORT' => isset($areaOfServices) ? in_array('04', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_ELECT_GAS_WATER_SUPPLY' => isset($areaOfServices) ? in_array('05', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_FINANCIAL_INSTITUTION' => isset($areaOfServices) ? in_array('06', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_HOTEL_GUEST_HOUSES' => isset($areaOfServices) ? in_array('07', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_RESTAURANTS' => isset($areaOfServices) ? in_array('08', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_RENTAL_LEASING_SRV' => isset($areaOfServices) ? in_array('09', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_RESEARCH_CONSULTANCY' => isset($areaOfServices) ? in_array('10', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_HEALTHCARE' => isset($areaOfServices) ? in_array('11', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_EDUCATION_TRAINING' => isset($areaOfServices) ? in_array('12', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_TELECOMM_INTERNET' => isset($areaOfServices) ? in_array('13', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_SOFTWARE_ITES' => isset($areaOfServices) ? in_array('14', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_SPORT_ENTERTAINMENT' => isset($areaOfServices) ? in_array('15', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_EVENT_MNG_CATERING' => isset($areaOfServices) ? in_array('16', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_WORKSHOP_ENGINEERING' => isset($areaOfServices) ? in_array('17', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_TOUR_OPER_TRAVEL_AGENT' => isset($areaOfServices) ? in_array('18', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_ADVERTISING_PROMOTION' => isset($areaOfServices) ? in_array('19', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_CUST_BROKE_FREI_FWD' => isset($areaOfServices) ? in_array('20', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_RADIO_TV_OPERATION' => isset($areaOfServices) ? in_array('21', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_CONSULTANCY' => isset($areaOfServices) ? in_array('22', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_OTHER' => isset($areaOfServices) ? in_array('23', $areaOfServices) ? $selectedvalue : "" : "",
            'A_H_OTHER_TEXT' => isset($masterData->please_specify_h) ? $masterData->please_specify_h : "",
            'A_L_EMPLOYEES_NUM' => isset($masterData->employee_number) ? $masterData->employee_number : "",
            'A_L_ZERO_RATED_SUPPLY_R1' => isset($masterData->zero_rated_supply) ? $masterData->zero_rated_supply : "", // need to clear
            'A_L_VAT_EXEMPT_SUPPLY_R1' => isset($masterData->vat_extended_supply) ? $masterData->vat_extended_supply : "",
            'A_N_FULLNAME' => isset($masterData->sfp_contact_name) ? $masterData->sfp_contact_name : "",
            'A_N_DESIGNATION' => !empty($masterData->auth_designation) ? explode('@', $masterData->auth_designation)[1] : "",
            'A_N_CONFIRM' => isset($masterData->accept_terms) ? $selectedvalue : "",
            'A_CIR_VAT_OFFICE' => "",
            'A_EFFE_DATE' => "",
            'A_EXIST_BIN' => "",
            'A_BIN_NAME' => "",
            'A_COMM_CERT' => "",
            'A_OFFICER_CONFIRM' => "",
            'A_N_DESIGN_ID' => !empty($masterData->auth_designation) ? explode('@', $masterData->auth_designation)[0] : "",
            'A_C_BIDA_REGIS_DATS' => isset($masterData->bida_reg_issue_date) ? Carbon::parse($masterData->bida_reg_issue_date)->format('d.m.Y') : "",
            'A_F_IMPORT_IRC_DATS' => isset($masterData->issue_date_imports) ? Carbon::parse($masterData->issue_date_imports)->format('d.m.Y') : "",
            'A_F_EXPORT_ERC_DATS' => isset($masterData->issue_date_exports) ? Carbon::parse($masterData->issue_date_exports)->format('d.m.Y') : "",
            'A_L_TAX_TOT_PAST_12_MONTHS_02' => isset($masterData->taxable_turnover) ? $masterData->taxable_turnover : "",
            'A_L_PRO_TOT_NEXT_12_MONTHS_02' => isset($masterData->projected_turnover) ? $masterData->projected_turnover : "",
            'A_IS_FORCE' => "",
            'FORCE_TYPE' => "",
        ];
        $mSection = [[
            "A_FULLNAME" => "",
            "A_DESCRIPTION" => "",
            "A_ID_CATEGORY" => "",
            "A_NID" => "",
            "A_PASSPORT_NUM" => "",
            "A_ISSUE_COUNTRY" => "",
            "A_MOBILE_NUM" => "",
            "A_EMAIL" => "",
            "A_PURPOSE" => ""
        ]];
        if (isset($masterData->full_name_authorized)) {
            $mSection = [];
            foreach ($masterData->full_name_authorized as $key => $value) {
                $mSectiondata = [
                    "A_FULLNAME" => $masterData->full_name_authorized[$key],
                    "A_DESCRIPTION" => isset($masterData->authorized_designation[$key]) ? explode('@', $masterData->authorized_designation[$key])[0] : "",
                    "A_ID_CATEGORY" => isset($masterData->identity_category_authorized[$key]) ? explode('@', $masterData->identity_category_authorized[$key])[0] : "",
                    "A_NID" => !empty($masterData->authorized_nid)?$masterData->authorized_nid[$key]:'',
                    "A_PASSPORT_NUM" => !empty($masterData->authorized_passport_no)?$masterData->authorized_passport_no[$key]:'',
                    "A_ISSUE_COUNTRY" => isset($masterData->authorized_nationality[$key]) ? explode('@', $masterData->authorized_nationality[$key])[0] : "",
                    "A_MOBILE_NUM" => $masterData->authorized_mobile[$key],
                    "A_EMAIL" => $masterData->authorized_email[$key],
                    "A_PURPOSE" => isset($masterData->purpose[$key]) ? explode('@', $masterData->purpose[$key])[0] : ""
                ];

                $mSection[] = $mSectiondata;
            }
        }
        $jSection = [[
            "A_ACCNAME" => "",
            "A_ACCNO" => "",
            "A_BANCD" => "",
            "A_NAMEOFBANK" => "",
            "A_BANKL" => "",
            "A_BRANCH" => ""
        ]];


        if (isset($masterData->account_name)) {
            $jSection = [];
            foreach ($masterData->account_name as $key => $value) {
                $jSectiondata = [
                    "A_ACCNAME" => $masterData->account_name[$key],
                    "A_ACCNO" => $masterData->account_number[$key],
                    "A_BANCD" => isset($masterData->bank_name[$key]) ? explode('@', $masterData->bank_name[$key])[0] : "",
                    "A_NAMEOFBANK" => isset($masterData->bank_name[$key]) ? explode('@', $masterData->bank_name[$key])[1] : "",
                    "A_BANKL" => isset($masterData->branch_name[$key]) ? explode('@', $masterData->branch_name[$key])[0] : "",
                    "A_BRANCH" => isset($masterData->branch_name[$key]) ? explode('@', $masterData->branch_name[$key])[1] : ""

                ];

                $jSection[] = $jSectiondata;
            }
        }


        $branchInfo = [[
            "A_ADDR" => "",
            "A_BRANCH_NAME" => "",
            "A_ANNUAL_TURNOVER" => "",
            "A_BIN" => "",
            "A_CATEGORY" => "",
            "A_BRANCH_ID" => "",
            "A_ID_TEXT" => ""
        ]];

        if (isset($masterData->e_branch_name)) {
            $branchInfo = [];
            foreach ($masterData->e_branch_name as $key => $value) {
                $branchData = [
                    "A_ADDR" => $masterData->branch_address[$key],
                    "A_BRANCH_NAME" => $masterData->e_branch_name[$key],
                    "A_ANNUAL_TURNOVER" => $masterData->annual_turnover[$key],
                    "A_BIN" => $masterData->bin[$key],
                    "A_CATEGORY" => isset($masterData->branch_category[$key]) ? explode('@', $masterData->branch_category[$key])[0] : "",
                    "A_BRANCH_ID" => $masterData->branch_id[$key],
                    "A_ID_TEXT" => "",
                ];

                $branchInfo[] = $branchData;
            }
        }

        $iSection = [[
            "A_DESC_ECONOMIC_ACTVT" => "",
            "GOSERV_CODE" => "",
            "NAME" => "",
            "ITEM_ID" => ""
        ]];
        if (isset($masterData->commercial_description)) {
            $iSection = [];
            foreach ($masterData->commercial_description as $key => $value) {
                $iSectionData = [
                    "A_DESC_ECONOMIC_ACTVT" => $masterData->commercial_description[$key],
                    "GOSERV_CODE" => !empty($masterData->hs_code_hidden[$key]) ? explode('@', $masterData->hs_code_hidden[$key])[1] : "",
                    "NAME" => !empty($masterData->hs_code_hidden[$key]) ? explode('@', $masterData->hs_code_hidden[$key])[2] : "",
                    "ITEM_ID" => !empty($masterData->hs_code_hidden[$key]) ? explode('@', $masterData->hs_code_hidden[$key])[0] : "",
                ];
                $iSection[] = $iSectionData;
            }
        }

        $l7Section = [[
            "A_DESC_OUTPUT_SELL_UNIT" => "",
            "A_CODE" => "",
            "A_DESC_MAJOR_INPUT" => "",
            "A_QUAN_UNIT_OUTPUT" => "",
            "GOSERV_CODE_OUT" => "",
            "A_SELLING_UNIT" => "",
            "GOSERV_CODE_IN" => "",
            "ITEM_ID_IN" => "",
            "ITEM_ID_OUT" => "",
            "ITEM_ID" => ""
        ]];

        if (isset($masterData->commercial_description_output)) {
            $l7Section = [];
            foreach ($masterData->commercial_description_output as $key => $value) {
                $l7SectionData = [
                    "A_DESC_OUTPUT_SELL_UNIT" => $masterData->commercial_description_output[$key],
                    "A_CODE" => "",
                    "A_DESC_MAJOR_INPUT" => $masterData->description_major_inputs[$key],
                    "A_QUAN_UNIT_OUTPUT" => $masterData->quantity_used[$key],
                    "GOSERV_CODE_OUT" => !empty($masterData->hs_code_output_hidden[$key]) ? explode('@', $masterData->hs_code_output_hidden[$key])[1] : "",
                    "A_SELLING_UNIT" => $masterData->selling_unit[$key],
                    "GOSERV_CODE_IN" => !empty($masterData->hs_code_input_hidden[$key]) ? explode('@', $masterData->hs_code_input_hidden[$key])[1] : "",
                    "ITEM_ID_IN" => !empty($masterData->hs_code_input_hidden[$key]) ? explode('@', $masterData->hs_code_input_hidden[$key])[0] : "",
                    "ITEM_ID_OUT" => !empty($masterData->hs_code_output_hidden[$key]) ? explode('@', $masterData->hs_code_output_hidden[$key])[0] : "",
                    "ITEM_ID" => ""
                ];

                $l7Section[] = $l7SectionData;
            }
        }

        $l6Section = [[
            "A_DESCRIPTION" => "",
            "A_VALUE_BDT" => "",
            "GOSERV_CODE" => "",
            "A_PRO_CAPACITY" => "",
            "A_CONDITION" => "",
            "ITEM_ID" => ""
        ]];

        if (isset($masterData->description)) {
            $l6Section = [];
            foreach ($masterData->description as $key => $value) {
                $l6SectionData = [
                    "A_DESCRIPTION" => $masterData->description[$key],
                    "A_VALUE_BDT" => $masterData->value_bdt[$key],
                    "GOSERV_CODE" => isset($masterData->hs_code_major_hidden[$key]) ? explode('@', $masterData->hs_code_major_hidden[$key])[1] : "",
                    "A_PRO_CAPACITY" => $masterData->production_capacity[$key],
                    "A_CONDITION" => isset($masterData->physical_condition[$key]) ? explode('@', $masterData->physical_condition[$key])[0] : "",
                    "ITEM_ID" => isset($masterData->hs_code_major_hidden[$key]) ? explode('@', $masterData->hs_code_major_hidden[$key])[0] : "",
                ];

                $l6Section[] = $l6SectionData;
            }
        }


        $kSection = [[
            "A_ETIN" => "",
            "A_FULLNAME" => "",
            "A_DESCRIPTION" => "",
            "A_SHARE" => "",
            "A_ID_CATEGORY" => "",
            "A_NID" => "",
            "A_PASSPORT_NUM" => "",
            "A_ISSUE_COUNTRY" => "",
            "A_BIN" => "",
        ]];
        if (isset($masterData->owner_name)) {
            $kSection = [];
            foreach ($masterData->owner_name as $key => $value) {
//                dd($masterData->owner_designation);
                $kSectionData = [
                    "A_ETIN" => $masterData->e_tin[$key],
                    "A_FULLNAME" => $masterData->owner_name[$key],
                    "A_DESCRIPTION" => !empty($masterData->owner_designation[$key]) ? explode('@', $masterData->owner_designation[$key])[1] : "",
                    "A_SHARE" => $masterData->share[$key],
                    "A_ID_CATEGORY" => isset($masterData->identity_category_owner[$key]) ? explode('@', $masterData->identity_category_owner[$key])[0] : "",
                    "A_NID" => !empty($masterData->owner_nid)?$masterData->owner_nid[$key]:'',
                    "A_PASSPORT_NUM" => !empty($masterData->owner_passport_no)?$masterData->owner_passport_no[$key]:'',
                    "A_ISSUE_COUNTRY" => isset($masterData->owner_nationality[$key]) ? explode('@', $masterData->owner_nationality[$key])[0] : "",
                    "A_BIN" =>!empty($masterData->owner_bin)?$masterData->owner_bin[$key]:'',
                ];

                $kSection[] = $kSectionData;
            }
        }

        $bin9 = [array(
            'A_BIN_9' => !empty($masterData->registeredBin) && $masterData->registeredBin == 2 ? !empty($masterData->bin_number) ? $masterData->bin_number : "" : ""
        )];


        $submissionData ['MainForm'] = $mainform;
        $submissionData ['ZTF_AUTHOR'] = $mSection;
        $submissionData ['ZTF_BANKACC_1'] = $jSection;
        $submissionData ['ZTF_BIN_9'] = $bin9;
        $submissionData ['ZTF_BRANCH_INFO'] = $branchInfo; //E section
        $submissionData ['ZTF_BUSI_CLAS'] = $iSection;
        $submissionData ['ZTF_IO_DATA'] = $l7Section;
        $submissionData ['ZTF_MAJOR_CM'] = $l6Section;
        $submissionData ['ZTF_OWNER_DIRECT'] = $kSection;
        $documents = DynamicAttachmentVAT::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();
        $allDocumentsInfo = [];
        $allDocumentsData = [];
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";

        $link .= "://";
        $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';
        $base64url = Config('stackholder.bsse64_public_api'). $hosturl;
        foreach ($documents as $value) {
            $docInfo = [
                "ATT_DOCTYPE" => $value->doc_id,
                "TEXT" => $value->doc_name,
                "NOTES" => "",
                "CHKBOX" => !empty($value->doc_path) && $value->doc_path != "" ? $selectedvalue : "",
            ];
            $allDocumentsInfo[] = $docInfo;

            if (!empty($value->doc_path) && $value->doc_path != "") {
                $pathinfo = pathinfo($value->doc_path);
                $docdata = [
                    "FILENAME" => $pathinfo['filename'],
                    "CONTENT" => $base64url . $value->doc_path,
                    "FILETYPE" => $pathinfo['extension'],
                    "DOCTYPE" => "atta",
                ];
                $allDocumentsData[] = $docdata;
            }

        }

        $submissionData['ZTF_R114_ATT_DOCTYPE'] = $allDocumentsInfo;
        $submissionData['ATTACH_DOCUMENTS'] = $allDocumentsData;

        if ($status == 2) {
            $vatRequestQueue = new RequestQueueVat();
            $vatRequestQueue->type = 'Resubmission';
            $vatRequestQueue->status = 0;   // 10 = payment not submitted
            $vatAppStatus = VatApplicatonStatus::where('id', $app_id)
                ->update(['completed' => 0]);
        } else {
            $vatRequestQueue = RequestQueueVat::firstOrNew([
                'ref_id' => $app_id
            ]);
            $vatRequestQueue->type = 'Submission';
            $vatRequestQueue->status = 10;   // 10 = payment not submitted
        }
        $vatRequestQueue->ref_id = $app_id;
        $vatRequestQueue->request_json = json_encode($submissionData);

        $vatRequestQueue->save();

    }

    public function showShortfallMessage($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $tracking_no = ProcessList::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->value('tracking_no');
        $shortfallData = VatApplicatonStatus::where('ref_id', $app_id)->first();
        return view('VATReg::vat-shortfall-message', compact('shortfallData', 'tracking_no'));

    }
}
