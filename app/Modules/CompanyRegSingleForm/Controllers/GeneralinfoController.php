<?php

namespace App\Modules\CompanyRegSingleForm\Controllers;
use App\Modules\CompanyRegSingleForm\Models\Rjsc_nr_apps_certificate;
use App\Modules\CompanyRegSingleForm\Models\RjscNrPayConfirm;
use App\Modules\CompanyRegSingleForm\Models\RjscNrSubmitForms;
use App\storage;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyRegSingleForm\Models\AoaInfo;
use App\Modules\CompanyRegSingleForm\Models\ListSubscriber;
use App\Modules\CompanyRegSingleForm\Models\CompanyRegSingleForm;
use App\Modules\CompanyRegSingleForm\Models\NrClause;
use App\Modules\CompanyRegSingleForm\Models\Objective;
use App\Modules\CompanyRegSingleForm\Models\Rjsc_NrForms;
use App\Modules\CompanyRegSingleForm\Models\RjscNrQualificShare;
use App\Modules\CompanyRegSingleForm\Models\RjscWitness;
use App\Modules\CompanyRegSingleForm\Models\RjscWitnessFilledBy;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use App\Modules\CompanyRegSingleForm\Models\RjscNrParticularBody;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
//use mPDF;

use Mpdf\Mpdf;

