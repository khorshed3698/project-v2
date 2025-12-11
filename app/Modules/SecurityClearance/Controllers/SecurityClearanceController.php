<?php

namespace App\Modules\SecurityClearance\Controllers;

use App\Libraries\Encryption;
use App\Modules\SecurityClearance\Models\SecurityClearance;
use App\Modules\SecurityClearance\Models\SecurityClearanceStatus;
use App\Modules\SecurityClearance\SecurityClearanceACL;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use yajra\Datatables\Datatables;
use Exception;

class SecurityClearanceController extends Controller
{
    const PROCESS_TYPE_WORK_PERMIT_NEW = 2;
    const PROCESS_STATUS_APPROVED = 25;
    const USER_TYPE_DESK = '4x404';
    const USER_TYPE_IT_HELP = '2x202';
    const PROBLEM_IN_DATA = -1;
    const WAITING_FOR_SUBMISSION = 2;
    const ACTIVE_STATUS = 1;

    private $securityClearanceACL;

    public function __construct(SecurityClearanceACL $securityClearanceACL)
    {
        $this->securityClearanceACL = $securityClearanceACL;
    }

    public function index()
    {
        if (!$this->checkAccessRight()) {
            return $this->sendResponseError();
        }

        try {
            $data['application_by_status'] = $this->applicationsByStatus();
            return view("SecurityClearance::list", $data);
        } catch (Exception $e) {
            $this->logAndFlashError($e, 'SCC-101');
            return redirect()->back();
        }
    }

    public function getList(Request $request)
    {
        if (!$this->checkAccessRight()) {
            return $this->sendResponseError();
        }

        try {
            if ($request->get('applications_by_status') == 'applications_by_status') {
                $data = $this->getApplicationData($request->get('status'));
            } else {
                $data = $this->getApplicationData();
            }

            return $this->getDataTableData($data);
        } catch (Exception $e) {
            $this->logAndFlashError($e, 'SCC-102');
            return $this->sendJsonResponseError();
        }
    }

    public function getTrackingNoList(Request $request)
    {
        if (!$this->checkAccessRight()) {
            return $this->sendResponseError();
        }

        try {
            return $this->getDataTableData(
                $this->getApplicationData('',$request->get('tracking_no'))
            );
        } catch (Exception $e) {
            $this->logAndFlashError($e, 'SCC-103');
            return $this->sendJsonResponseError();
        }
    }

    private function checkAccessRight()
    {
        return $this->securityClearanceACL->getAccessRight('V');
    }

    private function applicationsByStatus()
    {
        $status = SecurityClearanceStatus::where(['process_type_id' => self::PROCESS_TYPE_WORK_PERMIT_NEW, 'status' => self::ACTIVE_STATUS])->get([
            'id',
            'status_name',
            'color'
        ]);

        $data = [];

        foreach ($status as $row) {
            $data[$row->id] = [
                'process_type_id' => self::PROCESS_TYPE_WORK_PERMIT_NEW,
                'status_id' => $row->id,
                'status_name' => $row->status_name,
                'no_of_application' => 0,
                'color' => $row->color,
            ];
        }

        $applications = SecurityClearance::get(['id','status']);

        foreach ($applications as $application) {
            if (isset($data[$application->status]['no_of_application'])) {
                $data[$application->status]['no_of_application'] = $data[$application->status]['no_of_application'] + 1;
            }
        }

        return $data;
    }

    private function getApplicationData($status = '', $tracking_no='')
    {
        $user_type = Auth::user()->user_type;

        $query = ProcessList::leftJoin('process_type', 'process_list.process_type_id', '=', 'process_type.id')
            ->leftJoin('security_clearance_request_queue', 'process_list.ref_id', '=', 'security_clearance_request_queue.ref_id')
            ->leftJoin('security_clearance_status', 'security_clearance_request_queue.status', '=', 'security_clearance_status.id')
            ->leftjoin('process_status', function ($on) {
                $on->on('process_list.status_id', '=', 'process_status.id')
                    ->on('process_list.process_type_id', '=', 'process_status.process_type_id', 'and');
            })
            ->where('process_type.active_menu_for', 'like', "%$user_type%")
            ->where('process_list.process_type_id', self::PROCESS_TYPE_WORK_PERMIT_NEW)
            ->where('process_list.status_id', self::PROCESS_STATUS_APPROVED);

        if ($status !== '') {
            $query->where('security_clearance_request_queue.status', $status)
            ->orderBy('security_clearance_request_queue.id', 'asc');
        } elseif ($tracking_no !== '') {
            $query->where('process_list.tracking_no', $tracking_no);
        } else {
            $security_clearance_ids = SecurityClearance::lists('ref_id')->toArray();
            $query->leftJoin('wp_apps', 'process_list.ref_id', '=', 'wp_apps.id')
                ->whereNotIn('process_list.ref_id', $security_clearance_ids)
                ->whereNotNull('wp_apps.certificate_link');
        }

        return $query->orderBy('process_list.created_at', 'desc')->get([
            'process_list.id',
            'process_list.ref_id',
            'process_list.tracking_no',
            'process_list.process_type_id',
            'process_list.updated_at',
            'process_type.form_url',
            'process_status.status_name as process_status',
            'process_type.name as process_name',
            'security_clearance_request_queue.id as request_queue_id',
            'security_clearance_request_queue.status as queue_status_id',
            'security_clearance_request_queue.fl_certificate',
            'security_clearance_request_queue.certificate',
            'security_clearance_request_queue.status',
            'security_clearance_status.status_name as security_clearance_status',
        ]);
    }

