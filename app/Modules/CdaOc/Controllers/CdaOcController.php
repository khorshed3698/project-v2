<?php

namespace App\Modules\CdaOc\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\CdaOcJob\CdaOcGetPayment;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\CdaOc\Models\CdaOc;
use App\Modules\CdaOc\Models\CdaOcDynamicAttachment;
use App\Modules\CdaOc\Models\CdaOcPaymentConfirmGetPayment;
use App\Modules\CdaOc\Models\CdaOcRequestQueue;
use App\Modules\CdaOc\Models\CdaOcResubmitApp;
use App\Modules\CdaOc\Requests\StorePostRequest;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Mockery\Exception;

class CdaOcController extends Controller
{
    const ACL = 'CdaOc';
    const PROCESS_TYPE = 132;

    public function appForm(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CdaOc-1001]';
        }

        if (!ACL::getAccsessRight(self::ACL, '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [CdaOc-971]</h4>"]);
        }

        try {
            $data = [];
            $data['token'] = $this->getToken();
            $data['agent_id'] = Config('stakeholder.agent_id');
            $data['client_id'] = Config('stakeholder.cda.oc.client');
            $data['service_url'] = Config('stakeholder.cda.oc.service_url');
            $data['city_corporation'] = ['1' => 'চট্টগ্রাম সিটি কর্পোরেশন (চসিক)'];

            $public_html = strval(view("CdaOc::application-form", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);

        } catch (\Exception $e) {
            Log::error('CdaOc : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CdaOc-1064]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CdaOc-1064]');
            return redirect()->back();
        }
    }

    public function appFormEdit($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CdaOc-1002]';
        }
        if (!ACL::getAccsessRight(self::ACL, '-E')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [CdaOc-1003]</h4>"]);
        }
        try {
            $data = [];
            $data['applicationId'] = Encryption::decodeId($appId);
            $data['companyIds'] = CommonFunction::getUserWorkingCompany();

            $data['app_info'] = ProcessList::leftjoin('cda_oc_apps', 'cda_oc_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($data) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw(self::PROCESS_TYPE));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'cda_oc_apps.sf_payment_id')
                ->where('process_list.process_type_id', self::PROCESS_TYPE)
                ->where('process_list.ref_id', $data['applicationId'])
                //->whereIn('process_list.company_id', $data['companyIds'])
                ->first([
                    'cda_oc_apps.*',
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

            $data['city_corporation'] = ['1' => 'চট্টগ্রাম সিটি কর্পোরেশন (চসিক)'];
            $data['client_id'] = Config('stakeholder.cda.oc.client');
            $data['agent_id'] = Config('stakeholder.agent_id');
            $data['app_data'] = json_decode($data['app_info']->appdata);

            if ($data['app_info']->status_id == 1) {
                $app_id = Encryption::encodeId($data['applicationId']);
                $public_html = strval(view("CdaOc::wait-for-payment", compact('app_id')));
                return response()->json(['responseCode' => 1, 'html' => $public_html]);
            }

            if ($data['app_info']->status_id == 1) {
                return redirect('cda-oc/check-submission/' . Encryption::encodeId($data['app_info']->id));
            }

            $data['token'] = $this->getToken();
            $data['service_url'] = Config('stakeholder.cda.oc.service_url');

            $public_html = strval(view("CdaOc::application-form-edit", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('CdaOc : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CdaOc-1004]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CdaOc-1005]');
            return redirect()->back();
        }
    }

    public function appFormView($appId, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way. [CdaOc-1005]';
        }

        if (!ACL::getAccsessRight(self::ACL, '-V-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [CdaOc-1006]</h4>"]);
        }

        try {
            $data = [];
            $data['applicationId'] = Encryption::decodeId($appId);
            $data['companyIds'] = CommonFunction::getUserWorkingCompany();
            $data['document'] = CdaOcDynamicAttachment::where('ref_id', $data['applicationId'])->where('status', 1)->get();
            $data['app_info'] = ProcessList::leftjoin('cda_oc_apps', 'cda_oc_apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($data) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw(self::PROCESS_TYPE));
                })
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'cda_oc_apps.sf_payment_id')
                ->where('process_list.process_type_id', self::PROCESS_TYPE)
                ->where('process_list.ref_id', $data['applicationId'])
                //->whereIn('process_list.company_id', $data['companyIds'])
                ->first([
                    'cda_oc_apps.*',
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

            $data['app_data'] = json_decode($data['app_info']->appdata);

            // Resubmission add form Information
            $shortfallAttachments = "";
            if ($data['app_info']->status_id == 27) {
                $shortfallAttachments = $this->getShortfallAttachments('incoming');
            }

            // Resubmission view form Information
            $data['resubmissionInfo'] = "";
            if ($data['app_info']->status_id == 32) {
                $data['resubmissionInfo'] = CdaOcResubmitApp::where('ref_id', $data['applicationId'])->first();
            }

            $data['spPaymentinformation'] = SonaliPaymentStackHolders::where('app_id', $data['applicationId'])
                ->where('process_type_id', self::PROCESS_TYPE)
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

            $data['token'] = $this->getToken();
            $public_html = strval(view("CdaOc::application-form-view", $data));
            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            Log::error('CdaOc : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CdaOc-10025]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CdaOc-1026]');
            return redirect()->back();
        }
    }

    public function appStore(StorePostRequest $request)
    {
        if (!ACL::getAccsessRight(self::ACL, !empty($request->get('app_id')) ? '-E-' : '-A-')) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 class='text-center text-danger'>You have no access right! Contact with system admin for more information. [CdaOc-1016]</h4>"]);
        }

        try {

            $rules = [];
            $messages = [];
            $rules['BuildingClassId'] = 'required';
            $messages['BuildingClassId.required'] = 'অকুপেন্সির ধরন, বাধ্যতামূলক';

            if (!empty($request->app_id)) {
                $decodedId = Encryption::decodeId($request->get('app_id'));
//                $rules['MobileNo'] = 'required|unique:cda_oc_apps,user_phone,' . $decodedId . ',id,user_email,' . Auth::user()->user_email;
            } else {
//                $rules['MobileNo'] = 'required|unique:cda_oc_apps,user_phone,NULL,id,user_email,' . Auth::user()->user_email;
            }
            if ($request->get('actionBtn') != 'draft') {
                $rules['IsApprovedRA'] = 'required';
                $messages['IsApprovedRA.required'] = 'অনুমোদিত আবাসিক এলাকা কিনা, বাধ্যতামূলক';
                $rules['ApplicationDate'] = 'required';
                $messages['ApplicationDate.required'] = 'আবেদনের তারিখ, বাধ্যতামূলক';
                $rules['PermitNo'] = 'required';
                $messages['PermitNo.required'] = 'নির্মাণ অনুমোদন নাম্বার, বাধ্যতামূলক';
                $rules['ConstructionCompletedDate'] = 'required';
                $messages['ConstructionCompletedDate.required'] = 'কাজ সমাপ্তের তারিখ, বাধ্যতামূলক';
                $rules['CityCorporationId'] = 'required';
                $messages['CityCorporationId.required'] = 'সিটি কর্পোরেশন/পৌরসভা/গ্রাম/মহল্লা, বাধ্যতামূলক';
                $rules['BS'] = 'required';
                $messages['BS.required'] = 'বি. এস. নং, বাধ্যতামূলক';
                $rules['RS'] = 'required';
                $messages['RS.required'] = 'আর. এস. নং, বাধ্যতামূলক';
                $rules['ThanaId'] = 'required';
                $messages['ThanaId.required'] = 'থানার নাম, বাধ্যতামূলক';
                $rules['BlockId'] = 'required';
                $messages['BlockId.required'] = 'ব্লক নং, বাধ্যতামূলক';
                $rules['SeatId'] = 'required';
                $messages['SeatId.required'] = 'সিট নং, বাধ্যতামূলক';
                $rules['WardId'] = 'required';
                $messages['WardId.required'] = 'ওয়ার্ড নং, বাধ্যতামূলক';
                $rules['SectorId'] = 'required';
                $messages['SectorId.required'] = 'সেক্টর নং, বাধ্যতামূলক';
                $rules['RoadName'] = 'required';
                $messages['RoadName.required'] = 'রাস্তার নাম, বাধ্যতামূলক';
                $rules['PlotArea'] = 'required';
                $messages['PlotArea.required'] = 'বাহুর মাপ সহ জমি/প্লটের পরিমাণ, বাধ্যতামূলক';
                $rules['PlotDesc'] = 'required';
                $messages['PlotDesc.required'] = 'জমি/প্লট এ বিদ্যমান বাড়ি/কাঠামোর বিবরণ, বাধ্যতামূলক';
            }

            $this->validate($request, $rules, $messages);

            $company_id = CommonFunction::getUserWorkingCompany();

            DB::beginTransaction();

            if ($request->get('app_id')) {
                $appData = CdaOc::find($decodedId);
                $processData = ProcessList::where(['process_type_id' => self::PROCESS_TYPE, 'ref_id' => $appData->id])->first();
            } else {
                $appData = new CdaOc();
                $processData = new ProcessList();
                $processData->company_id = $company_id;
            }

//            $appData->user_email = Auth::user()->user_email;
//            $appData->user_phone = Auth::user()->user_phone;
            $appData->appdata = json_encode($request->all());
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
            $processData->process_type_id = self::PROCESS_TYPE;
            $processData->process_desc = ''; // for re-submit application
            $processData->company_id = $company_id;
            $processData->submitted_at = Carbon::now()->toDateTimeString();
            $processData->read_status = 0;

            $jsonData['Applied by'] = CommonFunction::getUserFullName();
            $jsonData['Email'] = Auth::user()->user_email;
            $jsonData['Mobile'] = Auth::user()->user_phone;
            $processData['json_object'] = json_encode($jsonData);
            $processData->save();

            // Start file uploading
            $docIds = $request->get('dynamicDocumentsId');
            if (isset($docIds)) {
                foreach ($docIds as $docs) {
                    $docIdName = explode('@', $docs);
                    $doc_id = $docIdName[0];
                    $doc_name = $docIdName[1];

                    $app_doc = CdaOcDynamicAttachment::firstOrNew([
                        'process_type_id' => self::PROCESS_TYPE,
                        'ref_id' => $appData->id,
                        'doc_id' => $doc_id,
                    ]);
                    $app_doc->doc_name = $doc_name;
                    $app_doc->doc_path = $request->get('validate_field_' . $doc_id);
                    $app_doc->save();
                }
            }
            /* End file uploading */

            if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
                if (empty($processData->tracking_no)) {
                    if ($request->get('actionBtn') != "draft" && $processData->status_id != 2 && strlen(trim($processData->tracking_no)) == 0) {
                        $trackingPrefix = 'CDA-OC-' . date("dMY") . '-';
                        $process_type = self::PROCESS_TYPE;
                        DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-3,3) )+1,1),3,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id =$process_type and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");
                    }
                }
            }
            $tracking_no = CommonFunction::getTrackingNoByProcessId($processData->id);
            //$this->submissionJson($appData->id, $tracking_no, $processData->status_id);

            if ($request->get('actionBtn') != "draft") {
                $this->submissionJson($appData->id, $tracking_no, $processData->status_id);
                $CdaOcRequest = CdaOcRequestQueue::firstOrNew([
                    'ref_id' => $appData->id,
                    'type' => 'use_description',
                ]);

                $floorInfo = [];
                if ($CdaOcRequest->status != 1) {
                    foreach ($request->get('FloorTypeId') as $key => $value) {
                        $floorInfo1 = [];
                        $floorInfo1['FloorTypeId'] = !empty($request->get('FloorTypeId')[$key]) ? explode('@', $request->get('FloorTypeId')[$key])[0] : '';
                        $floorInfo1['FloorUseId'] = !empty($request->get('FloorUseId')[$key]) ? explode('@', $request->get('FloorUseId')[$key])[0] : '';
                        $floorInfo1['PartialCompletion'] = !empty($request->get('PartialCompletion')[$key]) ? $request->get('PartialCompletion')[$key] : '';
                        $floorInfo1['FullCompletion'] = !empty($request->get('FullCompletion')[$key]) ? $request->get('FullCompletion')[$key] : '';
                        $floorInfo1['FloorTotalArea'] = !empty($request->get('FloorTotalArea')[$key]) ? $request->get('FloorTotalArea')[$key] : '';
                        $floorInfo1['FloorUse'] = !empty($request->get('FloorUse')[$key]) ? explode('@', $request->get('FloorUse')[$key])[0] : '';

                        $floorInfo['infoBeans'][$key] = $floorInfo1;
                    }
                }
                $CdaOcRequest->type = 'use_description';
                $CdaOcRequest->request_json = json_encode($floorInfo);
                $CdaOcRequest->status = -1;
                $CdaOcRequest->save();
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
                return redirect('cda-oc/list/' . Encryption::encodeId(self::PROCESS_TYPE));
            }
            if ($request->get('actionBtn') != "draft" && $processData->status_id == 2) {
                return redirect('cda-oc/list/' . Encryption::encodeId(self::PROCESS_TYPE));
            }
            $this->dispatch(new CdaOcGetPayment($processData->ref_id));
            return redirect('cda-oc/check-payment/' . Encryption::encodeId($appData->id));
        } catch
        (Exception $e) {
            //dd($e->getMessage(), $e->getFile(), $e->getLine());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CdaOc-0301]');
            return Redirect::back()->withInput();
        }
    }

    // Get CDA OC token for authorization
    public function getToken()
    {
        // Get credentials from database
        $cda_oc_token_api_url = Config('stakeholder.cda.oc.token_url');
        $cda_oc_client_id = Config('stakeholder.cda.oc.client');
        $cda_oc_client_secret = Config('stakeholder.cda.oc.secret');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array(
            'client_id' => $cda_oc_client_id,
            'client_secret' => $cda_oc_client_secret,
            'grant_type' => 'client_credentials'
        )));
        curl_setopt($curl, CURLOPT_URL, "$cda_oc_token_api_url");
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

    public function checkstatus($app_id)
    {
        return view("CdaOc::wait-for-payment", compact('app_id'));
    }

    public function submissionJson($app_id, $tracking_no, $status_id)
    {
        $CdaOcRequest = CdaOcRequestQueue::firstOrNew(['ref_id' => $app_id]);

        //if ($CdaOcRequest->status != 1) {

        $appData = CdaOc::where('id', $app_id)->first();
        $masterData = json_decode($appData->appdata);

        $buildingSubClassList = [];
        foreach ($masterData->buildingSubClassList as $buildingSubClass) {
            !empty($buildingSubClass) ? array_push($buildingSubClassList, (int)explode('@', $buildingSubClass)[0]) : '';
        }


        $paramAppdata['buildingSubClassList'] = $buildingSubClassList;

        $param = [
            "BuildingClassId" => !empty($masterData->BuildingClassId) ? explode('@', $masterData->BuildingClassId)[0] : '',
            "IsApplicationPartial" => "false",
            "IsApprovedRA" => !empty($masterData->IsApprovedRA) ? ($masterData->IsApprovedRA == 1 ? 'true' : 'false') : '',
            "ApplicationDate" => !empty($masterData->ApplicationDate) ? (date('m-d-Y', strtotime($masterData->ApplicationDate))) : '',
            "PermitNo" => !empty($masterData->PermitNo) ? ($masterData->PermitNo) : '',
            "ConstructionCompletedDate" => !empty($masterData->ConstructionCompletedDate) ? (date('m-d-Y', strtotime($masterData->ConstructionCompletedDate))) : '',
            "CityCorporationId" => !empty($masterData->CityCorporationId) ? ($masterData->CityCorporationId) : '',
            "BS" => !empty($masterData->BS) ? ($masterData->BS) : '',
            "RS" => !empty($masterData->RS) ? ($masterData->RS) : '',
            "ThanaId" => !empty($masterData->ThanaId) ? explode('@', $masterData->ThanaId)[0] : '',
            "MoujaId" => !empty($masterData->MoujaId) ? explode('@', $masterData->MoujaId)[0] : '',
            "BlockId" => !empty($masterData->BlockId) ? explode('@', $masterData->BlockId)[0] : '',
            "SeatId" => !empty($masterData->SeatId) ? explode('@', $masterData->SeatId)[0] : '',
            "WardId" => !empty($masterData->WardId) ? explode('@', $masterData->WardId)[0] : '',
            "SectorId" => !empty($masterData->SectorId) ? explode('@', $masterData->SectorId)[0] : '',
            "RoadName" => !empty($masterData->RoadName) ? ($masterData->RoadName) : '',
            "PlotArea" => !empty($masterData->PlotArea) ? ($masterData->PlotArea) : '',
            "PlotDesc" => !empty($masterData->PlotDesc) ? ($masterData->PlotDesc) : '',
            "UserName" => !empty($masterData->UserName) ? ($masterData->UserName) : '',
            "MobileNo" => !empty($masterData->MobileNo) ? ($masterData->MobileNo) : '',
            "Email" => !empty($masterData->Email) ? ($masterData->Email) : '',
            "Address" => !empty($masterData->Address) ? ($masterData->Address) : '',
            "BidaTrackingNo" => !empty($tracking_no) ? ($tracking_no) : ''
        ];

        $file = '';
        if ($masterData->Signature) {
            $image_parts = explode(";base64,", $masterData->Signature);
            $signature = [
                "Signature" => $image_parts[1],
                //"Signature" => !empty($file) ? $file . '.' . $image_type : ''
            ];


//            $image_type_aux = explode("image/", $image_parts[0]);
//            $image_type = $image_type_aux[1];
//            $image_base64 = base64_decode($image_parts[1]);
////            dd($masterData->Signature, $image_parts, $image_parts[1]);
//            $yFolder = "uploads/" . date("Y");
//            if (!file_exists($yFolder)) {
//                mkdir($yFolder, 0777, true);
//                $myfile = fopen($yFolder . "/index.html", "w");
//                fclose($myfile);
//            }
//            $ym = date("Y") . "/" . date("m") . "/";
//            $ym1 = "uploads/" . date("Y") . "/" . date("m");
//            if (!file_exists($ym1)) {
//                mkdir($ym1, 0777, true);
//                $myfile = fopen($ym1 . "/index.html", "w");
//                fclose($myfile);
//            }
//            $path = "uploads/";
//            $file = $ym. uniqid();
//            file_put_contents($path.$file. '.' . $image_type, $image_base64);
        }


        $paramAppdata += $param;
        $paramAppdata += $signature;

        $CdaOcRequest->ref_id = $appData->id;
        $CdaOcRequest->type = 'submission';

        $CdaOcRequest->status = -1;   // 0 = payment not submitted
        $CdaOcRequest->request_json = json_encode($paramAppdata);
        $CdaOcRequest->save();
        DB::commit();

    }

    public function getRefreshToken()
    {
        $token = $this->getToken();
        return response($token);
    }

    public function waitForPayment($applicationId)
    {
        return view("CdaOc::wait-for-payment", compact('applicationId'));
    }

    public function checkPayment(Request $request)
    {
        $application_id = Encryption::decodeId($request->enc_app_id);

        $paymentInfoData = CdaOcPaymentConfirmGetPayment::where(['ref_id' => $application_id])->orderBy('id', 'desc')->first();
        $decodedResponse = json_decode($paymentInfoData->response_get_payment);
        $status = intval($paymentInfoData->status);
        if ($status == 0) {
            $applyPaymentfeeWithVat = $decodedResponse->data->TotalFee;
            $ServicepaymentData = ApiStackholderMapping:: where(['process_type_id' => self::PROCESS_TYPE])->first(['amount']);
            $paymentInfo = view(
                "CdaOc::paymentInfo",
                compact('applyPaymentfeeWithVat', 'ServicepaymentData'))->render();
        }
        if ($paymentInfoData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => -1, 'message' => 'Connecting to CDA server.']);
        } elseif ($status == -3) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == -2) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => $status, 'message' => $decodedResponse]);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($paymentInfoData->id), 'status' => 0, 'message' => 'Your Request has been successfully verified', 'paymentInformation' => $paymentInfo]);
        }
    }

    public function cdaPayment(Request $request)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        $appId = Encryption::decodeId($request->get('enc_app_id'));
        $appInfo = CdaOc::find($appId);
        $processData = ProcessList::where('ref_id', $appId)
            ->where('process_type_id', self::PROCESS_TYPE)
            ->first();

        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => self::PROCESS_TYPE,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);
        if (!$payment_config) {
            Session::flash('error', "Payment configuration not found [CDA-OC-1123]");
            return redirect()->back()->withInput();
        }
        $stackholderMappingInfo = ApiStackholderMapping::where('stackholder_id', $payment_config->stackholder_id)
            ->where('is_active', 1)
            ->where('process_type_id', self::PROCESS_TYPE)
            ->get([
                'receiver_account_no',
                'amount',
                'distribution_type'
            ])->toArray();


        $cdaOcPaymentData = CdaOcPaymentConfirmGetPayment::where('ref_id', $appId)->first();
        $cdaOcDecodedPaymentData = json_decode($cdaOcPaymentData->response_get_payment);
        $appFeeAccount = '';
        $appFeeAmount = '';
        if (!empty($cdaOcDecodedPaymentData)) {
            $appFeeAccount = $cdaOcDecodedPaymentData->data->AccountNo;
            $appFeeAmount = (string)$cdaOcDecodedPaymentData->data->TotalFee;
        }
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
        $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appInfo->id, 'process_type_id' => self::PROCESS_TYPE, 'payment_config_id' => $payment_config->id]);
        $paymentInfo->payment_config_id = $payment_config->id;
        $paymentInfo->app_id = $appInfo->id;
        $paymentInfo->process_type_id = self::PROCESS_TYPE;
        $paymentInfo->app_tracking_no = '';
        $paymentInfo->receiver_ac_no = $account_numbers;
        $paymentInfo->payment_category_id = $payment_config->payment_category_id;
        $paymentInfo->ref_tran_no = $processData->tracking_no . "-03";
        $paymentInfo->pay_mode = 'Online';
        $paymentInfo->pay_amount = $pay_amount;
        $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
        $paymentInfo->contact_name = $request->get('sfp_contact_name');
        $paymentInfo->contact_email = $request->get('sfp_contact_email');
        $paymentInfo->contact_no = $request->get('sfp_contact_phone');
        $paymentInfo->address = $request->get('sfp_contact_address');
        $paymentInfo->sl_no = 1; // Always 1
        $paymentInsert = $paymentInfo->save();
        CdaOc::where('id', $appInfo->id)->update(['sf_payment_id' => $paymentInfo->id]);
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

    }

    public function afterPayment($payment_id)
    {
        $stackholderDistibutionType = Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
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
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            $processData->save();


            $data2 = StackholderSonaliPaymentDetails::where('payment_id', $payment_id)->where('distribution_type', $stackholderDistibutionType)->get();
            foreach ($data2 as $value) {
                $singleResponse = json_decode($value->verification_response);
                if (!empty($singleResponse)) {
                    $rData0['Form401Id'] = '';
                    $rData0['Amount'] = !empty($singleResponse->TranAmount) ? $singleResponse->TranAmount : '';
                    $rData0['PaymentDate'] = !empty($singleResponse->TransactionDate) ? date("m.d.Y", strtotime($singleResponse->TransactionDate)) : '';
                    $rData0['InvoiceNo'] = !empty($paymentInfo->ref_tran_no) ? $processData->tracking_no : '';
                    $rData0['PaymentMode'] = !empty($paymentInfo->pay_mode) ? $processData->pay_mode : '';
                    $rData0['TrackingNo'] = !empty($processData->tracking_no) ? $processData->tracking_no : '';
                }
            }

            $paymentConfirm = CdaOcPaymentConfirmGetPayment::where('ref_id', $processData->ref_id)->first();
            $paymentConfirm->request = !empty($request_data) ? $request_data : '';
            $paymentConfirm->status = 1;
            $paymentConfirm->save();

            CdaOcRequestQueue::where('ref_id', $processData->ref_id)
                ->where('type', 'submission')
                ->update(['status' => 0]);
            DB::commit();
            Session::flash('success', 'Payment submitted successfully');
            return redirect('cda-oc/list/' . Encryption::encodeId(self::PROCESS_TYPE));
        } catch (\Exception $e) {
            DB::rollback();
            \Illuminate\Support\Facades\Log::error('CDA OC: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [CDA OC-1021]');
            Session::flash('error',
                'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage() . ' [CDA OC-1021]');
            return redirect('process/licence-applications/' . $redirect_path['edit'] . '/' . Encryption::encodeId($processData->ref_id) . '/' . Encryption::encodeId($processData->process_type_id));
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
                $processData->read_status = 0;

                $general_submission_process_data = CommonFunction::getGeneralSubmission(self::PROCESS_TYPE);

                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];
                $processData->process_desc = 'Counter Payment Confirm';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);

                $data1 = StackholderSonaliPaymentDetails::where('payment_id', $payment_id)->where('distribution_type', $stackholderDistibutionType)->get();
                foreach ($data1 as $data2) {
                    $totalAmount = 0;
                    $singleResponse = json_decode($data2->verification_response);
                    $totalAmount = $totalAmount + $singleResponse->TranAmount;
                }

                $paymentArray = array(
                    'Form401Id' => '',
                    'Amount' => !empty($totalAmount) ? (string)$totalAmount : '',
                    'PaymentDate' => !empty($singleResponse->TransactionDate) ? date("m.d.Y", strtotime($singleResponse->TransactionDate)) : '',
                    'InvoiceNo' => !empty($paymentInfo->ref_tran_no) ? $paymentInfo->ref_tran_no : '',
                    'PaymentMode' => !empty($paymentInfo->pay_mode) ? $processData->pay_mode : '',
                    'TrackingNo' => !empty($processData->tracking_no) ? $processData->tracking_no : ''
                );


                $PaymentConfirm = CdaOcPaymentConfirmGetPayment::firstOrNew(['ref_id' => $paymentInfo->app_id]);
                $PaymentConfirm->request = json_encode($paymentArray);
                $PaymentConfirm->status = 1;
                $PaymentConfirm->save();

                CdaOcRequestQueue::where('ref_id', $processData->ref_id)
                    ->where('type', 'submission')
                    ->update(['status' => 0]);

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
            CdaOcRequestQueue::where('ref_id', $processData->ref_id)
                ->where('type', 'submission')
                ->update(['status' => 0]);
            DB::commit();
            return redirect('cda-oc/list/' . Encryption::encodeId(self::PROCESS_TYPE));
        } catch (\Exception $e) {
            DB::rollback();
            //dd($e->getLine() . $e->getMessage());
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('cda-oc/list/' . Encryption::encodeId(self::PROCESS_TYPE));
        }
    }

    public function getDynamicDoc(Request $request)
    {
        $cda_oc_service_url = Config('stakeholder.cda.oc.service_url');
        $app_id = $request->get('appId');

        // Get token for API authorization
        $token = $this->getToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $cda_oc_service_url . "/info/attachment-list",
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
                "agent-id: 2",
                "Authorization: Bearer  $token",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
//        dd($response);
        curl_close($curl);

        $decoded_response = json_decode($response, true);

        $html = '';
        if ($decoded_response['responseCode'] == 200) {
            if ($decoded_response['data'] != '') {
                $attachment_list = $decoded_response['data'];

                $clr_document = CdaOcDynamicAttachment::where('process_type_id', self::PROCESS_TYPE)->where('ref_id', $app_id)->get();
                $clrDocuments = [];
                foreach ($clr_document as $documents) {
                    $clrDocuments[$documents->doc_id]['document_id'] = $documents->doc_id;
                    $clrDocuments[$documents->doc_id]['file'] = $documents->doc_path;
                    $clrDocuments[$documents->doc_id]['document_name_en'] = $documents->doc_name;
                }
                $html = view("CdaOc::dynamic-document", compact('attachment_list', 'clrDocuments', 'app_id')
                )->render();
            }
        }
        return response()->json(['responseCode' => 1, 'data' => $html]);
    }

    public function uploadDocument()
    {
        return View::make('CdaOc::ajaxUploadFile');
    }
}