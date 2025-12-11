<?php

namespace App\Modules\Users\Controllers;

use App\ActionInformation;
use App\Http\Controllers\Controller;
use App\Libraries\ImageProcessing;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\CompanyAssociation\Models\CompanyAssociation;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Users\Models\DivisionalOffice;
use App\Modules\Users\Models\AuditPassword;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\Delegation;
use App\Modules\Users\Models\DepartmentInfo;
use App\Modules\Users\Models\OsspidLog;
use App\Modules\Users\Models\SubDepartment;
use App\Modules\Users\Models\UserLogs;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UsersModelEditable;
use App\Modules\Users\Models\UserTypes;
use App\Modules\Users\Models\AreaInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;
use yajra\Datatables\Datatables;
use Exception;
use Illuminate\Support\Facades\Response;

class UsersController extends Controller
{
    const HTTP_STATUS_BAD_REQUEST = 400;
    const HTTP_STATUS_UNPROCESSABLE_ENTITY = 422;
    const HTTP_STATUS_INTERNAL_SERVER_ERROR = 500;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view("Users::index");
    }

    /*
     * user's list for system admin
     */
    public function lists()
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-971]');

//        if (!CommonFunction::isAdmin()) {
//            Session::flash('error', 'Permission Denied');
//            return redirect('dashboard');
//        }

        $logged_in_user_type = Auth::user()->user_type;
        $user = 'user';
        return view('Users::user_list', compact('logged_in_user_type', 'user'))
            ->with('title', 'User List');
    }


    /*
     * user's details information by ajax request
     */
    public function getRowDetailsData(Users $user)
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-972]');

        $mode = ACL::getAccsessRight('user', 'V');
        $userList = $user->getUserList();
        return Datatables::of($userList)
            ->addColumn('action', function ($userList) use ($mode) {
                if ($mode) {
                    $assign_desk_btn = '';
                    $assign_parameters_btn = '';
                    $assign_division_btn = '';
                    $assignDepartment = '';
                    $force_log_out_btn = '';
                    $accessLog = '';
                    $company_associated = '';

                    if (Auth::user()->user_type == '1x101' or Auth::user()->user_type == '14x141') {
                        // if ($userList->user_type == '4x404') {
                        if (in_array($userList->user_type, ['4x404', '6x606'])) {
//                            $assign_desk_btn = ' <a href="' . url('/users/assign-desk/' . Encryption::encodeId($userList->id)) .
//                                '" class="btn btn-xs btn-info" ><i class="fa fa-check-circle"></i> Assign Desk</a>';

                            $assign_parameters_btn = ' <a href="' . url('/users/assign-parameters/' . Encryption::encodeId($userList->id)) .
                                '" class="btn btn-xs btn-warning" ><i class="fa fa-check-circle"></i> Assign Perameters</a>';


//                            $assign_division_btn = ' <a href="' . url('/users/assign-division/' . Encryption::encodeId($userList->id)) .
//                                '" class="btn btn-xs btn-primary" ><i class="fa fa-check-circle"></i> Assign Division</a>';


//                            $assignDepartment = ' <a href="' . url('/users/assign-department/' . Encryption::encodeId($userList->id)) .
//                                '" class="btn btn-xs btn-warning" ><i class="fa fa-check-circle"></i> Assign Department</a>';

                        } else if ($userList->user_type == '5x505') {
//                            $company_associated = ' <a href="' . url('/users/company-associated/' . Encryption::encodeId($userList->id)) .
//                                '" class="btn btn-xs btn-default" ><i class=" fa fa-group"></i> Company Assoc</a>';
                        } else if (in_array($userList->user_type, ['9x901', '9x902', '9x903', '9x904'])) {
                            $assign_desk_btn = ' <a href="' . url('/users/assign-desk/' . Encryption::encodeId($userList->id)) .
                                '" class="btn btn-xs btn-info" ><i class="fa fa-check-circle"></i> Assign Desk</a>';
                        }

                    }
                    if (Auth::user()->user_type == '1x101' or Auth::user()->user_type == '14x141') {
                        $accessLog = ' <a href="' . url('/users/access-log/' . Encryption::encodeId($userList->id)) .
                            '" class="btn btn-xs btn-success" ><i class="fa fa-key"></i> Access Log</a>';
                    }
                    if ((Auth::user()->user_type == '1x101' or Auth::user()->user_type == '14x141') && !empty($userList->login_token)) {
                        $force_log_out_btn = ' <a onclick="return confirm(\'Are you sure?\')" href="' . url('/users/force-logout/' . Encryption::encodeId($userList->id)) .
                            '" class="btn btn-xs btn-danger" ><i class="fas fa-sign-out-alt"></i> Force Log out</a>';
                    }
                    return ' <a href="' . url('users/view/' . Encryption::encodeId($userList->id)) . '" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open"></i> Open</a>' . $force_log_out_btn . $assign_desk_btn . $assign_parameters_btn. $assignDepartment. $assign_division_btn . $company_associated . $accessLog;
                } else {
                    return '';
                }
            })
            ->editColumn('user_status', function ($userList) {
                if ($userList->user_status == 'inactive') {
                    $activate = 'class="text-danger" ';
                } else {
                    $activate = 'class="text-success" ';
                }

                if ($userList->is_approved == 0) {
                    return '<span class="text-danger"><b>' . 'Not approved' . '</b></span>';
                }
                return '<span ' . $activate . '><b>' . $userList->user_status . '</b></span>';
            })
            ->filterColumn('user_full_name', function ($query, $keyword) {
                $sql = "CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name)  like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->removeColumn('id', 'is_sub_admin')
            ->make(true);
    }


    public function assignDesk($id)
    {
        if (!ACL::getAccsessRight('user', 'A'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-973]');
        try {
            $user_id = Encryption::decodeId($id);
            $user_exist_desk = Users::where('id', $user_id)->first(['desk_id', 'user_email']);
            $select = array();
            if ($user_exist_desk != null) {
                $user_exist_desk_arr = explode(',', $user_exist_desk->desk_id);
                foreach ($user_exist_desk_arr as $user_desk) {
                    $select[] = $user_desk;
                }
            }
            $desk_list = UserDesk::where('status', 1)->get(['desk_name', 'id']);
            $desk_status = ['0' => 'Inactive', '1' => 'Active'];
            $user_id = Encryption::encodeId($user_id);
            return view('Users::assign-desk', compact('desk_list', 'select', 'user_id', 'desk_status', 'user_exist_desk'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1051]');
            return Redirect::back()->withInput();
        }
    }

    public function assignDivision($id)
    {
        if (!ACL::getAccsessRight('user', 'A'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-973]');
        try {
            $user_id = Encryption::decodeId($id);
            $user_info = Users::where('id', $user_id)->first(['id', 'user_email','division_id','department_id','sub_department_id']);

            $select = array();
            if ($user_info != null) {

                $user_info_new = explode(',', $user_info->division_id);

                foreach ($user_info_new as $user_Division) {
                    $select[] = $user_Division;
                }
            }
            $division_list = DivisionalOffice::where('is_archive', 0)->get(['office_name', 'id']);


            $departmentIds=explode(',',$user_info->department_id);
            $departmentList= DepartmentInfo::whereIn('id',$departmentIds)->lists('short_name', 'id');

            $AssignDepartmentName='';
            foreach ($departmentList as $deptName) {
                $AssignDepartmentName=$AssignDepartmentName.'  '.$deptName.',';
            }
            $AssignDepartmentName = chop($AssignDepartmentName,",");
//

            $subdepartmentIds=explode(',',$user_info->sub_department_id);
            $subdepartmentList= SubDepartment::whereIn('id',$subdepartmentIds)->lists('short_name', 'id');

            $AssignSubDepartmentName='';
            foreach ($subdepartmentList as $subdeptName) {
                $AssignSubDepartmentName=$AssignSubDepartmentName.'  '.$subdeptName.',';
            }
            $AssignSubDepartmentName = chop($AssignSubDepartmentName,",");



            $status = ['0' => 'Inactive', '1' => 'Active'];
            $user_id = Encryption::encodeId($user_id);
            return view('Users::assign_division', compact('division_list', 'select', 'user_id', 'status', 'user_info','AssignDepartmentName','AssignSubDepartmentName'));
        } catch (\Exception $e) {
            //dd($e->getLine(), $e->getMessage());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1051]');
            return Redirect::back()->withInput();
        }
    }

    public function assignParameters ($id)
    {
        if (!ACL::getAccsessRight('user', 'A'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-973]');
        try {
            $user_id = Encryption::decodeId($id);
            $user_info = Users::where('id', $user_id)->first(['id','desk_id', 'user_email','division_id','department_id','sub_department_id','user_phone','user_first_name','user_middle_name','user_last_name', 'desk_training_id']);
            $fullName='';
            $fullName=$fullName.''.$user_info->user_first_name.' '.$user_info->user_middle_name.' '.$user_info->user_last_name;



            $selectDesk = array();
            $selectDivision = array();
            $selectDepartment = array();
            $selectsubDepartment = array();
            if ($user_info != null) {
                $selectDesk = explode(',', $user_info->desk_id);
                $selectDivision = explode(',', $user_info->division_id);
                $selectDepartment = explode(',', $user_info->department_id);
                $selectsubDepartment = explode(',', $user_info->sub_department_id);
            }
            $desk_list = UserDesk::where('status', 1)->get(['desk_name', 'id']);
            $division_list = DivisionalOffice::where('is_archive', 0)->get(['office_name', 'id']);

            $Department_list = DepartmentInfo::where('status', 1)->where('is_archive', 0)->get(['name', 'id']);
            $subDepartment_list = SubDepartment::where('status', 1)->where('is_archive', 0)->get(['name', 'id']);



            return view('Users::assign_users_parameters', compact('division_list','selectDivision','selectDesk','fullName','selectDepartment','selectsubDepartment','Department_list','subDepartment_list', 'user_info','desk_list'));
        } catch (\Exception $e) {
            //dd($e->getLine(), $e->getMessage());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1051]');
            return Redirect::back()->withInput();
        }
    }

    public function assignDeskSave(Request $request)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-974]');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));
            $assign_desk = 0;
//            dd($request->all());
            $ids = $request->get('division_ids');
            if (count($assign_desk) != 1) {
                Session::flash('error', 'Please select one desk for single user.');
                return redirect()->back();
            }
            if ($request->get('user_types') != null)
                $assign_desk = implode(',', $request->get('user_types'));

            DB::beginTransaction();
            $deskData = Users::FirstorNew(['id' => $user_id]);
            $deskData->desk_id = $assign_desk;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);

            //for audit log
            CommonFunction::createAuditLog('saveAssignUserDesk', $request);

            Session::flash('success', 'Successfully assigned desk.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1052]');
            return redirect()->back();
        }
    }

    public function assignDivisionSave(Request $request)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-974]');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));
//            $assign_division = 0;
//            dd($request->all());
            $assign_division = $request->get('division_ids');
//            if (count($assign_division) != 1) {
//                Session::flash('error', 'Please select one division for single user.');
//                return redirect()->back();
//            }
            if ($request->get('division_ids') != null)
                $assign_division = implode(',', $request->get('division_ids'));

            DB::beginTransaction();
            $deskData = Users::FirstorNew(['id' => $user_id]);
            $deskData->division_id = $assign_division;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);

            //for audit log
            CommonFunction::createAuditLog('saveAssignUserDesk', $request);

            Session::flash('success', 'Successfully assigned division.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1052]');
            return redirect()->back();
        }
    }

    public function assignParametersSave(Request $request)
    {

        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-974]');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));

            $assign_division = $request->get('division_ids');
            if ($request->get('division_ids') != null){
                $assign_division = implode(',', $request->get('division_ids'));
            }

