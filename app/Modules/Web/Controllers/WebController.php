<?php namespace App\Modules\Web\Controllers;

use App\Http\Controllers\Controller;

use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\BidaRegistration\Models\BidaRegistration;
use App\Modules\BidaRegistrationAmendment\Models\BidaRegistrationAmendment;
use App\Modules\Dashboard\Models\DashboardObjectDynamic;
use App\Modules\ProcessPath\Models\ProcessHistory;
use App\Modules\Reports\Models\ReportHelperModel;
use App\Modules\Settings\Models\Bank;
use App\Modules\Settings\Models\Configuration;
use App\Modules\Settings\Models\PdfPrintRequestQueue;
use App\Modules\Settings\Models\RegulatoryAgency;
use App\Modules\Settings\Models\RegulatoryAgencyDetails;
use App\Modules\Settings\Models\ServiceDetails;
use App\Modules\Settings\Models\UserManual;
use App\Modules\Training\Models\TrainingParticipants;
use App\Modules\Training\Models\TrainingResource;
use App\Modules\Training\Models\TrainingSchedule;
use App\Modules\Users\Models\AreaInfo;
use App\Modules\Users\Models\UsersModelEditable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Validator;
use yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Log;


class WebController extends Controller
{

    public function loadPageObjects(Request $request)
    {
        $key = $request->get('key');
        $response = $this->getPageTitles($key);

        if ($response) {
            $result = json_decode($response);
            $HtmlData = '';
            foreach ($result as $row) {
                $HtmlData .= '<div data-layout="' . $row->layout . '" class="' . $row->key . '" id="' . $row->key . '" ><a  href="#' . $row->key . '" onclick="loadObject(\'' . $row->key . '\')"><div class="text-center widget-custom"><div>' . $row->title . '</div><div class="vk_ard"></div></div></a>';

                $HtmlData .= '</div>';
            }
            return array('responseCode' => 1, 'data' => $HtmlData);
        } else {
            return array('responseCode' => 0, 'data' => 'Data not found.');
        }
    }

    public function loadDashboardObjectsList(Request $request)
    {
        $key = $request->get('key');

        try {
            $layout = strtolower(DashboardObjectDynamic::where('key', $key)->where('status',1)->pluck('layout'));
            $HtmlData = '';

            switch ($layout) {
                case "html":
                case "javascript":
                    $dashboard_object = DashboardObjectDynamic::where('key', $key)->where('status',1)->first(['title', 'is_encrypted', 'response']);

                    if ($dashboard_object != null) {

                        $objRh = new ReportHelperModel();
                        $converted_response = $objRh->ConvParaEx($dashboard_object->response, $request->get('input'));
                        $HtmlData .= '<h3 style="text-align: center;" class="hide-title">' . $dashboard_object->title . '</h3>';
                        $response = $converted_response;
                        if ($dashboard_object->is_encrypted == 1) {
                            $response = Encryption::dataDecode($response);
                        }
                        $HtmlData .= $response;
                    }
                    break;

                case "json":
                    $HtmlData = $this->getDashboardObjectsJson($key, $request->get('input'));
                    break;

                case "html_cm":
                    $response = $this->getDashboardObjectsJson($key, $request->get('input'));
                    if (empty($response) or $response == null) {
                        $HtmlData = 'Data not found.';
                    } else {
                        $result = json_decode($response);
                        $HtmlData .= '<h3 style="text-align: center;" class="hide-title">' . $result->title . '</h3>';
                        $HtmlData .= createHTML_CM($result->json);
                    }
                    break;

                default:

                    $response = $this->getDashboardObjectsJson($key, $request->get('input'));
                    if (empty($response) or $response == null) {
                        $HtmlData = 'Data not found.';
                    } else {

                        $result = json_decode($response);

                        $HtmlData .= '<h3 style="text-align: center;" class="hide-title">' . $result->title . '</h3>';
                        if (count($result->json) > 0) {
                            // $HtmlData .= createHTMLTable($result->json, 2000, $key);
                            $HtmlData .= createHTMLTable($result->json, 2000);
                            $HtmlData .= '<h1 style="text-align: right;font-size: 12px;color:gray;">Report Generated on: ' . $result->updated . '</h1>';
                        } else {
                            $HtmlData .= 'Data not found.';
                        }

                    }

            }

            return array('responseCode' => 1, 'data' => $HtmlData, 'layout' => $layout);
        } catch
        (\Exception $e) {
            return array('responseCode' => 1, 'data' => Utility::eMsg($e, 'WC001', 'Bad record found'));
        }
    }

    public static function getPageTitles($key)
    {
        $object_details = DashboardObjectDynamic::where('pages', 'like', "%" . $key . "]%")->where('status',1)->get(['title', 'key', 'layout']);
        if ($object_details) {

//            $return_response = json_encode(array(
//                    'key'=>$key,
//                    'title'=>$object_details->title
//            ));
            return $object_details;
        } else {
            return null;
        }
    }

