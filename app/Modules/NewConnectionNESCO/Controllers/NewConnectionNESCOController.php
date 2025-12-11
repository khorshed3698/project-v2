<?php

namespace App\Modules\NewConnectionNESCO\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\NewConnectionNESCO\Models\NescoDemandPaymentInfo;
use App\Modules\NewConnectionNESCO\Models\NESCOPaymentConfirm;
use App\Modules\NewConnectionNESCO\Models\NESCOPaymentInfo;
use App\Modules\NewConnectionNESCO\Models\DynamicAttachmentNESCO;
use App\Modules\NewConnectionNESCO\Models\NewConnectionNESCO;
use App\Modules\NewConnectionNESCO\Models\RequestQueueNESCO;
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


class NewConnectionNESCOController extends Controller
{

    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        $this->process_type_id = 116;
        $this->aclName = 'NewConnectionNESCO';
    }

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [NewConectionNESCO-1001]';
        }

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionNESCO-971]</h4>"]);
        }
        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();
            $document = Attachment::where('process_type_id', $this->process_type_id)->orderBy('order')->get();
            $process_type_id = $this->process_type_id;
            $token = $this->getToken();
            $nesco_service_url = Config('stackholder.NESCO_SERVICE_API_URL');
            $viewMode = 'off';
            $mode = '-A-';
            $public_html = strval(view("NewConnectionNESCO::application-form", compact('process_type_id', 'viewMode', 'mode', 'document', 'token', 'nesco_service_url')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('AppForm : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [ETC-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [ETC-1064]');
            return redirect()->back();
        }
    }

    public function appStore(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: #ff0000;margin-top: 250px;text-align: center;'>You have no access right! Contact with system admin for more information. [NewConectionNESCO-970]</h4>"]);
        }
        $company_id = Auth::user()->company_ids;
        try {
            DB::beginTransaction();
            $companyId = CommonFunction::getUserSubTypeWithZero();
            $data = $request->all();
            if ($request->get('app_id')) {
                $decodedId = Encryption::decodeId($data['app_id']);
                $appData = NewConnectionNESCO::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new NewConnectionNESCO();
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
                    $app_doc = DynamicAttachmentNESCO::firstOrNew([
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


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    $processTypeId = $this->process_type_id;
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'NESCO-' . date("dMY") . '-';
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
                $this->SubmmisionjSon($appData->id, $tracking_no, $processData->status_id, $request->ip());
            }


            if ($request->get('actionBtn') != "draft" && $processData->status_id != 5) {

                $paymentInfo = NESCOPaymentInfo::firstOrNew(['ref_id' => $appData->id]);
                $paymentInfo->ref_id = $appData->id;
                $paymentInfo->tracking_no = $processData->tracking_no;
                $paymentInfo->app_fee_status = 10; // application not yet submitted
                $paymentInfo->app_account_status = 10; //application not yet submitted
                $paymentInfo->save();
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
                return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
            }
            return redirect('new-connection-nesco/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [SPE0301]');
            return Redirect::back()->withInput();
        }
    }

//    Store End
    public function waitfordemandpayment($applicationId)
    {
        $app_id = Encryption::decodeId($applicationId);
        return view("NewConnectionNESCO::waiting-for-payment-demand", compact('applicationId', 'paymentId'));
    }


    public function applicationViewEdit($appId, $openMode = '', Request $request)
    {
//        if (!$request->ajax()) {
//            return 'Sorry! this is a request without proper way. [NESCO-1002]';
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
            $appInfo = ProcessList::leftJoin('nesco_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
            $alreadySubmitted = RequestQueueNESCO::where('type', 'Submission')
                ->Where('ref_id', $decodedAppId)->first();
            if ($alreadySubmitted) {
                if ($alreadySubmitted->status == 1 && $appInfo->status_id != 5) {
                    $applicationId = $appId;
                    $public_html = strval(view("NewConnectionNESCO::waiting-for-payment-whout-sidebar", compact('applicationId', 'paymentId')));
                    return response()->json(['responseCode' => 1, 'html' => $public_html]);
                } else if ($alreadySubmitted->status == -1 && $appInfo->status_id != 5) {
                    $decodedResponse = json_decode($alreadySubmitted->response_json);
                    Session::flash('error', $decodedResponse->data->message);
                }
            }


            $appData = json_decode($appInfo->appdata);
            $company_id = $appInfo->company_id;

            $department_id = CommonFunction::getDeptIdByCompanyId($appInfo->company_id);

            $token = $this->getToken();
            $nesco_service_url = Config('stackholder.NESCO_SERVICE_API_URL');
            $public_html = strval(view("NewConnectionNESCO::application-form-edit", compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'document', 'token', 'nesco_service_url', 'descriptionLoad')));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('NESCOViewEditForm: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [NESCO-1015]');
            return response()->json([
                'responseCode' => 1,
                'html' => "<h4 class='custom-err-msg'>" . CommonFunction::showErrorPublic($e->getMessage()) . "[NESCO-1015]" . "</h4>"
            ]);
        }
    }

