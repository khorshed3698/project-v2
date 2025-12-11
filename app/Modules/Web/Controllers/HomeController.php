<?php

namespace App\Modules\Web\Controllers;

use App\Libraries\CommonFunction;
use App\Http\Controllers\Controller;
use App\Services\TokenService;
use Exception;
use App\Modules\Settings\Models\HomePageSlider;
use Illuminate\Http\Request;
use App\HomePageArticle;
use App\Services\ApiHandlerService;
use App\Modules\ProcessPath\Models\ProcessType;
use App\Modules\ProcessPath\Models\ProcessList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $baseUrl;
    private $clientId;
    private $clientSecret;
    public function __construct()
    {
        $this->baseUrl = config('app.insightdb_api_base_url');
        $this->clientId = config('app.insightdb_oauth_client_id');
        $this->clientSecret = config('app.insightdb_oauth_client_secret');
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $data = [];
        $data['api_token'] = '';

        // Implement cache here
        $data['home_slider_image'] = HomePageSlider::where('status', 1)->orderBy('id', 'DESC')->take(5)->get();

        // Load global settings
        CommonFunction::GlobalSettings();

        return view('Web::home.index', $data);
    }

    public function serviceTracking()
    {
        $data = [];
        // $data['processType'] = ProcessType::whereStatus(1)->lists('name', 'id')->all();
        // $data['companyInfo'] = CompanyInfo::where('is_eligible', 1)->lists('company_name', 'id')->all();
        $data['processType'] = ProcessType::where('status', 1)
            ->orderBy('service_name', 'asc')
            ->get([
                'id',
                DB::raw("CONCAT(IF(bida_service_status=1, 'BIDA Service: ', ''), `process_supper_name`, '- ', `process_sub_name`) AS service_name")
            ]);

        $processTypeArray = [];
        foreach ($data['processType'] as $item) {
            $processTypeArray[$item->id] = $item->service_name;
        }

        $data['processType'] = $processTypeArray;


        return view('Web::home.service_tracking', $data);
    }

    public function getCompanyByProcessType(Request $request)
    {
        $companyInfo = ProcessList::
        leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
            ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
            ->where('process_type_id', $request->get('value'))
            ->where('company_info.is_eligible', 1)
            ->groupBy('company_info.id')
            ->select('company_info.company_name as company_name', 'company_info.id as id')
            ->lists('company_name', 'id')
            ->all();
        return response()->json($companyInfo);
    }

    public function searchServiceInfo(Request $request)
    {
        $error = ['responseCode' => 404, 'data' => null, 'success' => false, 'message' => 'Data not found'];
        try {
            $processType = $request->get('process_type');
            $companyId = $request->get('company_id');
            $trackId = trim($request->get('tracking_number'));
            if(!$processType || !$trackId || !$companyId){
                return response()->json($error);
            }
            $processInfo = ProcessList::leftJoin('process_type', 'process_type.id', '=', 'process_list.process_type_id')
                ->leftJoin('company_info', 'company_info.id', '=', 'process_list.company_id')
                ->leftJoin('process_status', function ($join) {
                    $join->on('process_status.process_type_id', '=', 'process_list.process_type_id')
                        ->on('process_status.id', '=', 'process_list.status_id');
                })
                ->where('process_list.process_type_id', $processType)
                ->where('process_list.tracking_no', $trackId)
                ->where('process_list.company_id', $companyId)
                ->first([
                    'process_list.id as pl_id',
                    'process_list.tracking_no as tracking_no',
                    'process_list.process_type_id as process_type_id',
                    'process_list.created_at as created_at',
                    'process_list.status_id as status',
                    'process_type.name as service_name',
                    'process_status.status_name as status_name',
                    'company_info.company_name as company_name',
                ]);

            if(isset($processInfo)){
                if ($processInfo->status == -1) {
                    return response()->json($error);
                }
                // $historyData = DB::table('process_list_hist')
                //     ->leftJoin('process_status', 'process_status.id', '=', 'process_list_hist.status_id')
                //     ->where('process_status.process_type_id', $processInfo->process_type_id)
                //     ->where('process_list_hist.process_id', $processInfo->pl_id)
                //     ->where('process_list_hist.status_id', '!=', -1)
                //     ->get([
                //         'process_status.status_name as status_name',
                //     ]);
                $responseData = [
                    'tracking_id' => $processInfo->tracking_no,
                    'service_name' => $processInfo->service_name,
                    'company_name' => $processInfo->company_name,
                    'submit_date' => Carbon::parse($processInfo->created_at)->format('d F Y, h:i A'),
                    'status' => $processInfo->status,
                    'current_status' => $processInfo->status_name,
                ];

                $contents = view('Web::home.service_tracking_details', compact('responseData'))->render();
                $data = ['responseCode' => 200, 'data' => $contents, 'success' => true, 'message' => 'Data fetched successfully'];
                return response()->json($data);
            }else{
                return response()->json($error);
            }

        } catch (Exception $e) {
            Log::error('searchServiceInfo: ' . $e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            return response()->json($error);
        }
    }

    public function commonInnerPage(Request $request)
    {
        $data = [];
        $data['contents'] = HomePageArticle::where('page_name', 'about_bida')->pluck('page_content');
        $data['title'] = 'About BIDA';

        CommonFunction::createHomePageViewLog('aboutBIDA', $request);

        return view('Web::home.common_innerpage', $data);
    }

    public function dynamicDataSets(Request $request)
    {
        $serviceType = $request->get('service_type');
        $month = $request->get('month');
        $year = $request->get('year');
        if(!$serviceType || !$year){
            return response()->json(['error' => 'Invalid request data'], 400);
        }

//        $currentYear = Carbon::now()->year;
        if (empty($month)) {
            $startDate = $year.'-01-01';
            $endDate = $year.'-12-31';
        }else{
            $startDate = $year.'-'.$month.'-01';
            $endDate = $year.'-'.$month.'-31';
        }

        $dateTimeText = "From $startDate To $endDate";

        $endPoint = '/api/api-bank/data-hub/bida_oss_landing_page/data-sets';
        $data = [
            "data_sets" => [
                "bida_oss_public_service_feedback",
                "bida_oss_public_service_data"
            ],
            "parameters" => [
                "service_type" => $serviceType,
                "start_date" => $startDate,
                "end_date" => $endDate
            ]
        ];


        $apiHandler = new ApiHandlerService();
        $response = $apiHandler->makeRequest($this->baseUrl, $endPoint,'POST', $this->clientId, $this->clientSecret, $data);
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $responseData = $response->getData(true);
        } else {
            $responseData = $response['data'];

        }

        if (isset($responseData['error'])) {
            return response()->json(['error' => $responseData['error']], 400);
        } else {

            // $contents = view('Web::home.partials.dataSets', compact(
            //     'responseData', 'dateTimeText'))->render();
            $data = ['responseCode' => 200, 'data' => $responseData, 'dateTimeText' => $dateTimeText, 'success' => true, 'message' => 'Data fetched successfully'];
            return response()->json($data);
        }

    }

    public function getInsightDBApiToken()
    {
        $tokenCacheKey = 'insightdb_api_token';
        if (Cache::has($tokenCacheKey)) {
            $insightdb_api_token = Cache::get($tokenCacheKey);
        }else {
            $tokenService = new TokenService();
            $insightdb_api_token = $tokenService->getToken($this->clientId, $this->clientSecret);
            Cache::put($tokenCacheKey, $insightdb_api_token, Carbon::now()->addSeconds(270));
        }

        return response()->json('ok');
    }

    public function fetchDataSets(Request $request)
    {
        $rules = [
            'data_sets' => 'required|array',
            'parameters.service_type' => 'required|integer',
            'parameters.start_date' => 'required|date',
            'parameters.end_date' => 'required|date',
        ];
    
        $messages = [
            'data_sets.required' => 'The data_sets field is required.',
            'data_sets.array' => 'The data_sets field must be an array.',
            'parameters.service_type.required' => 'Service type is required.',
            'parameters.start_date.required' => 'Start date is required.',
            'parameters.end_date.required' => 'End date is required.',
        ];
    
        $this->validate($request, $rules, $messages);
    
        $validated = $request->all();

        $data = [];

        // Check for the dataset
        if (in_array('bida_oss_public_service_data', $validated['data_sets'])) {

            // Prepare bound parameters
            $startDate = $validated['parameters']['start_date'];
            $endDate = $validated['parameters']['end_date'];
            $serviceType = $validated['parameters']['service_type'];

            $query = "
                SELECT 
                IFNULL(sla.Organization, '-') AS `Entity`,
                IFNULL(sla.agency_section, '-') AS `Agency/Section`,
                IFNULL(sla.Service, '-') AS `Service`,
                IFNULL(CONCAT(sla.sla, IF (sla.sla > 1, ' Days', ' Day')), '-') AS `Stipulated Delivery Timeline`,
                IFNULL(FORMAT(Disposed, 0), '-') AS Disposed, 
                IFNULL(`Percentage within Timelines`, '-') AS `% within Stipulated Timelines`, 
                IFNULL(sla.logo_url, 'https://bidaquickserv.org/assets/images/dashboard/government_of_bangladesh.png') AS logo 
                FROM (
                SELECT 
                    bida_service_status, 
                    CASE 
                    WHEN bida_service_status = 1 THEN 'Bangladesh Investment Development Authority (BIDA)' 
                    WHEN bida_service_status IN (0, 2) THEN process_supper_name 
                    END AS Organization, 
                    CASE 
                    WHEN pt.bida_service_status = 1 THEN 'https://bidaquickserv.org/uploads/logo/bida-logo_200X69.png' 
                    WHEN pt.bida_service_status IN (0, 2) THEN CONCAT('https://bidaquickserv.org/', logo) 
                    END AS logo_url, 
                    pt.name AS Service, 
                    agency_section, 
                    sla 
                FROM 
                    process_type pt 
                    LEFT JOIN service_sla sla ON pt.id = sla.process_type_id 
                WHERE 
                    pt.status = 1 
                    AND pt.id != 700 
                    AND pt.public_service_type = {$serviceType} 
                GROUP BY 
                    pt.name, agency_section
                ) sla 
                LEFT JOIN (
                SELECT 
                    agency_section AS `Agency/Section`, 
                    obj_id AS Service, 
                    CONCAT(sla, ' Day') AS `Stipulated Delivery Timeline`, 
                    IFNULL(SUM(Approved), 0) + IFNULL(SUM(Rejected), 0) AS Disposed, 
                    CONCAT(
                    ROUND(
                        IFNULL(
                        (
                            SUM(Ratings) / 
                            (IFNULL(SUM(Approved), 0) + IFNULL(SUM(Rejected), 0))
                        ) * 100, 
                        0
                        ), 0
                    ), '%'
                    ) AS `Percentage within Timelines`
                FROM (
                    SELECT 
                    public_service_type, 
                    agency_section, 
                    obj_id, 
                    SUM(IF(status_id IN(25, 26), 1, 0)) AS Approved, 
                    SUM(IF(status_id IN(6, 27), 1, 0)) AS Rejected, 
                    ROUND(AVG(ActualDuration), 0) AS KPI, 
                    SUM(
                        IF(
                        ActualDuration <= sla AND status_id IN(6, 25, 26, 27), 
                        1, 
                        0
                        )
                    ) AS Ratings, 
                    ActualDuration, 
                    sla 
                    FROM (
                    SELECT 
                        public_service_type, 
                        agency_section, 
                        pl.id AS PLID, 
                        pl.process_type_id, 
                        sla.sub_department_id AS SubDID, 
                        pt.name AS obj_id, 
                        pl.status_id, 
                        pl.processing_duration_1 AS ActualDuration, 
                        sla.sla 
                    FROM 
                        process_list_dt pldt 
                        LEFT JOIN process_list pl ON pl.id = pldt.process_id 
                        LEFT JOIN process_type pt ON pl.process_type_id = pt.id 
                        LEFT JOIN service_sla sla ON 
                        pl.process_type_id = sla.process_type_id 
                        AND pl.department_id = sla.department_id 
                        AND pl.sub_department_id = sla.sub_department_id 
                    WHERE 
                        pl.status_id IN (6, 25, 26, 27) 
                        AND pl.process_type_id != 100 
                        AND pt.public_service_type = {$serviceType} 
                        AND pldt.disposal_dt BETWEEN :start_date AND :end_date
                    ) t1 
                    GROUP BY obj_id, agency_section
                ) tab 
                GROUP BY public_service_type, obj_id, agency_section
                ) sd ON sla.Service = sd.Service 
                AND sla.agency_section = sd.`Agency/Section` 
                ORDER BY bida_service_status, Entity, Service
            ";

            $data['bida_oss_public_service_data'] = DB::select(DB::raw($query), [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            $feedbackQuery = "
                SELECT 
                    Organization, 
                    Service, 
                    FORMAT(Feedbacks, 0) AS `Number of Feedback`, 
                    Rating AS `Feedback Ratting (out of 5)` 
                FROM (
                    SELECT 
                        public_service_type, 
                        COUNT(DISTINCT Organization) AS Organization, 
                        COUNT(DISTINCT id) AS Service 
                    FROM (
                        SELECT 
                            public_service_type, 
                            process_supper_name, 
                            CASE 
                                WHEN bida_service_status = 1 THEN 'Bangladesh Investment Development Authority (BIDA)' 
                                WHEN bida_service_status = 2 THEN process_supper_name 
                            END AS Organization, 
                            id, 
                            name AS status, 
                            bida_service_status 
                        FROM 
                            process_type 
                        WHERE 
                            bida_service_status > 0 
                            AND public_service_type = {$serviceType}
                    ) s 
                    GROUP BY public_service_type
                ) service 
                LEFT JOIN (
                    SELECT 
                        public_service_type, 
                        SUM(CASE WHEN is_feedback = 1 THEN 1 ELSE 0 END) AS Feedbacks, 
                        ROUND(AVG(rating), 2) AS Rating 
                    FROM (
                        SELECT 
                            public_service_type, 
                            pl.id, 
                            pl.tracking_no, 
                            pl.is_feedback, 
                            pl.rating, 
                            pl.status_id, 
                            pt.id AS process_type, 
                            pt.process_supper_name 
                        FROM 
                            process_list_dt pldt 
                            LEFT JOIN process_list pl ON pldt.process_id = pl.id 
                            LEFT JOIN process_type pt ON pl.process_type_id = pt.id 
                        WHERE 
                            pldt.feedback_dt BETWEEN :start_date AND :end_date
                            AND pl.is_feedback = 1 
                            AND pl.process_type_id != 100 
                            AND pt.public_service_type = {$serviceType}
                    ) AS tab 
                    GROUP BY public_service_type
                ) ratting 
                ON service.public_service_type = ratting.public_service_type
            ";

            $data['bida_oss_public_service_feedback'] = DB::select(DB::raw($feedbackQuery), [
                'start_date' => $startDate,
                'end_date' => $endDate
            ]);

            return response()->json([
                'responseCode' => 200,
                'message' => 'success',
                'success' => true,
                'data' => $data
            ]);
        }

        return response()->json([
            'responseCode' => 404,
            'message' => 'error',
            'success' => false,
            'data' => []
        ], 404);
    }


    public function getLatestNotice()
    {
        $notice = DB::table('notice')
            ->select(DB::raw("DATE_FORMAT(updated_at,'%d-%b-%Y') as notice_date"), 'heading', 'details')
            ->where('is_active', 1)
            ->where('status', 'public')
            ->orderBy('updated_at', 'desc')
            ->limit(1)
            ->get();

        return response()->json([
            'responseCode' => 200,
            'message' => 'success',
            'success' => true,
            'data' => $notice
        ]);
    }


}