<?php
$accessMode = ACL::getAccsessRight('LabourInspection');
if (!ACL::isAllowed($accessMode, '-V-')) {
    die('You have no access right ! Please contact with system admin if you have any query.[ML-1027]');
}
?>
<!-- Start -:- View Form --->
<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                <div class="alert alert-success alert-dismissible" hidden id="alert_success">
                    <span></span>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                </div>
                <div class="alert alert-danger alert-dismissible" hidden style="display: none" id="alert_error">
                    <span></span>
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                </div>
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4 class="pull-left">Information of Factory/Establishment</h4>
                        <h5 class="pull-right">Form 77 - Dhara 3ko and 326 and, Bidhi 7(1), 354, 355(3), 356(2) and
                            357(1)</h5>
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <div class="pull-left">
                                    Application for License of Establishment
                                </div>
                                <div class="pull-right">
                                    @if(!empty($appInfo->ml_app_view_url))
                                        <a class="btn btn-md btn-primary" href="{{ $appInfo->ml_app_view_url }}"
                                           target="_blank"
                                           role="button"
                                           aria-expanded="false" id="status_check">
                                            Application View
                                        </a>
                                    @endif
                                    <a class="btn btn-md btn-info" role="button"
                                       aria-expanded="false" id="status_check" onclick="checkStatus()">
                                        Status Check
                                    </a>
                                    <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo"
                                       role="button"
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
                                        <li><strong>Mutation Land Application no.
                                                : </strong>{{ $appInfo->rajuk_application_no  }}</li>
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
{{--                                    @if (isset($app_data)  && empty($appInfo->ml_redirect_url))--}}
{{--                                        <li>--}}
{{--                            <span id="submit_status" style="padding: 5px" class="label label-info"><i--}}
{{--                                        class="fa fa-spinner fa-spin"></i> Submitting Application</span>--}}
{{--                                        </li>--}}
{{--                                    @endif--}}
                                </ol>
                                @if($appInfo->ml_submission_status == 0)
                                    <div class="alert alert-success alert-dismissible text-center" hidden=""
                                         style="display: block;"
                                         id="submitting_message">
                                        <span>Waiting for submission. After submission, you will redirect to mutation website</span>
                                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×
                                        </button>
                                    </div>
                                @endif

                            <!--                Payment information-->
                                @include('SonaliPaymentStackHolder::payment-information')


                            </div>
                        </div><!-- Buttom Panel-->


                        {{-- start -:- Industry Classification/Type --}}
                        <div class="panel panel-info">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Classification/Industrial Sector</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->industry_id) ? explode('@', $app_data->industry_id)[1] : '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Industry Classification/Type --}}

                        {{-- start -:- Basic information of the factory --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Basic information of the factory</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory full name (English)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_en)?$app_data->factory_name_en:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory full name (Bengali)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_bn)?$app_data->factory_name_bn:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory Head Office Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_bn)?$app_data->factory_head_office_address:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Correspondence address with factory</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_bn)?$app_data->correspondence_address_with_factory:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Correspondence email address with factory</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->layout_factory_mail_address)?$app_data->layout_factory_mail_address:'' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Basic information of the factory --}}

                        {{-- start -:- Identification of the owner --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Identification of the owner</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Name of Owner/Managing Director</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->owner_name)?$app_data->owner_name:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Present address of owner</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->owner_present_address)?$app_data->owner_present_address:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory Head Office Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_bn)?$app_data->factory_head_office_address:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Permanent address of owner</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->owner_permanent_address)?$app_data->owner_permanent_address:'' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Identification of the owner --}}


                        {{-- start -:- Factory location --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Factory location</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Division</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->division_id) ? explode('@', $app_data->division_id)[1] : '' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">District</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->district_id) ? explode('@', $app_data->district_id)[2] : '' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Upazila/Thana</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->upazilla_id) ? explode('@', $app_data->upazilla_id)[2] : '' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Post Office</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->post_office) ? explode('@', $app_data->post_office)[2] : '' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Road no. (in English)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->road_no_en)?$app_data->road_no_en:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Road no. (in Bengali)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->road_no_bn)?$app_data->road_no_bn:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">House/Holding/Village/ Mahalla (in English)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->holding_name_en)?$app_data->holding_name_en:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">House/Holding/Village/ Mahalla (in Bengali)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->holding_name_bn)?$app_data->holding_name_bn:'' }}</div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Nearest Railway Station/Steamer Ghat/Launch Ghat</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->nearest_railway_steamer_launch)?$app_data->nearest_railway_steamer_launch:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Nearest bus stop</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->nearest_bus_stop)?$app_data->nearest_bus_stop:'' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Factory location --}}

                        {{-- start -:- Building renovation information --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Building renovation information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Name of Notifying Authority</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->notify_authority_name)?$app_data->notify_authority_name:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Load carrying capacity of building</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->layout_load_bearing_capacity)?$app_data->layout_load_bearing_capacity:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Date of Approval</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->building_plan_approval_date)?$app_data->building_plan_approval_date:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Reference No.</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->building_plan_reference_no)?$app_data->building_plan_reference_no:'' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Building renovation information --}}

                        {{-- end -:- Information regarding work environment --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Managing Authority</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th>Type of machine</th>
                                                <th>Number/Measurement</th>
                                                <th>Location</th>
                                                <th>Amount of load generated during operation</th>
                                            </tr>
                                            </thead>
                                            <tbody>
{{--                                            {{ dd($app_data->factory_machine_type,$app_data->factory_machine_measurement,$app_data->factory_machine_location,$app_data->factory_machine_amount) }}--}}
                                            @if(count($app_data->factory_machine_type) > 0)
                                                <?php $sn = 1; ?>
                                                @foreach($app_data->factory_machine_type as $key => $value)
                                                    <tr>
                                                        <td>{{ $sn++ }}</td>
                                                        <td>{{ !empty($app_data->factory_machine_type[$key])?$app_data->factory_machine_type[$key]:'' }}</td>
                                                        <td>{{ !empty($app_data->factory_machine_measurement[$key])?$app_data->factory_machine_measurement[$key]:'' }}</td>
                                                        <td>{{ !empty($app_data->factory_machine_location[$key])?$app_data->factory_machine_location[$key]:'' }}</td>
                                                        <td>{{ !empty($app_data->factory_machine_amount[$key])?$app_data->factory_machine_amount[$key]:'' }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Information regarding work environment --}}


                        <div class="panel panel-info">
                            <div class="panel-heading">Attachments</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class=" col-md-12">
                                            <table class="table table-bordered table-hover" id="loadDetails">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Document Name</th>
                                                    <th>File</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(!empty($document))
                                                    @foreach($document as $key => $value)
                                                        <tr>
                                                            <td>{{$key+1}} .</td>
                                                            <td>{{$value->doc_name}}</td>
                                                            <td>
                                                                <a target="_blank" class="btn btn-xs btn-primary"
                                                                   href="{{URL::to('/uploads/'.$value->doc_path)}}"
                                                                   title="Other File {{$key+1}}">
                                                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                    Open File
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div><!-- .panel-success .panel-body -->
                </div><!-- .panel-success -->
            </div><!-- ./box-body -->
        </div><!-- ./box -->
    </div><!-- ./col-md-12 -->
</section>
<!-- End -:- View Form --->


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

    .photo_size {
        max-height: 150px;
        max-width: 200px;
    }
</style>

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
                    } else if (response.status == 2) { //already submitted to land
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