class GeneralinfoController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    protected $rjscBaseUrl;
    protected $clientId;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 134;
        $this->aclName = 'CompanyRegSingleForm';
        $this->rjscBaseUrl = 'https://testapi-k8s.oss.net.bd/api/rjsc-api';
        $this->clientId = 'OSS_BIDA';
        $this->logUrl = '';
    }

    public function appStore(Request $request)
    {


        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;


        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->get('actionBtn') != 'draft') {
            $rules['liability_type_id'] = 'required';
            $rules['address_entity'] = 'required';
            $rules['entity_district_id'] = 'required';
            $rules['entity_email_address'] = 'required';
            $rules['main_business_objective'] = 'required';
            $rules['business_sector_id'] = 'required';
            $rules['business_sub_sector_id'] = 'required';
            $rules['authorize_capital'] = 'required';
            $rules['number_shares'] = 'required';
            $rules['value_of_each_share'] = 'required';
            $rules['minimum_no_of_directors'] = 'required';
            $rules['quorum_agm_egm_num'] = 'required';
            $rules['q_directors_meeting_num'] = 'required';
            $rules['duration_of_chairmanship'] = 'required';
            $rules['duration_managing_directorship'] = 'required';
        }


        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = CompanyRegSingleForm::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new CompanyRegSingleForm();
                $processData = new ProcessList();
            }
            $rjscOffice = explode('@', $request->get('rjsc_office_name'));
            $appData->rjsc_office_id = !empty($rjscOffice[0]) ? $rjscOffice[0] : '';
            $appData->rjsc_office_name = !empty($rjscOffice[1]) ? $rjscOffice[1] : '';

            $entityType = explode('@', $request->get('entity_type'));
            $appData->entity_type_id = !empty($entityType[0]) ? $entityType[0] : '';
            $appData->entity_type_name = !empty($entityType[1]) ? $entityType[1] : '';

            $liabilityTypeId = explode('@', $request->get('liability_type_id'));
            $appData->liability_type_id = !empty($liabilityTypeId[0]) ? $liabilityTypeId[0] : '';
            $appData->liability_type_name = !empty($liabilityTypeId[1]) ? $liabilityTypeId[1] : '';

            if (CommonFunction::asciiCharCheck($request->get('address_entity'))) {
                $appData->address_entity = $request->get('address_entity');
            } else {
                Session::flash('error', 'non-ASCII Characters found in address_entity [GI-1001]');
                return Redirect::to(URL::previous() . "#step1");
            }

            $entityDistId = explode('@', $request->get('entity_district_id'));
            $appData->entity_district_id = !empty($entityDistId[0]) ? $entityDistId[0] : '';
            $appData->entity_district_name = !empty($entityDistId[1]) ? $entityDistId[1] : '';
            $appData->entity_email_address = $request->get('entity_email_address');

            if (CommonFunction::asciiCharCheck($request->get('main_business_objective'))) {
                $appData->main_business_objective = $request->get('main_business_objective');
            } else {

                Session::flash('error', 'non-ASCII Characters found in main_business_objective [GI-1002]');
                return Redirect::to(URL::previous() . "#step1");

            }
            $businessSectId = explode('@', $request->get('business_sector_id'));
            $businessSubSectId = explode('@', $request->get('business_sub_sector_id'));
            $appData->business_sector_id = !empty($businessSectId[0]) ? $businessSectId[0] : '';
            $appData->business_sector_name = !empty($businessSectId[1]) ? $businessSectId[1] : '';
            $appData->business_sub_sector_id = !empty($businessSubSectId[0]) ? $businessSubSectId[0] : '';
            $appData->business_sub_sector_name = !empty($businessSubSectId[1]) ? $businessSubSectId[1] : '';
            $appData->authorize_capital = $request->get('authorize_capital');
            $appData->number_shares = $request->get('number_shares');
            $appData->value_of_each_share = $request->get('value_of_each_share');
            $appData->minimum_no_of_directors = $request->get('minimum_no_of_directors');
            $appData->maximum_no_of_directors = $request->get('maximum_no_of_directors');
            $appData->maximum_no_of_directors = $request->get('maximum_no_of_directors');
            $appData->quorum_agm_egm_num = $request->get('quorum_agm_egm_num');

            if (CommonFunction::asciiCharCheck($request->get('quorum_agm_egm_word'))) {
                $appData->quorum_agm_egm_word = $request->get('quorum_agm_egm_word');
            } else {

                Session::flash('error', 'non-ASCII Characters found in quorum_agm_egm_word [GI-1003]');
                return Redirect::to(URL::previous() . "#step1");

            }

            $appData->q_directors_meeting_num = $request->get('q_directors_meeting_num');

            if (CommonFunction::asciiCharCheck($request->get('q_directors_meeting_word'))) {
                $appData->q_directors_meeting_word = $request->get('q_directors_meeting_word');
            } else {

                Session::flash('error', 'non-ASCII Characters found in q_directors_meeting_word [GI-1004]');
                return Redirect::to(URL::previous() . "#step1");

            }

            $appData->duration_of_chairmanship = $request->get('duration_of_chairmanship');
            $appData->duration_of_chairmanship = $request->get('duration_of_chairmanship');
            $appData->duration_managing_directorship = $request->get('duration_managing_directorship');
            //$appData->sequence = 2;


            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
                $appData->sequence = 1; # If save as draft , data should be current step.
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
                $appData->sequence = 2;
            }

            $appData->save();
            Session::put('sequence', $appData->sequence);
            $deptSubDeptData = [
                'company_id' => $company_id,
                'department_id' => CommonFunction::getDeptIdByCompanyId($company_id)
            ];
            $deptAndSubDept = CommonFunction::DeptSubDeptSpecification($this->process_type_id, $deptSubDeptData);
            $processData->department_id = $deptAndSubDept['department_id'];
            $processData->sub_department_id = $deptAndSubDept['sub_department_id'];
            $processData->ref_id = $appData->id;
            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;
            $processData->read_status = 0;

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $jsonData['Department'] = CommonFunction::getDepartmentNameById($processData->department_id);
            $processData['json_object'] = json_encode($jsonData);


            $processData->save();

            $appData->save();

            if ($processData->status_id == 0) {
                dd('Application status not found!');
            }

            DB::commit();

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }

            if ($request->get('actionBtn') == "draft") {
                return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
            }

            if ($request->get('app_id') && !empty($request->get('app_id'))) {

                return Redirect::to(URL::previous() . "#step2");
            }
            return redirect('licence-applications/company-registration/add#step2');
            // return redirect('company-registration-sf/list/' . Encryption::encodeId($this->process_type_id));

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }


    public function applicationViewEdit($applicationId, $openMode = '', Request $request)
    {
        $mode = 'SecurityBreak';
        $viewMode = 'SecurityBreak';
        if ($openMode == 'view') {
            $viewMode = 'on';
            $mode = '-V-';
        } else if ($openMode == 'edit') {
            $viewMode = 'off';
            $mode = '-E-';
        }


        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }
        // it's enough to check ACL for view mode only
        if (!ACL::getAccsessRight($this->aclName, $mode)) {
            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
        }

        try {

            Session::put('current_app_id', $applicationId);
            $applicationId = Encryption::decodeId($applicationId);
            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('rjsc_cr_sf_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('process_status as ps', function ($join) use ($process_type_id) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($process_type_id));
                })
                ->where('process_list.ref_id', $applicationId)
                ->where('process_list.process_type_id', $process_type_id)
                ->leftJoin('api_stackholder_sp_payment as sfp', 'sfp.id', '=', 'apps.gf_payment_id')
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
                    'sfp.contact_name as sfp_contact_name',
                    'sfp.contact_email as sfp_contact_email',
                    'sfp.contact_no as sfp_contact_phone',
                    'sfp.address as sfp_contact_address',
                    'sfp.pay_amount as sfp_pay_amount',
                    'sfp.vat_on_pay_amount as sfp_vat_on_pay_amount',
                    'sfp.total_amount as sfp_total_amount',
                    'sfp.transaction_charge_amount as sfp_transaction_charge_amount',
                    'sfp.vat_on_transaction_charge as sfp_vat_on_transaction_charge',
                    'sfp.payment_status as sfp_payment_status',
                    'ps.status_name',
                    'apps.*',
                ]);


            $companytype = $appInfo->entity_type_id;
            $particulars = RjscNrParticularBody::where('app_id', $applicationId)->get();

            $subscriber = ListSubscriber::leftjoin('rjsc_company_positions as rjsc_position',
                function ($join) use ($companytype) {
                    $join->on('rjsc_position.rjsc_id', '=', 'rjsc_cr_sf_subscribers_info.position')
                        ->where('rjsc_position.rjsc_company_type_rjsc_id', '=', $companytype);
                })
                ->where('rjsc_cr_sf_subscribers_info.app_id', $applicationId)
                ->orwhere('rjsc_cr_sf_subscribers_info.app_id', $applicationId)
                ->get(
                    ['rjsc_cr_sf_subscribers_info.*', 'rjsc_position.title']
                );

            $objectives = Objective::where('rjsc_nr_app_id', $applicationId)->get();

            $witnessData = RjscWitness::where('rjsc_nr_app_id', $applicationId)->orderBy('witness_flag', 'asc')->get(['name', 'address', 'phone', 'national_id', 'witness_flag'])->toArray();
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name', 'position_id', 'address', 'district_id']);

            $nominationEntity = RjscNrParticularBody::where('app_id', $applicationId)->lists('name_corporation_body', 'id')->all();

            $nrAoaClause = AoaInfo::where('rjsc_nr_app_id', $applicationId)
                ->orderby('sequence', 'asc')
                ->get();

            $authorizeCapitalValidate = CompanyRegSingleForm::leftJoin('rjsc_cr_sf_subscribers_info', 'rjsc_cr_sf_subscribers_info.app_id', '=', 'rjsc_cr_sf_apps.id')
                ->where('rjsc_cr_sf_apps.id', $applicationId)
                ->select(DB::raw('CASE WHEN rjsc_cr_sf_apps.value_of_qualification_share*sum(no_of_subscribed_shares) <= rjsc_cr_sf_apps.authorize_capital THEN 1  ELSE 0 END as capital_validate'))
                ->first(['capital_validate']);
            if($appInfo->company_verification_status == -1){
                Session::put('sequence', 0);
            }else{
                Session::put('sequence', $appInfo->sequence);
            }


            $filestatus = RjscNrSubmitForms::where('app_id', $applicationId)->get(['ref_id', 'file', 'form_name']);
            $uploadedFile = $filestatus->pluck('ref_id')->toArray();

            $entity_type_id = intval($appInfo->entity_type_id);