//     End Application Edit

    public function waitForPayment($applicationId)
    {

        return view("NewConnectionNESCO::waiting-for-payment", compact('applicationId'));
    }

    public function checkPayment(Request $request)
    {

        $application_id = Encryption::decodeId($request->enc_app_id);

        $nescoPaymentInfo = NESCOPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => $this->process_type_id])->first(['amount']);
        $app_fee_status = intval($nescoPaymentInfo->app_fee_status);

        $queueData = RequestQueueNESCO::where('ref_id', $application_id)->first();
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
        if ($status == 1) {
            $paymentData = json_decode($nescoPaymentInfo->app_fee_json);
            $applyPaymentfee = $paymentData->data->data[0]->total_payable_account_fee;
            $paymentInfo = view(
                "NewConnectionNESCO::paymentInfo",
                compact('applyPaymentfee', 'ServicepaymentData'))->render();
        }
        if ($nescoPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($nescoPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to Nesco server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($nescoPaymentInfo->id), 'status' => -1, 'message' => 'Waiting for response from Nesco']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($nescoPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == -4 || $status == -5) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($nescoPaymentInfo->id), 'status' => $status, 'message' => 'Failed to submit Application']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($nescoPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function nescoPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = NewConnectionNESCO::find($appId);
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

        $nescoPayment = NESCOPaymentInfo::where('ref_id', $appId)->first();
        $nescoPaymentdata = json_decode($nescoPayment->app_fee_json);
        $appFeeAccount = $nescoPaymentdata->data->data[0]->account_no_for_application_fee;
        $appFeeAmount = $nescoPaymentdata->data->data[0]->application_fee;
        $vatFeeAmount = $nescoPaymentdata->data->data[0]->vat;
        $vatFeeAccount = $nescoPaymentdata->data->data[0]->account_no_for_vat;


      $appFeePaymentInfo = array(
            'receiver_account_no' => $appFeeAccount,
            'amount' => $appFeeAmount,
          'distribution_type' => $stackholderDistibutionType,
        );
        $stackholderMappingInfo[] = $appFeePaymentInfo;

        $vatFeePaymentInfo = array(
            'receiver_account_no' => $vatFeeAccount,
            'amount' => $vatFeeAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $vatFeePaymentInfo;


        $stackholderMappingInfo = array_reverse($stackholderMappingInfo);
        $pay_amount = 0;
        $account_no = "";
        $distribution_type = "";

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
        NewConnectionNESCO::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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

        ///////////////////// stockholder Payment End//////////////////////////
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

            if ($paymentInfo->payment_category_id == 3) { //govt fee
                $processData->status_id = 1;
                $processData->desk_id = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                NewConnectionNESCO::where('id', $processData->ref_id)->update(['is_submit' => 1]);
                //form 1 and from 2 json generate
//                $this->DOERequestToJson($processData->ref_id);
            } else if ($paymentInfo->payment_category_id == 2) {
                NewConnectionNESCO::where('id', $processData->ref_id)->update(['demand_submit' => 1]);
            }
            $processData->save();
            $appData = NewConnectionNESCO::where('id', $processData->ref_id)->first();
            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                $nescoPaymentdata = NESCOPaymentInfo::where('ref_id', $processData->ref_id)->first();
                $nesco_tracking_no = $nescoPaymentdata->tracking_no;
                $nescoPaymentdata = json_decode($nescoPaymentdata->app_fee_json);
                $nescoPaymentdata = $nescoPaymentdata->data;
                $appFeeAccount = $nescoPaymentdata->data[0]->account_no_for_application_fee;
                $vatFeeAccount = $nescoPaymentdata->data[0]->account_no_for_vat;
                $rData0['ossAgentName'] = "BIDA";
                foreach ($data2 as  $value) {
                    $singleResponse = json_decode($value->verification_response,true);

                        if ($singleResponse['TranAccount'] == $appFeeAccount) {
                            $rData0['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "general"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $vatFeeAccount) {
                            $rData0['payment'][] =  array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "vat"
                            ],$singleResponse);
                        }

                }
            } else if ($paymentInfo->payment_category_id == 2) { // type 2 for demand fee payment
                $demandInfo = NescoDemandPaymentInfo::where('ref_id', $appData->id)->first(['response']);

                $demandFeeResponse = json_decode($demandInfo->response);
                $demandFeeResponse = $demandFeeResponse->data;

                $accountVat = $demandFeeResponse->data->account_no_for_vat;
                $accountDeposit = $demandFeeResponse->data->account_no_for_deposit;
                $estimationAccount = $demandFeeResponse->data->account_no_for_estimation;
                $rData1['ossAgentName'] = "BIDA";
                foreach ($data2 as $value) {
                    $singleResponse = json_decode($value->verification_response,true);
                        if ($singleResponse['TranAccount'] == $estimationAccount) {
                            $rData1['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' =>$appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "general"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $accountVat) {
                            $rData1['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "vat"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $accountDeposit) {
                            $rData0 = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no
                            ],$singleResponse);
                        }


                }
            }


            $nescoPaymentConfirm = new NESCOPaymentConfirm();
            if ($paymentInfo->payment_category_id == 2) {
                $nescoPaymentConfirm->is_demand = 1;
            }
            $nescoPaymentConfirm->request = json_encode($rData0);
            $nescoPaymentConfirm->ref_id = $paymentInfo->app_id;
            $nescoPaymentConfirm->tracking_no = $processData->tracking_no;