    private function getDataTableData($data)
    {
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                $html = '';
                if (in_array(Auth::user()->user_type, [self::USER_TYPE_DESK, self::USER_TYPE_IT_HELP])) {
                    $html ='<a target="_blank" href="' . url('process/' . $data->form_url . '/view-app/' . Encryption::encodeId($data->ref_id) . '/' . Encryption::encodeId($data->process_type_id)) . '" class="btn btn-xs btn-primary button-color"> Open</a> &nbsp;';
                    
                    if (isset($data->request_queue_id)) {
                        $html .= '<a  target="" href="' . url('security-clearance/verify-json-request/'. Encryption::encodeId($data->request_queue_id) ) . '" class="btn btn-xs btn-primary"><i class="fas fa-check"></i> Verify</a> &nbsp;';
                        $html .= '<a  target="" href="javascript:void(0)"  onclick="statusCheck('."'". Encryption::encodeId($data->ref_id)."'".')"  class="btn btn-xs btn-info button-color"> Status Check</a> &nbsp;';
                        
                    }
                    $html .= '<a  target="" href="javascript:void(0)"  onclick="send('."'". Encryption::encodeId($data->ref_id)."'".')" class="btn btn-xs btn-info button-color"> Send to MoHA</a> &nbsp; ';

                    // if ($data->queue_status_id == self::PROBLEM_IN_DATA || $data->queue_status_id === null) {
                    //     $html .= '<a  target="" href="javascript:void(0)"  onclick="send('."'". Encryption::encodeId($data->ref_id)."'".')" class="btn btn-xs btn-info button-color"> Send to MoHA</a> &nbsp; ';
                    //     //$html .= '<a  target="" href="javascript:void(0)"  onclick="statusCheck('."'". Encryption::encodeId($data->ref_id)."'".')"  class="btn btn-xs btn-info button-color m-3"> Status Check</a>';
                    // }

                    if ($data->queue_status_id > 0 && $data->queue_status_id != self::WAITING_FOR_SUBMISSION) {
                        if ($data->certificate != '') {
                            $html .= '<a target="_blank" href="' . url($data->certificate) . '" class="btn btn-xs btn-primary button-color"> SC Download</a>';
                        }
                        if ( $data->fl_certificate != '') {
                            $html .= '<a target="_blank" href="' . url($data->fl_certificate) . '" class="btn btn-xs btn-warning button-color"> AL Download</a>';
                        }
                    }
                }
                return $html;
            })
            ->addColumn('queue_status', function ($data) {
                return empty($data->security_clearance_status) ? 'Waiting for Submission' : $data->security_clearance_status;
            })
            ->addColumn('last_status', function ($data) {
                return $data->process_status . '<br/>' . $data->updated_at;
            })
            ->removeColumn('id', 'ref_id', 'process_type_id')
            ->make(true);
    }

    private function sendResponseError()
    {
        return 'You have no access right! Please contact system administration for more information.';
    }

    private function logAndFlashError($e, $error_code)
    {
        Log::error("Error occurred in SecurityClearanceController@{$error_code} ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
        Session::flash('error', "Something went wrong during application data load [{$error_code}]");
    }

    private function sendJsonResponseError()
    {
        return response()->json(['responseCode' => 0, 'error' => 'Something went wrong!']);
    }

    public function verifyJsonRequest($request_queue_id)
    {
        $request_queue_id = Encryption::decodeId($request_queue_id);

        $result = SecurityClearance::find($request_queue_id)->toArray();
        
        unset($result['id'], $result['created_by'], $result['updated_by']);


        return view('SecurityClearance::sql_result', compact('result'));
    }
}