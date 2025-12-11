<?php

namespace App\Modules\CompanyRegSingleForm\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyRegSingleForm\Models\CompanyRegSingleForm;
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
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 134;
        $this->aclName = 'CompanyRegSingleForm';
    }

    public function saveRegForm(Request $request)
    {
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
            $appData->verified_company_name = $request->get('companySearch');
            $appData->company_verification_status = 1;
            if($appData->rjsc_from_submit_status == 1){
                $appData->sequence = 14;
            }else{
                $appData->sequence = 1;
            }


            $appData->entity_id = CommonFunction::getUserWorkingCompany();

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
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
            }

            $appData->save();
            $application_id = Encryption::encodeId($appData->id);

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
            Session::put('sequence', $appData->sequence);
            Session::put('current_app_id', $application_id);
            if ($request->get('app_id')) {
                return Redirect::to(URL::previous() . "#step1");
            }
            return redirect('process/licence-applications/company-registration-sf/view/' . $application_id . '/' . Encryption::encodeId($this->process_type_id) . '#step1');

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }
}
