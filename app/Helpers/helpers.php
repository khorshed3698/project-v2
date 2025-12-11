<?php

//Helper functions add here
use App\Libraries\CommonFunction;
use App\Libraries\Encryption;
use App\Modules\Dashboard\Models\DashboardObjectDynamic;
use App\Modules\Users\Models\Users;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Modules\Training\Models\TrParticipant;
use App\Modules\Training\Models\TrSchedule;
use App\Modules\Training\Models\TrScheduleSession;

function countryBD()
{
    return [countryBDId() => 'Bangladeshi'];
}

function countryBDId()
{
    return 18;
}

function getDbUserName()
{
    return 'user004';
//    $user='mysql-main';
//    echo \Illuminate\Support\Facades\Session::has('mysql_access');
//    echo 'session=';
//        echo session('mysql_access');
//    die();
//    if(session('mysql_access')){
//        $user=session('mysql_access');
//    }
//    return '';
}

function getDbUserPassword()
{
    return 'Alter@!@#';
}

function getList($sql)
{
    $values = Array();
    $i = 0;
    if (strtoupper(substr($sql . '      ', 0, 7)) == 'SELECT ' && strpos(';', $sql) <= 1) {
        $rs = DB::select($sql);
        $fields = array_keys((array)$rs[0]);
        if (count($fields) > 1) $i = 1;
        foreach ($rs as $row) {
            $values[$row->$fields[0]] = $row->$fields[$i];
        }
    } else {
        $val = explode(',', $sql);
        for ($i = 0; $i < count($val); $i++) {
            $values[$val[$i]] = $val[$i];
        }
    }
    $data = collect($values);
    return $data;
}

function getBank()
{
    $data = \App\Modules\Users\Models\Bank::orderBy('name')->lists('name', 'id');
    return $data;
}

function getAgency()
{
    $data = \App\Modules\Settings\Models\Agency::orderBy('license_no')
        ->select(\Illuminate\Support\Facades\DB::raw("concat(license_no,' ',name) as name"), 'id')
        ->lists('name', 'id');
    return $data;
}

function createHTMLTable($arr, $max_length = 25)
{
    $arr = json_decode(json_encode($arr), true);
    $table = '<table class="table basicDataTable dataTable" id="dTable_' . time() . '"><thead>';
    if (isset($arr[0])) {
        $keys = array_keys($arr[0]);
        $table .= '<tr>';
        foreach ($keys as $value) {
            $table .= '<th>' . $value . '</th>';
        }
        $table .= '</tr></thead><tbody>';
        foreach ($arr as $value) {
            $table .= '<tr>';
            foreach ($value as $value2) {
                $table .= '<td>' . formatTDValue($value2, $max_length) . '</td>';
            }
            $table .= '</tr>';
        }
    }
    $table .= '</tbody></table>';
    return $table;
}

function formatTDValue($cell, $maxwd = 25)
{
    if ($cell) {
        if (strlen($cell) > $maxwd) {
            mb_internal_encoding("UTF-8");
            $cell_data = mb_substr($cell, 0, $maxwd - 2);
            return '<span title="' . $cell . '">' . $cell_data . '...</span>';
        } elseif (strlen($cell) > 12) {
            return $cell;
        } else if (is_float($cell)) {
            return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell, 2) . '</span>';
        } elseif (is_numeric($cell)) {
            if ($cell < 10000) {
                return $cell;
            } elseif ($cell < 1000000) {
                return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell, 0) . '</span>';
            } else {
                return '<span style="text-align:right;" title="' . $cell . '">' . number_format($cell / 1000000, 2) . 'M</span>';
            }
        } else {
            return $cell;
        }
    } else {
        return '&nbsp;';
    }
}