//            dd($doePaymentConfirm);
            $nescoPaymentConfirm->save();
            if ($paymentInfo->payment_category_id == 2) {
                $itemPaymentConfirm = new NESCOPaymentConfirm();
                $itemPaymentConfirm->request = json_encode($rData1);
                $itemPaymentConfirm->ref_id = $paymentInfo->app_id;
                $itemPaymentConfirm->tracking_no = $processData->tracking_no;
                $itemPaymentConfirm->is_demand = 2;
                $itemPaymentConfirm->save();
            }

            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
        } catch
        (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
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
                $appData = NewConnectionNESCO::where('id', $processData->ref_id)->first();
                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
                if ($paymentInfo->payment_category_id == 3) {  //type 3 for application feee
                    $nescoPaymentdata = NESCOPaymentInfo::where('ref_id', $processData->ref_id)->first();
                    $nesco_tracking_no = $nescoPaymentdata->tracking_no;
                    $nescoPaymentdata = json_decode($nescoPaymentdata->app_fee_json);
                    $nescoPaymentdata = $nescoPaymentdata->data;
                    $appFeeAccount = $nescoPaymentdata->data[0]->account_no_for_application_fee;
                    $vatFeeAccount = $nescoPaymentdata->data[0]->account_no_for_vat;
                    $rData0['ossAgentName'] = "BIDA";
                    foreach ($data2 as  $value) {
                        $singleResponse = json_decode($value->verification_response,true);

                        if ($singleResponse['TranAccount'] == $appFeeAccount) {
                            $rData0['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "general"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $vatFeeAccount) {
                            $rData0['payment'][] =  array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "vat"
                            ],$singleResponse);
                        }

                    }
                } else if ($paymentInfo->payment_category_id == 2) { // type 2 for demand fee payment
                    $demandInfo = NescoDemandPaymentInfo::where('ref_id', $appData->id)->first(['response']);

                    $demandFeeResponse = json_decode($demandInfo->response);
                    $demandFeeResponse = $demandFeeResponse->data;

                    $accountVat = $demandFeeResponse->data->account_no_for_vat;
                    $accountDeposit = $demandFeeResponse->data->account_no_for_deposit;
                    $estimationAccount = $demandFeeResponse->data->account_no_for_estimation;
                    $rData1['ossAgentName'] = "BIDA";
                    foreach ($data2 as $value) {
                        $singleResponse = json_decode($value->verification_response,true);
                        if ($singleResponse['TranAccount'] == $estimationAccount) {
                            $rData1['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' =>$appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "general"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $accountVat) {
                            $rData1['payment'][] = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no,
                                'transaction_type' => "vat"
                            ],$singleResponse);
                        } elseif ($singleResponse['TranAccount'] == $accountDeposit) {
                            $rData0 = array_merge([
                                'ossAgentName' => "BIDA",
                                'tracking_no' => $appData->nesco_tracking_no,
                                'ossTrackingNo' => $processData->tracking_no
                            ],$singleResponse);
                        }


                    }
                }


                $nescoPaymentConfirm = new NESCOPaymentConfirm();
                if ($paymentInfo->payment_category_id == 2) {
                    $nescoPaymentConfirm->is_demand = 1;
                }
                $nescoPaymentConfirm->request = json_encode($rData0);
                $nescoPaymentConfirm->ref_id = $paymentInfo->app_id;
                $nescoPaymentConfirm->tracking_no = $processData->tracking_no;
