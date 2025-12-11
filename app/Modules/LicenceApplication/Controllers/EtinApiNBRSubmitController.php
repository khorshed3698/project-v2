<?php

namespace App\Modules\LicenceApplication\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\LicenceApplication\Models\Etin\EtinJurisdictionList;
use App\Modules\LicenceApplication\Models\Etin\NbrAreaInfo;
use App\Modules\LicenceApplication\Models\Etin\EtinRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EtinApiNBRSubmitController extends Controller
{

    public static function formateSaveDataForApiSubmit($etin, $basicAppInfo, $processTypeId)
    {
        try {
            $eTinRequest = EtinRequest::firstOrNew(['ref_id' => $etin->id, 'process_type_id' => $processTypeId]);

            $currentAddress = [
                'AddrTypeNo' => 1,  // required
                'Addr' => $etin->ceo_address,
                'Addr1' => '',
                'CountNo' => NbrAreaInfo::getNbrCountryId($etin->ceo_country_id),// need to work country
                'DistNo' => $etin->ceo_district_id,
                'UpzaNo' => $etin->ceo_thana_id,
                'ThanaNo' => $etin->ceo_thana_id,
                'PostCode' => $etin->ceo_post_code,
                'City' => '',
                'State' => '',
                'ZipCode' => ''
            ];

            $parmanentAddress = [
                'AddrTypeNo' => 2,  // required
                'Addr' => $etin->office_address,
                'Addr1' => '',
                'CountNo' => NbrAreaInfo::getNbrCountryId($etin->reg_office_country_id),
                'DistNo' => $etin->office_district_id,
                'UpzaNo' => $etin->office_thana_id,
                'ThanaNo' => $etin->office_thana_id,
                'PostCode' => $etin->office_post_code,
                'City' => '',
                'State' => '',
                'ZipCode' => '',
            ];

            $otherAddress = [
                'AddrTypeNo' => 2,  // required
                'Addr' => $etin->other_address,
                'Addr1' => '',
                'CountNo' => NbrAreaInfo::getNbrCountryId($etin->other_address_country_id),
                'DistNo' => $etin->other_address_district_id,
                'UpzaNo' => $etin->other_address_thana_id,
                'ThanaNo' => $etin->other_address_thana_id,
                'PostCode' => $etin->other_address_post_code,
                'City' => '',
                'State' => '',
                'ZipCode' => '',
            ];

            if (empty($etin->other_address) && empty($etin->other_country_id)
                && empty($etin->other_address_district_id) && empty($etin->other_address_thana_id)
                && empty($etin->other_address_post_code)) {

                $otherAddress = null;
            }

            $eTinSubmitApi = [
                'RegTypeMastNo' => 3,   // Company ID
                'RegTypeNo' => 2,   // Company Type (Private Limited Company / Public Limited Company)
                'IsOldTin' => false,// Always False (New Registration)
                'RegJuriTypeNo' => intval($etin->main_source_income),   // Business Type (Business (Individual/Firm) / Business (Ltd. Company))
                'DistNo' => $etin->main_source_income_location, // Dhaka
                'JuriSelectTypeNo' => 5,// Company
                'JuriSelectListNo' => intval($etin->company_id),// Selected Company Id (Software developer Ltd)
                'JuriSubListName' => $etin->juri_sub_list_name,  // Selected Company Name (user will input) // Conditionally its required / optional
                'JuriSubListNo' => null,
                'JuriListName' => $etin->juri_sub_list_name,  // Selected Company Name (user will input) // Conditionally its required / optional
                'SubListName' => $etin->juri_sub_list_name,  // new entry in update api document
                'JuriTypeNo' => '',//confusion
                'CountryNo' => NbrAreaInfo::getNbrCountryId($etin->ceo_country_id),
                'AssesName' => $etin->ceo_full_name, // required



                'Gender' => 1, // new entry in update api document
                'DOB' => Carbon::parse(Auth::user()->user_DOB)->format('Y/m/d'), // new entry in update api document
                "FatherName" => $basicAppInfo->ceo_father_name, // new entry in update api document
                "MotherName" => $basicAppInfo->ceo_mother_name, // new entry in update api document
                "PassportNo" => $basicAppInfo->ceo_passport_no, // new entry in update api document
//                "PassportIssueDate" => Carbon::parse(Auth::user()->passport_date_of_issue)->format('Y/m/d'), // new entry in update api document
//                "PassportExpiryDate" => Carbon::parse(Auth::user()->passport_date_of_expire)->format('Y/m/d'), // new entry in update api document


                'IncorpNumber' => $etin->incorporation_certificate_number, // required
                'IncorpDate' => date('Y/m/d', strtotime($etin->incorporation_certificate_date)), // required
                'DesigNo' => intval($etin->ceo_designation),// optional
                'RelevantName' => $etin->ceo_full_name, // optional
                'ContactTelephone' => $etin->ceo_mobile_no, // required
                'ContactFax' => $etin->ceo_fax_no,  // optional
                'ContactEmailAddr' => $etin->ceo_email, // required
                'RjscName' => $etin->ceo_full_name,  // required
                'CurrentAddress' => $currentAddress,
                'PermanentAddress' => $parmanentAddress,
                'OtherAddress' => $otherAddress,
            ];

            $eTinRequest->status = 0;
            $eTinRequest->request = json_encode($eTinSubmitApi);

            $eTinRequest->save();

            return ['success' => true];

        } catch (\Exception $e) {

            Log::error($e->getTraceAsString());
            return ['success' => false, 'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[EAPI-1027]"];
        }
    }

}