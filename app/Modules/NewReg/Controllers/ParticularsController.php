<?php

namespace App\Modules\NewReg\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewReg\Models\NewReg;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Modules\NewReg\Models\RjscNrParticularBody;
use App\Modules\NewReg\Models\ListSubscriber;
use Illuminate\Support\Facades\URL;

class ParticularsController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        if(Session::has('lang')){
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 104;
        $this->aclName = 'NewReg';
    }

    public function rjscPartiularStore(Request $request)
    {

        // Set permission mode and check ACL
        $encodedAppId = Session::get('current_app_id');
        $app_id = (!empty($encodedAppId) ? Encryption::decodeId($encodedAppId) : '');
        $mode = (!empty($app_id) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
//        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
//            Session::flash('error', "Sorry! Your selected company is not eligible or you have no approved Basic Information application.");
//            return redirect()->back();
//        }

        // Validation Rules when application submitted
        $rules = [];
        $messages = [];
        if ($request->action_btn != 'draft'){
            $rules['no_qualific_share'] = 'required';
            $rules['value_of_each_share'] = 'required';
        }
        $this->validate($request, $rules,$messages);

        try {
            DB::beginTransaction();
            if ($encodedAppId) {
                $appData = NewReg::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new NewReg();
//                dd($appData);
                $processData = new ProcessList();
            }

           // dd($app_id);

            $appData->no_of_qualification_share = $request->no_qualific_share;
            $appData->value_of_qualification_share = $request->value_of_each_share;

            if (CommonFunction::asciiCharCheck($request->agreement_witness_name)){
                $appData->agreement_witness_name = $request->agreement_witness_name;
            }else{

                Session::flash('error', 'non-ASCII Characters found in agreement_witness_name [PI-1004]');
                return Redirect::to(URL::previous() . "#step2");

            }

            if (CommonFunction::asciiCharCheck($request->agreement_witness_address)){
                $appData->agreement_witness_address = $request->agreement_witness_address;
            }else{

                Session::flash('error', 'non-ASCII Characters found in agreement_witness_address [PI-1005]');
                return Redirect::to(URL::previous() . "#step2");

            }

            $appData->agreement_witness_district_id = $request->agreement_district_id;
            $appData->save();

//            $particularBody = RjscNrParticularBody::where('rjsc_nr_app_id',$app_id)->get();
            $reqData = $request->name_corporation_body;
            RjscNrParticularBody::where('rjsc_nr_app_id',$app_id)->delete();
            $checkcound=ListSubscriber::where('app_id',$app_id)->count();
            $subscriberstaus=true;
            if ($checkcound >0){
                $subscriberstaus=false;
            }


           foreach( $reqData as $key => $value) {
               $particularBody = new RjscNrParticularBody();
               $particularBody->serial_number = $key;

               if (CommonFunction::asciiCharCheck($request->name_corporation_body[$key])){
                   $particularBody->name_corporation_body = $request->name_corporation_body[$key];
               }else{

                   Session::flash('error', 'non-ASCII Characters found in name_corporation_body [PI-1001]');
                   return Redirect::to(URL::previous() . "#step2");

               }

               if (CommonFunction::asciiCharCheck($request->represented_by[$key])){
                   $particularBody->represented_by = $request->represented_by[$key];
               }else{

                   Session::flash('error', 'non-ASCII Characters found in represented_by [PI-1002]');
                   return Redirect::to(URL::previous() . "#step2");

               }

               if (CommonFunction::asciiCharCheck($request->address[$key])){
                   $particularBody->address = $request->address[$key];
               }else{

                   Session::flash('error', 'non-ASCII Characters found in address [PI-1003]');
                   return Redirect::to(URL::previous() . "#step2");

               }

               $particularBody->district_id = $request->district_id[$key];
               $particularBody->no_subscribed_shares = $request->no_subscribed_shares[$key];
               $particularBody->rjsc_nr_app_id = $appData->id;

               if ( $request->name_corporation_body[$key] != null || $request->represented_by[$key] != null || $request->no_subscribed_shares[$key] !=null
                   || $request->no_subscribed_shares[$key] != '' || $request->name_corporation_body[$key] != '' || $request->represented_by[$key] != ''){
                   $particularBody->save();

                   if ($subscriberstaus==true){

////                   dd($appData->id);
//                       $particularAndSubscriber = new ListSubscriber();
////                   $particularAndSubscriber->corporation_body_name = $request->name_corporation_body[$key];
//                       $particularAndSubscriber->corporation_body_name = $request->represented_by[$key]." Nominee of ".$request->name_corporation_body[$key];
//                       $particularAndSubscriber->serial_number = $key;
//                       $particularAndSubscriber->position = 1;
////               $particularAndSubscriber -> representative_name = $request->represented_by[$key];
////               $particularAndSubscriber -> corporation_body_address = $request->address[$key];
////               $particularAndSubscriber -> corporation_body_district_id  = $request->district_id[$key];
////                   $particularAndSubscriber->no_of_subscribed_shares = $request->no_subscribed_shares[$key];
//                       $particularAndSubscriber->no_of_subscribed_shares = 0;
//                       $particularAndSubscriber->app_id = $appData->id;
//                       $particularAndSubscriber->save();
                   }

               }


           }

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id','desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }

            $appData->save();

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

            if($processData->status_id == 0){
                dd('Application status not found!');
            }
            //update sequence
            $sequence=NewReg::find($app_id);
            $sequence->sequence=3;
            $sequence->save();
            Session::put('sequence', $sequence->sequence);
            DB::commit();

            if($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {
                $processData = ProcessList::leftJoin('process_type','process_type.id','=','process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
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
                    'process_sub_name' => $processData->process_sub_name,
                    'process_supper_name' => $processData->process_supper_name,
                    'process_type_name' => $processData->process_type_name,
                    'remarks'=> ''
                ];
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }


            if ($encodedAppId) {

                return Redirect::to(URL::previous() . "#step3");
            }
            return redirect('licence-applications/company-registration/add#step3');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }
}
