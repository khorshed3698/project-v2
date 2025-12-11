<?php

namespace App\Modules\NewReg\Controllers;

use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\NewReg\Models\NewReg;
use App\Modules\NewReg\Models\Objective;
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

class ObjectiveController extends Controller
{
    protected $aclName;
    public function __construct()
    {
        if(Session::has('lang')){
            App::setLocale(Session::get('lang'));
        }
        $this->aclName = 'NewReg';
    }

    public function saveobjective(Request $request){
        // Set permission mode and check ACL
        $app_id = (!empty($request->get('app_id')) ? Encryption::decodeId($request->get('app_id')) : '');
        $mode = (!empty($request->get('app_id')) ? '-E-' : '-A-');
        if (!ACL::getAccsessRight($this->aclName, $mode, $app_id)) {
            abort('400', 'You have no access right! Contact with system admin for more information.');
        }

        // Check whether the applicant company is eligible and have approved basic information application
        $company_id = Auth::user()->company_ids;
//        if(CommonFunction::checkEligibilityAndBiApps($company_id) != 1){
//            Session::flash('error', "Sorry! Your selected company is not eligible or you have no approved Basic Information application.");
//            return redirect()->back();
//        }

        if ($request->get('actionBtn') != 'draft') {
            $rules = [
                'objective' => 'required',
            ];

            $messages = [
            ];

            $this->validate($request, $rules, $messages);
        }

        try {



            DB::beginTransaction();
            $data = $request->all();


//            if ($request->get('app_id')) {
//                $decodedId = Encryption::decodeId($data['app_id']);
//                $appData = ImportPermit::find($decodedId);
//                $processData = ProcessList::where(['process_type_id' => $this->process_type_id, 'ref_id' => $appData->id])->first();
//            } else {
//                $appData = new ImportPermit();
//                $processData = new ProcessList();
//                $processData->company_id = $companyId;
//            }
                Objective::where('rjsc_nr_app_id',$app_id)->delete();
                foreach ($data['objective'] as $key => $dat) {
                    $objective = new Objective();
                    $objective->rjsc_nr_app_id = $app_id;
                    $objective->serial_number = $key;
                    if (CommonFunction::asciiCharCheck($data['objective'][$key])){
                        $objective->objective = $data['objective'][$key];
                    }else{
                        Session::flash('error', 'non-ASCII Characters found in objective [OB-1002]');
                        return Redirect::to(URL::previous() . "#step12");
                    }

                    $objective->save();
                }
            //update sequence
            $sequence=NewReg::find($app_id);
            $sequence->sequence=13;
            $sequence->save();
            Session::put('sequence', $sequence->sequence);

            DB::commit();

            if (!empty($app_id)) {

                return Redirect::to(URL::previous() . "#step13");
            }
            return redirect('licence-applications/company-registration/add/#step13');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back()->withInput();
        }
    }
}
