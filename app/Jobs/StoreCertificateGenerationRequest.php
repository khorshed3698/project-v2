<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Libraries\CommonFunction;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\UserDesk;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\PdfServiceInfo;
use App\Modules\Users\Models\Users;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * StoreCertificateGenerationRequest
 */
class StoreCertificateGenerationRequest extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    use DispatchesJobs;


    private $app_id;
    private $process_type_id;
    private $approver_desk_id;
    private $department;
    private $created_by_user_id;
    private $certificate_type;


    /**
     * __construct
     *
     * @param  mixed $app_id
     * @param  mixed $process_type_id
     * @param  mixed $approver_desk_id
     * @param  mixed $department
     * @param  mixed $created_by_user_id
     * @param  mixed $certificate_type
     * @return void
     */
    public function __construct($app_id, $process_type_id, $approver_desk_id = 0, $department = '', $created_by_user_id, $certificate_type = 'generate')
    {
        $this->app_id = $app_id;
        $this->process_type_id = $process_type_id;
        $this->approver_desk_id = $approver_desk_id;
        $this->department = $department;
        $this->created_by_user_id = $created_by_user_id;
        $this->certificate_type = $certificate_type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $app_id = $this->app_id;
            $process_type_id = $this->process_type_id;
            $approver_desk_id = $this->approver_desk_id;
            $department = $this->department;
            $created_by_user_id = $this->created_by_user_id;
            $certificate_type = $this->certificate_type;



            $tableName = '';
            $fieldName = '';
            switch ($process_type_id) {
                case 1: //Visa Recommendation New (1)
                    $appInfo = ProcessList::leftJoin('vr_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.app_type_id as app_type_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    // 5 = Visa On Arrival
                    if ($appInfo->app_type_id == 5) {
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'vr_certificate_on_arrival')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else {
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'vr_certificate_others')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 2: //Work permit new (2)
                    if ($department == 1) { //Commercial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_new_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else { //Industrial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_new_industrial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 3: //Work permit extension (3)
                    if ($department == 1) { //Commercial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_extension_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else { //Industrial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_extension_industrial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 4: //Work permit amendment (4)
                    if ($department == 1) { //Commercial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_amendment_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else { //Industrial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_amendment_industrial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 5: //Work permit cancellation (5)
                    if ($department == 1) { //Commercial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_cancellation_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else { //Industrial
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'wp_cancellation_industrial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 6: // Office Permission New (6)
                    // get office type of application
                    $appInfo = ProcessList::leftJoin('opn_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.office_type']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->office_type == 1) { // Branch office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'opn_branch_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } elseif ($appInfo->office_type == 2) { // Liaison office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'opn_liaison_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } elseif ($appInfo->office_type == 3) { // Representative office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'opn_representative_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else {
                        return false;
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 7: // Office permission extension (7)
                    $appInfo = ProcessList::leftJoin('ope_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.office_type']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->office_type == 1) { // Branch office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'ope_branch_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } elseif ($appInfo->office_type == 2) { // Liaison office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'ope_liaison_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } elseif ($appInfo->office_type == 3) { // Representative office
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'ope_representative_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else {
                        return false;
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 8: // Office permission amendment (8)
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'opa_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 9: // Office Permission Cancellation (9)
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'opc_commercial')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 10: //Visa Recommendation Amendment(10)
                    $appInfo = ProcessList::leftJoin('vra_apps as app', 'app.id', '=', 'process_list.ref_id') // according new visa type
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.app_type_id as app_type_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->app_type_id == 5) {
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'vr_amendment_on_arrival')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else {
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'vr_amendment_others')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 11: //Remittance Approval new(11)
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'remittance_new')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 12: //Bida Registration Amendment
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'bida_registration_amendment')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 13: //IRC Recommendation New
                    $appInfo = ProcessList::leftJoin('irc_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.irc_purpose_id as app_purpose_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->app_purpose_id == 1) { // 1 = raw materials
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_1st_adhoc_raw_materials')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 2) { //2 =spare parts
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_1st_adhoc_spare_parts')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 3) { // 3 = both
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_1st_adhoc_both')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 14: //IRC Recommendation 2nd adhoc
                    $appInfo = ProcessList::leftJoin('irc_2nd_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.irc_purpose_id as app_purpose_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->app_purpose_id == 1) { // 1 = raw materials
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_2nd_adhoc_raw_materials')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 2) { //2 =spare parts
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_2nd_adhoc_spare_parts')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 3) { // 3 = both
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_2nd_adhoc_both')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 15: //IRC Recommendation 3rd adhoc
                    $appInfo = ProcessList::leftJoin('irc_3rd_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.irc_purpose_id as app_purpose_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->app_purpose_id == 1) { // 1 = raw materials
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_3rd_adhoc_raw_materials')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 2) { //2 =spare parts
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_3rd_adhoc_spare_parts')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->app_purpose_id == 3) { // 3 = both
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'irc_rec_3rd_adhoc_both')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;

                case 17: //VIP Lounge
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'vipl_certificate')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                case 19: //Waiver Condition 7
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'waiver_condition_7')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;

                    break;
                case 20: //Waiver Condition 8
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'waiver_condition_8')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;

                    break;
                case 21: //Import Permission New
                    $pdf_info = PdfServiceInfo::where('certificate_name', 'import_permission')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;

                    break;
                case 102: //BIDA Registration
                    $appInfo = ProcessList::leftJoin('br_apps as app', 'app.id', '=', 'process_list.ref_id')
                        ->where('process_list.ref_id', $app_id)
                        ->where('process_list.process_type_id', $process_type_id)
                        ->first(['app.organization_status_id']);
                    if (empty($appInfo)) {
                        return false;
                    }

                    if ($appInfo->organization_status_id === 1) { // Joint Venture
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'bida_registration_joint_venture')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->organization_status_id === 2) { // Foreign
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'bida_registration_foreign')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    } else if ($appInfo->organization_status_id === 3) { // Local
                        $pdf_info = PdfServiceInfo::where('certificate_name', 'bida_registration_local')->first(['pdf_server_url', 'reg_key', 'pdf_type', 'certificate_name', 'table_name', 'field_name']);
                    }

                    if (empty($pdf_info)) {
                        return false;
                    }
                    $tableName = $pdf_info->table_name;
                    $fieldName = $pdf_info->field_name;
                    break;
                default:
                    return false;
                    break;
            } // ending of switch case

            $url_store = PdfPrintRequestQueue::firstOrNew([
                'process_type_id' => $process_type_id,
                'app_id' => $app_id
            ]);

            $url_store->process_type_id = $process_type_id;
            $url_store->app_id = $app_id;
            $url_store->pdf_server_url = $pdf_info->pdf_server_url;
            $url_store->reg_key = $pdf_info->reg_key;
            $url_store->pdf_type = $pdf_info->pdf_type;
            $url_store->certificate_name = $pdf_info->certificate_name;
            $url_store->prepared_json = 0;
            $url_store->table_name = $tableName;
            $url_store->field_name = $fieldName;
            $url_store->url_requests = '';
            $url_store->job_sending_status = 0;
            $url_store->no_of_try_job_sending = 0;
            $url_store->job_receiving_status = 0;
            $url_store->no_of_try_job_receving = 0;

            if ($certificate_type == 'generate') {
                $url_store->signatory = $created_by_user_id;

                // Store approver information
                $this->storeSignatureQRCode($process_type_id, $app_id, $created_by_user_id, $approver_desk_id, 'final');
            }

            $url_store->updated_at = date('Y-m-d H:i:s');
            return $url_store->save();
        } catch (\Exception $exception) {
            /**
             * if a job failed to process then it will be attempted again until maximum tries. 
             * The release method accepts one argument: the number of seconds you wish to wait until the job is made available again.
             * 
             * Release for 60 seconds
             */
            $this->release(60);
        }
    }

    /**
     * storeSignatureQRCode
     *
     * @param  mixed $process_type_id
     * @param  mixed $app_id
     * @param  mixed $user_id
     * @param  mixed $approver_desk_id
     * @param  mixed $signature_type
     * @return void
     */
    public function storeSignatureQRCode($process_type_id, $app_id, $user_id, $approver_desk_id, $signature_type = 'final')
    {
        $pdf_sign = \App\Modules\Settings\Models\pdfSignatureQrcode::firstOrNew([
            "signature_type" => $signature_type,
            "app_id" => $app_id,
            "process_type_id" => $process_type_id,
        ]);

        $user_info = Users::where('id', $user_id)->first([
            DB::raw("CONCAT(user_first_name,' ',user_middle_name, ' ',user_last_name) as user_full_name"),
            'designation',
            'user_phone',
            'user_number',
            'user_email',
            'signature_encode',
        ]);
        $pdf_sign->signer_user_id = $user_id;
        $pdf_sign->signer_desk = UserDesk::where('id', $approver_desk_id)->pluck('desk_name');
        $pdf_sign->signer_name = $user_info->user_full_name;
        $pdf_sign->signer_designation = $user_info->designation;
        $pdf_sign->signer_phone = $user_info->user_phone;
        $pdf_sign->signer_mobile = $user_info->user_number;
        $pdf_sign->signer_email = $user_info->user_email;
        $pdf_sign->signature_encode = $user_info->signature_encode;
        $pdf_sign->save();
    }
}
