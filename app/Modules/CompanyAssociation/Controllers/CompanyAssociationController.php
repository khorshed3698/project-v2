<?php

namespace App\Modules\CompanyAssociation\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\CompanyAssociation\Models\CompanyAssociation;
use App\Modules\CompanyAssociation\Requests\CompanyAssociationRequest;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use yajra\Datatables\Datatables;

class CompanyAssociationController extends Controller
{
    protected $aclName;

    public function __construct()
    {
//        if (Session::has('lang')) {
//            App::setLocale(Session::get('lang'));
//        }
        $this->aclName = 'CompanyAssociation';
    }

    public function getList()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information. [CAC-1001]');

        return view("CompanyAssociation::list");
    }

    public function getCompanyAssociationList()
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1002]');

        $companyAssociationList = CompanyAssociation::companyAssociationList();
        $process_mode = ACL::getAccsessRight($this->aclName, '-UP-');
        return Datatables::of($companyAssociationList)
            ->editColumn('requested_company_id', function ($list) {
                return $list->company_name . (!empty($list->approved_user_type) ? ' (' . $list->approved_user_type . ')' : '');
            })
            ->editColumn('business_category', function ($list) {
                return ($list->business_category == 1) ? 'Private' : 'Government';
            })
            ->editColumn('status_id', function ($list) {
                if ($list->status_id == '1') {
                    $status_class = 'class="label label-primary" ';
                    $status_text = 'Submitted';
                } elseif ($list->status_id == '6') {
                    $status_class = 'class="label label-danger" ';
                    $status_text = 'Rejected';
                } elseif ($list->status_id == '25') {
                    $status_class = 'class="label label-success" ';
                    $status_text = 'Approved';
                }
                return '<span ' . $status_class . '><b>' . $status_text . '</b></span>';
            })
            ->editColumn('status', function ($list) {
                if ($list->status == '1') {
                    $status_class = 'class="label label-info" ';
                    $status_text = 'Active';
                } elseif ($list->status == '0') {
                    $status_class = 'class="label label-warning" ';
                    $status_text = 'Inactive';
                }
                return '<span ' . $status_class . '><b>' . $status_text . '</b></span>';
            })
            ->addColumn('action', function ($list) use ($process_mode) {

                $btn = '';
                $btn .= "<a target='_blank' class='btn btn-xs btn-primary' href='/company-association/open/" . Encryption::encodeId($list->id) . "'><i class='fa fa-folder-open'></i> Open</a>";
                if ($process_mode) {
                    if ($list->status_id == 25 and $list->request_type == 'Add' and $list->status == 0) {
                        $btn .= " <a class='btn btn-xs btn-success' href='/company-association/status/" . Encryption::encodeId($list->id) . "/" . Encryption::encodeId(1) . "'><i class='fa fa-unlock'></i> Activate</a>";
                    } elseif ($list->status_id == 25 and $list->request_type == 'Add' and $list->status == 1) {
                        $btn .= " <a class='btn btn-xs btn-danger' href='/company-association/status/" . Encryption::encodeId($list->id) . "/" . Encryption::encodeId(0) . "'><i class='fa fa-lock'></i> Deactivate</a>";
                    }
                }

                return $btn;

            })
            ->editColumn('application_date', function ($list) {
                return date('d-M-Y', strtotime($list->application_date));
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function appForm()
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1003]');

        try {

            $current_company_ids = CommonFunction::getUserCompanyAllWithZeroWithoutEloquent();
            $user_id = Auth::user()->id;
            $current_company_lists = CompanyInfo::leftJoin('company_association_request', function ($on) use ($user_id) {
                $on->on('company_association_request.requested_company_id', '=', 'company_info.id')
                    ->where('company_association_request.user_id', '=', $user_id);
            })->whereIn('company_info.id', $current_company_ids)
                ->select(DB::raw("concat(company_name, ' (', ifnull(company_association_request.approved_user_type, 'N/A'), ')') as company_name"), 'company_info.id')
                ->lists('company_name', 'company_info.id')->toArray();

            $companyList = CompanyInfo::where('company_status', 1)
                ->where('is_approved', 1)
                ->where('is_eligible', 1)
                ->where('is_rejected', 'no')
                ->get(['company_name', 'id']);

            return view('CompanyAssociation::create-form', compact('current_company_lists', 'current_company_ids', 'companyList', 'companyIdsArray'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1011]');
            return redirect()->back();
        }
    }

    public function appStore(CompanyAssociationRequest $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-A-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1004]');

        try {

            DB::beginTransaction();

            $currentCompanyIds = implode(CommonFunction::getUserCompanyAllWithZeroWithoutEloquent(), ',');

            if ($request->get('request_type') === 'Remove') {
                $company_id = Encryption::decodeId($request->get('remove_req_company_id'));
                $success_message = 'Company association successfully submitted for removal.';
            } elseif ($request->get('request_type') === 'Add') {
                if ($request->has('company_types')) {
                    $companyData = CompanyInfo::where('company_name', $request->get('company_name_en'))
                        ->orWhere(function ($query) use ($request) {
                            $query->where('company_name_bn', $request->get('company_name_bn'))
                                ->where('company_name_bn', '!=', '')
                                ->whereNotNull('company_name_bn');
                        })
                        ->first();
                    if ($companyData) {
                        if ($companyData->is_rejected == 'no') {
                            DB::rollback();
                            Session::flash('error', 'Your company name is duplicate! Please give a unique name. [CAC-1012]');
                            return Redirect::back()->withInput();
                        } else {
                            $companyData->business_category = $request->get('business_category');
                            $companyData->company_name = trim($request->get('company_name_en'));
                            $companyData->company_name_bn = trim($request->get('company_name_bn'));
                            $companyData->created_by = Auth::user()->id;
                            $companyData->created_at = date('Y-m-d H:i:s');
                            $companyData->is_rejected = 'no'; // again reset the rejected status
                            $companyData->is_approved = 1; // direct approve company
                            $companyData->is_eligible = 0; //
                            $companyData->company_status = 1; // active company
                            $companyData->save();
                            $company_id = $companyData->id;
                        }
                    } else {
                        $company_id = DB::table('company_info')->insertGetId([
                            'business_category' => $request->get('business_category'),
                            'company_name' => trim($request->get('company_name_en')),
                            'company_name_bn' => trim($request->get('company_name_bn')),
                            'is_approved' => 1,
                            'is_eligible' => 0,
                            'company_status' => 1,
                            'created_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->id
                        ]);
                    }
                    $success_message = 'Company association successfully submitted. Please contact the corresponding authorized personnel.';
                } else {
                    $company_id = Encryption::decodeId($request->get('requested_company_id'));
                    $success_message = 'Company association successfully submitted. Please contact the corresponding authorized personnel of the organization.';
                }
            }
            $companyAssociation = CompanyAssociation::firstOrNew([
                'user_id' => Auth::user()->id,
                'requested_company_id' => $company_id,
                'request_type' => $request->get('request_type')
            ]);
            $companyAssociation->current_company_ids = $currentCompanyIds;
            $companyAssociation->user_remarks = $request->get('user_remarks');
            $companyAssociation->request_type = $request->get('request_type');
            if ($request->has('company_types')) {
                $companyAssociation->company_type = 'new';
                $companyAssociation->business_category = $request->get('business_category');
                $companyAssociation->company_name_en = trim($request->get('company_name_en'));
                $companyAssociation->company_name_bn = trim($request->get('company_name_bn'));
            } else {
                $existing_company_info = CompanyInfo::where('id', $company_id)->first(['id','business_category','company_name','company_name_bn']);
                $companyAssociation->company_type = 'existing';
                $companyAssociation->business_category = $existing_company_info->business_category;
                $companyAssociation->company_name_en = $existing_company_info->company_name;
                $companyAssociation->company_name_bn = $existing_company_info->company_name_bn;
            }

            if ($request->hasFile('authorization_letter')) {
                $yearMonth = date("Y") . "/" . date("m") . "/";
                $path = 'users/upload/' . $yearMonth;
                $_authorization_file = $request->file('authorization_letter');

                $full_name_concat = trim(CommonFunction::getUserFullName());
                $full_name = str_replace(' ', '_', $full_name_concat);

                $authorization_file = ($company_id . '_' . $full_name . '_' . rand(0, 9999999) . '.' . $_authorization_file->getClientOriginalExtension());
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $_authorization_file->move($path, $authorization_file);
                $authorization_file_path = $yearMonth . $authorization_file;
                $companyAssociation->authorization_letter = $authorization_file_path;
            }
            $companyAssociation->application_date = date('Y-m-d H:i:s');
            $companyAssociation->status_id = 1;
            $companyAssociation->save();

            DB::commit();

            Session::flash('success', $success_message);

            return redirect('company-association/list');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1013]');
            return redirect()->back()->withInput();
        }
    }

    public function appOpen($requestId)
    {
        if (!ACL::getAccsessRight($this->aclName, '-V-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1005]');

        try {
            $decodedRequestId = Encryption::decodeId($requestId);
            $companyAssociationRequest = CompanyAssociation::leftJoin('company_info', 'company_info.id', '=', 'company_association_request.requested_company_id')
                ->leftJoin('users', 'users.id', '=', 'company_association_request.user_id')
                ->select('company_association_request.*', 'company_info.company_name as company_en', 'company_info.company_name_bn as company_bn', DB::raw('CONCAT(users.user_first_name," ",users.user_middle_name," ",users.user_last_name ) as full_name'), 'users.user_email')
                ->where('company_association_request.id', $decodedRequestId)
                ->first();

            $user_id = $companyAssociationRequest->user_id;
            $requested_company_id = $companyAssociationRequest->requested_company_id;
            $previous_company_ids = explode(',', $companyAssociationRequest->current_company_ids);


//            $companyList = CompanyInfo::whereIn('id', $previousCompany_ids)
//                ->orWhere('id', $requestedCompany_id)
//                ->get(['company_name', 'id']);

            $previous_company_lists = CompanyInfo::leftJoin('company_association_request', function ($on) use ($user_id) {
                $on->on('company_association_request.requested_company_id', '=', 'company_info.id')
                    ->where('company_association_request.user_id', '=', $user_id)
                    ->where('company_association_request.request_type', '=', 'Add');
            })->whereIn('company_info.id', $previous_company_ids)
                ->get([DB::raw("concat(company_name, ' (', ifnull(company_association_request.approved_user_type, 'N/A'), ')') as company_name"), 'company_info.id']);


            $current_company = CompanyInfo::leftJoin('company_association_request', function ($on) use ($user_id) {
                $on->on('company_association_request.requested_company_id', '=', 'company_info.id')
                    ->where('company_association_request.created_by', '=', $user_id);
            })->where('company_info.id', $requested_company_id)
                ->first([DB::raw("concat(company_name, ' (', ifnull(company_association_request.approved_user_type, 'N/A'), ')') as company_name"), 'company_info.id']);


            $viewMode = 'off';
            if ($companyAssociationRequest->user_id == Auth::user()->id) {
                $viewMode = 'on';
            } elseif ($companyAssociationRequest->user_id != Auth::user()->id && $companyAssociationRequest->status_id != 1) {
                $viewMode = 'on';
            }

            return view('CompanyAssociation::open-form', compact('previous_company_lists', 'current_company', 'requestedCompany_id', 'previousCompany_ids', 'companyAssociationRequest', 'viewMode'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1014]');
            return redirect()->back();
        }
    }

    public function appUpdate(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-UP-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1006]');


        $rules['status_id'] = 'required';
        $messages = ['status_id.required' => 'Update type is required'];
        $this->validate($request, $rules, $messages);


        try {

            DB::beginTransaction();
            $app_id = Encryption::decodeId($request->get('app_id'));

            if ($request->get('status_id') == 'Reject') {

                $companyAssociation = CompanyAssociation::find($app_id);
                $companyAssociation->status_id = 6;
                $companyAssociation->desk_remarks = $request->get('desk_remarks');
                $companyAssociation->save();

                Session::flash('success', 'Company association request rejected successfully.');

            } elseif ($request->get('status_id') == 'Approved') {

                $companyAssociation = CompanyAssociation::find($app_id);
                $companyAssociation->approved_user_type = $request->get('approved_user_type');
                $companyAssociation->status_id = 25;
                $companyAssociation->status = 1;
                $companyAssociation->desk_remarks = $request->get('desk_remarks');
                $companyAssociation->save();


                // Update users company
                $user = Users::find($companyAssociation->user_id);

                if ($companyAssociation->request_type == 'Add') {

                    // Check user type assigned given by system admin
                    if (!in_array($request->get('approved_user_type'), ['Employee', 'Consultant'])) {
                        DB::rollback();
                        Session::flash('error', 'Sorry, Unknown keyword for user type! [CAC-122]');
                        return redirect()->back()->withInput();
                    }

                    // Add company id with existing id arrays
                    if (!empty($user->company_ids)) {
                        $user_company_array = explode(',', $user->company_ids);
                    } else {
                        $user_company_array = [];
                    }


                    /*
                     * check the requested company is exist in users current company list, if have, then no need to add
                     * otherwise requested company will be added with current company
                     */
                    $search_company_in_existing_list = array_search($companyAssociation->requested_company_id, $user_company_array);
                    if ($search_company_in_existing_list === false) {
                        $user_company_array[] = $companyAssociation->requested_company_id;
                    }

                } elseif ($companyAssociation->request_type == 'Remove') {
                    // remove company from users table
                    $user_company_array = explode(',', $user->company_ids);

                    // Search requested company id in current company list, if found return key otherwise return false
                    $key = array_search($companyAssociation->requested_company_id, $user_company_array);
                    if ($key !== false) {
                        unset($user_company_array[$key]); // Remove requested company id from array
                    }
                }
                $updated_company_ids = implode($user_company_array, ',');
                $updated_company_ids = (empty($updated_company_ids) ? 0 : $updated_company_ids);
                $user->company_ids = $updated_company_ids;
                $user->save();

                Session::flash('success', 'Company association request approved successfully.');

            } else {
                DB::rollback();
                Session::flash('error', 'Sorry, Unknown keyword for update type! [CAC-123]');
                return redirect()->back()->withInput();
            }

            DB::commit();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1015]');
            return redirect()->back();
        }
    }

    public function status_update($request_id, $status_id)
    {
        if (!ACL::getAccsessRight($this->aclName, '-UP-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1007]');

        try {


            $request_id = Encryption::decodeId($request_id);
            $status_id = Encryption::decodeId($status_id);

            if (!in_array($status_id, [0, 1])) {
                Session::flash('error', 'Sorry, invalid status!!! [CAC-1016]');
                return redirect()->back();
            }

            $companyAssociation = CompanyAssociation::find($request_id);
            if (empty($companyAssociation)) {
                Session::flash('error', 'Sorry, Request data not found!!! [CAC-1017]');
                return redirect()->back();
            }

            if ($companyAssociation->status_id != 25 && $companyAssociation->request_type != 'Add') {
                Session::flash('error', 'Sorry, Request data can not be activate or deactivate. [CAC-1018]');
                return redirect()->back();
            }

            DB::beginTransaction();

            // Update users company
            $user = Users::find($companyAssociation->user_id);

            // if status is activate
            if ($status_id == 1) {

                if ($user->company_ids == 0) {
                    $user_company_array[] = $companyAssociation->requested_company_id;
                } else {
                    // Add company id with existing id arrays
                    $user_company_array = explode(',', $user->company_ids);

                    /*
                     * check the requested company is exist in users current company list, if have, then no need to add
                     * otherwise requested company will be added with current company
                     */
                    $search_company_in_existing_list = array_search($companyAssociation->requested_company_id, $user_company_array);
                    if ($search_company_in_existing_list === false) {
                        $user_company_array[] = $companyAssociation->requested_company_id;
                    }
                }

                Session::flash('success', 'Company association request has ben activate successfully');

            } // if status is deactivate
            elseif ($status_id == 0) {
                // remove company from users table
                $user_company_array = explode(',', $user->company_ids);

                // Search requested company id in current company list, if found return key otherwise return false
                $key = array_search($companyAssociation->requested_company_id, $user_company_array);
                if ($key !== false) {
                    unset($user_company_array[$key]); // Remove requested company id from array
                }

                Session::flash('success', 'Company association request has ben deactivate successfully');
            }

            $updated_company_ids = implode($user_company_array, ',');
            $updated_company_ids = (empty($updated_company_ids) ? 0 : $updated_company_ids);
            $user->company_ids = $updated_company_ids;
            $user->save();

            $companyAssociation->status = $status_id;
            $companyAssociation->save();


            DB::commit();
            return redirect()->back();

        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1019]');
            return redirect()->back();
        }
    }

    public function changeOragizationForm()
    {
        $companyIds = CommonFunction::getUserCompanyAllWithZeroWithoutEloquent();
        // User will got all associated company
        $companyList = CompanyInfo::where('company_status', 1)
            ->where('is_approved', 1)
            ->whereIn('id', $companyIds)
            ->where('is_rejected', 'no')
            ->get(['company_name', 'id']);
        return \view('CompanyAssociation::switch-company', compact('companyList'));
    }

    public function updateWorkingCompany(Request $request)
    {
        $rules['requested_company_id'] = 'required';
        $messages = [
            'requested_company_id.required' => 'At least select one company'
        ];

        $this->validate($request, $rules, $messages);

        try {
            $request_company_id = $request->get('requested_company_id');
            $companyIds = CommonFunction::getUserCompanyAllWithZeroWithoutEloquent();

            if (in_array($request_company_id, $companyIds) ) {
                $company_association_request = CommonFunction::getWorkingUserType($request_company_id);
                if (empty($company_association_request)) {
                    Session::flash('error', 'Your company not active! [CAC-1020]');
                    return redirect()->back();
                }

                $user_id = Auth::user()->id;
                if (!empty($company_association_request)) {
                    $working_user_type = $company_association_request->approved_user_type;
                    Users::where('id', $user_id)
                        ->update([
                            'working_company_id' => $request->get('requested_company_id'),
                            'working_user_type' => $working_user_type
                        ]);
                }
//                else {
//                    DB::statement("UPDATE users SET working_company_id = company_ids where id = $user_id");
//                }

                // User wise permission for menu (Sidebar) and widget (Dashboard)
                Session::forget('accessible_process');
                CommonFunction::setAccessibleProcessTypeList();

                // Irms destroy previous session data and create new one if exists
                Session::forget('irms_feedback_tracking_number');
                UtilFunction::isIrmsFeedbackSubmissionDateExpired($request->get('requested_company_id'));

                $current_company_name = CommonFunction::getCompanyNameById($request->get('requested_company_id'));
                Session::flash('success', 'Congrats! Your working company has been changed successfully. Current working company is <b>' . $current_company_name . '</b>');
                return redirect('/dashboard');
            }

            Session::flash('error', 'You are not eligible for this company.[CAC-USC-001]');
            return redirect()->back();

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1021]');
            return redirect()->back();
        }

    }

    public function addOrRemoveCompanyToUser($companyAssociation)
    {
        // Update users company
        $user = Users::find($companyAssociation->user_id);

        if ($companyAssociation->request_type == 'Add') {

            // Add company id with existing id arrays
            if (!empty($user->company_ids)) {
                $user_company_array = explode(',', $user->company_ids);
            } else {
                $user_company_array = [];
            }

            /*
             * check the requested company is exist in users current company list, if have, then no need to add
             * otherwise requested company will be added with current company
             */
            $search_company_in_existing_list = array_search($companyAssociation->requested_company_id, $user_company_array);
            if ($search_company_in_existing_list === false) {
                $user_company_array[] = $companyAssociation->requested_company_id;
            }

        } elseif ($companyAssociation->request_type == 'Remove') {
            // remove company from users table
            $user_company_array = explode(',', $user->company_ids);

            // Search requested company id in current company list, if found return key otherwise return false
            $key = array_search($companyAssociation->requested_company_id, $user_company_array);
            if ($key !== false) {
                unset($user_company_array[$key]); // Remove requested company id from array
            }
        }
        $updated_company_ids = implode($user_company_array, ',');
        $user->company_ids = $updated_company_ids;
        $user->save();
    }

    public function loadLetterChangeModal($request_id)
    {
        $request_id = Encryption::decodeId($request_id);
        $company_association_info = CompanyAssociation::where('id', $request_id)
            ->first([
                'id',
                'authorization_letter'
            ]);
        return view('CompanyAssociation::letter-change-modal', compact('company_association_info'));
    }

    public function uploadDocument()
    {
        return View::make('CompanyAssociation::ajaxUploadFile');
    }

    public function saveChangeLetter(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-UP-'))
            abort('400', 'You have no access right! This incidence will be reported. Contact with system admin for more information.  [CAC-1008]');

        try {
            $rules = [
                'authorization_letter' => 'required'
            ];
            $messages = [];
            $validation = \Illuminate\Support\Facades\Validator::make($request->all(), $rules, $messages);
            if ($validation->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => $validation->errors(),
                ]);
            }

            $request_id = Encryption::decodeId($request->get('request_id'));

            CompanyAssociation::where('id', $request_id)
                ->update([
                    'authorization_letter' => $request->get('authorization_letter')
                ]);

            return response()->json([
                'success' => true,
                'status' => 'Authorization letter has been saved successfully.',
                'link' => '/company-association/open/' . Encryption::encodeId($request_id)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()).' [CAC-1022]'
            ]);
        }
    }
}