//            $Rjsc_NrForms = $this->getDynamicDoc();
            $Rjsc_NrForms = Rjsc_NrForms::where('status', '1')->where(function ($query) use ($entity_type_id) {
                if ($entity_type_id == 1) {
                    $query->where('type', '=', 'Private');
                } elseif ($entity_type_id == 2) {
                    $query->where('type', '=', 'Public');
                }
            })->get([
                'id',
                'name',
                'description',
                'is_extra'
            ]);
            $additionalAttachment = RjscNrSubmitForms::where('app_id', $applicationId)->where('is_extra', 1)->get(['file', 'form_name', 'app_id', 'is_extra']);
            $rjsc_nr_certificate = Rjsc_nr_apps_certificate::where('ref_id', $applicationId)
                ->get();
            $payment_response = RjscNrPayConfirm::where('ref_id', $applicationId)
                ->orderby('id', 'desc')
                ->first();
            $rjscBaseApi =  Config('stackholder.RJSC_NC_API_URL');
            $rjscClientId = $this->clientId;
            $logUrl = $this->logUrl;
            $newReg = new NewRegController();
            $token = $newReg->getRjscToken();

            $public_html = strval(view("CompanyRegSingleForm::new-reg-edit.new-reg-application-edit",
                compact('process_type_id', 'appInfo', 'viewMode', 'mode', 'particulars', 'subscriber',
                    'witnessData', 'witnessDataFiled', 'objectives','rjscBaseApi','rjscClientId',
                    'filestatus', 'Rjsc_NrForms', 'applicationId',
                    'additionalAttachment', 'nominationEntity', 'nrAoaClause',
                    'authorizeCapitalValidate', 'rjsc_nr_certificate', 'payment_response','token','logUrl','uploadedFile')));

            return response()->json(['responseCode' => 1, 'html' => $public_html]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 1, 'html' => CommonFunction::showErrorPublic($e->getLine() . $e->getMessage()) . "[PR-1010]"]);
        }
    }

    public function appFormPdf($app_id)
    {

        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {

            $applicationId = Encryption::decodeId($app_id);

            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('rjsc_cr_sf_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('area_info', 'area_info.area_id', '=', 'apps.entity_district_id')
                ->leftJoin('rjsc_company_type as compayType', 'compayType.rjsc_id', '=', 'apps.entity_type_id')
                ->leftJoin('rjsc_nr_liability_types as LiabilityType', 'LiabilityType.liability_types_id', '=', 'apps.liability_type_id')
                ->leftJoin('rjsc_nr_business_sector as busSector', 'busSector.sector_id', '=', 'apps.business_sector_id')
                ->leftJoin('rjsc_nr_bus_sub_sector as busSubSector', 'busSubSector.sub_sector_id', '=', 'apps.business_sub_sector_id')
                ->where('process_list.ref_id', $applicationId)
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
                    'apps.*',
                    'area_info.rjsc_name',
                    'compayType.name',
                    'busSector.name as bus_sec_name',
                    'busSubSector.name as sus_seb_sec_name'
                ]);


            $particulars = RjscNrParticularBody::where('app_id', $applicationId)->get();
            $rjscQualifiShare = RjscNrQualificShare::where('rjsc_nr_app_id', $applicationId)->get();
            $subscriber = ListSubscriber::leftjoin('rjsc_company_positions as rjsc_position', 'rjsc_position.rjsc_id', '=', 'rjsc_cr_sf_subscribers_info.position')->where('rjsc_cr_sf_subscribers_info.app_id', $applicationId)->get(
                ['rjsc_cr_sf_subscribers_info.*', 'rjsc_position.title']
            );
            $objectives = Objective::where('rjsc_nr_app_id', $applicationId)->get();
            $witnessData = RjscWitness::where('rjsc_nr_app_id', $applicationId)->orderBy('witness_flag', 'asc')->get(['name', 'address', 'phone', 'national_id', 'witness_flag'])->toArray();
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name', 'position_id', 'address', 'district_id']);

            $entityType = CommonFunction::getFullEntityType($appInfo->entity_type_id);
            $sub_sectors = ['' => 'Select one'] + RjscSubsector::orderBy('name')->lists('name', 'sub_sector_id')->all();
            $nominationEntity = RjscNrParticularBody::where('app_id', $applicationId)->lists('name_corporation_body', 'id')->all();
            $nrClause = NrClause::where('status', 1)->lists('name', 'clause_id')->all();
            $nrAoaClause = AoaInfo::where('rjsc_nr_app_id', $applicationId)
                ->orderby('sequence', 'asc')
                ->get();
//            dd($nrAoaClause);
            $contents = view("CompanyRegSingleForm::new-reg-edit.new-reg-pdf",
                compact('process_type_id', 'appInfo', 'rjscQualifiShare'
                    , 'particulars', 'subscriber', 'witnessData', 'witnessDataFiled', 'objectives',
                    'entityType', 'sub_sectors', 'nominationEntity', 'nrAoaClause', 'nrClause'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }


    public function appForm_I_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        //return $flag;
        try {

            $applicationId = Encryption::decodeId($app_id);

            $process_type_id = $this->process_type_id;
            $appInfo = ProcessList::leftJoin('rjsc_cr_sf_apps as apps', 'apps.id', '=', 'process_list.ref_id')
                ->leftJoin('area_info as ai', 'ai.rjsc_id', '=', 'apps.declaration_district_id')
                ->leftJoin('rjsc_company_positions as rcp', 'rcp.rjsc_id', '=', 'apps.declaration_position_id')
                ->where('process_list.ref_id', $applicationId)
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
                    'rcp.title as rcptitle',
                    'ai.area_nm',
                    'apps.*'
                ]);
            $contents = view("CompanyRegSingleForm::preview.new-reg-form-i-pdf",
                compact('process_type_id', 'appInfo'))->render();
            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);

            /* *****for pdf downloading and storing in public folder ******
       */
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_i.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }

    }

    public function appForm_VI_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {

            $applicationId = Encryption::decodeId($app_id);

            $process_type_id = $this->process_type_id;
            $appInfo = CompanyRegSingleForm::find($applicationId);
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name']);

            $nocRjscOffice = $appInfo->rjsc_office_name;

            $contents = view("CompanyRegSingleForm::preview.new-reg-form-vi-pdf",
                compact('process_type_id', 'appInfo', 'witnessDataFiled', 'nocRjscOffice'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');

            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_vi.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function appForm_IX_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {

            $applicationId = Encryption::decodeId($app_id);

            $process_type_id = $this->process_type_id;
            $appInfo = CompanyRegSingleForm::find($applicationId);
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name']);
            $directors = ListSubscriber::where('app_id', $applicationId)->where('is_director', 1)->get(['serial_number', 'corporation_body_name', 'usual_residential_address', 'usual_residential_district_id', 'digital_signature', 'subscriber_photo']);


            $contents = view("CompanyRegSingleForm::preview.new-reg-form-ix-pdf",
                compact('process_type_id', 'appInfo', 'witnessDataFiled', 'directors'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            // $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_ix.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function appForm_X_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($app_id);


            $process_type_id = $this->process_type_id;
            $appInfo = CompanyRegSingleForm::find($applicationId);
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name']);

            /*rakibul start*/
            $managingdirector = ListSubscriber::where('app_id', $applicationId)
                ->where('is_director', 1)
                ->where('position', 3)
                ->first(['digital_signature']);

            $signature = "";
            if (count($managingdirector) > 0) {
                $signature = $managingdirector->digital_signature;
            }
            $directors = ListSubscriber::where('app_id', $applicationId)
                ->where('is_director', 1)
                ->get(['serial_number', 'corporation_body_name', 'digital_signature', 'usual_residential_address', 'usual_residential_district_id', 'other_occupation']);


            /*rakibul End*/
            $contents = view("CompanyRegSingleForm::preview.new-reg-form-x-pdf", compact('appInfo', 'witnessDataFiled', 'directors', 'districts', 'signature'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_x.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }

            return Redirect::to(URL::previous() . "#step14");
        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function appForm_moa_Pdf($app_id, $flag = null)
    {

        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $applicationId = Encryption::decodeId($app_id);
        $appInfo = CompanyRegSingleForm::find($applicationId);

        if (count($appInfo) > 0) {
            try {
                $witnessDataFiled = RjscWitness::where('rjsc_nr_app_id', $applicationId)
                    ->orderBy('witness_flag', 'asc')
                    ->get()->toArray();
//                dd($witnessDataFiled);
                /*rakibul start*/


                $managingdirector = ListSubscriber::where('app_id', $applicationId)
                    ->where('is_director', 1)
                    ->where('position', 3)
                    ->first(['digital_signature']);


                $moadata = Objective::where('rjsc_nr_app_id', $applicationId)->get();
                $subscribers = ListSubscriber::where('app_id', $applicationId)
                    ->get();


                $contents = view("CompanyRegSingleForm::preview.new-reg-form-moa-pdf", compact('appInfo', 'subscribers', 'districts', 'signature', 'liabilitytypes', 'nationality', 'witnessDataFiled', 'moadata'))->render();

                $mpdf = new mPDF([
                    'utf-8', // mode - default ''
                    'A4', // format - A4, for example, default ''
                    12, // font size - default 0
                    'dejavusans', // default fot family
                    10, // margin_leftn
                    10, // margin right
                    10, // margin top
                    15, // margin bottom
                    10, // margin header
                    9, // margin footer
                    'P'
                ]);

                // $mpdf->Bookmark('Start of the document');
                $mpdf->useSubstitutions;
                $mpdf->SetProtection(array('print'));
                $mpdf->SetDefaultBodyCSS('color', '#000');
                $mpdf->SetTitle("BIDA One Stop Service");
                $mpdf->SetSubject("Subject");
                $mpdf->SetAuthor("Business Automation Limited");
                $mpdf->autoScriptToLang = true;
                $mpdf->baseScript = 1;
                $mpdf->autoVietnamese = true;
                $mpdf->autoArabic = true;

                $mpdf->autoLangToFont = true;
                $mpdf->SetDisplayMode('fullwidth');
                $mpdf->SetHTMLFooter('

                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
                $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
                $mpdf->setAutoTopMargin = 'stretch';
                $mpdf->setAutoBottomMargin = 'stretch';
                $mpdf->WriteHTML($stylesheet, 1);

                $mpdf->WriteHTML($contents, 2);

                $mpdf->defaultfooterfontsize = 10;
                $mpdf->defaultfooterfontstyle = 'B';
                $mpdf->defaultfooterline = 0;

                $mpdf->SetCompression(true);
                //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
                if (isset($flag)) {
                    $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_moa.pdf', 'F');
                } else {
                    $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
                }

                return Redirect::to(URL::previous() . "#step14");


            } catch (\Exception $e) {
                dd($e->getMessage().$e->getLine());
                Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
                return Redirect()->back()->withInput();
            }

        } else {
            Session::flash('error', "This Applicaiton Not Exists");
            return Redirect()->back();
        }

    }

    public function appForm_XI_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($app_id);


            $process_type_id = $this->process_type_id;
            $appInfo = CompanyRegSingleForm::find($applicationId);
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name']);

            $directors = ListSubscriber::where('app_id', $applicationId)
                ->where('is_director', 1)
                ->get(['serial_number', 'digital_signature', 'usual_residential_address', 'usual_residential_district_id', 'other_occupation']);

            /*rakibul End*/
            $contents = view("CompanyRegSingleForm::preview.new-reg-form-xi-pdf", compact('appInfo', 'witnessDataFiled', 'directors', 'districts'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_xi.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");

        } catch (\Exception $e) {
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1115]');
            return Redirect()->back()->withInput();
        }
    }

    public function appForm_XII_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($app_id);

            $process_type_id = $this->process_type_id;
            $appInfo = CompanyRegSingleForm::find($applicationId);
            $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $applicationId)->first(['name']);

            $subscriberList = ListSubscriber::leftJoin('rjsc_nr_countries', 'rjsc_cr_sf_subscribers_info.original_nationality_id', '=', 'rjsc_nr_countries.id')
                ->leftJoin('rjsc_nr_countries as c2', 'rjsc_cr_sf_subscribers_info.present_nationality_id', '=', 'c2.id')
                ->where('app_id', $applicationId)->get(['rjsc_cr_sf_subscribers_info.*', 'rjsc_nr_countries.nationality', 'c2.nationality as present_nationality']);

            $contents = view("CompanyRegSingleForm::preview.new-reg-form-xii-pdf", compact('appInfo', 'witnessDataFiled', 'subscriberList'))->render();
            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_xii.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");


        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1116]');
            return Redirect()->back()->withInput();
        }
    }

    public function appForm_XIV_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($app_id);
            $process_type_id = $this->process_type_id;
            //$appInfo = CompanyRegSingleForm::find($applicationId); rjsc_nr_doc_filled_by
            $totalSubscribedShare = ListSubscriber::where('app_id', $applicationId)->sum('no_of_subscribed_shares');
            $appInfo = CompanyRegSingleForm::leftJoin('rjsc_nr_doc_filled_by as rjc', 'rjc.rjsc_nr_app_id', '=', 'rjsc_cr_sf_apps.id')
                ->where('rjsc_cr_sf_apps.id', $applicationId)
                ->first();

            $contents = view("CompanyRegSingleForm::preview.new-reg-form-xiv-pdf", compact('appInfo', 'districts', 'totalSubscribedShare'))->render();
            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);

            // $mpdf->Bookmark('Start of the document');
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            //$mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_xiv.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }
            return Redirect::to(URL::previous() . "#step14");


        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1117]');
            return Redirect()->back()->withInput();
        }
    }


    public function appForm_article_Pdf($app_id, $flag = null)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $applicationId = Encryption::decodeId($app_id);
            $appInfo = CompanyRegSingleForm::find($applicationId);



            $entityType = CommonFunction::getFullEntityType($appInfo->entity_type_id);

            $nrAoaClause = AoaInfo::where('rjsc_nr_app_id', $applicationId)
                ->orderby('sequence', 'asc')
                ->get();

            $contents = view("CompanyRegSingleForm::preview.new-reg-form-article-pdf", compact('appInfo', 'nrAoaClause', 'entityType'))->render();

            $mpdf = new mPDF([
                'utf-8', // mode - default ''
                'A4', // format - A4, for example, default ''
                12, // font size - default 0
                'dejavusans', // default fot family
                10, // margin_leftn
                10, // margin right
                10, // margin top
                15, // margin bottom
                10, // margin header
                9, // margin footer
                'P'
            ]);
            $mpdf->useSubstitutions;
            $mpdf->SetProtection(array('print'));
            $mpdf->SetDefaultBodyCSS('color', '#000');
            $mpdf->SetTitle("BIDA One Stop Service");
            $mpdf->SetSubject("Subject");
            $mpdf->SetAuthor("Business Automation Limited");
            $mpdf->autoScriptToLang = true;
            $mpdf->baseScript = 1;
            $mpdf->autoVietnamese = true;
            $mpdf->autoArabic = true;

            $mpdf->autoLangToFont = true;
            $mpdf->SetDisplayMode('fullwidth');
            $mpdf->SetHTMLFooter('
                    <table width="100%">
                        <tr>
                            <td width="50%"><i style="font-size: 10px;">Download time: {DATE j-M-Y h:i a}</i></td>
                            <td width="50%" align="right"><i style="font-size: 10px;">{PAGENO}/{nbpg}</i></td>
                        </tr>
                    </table>');
            $stylesheet = file_get_contents('assets/stylesheets/appviewPDF.css');
            $mpdf->setAutoTopMargin = 'stretch';
            $mpdf->setAutoBottomMargin = 'stretch';
            $mpdf->WriteHTML($stylesheet, 1);

            $mpdf->WriteHTML($contents, 2);

            $mpdf->defaultfooterfontsize = 10;
            $mpdf->defaultfooterfontstyle = 'B';
            $mpdf->defaultfooterline = 0;

            $mpdf->SetCompression(true);
            // $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            if (isset($flag)) {
                $mpdf->Output('uploads/rjsc_sf_pdf/' . $applicationId . '_article.pdf', 'F');
            } else {
                $mpdf->Output($appInfo->tracking_no . '.pdf', 'I');
            }

            return Redirect::to(URL::previous() . "#step14");

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [CR-1118]');
            return Redirect()->back()->withInput();
        }
    }

    public function appFormPreview($app_id)
    {
        $applicationId = Encryption::decodeId($app_id);
        $entity_type_id = CompanyRegSingleForm::where('id', $applicationId)->pluck('entity_type_id');
        $filestatus = RjscNrSubmitForms::where('app_id', $applicationId)->orderBy('ref_id', 'asc')->get(['ref_id', 'file', 'form_name']);
        $entity_type_id = intval($entity_type_id);
        $appInfo = Rjsc_NrForms::where('status', '1')->where(function ($query) use ($entity_type_id) {
            if ($entity_type_id == 1) {
                $query->where('type', '=', 'Private');
            } elseif ($entity_type_id == 2) {
                $query->where('type', '=', 'Public');
            }
        })
            ->orderBy('id', 'asc')
            ->get([
                'id',
                'name',
                'description',
                'is_extra'
            ]);

        return view("CompanyRegSingleForm::preview.preview-list", compact('appInfo', 'applicationId', 'filestatus'));
    }


    public function appFormFind($form_id, $app_id)
    {
        $formId = Encryption::decodeId($form_id);
        $applicationId = Encryption::decodeId($app_id);
        $flag = 'D';

        if ($formId == 1 || $formId == 2) {
            $formName = 'FORM I';
            $form_no = '_i';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-i-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 3 || $formId == 4) {
            $formName = 'FORM VI';
            $form_no = '_vi';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-vi-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 5 || $formId == 6) {
            $formName = 'FORM IX';
            $form_no = '_ix';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-ix-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 7 || $formId == 8) {
            $formName = 'FORM X';
            $form_no = '_x';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-x-pdf/' . $app_id . '/' . $flag);
            }


        } else if ($formId == 9) {
            $formName = 'FORM XI';
            $form_no = '_xi';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-xi-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 10 || $formId == 11) {
            $formName = 'FORM XII';
            $form_no = '_xii';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/company-registration-sf-form-xii-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 12) {
            $formName = 'FORM XIV';
            $form_no = '_xiv';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-xiv-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 13 || $formId == 14) {
            $formName = 'FORM MOA';
            $form_no = '_moa';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-moa-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 15 || $formId == 16) {
            $formName = 'FORM AOA';
            $form_no = '_article';
            if ($this->savePdfData($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFile($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-article-pdf/' . $app_id . '/' . $flag);
            }

        } else {
            return redirect()->back();
        }
    }

    public function deleteExistingFile($applicationId, $form_no)
    {
        $filePath = 'uploads/rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
        $fileIsDeleted = File::delete($filePath);
        return true;
    }

    public function savePdfData($formId, $applicationId, $formName, $form_no)
    {
        $form_pdf_save = new RjscNrSubmitForms();
        $find_pdf = RjscNrSubmitForms::where('app_id', $applicationId)
            ->where('ref_id', $formId)
            ->count();

        if ($find_pdf == 0) {
            $form_pdf_save->ref_id = $formId;
            $form_pdf_save->app_id = $applicationId;
            $form_pdf_save->file = 'rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
            $form_pdf_save->form_name = $formName;
            $form_pdf_save->is_extra = '1';
            return $form_pdf_save->save();

        } else {
            $pdfinfo = RjscNrSubmitForms::where('app_id', $applicationId)
                ->where('ref_id', $formId)
                ->first();
            $pdfinfo->ref_id = $formId;
            $pdfinfo->app_id = $applicationId;
            $pdfinfo->file = 'rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
            $pdfinfo->form_name = $formName;
            $pdfinfo->is_extra = '1';
            return $pdfinfo->save();
        }
    }

    public function appFormsUpload()
    {
        return view("CompanyRegSingleForm::preview.preview-list1");
    }

    public function storeFiles(Request $request)
    {
        try {
            $app_id = Encryption::decodeId($request->get('app_id'));
            $ref_id = Encryption::decodeId($request->get('ref_id'));
            $form_name = $request->get('form_name');
            $base64_img = $request->get('base64_img');
            $files_data = array(
                'app_id' => $app_id,
                'ref_id' => $ref_id,
                'form_name' => $form_name,
                'doc' => $base64_img,
                'status' => 1,
                'is_deleted' => 0
            );
            $img_obj = RjscNrSubmitForms::firstOrNew(['app_id' => $app_id, 'ref_id' => $ref_id]);
            foreach ($files_data as $field => $value) {
                $img_obj->$field = $value;
            }
            $img_obj->save();
            return response()->json(['responseCode' => 1]);
        } catch (\Exception $e) {
            return response()->json(['responseCode' => 0, 'msg' => $e->getMessage()]);
        }
    }


    public function PDFUploadCheck($app_id)
    {
        $applicationId = Encryption::decodeId($app_id);
        $entity_type_id = CompanyRegSingleForm::where('id', $applicationId)->pluck('entity_type_id');
        $filestatus = RjscNrSubmitForms::where('app_id', $applicationId)->get(['ref_id']);
        $entity_type_id = intval($entity_type_id);
        $All_pdf_forms = Rjsc_NrForms::where('status', '1')->where(function ($query) use ($entity_type_id) {
            if ($entity_type_id == 1) {
                $query->where('type', '=', 'Private');
            } elseif ($entity_type_id == 2) {
                $query->where('type', '=', 'Public');
            }
        })->count();


        $upload_pdf_forms = RjscNrSubmitForms::where('app_id', $applicationId)
            ->whereNotNull('doc')
            ->count();
        if ($All_pdf_forms != $upload_pdf_forms) {

            Session::flash('error', "Sorry! have to upload all files");
            return redirect()->back()->withInput();
        } else {
            CompanyRegSingleForm::where('id', $applicationId)
                ->update(['doc_status' => '1']);

            Session::flash('success', "done successfully");
            return redirect()->back()->withInput();
        }
    }


    public function appFormFindTest($form_id, $app_id)
    {
        //return '12';
        $formId = Encryption::decodeId($form_id);
        $applicationId = Encryption::decodeId($app_id);
        $flag = 'D';

        if ($formId == 1 || $formId == 2) {
            $formName = 'FORM I';
            $form_no = '_i';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-i-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 3 || $formId == 4) {
            $formName = 'FORM VI';
            $form_no = '_vi';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-vi-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 5 || $formId == 6) {
            $formName = 'FORM IX';
            $form_no = '_ix';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-ix-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 7 || $formId == 8) {
            $formName = 'FORM X';
            $form_no = '_x';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-x-pdf/' . $app_id . '/' . $flag);
            }


        } else if ($formId == 9) {
            $formName = 'FORM XI';
            $form_no = '_xi';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-xi-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 10 || $formId == 11) {
            $formName = 'FORM XII';
            $form_no = '_xii';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-xii-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 12) {
            $formName = 'FORM XIV';
            $form_no = '_xiv';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-xiv-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 13 || $formId == 14) {
            $formName = 'FORM MOA';
            $form_no = '_moa';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-moa-pdf/' . $app_id . '/' . $flag);
            }

        } else if ($formId == 15 || $formId == 16) {
            $formName = 'FORM AOA';
            $form_no = '_article';
            if ($this->savePdfDataTest($formId, $applicationId, $formName, $form_no) == true) {
                $this->deleteExistingFileTest($applicationId, $form_no);
                return redirect('company-registration-sf/new-reg-form-article-pdf/' . $app_id . '/' . $flag);
            }

        } else {
            return redirect()->back();
        }
    }

    public function deleteExistingFileTest($applicationId, $form_no)
    {
        $filePath = 'uploads/rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
        $fileIsDeleted = File::delete($filePath);
        return true;
    }

    public function savePdfDataTest($formId, $applicationId, $formName, $form_no)
    {
        $form_pdf_save = new RjscNrSubmitForms();
        $find_pdf = RjscNrSubmitForms::where('app_id', $applicationId)
            ->where('ref_id', $formId)
            ->count();
        if ($find_pdf == 0) {
            $form_pdf_save->ref_id = $formId;
            $form_pdf_save->app_id = $applicationId;
            $form_pdf_save->file = 'rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
            $form_pdf_save->form_name = $formName;
            $form_pdf_save->is_extra = '0';
            return $form_pdf_save->save();

        } else {
            $pdfinfo = RjscNrSubmitForms::where('app_id', $applicationId)
                ->where('ref_id', $formId)
                ->first();
            $pdfinfo->ref_id = $formId;
            $pdfinfo->app_id = $applicationId;
            $pdfinfo->file = 'rjsc_sf_pdf/' . $applicationId . $form_no . '.pdf';
            $pdfinfo->form_name = $formName;
            $pdfinfo->is_extra = '0';
            return $pdfinfo->save();
        }
    }

    public function PDFUploadCheckTest($app_id)
    {
        $applicationId = Encryption::decodeId($app_id);
        $entity_type_id = CompanyRegSingleForm::where('id', $applicationId)->pluck('entity_type_id');
        $filestatus = RjscNrSubmitForms::where('app_id', $applicationId)->get(['ref_id']);
        $entity_type_id = intval($entity_type_id);
        $All_pdf_forms = Rjsc_NrForms::where('status', '1')->where('is_extra', '0')->where(function ($query) use ($entity_type_id) {
            if ($entity_type_id == 1) {
                $query->where('type', '=', 'Private');
            } elseif ($entity_type_id == 2) {
                $query->where('type', '=', 'Public');
            }
        })->count();


        $upload_pdf_forms = RjscNrSubmitForms::where('app_id', $applicationId)
            ->where('is_extra', '0')
            ->whereNotNull('doc')
            ->count();
        if ($All_pdf_forms != $upload_pdf_forms) {

            Session::flash('error', "Sorry! have to upload all files");
            return redirect()->back()->withInput();
        } else {
            CompanyRegSingleForm::where('id', $applicationId)
                ->update(['doc_status' => '1']);

            Session::flash('success', "done successfully");
            return redirect()->back()->withInput();
        }
    }

    public function getDynamicDoc()
    {
        $rjscBaseApi =  Config('stackholder.RJSC_NC_API_URL');

        $newReg = new NewRegController();
        $token = $newReg->getRjscToken();

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $rjscBaseApi . "/doc-name",
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
                "client-id: OSS_BIDA",
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $decoded_response = json_decode($response, true);
        $docArr = [];
        foreach ($decoded_response['data'] as $value){
            $docArr[$value['id']] =$value['title'];
        }

        return $decoded_response['data'];
    }


}


