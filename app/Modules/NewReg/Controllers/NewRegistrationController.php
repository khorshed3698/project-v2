<?php

namespace App\Modules\NewReg\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewReg\Models\NewReg;
use App\Modules\NewReg\Models\RjscSubmissionVerify;
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
use Illuminate\Support\Facades\URL;

class NewRegistrationController extends Controller
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

    public function saveRegForm(Request $request){


       // dd($request->get('actionBtn'));
        // Set permission mode and check ACL
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

        // Validation Rules when application submitted
        $rules = [];
        $messages = [];

        if ($request->get('actionBtn') != 'draft'){
            $rules['entity_type_id'] = 'required';
        }
        $messages['submission_no.exists'] = 'Submission Number Is not Valid.';

        $this->validate($request, $rules,$messages);
        //dd('success');

        try {
            DB::beginTransaction();
            if ($request->get('app_id')) {
                $appData = NewReg::find($app_id);
                $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id]);
            } else {
                $appData = new NewReg();
//                dd($appData);
                $processData = new ProcessList();
            }
            $submissionverifationid=Encryption::decodeId($request->get('verification_id'));
            $submissiondata=RjscSubmissionVerify::find($submissionverifationid);
            $appData->entity_id=Auth::user()->company_ids;
            $appData->entity_type_id=$request->get('entity_type_id');
            //$appData->submission_no=$request->get('submission_no');
            $appData->submission_no=$submissiondata->submission_no;

            if (CommonFunction::asciiCharCheck($request->get('clearence_letter_no'))){
              //  $appData->clearence_letter_no=$request->get('clearence_letter_no');
                $appData->clearence_letter_no=$submissiondata->clearence_letter_no;
            }else{

                Session::flash('error', 'non-ASCII Characters found in clearence_letter_no [CI-1001]');
                return Redirect::to(URL::previous() . "#step1");

            }

            $appData->sequence= 1;

//            dd($appData);

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
            $application_id=Encryption::encodeId($appData->id);

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

            //$appData->save();




            if($processData->status_id == 0){
                dd('Application status not found!');
            }

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
           // dd($application_id);
            Session::put('sequence', $appData->sequence);
            Session::put('current_app_id', $application_id);
            if ($request->get('app_id')) {
                return Redirect::to(URL::previous() . "#step1");
            }
            return redirect('process/licence-applications/company-registration/view/'.$application_id.'/'.Encryption::encodeId($this->process_type_id).'#step1');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }
}