    public static function getDashboardObjectsJson($key, $input = '')
    {
        $data = array('responseCode' => 0, 'data' => '');
        $response = '';
        $object_details = DashboardObjectDynamic::where('key', $key)->where('status',1)->first();
        if ($object_details) {

            // Set query as Decrypted if encrypted
            $query = $object_details->query;
            if ($object_details->is_encrypted == 1) {
                $query = Encryption::dataDecode($object_details->query);
            }
            $updated = date('Y-m-d H:i:s', strtotime($object_details->updated_at));
            if ($object_details->updated_at && $object_details->updated_at > '0') {
                $extended_time = Carbon::parse($object_details->updated_at)->addSeconds($object_details->time_limit);
            } else {
                $extended_time = Carbon::now();
            }

            if ($extended_time <= Carbon::now() && $query) { // limited time over
                if ($input) {
                    $objRh = new ReportHelperModel();
                    $sql = $objRh->ConvParaEx($query, $input);
                } else {
                    $sql = $query;
                }
                try {
                    $response = json_encode(DB::select(DB::raw($sql)));
                } catch (\Exception $e) {
                    if ($e->getMessage())
                        return null;
                }
                DashboardObjectDynamic::where('key', $key)->update([
                    'response' => $response,
                    'updated_at' => Carbon::now()
                ]);
                $updated = date('Y-m-d H:i:s');
            } else {
                // limited time is not over
                $response = $object_details->response;
            };

            $return_response = json_encode(array(
                'key' => $key,
                'title' => $object_details->title,
                'layout' => $object_details->layout,
                'json' => json_decode($response),
                'updated' => $updated
            ));
            return $return_response;
        } else {
            return null;
        }
    }

    public function loadDashboardObjectsChart($key)
    {
        $result = json_decode($this->getDashboardObjectsJson($key));
        if ($result != null) {
            if ($key == 'PRE_HALNAGAT') {
                return view('public_home.report-dashboard-object', compact('result'));
            } elseif ($key == 'REG_HALNAGAT') {
                return view('public_home.report-dashboard-object', compact('result'));
            }
        } else {
            return 'Data not found.';
        }
    }

    public function getTrainingPublicSchedule(Request $request)
    {
        $training_id = Encryption::decodeId($request->get('training_id'));
        if (!$training_id) {
            return 'Invalid data!!!';
        }
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d H:i:s', time());
        $schedule_list = TrainingSchedule::where('training_id', $training_id)
            ->leftJoin('trainings', 'training_schedule.training_id', '=', 'trainings.id')
            ->leftJoin('training_participants as tp', function ($query) {
                $query->on('tp.training_schedule_id', '=', 'training_schedule.id');
                $query->whereIn('tp.status', [1, 2, 3]);
            })
//			->where('training_schedule.end_time', '>', $date)
            ->where('training_schedule.status', '1')
            ->groupBy('training_schedule.id')
            ->orderBy('training_schedule.start_time', 'asc')
            ->get([DB::raw('count(tp.id) as total_participant'), 'training_schedule.total_seats', 'training_schedule.id', 'training_schedule.trainer_name', 'training_schedule.location', 'training_schedule.start_time', 'training_schedule.end_time', 'trainings.title as training_title', 'trainings.public_user_types as public_user_types']);
        $training_resource = TrainingResource::where('training_id', $training_id)
            ->where('status', '=', 2)
            ->where('is_deleted', 0)
            ->get();
        return view('Training::public-training.schedule', compact('schedule_list', 'training_resource'));

    }

    public function applyForm(Request $request)
    {
        $schedule_id = Encryption::decodeId($request->get('schedule_id'));
        $training_info = TrainingSchedule::leftJoin('trainings', 'trainings.id', '=', 'training_schedule.training_id')
            ->where('training_schedule.id', $schedule_id)->first(['trainings.public_user_types', 'location', 'trainings.title as title', 'training_schedule.total_seats as total_seats', 'trainings.user_types as user_types', 'training_schedule.start_time as start_time', 'training_schedule.end_time as end_time']);
        $user_type = explode(",", $training_info->user_types);
        $total_participants = TrainingParticipants::where('training_schedule_id', $schedule_id)->whereIn('status', [1, 2, 3, 4])->count();
        $scheduleData = TrainingSchedule::where('id', $schedule_id)->first(['total_seats', 'start_time']);
        //		get the current time and date


        $info['total_applied'] = TrainingParticipants::where('training_schedule_id', $schedule_id)->whereIn('status', [1, 2, 3, 4])->count();
        $info['total_verified'] = TrainingParticipants::where('training_schedule_id', $schedule_id)->whereIn('status', [2, 3, 4])->count();
        $districts = AreaInfo::where('area_type', 2)->orderBy('area_nm', 'ASC')->lists('area_nm', 'area_nm')->all();
        $bank = Bank::orderBy('id', 'desc')->lists('name', 'name')->all();


        date_default_timezone_set('Asia/Dhaka');
        $current_time = date('Y-m-d h:i:s', time());
        if (!$schedule_id) {
            return response()->json(['responseCode' => 2, 'public_html' => '', 'msg' => 'Invalid data!!!']);
        } else if ($total_participants >= $scheduleData['total_seats']) {
            return response()->json(['responseCode' => 2, 'public_html' => '', 'msg' => 'Sorry All seat has been booked!!!']);
        } else if (strtotime($current_time) >= strtotime($scheduleData['start_time'])) {
            return response()->json(['responseCode' => 2, 'public_html' => '', 'msg' => 'Sorry Booking time already over!!!']);
        }
        $public_html = strval(view('Training::public-training.apply', compact('schedule_id', 'training_info', 'user_type', 'info', 'districts', 'bank')));
        return response()->json(['responseCode' => 1, 'public_html' => $public_html, 'msg' => 'Sorry All seat has been booked!!!']);
    }

