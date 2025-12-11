<?php

namespace App\Modules\Web\Controllers;

use App\Modules\BidaRegistrationAmendment\Models\BidaRegistrationAmendment;
use Exception;
use App\BRCommonPool;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Modules\ProcessPath\Models\ProcessList;

class OldDataUpdateController extends Controller
{
    public function brCommonPoolList()
    {
        try {

            DB::beginTransaction();

            // get duplicate list
            $duplicateCompanyIds = DB::table('br_common_pool as bcp')
                ->join('process_list as p', 'p.company_id', '=', 'bcp.company_id')
                ->select(
                    'bcp.id',
                    'bcp.company_id',
                    'bcp.br_tracking_no',
                    'bcp.bra_tracking_no',
                    'bcp.ref_app_tracking_no',
                    'bcp.ref_app_approve_date',
                    'bcp.project_name'
                )
                ->whereIn('bcp.company_id', function ($query) {
                    $query->select('company_id')
                        ->from('br_common_pool')
                        ->groupBy('company_id', 'project_name')
                        ->havingRaw('COUNT(*) > 1');
                })
                ->where('bcp.br_tracking_no', '!=', '0')
                ->where('bcp.bra_tracking_no', '!=', '0')
                ->where('bcp.company_id', '!=', '0')
                ->where('p.status_id', 25)
                ->groupBy('bcp.company_id')
                ->limit(1)
                ->get();

            // ensure this query will be return br_tracking_no and bra_tracking_no

            if (empty($duplicateCompanyIds) || empty($duplicateCompanyIds[0])) {
                echo 'No duplicate data found';
            }

            $actionData =  $duplicateCompanyIds[0];

            $lastApprovedBraData = ProcessList::join('bra_apps as a', 'a.id', '=', 'process_list.ref_id')
                ->where('process_list.company_id', $actionData->company_id)
                ->where('process_list.status_id', 25)
                ->where('process_list.process_type_id', 12)
                ->where(function ($query) use ($actionData) {
                    $query->where('a.n_project_name', $actionData->project_name)
                        ->orWhere('a.project_name', $actionData->project_name);
                })
                ->orderBy('process_list.completed_date', 'desc')
                ->first([
                    'process_list.ref_id',
                    'process_list.tracking_no',
                    'process_list.process_type_id',
                ]);

            if (!$lastApprovedBraData) {
                dd('No approved data found');
            }

            // find out br_common_pool_id which has br_tracking_no
            $brData = DB::table('br_common_pool')->where('company_id', $actionData->company_id)
                ->where('br_tracking_no', $actionData->br_tracking_no)
                ->first();

            if (empty($brData)) {
                dd('No approved data found');
            }

            // update this row using last bra data
            $this->braDataStore($brData->id, $lastApprovedBraData);

            // remove other br_common_pool_id
            DB::table('br_common_pool')
                ->where('company_id', $actionData->company_id)
                ->where('project_name', $actionData->project_name)
                ->where('id', '!=', $brData->id)
                ->delete();

            DB::commit();

            echo 'Success';

        } catch (Exception $e) {
            DB::rollback();
            Log::error('OldDataUpdateController: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WPNC-1011]');
        }

    }

