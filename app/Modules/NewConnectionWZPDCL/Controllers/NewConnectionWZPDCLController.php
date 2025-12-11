<?php

namespace App\Modules\NewConnectionWZPDCL\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\BasicInformation\Models\BasicInformation;

use App\Modules\NewConnectionWZPDCL\Models\DynamicAttachmentWZPDCL;
use App\Modules\NewConnectionWZPDCL\Models\NewConnectionWZPDCL;
use App\Modules\NewConnectionWZPDCL\Models\RequestQueueWZPDCL;
use App\Modules\NewConnectionWZPDCL\Models\WzpdclDemandPaymentInfo;
use App\Modules\NewConnectionWZPDCL\Models\WzpdclPaymentConfirm;
use App\Modules\NewConnectionWZPDCL\Models\WZPDCLPaymentInfo;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;


class NewConnectionWZPDCLController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 120;
        $this->aclName = 'NewConnectionWZPDCL';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionWZPDCL-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConnectionWZPDCL-971]</h4>"]);
        }

        try {

            $company_id = Auth::user()->company_ids;
            $ceoInfo = BasicInformation::where('company_id', $company_id)->first();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $wzpdcl_service_url = Config('stackholder.WZPDCL_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionWZPDCL::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'wzpdcl_service_url', 'ceoInfo')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WZPDCL-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [WZPDCL-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConnectionWZPDCL-971]</h4>"]);
        }

        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionWZPDCL::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionWZPDCL();
                $processData = new ProcessList();
                $processData->company_id = $companyId;
            }

            $data = json_encode($request->all());
            $appData->appdata = $data;
            $appData->save();
            // dd($appData);

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
                    $app_doc = DynamicAttachmentWZPDCL::firstOrNew([
                        'process_type_id' => $this->process_type_id,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id
                    ]);
                    $app_doc->status = 0;
                    $app_doc->doc_id = $doc_id;
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }
// /* End file uploading */


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {

                    $processTypeId = $this->process_type_id;

                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'WZPDCL-' . date("dMY") . '-';
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
                $this->submissionJson($appData->id, $tracking_no, $processData->status_id, $request->ip());
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) {
                $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                    ->where([
                        'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                        'api_stackholder_payment_configuration.payment_category_id' => 3,
                        'api_stackholder_payment_configuration.status' => 1,
                        'api_stackholder_payment_configuration.is_archive' => 0,
                    ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

                if (!$payment_config) {
                    Session::flash('error', "Payment configuration not found [WZPDCL-1123]");
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


                $stackholderMappingInfo = array_reverse($stackholderMappingInfo);
                $pay_amount = 0;
                $account_no = "";
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
                $paymentInfo->contact_name = CommonFunction::getUserFullName();
                $paymentInfo->contact_email = Auth::user()->user_email;
                $paymentInfo->contact_no = Auth::user()->user_phone;
                $paymentInfo->address = Auth::user()->road_no;
                $paymentInfo->sl_no = 1; // Always 1
                $paymentInsert = $paymentInfo->save();
                NewConnectionWZPDCL::where('id', $appData->id)->update(['sf_payment_id' => $paymentInfo->id]);
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
                if ($request->get('actionBtn') != "draft" && $paymentInsert) {
                    return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));
                }

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
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [WZPDCL-1023]');
            }

            if ($request->get('actionBtn') == "draft") {
                return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
            }

        } catch
        (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [wzpdcl0301]');
            return Redirect::back()->withInput();
        }
    }

