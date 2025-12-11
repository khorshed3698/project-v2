<?php
namespace App\Modules\Reports\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reports\Models\ReportRequestList;
use App\Modules\Reports\Models\Reports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Libraries\Encryption;
use Illuminate\Support\Facades\DB;
use yajra\Datatables\Datatables;


class PdfReportController extends Controller
{
    public function showCrystalReportModal(Request $request){

        $report_id = $request->get('report_id');
        $reportsql = $request->get('reportsql');
        $search_keys = $request->get('search_keys');

        return view('Reports::crystal_report_modal')
            ->with('report_id', $report_id)
            ->with('search_keys', $search_keys)
            ->with('reportsql', $reportsql);
    }

    public function showCrystalReportData(Request $request){

        $decoded_report_id = Encryption::decodeId($request->get('report_id'));

        $reportRequestList = ReportRequestList::where('report_id', $decoded_report_id)
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'DSC')
            ->get(['pdf_url', 'created_at','search_keys']);


        return Datatables::of(($reportRequestList))
            ->addColumn('pdf_download_link', function ($reportRequestList) {
                return '<a target="_blank" class="btn btn-danger" href="'.$reportRequestList->pdf_url.'"><i class="fa fa-download"></i></a>';
            })
            ->make(true);
    }


    public function generateCrystalReport(Request $request)
    {
        $report_id = Encryption::decodeId($request->get('report_id'));
        $SQL = $request->get('reportsql');
        #$SQL = Encryption::dataDecode($reportsql);
        $pdfurl = $request->get('pdfurl');
        $this->requestAPI($report_id, 'new-job',$pdfurl,$SQL);
        $data = ['responseCode' => 1, 'msg' => 'Certificate generation on process!!!'];
        return response()->json($data);

    }

    public function ajaxApiFeedback(Request $request)
    {
        $report_id = Encryption::decodeId($request->get('report_id'));
        $pdfurl = $request->get('pdfurl');
        $search_keys = Encryption::dataDecode($request->get('search_keys'));

        $response = $this->requestAPI($report_id, 'job-status', $pdfurl);

        /*
         * API request for certificate generation will done here
         */



        $data = ['responseCode' => 0, 'data' => '','ref_id' => 0];
        if (isset($response->response)) {

            if ($response->response->status == 0 or $response->response->status == -1) {
                // In-progress
                $data = ['responseCode' => 1, 'data' => 2,'ref_id' => 0];
            } elseif ($response->response->status == 1) {
                //Print DONE
                $search_keys = explode(',',$search_keys);
                if(count($search_keys) > 2){
                    $searchKeys = array_slice($search_keys, 1, -1);
                    $search_keys = implode(',',$searchKeys);
                }else{
                    $search_keys = '';
                }

                $repObj = ReportRequestList::create(
                    [
                        'report_id' => $report_id,
                        'search_keys' => $search_keys,
                        'user_id' => Auth::user()->id,
                        'pdf_url' => trim($response->response->download_link),
                        'user_type' => Auth::user()->user_type
                    ]
                );

                $ref_id = Encryption::encodeId($repObj->id);
                $data = ['responseCode' => 1, 'data' => 1,'ref_id' => $ref_id];
            } else {
                // Information not eligible!
                $data = ['responseCode' => 1, 'data' => -1,'ref_id' => 0];
            }
        }
        return response()->json($data);
    }

    public function updateDownloadPanel(Request $request)
    {
        $ref_id = Encryption::decodeId($request->get('ref_id'));
        $repObj = ReportRequestList::where('id',$ref_id)->first();

        if ($repObj != null) {
            $return = '';
            //$return .= '<button type="button" id="crystal_gen_btn" reportsql="'.$reportsql.'"  report_id="'.Encryption::encodeId($repObj->id).'" class="btn btn-primary pull-left">Generate Report</button>';
            $return .= '<a target="_blank" class="btn btn-danger pull-left" href="'.$repObj->pdf_url.'"><i class="fa fa-download"></i></a>';
            $responseCode = 1;
        } else {
            $responseCode = 0;
            $return = '';
        }
        $data = ['responseCode' => $responseCode, 'data' => $return];
        return response()->json($data);
    }


    private function requestAPI($app_id, $action = '',$pdfurl = '', $SQL='')
    {
        $reportObj = Reports::where('report_id',$app_id)->first([
            'report_id',
            'res_key',
            'pdf_type',
        ]);

        if($reportObj == null){
            return false;
        }



        $pdf_type = $reportObj->pdf_type;
        $reg_key = $reportObj->res_key;

        $data = array();
        $json_data = array();

        if ($action == "new-job") {
            $SQL = Encryption::dataDecode($SQL);
            $json_data = DB::select(DB::raw($SQL));
            // if (isset($json_data[0])) {
            //     $json_data = $json_data;
            // }
        }

        $data['data'] = array(
            'reg_key' => $reg_key,       // Authentication key
            'pdf_type' => $pdf_type,     // letter type
            'ref_id' => $app_id,         //app_id
            'json' => $json_data,         //Json Data
            'param' => array(
                'id' => $app_id  // app_id
            )
        );

        $data1 = urlencode(json_encode($data));
        if ($action == "job-status") {
            $url = "{$pdfurl}api/job-status";
        } else if ($action == "new-job") {
            $url = "{$pdfurl}api/new-job";
        } else {
            return false;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "requestData=" . $data1);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }
        $dataResponse = json_decode($response);

        return $dataResponse;
    }





    private function XrequestAPI($app_id, $action = '',$pdfurl = '',$SQL='')
    {
        $reportObj = Reports::where('report_id',$app_id)->first([
            'report_id',
            'res_key',
            'pdf_type',
        ]);

        if($reportObj == null){
            return false;
        }



        $pdf_type = $reportObj->pdf_type;
        $reg_key = $reportObj->res_key;

        $data = array();
        $json_data = array();

        if($action == "new-job")
        {
            $json_data = DB::select(DB::raw($SQL));
        }

        $data['data'] = array(
            'reg_key' => $reg_key,       // Authentication key
            'pdf_type' => $pdf_type,     // letter type
            'ref_id' => $app_id,         //app_id
            'json' => $json_data,         //Json Data
            'param' => array(
                'id' => $app_id  // app_id
            )
        );
        $data1 = urlencode(json_encode($data));


        $url = '';
        if ($action == "job-status") {
            $url = "{$pdfurl}api/job-status?requestData=$data1";
        } else if ($action == "new-job") {
            $url = "{$pdfurl}api/new-job?requestData=$data1";

        } else {
            return false;
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, config('app.curlopt_ssl_verifyhost'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, config('app.curlopt_ssl_verifypeer'));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            echo "\n<br />";
            $response = '';
        } else {
            curl_close($ch);
        }
        $dataResponse = json_decode($response);
        return $dataResponse;
    }
}