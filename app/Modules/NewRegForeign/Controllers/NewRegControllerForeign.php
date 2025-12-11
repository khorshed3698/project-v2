<?php

namespace App\Modules\NewRegForeign\Controllers;

use App\Libraries\ACL;

use App\Libraries\UtilFunction;
use App\Modules\NewRegForeign\Models\AoaInfo;
use App\Modules\NewReg\Models\NewReg;
use App\Modules\NewReg\Models\NrClause;
use App\Modules\NewRegForeign\Models\ListSubscriber;
use App\Modules\NewReg\Models\Rjsc_nr_apps_certificate;
use App\Modules\NewRegForeign\Models\Rjsc_NrForms;
use App\Modules\NewReg\Models\RjscCompanyPosition;
use App\Modules\NewReg\Models\RjscLiability;
use App\Modules\NewReg\Models\RjscNationality;
use App\Modules\NewRegForeign\Models\RjscNrFDoc;
use App\Modules\NewReg\Models\RjscNrDocList;
use App\Modules\NewReg\Models\RjscNrEntityType;
use App\Modules\NewRegForeign\Models\RjscNrFPayment;
use App\Modules\NewRegForeign\Models\RjscNrfRequest;
use App\Modules\NewRegForeign\Models\RjscNrfSubmitForms;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewReg\Models\RjscArea;
use App\Modules\NewReg\Models\RjscNrParticularBody;
use App\Modules\NewReg\Models\RjscNrQualificShare;
use App\Modules\NewReg\Models\RjscSector;
use App\Modules\NewReg\Models\RjscSubmissionVerify;
use App\Modules\NewReg\Models\RjscSubsector;
use App\Modules\NewRegForeign\Models\NewRegForeign;
use App\Modules\NewRegForeign\Models\RjscNrFPaymentInfo;
use App\Modules\NewRegForeign\Models\RjscWitness;
use App\Modules\NewRegForeign\Models\RjscWitnessFilledBy;
use App\Modules\OfficePermissionNew\Models\OfficePermissionNew;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class NewRegControllerForeign extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        $this->process_type_id = 111;
        $this->aclName = 'NewReg';
    }

    public function selectCompanyType(){
        $public_html = strval(view('NewRegForeign::new-reg.company_type'));
        return response()->json(['responseCode' => 1, 'html' => $public_html]);
    }


    public function htmlpage()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $entityType = null;
        $rjscCompanyPosition = [];
        $nominationEntity = [];
        $company_id = Auth::user()->company_ids;
        $OfficePermissionNew = OfficePermissionNew::leftJoin('process_list', 'process_list.ref_id', '=', 'opn_apps.id')
            ->where('process_list.process_type_id', 6) // 1 is for Office Permission New
            ->where('process_list.status_id', 25) // 25 is completed.
            ->where('process_list.company_id', $company_id)
            ->first();

        if (empty($OfficePermissionNew)) {
            if(!in_array(CommonFunction::getUserType(), ['15x151','5x506'])){
                return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;text-align: center'>You must have a approved New Office Permission.</h4>"]);
            }
        }