//start assign desk

            $assign_desk = 0;
            if ($request->get('user_types') != null) {
                $assign_desk = implode(',', $request->get('user_types'));
            }
//            if (count($request->get('user_types')) != 1) {
//                Session::flash('error', 'Please select one desk for single user.');
//                return redirect()->back();
//            }
//end assign desk

//start assign department

            $assign_dpt = $request->get('department_name');
            $assign_sub_dpt = $request->get('sub_department_name');

            //department
            if (count($assign_dpt) != 1) {
                Session::flash('error', 'Please select one department for single user. [UC-1041]');
                return redirect()->back();
            }
            if (count($assign_sub_dpt) < 1) {
                Session::flash('error', 'Please select one sub department for single user.');
                return redirect()->back();
            }


            if ($request->get('department_name') != null)
                $assign_dpt = implode(',', $request->get('department_name'));

            if ($request->get('sub_department_name') != null)
                $assign_sub_dpt = implode(',', $request->get('sub_department_name'));


//end assign department

            DB::beginTransaction();
            $deskData = Users::find($user_id);
            $deskData->desk_training_id = empty($request->get('training_assign')) ? null : $request->get('training_assign');
            $deskData->division_id = empty($assign_division) ? '0' : $assign_division;
            $deskData->desk_id = $assign_desk;
            $deskData->department_id = $assign_dpt;
            $deskData->sub_department_id = $assign_sub_dpt;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);


            //for audit log
            CommonFunction::createAuditLog('saveAssignUserDesk', $request);

            Session::flash('success', 'Successfully assigned parameters.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1052]');
            return redirect()->back();
        }
    }


    public function assignDepartment($id)
    {
        if (!ACL::getAccsessRight('user', 'A'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-975]');
        try {
            $user_id = Encryption::decodeId($id);
            $user_exist_dpt = Users::where('id', $user_id)->first(['department_id', 'sub_department_id', 'user_email']);

            //department
            $select = array();
            if ($user_exist_dpt != null) {
                $user_exist_dpt_arr = explode(',', $user_exist_dpt->department_id);
                foreach ($user_exist_dpt_arr as $user_dpt) {
                    $select[] = $user_dpt;
                }
            }

            //sub-deparment
            $select_sub_dpt = array();
            if ($user_exist_dpt != null) {
                $user_exist_sub_dpt_arr = explode(',', $user_exist_dpt->sub_department_id);
                foreach ($user_exist_sub_dpt_arr as $sub_user_dpt) {
                    $select_sub_dpt[] = $sub_user_dpt;
                }
            }

            $dpt_list = DepartmentInfo::where('status', 1)->where('is_archive', 0)->get(['name', 'id']);
            $sub_dpt_list = SubDepartment::where('status', 1)->where('is_archive', 0)->get(['name', 'id']);
            $user_id = $id;
            return view('Users::assign-department', compact('dpt_list', 'sub_dpt_list', 'select', 'select_sub_dpt', 'user_id', 'desk_status', 'user_exist_dpt'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [UC-1011]');
            return Redirect::back()->withInput();
        }
    }

    public function assignDepartmentSave(Request $request)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-976]');
        try {
            //dd($request->all());
            $user_id = Encryption::decodeId($request->get('user_id'));
            $assign_dpt = 0;
            $assign_sub_dpt = 0;

            $assign_dpt = $request->get('department_name');
            $assign_sub_dpt = $request->get('sub_department_name');
//            dd($assign_sub_dpt);

            //department
            if (count($assign_dpt) != 1) {
                Session::flash('error', 'Please select one department for single user. [UC-1041]');
                return redirect()->back();
            }
            if ($request->get('department_name') != null)
                $assign_dpt = implode(',', $request->get('department_name'));

//            //sub department
//            if(count($assign_sub_dpt)!=1){
//                Session::flash('error', 'Please select one sub department for single user.');
//                return redirect()->back();
//            }

            if ($request->get('sub_department_name') != null)
                $assign_sub_dpt = implode(',', $request->get('sub_department_name'));

//            dd($assign_sub_dpt);


            DB::beginTransaction();
            $deskData = Users::FirstorNew(['id' => $user_id]);
            $deskData->department_id = $assign_dpt;
            $deskData->sub_department_id = $assign_sub_dpt;
            $deskData->save();
            DB::commit();
            $loginController = new LoginController();
            $loginController::killUserSession($user_id);
            //for audit log
            CommonFunction::createAuditLog('saveAssignUserDepartment', $request);

            Session::flash('success', 'Successfully assigned Department/ Sub-department.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1053]');
            return redirect()->back();
        }
    }

    public function companyAssociated($id)
    {
        if (!ACL::getAccsessRight('user', 'A'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-977]');
        try {
            $user_id = Encryption::decodeId($id);
            $user_exist_company = Users::where('id', $user_id)->first(['company_ids', 'user_email']);
            $select_dpt = array();
            if ($user_exist_company != null) {
                $user_exist_company_arr = explode(',', $user_exist_company->company_ids);
                foreach ($user_exist_company_arr as $company) {
                    $select[] = $company;
                }
            }

            $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
//                ->select('id', 'ai.area_nm as divisionName', 'di.area_nm as districtName',
////                    DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info')
//                    DB::raw("CONCAT(company_info.company_name,'  (',company_info.company_name_bn, ')') as company_name")
//                )
                ->where('is_approved', 1)
                ->where('company_status', 1)
                ->orderBy('company_name', 'ASC')
                ->get(
                    [
                        'id',
//                    'company_info.company_name',
//                    'company_info.company_name_bn',
                        DB::raw("CONCAT(COALESCE(company_info.company_name,'------'),'  (',COALESCE(company_info.company_name_bn,'------'), ')') as company_name"),
                        'ai.area_nm as divisionName',
                        'di.area_nm as districtName'
                    ]
                );
//            dd($company_list);

            $desk_status = ['0' => 'Inactive', '1' => 'Active'];
            $user_id = Encryption::encodeId($user_id);
            return view('Users::company-associated', compact('company_list', 'select', 'user_id', 'desk_status', 'user_exist_company'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [UC-1012]');
            return Redirect::back()->withInput();
        }
    }

    public function CompanyAssociatedSave(request $request)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-978]');
        try {

            $user_id = Encryption::decodeId($request->get('user_id'));
            $company_associated = 0;
            if ($request->get('company_associated') != null)
                $company_associated = implode(',', $request->get('company_associated'));

            DB::beginTransaction();
            $companyData = Users::FirstorNew(['id' => $user_id]);
            $companyData->company_ids = $company_associated;
            $companyData->save();
            DB::commit();
//            $loginController = new LoginController();
//            $loginController::killUserSession($user_id);

            //for audit log
            CommonFunction::createAuditLog('saveCompanyAssociation', $request);

            Session::flash('success', 'Successfully Company Associated.');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1054]');
            return redirect()->back();
        }
    }

    public function companyAssociatedByUser()
    {
        if (!ACL::getAccsessRight('user', 'V'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-979]');
        try {
            $user_id = CommonFunction::getUserId();
            $user_exist_company = Users::where('id', $user_id)->first(['user_sub_type', 'user_email']);
            $select = array();
            if ($user_exist_company != null) {
                $user_exist_company_arr = explode(',', $user_exist_company->user_sub_type);
                foreach ($user_exist_company_arr as $company) {
                    $select[] = $company;
                }
            }

            $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->select(
                    'id',
//                    DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info')
                    DB::raw("CONCAT(company_info.company_name,'  (',company_info.company_name_bn, ')') as company_name")
//                    DB::raw('CONCAT(company_info.company_name,"  (",company_info.company_name_bn, ")") as company_info')
                )
                ->where('is_approved', 1)
                ->where('created_by', $user_id)
                ->where('company_status', 1)
                ->orderBy('company_name', 'ASC')
                ->get(['company_info', 'id']);

            $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id')->all();
            $user_id = Encryption::encodeId($user_id);
            return view('Users::company-info', compact('company_list', 'select', 'user_id', 'desk_status', 'user_exist_company', 'divisions', 'districts', 'thana'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [UC-1013]');
            return Redirect::back()->withInput();
        }
    }

    public function getDeligatedUserInfo(Request $request)
    {
//        if (!ACL::getAccsessRight('user', 'A'))
//            abort(401, 'You have no access right. Contact with system admin for more information. [UC-971]');
        $userType = $request->get('designation');
        $result = Users::where('user_type', '=', $userType)
            ->Where('user_status', '=', 'active')
            ->Where(function ($result) {
                return $result->where('delegate_to_user_id', '=', null)
                    ->orWhere('delegate_to_user_id', '=', 0);
            })
            ->Where('id', '!=', Auth::user()->id)
            ->get([DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name,  '', concat(' (',  user_email, ')')) as user_full_name"), 'id']);
        echo json_encode($result);
    }

    public function processDeligation(Request $request)
    {

        $delegate_by_user_id = Auth::user()->id;
        $delegate_to_user_id = $request->get('delegated_user');
        $dependend_on_from_userid = Users::where('delegate_to_user_id', '=', $delegate_by_user_id)->get(['id', 'delegate_to_user_id']);

        DB::beginTransaction();
        foreach ($dependend_on_from_userid as $dependentUser) {
            $updateDependent = Users::findOrFail($dependentUser->id);
            $updateDependent->delegate_to_user_id = $delegate_to_user_id;
            $updateDependent->delegate_by_user_id = $delegate_by_user_id;
            $updateDependent->save();

            $delegation = new Delegation();
            $delegation->delegate_form_user = $dependentUser->id;
            $delegation->delegate_by_user_id = $delegate_by_user_id;
            $delegation->delegate_to_user_id = $delegate_to_user_id;
            $delegation->remarks = $request->get('remarks');
            $delegation->status = 1;
            $delegation->save();
        }
        DB::commit();

        $UData = array(
            'delegate_to_user_id' => $delegate_to_user_id,
            'delegate_by_user_id' => $delegate_by_user_id,
        );

        $complete = Users::where('id', $delegate_by_user_id)
            ->orWhere('delegate_to_user_id', $delegate_by_user_id)
            ->update($UData);

        $type = Auth::user()->user_type;

        $user_type = explode('x', $type)[0];

        if ($user_type != 1 || $user_type != 2) {
            Session::put('sess_delegated_user_id', $delegate_by_user_id);
        }

        if ($complete) {
            Delegation::create([
                'delegate_form_user' => $delegate_by_user_id,
                'delegate_by_user_id' => $delegate_by_user_id,
                'delegate_to_user_id' => $delegate_to_user_id,
                'remarks' => $request->get('remarks'),
                'status' => 1,
            ]);
            return redirect()
                ->intended('/users/delegate')
                ->with('success', 'Delegation process completed Successfully');
        } else {
            Session::flash('error', 'Delegation Not completed. [UC-1014]');
            return redirect('users/profileinfo/#tab_3');
        }
    }

    public function delegate()
    {
        $delegate_to_user_id = Auth::user()->delegate_to_user_id;
        $info = Users::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
            ->where('users.id', $delegate_to_user_id)->first(['user_first_name', 'user_middle_name', 'user_last_name', 'user_email', 'user_phone', 'ud.desk_name']);
        return view("Dashboard::delegated", compact('info'));
    }

    public function removeDeligation($DelegateId = '')
    {

        if ($DelegateId == '') {
            $sess_user_id = Auth::user()->id;
        } else {
            $sess_user_id = Encryption::decodeId($DelegateId);
        }


        //USER INFO DELATION REMOVE
        Users::where('id', $sess_user_id)
            ->update(['delegate_to_user_id' => 0, 'delegate_by_user_id' => 0]);

        Users::where('delegate_by_user_id', $sess_user_id)
            ->where('id', '!=', $sess_user_id)
            ->update(['delegate_to_user_id' => $sess_user_id, 'delegate_by_user_id' => $sess_user_id]);

        //DELEGATION HISTORY UPDATE
        $id = Delegation::where('delegate_by_user_id', $sess_user_id)
            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->update(['remarks' => '', 'status' => 0]);

        //REMOVE DELEGATION HISTORY ENTRY
        Delegation::where('delegate_by_user_id', $sess_user_id)
            ->where('delegate_to_user_id', Auth::user()->delegate_to_user_id)
            ->orderBy('created_at', 'DESC')->first();

        Session::flash('success', 'Remove Delegation Successfully');
        Session::forget('sess_delegated_user_id');

        if ($DelegateId == '') {
            return redirect("dashboard");
        } else {
            return redirect("users/delegations/" . Encryption::encodeId($sess_user_id));
        }

    }


    public function failedLoginHist(request $request, $email)
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-980]');

//        if (!CommonFunction::isAdmin()) {
//            Session::flash('error', 'Permission Denied');
//            return redirect('dashboard');
//        }
        $logged_in_user_type = Auth::user()->user_type;
        $decodedUserEmail = Encryption::decodeId($email);
        $user = Users::where('user_email', $decodedUserEmail)
            ->select(DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'id', 'user_phone')
            ->first(['id', 'user_full_name', 'user_phone']);
        return view('Users::failed-loginHistory', compact('logged_in_user_type', 'user', 'decodedUserEmail', 'email'));
    }

    public function accessLogHist($userId)
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right. Contact with system admin for more information. [UC-981]');

        $decodedUserId = Encryption::decodeId($userId);

//        if (!CommonFunction::isAdmin()) {
//            Session::flash('error', 'Permission Denied');
//            return redirect('dashboard');
//        }
        $logged_in_user_type = Auth::user()->user_type;
        $user = Users::find($decodedUserId);
        $user_name = $user->user_first_name . ' ' . $user->user_middle_name . ' ' . $user->user_last_name;
        $user_phone = $user->user_phone;
        $email = $user->user_email;
        return view('Users::access-log', compact('logged_in_user_type', 'user', 'userId', 'email', 'user_name', 'user_phone'));
    }

    public function getAccessLogData($userId)
    {
        $decodedUserId = Encryption::decodeId($userId);
        $user_logs = UserLogs::JOIN('users', 'users.id', '=', 'user_logs.user_id')
            ->where('user_logs.user_id', '=', $decodedUserId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['users.designation', 'users.created_at', 'users.user_phone', 'user_logs.user_id', 'ip_address', 'login_dt', 'logout_dt', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($user_logs)
            ->make(true);
    }

    public function getAccessLogDataForSelf()
    {
        try {
            $osspidLog = new OsspidLog();
            $access_token = $osspidLog->getAuthToken();

            if (!$access_token) {
                return Response::json(['error' => 'An error occurred while fetching data. Please try again later. Token Not Found!'], self::HTTP_STATUS_BAD_REQUEST);
            }

            $user_logs = $osspidLog->getOsspidAccessLogHistory($access_token);

            if (
                !$user_logs ||
                !isset($user_logs->osspidLoggerResponse) ||
                !isset($user_logs->osspidLoggerResponse->responseData)
            ) {
                return Response::json(['error' => 'No access log data available'], self::HTTP_STATUS_UNPROCESSABLE_ENTITY);

            }

            return Datatables::of(collect($user_logs->osspidLoggerResponse->responseData))->make(true);

        } catch (Exception $e) {
            Log::error('UsersController@getAccessLogDataForSelf: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            Return Response::json(['error' => CommonFunction::showErrorPublic($e->getMessage())], self::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAccessLogFailed()
    {
        try {
            $osspidLog = new OsspidLog();
            $access_token = $osspidLog->getAuthToken();

            if (!$access_token) {
                return Response::json(['error' => 'Token not found'], self::HTTP_STATUS_BAD_REQUEST);
            }

            $user_Failed = $osspidLog->getOsspidFailedLoginHistory($access_token);

            if (
                !$user_Failed ||
                !isset($user_Failed->osspidLoggerResponse) ||
                !isset($user_Failed->osspidLoggerResponse->responseData)
            ) {
                return Response::json(['error' => 'No failed login data available'], self::HTTP_STATUS_UNPROCESSABLE_ENTITY);
            }

            return Datatables::of(collect($user_Failed->osspidLoggerResponse->responseData))->make(true);

        } catch (Exception $e) {
            Log::error('UsersController@getAccessLogDataForSelf: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            Return Response::json(['error' => CommonFunction::showErrorPublic($e->getMessage())], self::HTTP_STATUS_INTERNAL_SERVER_ERROR);
        }
    }


    private function getUserAccessOsspidToken()
    {
        $url = 'https://idp.oss.net.bd/auth/realms/dev/protocol/openid-connect/token';
        $postdata = array(
            'grant_type' => 'client_credentials',
            'client_id' => 'osspid-logger-service-bida-client',
            'client_secret' => '097c270d-51ca-49e1-b0c4-4bf1aeff2377'
        );

        try {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 100);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 100);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postdata));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer')); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-Type: application/x-www-form-urlencoded"));
            $response = curl_exec($curl);
            $result = json_decode($response);

            if ($result == null)
                return true;

            return $result->access_token;

        } catch (Exception $e) {
            return false;
        }
    }


    public function getLast50Action()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $last50Action = ActionInformation::where('user_id', '=', Auth::user()->id)->orderBy('id', 'DESC')->take(50)
            ->get(['action_info.action', 'action_info.ip_address', 'action_info.created_at', DB::raw('@rownum  := @rownum  + 1 AS rownum')]);
        return Datatables::of($last50Action)
            ->editColumn('rownum', function ($data) {
                return $data->rownum;
            })
            ->make(true);
    }


    public function getRowFailedData(request $request, Users $email)
    {
        $email = Encryption::decodeId($request->get('email'));
        $mode = ACL::getAccsessRight('user', 'V');
        $failed_login_history = DB::table('failed_login_history')->where('user_email', $email)->orderby('created_at', 'DESC');
        //$userList = $user->getUserList();
        return Datatables::of($failed_login_history)
            ->addColumn('action', function ($failed_login_history) use ($mode) {
                if ($mode) {
                    return '<a  data-toggle="modal" data-target="#myModal" id="' . $failed_login_history->id . '" onclick="myFunction(' . $failed_login_history->id . ')" class="ss btn btn-xs btn-primary" ><i class="fa fa-retweet"></i> Resolved</a>';
                }
            })
            ->editColumn('remote_address', function ($failed_login_history) {
                return '' . $failed_login_history->remote_address . '</span>';
            })
            ->removeColumn('id', 'is_sub_admin')
            ->make(true);
    }

    public function FailedDataResolved(request $request)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-982]');

        $date = date('Y-m-d h:i:s a', time());
        $failed_login_history = DB::table('failed_login_history')->where('id', $request->get('failed_login_id'))->first();
        DB::beginTransaction();
        DB::table('delete_login_history')->insert(
            [
                'remote_address' => $failed_login_history->remote_address,
                'user_email' => $failed_login_history->user_email,
                'deleted_by' => $logged_in_user_type = Auth::user()->id,
                'remarks' => $request->get('remarks'),
                'created_at' => $date,
                'updated_at' => $date
            ]
        );
        DB::table('failed_login_history')->where('id', $request->get('failed_login_id'))->delete();
        DB::commit();
        return redirect()->back()->with('success', 'Successfully Resolved');
    }

    /*
     * view individual user from admin panel
     */
    public function view($id, Users $usersModel)
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-983]');

        try {
            $user_id = Encryption::decodeId($id);
            $user = $usersModel->getUserRow($user_id);
            //desk name
            $desk_id = explode(',', $user->desk_id);
            $desk = UserDesk::whereIn('id', $desk_id)->get(['desk_name']);
            //Department name
            $department_id = explode(',', $user->department_id);
            $departments = DepartmentInfo::whereIn('id', $department_id)->get(['name']);

            //sub-department
            $sub_department_id = explode(',', $user->sub_department_id);
            $sub_departments = SubDepartment::whereIn('id', $sub_department_id)->get(['name']);

            $company_id = explode(',', $user->company_ids);
            $company_list = CompanyInfo::leftJoin('company_association_request as ca', 'company_info.id', '=', 'ca.requested_company_id')
                ->whereIn('company_info.id', $company_id)
                ->where('ca.request_type', 'Add')
                ->where('ca.user_id', $user_id)
                ->where('company_info.is_approved', 1)
                ->where('ca.status_id', 25)
                ->where('ca.status', 1)
                ->where('company_info.is_rejected', 'no')
                ->whereIn('company_info.id', $company_id)->orderBy('company_name', 'ASC')
                ->orderBy('company_info.company_name','ASC')
                ->get([
                    'company_info.id',
                    'company_info.company_name',
                    'company_info.company_name_bn',
                    'ca.authorization_letter'
                ]);

//            $company_list = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
//                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
////            ->select('company_info.id','company_info.company_name','ai.area_nm as divisionName', 'di.area_nm as districtName'
////                DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info')
////            )
//                ->whereIn('company_info.id', $company_id)->orderBy('company_name', 'ASC')
//                ->get([
//                    'company_info.id',
//                    DB::raw("CONCAT(company_info.company_name,'  (',company_info.company_name_bn, ')') as company_name"),
//                    'ai.area_nm as divisionName',
//                    'di.area_nm as districtName'
//                ]);

            $user_type_part = explode('x', $user->user_type);

            $delegateInfo = '';
            // get delegation info if user is delegated
            if ($user->delegate_to_user_id != 0) {
                $delegateInfo = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                    ->leftJoin('user_types as ut', 'ut.id', '=', 'users.user_type')
                    ->where('users.id', $user->delegate_to_user_id)
                    ->first(['users.id', DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'users.desk_id', 'ut.type_name',
                        'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                        'designation', 'user_phone'
                    ]);
            }

            $auth_file = '';
            if (count($user_type_part) > 1) {
                $user_types = UserTypes::where('id', 'LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "_")
                    ->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
                    ->where('status', 'active')
                    ->orderBy('type_name')
                    ->lists('type_name', 'id');
                $delegationInfo = '';
                if ($user->delegate_to_user_id > 0) {
                    $delegationInfo = Users::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                        ->where('users.id', $user->delegate_to_user_id)
                        ->first(['users.id', DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'user_email', 'user_phone', 'ud.desk_name']);
                }
                return view('Users::view-printable', compact("user", "user_types", "userMoreInfo", 'auth_file', 'desk', 'departments', 'sub_departments', 'delegationInfo', 'delegateInfo', 'company_list'));
            } else {
                Session::flash('error', 'User Type not defined. [UC-1015]');
                return redirect('users/lists');
            }
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1055]');
            return \redirect()->back();
        }
    }


    // for adding new users from Authentic Admin's end
    public function createNewUser()
    {
        if (!ACL::getAccsessRight('user', '-A-')) {
            die('You have no access right! Please contact with system admin for more information. [UC-991]');
        }

        try {
            $logged_user_type = Auth::user()->user_type;
            $user_type_part = explode('x', $logged_user_type);
            if ($logged_user_type == '1x101' or $logged_user_type == '14x141') { // 1x101 is Sys Admin, 14x141 is Programmer
                $user_types = UserTypes::where('is_registarable', '!=', '-1')
                    ->whereNotIn('id', ['5x505'])
                    ->where('status', '=', 'active')
                    ->lists('type_name', 'id');
            } else {
                $user_types = UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
                    ->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
                    ->where('status', '=', 'active')
                    ->orderBy('type_name')->lists('type_name', 'id');
            }

            $user_desk = UserDesk::orderBy('desk_name')->lists('desk_name', 'id');


            $company_list = CompanyInfo::where('is_approved', 1)->orderBy('company_name', 'ASC')->lists('company_name', 'id')->all();
            $nationalities = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $departments = ['' => 'Select One'] + DepartmentInfo::lists('name', 'id')->all();
            $desks = ['' => 'Select One'] + UserDesk::lists('desk_name', 'id')->all();
            $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
            $divisions = AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm', 'area_id');
            $districts = AreaInfo::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_id');
            $passport_types = [
                'ordinary' => 'Ordinary',
                'diplomatic' => 'Diplomatic',
                'official' => 'Official',
            ];

            return view("Users::new-user", compact("user_types", "logged_user_type",
                "user_desk", "districts", "divisions", 'departments', 'desks', "countries", 'nationalities', "economicZone", "company_list", "passport_types"));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1056]');
            return \redirect()->back();
        }
    }

    public function verification($confirmationCode)
    {
        $user = Users::where('user_hash', $confirmationCode)->first();
        if (!$user) {
            \Session::flash('error', 'Invalid Token! Please resend email verification link. [UC-1016]');

            return redirect()->away(UtilFunction::logoutFromKeyCloak());
            //return redirect('login');
        }
        $currentTime = new Carbon;
        $validateTime = new Carbon($user->created_at . '+6 hours');
        if ($currentTime >= $validateTime) {
            Session::flash('error', 'Verification link is expired (validity period 6 hrs). Please sign up again! [UC-1017]');
            return redirect()->away(UtilFunction::logoutFromKeyCloak());
            //return redirect('/login');
        }

        $user_type = $user->user_type;
        $districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();

        if ($user->user_verification != 'yes') {
            $districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
            return view('Users::verification', compact('user_type', 'confirmationCode', 'districts'));
        } else {
            \Session::flash('error', 'Invalid Token! Please sign up again. [UC-1018]');
            return redirect('/');
        }
    }

    //When completing registration, to get thana after selecting district
    public function getThanaByDistrictId(Request $request)
    {
        $district_id = $request->get('districtId');

        $thanas = AreaInfo::where('PARE_ID', $district_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $thanas];
        return response()->json($data);
    }

    public function getBranchByBank(Request $request)
    {
        $bank_id = $request->get('bankId');

        $branches = BankBranch::where('bank_id', $bank_id)->orderBy('branch_name', 'ASC')->lists('branch_name', 'id');
        $data = ['responseCode' => 1, 'data' => $branches];
        return response()->json($data);
    }

    public function getDistrictByDivision(Request $request)
    {
        $division_id = $request->get('divisionId');

        $districts = AreaInfo::where('PARE_ID', $division_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    /*
     * individual User's profile Info view
     */
    public function profileInfo()
    {
        if (!ACL::getAccsessRight('user', '-V-'))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-984]');

        $desk = '';
        $approvalCenter = '';
        $dpts = '';
        $companyAssociated = '';
        $users = Users::find(Auth::user()->id);

        if ($users->department_id != '') {
            $department_id = explode(',', $users->department_id);
            $dpts = DepartmentInfo::whereIn('id', $department_id)->get(['name']);
        }

        //sub department ...
        if ($users->sub_department_id != '') {
            $sub_department_id = explode(',', $users->sub_department_id);
            $sub_dpts = SubDepartment::whereIn('id', $sub_department_id)->get(['name']);
        }

        if ($users->desk_id != '') {
            $desk_id = explode(',', $users->desk_id);
            $desk = UserDesk::whereIn('id', $desk_id)->get(['desk_name']);
        }

        if ($users->division_id != '') {
            $division_id = explode(',', $users->division_id);
            $approvalCenter = DivisionalOffice::whereIn('id', $division_id)->get(['office_name']);
        }

        if ($users->company_ids != '') {
            $company_id = explode(',', $users->company_ids);
            $companyAssociated = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
//                ->select('company_info.id', 'company_info.company_name','ai.area_nm as divisionName', 'di.area_nm as districtName'
//                    DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info')
//                )
                ->whereIn('company_info.id', $company_id)->orderBy('company_name', 'ASC')
                ->get([
                    'company_info.id',
                    DB::raw("CONCAT(company_info.company_name,'  (',company_info.company_name_bn, ')') as company_name"),
                    'ai.area_nm as divisionName',
                    'di.area_nm as districtName'
                ]);
            $auth_letter = CompanyAssociation::where([
                'user_id' => Auth::user()->id,
                'request_type' => 'Add',
                'requested_company_id' => Auth::user()->company_ids // Current working company id
            ])->pluck('authorization_letter');
        }

        $user_nationality = Countries::orderby('nationality')
            ->where('id', $users->nationality_id)
            ->orWhere('iso', $users->nationality)
            ->pluck('nationality');

        $userType = CommonFunction::getUserType();
        $designationUserType = UserTypes::where('status', 'active')->where('id', $userType)->pluck('delegate_to_types');
        $type_id = explode(",", $designationUserType);
        $delegate_to_types = UserTypes::whereIn('id', array_map('trim', $type_id))->lists('type_name', 'id');
        $process_type = ProcessType::where('status', 1)->lists('name', 'id');

        $profile_pic = CommonFunction::getPicture('user', Auth::user()->id);
        $user_type_info = UserTypes::where('id', $users->user_type)->first();
        $image_config = CommonFunction::getImageConfig('IMAGE_SIZE');
        $doc_config = CommonFunction::getImageConfig('DOC_IMAGE_SIZE');
        $auth_file = '';
        $id = Encryption::encodeId(Auth::user()->id);

        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();

        $business_category = 0;
        if (Auth::user()->user_type == '5x505') {
            $business_category = Auth::user()->company->business_category;
        }

        return view('Users::profile-info', compact('id', 'users', 'process_type', 'divisions','approvalCenter','division_id', 'user_nationality', 'user_type_info', 'profile_pic', 'districts', 'image_config', 'doc_config', 'auth_file', 'desk', 'delegate_to_types', 'dpts', 'sub_dpts', 'companyAssociated', 'auth_letter','business_category'));
    }


    /*
     * user's account activaton
     */
    public function activate($id)
    {
        if (!ACL::getAccsessRight('user', 'E')) die ('no access right!');
        $user_id = Encryption::decodeId($id);
        try {
            $user = Users::where('id', $user_id)->first();
            $company_ids = explode(',', $user->company_ids);
            $user_active_status = $user->user_status;

            if ($user_active_status == 'active') {
                Users::where('id', $user_id)->update(['user_status' => 'inactive']);
                \Session::flash('error', "User's Profile has been deactivated Successfully! [UC-1019]");
            } elseif ($user_active_status == 'inactive') {
                // The below code is comment cause a user can play the role of multiple companies
                // Agency user can active a single user at a time.
//                $user_type = explode('x', $user->user_type);
//                if (in_array($user->user_type, ['5x505', '6x606'])) {
//
//                    foreach ($company_ids as $companyId) {
//                        $anotherUser = Users::where(function ($query) {
//                            $query->where('is_approved', 1);
//                            $query->where('user_status', 'active');
//                        })
////                            ->where('company_ids', 'like', '%' . $companyId . '%')
////                            ->whereRaw("company_ids REGEXP '^([0-9][,]+)$companyId([,]+[,0-9])$'")
//                            ->whereRaw("FIND_IN_SET('$companyId', company_ids)")
//                            ->where('id', '!=', $user->id)
//                            ->where('user_type', $user->user_type)
//                            ->count();
//                        if ($anotherUser) {
//                            \Session::flash('error', "Multiple user will not be active for a company. [UC1256]");
//                            return redirect()->back();
//                        }
//                    }
//                }

                Users::where('id', $user_id)->update(['user_status' => 'active']);
                \Session::flash('success', "User's profile has been activated successfully!");

                $receiverPhoneEmail = Users::where('id', $user_id)->get(['user_email', 'user_phone']);
                CommonFunction::sendEmailSMS('ACCOUNT_ACTIVATION', [], $receiverPhoneEmail);
            }
            LoginController::killUserSession($user_id);
            return redirect('users/lists/');
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1057]');
            return Redirect::back()->withInput();
        }
    }

    /*
     * User's password update function
     */
    public function updatePassFromProfile(Request $request)
    {
        $userId = Encryption::decodeId($request->get('Uid'));
        if (!ACL::getAccsessRight('user', 'SPU', $userId))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-985]');

        $dataRule = [
            'user_old_password' => 'required',
            'user_new_password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{6,}$/'
            ],
            'user_confirm_password' => [
                'required',
                'same:user_new_password',
            ]
        ];

        $validator = Validator::make($request->all(), $dataRule);
        if ($validator->fails()) {
            return redirect('users/profileinfo#tab_2')->withErrors($validator)->withInput();
        }

        try {
            $old_password = $request->get('user_old_password');
            $new_password = $request->get('user_new_password');

//          $password_match = Users::where('id', Auth::user()->id)->pluck('password');
            $getPasswordMatch = AuditPassword::where('user_id', Auth::user()->id)->get(['password']);

            DB::beginTransaction();
            if (count($getPasswordMatch) == 0) {
                $password_match = Users::where('id', Auth::user()->id)->pluck('password');
                $password_chk = Hash::check($old_password, $password_match);

                if ($password_chk == true) {
                    Users::where('id', Auth::user()->id)
                        ->update(array('password' => Hash::make($new_password)));
                    AuditPassword::create([
                        'user_id' => CommonFunction::getUserId(),
                        'password' => Hash::make($new_password),
                    ]);
                    Auth::logout();
                    $loginObj = new LoginController();
                    $loginObj->entryAccessLogout();


                    DB::commit();
                    \Session::flash('success', 'Your password has been changed successfully! Please login with the new password.');
                    return redirect('login');
                } else {
                    DB::rollback();
                    \Session::flash('error', 'Password do not match. [UC-1020]');
                    return Redirect('users/profileinfo#tab_2')->with('status', 'error');
                }
            }

            $result = false;
            foreach ($getPasswordMatch as $password_match) {
                $password_chk = Hash::check($old_password, $password_match->password);
                if ($password_chk == true)
                    $result = true;
            }
//          $password_chk = Hash::check($old_password, $password_match);

            if ($result == true) {
                $hasPassword = Hash::make($new_password);
                Users::where('id', Auth::user()->id)
                    ->update(array('password' => $hasPassword));

                AuditPassword::create([
                    'user_id' => CommonFunction::getUserId(),
                    'password' => $hasPassword,
                ]);

                Auth::logout();
                $loginObj = new LoginController();
                $loginObj->entryAccessLogout();

                DB::commit();
                \Session::flash('success', 'Your password has been changed successfully! Please login with the new password.');
                return redirect('login');
            } else {
                DB::rollback();
                \Session::flash('error', 'Password do not match. [UC-1021]');
                return Redirect('users/profileinfo#tab_2')->with('status', 'error');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1022]');
            return Redirect::back()->withInput();
        }
    }

    /*
     * password update from admin panel
     */

    /*
     * password update from admin panel
     */
    public function resetPassword($id)
    {
        if (!ACL::getAccsessRight('user', 'R'))
            die('no access right!. [UC-992]');
        try {
            $user_id = Encryption::decodeId($id);
            $password = str_random(10);

            DB::beginTransaction();
            $user_active_status = DB::table('users')->where('id', $user_id)->first(['user_status', 'id']);

            if ($user_active_status->user_status == 'active') {
                $hasPassword = Hash::make($password);
                Users::where('id', $user_id)->update([
                    'password' => $hasPassword
                ]);
                AuditPassword::create([
                    'user_id' => $user_active_status->id,
                    'password' => $hasPassword,
                ]);

                \Session::flash('success', "User's password has been reset successfully! An email has been sent to the user!");
            } else {
                DB::rollback();
                \Session::flash('error', "User profile has not been activated yet! Password can not be changed. [UC-1023]");
            }
            DB::commit();
            return redirect('users/lists');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1058]');
            return Redirect::back()->withInput();
        }
    }


    public function storeNewUser(Request $request)
    {
        if (!ACL::getAccsessRight('user', '-A-')) {
            die('You have no access right! Please contact with system admin for more information. [UC-993]');
        }

        $rules = [];
        $rules['user_first_name'] = 'required';
        $rules['designation'] = 'required';
        $rules['user_gender'] = 'required';
        $rules['user_DOB'] = 'required|date';
        $rules['user_phone'] = 'required';
        $rules['user_email'] = 'required|email|unique:users';
        $rules['country_id'] = 'required';
        $rules['nationality'] = 'required';
        $rules['road_no'] = 'required';
        $rules['post_code'] = 'required';

        $rules['nationality_type'] = 'required|in:bangladeshi,foreign';
        $rules['identity_type_bd'] = 'required_if:nationality_type,==,bangladeshi|in:nid,tin';
        $rules['identity_type_foreign'] = 'required_if:nationality_type,==,foreign|in:passport,tin';

        $rules['department'] = 'required_if:user_type,4x404';
        $rules['desk'] = 'required_if:user_type,4x404';

//        $rules['company_id'] = 'required_if:user_type,5x505';

        $rules['division'] = 'required_if:nationality,18|integer';
        $rules['district'] = 'required_if:nationality,18|integer';
        $rules['police_station'] = 'required_if:nationality,18|integer';

        $rules['state'] = 'required_unless:nationality,18';
        $rules['province'] = 'required_unless:nationality,18';

        $rules['passport_nationality'] = 'required_if:identity_type_foreign,==,passport|integer';
        $rules['passport_type'] = 'required_if:identity_type_foreign,==,passport|in:ordinary,diplomatic,official';
        $rules['passport_no'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_surname'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_given_name'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_DOB'] = 'required_if:identity_type_foreign,==,passport|date|date_format:d-M-Y';
        $rules['passport_date_of_expire'] = 'required_if:identity_type_foreign,==,passport|date|date_format:d-M-Y';

        $messages = [];
        $this->validate($request, $rules, $messages);

        try {
            $token_no = hash('SHA256', "-" . $request->get('user_email') . "-");
            $encrypted_token = Encryption::encodeId($token_no);

//            $companyId = 0;
//            if (in_array($request->get('user_type'), ['5x505'])) {
//                $companyId = $request->get('company_id');
//            }

            DB::beginTransaction();

            $identity_type = 'none';
            if(!empty($request->get('identity_type_bd'))) {
                $identity_type = $request->get('identity_type_bd');
            } elseif (!empty($request->get('identity_type_foreign'))) {
                $identity_type = $request->get('identity_type_foreign');
            }

            $nationality_type = $request->get('nationality_type');

            $data = array(
                'user_first_name' => $request->get('user_first_name'),
                'user_middle_name' => $request->get('user_middle_name'),
                'user_last_name' => $request->get('user_last_name'),
                'designation' => $request->get('designation'),
                'user_gender' => $request->get('user_gender'),
                'user_DOB' => CommonFunction::changeDateFormat($request->get('user_DOB'), true),
                'user_phone' => $request->get('user_phone'),
                'user_number' => $request->get('user_number'),
                'user_email' => $request->get('user_email'),
                'user_hash' => $encrypted_token,
                //'company_ids' => $companyId,
                'user_type' => $request->get('user_type'),
                'desk_id' => $request->get('desk'),
                'department_id' => $request->get('department'),

                'nationality_type' => $nationality_type,
                'identity_type' => $identity_type,

                'country_id' => $request->get('country_id'),
                'nationality_id' => $request->get('nationality'),

                'road_no' => $request->get('road_no'),
                'post_code' => $request->get('post_code'),

                'user_status' => 'active',
                'is_approved' => 1,
                'user_agreement' => 0,
                'first_login' => 0,
                'user_verification' => 'no',
                'user_hash_expire_time' => new Carbon('+6 hours')
            );

            if ($nationality_type === 'bangladeshi') {
                $data['division'] = $request->get('division');
                $data['district'] = $request->get('district');
                $data['thana'] = $request->get('police_station');
                $data['post_office'] = $request->get('post_office');
            } elseif ($nationality_type === 'foreign') {
                $data['state'] = $request->get('state');
                $data['province'] = $request->get('province');
            }

            if ($identity_type === 'nid') {
                $data['user_nid'] = $request->get('user_nid');
            } elseif ($identity_type === 'tin') {
                $data['user_tin'] = $request->get('user_tin');
            } elseif ($identity_type === 'passport') {
                $data['passport_no'] = $request->get('passport_no');
                $data['passport_nationality_id'] = $request->get('passport_nationality');
                $data['passport_type'] = $request->get('passport_type');
                $data['passport_surname'] = $request->get('passport_surname');
                $data['passport_given_name'] = $request->get('passport_given_name');
                $data['passport_personal_no'] = $request->get('passport_personal_no');
                $data['passport_DOB'] = CommonFunction::changeDateFormat($request->get('passport_DOB'), true);
                $data['passport_date_of_expire'] = CommonFunction::changeDateFormat($request->get('passport_date_of_expire'), true);
            }

            Users::create($data);

            $receiverInfo[] = [
                'user_email' => $request->get('user_email'),
                'user_phone' => $request->get('user_phone')
            ];

            $appInfo = [
                'verification_link' => url('users/verify-created-user/' . ($encrypted_token))
            ];

            CommonFunction::sendEmailSMS('CONFIRM_ACCOUNT', $appInfo, $receiverInfo);

            DB::commit();

            //for audit log
            CommonFunction::createAuditLog('saveNewUserFromSystemAdmin', $request);

            Session::flash('success', 'User has been created successfully! An email has been sent to the user for email verification.');
            return redirect('users/lists');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1024]');
            return Redirect::back()->withInput();
        }
    }

    // Verifying new users created by admin
    public function verifyCreatedUser($encrypted_token)
    {
        $user = Users::where('user_hash', $encrypted_token)->first();
        if (!$user) {
            Session::flash('error', 'Invalid Token. Please try again... [UC-1025]');
            return redirect('login');
        }

        // check hash expire time and current time
        $currentTime = new Carbon;
        if ($currentTime >= $user->user_hash_expire_time) {
            \Session::flash('error', "Token time expired! [UC-1026]");
            return redirect('/');
        }

        if ($user->user_verification == 'no') {
            return view('Users::verify-created-user', compact('encrypted_token'));
        } else {
            Session::flash('error', 'Invalid Token! Please sign-up again to continue. [UC-1027]');
            return redirect('/');
        }
    }

    public function createdUserVerification($encrypted_token, Request $request, Users $usersmodel)
    {
        try {
            $user = Users::where('user_hash', $encrypted_token)->first();

            if (!$user) {
                Session::flash('error', 'Invalid token! Please sign up again to complete the process. [UC-1028]');
                return redirect('create');
            }

            $this->validate($request, [
                'user_agreement' => 'required',
            ]);
            DB::beginTransaction();
            $createdByInfo = Users::where('id', $user->created_by)->first(['user_phone', 'user_email']);
            $ossPidRequestData['osspidRequest'] = array(
                'clientId' => config('app.osspid_client_id'),
                'secretKey' => config('app.osspid_client_secret_key'),
                'requestType' => 'REGISTRATION',
                'version' => '1.0',
                'requestData' => array(
                    'domain' => config('app.old_training_domain'),
                    'userInfo' => array(
                        'name' => $user->user_first_name . ' ' . $user->user_middle_name . ' ' . $user->user_last_name,
                        'email' => $user->user_email,
//                        'password' => $user_password,
                        'gender' => $user->user_gender,
                        'mobileNo' => $user->user_phone,
                        'dob' => $user->user_DOB,
                    ),
                    'createdBy' => array(
                        'email' => (isset($createdByInfo->user_email) ? $createdByInfo->user_email : ''),
                        'contactNo' => (isset($createdByInfo->user_phone) ? $createdByInfo->user_phone : ''),
                    ),
                )
            );


            $jsonEncodeData = json_encode($ossPidRequestData);
            $encodedOssPidRequestData = urlencode($jsonEncodeData);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, config('app.osspid_base_url_ip') . "/osspid-reg/api?param=" . $encodedOssPidRequestData);
            curl_setopt($ch, CURLOPT_POST, 0);
            //curl_setopt($ch, CURLOPT_POSTFIELDS, "requestData=$requested_url");
            // receive server response ...
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                DB::rollback();
                Session::flash('error', 'Request Error: ' . curl_error($ch) . '[OSSPID-399]');
                curl_close($ch);
                return redirect()->back();
            }
            curl_close($ch);