//    Store End
    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        return view("NewConnectionWZPDCL::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [WZPDCL-1002]';
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

        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information [wzpdcl-973]</h4>"
            ]);
        }

        try {

            $process_type_id = $this->process_type_id;
            // get application,process info
            $appInfo = ProcessList::leftJoin('wzpdcl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
//            $alreadySubmitted = RequestQueueWZPDCL::where('type', 'Submission')
//                ->Where('ref_id', $decodedAppId)->first();
//            if ($alreadySubmitted) {
//                if ($alreadySubmitted->status == 1 && $appInfo->status_id != 5) {
//                    $applicationId = $appId;
//                    $public_html = strval(view("NewConnectionWZPDCL::waiting-for-payment-whout-sidebar", compact('applicationId', 'paymentId')));
//                    return response()->json(['responseCode' => 1, 'html' => $public_html]);
//                } else if ($alreadySubmitted->status == -1 && $appInfo->status_id != 5) {
//                    $decodedResponse = json_decode($alreadySubmitted->response_json);
//                    Session::flash('error', $decodedResponse->data->message);
//                }
//            }


            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;


            $token = $this->getToken();
            $wzpdcl_service_url = Config('stackholder.WZPDCL_SERVICE_API_URL');
            $public_html = strval(view("NewConnectionWZPDCL::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'token', 'wzpdcl_service_url', 'descriptionLoad')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('WZPDCLViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WZPDCL-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[WZPDCL-1015]" . "</h4>"
            ]);
        }
    }

