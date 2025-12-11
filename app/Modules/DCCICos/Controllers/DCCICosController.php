<?php

namespace App\Modules\DCCICos\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\BccCDA\Models\DynamicAttachmentBccCDA;
use App\Modules\DCCICos\Models\DCCICos;
use App\Modules\DCCICos\Models\DCCICosShortfall;
use App\Modules\DCCICos\Models\DCCICosUserInfo;
use App\Modules\DCCICos\Models\DCCIPaymentConfirm;
use App\Modules\DCCICos\Models\DynamicAttachmentDCCICos;
use App\Modules\DCCICos\Models\RequestQueueDCCICos;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\CompanyInfo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mockery\Exception;


class DCCICosController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 123;
        $this->aclName = 'DCCI_COS';
    }


    public function appForm()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [DCCI-COS-971]</h4>"]);
        }
        try {
            $companyId = Auth::user()->working_company_id;
            $companyname = CompanyInfo::where('id', $companyId)->first(['company_name']);
            $oss_user_info['userEmail'] = Auth::user()->user_email;
            $oss_user_info['userMobile'] = Auth::user()->user_phone;
            $oss_user_info['companyName'] = $companyname->company_name;
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("DCCICos::application-form", compact('process_type_id', 'viewMode', 'oss_user_info', 'mode', 'token', 'bida_service_url')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [DCCI-COS-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 200px;text-align: center;'>You have no access right! Contact with system admin for more information. [DCCI-COS-77]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = Auth::user()->working_company_id;
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($request->get('app_id'));
                $appData = DCCICos::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
                DynamicAttachmentDCCICos::where('ref_id', $appData->id)->where('doc_type', 'other')->delete();
            } else {
                $appData = new DCCICos();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
                $user_mail = Auth::user()->user_email;
                $dcci_membership = DCCICosUserInfo::where('user_mail', $user_mail)->first(['dcci_response_json']);
                $decoded_membership = json_decode($dcci_membership->dcci_response_json);
                $is_member = ($decoded_membership->isDcciMember != '' || $decoded_membership->isDcciMember != null) ? $decoded_membership->isDcciMember : 'NO';
                $appData->dcci_member = $is_member;
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


            // Start file uploading
            $docIds = $request->get('dynamicDocumentsId');
            //Start file uploading
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[1];
                    $doc_name = $docIdName[0];
                    $doc_priority = $docIdName[2];
                    $app_doc = DynamicAttachmentDCCICos::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_priority = $doc_priority;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->date = !empty($request->get('date_' . $doc_id)) ? Carbon::parse($request->get('date_' . $doc_id))->format('y-m-d') : '';
                    $app_doc->doc_number = $request->get('number_' . $doc_id);
                    $app_doc->save();
                }
            } /* End file uploading */

            $docArr = ($request->get('validate_field_otherFile') !== '') ? $request->get('validate_field_otherFile') : [];
            if (count($docArr) > 0) {
                $iKey = 100;
                foreach ($docArr as $key => $path) {
                    $application_doc = DynamicAttachmentDCCICos::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $iKey
                    ]);
                    $application_doc->doc_name = 'Other File ' . ($key + 1);
                    $application_doc->doc_priority = 0;
                    $application_doc->doc_path = $path;
                    $application_doc->doc_type = 'other';
                    $application_doc->save();
                    $iKey = $iKey + 1;
                }
            }
            /* End file uploading */

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'DCCI-' . date("dMY") . '-';
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
                $dcci_tracking_no = !empty($request->tracking_id) ? $request->tracking_id : '';
                $this->SubmissionJson($appData->id, $tracking_no, $processData->status_id, $request->ip(), $dcci_tracking_no);
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


            if ($request->get('actionBtn') == "draft") {
                return redirect('dcci-cos/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('dcci-cos/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('dcci-cos/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

    public function SubmissionJson($app_id, $tracking_no, $statusid, $ip_address, $dcci_tracking_no)
    {
        // Submission Request Data

        $dcciRequest = RequestQueueDCCICos::firstOrNew([
            'ref_id' => $app_id
        ]);
        if ($dcciRequest->status == 0 || $dcciRequest->status == -1 || $dcciRequest->status == 1 || $dcciRequest->status == 5) {
            $appData = DCCICos::where('id', $app_id)->first();
            $masterData = json_decode($appData->appdata);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
                $link = "https";
            else
                $link = "http";

            $companyData = [
                'ref_country_id' => !empty($masterData->country_company) ? (int)explode('@', $masterData->country_company)[0] : '',
                'ref_division_id' => !empty($masterData->division_company) ? (int)explode('@', $masterData->division_company)[0] : '',
                'ref_district_id' => !empty($masterData->district_company) ? (int)explode('@', $masterData->district_company)[0] : '',
                'ref_thana_id' => !empty($masterData->thana_company) ? (int)explode('@', $masterData->thana_company)[0] : '',
                'ref_post_office_id' => !empty($masterData->post_office_company) ? (int)explode('@', $masterData->post_office_company)[0] : '',
                'company_address' => !empty($masterData->road_company) ? $masterData->road_company : '',
                'currency' => !empty($masterData->currency) ? explode('@', $masterData->currency)[0] : '',
                'factory_country_id' => !empty($masterData->country_factory) ? (int)explode('@', $masterData->country_factory)[0] : '',
                'factory_division_id' => !empty($masterData->division_factory) ? (int)explode('@', $masterData->division_factory)[0] : '',
                'factory_district_id' => !empty($masterData->district_factory) ? (int)explode('@', $masterData->district_factory)[0] : '',
                'factory_upazila_id' => !empty($masterData->thana_factory) ? (int)explode('@', $masterData->thana_factory)[0] : '',
                'factory_post_office_id' => !empty($masterData->post_office_factory) ? (int)explode('@', $masterData->post_office_factory)[0] : '',
                'factory_address' => !empty($masterData->road_factory) ? $masterData->road_factory : '',
                'consignee_company_name' => !empty($masterData->consignee_company_name) ? $masterData->consignee_company_name : '',
                'consignee_address' => !empty($masterData->company_address) ? $masterData->company_address : '',
                'destination_address' => !empty($masterData->destination_address) ? $masterData->destination_address : '',
                'particulars_of_transport' => !empty($masterData->particuler_transport) ? $masterData->particuler_transport : '',
            ];

            $companyData['goods_marks'] = !empty($masterData->marks) ? $masterData->marks : [];
            $companyData['goods_quantity'] = !empty($masterData->quantity) ? $masterData->quantity : [];
            $companyData['goods_description'] = !empty($masterData->description) ? $masterData->description : [];
            $companyData['goods_weight'] = !empty($masterData->weight) ? $masterData->weight : [];
            $companyData['goods_value'] = !empty($masterData->value) ? $masterData->value : [];
            $companyData['ref_goods_hs_code_id'] = !empty($masterData->hscodeId) ? $masterData->hscodeId : [];

            //Update application when shortfall
            if (!empty($dcci_tracking_no)) {
                $companyData['tracking_id'] = $dcci_tracking_no;
                $dcciRequest->type = 'resubmission';
            } else {
                $dcciRequest->type = 'submission';
            }

            $doc_data = [
                'invoice_no' => !empty($masterData->number_2) ? $masterData->number_2 : '',
                'invoice_date' => !empty($masterData->date_2) ? Carbon::parse($masterData->date_2)->format('Y-m-d') : '',
                'exp_no' => !empty($masterData->number_3) ? $masterData->number_3 : '',
                'exp_date' => !empty($masterData->date_3) ? Carbon::parse($masterData->date_3)->format('Y-m-d') : '',
                'lc_tt_cont_pl_no' => !empty($masterData->number_4) ? $masterData->number_4 : '',
                'lc_tt_cont_pl_date' => !empty($masterData->date_4) ? Carbon::parse($masterData->date_4)->format('Y-m-d') : '',
                'print_count_original' => !empty($masterData->print_count_original) ? $masterData->print_count_original : '',
                'packaging_list_no' => !empty($masterData->number_5) ? $masterData->number_5 : '',
                'packaging_list_date' => !empty($masterData->date_5) ? $masterData->date_5 : '',
                'print_count_copy' => !empty($masterData->print_count_copy) ? $masterData->print_count_copy : ''
            ];
            $formData['application_dto'] = array_merge($companyData, $doc_data);
            $formData['signature'] = !empty($masterData->validate_field_signature) ? $masterData->validate_field_signature : '';
            $formData['seal'] = !empty($masterData->validate_field_seal) ? $masterData->validate_field_seal : '';
            $formData['commercial_invoice'] = !empty($masterData->validate_field_2) ? $masterData->validate_field_2 : '';
            $formData['export_form'] = !empty($masterData->validate_field_3) ? $masterData->validate_field_3 : '';
            $formData['lctt'] = !empty($masterData->validate_field_4) ? $masterData->validate_field_4 : '';
            $formData['packaging_list'] = !empty($masterData->validate_field_5) ? $masterData->validate_field_5 : '';
            $other = !empty($masterData->validate_field_otherFile) ? $masterData->validate_field_otherFile : [];
            foreach ($other as $key => $value) {
                $k = $key + 1;
                $formData['other' . $k] = $value;
            }
            $dcciRequest->ref_id = $appData->id;
            $dcciRequest->tracking_no = $tracking_no;
            $dcciRequest->applicant_mail = Auth::user()->user_email;
            $dcciRequest->status = 0;
            $dcciRequest->request_json = json_encode($formData);
            $dcciRequest->save();
        }
    }


    public function applicationEdit($appId, $openMode = '', Request $request)
    {
        $viewMode = 'SecurityBreak';
        $mode = '-E-';

        if (!ACL::getAccsessRight('BccCDA', $mode)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }
        try {
            $user_info = Auth::user()->user_email;
            $applicationId = Encryption::decodeId($appId);

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftjoin('dcci_cos_apps', 'dcci_cos_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'dcci_cos_apps.sf_payment_id')
                ->where('process_list.process_type_id', $this->process_type_id)
                ->where('process_list.ref_id', $applicationId)
//                ->whereIn('process_list.company_id', $companyIds)
                ->first([
                    'dcci_cos_apps.*',
                    'ps.status_name',
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
                ]);

            $appData = json_decode($appInfo->appdata);
            $shortfallmsg = '';
            if ($appInfo->status_id == 5) {
                $shortfall = DCCICosShortfall::where('ref_id', $applicationId)->first(['response_json']);
                $decoded_response = json_decode($shortfall->response_json);
                $shortfallmsg = $decoded_response->payload->details;
            }

            $token = $this->getToken();
            $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');

            $public_html = strval(view("DCCICos::application-form-edit", compact('appInfo', 'appData', 'viewMode', 'bida_service_url', 'user_info', 'token', 'mode', 'shortfallmsg')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch
        (\Exception $e) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[CDA BCC-1015]" . "</h4>"
            ]);
        }
    }

    public function applicationView($appId, Request $request)
    {
        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [TRADE LIcense DSCC-974]</h4>"
            ]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        //dd($decodedAppId);
        $process_type_id = $this->process_type_id;
        //$companyIds = CommonFunction::getUserCompanyWithZero();

        // get application,process info

        $appInfo = ProcessList::leftJoin('dcci_cos_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
        //            dd($appData);
//        $dynamic_shortfall = DynamicShortfallAttachmentDPDC::where('ref_id', $appInfo->ref_id)->get();
        // dd($dynamic_shortfall);
        $pdf = RequestQueueDCCICos::where('origin_certificate_status', 1)
            ->where('ref_id', $decodedAppId)->first(['origin_certificate']);
        $certificate = RequestQueueDCCICos::where('cosignor_certificate_status', 1)
            ->where('ref_id', $decodedAppId)->first(['cosignor_certificate']);
        $company_id = $appInfo->company_id;
        $document = DynamicAttachmentDCCICos::where('process_type_id', $this->process_type_id)->where(['ref_id' => $decodedAppId, 'doc_type' => 'regular'])->orderBy('doc_id')->get();

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

        $token = $this->getToken();

        $tl_dscc_service_url = Config('stackholder.TL_DSCC_SERVICE_API_URL');

        $public_html = strval(view(
            "DCCICos::application-form-view",
            compact('appInfo', 'appData', 'pdf', 'document', 'process_type_id', 'viewMode', 'mode', 'token', 'tl_dscc_service_url', 'spPaymentinformation', 'certificate')
        ));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function getDynamicDoc(Request $request)
    {
        $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
        $app_id = $request->appId;
        $user_mail = Auth::user()->user_email;
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bida_service_url . "/info/document-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json",
                "user-email: " . $user_mail,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data'];
                $clr_document = DynamicAttachmentDCCICos::where('process_type_id', $this->process_type_id)->where(['ref_id' => $app_id, 'doc_type' => 'regular'])->get();

                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                    $clrDocuments[$documents->doc_id]['doc_number'] = $documents->doc_number;
                    $clrDocuments[$documents->doc_id]['date'] = $documents->date;
                }

                $clr_other_document = DynamicAttachmentDCCICos::where('process_type_id', $this->process_type_id)->where(['ref_id' => $app_id, 'doc_type' => 'other'])->get();

                $clrOtherDocuments = [];

                foreach ($clr_other_document as $otherdocuments) {
                    $clrOtherDocuments[$otherdocuments->doc_id]['doucument_id'] = $otherdocuments->doc_id;
                    $clrOtherDocuments[$otherdocuments->doc_id]['file'] = $otherdocuments->doc_path;
                    $clrOtherDocuments[$otherdocuments->doc_id]['doc_name'] = $otherdocuments->doc_name;
                }

                $html = view(
                    "DCCICos::dynamic-document",
                    compact('attachment_list', 'clrDocuments', 'clrOtherDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function getUnitPrice(Request $request)
    {
        $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
        $user_mail = Auth::user()->user_email;

        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bida_service_url . "/info/get-co-payment-list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json",
                "user-email: " . $user_mail,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $data = '';
        if ($decoded_response['responseCode'] == 200) {
            $data = $decoded_response['data']['payload'];
        }
        return response()->json(['responseCode' => 1, 'data' => $data]);
    }

    public function hsCodeSearch(Request $request)
    {
        $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
        $app_id = $request->appId;
        $q_data = $request->q;
        $user_mail = Auth::user()->user_email;
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bida_service_url . "/info/hs-code/" . $q_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer  $token",
                "Content-Type: application/json",
                "user-email: " . $user_mail,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        return json_encode($decoded_response['data']);
    }

    public function dcciUserInfo(Request $request)
    {
        $user['user_email'] = Auth::user()->user_email;
        $user['membership_id'] = $request->membership_id;
        $user['passbook_no'] = $request->passbook_no;
        $dcci_user_info = $this->getUserInfo($user);
        $dcci_user_info = json_decode($dcci_user_info);
        $user_mail= '';
        $responseCode = 1;
        $companyId = Auth::user()->working_company_id;
        $company_name_col = CompanyInfo::select('company_name')->find($companyId);
        $company_name = $company_name_col->company_name;
        if ($dcci_user_info->responseCode == 400) {
            $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
            $token = $this->getToken();
            $phone = Auth::user()->user_phone;
            $member_id = $request->membership_id;
            $passbook_no = $request->passbook_no;
            $number_with_code = substr($phone, 0, 3);
            if ($number_with_code == '+88') {
                $phone = substr($phone, 3);
            }
            $request_data = json_encode([
                'user_email' => Auth::user()->user_email,
                'user_mobile' => $phone,
                'company_name' => $company_name,
                'membership_id' => $member_id,
                'passbook_no' => $passbook_no,
            ]);

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $bida_service_url . "/info/user-info",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $request_data,
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer $token",
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            $decoded_response = json_decode($response, true);
            $user_info = $decoded_response['data'];
            $user_mail = $user_info['userEmail'];

        }else{
            $user_mail = $dcci_user_info->data->userEmail;
            if ($dcci_user_info->data->companyName != $company_name) {
                $company_name = $dcci_user_info->data->companyName;
                $responseCode = 2;
            }
        }
        $enc_id = Encryption::encodeId($this->process_type_id) ;
        return json_encode(['responseCode'=> $responseCode,'enc_id'=>$enc_id, 'user_mail'=> $user_mail ,'company_name'=>$company_name]);
    }

    public function getUserInfo($user)
    {
        $bida_service_url = Config('stackholder.BIDA_SERVICE_API_URL');
        $token = $this->getToken();
        $request_data = json_encode([
            'user_email' => $user['user_email'],
            'membership_id' => $user['membership_id'],
            'passbook_no' => $user['passbook_no'],
        ]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $bida_service_url . "/info/user-information",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        $err = curl_error($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        if($decoded_response['data'] != ''){
            $dcci_user_info = DCCICosUserInfo::firstOrNew([
                'oss_user_id' => Auth::user()->id,
                'user_mail' => $user['user_email']
            ]);
            $dcci_user_info->dcci_response_json = json_encode($decoded_response['data']);
            $dcci_user_info->save();
        }


        return $response;
    }


    public function waitForPayment($applicationId)
    {
        return view("DCCICos::waiting-for-payment", compact('applicationId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);
        $paymentInfoData = RequestQueueDCCICos::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $decodedResponse = json_decode($paymentInfoData->response_json);
        $status = intval($paymentInfoData->status);

        if ($status == 1) {
            $applyPaymentfee = $decodedResponse->data->payload->payment_information->total_payable;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
            $paymentInfo = view(
                "DCCICos::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($paymentInfoData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 0, 'message' => 'Connecting to DCCI Server.']);
        } elseif ($status == 5) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 0, 'message' => 'Application submitted successfully.']);
        } elseif ($status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => -1, 'message' => json_encode($decodedResponse->data->payload->errors)]);
        } elseif ($status == -2) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => $decodedResponse]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function dcciPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = DCCICos::find($appId);
        $queueData = RequestQueueDCCICos::where('ref_id', $appId)->first();
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', $this->process_type_id)
            ->first();

        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [DCCI-1123]");
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


        $dcciPaymentData = DCCIPaymentConfirm::where('ref_id', $appId)->first();
        $amount = json_decode($queueData->response_json);
        $dcciPaymentDataAmount = $amount->data->payload->payment_information->total_payable;
        $dcciPaymentDataAccount = json_decode($dcciPaymentData->app_fee_account_json);
        $appFeeAccount = '';
        foreach ($dcciPaymentDataAccount as $key => $value) {
            if ($key == 0) {
                $appFeeAccount = $value->accountNumber;
            }
        }
        $appFeeAmount = (string)$dcciPaymentDataAmount;

        $appFeePaymentInfo = array(
            'receiver_account_no' => $appFeeAccount,
            'amount' => $appFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $appFeePaymentInfo;

        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);
        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }
        $account_numbers = rtrim($account_no, '-');
        // Get SBL payment configuration
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appInfo->id, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appInfo->id;
        $paymentInfo->process_type_id = $this->process_type_id;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->ref_tran_no = $processData->tracking_no . "-01";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = $request->get('sfp_contact_name');
        $paymentInfo->contact_email = $request->get('sfp_contact_email');
        $paymentInfo->contact_no = $request->get('sfp_contact_phone');
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();
        DCCICos::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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
            $paymentDetails->save();
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

    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        if (empty($payment_id)) {
            Session::flash('error', 'Something went wrong!, payment id not found.');
            return \redirect()->back();
        }
        DB::beginTransaction();
        $payment_id = Encryption::decodeId($payment_id);
        $paymentInfo = SonaliPaymentStackHolders::find($payment_id);
        $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('dcci_cos_apps', 'dcci_cos_apps.id', '=', 'process_list.ref_id')
            ->where('ref_id', $paymentInfo->app_id)
            ->where('process_type_id', $paymentInfo->process_type_id)
            ->first([
                'process_type.name as process_type_name',
                'process_type.process_supper_name',
                'process_type.process_sub_name',
                'process_type.form_id',
                'process_list.*'
            ]);
        $applicantEmailPhone =UtilFunction::geCompanySKHUsersEmailPhone($processData->company_id);

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


            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            foreach ($data2 as $value) {
                $singleResponse = json_decode($value->verification_response);
                    $rData0['total_amount'] = $singleResponse->TranAmount;
                    $rData0['transaction_id'] = $singleResponse->TransactionId;
                    $rData0['currency'] = 'BDT';
                    $rData0['payment_time'] = $singleResponse->TransactionDate;

            }
            $request_data = json_encode($rData0);
            $paymentConfirm = DCCIPaymentConfirm::where('ref_id', $processData->ref_id)->first();
            $paymentConfirm->request_payment = $request_data;
            $paymentConfirm->app_fee_status = 1;
            $paymentConfirm->status = 0;
            $paymentConfirm->save();
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('dcci-cos/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('DCCI COS: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [DCCI COS-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [DCCI COS-1021]');
            return redirect('process/licence-applications/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
            DB::beginTransaction();
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


                DCCICos::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $data1 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                foreach ($data1 as $data2){
                    $totalAmount = 0;
                    $singleResponse = json_decode($data2->verification_response);
                    $totalAmount = $totalAmount + $singleResponse->TranAmount;
                }
                $paymentArray = array(
                    'refno' => $processData->tracking_no,
                    'pstatus' => 'Y',
                    'amt' => (string) $totalAmount
                );
                $PaymentConfirm = new DCCIPaymentConfirm();
                $PaymentConfirm->request = json_encode($paymentArray);
                $PaymentConfirm->ref_id = $paymentInfo->app_id;
                $PaymentConfirm->oss_tracking_no = $processData->tracking_no;
                $PaymentConfirm->save();

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
            return redirect('dcci-cos/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('dcci-cos/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function uploadDocument()
    {
        return View::make('DCCICos::ajaxUploadFile');
    }

    public function getDocList(Request $request)
    {
        $document = Attachment::where('attachment_list.process_type_id', $this->process_type_id)
            ->where('attachment_list.status', 1)
            ->where('attachment_list.is_archive', 0)
            ->orderBy('attachment_list.order')
            ->get(['attachment_list.*']);

        $clrDocuments = [];
        $clrOtherDocuments = [];
        if ($request->has('app_id') && $request->get('app_id') != '') {
            $clr_document = DynamicAttachmentBccCDA::where('process_type_id', $this->process_type_id)->where('ref_id', Encryption::decodeId($request->get('app_id')))->get();
            foreach ($clr_document as $documents) {
                $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->id;
                $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
            }
        }
        $html = strval(view("DCCICos::documents", compact('document', 'clrDocuments', 'clrOtherDocuments')));
        return response()->json(['html' => $html]);
    }

    public function getToken()
    {
        // Get credentials from database
        $bida_idp_url = Config('stackholder.BIDA_TOKEN_API_URL');
        $bida_client_id = Config('stackholder.BIDA_CLIENT_ID');
        $bida_client_secret = Config('stackholder.BIDA_CLIENT_SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $bida_client_id,
            'client_secret' => $bida_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$bida_idp_url");
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

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }
}
