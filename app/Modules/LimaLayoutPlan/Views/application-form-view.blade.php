<?php
$accessMode = ACL::getAccsessRight('LimaFactoryLayout');
if (!ACL::isAllowed($accessMode, '-V-')) {
    die('You have no access right ! Please contact with system admin if you have any query.[ML-1027]');
}
?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }
</style>
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
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h4 class="pull-left">Application for Factory Layout Approval</h4>
                        <div class="pull-right">
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
                            @if(!empty($appInfo->dife_trackingt_id))
                                <li><strong>DIFE Tracking no.
                                        : </strong>{{ $appInfo->dife_trackingt_id }}</li>
                            @endif
                            <li class="highttext"><strong> Date of Submission:
                                    {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                            </li>
                            <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
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
                        @include('SonaliPaymentStackHolder::payment-information')

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
                                <h5>Factory’s Basic Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Full Name of the Factory (in English)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_en)?$app_data->factory_name_en:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Full Name of the Factory (in Bengali)</span>
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
                                            <span class="v_label">Postal Address to the Factory</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->factory_name_bn)?$app_data->correspondence_address_with_factory:'' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end -:- Basic information of the factory --}}

                        {{-- start -:- Identification of the owner --}}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5>Owner’s Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Name of the Owner/Managing Director</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->owner_name)?$app_data->owner_name:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory Owner’s Present Address</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->owner_present_address)?$app_data->owner_present_address:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Factory Owner’s Permanent Address</span>
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
                                <h5>Building’s Approval Information</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Name of the Approval Authority</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->notify_authority_name)?$app_data->notify_authority_name:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Load Bearing capacity according to the Structural Design (PSF)</span>
                                            <span class="pull-right">&#58;</span>
                                        </div>
                                        <div class="col-md-7">{{ !empty($app_data->layout_load_bearing_capacity)?$app_data->layout_load_bearing_capacity:'' }}</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="text-left col-md-5">
                                            <span class="v_label">Plan Approval Date</span>
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
                                <h5>Information about the Working Environment</h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>SN</th>
                                                <th>Types of Machines</th>
                                                <th>Number</th>
                                                <th>Location</th>
                                                <th>Load</th>
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
                                                    <?php $inc = 1;?>
                                                    @foreach($document as $key => $value)
                                                        @if(!empty($value->doc_path))
                                                            <tr>
                                                                <td>{{$inc++}} .</td>
                                                                <td>{{$value->doc_name}}</td>
                                                                <td>
                                                                    <a target="_blank" class="btn btn-xs btn-primary"
                                                                       href="{{URL::to('/uploads/'.$value->doc_path)}}"
                                                                       title="Other File {{$key+1}}">
                                                                        <i class="fa fa-file-pdf-o"
                                                                           aria-hidden="true"></i>
                                                                        Open File
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endif
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

<script>
    function checkStatus() {
        $.ajax({
            url: '/licence-applications/lima-factory-layout/check_status',
            type: "POST",
            data: {
                app_id: '{{$appInfo->id}}',
                tracking_no: '{{$appInfo->tracking_no}}',
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function () {
                $('#status_check').html('<i class="fa fa-spinner fa-spin"></i> Status Check');
                $('#status_check').prop('disabled', true);
            },
            success: function (response) {
                if (response.status == "Success") {
                    $("#alert_success span").text(response.data.status);
                    $('#alert_success').show();
                    $('#alert_error').hide();
                } else {
                    $("#alert_error span").text(response.data.status);
                    $('#alert_error').show();
                    $('#alert_success').hide();
                }
            }, error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            },
            complete: function () {
                $('#status_check').html('Status Check');
                $('#status_check').prop('disabled', false);
            }
        });
        return false; // keeps the page from not refreshing
    }// end -:- checkStatus()
</script>