    private function braDataStore($br_common_pool_id, $lastApprovedBraData)
    {
        $braData = BidaRegistrationAmendment::where('id', $lastApprovedBraData->ref_id)->first();

        $appData = BRCommonPool::find($br_common_pool_id);
        $appData->bra_tracking_no = $lastApprovedBraData->tracking_no;

        $appData->ref_app_tracking_no = $braData->ref_app_tracking_no;
        $appData->ref_app_approve_date = $braData->ref_app_approve_date;
        $appData->bra_approved_date = $braData->approved_date;

        // Company information
        $appData->company_id = $braData->company_id;
        $appData->company_name = !empty($braData->n_company_name) ? $braData->n_company_name : $braData->company_name;
        $appData->company_name_bn = !empty($braData->n_company_name_bn) ? $braData->n_company_name_bn : $braData->company_name_bn;
        $appData->organization_type_id = !empty($braData->n_organization_type_id) ? $braData->n_organization_type_id : $braData->organization_type_id;
        $appData->organization_status_id = !empty($braData->n_organization_status_id) ? $braData->n_organization_status_id : $braData->organization_status_id;
        $appData->ownership_status_id = !empty($braData->n_ownership_status_id) ? $braData->n_ownership_status_id : $braData->ownership_status_id;
        $appData->country_of_origin_id = !empty($braData->n_country_of_origin_id) ? $braData->n_country_of_origin_id : $braData->country_of_origin_id;
        $appData->project_name = !empty($braData->n_project_name) ? $braData->n_project_name : $braData->project_name;

        //Business class
        $appData->section_id = !empty($braData->n_section_id) ? $braData->n_section_id : $braData->section_id;
        $appData->division_id = !empty($braData->n_division_id) ? $braData->n_division_id : $braData->division_id;
        $appData->group_id = !empty($braData->n_group_id) ? $braData->n_group_id : $braData->group_id;
        $appData->class_id = !empty($braData->n_class_id) ? $braData->n_class_id : $braData->class_id;
        $appData->class_code = !empty($braData->n_class_code) ? $braData->n_class_code : $braData->class_code;
        $appData->sub_class_id = isset($braData->n_sub_class_id) ? $braData->n_sub_class_id : $braData->sub_class_id;
        $appData->other_sub_class_code = !empty($braData->n_other_sub_class_code) ? $braData->n_other_sub_class_code : $braData->other_sub_class_code;
        $appData->other_sub_class_name = !empty($braData->n_other_sub_class_name) ? $braData->n_other_sub_class_name : $braData->other_sub_class_name;
        $appData->other_sub_class_name = !empty($braData->n_other_sub_class_name) ? $braData->n_other_sub_class_name : $braData->other_sub_class_name;

        // CEO information
        $appData->ceo_full_name = !empty($braData->n_ceo_full_name) ? $braData->n_ceo_full_name : $braData->ceo_full_name;
        $appData->ceo_dob = !empty($braData->n_ceo_dob) ? $braData->n_ceo_dob : $braData->ceo_dob;
        $appData->ceo_spouse_name = !empty($braData->n_ceo_spouse_name) ? $braData->n_ceo_spouse_name : $braData->ceo_spouse_name;
        $appData->ceo_designation = !empty($braData->n_ceo_designation) ? $braData->n_ceo_designation : $braData->ceo_designation;
        $appData->ceo_country_id = !empty($braData->n_ceo_country_id) ? $braData->n_ceo_country_id : $braData->ceo_country_id;
        $appData->ceo_district_id = !empty($braData->n_ceo_district_id) ? $braData->n_ceo_district_id : $braData->ceo_district_id;
        $appData->ceo_thana_id = !empty($braData->n_ceo_thana_id) ? $braData->n_ceo_thana_id : $braData->ceo_thana_id;
        $appData->ceo_post_code = !empty($braData->n_ceo_post_code) ? $braData->n_ceo_post_code : $braData->ceo_post_code;
        $appData->ceo_address = !empty($braData->n_ceo_address) ? $braData->n_ceo_address : $braData->ceo_address;
        $appData->ceo_telephone_no = !empty($braData->n_ceo_telephone_no) ? $braData->n_ceo_telephone_no : $braData->ceo_telephone_no;
        $appData->ceo_mobile_no = !empty($braData->n_ceo_mobile_no) ? $braData->n_ceo_mobile_no : $braData->ceo_mobile_no;
        $appData->ceo_fax_no = !empty($braData->n_ceo_fax_no) ? $braData->n_ceo_fax_no : $braData->ceo_fax_no;
        $appData->ceo_email = !empty($braData->n_ceo_email) ? $braData->n_ceo_email : $braData->ceo_email;
        $appData->ceo_father_name = !empty($braData->n_ceo_father_name) ? $braData->n_ceo_father_name : $braData->ceo_father_name;
        $appData->ceo_mother_name = !empty($braData->n_ceo_mother_name) ? $braData->n_ceo_mother_name : $braData->ceo_mother_name;
        $appData->ceo_nid = !empty($braData->n_ceo_nid) ? $braData->n_ceo_nid : $braData->ceo_nid;
        $appData->ceo_passport_no = !empty($braData->n_ceo_passport_no) ? $braData->n_ceo_passport_no : $braData->ceo_passport_no;
        $appData->ceo_city = !empty($braData->n_ceo_city) ? $braData->n_ceo_city : $braData->ceo_city;
        $appData->ceo_state = !empty($braData->n_ceo_state) ? $braData->n_ceo_state : $braData->ceo_state;
        $appData->ceo_gender = !empty($braData->n_ceo_gender) ? $braData->n_ceo_gender : $braData->ceo_gender;

        // Office Address
        $appData->office_division_id = !empty($braData->n_office_division_id) ? $braData->n_office_division_id : $braData->office_division_id;
        $appData->office_district_id = !empty($braData->n_office_district_id) ? $braData->n_office_district_id : $braData->office_district_id;
        $appData->office_thana_id = !empty($braData->n_office_thana_id) ? $braData->n_office_thana_id : $braData->office_thana_id;
        $appData->office_post_office = !empty($braData->n_office_post_office) ? $braData->n_office_post_office : $braData->office_post_office;
        $appData->office_post_code = !empty($braData->n_office_post_code) ? $braData->n_office_post_code : $braData->office_post_code;
        $appData->office_address = !empty($braData->n_office_address) ? $braData->n_office_address : $braData->office_address;
        $appData->office_telephone_no = !empty($braData->n_office_telephone_no) ? $braData->n_office_telephone_no : $braData->office_telephone_no;
        $appData->office_mobile_no = !empty($braData->n_office_mobile_no) ? $braData->n_office_mobile_no : $braData->office_mobile_no;
        $appData->office_fax_no = !empty($braData->n_office_fax_no) ? $braData->n_office_fax_no : $braData->office_fax_no;
        $appData->office_email = !empty($braData->n_office_email) ? $braData->n_office_email : $braData->office_email;

        // Factory Address
        $appData->factory_district_id = !empty($braData->n_factory_district_id) ? $braData->n_factory_district_id : $braData->factory_district_id;
        $appData->factory_thana_id = !empty($braData->n_factory_thana_id) ? $braData->n_factory_thana_id : $braData->factory_thana_id;
        $appData->factory_post_office = !empty($braData->n_factory_post_office) ? $braData->n_factory_post_office : $braData->factory_post_office;
        $appData->factory_post_code = !empty($braData->n_factory_post_code) ? $braData->n_factory_post_code : $braData->factory_post_code;
        $appData->factory_address = !empty($braData->n_factory_address) ? $braData->n_factory_address : $braData->factory_address;
        $appData->factory_telephone_no = !empty($braData->n_factory_telephone_no) ? $braData->n_factory_telephone_no : $braData->factory_telephone_no;
        $appData->factory_mobile_no = !empty($braData->n_factory_mobile_no) ? $braData->n_factory_mobile_no : $braData->factory_mobile_no;
        $appData->factory_fax_no = !empty($braData->n_factory_fax_no) ? $braData->n_factory_fax_no : $braData->factory_fax_no;

        // Project status
        $appData->project_status_id = !empty($braData->n_project_status_id) ? $braData->n_project_status_id : $braData->project_status_id;

        // Date of commercial operation
        $appData->commercial_operation_date = !empty($braData->n_commercial_operation_date) ? $braData->n_commercial_operation_date : $braData->commercial_operation_date;

        // Sales
        $appData->local_sales = $braData->n_local_sales != null ? $braData->n_local_sales : $braData->local_sales;
        $appData->foreign_sales = !empty($braData->n_foreign_sales) ? $braData->n_foreign_sales : $braData->foreign_sales;
        // $appData->direct_export = $braData->n_direct_export != null ? $braData->n_direct_export : $braData->direct_export;
        // $appData->deemed_export = $braData->n_deemed_export != null ? $braData->n_deemed_export : $braData->deemed_export;
        $appData->total_sales = !empty($braData->n_total_sales) ? $braData->n_total_sales : $braData->total_sales;

        // Manpower of the organization
        $appData->local_male = !empty($braData->n_local_male) ? $braData->n_local_male : $braData->local_male;
        $appData->local_female = !empty($braData->n_local_female) ? $braData->n_local_female : $braData->local_female;
        $appData->local_total = !empty($braData->n_local_total) ? $braData->n_local_total : $braData->local_total;
        $appData->foreign_male = !empty($braData->n_foreign_male) ? $braData->n_foreign_male : $braData->foreign_male;
        $appData->foreign_female = !empty($braData->n_foreign_female) ? $braData->n_foreign_female : $braData->foreign_female;
        $appData->foreign_total = !empty($braData->n_foreign_total) ? $braData->n_foreign_total : $braData->foreign_total;
        $appData->manpower_total = !empty($braData->n_manpower_total) ? $braData->n_manpower_total : $braData->manpower_total;
        $appData->manpower_local_ratio = !empty($braData->n_manpower_local_ratio) ? $braData->n_manpower_local_ratio : $braData->manpower_local_ratio;
        $appData->manpower_foreign_ratio = !empty($braData->n_manpower_foreign_ratio) ? $braData->n_manpower_foreign_ratio : $braData->manpower_foreign_ratio;
        $appData->manpower_foreign_ratio = !empty($braData->n_manpower_foreign_ratio) ? $braData->n_manpower_foreign_ratio : $braData->manpower_foreign_ratio;

        if (!empty($braData->n_local_land_ivst) || !empty($braData->n_local_building_ivst) || !empty($braData->n_local_machinery_ivst) || !empty($braData->n_local_others_ivst) ||
            !empty($braData->n_local_others_ivst) || !empty($braData->n_local_wc_ivst) || !empty($braData->n_total_fixed_ivst_million) || !empty($braData->n_total_fixed_ivst) ||
            !empty($braData->n_usd_exchange_rate) || !empty($braData->n_total_fee) || !empty($braData->n_finance_src_loc_equity_1) || !empty($braData->n_finance_src_foreign_equity_1) ||
            !empty($braData->n_finance_src_loc_total_equity_1) || !empty($braData->n_finance_src_loc_loan_1) || !empty($braData->n_finance_src_foreign_loan_1) ||
            !empty($braData->n_finance_src_total_loan) || !empty($braData->n_finance_src_loc_total_financing_m) || !empty($braData->n_finance_src_loc_total_financing_1)) {

            //Investment
            $appData->local_land_ivst = empty($braData->n_local_land_ivst) ? null : $braData->n_local_land_ivst;
            $appData->local_land_ivst_ccy = empty($braData->n_local_land_ivst_ccy) ? null : $braData->n_local_land_ivst_ccy;
            $appData->local_building_ivst = empty($braData->n_local_building_ivst) ? null : $braData->n_local_building_ivst;
            $appData->local_building_ivst_ccy = empty($braData->n_local_building_ivst_ccy) ? null : $braData->n_local_building_ivst_ccy;
            $appData->local_machinery_ivst = empty($braData->n_local_machinery_ivst) ? null : $braData->n_local_machinery_ivst;
            $appData->local_machinery_ivst_ccy = empty($braData->n_local_machinery_ivst_ccy) ? null : $braData->n_local_machinery_ivst_ccy;
            $appData->local_others_ivst = empty($braData->n_local_others_ivst) ? null : $braData->n_local_others_ivst;
            $appData->local_others_ivst_ccy = empty($braData->n_local_others_ivst_ccy) ? null : $braData->n_local_others_ivst_ccy;
            $appData->local_wc_ivst = empty($braData->n_local_wc_ivst) ? null : $braData->n_local_wc_ivst;
            $appData->local_wc_ivst_ccy = empty($braData->n_local_wc_ivst_ccy) ? null : $braData->n_local_wc_ivst_ccy;
            $appData->total_fixed_ivst_million = empty($braData->n_total_fixed_ivst_million) ? null : $braData->n_total_fixed_ivst_million;
            $appData->total_fixed_ivst = empty($braData->n_total_fixed_ivst) ? null : $braData->n_total_fixed_ivst;
            $appData->usd_exchange_rate = empty($braData->n_usd_exchange_rate) ? null : $braData->n_usd_exchange_rate;
            $appData->total_fee = empty($braData->n_total_fee) ? null : $braData->n_total_fee;

            //Source of finance
            $appData->finance_src_loc_equity_1 = empty($braData->n_finance_src_loc_equity_1) ? 0 : $braData->n_finance_src_loc_equity_1;
            $appData->finance_src_foreign_equity_1 = empty($braData->n_finance_src_foreign_equity_1) ? 0 : $braData->n_finance_src_foreign_equity_1;
            $appData->finance_src_loc_total_equity_1 = empty($braData->n_finance_src_loc_total_equity_1) ? 0 : $braData->n_finance_src_loc_total_equity_1;
            $appData->finance_src_loc_loan_1 = empty($braData->n_finance_src_loc_loan_1) ? 0 : $braData->n_finance_src_loc_loan_1;
            $appData->finance_src_foreign_loan_1 = empty($braData->n_finance_src_foreign_loan_1) ? 0 : $braData->n_finance_src_foreign_loan_1;
            $appData->finance_src_total_loan = empty($braData->n_finance_src_total_loan) ? 0 : $braData->n_finance_src_total_loan;
            $appData->finance_src_loc_total_financing_m = empty($braData->n_finance_src_loc_total_financing_m) ? 0 : $braData->n_finance_src_loc_total_financing_m;
            $appData->finance_src_loc_total_financing_1 = empty($braData->n_finance_src_loc_total_financing_1) ? 0 : $braData->n_finance_src_loc_total_financing_1;
        }else {
            //Investment
            $appData->local_land_ivst = empty($braData->local_land_ivst) ? null : $braData->local_land_ivst;
            $appData->local_land_ivst_ccy = empty($braData->local_land_ivst_ccy) ? null : $braData->local_land_ivst_ccy;
            $appData->local_building_ivst = empty($braData->local_building_ivst) ? null : $braData->local_building_ivst;
            $appData->local_building_ivst_ccy = empty($braData->local_building_ivst_ccy) ? null : $braData->local_building_ivst_ccy;
            $appData->local_machinery_ivst = empty($braData->local_machinery_ivst) ? null : $braData->local_machinery_ivst;
            $appData->local_machinery_ivst_ccy = empty($braData->local_machinery_ivst_ccy) ? null : $braData->local_machinery_ivst_ccy;
            $appData->local_others_ivst = empty($braData->local_others_ivst) ? null : $braData->local_others_ivst;
            $appData->local_others_ivst_ccy = empty($braData->local_others_ivst_ccy) ? null : $braData->local_others_ivst_ccy;
            $appData->local_wc_ivst = empty($braData->local_wc_ivst) ? null : $braData->local_wc_ivst;
            $appData->local_wc_ivst_ccy = empty($braData->local_wc_ivst_ccy) ? null : $braData->local_wc_ivst_ccy;
            $appData->total_fixed_ivst_million = empty($braData->total_fixed_ivst_million) ? null : $braData->total_fixed_ivst_million;
            $appData->total_fixed_ivst = empty($braData->total_fixed_ivst) ? null : $braData->total_fixed_ivst;
            $appData->usd_exchange_rate = empty($braData->usd_exchange_rate) ? null : $braData->usd_exchange_rate;
            $appData->total_fee = empty($braData->total_fee) ? null : $braData->total_fee;

            //Source of finance
            $appData->finance_src_loc_equity_1 = empty($braData->finance_src_loc_equity_1) ? 0 : $braData->finance_src_loc_equity_1;
            $appData->finance_src_foreign_equity_1 = empty($braData->finance_src_foreign_equity_1) ? 0 : $braData->finance_src_foreign_equity_1;
            $appData->finance_src_loc_total_equity_1 = empty($braData->finance_src_loc_total_equity_1) ? 0 : $braData->finance_src_loc_total_equity_1;
            $appData->finance_src_loc_loan_1 = empty($braData->finance_src_loc_loan_1) ? 0 : $braData->finance_src_loc_loan_1;
            $appData->finance_src_foreign_loan_1 = empty($braData->finance_src_foreign_loan_1) ? 0 : $braData->finance_src_foreign_loan_1;
            $appData->finance_src_total_loan = empty($braData->finance_src_total_loan) ? 0 : $braData->finance_src_total_loan;
            $appData->finance_src_loc_total_financing_m = empty($braData->finance_src_loc_total_financing_m) ? 0 : $braData->finance_src_loc_total_financing_m;
            $appData->finance_src_loc_total_financing_1 = empty($braData->finance_src_loc_total_financing_1) ? 0 : $braData->finance_src_loc_total_financing_1;
        }

        // Public utility service
        if (!empty($braData->n_public_land) || !empty($braData->n_public_electricity) || !empty($braData->n_public_gas) || !empty($braData->n_public_telephone) ||
            !empty($braData->n_public_road) || !empty($braData->n_public_water) || !empty($braData->n_public_drainage) || !empty($braData->n_public_others)) {

            $appData->public_land = empty($braData->n_public_land) ? 0 : $braData->n_public_land;
            $appData->public_electricity = empty($braData->n_public_electricity) ? 0 : $braData->n_public_electricity;
            $appData->public_gas = empty($braData->n_public_gas) ? 0 : $braData->n_public_gas;
            $appData->public_telephone = empty($braData->n_public_telephone) ? 0 : $braData->n_public_telephone;
            $appData->public_road = empty($braData->n_public_road) ? 0 : $braData->n_public_road;
            $appData->public_water = empty($braData->n_public_water) ? 0 : $braData->n_public_water;
            $appData->public_drainage = empty($braData->n_public_drainage) ? 0 : $braData->n_public_drainage;
            $appData->public_others = empty($braData->n_public_others) ? 0 : $braData->n_public_others;
        } else {
            $appData->public_land = empty($braData->public_land) ? 0 : $braData->public_land;
            $appData->public_electricity = empty($braData->public_electricity) ? 0 : $braData->public_electricity;
            $appData->public_gas = empty($braData->public_gas) ? 0 : $braData->public_gas;
            $appData->public_telephone = empty($braData->public_telephone) ? 0 : $braData->public_telephone;
            $appData->public_road = empty($braData->public_road) ? 0 : $braData->public_road;
            $appData->public_water = empty($braData->public_water) ? 0 : $braData->public_water;
            $appData->public_drainage = empty($braData->public_drainage) ? 0 : $braData->public_drainage;
            $appData->public_others = empty($braData->public_others) ? 0 : $braData->public_others;
        }

        //Trade licence details
        $appData->trade_licence_num = !empty($braData->n_trade_licence_num) ? $braData->n_trade_licence_num : $braData->trade_licence_num;
        $appData->trade_licence_issuing_authority = !empty($braData->n_trade_licence_issuing_authority) ? $braData->n_trade_licence_issuing_authority : $braData->trade_licence_issuing_authority;

        //Tin
        $appData->tin_number = !empty($braData->n_tin_number) ? $braData->n_tin_number : $braData->tin_number;

        //Description of machinery and equipment
        $appData->machinery_local_qty = !empty($braData->n_machinery_local_qty) ? $braData->n_machinery_local_qty : $braData->machinery_local_qty;
        $appData->machinery_local_price_bdt = !empty($braData->n_machinery_local_price_bdt) ? $braData->n_machinery_local_price_bdt : $braData->machinery_local_price_bdt;
        $appData->imported_qty = !empty($braData->n_imported_qty) ? $braData->n_imported_qty : $braData->imported_qty;
        $appData->imported_qty_price_bdt = !empty($braData->n_imported_qty_price_bdt) ? $braData->n_imported_qty_price_bdt : $braData->imported_qty_price_bdt;
        $appData->total_machinery_price = !empty($braData->n_total_machinery_price) ? $braData->n_total_machinery_price : $braData->total_machinery_price;
        $appData->total_machinery_qty = !empty($braData->n_total_machinery_qty) ? $braData->n_total_machinery_qty : $braData->total_machinery_qty;


        //Description of raw &amp; packing materials
        $appData->local_description = !empty($braData->n_local_description) ? $braData->n_local_description : $braData->local_description;
        $appData->imported_description = !empty($braData->n_imported_description) ? $braData->n_imported_description : $braData->imported_description;


        // Information of (Chairman/ Managing Director/ Or Equivalent)
        $appData->g_full_name = !empty($braData->n_g_full_name) ? $braData->n_g_full_name : $braData->g_full_name;
        $appData->g_designation = !empty($braData->n_g_designation) ? $braData->n_g_designation : $braData->g_designation;
        $appData->g_signature = !empty($braData->n_g_signature) ? $braData->n_g_signature : $braData->g_signature;

        // Why do you want to BIDA Registration Amendment?
        $appData->major_remarks = $braData->major_remarks;

        //Authorized Person Information
        $appData->accept_terms = $braData->accept_terms;

        $appData->sf_payment_id = $braData->sf_payment_id;
        $appData->gf_payment_id = $braData->gf_payment_id;
        $appData->save();

        return $appData->id;
    }
}