//            dd($doePaymentConfirm);
                $nescoPaymentConfirm->save();
                if ($paymentInfo->payment_category_id == 2) {
                    $itemPaymentConfirm = new NESCOPaymentConfirm();
                    $itemPaymentConfirm->request = json_encode($rData1);
                    $itemPaymentConfirm->ref_id = $paymentInfo->app_id;
                    $itemPaymentConfirm->tracking_no = $processData->tracking_no;
                    $itemPaymentConfirm->is_demand = 2;
                    $itemPaymentConfirm->save();
                }

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user

                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('new-connection-nesco/list/' . Encryption::encodeId($this->process_type_id));
        }
    }

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
                'html' => "<h4 class='custom-err-msg'>You have no access right! Contact with system admin for more information. [BRC-974]</h4>"
            ]);
        }

        $decodedAppId = Encryption::decodeId($appId);
        //dd($decodedAppId);
        $process_type_id = $this->process_type_id;
        //$companyIds = CommonFunction::getUserCompanyWithZero();

        // get application,process info

        $appInfo = ProcessList::leftJoin('nesco_apps as apps', 'apps.id', '=', 'process_list.ref_id')
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
//        $shortfallarr = [];
//
//        if ($appInfo->status_id === 50) {
//            $shortFallData = DpdcDocumentShortfall::where('ref_id', $appInfo->id)->where('status', 1)->first();
//            if ($shortFallData) {
//                $response = json_decode($shortFallData->response);
//                $shortfallarr = $response->result;
//            }
//        }
        $appData = json_decode($appInfo->appdata);
        //            dd($appData);
//        $dynamic_shortfall = DynamicShortfallAttachmentDPDC::where('ref_id', $appInfo->ref_id)->get();
        // dd($dynamic_shortfall);
        $company_id = $appInfo->company_id;

//        $demand_view = 0;
//
//        if ($appInfo->demand_status != 0) {
//            $demand_view = 1;
//        }
//
//
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
        $nesco_service_url = Config('stackholder.NESCO_SERVICE_API_URL');

        $public_html = strval(view(
            "NewConnectionNESCO::application-form-view",
            compact('appInfo', 'appData', 'process_type_id', 'viewMode', 'mode', 'token', 'nesco_service_url', 'spPaymentinformation')
        ));
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
            NewConnectionNESCO::where('id', $request->get('ref_id'))->update(['is_submit_shortfall' => 1]);
        } /* End file uploading */
        // dd($request->all());
        return redirect()->back();
    }

