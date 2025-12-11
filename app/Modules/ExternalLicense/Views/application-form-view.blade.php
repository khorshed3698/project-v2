<?php
$accessMode = ACL::getAccsessRight('RajukLUCGeneral');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right , Please contact with system admin if you have any query !');
}

?>
<style>
    .panel-heading {
        padding: 2px 5px;
        overflow: hidden;
    }

    .process-history {
        display: none;
    }

    .full-page {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgb(255, 255, 255);
        z-index: 9000;
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
                    <strong>{{$serviceConfiguration->servicename}}
                        of {{$serviceConfiguration->agencyname}}</strong>
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

                @if( !empty($appInfo->process_desc ) )
                <div class="alert alert-success" role="alert">{{ $appInfo->process_desc }}</div>
                @endif

                <ol class="breadcrumb">
                        <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                        <li class="highttext"><strong> Date of Submission:
                                {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                        </li>
                        <li><strong>Current Status : </strong> {{ ucfirst($appInfo->stakeholder_status_name)}}</li>
                        {{--                    @if(!empty($appInfo->redirect_url))--}}
                        {{--                        <li><a role="button" id="statusCheck" class="btn btn-primary">Check Status</a></li>--}}
                        {{--                    @endif--}}
                        @if (isset($appInfo)  && !empty($appInfo->certificate_url))
                            <li>
                                <a href="{{ url($appInfo->certificate_url) }}"
                                   class="btn show-in-view btn-xs btn-info"
                                   title="Download Approval Letter" target="_blank"> <i
                                            class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                            </li>
                        @endif
                    </ol>

               <!-- start -:- breadcrumb other info -->
                @if(!empty($appInfo->others_info))
                <?php
                    $others_info = json_decode($appInfo->others_info, true);
                ?>
                <ol class="breadcrumb">
                    @foreach($others_info as $key => $value)
                        @if(!empty($value) && !is_array($value))
                            @if(filter_var($value, FILTER_VALIDATE_URL) !== false)
                                <li><strong>{{ str_replace('_', ' ',ucfirst($key)) }} : </strong><a class="btn btn-xs btn-primary" href="{{ $value }}" target="_blank">Download</a></li>
                            @else
                                <li><strong>{{ str_replace('_', ' ',ucfirst($key)) }} : </strong>{{ $value }}</li>
                            @endif
                        @endif
                    @endforeach
                </ol>
                @endif
                <!-- end -:- breadcrumb other info -->

                <!--                Payment information-->
                @include('SonaliPaymentStackHolder::payment-information')
                <div id="responseData">

                </div>
                @if(!empty($appInfo->redirect_url) && !empty($serviceConfiguration->view_in_iframe))
                    <div class="pull-right">

                        <a class="btn btn-md btn-block" data-toggle="collapse" href="#oss_data" role="button"
                           aria-expanded="false" aria-controls="collapseExample">
                            <i class="far fa-eye"></i>
                            View Data
                        </a>
                    </div>
                @endif
                <div id="oss_data" class="{{!empty($appInfo->redirect_url) && !empty($serviceConfiguration->view_in_iframe)?'collapse':''}}">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-body">

                                <?php
                                $sl = 1;
                                $defaultData = [];
                                if(!empty($serviceConfiguration->default_data)){
                                    $defaultData = (array)$serviceConfiguration->default_data;
                                }
                                ?>

                                @foreach($appData as $key=>$value)
                                    <?php
                                    if(array_key_exists($key,$defaultData)){
                                        continue;
                                    }
                                        $input_specification_key = isset($serviceConfiguration->input_specification->$key)?$serviceConfiguration->input_specification->$key:'';
                                        if(!empty($input_specification_key->label)){
                                            $label = ucfirst($serviceConfiguration->input_specification->$key->label);
                                        }else{
                                            $label = ucfirst(str_replace('_', ' ', $key));
                                        }
                                    ?>

                                    @if($key != 'attachments')
                                        @if($sl % 2 == 1)
                                            <div class="form-group">
                                                <div class="row">
                                                    @endif
                                                    <div class="col-md-6 col-xs-12">

                                                        <div class="col-md-5 col-xs-6">
                                                            <span class="v_label">{{ucfirst($label)}}</span>
                                                            <span class="pull-right">&#58;</span>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <?php
                                                                $mailformat = '/^\w+([\.-]?\w+)*@.+([\.-]?\w+)*(\.\w{2,3})+$/';
                                                                $listData = '/^\w+([\.-]?\w+)*@.+$/';
                                                            if (is_array($value)) {
                                                                $allSelected = '';
                                                                foreach ($value as $selectedValue) {
                                                                    $allSelected .= explode('@', $selectedValue)[1] . ',';
                                                                }
                                                                echo rtrim($allSelected, ',');
                                                            } elseif (preg_match($mailformat, $value)) {
                                                                echo $value;
                                                            } elseif (preg_match($listData, $value)) {
                                                                echo explode('@', $value)[1];
                                                            } else {
                                                                echo $value;
                                                            }
                                                            ?>

                                                        </div>

                                                    </div>
                                                    @if($sl % 2 == 0)
                                                </div>
                                            </div>
                                        @endif
                                        <?php
                                        $sl++;
                                        ?>

                                    @endif

                                @endforeach

                            </div>

                        </div>
                    </div>

                    @if(!empty($appData->attachments))
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>File Attachment</strong></div>
                            <div class="panel-body">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-striped table-bordered table-hover ">
                                        <tbody>
                                        <?php $i = 1;
                                        $document = $appData->attachments;
                                        ?>
                                        @foreach($document as $key => $row)
                                            @if(!empty($row))
                                                <tr>
                                                    <td>{!! $i !!} .</td>
                                                    <td colspan="6"> {{str_replace('_',' ',$key)}}</td>
                                                    <td colspan="2">
                                                        @if($row !='' && $row !=null)
                                                            <a target="_blank" class="btn btn-xs btn-primary"
                                                               href="{{$row}}"
                                                               title="{{str_replace('_',' ',$key)}}">
                                                                <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                                View File
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                            <?php $i++; ?>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($serviceConfiguration->declaration))
                        <div class="panel panel-info">
                            <div class="panel-heading" style="padding-bottom: 4px;">
                                <strong>DECLARATION</strong>
                            </div>
                            <div class="panel-body">

                                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            {!! Form::checkbox('accept_terms',1,true, array('id'=>'accept_terms',
                                            'class'=>'required','disabled')) !!}
                                            {{$serviceConfiguration->declaration}}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(!empty($appInfo->redirect_url) && !empty($serviceConfiguration->view_in_iframe))
                        <div class="col-md-12">
                            <a class="btn btn-info " id="externalFormFullScreen">Full Screen</a>
                        </div>

                        <div class="col-md-12" id="externalForm">
                            <a style="display: none" class="btn btn-danger" id="externalFormClose">Minimize</a>
                            <iframe src="{{$appInfo->redirect_url}}" width="100%" height="600" title="description"></iframe>
                        </div>
                    @elseif(!empty($appInfo->redirect_url))
                        <div class="col-md-12 text-center">
                            <a class="btn btn-info" id="externalForm">Please click here to fill detailed application form</a>
                        </div>
                        <br>
                        <br>
                        <br>
                    @endif

                </div>


            </div>
        </div>


    </div>
</section>

<script>

    {{--    @if($appInfo->rajuk_submission_status == 0)--}}
    {{--        checkgenerator();--}}
    {{--    @endif--}}



    var popupWindow=null;
    var isOpen = 0;
    $(document).on('click', '#externalForm', function (e) {
        const mainWinHeight = window.innerHeight;
        const mainWinWidth = window.innerWidth;
        // Set the height of the popup window to 90% of the main window's height
        const popupHeight = Math.round(mainWinHeight * 0.9);
        const popupWidth = Math.round(mainWinWidth * 0.9);
        var left = (screen.width - popupWidth) / 2;
        var top = (screen.height - popupHeight) / 4;
        const windowFeatures = `width=${popupWidth},height=${popupHeight},scrollbars=yes,top=${top},left=${left}`;
        $("body").attr('onclick',"parent_disable()");
        let linkUrl = "{{$appInfo->redirect_url}}";
        linkUrl = linkUrl.replace(/&amp;/g, "&");
        popupWindow = window.open(linkUrl, "_blank", windowFeatures);

    });
    function parent_disable() {
        if(popupWindow && !popupWindow.closed){
            popupWindow.focus();
        }

    }
    $(document).on('click', '#statusCheck', function (e) {
        $(this).html("<i class='fa fa-spinner fa-spin'> </i> Loading ...");
        $(this).addClass('disabled');
        $appInfo =
            $.ajax({
                url: '/licence-applications/external-service/status-check',
                type: "POST",
                data: {
                    app_id: '{{\App\Libraries\Encryption::encodeId($appInfo->id)}}',
                    process_type_id: '{{\App\Libraries\Encryption::encodeId($appInfo->process_type_id)}}',
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $("#responseData").text(JSON.stringify(response.result));
                        $("#statusCheck").removeClass('disabled');
                        $("#statusCheck").text("Check Status");
                    }
                }, error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
            });
    });
    $(document).on('click', '#externalFormFullScreen', function (e) {
        $("#externalForm").addClass('full-page');
        $("#externalForm").find('object').css('height', '100vh')
        $("#externalFormClose").show();
    })
    $(document).on('click', '#externalFormClose', function (e) {
        $("#externalForm").removeClass('full-page');
        $("#externalForm").find('object').css('height', '600px')
        $("#externalFormClose").hide();
    })

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
