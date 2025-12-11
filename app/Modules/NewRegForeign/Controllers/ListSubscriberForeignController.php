<?php

namespace App\Modules\NewRegForeign\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewRegForeign\Models\ListSubscriber;
use App\Modules\NewRegForeign\Models\NewRegForeign;
use App\Modules\NewReg\Models\RjscOffice;
use App\Modules\NewReg\Models\RjscArea;
use App\Modules\NewReg\Models\RjscSector;
use App\Modules\NewRegForeign\Models\RjscCompanyPosition;
use App\Modules\NewRegForeign\Models\RjscTinVerify;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class ListSubscriberForeignController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        if(Session::has('lang')){
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 111;
        $this->aclName = 'NewReg';
    }

    public function appStore(Request $request){
//        dd($request->all());
        $sub_id = $request->get('sub_id');
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
//        $company_id = Auth::user()->company_ids;
//        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
//            Session::flash('error', "Sorry! Your selected company is not eligible or you have no approved Basic Information application.");
//            return redirect()->back();
//        }

        // Validation Rules when application submitted
        $rules = [];
        $messages = [];

        $rules['corporation_body_name'] = 'required';

        $this->validate($request, $rules, $messages);

        try {


            DB::beginTransaction();

            if ($request->get('sub_id')) {
                $subscriberData = ListSubscriber::find($sub_id);
            } else {
                $subscriberData = new ListSubscriber();
            }

            $total = ListSubscriber::where('app_id', Encryption::decodeId($request->get('app_id')))->orderBy('id', 'desc')->first();
            $company_type=NewRegForeign::where('id',$app_id)->first(['entity_type_id']);

//            $is_director=RjscCompanyPosition::where('rjsc_company_type_rjsc_id', $company_type->entity_type_id)
//                ->where('rjsc_id', $request->get('position'))->get(['is_director']);
//            if (count($is_director)<=0){
//                Session::flash('error','Position not matched.');
//                return Redirect::to(URL::previous() . "#step3");
//            }
            $subscriberData->app_id = Encryption::decodeId($request->get('app_id'));

            if (count($total) != 0){
                $subscriberData->serial_number = $total->serial_number+1;
            }else{
                $subscriberData->serial_number = 1;
            }

            if (CommonFunction::asciiCharCheck($request->get('corporation_body_name'))){
                $subscriberData->corporation_body_name = $request->get('corporation_body_name');
            }else{
                Session::flash('error', 'non-ASCII Characters found in corporation_body_name [LS-1001]');
                return Redirect::to(URL::previous() . "#step3");
            }
            if (CommonFunction::asciiCharCheck($request->get('former_individual_name'))){
                $subscriberData->former_individual_name = $request->get('former_individual_name');
            }else{
                Session::flash('error', 'non-ASCII Characters found in former_individual_name [LS-1002]');
                return Redirect::to(URL::previous() . "#step3");
            }
            if (CommonFunction::asciiCharCheck($request->get('father_name'))){
                $subscriberData->father_name = $request->get('father_name');
            }else{
                Session::flash('error', 'non-ASCII Characters found in father_name [LS-1003]');
                return Redirect::to(URL::previous() . "#step3");
            }

            if (CommonFunction::asciiCharCheck($request->get('mother_name'))){
                $subscriberData->mother_name = $request->get('mother_name');
            }else{
                Session::flash('error', 'non-ASCII Characters found in mother_name [LS-1004]');
                return Redirect::to(URL::previous() . "#step3");
            }
            if (CommonFunction::asciiCharCheck($request->get('usual_residential_address'))){
                $subscriberData->usual_residential_address = $request->get('usual_residential_address');
            }else{
                Session::flash('error', 'non-ASCII Characters found in usual_residential_address [LS-1005]');
                return Redirect::to(URL::previous() . "#step3");
            }

            if (CommonFunction::asciiCharCheck($request->get('usual_residential_address'))){
                $subscriberData->usual_residential_address = $request->get('usual_residential_address');
            }else{
                Session::flash('error', 'non-ASCII Characters found in usual_residential_address [DU-1006]');
                return Redirect::to(URL::previous() . "#step3");
            }

            $usualResDist = explode('@', $request->get('usual_residential_district_id'));
            $subscriberData->usual_residential_district_id = !empty($usualResDist[0]) ? $usualResDist[0] : '';
            $subscriberData->usual_residential_district_name = !empty($usualResDist[1]) ? $usualResDist[1] : '';

            if (CommonFunction::asciiCharCheck($request->get('permanent_address'))){
                $subscriberData->permanent_address = $request->get('permanent_address');
            }else{
                Session::flash('error', 'non-ASCII Characters found in permanent_address [DU-1007]');
                return Redirect::to(URL::previous() . "#step3");
            }
            $perResDist = explode('@', $request->get('permanent_address_district_id'));
            $subscriberData->permanent_address_district_id = !empty($perResDist[0]) ? $perResDist[0] : '';
            $subscriberData->permanent_address_district_name = !empty($perResDist[1]) ? $perResDist[1] : '';

            $subscriberData->mobile = $request->get('mobile');
            $subscriberData->email = $request->get('email');

            $preNationality = explode('@', $request->get('present_nationality_id'));
            $subscriberData->present_nationality_id = !empty($preNationality[0]) ? $preNationality[0] : '';
            $subscriberData->present_nationality_name = !empty($preNationality[1]) ? $preNationality[1] : '';

            $orgNationality = explode('@', $request->get('original_nationality_id'));
            $subscriberData->original_nationality_id = !empty($orgNationality[0]) ? $orgNationality[0] : '';
            $subscriberData->original_nationality_name = !empty($orgNationality[1]) ? $orgNationality[1] : '';

//            $subscriberData->dob = $request->get('dob');
            $subscriberData->dob = (!empty($request->get('dob')) ? date('Y-m-d', strtotime($request->get('dob'))) : '');
            if (CommonFunction::asciiCharCheck($request->get('tin_no'))){
                $subscriberData->tin_no = $request->get('tin_no');
            }else{
                Session::flash('error', 'non-ASCII Characters found in tin_no [DU-1010]');
                return Redirect::to(URL::previous() . "#step3");
            }

            $position = explode('@', $request->get('position'));
            $subscriberData->position = !empty($position[0]) ? $position[0] : '';
            $subscriberData->position_name = !empty($position[1]) ? $position[1] : '';

//            $subscriberData->is_director =$is_director[0]->is_director;
            $subscriberData->signing_qualification_share_agreement =(int)$request->get('signing_qualification_share_agreement');
            $subscriberData->is_tin = $request->get('is_tin');
            $subscriberData->nominating_entity_id = $request->get('nominating_entity_id');
//            $subscriberData->appointment_date = $request->get('appointment_date');
            $subscriberData->appointment_date = (!empty($request->get('appointment_date')) ? date('Y-m-d', strtotime($request->get('appointment_date'))) : '');
            if (CommonFunction::asciiCharCheck($request->get('other_occupation'))){
                $subscriberData->other_occupation = $request->get('other_occupation');
            }else{
                Session::flash('error', 'non-ASCII Characters found in other_occupation [DU-1011]');
                return Redirect::to(URL::previous() . "#step3");
            }
            if (CommonFunction::asciiCharCheck($request->get('directorship_in_other_company'))){
                $subscriberData->directorship_in_other_company = $request->get('directorship_in_other_company');
            }else{
                Session::flash('error', 'non-ASCII Characters found in directorship_in_other_company [DU-1012]');
                return Redirect::to(URL::previous() . "#step3");
            }

            $subscriberData->no_of_subscribed_shares = $request->get('no_of_subscribed_shares');
            if (CommonFunction::asciiCharCheck($request->get('national_id_passport_no'))){
                $subscriberData->national_id_passport_no = $request->get('national_id_passport_no');
            }else{
                Session::flash('error', 'non-ASCII Characters found in national_id_passport_no [DU-1013]');
                return Redirect::to(URL::previous() . "#step3");
            }
            $subscriberData->national_id_passport_no = $request->get('national_id_passport_no');
            $signature = $request->file('digital_signature');
            $subscriberphoto = $request->file('subscriber_photo');
            if($request->get('is_tin') == 1){
                $tindata=RjscTinVerify::where('status',1)
                    ->whereNotNull('response')
                    ->where('tin',$request->get('tin_no'))
                    ->where('ref_id',Encryption::decodeId($request->get('app_id')))
                    ->first();
                if(empty($tindata)){
                    Session::flash('error','Given tin number not verified !!');
                    return Redirect::to(URL::previous() . "#step3");
                }
                $tindata_decoded=json_decode($tindata->response);
                $subscriberData->corporation_body_name=$tindata_decoded->assesName;
                $subscriberData->permanent_address =$tindata_decoded->address->present->addr;
                $subscriberData->permanent_address = $tindata_decoded->address->permanent->addr;
                if($tindata_decoded->nid != null || $tindata_decoded->nid !=""){
                    $subscriberData->national_id_passport_no = $tindata_decoded->nid;
                }else{
                    if($tindata_decoded->passportNumber != null){
                        $subscriberData->national_id_passport_no = $tindata_decoded->passportNumber;
                    }
                }

            }

            if(isset($signature) && !empty($signature) ){
               // dd('success');
                $fileMime = $signature->getClientMimeType();

               $validMimes = array('image/jpeg','image/jpg','image/png');
                $fileExt = array_search($fileMime, $validMimes, true);
                //dd($fileMime,$fileExt);

                if($fileExt > -1){
                    $size = $signature->getClientSize();
                    $filesize = ($size / 1000);
                    if($filesize < 2000){
                        $currentDate = Carbon::now()->toDateString();
                        $filename = $currentDate.'-'.uniqid().'.'.
                            $signature->getClientOriginalExtension();
                        if(!file_exists('rjsc_newreg_digital_signature')){
                            mkdir('rjsc_newreg_digital_signature',0777,true);
                        }
                        $signature->move('rjsc_newreg_digital_signature',$filename);
                        $subscriberData->digital_signature=$filename;
                    }else{
                        Session::flash('error','Upload copy file must lower than 2 MB');
                        return Redirect::to(URL::previous() . "#step3");
                    }
                }else{
                    Session::flash('error','Upload copy must be JPG or JPEG or PNG file');
                    return Redirect::to(URL::previous() . "#step3");
                }
            }
            if(isset($subscriberphoto) && !empty($subscriberphoto) ){
               // dd('success');
                $fileMime = $subscriberphoto->getClientMimeType();

                $validMimes = array('image/jpeg','image/png');
                $fileExt = array_search($fileMime, $validMimes, true);
                //dd($fileMime,$fileExt);

                if($fileExt > -1){
                    $size = $subscriberphoto->getClientSize();
                   $filesize = ($size / 1000);
                    if($filesize < 2000){
                        $currentDate = Carbon::now()->toDateString();
                       $filename = $currentDate.'-'.uniqid().'.'.
                            $subscriberphoto->getClientOriginalExtension();
                        if(!file_exists('subscriber_photo')){
                            mkdir('subscriber_photo',0777,true);
                        }
                        $subscriberphoto->move('subscriber_photo',$filename);
                       $subscriberData->subscriber_photo=$filename;
                    }else{
                        Session::flash('error','Upload copy file must lower than 2 MB');
                        return Redirect::to(URL::previous() . "#step3");
                    }
                }else{
                    Session::flash('error','Upload copy must be JPG or PNG file');
                   return Redirect::to(URL::previous() . "#step3");
                }
            }
            $subscriberData->save();
            if($request->has('tin_id')){
                $tin_verify_id = Encryption::decodeId($request->get('tin_id'));
                RjscTinVerify::where('id', $tin_verify_id)->update([
                    'nr_subscribers_id'=> $subscriberData->id
                ]);
            }
            $sequence=NewRegForeign::find(Encryption::decodeId($request->get('app_id')));
            $sequence->sequence = 4;




            $sequence->save();
            Session::put('sequence', $sequence->sequence);

            DB::commit();
            if ($request->get('sub_id')) {
                return Redirect::to(URL::previous() . "#step3");
            }

            if(isset($request->saveMode)){
                return Redirect::to(URL::previous() . "#step3");
            }

            return redirect('licence-applications/company-registration-foreign/add#step3');

        } catch (\Exception $e) {
            DB::rollback();
//            dd($e->getMessage().$e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }

    public function gotowitness(){
        Session::put('sequence', 4);
        return redirect('licence-applications/company-registration/add#step4');
    }

    public function gotowitnessEdit($app_id,$company_type){
        $app_id=Encryption::decodeId($app_id);
        $authorizedPersonData= ListSubscriber::where('app_id', $app_id)
            ->where('position',8)
            ->where('present_nationality_id',13)
            ->count();
//        dd($authorizedPersonData);
        $subscriberDataall= ListSubscriber::where('app_id', $app_id)
            ->count();

//        if($subscribeData == 0 && $subscriberDataall < 2){
        if($subscriberDataall < 2) {
            Session::flash('error', 'Need Minimum 1 Authorized Person and 1 Director/Manager');
            return Redirect::to(URL::previous() . "#step3");
        }elseif($authorizedPersonData == 0){
            Session::flash('error', 'Need Minimum 1 Authorized Person');
            return Redirect::to(URL::previous() . "#step3");
        } else{
            Session::put('sequence', 4);
            return Redirect::to(URL::previous() . "#step4");

         }

    }


    public function getData(Request $request)
    {
        $sub_id= $request->get('id');
        $subscribeData= ListSubscriber::where('id', $sub_id)->first();
        $app_id = $subscribeData->app_id;
        $result = RjscTinVerify::where('ref_id', $app_id)
            ->where('nr_subscribers_id', $sub_id)
            ->count();

        $response_code = 0;
        if($result > 0) {
            $status = RjscTinVerify::where('ref_id', $app_id)
                ->where('nr_subscribers_id', $sub_id)
                ->first(['status']);
            if ($status->status == 1){
                $response_code = 1;
            }
        }else{
            if($subscribeData->position !=null){
                $response_code = 1;
            }

        }
        return response()->json(['response_code'=>$response_code,'subscribeData'=>$subscribeData]);

//        return json_encode($subscribeData);
    }

    public function deleteData(Request $request){
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            return response()->json(['responseCode' => 0, 'html' => "You have no access right! Contact with system admin for more information"]);
        }
        $sub_id= $request->get('id');
        ListSubscriber::where('id', $sub_id)->delete();

        return 1;
    }

    public function storeTin(Request $request)
    {
        $app_id = Encryption::decodeId($request->get('app_id'));
        $tin_no = $request->get('tin_no');

        /*$tinData = RjscTinVerify::where('tin', $tin_no)
            ->where('ref_id',$app_id)
            ->orderBy('id','desc')->first();*/
        $tinData = ListSubscriber::where('tin_no', $tin_no)
            ->where('app_id',$app_id)
            ->orderBy('id','desc')->first();

        if (count($tinData) == 0){
            $tinDataVerify = RjscTinVerify::where('tin', $tin_no)
                ->where('ref_id',$app_id)
                ->orderBy('id','desc')->first();
            if (count($tinDataVerify) == 0){
                $tinData = RjscTinVerify::create([
                    'status'=> 0,
                    'ref_id' => $app_id,
                    'tin' => $tin_no,
                ]);
            }else{
                $tinData=$tinDataVerify;
            }
        }else{
            $message = 'This tin number already exists for this application.';
            $responseCode = 2;
            return response()->json(['responseCode' => $responseCode, 'message' => $message]);
        }
        $message = 'Your request has been locked on verify';
        $responseCode = 1;

        return response()->json(['responseCode' => $responseCode, 'message' => $message, 'tin_id' => Encryption::encodeId($tinData->id), 'enc_status' => Encryption::encodeId(0)]);


    }

    public function tinResponse(Request $request)
    {
        $rjsc_request_id = Encryption::decodeId($request->tin_id);

        $rjscData = RjscTinVerify::find($rjsc_request_id);

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

    public function checkTinStatus(Request $request)
    {
        $app_id = Encryption::decodeId($request->get('app_id'));
        $tin_no = $request->get('tin_no');
        RjscTinVerify::create([
            'status'=> 0,
            'ref_id' => $app_id,
            'tin' => $tin_no,
        ]);


    }
    public function checkisdirector(Request $request)
    {
        $company_type=Encryption::decodeId($request->get('company_type'));
       
        $position=$request->get('position_id');
       
        $is_director=RjscCompanyPosition::where('rjsc_company_type_rjsc_id',$company_type)
            ->where('rjsc_id',$position)
            ->where('is_director',1)->count();
        
        $director_response=0;
        if($is_director>0){
            $director_response=1;
        }
        return response()->json(['director_response'=>$director_response]);

    }
}