// Get RJSC token for authorization
    public function getToken()
    {
        // Get credentials from database
        $nesco_idp_url = Config('stackholder.NESCO_TOKEN_API_URL');
        $nesco_client_id = Config('stackholder.NESCO_SERVICE_CLIENT_ID');
        $nesco_client_secret = Config('stackholder.NESCO_SERVICE_CLIENT_SECRET');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $nesco_client_id,
            'client_secret' => $nesco_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$nesco_idp_url");
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
        $nesco_service_url = Config('stackholder.NESCO_SERVICE_API_URL');
        $app_id = $request->appId;
        $conn_id = $request->conn_type_id;
        $phase_id = $request->phase_id;
        $tariff_id = $request->tariff_id;
        // Get token for API authorization
        $token = $this->getToken();
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $nesco_service_url . "/document-inputs",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"ossAgentName\":\"BIDA\", \"connection_type_id\" : \"$conn_id\",\"connection_phase_id\" : \"$phase_id\",\"tariff_group_id\":\"$tariff_id\"}",
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
            $attachment_list = '';
            if (isset($decoded_response['data']['data']['documentInputs'])) {
                $attachment_list = $decoded_response['data']['data']['documentInputs'];

                $clr_document = DynamicAttachmentNESCO::where('process_type_id', $this->process_type_id)->where('ref_id', $app_id)->get();

                $clrDocuments = [];

                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['doucument_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['doc_name'] = $documents->doc_name;
                }
                //dd($clrDocuments);


            }
            $html = view(
                "NewConnectionNESCO::dynamic-document",
                compact('attachment_list', 'clrDocuments', 'app_id')
            )->render();
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
        return View::make('NewConnectionNESCO::ajaxUploadFile');
    }


    public function SubmmisionjSon($app_id, $tracking_no, $statusid, $ip_address)
    {
        // Submission Request Data

        if ($statusid == 2) {
            $nescoRequest = new RequestQueueNESCO();
        } else {
            $nescoRequest = RequestQueueNESCO::firstOrNew([
                'ref_id' => $app_id
            ]);
        }

        $appData = NewConnectionNESCO::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);