    public function applyPublicTraining(Request $request)
    {
        $schedule_id = Encryption::decodeId($request->get('schedule_id'));
        $training_info = TrainingSchedule::leftJoin('trainings', 'trainings.id', '=', 'training_schedule.training_id')
            ->where('training_schedule.id', $schedule_id)->first(['trainings.user_types as user_types', 'training_schedule.total_seats', 'training_schedule.end_time']);
        $user_type = explode(",", $training_info->user_types);
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:11|max:14',
            'district' => 'required',
            'trainee_nid' => 'digits_between:10,17|required|integer',
            'dob' => 'required',
            'g-recaptcha-response' => 'required'
        ];
        if (array_intersect($user_type, CommonFunction::bankUser())) {
            $rules['bank'] = 'required';
        }

        $agency_name = '';
        if (array_intersect($user_type, CommonFunction::agencyUser())) {
            $agency_name = Agency::where('license_no', '=', $request->get('agency_license'))->where('is_active', 1)->pluck('name');
            if ($agency_name == null) {
                return response()->json(['responseCode' => 4, 'msg' => 'Agency licence that you inserted is not valid or agency is inactive!!!']);
            }
            $rules['agency_license'] = 'required|integer|digits:4';
        }


        $validator = Validator::make($request->all(), $rules);
        $total_participants = TrainingParticipants::where('training_schedule_id', $schedule_id)->whereIn('status', [1, 2, 3, 4])->count();
        $current_training_id = TrainingSchedule::where('id', $schedule_id)->pluck('training_id');


        $trainee_exist = TrainingParticipants::leftJoin('training_schedule', 'training_participants.training_schedule_id', '=', 'training_schedule.id')
            ->where('training_schedule.training_id', $current_training_id)
            ->where('training_participants.email', $request->get('email'))
            ->whereIn('training_participants.status', ['1,2,3,4'])
            ->count();
        date_default_timezone_set('Asia/Dhaka');
        $date = date('Y-m-d H:i:s', time());
        if ($date >= $training_info->end_time) {
            return response()->json(['responseCode' => 6, 'msg' => 'Sorry, The Training is already over!!!']);
        }
        if ($total_participants >= $training_info->total_seats) {
            return response()->json(['responseCode' => 5, 'msg' => 'Sorry, All seat has been booked!!!']);
        } else if ($validator->fails()) {
            return response()->json(['responseCode' => 2, 'msg' => 'Please insert all information carefully.']);
        } else if ($trainee_exist > 0) {
            return response()->json(['responseCode' => 3, 'msg' => 'You have already applied to this training.']);
        }

        try {

            DB::beginTransaction();

            TrainingParticipants::create(
                array(
                    'training_schedule_id' => $schedule_id,
                    'name' => $request->get('name'),
                    'email' => $request->get('email'),
                    'mobile' => $request->get('phone'),
                    'district' => $request->get('district'),
                    'trainee_nid' => $request->get('trainee_nid'),
                    'bank' => $request->get('bank'),
                    'agency_license' => $request->get('agency_license'),
                    'agency_name' => $agency_name,
                    'dob' => date('Y-m-d', strtotime($request->get('dob'))),
                ));
            DB::commit();
            $data = ['responseCode' => 1, 'msg' => '<div class="row well"><span class="text-success text-center"><strong>Thank You. You are successfully registered in this Training.</strong></span></div>'];

        } catch (\Exception $e) {
            DB::rollback();
            $data = ['responseCode' => 0, 'msg' => '<span class="text-success"><b>Something was wrong. Please try again.<span class="text-success"><b>'];
        }
        return response()->json($data);
    }

