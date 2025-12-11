<?php
$accessMode = ACL::getAccsessRight('LabureInspection');
if (!ACL::isAllowed($accessMode, '-V-')) {
    die('You have no access right! Please contact with system admin if you have any query.[ML-1027]');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }

    .photo_size{
        max-height: 150px;
        max-width: 200px;
    }
</style>
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible" hidden id="alert_success">
            <span></span>
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        </div>
        <div class="alert alert-danger alert-dismissible" hidden  style="display: none" id="alert_error">
            <span></span>
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    Mutation Land
                </div>
                <div class="pull-right">
                    @if(!empty($appInfo->ml_app_view_url))
                        <a class="btn btn-md btn-primary" href="{{ $appInfo->ml_app_view_url }}" target="_blank" role="button"
                           aria-expanded="false" id="status_check">
                            Application View
                        </a>
                    @endif
                    <a class="btn btn-md btn-info" role="button"
                       aria-expanded="false" id="status_check" onclick="checkStatus()">
                        Status Check
                    </a>
                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                       aria-expanded="false" aria-controls="collapseExample">
                        <i class="far fa-money-bill-alt"></i>
                        Payment Info
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <ol class="breadcrumb">
                    <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                    @if(!empty($appInfo->ml_app_id))
                        <li><strong>Mutation application no: </strong>{{ $appInfo->ml_app_id  }}</li>
                    @endif
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    @if(!empty($appInfo->rajuk_application_no))
                        <li><strong>Mutation Land Application no. : </strong>{{ $appInfo->rajuk_application_no  }}</li>
                    @endif
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                        <li>
                            <a href="{{ url($appInfo->certificate_link) }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                    @if (isset($appInfo) && $appInfo->resubmit_status == -1 && isset($appInfo->resubmit_url))
                        <li>
                            <a href="{{ url($appInfo->resubmit_url) }}"
                               class="btn show-in-view btn-xs btn-primary"
                               title="Download Approval Letter" target="_blank"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Resubmit Application</b></a>
                        </li>
                    @endif
                    @if (isset($app_data)  && empty($appInfo->ml_redirect_url))
                        <li>
                            <span id="submit_status" style="padding: 5px" class="label label-info"><i
                                        class="fa fa-spinner fa-spin"></i> Submitting Application</span>
                        </li>
                    @endif
                </ol>
                @if($appInfo->ml_submission_status == 0)
                <div class="alert alert-success alert-dismissible text-center" hidden="" style="display: block;" id="submitting_message">
                    <span>Waiting for submission. After submission, you will redirect to mutation website</span>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                </div>
                @endif

                <!--                Payment information-->
                @include('SonaliPaymentStackHolder::payment-information')

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Company Information</strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Company Name (Bangla)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->company_name_bn) ?  $app_data->company_name_bn : ''}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Company Name (English)</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->company_name_en) ?  $app_data->company_name_en : ''}}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Owner Type</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->owner_type) ?  explode('@',$app_data->owner_type)[1] : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>Authorization</strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Full Name</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->full_name) ?  $app_data->full_name : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Designation</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->designation) ?  $app_data->designation : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Mobile Number</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->mobile_no) ?  $app_data->mobile_no : ''}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Email</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        {{ !empty($app_data->email) ?  $app_data->email : ''}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left col-md-5">
                                        <span class="v_label">Applicant Photo</span>
                                        <span class="pull-right">&#58;</span>
                                    </div>
                                    <div class="col-md-7">
                                        <figure>
                                            <img src="{{ (!empty($app_data->applicant_photo)? url('users/upload/'.$app_data->applicant_photo) : url('assets/images/no-image.png')) }}"
                                                 class="img-responsive photo_size img-thumbnail"
                                                 id="applicant_photo_preview"/>
                                        </figure>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>

    @if($appInfo->ml_submission_status == 0 && $appInfo->created_by == Auth::user()->id)
        setTimeout(checkgenerator, 10000);
    @endif

    function checkgenerator() {

        $.ajax({
            url: '/mutation-land/check-api-request-status',
            type: "POST",
            data: {
                app_id: '{{$appInfo->id}}',
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {
                if (response.responseCode == 1) {
                    if (response.status == 1) {
                        $('#submit_status').html('');
                        myWindow = location.replace(response.ml_redirect_url);
                    }else if(response.status == 2){ //already submitted to land
                        console.log(response.status);
                        location.reload();
                    } else {
                        myVar = setTimeout(checkgenerator, 3000);
                    }
                } else if (response.responseCode == 0) {
                    myVar = setTimeout(checkgenerator, 3000);
                } else {
                    $('#submit_status').html('');
                    swal({type: 'error', title: 'Oops...', text: response.message});
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
        return false; // keeps the page from not refreshing
    }

    function checkStatus() {
        $.ajax({
            url: '/mutation-land/check_status',
            type: "POST",
            data: {
                app_id: '{{$appInfo->id}}',
                tracking_no: '{{$appInfo->tracking_no}}',
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function (response) {

                if (response.responseCode == "RSP200") {
                    $("#alert_success span").text(response.data.application_status);
                    $('#alert_success').show();
                    $('#alert_error').hide();
                } else {
                    $("#alert_error span").text(response.data.application_status);
                    $('#alert_error').show();
                    $('#alert_success').hide();
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
        });
        return false; // keeps the page from not refreshing
    }
</script>
