<?php

namespace App\Modules\LicenceApplication\Controllers;


use App\Libraries\CommonFunction;
use App\Modules\LicenceApplication\Models\TradeLicence\DccAreaInfo;
use App\Modules\LicenceApplication\Models\TradeLicence\TradeLicenceRequest;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TLApiFormatJsonSaveController
{
    private static $bidaUserId = 38;

    public static function formateJsonSaveDataForApiSubmit($tradeLicence, $processTypeId)
    {

        try {

            $companyIds = CommonFunction::getUserCompanyWithZero();

            $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
                ->where('process_list.process_type_id', 100)
                ->where('process_list.status_id', 25)
                ->whereIn('process_list.company_id', $companyIds)
                ->first(['ea_apps.*']);

            $tradeLicenceRequest = TradeLicenceRequest::firstOrNew(['ref_id' => $tradeLicence->id]);

            $tradeLicenceSubmitApi = [
                'pOldOrNew' => 0,           //  = 0 (new)
                'pAppUserId' => 0,          //  = 0 (number)

                // ------------- < user creation > ----------------

                'pRegType' => 2,            //  = 2 (Institution)
                'pFullName' => $basicAppInfo->ceo_full_name,            //  User Full Name - English
                'pFullNameBn' => $basicAppInfo->ceo_full_name,          //  User Full Name - English
                'pMobileNo' => $basicAppInfo->ceo_mobile_no,            //  Mobile No - English
                'pMailId' => $basicAppInfo->ceo_email,                  //  Email - English

                // ------------- </ user creation > ----------------

                // ------------- < profile update > ----------------

                'pFatherName' => $basicAppInfo->ceo_father_name,          //  Father Full Name - English
                'pFatherNameBn' => $basicAppInfo->ceo_father_name,        //  Father Full Name - English
                'pMotherName' => $basicAppInfo->ceo_mother_name,          //  Mother Full Name - English
                'pMotherNameBn' => $basicAppInfo->ceo_mother_name,        //  Mother Full Name - English
                'pSpouseName' => $basicAppInfo->ceo_spouse_name,          //  Spouse Full Name (English)
                'pSpouseNameBn' => $basicAppInfo->ceo_spouse_name,        //  Spouse Full Name (English)
                'pDoB' => date('d/m/Y', strtotime($basicAppInfo->ceo_dob)),                       //  Date of Birth - English
                'pNid' => $basicAppInfo->ceo_nid,                       //  National ID or Birth Registration or Passport No
                'pBrthRegNo' => 0,                                      //  Birth Registration No - English
                'pPassport' => !empty($basicAppInfo->ceo_passport_no) ? $basicAppInfo->ceo_passport_no : 0,
                'ppre_details' => $tradeLicence->business_address,         //  Present Address Detail - English
                'ppre_details_bn' => $tradeLicence->business_address,      //  Present Address Detail - English

                /* <not mandatory> */
                'ppre_holding_no' => $tradeLicence->business_holding,      //  = 0 Present Holding No (English) *not mandatory
                'ppre_holding_no_bn' => $tradeLicence->business_holding,   //  = 0 Present Holding No (English) *not mandatory
                'ppre_road' => $tradeLicence->business_road,            //  = 0 Present Road (English) *not mandatory
                'ppre_road_bn' => $tradeLicence->business_road,         //  = 0 Present Road (English) *not mandatory
                'pper_holding_no' => $tradeLicence->business_holding,      //  = 0 Permanent Holding No (English) *not mandatory
                'pper_holding_no_bn' => $tradeLicence->business_holding,   //  = 0 Permanent Holding No (English) *not mandatory
                'pper_road' => $tradeLicence->business_road,            //  = 0 Permanent road *not mandatory
                'pper_road_bn' => $tradeLicence->business_road,         //  = 0 Permanent road *not mandatory
                /* </not mandatory> */

                'ppre_village' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),          //  Present Village - English
                'ppre_village_bn' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),        //  Present Village - English
                'ppre_post_code' => $basicAppInfo->office_post_code,                                                //  Present Post Code - English
                'ppre_post_code_bn' => $basicAppInfo->office_post_code,                                             //  Present Post Code - English
                'ppre_division' => DccAreaInfo::getDccAddressId($basicAppInfo->office_division_id, 1),      //  Present Division Code
                'ppre_district' => DccAreaInfo::getDccAddressId($basicAppInfo->office_district_id, 2),      //  Present District Code
                'ppre_upazilla' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),         //  Present Thana Code
                'ppre_upazilla2' => $basicAppInfo->office_thana_id,         //  Present Thana Code

                'pper_details' => $tradeLicence->business_address,         //  Permanent address details
                'pper_details_bn' => $tradeLicence->business_address,      //  Permanent address details
                'pper_village' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),         // Permanent village
                'pper_village_bn' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),      // Permanent village
                'pper_post_code' => $basicAppInfo->office_post_code,              // Permanent post code
                'pper_post_code_bn' => $basicAppInfo->office_post_code,           // Permanent post code
                'pper_division' => DccAreaInfo::getDccAddressId($basicAppInfo->office_division_id, 1),           // Permanent Division Code
                'pper_district' => DccAreaInfo::getDccAddressId($basicAppInfo->office_district_id, 2),           // Permanent District COde
                'pper_upazilla' => DccAreaInfo::getDccAddressId($basicAppInfo->office_thana_id, 3),           // Permanent Thana Code
                //'p_img_name' => $tradeLicence->applicant_pic,                                       // applicant image name not mentatory
                'p_img_name' => 'queue-pro.png',                                       // applicant image name not mentatory
                //'img_path' => asset('/users/upload/'.$tradeLicence->applicant_pic),             // application image downloadable path
                'img_path' => 'https://batworld.com/wp-content/uploads/2017/12/queue-pro.png',             // application image downloadable path

                // ------------- </ profile update > ----------------

                // ------------- < license data > ----------------

                'pInstId' => 2,                                             // = 2 Permanent Thana Code Institute id
                'pBusiName' => $tradeLicence->business_name,                // Business Name= english
                'pBusiNameBn' => $tradeLicence->business_name,              // Business Name= english
                'pHoldingNo' => $tradeLicence->business_holding,            // Holding Number= english
                'pHoldingNoBn' => $tradeLicence->business_holding,          // Holding Number= english
                'pBusAddr' => $tradeLicence->business_address,              // Business Address= english
                'pBusAddrBn' => $tradeLicence->business_address,            // Business Address= english
                'pBusiArea' => $tradeLicence->business_area,                // Business area= english
                'pBusiward' => $tradeLicence->business_ward,                // Business area= english
                'pBStDate' => date('d/m/Y', strtotime($tradeLicence->business_start_date)),           // Business start date must be oracle date format
                'pBusiType' => $tradeLicence->business_nature,              // Business type code 1. Limited Company 2. Other Single

                'pAuthCapital' => $tradeLicence->authorised_capital,        // Authorized Capital (Number)
                'pPaidUpCapital' => $tradeLicence->paidup_capital,          // Paid Up Capital (Number)

                'pBusiCat' => 0,                                            // = 0 Always zero, From Mr. Bellal. Now vendor only calculate pSubCats field
                'pBusiSCat' => 0,                                           // = 0
                'pRetailWs' => $tradeLicence->business_activity_type,       // = 1 (Retailer),  2 (wholesaler)
                'pBusiPlace' => $tradeLicence->business_plot_type,          // = 1 (Govertment) , 2 (Private)
                'pMarket' => 0,                                             // = 0 instructed to give only 0

                /* <not mandatory> */
                'pShopNo' => 0,                                             //  = 0 Shop Number must be 0.
                /* </not mandatory> */

                'pSbHeight' => intval($tradeLicence->business_signboard_height),        // SignBoard Height (integer)
                'pSbWidth' => intval($tradeLicence->business_signboard_width),          // SignBoard Width (integer)
                'pForYears' => 1,                                                       // = 1
                'pSubCats' => $tradeLicence->business_sub_category,                     // Business Sub-Category code
                'pIsFactory' => ($tradeLicence->business_factory == 'Yes') ? 1 : 0,     // = 0 (No), 1 (yes)
                'pIsChemical' => ($tradeLicence->business_chemical == 'Yes') ? 1 : 0,   // = 0 (No), 1 (yes)
                'pIsGovPlot' => ($tradeLicence->business_plot_type == 1) ? 1 : 0,   // = 0 (No), 1 (yes)
                'pBusiDesc' => $tradeLicence->business_details,                     // Business Details - English
                'pUsrAcptnce' => 1,                                                 // = 1
                'pBusRoad' => $tradeLicence->business_road,                         // Business Road - English
                'pBusRoadBn' => $tradeLicence->business_road,                       // Business Road - English
                'pIsCommPlot' => ($tradeLicence->business_plot_category == 1) ? 1 : 0,  // 0 (No), 1 (yes) is commercial plot
                'pMrktFlrNo' => 0,                                                  //  = 0 Market Floor must be 0

                // ------------- </ license data > ----------------

                // ---------------- <  payment > ------------------

                'pOldLicNo' => 0,               // = 0 its not a Old trade license no
                'pLicenseFees' => $tradeLicence->fees,            // //license fees provided by api
                'pSBFees' => (intval($tradeLicence->business_signboard_height) * intval($tradeLicence->business_signboard_width)) * 80,                 // //signboard fees: (Height * Width) * 80, -	Example: (("pSbHeight" * "pSbWidth") * 80)
                'pIncomeTax' => $tradeLicence->tax,              // = 0 income tax
                'pSurcharge' => 0,              // = 0  surcharge
                'pVAT' => $tradeLicence->vat,                    // //Not "0", VAT (15%): (TotalFees + SBFees) * .15, -	Example: (("pLicenseFees" + "pSBFees") * .15)
                'pCcSlipNo' => 0,               // = 0
                'pBankSlipNo' => 0,             // = 0
                'pPayDate' => date('d/m/Y'),   // Sysdate

                // ---------------- </  payment > ------------------
                'pLang' => 1,                   // = 1 (English), 2 (Bangla)
                'pLoginUser' => self::$bidaUserId,              // = BIDA User ID = 38 in vendor table
            ];


            $tradeLicenceRequest->status = 0;
            $tradeLicenceRequest->file_submit_status = 0;
            $tradeLicenceRequest->has_certificate = 0;
            $tradeLicenceRequest->process_type_id = $processTypeId;
            $tradeLicenceRequest->request = json_encode($tradeLicenceSubmitApi, JSON_UNESCAPED_SLASHES);
            $tradeLicenceRequest->processing_at = date('Y-m-d h:i:s');
            $tradeLicenceRequest->is_deleted = 0;

            $success = $tradeLicenceRequest->save();

            if ($success) {
                return ['success' => true];
            }
            return ['success' => false, 'message' => "Sorry Can not store the json [TLAPI-1001]"];

        } catch (\Exception $e) {

            Log::error('TLApiFormatJsonSaveController-Catch: ' . $e->getMessage() . ' ' .$e->getTraceAsString(). $e->getFile() . ' ' . $e->getLine() . ' [TLC-1052]');
            return ['success' => false, 'message' => CommonFunction::showErrorPublic($e->getMessage()) . "[TLAPI-1002]"];
        }
    }
}
