<?php namespace App\Modules\BoardMeting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Apps\Models\EmailQueue;
use App\Modules\Apps\Models\VisaTypes;
use App\Modules\BasicInformation\Models\EA_OrganizationStatus;
use App\Modules\BoardMeting\Models\Agenda;

use App\Modules\BoardMeting\Models\AgendaMapping;
use App\Modules\BoardMeting\Models\AgendaRemarks;
use App\Modules\Remittance\Models\BidaRegInfo;
use App\Modules\Remittance\Models\BriefDescription;
use App\Modules\Remittance\Models\PresentStatus;
use App\Modules\Settings\Models\SubSector;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\BoardMeting\Models\BoardMeetingProcessStatus;
use App\Modules\BoardMeting\Models\BoardMeetingDoc;
use App\Modules\BoardMeting\Models\BoardMeting;
use App\Modules\BoardMeting\Models\Committee;
use App\Modules\Users\Models\Countries;
use App\Modules\Remittance\Models\RemittanceType;
use App\Modules\BoardMeting\Models\ProcessListBMRemarks;
use App\Modules\BoardMeting\Models\ProcessListBoardMeting;
use App\Modules\ProcessPath\Models\ProcessList;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\Settings\Models\Currencies;
use App\Modules\Users\Models\Nationality;
use App\Modules\Users\Models\Users;
use App\Modules\WorkPermitExtension\Models\WorkPermitExtension;
use App\Modules\WorkPermitNew\Models\WorkPermitNew;
use App\Modules\WorkPermitNew\Models\WP_VisaTypes;
use Illuminate\Http\Request;
use App\Libraries\ACL;
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Response as FacadeResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use League\Fractal\Resource\Collection;
//use mPDF;
//use Mpdf\Mpdf;
use Mpdf\Mpdf;
//use mPDF;
use Symfony\Component\HttpFoundation\File\File;
use yajra\Datatables\Datatables;
use Validator;
use App\Modules\Apps\Models\PaymentMethod;

class AgendaController extends Controller
{