//     End Application Edit

    public function waitForPayment($applicationId)
    {
        return view("NewConnectionWZPDCL::waiting-for-payment", compact('applicationId', 'paymentId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $wzpdclPaymentInfo = WZPDCLPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $app_fee_status = intval($wzpdclPaymentInfo->app_fee_status);


        $queueData = RequestQueueWZPDCL::where('ref_id', $application_id)->first();
        $app_status = $queueData->status;
        if ($app_status == -1) {
            $app_fee_status = -4;
        } elseif ($app_status == -2 || $app_status == -3) {
            $app_fee_status = -5;
        }

        if ($app_fee_status == 1) {
            $status = 1;
        } elseif ($app_fee_status == 0) {
            $status = 0;
        } elseif ($app_fee_status == -1) {
            $status = -1;
        } elseif ($app_fee_status == 10) {
            $status = 0;
        } elseif ($app_fee_status == -4) {
            $status = -4;
        } elseif ($app_fee_status == -5) {
            $status = -5;
        } else {
            $status = -3;
        }

        if ($wzpdclPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($wzpdclPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to Wzpdcl server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($wzpdclPaymentInfo->id), 'status' => -1, 'message' => 'Waiting for response from Wzpdcl']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($wzpdclPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == -4 || $status == -5) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($wzpdclPaymentInfo->id), 'status' => $status, 'message' => 'Failed to submit Application']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($wzpdclPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }
    }


    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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

            if ($paymentInfo->payment_category_id == 3) { //govt fee
                $processData->status_id = 1;
                $processData->desk_id = 0;
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                NewConnectionWZPDCL::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                RequestQueueWZPDCL::where('ref_id', $processData->ref_id)->update(['status' => 0]);
                //form 1 and from 2 json generate
//                $this->DOERequestToJson($processData->ref_id);
            } else if ($paymentInfo->payment_category_id == 2) {
                NewConnectionWZPDCL::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
            }

            $processData->save();
            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            $appData = NewConnectionWZPDCL::where('id', $processData->ref_id)->first();

            if ($paymentInfo->payment_category_id == 2) {  //type 3 for application feee
                $wzpdclDemandPaymentdata = WzpdclDemandPaymentInfo::where('ref_id', $processData->ref_id)->first();

                $demandFeedata = json_decode($wzpdclDemandPaymentdata->response);

                foreach ($data2 as $value) {
                    $singleResponse = json_decode($value->verification_response);
                    $rData0['ossAgentName'] = "BIDA";
                        $rData0['trackingNumber'] = $wzpdclDemandPaymentdata->wzpdcl_tracking_no;
                        $rData0['appSerialNumber'] = "1";
                        $rData0['amount'] = $singleResponse->TranAmount;
                        $rData0['cardType'] = "10";
                        $rData0['paymentFor'] = $singleResponse->TranAmount == $demandFeedata->data->data[0]->DemandCostTotal ? "2" : "3";
                        $rData0['fee'] = "0";
                        $rData0['result'] = "ok";
                        $rData0['txNid'] = $singleResponse->TransactionId;
                        $rData0['tranDate'] = Carbon::parse($singleResponse->TransactionDate)->format('d/m/Y');
                        $rData0['description'] = "332211";
                        $rData0['accountNumber'] = $singleResponse->TranAccount;
                        if ($paymentInfo->payment_category_id == 2) {
                            $demandFeeConfirm = new WzpdclPaymentConfirm();
                            $demandFeeConfirm->request = json_encode($rData0);
                            $demandFeeConfirm->wzpdcl_request = json_encode($rData0);
                            $demandFeeConfirm->ref_id = $paymentInfo->app_id;
                            $demandFeeConfirm->oss_tracking_no = $processData->tracking_no;
                            $demandFeeConfirm->is_demand = 1;
                            $demandFeeConfirm->save();
                        }


                }
            }

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong! application not updated after payment.');
            return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function afterCounterPayment($payment_id)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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


            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if ($paymentInfo->is_verified == 1) {
                if ($paymentInfo->payment_category_id == 3) { // for applicaiton submission
                    $processData->status_id = 1;
                    $processData->desk_id = 0;
                    $processData->read_status = 0;

                    $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

                    NewConnectionWZPDCL::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                    //form 1 and from 2 json generate
                    //                    $this->DOERequestToJson($processData->ref_id);

                } elseif ($paymentInfo->payment_category_id == 2) { //demand fee
                    NewConnectionWZPDCL::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
                }
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

//                $verification_response = json_decode($paymentInfo->offline_verify_response);

                //dd($data2);
                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                if ($paymentInfo->payment_category_id == 2) {  //type 3 for application feee
                    $wzpdclDemandPaymentdata = WzpdclDemandPaymentInfo::where('ref_id', $processData->ref_id)->first();

                    $demandFeedata = json_decode($wzpdclDemandPaymentdata->response);

                    foreach ($data2 as $value) {
                        $singleResponse = json_decode($value->verification_response);
                        $rData0['ossAgentName'] = "BIDA";
                        $rData0['trackingNumber'] = $wzpdclDemandPaymentdata->wzpdcl_tracking_no;
                        $rData0['appSerialNumber'] = "1";
                        $rData0['amount'] = $singleResponse->TranAmount;
                        $rData0['cardType'] = "10";
                        $rData0['paymentFor'] = $singleResponse->TranAmount == $demandFeedata->data->data[0]->DemandCostTotal ? "2" : "3";
                        $rData0['fee'] = "0";
                        $rData0['result'] = "ok";
                        $rData0['txNid'] = $singleResponse->TransactionId;
                        $rData0['tranDate'] = Carbon::parse($singleResponse->TransactionDate)->format('d/m/Y');
                        $rData0['description'] = "332211";
                        $rData0['accountNumber'] = $singleResponse->TranAccount;
                        if ($paymentInfo->payment_category_id == 2) {
                            $demandFeeConfirm = new WzpdclPaymentConfirm();
                            $demandFeeConfirm->request = json_encode($rData0);
                            $demandFeeConfirm->wzpdcl_request = json_encode($rData0);
                            $demandFeeConfirm->ref_id = $paymentInfo->app_id;
                            $demandFeeConfirm->oss_tracking_no = $processData->tracking_no;
                            $demandFeeConfirm->is_demand = 1;
                            $demandFeeConfirm->save();
                        }


                    }
                }


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
            return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getLine() . $e->getMessage());
            DB::rollback();

            Session::flash('error', 'Something went wrong!Application not updated after payment. Error : ' . $e->getMessage());
            return redirect('new-connection-wzpdcl/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

    public function applicationView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [WZPDCL-1003]';
        }

        $viewMode = 'on';
        $mode = '-V-';

        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [WZPDCL-974]</h4>"
            ]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        $process_type_id = $this->process_type_id;

        $appInfo = ProcessList::leftJoin('wzpdcl_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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


        $demandInfo = WzpdclDemandPaymentInfo::where('bank_info_status', 1)
            ->where('status', 1)
            ->where('ref_id', $decodedAppId)->first();

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


        $token = $this->getToken();
        $wzpdcl_service_url = Config('stackholder.WZPDCL_SERVICE_API_URL');

        $public_html = strval(view("NewConnectionWZPDCL::application-form-view", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'wzpdcl_service_url', 'spPaymentinformation', 'demandInfo')));

        return response()->json(['responseCode' => 1, 'html' => $public_html]);

    }

    public function shortfallDoc(Request $request)
    {
        //dd($request->all());
        $docIds = $request->get('dynamicDocumentsId');
        $remarks = $request->get('remarks');

        $submission_url = "https://onlineapplication.dpdc.org.bd/api/rest/sfall_doc_upload.php";
        if (isset($docIds)) {
            foreach ($docIds as $key => $docs) {
                $docIdName = explode('@', $docs);
                $doc_id = $docIdName[0];
                $doc_name = $docIdName[1];
                $app_doc = DynamicShortfallAttachmentDPDC::firstOrNew([
                    'process_type_id' => $this->process_type_id,
                    'ref_id' => $request->get('ref_id'),
                    'doc_id' => $doc_id
                ]);
                $app_doc->remarks = $remarks[$key];
                $app_doc->submission_url = $submission_url;
                $app_doc->doc_id = $doc_id;
                $app_doc->doc_name = $doc_name;
                $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                $app_doc->response = "";
                $app_doc->request_info = "";
                $app_doc->status = 0;
                $app_doc->save();
            }
            NewConnectionWZPDCL::where('id', $request->get('ref_id'))->update(['is_submit_shortfall' => 1]);
        } /* End file uploading */
        // dd($request->all());
        return redirect()->back();
    }

