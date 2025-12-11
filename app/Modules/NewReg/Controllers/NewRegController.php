<?php

namespace App\Modules\NewReg\Controllers;

use App\Libraries\ACL;
use App\Modules\LicenceApplication\Models\NameClearance\NCRecordRjsc;
use App\Modules\LicenceApplication\Models\NameClearance\NcRjscPayConfirm;
use App\Modules\LicenceApplication\Models\NameClearance\NcRjscPayment;
use App\Modules\NewReg\Models\AoaInfo;
use App\Modules\NewReg\Models\NewReg;
use App\Modules\NewReg\Models\NrClause;
use App\Modules\NewReg\Models\ListSubscriber;
use App\Modules\NewReg\Models\Objective;
use App\Modules\NewReg\Models\Rjsc_nr_apps_certificate;
use App\Modules\NewReg\Models\Rjsc_Nr_certificate;
use App\Modules\NewReg\Models\Rjsc_NrForms;
use App\Modules\NewReg\Models\RjscCompanyPosition;
use App\Modules\NewReg\Models\RjscLiability;
use App\Modules\NewReg\Models\RjscMoaDefaultClause;
use App\Modules\NewReg\Models\RjscNationality;
use App\Modules\NewReg\Models\RjscNrDoc;
use App\Modules\NewReg\Models\RjscNrDocList;
use App\Modules\NewReg\Models\RjscNrEntityType;
use App\Modules\NewReg\Models\RjscNrPayment;
use App\Modules\NewReg\Models\RjscNrPaymentInfo;
use App\Modules\NewReg\Models\RjscNrRequest;
use App\Modules\NewReg\Models\RjscNrSubmitForms;
use App\Modules\NewReg\Models\RjscOffice;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewReg\Models\RjscArea;
use App\Modules\NewReg\Models\RjscNrParticularBody;
use App\Modules\NewReg\Models\RjscNrQualificShare;
use App\Modules\NewReg\Models\RjscSector;
use App\Modules\NewReg\Models\RjscSubmissionVerify;
use App\Modules\NewReg\Models\RjscSubsector;
use App\Modules\NewReg\Models\RjscWitness;
use App\Modules\NewReg\Models\RjscWitnessFilledBy;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Settings\Models\Configuration;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\SonaliPaymentStackHolders;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderSonaliPaymentDetails;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class NewRegController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        $this->process_type_id = 104;
        $this->aclName = 'NewReg';
    }

    public function selectCompanyType(){
        $rjscOffice = RjscNrEntityType::lists('name', 'entity_type_id')->all();
        $public_html = strval(view('NewReg::new-reg.company_type',compact('rjscOffice')));
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
        $nrAoaClause = AoaInfo::leftJoin('rjsc_nr_clause', 'rjsc_nr_clause.clause_id', '=', 'rjsc_nr_aoa_info.clause_title_id')
            ->where('rjsc_nr_aoa_info.rjsc_nr_app_id',$app_id)
            ->get(['rjsc_nr_clause.name','rjsc_nr_aoa_info.*']);

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

        return view("NewReg::new-reg.new-reg-application", compact('nrClause','nrAoaClause','districts',
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
            dd($e->getMessage(), $e->getLine());
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
                'district_id' => 'required'
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

            $data = $request->all();
            if ($request->get('app_id')) {
                $appData = RjscWitnessFilledBy::firstOrNew(['rjsc_nr_app_id'=> $app_id]);
            }else {
                $appData = new RjscWitnessFilledBy();
            }

            $appData->rjsc_nr_app_id = $app_id;


            // dd($appData);
            if (CommonFunction::asciiCharCheck($data['name_document_by'])){
                $appData->name = $data['name_document_by'];
            }else{

                Session::flash('error', 'non-ASCII Characters found in name_document_by [GI-1002]');
                return Redirect::to(URL::previous() . "#step4");

            }

            $appData->position_id = $data['position_id'];

            if (CommonFunction::asciiCharCheck($data['address_document_by'])){
                $appData->address = $data['address_document_by'];
            }else{

                Session::flash('error', 'non-ASCII Characters found in address_document_by [GI-1002]');
                return Redirect::to(URL::previous() . "#step4");

            }

            $appData->district_id = $data['district_id'];



//            if ($request->get('actionBtn') == "draft") {
//                // TODO:: if draft then status
//            } else {
//                // TODO:: if draft then status
//            }
            $appData->save();
            // Particulars Body submitted
            RjscWitness::where('rjsc_nr_app_id',$app_id)->delete();

            foreach ($request->get('name') as $key => $dat) {
                $objWitness = new RjscWitness();
                $objWitness->rjsc_nr_app_id = $app_id;

                if (CommonFunction::asciiCharCheck($data['name'][$key])){
                    $objWitness->name = $data['name'][$key];
                }else{

                    Session::flash('error', 'non-ASCII Characters found in name [GI-1002]');
                    return Redirect::to(URL::previous() . "#step4");

                }

                if (CommonFunction::asciiCharCheck($data['address'][$key])){
                    $objWitness->address = $data['address'][$key];
                }else{

                    Session::flash('error', 'non-ASCII Characters found in address [GI-1002]');
                    return Redirect::to(URL::previous() . "#step4");

                }

                $objWitness->phone = $data['phone'][$key];
                $objWitness->national_id = $data['national_id'][$key];
                $objWitness->witness_flag = $key + 1;
                $objWitness->save();
            }

            if($app_id){
                $sequence = NewReg::find($app_id);
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
            dd($e->getMessage(), $e->getLine());
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
        $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->first(['name', 'position_id', 'address', 'district_id']);
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
            dd($e->getMessage(), $e->getLine());
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
        if ($request->get('actionBtn') != "draft" && $processData->status_id != 2) { // when application submitted but not as re-submitted
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

        if ($request->get('actionBtn') == "saveAndContinue") {
            $noofclause=AoaInfo::where('rjsc_nr_app_id', $app_id)->count();
            if($noofclause>=25){
                $sequence = NewReg::find($app_id);
                $sequence->sequence = 14;
                $sequence->save();
                Session::put('sequence', $sequence->sequence);
                return Redirect::to(URL::previous() . "#step14");

            }else{
                Session::flash('error', "Need to Add Minimum 25(Twenty-five) Clause.");
                return Redirect::to(URL::previous() . "#step13");
            }

        }

        DB::beginTransaction();

        $Info = NewReg::where('id', $app_id)->first();
        $entity_type_id = intval($Info->entity_type_id);
        $All_pdf_forms = Rjsc_NrForms::where('status', '1')->where('is_extra','!=', '1')->where(function ($query) use ($entity_type_id) {
            if ($entity_type_id == 1) {
                $query->where('type', '=', 'Private');
            } elseif ($entity_type_id == 2) {
                $query->where('type', '=', 'Public');
            }
        })->count();


        $upload_pdf_forms = RjscNrSubmitForms::where('app_id', $app_id)
        ->where('is_extra','!=', '0')  //0 means is extra 1, 1 means is extra 0
            ->whereNotNull('doc')
            ->count();

        if ($All_pdf_forms != $upload_pdf_forms){
            DB::rollback();
            Session::flash('error', "Sorry! have to upload all files");
//            return redirect()->back()->withInput();
            return Redirect::to(URL::previous() . "#step14");
        }
        elseif($Info->doc_status != 2) {
            $Info->doc_status = 1;
            $Info->save();
        }

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
            $jsondata=$this->getData($app_id);
            $requestdata=RjscNrRequest::where('ref_id',$app_id)->first();
            if (count($requestdata)<=0){
            $requestdata = new RjscNrRequest();

            $requestdata->ref_id=$app_id;
            $requestdata->request=$jsondata;


            $processTypeId = $this->process_type_id;
                $servertype = '';
                if (env('server_type', 'live') == 'live') {
                    $servertype = '';
                }else{
                    $servertype = '';
                }
            $trackingPrefix = "NR$servertype-" . date("dMY") . '-';

            DB::statement("update  process_list, process_list as table2  SET process_list.tracking_no=(
                            select concat('$trackingPrefix',
                                    LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                          ) as tracking_no
                             from (select * from process_list ) as table2
                             where table2.process_type_id ='$processTypeId' and table2.id!='$processData->id' and table2.tracking_no like '$trackingPrefix%'
                        )
                      where process_list.id='$processData->id' and table2.id='$processData->id'");


            $nr_data = NewReg::where('id',$app_id)->first(['submission_no']);
            $trackingid = CommonFunction::getTrackingNoByProcessId($processData->id);
//            $requestdata->process_type_id = $this->process_type_id;
//            $requestdata->tracking_no = $trackingid;
//            $requestdata->licence_application_id = $nr_data->submission_no;
            $requestdata->save();

            $processData->process_type_id = $this->process_type_id;
            $processData->save();
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

        return redirect('licence-applications/company-registration/applicationstatus/'.Encryption::encodeId($app_id));


    }

    public  function checkstatus($app_id){

        return view("NewReg::new-reg.wait-for-payment",compact('app_id'));

    }
    public  function applicationstatus(Request $request){
        $app_id = Encryption::decodeId($request->appid);
//            $processstatus = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id])->get(['status_id']);
//            return response()->json($processstatus);
//
//        $application_id = Encryption::decodeId($request->enc_app_id);

        $rjscData = RjscNrRequest::where('ref_id',$app_id)->orderBy('id', 'desc')->first();

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
        $docstatus = RjscNrDoc::where(['ref_id' => $app_id])
            ->orderBy('id', 'desc')
            ->get(['status']);
        $rowcount=count($docstatus);
        $docdata['status']=$docstatus;
        $docdata['count']=$rowcount;

        return response()->json($docdata);

    }
    public  function checkpaymentstatus(Request $request){
        $app_id = Encryption::decodeId($request->appid);


        $paymentstatus = RjscNrPaymentInfo::where(['ref_id' => $app_id])
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

        try {


            $stackholderDistibutionType=Config('payment.spg_settings_stack_holder.stackholder_distribution_type');
            $appId = Encryption::decodeId($request->get('enc_app_id'));

            $NRRecordRjsc = RjscNrRequest::where('ref_id', $appId)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->first();

            $NRPaymentInfo = RjscNrPaymentInfo::where('ref_id', $appId)
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->first();


            $appInfo = NewReg::find($appId);

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
//            dd($payment_config);
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
                    'distribution_type',
                ])->toArray();


//            $jsonData = '{"rjsc_fee":600,"rjsc_fees_ac_no":"0002601020864","rjsc_fees_ac_name":"RJSC FEES ACCOUNT-1-1735-0000-1816","rjsc_vat":90,"rjsc_vat_ac_no":"0117202000919","rjsc_vat_ac_name":"RJSC VAT ACC-1-1133-0010-0311","rjsc_duty":10,"rjsc_duty_ac_no":"0117202000918","rjsc_duty_ac_name":"RJSC STAMP DUTY ACC-1-1101-0020-1321"}';
            $jsonData = json_decode($NRPaymentInfo->response);
            $jsonData= $jsonData->msg;
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


            $rjscVatAccount3 = array(
                'receiver_account_no' => $jsonData->rjsc_duty_ac_no,
                'amount' => $jsonData->rjsc_duty,
                'distribution_type' => $stackholderDistibutionType
            );

            $stackholderMappingInfo[] = $rjscVatAccount3;


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
            DB::beginTransaction();
            $paymentInfo = SonaliPaymentStackHolders::firstOrNew(['app_id' => $appId, 'process_type_id' => $this->process_type_id, 'payment_config_id' => $payment_config->id]);
            $paymentInfo->payment_config_id = $payment_config->id;
            $paymentInfo->app_id = $appId;
            $paymentInfo->process_type_id = $this->process_type_id;
            $paymentInfo->app_tracking_no = '';
            $paymentInfo->receiver_ac_no = $account_numbers;
            $paymentInfo->payment_category_id = $payment_config->payment_category_id;
            $paymentInfo->ref_tran_no = $NRPaymentInfo->submission_no."/2/01";
            $paymentInfo->pay_amount = $pay_amount;
            $paymentInfo->total_amount = ($paymentInfo->pay_amount + $paymentInfo->vat_on_pay_amount);
            $paymentInfo->contact_name = CommonFunction::getUserFullName();
            $paymentInfo->contact_email = Auth::user()->user_email;
            $paymentInfo->contact_no = Auth::user()->user_phone;
            $paymentInfo->address = Auth::user()->road_no;
            $paymentInfo->sl_no = 1; // Always 1
            $paymentInsert = $paymentInfo->save();
            NewReg::where('id', $appId)->update(['gf_payment_id' => $paymentInfo->id]);
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

        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getLine()) . "[VRA-1025]");
            return redirect()->back()->withInput();
        }
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
            $applicantEmailPhone = Users::where('id', Auth::user()->id)
                ->get(['user_email', 'user_phone']);

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
                $processData->status_id = 16;
                $processData->desk_id = 7;
                $processData->read_status = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

            }

            $processData->save();


            SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                ->where('process_type_id', $this->process_type_id)
                ->update(['app_tracking_no' => $appInfo['tracking_no']]);

            $NRPaymentRjsc = RjscNrPaymentInfo::where('ref_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first();


            $verification_response = json_decode($paymentInfo->verification_response);
            //dd();

            $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
            $lopt_url = $spg_conf->value;
            $userName=  env('spg_user_id');
            $password= env('spg_password');
            $ownerCode= env('st_code');
            $referenceDate= $paymentInfo->payment_date;
            $requiestNo= $paymentInfo->request_id;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "$lopt_url",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => FALSE,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

//dd($response);

            $account_num=$spg_conf->details;
//            $data=$response;
            $data1=json_decode($response);
            $data2=json_decode($data1);
            $rData0['nc_save_id'] = $NRPaymentRjsc->submission_no;
            $rData0['nc_request_by'] = $verification_response->ApplicantName;
            $rData0['remarks'] = "";
            $rData0['branch_code'] = $verification_response->BrCode;
//dd($data2);

            foreach ($data2 as $key=>$value){
                if($value->TranAccount!=$account_num){
                    $rData0['account_info'][] =[
                        'account_no'=>$value->TranAccount,
                        'particulars'=>$value->ReferenceNo,
                        'balance'=>0,
                        'deposit'=>$value->TranAmount,
                        'tran_date'=>$value->TransactionDate,
                        'tran_id'=>$value->TransactionId
                    ];
                }
            }


            $ncRjscPyament = new RjscNrPayment();
            $ncRjscPyament->request = json_encode($rData0);
            $ncRjscPyament->ref_id = $paymentInfo->app_id;
            $ncRjscPyament->tracking_no = $processData->tracking_no;
            $ncRjscPyament->save();

            DB::commit();
//            dd(json_encode($rData0));
            Session::flash('success', 'Payment submitted successfully');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine());
            DB::rollback();
            Session::flash('error', 'Something went wrong!, application not updated after payment.');
            return redirect('licence-application/list/' . Encryption::encodeId($this->process_type_id));
        }
    }


    public function afterCounterPayment($payment_id)
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
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_list.*'
                ]);
            $applicantEmailPhone = Users::where('id', Auth::user()->id)
                ->get(['user_email', 'user_phone']);
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

                $processData->status_id = 16;
                $processData->desk_id = 7;
                $processData->read_status = 0;

                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));

                $processData->process_desc = 'Counter Payment Confirm';


                $paymentInfo->payment_status = 1;
                $paymentInfo->save();

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);


                $verification_response = json_decode($paymentInfo->offline_verify_response);

                SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);


                $NRPaymentRjsc = RjscNrPaymentInfo::where('ref_id', $paymentInfo->app_id)->orderBy('id', 'DESC')->first();


                $spg_conf = Configuration::where('caption', 'spg_TransactionDetails')->first();
                $lopt_url = $spg_conf->value;
                $userName=  env('spg_user_id');
                $password= env('spg_password');
                $ownerCode= env('st_code');
                $referenceDate= $paymentInfo->payment_date;
                $requiestNo= $paymentInfo->request_id;

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "$lopt_url",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_SSL_VERIFYPEER => FALSE,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => "{\n\"AccessUser\":{\n\"userName\":\"$userName\",\n\"password\":\"$password\"\n},\n\"OwnerCode\":\"$ownerCode\",\n\"ReferenceDate\":\"$referenceDate\",\n\"RequiestNo\":\"$requiestNo\",\n\"isEncPwd\":true\n}",
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json"
                    ),
                ));

                $response = curl_exec($curl);

                $err = curl_error($curl);

                curl_close($curl);

