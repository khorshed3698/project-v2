<?php

namespace App\Modules\NewReg\Controllers;


use App\Http\Controllers\Controller;
use App\Libraries\CommonFunction;
use App\Modules\NewReg\Models\ApplicationFeedback;
use App\User;
use Illuminate\Http\Request;
use App\Libraries\Encryption;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class FeedbackController extends Controller
{
    protected $process_type_id;
    protected $aclName;
    public function __construct()
    {
        if (Session::has('lang')) {
            App::setLocale(Session::get('lang'));
        }
        $this->process_type_id = 104;
        $this->aclName = 'NewReg';
    }

    public function feedbackform($app_id){
        $applicationId = Encryption::decodeId($app_id);
        $process_type_id = $this->process_type_id;
        $appInfo = ProcessList::leftJoin('rjsc_nr_apps as apps', 'apps.id', '=', 'process_list.ref_id')
            ->where('process_list.ref_id', $applicationId)
            ->where('process_list.process_type_id', $process_type_id)
            ->first([
                'process_list.id as process_list_id',
                'process_list.desk_id',
                'process_list.process_type_id',
                'process_list.status_id',
                'process_list.ref_id',
                'process_list.tracking_no',
                'process_list.company_id',
                'apps.*',
            ]);
        $feedback = ApplicationFeedback::where('ref_id',$applicationId)->where('process_type_id',$this->process_type_id)
            ->get();
        return view('NewReg::feedback',compact('appInfo','feedback'));
    }

    public  function storefeedback(Request $request)
    {
        try{
            $application_id =Encryption::decodeId($request->get('app_id')) ;
            DB::beginTransaction();
            $prefix = date('Y_');
            $all_url = '';
            if ($request->hasFile('attachment')) {
                foreach ($request->file('attachment') as $attachment){
                        $path = 'application_feedback';
                        $i = strripos($attachment->getClientOriginalName(), '.');
                        $ext = strtolower(substr($attachment->getClientOriginalName(), $i + 1));
                        $original_file = trim(sprintf("%s", uniqid($prefix) . "_af" . "." . $ext));
                        $file_type = $attachment->getClientMimeType();
                        if ($file_type != 'application/pdf') {
                            Session::flash('error', 'File must be in PDF format');
                            return redirect()->back()->withInput();
                        }
                        $authoFileUrl = $original_file;
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                            $myfile = fopen($path . "/index.html", "w");
                            fclose($myfile);
                        }
                    $attachment->move('application_feedback', $authoFileUrl);
                        $filepath = $path . '/' . $authoFileUrl;
                       $all_url .= $filepath.'@';
                    }

            }

            ApplicationFeedback::create([
                'ref_id'=> $application_id,
                'process_type_id' => $this->process_type_id,
                'feedback_text' => $request->get('feedback_text'),
                'type' =>1,
                'attachment' =>rtrim($all_url,'@')
            ]);
            DB::commit();
            return redirect('/new-reg/feedback/'.$request->get('app_id'));
        }catch (\Exception $e) {
            DB::rollback();
//            dd($e->getMessage(), $e->getLine());
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . "[NRC-1001]");
            return redirect()->back()->withInput();
        }
    }

    public static function getUserFullNameById($user_id)
    {
        if ($user_id != "") {
            $user_data = User::where('id',$user_id)->first();
            if (isset($user_data)){
                return $user_data->user_first_name . ' ' . $user_data->user_middle_name . ' ' . $user_data->user_last_name;
            }else {
                return 'N/A';
            }

        } else {
            return 'N/A';
        }
    }

}
