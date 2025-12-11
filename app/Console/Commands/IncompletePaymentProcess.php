<?php


namespace App\Console\Commands;

use App\Libraries\CommonFunction;
use App\Libraries\UtilFunction;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\SonaliPayment\Controllers\SonaliPaymentController;
use App\Modules\SonaliPayment\Models\IpnRequest;
use App\Modules\SonaliPayment\Models\SonaliPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class IncompletePaymentProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ipn:incomplete-payment-process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processing pending payment.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param SonaliPaymentController $sonaliPaymentController
     * @return mixed
     */
    public function handle(SonaliPaymentController $sonaliPaymentController)
    {
        $getPendingPayment = IpnRequest::where('is_required_auto_recover', 1)
            ->where('no_of_try', '<', 10)
            ->limit(5)
            ->orderBy('id', 'desc')
            ->get();

        if ($getPendingPayment->count()) {
            foreach ($getPendingPayment as $ipn_payment) {

                $sonaliPayment = SonaliPayment::where('ref_tran_no', $ipn_payment->ref_tran_no)
                    ->first();
                if (empty($sonaliPayment)) {
                    echo "Payment Ref. No. $ipn_payment->ref_tran_no. Payment information not found.\n";
                    continue;
                }

                DB::beginTransaction();
                try {
                    $ipn_update = IpnRequest::find($ipn_payment->id);

                    // Get payment verification status
                    $verifyStatus = $sonaliPaymentController->transactionVerificationWithRefNo($sonaliPayment->id);

                    if ($verifyStatus === false) {
                        echo "Payment Ref. No. $ipn_payment->ref_tran_no. " . Session::get('error') . "\n";
                    } else if (isset($verifyStatus['code']) && $verifyStatus['code'] == '200') {

                        // Single payment distribution verify
                        $sonaliPaymentController->singlePaymentDetailsVerification($sonaliPayment->id);

                        // Calling the common function for post-payment processing
                        $afterPaymentStatus = $this->commonAfterPaymentForAutoRecover($sonaliPayment);

                        /**
                         * If post-payment processing is successful,
                         * the auto-recovery status of Payment and IPN will be updated.
                         */
                        if ($afterPaymentStatus['status']) {
                            $sonaliPayment->is_auto_recovered = 1;
                            $sonaliPayment->save();

                            $ipn_update->is_required_auto_recover = 0;
                            echo "Payment Ref. No. $ipn_payment->ref_tran_no. Payment have been completed successfully.\n";
                        } else {
                            echo "Payment Ref. No. $ipn_payment->ref_tran_no. " . $afterPaymentStatus['msg'] . "\n";
                        }
                    } else {
                        echo "Payment Ref. No. $ipn_payment->ref_tran_no. " . $verifyStatus['message'] . "\n";
                    }

                    $ipn_update->no_of_try = $ipn_update->no_of_try + 1;
                    $ipn_update->save();
                    DB::commit();
                } catch (\Exception $exception) {
                    DB::rollback();
                    echo "Payment Ref. No. $ipn_payment->ref_tran_no. " . $exception->getMessage() . "\n";
                }
            }
        } else {
            echo "There have no pending payment to process.\n";
        }
    }

    /**
     * @param $paymentInfo
     * @return array
     */
    public function commonAfterPaymentForAutoRecover($paymentInfo)
    {
        try {

            if (!in_array($paymentInfo->process_type_id, config('bida_service.active'))) {
                return [
                    'status' => 0,
                    'msg' => 'The process type ' . $paymentInfo->process_type_id . 'is not authorized for auto-recover.'
                ];
            }

            $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->where('ref_id', $paymentInfo->app_id)
                ->where('process_type_id', $paymentInfo->process_type_id)
                ->first([
                    'process_type.name as process_type_name',
                    'process_type.process_supper_name',
                    'process_type.process_sub_name',
                    'process_type.form_id',
                    'process_list.*'
                ]);

            //get users email and phone no according to working company id
            $applicantEmailPhone = UtilFunction::geCompanyUsersEmailPhone($processData->company_id);

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


            if ($paymentInfo->payment_category_id == 1) {
                if ($processData->status_id != '-1') {
                    return [
                        'status' => 0,
                        'msg' => 'Application status is not valid to do post-payment processing.'
                    ];
                }

                $general_submission_process_data = CommonFunction::getGeneralSubmission($paymentInfo->process_type_id);
                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->process_desc = 'Service Fee Payment completed successfully.';
                $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date

                // Application submit status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                // application submission mail sending
                CommonFunction::sendEmailSMS('APP_SUBMIT', $appInfo, $applicantEmailPhone);
            } elseif ($paymentInfo->payment_category_id == 2) {
                if (!in_array($processData->status_id, [15, 32])) {
                    return [
                        'status' => 0,
                        'msg' => 'Application status is not valid to do post-payment processing.'
                    ];
                }

                $general_submission_process_data = CommonFunction::getGovtPaySubmission($paymentInfo->process_type_id);
                $processData->status_id = $general_submission_process_data['process_starting_status'];
                $processData->desk_id = $general_submission_process_data['process_starting_desk'];

                $processData->read_status = 0;
                $processData->process_desc = 'Government Fee Payment completed successfully.';
                $appInfo['payment_date'] = date('d-m-Y', strtotime($paymentInfo->payment_date));
                $appInfo['govt_fees'] = CommonFunction::getGovFeesInWord($paymentInfo->pay_amount);
                $appInfo['govt_fees_amount'] = $paymentInfo->pay_amount;

                // Application status_id for email queue
                $appInfo['status_id'] = $processData->status_id;

                CommonFunction::sendEmailSMS('APP_GOV_PAYMENT_SUBMIT', $appInfo, $applicantEmailPhone);
            } else {
                return [
                    'status' => 0,
                    'msg' => 'Payment category is not valid.'
                ];
            }
            $processData->save();

            return [
                'status' => 1,
                'msg' => 'post-payment processing done.'
            ];
        } catch (\Exception $exception) {
            return [
                'status' => 0,
                'msg' => $exception->getMessage()
            ];
        }
    }
}