function report_gen($id, $data, $report_title, $link = '', $heading = '')
{
    $dataTablePara = '';
    $showaction = false;
    $hiddenFields = array();
    $cols = array();
    $count = 0;
    if ($link) {
        $json_data = json_decode($link);
        if (!empty($json_data)) {
            foreach ($json_data as $jrow) {
                if ($jrow->type == 'link') {
                    $showaction = true;
                } else if ($jrow->type == 'dataTable') {
                    $dataTablePara = $jrow->properties;
                } else if ($jrow->type == 'column') {
                    $cols[$jrow->ID]['caption'] = $jrow->caption;
                    $cols[$jrow->ID]['style'] = $jrow->style;
                } else if ($jrow->type == 'hidden') {
                    $hiddenFields = explode(',', $jrow->fields);
                } else {
                    $showaction = true;
                }
            }
        }
    }
    ?>
    <div class="hero-widget well well-sm">
        <?php if ($heading) { ?>
            <div class="report_heading">
                <div><?php echo $heading; ?></div>
            </div>
        <?php } ?>
        <div id="report_title">
            <h4>
                <?php echo $report_title . ''; ?>
            </h4>
        </div>
        <?php if (count($data) > 0) { ?>
            <link rel="stylesheet" href="<?php echo url(); ?>assets/css/dataTables.tableTools.css"/>
            <script src="<?php echo url(); ?>assets/js/dataTables.tableTools.js"></script>
            <table id="dbt_<?php echo $id; ?>" class="datatable table-rpt-border table table-responsive table-condensed"
                   id="tblQInfo" aria-label="Detailed Report Data Table">
                   <caption class="sr-only">Report</caption>
                <thead>
                <tr class="d-none">
                    <th aria-hidden="true" scope="col"></th>
                </tr>
                <tr>
                    <?php
                    foreach ($data[0] as $key => $value) {
                        if (in_array($key, $hiddenFields)) {
                            continue;
                        }
                        echo '<th';
                        if (isset($cols[$key]['style']))
                            echo ' style="' . $cols[$key]['style'] . '"';
                        echo '>';
//                        echo isset($cols[$key]['caption']) ? $cols[$key]['caption'] : getFieldTitle($key);
//                        echo $cols[$key]['caption'];
                        echo isset($cols[$key]['caption']) ? $cols[$key]['caption'] : ucfirst(str_replace('_', ' ', $key));
                        echo '</th>';
                    }
                    if ($showaction) {
                        echo '<th>Action</th>';
                    }
                    ?>
                </tr>
                </thead>
                <tbody>
                <?php
                $sl = 0;
                foreach ($data as $row):
                    $rowdata = array();
                    if ($sl % 2 == 0) {
                        $row_bg_color = 'style="background-color:#FAFAFA"';
                    } else {
                        $row_bg_color = 'style=""';
                    }
//                            if ($count >= $CI->config->item('MAX_DATA')) {
//                                echo '<tfoot><tr><td colspan="5"><b>Showing ' . $CI->config->item('MAX_DATA') . ' rows out of total ' . count($data) . '! Please export as CSV to show all data.</b></td></tr></tfoot>';
//                                break;
//                            } $count++;
                    ?>
                    <tr <?php echo $row_bg_color; ?>>
                        <?php
                        foreach ($row as $key => $field_value):
                            if (in_array($key, $hiddenFields)) {
                                $rowdata[$key] = $field_value;
                                continue;
                            }
                            //echo '<td>';
                            echo '<td';
                            if (isset($cols[$key]['style']))
                                echo ' style="' . $cols[$key]['style'] . ';"';
                            echo '>';
                            if (is_numeric($field_value)) {
                                echo '<span style="text-align:center;width:100%;float: left;">' . $field_value . '&nbsp;</span>';
                            } else {
                                echo '<span style="text-align:left;width:100%;float: left;">' . $field_value . '&nbsp;</span>';
                            }
                            echo '</td>';
                            if ($link) {
                                $rowdata[$key] = $field_value;
                            }
                        endforeach;
                        if ($showaction) {
                            echo '<td>';
                            foreach ($json_data as $jrow) {
                                if ($jrow->type == 'link') {
                                    $rowdata['baseurl'] = base_url();
                                    echo '<a href="' . ConvPara($jrow->url, $rowdata) . '">' . $jrow->caption . '</a>&nbsp;';
                                } else if ($jrow->type == 'dataTable') {

                                } else {
                                    //print_r($jrow);
                                }
                            }
                            echo '</td>';
                        }
                        ?>
                    </tr>
                    <?php
                    $sl++;
                endforeach;
                ?>
                </tbody>
            </table>

            <?php
        } else {
            echo '<h4 style="text-align: center;color: gray">Data Not Found!</h4>';
        }
        ?>
    </div>

    <?php
    return $count;
}