//    Public Training Video Resource
    public function publicTrainingVideo($id)
    {
        $resource_id = Encryption::decodeId($id);
        $resourceDetail = TrainingResource::where('id', $resource_id)
            ->first();
        return view("Web::training-public-video", compact('resourceDetail'));
    }

    public function chartRender(Request $request)
    {

        $key = $request->get('key');
        switch ($key) {
            case 'AP_DIVISION_DISTRICT':
                $sql = "select ai.area_nm as TITLE, count(ai2.area_id) as VALUE 
                        from area_info ai inner join area_info ai2 on ai.pare_id = 0 and ai.area_id = ai2.pare_id
                        group by ai.area_id";

                $info = DB::select($sql);
                $data = array();
                $inc = 0;
                if (count($info) > 0) {
                    foreach ($info as $inf) {
                        $data[$inc]['total_thana'] = $inf->VALUE;
                        $data[$inc]['division'] = $inf->TITLE;
                        $inc++;
                    }
                }
                break;
            default:
                $data = array();
                break;
        }

        return response()->json($data);
    }

    public function getHomePageDODObject(Request $request)
    {
//        $agency_id = $request->get('agency_id');
//        $session_id = $request->get('session_id');
//        $decoded_session_id = Encryption::decodeId($session_id);
        $decoded_session_id = 11; //static data demo data
        $key = $request->get('key');

        $dod = DashboardObjectDynamic::where('key', '=', $key)->where('layout', 'javascript')->where('status',1)->first(['query', 'is_encrypted', 'response']);
//        CommonFunction::ddd($key);
        $content = '';
        if ($dod != null) {
            if ($dod->is_encrypted == 1) {
                $content = \App\Libraries\Encryption::dataDecode($dod->response);
            } else {
                $content = $dod->response;
            }

//            $content = str_replace('##AGENCY_ID##', "'" . $agency_id . "'", $content);
//            $content = str_replace('##SESSION_ID##', "'" . $session_id . "'", $content);
            $content = str_replace('##DECODE_SESSION_ID##', $decoded_session_id, $content);
            $content = str_replace('##DOD_KEY##', "'" . $key . "'", $content);

        }
        return ['responseCode' => 1, 'data' => $content];
    }

    public function twoStep()
    {
        try {
            return view("Users::two-step");
        } catch (\Exception $e) {
            Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()));
            return Redirect::back()->withInput();
        }
    }

    public function checkTwoStep(Request $request)
    {
        try {
            $steps = $request->get('steps');
            $code = rand(1000, 9999);
            $user_email = Session::getFacadeApplication('email');
            //$user_phone = Session::get('phone');
            //$token = $code;
            $encrypted_token = Encryption::encode($code);
            UsersModelEditable::where('user_email', $user_email)->update(['auth_token' => $encrypted_token]);
            $emailQueueId = EmailQueue::where('user_id', Auth::user()->id)->orderby('id', 'DESC')->first(['id']);
            Session::put('email_queue_id', $emailQueueId->id);
            if ($request->get('req_dta') != null) {
                $req_dta = $request->get('req_dta');
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone', 'req_dta'));
            } else {
                return view("Users::check-two-step", compact('steps', 'user_email', 'user_phone'));
            }
        } catch (\Exception $e) {
            Session::flash('error', 'Sorry! Something is Wrong.');
            return Redirect::back()->withInput();
        }
    }

    public function getDocs($pdfType, $docId)
    {
        $pdf_request_info = PdfPrintRequestQueue::where(
            [
                'pdf_type' => $pdfType,
                'doc_id' => $docId
            ])
            ->first();
        if (empty($pdf_request_info)) {
            dd('Something went wrong! pdf not found [WC-100]');
        }

        if (in_array($pdf_request_info->process_type_id, [103, 104, 105, 106, 107, 108])) {

            $pdfCertificateData = DB::select(DB::raw('SELECT
                   pdf_print_requests_queue.certificate_link AS certificate_link,
                   (
                      SELECT
                         name 
                      FROM
                         process_type 
                      WHERE
                         pdf_print_requests_queue.process_type_id = process_type.id LIMIT 1
                   )
                   AS process_name,
                   applicant.user_full_name AS applicant_name,
                   recommeder.user_full_name AS recommeder_name,
                   approver.user_full_name AS approver_name,
                   applicant.user_email AS applicant_email,
                   recommeder.user_email AS recommeder_email,
                   approver.user_email AS approver_email,
                   applicant.user_hash AS applicant_hash,
                   recommeder.user_hash AS recommeder_hash,
                   approver.user_hash AS approver_hash,
                   (
                      SELECT
                         updated_at 
                      FROM
                         process_list_hist 
                      WHERE
                         process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                         AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                         AND process_list_hist.status_id = 1 
                      ORDER BY
                         process_list_hist.updated_at ASC LIMIT 1
                   )
                   AS applicant_time ,
                   (
                      SELECT
                         updated_at 
                      FROM
                         process_list_hist 
                      WHERE
                         process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                         AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                         AND process_list_hist.status_id != 1 
                      ORDER BY
                         process_list_hist.updated_at DESC LIMIT 1 offset 1
                   )
                   AS recommender_time,               
                   (
                      SELECT
                         updated_at 
                      FROM
                         process_list_hist 
                      WHERE
                         process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                         AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                         AND process_list_hist.status_id != 1 
                      ORDER BY
                         process_list_hist.updated_at DESC LIMIT 1
                   )
                   AS approval_time
                   
                FROM
                   pdf_print_requests_queue 
                   LEFT JOIN
                      users AS applicant 
                      ON applicant.id = 
                      (
                         SELECT
                            updated_by 
                         FROM
                            process_list_hist 
                         WHERE
                            process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                            AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                            AND process_list_hist.status_id = 1 
                         ORDER BY
                            process_list_hist.updated_at DESC LIMIT 1
                      )
                   LEFT JOIN
                      users AS recommeder 
                      ON recommeder.id = 
                      (
                         SELECT
                            updated_by 
                         FROM
                            process_list_hist 
                         WHERE
                            process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                            AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                            AND process_list_hist.status_id != 1 
                         ORDER BY
                            process_list_hist.updated_at DESC limit 1 offset 1
                      )
                   LEFT JOIN
                      users AS approver 
                      ON approver.id = 
                      (
                         SELECT
                            updated_by 
                         FROM
                            process_list_hist 
                         WHERE
                            process_list_hist.process_id = pdf_print_requests_queue.process_type_id 
                            AND process_list_hist.ref_id = pdf_print_requests_queue.app_id 
                            AND process_list_hist.status_id != 1 
                         ORDER BY
                            process_list_hist.updated_at DESC limit 1
                      )
                WHERE
                   pdf_print_requests_queue.pdf_type = "bida.gvisa.d" 
                        AND pdf_print_requests_queue.doc_id = "1D615C84955" '));
            $pdfCertificate = (isset($pdfCertificateData[0]) && !empty($pdfCertificateData[0])) ? $pdfCertificateData[0] : null;
            return view('Web::view-certificate_stakeholder', compact('pdfCertificate', 'pdf_request_info'));

        } else {

            $help_information = Configuration::where('caption', 'HELPDESK_INFO')->first();

            $pdfCertificate = collect(DB::select(DB::raw("SELECT pdf_print_requests_queue.certificate_link AS certificate_link, process_type.name as process_name, submitted_at.process_id, submitted_at.company_id,
            Concat_ws(' ', applicant.user_first_name, applicant.user_middle_name, applicant.user_last_name) AS applicant_name, applicant.user_email AS applicant_email, 
            submitted_at.hash_value AS applicant_hash, submitted_at.updated_at as applicant_time, applicant.user_phone as applicant_phone,
            Concat_ws(' ', approver.user_first_name, approver.user_middle_name, approver.user_last_name) AS approver_name, approver.user_email AS approver_email, 
            approved_at.hash_value AS approver_hash, approved_at.updated_at as approval_time, approver.user_phone as approver_phone
            FROM pdf_print_requests_queue
            LEFT JOIN process_type on process_type.id = pdf_print_requests_queue.process_type_id
            LEFT JOIN process_list_hist as submitted_at on submitted_at.ref_id = pdf_print_requests_queue.app_id and submitted_at.process_type = pdf_print_requests_queue.process_type_id and submitted_at.status_id = 1
            LEFT JOIN process_list_hist as approved_at on approved_at.ref_id = pdf_print_requests_queue.app_id and approved_at.process_type = pdf_print_requests_queue.process_type_id and approved_at.status_id = 25
            LEFT JOIN users as applicant ON applicant.id = submitted_at.updated_by
            LEFT JOIN users as approver ON approver.id = pdf_print_requests_queue.signatory
            WHERE pdf_print_requests_queue.pdf_type = '$pdfType' AND pdf_print_requests_queue.doc_id = '$docId'
            order by submitted_at.id desc limit 1")))->first();

            if (empty($pdfCertificate)) {
                dd('Something went wrong! certificate data not found [WC-101]');
            }

            $recommender_info = ProcessHistory::where('process_id', $pdfCertificate->process_id)
                ->leftjoin('users', 'users.id', '=', 'process_list_hist.updated_by')
                ->where('process_list_hist.desk_id', '!=', 0)
                ->orderBy('process_list_hist.id', 'desc')
                ->skip(1)
                ->take(1)
                ->first([
                    DB::raw("Concat_ws(' ', users.user_first_name, users.user_middle_name, users.user_last_name) AS recommender_name"),
                    'users.user_email AS recommender_email',
                    'process_list_hist.id',
                    'process_list_hist.hash_value',
                    'process_list_hist.updated_at'
                ]);
            return view('Web::view-certificate', compact('pdfCertificate', 'pdf_request_info', 'recommender_info', 'help_information'));

        }
    }
    
    // public function getDirMacDocs($appId, $processTypeId)
    // {
    //     try{
    //         // $decodedAppId = CommonFunction::decodeId($appId);
    //         // $decodedProcessTypeId = CommonFunction::decodeId($processTypeId);
    //         $decodedAppId = Encryption::dataDecode($appId);
    //         $decodedProcessTypeId = Encryption::dataDecode($processTypeId);
    //         $pdf_request_info = PdfPrintRequestQueue::where([
    //             'app_id' => $decodedAppId,
    //             'process_type_id' => $decodedProcessTypeId,
    //         ])->first();
            
    //         if (empty($pdf_request_info)) {
    //             dd('Something went wrong! pdf not found [WC-200]');
    //         }

    //         if (in_array($decodedProcessTypeId, [102, 12])) {

    //             $help_information = Configuration::where('caption', 'HELPDESK_INFO')->first();

    //             $pdfCertificate = collect(DB::select(DB::raw("SELECT process_type.name as process_name, submitted_at.process_id, submitted_at.company_id,
    //             Concat_ws(' ', applicant.user_first_name, applicant.user_middle_name, applicant.user_last_name) AS applicant_name, applicant.user_email AS applicant_email, 
    //             submitted_at.hash_value AS applicant_hash, submitted_at.updated_at as applicant_time, applicant.user_phone as applicant_phone,
    //             Concat_ws(' ', approver.user_first_name, approver.user_middle_name, approver.user_last_name) AS approver_name, approver.user_email AS approver_email, 
    //             approved_at.hash_value AS approver_hash, approved_at.updated_at as approval_time, approver.user_phone as approver_phone
    //             FROM pdf_print_requests_queue
    //             LEFT JOIN process_type on process_type.id = pdf_print_requests_queue.process_type_id
    //             LEFT JOIN process_list_hist as submitted_at on submitted_at.ref_id = pdf_print_requests_queue.app_id and submitted_at.process_type = pdf_print_requests_queue.process_type_id and submitted_at.status_id = 1
    //             LEFT JOIN process_list_hist as approved_at on approved_at.ref_id = pdf_print_requests_queue.app_id and approved_at.process_type = pdf_print_requests_queue.process_type_id and approved_at.status_id = 25
    //             LEFT JOIN users as applicant ON applicant.id = submitted_at.updated_by
    //             LEFT JOIN users as approver ON approver.id = pdf_print_requests_queue.signatory
    //             WHERE pdf_print_requests_queue.process_type_id = '$decodedProcessTypeId' AND pdf_print_requests_queue.app_id = '$decodedAppId'
    //             order by submitted_at.id desc limit 1")))->first();
                
    //             if (empty($pdfCertificate)) {
    //                 dd('Something went wrong! certificate data not found [WC-201]');
    //             }

    //             $dirMachineryDoc = null;

    //             if ($decodedProcessTypeId == 102) {
    //                 $query = BidaRegistration::where('id', $decodedAppId);
    //             } elseif ($decodedProcessTypeId == 12) {
    //                 $query = BidaRegistrationAmendment::where('id', $decodedAppId);
    //             }

    //             if (isset($query)) {
    //                 $dirMachineryDoc = $query->first(['list_of_dir_machinery_doc']);
    //             }

    //             if (empty($dirMachineryDoc)) {
    //                 dd('Something went wrong! certificate data not found [WC-102]');
    //             }

    //             $dirMachineryDocfullPath = config('app.project_root').'/'.$dirMachineryDoc->list_of_dir_machinery_doc;

    //             $recommender_info = ProcessHistory::where('process_id', $pdfCertificate->process_id)
    //                 ->leftjoin('users', 'users.id', '=', 'process_list_hist.updated_by')
    //                 ->where('process_list_hist.desk_id', '!=', 0)
    //                 ->orderBy('process_list_hist.id', 'desc')
    //                 ->skip(1)
    //                 ->take(1)
    //                 ->first([
    //                     DB::raw("Concat_ws(' ', users.user_first_name, users.user_middle_name, users.user_last_name) AS recommender_name"),
    //                     'users.user_email AS recommender_email',
    //                     'process_list_hist.id',
    //                     'process_list_hist.hash_value',
    //                     'process_list_hist.updated_at'
    //                 ]);
    //             return view('Web::dir-mac-certificate', compact('dirMachineryDocfullPath', 'pdfCertificate', 'pdf_request_info', 'recommender_info', 'help_information'));
    //         }
    //     } catch (\Exception $e) {
    //         Log::error('ListOfDirectorMachinery : ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine() . ' [WC-103]');
    //         Session::flash('error', CommonFunction::showErrorPublic($e->getMessage()) . ' [WC-103]');
    //         return Redirect::back()->withInput();
    //     }
    // }
        

    public function getAgencyList(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }


        $ipaAgencies = RegulatoryAgency::leftJoin('regulatory_agencies_details', 'regulatory_agencies_details.regulatory_agencies_id', '=', 'regulatory_agencies.id')
            ->where('regulatory_agencies.is_archive', 0)
            ->where('regulatory_agencies.status', 1)
            ->groupBy('regulatory_agencies.id')
            ->where('agency_type', 'ipa')
//            ->sortBy('order')
            ->orderBy('regulatory_agencies.order', 'asc')
            ->get([
                'regulatory_agencies.id',
                'regulatory_agencies.name',
                'regulatory_agencies.description',
                'regulatory_agencies.url',
                'regulatory_agencies.order',
                'regulatory_agencies.agency_type',
                'regulatory_agencies.contact_name',
                'regulatory_agencies.designation',
                'regulatory_agencies.mobile',
                'regulatory_agencies.phone',
                'regulatory_agencies.email',
                'regulatory_agencies.updated_at',
                DB::raw('group_concat(regulatory_agencies_details.id) as regulatory_agencies_details_ids'),
                DB::raw('group_concat(regulatory_agencies_details.service_name) as regulatory_agencies_services')
            ]);

        $regulatory_agencies = RegulatoryAgency::leftJoin('regulatory_agencies_details', 'regulatory_agencies_details.regulatory_agencies_id', '=', 'regulatory_agencies.id')
            ->where('regulatory_agencies.is_archive', 0)
            ->where('regulatory_agencies.status', 1)
            ->groupBy('regulatory_agencies.id')
//            ->orderBy('regulatory_agencies.order', 'asc')
            ->get([
                'regulatory_agencies.id',
                'regulatory_agencies.name',
                'regulatory_agencies.description',
                'regulatory_agencies.url',
                'regulatory_agencies.order',
                'regulatory_agencies.agency_type',
                'regulatory_agencies.contact_name',
                'regulatory_agencies.designation',
                'regulatory_agencies.mobile',
                'regulatory_agencies.phone',
                'regulatory_agencies.email',
                'regulatory_agencies.updated_at',
                DB::raw('group_concat(regulatory_agencies_details.id) as regulatory_agencies_details_ids'),
                DB::raw('group_concat(regulatory_agencies_details.service_name) as regulatory_agencies_services')
            ]);

        // for home page view log
        CommonFunction::createHomePageViewLog('agencyInfo', $request);

        $content = strval(view('public_home.ipa_cpa_agency', compact('regulatory_agencies', 'ipa_regulatory_agencies_details', 'clp_regulatory_agencies_details')));
        return response()->json(['response' => $content]);
    }

    public function getSubAgencyContent($sub_agency_id, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $sub_agency_id = Encryption::decodeId($sub_agency_id);

        $regulatory_agency_details = RegulatoryAgencyDetails::where('id', $sub_agency_id)
            ->first([
                'id',
                'regulatory_agencies_id',
                'service_name',
                'is_online',
                'method_of_recv_service',
                'who_get_service',
                'documents',
                'fees',
                'updated_at'
            ]);

        $log_key = 'agencyInfo.details.'.$regulatory_agency_details->id;
        // for home page view log
        CommonFunction::createHomePageViewLog($log_key, $request, $regulatory_agency_details->id);

        $content = strval(view('public_home.sub_agency_content', compact('regulatory_agency_details')));
        return response()->json(['response' => $content]);
    }

    public function getAvailableService(Request $request)
    {
//        $dynamicSection = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
//            ->where('service_details.status', 1)
//            ->orderBy('pt.process_supper_name', 'asc')
//            ->orderBy('pt.process_sub_name', 'desc')
//            ->get(['pt.process_supper_name', 'pt.type_key','pt.process_sub_name', 'pt.id', 'service_details.id as sd_id']);
//
//        // for home page view log
//        CommonFunction::createHomePageViewLog('availableServiceInfo', $request);
//
//        $content = strval(view('public_home.available_service', compact('dynamicSection')));
//        return response()->json(['response' => $content]);


        //
        $queryResult = ServiceDetails::leftJoin('process_type as pt', 'pt.id', '=', 'service_details.process_type_id')
            ->where('service_details.status', 1)
            ->orderBy('pt.process_supper_name', 'asc')
            ->orderBy('pt.process_sub_name', 'desc')
            ->get(['pt.process_supper_name', 'pt.type_key', 'pt.process_sub_name', 'pt.id', 'service_details.id as sd_id', 'service_details.description']);

        $data['availableServices'] = [];
        $data['supperNameCount'] = 0;
        $data['subNameCount'] = 0;

        foreach ($queryResult as $row) {
            $supperName = $row->process_supper_name;
            $subName = $row->process_sub_name;

            if (empty($supperName) || empty($subName)) {
                continue;
            }

            if (!isset($data['availableServices'][$supperName])) {
                $data['availableServices'][$supperName] = [];
            }

            if (!isset($data['availableServices'][$supperName][$subName])) {
                $data['availableServices'][$supperName][$subName] = [
                    'sd_id' => $row->sd_id,
                    'description' => $row->description,
                ];
            }
        }

        CommonFunction::createHomePageViewLog('availableServiceInfo', $request);

        $content = strval(view('Web::home.available_online_services', $data));

        return response()->json(['response' => $content]);

    }

    public function getAvailableServiceDetails($service_detail_id, Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $service_detail_id = Encryption::decodeId($service_detail_id);

        $service_details = ServiceDetails::leftJoin('process_type', 'process_type.id', '=', 'service_details.process_type_id')
            ->where('service_details.id', $service_detail_id)
            ->first(['service_details.description', 'service_details.updated_at','process_type.type_key','process_type.id']);

        // for home page view log
        $log_key = 'availableServiceInfo.details.'.$service_details->type_key;
        CommonFunction::createHomePageViewLog($log_key, $request, $service_details->id);

        $service_details->last_update = Carbon::parse($service_details->updated_at)->diffForHumans();

        return $service_details;
    }

    public function necessaryResources(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $content = strval(view('Web::home.necessary_resources'));

        return response()->json(['response' => $content]);
    }

    public function getNecessaryResources( Request $request){
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }
        $data = UserManual::where('status', 1)->orderBy('id', 'desc')->get();
        return Datatables::of($data)
            ->addColumn('action', function ($data) {
                if (file_exists($data->pdfFile)){
                    return '<a href="'.'/'. $data->pdfFile. '" class="btn btn-xs btn-success" aria-hidden="true" target="_blank" download><i class="fa fa-download"></i> Download</a>';
                }else {
                    return '';
                }

            })
            ->removeColumn('id')
            ->make(true);
    }

    public function getBBSCode(Request $request)
    {
        CommonFunction::createHomePageViewLog('sectorInfo', $request);

        $content = strval(view('Web::home.business_sector'));

        return response()->json(['response' => $content]);
    }

    public function viewPageLinkCount(Request $request)
    {
        if (!empty($request->get('log_key'))) {
            CommonFunction::createHomePageViewLog($request->get('log_key'), $request);
            return response()->json(['response' => 'success']);
        }
        return response()->json(['response' => 'error']);
    }

    public function getBusinessClassList(Request $request)
    {
        $data = collect(DB::select("
            SELECT 
            sec_class.id, 
            sec_class.code,
            CONCAT('(',sec_section.code,') ',sec_section.name) AS section_name_code,
            CONCAT(CONCAT(sec_class.code,' - ',sec_class.name), '<p>',GROUP_CONCAT(CONCAT(subb_class.code,' - ',subb_class.name) SEPARATOR '<br />'),'</p>') class
            FROM (SELECT * FROM sector_info_bbs WHERE TYPE = 4) sec_class
            LEFT JOIN sector_info_bbs sec_group ON sec_class.pare_id = sec_group.id 
            LEFT JOIN sector_info_bbs sec_division ON sec_group.pare_id = sec_division.id 
            LEFT JOIN sector_info_bbs sec_section ON sec_division.pare_id = sec_section.id
            LEFT JOIN sector_info_bbs subb_class ON subb_class.pare_id = sec_class.id
            GROUP BY sec_class.id
            ORDER BY sec_section.code ASC;
        "));

        return Datatables::of($data)
            ->filterColumn('class', function ($query, $keyword) {
                $sql = "CONCAT(CONCAT(sec_class.code,' - ',sec_class.name), '<p>',GROUP_CONCAT(CONCAT(subb_class.code,' - ',subb_class.name) SEPARATOR '<br />'),'</p>') like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('section_name_code', function ($query, $keyword) {
                $sql = "CONCAT('(',sec_section.code,') ',sec_section.name) like ?";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->removeColumn('id')
            ->make(true);
    }

    /*
     * is helful article log
     * @request ajax
     * @param request
     *
     */

    public function isHelpFul(Request $request)
    {
        if (!$request->ajax()) {
            return 'Sorry! this is a request without proper way.';
        }

        $reference_id = '';

        switch ($request->slug) {
            case 1: // 1 = for available service
                $service_detail_id = Encryption::decodeId($request->service_detail_id);
                $service_details = ServiceDetails::leftJoin('process_type', 'process_type.id', '=', 'service_details.process_type_id')
                    ->where('service_details.id', $service_detail_id)
                    ->first(['service_details.id','process_type.type_key','process_type.id']);
                $log_key = 'availableServiceInfo.details.'.$service_details->type_key;
                $reference_id = $service_details->id;
                break;
            case 2: // 2 = for agency info
                $sub_agency_id = Encryption::decodeId($request->sub_agency_id);
                $log_key = 'agencyInfo.details.'.$sub_agency_id;
                $reference_id = $sub_agency_id;
                break;
            case 3: // 3 = for sectorInfo
                $log_key = 'sectorInfo';
                break;
            case 4: // 3 = for support need help page
                $log_key = 'needHelp';
                break;
            case 5: // 5 = for about quick service portal menu
                $log_key = 'aboutQuickServicePortal';
                break;
            case 6: // 6 = for About OSSPID menu
                $log_key = 'aboutOSSPID';
                break;
            case 7: // 7 = for Contact Us menu
                $log_key = 'contactUs';
                break;
            case 8: // 8 = for Document and Downloads menu
                $log_key = 'documentAndDownload';
                break;
            case 9: // 9 = for Privacy Statement menu
                $log_key = 'privacyStatement';
                break;
            case 10: // 10 = for About One Stop Service menu
                $log_key = 'OneStopService';
                break;
            case 11: // 11 = for About BIDA menu
                $log_key = 'aboutBIDA';
                break;
            case 12: // 12 = for Terms of Services & Disclaimer menu
                $log_key = 'termsOfServices';
                break;
            default:
                return 0;
        }
        // for home page view log
        CommonFunction::createHomePageViewLog($log_key, $request, $reference_id);

    }
}