    public function view($board_meeting_id)
    {
        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);
//        $pendingAgendaCount = ProcessListBoardMeting::where('board_meeting_id', $meeting_id)
////            ->where('bm_status_id',0)
//            ->whereIn('bm_status_id',['',0])
//            ->where('pl_agenda_name','!=','')
//            ->count();
//        dd($pendingAgendaCount);
        $chairmen = Committee::where('board_meeting_id', $meeting_id)->where('type','yes')->first();
        $document = BoardMeetingDoc::where('board_meting_id',$meeting_id)->get();
        $status = BoardMeetingProcessStatus::where('type_id', 3)->lists('status_name','id')->all();
        return view('BoardMeting::agenda.agenda-list', compact('board_meeting_id','pendingAgendaCount', 'chairmen', 'board_meeting_data','document','status'));
    }

    public function getAgendaData(request $request)
    {
        $mode = ACL::getAccsessRight('BoardMeting', '-V-');
        $board_meting_id = Encryption::decodeId($request->get('board_meting_id'));
        $boardMeetingStatus = BoardMeting::where('id', $board_meting_id)->first(['status']);
        $chairmen = Committee::where('board_meeting_id', $board_meting_id)->where('type','yes')->first();
        $agendaList = Agenda::leftJoin('process_type', 'process_type.id', '=', 'agenda.process_type_id')
            ->leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'agenda.status')
            ->where('board_meting_id', $board_meting_id)
            ->where('agenda.is_archive', 0)
            ->groupBy('agenda.name')
            ->orderBy('agenda.id', 'DESC')
            ->get(['agenda.id', 'agenda.name', 'description', 'process_type.name as process_type_name', 'agenda.is_active', 'agenda.created_at', 'board_meeting_process_status.status_name', 'board_meeting_process_status.panel']);

        return Datatables::of($agendaList)
            ->addColumn('action', function ($agendaList) use ($mode,$boardMeetingStatus, $chairmen) {
                if ($mode) {
                    $userType = CommonFunction::getUserType();
                    $button = ' <a href="' . url('board-meting/agenda/process/' . $agendaList->name .'/' . Encryption::encodeId($agendaList->id)) . '" class="btn btn-xs btn-primary open" ><i class="fa fa-folder-open-o"></i> View Agenda</a>';
//                    if (!in_array($boardMeetingStatus->status, [5, 10])) {  //5= fixed status 10=complete
//                        if (!in_array($boardMeetingStatus->status, [5, 10]) && $userType == '13x303' || (isset($chairmen) && $chairmen->user_email == Auth::user()->user_email)) {  //5= fixed status 10=complete
//                            //$button .= ' <a href="' . url('board-meting/agenda/edit/' . Encryption::encodeId($agendaList->id)) . '" class="btn btn-xs btn-success open" ><i class="fa fa-edit"></i> Edit</a>';
//                           // $button .= ' <a  onclick="deleteAgenda(' . $agendaList->id . ')" class="btn btn-xs btn-danger remove" ><i class="fa fa-times"></i></a>';
//                        }
//                    }
                    return $button;
                } else {
                    return '';
                }
            })
            ->editColumn('is_active', function ($agendaList) {
                if ($agendaList->status_name != '') {
                    $activate = 'class="label btn btn-' . $agendaList->panel . '" ';
                    $status_name = $agendaList->status_name;
                } else {
                    $activate = 'class="label btn btn-warning" ';
                    $status_name = 'Pending';
                }
                return '<span ' . $activate . '><b>' . $status_name . '</b></span>';
            })
            ->removeColumn('id')
            ->make(true);
    }

    public function deleteAgenda(Request $request)
    {
        $id = $request->get('agenda_id');
        Agenda::where('id', $id)->delete();
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function downloadAgenda($board_meeting_id)
    {
        $meeting_id = Encryption::decodeId($board_meeting_id);
        $board_meeting_data = BoardMeting::find($meeting_id);

        $totalApplication = ProcessListBoardMeting::where('process_list_board_meeting.board_meeting_id',$meeting_id)
            ->get([
                'process_id'
            ]);

        $BoardMeetingWiseAllAgenda = Agenda::where('board_meting_id',$meeting_id)
            ->orderBy('name')
            ->groupBy('name')
            ->groupBy('agenda_type')
            ->get([
                'name',
                'agenda_type',
                'process_type_id',
            ]);

        $arrayData = [];
        foreach ($BoardMeetingWiseAllAgenda as $data) {
            $array1 = AgendaMapping::where('agenda_name',$data->name)
                ->where('type',$data->agenda_type)
                ->orderBy('agenda_name')
                ->first([
                    'agenda_name',
                    'type',
                    'agenda_heading_title',
                    'table_heading_json_format'
                ]);
            $array1['process_type_id'] = $data->process_type_id;
            $arrayData[] = $array1;
        }

        $countries =  Countries::where('country_status', 'Yes')->orderBy('nicename', 'asc')->lists('nicename', 'id')->all();
        $divisions =  AreaInfo::where('area_type', 1)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $districts =  AreaInfo::where('area_type', 2)->orderBy('area_nm', 'asc')->lists('area_nm', 'area_id')->all();
        $thana = AreaInfo::orderby('area_nm')->where('area_type', 3)->lists('area_nm', 'area_id');

        $meetingName = '';
        if ($board_meeting_data->meting_type == 1) { // Inter-Ministerial Committee Meeting
            $meetingName = "Inter-ministerial meeting minutes";

            $sql1 = "SELECT 'Work Permit' `Module_Name`,
                  SUM(IF(process_type_id=2,1,0)) `New`,
                  SUM(IF(process_type_id=3,1,0)) `Extension`,
                  SUM(IF(process_type_id=4,1,0)) `Amendment`,
                  SUM(IF(process_type_id=5,1,0)) `Cancellation` 
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id 
                  where plbm.board_meeting_id = $meeting_id 
                  and agenda.process_type_id in (2,3,4,5) limit 1";

            $wpAppNew = \DB::select(DB::raw($sql1))[0];

            $projectOfficeSql = "SELECT 'Project Office' `Module_Name`,
                  SUM(IF(process_type_id=22,1,0)) `New`,
                  SUM(IF(process_type_id=23,1,0)) `Extension`,
                  SUM(IF(process_type_id=24,1,0)) `Amendment`,
                  SUM(IF(process_type_id=25,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (22, 23, 24, 25) limit 1";
            $projectOfficeApp = \DB::select(DB::raw($projectOfficeSql))[0];
            
            $branchNew = 0;
            $liaison_officeNew = 0;
            $representative_officeNew = 0;

            $branchExt = 0;
            $liaison_officeExt = 0;
            $representative_officeExt = 0;

            $branchAme = 0;
            $liaison_officeAme = 0;
            $representative_officeAme = 0;

            $branchCan = 0;
            $liaison_officeCan = 0;
            $representative_officeCan = 0;

            foreach ($totalApplication as $total) {
                // office permission new
                $sql2 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 1 ) branch_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 6  
                        and `opn_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opn_apps` on `process_list`.`ref_id` = `opn_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 6 
                        and `opn_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplication = \DB::select(DB::raw($sql2))[0];
                $branchNew += $typeWiseApplication->branch_office;
                $liaison_officeNew += $typeWiseApplication->liaison_office;
                $representative_officeNew += $typeWiseApplication->representative_office;

                // office permission extension
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7  
                        and `ope_apps`.`office_type` = 1 ) branch_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7
                        and `ope_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `ope_apps` on `process_list`.`ref_id` = `ope_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 7 
                        and `ope_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationExt = \DB::select(DB::raw($sql3))[0];
                $branchExt += $typeWiseApplicationExt->branch_office;
                $liaison_officeExt += $typeWiseApplicationExt->liaison_office;
                $representative_officeExt += $typeWiseApplicationExt->representative_office;

                // office permission Amendment
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 8 
                        and `opa_apps`.`office_type` = 1 ) branch_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id
                        and `process_list`.`process_type_id` = 8                         
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationAme = \DB::select(DB::raw($sql3))[0];
                $branchAme += $typeWiseApplicationAme->branch_office;
                $liaison_officeAme += $typeWiseApplicationAme->liaison_office;
                $representative_officeAme += $typeWiseApplicationAme->representative_office;

                // office permission cancellation
                $sql3 = "SELECT 
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 1 ) branch_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 2 ) liaison_office,
                        
                        (select  count(process_list.id) from `process_list` 
                        left join `opa_apps` on `process_list`.`ref_id` = `opa_apps`.`id` 
                        where `process_list`.`id` = $total->process_id 
                        and `process_list`.`process_type_id` = 9                        
                        and `opa_apps`.`office_type` = 3 ) representative_office";
                $typeWiseApplicationCan = \DB::select(DB::raw($sql3))[0];
                $branchCan += $typeWiseApplicationCan->branch_office;
                $liaison_officeCan += $typeWiseApplicationCan->liaison_office;
                $representative_officeCan += $typeWiseApplicationCan->representative_office;
            }

            $countAllApplication = $branchNew+$branchExt+$branchAme+$branchCan+$liaison_officeNew+$liaison_officeExt+
                $liaison_officeAme+$liaison_officeCan+$representative_officeNew+$representative_officeExt+
                $representative_officeAme+$representative_officeCan+$wpAppNew->New+$wpAppNew->Extension+
                $wpAppNew->Amendment+$wpAppNew->Cancellation+
                $projectOfficeApp->New;

            $nationality = Countries::where('country_status', 'Yes')->where('nationality', '!=', '')->orderby('nationality', 'asc')->lists('nationality', 'id');
            $WP_visaTypes = VisaTypes::where('status', 1)->where('is_archive', 0)->orderBy('type', 'asc')->lists('type', 'id');
            $currencies = Currencies::where('is_archive', 0)->where('is_active', 1)->orderBy('code')->lists('code', 'id');
            $paymentType = PaymentMethod::where('status', 1)->where('is_archive', 0)->lists('name', 'id');
            $ms = 0;

            $contents = view('BoardMeting::agenda.agenda-download',compact("meetingInfo","board_meeting_data",
                "wpAppNew","BoardMeetingWiseAllAgenda","arrayData","meeting_id","nationality",
                "branchNew","liaison_officeNew","representative_officeNew",
                "branchExt","liaison_officeExt","representative_officeExt",
                "branchAme","liaison_officeAme","representative_officeAme",
                "branchCan","liaison_officeCan","representative_officeCan",
                "countAllApplication","WP_visaTypes","currencies","ms","divisions","districts","thana","countries","paymentType","projectOfficeApp"))
                ->render();
        }
        elseif ($board_meeting_data->meting_type == 2) { // Executive Council of BIDA
            $SubSector = SubSector::orderBy('name')->lists('name', 'id')->all();
            $EA_OrganizationStatus = EA_OrganizationStatus::orderBy('name')->lists('name', 'id')->all();
            $remittancePresentStatus = PresentStatus::orderby('name')->where('status',1)->where('is_archive',0)->lists('name','id');
            $meetingName = "Executive Council of BIDA";
            $remittanceType = RemittanceType::orderby('name')->where('status',1)->where('is_archive',0)->lists('name','id');

            $sql1 = "SELECT 'Remittance' `Module_Name`,
                  SUM(IF(process_type_id=11,1,0)) `New`,
                  SUM(IF(process_type_id=12,1,0)) `Extension`,
                  SUM(IF(process_type_id=13,1,0)) `Amendment`,
                  SUM(IF(process_type_id=14,1,0)) `Cancellation`
                  from process_list_board_meeting plbm
                  Left join agenda on agenda.id = plbm.agenda_id
                  where plbm.board_meeting_id = $meeting_id
                  and agenda.process_type_id in (11,12,13,14) limit 1";
            $remittance = \DB::select(DB::raw($sql1))[0];
            $ms = 0;
            $contents = view('BoardMeting::agenda.agenda-download-remittance',compact(
                "meeting_id",'board_meeting_data',"nationality",'arrayData',
                "divisions",'ms',"countAllApplication","districts","thana","countries","remittance","applicatonData","ra_bida_reg_info",
                "SubSector","EA_OrganizationStatus","remittancePresentStatus","totalApplication","remittanceType"
            ))->render();
        }
        else {
            Session::flash('error', "Sorry! No meeting type found!");
            return redirect()->back();
        }

        $mpdf = new mPDF([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => 10,
            'default_font' => 'timesnewroman',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        if (config('app.server_type') != 'live') {
            $mpdf->SetWatermarkText('TEST PURPOSE ONLY');
            $mpdf->showWatermarkText = true;
            $mpdf->watermark_font = 'timesnewroman';
            $mpdf->watermarkTextAlpha = 0.1;
        }

        $mpdf->AddPage('L'); // Adds a new page in Landscape orientation
        $mpdf->useSubstitutions;
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDefaultBodyCSS('color', '#000');
        $mpdf->SetTitle("Bangladesh Investment Development Authority (BIDA)");
        $mpdf->SetSubject($board_meeting_data->meting_number.' '.$meetingName);
        $mpdf->SetAuthor("Business Automation Limited");
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoVietnamese = true;
        $mpdf->autoArabic = true;
        $mpdf->autoLangToFont = true;
        $mpdf->SetDisplayMode('fullwidth');

        //static header section
        $mpdf->SetHTMLFooter('
        <table width="100%">
            <tr>
                <td width="33%" style="font-size: 9px;"><i>'.$board_meeting_data->meting_number.' '.$meetingName.'</i></td>
                <td width="33%" style="text-align: center;font-size: 9px;"><i>Holding Date: '.$newDate = date("j-M-Y", strtotime($board_meeting_data->meting_date)).'</i></td>
                <td width="33%" style="text-align: right;font-size: 9px;"><i>{PAGENO}/{nbpg}</i></td>
            </tr>
        </table>');

        $stylesheet = file_get_contents('assets/css/pdf_download_check_v1.css');
        $mpdf->setAutoTopMargin = 'stretch';
        $mpdf->setAutoBottomMargin = 'stretch';
        $mpdf->WriteHTML($stylesheet, 1);
        $mpdf->WriteHTML($contents, 2);
        $mpdf->defaultfooterfontsize = 9;
        $mpdf->defaultfooterfontstyle = 'B';
        $mpdf->defaultfooterline = 0;
        $mpdf->SetCompression(true);

        $directoryName = 'Agenda_'.$meeting_id.'_'. date("Y/m/d");

        $pdfFilePath = $directoryName.'.pdf';

        $mpdf->Output($pdfFilePath, 'D'); // D = download only, F = Save only, I = view only
    }

    public function createNewAgenda($board_meeting_id)
    {
        $board_meeting_data = BoardMeting::find(Encryption::decodeId($board_meeting_id));
        $userType = CommonFunction::getUserType();
        $process_type =  ProcessType::where('status', 1)
            ->where('process_type.active_menu_for', 'like', "%$userType%")
            ->lists('name', 'id')->all();
        $chairmen = Committee::where('board_meeting_id', Encryption::decodeId($board_meeting_id))->where('type','yes')->first();
        return view('BoardMeting::agenda.create-agenda', compact('process_type','board_meeting_data','chairmen'));
    }

    public function storeAgenda(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
//                'description' => 'required',
//                'is_active' => 'required'
            ]);

            DB::beginTransaction();
            $agenda_id = Agenda::create([
                'name' => $request->get('name'),
//                'description' => $request->get('description'),
                'process_type_id' => $request->get('process_type_id'),
                'board_meting_id' => Encryption::decodeId($request->get('board_meting_id')),
                'is_active' => 1
            ]);
            BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))
                ->update(['sequence_no'=>'3']);

            $boardMeetingDate = BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))->first()->meting_date;
            $process_type_name = ProcessType::where('id', $request->get('process_type_id'))->first()->name;

            $body_msg = '<span style="text-align:justify;">';
            $body_msg .= '<b>Your agenda info: <br>Agenda Name: </b> ' . $request->get('name') . '<br>
             <b>Process Type: </b>'.$process_type_name.' <br><b>Meeting Date:</b> '.date("d-M-Y", strtotime($boardMeetingDate)).'<br> <b>Description:</b>' . $request->get('description') .
                $body_msg .= '</span>';
            $body_msg .= '<br/><br/><br/>Thanks<br/>';
            $body_msg .= "<b>".env('PROJECT_NAME')." </b>";

            $header = "Agenda Information for Board Meeting";
            $param = $body_msg;
            $email_content = view("Users::message", compact('header', 'param'))->render();
            $emailQueue = new EmailQueue();
            $emailQueue->process_type_id = 0; // NO SERVICE ID
            $emailQueue->app_id = $agenda_id;
            $emailQueue->email_content = $email_content;
            $emailQueue->email_to = auth::user()->user_email;
            $emailQueue->sms_to =  auth::user()->user_phone;
            $emailQueue->email_subject = $header;
            $emailQueue->attachment = '';
            $emailQueue->save();

            DB::commit();
            Session::flash('success', 'Data is stored successfully!');
            return redirect('/board-meting/agenda/list/' . $request->get('board_meting_id'));
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }

    }

    public function editAgenda($id)
    {
        $agenda_id = Encryption::decodeId($id);
        $userType = CommonFunction::getUserType();
        $process_type = ['' => 'Select One'] + ProcessType::where('status', 1)
                ->where('process_type.active_menu_for', 'like', "%$userType%")
                ->lists('name', 'id')->all();

        $agendaData = Agenda::leftJoin('board_meeting_doc', 'agenda.id', '=', 'board_meeting_doc.agenda_id')
            ->where('agenda.id', $agenda_id)->first(['agenda.*','board_meeting_doc.doc_name','board_meeting_doc.file']);
        $chairmen = Committee::where('board_meeting_id', $agendaData->board_meting_id)->where('type','yes')->first();
        return view('BoardMeting::agenda.edit-agenda', compact('agendaData', 'id', 'process_type','chairmen'));
    }

    public function updateAgenda(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try{

            DB::beginTransaction();
            $agenda_id = Encryption::decodeId($id);
            Agenda::where('id', $agenda_id)->update([
                'name' => $request->get('name'),
//                'description' => $request->get('description'),
                'process_type_id' => $request->get('process_type_id'),
            ]);
//            BoardMeting::where('id',Encryption::decodeId($request->get('board_meting_id')))
//                ->update(['sequence_no'=>'3']);

//            $attach_file = $request->file('agenda_file');
//            if ($request->hasFile('agenda_file')) {
//                foreach ($attach_file as $afile) {
//                    $fileType = $afile->getClientOriginalExtension();
//                    $getSize = $afile->getSize();
//                    if ($getSize > (1024 * 1024 * 3)) {
//                        Session::flash('error', 'File size max 3 MB');
//                        return redirect()->back();
//                    }
//                    $support_type = array('pdf','xls','xlsx','ppt','pptx','docx','doc');
//                    if (!in_array($fileType, $support_type)) {
//                        Session::flash('error', 'File type must be xls,xlsx,ppt,pptx,pdf,doc,docx format');
//                        return redirect()->back();
//                    }
//                    $original_file = $afile->getClientOriginalName();
//                    $afile->move('uploads/agenda/', time() . $original_file);
//                    $boardMeeting = BoardMeetingDoc::where('agenda_id', $agenda_id)->first();
//
//                    if ($boardMeeting == null) {
//
//                        $file = new BoardMeetingDoc();
//                        $file->file = 'uploads/agenda/' . time() . $original_file;
//                        $file->agenda_id = $agenda_id;
//                        $file->board_meting_id = Encryption::decodeId($request->get('board_meeting_id'));
//                        $file->save();
//                    } else {
//                        BoardMeetingDoc::where('agenda_id', $agenda_id)
//                            ->update([
//                                'file' => 'uploads/agenda/' . time() . $original_file,
//                            ]);
//                    }
//                }
//            }
            DB::commit();
            Session::flash('success', 'Data is Update successfully!');
            return \redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }

    }

    public function agendaWiseProcess($agendaName,$agendaId)
    {
        $agenda_id = Encryption::decodeId($agendaId);
        $process_type_id = '1';
        $userType = Auth::user()->user_type;
        $ProcessType = ProcessType::whereStatus(1)
            ->where(function ($query) use ($userType) {
                $query->where('active_menu_for', 'like', "%$userType%");
            })
            ->orderBy('name')
            ->lists('name', 'id')
            ->all();
        $agendaInfo = Agenda::leftJoin('process_type', 'process_type.id', '=', 'agenda.process_type_id')
            ->leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'agenda.status')
            ->where('agenda.id', $agenda_id)
            ->where('agenda.name', $agendaName)
            ->first(['agenda.*', 'process_type.name as process_name',
                'board_meeting_process_status.status_name', 'board_meeting_process_status.id as status_id', 'board_meeting_process_status.panel']);


        $appStatus = ['' => 'Select One'] + BoardMeetingProcessStatus::where('is_active', 1)
                ->where('type_id',1)//one mean agenda status
                ->lists('status_name', 'id')->all();

        $boardMeetingInfo = BoardMeting::leftJoin('board_meeting_process_status', 'board_meeting_process_status.id', '=', 'board_meting.status')
            ->where('board_meting.id', $agendaInfo->board_meting_id)->first(['board_meting.*','board_meeting_process_status.status_name','board_meeting_process_status.panel']);

        $status = BoardMeetingProcessStatus::where('type_id', 3)->lists('status_name','id')->all();

        $bm_chairman = Committee::where('board_meeting_id', $agendaInfo->board_meting_id)
            ->where('type','Yes')
            ->first();
        $getChairmensRemarksInAgenda = Agenda::where('id', $agenda_id)->pluck('remarks');
        $countAgendaRemarks = AgendaRemarks::where('agenda_id',$agenda_id)->count();
        $alreadyExistProcess = ProcessListBoardMeting::where('process_list_board_meeting.agenda_id',$agenda_id)->get();

        $chairmanRemarks = true;
        foreach ($alreadyExistProcess as $remarks)
        {
            if($remarks->bm_status_id == 0){ //chairman remarks
                $chairmanRemarks = false;
            }
        }

        Session::put('agenda_id', $agendaId);
        Session::put('board_meeting_id', Encryption::encodeId($agendaInfo->board_meting_id));

        return view('BoardMeting::agenda.agenda-process', compact('status','process_type_id', 'bm_chairman',
            'ProcessType', 'agendaId','agendaName', 'agendaInfo', 'boardMeetingInfo', 'document', 'appStatus', 'getChairmensRemarksInAgenda',
            'countAgendaRemarks','alreadyExistProcess','chairmanRemarks'));
    }

    public function saveAgendaWiseBoardMeting(request $request)
    {
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $agendaInfo = Agenda::where('id', $agenda_id)->first(['board_meting_id']);
        if($request->get('process_list_ids')){

            foreach($request->get('process_list_ids') as $value){
                $boardMeting = new ProcessListBoardMeting();
                $boardMeting->process_id = $value;
                $boardMeting->agenda_id = $agenda_id;
                $boardMeting->board_meeting_id = $agendaInfo->board_meting_id;
                $boardMeting->is_active = 1;
                $boardMeting->save();
            }
        }else {

            $boardMeting = new ProcessListBoardMeting();
            $boardMeting->process_id = $request->get('process_list_id');
            $boardMeting->agenda_id = $agenda_id;
            $boardMeting->board_meeting_id = $agendaInfo->board_meting_id;
            $boardMeting->is_active = 1;
            $boardMeting->save();
        }
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    //my process
    public function agendaWiseBoardMeting(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $list = ProcessListBoardMeting::getBoardMeeting($process_type_id, $status, $request, $desk);
        $boardMeetingStatus = BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))->first(['status']);
        $status = BoardMeetingProcessStatus::where('type_id', 3)->get();
        if (count($list) > 0 && $list[0]->id != null) {
            return Datatables::of($list)
                ->addColumn('action', function ($list) use ($boardMeetingStatus,$status) {
                    $html = '';
                    if(!in_array($boardMeetingStatus->status,[6,10])) { //6 = created 10= completed
                        if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {
                            $html.= '<button style="margin:15px 0px;" type="button" value="'.$list->process_list_board_id.'" class="btn btn-xs btn-info individual_action_save"><i class="fa fa-save"></i> Save</button>&nbsp;';
                        }
                    }
//                    $html.= '<a  target="_blank" href="' . url('/process/'.$list->form_url . '/view/' . Encryption::encodeId($list->ref_id)) .'/board-meeting'. '" class="btn btn-xs btn-primary"> <i class="fa fa-folder-open"></i> Open </a>&nbsp;';
                    $html .= '<a target="_blank" href="' . url('process/' . $list->form_url . '/view-app/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)). '" class="btn btn-xs btn-primary button-color" style="color: white"> <i class="fa fa-folder-open"></i> Open</a>  &nbsp;';
//                    if(!in_array($boardMeetingStatus->status,[5,10]))  {
//                        $html.='<span onclick="deleteItem(' . $list->process_list_board_id . ')" class=" btn btn-danger btn-xs"><i class="fa fa-times"></i></button>';
//                    }

                    return $html;
                })
                ->editColumn('json_object', function ($list) {
                    return @getDataFromJson($list->json_object);
                })
                ->addColumn('desk', function ($list) {
                    return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
                })
                ->editColumn('updated_at', function ($list) use($status,$boardMeetingStatus) {

                    $chairman = 0;
                    $bm_chairman = Committee::where('board_meeting_id', $list->pr_board_meeting_id)
                        ->where('type','Yes')
                        ->first();
                    $html ='';
                    if(!in_array($boardMeetingStatus->status,[6,10])) { //6 = created 10= completed

                        if($list->agendaStatus == 0) {
                            $memberRemarks = CommonFunction::getMemberRemarks($list->process_list_board_id);
                            /* for members */
                            if(empty($memberRemarks)){
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'></textarea>";
                            }else{
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'>$memberRemarks</textarea>";
                            }

                            if ($list->bm_remarks != "") {
                                $html = "  <textarea placeholder='Write your remark here...' class='form-control hidden' name='remark_$list->process_list_board_id' >$list->bm_remarks</textarea>";
                            }
                            if (count($bm_chairman) > 0) {   /*  for chairman */
                                if (Auth::user()->user_email == $bm_chairman->user_email) {
                                    if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {

                                        $html = "   <textarea placeholder='Write your remark here...' class='form-control input-sm remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'>$list->bm_remarks</textarea>";
                                        $html .= "</br></br>$list->bm_status<select class='form-control input-sm status_for_$list->process_list_board_id' name='status_for_$list->process_list_board_id'>";
                                        $html .= "<option value='0'>Select Status</option>";
                                        foreach ($status as $value) {
                                            if ($list->bm_status_id == $value->id) {
                                                $selected = 'selected';
                                            } else {
                                                $selected = '';
                                            }
                                            $html .= "<option value='$value->id' $selected>$value->status_name</option>";
                                        }
                                        $html .= "</select></br>";
                                    }

                                }
                            }

                        }
                    }
                    $html .= ' <button style="margin:10px 0px;" type="button" class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal" onclick="viewRemarks(' . $list->process_list_board_id . ')">All Remarks ('. $list->totalRemarks .')</button>';

                    return $html;
                })->editColumn('desk', function ($list) {
                    $html = "";
                    //if($list->bm_remarks == "" && $list->agendaStatus == 0) {
                    if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {
                        $html = "<input type='checkbox' value='$list->process_list_board_id' class='checkbox' name='checkbox[$list->process_list_board_id]'>";
                    }
                    return $html;
                })
                ->removeColumn('id', 'ref_id', 'process_type_id', 'updated_by', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
                ->make(true);
        } else {
            $list = ProcessType::where('id', 1)->get();//just demo data
            return Datatables::of($list)
                ->addColumn('action', function ($list) use ($status,$boardMeetingStatus) {
                    $html = '';
                    if(!in_array($boardMeetingStatus->status,[5,10]))  {
                        $html = ' <span class="btn btn-xs btn-primary processList " onclick="addMore()"> Add More <i class="fa fa-arrow-right"></i></span>';
                    }
                    return $html;
                })
                ->editColumn('json_object', function ($list) {
                    return '';
                })
                ->addColumn('desk', function ($list) {
                    return '';
                })
                ->editColumn('updated_at', function ($list) {
                    return '';
                })
                ->editColumn('tracking_no', function ($list) {
                    return '';
                })
                ->editColumn('process_name', function ($list) {
                    return '';
                })
                ->editColumn('status_name', function ($list) {
                    return '';
                })
                ->setRowAttr([
                    'color' => function($list) {
                        return 'rad';
                    },
                ])
                ->make(true);
        }
    }

    public function agendaWiseBoardMetingNew(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $list = ProcessListBoardMeting::getBoardMeetingNew($process_type_id, $status, $request, $desk);
        $boardMeetingStatus = BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))->first(['status']);
        $status = BoardMeetingProcessStatus::where('type_id', 3)->get();

        return Datatables::of($list)
            ->editColumn('tracking_no', function ($list) {
                return '<a target="_blank" style="text-decoration: none"  href="' . url('process/' . $list->form_url . '/view-app/' . Encryption::encodeId($list->ref_id) . '/' . Encryption::encodeId($list->process_type_id)) .'" > '.$list->tracking_no.' </a>';
            })
//            ->editColumn('company_name', function ($list) {
//                return  $this->getBasicInfoCompanyName($list->company_id);
//
//            })
            ->editColumn('decision', function ($list) use($status,$boardMeetingStatus) {

                $chairman = 0;
                $bm_chairman = Committee::where('board_meeting_id', $list->pr_board_meeting_id)
                    ->where('type','Yes')
                    ->first();
                $html ='';
                if(in_array($boardMeetingStatus->status,[6])) { //6 = created 10= completed

                    if (count($bm_chairman) > 0) {   /*  for chairman */
                        if (Auth::user()->user_email == $bm_chairman->user_email) {
                            $html .= '<span class="label label-'.$list->panel.'">'.$list->bm_status.'</span>';
                            if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {

                                $html = "$list->bm_status<select style='margin: 5px 0px;' class='form-control input-sm status_for_$list->process_list_board_id' name='status_for_$list->process_list_board_id'>";
                                $html .= "<option value='0'>Select Status</option>";
                                foreach ($status as $value) {
                                    if ($list->bm_status_id == $value->id) {
                                        $selected = 'selected';
                                    } else {
                                        $selected = '';
                                    }
                                    $html .= "<option value='$value->id' $selected>$value->status_name</option>";
                                }

                                $is_hidden_start_date = '';
                                $is_hidden_end_date = '';
                                $is_desired_duration = '';
                                $is_duration_amount = '';
                                if($list->duration_start_date_from_dd == null){
                                    $is_hidden_start_date = 'hidden';
                                }
                                if($list->duration_end_date_from_dd == null){
                                    $is_hidden_end_date = 'hidden';
                                }

                                if($list->desired_duration_from_dd == null){
                                    $is_desired_duration = 'hidden';
                                }

                                if($list->duration_amount_from_dd == null){
                                    $is_duration_amount = 'hidden';
                                }



                                $duration_start_date_from_dd = date('d-M-Y', strtotime($list->duration_start_date_from_dd));
                                $duration_end_date_from_dd = date('d-M-Y', strtotime($list->duration_end_date_from_dd));
                                $html .= "</select>";
                                $html .= "   <textarea placeholder='Write your remark here...' style='width: 100%' class=' form-control input-sm remark_$list->process_list_board_id' name='remark_$list->process_list_board_id'>$list->bm_remarks</textarea>";
                                $html .= "  
                                        <div  style='width: 100%' class='$is_hidden_start_date'>
                                        <fieldset class=\"scheduler-border\" '>
                                            <legend class=\"scheduler-border\">Desired Duration</legend>
                                            <div class=\"form-group\">
                                          
                                                
                                                
                                                <input type='text' value='".$list->process_type_id."' id='process_type_id' class='hidden'>
                                                    <div class=\"col-md-6 $is_hidden_start_date \">
                                                        <label for=\"approved_duration_start_date\" class=\"text-left required-star col-md-12\">Start Date</label>
                                                        <div class=\"col-md-12\">
                                                            <div class=\"input-group date datetimepicker6\" id=\"datetimepicker6\">
                                                                <input data-id ='$is_hidden_start_date' class=\"form-control input-md required approved_duration_start_date_$list->process_list_board_id \" placeholder=\"dd-mm-yyyy\" name=\"approved_duration_start_date\" type=\"text\" value=\"$duration_start_date_from_dd\" id=\"approved_duration_start_date\">
                                                                <span class=\"input-group-addon\"><span class=\"fa fa-calendar\"></span></span>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class=\"col-md-6 $is_hidden_end_date \">
                                                        <label for=\"approved_duration_end_date\" class=\"text-left required-star col-md-12\">End Date</label>
                                                        <div class=\"col-md-12\">
                                                            <div class=\"input-group date datetimepicker7\" id=\"datetimepicker7\">
                                                                <input data-id ='$is_hidden_end_date' class=\"form-control input-md required approved_duration_end_date_$list->process_list_board_id\" placeholder=\"dd-mm-yyyy\"  name=\"approved_duration_end_date\" type=\"text\" value=\"$duration_end_date_from_dd\" id=\"approved_duration_end_date\">
                                                                <span class=\"input-group-addon\"><span class=\"fa fa-calendar\"></span></span>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                  
                                            </div>
                                            
                                            <div class=\"form-group\">

                                                    <div class=\"col-md-6 $is_desired_duration \">
                                                        <label for=\"approved_duration_start_date\" class=\"text-left required-star col-md-12\">Desired Duration (in days)</label>
                                                        <div class=\"col-md-12\">
                                                                <input data-id ='$is_desired_duration' style='width: 100%' class=\"form-control input-md approved_desired_duration_$list->process_list_board_id required\"  name=\"approved_desired_duration_$list->process_list_board_id\" type=\"text\" value=\"$list->desired_duration_from_dd\" id=\"approved_desired_duration\">
                                                        </div>
                                                    </div>
                                                    <div class=\"col-md-6 $is_duration_amount\">
                                                        <label for=\"approved_duration_end_date\" class=\"text-left required-star col-md-12\">Payable amount</label>
                                                        <div class=\"col-md-12\">
                                                               <input data-id ='$is_duration_amount' style='width: 100%' class=\"form-control input-md approved_duration_amount_$list->process_list_board_id required\" name=\"approved_duration_amount_$list->process_list_board_id\" type=\"text\" value=\"$list->duration_amount_from_dd\" id=\"approved_duration_amount\">
                                                               <span class=\"text-danger\" style=\"font-size: 12px; font-weight: bold\" id=\"approved_duration_year\"></span>
                                                        </div>
                                                        </div>
                                                    
                                                  
                                                </div>
                                            </div>
                                        </fieldset>
                                        </div>
                                        ";
                                $html.= '<button style="margin:8px 0px;" type="button" value="'.$list->process_list_board_id.'" class="btn btn-md btn-primary individual_action_save"><i class="fa fa-save"></i> Process</button>&nbsp;';
                            }

                        }
                    }
                }else{
                    $html .= '<span class="label label-'.$list->panel.'">'.$list->bm_status.'</span>';
                }

                return $html;
            })
            ->editColumn('select_btn', function ($list) {
                $html = "";
                //if($list->bm_remarks == "" && $list->agendaStatus == 0) {
                if ($list->bm_status_id <= 0 || $list->bm_status_id == null) {
                    $html = "<input type='checkbox' value='$list->process_list_board_id' class='checkbox' name='checkbox[$list->process_list_board_id]'>";
                }
                return $html;
            })
            ->removeColumn('id', 'ref_id', 'process_type_id', 'updated_by', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
            ->make(true);
    }

    protected function getBasicInfoCompanyName($company_id)
    {
        $basicAppInfo = ProcessList::leftjoin('ea_apps', 'ea_apps.id', '=', 'process_list.ref_id')
            ->where('process_list.process_type_id', 100)
            ->where('process_list.status_id', 25)
            ->where('process_list.company_id', $company_id)
            ->first(['ea_apps.company_name']);
        return $basicAppInfo->company_name;
    }

    // common update batch
    public function updateRemarks(Request $request)
    {
        $board_meeting_id = 0;
        if (!empty($request->get('checkbox'))) {
            DB::beginTransaction();
            foreach ($request->get('checkbox') as $key=>$value) {
                $chairman = 0;
                $bm_process_list = ProcessListBoardMeting::where('id', $key)->first();
                $board_meeting_id = $bm_process_list->board_meeting_id;
                $bm_chairman = Committee::where('board_meeting_id', $bm_process_list->board_meeting_id)
                    ->where('type','Yes')
                    ->first();
                BoardMeting::where('id',$bm_process_list->board_meeting_id)->update(['sequence_no'=>5]);

                if ($request->get('bm_status_id') != "") {
                    $bm_status = $request->get('bm_status_id');
                } else {
                    $bm_status =  $request->get('status_for_'.$key);
                }

                if ($request->get('remarks') != "") {
                    $remarks = $request->get('remarks');
                } else {
                    $remarks = $request->get('remark_'.$key);
                }

                $getChairmensRemarks = ProcessListBoardMeting::where('id', $key)->pluck('bm_remarks');

                if (count($bm_chairman) > 0) {
                    /* if auth user is chairmen of Board Meeting then remarks will be save on
                    * ProcessListBoardMeting table -> bm_remarks field
                    */
                    if (Auth::user()->user_email == $bm_chairman->user_email && $bm_status > 0) { // on for meeting chairman
                        $chairman = 1;
//                        ProcessListBoardMeting::where('id', $key)->update([
//                            'bm_status_id' => $bm_status,
//                            'bm_remarks' => $remarks,
//                        ]);

                        $updateProcessMeeting = ProcessListBoardMeting::where('id', $key)->first();
                        $updateProcessMeeting->bm_status_id = $bm_status;
                        $updateProcessMeeting->bm_remarks = $remarks;
                        $updateProcessMeeting->save();

                        $boardMeeting = new BoardMetingController();
                        $result = $boardMeeting->individualCompleteAction($board_meeting_id, $updateProcessMeeting);
                        if (!$result) {
                            Session::flash('error', 'Something wrong. [AC-1001]');
                        }
                    }
                }

                /* if auth user is not chairmen of Board Meeting then remarks will be save on
                * process_list_bm_remarks table -> remarks field
                */
                if ($getChairmensRemarks == "" || (Auth::user()->user_email == $bm_chairman->user_email)) {
                    ProcessListBMRemarks::where('user_id', Auth::user()->id)->where('bm_process_id', $key)->delete();
                    $processRemark = new ProcessListBMRemarks();
                    $processRemark->bm_process_id = $key;
                    $processRemark->user_id = Auth::user()->id;
                    $processRemark->chairman = $chairman;
                    $processRemark->remarks = $remarks;
                    $processRemark->save();
                }
            }


//            $this->countPendingAgenda($board_meeting_id,$request->agenda_name);
            $pendingAgendaCount = ProcessListBoardMeting::where('board_meeting_id', $board_meeting_id)
                ->whereIn('bm_status_id',['',0])
                ->where('pl_agenda_name','!=','')
                ->count();
            if ($pendingAgendaCount == 0) {
                $board_meeting_data = BoardMeting::find($board_meeting_id);
                $chairmen = Committee::where('board_meeting_id', $board_meeting_id)->where('type','yes')->first();
                if (!empty($chairmen->user_email)) {

                    if ((Auth::user()->user_email == $chairmen->user_email) && $board_meeting_data->status == 6) {
                        $boardMeeting = new BoardMetingController();
                        $result = $boardMeeting->completeMeeting($board_meeting_id);
                        if ($result) {
                            DB::commit();
                            Session::flash('success', 'Your board meeting has been accomplished!!');
                            return Redirect::back();
                        } else {
                            DB::rollback();
                            Session::flash('error', 'Something was wrong!!');
                            return Redirect::back();
                        }
                    }
                }
            }
            DB::commit();
            Session::flash('success', 'Your status has been updated!!');
        }
        else{
            Session::flash('error', 'Please select a process from process list!');
        }

        return Redirect::back();
    }

    public function getAgendaProcessRemarks(Request $request){
        $bm_process_id = $request->get('bm_process_id');
        $remarks = ProcessListBMRemarks::
        leftJoin('users', 'users.id', '=', 'process_list_bm_remarks.user_id')
            ->where('bm_process_id',$bm_process_id)
            ->select('process_list_bm_remarks.chairman','users.user_email','users.user_pic','process_list_bm_remarks.remarks',DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name) as user_full_name"))
            ->get();
//            ->get(['process_list_bm_remarks.chairman','users.user_full_name','users.user_email','users.user_pic','process_list_bm_remarks.remarks']);

        return response()->json(['responseCode' => 1, 'status' => 'success', 'data' => $remarks]);
    }

    public function getAgendaRemarks(Request $request){
        $agendaId = Encryption::decodeId($request->get('agendaId'));
        $remarks = AgendaRemarks::leftJoin('users', 'users.id', '=', 'agenda_list_remarks.user_id')
            ->where('agenda_id',$agendaId)
            ->select('users.user_email','agenda_list_remarks.chairman', 'users.user_pic','agenda_list_remarks.remarks',DB::raw("CONCAT(users.user_first_name,' ',users.user_middle_name, ' ',users.user_last_name) as user_full_name"))
            ->get();

        return response()->json(['responseCode' => 1, 'status' => 'success', 'data' => $remarks]);
    }

    public function agendaWiseProcessList(Request $request, $status = '', $desk = '')
    {
        $process_type_id = session('active_process_list');
        $status == '-1000' ? '' : $status;
        $list = ProcessListBoardMeting::getBoardMeetingList($process_type_id, $status, $request, $desk);
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $agenda = CommonFunction::alreadyAddedAgenda($agenda_id);
        return Datatables::of($list)
            ->addColumn('action', function ($list) use ($status, $request,$agenda,$agenda_id) {
                $html = '<a target="_blank" href="' . url($list->form_url . '/view-app/' . Encryption::encodeId($list->ref_id)). '" class="btn btn-xs btn-primary"> <i class="fa fa-folder-open"></i> Open</a>  &nbsp;';

                $boardMeetingStatus = BoardMeting::where('id', Encryption::decodeId($request->get('board_meeting_id')))->first(['status']);

                if(!in_array($boardMeetingStatus->status,[5,10]))  { //5=fixed
//                    $alreadyAdd = CommonFunction::alreadyAdded($list->id, $agenda_id);
//                    if ($alreadyAdd == 1) {
//                        $html .= '<button class="btn btn-warning btn-xs">Already Added</button>';
//                    } else {
//                        $html .= '<button value="' . $list->id . '" class="add_to_board btn btn-warning btn-xs"> Add to Board Meeting </button>';
//                    }
                }
                $html .= '<button value="' . $list->id . '" class="add_to_board btn btn-warning btn-xs"> Add to Board Meeting </button>';
                return $html;
            })
            ->editColumn('json_object', function ($list) {
                return @getDataFromJson($list->json_object);
            })
            ->addColumn('desk', function ($list) {
                return $list->desk_id == 0 ? 'Applicant' : $list->desk_name;
            })->addColumn('serial', function ($list) use ($agenda,$agenda_id) {

                // if ($agenda == 1) {
//                $alreadyAdd = CommonFunction::alreadyAdded($list->id, $agenda_id);
//                if ($alreadyAdd == 1) {
//                    $html = "<input type='checkbox' disabled checked value='$list->id' class='checkbox_process_disable' name='checkbox[$list->id]'>";
//                } else {
//                    $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
//                }
                // } else {
                //  $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
                // }
                $html = "<input type='checkbox' value='$list->id' class='checkbox_process' name='checkbox[$list->id]'>";
                return $html;
            })
            ->editColumn('updated_at', function ($list) {
                return CommonFunction::updatedOn($list->updated_at);
            })
            ->removeColumn('id', 'ref_id', 'process_type_id', 'closed_by', 'created_by', 'updated_by', 'desk_id', 'status_id', 'locked_by', 'ref_fields')
            ->make(true);
    }

    public function updateProcess(request $request)
    {
//        dd($request);
        $transferType = $request->get('meeting_transfer');
        $agenda_id = Encryption::decodeId($request->get('agenda_id'));
        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        if(isset($transferType) && $transferType == 'no'){
            $this->TransferProcessList($agenda_id, $board_meeting_id);
            return \redirect('board-meting/agenda/list/'.$request->get('board_meeting_id'));
        }
        try {

            $data = Agenda::where('board_meting_id', $board_meeting_id)
                ->where('id', $agenda_id)->first();

            if ($request->get('status_id') == 3) {
//                $NextBoardMeeting = BoardMeting::where('id', '>', $data->board_meting_id)->min('id');
                $NextBoardMeeting = BoardMeting::where('meting_date', '>', date("Y-m-d"))
                    ->where('is_active', '=', 1)
                    ->orderBy('meting_date')
                    ->first();
                if ($NextBoardMeeting == null) {
                    Session::flash('error', "Roll-over is not possible, Please create a upcoming board meeting");
                    return Redirect::back();
                }
                $NextBoardMeeting = $NextBoardMeeting->id;
                $status_id = $data->status;

            } else {
                $NextBoardMeeting = $data->board_meting_id; //Current Board Meeting id
                $status_id = $request->get('status_id');
            }

            Agenda::where('id', $agenda_id)
                ->update([
                    'status' => $status_id,
//                    'remarks' => $request->get('remarks'),
                    'board_meting_id' => $NextBoardMeeting,
                    'previous_board_meeting_id' => $board_meeting_id,
                ]);
            $bm_chairman = Committee::where('board_meeting_id', $board_meeting_id)
                ->where('type','Yes')
                ->first();
            $getChairmensRemarksInAgenda = Agenda::where('id', $agenda_id)->pluck('remarks');

//            dd($agenda_id, $getChairmensRemarksInAgenda, $bm_chairman);

            if (count($bm_chairman) > 0){
                /* if auth user is chairmen of Board Meeting then remarks will be save on
                * ProcessListBoardMeting table -> bm_remarks field
                */
                if (Auth::user()->user_email == $bm_chairman->user_email){ // on for meeting chairman
                    $chairman = 1;
                    Agenda::where('id', $agenda_id)->update([
                        'remarks' => $request->get('remarks'),
                    ]);
                }
            }

            /* if auth user is not chairmen of Board Meeting then remarks will be save on
                * process_list_bm_remarks table -> remarks field
                */
//            dd($getChairmensRemarksInAgenda);
//            dd($agenda_id);
            if($getChairmensRemarksInAgenda == null || (Auth::user()->user_email == $bm_chairman->user_email)){

                $agendaRemarks = new AgendaRemarks();
                $agendaRemarks->agenda_id = $agenda_id;
                $agendaRemarks->user_id = Auth::user()->id;
                $agendaRemarks->remarks = $request->get('remarks');
                $agendaRemarks->save();
            }


            Session::flash('success', 'Your status has been updated!!');
            return Redirect::back()->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [RC-1060]');
            return redirect()->back()->withInput();
        }
    }

    protected function TransferProcessList($agenda_id, $board_meeting_id){

        DB::beginTransaction();
        $datas = ProcessListBoardMeting::leftJoin('process_list_bm_remarks', 'process_list_board_meeting.id', '=', 'process_list_bm_remarks.bm_process_id')
            ->where('agenda_id',$agenda_id)->where('board_meeting_id',$board_meeting_id)->get(['process_list_board_meeting.id']);

        foreach ($datas as $row){
            ProcessListBMRemarks::where('bm_process_id',$row->id)->delete();
        }
        ProcessListBoardMeting::where('agenda_id',$agenda_id)->where('board_meeting_id',$board_meeting_id)->delete();
        AgendaRemarks::where('agenda_id',$agenda_id)->delete();
        Agenda::where('id',$agenda_id)->delete();
        DB::commit();


    }

    public function deleteItem(Request $request)
    {
        $id = $request->get('process_list_board_id');
        ProcessListBoardMeting::where('id', $id)->delete();
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    public function getRollOverDate(Request $request)
    {
        $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
        $upComing = BoardMeting::where('meting_date', '>', date("Y-m-d"))
            ->where('is_active', '=', 1)
            ->orderBy('meting_date')
            ->first();
//        $NextBoardMeeting = BoardMeting::where('id', $upcomming)->min('meting_date');
        if($upComing != null){
            $NextBoardMeetingDate ="<label class='alert alert-success' style='font-weight: bold'>It will be transfer to the meeting date: ".date("d-M-Y", strtotime($upComing->meting_date))."  &nbsp;<label><input type='radio' name='meeting_transfer' value='yes'>Yes</label>&nbsp;<label><input name='meeting_transfer' type='radio' checked value='no'> No and forward to process list </label> </div>";
            $status = true;
        } else {
            $NextBoardMeetingDate = "<div class='alert alert-danger text-bold'style='font-weight: bold'>Roll-over is not possible, Please create a upcoming board meeting or forward to process list <label><input type='radio' name='meeting_transfer' checked value='no'>Yes</label> </div>";
            $status = false;
        }
        return response()->json(['responseCode' => 1, 'data' => $NextBoardMeetingDate, 'status' => $status]);
    }

    public function deleteBoardMeetingProcess(Request $request)
    {
        if($request->get('process_list_board_meeting_ids')){
            foreach($request->get('process_list_board_meeting_ids') as $value){
                ProcessListBoardMeting::where('id',$value)->delete();
            }
        }
        return response()->json(['responseCode' => 1, 'status' => 'success']);
    }

    protected function saveIndividualAction(Request $request)
    {
        try {

            $process_list_id = $request->get('process_list_id');
            $board_meeting_id = Encryption::decodeId($request->get('board_meeting_id'));
            $chairman = 0;
//        $bm_process_list = ProcessListBoardMeting::where('id', $process_list_id)->first();
            $bm_chairman = Committee::where('board_meeting_id', $board_meeting_id)
                ->where('type','Yes')
                ->first();

            DB::beginTransaction();
            $board_meeting_data = BoardMeting::where('id', $board_meeting_id)->first();
            $board_meeting_data->sequence_no = 5;
            $board_meeting_data->save();
//            BoardMeting::where('id',$board_meeting_id)->update(['sequence_no'=>5]);

            $remarks = $request->get('remarks');
            $bm_status = $request->get('bm_status_id');
            if (count($bm_chairman) > 0) {
                if (Auth::user()->user_email == $bm_chairman->user_email) { // on for meeting chairman
                    $chairman = 1;
                    $updateProcessMeeting = ProcessListBoardMeting::where('id', $process_list_id)->first();
                    $updateProcessMeeting->bm_status_id = $bm_status;
                    $updateProcessMeeting->bm_remarks = $remarks;
                    if(!empty($request->get('approved_duration_start_date'))){
                        $updateProcessMeeting->duration_start_date_from_dd = date('Y-m-d', strtotime($request->get('approved_duration_start_date')));
                    }
                    if(!empty($request->get('approved_duration_end_date'))){
                        $updateProcessMeeting->duration_end_date_from_dd = date('Y-m-d', strtotime($request->get('approved_duration_end_date')));
                    }
                    if(!empty($request->get('approved_desired_duration'))){
                        $updateProcessMeeting->desired_duration_from_dd = $request->get('approved_desired_duration');
                    }
                    if(!empty($request->get('approved_duration_amount'))){
                        $updateProcessMeeting->duration_amount_from_dd = $request->get('approved_duration_amount');
                    }
                    $updateProcessMeeting->save();
//                    ProcessListBoardMeting::where('id', $process_list_id)->update([
//                        'bm_status_id' => $bm_status,
//                        'bm_remarks' => $remarks,
//                    ]);
                }
            }

            ProcessListBMRemarks::where('user_id', CommonFunction::getUserId())->where('bm_process_id', $process_list_id)->delete();
            $processRemark = new ProcessListBMRemarks();
            $processRemark->bm_process_id = $request->get('process_list_id');
            $processRemark->user_id = CommonFunction::getUserId();
            $processRemark->chairman = $chairman;
            $processRemark->remarks = $remarks;
            $processRemark->save();

            // checking self chairman
            if (count($bm_chairman) > 0) {
                if (Auth::user()->user_email == $bm_chairman->user_email) { // only for meeting chairman
                    // individual action start
                    $boardMeeting = new BoardMetingController();
                    $result = $boardMeeting->individualCompleteAction($board_meeting_id, $updateProcessMeeting);
                    if (!$result) {
                        Session::flash('error', 'Something wrong. [AC-1002]');
                    }
                }
            }

            $pendingAgendaCount = ProcessListBoardMeting::where('board_meeting_id', $board_meeting_id)
                ->whereIn('bm_status_id',['',0])
                ->where('pl_agenda_name','!=','')
                ->count();

            if ($pendingAgendaCount == 0) {
//                $board_meeting_data = BoardMeting::find($board_meeting_id);
//                $chairmen = Committee::where('board_meeting_id', $board_meeting_id)->where('type','yes')->first();
                if (!empty($bm_chairman->user_email)) {
                    if ((Auth::user()->user_email == $bm_chairman->user_email) && $board_meeting_data->status == 6) {

                        $boardMeeting = new BoardMetingController();
                        $result = $boardMeeting->completeMeeting($board_meeting_id);
                        if ($result) {
                            DB::commit();
                            //last application from individual run the condition return final output
                            return response()->json(['responseCode' => 1, 'status' => 'success','is_complete'=>1,'is_final'=>1]);
                        } else {
                            return response()->json(['responseCode' => 1, 'status' => 'success','is_complete'=>2,'is_final'=>0]);
                        }
                    }
                }
            }

//            DB::rollback();
//            return response()->json(['responseCode' => 1, 'status' => 'success','is_complete'=>3]);
            DB::commit();
            return response()->json(['responseCode' => 1, 'status' => 'success','is_complete'=>0,'is_final'=> 0]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['responseCode' => 1, 'status' => 'success','is_complete'=>3,'is_final'=> 0]);
        }
    }

    public function pdfview()
    {
        $areaInfo = DB::table('area_info')->get();

        $html = view("BoardMeting::bangla", compact('areaInfo'));

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults(); // extendable default Configs
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults(); // extendable default Fonts
        $fontData = $defaultFontConfig['fontdata'];

        $mpdf = new mPDF([
            'tempDir'       => storage_path(),
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts'), // to find like /public/fonts/SolaimanLipi.ttf
            ]),
            'fontdata' => $fontData + [
                    'solaimanlipi' => [
                        'R' => "SolaimanLipi.ttf",
                        'useOTL' => 0xFF,
                    ],
                    'nikosh' => [
                        'R' => "Nikosh.ttf",
                        'useOTL' => 0xFF,
                    ],
                    //... you can add more custom font here
                ],
            'default_font' => 'solaimanlipi', // default font is not mandatory, you can use in css font
        ]);

        $mpdf->WriteHTML($html);
        $mpdf->Output('bangla_text.pdf','I'); exit;
    }



    public function countPendingAgenda($board_meeting_id, $agenda_name){

        $pendingAgendaCount = ProcessListBoardMeting::where('board_meeting_id', $board_meeting_id)
            ->where('pl_agenda_name',$agenda_name)
            ->whereIn('bm_status_id',['',0])
            ->where('pl_agenda_name','!=','')
            ->count();
        if($pendingAgendaCount == 0){
            Agenda::where('board_meting_id', $board_meeting_id)
                ->where('name',$agenda_name)
                ->update([
                    'status' => 1,
//                    'remarks' => $request->get('remarks'),
                    // 'board_meting_id' => $NextBoardMeeting,
                    // 'previous_board_meeting_id' => $board_meeting_id,
                ]);
        }
        // dd($pendingAgendaCount);
    }
}