//        $response = @file_get_contents("http://dev-mongo.eserve.org.bd:8075/osspid/api?param=".$encodedOssPidRequestData, false);

            $decodedResponseData = json_decode($response);
            $responseCode = null;
            $messageCode = null;
            if (isset($decodedResponseData->osspidResponse->responseCode)) {
                $responseCode = $decodedResponseData->osspidResponse->responseCode;
                if (isset($decodedResponseData->osspidResponse->message->code)) {
                    $messageCode = $decodedResponseData->osspidResponse->message->code;
                }
            }
            // Bad request format
            if ($responseCode == 400) {
                DB::rollback();
                Session::flash('error', 'Bad request format for OSSPID [OSSPID-400]');
                return \redirect()->back();
            }
            elseif ($responseCode == 401) {
                // Unauthorized user access tried
                // invalid client id and secret key

                if ($messageCode == 40100) {
                    Session::flash('error', 'Invalid client information, please recheck client id, secret key. [OSSPID-40100]');
                } elseif ($messageCode == 40101) {
                    Session::flash('error', 'Invalid client information, please recheck client id, secret key. [OSSPID-40101]');
                }
                DB::rollback();
                return \redirect()->back();
            } // if email already exists in OSSPID then this is verified user
            elseif ($responseCode == 412) {
                if ($messageCode == 41200) {
                    Session::flash('success', 'Your account created successfully,  You may login using previous OSSPID password. [OSSPID-41200]');
                } elseif ($messageCode == 41201) {
                    Session::flash('error', 'Data validation exception occurred. [OSSPID-41201]');
                    DB::rollback();
                    return \redirect()->back();
                } elseif ($messageCode == 41203) {
                    Session::flash('error', 'Block-Chain server error occurred. [OSSPID-41203]');
                    DB::rollback();
                    return \redirect()->back();
                }
            } elseif ($responseCode == 200) {
                // successful response

                Session::flash('success', "Your account created successfully, Your account information has been sent into <b>(" . $user->user_email . ")</b>, You may login into BIDA-OSS through OSSPID.");
            } else {
                DB::rollback();
                Session::flash('error', 'Something went wrong [OSS-420]');
                return \redirect()->back();
            }

            $user->user_verification = 'yes';
            $user->save();

            DB::commit();
            return redirect('login');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1059]');
            return \redirect()->back();
        }
    }


    /*
     * edit individual user from admin panel
     */
    public function edit($id)
    {
        $user_id = Encryption::decodeId($id);
//        ACL must be modified for IT admin edit permission
        if (!ACL::getAccsessRight('user', 'E', $user_id))
            die('no access right! [UC-994]');
        $users = Users::where('id', $user_id)->first();

        $countries = Countries::where('country_status', 'Yes')->orderby('nicename')->lists('nicename', 'id');
        $nationalities = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
        $divisions = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        list($user_type) = explode('x', $users->user_type);
        $districts = ['' => 'Select one'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_id')->all();
        $passport_types = [
            'ordinary' => 'Ordinary',
            'diplomatic' => 'Diplomatic',
            'official' => 'Official',
        ];
        $logged_in_user_type = CommonFunction::getUserType();
        $companyIds = explode(',', $users->company_ids);
        if ($user_type == '11') {
            $bank_name = Bank::whereIn('id', $companyIds)->pluck('name');
        } else {
            $bank_name = '';
        }
        $user_type_part = explode('x', $logged_in_user_type);
        $edit_user_type = UserTypes::where('id', $users->user_type)->pluck('type_name');

        $IT_users = array('2x201', '2x202', '2x203', '2x205');
        if ($logged_in_user_type == '2x201') { // 2x201 for IT admin
            if (in_array($users->user_type, $IT_users)) {
                $user_types = [$users->user_type => $edit_user_type] + UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
                        ->orderBy('type_name')->lists('type_name', 'id')
                        ->all();
            } else {
                $user_types = [$users->user_type => $edit_user_type];
            }
        } else {
            $user_types = [$users->user_type => $edit_user_type] + UserTypes::where('id', 'LIKE', "$user_type_part[0]x" . substr($user_type_part[1], 0, 2) . "_")
                    ->where('id', 'NOT LIKE', "$user_type_part[0]_" . substr($user_type_part[1], 0, 2) . "0")
                    ->where('id', '!=', '1X101')
                    ->orderBy('type_name')->lists('type_name', 'id')
                    ->all();
        }
        $branch_list = array();
        if ($user_type == '11') {
            $branch_list = BankBranch::whereIn('bank_id', $users->company_ids)->orderBy('name', 'ASC')->lists('name', 'id')->all();
        }
        return view('Users::edit', compact("users", "user_types", 'countries', 'nationalities', 'logged_in_user_type', 'divisions', 'districts', 'passport_types', 'bank_name', 'branch_list'));
    }

    public function update($id, Request $request)
    {
        $user_id = Encryption::decodeId($id);

        // ACL must be modified for IT admin update permission
        if (!ACL::getAccsessRight('user', 'E', $user_id))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-986]');

        $rules = [];
        $rules['user_first_name'] = 'required';
        $rules['designation'] = 'required';
        $rules['user_gender'] = 'required';
        $rules['user_DOB'] = 'required|date';
        $rules['user_phone'] = 'required';
        $rules['user_email'] = 'required|email|unique:users,user_email,'.$user_id;
        $rules['country_id'] = 'required';
        $rules['nationality'] = 'required';
        $rules['road_no'] = 'required';
        $rules['post_code'] = 'required';

        $rules['nationality_type'] = 'required|in:bangladeshi,foreign';
        $rules['identity_type_bd'] = 'required_if:nationality_type,==,bangladeshi|in:nid,tin';
        $rules['identity_type_foreign'] = 'required_if:nationality_type,==,foreign|in:passport,tin';

//        $rules['department'] = 'required_if:user_type,4x404';
//        $rules['desk'] = 'required_if:user_type,4x404';

        $rules['company_id'] = 'required_if:user_type,5x505';

        $rules['division'] = 'required_if:nationality,18|integer';
        $rules['district'] = 'required_if:nationality,18|integer';
        $rules['police_station'] = 'required_if:nationality,18|integer';

        $rules['state'] = 'required_unless:nationality,18';
        $rules['province'] = 'required_unless:nationality,18';

        $rules['passport_nationality'] = 'required_if:identity_type_foreign,==,passport|integer';
        $rules['passport_type'] = 'required_if:identity_type_foreign,==,passport|in:ordinary,diplomatic,official';
        $rules['passport_no'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_surname'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_given_name'] = 'required_if:identity_type_foreign,==,passport';
        $rules['passport_DOB'] = 'required_if:identity_type_foreign,==,passport|date|date_format:d-M-Y';
        $rules['passport_date_of_expire'] = 'required_if:identity_type_foreign,==,passport|date|date_format:d-M-Y';

        $messages = [];
        $this->validate($request, $rules, $messages);

        try {

            DB::begintransaction();

            $identity_type = 'none';
            if(!empty($request->get('identity_type_bd'))) {
                $identity_type = $request->get('identity_type_bd');
            } elseif (!empty($request->get('identity_type_foreign'))) {
                $identity_type = $request->get('identity_type_foreign');
            }

            $nationality_type = $request->get('nationality_type');

            $data = array(
                'user_first_name' => $request->get('user_first_name'),
                'user_middle_name' => $request->get('user_middle_name'),
                'user_last_name' => $request->get('user_last_name'),
                'designation' => $request->get('designation'),
                'user_gender' => $request->get('user_gender'),
                'user_DOB' => CommonFunction::changeDateFormat($request->get('user_DOB'), true),
                'user_phone' => $request->get('user_phone'),
                'user_number' => $request->get('user_number'),
                //'user_email' => $request->get('user_email'),
                //'user_type' => $request->get('user_type'),
                //'desk_id' => $request->get('desk'),
                //'department_id' => $request->get('department'),

                'nationality_type' => $nationality_type,
                'identity_type' => $identity_type,

                'country_id' => $request->get('country_id'),
                'nationality_id' => $request->get('nationality'),

                'road_no' => $request->get('road_no'),
                'post_code' => $request->get('post_code'),
            );

            if ($nationality_type === 'bangladeshi') {
                $data['division'] = $request->get('division');
                $data['district'] = $request->get('district');
                $data['thana'] = $request->get('police_station');
                $data['post_office'] = $request->get('post_office');
            } elseif ($nationality_type === 'foreign') {
                $data['state'] = $request->get('state');
                $data['province'] = $request->get('province');
            }

            if ($identity_type === 'nid') {
                $data['user_nid'] = $request->get('user_nid');
            } elseif ($identity_type === 'tin') {
                $data['user_tin'] = $request->get('user_tin');
            } elseif ($identity_type === 'passport') {
                $data['passport_no'] = $request->get('passport_no');
                $data['passport_nationality_id'] = $request->get('passport_nationality');
                $data['passport_type'] = $request->get('passport_type');
                $data['passport_surname'] = $request->get('passport_surname');
                $data['passport_given_name'] = $request->get('passport_given_name');
                $data['passport_personal_no'] = $request->get('passport_personal_no');
                $data['passport_DOB'] = CommonFunction::changeDateFormat($request->get('passport_DOB'), true);
                $data['passport_date_of_expire'] = CommonFunction::changeDateFormat($request->get('passport_date_of_expire'), true);
            }

            UsersModelEditable::find($user_id)->update($data);

            \Session::flash('success', "User's profile has been updated successfully!");
            DB::commit();

            //for audit log
            CommonFunction::createAuditLog('userProfileUpdate', $request, $user_id);

            //return redirect('users/edit/' . $id);
            return redirect('users/lists');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1060]');
            return Redirect::back()->withInput();
        }
    }

    public function companyInfoSave(Request $request)
    {
        if (!ACL::getAccsessRight('user', 'E')) {
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-987]');
        }
        $this->validate($request, [
            'company_name' => 'required|regex:/^[a-zA-Z\'\. \&]+$/',
            'division' => 'required',
            'district' => 'required',
            'thana' => 'required',
        ]);
        try {

            $companyData = new CompanyInfo();
            $companyData->company_name = $request->get('company_name');
            $companyData->division = $request->get('division');
            $companyData->district = $request->get('district');
            $companyData->thana = $request->get('thana');
            $companyData->save();

            \Session::flash('success', "Company information save successfully!");
            return Redirect::back();

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1029]');
            return Redirect::back()->withInput();
        }
    }

    /*
     * function for approve a user
     */
    public function approveUser($id, Request $request)
    {
        if (!ACL::getAccsessRight('user', '-APV-')) {
            die('no access right! [UC-995]');
        }

        try {

            DB::beginTransaction();
            $user_id = Encryption::decodeId($id);
            $user = Users::find($user_id);

            // if user don't verify his email then sysadmin can't approve this user
            if (($user->user_agreement == 0) || ($user->user_verification == 'no')) {
                DB::rollback();
                Session::flash('error', "Sorry ! This user has not verified his email yet. [UC-1030]");
                return redirect('users/lists');
            }


            // Check company is valid or not
            $company_info = CompanyInfo::find($user->working_company_id);
            if (empty($company_info)) {
                DB::rollback();
                Session::flash('error', "Invalid company id. Please contact with system support team. [UC1274]");
                return redirect('users/lists');
            }


            // this code will be uncommented if any issue arise
            // This code was developed for approving company at the time of user approved.

            /*
            if (in_array($user->user_type, ['5x505'])) {
                $company_idsArray = explode(',', $user->company_ids);
                if (count($company_idsArray) > 0) {
                    // company approved when this company is not rejected before
                    $company_info = CompanyInfo::find($company_idsArray[0]);
                    if (empty($company_info)) {
                        DB::rollback();
                        Session::flash('error', "Invalid company id. Please contact with system support team. [UC1274]");
                        return redirect('users/lists');
                    }

                    if ($company_info->is_rejected == 'yes') {
                        DB::rollback();
                        Session::flash('error', "User's company has already been rejected earlier [UC1275]");
                        return redirect('users/lists');
                    }
                    // this code will be uncommented if any issue arise
                    $company_info->is_approved = 1;
                    $company_info->company_status = 1;
                    $company_info->save();
                }
            }
            */

            $user->user_status = 'active';
            $user->is_approved = 1;
            $user->save();

            $receiverInfo[] = [
                'user_email' => $user->user_email,
                'user_phone' => $user->user_phone
            ];
            CommonFunction::sendEmailSMS('APPROVE_USER', [], $receiverInfo);
            DB::commit();

            \Session::flash('success', "The user has been approved successfully!");
            return redirect('users/lists');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1061]');
            return Redirect::back()->withInput();
        }
    }

    /*
     * function for reject a user
     */
    public function rejectUser($id, Request $request)
    {
        if (!ACL::getAccsessRight('user', '-REJ-'))
            die('no access right! [UC-996]');

        try {

            DB::beginTransaction();
            $user_id = Encryption::decodeId($id);
            $reject_reason = $request->get('reject_reason');
            $userData = Users::find($user_id);

            // user reject
            Users::where('id', $user_id)->update([
                'user_status' => 'rejected',
                'user_status_comment' => $reject_reason,
                'login_token' => '', // token null if this user is current login then logout
                'is_approved' => 0
            ]);


            // This code will uncommented when company need to approved from user sign-up
            // The company will be rejected if this user's company is still not approved
            /*
            $company_idsArray = explode(',', $userData->company_ids);
            if (count($company_idsArray) == 1) {
                if (in_array($userData->user_type, ['5x505', '6x606'])) {
                    CompanyInfo::where('id', $company_idsArray[0])->where('is_approved', '!=', 1)
                        ->update(['is_rejected' => 'yes']);
                }
            }
            */

            \Session::flash('error', "User's Profile has been Rejected Successfully!");

            $receiverInfo[] = [
                'user_email' => $userData->user_email,
                'user_phone' => $userData->user_phone
            ];
            $appInfo = [
                'reject_reason' => $reject_reason
            ];
            CommonFunction::sendEmailSMS('REJECT_USER', $appInfo, $receiverInfo);

            DB::commit();
            return redirect('users/lists');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1062]');
            return Redirect::back();
        }
    }

    //When completing registration, to get thana after selecting district
    public function get_thana_by_district_id(Request $request)
    {
        $district_id = $request->get('districtId');

        $thanas = AreaInfo::where('PARE_ID', $district_id)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $thanas];
        return response()->json($data);
    }

    public function profile_update(Request $request)
    {
        $userId = Encryption::decodeId($request->get('Uid'));
        if (!ACL::getAccsessRight('user', 'SPU', $userId)) {
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-988]');
        }

        $rules = [];
        $messages = [];

        $rules['user_first_name'] = 'required';
        $rules['designation'] = 'required';
        $rules['user_DOB'] = 'required|date|date_format:d-M-Y';
        $rules['user_phone'] = 'required';

        // Business category private
        $business_category = (Auth::user()->user_type == '5x505') ? Auth::user()->company->business_category : 0;

        if ($business_category == 1) {
            $rules['post_code'] = 'required';
            $rules['road_no'] = 'required';

            if (Auth::user()->nationality_id == '18' || Auth::user()->nationality == 'BD') {
                $rules['division'] = 'required|integer';
                $rules['district'] = 'required|integer';
                $rules['thana'] = 'required|integer';
                $rules['post_office'] = 'required';
            } else {
                $rules['state'] = 'required';
                $rules['province'] = 'required';
            }
        }

        $rules['applicant_photo_base64'] = 'required_without:applicant_photo';
        $rules['applicant_photo'] = 'required_without:applicant_photo_base64';

        if (Auth::user()->user_type == '4x404') {
            $rules['applicant_signature_base64'] = 'required_without:applicant_signature';
            $rules['applicant_signature'] = 'required_without:applicant_signature_base64';

            $messages['applicant_signature_base64.required_without'] = 'The applicant signature field is required.';
            $messages['applicant_signature.required_without'] = 'The applicant signature field is required.';
        }

        $messages['user_first_name.required'] = 'User’s first name field is required.';
        $messages['designation.required'] = 'Designation field is required.';
        $messages['user_DOB.required'] = 'Date of Birth field is required.';
        $messages['user_phone.required'] = 'Mobile Number field is required.';
        $messages['post_code.required'] = 'Post Code (number) field is required.';
        $messages['road_no.required'] = 'Address field is required.';
        $messages['division.required'] = 'Division field is required.';
        $messages['district.required'] = 'District field is required.';
        $messages['thana.required'] = 'Police Station field is required.';
        $messages['post_office.required'] = 'Post Office field is required.';
        $messages['state.required'] = 'State field is required.';
        $messages['province.required'] = 'Province/ City field is required.';

        $messages['applicant_photo_base64.required_without'] = 'The applicant photo field is required.';
        $messages['applicant_photo.required_without'] = 'The applicant photo field is required.';

        $this->validate($request, $rules, $messages);

        try {
            $data = [
                'user_first_name' => $request->get('user_first_name'),
                'user_middle_name' => $request->get('user_middle_name'),
                'user_last_name' => $request->get('user_last_name'),
                'user_DOB' => Carbon::createFromFormat('d-M-Y', $request->get('user_DOB'))->format('Y-m-d'),
                'user_phone' => $request->get('user_phone'),
                'user_number' => $request->get('user_number'),
                'designation' => $request->get('designation'),
            ];

            // Business category 2 = government
            if ($business_category != 2) {

                $data['post_code'] = $request->get('post_code');
                $data['road_no'] = $request->get('road_no');

                if (Auth::user()->nationality_id == '18' || Auth::user()->nationality == 'BD') {
                    $data['division'] = $request->get('division');
                    $data['district'] = $request->get('district');
                    $data['thana'] = $request->get('thana');
                    $data['post_office'] = $request->get('post_office');
                } else {
                    $data['state'] = $request->get('state');
                    $data['province'] = $request->get('province');
                }
            }

            $_file = $request->file('authorization_file');
            if ($request->hasFile('authorization_file')) {
                $original_file = $_file->getClientOriginalName();
                $_file->move('uploads', $original_file);
                $data['authorization_file'] = $original_file;
            }

            $prefix = date('Y_');

            // signature upload
            if (!empty($request->applicant_signature_base64)) {
                $path = 'users/signature/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $splited = explode(',', substr($request->get('applicant_signature_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImageEncode = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 80));
                $base64ResizeImage = base64_decode($base64ResizeImageEncode);
                $applicant_signature_name = trim(uniqid($prefix, true) . '.' . 'jpeg');
                file_put_contents($path . $applicant_signature_name, $base64ResizeImage);
                $data['signature'] = $applicant_signature_name;
                $data['signature_encode'] = $base64ResizeImageEncode; // previous store 150x40
            }

            // Profile Image photo upload
            if (!empty($request->applicant_photo_base64)) {
                $path = public_path() . '/users/upload/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $splited = explode(',', substr($request->get('applicant_photo_base64'), 5), 2);
                $imageData = $splited[1];
                $base64ResizeImage = base64_encode(ImageProcessing::resizeBase64Image($imageData, 300, 300));
                $base64ResizeImage = base64_decode($base64ResizeImage);

                $applicant_photo_name = trim(uniqid($prefix, true) . '.' . 'jpeg');
                file_put_contents($path . $applicant_photo_name, $base64ResizeImage);
                $data['user_pic'] = $applicant_photo_name;
            }

            UsersModelEditable::find($userId)->update($data);

            //for audit log
            CommonFunction::createAuditLog('userProfileUpdate', $request, $userId);

            \Session::flash('success', 'Your profile has been updated successfully.');
            return redirect('users/profileinfo');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-5025]');
            return Redirect::back();
        }
    }


    /*
     * forcefully logout a user by admin
     */
    public function forceLogout($user_id)
    {
        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-989]');

        $id = Encryption::decodeId($user_id);
        $loginController = new LoginController();
        $loginController::killUserSession($id);
        Session::flash('success', "User has been successfully logged out by force!");
        return redirect('users/lists');
    }

    /*
     * forget-password
     */
    public function forgetPassword()
    {
        return view('Users::forget-password');
    }

    //For Forget Password functionality
    public function resetForgottenPass(Request $request)
    {
        $v = Validator::make($request->all(), [
            'g_captcha_response' => 'required',
        ]);
        if ($v->fails()) {
            return response()->json(['error' => true, 'messages' => 'The g-recaptcha-response field is required.', 'id' => '']);
        }
        $email = $request->get('user_email');
        $users = DB::table('users')
            ->where('user_email', $email)
            ->first();


        if (!empty($users)) {

            if ($users->user_status == 'inactive' && $users->user_verification == 'no') {
//                \Session::flash('error', 'No user with this email is existed in our current database. Please sign-up first');
//                return Redirect('forget-password')->with('status', 'error');
                $messages = '<div class="alert alert-danger alert-dismissible"> <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>No user with this email is existed in our current database. Please sign-up first</div>';
                return response()->json(['error' => true, 'messages' => $messages, 'id' => '']);
            }
            if ($users->social_login == 1) {
                $messages = '<div class="alert alert-danger alert-dismissible"> <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</buttonThis option is not allowed for the user who has signed-up from Google or Facebook!</div>';
                return response()->json(['error' => true, 'messages' => $messages, 'id' => '']);
//                \Session::flash('error', 'This option is not allowed for the user who has signed-up from Google or Facebook!');
//                return Redirect('forget-password')->with('status', 'error');
            }

            $token_no = hash('SHA256', "-" . $email . "-");
            $update_token_in_db = array(
                'user_hash' => $token_no,
            );
            DB::table('users')
                ->where('user_email', $email)
                ->update($update_token_in_db);

            $encrytped_token = Encryption::encode($token_no);

            //$messages =  '<div class="alert alert-success alert-dismissible"> <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Please check your email to verify Password Change</div>';
            //return response()->json(['error'=> false, 'messages'=> $messages , 'id'=> Encryption::encodeId($id)]);

        } else {
            $messages = '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button> No user with this email is existed in our current database. Please sign-up first</div>';
            return response()->json(['error' => true, 'messages' => $messages, 'id' => '']);
        }
    }

    // Forgotten Password reset after verification
    public function verifyForgottenPass($token_no)
    {
        $TOKEN_NO = Encryption::decode($token_no);

        $user = UsersModel::where('user_hash', $TOKEN_NO)->first();

        if ($user) {
            $user_password = str_random(10);

            DB::table('users')
                ->where('user_hash', $TOKEN_NO)
                ->update(array('password' => Hash::make($user_password)));

            \Session::flash('success', 'Your password has been reset successfully! Please check your mail for access information.');
            return redirect('login');
        } else { /* If User couldn't be found */
            \Session::flash('error', 'Invalid token! No such user is found. Please sign up first. [UC-1031]');
            return redirect('signup');
        }
    }


    public function checkingEmailQueueForForgetPassword(Request $request)
    {
        $email_queue_id = Encryption::decodeId($request->get('id'));
        $getStatus = EmailQueue::where('id', $email_queue_id)->first(['email_status']);
        if ($getStatus->email_status == 1) {
            $messages = '<div class="alert alert-success alert-dismissible"> <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>Please check your email to verify Password Change</div>';
            return response()->json(['responseCode' => 1, 'messages' => $messages, 'id' => '']);
        } else {
            $messages = 'wait';
            return response()->json(['responseCode' => 0, 'messages' => $messages, 'id' => '']);
        }
    }

    /*
     * user support
     */
