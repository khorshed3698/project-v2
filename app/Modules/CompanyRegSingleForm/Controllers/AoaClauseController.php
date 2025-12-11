<?php

namespace App\Modules\CompanyRegSingleForm\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\CompanyRegSingleForm\Models\AoaInfo;
use App\Modules\CompanyRegSingleForm\Models\CompanyRegSingleForm;
use App\Modules\CompanyRegSingleForm\Models\ListSubscriber;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\Users\Models\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class AoaClauseController extends Controller
{
    protected $process_type_id;
    protected $aclName;

    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 134;
        $this->aclName = 'CompanyRegSingleForm';
    }

    public function saveAoaCloause(Request $request)
    {
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->working_company_id;


        // Validation Rules when application submitted
        $rules = [];
        $messages = [];

        $rules['clause_title_id'] = 'required';
        /*$rules['clause'] = 'required';*/
        $clause = explode('@',$request->get('clause_title_id'));
        $clauseTitleId = $clause[0];
        $clauseTitle = $clause[1];

        $this->validate($request, $rules, $messages);

        try {
            DB::beginTransaction();

            $clause_for_rjsc = "";
            $processData = ProcessList::firstOrNew(['process_type_id' => $this->process_type_id, 'ref_id' => $app_id]);
            $appData = new AoaInfo();
            $appData->clause_title =$clauseTitle;

            $sequenceExits = AoaInfo::where('rjsc_nr_app_id', $app_id)->orderby('sequence', 'desc')->take(1)->first(['sequence']);

            if ($sequenceExits) {
                $appData->sequence = ($sequenceExits->sequence + 1);
            } else {
                $appData->sequence = 1;
            }
            $appData->rjsc_nr_app_id = $app_id;
            if (CommonFunction::asciiCharCheck($clauseTitleId)){
                $appData->clause_title_id = $clauseTitleId;
            }else{
                Session::flash('error', 'non-ASCII Characters found in clause_title_id [AOA-1001]');
                return Redirect::to(URL::previous() . "#step13");
            }
            $nrData = CompanyRegSingleForm::find($app_id);
            if (CommonFunction::asciiCharCheck($request->get('clause'))){
                $clause_for_rjsc = $request->get('clause');
            }else{
                Session::flash('error', 'non-ASCII Characters found in clause [AOA-1001]');
                return Redirect::to(URL::previous() . "#step13");
            }

            if ($clauseTitleId == 19) { // for Chairman

                $existing_chairman_data = AoaInfo::where('clause_title_id', 19 )->where('rjsc_nr_app_id', $app_id)->get();
                if (count($existing_chairman_data) > 0){
                    if (CommonFunction::asciiCharCheck($request->get('clause'))){
                        $appData->clause  = $request->get('clause');
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }

                }else{
                    $cluase = ListSubscriber::where('app_id', $app_id)->where('position', 4)->first(['corporation_body_name']);
                    $text = (isset($cluase) ? '<b>' . $cluase->corporation_body_name . '</b>' : ' ') . ' ' . $request->get('clause');
                    $appData->clause = $text;
                }
            }  elseif ($clauseTitleId == 20) { // for md managing director

                $existing_chairman_data = AoaInfo::where('clause_title_id', 20 )->where('rjsc_nr_app_id', $app_id)->get();
                if (count($existing_chairman_data) > 0){
                    if (CommonFunction::asciiCharCheck($request->get('clause'))){
                        $appData->clause  = $request->get('clause');
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }

                }else{
                    $cluase = ListSubscriber::where('app_id', $app_id)->where('position', 3)->first(['corporation_body_name']);
                    $text = (isset($cluase) ? '<b>' . $cluase->corporation_body_name . '</b>' : ' ') . ' ' . $request->get('clause');
                    $appData->clause = $text;
                }
            }elseif ($clauseTitleId == 15) { // for Directors
                $existing_director_data = AoaInfo::where('clause_title_id', 15 )->where('rjsc_nr_app_id', $app_id)->get();
                if (count($existing_director_data) > 0){
                    $appData->clause = $request->get('clause');
                }else{
                    $predifined_text = "Until otherwise deterined by the Company in General Meeting the number of Directors shall not be less than $nrData->minimum_no_of_directors (".CommonFunction::convert_number_to_words($nrData->minimum_no_of_directors).") and not more than $nrData->maximum_no_of_directors(".CommonFunction::convert_number_to_words($nrData->maximum_no_of_directors)."). The following persons shall be the first and permanent Directors of the Company unless any one of them voluntarily resigns the office or otherwise removed their form under the provisions of Section 108(1) of the Companis Act, 1994.";
                    $cluase = ListSubscriber::where('app_id', $app_id)->where('is_director', 1)->get(['corporation_body_name']);
//                    $text = $predifined_text.$request->get('clause');
                    $text = $predifined_text;
                    $text .= '<br/>';
                    if (isset($cluase) && count($cluase) > 0) {
                        foreach ($cluase as $name) {
                            $text .= '<b>' . $name->corporation_body_name . "</b> <br/>";
                        }
                    }
                    $appData->clause = $text;
                }

            }elseif ($clauseTitleId == 12) { // For Quorum (AGM)
                $predifined_text = "$nrData->quorum_agm_egm_num (".CommonFunction::convert_number_to_words($nrData->quorum_agm_egm_num).")";
                $existing_charman_data = AoaInfo::where('clause_title_id', 12 )->where('rjsc_nr_app_id', $app_id)->get();
                if (count($existing_charman_data) > 0){
                    if (CommonFunction::asciiCharCheck($request->get('clause'))){
                        $appData->clause  = $request->get('clause');
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }
                }
                else{
                    $text = $predifined_text." ".$request->get('clause');
                    $text .= '<br/>';
                    $appData->clause = $text;
                    if (CommonFunction::asciiCharCheck($text)){
                        $appData->clause  = $text;
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }
                }
            }
            elseif ($clauseTitleId == 13) { // For Quorum board meeting
                $predifined_text = "$nrData->q_directors_meeting_num (".CommonFunction::convert_number_to_words($nrData->q_directors_meeting_num).")";
                $existing_charman_data = AoaInfo::where('clause_title_id', 13 )->where('rjsc_nr_app_id', $app_id)->get();
                if (count($existing_charman_data) > 0){
                    if (CommonFunction::asciiCharCheck($request->get('clause'))){
                        $appData->clause = $request->get('clause');
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }

                }
                else{
                    $text = $predifined_text." ".$request->get('clause');
                    $text .= '<br/>';
                    if (CommonFunction::asciiCharCheck($text)){
                        $appData->clause = $text;
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }
                }
            }

            elseif ($clauseTitleId == 5) {// Share capital
                $existing_director_data = AoaInfo::where('clause_title_id', 5 )->where('rjsc_nr_app_id', $app_id)->get();

                if (count($existing_director_data) > 0){
                    if (CommonFunction::asciiCharCheck($request->get('clause'))){
                        $appData->clause = $request->get('clause');
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }

                }else{
                    $predifined_text = "The Authorized Share Capital of the Company is TK $nrData->authorize_capital (".CommonFunction::convert_number_to_words($nrData->authorize_capital).") divided
                    into $nrData->number_shares (".CommonFunction::convert_number_to_words($nrData->number_shares).") Ordinary Shares of Tk $nrData->value_of_each_share (".CommonFunction::convert_number_to_words($nrData->value_of_each_share).") each ";
//                    $text = $predifined_text.$request->get('clause');
                    $text = $predifined_text." ".$request->get('clause');
                    if (CommonFunction::asciiCharCheck($text)){
                        $appData->clause = $text;
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                        return Redirect::to(URL::previous() . "#step13");
                    }
                }

            }
            else {
                if (CommonFunction::asciiCharCheck($request->get('clause'))){
                    $appData->clause = $request->get('clause');
                }else{
                    Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
                    return Redirect::to(URL::previous() . "#step13");
                }

            }

//            dd($appData);

            if ($request->get('actionBtn') == "draft") {
                $processData->status_id = -1;
                $processData->desk_id = 0;
            } else {
                if ($processData->status_id == 5) { // For shortfall application re-submission
                    $getLastProcessInfo = ProcessHistory::where('process_id', $processData->id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
                    $processData->status_id = 2; // resubmit
                    $processData->desk_id = $getLastProcessInfo->desk_id;
                    $processData->process_desc = 'Re-submitted from applicant';
                } else {  // For new application submission
                    $processData->status_id = -1;
                    $processData->desk_id = 0; // 5 is Help Desk (For Licence Application Module)
                    $processData->submitted_at = date('Y-m-d H:i:s'); // application submitted Date
                }
            }
            $appData->clause_for_rjsc = $clause_for_rjsc;
            $appData->save();

            $processData->process_type_id = $this->process_type_id;
            $processData->process_desc = '';// for re-submit application
            $processData->company_id = $company_id;

            $jsonData['Applicant Name'] = CommonFunction::getUserFullName();
            $jsonData['Applicant Email'] = Auth::user()->user_email;
            $jsonData['Company Name'] = CommonFunction::getCompanyNameById($company_id);
            $processData['json_object'] = json_encode($jsonData);

            $processData->save();

            $appData->save();

            if ($processData->status_id == 0) {
                dd('Application status not found!');
            }

            /*    $sequence=NewReg::find($app_id);
                $sequence->sequence=14;
                $sequence->save();*/

            DB::commit();


            if ($request->get('actionBtn') != "draft" && ($processData->status_id == 2)) {
                $processData = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                    ->where('process_list.id', $processData->id)
                    ->first([
                        'process_type.name as process_type_name',
                        'process_type.process_supper_name',
                        'process_type.process_sub_name',
                        'process_list.*'
                    ]);
            }

            if ($processData->status_id == -1) {
                Session::flash('success', 'Successfully updated the Application!');
            } elseif ($processData->status_id == 1) {
                Session::flash('success', 'Successfully Application Submitted !');
            } elseif (in_array($processData->status_id, [2])) {
                Session::flash('success', 'Successfully Application Re-Submitted !');
            } else {
                Session::flash('error', 'Failed due to Application Status Conflict. Please try again later! [BI-1023]');
            }


            if ($request->get('app_id') && !empty($request->get('app_id'))) {

                return Redirect::to(URL::previous() . "#step13");
            }

            return Redirect::to(URL::previous() . "#step13");

        } catch (\Exception $e) {
            dd($e->getLine().$e->getMessage());
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getLine()) . ' [EAC-1060]');
            return redirect()->back()->withInput();
        }
    }

    public function updateAoaCloause(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            die('You have no access right! Please contact system administration for more information.');
        }
        $id = Encryption::decodeId($request->row_id);
        $clause_title_id = $request->get('clause_title_id');

        if (CommonFunction::asciiCharCheck($request->get('clause'))) {
            $clause = $request->get('clause');
        } else {
            Session::flash('error', 'non-ASCII Characters found in clause [AOA-1002]');
            return Redirect::to(URL::previous() . "#step13");
        }


        $updaeData = AoaInfo::where('id', $id)->update([
            'clause_title_id' => $clause_title_id,
            'clause' => $clause
        ]);

        if ($updaeData) {
            Session::flash('success', 'Data update Successfully');
        } else {
            Session::flash('error', 'Something Went Wrong');
        }
        return Redirect::to(URL::previous() . "#step13");

    }

    public function deleteAoaCloause(Request $request)
    {
        if (!ACL::getAccsessRight($this->aclName, '-E-')) {
            Session::flash('error', 'You have no access right! Contact with system admin for more information');
            return response()->json(['responseCode' => 1, 'redirectUrl' => URL::previous() . "#step13"]);
        }
        $row_id = Encryption::decodeId($request->row_id);
        $deleteRow = AoaInfo::where('id', $row_id)->delete();
        if ($deleteRow) {
            Session::flash('success', 'Data Deleted Successfully');
            $redirectUrl = URL::previous() . "#step13";
            return response()->json(['responseCode' => 1, 'redirectUrl' => $redirectUrl]);
        }
        Session::flash('error', 'Something went wrong');
        return response()->json(['responseCode' => 0]);
    }

    public function articleShow(Request $request)
    {

        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $nrData = CompanyRegSingleForm::find($app_id);

        if ($request->get('clause_title_id') == 19) { // for Chairman

            $existing_chairman_data = AoaInfo::where('clause_title_id', 19)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_chairman_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $cluase = ListSubscriber::where('app_id', $app_id)->where('position', 4)->first(['corporation_body_name']);
                $text = (isset($cluase) ? '<b>' . $cluase->corporation_body_name . '</b>' : ' ') . ' ' . $request->get('clause');
                $data = ['response' => 1, 'data' => $text];
            }
        } elseif ($request->get('clause_title_id') == 20) { // for md managing director

            $existing_chairman_data = AoaInfo::where('clause_title_id', 20)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_chairman_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $cluase = ListSubscriber::where('app_id', $app_id)->where('position', 3)->first(['corporation_body_name']);
                $text = (isset($cluase) ? '<b>' . $cluase->corporation_body_name . '</b>' : ' ') . ' ' . $request->get('clause');
                $data = ['response' => 1, 'data' => $text];
            }
        } elseif ($request->get('clause_title_id') == 15) {// for Directors
            $existing_director_data = AoaInfo::where('clause_title_id', 15)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_director_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $predifined_text = "Until otherwise deterined by the Company in General Meeting the number of Directors shall not be less than $nrData->minimum_no_of_directors (" . CommonFunction::convert_number_to_words($nrData->minimum_no_of_directors) . ") and not more than $nrData->maximum_no_of_directors(" . CommonFunction::convert_number_to_words($nrData->maximum_no_of_directors) . "). The following persons shall be the first and permanent Directors of the Company unless any one of them voluntarily resigns the office or otherwise removed their form under the provisions of Section 108(1) of the Companis Act, 1994.";
                $cluase = ListSubscriber::where('app_id', $app_id)->where('is_director', 1)->get(['corporation_body_name']);
                $text = $predifined_text;
                if (isset($cluase) && count($cluase) > 0) {
                    foreach ($cluase as $name) {
                        $text .= '
                        ' . $name->corporation_body_name;
                    }
                }
                $data = ['response' => 1, 'data' => $text];
            }

        } elseif ($request->get('clause_title_id') == 12) { // For Quorum (AGM)
            $predifined_text = "$nrData->quorum_agm_egm_num (" . CommonFunction::convert_number_to_words($nrData->quorum_agm_egm_num) . ")";
            $existing_charman_data = AoaInfo::where('clause_title_id', 12)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_charman_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $text = $predifined_text;
//                $text .= '<br/>';
                $data = ['response' => 1, 'data' => $text];
            }
        } elseif ($request->get('clause_title_id') == 13) { // For Quorum boardmeeting
            $predifined_text = "$nrData->q_directors_meeting_num (" . CommonFunction::convert_number_to_words($nrData->q_directors_meeting_num) . ")";
            $existing_charman_data = AoaInfo::where('clause_title_id', 13)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_charman_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $text = $predifined_text;
//                $text .= '<br/>';
                $data = ['response' => 1, 'data' => $text];
            }
        } elseif ($request->get('clause_title_id') == 5) {// Share capital
            $existing_director_data = AoaInfo::where('clause_title_id', 5)->where('rjsc_nr_app_id', $app_id)->get();
            if (count($existing_director_data) > 0) {
                $data = ['response' => 0, 'data' => ''];
            } else {
                $predifined_text = "The Authorized Share Capital of the Company is TK $nrData->authorize_capital (" . CommonFunction::convert_number_to_words($nrData->authorize_capital) . ") divided into $nrData->number_shares (" . CommonFunction::convert_number_to_words($nrData->number_shares) . ") Ordinary Shares of Tk $nrData->value_of_each_share (" . CommonFunction::convert_number_to_words($nrData->value_of_each_share) . ") each ";
                $text = $predifined_text;
                $data = ['response' => 1, 'data' => $text];
            }

        } else {
            $data = ['response' => 0, 'data' => ''];
        }
        return response()->json($data);
    }
}