function getWebLink($website)
{
    if (substr($website, 0, 4) != 'http') {
        $website = config('app.http_proxy') . $website;
    }
    return $website;
}

function mobileMask($mobile)
{
    if (strlen($mobile) > 13) {
        $mobile = substr($mobile, 0, 9) . '***' . substr($mobile, 12);
    } elseif (strlen($mobile) > 10) {
        $mobile = substr($mobile, 0, 6) . '***' . substr($mobile, 9);
    }
    return $mobile;
}

function NidMask($nid)
{
    if (strlen($nid) > 13) {
        $nid = substr($nid, 0, 2) . '**' . substr($nid, 4, 5) . '***' . substr($nid, 12);
    } elseif (strlen($nid) < 11) {
        $nid = substr($nid, 0, 5) . '***' . substr($nid, 8);
    }
    return $nid;
}

function processVerifyData($applicationInfo)
{
    $data = '#D' . $applicationInfo->desk_id . '#R' . $applicationInfo->id . '#S' . $applicationInfo->status_id . '#T' . $applicationInfo->updated_at;

    return $data;
}

function encodeId($_id)
{
    return \App\Libraries\Encryption::encodeId($_id);
}

function decodeId($_id)
{
    return \App\Libraries\Encryption::decodeId($_id);
}

function encode($data)
{
    return \App\Libraries\Encryption::encode($data);
}

function decode($data)
{
    return \App\Libraries\Encryption::decode($data);
}

function strippedSpace($str)
{
    return strtolower(preg_replace('/\s+/', '-', $str));
}

function dodObject($key)
{

    $dods = DashboardObjectDynamic::where('pages', 'LIKE', '%[dod_' . $key . ']%')->where('layout', 'javascript')->get(['query', 'is_encrypted']);
    $content = '';
    if (count($dods) > 0) {
        foreach ($dods as $dod) {
            if ($dod->is_encrypted == 1) {
                $content .= \App\Libraries\Encryption::dataDecode($dod->query);
            } else {
                $content .= $dod->query;
            }

        }
    }
    $content .= '<div class="dod" id="dod_' . $key . '">Loading...</div>';
    return $content;

}

//function getDataFromJson($object, $json){
//    try {
//        $return_data = '';
//        $obj1 = explode(',', $object);
//        if($obj1) {
//            foreach ($obj1 as $obj1_1) {
//                $obj2 = explode(':', $obj1_1);
//                $jsonDecoded = json_decode($json);
//                $obj3 = explode('->', $obj2[1]);
//
//                $jsonPart = '';
//                foreach ($obj3 as $row) {
//                    $row = trim($row);
//                    if ($jsonPart == '') {
//                        $jsonPart = $jsonDecoded->$row;
//                    } else {
//                        $jsonPart = $jsonPart->$row;
//                    }
//                }
//                $return_data .= $obj2[0] . ': ' . $jsonPart . ', ';
//
//            }
//        }
//        dd($return_data);
//        return rtrim($return_data, ', ');
//    } catch (\Exception $e) {
//        return 'Error: '.$e->getMessage();
//    }
//}

/**
 * Get the path to a versioned Mix file.
 *
 * @param  string  $path
 * @param  string  $manifestDirectory
 * @return \Illuminate\Support\HtmlString
 *
 * @throws \Exception
 */