//dd($masterData);
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            $link = "https";
        else
            $link = "http";

        $link .= "://";
        $hosturl = $link . $_SERVER['HTTP_HOST'] . '/uploads/';
        $hiddenDocumentIDs = [];
        $customerExtraFiles = [];

        if (!empty($masterData->dynamicDocumentsId)) {
            $dynamicDocument = $masterData->dynamicDocumentsId;
            foreach ($dynamicDocument as $value) {
                $id = explode('@', $value)[0];
                $filedname = 'validate_field_' . $id;
                $path = $hosturl . $masterData->$filedname;
                $hiddenDocumentIDs[] = array('hiddenDocumentID' => $id);
                $customerExtraFiles[] = array('customer_extra_files' => $path);
            }
        }


        $param = [
            'ossTrackingNo' => $tracking_no,
            'ossAgentName' => 'BIDA',
            'customer_mobile' => !empty($masterData->mobile_no) ? $masterData->mobile_no : '',
            'name' => !empty($masterData->applicant_name) ? $masterData->applicant_name : '',
            'father_name' => !empty($masterData->father_name_or_organization) ? $masterData->father_name_or_organization : '',
            'mother_name' => !empty($masterData->applicant_mother_name) ? $masterData->applicant_mother_name : '',
            'spouse_name' => !empty($masterData->applicant_husband_or_wife_name) ? $masterData->applicant_husband_or_wife_name : '',
            'dob' => !empty($masterData->applicant_dob) ? Carbon::parse($masterData->applicant_dob)->format('d/m/Y') : '',
            'gender' => !empty($masterData->applicant_gender) ? explode('@', $masterData->applicant_gender)[0] : '',
            'post_office' => !empty($masterData->applicant_post_office) ? $masterData->applicant_post_office : '',
            'district_id' => !empty($masterData->applicant_district) ? explode('@', $masterData->applicant_district)[0] : '',
            'nid' => !empty($masterData->applicant_nid_no) ? $masterData->applicant_nid_no : '',
            'tin' => !empty($masterData->applicant_tin) ? $masterData->applicant_tin : '',
            'customer_address1' => !empty($masterData->address_line_1) ? $masterData->address_line_1 : '',
            'customer_address2' => !empty($masterData->address_line_2) ? $masterData->address_line_2 : '',
            'customer_email' => !empty($masterData->email) ? $masterData->email : '',
            'connection_house_no' => !empty($masterData->house_or_dag_no) ? $masterData->house_or_dag_no : '',
            'connection_plot_no' => !empty($masterData->plot_no) ? $masterData->plot_no : '',
            'connection_road_no' => !empty($masterData->av_lane_road_no) ? $masterData->av_lane_road_no : '',
            'connection_block_no' => !empty($masterData->block) ? $masterData->block : '',
            'connection_district_id' => !empty($masterData->district) ? explode('@', $masterData->district)[0] : '',
            'connection_upazilla_id' => !empty($masterData->thana) ? explode('@', $masterData->thana)[0] : '',
            'connection_section' => !empty($masterData->section) ? $masterData->section : '',
            'connection_division_id' => !empty($masterData->division) ? explode('@', $masterData->division)[0] : '',
//                'connection_division_id' =>!empty($masterData->district)?explode('@',$masterData->district)[1]:'',
            'connection_type_id' => !empty($masterData->connection_type) ? explode('@', $masterData->connection_type)[0] : '',
            'existing_account_no' => !empty($masterData->existing_account_no) ? $masterData->existing_account_no : '',
            'connection_load' => !empty($masterData->load) ? $masterData->load : '',
            'connection_phase_id' => !empty($masterData->phase) ? explode('@', $masterData->phase)[0] : '',
            'tariff_group_id' => !empty($masterData->tariff) ? explode('@', $masterData->tariff)[0] : '',
            'loadNo' => !empty($masterData->meter) ? $masterData->meter : '',
            'customer_photo_file' => !empty($masterData->validate_field_photo) ? $hosturl . $masterData->validate_field_photo : '',
            'customer_signature_file' => !empty($masterData->validate_field_signature) ? $hosturl . $masterData->validate_field_signature : '',
            'customer_nid_file' => !empty($masterData->validate_field_nid) ? $hosturl . $masterData->validate_field_nid : '',
            'customer_land_karij_file' => !empty($masterData->validate_field_land) ? $hosturl . $masterData->validate_field_land : '',
            'hiddenDocumentIDs' => $hiddenDocumentIDs,
            'customerExtraFiles' => $customerExtraFiles,
            "oss" => "bida",
            "post_code" => !empty($masterData->applicant_post_code) ? $masterData->applicant_post_code : '',
        ];

        if ($statusid == 2) {
            $param['tracking_no'] = $appData->nesco_tracking_no;
            $param['applicationDocumentIds'] = [];
        }


        $nescoRequest->ref_id = $appData->id;
        if ($statusid == 2) {
            $nescoRequest->type = 'Resubmission';
        } else {
            $nescoRequest->type = 'Submission';
        }

        $nescoRequest->status = 0;   // 10 = payment not submitted
        $nescoRequest->request_json = json_encode($param);
        //dd($dpdcRequest->request_json);
        $nescoRequest->save();
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
        $appInfo = NewConnectionNESCO::find($appId);

        if (!$appInfo) {
            Session::flash('error', "Application not found [dpdc-1101]");
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

        $DemandPaymentInfo = NescoDemandPaymentInfo::where('ref_id', $appId)->first();

        if (!$DemandPaymentInfo) {
            Session::flash('error', "Payment response not found [dpdc-2222]");
            return redirect()->back()->withInput();
        }
        $paymentResponse = json_decode($DemandPaymentInfo->response);

//
        if ($paymentResponse->data == null) {
            Session::flash('error', "Payment data not found not found [dpdc-1101]");
            return redirect()->to('/dashboard');
        }
        $paymentResponse = $paymentResponse->data;

        $accountVat = $paymentResponse->data->account_no_for_vat;
        $accountDeposit = $paymentResponse->data->account_no_for_deposit;
        $amountDeposit = $paymentResponse->data->deposit_amount;
        $estimationAccount = $paymentResponse->data->account_no_for_estimation;
        $estimationAmount = $paymentResponse->data->estimation_amount;
        $vatAmount = ($paymentResponse->data->vat_for_deposit_amount + $paymentResponse->data->vat_for_estimation_amount);


        $paymentdataDeposit = array(
            'receiver_account_no' => $accountDeposit,
            'amount' => $amountDeposit,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $paymentdataDeposit;

        $paymentdataEstimate = array(
            'receiver_account_no' => $estimationAccount,
            'amount' => $estimationAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $paymentdataEstimate;

        $paymentdataEstimate = array(
            'receiver_account_no' => $accountVat,
            'amount' => $vatAmount,
            'distribution_type' => $stackholderDistibutionType,
        );

        $stackholderMappingInfo[] = $paymentdataEstimate;


        $pay_amount = 0;
        $account_no = "";
        foreach ($stackholderMappingInfo as $data) {
            $pay_amount += $data['amount'];
            $account_no .= $data['receiver_account_no'] . "-";
        }

        $account_numbers = rtrim($account_no, '-');
//        dd($account_numbers);

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

        $dpdcPaymentInfo = NescoDemandPaymentInfo::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();

        $status = intval($dpdcPaymentInfo->status);

        if ($dpdcPaymentInfo == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 0, 'message' => 'Connecting to BPDB server.']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Waiting for response from DOE']);
        } elseif ($status == -2 || $status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($dpdcPaymentInfo->id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }
    }

    public
    function dpdcRequestToJsonResubmit($request, $appDataId, $ossTrackingNo, $dpdc_traking_num)
    {
        // Re-Submission Request Data

        $dpdcResubmissionRequest = new ResubmissionRequestQueuedpdc();

        $reSubmissionData = [];
        $reSubmissionData['clientId'] = $this->getClientId();
        $reSubmissionData['ossTrackingNo'] = $ossTrackingNo;
        $reSubmissionData['ossAgentName'] = config('stackholder.oss_agent_name');
        $reSubmissionData['personalInfo']['trakingNum'] = $dpdc_traking_num;
        $reSubmissionData['personalInfo']['applicationType'] = $request->application_type;
        $reSubmissionData['personalInfo']['organizationName'] = $request->organization_name;
        $reSubmissionData['personalInfo']['ApplicantConName'] = $request->connection_name;
        $reSubmissionData['personalInfo']['nameEn'] = $request->applicant_name_english;
        $reSubmissionData['personalInfo']['nameBn'] = $request->applicant_name_bangla;
        $reSubmissionData['personalInfo']['designation'] = $request->authorized_person_designation;
        $reSubmissionData['personalInfo']['ApplicantFName'] = $request->father_name;
        $reSubmissionData['personalInfo']['ApplicantMName'] = $request->mother_name;
        $reSubmissionData['personalInfo']['ApplicantSpouse'] = $request->applicant_spouse_name;
        $reSubmissionData['personalInfo']['mobile'] = $request->applicant_mobile_no;
        $reSubmissionData['personalInfo']['nid'] = $request->nation_id;
        $reSubmissionData['personalInfo']['passport'] = $request->applicant_passport_no;
        $reSubmissionData['personalInfo']['gender'] = $request->sex;
        $reSubmissionData['personalInfo']['dob'] = date('d/m/Y', strtotime($request->date_of_birth));


        if (isset($request->validate_field_signature) && !empty($request->validate_field_signature)) {
            $signatureFilePath = env('PROJECT_ROOT') . '/uploads/' . $request->validate_field_signature;
            // $signatureData = file_get_contents($signatureFilePath);
            // $signatureBase64 = base64_encode($signatureData);
            $reSubmissionData['personalInfo']['signature'] = $signatureFilePath;
            $path_info = pathinfo($signatureFilePath);
            $reSubmissionData['personalInfo']['signType'] = $path_info['extension'];
        }


        if (isset($request->validate_field_photo) && !empty($request->validate_field_photo)) {
            $photoFilePath = env('PROJECT_ROOT') . '/uploads/' . $request->validate_field_photo;
            // $photoData = file_get_contents($photoFilePath);
            // $photoBase64 = base64_encode($photoData);
            $reSubmissionData['personalInfo']['photo'] = $photoFilePath;
            $path_info = pathinfo($photoFilePath);
            $reSubmissionData['personalInfo']['photoType'] = $path_info['extension'];
        }


        $reSubmissionData['mailingAddress']['plot'] = $request->house_no;
        $reSubmissionData['mailingAddress']['section'] = $request->union;
        $district = explode("@", $request->district);
        $reSubmissionData['mailingAddress']['district'] = $district[0];
        $thana = explode("@", $request->thana);
        $reSubmissionData['mailingAddress']['thana'] = $thana[0];
        $reSubmissionData['mailingAddress']['road'] = $request->lane_no;
        $reSubmissionData['mailingAddress']['block'] = $request->block;
        $reSubmissionData['mailingAddress']['postcode'] = $request->post_code;
        $reSubmissionData['mailingAddress']['email'] = $request->email;

        $reSubmissionData['connectionAddress']['dagNo'] = $thana[0];
        $reSubmissionData['connectionAddress']['laneNo'] = $request->connection_lane_no;
        $reSubmissionData['connectionAddress']['section'] = $request->connection_union;
        $reSubmissionData['connectionAddress']['block'] = $request->connection_block;
        $reSubmissionData['connectionAddress']['postCode'] = $request->connection_post_code;
        $dpdczone = explode("@", $request->dpdc_zone);
        $reSubmissionData['connectionAddress']['dpdcZone'] = $dpdczone[0];
        $esu = explode("@", $request->esu);
        $connection_area = explode("@", $request->connection_area);
        $reSubmissionData['connectionAddress']['esu'] = $connection_area[2];
        $reSubmissionData['connectionAddress']['connectionArea'] = $connection_area[0];
        $reSubmissionData['connectionAddress']['conMobile'] = $request->connection_mobile_no;
        $connectionDistrict = explode("@", $request->connection_district);
        $reSubmissionData['connectionAddress']['district'] = $connectionDistrict[0];
        $connectionThana = explode("@", $request->connection_thana);
        $reSubmissionData['connectionAddress']['thana'] = $connectionThana[0];


        $reSubmissionData['permanentAddress']['permHouseNo'] = $request->permanet_house_no;
        $reSubmissionData['permanentAddress']['permRoad'] = $request->lane_no;
        $reSubmissionData['permanentAddress']['permSection'] = $request->permanet_union;
        $reSubmissionData['permanentAddress']['permBlock'] = $request->permanet_block;
        $permanetDistrict = explode("@", $request->permanet_district);
        $reSubmissionData['permanentAddress']['permDistrict'] = $permanetDistrict[0];
        $permanetThana = explode("@", $request->permanet_thana);
        $reSubmissionData['permanentAddress']['permThana'] = $permanetThana[0];
        $reSubmissionData['permanentAddress']['permPost'] = $request->permanet_post_code;
        $reSubmissionData['permanentAddress']['permEmail'] = $request->permanet_email;


        // multiple Data
        if (isset($request->description_of_load) && !empty($request->description_of_load)) {
            foreach ($request->description_of_load as $key => $value) {
                $description = explode("@", $request->description_of_load[$key]);
                $reSubmissionData['connectionDetails']['connections'][$key]['loadItemCode'] = $description[0];
                $reSubmissionData['connectionDetails']['connections'][$key]['loadPerItemInWatt'] = $request->load_per_item[$key];
                $reSubmissionData['connectionDetails']['connections'][$key]['noOfItem'] = $request->no_of_item[$key];
                $reSubmissionData['connectionDetails']['connections'][$key]['totalLoadInWatt'] = $request->total_load[$key];
            }
        }
        //doen

        $reSubmissionData['connectionDetails']['conType']['connectionType'] = $request->connectionType;
        $category = explode("@", $request->category);
        $reSubmissionData['connectionDetails']['conType']['category'] = $category[0];
        $phase = explode("@", $request->phase);
        $reSubmissionData['connectionDetails']['conType']['phase'] = $phase[0];
        $reSubmissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';
        $reSubmissionData['connectionDetails']['conType']['demandLoadPerMeterInKilowatt'] = '';


        $attachments = DynamicAttachmentdpdc::where('process_type_id', $this->process_type_id)->where('ref_id', $appDataId)->get();

        if (count($attachments) > 0) {
            foreach ($attachments as $key => $attachment) {
                $attachmentFilePath = env('PROJECT_ROOT') . '/uploads/' . $attachment->doc_path;
                // $attachmentData = file_get_contents($attachmentFilePath);
                // $attachmentBase64 = base64_encode($attachmentData);
                $reSubmissionData['connectionDetails']['attachments'][$key]['stringDoc'] = $attachmentFilePath;
                $path_info = pathinfo($attachmentFilePath);
                $reSubmissionData['connectionDetails']['attachments'][$key]['docType'] = $path_info['extension'];
                $reSubmissionData['connectionDetails']['attachments'][$key]['fileName'] = $attachment->doc_name;
                $reSubmissionData['connectionDetails']['attachments'][$key]['docCode'] = $attachment->doc_id;
                $reSubmissionData['connectionDetails']['attachments'][$key]['conTypeCode'] = $request->connectionType;
                $reSubmissionData['connectionDetails']['attachments'][$key]['tarrif'] = $category[0];
                $reSubmissionData['connectionDetails']['attachments'][$key]['phaseTypeCode'] = $phase[0];
            }
        }
        $dpdcResubmissionRequest->ref_id = $appDataId;
        $dpdcResubmissionRequest->type = 'ReSubmission';
        $dpdcResubmissionRequest->status = 0;
        $dpdcResubmissionRequest->request = json_encode($reSubmissionData);
        //dd($dpdcRequest->request_json);
        $dpdcResubmissionRequest->save();


        // Submission Request Data ends
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
        return view("NewConnectionNESCO::view-demand", compact('appInfo'));
    }

}