//    public function support()
//    {
//        $faqs = Faq::leftJoin('faq_multitypes', 'faq.id', '=', 'faq_multitypes.faq_id')
//            ->leftJoin('faq_types', 'faq_multitypes.faq_type_id', '=', 'faq_types.id')
//            ->where('status', 'public')
//            ->where('faq_types.name', 'login')
//            ->get(['question', 'answer', 'status', 'faq_type_id as types', 'name as faq_type_name', 'faq.id as id']);
//
//        return view("Users::support", compact('faqs'));
//    }

    public function getUserSession(Request $request)
    {
        if (Auth::user()) {
            $checkSession = UsersModel::where(['id' => Auth::user()->id, 'login_token' => Encryption::encode(Session::getId())])->count();
            if ($checkSession >= 1) {
                $data = ['responseCode' => 1, 'data' => 'matched'];
            } else {
                UtilFunction::keycloakLogoutCurl();
                $data = ['responseCode' => -1, 'data' => 'not matched'];
            }
        } else {
            UtilFunction::keycloakLogoutCurl();
            $data = ['responseCode' => -1, 'data' => 'closed'];
        }

        return response()->json($data);
    }

//System admin delegation process
    public function delegations($id)
    {
        $delegate_to_user_id = Encryption::decodeId($id);

        // check this user is delegated or not ???
        $isDelegate = Users::where('id', $delegate_to_user_id)->pluck('delegate_to_user_id');
        if ($isDelegate != 0) {
            $info = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                ->leftJoin('user_types as ut', 'ut.id', '=', 'users.user_type')
                ->where('users.id', $isDelegate)
                ->first(['users.id', DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'users.desk_id', 'ut.type_name',
                    'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                    'designation', 'user_phone'
                ]);
            Session::put('sess_delegated_user_id', $isDelegate);
        } else {
            $info = UsersModel::leftJoin('user_desk as ud', 'ud.id', '=', 'users.desk_id')
                ->leftJoin('user_types as ut', 'ut.id', '=', 'users.user_type')
                ->where('users.id', $delegate_to_user_id)
                ->first(['users.id', DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'users.desk_id', 'ut.type_name',
                    'user_email', 'user_phone', 'users.user_type', 'ud.desk_name',
                    'designation', 'user_phone'
                ]);

        }

        $desk_id = $info->desk_id;
        $user_type = $info->user_type;

        if ($desk_id == '' || $desk_id == 0) {
            Session::flash('error', 'Desk id is empty! [UC-1032]');
            return redirect("users/view/" . Encryption::encodeId($delegate_to_user_id));
        }

        $deligate_to_desk_data = UserTypes::where('id', $user_type)->first(['delegate_to_types']);
        if (count($deligate_to_desk_data) > 0) {
            $deligate_to_type = explode(',', $deligate_to_desk_data->delegate_to_types);
            $designation = UserTypes::whereIn('id', $deligate_to_type)->lists('type_name', 'id');
        }

        return view("Users::delegation", compact('isDelegate', 'delegate_to_user_id', 'info', 'designation'));


    }


    public function storeDelegation(Request $request)
    {

        if (!ACL::getAccsessRight('user', 'E'))
            abort(401, 'You have no access right!. Contact with system admin for more information. [UC-990]');

        try {

            $delegate_by_user_id = Auth::user()->id;
            $delegate_to_user_id = $request->get('delegated_user');
            $delegate_from_user_id = $request->get('user_id');


            $dependend_on_from_userid = UsersModel::where('delegate_to_user_id', '=', $delegate_from_user_id)->get(['id', 'delegate_to_user_id']);

            DB::beginTransaction();

            foreach ($dependend_on_from_userid as $dependentUser) {
                $updateDependent = UsersModel::findOrFail($dependentUser->id);
                $updateDependent->delegate_to_user_id = $delegate_to_user_id;
                $updateDependent->delegate_by_user_id = $delegate_by_user_id;
                $updateDependent->save();

                $delegation = new Delegation();
                $delegation->delegate_form_user = $dependentUser->id;
                $delegation->delegate_by_user_id = $delegate_by_user_id;
                $delegation->delegate_to_user_id = $delegate_to_user_id;
                $delegation->remarks = $request->get('remarks');
                $delegation->status = 1;
                $delegation->save();

            }

            $data = [
                'delegate_form_user' => $delegate_from_user_id,
                'delegate_by_user_id' => $delegate_by_user_id,
                'delegate_to_user_id' => $delegate_to_user_id,
                'remarks' => $request->get('remarks'),
                'status' => 1
            ];
            Delegation::create($data);

            $udata = array(
                'delegate_to_user_id' => $delegate_to_user_id,
                'delegate_by_user_id' => $delegate_by_user_id
            );

            $complt = UsersModel::where('id', $delegate_from_user_id)
                ->orWhere('delegate_to_user_id', $delegate_from_user_id)
                ->update($udata);

            if ($complt) {
                DB::commit();
                Session::flash('success', 'Delegation process completed Successfully');
                return redirect("users/lists");
            } else {
                DB::rollback();
                Session::flash('error', 'Delegation Not completed. [UC-1033]');
                return redirect("users/view/" . Encryption::encodeId($delegate_from_user_id));
            }

        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1063]');
            return Redirect::back();
        }


    }

    public function getDeligatedUserInfos(Request $request)
    {
        $type_id = $request->get('designation');
        $delegate_form_user_id = $request->get('delegate_form_user_id');
        $result = Users::where('user_type', '=', $type_id)
            ->Where(function ($result) use ($delegate_form_user_id) {
                return $result->where('delegate_to_user_id', '=', null)
                    ->orWhere('delegate_to_user_id', '=', 0);
            })
            ->where('id', '!=', $delegate_form_user_id)
            ->get([DB::raw("CONCAT(user_first_name, ' ', user_middle_name, ' ', user_last_name) as user_full_name"), 'id']);
        echo json_encode($result);
    }

    public function twoStep()
    {
        try {
            return view("Users::two-step");
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [UC-1034]');
            return Redirect::back()->withInput();
        }
    }

    public function checkTwoStep(Request $request)
    {
        try {
            $steps = $request->get('steps');
            $code = rand(1000, 9999);
            $token = $code . '-' . Auth::user()->id;
            $encrypted_token = Encryption::encode($token);
            UsersModelEditable::where('user_email', Auth::user()->user_email)->update(['auth_token' => $encrypted_token]);
            $emailQueueId = EmailQueue::where('user_id', Auth::user()->id)->orderby('id', 'DESC')->first(['id']);
            Session::put('email_queue_id', $emailQueueId->id);
            if ($request->get('req_dta') != null) {
                $req_dta = $request->get('req_dta');
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone', 'req_dta'));
            } else {
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone'));
            }
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1064]');
            return Redirect::back()->withInput();
        }
    }

    public function verifyTwoStep(Request $request)
    {
        $this->validate($request, [
            'security_code' => 'required',
        ]);

        try {
            $security_code = trim($request->get('security_code'));
            $user_id = Auth::user()->id;
            $token = $security_code . '-' . $user_id;
            $encrypted_token = Encryption::encode($token);
            $count = UsersModel::where('id', $user_id)->where(['auth_token' => $encrypted_token])->count();

            UsersModel::where('id', $user_id)->update(['auth_token' => '']);
            // Profile updated related
            if ($request->get('req_dta') != null) {
                $req_dta = (array)json_decode(Encryption::decode($request->get('req_dta')));

                if ($count > 0) {
                    //=====================updating information=========================
                    $auth_token_allow = 0;
                    if (isset($req_dta['auth_token_allow']) == '1') {
                        $auth_token_allow = 1;
                    }

                    if (substr($req_dta['user_phone'], 0, 2) == '01') {
                        $mobile_no = '+88' . $req_dta['user_phone'];
                    } else {
                        $mobile_no = $req_dta['user_phone'];
                    }
                    $type = explode('x', Auth::user()->user_type);
                    $is_address_change = 0;
                    if (in_array($type[0], CommonFunction::firstTimeAddressChangeUserType())) {
                        if (isset($req_dta['district']) || isset($req_dta['thana'])) {
                            $is_address_change = 1;
                        }
                    }
                    $userData = UsersModelEditable::find(Auth::user()->id);
                    $userData->user_full_name = $req_dta['user_full_name'];
                    $userData->auth_token_allow = $auth_token_allow;
                    $userData->user_DOB = Carbon::createFromFormat('d-M-Y', $req_dta['user_DOB'])->format('Y-m-d');
                    $userData->user_phone = $mobile_no;
                    $userData->is_address_change = $is_address_change;
                    $userData->district = $req_dta['district'];
                    $userData->thana = $req_dta['thana'];
                    $userData->save();
                    $this->entryAccessLog();
                    //----------------------end----------------------------
                    Session::flash('success', "Updated profile successfully");
                    return redirect('users/profileinfo');
                } else {
                    Session::flash('error', "Security Code doesn't match. [UC-1035]");
                    return redirect('/users/two-step/profile-update?req=' . $request->get('req_dta'));
                }
            } else {
                // Default two step verification


                if ($count > 0) {
                    $this->entryAccessLog();
                    $project_name = config('app.project_name');
                    Session::flash('success', "Security match successfully! Welcome to .$project_name. platform");
                    return redirect('dashboard');
                } else {

                    Session::flash('error', "Security Code doesn't match. [UC-1036]");
                    return redirect('users/two-step');
                }
            }
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1065]');
            return Redirect::back()->withInput();
        }
    }

    public function getServerTime()
    {
        $databaseTime = DB::select("SELECT NOW() as db_time");
        $db_date = date('d-M-Y', strtotime($databaseTime[0]->db_time));
        $db_time = date('g:i:s A', strtotime($databaseTime[0]->db_time));

        $app_date = date('d-M-Y');
        $app_time = date('g:i:s A');

        $dateTime = [
            'db_date' => $db_date,
            'db_time' => $db_time,
            'app_date' => $app_date,
            'app_time' => $app_time,
        ];

        return $dateTime;
    }

    public function entryAccessLog()
    {
        // access_log table.
        $str_random = str_random(10);
        $insert_id = DB::table('user_logs')->insertGetId(
            array(
                'user_id' => Auth::user()->id,
                'login_dt' => date('Y-m-d H:i:s'),
                'ip_address' => \Request::getClientIp(),
                'access_log_id' => $str_random
            )
        );

        Session::put('access_log_id', $str_random);
    }

    //View uploaded file Authorization letter
    public function viewAuthLetter($encrypted_doc_id)
    {
        try {
            $upload_doc = Encryption::decode($encrypted_doc_id);
            return view("Users::view-upload-doc", compact('upload_doc'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC1090]');
            return Redirect::back()->withInput();
        }
    }

    public function resendVerification(Request $request, $user_email = '')
    {

        if (empty($user_email) or $user_email == '') {
            $rules = [];
            $rules['email'] = 'required|email';
            $rules['g-recaptcha-response'] = 'required';

            $messages = [];
            $this->validate($request, $rules, $messages);
        }

        if ($user_email != '') {
            $decoded_user_email = Encryption::decode($user_email);
        }

        try {
            $mailId = $request->get('email');
            if ($user_email != '') {
                $mailId = $decoded_user_email;
            }
            $check = Users::where('user_email', $mailId)->first();
            if (empty($check)) {
                Session::flash('error', 'Invalid email. [UC-1037]');
                return \redirect()->back();
            }

            if ($check->user_verification == 'yes') {
                Session::flash('error', 'This user already verified. [UC-1038]');
                return \redirect()->back();
            }
            $token_no = hash('SHA256', "-" . $mailId . "-");
            $encrypted_token = Encryption::encodeId($token_no);
            $data = array(
                'user_hash' => $encrypted_token,
                'user_hash_expire_time' => new Carbon('+6 hours')
            );

            $receiverInfo[] = [
                'user_email' => $mailId,
                'user_phone' => $check->user_phone
            ];

            $appInfo = [
                'verification_link' => url('users/verify-created-user/' . ($encrypted_token))
            ];

            CommonFunction::sendEmailSMS('CONFIRM_ACCOUNT', $appInfo, $receiverInfo);

            Users::where('user_email', $mailId)
                ->update($data);

            Session::flash('success', 'Verification email resent successfully.');
            return \redirect()->back();

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[UC-1066]');
            return Redirect::back()->withInput();
        }
    }

}