//        Session::put('sequence', 0);
        if(!empty(Session::get('current_app_id'))){
            $app_id= Session::get('current_app_id');
            $app_id = Encryption::decodeId($app_id);
            $rjscNrApps = NewReg::find($app_id);

            if(empty($rjscNrApps)){
                session()->forget('current_app_id');
            }else{
                $entityType = CommonFunction::getFullEntityType($rjscNrApps->entity_type_id);
                $rjscCompanyPosition = RjscCompanyPosition::where('rjsc_company_type_rjsc_id', $rjscNrApps->entity_type_id)->lists('title', 'rjsc_id')->all();
                $nominationEntity = RjscNrParticularBody::where('rjsc_nr_app_id',$app_id)->lists('name_corporation_body', 'id')->all();
            }
        }else{
            $app_id= 0;
        }

        $rjscNrDocList = RjscNrDocList::where('status', 1)->lists('name', 'doc_id')->all();
        $nrClause = NrClause::where('status',1)->lists('name', 'clause_id')->all();
        $nrAoaClause = AoaInfo::leftJoin('rjsc_nr_clause', 'rjsc_nr_clause.clause_id', '=', 'rjsc_nrf_aoa_info.clause_title_id')
            ->where('rjsc_nrf_aoa_info.rjsc_nrf_app_id',$app_id)
            ->get(['rjsc_nr_clause.name','rjsc_nrf_aoa_info.*']);

        $rjscOffice = RjscNrEntityType::lists('name', 'entity_type_id')->all();

        $districts = ['0' => 'Select One'] + RjscArea::orderby('name')->where('area_type', 2)->lists('name', 'rjsc_id')->all();
        $nationality = RjscNationality::where('status', 1)->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'country_id');
        $liabilitytypes = RjscLiability::where('status', 1)->where('name', '!=', '')->orderby('name', 'asc')->lists('name', 'liability_types_id');
        $rjscsector = ['0' => 'Select One'] + RjscSector::orderby('name')->where('status', 1)->lists('name', 'sector_id')->all();
        $sub_sectors = ['' => 'Select one'] + RjscSubsector::orderBy('name')->lists('name', 'sub_sector_id')->all();

        $authorizeCapitalValidate = NewReg::leftJoin('nr_subscribers_individual_info', 'nr_subscribers_individual_info.app_id', '=', 'rjsc_nr_apps.id')
            ->where('rjsc_nr_apps.id', $app_id)
            ->select(DB::raw('CASE WHEN rjsc_nr_apps.value_of_qualification_share*sum(no_of_subscribed_shares) <= rjsc_nr_apps.authorize_capital THEN 1  ELSE 0 END as capital_validate'))
            ->first(['capital_validate']);

        $subscriber= [];

        if ($app_id != '' || $app_id != 0){
            $subscriber= ListSubscriber::leftjoin('rjsc_company_positions as rjsc_position', 'rjsc_position.rjsc_id', '=', 'nr_subscribers_individual_info.position')->where('nr_subscribers_individual_info.app_id', $app_id)->get(
                ['nr_subscribers_individual_info.*', 'rjsc_position.title']
            );
        }

        return view("NewRegForeign::new-reg.new-reg-application", compact('nrClause','nrAoaClause','districts',
            'rjscOffice', 'nationality', 'subscriber','entityType', 'rjscCompanyPosition',
            'rjscNrDocList','liabilitytypes','rjscsector','sub_sectors','nominationEntity', 'authorizeCapitalValidate' ));
    }

    public function rjscPartiularStore(Request $request)
    {
        if ($request->get('actionBtn') != 'draft') {
            $rules = [
                'name_corporation_body' => 'required',
                'represented_by' => 'required',
                'address' => 'required',
                'district_id' => 'required',
                'no_subscribed_shares' => 'required',
                'no_qualific_share' => 'required',
                'value_of_each_share' => 'required',
                'agreement_witness_name' => 'required',
                'agreement_witness_address' => 'required',
            ];

            $messages = [
//                'source_country.required_unless' => 'The source country field is required',
            ];

            $this->validate($request, $rules, $messages);
        }

        try {

            $rjsc_nr_app_id = 1;

            DB::beginTransaction();
            $data = $request->all();

//            if ($request->get('app_id')) {
//                $decodedId = Encryption::decodeId($data['app_id']);
//                $appData = ImportPermit::find($decodedId);
//                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
//            } else {
//                $appData = new ImportPermit();
//                $processData = new ProcessList();
//                $processData->company_id = $companyId;
//            }

            $appData = new RjscNrQualificShare();

            $appData->rjsc_nr_app_id = $rjsc_nr_app_id;
            $appData->no_qualific_share = $data['no_qualific_share'];
            $appData->value_of_each_share = $data['value_of_each_share'];
            $appData->agreement_witness_name = $data['agreement_witness_name'];
            $appData->agreement_witness_address = $data['agreement_witness_address'];
            $appData->district_id = $data['agreement_district_id'];

//            if ($request->get('actionBtn') == "draft") {
//                // TODO:: if draft then status
//            } else {
//                // TODO:: if draft then status
//            }


            $appData->save();

            // Particulars Body submitted
            foreach ($request->get('name_corporation_body') as $key => $dat) {
                $objParticular = new RjscNrParticularBody();
                $objParticular->rjsc_nr_app_id = $rjsc_nr_app_id;
                $objParticular->name_corporation_body = $data['name_corporation_body'][$key];
                $objParticular->represented_by = $data['represented_by'][$key];
                $objParticular->address = $data['address'][$key];
                $objParticular->district_id = $data['district_id'][$key];
                $objParticular->no_subscribed_shares = $data['no_subscribed_shares'][$key];
                $objParticular->save();
            }
            DB::commit();
            Session::flash('success', 'Successfully Stored Data');
            return redirect('licence-applications/company-registration/add#step3');
        } catch (\Exception $e) {
            DB::rollback();
//            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back()->withInput();
        }

    }

    public function witnessStore(Request $request)
    {
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
//        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
//            Session::flash('error', "Sorry! Your selected company is not eligible or you have no approved Basic Information application.");
//            return redirect()->back();
//        }


        if ($request->get('actionBtn') != 'draft') {
            $rules = [
//                'name.*' => 'required|string',
//                'address.*' => 'required|string',
//                'phone.*' => 'required|string',
//                'national_id.*' => 'required|string',
                'name_document_by' => 'required',
                'position_id' => 'required',
                'address_document_by' => 'required',
                'district' => 'required'
            ];
            $messages = [
//                'source_country.required_unless' => 'The source country field is required',
            ];
            $this->validate($request, $rules, $messages);
        }
        try {


            DB::beginTransaction();

//            $data = $request->all();
//            if ($request->get('app_id')) {
//                if(!empty(RjscWitnessFilledBy::find($app_id))){
//                    $appData=RjscWitnessFilledBy::find($app_id);
//                }else {
//                    $appData = new RjscWitnessFilledBy();
//                }
//            } else {
//                $appData = new RjscWitnessFilledBy();
//            }
//
//            $appData->rjsc_nr_app_id = $app_id;
//dd($app_id);
            $data = $request->all();
            if ($request->get('app_id')) {
                $appData = RjscWitnessFilledBy::firstOrNew(['rjsc_nr_app_id'=> $app_id]);
            }else {
                $appData = new RjscWitnessFilledBy();
            }

            $appData->rjsc_nr_app_id = $app_id;



            if (CommonFunction::asciiCharCheck($data['name_document_by'])){
                $appData->name = $data['name_document_by'];
            }else{

                Session::flash('error', 'non-ASCII Characters found in name_document_by [GI-1002]');
                return Redirect::to(URL::previous() . "#step4");

            }

            $appData->position_id = $data['position_id'];

            if (CommonFunction::asciiCharCheck($data['organization'])){
                $appData->organization = $data['organization'];
            }else{

                Session::flash('error', 'non-ASCII Characters found in name_document_by [GI-1002]');
                return Redirect::to(URL::previous() . "#step4");

            }

            if (CommonFunction::asciiCharCheck($data['address_document_by'])){
                $appData->address = $data['address_document_by'];
            }else{

                Session::flash('error', 'non-ASCII Characters found in address_document_by [GI-1002]');
                return Redirect::to(URL::previous() . "#step4");

            }



            $entityDistrict = explode('@', $request->get('district'));
            $appData->district_id = !empty($entityDistrict[0]) ? $entityDistrict[0] : '';
            $appData->district_name = !empty($entityDistrict[1]) ? $entityDistrict[1] : '';

            $entityPosition = explode('@', $request->get('position_id'));
            $appData->position_id = !empty($entityPosition[0]) ? $entityPosition[0] : '';
            $appData->position_name = !empty($entityPosition[1]) ? $entityPosition[1] : '';


//            if ($request->get('actionBtn') == "draft") {
//                // TODO:: if draft then status
//            } else {
//                // TODO:: if draft then status
//            }
            $appData->save();
            // Particulars Body submitted
            RjscWitness::where('rjsc_nr_app_id',$app_id)->delete();


            if($app_id){
                $sequence = NewRegForeign::find($app_id);
                $sequence->sequence = 5;
                $sequence->save();
                Session::put('sequence', $sequence->sequence);
            }


            DB::commit();

            if ($app_id) {
                return Redirect::to(URL::previous() . "#step5");
            }

//            return redirect('licence-applications/company-registration/add#step5');
        } catch (\Exception $e) {
            DB::rollback();
//            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back()->withInput();
        }
    }

    public function newRegParticularEdit($app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        $app_id = 1;
        $districts = ['0' => 'Select One'] + RjscArea::orderby('name')->where('area_type', 2)->lists('name', 'rjsc_id')->all();
        $rjscSharedata = RjscNrQualificShare::where('rjsc_nr_app_id', $app_id)->first();
        $rjscParticulardata = RjscNrParticularBody::where('rjsc_nr_app_id', $app_id)->get();

        return view('NewReg::new-reg-edit.particular-body', compact('rjscSharedata', 'districts', 'rjscParticulardata', 'app_id'));
    }


    public function newRegWitnessEdit($app_id)
    {
//        $app_id =  Encryption::decodeId($app_id);
        $app_id = 1;
        $districts = ['0' => 'Select One'] + RjscArea::orderby('name')->where('area_type', 2)->lists('name', 'rjsc_id')->all();
        $rjscSharedata = RjscNrQualificShare::where('rjsc_nr_app_id', $id)->first();
        $rjscParticulardata = RjscNrParticularBody::where('rjsc_nr_app_id', $id)->get();

        return view('NewReg::new-reg-edit.app-edit-page', compact('districts', 'rjscSharedata', 'rjscParticulardata'));
        $witnessData = RjscWitness::where('rjsc_nr_app_id', $app_id)->orderBy('witness_flag', 'asc')->get(['name', 'address', 'phone', 'national_id', 'witness_flag'])->toArray();
        $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->first(['name', 'position_id', 'organization', 'address', 'district_id']);
        return view('NewReg::new-reg-edit.witness-document', compact('witnessData', 'districts', 'witnessDataFiled', 'app_id'));
    }

    public function newRegWitnessUpdate(Request $request, $app_id)
    {
        $app_id = Encryption::decodeId($app_id);
        if ($request->get('actionBtn') != 'draft') {
            $rules = [
//                'name.*' => 'required|string',
//                'address.*' => 'required|string',
//                'phone.*' => 'required|string',
//                'national_id.*' => 'required|string',
                'name_document_by' => 'required',
                'position_id' => 'required',
                'address_document_by' => 'required',
                'district_id' => 'required'
            ];
            $messages = [
//                'source_country.required_unless' => 'The source country field is required',
            ];
            $this->validate($request, $rules, $messages);
        }

        try {
            $rjsc_nr_app_id = $app_id;

            DB::beginTransaction();
            $data = $request->all();
//            if ($request->get('app_id')) {
//                $decodedId = Encryption::decodeId($data['app_id']);
//                $appData = ImportPermit::find($decodedId);
//                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
//            } else {
//                $appData = new ImportPermit();
//                $processData = new ProcessList();
//                $processData->company_id = $companyId;
//            }

            RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->delete();
            RjscWitness::where('rjsc_nr_app_id', $app_id)->delete();

            $appData = new RjscWitnessFilledBy();

            $appData->rjsc_nr_app_id = $rjsc_nr_app_id;
            $appData->name = $data['name_document_by'];
            $appData->position_id = $data['position_id'];
            $appData->address = $data['address_document_by'];
            $appData->district_id = $data['district_id'];

//            if ($request->get('actionBtn') == "draft") {
//                // TODO:: if draft then status
//            } else {
//                // TODO:: if draft then status
//            }

            $appData->save();
            // Particulars Body submitted
            foreach ($request->get('name') as $key => $dat) {
                $objWitness = new RjscWitness();
                $objWitness->rjsc_nr_app_id = $rjsc_nr_app_id;
                $objWitness->name = $data['name'][$key];
                $objWitness->address = $data['address'][$key];
                $objWitness->phone = $data['phone'][$key];
                $objWitness->national_id = $data['national_id'][$key];
                $objWitness->witness_flag = $key + 1;
                $objWitness->save();
            }
            DB::commit();
            Session::flash('success', 'Witness Data Update Successfully');
            return redirect()->back();
//            return redirect('licence-applications/company-registration/add#step5');
        } catch (\Exception $e) {
            DB::rollback();
//            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back()->withInput();
        }

    }
    public function loadSubSector(Request $request)
    {
        $sector_id = trim($request->get('sectorid'));
        $sub_sector = RjscSubsector::where([
            'sector_id' => $sector_id,
            'status' => 1,
        ])->get(['name', 'sub_sector_id']);
//        $sub_sector[0] = 'Others';

        return response()->json([
            'result' => $sub_sector
        ]);
    }

    public function noticeOfSituation()
    {
        Session::put('sequence', 7);
        sleep(1);
        return Redirect::to(URL::previous() . "#step7");
//        return redirect('licence-applications/company-registration/add#step7');
    }

    public function companiesAct()
    {
        Session::put('sequence', 8);
        sleep(1);
        return Redirect::to(URL::previous() . "#step8");
//        return redirect('licence-applications/company-registration/add#step8');
    }

