<?php

namespace App\Modules\SecurityClearance\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\Encryption;
use App\Modules\SecurityClearance\Models\SecurityClearance;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SecurityClearance\SecurityClearanceACL;
use Illuminate\Support\Facades\Log;
use Exception;

class SecurityClearanceApiController extends Controller
{
    const WAITING_FOR_SUBMISSION = 2;
    const PROCESS_TYPE_WORK_PERMIT_NEW = 2;
    const SEND_ERROR_CODE = 'SCAC-101';
    const CHECK_STATUS_ERROR_CODE = 'SCAC-102';

    private $securityClearanceACL;

    public function __construct(SecurityClearanceACL $securityClearanceACL)
    {
        $this->securityClearanceACL = $securityClearanceACL;
    }

    public function send($id)
    {
        if (!$this->checkAccessRight()) {
            return $this->sendResponseError();
        }

        try {
            $app_id = Encryption::decodeId($id);

            $data = SecurityClearance::firstOrNew(['ref_id' => $app_id]);
            $data->status = self::WAITING_FOR_SUBMISSION;
            $data->response_json = null;
            $data->save();

            return $this->sendSuccessError('Application sent to MoHA successfully!');

        } catch (Exception $e) {
            $this->keepLog($e, self::SEND_ERROR_CODE);
            return $this->sendResponseError();
        }
    }

    public function checkStatus($id)
    {
        try {

            if (!$this->checkAccessRight()) {
                return $this->sendResponseError();
            }

            $app_id = Encryption::decodeId($id);
            $request_id = $app_id . rand(101, 999);

            $tracking_no = processList::where('ref_id', $app_id)
                ->where('process_type_id', self::PROCESS_TYPE_WORK_PERMIT_NEW)
                ->value('tracking_no');

            $status_check_request= [
                'project_code' => 'bida-oss',
                'request_id' => $request_id,
                'tracking_no' => $tracking_no
            ];

            SecurityClearance::where('ref_id', $app_id)
                ->update([
                    'status_check_request_json' => json_encode($status_check_request, true),
                    'ready_to_check' => 1
                ]);

            return $this->sendSuccessError('Status update Successfully!');

        } catch (Exception $e) {
            $this->keepLog($e, self::CHECK_STATUS_ERROR_CODE);
            return $this->sendResponseError();
        }
    }

    private function checkAccessRight()
    {
        return $this->securityClearanceACL->getAccessRight('E');
    }

    private function sendResponseError()
    {
        return response()->json(['responseCode' => 0, 'error' => 'Something went wrong!']);
    }

    private function sendSuccessError($message)
    {
        return response()->json(['responseCode' => 1, 'data' => $message]);
    }

    private function keepLog($e, $errorCode)
    {
        Log::error("Error occurred in SecurityClearanceController@{$errorCode} ({$e->getFile()}:{$e->getLine()}): {$e->getMessage()}");
    }
}
