<?php
$accessMode = ACL::getAccsessRight('RajukLUCGeneral');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    table {
        counter-reset: section;
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }

    .row > .col-md-5,
    .row > .col-md-7,
    .row > .col-md-3,
    .row > .col-md-9,
    .row > .col-md-12 > strong:first-child {
        padding-bottom: 5px;
        display: block;
    }

    legend.scheduler-border {
        font-weight: normal !important;
    }

    .table {
        margin: 0;
    }

    .table > tbody > tr > td,
    .table > tbody > tr > th,
    .table > tfoot > tr > td,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > thead > tr > th {
        padding: 5px;
    }

    .mb5 {
        margin-bottom: 5px;
    }

    .mb0 {
        margin-bottom: 0;
    }
</style>
<section class="content" id="applicationForm">

    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-left">
                    <strong style="line-height: 30px;">
                        Land Use Clearance Application (General)
                    </strong>
                </div>
                <div class="pull-right">

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
                    <li class="highttext"><strong> Date of Submission:
                            {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                    </li>
                    <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                    @if(!empty($appInfo->rajuk_application_no))
                        <li><strong>RAJUK Application no. : </strong>{{ $appInfo->rajuk_application_no  }}</li>
                    @endif
                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                        <li>
                            <a href="{{ url($appInfo->certificate_link) }}"
                               class="btn show-in-view btn-xs btn-info"
                               title="Download Approval Letter" target="_blank" rel="noopener"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                        </li>
                    @endif
                    @if (isset($appInfo) && $appInfo->resubmit_status == -1 && isset($appInfo->resubmit_url))
                        <li>
                            <a href="{{ url($appInfo->resubmit_url) }}"
                               class="btn show-in-view btn-xs btn-primary"
                               title="Download Approval Letter" target="_blank" rel="noopener"> <i
                                        class="fa  fa-file-pdf-o"></i> <b>Resubmit Application</b></a>
                        </li>
                    @endif
                    @if (isset($appData)  && empty($appInfo->rajuk_redirect_url))
                        <li>
                            <span id="submit_status" style="padding: 5px" class="label label-info"><i
                                        class="fa fa-spinner fa-spin"></i> Submitting Application</span>
                        </li>
                    @endif
                </ol>

                <!--                Payment information-->
                @include('SonaliPaymentStackHolder::payment-information')

                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Land Use</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->land_use) ? explode('@',$appData->land_use)[1]: ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Occupancy Type's</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            @if(!empty($appData->land_use_sub_occupancy))
                                                @foreach($appData->land_use_sub_occupancy as $value)
                                                    <?php
                                                    $value1 = explode('@', $value);
                                                    ?>
                                                    <input checked type="checkbox" disabled/> {{$value1[1]}}<br/>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Applicant's Name (English)</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->applicant_name_en) ? $appData->applicant_name_en:''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Applicant's Name (Bangla)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->applicant_name_bn) ? $appData->applicant_name_bn : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Land Owner Email Address</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->land_owner_email) ? $appData->land_owner_email:''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Land Owner Mobile Number</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->land_owner_mobile) ? $appData->land_owner_mobile : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Present Address (Bangla)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->present_address) ? $appData->present_address: ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">NID / Passport No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->land_owner_mobile) ? $appData->nid_passport : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Holding Number</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->holding_no) ? $appData->holding_no: ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">District Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->district_name) ? explode('@',$appData->district_name)[1]: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Thana Name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->thana_name) ? explode('@',$appData->thana_name)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-info">
                        <div class="panel-heading" style="padding-bottom: 4px;">
                            <strong>DECLARATION</strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Full name</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->auth_name) ?$appData->auth_name: ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Cell number</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->auth_cell_number) ?$appData->auth_cell_number : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" style="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="col-md-5 col-xs-6">
                                            <span class="v_label">Email</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">
                                            {{!empty($appData->auth_email) ?$appData->auth_email: ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        {!! Form::checkbox('accept_terms',1,true, array('id'=>'accept_terms',
                                        'class'=>'required','disabled')) !!}
                                        All the details and information provided in this form are true and
                                        complete.
                                        I am aware that any untrue/incomplete statement may result in delay in
                                        BIN
                                        issuance and I may be subjected to full penal action under the Value
                                        Added
                                        Tax and Supplementary Duty Act, 2012 or any other applicable Act
                                        Prevailing
                                        at present.
                                    </label>
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

    @if($appInfo->rajuk_submission_status == 0)
        checkgenerator();
    @endif

    function checkgenerator() {

        $.ajax({
            url: '/rajuk-luc-general/check-api-request-status',
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
                        myWindow = location.replace(response.rajuk_redirect_url);
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
</script>