function mix($path, $manifestDirectory = '')
{
    static $manifests = [];

    if (! Str::startsWith($path, '/')) {
        $path = "/{$path}";
    }

    if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
        $manifestDirectory = "/{$manifestDirectory}";
    }

    if (file_exists(public_path($manifestDirectory.'/hot'))) {
        $url = file_get_contents(public_path($manifestDirectory.'/hot'));

        if (Str::startsWith($url, ['http://', 'https://'])) {
            return new HtmlString(Str::after($url, ':').$path);
        }

        return new HtmlString("//localhost:8080{$path}");
    }

    $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');

    if (! isset($manifests[$manifestPath])) {
        if (! file_exists($manifestPath)) {
            throw new Exception('The Mix manifest does not exist.');
        }

        $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
    }

    $manifest = $manifests[$manifestPath];

    if (! isset($manifest[$path])) {
        report(new Exception("Unable to locate Mix file: {$path}."));

        if (! app('config')->get('app.debug')) {
            return $path;
        }
    }

    return new HtmlString($manifestDirectory.$manifest[$path]);
}






/*
    training module
    start
*/

function traineeUserType()
{
    return "10x112";
}

function checkUserTrainingDesk()
{
    return Auth::user()->desk_training_id;
}

function userTrainingDesk()
{
    if(checkTrainingCoordinator()){
        return "Coordinator";
    }elseif(checkTrainingDirector()){
        return "Director";
    }
    
}

function checkTrainingCoordinator()
{
    return checkUserTrainingDesk() == 1;
}

function checkTrainingDirector()
{
    return checkUserTrainingDesk() == 2;
}

function checkTrainee()
{
    return Auth::user()->user_type == traineeUserType();
}

function checkUserPermissionTraining()
{
    $user_type = Auth::user()->user_type;
    $type = explode("x", $user_type); // $type[0] is user type first segment
    if(in_array($type[0], ["10","1",'5']) || ($type[0] == "4" && checkUserTrainingDesk())){
        return true;
    }

    return false;
}


function generateCertificateNumber($batch_name, $year)
{   
    $prefix = "TB-$batch_name-$year-";
    $lastNumber = getLastCertificateNumber($prefix);
    return $prefix.nextSegmentNumber($lastNumber);
}

function nextSegmentNumber($lastNumber)
{
    if ($lastNumber) {
        $nextSegment = sprintf('%04d', $lastNumber + 1);
    } else {
        $nextSegment = '0001';
    }

    return $nextSegment;
}

function getLastCertificateNumber($prefix)
{
    return TrParticipant::query()
        ->select(DB::raw('SUBSTRING(certificate_no, LENGTH(certificate_no) - 3) AS last_number'))
        ->where('certificate_no', 'like', "$prefix%")
        ->orderBy('last_number', 'DESC')
        ->limit(1)
        ->value('last_number');

}


function training_slider_image()
{
    $currentDate = date('Y-m-d');

    $trSchedules = TrSchedule::where('is_active', 1)
        ->where('status', 'upcoming')
        ->where('is_publish', 1)
        ->where('is_featured', 1)
        ->where('enroll_deadline', '>=', $currentDate) // Add this line
        ->orderBy('id', 'DESC')
        ->take(5)
        ->get();

    return $trSchedules;

}

function checkUserDeskNone()
{
    $user_desk_ids = CommonFunction::getUserDeskIds();
    if(!in_array(0, $user_desk_ids) && !in_array(99, $user_desk_ids)){
        return true;
    }
    return false;
}

function checkSeatAbility($schedule_id, $session_id)
{
    $schedule = TrScheduleSession::find($session_id);
    if($schedule->applicant_limit == 'limit'){
        $total_seat = $schedule->seat_capacity;
        $enrolled = TrParticipant::where('schedule_id', $schedule_id)->where('session_id', $session_id)->count();
        if($total_seat > $enrolled){
            return true;
        }
        return false;
    }
    else{
        return true;
    }
    
}