// Get RJSC token for authorization
    public function getToken()
    {
        // Get credentials from database
        $wzpdcl_idp_url = Config('stackholder.WZPDCL_TOKEN_API_URL');
        $wzpdcl_client_id = Config('stackholder.WZPDCL_SERVICE_CLIENT_ID');
        $wzpdcl_client_secret = Config('stackholder.WZPDCL_SERVICE_CLIENT_SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $wzpdcl_client_id,
            'client_secret' => $wzpdcl_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$wzpdcl_idp_url");
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


    public function getDynamicDoc(Request $request)
    {
        $wzpdcl_service_url = Config('stackholder.WZPDCL_SERVICE_API_URL');
        $app_id = $request->appId;
        $conn_divison_id = $request->conn_divison_id;
        $connection_type_id = $request->connection_type_id;
        $phase_id = $request->phase_id;
        $category_id = $request->category_id;
        $post_data = "{\"ossAgentName\":\"BIDA\", \"trackingNumber\":\"1\", \"appSerialNumber\":\"1\", \"connTypeId\" : \"$connection_type_id\",\"connPhaseId\" : \"$phase_id\",\"connCategoryId\":\"$category_id\",\"divisionId\":\"$conn_divison_id\"}";
        // Get token for API authorization
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $wzpdcl_service_url . "/documentToBeUploaded",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
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

                $clr_document = DynamicAttachmentWZPDCL::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();

                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
                //dd($clrDocuments);

                $html = view(
                    "NewConnectionWZPDCL::dynamic-document",
                    compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }


    public function deleteDynamicDoc(Request $request)
    {
        $process_type_id = $request->process_type_id;
        $ref_id = $request->ref_id;
        $doc_id = $request->doc_id;
        $res = DynamicAttachmentdpdc::where('doc_id', $doc_id)->where('ref_id', $ref_id)->where('process_type_id', $process_type_id)->delete();
        if ($res) {
            return response()->json(['responseCode' => 1, 'message' => 'Deleted']);
        };
        return response()->json(['responseCode' => 0, 'message' => 'Not Deleted']);
    }

    public function uploadDocument()
    {
        return View::make('NewConnectionWZPDCL::ajaxUploadFile');
    }


    public function submissionJson($app_id, $tracking_no, $statusid, $ip_address)
    {
        // Submission Request Data

        if ($statusid == 2) {
            $wzpdclRequest = new RequestQueueWZPDCL();
        } else {
            $wzpdclRequest = RequestQueueWZPDCL::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        $appData = NewConnectionWZPDCL::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);
//dd($masterData);
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";

        $link .= "://";
        $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';

        $param = [
            'ossTrackingNo' => $tracking_no,
            'ossAgentName' => 'BIDA',
            "wzpdclTrackingNum" => $statusid == 2 ? $appData->wzpdcl_tracking_no : '',
            'name' => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
            'nameBn' => !empty($masterData->name_in_bengali) ? $masterData->name_in_bengali : '',
            'mother' => !empty($masterData->mother_name) ? $masterData->mother_name : '',
            'father' => !empty($masterData->father_name) ? $masterData->father_name : '',
            'spouse' => !empty($masterData->spouse_name) ? $masterData->spouse_name : '',
            'nid' => !empty($masterData->national_id) ? $masterData->national_id : '',
            'passport' => !empty($masterData->applicant_passport) ? $masterData->applicant_passport : '',
            'gender' => !empty($masterData->applicant_gender) ? explode('@', $masterData->applicant_gender)[0] : '',
            'deathOfBirth' => !empty($masterData->applicant_dob) ? Carbon::parse($masterData->applicant_dob)->format('d/m/Y') : '',

            'house' => !empty($masterData->mail_house_no) ? $masterData->mail_house_no : '',
            'section' => !empty($masterData->mail_section) ? $masterData->mail_section : '',
            'district' => !empty($masterData->mail_district) ? explode('@', $masterData->mail_district)[0] : '',
            'thana' => !empty($masterData->mail_thana) ? explode('@', $masterData->mail_thana)[0] : '',
            'telephone' => !empty($masterData->mail_telephone) ? $masterData->mail_telephone : '',
            'mobile' => !empty($masterData->applicant_mobile) ? $masterData->applicant_mobile : '',
            'road' => !empty($masterData->mail_road_no) ? $masterData->mail_road_no : '',
            'block' => !empty($masterData->mail_block) ? $masterData->mail_block : '',
            'post' => !empty($masterData->mail_post_code) ? $masterData->mail_post_code : '',
            'email' => !empty($masterData->mailing_email) ? $masterData->mailing_email : '',
            'conHouse' => !empty($masterData->conn_house_no) ? $masterData->conn_house_no : '',
            'conSection' => !empty($masterData->conn_section) ? $masterData->conn_section : '',
            'conDistrict' => !empty($masterData->conn_district) ? explode('@', $masterData->conn_district)[0] : '',
            'conThana' => !empty($masterData->conn_thana) ? explode('@', $masterData->conn_thana)[0] : '',
            'conTelephone' => !empty($masterData->conn_telephone) ? $masterData->conn_telephone : '',
            'conMobile' => !empty($masterData->conn_mobile) ? $masterData->conn_mobile : '',
            'conRoad' => !empty($masterData->conn_road_no) ? $masterData->conn_road_no : '',
            'conBlock' => !empty($masterData->conn_block) ? $masterData->conn_block : '',
            'zone' => !empty($masterData->conn_zone) ? explode('@', $masterData->conn_zone)[0] : '',
            'division' => !empty($masterData->conn_divison) ? explode('@', $masterData->conn_divison)[0] : '',
            'area' => !empty($masterData->conn_area) ? explode('@', $masterData->conn_area)[0] : '',
            'conPost' => !empty($masterData->conn_post_code) ? $masterData->conn_post_code : '',
            'conEmail' => !empty($masterData->conn_email) ? $masterData->conn_email : '',
            'conType' => !empty($masterData->connection_type) ? explode('@', $masterData->connection_type)[0] : '',
            'conCatagory' => !empty($masterData->category) ? explode('@', $masterData->category)[0] : '',
            'conOrganization' => !empty($masterData->org_or_shop_name) ? $masterData->org_or_shop_name : '',
            'conPhase' => !empty($masterData->phase) ? explode('@', $masterData->phase)[0] : '',
            'conNoMeter' => '1',
            'conLoad' => !empty($masterData->demand_load) ? $masterData->demand_load : '',
            'appSerial' => '',
            'photo' => !empty($masterData->validate_field_photo) ? $hosturl . $masterData->validate_field_photo : '',
            'sign' => !empty($masterData->validate_field_signature) ? $hosturl . $masterData->validate_field_signature : '',

        ];


        $wzpdclRequest->ref_id = $appData->id;
        if ($statusid == 2) {
            $wzpdclRequest->type = 'Resubmission';
            $wzpdclRequest->status = 0;
        } else {
            $wzpdclRequest->type = 'Submission';
            $wzpdclRequest->status = 10;   // 10 = payment not submitted
        }
        $wzpdclRequest->request_json = json_encode($param);
        $wzpdclRequest->save();
        // Submission Request Data ends
    }

    public function getClientId()
    {
        $dpdc_api_url = Config('stackholder.DPDC_SERVICE_API_URL');
        $token = $this->getToken();

        $curl = curl_init();
        $user_name = Auth::user()->user_full_name != null && Auth::user()->user_full_name != "" ? Auth::user()->user_full_name : Auth::user()->user_first_name . ' ' . Auth::user()->user_middle_name . ' ' . Auth::user()->user_last_name;

        $request_data = json_encode([
            'userName' => $user_name,
            'userEmail' => Auth::user()->user_email,
            'userPhone' => Auth::user()->user_phone,
            'clientId' => config('stackholder.oss_agent_name')
        ]);
        //oooooo
        curl_setopt_array($curl, array(
            CURLOPT_URL => $dpdc_api_url . "/token?access_token=" . $token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $request_data,
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $decoded_response = json_decode($response, true);
        $result = $decoded_response['data'];

        $clientid = (isset($result['data'][0]['TOKEN']) ? $result['data'][0]['TOKEN'] : '');
        return $clientid;
    }


    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function additionalpayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionWZPDCL::find($appId);

        if (!$appInfo) {
            Session::flash('error', "Application not found [wzpdcl-1101]");
            return redirect()->back()->withInput();
        }
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
//            dd($payment_config);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [DOE-1123]");
            return redirect()->back()->withInput();
        }

        $stackholderMappingInfo = [];

        $DemandPaymentInfo = WzpdclDemandPaymentInfo::where('ref_id', $appId)->first();

        if (!$DemandPaymentInfo) {
            Session::flash('error', "Payment response not found [wzpdcl-2222]");
            return redirect()->back()->withInput();
        }
        $paymentBankInfo = json_decode($DemandPaymentInfo->response_bank_info);
        $paymentFeeInfo = json_decode($DemandPaymentInfo->response);

//

        if ($paymentBankInfo->data == null || $paymentFeeInfo->data == null) {
            Session::flash('error', "Payment data not found not found [wzpdcl-1101]");
            return redirect()->to('/dashboard');
        }

        $accountNo = $paymentBankInfo->data[0]->AccountNo;
        $demandFee = array(
            'receiver_account_no' => $accountNo,
            'amount' => $paymentFeeInfo->data->data[0]->DemandCostTotal,
            'distribution_type' => $stackholderDistibutionType,
        );
        $stackholderMappingInfo[] = $demandFee;
        $estimationFee = array(
            'receiver_account_no' => $accountNo,
            'amount' => $paymentFeeInfo->data->data[0]->EstimateCostTotal,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $estimationFee;


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
        $paymentInfo->ref_tran_no = $processData->tracking_no . "-02";
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = CommonFunction::getUserFullName();
        $paymentInfo->contact_email = Auth::user()->user_email;
        $paymentInfo->contact_no = Auth::user()->user_phone;
        $paymentInfo->address = Auth::user()->road_no;
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();

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
        return redirect('spg/initiate-multiple/stack-holder/' . Encryption::encodeId($paymentInfo->id));

        ///////////////////// stockholder Payment End//////////////////////////
    }

    public function checkDemandPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $dpdcPaymentInfo = WzpdclDemandPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $paymentData = json_decode($dpdcPaymentInfo->response);
        $status = intval($dpdcPaymentInfo->status);
        if ($status == 1) {
            $applyPaymentfee = $paymentData->data->data;
            $ServicepaymentData = ApiStackholderMapping:: where(['stackholder_id' => 10])->first(['amount']);
            $paymentInfo = view(
                "NewConnectionWZPDCL::paymentInfo",
                compact('applyPaymentfee'))->render();
        }

        if ($dpdcPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }


    public
    function demandView($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $appInfo = ProcessList::leftJoin('dpdc_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->where('process_list.ref_id', $app_id)
            ->where('process_list.process_type_id', $this->process_type_id)
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
        return view("NewConnectionWZPDCL::view-demand", compact('appInfo'));
    }

}
