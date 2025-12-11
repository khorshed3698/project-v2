<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en" xml:lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <title></title>
    <style>
        .well {
            min-height: 20px;
            padding: 19px;
            margin-bottom: 20px;
            background-color: #f5f5f5;
            border: 1px solid #e3e3e3;
            border-radius: 4px;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .05);
        }

        .well-sm {
            padding: 9px;
            border-radius: 3px;
        }

        a.btn {
            margin: 3px 0 !important;
        }
    </style>
    <link rel="stylesheet" href="https://prp.pilgrimdb.org/assets/stylesheets/styles.css"/>
</head>
<body class="o-page">
<div id="page">
    <div id="content">
        <div class="panel-body">
            <div class="well well-sm" style="text-align: center">
                <h4 class="text-info"></h4>
                @if(count($allReports)>0)
                    @foreach($allReports as $misreport)

                        <a href="{{url('web/view-mis-reports/'. encodeId($misreport->user_id) .'/'. encode($misreport->report_id.'||'.$misreport->flag) .'/'. encode($unix_time)) }}"
                           class="btn btn-info">{{ $misreport->title }}</a>
                        <br>
                    @endforeach
                @endif
                <br><br>
                <div class="text-center">
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>