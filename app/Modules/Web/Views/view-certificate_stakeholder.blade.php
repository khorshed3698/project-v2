@extends('layouts.plane')
@section ('body')
    @include('public_home.header')
    @include('public_home.style')
    <style type="text/css">
        .container {
            width: 900px;
            /*margin: 50px auto;*/
            /*border: 1px solid red;*/
            overflow: hidden;
        }

        .margin-top{
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
        .form-control{
            margin-bottom:10px;
        }
    </style>

    <div class="container" style="margin-top:20px;">
        <div class="row">
            <div class="col-md-12">
                <hr class="top-border"/>
            </div>
        </div>
        <div class="row">
            @if($pdfCertificate)
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-control">Document Ref:</div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-control">
                                <a href="{!! $pdfCertificate->certificate_link !!}"
                                   target="_blank">{!!  str_limit($pdfCertificate->certificate_link, $limit = 70, $end = '...') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-control"> Document Source:</div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-control">
                                <a href="{!! $pdfCertificate->certificate_link !!}"
                                   target="_blank">{!!  str_limit($pdfCertificate->certificate_link, $limit = 70, $end = '...') !!}</a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-control"> Service Name:</div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-control">
                                <p> {{ $pdfCertificate->process_name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Applicant:</legend>
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->applicant_name) ? $pdfCertificate->applicant_name : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->applicant_email) ? $pdfCertificate->applicant_email : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->applicant_hash ) ? $pdfCertificate->applicant_hash  : null  }}</p>
                                    </div>
                                    <div class="form-control">
                                        <p> {{  isset($pdfCertificate->applicant_time ) ? $pdfCertificate->applicant_time  : null }}</p>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Recommender:</legend>
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->recommeder_name) ? $pdfCertificate->recommeder_name : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->recommeder_email) ? $pdfCertificate->recommeder_email : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->recommeder_hash ) ? $pdfCertificate->recommeder_hash  : null  }}</p>
                                    </div>
                                    <div class="form-control">
                                        <p> {{  isset($pdfCertificate->recommender_time ) ? $pdfCertificate->recommender_time  : null }}</p>
                                    </div>

                                </div>
                            </fieldset>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <fieldset>
                                <legend>Approver:</legend>
                                <div class="col-md-9 col-md-offset-3">
                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->approver_name) ? $pdfCertificate->approver_name : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->approver_email) ? $pdfCertificate->approver_email : null }}</p>
                                    </div>

                                    <div class="form-control">
                                        <p> {{ isset($pdfCertificate->approver_hash ) ? $pdfCertificate->approver_hash  : null  }}</p>
                                    </div>
                                    <div class="form-control">
                                        <p> {{  isset($pdfCertificate->approval_time ) ? $pdfCertificate->approval_time  : null }}</p>
                                    </div>
                                </div>
                            </fieldset>

                        </div>
                    </div>

                    {{--<button class="btn btn-default pull-right margin-top"><b>Print</b></button>--}}

                </div>

                <div class="col-md-12 margin-top text-center">
                    <div class=""></div>
                    <p>Verified by Secured Document Printing System.</p>
                </div>
            @else
                <div class="col-md-12 text-center">
                    <h1 class="alert alert-danger text-danger">CERTIFICATE NOT FOUND</h1>
                </div>
            @endif

        </div>
    </div>
@stop
@include('public_home.footer_script')
<script type="text/javascript">
    function SelectAll(id) {
        document.getElementById(id).focus();
        document.getElementById(id).select();
    }
</script>