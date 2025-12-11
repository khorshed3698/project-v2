<?php

namespace App\Modules\Settings\Controllers;

use App\Features;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Libraries\UtilFunction;
use App\Modules\Apps\Models\Airports;
use App\Modules\apps\Models\Colors;
use App\Modules\Apps\Models\DocInfo;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\apps\Models\IndustryCategories;
use App\Modules\Apps\Models\SurveyFeatures;
use App\Modules\Dashboard\Models\Dashboard;
use App\Modules\Dashboard\Models\Services;
use App\Modules\Faq\Models\FaqTypes;
use App\Modules\ProcessPath\Models\DeptApplicationTypes;
use App\Modules\ProcessPath\Models\DeptProcessAppTypeMapping;
use App\Modules\ProcessPath\Models\DeptProcessMapping;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessStatus;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Settings\Models\ApplicationRollback;
use App\Modules\Settings\Models\Area;
use App\Modules\Settings\Models\Attachment;
use App\Modules\Settings\Models\AttachmentType;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\BankBranch;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Settings\Models\ForcefullyDataUpdate;
use App\Modules\Settings\Models\EaAppsChange;
use App\Modules\Apps\Models\Department;
use App\Modules\Users\Models\SubDepartment;
use App\Modules\Settings\Models\HighComissions;
use App\Modules\Settings\Models\Holiday;
use App\Modules\Settings\Models\HomePageSlider;
use App\Modules\Settings\Models\HsCodes;
use App\Modules\Settings\Models\Logo;
use App\Modules\Settings\Models\MaintenanceModeUser;
use App\Modules\Settings\Models\Notice;
use App\Modules\Settings\Models\Notification;
use App\Modules\Settings\Models\Organization;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfQueue;
use App\Modules\Settings\Models\Ports;
use App\Modules\Settings\Models\RegulatoryAgency;
use App\Modules\Settings\Models\RegulatoryAgencyDetails;
use App\Modules\Settings\Models\Sector;
use App\Modules\Settings\Models\SectorDivisions;
use App\Modules\Settings\Models\SectorProducts;
use App\Modules\Settings\Models\SecurityProfile;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\Stakeholder;
use App\Modules\Settings\Models\SubSector;
use App\Modules\Settings\Models\Units;
use App\Modules\Settings\Models\UserManual;
use App\Modules\Settings\Models\WhatsNew;
use App\Modules\Settings\Models\DashBoardSlider;
use App\Modules\SonaliPayment\Models\IpnRequest;
use App\Modules\SonaliPayment\Models\IpnRequestHistory;
use App\Modules\SonaliPayment\Models\PaymentCategory;
use App\Modules\SonaliPayment\Models\PaymentConfiguration;
use App\Modules\SonaliPayment\Models\PaymentDistribution;
use App\Modules\SonaliPayment\Models\PaymentDistributionType;
use App\Modules\SonaliPayment\Models\PaymentStakeholder;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStackholderMapping;
use App\Modules\SonaliPaymentStackHolder\Models\ApiStakeholder;
use App\Modules\SonaliPaymentStackHolder\Models\StackholderPaymentConfiguration;
use App\Modules\SonaliPaymentStackHolder\Models\StakeholderPaymentCategory;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\CompanyInfo;
use App\Modules\Users\Models\Countries;
use App\Modules\Users\Models\EconomicZones;
use App\Modules\Users\Models\ParkInfo;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UsersModel;
use App\Modules\Users\Models\UserTypes;
use App\Modules\BasicInformation\Models\BasicInformation;
use App\Modules\BasicInformation\Models\EA_OrganizationType;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BasicInformation\Models\EA_OwnershipStatus;
use App\Modules\BasicInformation\Models\EA_Service;
use App\Modules\BasicInformation\Models\EA_RegCommercialOffices;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Session;
use yajra\Datatables\Datatables;

class SettingsController extends Controller
{

//    public function __construct()
//    {
//        if (Session::has('lang'))
//            \App::setLocale(Session::get('lang'));
//        ACL::db_reconnect();
    //}