//    public function companiesAct2()
//    {
//        Session::put('sequence', 9);
//        sleep(1);
//        return redirect('licence-applications/company-registration/add#step9');
//    }

    public function agreementPage()
    {
        Session::put('sequence', 10);
        sleep(1);
        return Redirect::to(URL::previous() . "#step10");
//        return redirect('licence-applications/company-registration/add#step10');
    }

    public function particularsPage()
    {
        Session::put('sequence', 11);
        sleep(1);
        return Redirect::to(URL::previous() . "#step11");
//        return redirect('licence-applications/company-registration/add#step11');
    }

    public function getViewFor12Section()
    {
//        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
//            return response()->json(['responseCode' => 1, 'html' => "<h4 style='color: red;margin-top: 250px;margin-left: 70px;'>You have no access right! Contact with system admin for more information</h4>"]);
//        }
        $app_id = Encryption::decodeId(Session::get('current_app_id'));
        $RjscWitnessFilledBy = RjscWitnessFilledBy::where('rjsc_nr_app_id',$app_id)->first();
        $subscriberList = ListSubscriber::leftJoin('rjsc_nr_countries', 'nr_subscribers_individual_info.original_nationality_id', '=', 'rjsc_nr_countries.id')
            ->leftJoin('rjsc_nr_countries as c2', 'nr_subscribers_individual_info.present_nationality_id', '=', 'c2.id')
            ->where('app_id', $app_id)->get(['nr_subscribers_individual_info.*','rjsc_nr_countries.nationality','c2.nationality as present_nationality']);
        return response()->json([
            'RjscWitnessFilledBy' => $RjscWitnessFilledBy,
            'subscriberList' => $subscriberList
        ]);
    }

    public function memorandumAssociation()
    {
        Session::put('sequence', 12);
        sleep(1);
        return Redirect::to(URL::previous() . "#step12");
//        return redirect('licence-applications/company-registration/add#step12');
    }




    public function finalSubmit(Request $request)
    {

        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $app_id = Session::get('current_app_id');
        $app_id = Encryption::decodeId($app_id);

        DB::beginTransaction();
        $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id])->first();

        if ($request->get('actionBtn') == "draft") {
            $processData->status_id = -1;
            $processData->desk_id = 0;
        } else {
            if ($processData->status_id == 5) { // For shortfall
                $processData->status_id = 2;
            } else {
                $processData->status_id = 1;
            }

            $processData->desk_id = 1; // 1 is desk AD (For Import Permit)
        }

        $processData->process_type_id = $this->process_type_id;
        $processData->save();

        // Generate Tracking No for Submitted application
        if ($request->get('actionBtn') != "draft" && $processData->status_id != 2  && strlen(trim($processData->tracking_no)) == 0) { // when application submitted but not as re-submitted
            $trackingPrefix= 'CR-'.date("Ymd").'-';
            $processTypeId = $this->process_type_id;
            $updateTrackingNo = DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                                                            select concat('$trackingPrefix',
                                                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-4,4) )+1,1),4,'0')
                                                                          ) as tracking_no
                                                             from (select * from process_list ) as table2
                                                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                                                        )
                                                      where process_list.id='$processData->id' and table2.id='$processData->id'");
        }
        DB::commit();
        Session::flash('success', 'Application submitted');

        if(!empty($app_id)){
            session()->forget('current_app_id');
            session()->forget('sequence');
        }

        return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));


    }

    public function submitandsavejson(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $app_id = Session::get('current_app_id');
        $app_id = Encryption::decodeId($app_id);


        DB::beginTransaction();

        $Info = NewRegForeign::where('id', $app_id)->first();
        $Info->doc_status = 1;
        $Info->save();

//        $Info->is_additional_attachment = 0;
//
//        if($request->has('is_additional_attachment')){
//            $Info->is_additional_attachment = 1;
//                $totalFile = count($request->get('file'));
//                $i = 0;
//                $arrayFile =[];
//                while ($i <= $totalFile) {
//                    $fileName = RjscNrfSubmitForms::where('app_id', $app_id)->where('is_extra',1)
//                        ->where('file',$request->get("validate_field_$i"))
//                        ->first();
//                    if(count($fileName) > 0){
//                        $fileName->form_name = $request->get('file_name')[$i];
//                        $fileName->save();
////                        $fileName->id;
//                    }else{
//                        $arrayFile[] = $request->get("validate_field_$i");
//                    }
//
//                    $i++;
//                }
//
//
//                foreach ($arrayFile as $key=>$fileFath){
//
//                    if($fileFath != null){
//                        $file = new RjscNrfSubmitForms();
//                        $file->is_extra = 1;
//                        $file->app_id = $app_id;
//                        $file->file = $fileFath;
//                        $file->form_name = $request->get('file_name')[$key];
//                        $file->save();
//                        $fileIds[] = $file->id;
//                    }
//
//                }
//
//                if (!empty($fileIds)) {
////                    RjscNrfSubmitForms::where('app_id', $app_id)
////                        ->where('is_extra',1)
////                        ->whereNotIn('id', $fileIds)
////                        ->delete();
//
//                }
//
//
////            }
//
//        }
//
//        $Info->save();


        $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id])->first();
        $processData->status_id = -1;
        $processData->desk_id = 0;



        ////////////////// stockholder Payment start//////////////////////////
        $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
            ->where([
                'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                'api_stackholder_payment_configuration.payment_category_id' => 3,
                'api_stackholder_payment_configuration.status' => 1,
                'api_stackholder_payment_configuration.is_archive' => 0,
            ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

        if (!$payment_config) {
            DB::rollback();
            Session::flash('error', "Payment configuration not found [NR-107]");
            return redirect()->back()->withInput();
        }


        if ($request->get('actionBtn') == 'Submit' && $processData->status_id == -1 && $payment_config) {
            $requestdata=RjscNrfRequest::where('ref_id',$app_id)->first();
            if (count($requestdata)<=0){
                $requestdata = new RjscNrfRequest();
                $requestdata->ref_id=$app_id;
                $processTypeId = $this->process_type_id;

                $trackingPrefix = "NRF-" . date("dMY") . '-';

                DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");



                $trackingid = CommonFunction::getTrackingNoByProcessId($processData->id);


                $processData->process_type_id = $this->process_type_id;
                $processData->save();
                $jsondata=$this->getData($app_id);
                $requestdata->request=$jsondata;
                $requestdata->save();
            }else{
                $jsondata=$this->getData($app_id);
                $requestdata->ref_id=$app_id;
                $requestdata->request=$jsondata;
                $requestdata->save();
            }

        }
        ///////////////////// stockholder Payment End//////////////////////////


        DB::commit();
        if(!empty($app_id)){
            session()->forget('current_app_id');
            session()->forget('sequence');
        }
        if ($request->get('actionBtn') == "draft") {
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
        Session::flash('success', 'Application submitted');

        return redirect('licence-applications/company-registration-foreign/applicationstatus/'.Encryption::encodeId($app_id));


    }

    public  function checkstatus($app_id){

        return view("NewRegForeign::new-reg.wait-for-payment",compact('app_id'));

    }
    public  function applicationstatus(Request $request){
        $app_id = Encryption::decodeId($request->appid);
//            $processstatus = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id])->get(['status_id']);
//            return response()->json($processstatus);
//
//        $application_id = Encryption::decodeId($request->enc_app_id);

        $rjscData = RjscNrfRequest::where('ref_id',$app_id)->orderBy('id', 'desc')->first();

        $status = intval($rjscData->status);
        if ($rjscData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
        } elseif ($status == -2 || $status == -3 || $status == -4) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
        }


    }

    public  function checkdocstatus(Request $request){
        $app_id = Encryption::decodeId($request->appid);
        $docstatus = RjscNrFDoc::where(['ref_id' => $app_id])
            ->orderBy('id', 'desc')
            ->get(['status']);
        $rowcount=count($docstatus);
        $docdata['status']=$docstatus;
        $docdata['count']=$rowcount;

        return response()->json($docdata);

    }
    public  function checkpaymentstatus(Request $request){
        $app_id = Encryption::decodeId($request->appid);

        $paymentstatus = RjscNrFPaymentInfo::where(['ref_id' => $app_id])
            ->orderBy('id', 'desc')
            ->get(['status']);

        $rowcount=count($paymentstatus);
        $paymentdata['status']=$paymentstatus;
        $paymentdata['count']=$rowcount;

        if ($rowcount>0){
            $status = intval($paymentstatus[0]->status);
            $id = intval($app_id);
             if ($status == 0) {
                return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
            } elseif ($status == -1) {
                return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
            } elseif ($status == -2 || $status == -3 || $status == -4  ) {
                return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($id), 'status' => $status, 'message' => 'Your request could not be processed. Please contact with system admin']);
            } elseif ($status == 1) {
                return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($id), 'status' => 1, 'message' => 'Your Request has been successfully verified']);
            }else{
                 return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($id), 'status' => $status, 'message' => 'Your Request has been successfully verified']);
             }
        }else{
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => Encryption::encodeId(0), 'status' => -3, 'message' => 'Your request could not be processed. Please contact with system admin']);
        }

        return response()->json($paymentdata);


    }



    public function nrPayment(Request $request)
    {
        $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
        try {
            $appId = Encryption::decodeId($request->get('enc_app_id'));

            $NRRecordRjsc = RjscNrfRequest::where('ref_id', $appId)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->first();

            $NRPaymentInfo = RjscNrFPaymentInfo::where('ref_id', $appId)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->first();
            $appInfo = NewRegForeign::leftJoin('process_list','process_list.ref_id','=','rjsc_nrf_apps.id')
                ->where('rjsc_nrf_apps.id', $appId)
                ->where('process_type_id', $this->process_type_id)
                ->first([
                    'rjsc_nrf_apps.*',
                    'process_list.tracking_no',
                    'process_list.status_id',
                ]);

            if (empty($appInfo)) {
                Session::flash('error', "Your RJSCF Record not found [RJSCF-1125]");
                return \redirect()->back();
            }

            if (empty($NRRecordRjsc)) {
                Session::flash('error', "Your NR Record not found [NR-1125]");
                return \redirect()->back();
            }

            $concatTransctionId = $NRRecordRjsc->response . '1' . $appInfo->company_name;

            // Application Info
//            $appInfo = ProcessList::where([
//                'process_type_id' => $this->process_type_id,
//                'ref_id' => $appId,
//            ])->first(['tracking_no']);
            // Store payment info
            // Get Payment Configuration


            $payment_config = StackholderPaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=', 'api_stackholder_payment_configuration.payment_category_id')
                ->where([
                    'api_stackholder_payment_configuration.process_type_id' => $this->process_type_id,
                    'api_stackholder_payment_configuration.payment_category_id' => 3,
                    'api_stackholder_payment_configuration.status' => 1,
                    'api_stackholder_payment_configuration.is_archive' => 0,
                ])->first(['api_stackholder_payment_configuration.*', 'sp_payment_category.name']);

            if (!$payment_config) {
                Session::flash('error', "Payment configuration not found [VRA-1123]");
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


//            $jsonData = '{"rjsc_fee":600,"rjsc_fees_ac_no":"0002601020864","rjsc_fees_ac_name":"RJSC FEES ACCOUNT-1-1735-0000-1816","rjsc_vat":90,"rjsc_vat_ac_no":"0117202000919","rjsc_vat_ac_name":"RJSC VAT ACC-1-1133-0010-0311","rjsc_duty":10,"rjsc_duty_ac_no":"0117202000918","rjsc_duty_ac_name":"RJSC STAMP DUTY ACC-1-1101-0020-1321"}';
            $jsonData = json_decode($NRPaymentInfo->response);
            $jsonData= $jsonData->data->msg;
//            dd($jsonData);

//            if (env('server_type', 'local') == 'live') {
            $rjscPayAccount1 = array(
                'receiver_account_no' => $jsonData->rjsc_fees_ac_no,
                'amount' => $jsonData->rjsc_fee,
                'distribution_type' => $stackholderDistibutionType,
            );
            $stackholderMappingInfo[] = $rjscPayAccount1;


            $rjscVatAccount2 = array(
                'receiver_account_no' => $jsonData->rjsc_vat_ac_no,
                'amount' => $jsonData->rjsc_vat,
                'distribution_type' => $stackholderDistibutionType,
            );

            $stackholderMappingInfo[] = $rjscVatAccount2;

            if($jsonData->rjsc_duty !=0 && $jsonData->rjsc_duty != ""){
                $rjscVatAccount3 = array(
                    'receiver_account_no' => $jsonData->rjsc_duty_ac_no,
                    'amount' => $jsonData->rjsc_duty,
                    'distribution_type' => $stackholderDistibutionType,
                );
                $stackholderMappingInfo[] = $rjscVatAccount3;
            }





//            } else {
//                $rjscPayAccount1 = array(
//                    'receiver_account_no' => '0002601020864',
//                    'amount' => 600,
//                );
//
//                $stackholderMappingInfo[] = $rjscPayAccount1;
//
//
//                $rjscVatAccount2 = array(
//                    'receiver_account_no' => '0002601020966',
//                    'amount' => 90,
//                );
//
//                $stackholderMappingInfo[] = $rjscVatAccount2;
//
//
////                $rjscVatAccount3 = array(
////                    'receiver_account_no' => $jsonData->rjsc_duty_ac_no,
////                    'amount' => $jsonData->rjsc_duty,
////                );
////
////                $stackholderMappingInfo[] = $rjscVatAccount3;
//            }


            $pay_amount = 0;
            $account_no = "";
            foreach ($stackholderMappingInfo as $data) {
                $pay_amount += $data['amount'];
                $account_no .= $data['receiver_account_no'] . "-";
            }

            $account_numbers = rtrim($account_no, '-');


            // Get SBL payment configuration
            DB::beginTransaction();$rand = str_pad($this->process_type_id.$appId, 10, "0", STR_PAD_LEFT );
            $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appId;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = $appInfo->tracking_no;
            $paymentInfo->receiver_ac_no = $account_numbers;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
//            $paymentInfo->request_id = "010" . rand(1000000, 9999999);
//            $paymentInfo->payment_date = date('Y-m-d');
            // $paymentInfo->ref_tran_no= $appInfo->tracking_no."/1/".$rand;
            $paymentInfo->ref_tran_no= $NRPaymentInfo->submission_no."/1/".$rand;
//            $paymentInfo->ref_tran_date_time = date('Y-m-d H:i:s'); // need to clarify
            $paymentInfo->pay_amount = $pay_amount;
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInsert = $paymentInfo->save();
            NewRegForeign::where('id', $appId)->update(['sf_payment_id' => $paymentInfo->id]);


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

        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[VRA-1025]");
            return redirect()->back()->withInput();
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
                $processData->read_status = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

            }

            $processData->save();

            $NRPaymentRjsc = RjscNrFPaymentInfo::where('ref_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first();
            $verification_response = json_decode($paymentInfo->verification_response);
            //dd();

            $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();
            foreach ($data2 as $singleResponse){
                $value = json_decode($singleResponse->verification_response);
                    $rData0['account_info'][] =[
                        'account_no'=>$value->TranAccount,
                        'app_client_name'=>'OSS_BIDA',
                        'balance'=>0,
                        'branch_code'=>$verification_response->BrCode,
                        'deposit'=>$value->TranAmount,
                        'particulars'=>$value->ReferenceNo,
                        'remarks'=>"",
                        'tran_date'=>$value->TransactionDate,
                        'tran_id'=>$value->TransactionId,
                        'withdraw'=>0,

                    ];
            }


            $nrfRjscPyament = new RjscNrFPayment();
            $nrfRjscPyament->request = json_encode($rData0);
            $nrfRjscPyament->ref_id = $paymentInfo->app_id;
            $nrfRjscPyament->tracking_no = $processData->tracking_no;
            $nrfRjscPyament->save();
            CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            DB::commit();
//            dd(json_encode($rData0));
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
//            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
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


            /*
             * if payment verification status is equal to 1
             * then transfer application to 'Submit' status
             */
            if($paymentInfo->is_verified == 1)
            {
//
//                $processData->status_id = 1;
//                $processData->desk_id = 0;
                $processData->read_status = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

                $processData->process_desc = 'Counter Payment Confirm';


                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);


                $verification_response = json_decode($paymentInfo->offline_verify_response);

                $NRPaymentRjsc = RjscNrFPaymentInfo::where('ref_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first();


                $data2 = StackholderSonaliPaymentDetails::where('payment_id',$payment_id)->where('distribution_type',$stackholderDistibutionType)->get();

                foreach ($data2 as $key=>$singleResponse){
                    $value = json_decode($singleResponse->verification_response);
                        $rData0['account_info'][] =[
                            'account_no'=>$value->TranAccount,
                            'app_client_name'=>'OSS_BIDA',
                            'balance'=>0,
                            'branch_code'=>$verification_response->BrCode,
                            'deposit'=>$value->TranAmount,
                            'particulars'=>$value->ReferenceNo,
                            'remarks'=>"",
                            'tran_date'=>$value->TransactionDate,
                            'tran_id'=>$value->TransactionId,
                            'withdraw'=>0,

                        ];
                }

                $nrfRjscPyament = new RjscNrFPayment();
                $nrfRjscPyament->request = json_encode($rData0);
                $nrfRjscPyament->ref_id = $paymentInfo->app_id;
                $nrfRjscPyament->tracking_no = $processData->tracking_no;
                $nrfRjscPyament->save();

            }
            /*
             * if payment status is not equal 'Waiting for Payment Confirmation'
             * then transfer application to 'Waiting for Payment Confirmation' status
             */
            else{
                $processData->status_id = 3; // Waiting for Payment Confirmation
                $processData->desk_id = 0;
                $processData->process_desc = 'Waiting for Payment Confirmation.';
                $paymentInfo->payment_status = 3;
                $paymentInfo->save();

                // SMS/ Email sent to user to notify that application is Waiting for Payment Confirmation
                // TODO:: Needed to sent mail to user


                Session::flash('success', 'Application is waiting for Payment Confirmation');
            }

            $processData->save();
            DB::commit();
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment. Error : ' . $e->getMessage());
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }



    public function getData($app_id){

        //$app_id='w-Uh6Xx-M7kFe09mpl_b49VTNvstF3pRkGuEsnMdty0';
        //$app_id=284;
        // dd($app_id);

        $generalinformation=NewRegForeign::where('id',$app_id)->first();
//        $directorslist=ListSubscriber::where('app_id',$app_id)
//            ->where('is_director',1)->get();
/*need to check for is director */
        $directorslist=ListSubscriber::where('app_id',$app_id)->get();


        $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->first();
        $ossappid =  ProcessList::where('ref_id',$app_id)
                        ->where('process_type_id',$this->process_type_id)
                        ->first(['tracking_no']);



        $rData['generalInfo'] =[];
        if (!empty($generalinformation)) {
            $rData['generalInfo'] = array(
                'entityName' => $generalinformation->name_of_entity,
                'entityType' => $generalinformation->entity_type_id,
                'entitySubType' => $generalinformation->entity_sub_type_id,
                'countryOfOrigin' => $generalinformation->country_origin_id,
                'originalEntityAddress' => $generalinformation->address_entity_origin,
                'bdEntityAddress' => $generalinformation->address_entity,
                'bdEntityDistrictId' => $generalinformation->entity_district_id,
                'mainBusinessObjective' => $generalinformation->main_business_objective,
                'businessSector' => $generalinformation->business_sector_id,
                'businessSubSector' => $generalinformation->business_sub_sector_id,
                'ConsInstrumentId' => $generalinformation->name_constitution_instrument_id,
                'ConsDocEng' => $generalinformation->constitution_documents_in_english,
                'ConsDocTrans' => $generalinformation->constitution_documents_in_english_translation,
                'proposedEntityName' => $generalinformation->constitution_documents_in_english_translation,
                'bidaPermissionRef' => Carbon::createFromFormat('Y-m-d H:i:s', $generalinformation->bida_permission_ref)->format('d-M-Y'),
                'bidaPermissionDate' => Carbon::createFromFormat('Y-m-d H:i:s', $generalinformation->bida_permission_date)->format('d-M-Y'),
                'bidaPermissionEffectDate' => Carbon::createFromFormat('Y-m-d H:i:s', $generalinformation->business_start_date)->format('d-M-Y'),
                'bidaCompEstbDate' => Carbon::createFromFormat('Y-m-d H:i:s', $generalinformation->business_establish_date)->format('d-M-Y'),
            );
        }
        $rData['listOfDirectors'] =[];

        foreach ($directorslist as $key=>$director){

            $dob = null;
            if ($director->dob == null || $director->dob == 'NULL' || $director->dob == '' || $director->dob == '0000-00-00'){
                $dob = null;
            }else{
                $dob = date("d/m/Y", strtotime($director->dob) );
            }

            $rData['listOfDirectors'][] =array(
                'name' => $director->corporation_body_name,
                'fathersName' => $director->father_name,
                'mothersName' => $director->mother_name,
                'residentialAddress' => $director->usual_residential_address,
                'residentialDistrict' => $director->usual_residential_district_id,
                'permanentAddress' =>$director->permanent_address,
                'permanentDistrict' =>$director->permanent_address_district_id,
                'mobile' => $director->mobile,
                'email' => $director->email,
                'nationality' =>$director->present_nationality_id,
                'dateOfBirth' => $dob,
                'tin' => ($director->tin_no != 0) ? $director->tin_no : '',
                'position' => $director->position,
                'otherBusinessOccupation' => $director->other_occupation,
            );

        }

        if(!empty($witnessDataFiled)){
            $rData['filingBy'] = array(
                'name' => $witnessDataFiled->name,
                'position' => $witnessDataFiled->position_id,
                'organization' => $witnessDataFiled->organization,
                'address' => $witnessDataFiled->address,
                'fillingDistrict' =>$witnessDataFiled->district_id
            );
        }

        $rData['archiveDocInfo'] = array(
            'documentId' => "49",
            'moaPages' => $generalinformation->memorandum_asso_no,
            'aoaPages' => $generalinformation->article_asso_no
        );

        $rData['requestInfo'] = array(
            'ossApplicationId' => $ossappid->tracking_no
        );
        $rReq['req_json_data']['foreignRegData'] = $rData;
        return json_encode($rReq);
    }



    public function foreignCompanyAdd(){
        return view('NewReg::foreign-company.foreign-company-add');
    }

    public function downloadpdf($crt_no,$app_id){
        //dd($crt_no);
        $app_id=Encryption::decodeId($app_id);
        //dd($app_id);
        $data=Rjsc_nr_apps_certificate::where('ref_id',$app_id)
            ->where('status',1)
            ->where('id',$crt_no)
            ->first();

        //dd($data->toArray());
        if(count($data)>0){
            $base64=$data->certificate_content;
            $file = 'certificate_'.$data->certificate_name.'.pdf';
            //dd($file);
            //dd($file);
            $decoded = base64_decode($base64);
            file_put_contents($file, $decoded);
            if (file_exists($file)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="'.basename($file).'"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($file));
                readfile($file);
                exit;
            }
        }else{
            Session::flash('error',"Certificate not Generated.");
            return Redirect::to(URL::previous());
        }

    }

    public function aprovedApplication(){
        try{
            $certificatedata=NewReg::leftjoin('process_list as p_list','p_list.ref_id','=','rjsc_nr_apps.id')
                ->rightjoin('rjsc_nr_certificate as r_crt','r_crt.ref_id','=','p_list.ref_id')
                ->where('p_list.process_type_id',$this->process_type_id)
                ->where('rjsc_nr_apps.rjsc_status',0)
                ->where('p_list.status_id',25)
                ->orderBy('rjsc_nr_apps.id', 'desc')
                ->limit(10)
                ->get(['r_crt.*']);


            //dd(count($certificatedata));
            if(count($certificatedata)>0){
               // dd($certificatedata->toArray());
                foreach ($certificatedata as $key=>$value){
                    //dd($value->toArray());
                    DB::beginTransaction();
                    $app_id=$value->ref_id;
                    $nrApps=NewReg::find($app_id);

                    if($value->status==1){
                        $responsedata=json_decode($value->response);
                        // dd($responsedata);
                        if (isset($responsedata->certificate)) {

Rjsc_nr_apps_certificate::where('ref_id',$app_id)->delete();
                        foreach ($responsedata->certificate as $responsevalue) {
                            $certificate= new Rjsc_nr_apps_certificate();
                            $certificate->ref_id=$app_id;
                            $filename = str_replace("/"," ",$responsevalue->doc_title);
                            $certificate->certificate_name=$filename;
                            $certificate->certificate_content=$responsevalue->content;
                            $certificate->status=1;
                            $certificate->save();
                        }
                       // dd($nrApps);

                        $nrApps->rjsc_status=1;
                        }
                        else{
                        	$nrApps->rjsc_status=-2;
                        }

                    }else{
                        $nrApps->rjsc_status=$value->status;
                    }
                    //dd($nrApps->toArray());
                    $nrApps->save();
                    DB::commit();
                    dd('success');
                }
            }else{
                return response()->json(['message'=>'No data found']);
            }

            return response()->json(['message'=>'data updated successfully']);
        }catch (\Exception $e) {
              $nrApps=NewReg::find($app_id);
              $nrApps->rjsc_status=-2;
              $nrApps->save();
//            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back();
        }


    }
    public function uploadDocument(){
        return View::make('NewReg::ajaxUploadFile');
    }

    public function storesubmissionnumber(Request $request){
        $submission_no = $request->get('submission_no');
      
        $clearence_letter_no = $request->get('clearence_letter_no');

        $submissiondata = RjscSubmissionVerify::where('submission_no', $submission_no)
            ->where('clearence_letter_no', $clearence_letter_no)
            ->orderBy('id','desc')->first();

//        if (count($submissiondata) == 0){
            $submissiondata = RjscSubmissionVerify::create([
                'status'=> 1,
                'submission_no' => $submission_no,
                'clearence_letter_no' => $clearence_letter_no,
            ]);
//        }
        $message = 'Your request has been locked on verify';
        $responseCode = 1;
        return response()->json(['responseCode' => $responseCode, 'message' => $message, 'submission_verify_id' => Encryption::encodeId($submissiondata->id), 'enc_status' => Encryption::encodeId(0)]);
    }

    public function submissverifationResponse(Request $request)
    {
        $rjsc_request_id = Encryption::decodeId($request->verification_id);

        $rjscData = RjscSubmissionVerify::orderBy('id', 'desc')->where('id',$rjsc_request_id)->first();

        $status = intval($rjscData->status);
        if ($rjscData == null) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId(-3), 'enc_id' => '', 'status' => -3, 'message' => 'Your request is invalid. please try again']);
        } elseif ($status == 0) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 0, 'message' => 'Your request has been locked on verify']);
        } elseif ($status == -1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -1, 'enc_status' => Encryption::encodeId($status), 'message' => 'Your request in-progress']);
        } elseif ($status == -2) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -2, 'message' => $rjscData->response]);
        }elseif ($status == -4) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => -4, 'message' => $rjscData->response]);
        } elseif ($status == 1) {
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => 1, 'message' => 'Your Request has been successfully verified','jsonData'=>$rjscData]);
        }else{
            return response()->json(['responseCode' => 1, 'enc_status' => Encryption::encodeId($status), 'enc_id' => Encryption::encodeId($rjscData->id), 'status' => $status, 'message' => $rjscData->response]);
        }
    }

}
