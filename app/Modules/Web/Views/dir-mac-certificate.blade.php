@extends('layouts.front')

@section("content")
    <style type="text/css">
        .margin-top {
            margin-top: 30px !important;
        }

        .note p {
            margin-left: 25px;
            color: gray;
            font-weight: bold;
            text-decoration: underline;
        }

        a:hover {
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
        }

        .area p {
            font-weight: bold;
            font-style: italic;
        }

        .downloadlink a:link {
            text-decoration: none;
            color: #fff
        }

        .downloadlink a:active {
            text-decoration: none;
            color: #fff
        }

        .downloadlink a:visited {
            text-decoration: none;
            color: #fff
        }

        .downloadlink a:hover {
            text-decoration: none;
            color: #fff
        }

        .form-control {
            margin-bottom: 10px;
            height: auto;
            min-height: 34px;
            word-break: break-word;
        }

        .form-control p {
            margin: 0;
        }

        .table-bg-white {
            background: #fff;
            margin-bottom: 10px;
        }

        .table-bg-white > thead > tr > th,
        .table-bg-white > tbody > tr > th,
        .table-bg-white > tfoot > tr > th,
        .table-bg-white > thead > tr > td,
        .table-bg-white > tbody > tr > td,
        .table-bg-white > tfoot > tr > td {
            border: 1px solid #ccc;
            padding: 8px;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
            -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
            -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
            transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        }

        @media print {
            .logo_area {
                width: 20%;
                float: left;
                margin: 0;
            }
            
            .no-print, .no-print * {
                display: none !important;
            }
        }
    </style>

    <div class="row">
        @if($pdfCertificate)
            <div class="col-md-10 col-md-offset-1">
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table table-bordered table-bg-white" aria-label="Detailed Report Data Table">
                            <thead>
                                <tr class="d-none">
                                    <th aria-hidden="true" scope="col"></th>
                                </tr>
                            </thead>                            
                            <tbody>
                            <tr>
                                <td colspan=2" class="text-center">
                                    <h4><b>Organization Name: </b> {{ CommonFunction::getCompanyNameById($pdfCertificate->company_id) }}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%"><b>Document Ref</b></td>
                                <td><a href="{{ Request::url() }}">{{ Request::url() }}</a></td>
                            </tr>
                            <tr>
                                <td><b>Document Source</b></td>
                                
                                <td>
                                    <div class="pull-left">
                                        <a href="{!! $dirMachineryDocfullPath !!}"
                                           target="_blank">{!!  str_limit($dirMachineryDocfullPath, $limit = 40, $end = '...') !!}</a>
                                    </div>
                                    <div class="pull-right">
                                        <a download href="{{ $dirMachineryDocfullPath }}" target="_blank"
                                           class="btn btn-xs btn-success no-print"><i class="fa fa-download"></i>
                                            Download
                                            Certificate</a>
                                    </div>
                                    <div class="clearfix"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Service Name</b></td>
                                <td>{{ $pdfCertificate->process_name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-bg-white" aria-label="Detailed Report Data Table">
                            <tbody>
                            <tr>
                                <td rowspan="4" width="25%"><b>Applicant</b></td>
                                <td>{{ $pdfCertificate->applicant_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ $pdfCertificate->applicant_email }}</td>
                            </tr>
                            <tr>
                                <td>{{ date('d-M-Y h:i:s', strtotime($pdfCertificate->applicant_time)) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-bg-white" aria-label="Detailed Report Data Table">
                            <tbody>
                            <tr>
                                <td rowspan="4" width="25%"><b>Approver</b></td>
                                <td>{{ $pdfCertificate->approver_name }}</td>
                            </tr>
                            <tr>
                                <td>{{ $pdfCertificate->approver_email }}</td>
                            </tr>
                            <tr>
                                <td>{{ date('d-M-Y h:i:s', strtotime($pdfCertificate->approval_time)) }}</td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">
                                            View digital signature
                                        </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <button class="btn btn-default pull-right no-print" onclick="javascript:window.print()">
                            <b><i class="fa fa-print"></i> Print</b>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 text-center">
                        <i>Verified by Secured Document Printing System <br/>
                            For more information about this document please contact as <strong>Email ID:</strong> {{ !empty($help_information) ? $help_information->value : 'info@batworld.com' }}, <strong>Phone:</strong> {{ !empty($help_information) ? $help_information->value2 : '+8801755676721' }}
                            <br/><br/>
                        </i>
                    </div>
                </div>

            </div>

            <!--Digital signature-->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Digital signature</h4>
                        </div>
                        <div class="modal-body">
                            <p style="word-break: break-all">
                                {{ $pdfCertificate->approver_hash }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="col-md-12 text-center">
                <h1 class="alert alert-danger text-danger">CERTIFICATE NOT FOUND</h1>
            </div>
        @endif

    </div>
@endsection

@section('footer-script')
    <script>
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').focus()
        })
    </script>
@endsection