    public function index()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-901]');
        }
        return view("settings::index");
    }

    /* Starting of Bank Related Functions */

    public function bank()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-902]');
        }
        $getList = Bank::where("is_archive", 0)->get();
        return view("Settings::bank.list", compact('getList'));
    }

    public function createBank()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-903]');
        }
        return view("Settings::bank.form-basic");
    }

    public function storeBank(Request $request)
    {

        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-904]');
        }

        $this->validate($request, [
            'name' => 'required',
            'bank_code' => 'required',
            'location' => 'required',
            'email' => 'required|email',
            'phone' => 'required|Max:50|regex:/[0-9+,-]$/',
        ]);
        try {
            $insert = Bank::create(
                array(
                    'name' => $request->get('name'),
                    'bank_code' => $request->get('bank_code'),
                    'location' => $request->get('location'),
                    'email' => $request->get('email'),
                    'phone' => $request->get('phone'),
                    'address' => $request->get('address'),
                    'website' => $request->get('website'),
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-bank/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editBank($id)
    {
        $bank_id = Encryption::decodeId($id);
        $data = Bank::where('id', $bank_id)->first();

        return view("Settings::bank.edit", compact('data', 'id'));
    }

    public function updateBank($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information. [SC-905]');
        }
        $bank_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'bank_code' => 'required',
            'email' => 'required|email',
            'phone' => 'required|Max:50|regex:/[0-9+,-]$/',
            'location' => 'required',
        ]);

        Bank::where('id', $bank_id)->update([
            'name' => $request->get('name'),
            'bank_code' => $request->get('bank_code'),
            'location' => $request->get('location'),
            'email' => $request->get('email'),
            'phone' => $request->get('phone'),
            'address' => $request->get('address'),
            'website' => $request->get('website'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-bank/' . $id);
    }

    public function viewBank($id)
    {
        $bank_id = Encryption::decodeId($id);
        $data = Bank::where('id', $bank_id)->first();

        return view("Settings::bank.view", compact('data', 'id', 'bank_id', 'getList'));
    }

    /* Starting of Regulatory Agencies Related Functions */
    public function regulatoryAgency()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-906]');
        }
        return view("Settings::regulatory_agencies.list");
    }

    public function getRegulatoryAgencyData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $data = RegulatoryAgency::where('is_archive', 0)->orderBy('id', 'desc')->get();

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-regulatory-agency/' . Encryption::encodeId($data->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('agency_type', function ($data) {
                return strtoupper($data->agency_type);
            })
            ->editColumn('is_active', function ($data) use ($mode) {
                if ($data->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }

    public function createRegulatoryAgency()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }

        try {
            return view("Settings::regulatory_agencies.create");
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function storeRegulatoryAgency(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }

        $this->validate($request, [
            'name' => 'required',
            'agency_type' => 'required',
            'is_active' => 'required'
        ]);

        try {
            $insert = RegulatoryAgency::create([
                'name' => $request->get('name'),
                'url' => $request->get('url'),
                'order' => $request->get('order'),
                'agency_type' => $request->get('agency_type'),
                'contact_name' => $request->get('contact_name'),
                'designation' => $request->get('designation'),
                'mobile' => $request->get('mobile'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'description' => $request->get('description'),
                'status' => $request->get('is_active'),
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('settings/regulatory-agency');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function editRegulatoryAgency($encrypted_id)
    {
        try {
            $id = Encryption::decodeId($encrypted_id);

            $data = RegulatoryAgency::where('id', $id)->first();

            return view("Settings::regulatory_agencies.edit", compact('data', 'encrypted_id'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function updateRegulatoryAgency($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            $id = Encryption::decodeId($enc_id);

            $this->validate($request, [
                'name' => 'required',
                'agency_type' => 'required',
                'status' => 'required',
            ]);

            RegulatoryAgency::where('id', $id)->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'contact_name' => $request->get('contact_name'),
                'designation' => $request->get('designation'),
                'mobile' => $request->get('mobile'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'url' => $request->get('url'),
                'order' => $request->get('order'),
                'agency_type' => $request->get('agency_type'),
                'status' => $request->get('status'),
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/edit-regulatory-agency/' . Encryption::encodeId($id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    /* Ending of Regulatory Agencies Related Functions */

    /* Starting of Regulatory Agency Details Related Functions */
    public function regulatoryAgencyDetails()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        return view("Settings::regulatory_agency_details.list");
    }

    public function getRegulatoryAgencyDetailsData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

//        $data = RegulatoryAgencyDetails::where('is_archive', 0)->orderBy('id', 'desc')->get();
        $data = RegulatoryAgencyDetails::leftjoin('regulatory_agencies', 'regulatory_agencies_details.regulatory_agencies_id', '=', 'regulatory_agencies.id')
            ->where('regulatory_agencies_details.is_archive', 0)->orderBy('regulatory_agencies_details.id', 'desc')->get([
                'regulatory_agencies.name',
                'regulatory_agencies_details.id',
                'regulatory_agencies_details.service_name',
                'regulatory_agencies_details.is_online',
                'regulatory_agencies_details.status'
            ]);
        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-regulatory-agency-details/' . Encryption::encodeId($data->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('is_online', function ($data) use ($mode) {
                if ($data->is_online == 1) {
                    return "<span class='label label-warning'>Online</span>";
                } else {
                    return "<span class='label label-info'>Offline</span>";
                }
            })
            ->editColumn('status', function ($data) use ($mode) {
                if ($data->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }

    public function createRegulatoryAgencyDetails()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            $regulatoryAgencies = RegulatoryAgency::orderby('name')->where('status', 1)->lists('name', 'id');
            return view("Settings::regulatory_agency_details.create", compact('regulatoryAgencies'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function storeRegulatoryAgencyDetails(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }

        $this->validate($request, [
            'regulatory_agencies_id' => 'required',
            'service_name' => 'required',
            'is_online' => 'required',
            'status' => 'required',
        ]);

        try {
            $insert = RegulatoryAgencyDetails::create([
                'regulatory_agencies_id' => $request->get('regulatory_agencies_id'),
                'service_name' => $request->get('service_name'),
                'is_online' => $request->get('is_online'),
                'method_of_recv_service' => $request->get('method_of_recv_service'),
                'who_get_service' => $request->get('who_get_service'),
                'documents' => $request->get('documents'),
                'fees' => $request->get('fees'),
                'status' => $request->get('status')
            ]);
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-regulatory-agency-details/' . Encryption::encodeId($insert->id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function editRegulatoryAgencyDetails($encrypted_id)
    {
        try {
            $id = Encryption::decodeId($encrypted_id);

            $data = RegulatoryAgencyDetails::where('id', $id)->first();
            $regulatory_agency = RegulatoryAgency::where('id', $data->regulatory_agencies_id)->lists('name', 'id');
            return view("Settings::regulatory_agency_details.edit", compact('data', 'encrypted_id', 'regulatory_agency'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function updateRegulatoryAgencyDetails($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            $id = Encryption::decodeId($enc_id);

            $this->validate($request, [
                'regulatory_agencies_id' => 'required',
                'service_name' => 'required',
                'is_online' => 'required',
                'status' => 'required',
            ]);

            RegulatoryAgencyDetails::where('id', $id)->update([
                'regulatory_agencies_id' => $request->get('regulatory_agencies_id'),
                'service_name' => $request->get('service_name'),
                'is_online' => $request->get('is_online'),
                'method_of_recv_service' => $request->get('method_of_recv_service'),
                'who_get_service' => $request->get('who_get_service'),
                'documents' => $request->get('documents'),
                'fees' => $request->get('fees'),
                'status' => $request->get('status')
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/edit-regulatory-agency-details/' . Encryption::encodeId($id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    /* Ending of Regulatory Agency Details Related Functions */

    public function getList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $data = BankBranch::query();
        $data->leftJoin('bank', 'bank.id', '=', 'bank_branches.bank_id')
            ->where('bank_branches.is_archive', 0)
            ->orderBy('bank_branches.branch_name', 'desc')
            ->select(
                'bank_branches.id',
                'bank_branches.branch_name',
                'bank_branches.branch_code',
                'bank_branches.address',
                'bank_branches.manager_info',
                'bank_branches.is_active',
                'bank_branches.is_archive',
                'bank.name as bank_name'
            );

        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $url = "ConfirmDelete('" . Encryption::encodeId($data->id) . "')";
                return '<a href="' . url('settings/view-branch/' . Encryption::encodeId($data->id)) .
                    '" class="btn btn-xs btn-success"><i class="fa fa-folder-open"></i> Open</a>'
                    . ' <a href="javascript:void(0)" ' .
                    " class='btn btn-xs btn-danger' onclick=$url><i class='fa fa-trash'></i></a>";
            })->addColumn('is_active', function ($data) {
                if ($data->is_active === 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Inactive</label>";
                }

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function branch()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9873]');
        }

        return view("Settings::branch.list");
    }

    public function createBranch()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9874]');
        }

        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

        $banks = ['' => 'Select One'] + Bank::orderBy('name')->where('is_active', 1)->lists('name', 'id')->all();
        return view("Settings::branch.form-basic", compact('banks', 'districts', 'thana'));
    }

    public function storeAndUpdateBranch(Request $request, $id = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9875]');
        }

        $this->validate($request, [
            'bank_id' => 'required',
            'branch_code' => 'required | numeric | digits_between:1,8',
            'branch_name' => 'required',
            'address' => 'required',
        ]);
        if ($id) {
            $id = Encryption::decodeId($id);
        }

        $isDuplicate = BankBranch::where([
            'bank_id' => $request->get('bank_id'), 'branch_code' => $request->get('branch_code')
        ])
            ->where('id', '!=', $id)
            ->count();
        if ($isDuplicate > 0) {
            Session::flash('error', 'Duplicate branch for this bank.');
            return Redirect::back()->withInput();
        }

        try {

            $branchData = BankBranch::findOrNew($id);
            $branchData->bank_id = $request->get('bank_id');
            $branchData->branch_code = $request->get('branch_code');
            $branchData->district = $request->get('district');
            $branchData->thana = $request->get('thana');
            $branchData->branch_name = $request->get('branch_name');
            $branchData->address = $request->get('address');
            $branchData->manager_info = $request->get('manager_info');
            if ($id) {
                $branchData->is_active = $request->get('is_active');
            }
            $branchData->save();
            Session::flash('success', 'Branch is added successfully!');
            if ($id == $branchData->id) {
                Session::flash('success', 'Branch updated successfully!');
            }


            return redirect('/settings/edit-branch/' . Encryption::encodeId($branchData->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something went wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editBranch($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9876]');
        }
        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

        $bank_id = Encryption::decodeId($id);
        $banks = ['' => 'Select One'] + Bank::orderBy('name')->where('is_active', 1)->lists('name', 'id')->all();
        $data = BankBranch::where('id', $bank_id)->first();

        return view("Settings::branch.edit", compact('data', 'banks', 'districts', 'thana', 'id'));
    }

    public function viewBranch($id)
    {

        if (!ACL::getAccsessRight('settings', 'V')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9877]');
        }
        $branch_id = Encryption::decodeId($id);

        $districts = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();


        $data = BankBranch::leftJoin('bank', 'bank.id', '=', 'bank_branches.bank_id')
            ->where('bank_branches.id', $branch_id)
            ->first(['bank_branches.*', 'bank.name as bank_name']);
        return view("Settings::branch.view", compact('districts', 'thana', 'data', 'id', 'branch_id'));
    }


    /* Start of Stakeholder functions */

    public function stakeholder()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        return view("Settings::stakeholder.list");
    }

    public function getStakeholderData(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $stakeholders = Stakeholder::leftJoin('department', 'department.id', '=', 'stakeholder.department_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'stakeholder.process_type_id')
            ->select([
                'department.name as department_name',
                'process_type.name as service_name',
                'stakeholder.id',
                'stakeholder.status',
                DB::raw("CONCAT(stakeholder.name,'<br/>',stakeholder.designation) as name_designation"),
            ])
            ->where('stakeholder.is_archive', 0)
            ->orderBy('stakeholder.id', 'desc')
            ->get();


        return Datatables::of($stakeholders)
            ->addColumn('action', function ($stakeholders) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-stakeholder/' . Encryption::encodeId($stakeholders->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($stakeholders) use ($mode) {
                if ($stakeholders->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->filterColumn('name_designation', function ($query, $keyword) {
                $query->whereRaw("CONCAT(stakeholder.name,'<br/>',stakeholder.designation) like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function createStakeholder()
    {
        try {
            $departments = Department::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');

            return view("Settings::stakeholder.create", compact('departments'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function getProcessByDept(Request $request)
    {
        $getProcessList = DeptProcessMapping::leftJoin('process_type', 'process_type.id', '=',
            'dept_process_mapping.process_type_id')
            ->where('department_id', $request->get('dept_id'))
            ->groupBy('process_type_id')
            ->lists('dept_process_mapping.process_type_id', 'process_type.name');
        $data = ['responseCode' => 1, 'data' => $getProcessList];
        return response()->json($data);
    }

    public function storeStakeholder(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }

        $this->validate($request, [
            'department_id' => 'required|numeric',
            'process_type_id' => 'required|numeric',
            'name' => 'required',
            'designation' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        try {
            $insert = Stakeholder::create([
                'department_id' => $request->get('department_id'),
                'process_type_id' => $request->get('process_type_id'),
                'name' => $request->get('name'),
                'designation' => $request->get('designation'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/stakeholder');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function editStakeholder($encrypted_id)
    {
        try {
            $id = Encryption::decodeId($encrypted_id);

            $data = Stakeholder::where('id', $id)->first();
            $departments = Department::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');

            return view("Settings::stakeholder.edit", compact('data', 'encrypted_id', 'departments'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function updateStakeholder($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            $id = Encryption::decodeId($enc_id);

            $this->validate($request, [
                'department_id' => 'required|numeric',
                'process_type_id' => 'required|numeric',
                'name' => 'required',
                'designation' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required',
                'status' => 'required',
            ]);

            Stakeholder::where('id', $id)->update([
                'department_id' => $request->get('department_id'),
                'process_type_id' => $request->get('process_type_id'),
                'name' => $request->get('name'),
                'designation' => $request->get('designation'),
                'address' => $request->get('address'),
                'phone' => $request->get('phone'),
                'email' => $request->get('email'),
                'status' => $request->get('status'),
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/stakeholder');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    /* End of Stakeholder functions */

    /* Start of process category functions */

    public function processCategory()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            return view("Settings::process-category.list");
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-5000]");
            return Redirect::back();
        }
    }

    public function getProcessCategoryData(Request $request)
    {

        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $data = DeptProcessAppTypeMapping::leftJoin('department', 'department.id', '=',
            'dept_process_app_type_mapping.department_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'dept_process_app_type_mapping.process_type_id')
            ->leftJoin('dept_application_type', 'dept_application_type.id', '=',
                'dept_process_app_type_mapping.app_type_id')
            ->select([
                'department.name as department_name',
                'process_type.name as service_name',
                'dept_process_app_type_mapping.id',
                'dept_application_type.name as category',
                'dept_process_app_type_mapping.status',
            ])
            ->where('dept_process_app_type_mapping.is_archive', 0)
            ->orderBy('dept_process_app_type_mapping.id', 'desc')
            ->get();

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-process-category/' . Encryption::encodeId($data->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('status', function ($data) use ($mode) {
                if ($data->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }

    public function createProcessCategory()
    {
        try {
            $departments = Department::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');
            $organizations = Organization::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');
            $appTypes = DeptApplicationTypes::where('is_archive', 0)->where('status', 1)->lists('name', 'id');
            return view("Settings::process-category.create", compact('departments', 'appTypes', 'organizations'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-5001]");
            return Redirect::back();
        }
    }

    public function storeProcessCategory(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        $this->validate($request, [
            'organization_id' => 'required|numeric',
            'department_id' => 'required|numeric',
            'process_type_id' => 'required|numeric',
            'app_type_id' => 'required',
            'certificate_text' => 'required',
            'app_instruction' => 'required',
        ]);

        if (!in_array($request->get('process_type_id'), ['1', '2', '3', '4', '5', '10', '102'])) {
            Session::flash('error', 'This process type can\'t allowed for application category.');
            return redirect()->back()->withInput();
        }

        try {

            // for Work permit new, extension, amendment cancellation maximum 1 type can be created
            if (in_array($request->get('process_type_id'), ['2', '3', '4', '5'])) {
                $count_category = DeptProcessAppTypeMapping::where('department_id', $request->get('department_id'))
                    ->where('process_type_id', $request->get('process_type_id'))
                    ->count();
                if ($count_category == 1) {
                    Session::flash('error',
                        'There will be maximum one type of work permit applications. already have one');
                    return redirect()->back()->withInput();
                }
            }

            $match_category = DeptProcessAppTypeMapping::where('department_id', $request->get('department_id'))
                ->where('process_type_id', $request->get('process_type_id'))
                ->where('app_type_id', $request->get('app_type_id'))
                ->first();
            if (!empty($match_category)) {
                Session::flash('error', 'This application category already exists.');
                return redirect()->back()->withInput();
            }
            $insert = DeptProcessAppTypeMapping::create([
                'department_id' => $request->get('department_id'),
                'organization_id' => $request->get('organization_id'),
                'process_type_id' => $request->get('process_type_id'),
                'app_type_id' => $request->get('app_type_id'),
                'certificate_text' => addslashes($request->get('certificate_text')),
                'app_instruction' => $request->get('app_instruction')
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-process-category/' . Encryption::encodeId($insert->id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-5002]");
            return Redirect::back();
        }
    }

    public function editProcessCategory($encrypted_id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        try {
            $id = Encryption::decodeId($encrypted_id);
            $data = DeptProcessAppTypeMapping::where('id', $id)->first();
            $departments = Department::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');
            $organizations = Organization::where('status', 1)
                ->where('is_archive', 0)
                ->lists('name', 'id');
            $appTypes = DeptApplicationTypes::where('is_archive', 0)->where('status', 1)->lists('name', 'id');
            return view("Settings::process-category.edit",
                compact('data', 'encrypted_id', 'departments', 'appTypes', 'organizations'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-5003]");
            return Redirect::back();
        }
    }

    public function updateProcessCategory($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $id = Encryption::decodeId($enc_id);

            $this->validate($request, [
                'organization_id' => 'required|numeric',
                'department_id' => 'required|numeric',
                'process_type_id' => 'required|numeric',
                'app_type_id' => 'required',
                'certificate_text' => 'required',
                'app_instruction' => 'required',
                'status' => 'required',
            ]);

            if (!in_array($request->get('process_type_id'), ['1', '2', '3', '4', '5', '10', '102'])) {
                Session::flash('error', 'This process type can\'t allowed for application category.');
                return redirect()->back()->withInput();
            }

            // for Work permit new, extension, amendment cancellation maximum 1 type can be created
            if (in_array($request->get('process_type_id'), ['2', '3', '4', '5'])) {
                $count_category = DeptProcessAppTypeMapping::where('department_id', $request->get('department_id'))
                    ->where('process_type_id', $request->get('process_type_id'))
                    ->count();
                if ($count_category == 1) {
                    Session::flash('error',
                        'There will be maximum one type of work permit applications. already have one');
                    return redirect()->back()->withInput();
                }
            }

            $match_category = DeptProcessAppTypeMapping::where('id', '!=', $id)
                ->where('department_id', $request->get('department_id'))
                ->where('process_type_id', $request->get('process_type_id'))
                ->where('app_type_id', $request->get('app_type_id'))
                ->first();
            if (!empty($match_category)) {
                Session::flash('error', 'This category name already used.');
                return redirect()->back()->withInput();
            }

            DeptProcessAppTypeMapping::where('id', $id)->update([
                'department_id' => $request->get('department_id'),
                'organization_id' => $request->get('organization_id'),
                'process_type_id' => $request->get('process_type_id'),
                'app_type_id' => $request->get('app_type_id'),
                'certificate_text' => addslashes($request->get('certificate_text')),
                'app_instruction' => $request->get('app_instruction'),
                'status' => $request->get('status'),
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/edit-process-category/' . Encryption::encodeId($id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-5004]");
            return Redirect::back();
        }
    }

    /* End of process-category functions */

    /* Start of Currency related functions */

    public function currency()
    {
        $rows = Currencies::orderBy('code')->where('is_archive', 0)->get();
        return view("Settings::currency.list", compact('rows'));
    }

    public function createCurrency()
    {
        return view("Settings::currency.create", compact(''));
    }

    public function storeCurrency(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'usd_value' => '',
            'bdt_value' => '',
        ]);

        $insert = Currencies::create([
            'code' => $request->get('code'),
            'name' => $request->get('name'),
            'usd_value' => $request->get('usd_value'),
            'bdt_value' => $request->get('bdt_value'),
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-currency/' . Encryption::encodeId($insert->id));
    }

    public function editCurrency($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = Currencies::where('id', $id)->first();

        return view("Settings::currency.edit", compact('data', 'encrypted_id'));
    }

    public function updateCurrency($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'usd_value' => '',
            'bdt_value' => '',
        ]);

        Currencies::where('id', $id)->update([
            'code' => $request->get('code'),
            'name' => $request->get('name'),
            'usd_value' => $request->get('usd_value'),
            'bdt_value' => $request->get('bdt_value'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-currency/' . $enc_id);
    }

    /* End of Currency related functions */
    public function parks()
    {
        return view("Settings::park.list");
    }

    public function getEcoParkData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = ParkInfo::where('is_archive', 0)->orderBy('park_name', 'asc')
            ->get(['id', 'park_name', 'upazilla_name', 'district_name', 'park_area', 'remarks', 'status']);
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    $url = "ConfirmDelete('" . Encryption::encodeId($datas->id) . "')";
                    return '<a href="/settings/edit-park-info/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-success"><i class="fa fa-folder-open"></i> Open</a>'
                        . ' <a href="javascript:void(0)" ' .
                        " class='btn btn-xs btn-danger' onclick=$url><i class='fa fa-times'></i></a>";
                }
            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            // ->removeColumn('id')
            ->make(true);
    }

    public function createPark()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9878]');
        }
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::park.create", compact('districts'));
    }


    public function getPoliceStations(Request $request)
    {
        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_nm';
        } else {
            $areaField = 'area_info.area_nm_ban';
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $area = Area::where($areaField, $request->get('districtId'))->where('area_type', 2)->first();
        if ($area) {
            $area_id = $area->area_id;
            $get_data = Area::where('pare_id', DB::raw($area_id))
                ->whereNotNull($areaField)
                ->where('area_type', 3)
                ->select($areaField)
                ->orderBy($areaField)
                ->lists($areaField);

            $data = ['responseCode' => 1, 'data' => $get_data];
        }
        return response()->json($data);
    }

    public function getPoliceStationsWithId(Request $request)
    {

        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_id';
            $areaValue = 'area_info.area_nm';
            $area_select = array('area_info.area_nm', 'area_info.area_id');
        } else {
            $areaField = 'area_info.area_id';
            $areaValue = 'area_info.area_nm_ban';
            $area_select = 'area_info.area_nm_ban,area_info.area_id';
            $area_select = array('area_info.area_nm_ban', 'area_info.area_id');
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $area = Area::where($areaField, $request->get('districtId'))->where('area_type', 2)->first();
        if ($area) {
            $area_id = $area->area_id;
            $get_data = Area::where('pare_id', DB::raw($area_id))
                ->whereNotNull($areaField)
                ->where('area_type', 3)
                ->select($area_select)
                ->orderBy($areaField)
                ->lists('area_info.area_nm', 'area_info.area_id');
//                ->lists($areaField, $areaValue);

            $data = ['responseCode' => 1, 'data' => $get_data];
        }
        return response()->json($data);
    }

    public function getThana(Request $request)
    {

        if ($request->get('lang') && $request->get('lang') == 'en') {
            $areaField = 'area_info.area_nm';
        } else {
            $areaField = 'area_info.area_nm_ban';
        }

        $data = ['responseCode' => 0, 'data' => ''];
        $get_data = Area::where('pare_id', $request->get('districtId'))
            ->whereNotNull($areaField)
            ->where('area_type', 3)
            ->orderBy($areaField)
            ->lists($areaField, 'area_id');
        $data = ['responseCode' => 1, 'data' => $get_data];
        return response()->json($data);
    }

    public function getDistrictUser(Request $request)
    {
        $area_id = $request->get('districtId');
        $get_data = UsersModel::where('district', '=', $area_id)
            ->where(function ($query) {
                return $query->where('user_type', '=', '7x713');
            })
            ->select('user_full_name', 'id')
            ->orderBy('user_full_name')
            ->lists('user_full_name', 'id');
        $data = ['responseCode' => 1, 'data' => $get_data];
        return response()->json($data);
    }

    public function areaList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Area::all();
        return view("Settings::area.list", compact('getList'));
    }

    public function createArea()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $divisions = ['' => 'Select one'] + Area::orderBy('area_nm')
                ->where('pare_id', 0)->lists('area_nm', 'area_id')->all();

        return view("Settings::area.form-basic", compact('divisions'));
    }

    public function storeArea(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'area_nm' => 'required',
            'area_nm_ban' => 'required',
        ]);
        try {
            $area_type = $request->get('area_type');
            if ($area_type == 1) { //for division
                $parent_id = 0;
            } elseif ($area_type == 2) { // for district
                $parent_id = $request->get('division');
            } elseif ($area_type == 3) { //for thana
                $parent_id = $request->get('district');
            }

            $insert = Area::create([
                'area_type' => $area_type,
                'pare_id' => $parent_id,
                'area_nm' => $request->get('area_nm'),
                'area_nm_ban' => $request->get('area_nm_ban'),
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-area/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editArea($id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $area_id = Encryption::decodeId($id);
        $data = Area::leftJoin('area_info as ai', 'area_info.pare_id', '=', 'ai.area_id')
            ->where('area_info.area_id', $area_id)
            ->get(['area_info.*', 'ai.pare_id as division_id'])[0];


        $divisions = ['' => 'Select one'] + Area::orderBy('area_nm')
                ->where('pare_id', 0)->lists('area_nm', 'area_id')->all();

        return view("Settings::area.edit", compact('data', 'id', 'divisions'));
    }

    public function updateArea($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $area_id = Encryption::decodeId($id);

        $this->validate($request, [
            'area_nm' => 'required',
            'area_nm_ban' => 'required',
        ]);

        $area_type = $request->get('area_type');
        if ($area_type == 1) { //for division
            $parent_id = 0;
        } elseif ($area_type == 2) { // for district
            $parent_id = $request->get('division');
        } elseif ($area_type == 3) { //for thana
            $parent_id = $request->get('district');
        }

        Area::where('area_id', $area_id)->update([
            'area_type' => $area_type,
            'pare_id' => $parent_id,
            'area_nm' => $request->get('area_nm'),
            'area_nm_ban' => $request->get('area_nm_ban'),
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-area/' . $id);
    }

    public function get_district_by_division_id(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $divisionId = $request->get('divisionId');

        $districts = Area::where('PARE_ID', $divisionId)->orderBy('AREA_NM', 'ASC')->lists('AREA_NM', 'AREA_ID');
        $data = ['responseCode' => 1, 'data' => $districts];
        return response()->json($data);
    }

    public function getAreaData()
    {
        $areas = Area::orderBy('area_nm', 'asc')->get(['area_id', 'area_nm', 'area_nm_ban', 'area_type']);
        $mode = ACL::getAccsessRight('settings', 'E');

        return Datatables::of($areas)
            ->addColumn('action', function ($areas) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-area/' . Encryption::encodeId($areas->area_id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('area_type', function ($areas) {
                if ($areas->area_type == 1) {
                    return 'Division';
                } elseif ($areas->area_type == 2) {
                    return 'District';
                } elseif ($areas->area_type == 3) {
                    return 'Thana';
                }
            })
            //->removeColumn('area_id')
            ->make(true);
    }

    /* Starting of User Type Related Functions */

    public function userType()
    {
        $getList = UserTypes::leftJoin('security_profile as sp', 'sp.id', '=', 'user_types.security_profile_id')
            ->get(['user_types.id', 'type_name', 'security_profile_id', 'week_off_days', 'user_types.status']);

        return view("Settings::user_type.list", compact('getList'));
    }


    public function editUserType($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);
        $security_profiles = SecurityProfile::orderBy('profile_name', 'ASC')
            ->lists('profile_name', 'id');
        $data = UserTypes::where('id', $id)
            ->first([
                'id', 'type_name', 'security_profile_id', 'auth_token_type', 'db_access_data', 'updated_at',
                'updated_by', 'status'
            ]);
        return view("Settings::user_type.edit", compact('data', 'security_profiles'));
    }

    public function updateUserType($encoded_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'type_name' => 'required',
            'auth_token_type' => 'required',
        ]);
//        CommonFunction::createAuditLog('userType.edit', $request);
        $id = Encryption::decodeId($encoded_id);
        $update_data = array(
            'type_name' => $request->get('type_name'),
            'security_profile_id' => $request->get('security_profile'),
            'auth_token_type' => $request->get('auth_token_type'),
            'db_access_data' => Encryption::encode($request->get('db_access_data')),
            'status' => $request->get('status'),
            'updated_by' => Auth::user()->id,
        );
        $data = UserTypes::where('id', $id)
            ->update($update_data);

        if ($request->get('status') == 'inactive') {
            $user_ids = UsersModel::where('user_type', $id)->get(['id']);
            foreach ($user_ids as $user_id) {
                LoginController::killUserSession($user_id);
            }
        }

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-user-type/' . $encoded_id);
    }

    /* End of User Type related functions */

    /* Starting of Configuration Related Functions */

    public function configuration()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Configuration::where('is_locked', '=', 0)->get();
        return view("settings::config.list", compact('getList'));
    }

    public function editConfiguration($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $config_id = Encryption::decodeId($id);
        $data = Configuration::where('id', $config_id)->first();
        return view("settings::config.edit", compact('data', 'id'));
    }

    public function moreConfig()
    {
        if (Auth::user()->user_email == 'shoeb@batworld.com' || Auth::user()->user_email == 'mitul@batworld.com' || Auth::user()->user_email == 'mithu@batworld.com') {
            $getList = Configuration::where('is_locked', '=', 1)->get();
            return view("settings::config.list", compact('getList'));
        } else {
            Session::flash('error', 'Not permitted!');
            return redirect()->back();
        }
    }

    public function updateConfig($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $config_id = Encryption::decodeId($id);

        $this->validate($request, ['value' => 'required']);

        Configuration::where('id', $config_id)->update([
            'value' => $request->get('value'),
            'details' => $request->get('details'),
            'value2' => $request->get('value2'),
            'value3' => $request->get('value3'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-config/' . $id);
    }

    /* Starting of Notification Related Functions */

    public function notification()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = Notification::where('is_locked', 0)
            ->orderBy('id', 'desc')
            ->take(100)
            ->get();
        return view("settings::notify.list", compact('getList'));
    }

    public function viewNotify($id)
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $notify_id = Encryption::decodeId($id);
        $data = Notification::where('id', $notify_id)->first();
        return view("settings::notify.view", compact('data', 'id', '$notify_id'));
    }

    /* Start of FAQ Category related functions */

    public function faqCat()
    {
        return view("settings::faq_category.list");
    }

    public function createFaqCat()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_types = FaqTypes::lists('name', 'id');
        return view("settings::faq_category.create", compact('faq_types'));
    }

    public function getFaqCatDetailsData()
    {
        $mode = ACL::getAccsessRight('settings', 'E');
        $faq_types = FaqTypes::leftJoin('faq_multitypes', 'faq_types.id', '=', 'faq_multitypes.faq_type_id')
            ->leftJoin('faq', 'faq.id', '=', 'faq_multitypes.faq_id')
            ->groupBy('faq_types.id')
            ->get([
                'faq_types.id', 'faq_types.name', 'faq.status as faq_status',
                DB::raw('count(distinct faq_multitypes.faq_id) noOfItems, '
                    . 'sum(case when faq.status="unpublished" then 1 else 0 end) Unpublished,'
                    . 'sum(case when faq.status="draft" then 1 else 0 end) Draft,'
                    . 'sum(case when faq.status="private" then 1 else 0 end) Private')
            ]);

        return Datatables::of($faq_types)
            ->addColumn('action', function ($faq_types) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-faq-cat/' . Encryption::encodeId($faq_types->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a> '
                        . '<a href="/search/index?q=&faqs_type=' . $faq_types->id .
                        '" class="btn btn-xs btn-info"><i class="fa fa-folder-open"></i> Articles</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('Draft', function ($faq_types) {
                if ($faq_types->Draft > 0) {
                    return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=draft" .
                        '" class="">' . $faq_types->Draft . '</a>';
                } else {
                    return $faq_types->Draft;
                }
            })
            ->editColumn('Unpublished', function ($faq_types) {
                if ($faq_types->Unpublished > 0) {
                    return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=unpublished" .
                        '" class="">' . $faq_types->Unpublished . '</a>';
                } else {
                    return $faq_types->Unpublished;
                }
            })
            ->editColumn('Private', function ($faq_types) {
                if ($faq_types->Private > 0) {
                    return '<a href="/search/index?q=&faqs_type=' . $faq_types->id . "&status=private" .
                        '" class="">' . $faq_types->Private . '</a>';
                } else {
                    return $faq_types->Private;
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeFaqCat(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'name' => 'required',
        ]);

        $insert = FaqTypes::create(
            array(
                'name' => $request->get('name'),
                'created_by' => CommonFunction::getUserId()
            ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-faq-cat/' . Encryption::encodeId($insert->id));
    }

    public function editFaqCat($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = FaqTypes::where('id', $id)->first();

        return view("settings::faq_category.edit", compact('data', 'encrypted_id'));
    }

    public function updateFaqCat($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
        ]);

        FaqTypes::where('id', $faq_id)->update([
            'name' => $request->get('name'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-faq-cat/' . $id);
    }

    /* End of FAQ Category related functions */


    /* Starting of Document Related Functions */

    public function document()
    {
        return view("Settings::document.list");
    }

    public function getDocData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = Attachment::leftJoin('process_type', 'process_type.id', '=', 'attachment_list.process_type_id')
            ->leftJoin('attachment_type', 'attachment_type.id', '=', 'attachment_list.attachment_type_id')
            ->orderBy('attachment_list.id', 'desc')
            ->get([
                'attachment_list.*',
                'process_type.name as process_name',
                'attachment_type.name as attachment_type'
            ]);

        return Datatables::of($datas)
            ->editColumn('sl_no', function ($datas) {
                return '';
            })
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-document/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('doc_priority', function ($datas) {
                if ($datas->doc_priority == 1) {
                    return 'Mandatory';
                } else {
                    return 'Not Mandatory';
                }
            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    return '<span class="label label-success">Active</span>';
                } else {
                    return '<span class="label label-danger">Inactive</span>';
                }
            })
            ->editColumn('business_category', function ($datas) {
                if ($datas->business_category == 1) {
                    return 'Private';
                } elseif ($datas->business_category == 2) {
                    return 'Government';
                } elseif ($datas->business_category == 3) {
                    return 'Both';
                }
            })
            ->make(true);
    }

    public function createDocument()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $services = ProcessType::where('status', 1)->orderby('name')->lists('name', 'id');
        return view("Settings::document.create", compact('services'));
    }

    public function getAttachmentType(Request $request)
    {
        $service_id = trim($request->get('service_id'));
        $attachment_type = AttachmentType::where([
            'process_type_id' => $service_id, 'status' => 1, 'is_archive' => 0
        ])->lists('name', 'id');

        return response()->json([
            'result' => $attachment_type
        ]);
    }

    public function storeDocument(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $this->validate($request, [
                'doc_name' => 'required',
                'process_type_id' => 'required',
                'business_category' => 'required',
            ]);

            $attachemnt_id = $request->get('id');

            $attachemnt = Attachment::findOrNew($attachemnt_id);
            $attachemnt->doc_name = $request->get('doc_name');
            $attachemnt->short_note = $request->get('short_note');
            $attachemnt->process_type_id = $request->get('process_type_id');
            $attachemnt->attachment_type_id = $request->get('attachment_type_id');
            $attachemnt->doc_priority = $request->get('doc_priority');
            $attachemnt->order = $request->get('order');
            $attachemnt->max_size_per_page_kb = $request->get('max_size_per_page_kb');
            // $attachemnt->is_multiple = $request->get('is_multiple');  // Default value single(0)
            $attachemnt->business_category = $request->get('business_category');
            $attachemnt->status = $request->get('status');
            $attachemnt->save();

            Session::flash('success', 'Data has been changed successfully.');
            if ($request->get('page') == 'edit') {
                return redirect('settings/edit-document/' . Encryption::encodeId($attachemnt_id));
            } else {
                return redirect('settings/document/');
            }

        } catch (\Exception $e) {
            Session::flash('error', 'Unknown Error -.' . $e->getMessage() . ' [SC-1415]');
            return \redirect()->back()->withInput();
        }
    }

    public function editDocument($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $_id = Encryption::decodeId($id);
        $data = Attachment::where('id', $_id)->first();
        $processType = ProcessType::orderby('name')->lists('name', 'id');
        return view("Settings::document.edit", compact('data', 'id', 'processType'));
    }


    /* Starting of Economic Zone Related Functions */

    public function EcoZones()
    {
        return view("Settings::ecoZone.list");
    }

    public function getEcoZoneData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = EconomicZones::orderBy('name', 'asc')
            ->get(['id', 'name', 'upazilla', 'district', 'area', 'remarks']);
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-eco-zone/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-success"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function createEcoZone()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::ecoZone.create", compact('districts'));
    }

    public function storeEcoZone(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9879]');
        }
        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        $ParkInfo = new ParkInfo();

        $ParkInfo->park_name = $request->get('name');
        $ParkInfo->district_name = $request->get('district');
        $ParkInfo->upazilla_name = $request->get('upazilla');
        $ParkInfo->park_area = $request->get('area');
        $ParkInfo->remarks = $request->get('remarks');
        $ParkInfo->status = 1;
        $ParkInfo->save();

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-park-info/' . Encryption::encodeId($ParkInfo->id));
    }

    public function editEcoZone($id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9880]');
        }
        $_id = Encryption::decodeId($id);
        $data = ParkInfo::where('id', $_id)->first();
        $districts = Area::orderby('area_nm')->where('area_type', 2)->lists('area_nm', 'area_nm');
        return view("Settings::park.edit", compact('data', 'id', 'districts'));
    }

    public function updatePark($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9881]');
        }
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        ParkInfo::where('id', $_id)->update([
            'park_name' => $request->get('name'),
            'upazilla_name' => $request->get('upazilla'),
            'district_name' => $request->get('district'),
            'park_area' => $request->get('area'),
            'status' => $request->get('is_active'),
            'remarks' => $request->get('remarks'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-park-info/' . $id);
    }

    public function updateEcoZone($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'upazilla' => 'required',
            'district' => 'required',
            'area' => 'required',
        ]);

        EconomicZones::where('id', $_id)->update([
            'name' => $request->get('name'),
            'upazilla' => $request->get('upazilla'),
            'district' => $request->get('district'),
            'area' => $request->get('area'),
            'remarks' => $request->get('remarks'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('settings/edit-eco-zone/' . $id);
    }

    /* Start of Notice related functions */

    public function notice()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::notice.list");
    }

    public function createNotice()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::notice.create", compact(''));
    }

    public function getNoticeDetailsData()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $notice = Notice::where('is_archive', 0)->orderBy('notice.updated_at', 'desc')
            ->get([
                'notice.id', 'heading', 'details', 'importance', 'status', 'notice.updated_at as update_date',
                'is_active'
            ]);

        return Datatables::of($notice)
            ->addColumn('action', function ($notice) use ($mode) {
                if ($mode) {
                    $url = "ConfirmDelete('" . Encryption::encodeId($notice->id) . "')";
                    return '<a href="/settings/edit-notice/' . Encryption::encodeId($notice->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a> '
                        . ' '
                        . '<a href="javascript:void(0)" ' .
                        " class='btn btn-xs btn-danger' onclick=$url><i class='fa fa-times'></i></a>";
                } else {
                    return '';
                }
            })
//            ->editColumn('details', function ($notice) {
//                return substr($notice->details, 0, 150) . ' <a href="/support/view-notice/' . Encryption::encodeId($notice->id) . '">'
//                    . 'See more... </a>';
//            })
            ->editColumn('update_date', function ($notice) {
                return CommonFunction::changeDateFormat(substr($notice->update_date, 0, 10));
            })
            ->editColumn('status', function ($notice) {
                return ucfirst($notice->status);
            })
            ->editColumn('importance', function ($notice) {
                return ucfirst($notice->importance);
            })
            ->editColumn('is_active', function ($desk) {
                if ($desk->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);


    }

    public function storeNotice(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'heading' => 'required',
            'details' => 'required',
            'status' => 'required',
            'importance' => 'required',
        ]);
        try {
            $insert = Notice::create(
                array(
                    'heading' => $request->get('heading'),
                    'details' => $request->get('details'),
                    'status' => $request->get('status'),
                    'importance' => $request->get('importance'),
                    'prefix' => $request->get('board_meeting'),
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editNotice($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = Notice::where('id', $id)->first();
        return view("Settings::notice.edit", compact('data', 'encrypted_id'));
    }

    public function updateNotice($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $faq_id = Encryption::decodeId($id);

        $this->validate($request, [
            'heading' => 'required',
            'details' => 'required',
            'status' => 'required',
            'importance' => 'required',
        ]);

        Notice::where('id', $faq_id)->update([
            'heading' => $request->get('heading'),
            'details' => $request->get('details'),
            'status' => $request->get('status'),
            'importance' => $request->get('importance'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-notice/' . $id);
    }

    /* End of Notice related functions */

    /* Start of Logo related functions */
    public function logo()
    {
        $logoInformation = logo::all();
        return view("Settings::logo.list", compact('logoInformation'));
    }

    public function storeLogo(request $request)
    {
        $company_logo = $request->file('company_logo');
        $path = "uploads/logo";
        if ($request->hasFile('company_logo')) {
//            $img_file = trim(sprintf("%s", uniqid($prefix, true))) . $company_logo->getClientOriginalName();
            $img_file = $company_logo->getClientOriginalName();
            $mime_type = $company_logo->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png' || $mime_type == 'image/webp') {
                $company_logo->move($path, $img_file);
                $filepath = $path . '/' . $img_file;

                logo::where('id', 1)->update([
                    'logo' => $filepath,
                    'title' => $request->get('title'),
                    'manage_by' => $request->get('manage_by'),
                    'help_link' => $request->get('help_link'),
                    'created_by' => CommonFunction::getUserId()
                ]);
                Session::flash('success', 'Data has been changed successfully.');
                return redirect('/settings/edit-logo');
            } else {
                Session::flash('error', 'Company logo must be png or jpg or jpeg format');
                return redirect()->back();

            }
        } else {
            logo::where('id', 1)->update([
                'title' => $request->get('title'),
                'manage_by' => $request->get('manage_by'),
                'help_link' => $request->get('help_link'),
                'created_by' => CommonFunction::getUserId()
            ]);
            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/edit-logo');
        }
    }

    public function editLogo()
    {
        $logoInfo = Logo::first();
        return view("Settings::logo.edit", compact('logoInfo'));
    }

    public function serviceInfo()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $getList = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->get(['service_details.*', 'pt.name']);
        $services = ProcessType::orderby('name')->where('status', 1)->lists('name', 'id')->prepend('Select One', '');

        $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm',
                'area_id')->all();
        return view('Settings::service_info.service-info',
            compact('divisions', 'districts', 'thana', 'services', 'getList'));
    }

    public function createServiceInfoDetails()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $services = ProcessType::orderby('name')->where('status', 1)->lists('name', 'id');
        $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type', 1)->lists('area_nm',
                'area_id')->all();
        return view('Settings::service_info.create', compact('divisions', 'districts', 'thana', 'services', 'getList'));
    }

    public function serviceSave(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'process_type_id' => 'required|unique:service_details',
            'terms_and_conditions' => 'required',
            'is_active' => 'required'
        ]);

        try {

            $pdfFile = $request->file('terms_and_conditions');

            $path = "uploads/pdf";
            if ($request->hasFile('terms_and_conditions')) {
                $pdf_file = $pdfFile->getClientOriginalName();
                $mime_type = $pdfFile->getClientMimeType();
                if ($mime_type == 'application/pdf') {
                    $pdfFile->move($path, $pdf_file);
                    $filepath = $path . '/' . $pdf_file;
                } else {
                    Session::flash('error', 'File must be pdf format');
                    return redirect()->back();
                }
            }

            $insert = ServiceDetails::create(
                array(
                    'process_type_id' => $request->get('process_type_id'),
                    'description' => $request->get('description'),
                    'status' => $request->get('is_active'),
                    'terms_and_conditions' => $filepath,
                    'created_by' => CommonFunction::getUserId(),
                ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-service-info-details/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editServiceInfoDetails($encrypted_id)
    {

        $id = Encryption::decodeId($encrypted_id);
        $data = ServiceDetails::where('id', $id)->first();
        $pdf = explode("/", $data->terms_and_conditions);
        $filepdf = $pdf[2];
        $services = ProcessType::orderby('name')->where('status', 1)->lists('name', 'id');
        $getList = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->get(['service_details.*', 'pt.name']);
        return view("Settings::service_info.edit", compact('data', 'encrypted_id', 'getList', 'services', 'filepdf'));
    }

    public function updateServiceDetails($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $service_id = Encryption::decodeId($id);

        $this->validate($request, [
            'is_active' => 'required',
        ]);

        $pdfFile = $request->file('terms_and_conditions');
        if (isset($pdfFile)) {
            $path = "uploads/pdf";
            if ($request->hasFile('terms_and_conditions')) {
                $pdf_file = $pdfFile->getClientOriginalName();
                $mime_type = $pdfFile->getClientMimeType();
                if ($mime_type == 'application/pdf' || $mime_type == 'application/octet-stream') {
                    $pdfFile->move($path, $pdf_file);
                    $filepath = $path . '/' . $pdf_file;
                } else {
                    Session::flash('error', 'File must be pdf format');
                    return redirect()->back();

                }
            }
        } else {
            $filepath = $request->get('exist_pdf');
        }

        ServiceDetails::where('id', $service_id)->update([
            'description' => $request->get('description'),
            'status' => $request->get('is_active'),
            'terms_and_conditions' => $filepath,
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect()->back();
    }


//    public function storeLogo(request $request,$encrypted_id){
////        dd($request->all());
//        $id = Encryption::decodeId($encrypted_id);
//        $company_logo = $request->file('company_logo');
//        $path = "uploads/logo";
//        if ($request->hasFile('company_logo')) {
////            $img_file = trim(sprintf("%s", uniqid($prefix, true))) . $company_logo->getClientOriginalName();
//            $img_file = $company_logo->getClientOriginalName();
//            $mime_type = $company_logo->getClientMimeType();
//            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
//                $company_logo->move($path, $img_file);
//                $filepath= $path . '/' . $img_file;
//
//                logo::where('id', $id)->update([
//                    'logo' => $filepath,
//                    'title' => $request->get('title'),
//                    'manage_by' => $request->get('manage_by'),
//                    'help_link' => $request->get('help_link'),
//                    'created_by' => CommonFunction::getUserId()
//                ]);
//                Session::flash('success', 'Data has been changed successfully.');
//                return redirect('/settings/logo');
//            } else {
//                \Session::flash('error', 'Company logo must be png or jpg or jpeg format');
//                return redirect()->back();
//
//            }
//        }else{
//            logo::where('id', $id)->update([
//                'title' => $request->get('title'),
//                'manage_by' => $request->get('manage_by'),
//                'help_link' => $request->get('help_link'),
//                'created_by' => CommonFunction::getUserId()
//            ]);
//            Session::flash('success', 'Data has been changed successfully.');
//            return redirect('/settings/logo');
//        }
//    }
//    public function editLogo($encrypted_id){
//        $id = Encryption::decodeId($encrypted_id);
//        $logoInfo=Logo::find($id);
//        return view("Settings::logo.edit", compact('logoInfo','encrypted_id'));
//    }

    /* Start of High Commission related functions */

    public function highCommission()
    {
        return view("Settings::high_commission.list");
    }

    public function createHighCommission()
    {
        $countries = Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id');
        return view("Settings::high_commission.create", compact('countries'));
    }

    public function getHighCommissionData()
    {
        $mode = ACL::getAccsessRight('settings', 'E');
        $hc = HighComissions::leftJoin('country_info', 'high_comissions.country_id', '=', 'country_info.id')
            ->orderBy('country_info.name')
            ->get([
                'high_comissions.id', 'high_comissions.name', 'address', 'phone', 'email', 'is_active',
                'country_info.name as country'
            ]);
        return Datatables::of($hc)
            ->addColumn('action', function ($hc) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-high-commission/' . Encryption::encodeId($hc->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a> ';
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($notice) {
                if ($notice->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->editColumn('country', function ($hc) {
                if ($hc->country) {
                    return ucfirst(strtolower($hc->country));
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeHighCommission(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'country_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => '',
            'email' => 'required|email',
        ]);

        $highCommission = new HighComissions();
        $highCommission->name = $request->get('name');
        $highCommission->country_id = $request->get('country_id');
        $highCommission->address = $request->get('address');
        $highCommission->phone = $request->get('phone');
        $highCommission->email = $request->get('email');
        $highCommission->save();

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-high-commission/' . Encryption::encodeId($highCommission->id));
    }

    public function editHighCommission($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = HighComissions::where('id', $id)->first();
        $hc_country = Countries::where('id', $data->country_id)->pluck('nicename');
        $countries = Countries::where('country_status', 'Yes')->orderBy('name', 'asc')->lists('nicename', 'id');

        return view("Settings::high_commission.edit", compact('data', 'encrypted_id', 'hc_country', 'countries'));
    }

    public function updateHighCommission($highCommissionId, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $decodedId = Encryption::decodeId($highCommissionId);

        $this->validate($request, [
            'country_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => '',
            'email' => 'required',
        ]);

        $highCommission = HighComissions::find($decodedId);
        $highCommission->name = $request->get('name');
        $highCommission->country_id = $request->get('country_id');
        $highCommission->address = $request->get('address');
        $highCommission->phone = $request->get('phone');
        $highCommission->email = $request->get('email');
        $highCommission->is_active = $request->get('is_active');
        $highCommission->save();


        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-high-commission/' . $highCommissionId);
    }

    /* End of High Commission related functions */

    /* Start of HS Code related functions */

    public function HsCodes()
    {
        $rows = HsCodes::orderBy('product_name')
            ->where('is_archive', 0)
            ->get(['product_name', 'hs_code', 'is_active', 'id']);
        return view("Settings::hs_codes.list", compact('rows'));
    }

    public function createHsCode()
    {
        return view("Settings::hs_codes.create");
    }

    public function storeHsCode(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'product_name' => 'required',
        ]);

        $insert = HsCodes::create([
            'hs_code' => $request->get('hs_code'),
            'product_name' => $request->get('product_name'),
            'is_active' => 1,
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The HS Code is stored successfully!');
        return redirect('/settings/edit-hs-code/' . Encryption::encodeId($insert->id));
    }


    public function editHsCode($encrypted_id)
    {

        $id = Encryption::decodeId($encrypted_id);
        $data = HsCodes::where('id', $id)->first();
        return view("Settings::hs_codes.edit", compact('data', 'encrypted_id'));
    }


    public function updateHsCode($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'hs_code' => 'required',
        ]);

        HsCodes::where('id', $id)->update([
            'hs_code' => $request->get('hs_code'),
            'product_name' => $request->get('product_name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The HS Code  has been changed successfully.');
        return redirect('/settings/edit-hs-code/' . $enc_id);
    }


    /* End of HS Code related functions */

    /* Start of Industrial Category related functions */

    public function IndusCat()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9882]');
        }
        $rows = IndustryCategories::orderBy('industry_categories.name')
            ->leftJoin('colors', 'industry_categories.color_id', '=', 'colors.id')
            ->where('industry_categories.is_archive', 0)
            ->get([
                'industry_categories.name as indus_cat', 'colors.name as colo', 'industry_categories.id as indus_id',
                'industry_categories.is_active'
            ]);
        return view("Settings::industrial_category.list", compact('rows'));
    }

    public function createIndusCat()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9883]');
        }
        $colors = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
        return view("Settings::industrial_category.create", compact('colors'));
    }

//
    public function storeIndusCat(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9884]');
        }
        $this->validate($request, [
            'name' => 'required',
            'color_id' => 'required',
        ]);

        $insert = IndustryCategories::create([
            'name' => $request->get('name'),
            'color_id' => $request->get('color_id'),
            'is_active' => 1,
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The industrial category is stored successfully!');
        return redirect('/settings/edit-indus-cat/' . Encryption::encodeId($insert->id));
    }

    public function editIndusCat($encrypted_id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9885]');
        }
        $id = Encryption::decodeId($encrypted_id);
        $data = IndustryCategories::where('id', $id)->first();
        $colors = Colors::where('is_active', 1)->where('is_archive', 0)->orderBy('name')->lists('name', 'id');
        return view("Settings::industrial_category.edit", compact('data', 'encrypted_id', 'colors'));
    }

    public function updateIndusCat($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9886]');
        }
        $id = Encryption::decodeId($enc_id);
        $this->validate($request, [
            'name' => 'required',
            'color_id' => 'required',
        ]);

        IndustryCategories::where('id', $id)->update([
            'name' => $request->get('name'),
            'color_id' => $request->get('color_id'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The industrial category  has been changed successfully.');
        return redirect('/settings/edit-indus-cat/' . $enc_id);
    }

    /* End of Industrial Category related functions */


    /* Start of User Desk related functions */

    public function userDesk()
    {
        return view("Settings::user_desk.list");
    }

    public function createUserDesk()
    {
        $desks = UserDesk::orderBy('desk_name')->lists('desk_name', 'desk_id');
        return view("Settings::user_desk.create", compact('desks'));
    }

    public function getUserDeskData()
    {
        $mode = ACL::getAccsessRight('settings', 'E');
        $desk = UserDesk::orderBy('desk_name')
            ->get(['desk_id', 'desk_name', 'desk_status', 'delegate_to_desk']);

        return Datatables::of($desk)
            ->addColumn('action', function ($desk) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-user-desk/' . Encryption::encodeId($desk->desk_id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a> ';
                } else {
                    return '';
                }
            })
            ->editColumn('desk_status', function ($desk) {
                if ($desk->desk_status == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeUserDesk(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'desk_name' => 'required',
            'desk_status' => 'required',
            'delegate_to_desk' => '',
        ]);

        $insert = UserDesk::create([
            'desk_name' => $request->get('desk_name'),
            'desk_status' => $request->get('desk_status'),
            'delegate_to_desk' => $request->get('delegate_to_desk'),
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-user-desk/' . Encryption::encodeId($insert->id));
    }

    public function editUserDesk($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = UserDesk::where('desk_id', $id)->first();

        $desks = UserDesk::orderBy('desk_name')->lists('desk_name', 'desk_id');

        return view("Settings::user_desk.edit", compact('data', 'encrypted_id', 'desks'));
    }

    public function updateUserDesk($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'desk_name' => 'required',
            'desk_status' => 'required',
            'delegate_to_desk' => '',
        ]);

        UserDesk::where('desk_id', $id)->update([
            'desk_name' => $request->get('desk_name'),
            'desk_status' => $request->get('desk_status'),
            'delegate_to_desk' => $request->get('delegate_to_desk'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-user-desk/' . $enc_id);
    }

    /* End of User Desk related functions */

    /* Start of Security related functions */

    public function security()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1032]');
        }

        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::security.list", compact('user_types'));
    }

    public function getSecurityData()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            return response()->json([
                'data' => [],
                'error' => 'You have no access right! Please contact system administration for more information. [AR-1031]'
            ]);
        }

        $_data = SecurityProfile::get([
            'id', 'profile_name', 'allowed_remote_ip', 'week_off_days', 'work_hour_start', 'work_hour_end',
            'active_status'
        ]);
        return Datatables::of($_data)
            ->addColumn('action', function ($_data) {
                if ($_data->id != 1) {
                    return '<a href="/settings/edit-security/' . Encryption::encodeId($_data->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> <b>Open<b/></a>';
                }
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function storeSecurity(Request $request)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1031]');
            return Redirect::back()->withInput();
        }

        $this->validate($request, [
            'profile_name' => 'required',
            'allowed_remote_ip' => 'required',
            'week_off_days' => 'required',
        ]);
        SecurityProfile::create(
            array(
                'profile_name' => $request->get('profile_name'),
//                    'user_type' => $request->get('user_type'),
                'user_email' => $request->get('user_email'),
                'allowed_remote_ip' => $request->get('allowed_remote_ip'),
                'week_off_days' => $request->get('week_off_days'),
                'work_hour_start' => $request->get('work_hour_start'),
                'work_hour_end' => $request->get('work_hour_end'),
                'active_status' => $request->get('active_status'),
                'created_by' => CommonFunction::getUserId()
            ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/security');
    }

    public function editSecurity($_id)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1040]');
        }

        $id = Encryption::decodeId($_id);
        $data = SecurityProfile::where('id', $id)->first();
        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::security.edit", compact('data', '_id', 'user_types'));
    }

    public function updateSecurity($id, Request $request)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1034]');
            return Redirect::back()->withInput();
        }

        $_id = Encryption::decodeId($id);

        $this->validate($request, [
            'profile_name' => 'required',
            'allowed_remote_ip' => 'required',
            'week_off_days' => 'required',
        ]);

        SecurityProfile::where('id', $_id)->update([
            'profile_name' => $request->get('profile_name'),
//            'user_type' => $request->get('user_type'),
            'user_email' => $request->get('user_email'),
            'allowed_remote_ip' => $request->get('allowed_remote_ip'),
            'week_off_days' => $request->get('week_off_days'),
            'work_hour_start' => $request->get('work_hour_start'),
            'work_hour_end' => $request->get('work_hour_end'),
            'active_status' => $request->get('active_status'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/security');
    }

    /* End of Security related functions */

    /* Start of Server Inforelated functions */

    public function serverInfo(Request $request)
    {
        $getList = [
            [
                'caption' => 'Server Status',
                'function' => 'command:apache-status,whoami'
            ],
            [
                'caption' => 'Database',
                'function' => 'command:mysql_stat,show global status,show processlist,show table status,show full processlist'
            ],
            [
                'caption' => 'Process Status',
                'function' => 'command:NID process Status,PDF Gen Status'
            ],
            [
                'caption' => 'env file',
                'function' => "DB Host : " . env('DB_HOST') .
                    '<br/>User : ' . env('DB_USERNAME') .
                    '<br/>Database : ' . env('DB_DATABASE') .
                    '<br/>' .
                    '<br/>Mail Driver : ' . env('MAIL_DRIVER') .
                    '<br/>Mail Host : ' . env('MAIL_HOST') .
                    '<br/>Mail Port : ' . env('MAIL_PORT') .
                    '<br/>' .
                    '<br/>Recaptcha Public Key : ' . env('RECAPTCHA_PUBLIC_KEY') .
                    '<br/>Recaptcha Private Key : ' . env('RECAPTCHA_PRIVATE_KEY')
            ],
            [
                'caption' => 'php Info',
                'function' => 'command:phpinfo'
            ]
        ];
        return view("Settings::server-info.list", compact('getList'));
    }

    public function getCommandResult(Request $request)
    {
        $command = $request->get('command');
        $output = '';
        $dboutput = '';
        $result = null;
        echo 'Executing command ' . $command . ' at ' . date('h:i:s.u T', time()) . '<br />';
        if (Auth::user()->user_type == '1x101') {
            switch ($command) {
                case 'NID process Status':
                    $result = DB::select(
                        DB::raw("select 'In last 60 Minutes' as Period, verification_flag status, count(id) as noOfNID from pilgrims_nid where submitted_at > date_add(now(), interval -60 minute) group by Period,verification_flag
                                                union all
                                                select 'Total' as Period, verification_flag status, count(id) as noOfNID from pilgrims_nid group by Period,verification_flag"));
                    $dboutput = count($result);
                    break;
                case 'PDF Gen Status':
                    $result = DB::select(
                        DB::raw("select 'In last 60 Minutes' as Period, status, pdf_type, count(id) as noOfDoc from pdf_generator where created_at > date_add(now(), interval -60 minute) group by Period, status, pdf_type
                                                union all
                                                select 'Total' as Period, status, pdf_type, count(id) as noOfDoc from pdf_generator  group by Period, status, pdf_type"));
                    $dboutput = count($result);
                    break;
                case 'phpinfo':
                    phpinfo();
                    break;
                case 'apache-status':
                    $output = shell_exec('apachectl status');
                    break;
                case 'whoami':
                    $output = shell_exec('whoami');
                    $output .= '<br />' . UtilFunction::getVisitorRealIP();
                    break;
                case 'show processlist':
                case 'show table status':
                case 'show full processlist':
                case 'show global status':
                    $result = DB::select(DB::raw($command));
                    $dboutput = count($result);
                    break;
                case 'top':
                    $output = shell_exec('top');
                    break;
                case 'dir':
                    $output = shell_exec('dir');
                    break;
                case 'mysql_stat':
                    $link = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
                    $status = explode('  ', mysqli_stat($link));
                    echo '<pre>';
                    print_r($status);
                    $dboutput = '-1';
                    echo '</pre>';
                    break;
                default:
                    $output = 'command : ' . $command . ' not found!';
                    break;
            }
        } else {
            $output = 'User ' . CommonFunction::getUserFullName() . ' has no access to execute this command!';
        }
        if ($output) {
            echo "<div><pre>$output</pre></div>";
        } elseif ($dboutput != '') {
            if ($dboutput > 0) {
                echo createHTMLTable($result);
            } elseif ($dboutput < 0) {
                //system table
            } else {
                echo "<pre><strong><em>$command</em></strong> has no result!</pre>";
            }
        } else {
            echo "<pre>command <strong><em>$command</em></strong> has no response text!</pre>";
        }
        echo '<br />Executed on ' . date('h:i:s.u T', time());
        return '';
    }

    public function sendNotificationToUserType(Request $request, $userType)
    {
        $userTypeDecoded = Encryption::decodeId($userType);
        try {
            if (Auth::user()->user_type == '1x101') {
                $users = User::where('user_type', $userTypeDecoded)->get([
                    'id', 'user_type', 'user_email', 'user_phone'
                ]);
                if (isset($users) && count($users) > 0) {
                    foreach ($users as $user) {
                        $smsData['source'] = $request->get('message');
                        $smsData['destination'] = $user->user_phone;
                        $smsData['msg_type'] = 'SMS';
                        $smsData['ref_id'] = $user->id;
                        $smsData['is_sent'] = 0;
                        $smsData['priority'] = $request->get('priority');
                        Notification::create($smsData);
                    }
                }
                Session::flash("success", 'Sent notification.');
            } else {
                Session::flash("error", 'No access right');
            }
            return redirect('settings/edit-user-type/' . $userType);
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Somthing Wrong.');
            return redirect('settings/edit-user-type/' . $userType);
        }
    }

    /* End of Server Inforelated functions */

    /* Start of Ports related functions */

    public function ports()
    {
        $rows = Ports::orderBy('ports.name')
            ->leftJoin('country_info', 'country_info.iso', '=', 'ports.country_iso')
            ->where('ports.is_archive', 0)
            ->get([
                'country_info.nicename as country', 'ports.name as port_name', 'ports.id as port_id', 'ports.is_active'
            ]);
        return view("Settings::ports.list", compact('rows'));
    }

    public function createPort()
    {
        $countries = Countries::orderBy('nicename')->where('country_status', 'Yes')->lists('nicename', 'iso');
        return view("Settings::ports.create", compact('countries'));
    }

    public function storePort(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'country_iso' => 'required',
            'name' => 'required',
        ]);

        $insert = Ports::create([
            'country_iso' => $request->get('country_iso'),
            'name' => $request->get('name'),
            'is_active' => 1,
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The port is stored successfully!');
        return redirect('/settings/edit-port/' . Encryption::encodeId($insert->id));
    }

    public function editPort($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = Ports::where('id', $id)->first();
        $countries = Countries::orderBy('nicename')->where('country_status', 'Yes')->lists('nicename', 'iso');
        return view("Settings::ports.edit", compact('data', 'countries', 'encrypted_id'));
    }

    public function updatePort($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'country_iso' => 'required',
            'name' => 'required',
        ]);

        Ports::where('id', $id)->update([
            'country_iso' => $request->get('country_iso'),
            'name' => $request->get('name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The port has been changed successfully.');
        return redirect('/settings/edit-port/' . $enc_id);
    }

    /* End of Ports related functions */

    /* Start of Units related functions */

    public function Units()
    {
        $rows = Units::orderBy('name')->where('is_archive', 0)->get(['id', 'name', 'is_active']);
        return view("Settings::units.list", compact('rows'));
    }

    public function createUnit()
    {
        $active_status = ['1' => 'Active', '2' => 'Inactive'];
        return view("Settings::units.create", compact('active_status'));
    }

    public function storeUnit(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'name' => 'required',
            'is_active' => 'required',
        ]);

        $insert = Units::create([
            'name' => $request->get('name'),
            'is_active' => $request->get('is_active'),
            'created_by' => CommonFunction::getUserId(),
        ]);

        Session::flash('success', 'The new unit is stored successfully!');
        return redirect('/settings/edit-unit/' . Encryption::encodeId($insert->id));
    }

    public function editUnit($id)
    {
        $_id = Encryption::decodeId($id);
        $data = Units::where('id', $_id)->first();
        $active_status = ['1' => 'Active', '2' => 'Inactive'];
        return view("Settings::units.edit", compact('data', 'active_status', 'id'));
    }

    public function updateUnit($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($enc_id);

        $this->validate($request, [
            'name' => 'required',
            'is_active' => 'required',
        ]);

        Units::where('id', $id)->update([
            'name' => $request->get('name'),
            'is_active' => $request->get('is_active'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'The unit has been changed successfully.');
        return redirect('/settings/edit-unit/' . $enc_id);
    }

    /* End of Units related functions */
    public function softDelete($model, $_id)
    {
        try {
            $id = Encryption::decodeId($_id);

            switch (true) {
                case ($model == "Area"):
                    $cond = Area::where('area_id', $id);
                    $list = 'area-list';
                    break;
                case ($model == "Bank"):
                    $cond = Bank::where('id', $id);
                    $list = 'bank-list';
                    break;
                case ($model == "park-info"):
                    $cond = ParkInfo::where('id', $id);
                    $list = 'park-info';
                    break;
                case ($model == "Branch"):
                    $cond = BankBranch::where('id', $id);
                    $list = 'branch-list';
                    break;
                case ($model == "Currency"):
                    $cond = Currencies::where('id', $id);
                    $list = 'currency';
                    break;
                case ($model == "Document"):
                    $cond = docInfo::where('id', $id);
                    $list = 'document';
                    break;
                case ($model == "EcoZone"):
                    $cond = EconomicZones::where('id', $id);
                    $list = 'eco-zones';
                    break;
                case ($model == "HighCommissions"):
                    $cond = HighComissions::where('id', $id);
                    $list = 'high-commission';
                    break;
                case ($model == "hsCode"):
                    $cond = HsCodes::where('id', $id);
                    $list = 'hs-codes';
                    break;
                case ($model == "IndustryCategories"):
                    $cond = IndustryCategories::where('id', $id);
                    $list = 'indus-cat';
                    break;
                case ($model == "Notice"):
                    $cond = Notice::where('id', $id);
                    $list = 'notice';
                    break;
                case ($model == "Port"):
                    $cond = Ports::where('id', $id);
                    $list = 'ports';
                    break;
                case ($model == "Unit"):
                    $cond = Units::where('id', $id);
                    $list = 'units';
                    break;
                default:
                    Session::flash('error', 'Invalid Model! error code (Del-' . $model . ')');
                    return Redirect::back();
            }

            $cond->update([
                'is_archive' => 1,
                'updated_by' => CommonFunction::getUserId()
            ]);

            Session::flash('success', 'Data has been deleted successfully.');
            return redirect('/settings/' . $list);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[SC0009]');
            return Redirect::back()->withInput();
        }
    }

    /* Start of dashboard Object related functions */

    public function dashboardObj()
    {
        $getList = Dashboard::orderBy('db_obj_caption')
            ->get(['id', 'db_obj_title', 'db_obj_caption', 'db_obj_type', 'db_user_type']);
        return view("Settings::dashboard_obj.list", compact('getList'));
    }

    public function createDashboardObj()
    {
        return view("Settings::dashboard_obj.create", compact(''));
    }

    public function storeDashboardObj(Request $request)
    {
        $this->validate($request, [
            'db_obj_title' => 'required',
            'db_obj_caption' => 'required',
            'db_obj_type' => 'required',
            'db_obj_para1' => 'required',
            'db_user_type' => 'required',
        ]);

        $insert = Dashboard::create(
            array(
                'db_obj_title' => $request->get('db_obj_title'),
                'db_obj_caption' => $request->get('db_obj_caption'),
                'db_obj_type' => $request->get('db_obj_type'),
                'db_obj_para1' => $request->get('db_obj_para1'),
                'db_user_type' => $request->get('db_user_type'),
                'updated_by' => CommonFunction::getUserId()
            ));

        Session::flash('success', 'Data is stored successfully!');
        return redirect('/settings/edit-dash-obj/' . Encryption::encodeId($insert->id));
    }

    public function editDashboardObj($_id)
    {
        $id = Encryption::decodeId($_id);
        $data = Dashboard::where('id', $id)->first();
        $user_types = UserTypes::lists('type_name', 'id');
        return view("Settings::dashboard_obj.edit", compact('data', '_id', 'user_types'));
    }

    public function updateDashboardObj($id, Request $request)
    {
        $_id = Encryption::decodeId($id);
        $this->validate($request, [
            'db_obj_title' => 'required',
            'db_obj_caption' => 'required',
            'db_obj_type' => 'required',
            'db_obj_para1' => 'required',
            'db_user_type' => 'required',
        ]);

        Dashboard::where('id', $_id)->update([
            'db_obj_title' => $request->get('db_obj_title'),
            'db_obj_caption' => $request->get('db_obj_caption'),
            'db_obj_type' => $request->get('db_obj_type'),
            'db_obj_para1' => $request->get('db_obj_para1'),
            'db_user_type' => $request->get('db_user_type'),
            'updated_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-dash-obj/' . $id);
    }


    public function companyInfo()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9887]');
        }
        try {
            return view("Settings::company_info.list");
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function createCompany()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9888]');
        }
        try {
            $divisions = ['' => 'Select Division '] + AreaInfo::orderby('area_nm')->where('area_type',
                    1)->lists('area_nm', 'area_id')->all();
            return view("Settings::company_info.create", compact('divisions'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function storeCompany(request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9889]');
        }
        $this->validate($request, [
            'company_name' => 'required|unique:company_info',
            'division' => 'required',
            'district' => 'required',
            'thana' => 'required'
        ]);
        try {
            $companyData = new CompanyInfo();
            $companyData->company_name = $request->get('company_name');
            $companyData->company_name_bn = $request->get('company_name_bn');
            $companyData->division = $request->get('division');
            $companyData->district = $request->get('district');
            $companyData->thana = $request->get('thana');
            $companyData->save();
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/company-info-action/' . Encryption::encodeId($companyData->id));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function getCompanyData()
    {

        try {
            $companyInformation = CompanyInfo::
//            leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
//                ->leftJoin('process_list', 'process_list.company_id', '=', 'company_info.id')
//                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
//                ->leftJoin('users as user', 'user.id', '=', 'company_info.created_by')
//                ->select('company_info.id','company_info.created_at','company_info.is_approved', 'company_info.is_rejected', 'company_info.company_status',
//                    'company_name', 'ai.area_nm as divisionName', 'di.area_nm as districtName'
//                    DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_name')
//                )
            orderBy('company_info.created_at', 'desc')
                ->get([
                    'company_info.id',
                    'company_info.created_at',
                    'company_info.is_approved',
                    'company_info.is_rejected',
                    'company_info.company_status',
                    'company_name'
                ]);

            $mode = ACL::getAccsessRight('settings', 'V');

            return Datatables::of($companyInformation)
                ->editColumn('is_approved', function ($companyInformation) {
                    if ($companyInformation->is_approved == 1) {
                        $status = "Approved";
                        $class = "label label-info";
                    } else {
                        if ($companyInformation->is_rejected == 'yes') {
                            $status = "Rejected";
                            $class = "label label-danger";
                        } else {
                            $status = "Not Approved";
                            $class = "label label-warning";
                        }
                    }
                    return '<span class="' . $class . '">' . $status . '</span>';
                })
                ->editColumn('company_name', function ($companyInformation) {
                    $returnData = $companyInformation->company_name;
                    if ($companyInformation->divisionName) {
                        $returnData .= ", " . $companyInformation->divisionName;
                    }
                    if ($companyInformation->districtName) {
                        $returnData .= ", " . $companyInformation->districtName;
                    }
                    return $returnData;
                })
                ->editColumn('created_at', function ($companyInformation) {
                    return date('d-M-Y', strtotime($companyInformation->created_at));
                })
//                ->editColumn('updated_at', function ($companyInformation) {
//                    return date('d-M-Y h:i:s a', strtotime($companyInformation->updated_at));
//                })
                ->addColumn('action', function ($companyInformation) use ($mode) {
                    if ($mode) {
                        $html = '<a href="' . URL::to('settings/company-info-action/' . Encryption::encodeId($companyInformation->id)) .
                            '" class="btn btn-primary btn-xs">Open</a> ';
                        if ($companyInformation->is_approved == 1) {
                            if ($companyInformation->company_status == 0) { // status 0 = inactive
                                $html .= '<a href="' . URL::to('settings/company-change-status/' . Encryption::encodeId($companyInformation->id) . '/' .
                                        Encryption::encodeId(1)) . ' " class="btn btn-success btn-xs"onclick="return confirm(\'Are you sure you want to activate?\')" title="Please click to Activate">Activate</a> ';
                            } else { // status 1 = active
                                $html .= '<a href="' . URL::to('settings/company-change-status/' . Encryption::encodeId($companyInformation->id) . '/' .
                                        Encryption::encodeId(0)) . '"class="btn btn-danger btn-xs"onclick="return confirm(\'Are you sure you want to deactivate?\')" title="Please click to deactivate">Deactivate</a> ';
                            }
                        }
                        $checkUrl = CommonFunction::getBasicInfoUrl($companyInformation->id);
                        if (!empty($checkUrl)) {
                            $html .= '<a href="' . URL::to($checkUrl) . ' " target="_blank" class="btn btn-success btn-xs">Basic Info</a> ';
                        }
                        return $html;
                    } else {
                        return '';
                    }
                })
                ->make(true);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    /**************** For Rejected Draft Company *********/
    public function rejectedDraftCompanyList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            abort(401, 'You have no access right! Please contact system administration for more information. [SC-9872]');
        }
        try {
            return view("Settings::regected_draft_company.list");
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function getRejectedDraftCompanyList()
    {
        try {
            $regectedDraftCompanies = CompanyInfo::where('is_rejected', 'yes')
                ->orWhere('is_approved', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            $mode = ACL::getAccsessRight('settings', 'V');

            return Datatables::of($regectedDraftCompanies)
                ->editColumn('serial_no', function () {
                    return '';
                })
                ->editColumn('status', function ($data) {
                    if ($data->is_rejected == 'yes') {
                        return 'Rejected';
                    } elseif ($data->is_approved == 0) {
                        return 'Draft';
                    }
                })
                ->editColumn('created_at', function ($data) {
                    return date('d-M-Y', strtotime($data->created_at));
                })
                ->addColumn('action', function ($data) use ($mode) {
                    if ($mode) {
                        $html = '<a href="' . URL::to('settings/company-info-action/' . Encryption::encodeId($data->id)) .
                            '" class="btn btn-primary btn-xs">Open</a> ';
                        if ($data->is_rejected !== 'yes') {
                            $html .= '<a href="' . URL::to('settings/rejected-draft-company-change-status/' . Encryption::encodeId($data->id)) . ' " class="btn btn-danger btn-xs"onclick="return confirm(\'Are you sure?\')" title="Please click to reject">Reject</a> ';
                        }
                        return $html;
                    } else {
                        return '';
                    }
                })
                ->make(true);
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function rejectedDraftCompanyReject($id)
    {
        try {
            $company_id = Encryption::decodeId($id);
            $companyReject = CompanyInfo::where('id', $company_id)->update(['is_rejected' => 'yes']);
            if ($companyReject) {
                Session::flash('success', 'Succssefully Company Rejected!');
                return redirect()->back();
            } else {
                Session::flash('error', 'Unknown error occured!');
                return redirect()->back();
            }
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    /**************** End of Rejected Draft Company *********/

    public function companyInfoAction($id)
    {
        try {
            $company_id = Encryption::decodeId($id);

            $companyDetails = CompanyInfo::leftJoin('area_info as ai', 'ai.area_id', '=', 'company_info.division')
                ->leftJoin('area_info as di', 'di.area_id', '=', 'company_info.thana')
                ->leftJoin('users as user', 'user.id', '=', 'company_info.created_by')
                ->select('company_info.*', 'user.user_first_name', 'user.user_middle_name', 'user.user_last_name',
                    'ai.area_nm as divisionName',
                    'di.area_nm as districtName'
//                    DB::raw('CONCAT(company_name, ", ", ai.area_nm,", ", di.area_nm) AS company_info')
                )
                ->where('company_info.id', $company_id)
                ->first();
            return view("Settings::company_info/edit", compact('companyDetails'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function companyApprovedStatus($id)
    {
        try {
            $company_id = Encryption::decodeId($id);

            $companyData = CompanyInfo::find($company_id);
            $companyData->is_approved = 1;
            $companyData->company_status = 1;
            $companyData->save();

            Session::flash('success', 'Company Status Changed Successfully');
            return redirect('/settings/company-info');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function companyRejectedStatus($id)
    {
        try {
            DB::beginTransaction();
            $company_id = Encryption::decodeId($id);

            // if any application is under process under this company
            // then rejection is not possible
            $countInProcessingApp = ProcessList::where(['process_type_id' => '100', 'company_id' => $company_id])
                ->whereNotIn('status_id', [-1, 5, 6, 25])
                ->count();
            if ($countInProcessingApp > 0) {
                DB::rollback();
                Session::flash('error', 'Sorry! Currently the Basic Application is under process under this 
                company, hence the company can not be rejected at this time.');
                return redirect('/settings/company-info');
            }
            $companyData = CompanyInfo::find($company_id);
            $companyData->is_rejected = 'yes';
            $companyData->save();

            // if company is rejected, then logout all users of this company
            Users::whereRaw("FIND_IN_SET($company_id, company_ids)")->update(['login_token' => '']);

            DB::commit();
            Session::flash('success', 'Company has ben rejected');
            return redirect('/settings/company-info');
        } catch (Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }


    public function companyChangeStatus($id, $status_id)
    {
        try {
            DB::beginTransaction();
            $company_id = Encryption::decodeId($id);
            $status = Encryption::decodeId($status_id);

            // if deactivate status then, logout all users of this company
            if ($status == 0) {
                // if any application is under process under this company
                // then rejection is not possible
                $countInProcessingApp = ProcessList::where(['process_type_id' => '100', 'company_id' => $company_id])
                    ->whereNotIn('status_id', [-1, 5, 6, 25])
                    ->count();
                if ($countInProcessingApp > 0) {
                    DB::rollback();
                    Session::flash('error', 'Sorry! Currently the Basic Application is under process under this 
                company, hence the company can not be deactivate at this time.');
                    return redirect('/settings/company-info');
                }

                // if company is rejected, then logout all users of this company
                Users::whereRaw("FIND_IN_SET($company_id, company_ids)")->update(['login_token' => '']);
            }

            $companyData = CompanyInfo::find($company_id);
            $companyData->company_status = $status;
            $companyData->save();

            DB::commit();
            Session::flash('success', 'Company Status Changed Successfully');
            return redirect()->back();
        } catch (Exception $e) {
            DB::commit();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function whatsNew()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::whats_new.list");
    }

    public function whatsNewCreate()
    {
        return view('Settings::whats_new.create');
    }

    public function whatsNewStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);
        try {

            $image = $request->file('image');
            $path = "uploads/logo";
            if ($request->hasFile('image')) {
                $img_file = $image->getClientOriginalName();
                $mime_type = $image->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                } else {
                    Session::flash('error', 'Image must be png or jpg or jpeg format');
                    return redirect()->back();

                }
            }
            $insert = WhatsNew::create(
                array(
                    'title' => $request->get('title'),
                    'description' => $request->get('description'),
                    'is_active' => $request->get('is_active'),
                    'image' => $filepath,
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();
//            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getWhatsNew()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = WhatsNew::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-whats-new/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('image', function ($datas) {
//                $title = $datas->image;
//                return '<img src="'.$datas->image.'"';
                // return "<img src='/$datas->image' style='width: 95%'>";
                return "<img src='/$datas->image' style='width: 95%' onerror=\"this.onerror=null;this.src='" . asset('/assets/images/photo_default.png') . "'\">";

            })
            ->editColumn('is_active', function ($datas) {
                if ($datas->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editWhatsNew($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = WhatsNew::where('id', $id)->first();
        return view("Settings::whats_new.edit", compact('data', 'encrypted_id'));
    }

    public function updateWhatsNew(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);

        $image = $request->file('image');
        $path = "uploads/logo";

        if ($request->hasFile('image')) {
            $img_file = $image->getClientOriginalName();
            $mime_type = $image->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            } else {
                Session::flash('error', 'Image must be png or jpg or jpeg format');
                return redirect()->back();

            }
        }

        if (isset($filepath)) {
        } else {
            $filepath = $request->get('exist_image');
        }
        WhatsNew::where('id', $id)->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
            'image' => $filepath,
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/whats-new');
    }

///////////////////////////Dashboard Slider///////////////////////////////

    public function dashSlider()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::dashboardSlider.list");
    }

    public function dashSliderCreate()
    {
        return view('Settings::dashboardSlider.create');
    }

    public function dashSliderStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required',
            'order' => 'required|numeric|min:0|max:100|unique:dashboard_slider,order'
        ]);

        try {
            $image = $request->file('image');
            $path = "uploads/logo";
            if ($request->hasFile('image')) {
                $img_file = $image->getClientOriginalName();
                $mime_type = $image->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                } else {
                    Session::flash('error', 'Image must be png or jpg or jpeg format');
                    return redirect()->back();

                }
            }

            $insert = DashBoardSlider::create(
                array(
                    'title' => $request->get('title'),
                    'description' => $request->get('description'),
                    'is_active' => $request->get('is_active'),
                    'image' => $filepath,
                    'created_by' => CommonFunction::getUserId(),
                    'url' => $request->url ? $request->url : '',
                    'order' => $request->order ? $request->order : '',
                ));

            Session::flash('success', 'Data is stored successfully!');
            return redirect('settings/dashboard-slider');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getDashSlider()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = DashBoardSlider::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-dashboard-slider/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('image', function ($datas) {
//                $title = $datas->image;
//                return '<img src="'.$datas->image.'"';
                // return "<img src='/$datas->image' style='width: 95%'>";
                return "<img src='/$datas->image' style='width: 95%' onerror=\"this.onerror=null;this.src='" . asset('/assets/images/photo_default.png') . "'\">";

            })
            ->editColumn('is_active', function ($datas) {
                if ($datas->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editDashSlider($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = DashBoardSlider::where('id', $id)->first();
        return view("Settings::dashboardSlider.edit", compact('data', 'encrypted_id'));
    }

    public function updateDashSlider(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required',
            'order' => 'required|numeric|min:0|unique:dashboard_slider,order,' . $id
        ]);

        $image = $request->file('image');
        $path = "uploads/logo";

        if ($request->hasFile('image')) {
            $img_file = $image->getClientOriginalName();
            $mime_type = $image->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png') {
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            } else {
                Session::flash('error', 'Image must be png or jpg or jpeg format');
                return redirect()->back();

            }
        }

        if (isset($filepath)) {
        } else {
            $filepath = $request->get('exist_image');
        }
        DashBoardSlider::where('id', $id)->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
            'image' => $filepath,
            'created_by' => CommonFunction::getUserId(),
            'url' => $request->url ? $request->url : '',
            'order' => $request->order ? $request->order : '',
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/dashboard-slider');
    }




    // Holiday Start
    public function holiday()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::holiday.list");
    }

    public function holidayCreate()
    {
        return view('Settings::holiday.create');
    }

    public function holidayStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'title' => 'required',
            'date' => 'required|date|date_format:d-M-Y',
        ]);
        try {

            $insert = Holiday::create([
                'title' => $request->get('title'),
                'holiday_date' => (!empty($request->get('date')) ? date('Y-m-d',
                    strtotime($request->get('date'))) : null),
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/edit-holiday/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    /**
     * @return mixed
     */
    public function getHoliday()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = Holiday::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-holiday/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('is_active', function ($datas) {
                if ($datas->is_active == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editHoliday($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = Holiday::where('id', $id)->first();
        return view("Settings::holiday.edit", compact('data', 'encrypted_id'));
    }

    public function updateHoliday(Request $request, $id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'date' => 'required|date|date_format:d-M-Y',
            'is_active' => 'required'
        ]);

        Holiday::where('id', $id)->update([
            'title' => $request->get('title'),
            'holiday_date' => (!empty($request->get('date')) ? date('Y-m-d', strtotime($request->get('date'))) : null),
            'is_active' => $request->get('is_active'),
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/edit-holiday/' . Encryption::encodeId($id));
    }

    // Holiday End

    public function HomePageSlider()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::home_page_slider.list");
    }

    public function HomePageSliderCreate()
    {
        return view('Settings::home_page_slider.create');
    }

    public function homePageSliderStore(Request $request)
    {

        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);
        try {

            $image = $request->file('slider_image');
            $path = "uploads/sliderImage";
            if ($request->hasFile('slider_image')) {
                $img_file = $image->getClientOriginalName();
                $mime_type = $image->getClientMimeType();
                if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png' || $mime_type == 'image/webp') {
                    $image->move($path, $img_file);
                    $filepath = $path . '/' . $img_file;
                } else {
                    Session::flash('error', 'Image must be png or jpg or jpeg or webp format');
                    return redirect()->back();

                }
            }
            $insert = HomePageSlider::create(
                array(
                    'name' => $request->get('name'),
                    'link' => $request->get('link'),
                    'description' => $request->get('description'),
                    'status' => $request->get('status'),
                    'slider_image' => $filepath,
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();
//            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function editHomePageSlider($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = HomePageSlider::where('id', $id)->first();
        return view("Settings::home_page_slider.edit", compact('data', 'encrypted_id'));
    }

    public function updateHomePageSlider(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'status' => 'required'
        ]);

        $image = $request->file('slider_image');
        $path = "uploads/sliderImage";

        if ($request->hasFile('slider_image')) {
            $img_file = $image->getClientOriginalName();
            $mime_type = $image->getClientMimeType();
            if ($mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/png' || $mime_type == 'image/webp') {
                $image->move($path, $img_file);
                $filepath = $path . '/' . $img_file;
            } else {
                Session::flash('error', 'Image must be png or jpg or jpeg or webp format');
                return redirect()->back();

            }
        }

        if (isset($filepath)) {
        } else {
            $filepath = $request->get('exist_slider_image');
        }
        HomePageSlider::where('id', $id)->update([
            'name' => $request->get('name'),
            'link' => $request->get('link'),
            'description' => $request->get('description'),
            'status' => $request->get('status'),
            'slider_image' => $filepath,
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/home-page-slider');
    }


    public function getHomePageSlider()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = HomePageSlider::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-home-page-slider/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('slider_image', function ($datas) {
                $title = $datas->slider_image;
                // return '<img src="'.$datas->image.'"';
                // return "<img src='/$datas->slider_image' alt='image missing' style='width: 95%; height: 80px;'>";
                return "<img src='/$datas->slider_image' alt='image missing' style='width: 95%; height: 80px;' onerror=\"this.onerror=null;this.src='" . asset('/assets/images/photo_default.png') . "'\">";

                // return "ok";
            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'label label-success';
                    $status = 'Active';
                } else {
                    $class = 'label label-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->make(true);
    }


    public function features()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::features.list");
    }

    public function featuresCreate()
    {
        return view('Settings::features.create');
    }

    public function featuresStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);
        try {
            $insert = SurveyFeatures::create(
                array(
                    'title' => $request->get('title'),
                    'feature_description' => $request->get('description'),
                    'is_active' => $request->get('is_active'),
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();

        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getFeatures()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = SurveyFeatures::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-features/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('is_active', function ($datas) {
                if ($datas->is_active == 1) {
                    $class = 'label label-success';
                    $status = 'Active';
                } else {
                    $class = 'label label-danger';
                    $status = 'Inactive';

                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->editColumn('feature_description', function ($datas) {
                return str_limit($datas->feature_description, 120);
            })
            ->make(true);
    }

    public function editFeatures($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = SurveyFeatures::where('id', $id)->first();
        return view("Settings::features.edit", compact('data', 'encrypted_id'));
    }

    public function updatefeatures(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'is_active' => 'required'
        ]);


        SurveyFeatures::where('id', $id)->update([
            'title' => $request->get('title'),
            'feature_description' => $request->get('description'),
            'is_active' => $request->get('is_active'),
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/features');
    }

    public function form()
    {
        return view("Settings::form");
    }

    /**************** For User Manual *********/
    public function Usermanual()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::user-manual.list");
    }

    public function getUsermanual()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $datas = UserManual::orderBy('id', 'desc')
            ->get();
        return Datatables::of($datas)
            ->addColumn('action', function ($datas) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/edit-user-manual/' . Encryption::encodeId($datas->id) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('status', function ($datas) use ($mode) {
                if ($datas->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }

    public function UsermanualCreate()
    {
        return view('Settings::user-manual.create');
    }

    public function UsermanualStore(Request $request)
    {
        // return $request->all();
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $this->validate($request, [
            'typeName' => 'required',
            'details' => 'required',
            'termsCondition' => 'required',
            'status' => 'required'
        ]);
        try {

            $pdfFile = $request->file('pdfFile');
            $path = "uploads/pdf";
            if ($request->hasFile('pdfFile')) {
                $pdf_file = $pdfFile->getClientOriginalName();
                $mime_type = $pdfFile->getClientMimeType();
                if ($mime_type == 'application/pdf' || $mime_type == 'application/octet-stream') {
                    $pdfFile->move($path, $pdf_file);
                    $filepath = $path . '/' . $pdf_file;
                } else {
                    Session::flash('error', 'File must be pdf format');
                    return redirect()->back();

                }
            }
            $insert = UserManual::create(
                array(
                    'typeName' => $request->get('typeName'),
                    'details' => $request->get('details'),
                    'termsCondition' => $request->get('termsCondition'),
                    'status' => $request->get('status'),
                    'pdfFile' => $filepath,
                    'created_by' => CommonFunction::getUserId()
                ));

            Session::flash('success', 'Data is stored successfully!');
            return \redirect()->back();
//            return redirect('/settings/edit-notice/' . Encryption::encodeId($insert->id));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . '[SC-]');
            return Redirect::back()->withInput();
        }
    }

    public function editUsermanual($encrypted_id)
    {
        $id = Encryption::decodeId($encrypted_id);
        $data = UserManual::where('id', $id)->first();
        $pdf = explode("/", $data->pdfFile);
        $filepdf = $pdf[2];
        return view("Settings::user-manual.edit", compact('data', 'encrypted_id', 'filepdf'));
    }


    public function updateUsermanual(Request $request, $id)
    {

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($id);

        $this->validate($request, [
            'typeName' => 'required',
            'details' => 'required',
            'termsCondition' => 'required',
            'status' => 'required'
        ]);

        $pdfFile = $request->file('pdfFile');
        $path = "uploads/pdf";

        if ($request->hasFile('pdfFile')) {
            $pdf_file = $pdfFile->getClientOriginalName();
            $mime_type = $pdfFile->getClientMimeType();
            if ($mime_type == 'application/pdf' || $mime_type == 'application/octet-stream') {
                $pdfFile->move($path, $pdf_file);
                $filepath = $path . '/' . $pdf_file;
            } else {
                Session::flash('error', 'File must be pdf format');
                return redirect()->back();

            }
        }

        if (isset($filepath)) {
        } else {
            $filepath = $request->get('exist_pdf');
        }
        UserManual::where('id', $id)->update([

            'typeName' => $request->get('typeName'),
            'details' => $request->get('details'),
            'termsCondition' => $request->get('termsCondition'),
            'status' => $request->get('status'),
            'pdfFile' => $filepath,
            'created_by' => CommonFunction::getUserId()
        ]);

        Session::flash('success', 'Data has been changed successfully.');
        return redirect('/settings/user-manual');
    }

    //{{--------------sector--------------}}
    public function sectorCreate()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::sector.create");
    }

    public function sectorStore(Request $request, $sectorId = '')
    {
        $this->validate($request, [
            'name' => 'required',
            'status' => 'required'
        ]);

        if (!empty($sectorId)) {
            $sectorId = Encryption::decodeId($sectorId);
        }

        $sector = Sector::findOrNew($sectorId);
        $sector->name = $request->name;
        $sector->status = $request->status;
        $sector->save();
        Session::flash('success', 'Sector Data Saved successfully.');
        return redirect('/settings/sector/list');
    }

    public function sectorList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::sector.list");
    }

    public function sectorOpen($id)
    {
        $decodedId = Encryption::decodeId($id);
        $sectorInfo = Sector::find($decodedId);
        return view('Settings::sector.open', compact('sectorInfo'));
    }

    public function sectorEdit($id)
    {
        $decodedId = Encryption::decodeId($id);
        $sectorInfo = Sector::find($decodedId);
        return view('Settings::sector.edit', compact('sectorInfo'));
    }

    public function getSectorList()
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $data = Sector::where('is_archive', 0)->orderBy('created_at', 'desc')
            ->get(['id', 'name', 'status']);
        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/sector/edit/' . Encryption::encodeId($data->id) .
                        '" class="btn btn-xs btn-info btn-sm"><i class="fa fa-folder-open"></i> Edit</a>&nbsp;&nbsp;&nbsp;<a href="/settings/sector/open/' . Encryption::encodeId($data->id) .
                        '" class="btn btn-xs btn-primary btn-sm"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Inactive</label>";
            })
            ->removeColumn('id')
            ->make(true);
    }

    /*----------------sub-sector-----------------*/

    public function subSectorStore(Request $request, $subSectorId = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }

        $decodedId = '';
        if ($subSectorId) {
            $decodedId = Encryption::decodeId($subSectorId);
        }
        $rules = [
            'name' => 'required',
            'division_id' => 'required',
            'status' => 'required'
        ];

        $validation = Validator::make(Input::all(), $rules);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }
        $sectorId = Encryption::decodeId($request->get('sector_id'));
        try {
            $findDuplicate = SubSector::where([
                'sector_id' => $sectorId,
                'name' => $request->get('name'),
                'division_id' => $request->get('division_id')
            ])
                ->where('id', '!=', $decodedId)
                ->get();
            if (count($findDuplicate) > 0) {
                return response()->json([
                    'error' => true,
                    'status' => 'Duplicate sub-sector with same name and department under this sector',
                ]);
            }

            DB::beginTransaction();
            $level = SubSector::findOrNew($decodedId);
            $level->sector_id = $sectorId;
            $level->name = $request->get('name');
            $level->division_id = $request->get('division_id');
            $level->status = $request->get('status');
            $level->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => '/settings/sector/open/' . Encryption::encodeId($sectorId)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }


    public function getSubSectorList(Request $request)
    {
        $mode = ACL::getAccsessRight('settings', 'V');
        $decodedSectorId = Encryption::decodeId($request->get('sector_id'));
        DB::statement(DB::raw('set @rownum=0'));
        $data = SubSector::leftJoin('sec_division_list', 'sec_division_list.id', '=', 'sec_sub_sector_list.division_id')
            ->where('sec_sub_sector_list.is_archive', 0)
            ->where('sec_sub_sector_list.sector_id', $decodedSectorId)
            ->orderBy('sec_sub_sector_list.created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'), 'sec_sub_sector_list.id', 'sec_sub_sector_list.name',
                'sec_division_list.name AS division', 'sec_sub_sector_list.sector_id', 'sec_sub_sector_list.status'
            ]);
        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    $btn = "<a class='subSectorEditBtn btn btn-xs btn-info' data-toggle='modal' data-target='#myModal' onclick='openModal(this)' data-action='/settings/sub-sector/edit/" . Encryption::encodeId($data->id) . "'><i class='fa fa-edit'></i> Edit</a> ";
                    if ($data->status == 1) {
                        $btn .= " <a class='addProductBtn btn btn-xs btn-primary' data-toggle='modal' data-target='#myModal' onclick='openModal(this)' data-action='/settings/sub-sector/add-product/" . Encryption::encodeId($data->id) . "'><i class='fa fa-sitemap'></i> Add product</a> ";
                    }
                    return $btn;
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Inactive</label>";
            })
            ->make(true);
    }

    public function createSubSector($sectorId)
    {
        $decodedSectorId = Encryption::decodeId($sectorId);
        $sectorInfo = Sector::find($decodedSectorId);
        $division_list = SectorDivisions::where('status', 1)
            ->where('is_archive', 0)->lists('name', 'id');
        return view('Settings::sector.sub-sector-create', compact('sectorInfo', 'division_list'));
    }

    public function editSubSector($subSectorId)
    {
        $decodedId = Encryption::decodeId($subSectorId);
        $subSectorInfo = SubSector::find($decodedId);
        $division_list = SectorDivisions::where('status', 1)
            ->where('is_archive', 0)->lists('name', 'id');
        return view('Settings::sector.sub-sector-edit', compact('subSectorInfo', 'division_list'));
    }


    public function addEditProduct($subSectorId = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }
        $decodedsubSectorId = Encryption::decodeId($subSectorId);
        $product = SectorProducts::where('sub_sector_id', $decodedsubSectorId)->get();
        return view("Settings::sector.product-create-edit", compact('product', 'subSectorId'));
    }

    public function productStore(Request $request, $subSectorId = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }


        $data = $request->all();
        $decodedSubSectorId = Encryption::decodeId($subSectorId);

        try {
            DB::beginTransaction();
            $productIds = [];
            foreach ($data['name'] as $key => $productName) {
                if (empty($data['product_id'][$key])) {
                    $product = new SectorProducts();
                } else {
                    $product_id = $data['product_id'][$key];
                    $product = SectorProducts::where('id', $product_id)->first();
                }
                $product->sub_sector_id = $decodedSubSectorId;
                $product->name = $data['name'][$key];
                $product->isic_code = $data['isic_code'][$key];
                $product->status = $data['status'][$key];
                $product->save();
                $productIds[] = $product->id;
            }

            if (!empty($productIds)) {
                SectorProducts::where('sub_sector_id', $decodedSubSectorId)->whereNotIn('id', $productIds)->delete();
            }

            DB::commit();

            $sector_id = SubSector::where('id', $decodedSubSectorId)->first(['sector_id']);
            $sector_id = Encryption::encodeId($sector_id->sector_id);

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => '/settings/sector/open/' . $sector_id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }

    //--------------sector--------------

    /******************* Payment Configuration *******************/
    public function paymentConfiguration()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1033]');
        }

        return view("Settings::payment_configuration.list");
    }

    public function paymentConfigurationCreate()
    {
        
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1033]');
        }

        $processTypes = ProcessType::where('status', 1)->orderBy('name', 'asc')->lists('name', 'id');
        $paymentCategories = PaymentCategory::where('status', 1)->lists('name', 'id');
        return view('Settings::payment_configuration.create', compact('processTypes', 'paymentCategories'));
    }

    public function paymentConfigurationStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1031]');
            return Redirect::back()->withInput();
        }

        $this->validate($request, [
            'process_type_id' => 'required',
            'payment_category_id' => 'required',
            'amount' => 'required|numeric|min:1',
            //'vat_on_transaction_charge_percent' => 'numeric',
            //'trans_charge_percent' => 'numeric',
            //'trans_charge_min_amount' => 'numeric',
            //'trans_charge_max_amount' => 'numeric',
            'status' => 'required'
        ]);
        try {
            // check duplicate value with same process type and same payment category
            $existInfo = PaymentConfiguration::where([
                'process_type_id' => trim($request->get('process_type_id')),
                'payment_category_id' => trim($request->get('payment_category_id'))
            ])->where('is_archive', 0)->count();

            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate value with same process type and payment category!');
                return \redirect()->back()->withInput();
            }

            PaymentConfiguration::create(
                array(
                    'process_type_id' => $request->get('process_type_id'),
                    'payment_category_id' => $request->get('payment_category_id'),
                    'amount' => $request->get('amount'),
                    //'vat_on_transaction_charge_percent' => $request->get('vat_on_transaction_charge_percent'),
                    //'trans_charge_percent' => $request->get('trans_charge_percent'),
                    //'trans_charge_min_amount' => $request->get('trans_charge_min_amount'),
                    //'trans_charge_max_amount' => $request->get('trans_charge_max_amount'),
                    //'status' => $request->get('status'),
                    'trans_charge_percent' => 0, // default 0
                    'trans_charge_min_amount' => 0, // default 0
                    'trans_charge_max_amount' => 0, // default 0
                    'status' => 0, // default 0 because need to configuration payment distribution
                    'created_by' => CommonFunction::getUserId()
                ));


            // array(
            //     'process_type_id' => $request->get('process_type_id'),
            //     'payment_category_id' => $request->get('payment_category_id'),
            //     'amount' => $request->get('amount'),
            //     //'vat_on_transaction_charge_percent' => $request->get('vat_on_transaction_charge_percent'),
            //     //'trans_charge_percent' => $request->get('trans_charge_percent'),
            //     //'trans_charge_min_amount' => $request->get('trans_charge_min_amount'),
            //     //'trans_charge_max_amount' => $request->get('trans_charge_max_amount'),
            //     //'status' => $request->get('status'),
            //     // 'vat_on_transaction_charge_percent' => 0, // default 0
            //     'trans_charge_percent' => 0, // default 0
            //     'trans_charge_min_amount' => 0, // default 0
            //     'trans_charge_max_amount' => 0, // default 0
            //     'status' => 0, // default 0 because need to configuration payment distribution
            //     'created_by' => CommonFunction::getUserId()
            // );

            Session::flash('success', 'Data is stored successfully!');
            return \redirect('settings/payment-configuration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. [SC-2545]');
            return Redirect::back()->withInput();
        }
    }

    public function getPaymentConfiguration()
    {
        DB::statement(DB::raw('set @rownum=0'));
        $dtas = PaymentConfiguration::leftJoin('sp_payment_category', 'sp_payment_category.id', '=',
            'sp_payment_configuration.payment_category_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'sp_payment_configuration.process_type_id')
            ->where('sp_payment_configuration.is_archive', 0)
            ->orderBy('sp_payment_configuration.id', 'desc')->get([
                'sp_payment_configuration.id',
                'process_type.name as process_type_name',
                'sp_payment_category.name as payment_cat_name',
                'sp_payment_configuration.amount',
                //'sp_payment_configuration.vat_on_transaction_charge_percent',
                //'sp_payment_configuration.trans_charge_percent',
                //'sp_payment_configuration.trans_charge_max_amount',
                //'sp_payment_configuration.trans_charge_min_amount',
                'sp_payment_configuration.status',
                DB::raw('@rownum  := @rownum  + 1 AS sl_no')
            ]);

        return Datatables::of($dtas)
            ->addColumn('action', function ($datas) {
                return '<a href="/settings/edit-payment-configuration/' . Encryption::encodeId($datas->id) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';

            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editPaymentConfiguration($encrypted_id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1038]');
        }

        $id = Encryption::decodeId($encrypted_id);
        $processTypes = ProcessType::where('status', 1)->lists('name', 'id');
        $paymentCategories = PaymentCategory::where('status', 1)->lists('name', 'id');
        $data = PaymentConfiguration::where('id', $id)->first();
        return view("Settings::payment_configuration.edit",
            compact('data', 'encrypted_id', 'processTypes', 'paymentCategories'));
    }

    public function updatePaymentConfiguration(Request $request, $id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1031]');
            return Redirect::back()->withInput();
        }

        try {
            $id = Encryption::decodeId($id);

            // check duplicate value with same process type and same payment category but not current item
            $existInfo = PaymentConfiguration::where([
                'process_type_id' => trim($request->get('process_type_id')),
                'payment_category_id' => trim($request->get('payment_category_id'))
            ])->where('id', '!=', $id)
                ->where('is_archive', 0)
                ->count();
            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate value with same process type and payment category!');
                return \redirect()->back()->withInput();
            }

            //Stakeholder amount distribution check
            $stakeholder_distribution = PaymentDistribution::where('sp_pay_config_id', $id)
                ->where('status', 1)
                ->where('is_archive', 0)
                ->select(DB::raw('sum(pay_amount) as sum, process_type_id'))
                ->get();

            $stakeholder_amount = (!empty($stakeholder_distribution)) ? $stakeholder_distribution[0]->sum : 0;

            if (!in_array($stakeholder_distribution[0]->process_type_id, [2, 3, 6, 7])) { // WPN, WPE
                if (($request->get('status') == 1) && ($stakeholder_amount < $request->get('amount'))) {
                    Session::flash('error', 'Please distribute stakeholders properly.');
                    return \redirect()->back();
                }
            }

            //Stakeholder amount distribution check end

            $this->validate($request, [
                'process_type_id' => 'required',
                'payment_category_id' => 'required',
                'amount' => 'required|numeric|min:0',
//                'vat_on_transaction_charge_percent' => 'numeric',
//                'trans_charge_percent' => 'numeric',
//                'trans_charge_min_amount' => 'numeric',
//                'trans_charge_max_amount' => 'numeric',
                'status' => 'required'
            ]);

            PaymentConfiguration::where('id', $id)->update([
                'process_type_id' => $request->get('process_type_id'),
                'payment_category_id' => $request->get('payment_category_id'),
                'amount' => $request->get('amount'),
//                'vat_on_transaction_charge_percent' => $request->get('vat_on_transaction_charge_percent'),
//                'trans_charge_percent' => $request->get('trans_charge_percent'),
//                'trans_charge_min_amount' => $request->get('trans_charge_min_amount'),
//                'trans_charge_max_amount' => $request->get('trans_charge_max_amount'),
                'status' => $request->get('status'),
                'updated_by' => CommonFunction::getUserId()
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/payment-configuration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. [SC-2546]');
            return Redirect::back()->withInput();
        }
    }


    /******************* Stakeholder Payment Configuration *******************/
    public function stakeholderPaymentConfiguration()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1035]');
        }

        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::stakeholder_payment_configuration.list");
    }

    public function stakeholderPaymentConfigurationCreate()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1036]');
        }

        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $processTypes = ProcessType::where('status', 1)->where('bida_service_status', 2)->orderBy('name', 'asc')->lists('name', 'id');
        $paymentCategories = StakeholderPaymentCategory::where('status', 1)->orderBy('name', 'asc')->lists('name', 'id');
        $stakeholders = ApiStakeholder::where('is_active', 1)->orderBy('name', 'asc')->lists('name', 'id');
        return view('Settings::stakeholder_payment_configuration.create', compact('processTypes', 'paymentCategories', 'stakeholders'));
    }

    public function stakeholderPaymentConfigurationStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1031]');
            return Redirect::back()->withInput();
        }

        $this->validate($request, [
            'stakeholder_id' => 'required',
            'process_type_id' => 'required',
            'payment_category_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'status' => 'required'
        ]);
        try {
            // check duplicate value with same process type and same payment category
            $existInfo = StackholderPaymentConfiguration::where([
                'process_type_id' => trim($request->get('process_type_id')),
                'payment_category_id' => trim($request->get('payment_category_id'))
            ])->where('is_archive', 0)->count();
            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate value with same process type and payment category!');
                return \redirect()->back()->withInput();
            }

            StackholderPaymentConfiguration::create([
                'process_type_id' => $request->get('process_type_id'),
                'payment_category_id' => $request->get('payment_category_id'),
                'stackholder_id' => $request->get('stakeholder_id'),
                'amount' => $request->get('amount'),
                'status' => $request->get('status'),
                'updated_by' => CommonFunction::getUserId()
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return \redirect('settings/stakeholder-payment-configuration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. [SC-2545]');
            return Redirect::back()->withInput();
        }
    }

    public function getStakeholderPaymentConfiguration()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            return response()->json([
                'data' => [],
                'error' => 'You have no access right! Please contact system administration for more information. [AR-1031]'
            ]);
        }

        DB::statement(DB::raw('set @rownum=0'));
        $dtas = StackholderPaymentConfiguration::leftJoin('api_stackholder_sp_payment_category', 'api_stackholder_sp_payment_category.id', '=',
            'api_stackholder_payment_configuration.payment_category_id')
            ->leftJoin('process_type', 'process_type.id', '=', 'api_stackholder_payment_configuration.process_type_id')
            ->leftJoin('api_stackholder', 'api_stackholder.id', '=', 'api_stackholder_payment_configuration.stackholder_id')
            ->where('api_stackholder_payment_configuration.is_archive', 0)
            ->orderBy('api_stackholder_payment_configuration.id', 'desc')->get([
                'api_stackholder_payment_configuration.id',
                'api_stackholder_payment_configuration.stackholder_id',
                'api_stackholder.name as stackholder_name',
                'process_type.name as process_type_name',
                'api_stackholder_sp_payment_category.name as payment_cat_name',
                'api_stackholder_payment_configuration.amount',
                'api_stackholder_payment_configuration.status',
                DB::raw('@rownum  := @rownum  + 1 AS sl_no')
            ]);
        return Datatables::of($dtas)
            ->addColumn('action', function ($datas) {
                return '<a href="/settings/edit-stakeholder-payment-configuration/' . Encryption::encodeId($datas->id) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';

            })
            ->editColumn('status', function ($datas) {
                if ($datas->status == 1) {
                    $class = 'text-success';
                    $status = 'Active';
                } else {
                    $class = 'text-danger';
                    $status = 'Inactive';
                }
                return '<span class="' . $class . '"><b>' . $status . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editStakeholderPaymentConfiguration($encrypted_id)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1039]');
        }

        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $id = Encryption::decodeId($encrypted_id);
        $paymentCategories = StakeholderPaymentCategory::where('status', 1)->lists('name', 'id');
        $stakeholders = ApiStakeholder::where('is_active', 1)->lists('name', 'id');
        $data = StackholderPaymentConfiguration::LeftJoin('process_type', 'process_type.id', '=', 'api_stackholder_payment_configuration.process_type_id')
            ->where('api_stackholder_payment_configuration.id', $id)
            ->first([
                'api_stackholder_payment_configuration.*',
                'process_type.name'
            ]);
        return view("Settings::stakeholder_payment_configuration.edit",
            compact('data', 'encrypted_id', 'processTypes', 'paymentCategories', 'stakeholders'));
    }

    public function updateStakeholderPaymentConfiguration(Request $request, $id)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1031]');
            return Redirect::back()->withInput();
        }

        try {
            $id = Encryption::decodeId($id);
            $process_type_id = Encryption::decodeId($request->process_type_id);
            // check duplicate value with same process type and same payment category but not current item
            $existInfo = StackholderPaymentConfiguration::where([
                'process_type_id' => $process_type_id,
                'payment_category_id' => $request->payment_category_id
            ])->where('id', '!=', $id)
                ->where('is_archive', 0)
                ->count();
            if ($existInfo > 0) {
                Session::flash('error', 'Duplicate value with same process type and payment category!');
                return \redirect()->back()->withInput();
            }

            $this->validate($request, [
                'stakeholder_id' => 'required',
                'process_type_id' => 'required',
                'payment_category_id' => 'required',
                'amount' => 'required|numeric|min:0',
                'status' => 'required'
            ]);
//
            StackholderPaymentConfiguration::where('id', $id)->update([
                'process_type_id' => $process_type_id,
                'payment_category_id' => $request->get('payment_category_id'),
                'amount' => $request->get('amount'),
                'status' => $request->get('status'),
                'updated_by' => CommonFunction::getUserId()
            ]);
//
            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/stakeholder-payment-configuration');
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. [SC-2546]');
            return Redirect::back()->withInput();
        }

    }


    public function apiStakeholderDistribution($payConfigID)
    {
        $decodedConfigId = Encryption::decodeId($payConfigID);
        $paymentConfig = StackholderPaymentConfiguration::find($decodedConfigId);
        $distribution_types = PaymentDistributionType::where('status', 1)
            ->where('is_archive', 0)
            ->lists('name', 'id');

        return view('Settings::stakeholder_payment_configuration.stakeholder-distribution', compact('paymentConfig', 'distribution_types'));
    }

    public function getStakeholderPaymentDistributionData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $decodedProcessTypeId = Encryption::decodeId($request->get('process_type_id'));

        DB::statement(DB::raw('set @rownum=0'));

        $data = ApiStackholderMapping::where('api_stackholder_mapping.process_type_id', $decodedProcessTypeId)
            ->orderBy('api_stackholder_mapping.created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'),
                'api_stackholder_mapping.id',
                'api_stackholder_mapping.receiver_account_no',
                'api_stackholder_mapping.amount',
                'api_stackholder_mapping.category',
                'api_stackholder_mapping.is_active',
            ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    $btn = "<a class='subSectorEditBtn btn btn-xs btn-info' data-toggle='modal' data-target='#myModal' onclick='openModal(this)' data-action='/settings/api-stakeholder-distribution-edit/" . Encryption::encodeId($data->id) . "'><i class='fa fa-edit'></i> Edit</a> ";
                    return $btn;
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->is_active == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Inactive</label>";
                }
            })
            ->make(true);
    }

    public function apiStakeholderDistributionStore(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }
        $process_type_id = Encryption::decodeId($request->process_type_id);
        $stackholder_id = Encryption::decodeId($request->stackholder_id);
//        // check duplicate value with same process type and same payment category but not current item
//        $existInfo = ApiStackholderMapping::where([
//            'process_type_id' => $process_type_id,
//            'category' => $request->category,
//            'stackholder_id' => $stackholder_id,
//        ])->where('is_active', 1)
//            ->count();
//        if ($existInfo > 0) {
//            return response()->json([
//                'error' => true,
//                'status' => 'Sorry! Duplicate mapping is not allowed.',
//            ]);
//        }

        $rules = [
            'receiver_account_no' => 'required',
            'pay_amount' => 'required',
            'category' => 'required',
            'status' => 'required',
        ];

        $messages = [];
        $validation = Validator::make(Input::all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }
        try {
            $id = $request->get('data_id');
            $decodedId = '';
            if ($id != '') {
                $decodedId = Encryption::decodeId($id);
            }
            $mapping = ApiStackholderMapping::findOrNew($decodedId);
            $mapping->stackholder_id = $stackholder_id;
            $mapping->process_type_id = $process_type_id;
            $mapping->receiver_account_no = $request->get('receiver_account_no');
            $mapping->amount = $request->get('pay_amount');
            $mapping->category = $request->get('category');
            $mapping->is_active = $request->get('status');
            $mapping->save();

            $config_pay = StackholderPaymentConfiguration::where('process_type_id', $mapping->process_type_id)
                ->where('stackholder_id', $mapping->stackholder_id)
                ->first([
                    'id',
                    'status'
                ]);

            if ($mapping->status == 0) {
                StackholderPaymentConfiguration::where('id', $config_pay->id)
                    ->update(['status' => 0]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'status' => 'Data has been saved successfully & Payment config got inactive.',
                    'link' => '/settings/edit-stakeholder-payment-configuration/' . Encryption::encodeId($config_pay->id)
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully.',
                'link' => '/settings/api-edit-stakeholder-payment-configuration/' . Encryption::encodeId($config_pay->id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }

    public function apiEditStakeholderDistribution($id)
    {
        $decodedId = Encryption::decodeId($id);
        $data = ApiStackholderMapping::where('id', $decodedId)->first();
        return view('Settings::stakeholder_payment_configuration.stakeholder-distribution-edit', compact('data'));
    }


    public function airportCreate()
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::airport.create");
    }

    public function airportList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::airport.list");
    }

    /**
     * @return mixed
     */
    public function getAirportList()
    {
        $airport = Airports::orderBy('id', 'desc')->get();
        $mode = ACL::getAccsessRight('settings', 'E');

        return Datatables::of($airport)
            ->addColumn('action', function ($airport) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-airport/' . Encryption::encodeId($airport->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('status', function ($airport) {
                if ($airport->status == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Inactive</label>";
            })
            //->removeColumn('area_id')
            ->make(true);
    }

    public function airportStore(Request $request, $airportId = '')
    {

        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'email' => 'required | email',
            'city_name' => 'required',
            'country_name' => 'required',
        ]);

        if (!empty($airportId)) {
            $airportId = Encryption::decodeId($airportId);
        }

        try {
            $airport = Airports::findOrNew($airportId);
            $airport->code = $request->code;
            $airport->name = $request->name;
            $airport->email = $request->email;
            $airport->city_name = $request->city_name;
            $airport->country_name = $request->country_name;
            if (isset($request->status)) {
                $airport->status = $request->status;
            }
            $airport->save();

            Session::flash('success', 'Airport Data Saved successfully.');
            return redirect('/settings/airport/list');

        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function airportEdit($id)
    {

        $decodedId = Encryption::decodeId($id);
        $airportInfo = Airports::find($decodedId);

        return view('Settings::airport.edit', compact('airportInfo'));
    }


    /* End of dashboard Object related functions */

    /*pdf_queue and pdf_print_request_queue table interface related function */
    public function pdfPrintRequest()
    {
        return view('Settings::pdfPrintRequest.list');
    }

    public function getPdfPrintRequest()
    {
        $mode = ACL::getAccsessRight('settings', 'PPR-ESQ');

        $getList = PdfPrintRequestQueue::leftJoin('process_list', function ($join) {
            $join->on('pdf_print_requests_queue.app_id', '=', 'process_list.ref_id');
            $join->on('pdf_print_requests_queue.process_type_id', '=', 'process_list.process_type_id');
        })
            ->where('pdf_print_requests_queue.job_sending_status', -9)
            ->orWhere('pdf_print_requests_queue.job_receiving_status', -9)
            ->orWhere('pdf_print_requests_queue.prepared_json', -1)
            ->orWhere('pdf_print_requests_queue.prepared_json', -9)
            ->orderByRaw("job_sending_status = -9 DESC, job_receiving_status= -9 DESC, id DESC")
            ->get([
                'pdf_print_requests_queue.id',
                'pdf_print_requests_queue.job_sending_status',
                'pdf_print_requests_queue.no_of_try_job_sending',
                'pdf_print_requests_queue.job_receiving_status',
                'pdf_print_requests_queue.no_of_try_job_receving',
                'pdf_print_requests_queue.prepared_json',
                'pdf_print_requests_queue.certificate_name',
                'pdf_print_requests_queue.certificate_link',
                'process_list.tracking_no',
            ]);

        return Datatables::of($getList)
            ->addColumn('action', function ($getList) use ($mode) {
                if ($mode) {
                    $btn = '<a href="' . url('settings/resend-pdf-print-requests/' . Encryption::encodeId($getList->id)) .
                        '" class="btn btn-xs btn-danger"><i class="fas fa-envelope-square"></i> Resend</a> ';
                    $btn .= ' <a href="' . url('settings/edit-pdf-print-requests/' . Encryption::encodeId($getList->id)) .
                        '" class="btn btn-xs btn-success"><i class="fas fa-edit"></i> Edit</a>';
                    $btn .= ' <a href="' . url('settings/pdf-print-request-verify/' . Encryption::encodeId($getList->id) . '/' . Encryption::encode($getList->certificate_name)) .
                        '" class="btn btn-xs btn-primary"><i class="fas fa-check"></i> Verify</a>';
                    return $btn;
                } else {
                    return '';
                }
            })
            ->addColumn('certificate_link', function ($getList) {
                if ($getList->certificate_link != "") {
                    return '<a href="' . url($getList->certificate_link) .
                        '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-file-pdf  fa-fw"></i> Link</a> ';
                }

                return '';
            })
            ->make(true);
    }

    public function pdfPrintRequestSearchList(Request $request)
    {
        $mode = ACL::getAccsessRight('settings', 'PPR-ESQ');

        $getList = PdfPrintRequestQueue::leftJoin('process_list', function ($join) {
            $join->on('pdf_print_requests_queue.app_id', '=', 'process_list.ref_id');
            $join->on('pdf_print_requests_queue.process_type_id', '=', 'process_list.process_type_id');
        })
            ->where('process_list.tracking_no', $request->search_text)
            //   ->orderByRaw("job_sending_status = -9 DESC, job_receiving_status= -9 DESC, id DESC")
            ->get([
                'pdf_print_requests_queue.id',
                'pdf_print_requests_queue.job_sending_status',
                'pdf_print_requests_queue.no_of_try_job_sending',
                'pdf_print_requests_queue.job_receiving_status',
                'pdf_print_requests_queue.no_of_try_job_receving',
                'pdf_print_requests_queue.prepared_json',
                'pdf_print_requests_queue.certificate_name',
                'pdf_print_requests_queue.certificate_link',
                'process_list.tracking_no',
            ]);
        return Datatables::of($getList)
            ->addColumn('action', function ($getList) use ($mode) {
                if ($mode) {
                    $btn = '<a href="' . url('settings/resend-pdf-print-requests/' . Encryption::encodeId($getList->id)) .
                        '" class="btn btn-xs btn-danger"><i class="fas fa-envelope-square"></i> Resend</a> ';
                    $btn .= ' <a href="' . url('settings/edit-pdf-print-requests/' . Encryption::encodeId($getList->id)) .
                        '" class="btn btn-xs btn-success"><i class="fas fa-edit"></i> Edit</a>';
                    $btn .= ' <a href="' . url('settings/pdf-print-request-verify/' . Encryption::encodeId($getList->id) . '/' . Encryption::encode($getList->certificate_name)) .
                        '" class="btn btn-xs btn-primary"><i class="fas fa-check"></i> Verify</a>';
                    return $btn;
                } else {
                    return '';
                }
            })
            ->addColumn('certificate_link', function ($getList) {
                if ($getList->certificate_link != "") {
                    return '<a href="' . url($getList->certificate_link) .
                        '" class="btn btn-xs btn-info" target="_blank"><i class="fa fa-file-pdf  fa-fw"></i> Link</a> ';
                }

                return '';
            })
            ->make(true);
    }

    public function resendPdfPrintRequest($id)
    {
        try {
            $id = Encryption::decodeId($id);
            $resend = PdfPrintRequestQueue::find($id);
            $resend->job_sending_status = 0;
            $resend->no_of_try_job_sending = 0;
            $resend->job_receiving_status = 0;
            $resend->no_of_try_job_receving = 0;
            $resend->save();

            Session::flash('success', 'The pdf request will be resent successfully!');
            return Redirect::back();
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SCP-5101]");
            return \redirect()->back();
        }
    }

    public function editPdfPrintRequest($id)
    {
        $id = Encryption::decodeId($id);
        $pdf_print_request = PdfPrintRequestQueue::leftJoin('process_list', 'pdf_print_requests_queue.app_id', '=',
            'process_list.ref_id')
            ->whereRaw("`process_list`.`ref_id` = `pdf_print_requests_queue`.`app_id` and `process_list`.`process_type_id` = `pdf_print_requests_queue`.`process_type_id`")
            ->where('pdf_print_requests_queue.id', $id)
            ->first([
                'pdf_print_requests_queue.id',
                'pdf_print_requests_queue.job_sending_response',
                'pdf_print_requests_queue.job_receiving_response',
                'pdf_print_requests_queue.job_sending_status',
                'pdf_print_requests_queue.no_of_try_job_sending',
                'pdf_print_requests_queue.job_receiving_status',
                'pdf_print_requests_queue.no_of_try_job_receving',
                'pdf_print_requests_queue.prepared_json',
                'process_list.tracking_no',
            ]);

        return view('Settings::pdfPrintRequest.edit', compact('pdf_print_request'));
    }


    public function updatePdfPrintRequest(Request $request)
    {
        try {
            $id = Encryption::decodeId($request->id);
            $pdf_request = PdfPrintRequestQueue::findOrFail($id);
//            $pdf_request->reg_key = $request->reg_key;
//            $pdf_request->url_requests = $request->url_requests;
//            $pdf_request->pdf_type = $request->pdf_type;
//            $pdf_request->table_name = $request->table_name;
//            $pdf_request->certificate_name = $request->certificate_name;
//            $pdf_request->pdf_server_url = $request->pdf_server_url;
            $pdf_request->job_sending_status = $request->get('job_sending_status');
            $pdf_request->no_of_try_job_sending = $request->get('no_of_try_job_sending');
            $pdf_request->job_receiving_status = $request->get('job_receiving_status');
            $pdf_request->no_of_try_job_receving = $request->get('no_of_try_job_receving');
            $pdf_request->prepared_json = $request->get('prepared_json');
            $pdf_request->save();

            Session::flash('success', 'Data is updated successfully!');
            return Redirect::back();
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SCP-5102]");
            return \redirect()->back();
        }
    }


//    public function pdfPrintRequestUpdate(Request $request)
//    {
//        $pdf_id = Encryption::decodeId($request->pdf_id);
//        $pdfRequests = PdfPrintRequestQueue::find($pdf_id);
//        $pdfRequests->certificate_name = $request->certificate_name;
//        $pdfRequests->reg_key = $request->reg_key;
//        $pdfRequests->table_name = $request->table_name;
//        $pdfRequests->url_requests = $request->url_requests;
//        $pdfRequests->pdf_server_url = $request->pdf_server_url;
//        $pdfRequests->job_sending_status = $request->job_sending_status;
//        $pdfRequests->no_of_try_job_sending = $request->no_try_job_sending;
//        $pdfRequests->job_receiving_status = $request->job_receiving_status;
//        $pdfRequests->no_of_try_job_receving = $request->no_try_job_receving;
//        $pdfRequests->prepared_json = $request->prepared_json;
//        $pdfRequests->update();
//        return "Information is updated successfully";
//    }

    public function pdfQueue()
    {
        // $getList = \App\Modules\Settings\Models\PdfPrintRequestQueue::all();
        $getList = DB::table('process_list')
            ->join('pdf_queue', 'process_list.ref_id', '=', 'pdf_queue.app_id')
            ->select('pdf_queue.id',
                'pdf_queue.status',
                'pdf_queue.pdf_type',
                'pdf_queue.secret_key',
                'process_list.tracking_no')
            ->orderBy('pdf_queue.updated_at', 'desc')
            // ->limit('50')
            ->get();
        return view('Settings::pdfQueue.list', compact('getList'));
    }

    public function pdfQueueUpdate(Request $request)
    {
        $pdf_id = Encryption::decodeId($request->pdf_id);
        $pdfRequests = PdfQueue::find($pdf_id);
        $pdfRequests->status = $request->status;
        $pdfRequests->pdf_type = $request->pdf_type;
        $pdfRequests->secret_key = $request->secret_key;
        $pdfRequests->update();
        return "Information is updated successfully";
    }

    public function verifyPdfPrintRequest($pdf_id, $certificate_name)
    {
        $pdfId = Encryption::decodeId($pdf_id);
        $certificateName = Encryption::decode($certificate_name);

        $pdfRequests = DB::table('pdf_print_requests_queue')
            ->join('pdf_service_info', 'pdf_service_info.pdf_type', '=', 'pdf_print_requests_queue.pdf_type')
            ->where('pdf_service_info.certificate_name', $certificateName)
            ->where('pdf_print_requests_queue.id', $pdfId)
            ->get(['pdf_service_info.sql', 'pdf_print_requests_queue.app_id']);

        $app_id = $pdfRequests[0]->app_id;
        $sql = $pdfRequests[0]->sql;
        $requested_sql = str_replace("{app_id}", "$app_id", $sql);
        $result = DB::select(DB::raw("$requested_sql"));

        return view('Settings::pdfPrintRequest.sql_result', compact('result'));
    }


    /* Start of Payment Stakeholder functions */

    public function paymentStakeholder()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        return view("Settings::payment_stakeholder.list");
    }

    public function getPaymentStakeholderData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $paymentStakeholders = PaymentStakeholder::select([
            'sp_payment_stakeholder.id',
            'sp_payment_stakeholder.name',
            'sp_payment_stakeholder.account_name',
            'sp_payment_stakeholder.account_no',
            'sp_payment_stakeholder.mobile_no',
            'sp_payment_stakeholder.email',
            'sp_payment_stakeholder.status',
        ])
            ->where('sp_payment_stakeholder.is_archive', 0)
            ->orderBy('sp_payment_stakeholder.id', 'desc')
            ->get();

        return Datatables::of($paymentStakeholders)
            ->addColumn('action', function ($paymentStakeholders) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('settings/edit-payment-stakeholder/' . Encryption::encodeId($paymentStakeholders->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($paymentStakeholders) use ($mode) {
                if ($paymentStakeholders->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }

    public function createPaymentStakeholder()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        return view("Settings::payment_stakeholder.create");
    }

    public function storePaymentStakeholder(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        $this->validate($request, [
            'name' => 'required',
            'account_name' => 'required',
            'account_no' => 'required',
        ]);

        try {
            PaymentStakeholder::create([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'account_name' => $request->get('account_name'),
                'account_no' => $request->get('account_no'),
                'email' => $request->get('email'),
                'mobile_no' => $request->get('mobile_no')
            ]);

            Session::flash('success', 'Data is stored successfully!');
            return redirect('/settings/payment-stakeholder');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function editPaymentStakeholder($encrypted_id)
    {
        try {
            $id = Encryption::decodeId($encrypted_id);
            $data = PaymentStakeholder::where('id', $id)->first();

            return view("Settings::payment_stakeholder.edit", compact('data', 'encrypted_id'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    public function updatePaymentStakeholder($enc_id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'E')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        try {
            $id = Encryption::decodeId($enc_id);

            $this->validate($request, [
                'name' => 'required',
                'account_name' => 'required',
                'account_no' => 'required',
            ]);

            PaymentStakeholder::where('id', $id)->update([
                'name' => $request->get('name'),
                'description' => $request->get('description'),
                'account_name' => $request->get('account_name'),
                'account_no' => $request->get('account_no'),
                'email' => $request->get('email'),
                'mobile_no' => $request->get('mobile_no'),
                'status' => $request->get('status')
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/payment-stakeholder');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[SC-6001]");
            return \redirect()->back();
        }
    }

    /* End of Stakeholder functions */

    public function getPaymentDistributionData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $decodedPayConfigId = Encryption::decodeId($request->get('pay_config_id'));

        DB::statement(DB::raw('set @rownum=0'));

        $data = PaymentDistribution::leftJoin('sp_payment_distribution_type', 'sp_payment_distribution_type.id', '=', 'sp_payment_distribution.distribution_type')
            ->where('sp_payment_distribution.is_archive', 0)
            ->where('sp_payment_distribution.sp_pay_config_id', $decodedPayConfigId)
            ->orderBy('sp_payment_distribution.created_at', 'desc')
            ->get([
                DB::raw('@rownum := @rownum+1 AS sl'),
                'sp_payment_distribution.id',
                'sp_payment_distribution.stakeholder_ac_name',
                'sp_payment_distribution.stakeholder_ac_no as account_no',
                'sp_payment_distribution.purpose',
                'sp_payment_distribution.purpose_sbl',
                'sp_payment_distribution.pay_amount as amount',
                'sp_payment_distribution.fix_status',
                'sp_payment_distribution.status',
                'sp_payment_distribution_type.name as distribution_type_name'
            ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    $btn = "<a class='subSectorEditBtn btn btn-xs btn-info' data-toggle='modal' data-target='#myModal' onclick='openModal(this)' data-action='/settings/stakeholder-distribution-edit/" . Encryption::encodeId($data->id) . "'><i class='fa fa-edit'></i> Edit</a> ";
                    return $btn;
                }
            })
            ->editColumn('fix_status', function ($data) {
                if ($data->fix_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Fixed</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Unfixed</label>";
                }
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    return "<label class='btn btn-xs btn-success'>Active</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Inactive</label>";
                }
            })
            ->make(true);
    }

    public function stakeholderDistribution($payConfigID)
    {
        $decodedConfigId = Encryption::decodeId($payConfigID);
        $paymentConfig = PaymentConfiguration::find($decodedConfigId);
        $distribution_types = PaymentDistributionType::where('status', 1)
            ->where('is_archive', 0)
            ->lists('name', 'id');

        return view('Settings::payment_configuration.stakeholder-distribution', compact('paymentConfig', 'distribution_types'));
    }

    public function editStakeholderDistribution($distributionId)
    {
        $decodedDistId = Encryption::decodeId($distributionId);
        $stakeholderDistribution = PaymentDistribution::find($decodedDistId);
        $distribution_types = PaymentDistributionType::where('status', 1)
            ->where('is_archive', 0)
            ->lists('name', 'id');

        return view('Settings::payment_configuration.stakeholder-distribution-edit',
            compact('stakeholderDistribution', 'distribution_types'));
    }

    public function stakeholderDistributionStore(Request $request, $distributionId = '')
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information',
            ]);
        }

        $rules = [
            'stakeholder_ac_name' => 'required',
            'stakeholder_ac_no' => 'required',
            'fix_status' => 'required',
            'pay_amount' => 'required_if:fix_status,1',
            'distribution_type' => 'required',
            'status' => 'required',
        ];

        $messages = [
            'pay_amount.required_if' => 'The pay amount field is required when fix status is Fixed.',
            'distribution_type.required' => 'The distribution type is required.'
        ];

        $validation = Validator::make(Input::all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {
            $decodedDistributionId = '';
            if ($distributionId) {
                $decodedDistributionId = Encryption::decodeId($distributionId);
            }

            $decode_pay_config_id = Encryption::decodeId($request->get('pay_config_id'));
            $pay_amount = $request->get('pay_amount');
            if ($request->get('fix_status') == 0) {
                $pay_amount = 0;
            }

            /*
             * One Payment Configuration can not have more than one unfixed distribution
             */
//            if ($request->get('fix_status') == 0) {
//                $pay_amount = 0;
//                $unfixedStakeholders = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
//                    ->where('id', '!=', $decodedDistributionId)
//                    ->where('fix_status', 0)
//                    ->where('status', 1)
//                    ->where('is_archive', 0)
//                    ->count();
//                if ($unfixedStakeholders > 0) {
//                    return response()->json([
//                        'error' => true,
//                        'status' => 'There should be only one unfixed stakeholder under one payment configuration.',
//                    ]);
//                }
//            }

            /*
             * One Payment Configuration can not have duplicate distribution type
             */
            $count_distribution_data = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
                ->where('id', '!=', $decodedDistributionId)
                ->where('distribution_type', $request->get('distribution_type'))
                ->where('status', 1)
                ->where('is_archive', 0)
                ->count();
            if ($count_distribution_data > 0) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry! Duplicate distribution type is not allowed.',
                ]);
            }

            $pay_config = PaymentConfiguration::find($decode_pay_config_id);

            /*
             * A total amount of all distributors of a configuration can not exceed
             * the total amount of configuration
             * $stakeholder_previous = total amount of all active distributors without current distributor id
             */
            $stakeholder_previous = PaymentDistribution::where('sp_pay_config_id', $decode_pay_config_id)
                ->where('status', 1)
                ->where('id', '!=', $decodedDistributionId)
                ->where('is_archive', 0)
                ->select(DB::raw('sum(pay_amount) as sum'))
                ->get();

            $stakeholder_previous_amount = (!empty($stakeholder_previous)) ? $stakeholder_previous[0]->sum : 0;
            // $total_stakeholder_amount = $stakeholder_previous + amount of current distributor
            $total_stakeholder_amount = $stakeholder_previous_amount + $pay_amount;
            if ($total_stakeholder_amount > $pay_config->amount) {
                return response()->json([
                    'error' => true,
                    'status' => 'Total stakeholder amount will not be greater then configuration pay amount.',
                ]);
            }

            DB::beginTransaction();

            $ac_no = trim($request->get('stakeholder_ac_no'));

            /*
             * Check duplicate stakeholder by same account no, if exists than rollback
             */
//            $checkExistingAccount = PaymentDistribution::where('stakeholder_ac_no', $ac_no)
//                ->where('sp_pay_config_id', $decode_pay_config_id)
//                ->where('id', '!=', $decodedDistributionId)
//                ->where('is_archive', 0)
//                ->count();
//            if ($checkExistingAccount > 0) {
//                return response()->json([
//                    'error' => true,
//                    'status' => 'Stakeholder already exist with same account number !',
//                ]);
//            }

            $distribution = PaymentDistribution::findOrNew($decodedDistributionId);
            $distribution->process_type_id = $pay_config->process_type_id;
            $distribution->sp_pay_category_id = $pay_config->payment_category_id;
            $distribution->sp_pay_config_id = $decode_pay_config_id;
            $distribution->stakeholder_ac_name = $request->get('stakeholder_ac_name');
            $distribution->stakeholder_ac_no = $ac_no;
            $distribution->purpose = $request->get('purpose');
            $distribution->purpose_sbl = $request->get('purpose_sbl');
            $distribution->pay_amount = $pay_amount;
            $distribution->distribution_type = $request->get('distribution_type');
            $distribution->fix_status = $request->get('fix_status');
            $distribution->status = $request->get('status');
            $distribution->save();

            /* Business Logic
             * 1st condition
             * if (Current distributors status is inactive)
             * and (Total amount of distributors without current distributor) is less than(Total amount of payment configuration) then,
             * inactive the current payment configuration.
             *
             * 2nd condition
             * if (Total amount of all active distributors with current distributor id)
             * is less than (Total amount of payment configuration) then,
             * inactive the current payment configuration.
             */
            //$edited_total = $total_stakeholder_amount - $distribution->pay_amount;
            if (($distribution->status == 0 && $stakeholder_previous_amount < $pay_config->amount) || $total_stakeholder_amount < $pay_config->amount) {
                PaymentConfiguration::where('id', $decode_pay_config_id)->update(['status' => 0]);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'status' => 'Data has been saved successfully & Payment config got inactive.',
                    'link' => '/settings/edit-payment-configuration/' . Encryption::encodeId($decode_pay_config_id)
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully.',
                'link' => '/settings/edit-payment-configuration/' . Encryption::encodeId($decode_pay_config_id)
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage())
            ]);
        }
    }

    public function ipnList()
    {
        return view('Settings::ipn.ipn-list');
    }

    public function getIpnList()
    {
        $ipn = IpnRequest::where('is_archive', 0)->orderBy('id', 'desc')->get();
        return Datatables::of($ipn)
            ->addColumn('action', function ($ipn) {
                return '<a href="' . url('ipn/ipn-history/' . Encryption::encodeId($ipn->id)) .
                    '" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Open</a>';
            })
            ->editColumn('is_authorized_request', function ($ipn) {
                if ($ipn->is_authorized_request == 1) {
                    return "<label class='btn btn-xs btn-success'>Valid</label>";
                } else {
                    return "<label class='btn btn-xs btn-danger'>Wrong</label>";
                }
            })
            ->make(true);
    }

    public function ipnHistory($id)
    {
        $ipn_history = IpnRequestHistory::where('sp_ipn_request_id', Encryption::decodeId($id))->get();

        return view('Settings::ipn.ipn-history-list', compact('ipn_history'));
    }

    public function emailSmsQueueList()
    {
        return view('Settings::email_sms_queue.list');
    }

    public function getEmailSmsQueueList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'PPR-ESQ');

        $from = Carbon::now()->subMonths(12); // last 12 months data
        $to = Carbon::now();

        $data = EmailQueue::query();
        $data->leftJoin('process_list', function ($join) {
            $join->on('email_queue.process_type_id', '=', 'process_list.process_type_id')
                ->on('email_queue.app_id', '=', 'process_list.ref_id');
        })
            ->where(function ($query) {
                $query->where('email_queue.email_status', 0)->orWhere('email_queue.sms_status', 0);
            })
            ->whereBetween('email_queue.created_at', [$from, $to])
            ->where('email_queue.process_type_id', '!=', 0)
            ->orderBy('email_queue.id', 'DESC')
            ->select(
                'email_queue.id',
                'email_queue.caption',
                'email_queue.email_status',
                'email_queue.sms_status',
                'email_queue.sent_on',
                'process_list.tracking_no'
            );

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('email') .
                        '" class="btn btn-xs btn-info btn-sm"><i class="fas fa-at"></i> Resend email</a>&nbsp;&nbsp;<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('sms') .
                        '" class="btn btn-xs btn-primary btn-sm"><i class="fas fa-envelope-square"></i> Resend SMS</a>&nbsp;&nbsp;<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('both') .
                        '" class="btn btn-xs btn-success btn-sm"><i class="fas fa-folder-minus"></i> Resend Both</a>&nbsp;&nbsp;<a href="/settings/email-sms-queue/edit/' . Encryption::encodeId($data->id) .
                        '" class="btn btn-xs btn-warning btn-sm"><i class="far fa-edit"></i> Edit</a>';
                }
            })
            ->editColumn('email_status', function ($data) {
                if ($data->email_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Sent</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Pending</label>";
            })
            ->editColumn('sms_status', function ($data) {
                if ($data->sms_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Sent</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Pending</label>";
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function editEmailSmsQueue($id)
    {
        $decodedId = Encryption::decodeId($id);
        $emailSmsInfo = EmailQueue::leftJoin('process_list', function ($join) {
            $join->on('email_queue.process_type_id', '=', 'process_list.process_type_id')
                ->on('email_queue.app_id', '=', 'process_list.ref_id');
        })
            ->where('email_queue.id', $decodedId)
            ->orderBy('id', 'desc')
            ->first([
                'process_list.tracking_no',
                'email_queue.*',
            ]);

        return view('Settings::email_sms_queue.edit', compact('emailSmsInfo'));
    }

    public function updateEmailSmsQueue($id, Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'PPR-ESQ')) {
            die('You have no access right! Please contact system administration for more information.');
        }

        try {
            $decodedId = Encryption::decodeId($id);

            $this->validate($request, [
                'sms_to' => 'required',
                'sms_content' => 'required',
                'sms_status' => 'required',
                'email_to' => 'required',
                'email_cc' => 'required',
                'email_subject' => 'required',
                'email_content' => 'required',
                'email_status' => 'required',
            ]);

            EmailQueue::where('id', $decodedId)->update([
                'sms_to' => $request->get('sms_to'),
                'sms_content' => $request->get('sms_content'),
                'sms_status' => $request->get('sms_status'),
                'email_to' => $request->get('email_to'),
                'email_cc' => $request->get('email_cc'),
                'email_subject' => $request->get('email_subject'),
                'email_content' => $request->get('email_content'),
                'email_status' => $request->get('email_status'),
                'email_no_of_try' => 0,
                'sms_no_of_try' => 0,
            ]);

            Session::flash('success', 'Data has been changed successfully.');
            return redirect('/settings/email-sms-queue');
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[UESQ-001]");
            return Redirect::back();
        }
    }

    public function resendEmailSmsQueue($id, $type)
    {
        $decodedId = Encryption::decodeId($id);
        $decodedType = Encryption::decodeId($type);

        try {
            $emailSmsInfo = EmailQueue::find($decodedId);

            if (empty($emailSmsInfo)) {
                Session::flash('error', 'Information is not found![REQ-001]');
                return Redirect::back();
            }

            if ($decodedType == 'email') {
                $emailSmsInfo->email_status = 0;
                $emailSmsInfo->email_no_of_try = 0;
            } elseif ($decodedType == 'sms') {
                $emailSmsInfo->sms_status = 0;
                $emailSmsInfo->sms_no_of_try = 0;
            } elseif ($decodedType == 'both') {
                $emailSmsInfo->email_status = 0;
                $emailSmsInfo->sms_status = 0;
                $emailSmsInfo->email_no_of_try = 0;
                $emailSmsInfo->sms_no_of_try = 0;
            } else {
                Session::flash('error', 'Invalid format![REQ-001]');
                return Redirect::back();
            }

            //$emailSmsInfo->no_of_try = 0;
            $emailSmsInfo->save();

            Session::flash('success', 'The ' . $decodedType . ' will be resent successfully!');
            return Redirect::back();
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong.[REQ-002]');
            return Redirect::back();
        }
    }

    public function emailSmsSearchList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'PPR-ESQ');


        $data = EmailQueue::leftJoin('process_list', function ($join) {
            $join->on('email_queue.process_type_id', '=', 'process_list.process_type_id')
                ->on('email_queue.app_id', '=', 'process_list.ref_id');
        })
            ->where('process_list.tracking_no', trim($request->search_text))
            ->where('email_queue.process_type_id', '!=', 0)
            ->get(
                ['email_queue.id',
                    'email_queue.caption',
                    'email_queue.email_status',
                    'email_queue.sms_status',
                    'email_queue.sent_on',
                    'process_list.tracking_no'
                ]
            );

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('email') .
                        '" class="btn btn-xs btn-info btn-sm"><i class="fas fa-at"></i> Resend email</a>&nbsp;&nbsp;<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('sms') .
                        '" class="btn btn-xs btn-primary btn-sm"><i class="fas fa-envelope-square"></i> Resend SMS</a>&nbsp;&nbsp;<a href="/settings/resend-email-sms-queue/' . Encryption::encodeId($data->id) . '/' . Encryption::encodeId('both') .
                        '" class="btn btn-xs btn-success btn-sm"><i class="fas fa-folder-minus"></i> Resend Both</a>&nbsp;&nbsp;<a href="/settings/email-sms-queue/edit/' . Encryption::encodeId($data->id) .
                        '" class="btn btn-xs btn-warning btn-sm"><i class="far fa-edit"></i> Edit</a>';
                }
            })
            ->editColumn('email_status', function ($data) {
                if ($data->email_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Sent</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Pending</label>";
            })
            ->editColumn('sms_status', function ($data) {
                if ($data->sms_status == 1) {
                    return "<label class='btn btn-xs btn-success'>Sent</label>";
                }
                return "<label class='btn btn-xs btn-danger'>Pending</label>";
            })
            ->removeColumn('id')
            ->make(true);
    }

    //Application Rollback

    public function applicationRollbackList()
    {
        if (!in_array(Auth::user()->user_type, ['1x101', '4x404'])) {
            die('You have no access right! Please contact system administration for more information. [[AR-1025]');
        }
        return view('Settings::app_rollback.list');
    }

    public function getApplicationList()
    {
        $list = ApplicationRollback::applicationRollbackList();
        return Datatables::of($list)
            ->editColumn('status_id', function ($list) {
                if ($list->status_id == 1) {
                    return "<span class='label label-primary'>Submit</span>";
                } elseif ($list->status_id == 25) {
                    return "<span class='label label-success'>Approved</span>";
                } elseif ($list->status_id == 6) {
                    return "<span class='label label-danger'>Rejected</span>";
                }
            })
            ->editColumn('last_modified', function ($list) {
                return $list->modified_user . '<br>' . $list->updated_at;
            })
            ->addColumn('action', function ($list) {
                return '<a href="' . URL::to('settings/app-rollback-view/' . Encryption::encodeId($list->id)) .
                    '" class="btn btn-primary btn-xs"><i class="fa fa-folder-open"></i> Open</a> ';

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function applicationSearch()
    {
        if (!in_array(Auth::user()->user_type, ['1x101', '4x404'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1030]');
            return redirect()->back();
        }
        return view('Settings::app_rollback.search');
    }

    public function applicationRollbackOpen(Request $request)
    {
        $user_type = Auth::user()->user_type;

        if (!in_array($user_type, ['1x101', '4x404'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1035]');
            return redirect()->back();
        }

        try {
            $trackingNo = trim($request->get('tracking_no'));

            $appInfo = ProcessList::leftJoin('users', 'process_list.user_id', '=', 'users.id')
                ->leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
                ->leftJoin('company_info', 'process_list.company_id', '=', 'company_info.id')
                ->leftJoin('department', 'process_list.department_id', '=', 'department.id')
                ->leftJoin('sub_department', 'process_list.sub_department_id', '=', 'sub_department.id')
                ->leftJoin('process_status', function ($join) {
                    $join->on('process_list.process_type_id', '=', 'process_status.process_type_id')
                        ->on('process_list.status_id', '=', 'process_status.id');
                })
                ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_list.tracking_no', $trackingNo)
                ->orderBy('id', 'desc')
                ->first([
                    DB::raw("CONCAT(users.user_first_name, ' ', users.user_middle_name, ' ', users.user_last_name) as desk_user_name"),
                    'user_desk.desk_name',
                    'process_status.status_name',
                    'company_info.company_name',
                    'department.name as department_name',
                    'sub_department.name as sub_department_name',
                    'process_type.form_id',
                    'process_type.form_url',
                    'process_list.*',
                ]);

            if (empty($appInfo)) {
                Session::flash('error', 'Sorry! Application not found');
                return redirect()->back()->withInput();
            }

            if ($user_type == '4x404') { // Desk User

                /*
                |--------------------------------------------------------------------------
                | Only active services can be rollback from the desk user
                |--------------------------------------------------------------------------
                | 13	=   IRC Recommendation 1st adhoc
                |
                */
                if (!in_array($appInfo->process_type_id, [13])) {
                    Session::flash('error', 'Sorry! this service is not allowed to rollback.');
                    return redirect()->back()->withInput();
                }

                /*
                |--------------------------------------------------------------------------
                | The application can be rollback until the next desk user takes any action
                |--------------------------------------------------------------------------
                |
                */
                if ($appInfo->updated_by != Auth::user()->id) {
                    Session::flash('error', 'Sorry! you have no right to rollback the application because someone has already taken action.');
                    return redirect()->back()->withInput();
                }

                /*
                |--------------------------------------------------------------------------
                | Some status will not be rollback from the desk user
                |--------------------------------------------------------------------------
                | 25	=   approved
                |
                */
                if (in_array($appInfo->status_id, [25])) {
                    Session::flash('error', 'Sorry! the approved application will not be rollback.');
                    return redirect()->back()->withInput();
                }
            }

            // get application open url
            $openAppRoute = 'process/' . $appInfo->form_url . '/view-app/' . Encryption::encodeId($appInfo->ref_id) . '/' . Encryption::encodeId($appInfo->process_type_id);

            // get corresponding basic information application ID
            $basicAppID = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $appInfo->company_id)
                ->first(['process_list.ref_id', 'process_list.process_type_id', 'process_list.department_id', 'ea_apps.*']);
            if ($basicAppID->applicant_type == 'New Company Registration') {
                $BiRoute = 'basic-information/form-stakeholder/' . Encryption::encodeId('NCR') . '/' . Encryption::encodeId($appInfo->company_id);
            } else if ($basicAppID->applicant_type == 'Existing Company Registration') {
                $BiRoute = 'basic-information/form-stakeholder/' . Encryption::encodeId('ECR') . '/' . Encryption::encodeId($appInfo->company_id);
            } else if ($basicAppID->applicant_type == 'Existing User for BIDA services') {
                $BiRoute = 'basic-information/form-bida/' . Encryption::encodeId('EUBS') . '/' . Encryption::encodeId($appInfo->company_id);
            } else {
                $BiRoute = 'basic-information/form-bida/' . Encryption::encodeId('NUBS') . '/' . Encryption::encodeId($appInfo->company_id);
            }

            $status = ['' => 'Select One'] + ProcessStatus::where('process_type_id', $appInfo->process_type_id)
                    ->whereNotIn('id', [19])->lists('status_name', 'id')->all();

            $desk = ['' => 'Select One', 0 => 'Applicant'] + UserDesk::where(function ($query) {
                        $query->where('id', '<', 6)
                            ->orWhereIn('id', [21]);
                    })
                    ->lists('desk_name', 'id')
                    ->all();

            return view('Settings::app_rollback.open', compact('appInfo', 'openAppRoute', 'status', 'desk', 'BiRoute'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[AR-1075]");
            return \redirect()->back();
        }
    }

    protected function getUserByDesk(Request $request)
    {
        $desk_to = trim($request->get('desk_to'));

        $sql = "SELECT id as user_id, concat(user_first_name,' ', user_middle_name, ' ', user_last_name) as user_full_name from users WHERE is_approved = 1
                AND user_status='active' AND desk_id != 0
                AND desk_id REGEXP '^([0-9]*[,]+)*$desk_to([,]+[,0-9]*)*$'";
        $userList = DB::select(DB::raw($sql));

        $data = ['responseCode' => 1, 'data' => $userList];
        return response()->json($data);
    }

    public function applicationRollbackUpdate(Request $request)
    {
        $user_type = Auth::user()->user_type;

        if (!in_array($user_type, ['1x101', '4x404'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1036]');
            return redirect()->back();
        }

        $rules = [];
        $messages = [];

        $rules['remarks'] = 'required';
        $messages['remarks.required'] = 'Remarks field is required';

        if ($user_type != '4x404') {
            $rules['status_id'] = 'required';
            $rules['desk_id'] = 'required';

            $messages['status_id.required'] = 'Apply status field is required';
            $messages['desk_id.required'] = 'Send to desk field is required';
        }

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

            $decodedId = Encryption::decodeId($request->get('process_list_id'));
            $processData = ProcessList::find($decodedId);

            $presentInfo = ProcessList::leftJoin('user_desk', 'user_desk.id', '=', 'process_list.desk_id')
                ->leftJoin('process_status as ps', function ($join) use ($processData) {
                    $join->on('ps.id', '=', 'process_list.status_id');
                    $join->on('ps.process_type_id', '=', DB::raw($processData->process_type_id));
                })
                ->leftJoin('users', 'users.id', '=', 'process_list.user_id')
                ->leftJoin('board_meting', 'board_meting.id', '=', 'process_list.bm_process_id')
                ->where('process_list.id', $decodedId)
                ->first([
                    'process_list.id',
                    'process_list.status_id',
                    'process_list.desk_id',
                    'process_list.user_id',
                    'ps.status_name',
                    'user_desk.desk_name',
                    'process_list.process_desc',
                    'process_list.resend_deadline',
                    'process_list.bm_process_id',
                    'board_meting.meting_number',
                    DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name,  '', concat(' (',  user_email, ')')) as user_full_name")
                ]);

            if ($user_type == '4x404') { // desk user
                $processHistoryInfo = ProcessHistory::leftJoin('board_meting', 'board_meting.id', '=', 'process_list_hist.bm_process_id')
                    ->where('process_list_hist.process_id', $presentInfo->id)
                    ->orderBy('process_list_hist.id', 'desc')
                    ->skip(1)
                    ->take(1)
                    ->first([
                        'process_list_hist.id',
                        'process_list_hist.status_id',
                        'process_list_hist.user_id',
                        'process_list_hist.desk_id',
                        'process_list_hist.process_desc',
                        'process_list_hist.resend_deadline',
                        'process_list_hist.bm_process_id',
                        'board_meting.meting_number',
                    ]);

                $desk_id = $processHistoryInfo->desk_id;
                $status_id = $processHistoryInfo->status_id;
                $assignUserId = $processHistoryInfo->user_id;
            } else { // system admin
                $desk_id = $request->get('desk_id');
                $status_id = $request->get('status_id');
                $assignUserId = empty($request->get('is_user')) ? 0 : $request->get('is_user');
            }

            $ChangeInfo = ProcessList::leftJoin('user_desk', 'user_desk.id', '=', DB::raw($desk_id))
                ->leftJoin('process_status as ps', function ($join) use ($processData, $status_id) {
                    $join->on('ps.id', '=', DB::raw($status_id));
                    $join->on('ps.process_type_id', '=', DB::raw($processData->process_type_id));
                })
                ->leftJoin('users', 'users.id', '=', DB::raw($assignUserId))
                ->where('process_list.id', $decodedId)
                ->first([
                    'ps.status_name',
                    'user_desk.desk_name',
                    DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name,  '', concat(' (',  user_email, ')')) as user_full_name")
                ]);

            $jsonData[] = [
                'caption' => 'Status',
                'old_id' => $presentInfo->status_id,
                'old_value' => $presentInfo->status_name,
                'new_id' => $status_id,
                'new_value' => $ChangeInfo->status_name
            ];
            $jsonData[] = [
                'caption' => 'Desk',
                'old_id' => $presentInfo->desk_id,
                'old_value' => $presentInfo->desk_name,
                'new_id' => $desk_id,
                'new_value' => $ChangeInfo->desk_name
            ];
            $jsonData[] = [
                'caption' => 'User',
                'old_id' => empty($presentInfo->user_id) ? 0 : $presentInfo->user_id,
                'old_value' => empty($presentInfo->user_id) ? 'N/A' : $presentInfo->user_full_name,
                'new_id' => $assignUserId,
                'new_value' => empty($assignUserId) ? 'N/A' : $ChangeInfo->user_full_name,
            ];

            if ($user_type == '4x404') {
                if (!empty($presentInfo->bm_process_id) || !empty($processHistoryInfo->bm_process_id)) {
                    $jsonData[] = [
                        'caption' => 'Meeting Number',
                        'old_id' => $presentInfo->bm_process_id,
                        'old_value' => $presentInfo->meting_number,
                        'new_id' => $processHistoryInfo->bm_process_id,
                        'new_value' => $processHistoryInfo->meting_number,
                    ];
                }

                if (!empty($presentInfo->resend_deadline) || !empty($processHistoryInfo->resend_deadline)) {
                    $jsonData[] = [
                        'caption' => 'Resend Deadline',
                        'old_value' => $presentInfo->resend_deadline,
                        'new_value' => $processHistoryInfo->resend_deadline,
                    ];
                }

                $jsonData[] = [
                    'caption' => 'Process Remarks',
                    'old_value' => $presentInfo->process_desc,
                    'new_value' => $processHistoryInfo->process_desc,
                ];
            }

            $jsonDataEncoded = json_encode($jsonData);

            $rollbackData = new ApplicationRollback();
            $rollbackData->app_tracking_no = $processData->tracking_no;
            $rollbackData->data = $jsonDataEncoded;
            $rollbackData->remarks = $request->get('remarks');
            $rollbackData->status_id = 25;
            $rollbackData->save();

            if (empty($rollbackData->tracking_no)) {
                $trackingPrefix = 'AR-' . date("dMY") . '-';
                DB::statement("update  application_rollback_list, application_rollback_list as table2  SET application_rollback_list.tracking_no=(
                                select concat('$trackingPrefix',
                                        LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                              ) as tracking_no
                                 from (select * from application_rollback_list ) as table2
                                 where table2.id!='$rollbackData->id' and table2.tracking_no like '$trackingPrefix%'
                            )
                          where application_rollback_list.id='$rollbackData->id' and table2.id='$rollbackData->id'");
            }

            $processData->status_id = $status_id;
            $processData->desk_id = $desk_id;
            $processData->user_id = $assignUserId;
            if ($user_type == '4x404') {
                $processData->bm_process_id = $processHistoryInfo->bm_process_id;
                $processData->resend_deadline = $processHistoryInfo->resend_deadline;
                $processData->process_desc = $processHistoryInfo->process_desc;
            } else {
                $processData->process_desc = $request->get('remarks');
            }
            $processData->save();

            DB::commit();
            Session::flash('success', 'Successfully application rollbacked !');
            return redirect('/settings/app-rollback');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ArStore : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [AR-1060]');
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [AR-1060]');
            return redirect()->back()->withInput();
        }

    }

    public function viewApplicationRollback($encoded_id)
    {
        if (!in_array(Auth::user()->user_type, ['1x101', '4x404'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1040]');
            return redirect()->back();
        }

        try {
            $id = Encryption::decodeId($encoded_id);
            $rollbackAppInfo = ApplicationRollback::where('id', $id)
                ->first();

            $appInfo = ProcessList::leftJoin('users', 'process_list.user_id', '=', 'users.id')
                ->leftJoin('user_desk', 'process_list.desk_id', '=', 'user_desk.id')
                ->leftJoin('company_info', 'process_list.company_id', '=', 'company_info.id')
                ->leftJoin('department', 'process_list.department_id', '=', 'department.id')
                ->leftJoin('sub_department', 'process_list.sub_department_id', '=', 'sub_department.id')
                ->leftJoin('process_status', function ($join) {
                    $join->on('process_list.process_type_id', '=', 'process_status.process_type_id')
                        ->on('process_list.status_id', '=', 'process_status.id');
                })
                ->leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
                ->where('process_list.tracking_no', $rollbackAppInfo->app_tracking_no)
                ->orderBy('id', 'desc')
                ->first([
                    DB::raw("CONCAT(users.user_first_name, ' ', users.user_middle_name, ' ', users.user_last_name) as desk_user_name"),
                    'user_desk.desk_name',
                    'process_status.status_name',
                    'company_info.company_name',
                    'company_info.company_name_bn',
                    'department.name as department_name',
                    'sub_department.name as sub_department_name',
                    'process_type.form_id',
                    'process_type.form_url',
                    'process_list.*',
                ]);

            if ($appInfo == '') {
                Session::flash('error', 'Sorry! Application not found. [AR-1070]');
                return Redirect::back();
            }
            //get application open url
            $redirectPath = CommonFunction::getAppRedirectPathByJson($appInfo->form_id);
            $openAppRoute = 'process/' . $appInfo->form_url . '/' . $redirectPath['view'] . '/' . Encryption::encodeId($appInfo->ref_id) . '/' . Encryption::encodeId($appInfo->process_type_id);

            // get corresponding basic information application ID
            $basicAppID = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->where('process_list.company_id', $appInfo->company_id)
                ->first(['process_list.ref_id', 'process_list.process_type_id', 'process_list.department_id', 'ea_apps.*']);
            if ($basicAppID->applicant_type == 'New Company Registration') {
                $BiRoute = 'basic-information/form-stakeholder/' . Encryption::encodeId('NCR') . '/' . Encryption::encodeId($appInfo->company_id);
            } else if ($basicAppID->applicant_type == 'Existing Company Registration') {
                $BiRoute = 'basic-information/form-stakeholder/' . Encryption::encodeId('ECR') . '/' . Encryption::encodeId($appInfo->company_id);
            } else if ($basicAppID->applicant_type == 'Existing User for BIDA services') {
                $BiRoute = 'basic-information/form-bida/' . Encryption::encodeId('EUBS') . '/' . Encryption::encodeId($appInfo->company_id);
            } else {
                $BiRoute = 'basic-information/form-bida/' . Encryption::encodeId('NUBS') . '/' . Encryption::encodeId($appInfo->company_id);
            }

            $data = json_decode($rollbackAppInfo->data);

            return view('Settings::app_rollback.view', compact('data', 'appInfo', 'rollbackAppInfo', 'openAppRoute', 'BiRoute'));

        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[AR-1065]");
            return \redirect()->back();
        }
    }


    // Maintenance mode
    public function maintenanceMode()
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            die('You have no access right! Please contact system administration for more information. [AR-1035]');
        }

        $user_types = UserTypes::all([
            'id', 'type_name'
        ]);

        $maintenance_data = MaintenanceModeUser::findOrNew(1);

        $allowed_user_array = (empty($maintenance_data->allowed_user_ids) ? [] : explode(',',
            $maintenance_data->allowed_user_ids));

        $users = Users::leftjoin('user_types', 'user_types.id', '=', 'users.user_type')
            ->whereIn('users.id', $allowed_user_array)
            ->get([
                'users.id',
                'users.user_email',
                'users.user_first_name',
                'users.user_middle_name',
                'users.user_last_name',
                'user_types.type_name',
                'users.user_number'
            ]);
        return view('Settings::maintenance-mode.add-form', compact('user_types', 'maintenance_data', 'users'));
    }


    public function maintenanceModeStore(Request $request)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1036]');
            return Redirect::back()->withInput();
        }

        if ($request->has('submit_btn') && $request->get('submit_btn') == 'add_user') {
            $this->validate($request, [
                'user_email' => 'required|email'
            ]);
        } else {
            $rules = [];
            $rules['alert_message'] = 'required_if:operation_mode,==,2';
            $rules['operation_mode'] = 'required|numeric';

            $messages = [];
            $messages['alert_message.required_if'] = 'The alert message field is required when operation mode is Maintenance.';
            $this->validate($request, $rules, $messages);
        }


        try {

            if ($request->has('submit_btn') && $request->get('submit_btn') == 'add_user') {
                $user = Users::where('user_email', $request->get('user_email'))->first(['id']);
                if ($user) {
                    $maintenance_data = MaintenanceModeUser::find(1);
                    $allowed_user_array = (empty($maintenance_data->allowed_user_ids) ? [] : explode(',',
                        $maintenance_data->allowed_user_ids));

                    if (in_array($user->id, $allowed_user_array)) {
                        Session::flash('error', 'This user is already added [SC-320]');
                        return Redirect::back()->withInput();
                    }
//                    $allowed_user_array[] = $user->id;
                    array_push($allowed_user_array, $user->id);
                    $maintenance_data->allowed_user_ids = implode(',', $allowed_user_array);
                    $maintenance_data->save();
                    Session::flash('success', 'The user has been added successfully');
                    return Redirect::back()->withInput();
                }
                Session::flash('error', 'Invalid user email [SC-321]');
                return Redirect::back()->withInput();
            } else {

                $maintenance_data = MaintenanceModeUser::findOrNew(1);
                $maintenance_data->allowed_user_types = (empty($request->get('user_types')) ? '' : implode(',',
                    $request->get('user_types')));
                $maintenance_data->alert_message = $request->get('alert_message');
                $maintenance_data->operation_mode = $request->get('operation_mode');
                $maintenance_data->save();

                //get all active user
                $getActiveUser = DB::select("select id, login_token from users where user_status = 'active' and login_token != '' and id not in ($maintenance_data->allowed_user_ids)");
                if ($getActiveUser) {
                    //forcedly logout all active users
                    foreach ($getActiveUser as $value) {
                        $sessionID = Encryption::decode($value->login_token);
                        session::getHandler()->destroy($sessionID);
                    }
                    //update user login_token
                    DB::statement("UPDATE users SET login_token = '' WHERE user_status = 'active' and id not in ($maintenance_data->allowed_user_ids) and login_token !='' ");
                }
                //end forcedly logout

                Session::flash('success', 'Maintenance mode saved successfully!');
                return Redirect::back();
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something Wrong. ' . $e->getMessage() . ' [SC-322]');
            return Redirect::back()->withInput();
        }
    }

    public function removeUserFromMaintenance($user_id)
    {
        if (in_array(Auth::user()->user_type, ['1x102'])) {
            Session::flash('error', 'You have no access right! Please contact system administration for more information. [AR-1037]');
            return Redirect::back()->withInput();
        }

        $user_id = Encryption::decodeId($user_id);

        $maintenance_data = MaintenanceModeUser::find(1);

        $users_array = explode(',', $maintenance_data->allowed_user_ids);
        if (($key = array_search($user_id, $users_array)) !== false) {
            unset($users_array[$key]);
        }

        $maintenance_data->allowed_user_ids = (empty($user_id) ? '' : implode(',', $users_array));
        $maintenance_data->save();
        Session::flash('success', 'The user has been removed from allowed users.[SC-323]');
        return Redirect::back()->withInput();
    }

    public function editFormJson()
    {
        $json = '{"organization_tin":"organization_tin", "organization_name_en":"organization_name_en", "company_title":"company_title"}';
        $array = json_decode($json, true);
        $editableFiled = '';
        foreach ($array as $value) {
            $editableFiled .= ',[name ="' . $value . '"]';
        }

        return view('Settings::maintenance-mode.edit-json', compact('user_types', 'maintenance_data', 'json', 'editableFiled'));
    }

    /* Forcefully data update update functions */
    public function forcefullyDataUpdate()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [FDU-906]');
        }
        return view("Settings::forcefully_data_update.list");
    }

    public function getForcefullyDataList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $data = ForcefullyDataUpdate::leftJoin('users', 'users.id', '=', 'forcefully_data_update.updated_by')
            ->where('is_archive', 0)
            ->orderBy('id', 'desc')
            ->get([
                'forcefully_data_update.*',
                DB::raw("CONCAT(users.user_first_name, ' ', users.user_middle_name, ' ', users.user_last_name) as modified_user"),
            ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/forcefully-data-update-view/' . Encryption::encodeId($data->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('status_id', function ($data) use ($mode) {
                if ($data->status_id == 1) {
                    return "<span class='label label-primary'>Submit</span>";
                } elseif ($data->status_id == 25) {
                    return "<span class='label label-success'>Approved</span>";
                } elseif ($data->status_id == 6) {
                    return "<span class='label label-danger'>Rejected</span>";
                }
            })
            ->editColumn('data', function ($data) {
                return substr($data->data, 30, 50);
            })
            ->editColumn('last_modified', function ($data) {
                return $data->modified_user . '<br>' . $data->updated_at;

            })
            ->make(true);
    }

    public function singleForcefullyViewById($id)
    {
        $id = Encryption::decodeId($id);

        $forcefully_data_update = ForcefullyDataUpdate::leftJoin('users', 'users.id', '=', 'forcefully_data_update.user_id')
            ->leftJoin('company_info', 'company_info.id', '=', 'forcefully_data_update.company_id')
            ->where('forcefully_data_update.id', $id)
            ->first([
                'forcefully_data_update.*',
                DB::raw('CONCAT(user_first_name," ",user_middle_name," ",user_last_name, "(", user_email,")") AS user_info'),
                'company_info.company_name'
            ]);

        $datas = json_decode($forcefully_data_update->data);
        $affected_rows = explode(',', $forcefully_data_update->affected_row_ids);
        $affected_rows_count = count($affected_rows);
        return view("Settings::forcefully_data_update.view", compact('forcefully_data_update', 'datas', 'affected_rows_count'));
    }

    public function createForcefullyDataUpdate()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [FDU-907]');
        }

        try {
            $companies = CompanyInfo::where('company_status', 1)
                ->where('is_approved', 1)
                ->where('is_eligible', 1)
                ->where('is_rejected', 'no')
                ->get(['company_name', 'id']);

            $users = User::select('id', DB::raw('CONCAT(user_first_name," ",user_middle_name," ",user_last_name, "(", user_email,")") AS user_info'))
                ->where('user_type', '5x505')
                ->where('is_approved', 1)
                ->where('user_status', '!=', 'rejected')
                ->orderBy('user_first_name', 'ASC')
                ->get(['user_info', 'id'])
                ->all();

            return view("Settings::forcefully_data_update.create", compact('users', 'companies'));
        } catch (Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[FDU-6001]");
            return \redirect()->back();
        }
    }

    public function storeForcefullyDataUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry! this is a request without proper way. [FDU-1014]',
            ]);
        }

        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information. [FDU-907]',
            ]);
        }

        $rules = [];
        $messages = [];

        $rules["table_name"] = 'required';
        $messages["table_name.required"] = 'Table name field is required';

        $rules["update_type"] = 'required';
        $messages["update_type.required"] = 'Update type field is required';

        $rules["user_id"] = 'required_if:update_type,user';
        $messages["user_id.required_if"] = 'User ID is required';

        $rules["company_id"] = 'required_if:update_type,company';
        $messages["company_id.required_if"] = 'Company ID is required';

        $rules["row_id"] = 'required_if:update_type,field';
        $messages["row_id.required_if"] = 'Row ID is required';

        $rules["label_name"] = 'requiredArray';
        $rules["column_name"] = 'requiredArray';
        //$rules["column_value"] = 'requiredArray';

        foreach ($request->get('column_value') as $key => $value) {
            $rules["column_value.$key"] = 'required';
            $messages["column_value.$key.required"] = 'Value field is required';
        }

        $validation = Validator::make($request->all(), $rules, $messages);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validation->errors(),
            ]);
        }

        try {
            DB::beginTransaction();

            $update_type = $request->get('update_type');
            $table_name = $request->get('table_name');
            $company_id = (!empty($request->get('company_id')) ? Encryption::decodeId($request->get('company_id')) : '');
            $user_id = (!empty($request->get('user_id')) ? Encryption::decodeId($request->get('user_id')) : '');
            $row_id = $request->get('row_id');

            // Column name check validation
            foreach ($request->get('column_name') as $key => $value) {
                $column_name = trim($request->get('column_name')[$key]);
                $is_column_exists = DB::select(DB::raw("SELECT column_name FROM information_schema.columns WHERE table_name = '" . $table_name . "' AND column_name = '" . $column_name . "' LIMIT 1"));
                if (empty($is_column_exists)) {
                    DB::rollback();
                    return response()->json([
                        'error' => true,
                        'status' => 'The ' . $column_name . ' column name is not found. [FDU-912]',
                    ]);
                }
            }

            $forcefully_data_update = new ForcefullyDataUpdate();
            $forcefully_data_update->table_name = $table_name;
            $forcefully_data_update->update_type = $update_type;
            $affected_row_array = [];

            if ($update_type == 'company') {

                $process_data = DB::table("process_list")->leftJoin("process_type", "process_list.process_type_id", "=", "process_type.id")
                    ->leftJoin($table_name, "process_list.ref_id", "=", "$table_name.id")
                    ->where("process_list.company_id", $company_id)
                    ->where("process_type.table_name", $table_name)
                    ->whereNotIn("process_list.status_id", [-1, 5, 6, 22])
                    ->get([
                        "process_list.process_type_id as process_type_id",
                        "process_list.ref_id as ref_id",
                    ]);

                if (empty($process_data)) {
                    DB::rollback();
                    return response()->json([
                        'error' => true,
                        'status' => 'No company data found. [FDU-908]',
                    ]);
                }

                $forcefully_data_update->company_id = $company_id;

                // store JSON data
                $data = [];
                foreach ($process_data as $process) {

                    foreach ($request->get('label_name') as $key => $value) {

                        $column_name = trim($request->get('column_name')[$key]);
                        $get_old_value = DB::table($table_name)->where('id', $process->ref_id)->pluck($column_name);

                        $data1 = [];
                        $data1['label_name'] = $request->get('label_name')[$key];
                        $data1['column_name'] = $column_name;
                        $data1['old_value'] = $get_old_value;
                        $data1['new_value'] = $request->get('column_value')[$key];
                        $data[] = $data1;
                    }

                    $affected_row_array[] = $process->ref_id;
                }

            } elseif ($update_type == 'user') {

                $where_column_name = $table_name == 'users' ? 'id' : 'created_by';
                $get_user_type_data = DB::table($table_name)->where($where_column_name, $user_id)->get(['id']);

                if (empty($get_user_type_data)) {
                    DB::rollback();
                    return response()->json([
                        'error' => true,
                        'status' => 'No data found. [FDU-909]',
                    ]);
                }

                $forcefully_data_update->user_id = $user_id;

                // store JSON data
                $data = [];
                foreach ($get_user_type_data as $user_data) {

                    foreach ($request->get('label_name') as $key => $value) {

                        $column_name = trim($request->get('column_name')[$key]);
                        $get_old_value = DB::table($table_name)->where('id', $user_data->id)->pluck($column_name);

                        $data1 = [];
                        $data1['label_name'] = $request->get('label_name')[$key];
                        $data1['column_name'] = $column_name;
                        $data1['old_value'] = $get_old_value;
                        $data1['new_value'] = $request->get('column_value')[$key];
                        $data[] = $data1;
                    }


                    $affected_row_array[] = $user_data->id;
                }

            } elseif ($update_type == 'field') {

                $table_data = DB::table($table_name)->where('id', $row_id)->pluck('id');
                if (empty($table_data)) {
                    DB::rollback();
                    return response()->json([
                        'error' => true,
                        'status' => 'No data found. [FDU-930]',
                    ]);
                }

                $forcefully_data_update->row_id = $row_id;

                // store JSON data
                $data = [];
                foreach ($request->get('label_name') as $key => $value) {

                    $column_name = trim($request->get('column_name')[$key]);
                    $get_old_value = DB::table($table_name)->where('id', $row_id)->pluck($column_name);

                    $data1 = [];
                    $data1['label_name'] = $request->get('label_name')[$key];
                    $data1['column_name'] = $column_name;
                    $data1['old_value'] = $get_old_value;
                    $data1['new_value'] = $request->get('column_value')[$key];
                    $data[] = $data1;
                }

                $affected_row_array[] = $row_id;
            }


            // store JSON data
            $column_with_value = [];
            foreach ($request->get('column_name') as $key => $value) {
                $data1 = [];
                $data1['column_name'] = trim($request->get('column_name')[$key]);
                $data1['new_value'] = trim($request->get('column_value')[$key]);
                $column_with_value[] = $data1;
            }

            $forcefully_data_update->data = json_encode($data);
            $forcefully_data_update->column_with_value = json_encode($column_with_value);
            $forcefully_data_update->status_id = 1; // submit
            $forcefully_data_update->affected_row_ids = implode($affected_row_array, ',');
            $forcefully_data_update->save();

            // generate tracking nubmer
            if (empty($forcefully_data_update->tracking_no)) {
                $trackingPrefix = 'FDU-' . date("dMY") . '-';
                DB::statement("update  forcefully_data_update, forcefully_data_update as table2  SET forcefully_data_update.tracking_no=(
                    select concat('$trackingPrefix',
                            LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                  ) as tracking_no
                     from (select * from forcefully_data_update ) as table2
                     where table2.id!='$forcefully_data_update->id' and table2.tracking_no like '$trackingPrefix%'
                )
              where forcefully_data_update.id='$forcefully_data_update->id' and table2.id='$forcefully_data_update->id'");
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => 'Data has been saved successfully',
                'link' => '/settings/forcefully-data-update',
            ]);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . '[FDU-6001]',
            ]);
        }
    }

    public function approveForcefullyDataUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry! this is a request without proper way.',
            ]);
        }

        if (!ACL::getAccsessRight('settings', 'E')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information.',
            ]);
        }

        try {

            $id = Encryption::decodeId($request->get('id'));
            $status_id = Encryption::decodeId($request->get('status'));

            $forcefully_data_update = ForcefullyDataUpdate::find($id);
            if (empty($forcefully_data_update)) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry no data found!',
                ]);
            }

            if (Auth::user()->id == $forcefully_data_update->created_by) {
                return response()->json([
                    'error' => true,
                    'status' => 'Sorry! You have no permission!',
                ]);
            }

            if ($status_id == 6 || $status_id == '6') {
                $forcefully_data_update->status_id = 6; // rejected
                $forcefully_data_update->save();

                return response()->json([
                    'success' => true,
                    'status' => 'Your data has been successfully rejected.',
                ]);
            } else if ($status_id == 25 || $status_id == '25') {

                $array_ids = explode(',', $forcefully_data_update->affected_row_ids);
                $json_decode = json_decode($forcefully_data_update->column_with_value);
                $array_data = [];
                foreach ($json_decode as $data) {
                    $array_data[$data->column_name] = $data->new_value;
                }

                DB::table($forcefully_data_update->table_name)
                    ->whereIn('id', $array_ids)
                    ->update($array_data);

                $forcefully_data_update->status_id = 25;
                $forcefully_data_update->save();

                return response()->json([
                    'success' => true,
                    'status' => 'Your file has been successfully approved.',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . "[FDU-5001]",
            ]);
        }
    }


    public function getChangeBasicInfoList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [BIC-906]');
        }

        return view("Settings::change_basic_info.list");
    }

    public function getChangeBasicInfoListData(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $mode = ACL::getAccsessRight('settings', 'V');

        $data = EaAppsChange::leftJoin('users', 'users.id', '=', 'ea_apps_change.updated_by')
            ->where('is_archive', 0)
            ->orderBy('id', 'desc')
            ->get([
                'ea_apps_change.*',
                DB::raw("CONCAT(users.user_first_name, ' ', users.user_middle_name, ' ', users.user_last_name) as modified_user"),
            ]);

        return Datatables::of($data)
            ->addColumn('action', function ($data) use ($mode) {
                if ($mode) {
                    return '<a href="/settings/change-basic-info-view/' . Encryption::encodeId($data->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                }
            })
            ->editColumn('status_id', function ($data) use ($mode) {
                if ($data->status_id == 1) {
                    return "<span class='label label-primary'>Submit</span>";
                } elseif ($data->status_id == 25) {
                    return "<span class='label label-success'>Approved</span>";
                } elseif ($data->status_id == 6) {
                    return "<span class='label label-danger'>Rejected</span>";
                }
            })
            ->editColumn('data', function ($data) {
                return substr($data->data_view, 30, 50);
            })
            ->editColumn('last_modified', function ($data) {
                return $data->modified_user . '<br>' . $data->updated_at;

            })
            ->make(true);
    }


    public function changeBasicInfo($company_id)
    {

        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [BIC-907]');
        }

        $company_id = (!empty($company_id) ? Encryption::decodeId($company_id) : 0);

        $data = [];
        $data['appInfo'] = BasicInformation::leftJoin('process_list', 'process_list.ref_id', '=', 'ea_apps.id')
            ->leftJoin('department', 'department.id', '=', 'process_list.department_id')
            ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
            ->leftJoin('ea_service', 'ea_service.id', '=', 'ea_apps.service_type')
            ->leftJoin('ea_reg_commercial_offices', 'ea_reg_commercial_offices.id', '=', 'ea_apps.reg_commercial_office')
            ->leftJoin('ea_ownership_status', 'ea_ownership_status.id', '=', 'ea_apps.ownership_status_id')
            ->leftJoin('ea_organization_type', 'ea_organization_type.id', '=', 'ea_apps.organization_type_id')
            ->leftJoin('country_info as ceo_country', 'ceo_country.id', '=', 'ea_apps.ceo_country_id')
            ->leftJoin('area_info as ceo_district', 'ceo_district.area_id', '=', 'ea_apps.ceo_district_id')
            ->leftJoin('area_info as ceo_thana', 'ceo_thana.area_id', '=', 'ea_apps.ceo_thana_id')
            ->leftJoin('area_info as office_division', 'office_division.area_id', '=', 'ea_apps.office_division_id')
            ->leftJoin('area_info as office_district', 'office_district.area_id', '=', 'ea_apps.office_district_id')
            ->leftJoin('area_info as office_thana', 'office_thana.area_id', '=', 'ea_apps.office_thana_id')
            ->leftJoin('area_info as factory_district', 'factory_district.area_id', '=', 'ea_apps.factory_district_id')
            ->leftJoin('area_info as factory_thana', 'factory_thana.area_id', '=', 'ea_apps.factory_thana_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
            ->first([
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.department_id',
                'department.name as department',
                'company_info.business_category',
                'ea_apps.*',
                'ea_service.name as service_name',
                'ea_reg_commercial_offices.name as reg_commercial_office_name',
                'ea_ownership_status.name as ownership_status',
                'ea_organization_type.id as organization_type_id',
                'ea_organization_type.name as organization_type',
                'ceo_country.nicename as ceo_country_name',
                'ceo_district.area_nm as ceo_district_name',
                'ceo_thana.area_nm as ceo_thana_name',
                'office_division.area_nm as office_division_name',
                'office_district.area_nm as office_district_name',
                'office_thana.area_nm as office_thana_name',
                'factory_district.area_nm as factory_district_name',
                'factory_thana.area_nm as factory_thana_name',
            ]);

        if (empty($data['appInfo'])) {
            die('Sorry basic information not found!');
        }

        $data['eaRegCommercialOffices'] = ['' => 'Select one'] + EA_RegCommercialOffices::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $data['eaOrganizationStatus'] = ['' => 'Select one'] + EA_OrganizationStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $data['eaOwnershipStatus'] = ['' => 'Select one'] + EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
        $data['countries'] = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $data['divisions'] = ['' => 'Select One'] + AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $data['districts'] = ['' => 'Select One'] + AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $data['thana'] = ['' => 'Select One'] + AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();
        $data['departments'] = Department::where('status', 1)->where('is_archive', 0)->orderBy('name', 'asc')->lists('name', 'id');

        if ($data['appInfo']->business_category == 2) {  //2 = government
            $data['eaService'] = ['' => 'Select one'] + EA_Service::where('is_archive', 0)->where('status', 1)->whereIn('type', [2, 3])->orderBy('name')->lists('name', 'id')->all();
            $data['eaOrganizationType'] = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [2, 3])->orderBy('name')->lists('name', 'id')->all();
        } else { // 1 = private
            $data['eaService'] = ['' => 'Select one'] + EA_Service::where('is_archive', 0)->where('status', 1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
            $data['eaOrganizationType'] = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->where('status', 1)->whereIn('type', [1, 3])->orderBy('name')->lists('name', 'id')->all();
        }

        return view("Settings::change_basic_info.create", $data);
    }

    public function storeChangeBasicInfo(Request $request)
    {
        if (!ACL::getAccsessRight('settings', 'A')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information. [BIC-907]',
            ]);
        }

        $rules = [];
        $messages = [];
        if ($request->has('toggleCheck')) {
            foreach ($request->get('toggleCheck') as $key => $val) {
                $rules[$key] = 'required';

                $name_to_lable = str_replace('_', ' ', $key);
                $name_to_lable = str_replace('n ', '', $name_to_lable);
                $messages[$key . '.required'] = 'This ' . $name_to_lable . ' field is required because of the corresponding checkbox';
            }
        }

        $this->validate($request, $rules, $messages);

        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $company_id = (!empty($request->get('company_id')) ? Encryption::decodeId($request->get('company_id')) : '');

        try {

            $data_update = [];
            $data_view = [];

            $label_name = $request->get('label_name');
            $column_name = $request->get('column_name');
            $keys = $request->get('toggleCheck');

            if (count($keys) > 0) {
                $eaService = EA_Service::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $eaRegCommercialOffices = EA_RegCommercialOffices::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $eaOwnershipStatus = EA_OwnershipStatus::where('is_archive', 0)->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $eaOrganizationType = ['' => 'Select one'] + EA_OrganizationType::where('is_archive', 0)->whereIn('type', [1, 3])->where('status', 1)->orderBy('name')->lists('name', 'id')->all();
                $countries = ['' => 'Select one'] + Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
                $divisions = AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
                $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id')->all();

                foreach ($keys as $key => $value) {

                    $data_update1 = [];
                    $data_update1['label_name'] = (isset($label_name[$key]) ? $label_name[$key] : '');
                    $data_update1['column_name'] = (isset($column_name[$key]) ? $column_name[$key] : '');
                    $data_update1['old_value'] = ($request->has(substr($key, 2)) ? $request->get(substr($key, 2)) : '');
                    $data_update1['new_value'] = ($request->has($key) ? $request->get($key) : '');
                    if ($key == 'ceo_dob' || $key == 'n_ceo_dob') {
                        $data_update1['old_value'] = ($request->has('ceo_dob') ? date('Y-m-d', strtotime($request->get('ceo_dob'))) : null);
                        $data_update1['new_value'] = ($request->has('n_ceo_dob') ? date('Y-m-d', strtotime($request->get('n_ceo_dob'))) : null);
                    }
                    $data_update[] = $data_update1;

                    $data_view1 = [];
                    $data_view1['column_name'] = (isset($column_name[$key]) ? $column_name[$key] : '');
                    $data_view1['label_name'] = (isset($label_name[$key]) ? $label_name[$key] : '');

                    if ($key == 'service_type' || $key == 'n_service_type') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $eaService[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $eaService[$request->get($key)] : '');

                    } elseif ($key == 'reg_commercial_office' || $key == 'n_reg_commercial_office') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $eaRegCommercialOffices[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $eaRegCommercialOffices[$request->get($key)] : '');

                    } elseif ($key == 'ownership_status_id' || $key == 'n_ownership_status_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $eaOwnershipStatus[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $eaOwnershipStatus[$request->get($key)] : '');

                    } elseif ($key == 'organization_type_id' || $key == 'n_organization_type_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $eaOrganizationType[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $eaOrganizationType[$request->get($key)] : '');

                    } elseif ($key == 'ceo_country_id' || $key == 'n_ceo_country_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $countries[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $countries[$request->get($key)] : '');

                    } elseif ($key == 'office_division_id' || $key == 'n_office_division_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $divisions[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $divisions[$request->get($key)] : '');

                    } elseif ($key == 'office_district_id' || $key == 'factory_district_id' || $key == 'eo_district_id' || $key == 'n_office_district_id' || $key == 'n_factory_district_id' || $key == 'n_ceo_district_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $districts[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $districts[$request->get($key)] : '');

                    } elseif ($key == 'n_office_thana_id' || $key == 'n_factory_thana_id' || $key == 'n_ceo_thana_id' || $key == 'n_office_thana_id' || $key == 'n_factory_thana_id' || $key == 'n_ceo_thana_id') {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $thana[$request->get(substr($key, 2))] : '');
                        $data_view1['new_value'] = ($request->has($key) ? $thana[$request->get($key)] : '');

                    } else {
                        $data_view1['old_value'] = ($request->has(substr($key, 2)) ? $request->get(substr($key, 2)) : '');
                        $data_view1['new_value'] = ($request->has($key) ? $request->get($key) : '');
                    }

                    $data_view[] = $data_view1;

                    // Department and sub department
                    if ($key == 'n_service_type') {
                        $old_department_sub_department = ProcessList::leftJoin('department', 'process_list.department_id', '=', 'department.id')
                            ->leftJoin('sub_department', 'process_list.sub_department_id', '=', 'sub_department.id')
                            ->where('process_list.ref_id', $app_id)
                            ->where('process_list.company_id', $company_id)
                            ->where('process_list.process_type_id', 100)
                            ->first([
                                'process_list.department_id as old_department_id',
                                'department.name as old_department_name',
                                'process_list.sub_department_id as old_sub_department_id',
                                'sub_department.name as old_sub_department_name'
                            ]);

                        $new_dept_sub_dept = CommonFunction::basicInfoDepSubDepSet($request->get('n_service_type'));
                        $new_department = Department::where('id', $new_dept_sub_dept['department_id'])->first(['id', 'name']);
                        $new_sub_department = SubDepartment::where('id', $new_dept_sub_dept['sub_department_id'])->first(['id', 'name']);

                        $data_update1 = [];
                        $data_update1['label_name'] = 'Department';
                        $data_update1['column_name'] = 'department_id';
                        $data_update1['old_value'] = $old_department_sub_department->old_department_id;
                        $data_update1['new_value'] = $new_department->id;
                        $data_update[] = $data_update1;

                        $data_update1 = [];
                        $data_update1['label_name'] = 'Sub department';
                        $data_update1['column_name'] = 'sub_department_id';
                        $data_update1['old_value'] = $old_department_sub_department->old_sub_department_id;
                        $data_update1['new_value'] = $new_sub_department->id;
                        $data_update[] = $data_update1;

                        $data_view1 = [];
                        $data_view1['column_name'] = 'department_id';
                        $data_view1['label_name'] = 'Department';
                        $data_view1['old_value'] = $old_department_sub_department->old_department_name;
                        $data_view1['new_value'] = $new_department->name;
                        $data_view[] = $data_view1;

                        $data_view1 = [];
                        $data_view1['column_name'] = 'sub_department_id';
                        $data_view1['label_name'] = 'Sub department';
                        $data_view1['old_value'] = $old_department_sub_department->old_sub_department_name;
                        $data_view1['new_value'] = $new_sub_department->name;
                        $data_view[] = $data_view1;
                    }


                    // Reg Commercial Offices
                    if ($key == 'n_service_type' && $request->get('n_service_type') == 5) {
                        $old_reg_commercial_office_value = BasicInformation::leftJoin('ea_reg_commercial_offices', 'ea_apps.reg_commercial_office', '=', 'ea_reg_commercial_offices.id')
                            ->where('ea_apps.id', $app_id)
                            ->where('ea_apps.company_id', $company_id)
                            ->first([
                                'ea_apps.reg_commercial_office as old_reg_commercial_office',
                                'ea_reg_commercial_offices.name as old_reg_commercial_office_name',
                            ]);

                        $new_reg_commercial_office = EA_RegCommercialOffices::where('id', $request->get('n_reg_commercial_office'))->first(['name']);

                        $data_update1 = [];
                        $data_update1['label_name'] = 'Reg Commercial Office';
                        $data_update1['column_name'] = 'reg_commercial_office';
                        $data_update1['old_value'] = $old_reg_commercial_office_value->old_reg_commercial_office;
                        $data_update1['new_value'] = $request->get('n_reg_commercial_office');
                        $data_update[] = $data_update1;


                        $data_view1 = [];
                        $data_view1['label_name'] = 'Reg Commercial Office';
                        $data_view1['column_name'] = 'reg_commercial_office';
                        $data_view1['old_value'] = $old_reg_commercial_office_value->old_reg_commercial_office_name;
                        $data_view1['new_value'] = $new_reg_commercial_office->name;
                        $data_view[] = $data_view1;

                    }
                }

                $appData = new EaAppsChange();
                $appData->data_update = json_encode($data_update);
                $appData->data_view = json_encode($data_view);
                $appData->ref_id = $app_id;
                $appData->company_id = $company_id;
                $appData->status_id = 1;
                $appData->save();

                // generate tracking number
                if (empty($appData->tracking_no)) {
                    $trackingPrefix = 'BIC-' . date("dMY") . '-';
                    DB::statement("update  ea_apps_change, ea_apps_change as table2  SET ea_apps_change.tracking_no=(
                    select concat('$trackingPrefix',
                            LPAD( IFNULL(MAX(SUBSTR(table2.tracking_no,-5,5) )+1,1),5,'0')
                                  ) as tracking_no
                     from (select * from ea_apps_change ) as table2
                     where table2.id!='$appData->id' and table2.tracking_no like '$trackingPrefix%'
                )
              where ea_apps_change.id='$appData->id' and table2.id='$appData->id'");
                }

                return view("Settings::change_basic_info.list");
            }

            Session::flash('error', 'In order to Proceed please select at least one field for amendment.');
            return Redirect::back();
        } catch (\Exception $e) {
            Log::error('UpdateBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1071]');
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . '[BIC-1071]'
            ]);
        }
    }

    public function singleBasicInfoViewById($id)
    {
        $id = Encryption::decodeId($id);

        if (empty($id)) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry no data found!',
            ]);
        }

        $basic_info_update = EaAppsChange::leftJoin('company_info', 'company_info.id', '=', 'ea_apps_change.company_id')
            ->where('ea_apps_change.id', $id)
            ->first([
                'ea_apps_change.*',
                'company_info.company_name'
            ]);

        $data_view = json_decode($basic_info_update->data_view);
        return view("Settings::change_basic_info.view", compact('basic_info_update', 'data_view'));
    }

    public function approveBasicInfoDataUpdate(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry! this is a request without proper way. [BIC-1014]',
            ]);
        }

        if (!ACL::getAccsessRight('settings', 'E')) {
            return response()->json([
                'error' => true,
                'status' => 'You have no access right! Please contact system administration for more information.',
            ]);
        }

        $app_id = (!empty($request->get('id')) ? Encryption::decodeId($request->get('id')) : '');
        $status = (!empty($request->get('id')) ? Encryption::decodeId($request->get('status')) : '');

        $ea_apps_change_data = EaAppsChange::find($app_id);
        if (empty($ea_apps_change_data)) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry no data found!',
            ]);
        }

        if ($ea_apps_change_data->status_id != 1 || $ea_apps_change_data->created_by == Auth::user()->id) {
            return response()->json([
                'error' => true,
                'status' => 'Sorry you have no access!',
            ]);
        }

        try {

            if ($status == 25) {
                $department = [];
                $company_info = [];
                $basic_data = [];

                $json_decode_data = json_decode($ea_apps_change_data->data_update);

                foreach ($json_decode_data as $data) {
                    if ($data->column_name == 'department_id' || $data->column_name == 'sub_department_id') {
                        $department[$data->column_name] = $data->new_value;
                    } elseif ($data->column_name == 'company_name' || $data->column_name == 'company_name_bn') {
                        $company_info[$data->column_name] = $data->new_value;
                        $basic_data[$data->column_name] = $data->new_value;
                    } else {
                        $basic_data[$data->column_name] = $data->new_value;
                    }
                }

                if (!empty($basic_data)) {
                    BasicInformation::where('id', $ea_apps_change_data->ref_id)
                        ->where('company_id', $ea_apps_change_data->company_id)
                        ->update($basic_data);
                }

                if (!empty($department)) {
                    ProcessList::where('ref_id', $ea_apps_change_data->ref_id)
                        ->where('process_type_id', 100)
                        ->where('status_id', 25)
                        ->where('company_id', $ea_apps_change_data->company_id)
                        ->update($department);
                }

                if (!empty($company_info)) {
                    CompanyInfo::where('id', $ea_apps_change_data->company_id)
                        ->where('is_approved', 1)
                        ->update($company_info);
                }
            }

            $ea_apps_change_data->status_id = $status;
            $ea_apps_change_data->action_datetime = date('Y-m-d H:i:s');
            $ea_apps_change_data->save();

            return response()->json([
                'success' => true,
                'status' => 'Your file has been successfully updated.',
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('UpdateBasicInfo : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [BIC-1071]');
            return response()->json([
                'error' => true,
                'status' => CommonFunction::showErrorPublic($e->getMessage()) . '[BIC-1071]'
            ]);
        }
    }

    /* Ending Store BasicInfo Change functions */

    /**
     *  Developed : samiul@ba-systems.com
     *  Updated : 2023.09.04
     *  Method : externalServiceList
     **/
    public function externalServiceList()
    {
        if (!ACL::getAccsessRight('settings', 'V')) {
            die('You have no access right! Please contact system administration for more information. [SC-907]');
        }
        return view("Settings::external_service.list");
    }// end -:- externalServiceList()

    /**
     *  Developed : samiul@ba-systems.com
     *  Updated : 2023.09.04
     *  Method : getExternalServiceList
     **/
    public function getExternalServiceList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        $mode = ACL::getAccsessRight('settings', 'V');

        $externalServices = ProcessType::where('bida_service_status', 2)
            ->orderBy('id', 'desc')
            ->get();

        return Datatables::of($externalServices)
            ->addColumn('action', function ($externalServices) use ($mode) {
                if ($mode) {
                    return '<a href="' . url('/settings/external-service-list/edit/' . Encryption::encodeId($externalServices->id)) .
                        '" class="btn btn-xs btn-primary"><i class="fa fa-folder-open"></i> Open</a>';
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($stakeholders) use ($mode) {
                if ($stakeholders->status == 1) {
                    return "<span class='label label-success'>Active</span>";
                } else {
                    return "<span class='label label-danger'>Inactive</span>";
                }
            })
            ->make(true);
    }// end -:- getExternalServiceList

    public function externalServiceEdit($id)
    {
        $decode_id = Encryption::decodeId($id);
        $externalService = ProcessType::where('id', $decode_id)->first();

        return view("Settings::external_service.edit", compact('externalService'));
    }// end :- externalServiceEdit()

    public function externalServiceUpdate(Request $request)
    {
        try {
            $decode_id = Encryption::decodeId($request->process_type_id);
            DB::table('process_type')->where('id', $decode_id)->update(['external_service_config' => $request->external_service_config]);
            Session::flash('success', 'Data is stored successfully !');
            return Redirect::back();
        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            return Redirect::back()->withInput();
        }

    }// end -:- externalServiceStore()
}