//dd($response);

                $account_num=$spg_conf->details;
//            $data=$response;
                $data1=json_decode($response);
                $data2=json_decode($data1);
                $rData0['nc_save_id'] =  $NRPaymentRjsc->submission_no;
                $rData0['nc_request_by'] = Auth::user()->user_full_name;
                $rData0['remarks'] = "";
                $rData0['branch_code'] = $verification_response->BrCode;
//dd($data2);

                foreach ($data2 as $key=>$value){

                    if($value->TranAccount!=$account_num){
                        $rData0['account_info'][] =[
                            'account_no'=>$value->TranAccount,
                            'particulars'=>$value->ReferenceNo,
                            'balance'=>0,
                            'deposit'=>$value->TranAmount,
                            'tran_date'=>$value->TransactionDate,
                            'tran_id'=>$value->TransactionId
                        ];

                    }



                }



                $ncRjscPyament = new RjscNrPayment();
                $ncRjscPyament->request = json_encode($rData0);
                $ncRjscPyament->ref_id = $paymentInfo->app_id;
                $ncRjscPyament->tracking_no = $processData->tracking_no;
                $ncRjscPyament->save();

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



                // App Tracking ID store in Payment table
                SonaliPaymentStackHolders::where('app_id', $appInfo['app_id'])
                    ->where('process_type_id', $this->process_type_id)
                    ->update(['app_tracking_no' => $appInfo['tracking_no']]);

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

        $generalinformation=NewReg::where('id',$app_id)->first();
        $qualific_shares=RjscNrQualificShare::where('rjsc_nr_app_id',$app_id)->first();
        $particular_body=RjscNrParticularBody::where('rjsc_nr_app_id',$app_id)->get();
        $witnesslist=RjscWitness::where('rjsc_nr_app_id',$app_id)->get();
        $directorslist=ListSubscriber::where('app_id',$app_id)
            ->where('is_director',1)->get();
        $dmid=RjscMoaDefaultClause::where('status',1)
            ->first(['default_clause_id']);

        $objectives=Objective::where('rjsc_nr_app_id',$app_id)->get();
        $nrAoaClause = AoaInfo::where('rjsc_nr_app_id',$app_id)->get();
        $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->first();
        //dd(count($directorslist));
        //dd($qualific_shares);
        $paymentdata=NcRjscPayConfirm::where('licence_application_id',$generalinformation->submission_no)
            ->where('status', 1)->first();
        // dd(json_decode($paymentdata->response,true));

        $ossappid1="";
        $ossappid2="";

        $certificate = $generalinformation->clearence_letter_no;


        $submissionVerifyData = RjscSubmissionVerify::where('submission_no', $generalinformation->submission_no)
            ->where('clearence_letter_no', $certificate)
            ->orderBy('id','desc')->first();

        if (count($submissionVerifyData) <= 0){
            Session::flash('error',"Submission No. or Certificate No. Not verified from RJSC.");
            return Redirect::to(URL::previous());
        }


        if (count($paymentdata)>0){ // oss nc done
            $ossappid1=$paymentdata->tracking_no;
            $ossappid2=$paymentdata->tracking_no;
        }else{
            $ossappid1=null;
            $ossappid2=$submissionVerifyData->oss_app_id;
        }



        $rData0['nc_cert_no'] = $certificate;
        $rData0['nc_submission_no'] = $generalinformation->submission_no;
        $rData0['oss_app_id'] = $ossappid1;
        $rData['generalInfo'] =[];
        if (!empty($generalinformation)) {
            $rData['generalInfo'] = array(
                'entityName' => $submissionVerifyData->response_company_name,
                'entityType' => $generalinformation->entity_type_id,
                'liabilityType' => $generalinformation->liability_type_id,
                'entityAddress' => $generalinformation->address_entity,
                'entityEmail' => $generalinformation->entity_email_address,
                'entityDistrict' => $generalinformation->entity_district_id,
                'mainBusinessObjective' => $generalinformation->main_business_objective,
                'businessSector' => $generalinformation->business_sector_id,
                'businessSubSector' => $generalinformation->business_sub_sector_id,
                'authorizedCapital' => $generalinformation->authorize_capital,
                'numberOfShare' => $generalinformation->number_shares,
                'valueOfEachShare' => $generalinformation->value_of_each_share,
                'minimumNumberOfDirectors' => $generalinformation->minimum_no_of_directors,
                'maximumNumberOfDirectors' => $generalinformation->maximum_no_of_directors,
                'quorumOfAgm' => $generalinformation->quorum_agm_egm_num,
                'quorumOfAgmInWord' => $generalinformation->quorum_agm_egm_word,
                'quorumOfBoardOfDirectorsMeeting' => $generalinformation->q_directors_meeting_num,
                'quorumOfBoardOfDirectorsMeetingInWord' => $generalinformation->q_directors_meeting_word,
                'durationChairmanship' => $generalinformation->duration_of_chairmanship,
                'durationMd' => $generalinformation->duration_managing_directorship
            );
        }

        $rData['qualificationShareEachDirector']=[];
        if (!empty($generalinformation)){
            $rData['qualificationShareEachDirector'] = array(
                'numberOfQualificationShare' => $generalinformation->no_of_qualification_share,
                'valueOfEachShare' => $generalinformation->value_of_qualification_share,
                'nameOfWitness' => $generalinformation->agreement_witness_name,
                'witnessAddress' => $generalinformation->agreement_witness_address,
                'witnessDistrict' =>$generalinformation->agreement_witness_district_id
            );
        }
        // dd(count($directorslist));
        $rData['listOfDirectors'] =[];

        foreach ($directorslist as $key=>$director){
            $repsentserialno="";
            if($director->nominating_entity_id!="") {
                $representedSerial = RjscNrParticularBody::where('id', $director->nominating_entity_id)
                    ->first();
                if(!empty($representedSerial)){
                    $repsentserialno=$representedSerial->serial_number;
                }
            }

            $dob = null;
            if ($director->dob == null || $director->dob == 'NULL' || $director->dob == '' || $director->dob == '0000-00-00'){
                $dob = null;
            }else{
                $dob = date("d/m/Y", strtotime($director->dob) );
            }

            $appointment_date = null;
            if ($director->appointment_date == null || $director->appointment_date == 'NULL' || $director->appointment_date == '' || $director->appointment_date == '0000-00-00'){
                $appointment_date = null;
            }else{
                $appointment_date = date("d/m/Y", strtotime($director->appointment_date) );
            }

            $rData['listOfDirectors'][] =array(
                'name' => $director->corporation_body_name,
                'formarName' => $director->former_individual_name,
                'fathersName' => $director->father_name,
                'mothersName' => $director->mother_name,
                'residentialAddress' => $director->usual_residential_address,
                'residentialDistrict' => $director->usual_residential_district_id,
                'permanentAddress' =>$director->permanent_address,
                'permanentDistrict' =>$director->permanent_address_district_id,
                'mobile' => $director->mobile,
                'email' => $director->email,
                'nationality' =>$director->present_nationality_id,
                'otherNationality' => $director->original_nationality_id,
                'dateOfBirth' => $dob,
                'tin' => ($director->tin_no != 0) ? $director->tin_no : '',
                'position' => $director->position,
                'signingAgreementOfShare' => $director->signing_qualification_share_agreement,
                'nominatingEntity' => $director->nominating_entity_id,
                'dateOfAppoinment' =>$appointment_date,
                'otherBusinessOccupation' => $director->other_occupation,
                'directorshipInOtherCompany' =>$director->directorship_in_other_company,
                'numberOfSubscribedShares' => $director->no_of_subscribed_shares,
                'nationalId' => $director->national_id_passport_no,
                'representedSerialNo' => $repsentserialno
            );

        }

        $rData['particularsOfCorporateSubscriberList']=[];
        if(count($particular_body)>0){
            foreach ($particular_body as $key=>$particular){
                $rData['particularsOfCorporateSubscriberList'][] = array(
                    'nameOfCorporateBody' =>$particular->name_corporation_body,
                    'representedBy' => $particular->represented_by,
                    'address' => $particular->address,
                    'numberOfSubscribedShare' => $particular->no_subscribed_shares,
                    'district' => $particular->district_id,
                    'serialId' => $particular->serial_number
                );

            }
        }
        if(count($witnesslist)>0){

            foreach ($witnesslist as $key=>$witness){
                $rData['witnessList'][] = array(
                    'name' => $witness->name,
                    'address' => $witness->address,
                    'phone' => $witness->phone,
                    'nationalId' => $witness->national_id
                );

            }
        }
        if(!empty($witnessDataFiled)){
            $rData['filingBy'] = array(
                'name' => $witnessDataFiled->name,
                'position' => $witnessDataFiled->position_id,
                'address' => $witnessDataFiled->address,
                'fillingDistrict' =>$witnessDataFiled->district_id
            );
        }
        if(!empty($generalinformation)){
            $rData['registrationSignedBy'] = array(
                'name' => $generalinformation->declaration_name,
                'position' => $generalinformation->declaration_position_id,
                'organization' => $generalinformation->declaration_organization,
                'address' => $generalinformation->declaration_address,
                'signedByDistrict' => $generalinformation->declaration_district_id
            );
        }
        if(count($nrAoaClause)>0){
            foreach ($nrAoaClause as $key=>$clause){
                $rData['aoaClauseList'][] = array(
                    'clauseTitle' => $clause->clause_title_id,
                    'clauseDetails' =>$clause->clause_for_rjsc,
                    'serialId' => $clause->sequence
                );


            }

        }
        if(count($objectives)>0){

            foreach ($objectives as $key=>$objective){
                $rData['moaObjectiveList'][] = array(
                    'serialNumber' => $objective->serial_number,
                    'objectiveDetail' => $objective->objective,
                    'dmId' => $dmid->default_clause_id //rjsc_nr_default_clause_list
                );

            }

        }

        $rData['archiveDocInfo'] = array(
            'documentId' => "49",
            'moaPages' => $generalinformation->memorandum_asso_no,
            'aoaPages' => $generalinformation->article_asso_no
        );

        $rData['requestInfo'] = array(
            'entryBy' => '',
            'remoteAddress' => '',
            'ossApplicationId' => $ossappid2
        );
        $rReq = $rData0;
        $rReq['req_json_data']['registrationData'] = $rData;
        return json_encode($rReq);
    }

    public function getData2($app_id){

        //$app_id='w-Uh6Xx-M7kFe09mpl_b49VTNvstF3pRkGuEsnMdty0';
        //$app_id=284;
        // dd($app_id);

        $generalinformation=NewReg::where('id',$app_id)->first();
        $qualific_shares=RjscNrQualificShare::where('rjsc_nr_app_id',$app_id)->first();
        $particular_body=RjscNrParticularBody::where('rjsc_nr_app_id',$app_id)->get();
        $witnesslist=RjscWitness::where('rjsc_nr_app_id',$app_id)->get();
        $directorslist=ListSubscriber::where('app_id',$app_id)
            ->where('is_director',1)->get();
        $dmid=RjscMoaDefaultClause::where('status',1)
            ->first(['default_clause_id']);

        $objectives=Objective::where('rjsc_nr_app_id',$app_id)->get();
        $nrAoaClause = AoaInfo::where('rjsc_nr_app_id',$app_id)->get();
        $witnessDataFiled = RjscWitnessFilledBy::where('rjsc_nr_app_id', $app_id)->first();
        //dd(count($directorslist));
        //dd($qualific_shares);
        $paymentdata=NcRjscPayConfirm::where('licence_application_id',$generalinformation->submission_no)
            ->where('status', 1)->first();
        // dd(json_decode($paymentdata->response,true));

        $ossappid1="";
        $ossappid2="";

        $certificate = $generalinformation->clearence_letter_no;


        $submissionVerifyData = RjscSubmissionVerify::where('submission_no', $generalinformation->submission_no)
            ->where('clearence_letter_no', $certificate)
            ->orderBy('id','desc')->first();

        if (count($submissionVerifyData) <= 0){
            Session::flash('error',"Submission No. or Certificate No. Not verified from RJSC.");
            return Redirect::to(URL::previous());
        }


        if (count($paymentdata)>0){ // oss nc done
            $ossappid1=$paymentdata->tracking_no;
            $ossappid2=$paymentdata->tracking_no;
        }else{
            $ossappid1=null;
            $ossappid2=$submissionVerifyData->oss_app_id;
        }



        $rData0['nc_cert_no'] = $certificate;
        $rData0['nc_submission_no'] = $generalinformation->submission_no;
        $rData0['oss_app_id'] = $ossappid1;
        $rData['generalInfo'] =[];
        if (!empty($generalinformation)) {
            $rData['generalInfo'] = array(
                'entityName' => $submissionVerifyData->response_company_name,
                'entityType' => $generalinformation->entity_type_id,
                'liabilityType' => $generalinformation->liability_type_id,
                'entityAddress' => $generalinformation->address_entity,
                'entityEmail' => $generalinformation->entity_email_address,
                'entityDistrict' => $generalinformation->entity_district_id,
                'mainBusinessObjective' => $generalinformation->main_business_objective,
                'businessSector' => $generalinformation->business_sector_id,
                'businessSubSector' => $generalinformation->business_sub_sector_id,
                'authorizedCapital' => $generalinformation->authorize_capital,
                'numberOfShare' => $generalinformation->number_shares,
                'valueOfEachShare' => $generalinformation->value_of_each_share,
                'minimumNumberOfDirectors' => $generalinformation->minimum_no_of_directors,
                'maximumNumberOfDirectors' => $generalinformation->maximum_no_of_directors,
                'quorumOfAgm' => $generalinformation->quorum_agm_egm_num,
                'quorumOfAgmInWord' => $generalinformation->quorum_agm_egm_word,
                'quorumOfBoardOfDirectorsMeeting' => $generalinformation->q_directors_meeting_num,
                'quorumOfBoardOfDirectorsMeetingInWord' => $generalinformation->q_directors_meeting_word,
                'durationChairmanship' => $generalinformation->duration_of_chairmanship,
                'durationMd' => $generalinformation->duration_managing_directorship
            );
        }

        $rData['qualificationShareEachDirector']=[];
        if (!empty($generalinformation)){
            $rData['qualificationShareEachDirector'] = array(
                'numberOfQualificationShare' => $generalinformation->no_of_qualification_share,
                'valueOfEachShare' => $generalinformation->value_of_qualification_share,
                'nameOfWitness' => $generalinformation->agreement_witness_name,
                'witnessAddress' => $generalinformation->agreement_witness_address,
                'witnessDistrict' =>$generalinformation->agreement_witness_district_id
            );
        }
        // dd(count($directorslist));
        $rData['listOfDirectors'] =[];

        foreach ($directorslist as $key=>$director){
            $repsentserialno="";
            if($director->nominating_entity_id!="") {
                $representedSerial = RjscNrParticularBody::where('id', $director->nominating_entity_id)
                    ->first();
                if(!empty($representedSerial)){
                    $repsentserialno=$representedSerial->serial_number;
                }
            }

            $dob = null;
            if ($director->dob == null || $director->dob == 'NULL' || $director->dob == '' || $director->dob == '0000-00-00'){
                $dob = null;
            }else{
                $dob = date("d/m/Y", strtotime($director->dob) );
            }

            $appointment_date = null;
            if ($director->appointment_date == null || $director->appointment_date == 'NULL' || $director->appointment_date == '' || $director->appointment_date == '0000-00-00'){
                $appointment_date = null;
            }else{
                $appointment_date = date("d/m/Y", strtotime($director->appointment_date) );
            }

            $rData['listOfDirectors'][] =array(
                'name' => $director->corporation_body_name,
                'formarName' => $director->former_individual_name,
                'fathersName' => $director->father_name,
                'mothersName' => $director->mother_name,
                'residentialAddress' => $director->usual_residential_address,
                'residentialDistrict' => $director->usual_residential_district_id,
                'permanentAddress' =>$director->permanent_address,
                'permanentDistrict' =>$director->permanent_address_district_id,
                'mobile' => $director->mobile,
                'email' => $director->email,
                'nationality' =>$director->present_nationality_id,
                'otherNationality' => $director->original_nationality_id,
                'dateOfBirth' => $dob,
                'tin' => $director->tin_no,
                'position' => $director->position,
                'signingAgreementOfShare' => $director->signing_qualification_share_agreement,
                'nominatingEntity' => $director->nominating_entity_id,
                'dateOfAppoinment' =>$appointment_date,
                'otherBusinessOccupation' => $director->other_occupation,
                'directorshipInOtherCompany' =>$director->directorship_in_other_company,
                'numberOfSubscribedShares' => $director->no_of_subscribed_shares,
                'nationalId' => $director->national_id_passport_no,
                'representedSerialNo' => $repsentserialno
            );

        }

        $rData['particularsOfCorporateSubscriberList']=[];
        if(count($particular_body)>0){
            foreach ($particular_body as $key=>$particular){
                $rData['particularsOfCorporateSubscriberList'][] = array(
                    'nameOfCorporateBody' =>$particular->name_corporation_body,
                    'representedBy' => $particular->represented_by,
                    'address' => $particular->address,
                    'numberOfSubscribedShare' => $particular->no_subscribed_shares,
                    'district' => $particular->district_id,
                    'serialId' => $particular->serial_number
                );

            }
        }
        if(count($witnesslist)>0){

            foreach ($witnesslist as $key=>$witness){
                $rData['witnessList'][] = array(
                    'name' => $witness->name,
                    'address' => $witness->address,
                    'phone' => $witness->phone,
                    'nationalId' => $witness->national_id
                );

            }
        }
        if(!empty($witnessDataFiled)){
            $rData['filingBy'] = array(
                'name' => $witnessDataFiled->name,
                'position' => $witnessDataFiled->position_id,
                'address' => $witnessDataFiled->address,
                'fillingDistrict' =>$witnessDataFiled->district_id
            );
        }
        if(!empty($generalinformation)){
            $rData['registrationSignedBy'] = array(
                'name' => $generalinformation->declaration_name,
                'position' => $generalinformation->declaration_position_id,
                'organization' => $generalinformation->declaration_organization,
                'address' => $generalinformation->declaration_address,
                'signedByDistrict' => $generalinformation->declaration_district_id
            );
        }
        if(count($nrAoaClause)>0){
            foreach ($nrAoaClause as $key=>$clause){
                $rData['aoaClauseList'][] = array(
                    'clauseTitle' => $clause->clause_title_id,
                    'clauseDetails' =>$clause->clause_for_rjsc,
                    'serialId' => $clause->sequence
                );


            }

        }
        if(count($objectives)>0){

            foreach ($objectives as $key=>$objective){
                $rData['moaObjectiveList'][] = array(
                    'serialNumber' => $objective->serial_number,
                    'objectiveDetail' => $objective->objective,
                    'dmId' => $dmid->default_clause_id //rjsc_nr_default_clause_list
                );

            }

        }

        $rData['archiveDocInfo'] = array(
            'documentId' => "49",
            'moaPages' => $generalinformation->memorandum_asso_no,
            'aoaPages' => $generalinformation->article_asso_no
        );

        $rData['requestInfo'] = array(
            'entryBy' => '',
            'remoteAddress' => '',
            'ossApplicationId' => $ossappid2
        );
        $rReq = $rData0;
        $rReq['req_json_data']['registrationData'] = $rData;
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
            dd($e->getMessage(), $e->getLine());
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
                'status'=> 0,
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
