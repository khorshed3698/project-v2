<?php
namespace App\Modules\Reports\Controllers;

use App\Http\Requests\ReportsRequest;
use App\Http\Controllers\Controller;
use App\Libraries\ACL;
use App\Libraries\UtilFunction;
use App\Libraries\Utility;
use App\Modules\Reports\Models\FavReports;
use App\Modules\reports\Models\ReportRequestList;
use App\Modules\Reports\Models\Reports;
use App\Modules\Reports\Models\ReportsMapping;
use App\Modules\Users\Models\Users;
use App\Modules\Users\Models\UserTypes;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
//use App\Libraries\ReportHelper;
use App\Libraries\CommonFunction;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use App\Modules\Reports\Models\ReportHelperModel;
use App\Modules\Reports\Models\HelperModel;
use phpDocumentor\Reflection\Types\Null_;
use yajra\Datatables\Datatables;

class ReportsController extends Controller {

    public function __construct(){
        if (Session::has('lang'))
            App::setLocale(Session::get('lang'));

    }

    public function index()
    {
        $getList['result'] = Reports::leftJoin('custom_reports_mapping as rm','rm.report_id','=','custom_reports.report_id')
            ->where(function($query){
                //Sys Admin and MIS user will get all lists
                if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '15x151' and Auth::user()->user_type != '1x102'){
                    $query->where('rm.user_type', Auth::user()->user_type)
                        ->where('custom_reports.status',1)
                        ->where('rm.selection_type',2);
                }
            })
            ->orWhere(function($query){
                if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '15x151' and Auth::user()->user_type != '1x102') {
                    $query->where('rm.user_id', Auth::user()->id)
                        ->where('custom_reports.status', 1)
                        ->where('rm.selection_type', 1);
                }
            })
            ->groupBy('custom_reports.report_id')
            ->get(['custom_reports.report_id','report_title','status']);
        $getFavouriteList['fav_report'] = FavReports::join('custom_reports','custom_reports.report_id','=','custom_favorite_reports.report_id')
            ->where('custom_favorite_reports.user_id', Auth::user()->id)
            ->where('custom_favorite_reports.status',1)
            ->get(['custom_reports.report_id','report_title','custom_reports.status']);

        // query for getting unpublished lists
        $query = Reports::leftJoin('custom_reports_mapping as rm', 'rm.report_id', '=', 'custom_reports.report_id');
        if(Auth::user()->user_type != '1x101' and Auth::user()->user_type != '15x151' and Auth::user()->user_type != '1x102')
        {
            $query = $query->where('rm.user_type', Auth::user()->user_type);
        }
        $getUnpublishedList = $query->where('custom_reports.status',0)
            ->groupBy('custom_reports.report_id')
            ->get(['custom_reports.report_id','report_title','status']);
        //dd($getUnpublishedList);
        return view("Reports::list", compact('getList', 'getFavouriteList','getUnpublishedList'));
    }

    public function create()
    {
        $usersList = UserTypes::orderBy('type_name')->lists('type_name','id');
        return view("Reports::create", ['usersList' => $usersList]);
    }

    public function store(ReportsRequest $request)
    {

        if($request->has('is_column_text_full')){
            $is_column_text_full = $request->get('is_column_text_full');
        }else{
            $is_column_text_full = 0;
        }

        $sqlcontent = $request->getContent();
        parse_str($sqlcontent, $output);
        try{
            DB::beginTransaction();
            if(!ACL::getAccsessRight('report','A')) die ('no access right!');
            $selection_type=$request->get('selection_type');
            $reports = Reports::create([
                'report_title' => $request->get('report_title'),
                'selection_type'=>$selection_type,
                'report_para1' => Encryption::dataEncode($output['report_para1']),
                'status' => $request->get('status'),
                'is_column_text_full' => $is_column_text_full,
                'user_id' => 0,
                'updated_by' => 1
            ]);

            if ($selection_type==1){
                if($request->get('users')) {
                    foreach ($request->get('users') as $user_id) {
                        ReportsMapping::create([
                            'user_type' =>$this->getusertype($user_id),
                            'report_id' => $reports->id,
                            'selection_type'=>$selection_type,
                            'user_id'=> $user_id
                        ]);
                    }
                }
            }elseif($request->get('selection_type')==2){
                if($request->get('user_id')) {
                    foreach ($request->get('user_id') as $user_type) {
                        ReportsMapping::create([
                            'user_type' => $user_type,
                            'selection_type'=>$selection_type,
                            'report_id' => $reports->id
                        ]);
                    }
                }
            }else{
                dd(13);
            }
            DB::commit();
            Session::flash('success', 'Successfully Saved the Report.');
            return $request->redirect_to_new == 1 ? redirect('/reports/view/' . Encryption::encodeId($reports->id)) : redirect('/reports/edit/' . Encryption::encodeId($reports->id));
        }catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', Utility::eMsg($e, 'RPC001'));
            return Redirect::back()
//                ->withMessage($message_fail)
//                ->withErrors($validator)
                ->withInput();
        }
    }


    public function edit($id, Request $request)
    {
        if(!ACL::getAccsessRight('report','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);
        $report_data = Reports::where('report_id', $report_id)->first();
        $selected_user_type = ReportsMapping::where('report_id',$report_id)
            ->groupBy('user_type')
            ->lists('user_type')->toArray();
        $selection_type=$report_data->selection_type;
        $select=[];
        $selected_users=Users::whereIn('user_type',$selected_user_type)->get(['id','user_full_name','user_email']);
        $usersList = UserTypes::orderBy('type_name')->lists('type_name','id');
        $selected_user = ReportsMapping::where('report_id',$report_id)->lists('user_type')->all();
        if($report_data->selection_type==1){
            $select= ReportsMapping::where('report_id',$report_id)->lists('user_id')->toArray();
        }
        return view("Reports::edit",compact('report_data','selection_type','usersList','selected_user','selected_users','select','selected_user_type'));
    }
    public function getusertype($user_id){
        $user_type=Users::where('id',$user_id)->first(['user_type']);
        return $user_type->user_type;

    }
    public function getusers(Request $request){
        $users=Users::whereIn('user_type',$request->get('types'))->get(['id','user_full_name','user_email']);
        return response()->json($users);
    }
    public function update($id, ReportsRequest $request)
    {
        if($request->has('is_column_text_full')){
            $is_column_text_full = $request->get('is_column_text_full');
        }else{
            $is_column_text_full = 0;
        }

        $sqlcontent = $request->getContent();
        parse_str($sqlcontent, $output);
        if(!ACL::getAccsessRight('report','E')) die ('no access right!');
        $report_id = Encryption::decodeId($id);
        $selection_type=$request->get('selection_type');
        Reports::where('report_id', $report_id)->update([
            'report_title' => $request->get('report_title'),
            'selection_type'=>$selection_type,
            'report_para1' => Encryption::dataEncode($output['report_para1']),
            'status' => $request->get('status'),
            'is_column_text_full' => $is_column_text_full,
            'user_id' => 0,
            'updated_by' => CommonFunction::getUserId()
        ]);

        /*ReportsMapping::where('report_id',$report_id)->delete();*/
        if ($selection_type==1){
            if($request->get('users')) {
                ReportsMapping::where('report_id',$report_id)->where('selection_type',2)
                    ->delete();
                ReportsMapping::where('report_id',$report_id)
                    ->whereNotIn('user_id',$request->get('users'))
                    ->delete();
                foreach ($request->get('users') as $user_id) {
                    $reportmapping = ReportsMapping::firstOrNew(['user_id' => $user_id,'report_id' => $report_id]);
                    $reportmapping->user_type=$this->getusertype($user_id);
                    $reportmapping->selection_type=$selection_type;
                    $reportmapping->report_id=$report_id;
                    $reportmapping->user_id=$user_id;
                    $reportmapping->save();

                    /*remove from favourite */
                    $users=$request->get('users');
                    FavReports::where('report_id',$report_id)
                        ->whereNotIn('user_id',$users)
                        ->delete();
                }
            }
        }elseif($request->get('selection_type')==2){
            if($request->get('user_id')) {
                ReportsMapping::where('report_id',$report_id)->where('selection_type',1)->delete();
                ReportsMapping::where('report_id',$report_id)
                    ->whereNotIn('user_type',$request->get('user_id'))
                    ->delete();
                foreach ($request->get('user_id') as $user_type) {

                    $reportmapping = ReportsMapping::firstOrNew(['user_type' => $user_type,'report_id' => $report_id,'selection_type'=>$selection_type]);
                    $reportmapping->user_type=$user_type;
                    $reportmapping->selection_type=$selection_type;
                    $reportmapping->report_id=$report_id;
                    $reportmapping->user_id=NULL;
                    $reportmapping->save();

                    /*remove from favourite */
                    $user_type=$request->get('user_id');
//            dd($user_type);

                    $types=FavReports::where('report_id',$report_id)
                        ->join('users', function($leftJoin)use($user_type)
                        {
                            $leftJoin->on('users.id','=','custom_favorite_reports.user_id');
                            $leftJoin ->whereNotIn('users.user_type', $user_type);
                        })
                        ->delete();
                }
            }
        }else{
            dd(13);
        }



        Session::flash('success', 'Successfully Updated the Report.');
        return $request->redirect_to_new == 1 ? redirect('/reports/view/' . Encryption::encodeId($report_id)) : redirect('/reports/edit/' . Encryption::encodeId($report_id));
    }


    public function reportsVerify(Request $request) {

        $obj = new HelperModel();
        $sqlcontent = $request->getContent();
        $queryarray = parse_str($sqlcontent, $output);
        $sql = $output['sql'];

        $sql = preg_replace('/&gt;/','>',$sql);
        $sql = preg_replace('/&lt;/','<',$sql);

        echo '<hr /><code>'.$sql.'</code><hr />';
        $sql = $this->sqlSecurityGate($sql);
        $result=null;
        try {
            $result = DB::select(DB::raw($sql));
        } catch(QueryException $e) {
            echo $e->getMessage();
        }

        if($result){
            $result2 = array();
            foreach ($result as $value):
                $result2[] = $value;
                if (count($result2) > 99){
                    break;
                }
            endforeach;
            echo '<p></p><pre>';
            echo $obj->createHTMLTable($result2);
            echo '</pre>';
            echo 'showing ' . count($result2) . ' of '.count($result);
            echo '</p>';
        }
    }

    public function sqlSecurityGate($sql) {
        $sql = trim($sql);
        if(strlen($sql)<8){
            dd('Sql is not Valid: ' . $sql);
        }
        $select_keyword = strtoupper(substr($sql, 0, 7));
        $semicolon = strpos($sql, ';');
        if (($select_keyword == 'SELECT ') AND $semicolon == '') {
            return $sql;
        }elseif ((substr($select_keyword,0,5) == 'SHOW ' OR $select_keyword== 'EXPLAIN' OR substr($select_keyword,0,5) == 'DESC ')
            AND $semicolon == '' AND (Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='15x151')) {
            return $sql;
        } else {
            dd('Sql is not Valid: ' . $sql);
        }
    }

    public function showTables(Request $request) {

        if($request->session()->has('db_tables')){
            echo $request->session()->get('db_tables');
        } else {
            $tables = DB::select(DB::raw('show tables'));
            $count = 1;
            $ret = '<ul class="table_lists">';
            foreach ($tables as $table) {
                $table2 = json_decode(json_encode($table), true);

                $ret .= '<li class="table_name table_' . $count . '"><strong>' . $table2[key($table2)] .'</strong><br/>';
                $fields = DB::select(DB::raw('show fields from ' . $table2[key($table2)]));

                $fileds='';
                foreach ($fields as $field) {
                    $fileds .=  strlen($fileds)>0? ', '.$field->Field:''.$field->Field;
                }
                $ret .= $fileds;

                $ret .= '</li>';
                $count++;
            }
            $ret .= '</ul>';
            $request->session()->put('db_tables', $ret);
            echo $ret;
        }
    }
    public function view($report_id = '')
    {
        $objRh = new ReportHelperModel();
        $report_id2 = Encryption::decodeId($report_id);
        $fav_report_info = FavReports::where('report_id', $report_id2)
            ->where('user_id',Auth::user()->id)
            ->first();
        $report_unpublished_info = Reports::where('report_id', $report_id2)
            ->first();

        // Report Admins are out of this check
        // check that the favourite report is published or not
        // check that the favourite report is assigned or not
        if (in_array(Auth::user()->user_type,Reports::isReportAdmin()) != true)
        {
            if ($fav_report_info != null)
            {
                $is_publish = Reports::where([
                    'report_id' => $report_id2,
                    'status' => 1
                ])->count();
                $is_assigned = ReportsMapping::where([
                    'report_id' => $report_id2,
                    'user_type' => Auth::user()->user_type
                ])->count();
                if ($is_publish == 0 || $is_assigned == 0)
                {
                    Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
                    return redirect('reports');
                }
            }
            if ($report_unpublished_info != null)
            {
                if ($report_unpublished_info->status == 0)
                {
                    Session::flash('error', 'Sorry, This Report is unpublished or unassigned to your user type.');
                    return redirect('reports');
                }
            }
        }
        $encode_SQL = '';
        $search_keys = '';
        $report_data = Reports::where('report_id', $report_id2)->first();
        $reportParameter=$objRh->getSQLPara(Encryption::dataDecode($report_data->report_para1));
        return view('Reports::reportInputForm',compact('reportParameter','report_id','report_data','fav_report_info','encode_SQL','search_keys'));
    }

    public function showReport($report_id, Request $request)
    {

        $objRh = new ReportHelperModel();

        if (!$request->all()) {
            return redirect('reports/view/' . $report_id);
        }
        $reportId = Encryption::decodeId($report_id);

        $reportId = is_numeric($reportId) ? $reportId : null;
        if (!$reportId) {
            return redirect('dashboard');
        }

        $searchKey = array();
        $data = array();

        foreach ($request->all() as $key => $row) {

            if (substr($key, 0, 4) == 'rpt_') {
                $searchKey[] = $row;
                $data[$key] = $request->get($key);
                $request->session()->put($key, $request->get($key));
            }
            elseif (substr($key, 0, 5) == 'sess_') {

                $searchKey[] = $row;
                $data[$key] = session($key);
            } else {
                $searchKey[] = $row;
                $data[$key] = $request->get($key);
            }
        }
        if ($request->get('export_csv')) {
            $this->exportCSV($reportId, $data);
        } elseif ($request->get('export_csv_zip')) {
            $this->exportCSV_Zip($reportId, $data);
        } else{
            $report_data = Reports::where('report_id', $reportId)->first();
            $reportParameter = $objRh->getSQLPara(Encryption::dataDecode($report_data->report_para1));
            $SQL = $objRh->ConvParaEx(Encryption::dataDecode($report_data->report_para1), $data);
            $searchKey = implode(',',$searchKey);
            $search_keys = Encryption::dataEncode($searchKey);
            $encode_SQL = Encryption::dataEncode($SQL);
            try {
                $recordSet = DB::select(DB::raw($SQL));
                return view('Reports::reportGenerate', compact('recordSet', 'report_id', 'report_data', 'reportParameter','encode_SQL','search_keys'));
            } catch (QueryException $e) {
                Session::flash('error', $e->getMessage());
                return redirect('reports');
            }
        }
    }

    public function addToFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            $existing_fav_report = FavReports::where('report_id',$report_id)
                ->where('user_id',Auth::user()->id)
                ->count();
            if ($existing_fav_report > 0)
            {
                FavReports::where('report_id',$report_id)
                    ->where('user_id',Auth::user()->id)
                    ->update([
                        'status' => 1,
                        'updated_by' => CommonFunction::getUserId()
                    ]);
            }
            else{
                FavReports::create([
                    'user_id' => Auth::user()->id,
                    'report_id' => $report_id,
                    'status' => 1
                ]);
            }
            return Redirect::back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', Utility::eMsg($e, 'RPC002'));
            return Redirect::back();
        }
    }

    public function removeFavourite($id)
    {
        $report_id = Encryption::decodeId($id);
        try
        {
            FavReports::where('report_id',$report_id)
                ->where('user_id',Auth::user()->id)
                ->update([
                    'status' => 0,
                    'updated_by' => CommonFunction::getUserId()
                ]);
            return Redirect::back();
        }
        catch (\Exception $e)
        {
            Session::flash('error', Utility::eMsg($e, 'RPC003'));
            return Redirect::back();
        }
    }

    public function exportCSV($id, $data) {

        $objRh = new ReportHelperModel();
        $reportData = DB::select(DB::raw("SELECT * FROM custom_reports WHERE REPORT_ID='$id'"));
        $reportData = json_decode(json_encode($reportData));
        $name = $reportData['0']->report_title.'-'.$id.'-'.Carbon::now().'.csv';
        $report_name = str_replace(' ','_',$name) ;
        try {
            $SQL = base64_decode($reportData['0']->report_para1);
            $SQL = $objRh->ConvParaEx($SQL, $data);
            $data = DB::select(DB::raw($SQL));


            header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
            header("Content-Disposition: attachment; filename=$report_name");

            if ($data && count($data[0]) > 0) {
                $rc = 0;
                foreach ($data[0] as $key => $value) {
                    if ($rc > 0) {
                        echo ',';
                    } $rc++;
                    echo "$key";
                }
                echo "\r\n";
                foreach ($data as $row):
                    $rc = 0;
                    foreach ($row as $key => $field_value):
                        if ($rc > 0) {
                            echo ',';
                        } $rc++;
                        if (empty($field_value)) {
                            //
                        } else if (strlen($field_value) > 10) {
                            echo '"' . addslashes($field_value) . '"';
                        } else if (is_numeric($field_value)) {
                            echo $field_value;
                        } else {
                            echo '"' . addslashes($field_value) . '"';
                        }
                    endforeach;
                    echo "\r\n";
                endforeach;
            } else {
                echo "Data Not Found!";
            }

            // This exit will remaining
            exit();
        } catch (QueryException $e) {
            echo "CSV can't generate for following error: ";
            dd($e->getMessage());
            return redirect('re');

        }
    }
    public function exportCSV_Zip($id, $data) {

        $this->exportCSV($id, $data);
        exit();
        dd('zip library not found');
        $objRh = new ReportHelperModel();
        $this->load->library('zip');


        $reportData = $this->db->query("SELECT REPORT_PARA1 FROM custom_reports WHERE REPORT_ID='$id'")->result_array();

        $SQL = $reportData['0']['REPORT_PARA1'];
        $SQL = $objRh->ConvParaEx($SQL, $data);
        $data = $this->db->query($SQL)->result_array();
        $csv_data = '';
        if ($data && count($data[0]) > 0) {
            $rc = 0;
            foreach ($data[0] as $key => $value) {
                if ($rc > 0) {
                    $csv_data .= ',';
                } $rc++;
                $csv_data .= "$key";
            }
            $csv_data .= "\r\n";
            foreach ($data as $row):
                $rc = 0;
                foreach ($row as $key => $field_value):
                    if ($rc > 0) {
                        $csv_data .= ',';
                    } $rc++;
                    if (empty($field_value)) {
                        //
                    } else if (strlen($field_value) > 10) {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    } else if (is_numeric($field_value)) {
                        $csv_data .= $field_value;
                    } else {
                        $csv_data .= '"' . addslashes($field_value) . '"';
                    }
                endforeach;
                $csv_data .= "\r\n";
            endforeach;
        } else {
            $csv_data .= "Data Not Found!";
        }

        $folder_name = "reports_of_$id";
        $name = "report_$id.csv";
        $this->zip->add_data($name, $csv_data);

// Write the zip file to a folder on your server. Name it "report_id.zip"
//        $this->zip->archive(base_url()."csv/$name.zip");
// Download the file to your desktop. Name it "report_id.zip"
        $this->zip->download("$folder_name.zip");
//        redirect(site_url('reports/index' . $this->encryption->encode($id)));
    }
}
