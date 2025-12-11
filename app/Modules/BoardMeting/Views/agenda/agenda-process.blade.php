@extends('layouts.admin')
@section('content')
    <?php
    $moduleName = Request::segment(1);
    $user_type = CommonFunction::getUserType();
    $accessMode = "V";
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    $board_meeting_id = \App\Libraries\Encryption::encodeId($boardMeetingInfo->id)
    ?>
    <section class="content">
        <style>
            * {
                font-weight: normal;
            }

            .m10 {
                margin-bottom: 20px;
            }

            .m10 img {
                margin: 15px 0;
            }

            #myModal .modal-content {
                min-height: 300px;
                max-height: 550px;
                overflow-y: scroll;
            }

            div.floating-cart {
                position: absolute;
                top: 0;
                left: 0;
                width: 900px;
                height: 50px;
                background: #fff;
                z-index: 200;
                overflow: hidden;
                box-shadow: 0px 5px 31px -1px rgba(0, 0, 0, 0.15);
                display: none;
            }

            div.floating-cart .stats-container {
                display: none;
            }

            /*div.floating-cart .product-front{width:100%; top:0; left:0;}*/
            div.floating-cart.moveToCart {
                left: 500px !important;
                top: 900px !important;
                bottom: 0 !important;
                width: 500px;
                height: 47px;
                -webkit-transition: all 800ms ease-in-out;
                -moz-transition: all 800ms ease-in-out;
                -ms-transition: all 800ms ease-in-out;
                -o-transition: all 800ms ease-in-out;
                transition: all 800ms ease-in-out;
            }

            textarea {
                resize: none;
            }
        </style>
        <div class="box">
            <div class="box-body">

{{--                @include('BoardMeting::progress-bar')--}}

                <div class="col-lg-12">
                    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                </div>
                <div class="col-lg-12">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4><i class="fa fa-list"></i> <b>{!! trans('messages.information_for_board') !!}</b></h4>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="panel panel-info">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-4  col-md-offset-1 "
                                             style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;padding: 0px"
                                                   for="infrastructureReq" class="text-success col-md-6">{!! trans('messages.basic_list_of_meeting') !!}
                                            </label>
                                            <br>
                                        </div>
                                        <div class="col-md-4 col-md-offset-1 " style="border-bottom: 1px solid #c7bebe">
                                            <label style="font-size: 15px;color: #478fca;">{!! trans('messages.agenda_info') !!}</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">

                                        <div class="col-md-4  col-md-offset-1">
                                            <table aria-label="Detailed Report Data Table">
                                                <tr>
                                                    <th aria-hidden="true"  scope="col"></th>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">{!! trans('messages.meeting_no') !!}. :
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$boardMeetingInfo->meting_number}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">{!! trans('messages.meeting_date') !!}:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        {{date("d M Y h:m a", strtotime($boardMeetingInfo->meting_date))}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        {!! trans('messages.meeting_places') !!}:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;{{$boardMeetingInfo->location}}</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold;font-size: 14px; color: #5f5f5f;">
                                                        {!! trans('messages.status') !!}:
                                                    </td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp;<button
                                                                class="btn btn-xs btn-{{$boardMeetingInfo->panel}}">{{$boardMeetingInfo->status_name}}</button>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                        <div class="col-md-4 col-md-offset-1">
                                            <table aria-label="Detailed Report Data Table">
                                                <tr>
                                                    <th aria-hidden="true"  scope="col"></th>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;"> {!! trans('messages.name_of_agenda') !!} :</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;&nbsp; {{$agendaInfo->name}}</td>
                                                </tr>

                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">{!! trans('messages.status') !!}:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        &nbsp;@if($agendaInfo->status_name == '')
                                                            <label class="btn btn-warning btn-xs">Pending</label>
                                                        @else
                                                            <label class="btn btn-{{$agendaInfo->panel}} btn-xs">{{$agendaInfo->status_name}}</label>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-weight: bold; color: #5f5f5f;">{!! trans('messages.remarks') !!}:</td>
                                                    <td style="font-size: 13px;color: #5f5f5f;">
                                                        <span>{!! $agendaInfo->remarks  !!}</span>
                                                    </td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-panel-info">
                                <div id="listOfProcess"
                                     style="display:none;" class="panel panel-info">
                                    <div class="panel-heading"><i class="fa fa-list"> </i> <b>{!! trans('messages.process_waiting_for_board_meeting') !!}<span id="m"></span></b></div>
                                    <div class="nav-tabs-custom hidden list_of_process"
                                         style="margin-top: 15px;padding: 0px 5px;">
                                        <ul class="nav nav-tabs">

                                            @if($user_type != '1x1011' && $user_type != '5x505')

                                                <li id="tab1" class="active">
                                                    <a data-toggle="tab" href="#list_desk" class="mydesk"
                                                       aria-expanded="true">
                                                        <b>Process List</b>
                                                    </a>
                                                </li>

                                            @endif
                                        </ul>
                                        <div class="tab-content">
                                            <div id="list_desk" class="tab-pane active " style="margin-top: 20px;">

                                                <div class="table-responsive">
                                                    <table id="table_desk" class="table table-striped display"
                                                           style="width: 100%" aria-label="Detailed Report Data Table">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="select_all_process">
                                                                &nbsp;
                                                            </th>
                                                            <th>{!! trans('messages.current_desk') !!}</th>
                                                            <th>{!! trans('messages.tracking_no') !!}</th>
                                                            <th>{!! trans('messages.process_type') !!}</th>
                                                            <th style="width: 35%">{!! trans('messages.reference_data') !!}</th>
                                                            <th>{!! trans('messages.status') !!}</th>
                                                            <th>{!! trans('messages.modified') !!}</th>
                                                            <th>{!! trans('messages.action') !!}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>

                                                    <div class="col-md-12">

                                                        <b>
                                                            <input type="checkbox" name="select_all"
                                                                   class="select_all_process cursor"
                                                                   id="select_all_process_list">
                                                            <label class="cursor" for="select_all_process_list">{!! trans('messages.select_all') !!}</label>&nbsp;
                                                        </b>

                                                        <img src="/assets/images/Arrow_right.png" alt="Arrow_right"
                                                             style="width: 50px ;height:15px">
                                                        {!! Form::button('<i class="fa fa-plus"></i> Add to Board Meeting', array('type' => 'button', 'value'=> 'Add to Board Meeting', 'class' => 'add_to_board_from_process_list btn btn-warning btn-sm')) !!}
                                                    </div>
                                                    <div class="col-md-12"><br></div>

                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="panel panel-info">
                                <div class="panel-heading"><i class="fa fa-list"></i> <b>{!! trans('messages.processes_under_the_agenda') !!}</b>
                                </div>
                                @if(in_array($boardMeetingInfo->status,[5,10]))  {{-- //5= fixed status 10=complete--}}

                                {{--Chaking already exist process from board meeting --}}
                                @if(count($alreadyExistProcess) > 0)
                                    <div class="nav-tabs-custom hidden list_of_process"
                                         style="margin-top: 15px;padding: 0px 5px;">
                                        <ul class="nav nav-tabs">

                                            @if($user_type != '1x1011' && $user_type != '5x505')

                                                <li id="tab1" class="active">
                                                    <a data-toggle="tab" href="#list_desk" class="mydesk"
                                                       aria-expanded="true">
                                                        <b>{!! trans('messages.my_process') !!}</b>
                                                    </a>
                                                </li>

                                            @endif
                                        </ul>
                                        <div class="tab-content">
                                            <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                                <div class="table-responsive" style="overflow:hidden;">
                                                    {!! Form::open(array('url' => '/board-meting/agenda/update-remarks','method' => 'post', 'class' => 'form-horizontal', 'id'=>'update_remarks', 'role' => 'form')) !!}
                                                    <input type="hidden" name = "agenda_name" value="{{$agendaName}}">
                                                    <table id="board_meting" class="table table-striped display"
                                                           style="width: 100%" aria-label="Detailed Report Data Table">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="select_all"> &nbsp;</th>
                                                            <th>{!! trans('messages.tracking_no') !!}</th>
                                                            <th style="width: 30%">{!! trans('messages.reference_data') !!}</th>
                                                            <th>{!! trans('messages.status') !!}</th>
                                                            <th>{!! trans('messages.decision') !!}</th>
                                                            <th style="width: 12%">{!! trans('messages.action') !!}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>


                                                    @if($boardMeetingInfo->status == 5)
                                                        @if($chairmanRemarks == false)
                                                            @if($agendaInfo->status_name == '')
                                                                <div class="col-md-12">
                                                                    <div class="col-md-2" >
                                                                        <b>
                                                                            <input style="cursor:pointer" type="checkbox" name="select_all"
                                                                                   class="select_all cursor" id="select_all">
                                                                            <label class="cursor" for="select_all">Select All</label>
                                                                        </b>
                                                                        <h3 style="margin: 0px">
                                                                            <img src="/assets/images/Right_advance_arrow_down.png" alt="Right_advance_arrow_down"
                                                                                 style="
                                                            width: 60px">
                                                                        </h3>
                                                                    </div>
                                                                    <div class="col-md-3">
                                                                        </br></br>
                                                                        <textarea style="height: 50px;" id="remarksAll"
                                                                                  placeholder="Write your remark here for all selected process..."
                                                                                  class="form-control" rows="3"
                                                                                  name="remarks"></textarea>
                                                                    </div>
                                                                    @if (count($bm_chairman) > 0)
                                                                        @if (Auth::user()->user_email == $bm_chairman->user_email)
                                                                            <div class="col-md-3">
                                                                                </br>
                                                                                </br>
                                                                                {!! Form::select('bm_status_id', $status, null, array('class'=>'form-control input-lg',
                                                                                'placeholder' => 'Select Status', 'id'=>"bm_status_id")) !!}
                                                                                @if($errors->first('bm_status_id'))
                                                                                    <span class="control-label">
                                                                        <em><i class="fa fa-times-circle-o"></i> {{ $errors->first('bm_status_id','') }}</em>
                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    <div class="col-md-4">
                                                                        </br>
                                                                        </br>

                                                                        {!! Form::button('<i class="fa fa-save"></i> Save Remarks &  Status', array('type' => 'submit', 'value'=> 'Save Remarks &  Status', 'class' => 'btn save_remarks  btn-primary btn-lg','style'=>'padding: 9px 20px')) !!}
                                                                    </div>
                                                                    </br>

                                                                </div>
                                                                <div class="col-md-12"><br></div>
                                                            @else
                                                            @endif

                                                        @endif
                                                    @endif
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <h4 class="text-center">  {!!Html::image('assets/images/warning.png','Logo') !!}
                                        No process has been included.
                                    </h4>
                                @endif
                                @else

                                    <div class="nav-tabs-custom hidden list_of_process"
                                         style="margin-top: 15px;padding: 0px 5px;">
                                        <ul class="nav nav-tabs">

                                            @if($user_type != '1x1011' && $user_type != '5x505')

                                                <li id="tab1" class="active">
                                                    <a data-toggle="tab" href="#list_desk" class="mydesk"
                                                       aria-expanded="true">
                                                        <b>{!! trans('messages.my_process') !!}</b>
                                                    </a>
                                                </li>

                                            @endif
                                        </ul>
                                        <div class="tab-content">
                                            <div id="list_desk" class="tab-pane active" style="margin-top: 20px">
                                                <div class="table-responsive" style="overflow:hidden;">
                                                    {!! Form::open(array('url' => '/board-meting/agenda/update-remarks','method' => 'post', 'class' => 'form-horizontal', 'role' => 'form')) !!}
                                                    <table id="board_meting" class="table table-striped display"
                                                           style="width: 100%" aria-label="Detailed Report Data Table">
                                                        <thead>
                                                        <tr>
                                                            <th><input type="checkbox" class="select_all"> &nbsp;</th>
                                                            <th>{!! trans('messages.tracking_no') !!}</th>
                                                            <th style="width: 30%">{!! trans('messages.reference_data') !!}</th>
                                                            <th>{!! trans('messages.status') !!}</th>
                                                            <th>{!! trans('messages.decision') !!}</th>
                                                            <th style="width: 12%">{!! trans('messages.action') !!}</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>

                                                    @if(!in_array($boardMeetingInfo->status,[5,10])) {{-- //5= fixed status 10=complete --}}
                                                    {{--<div class="col-md-12">--}}
                                                        {{--<b>--}}
                                                            {{--<input  type="checkbox" name="select_all" class="select_all cursor"--}}
                                                                   {{--id="select_all">--}}
                                                            {{--<label  class="cursor" for="select_all">Select All</label>--}}
                                                        {{--</b>--}}

                                                        {{--<img src="/assets/images/Arrow_right.png" alt="Arrow_right"--}}
                                                             {{--style="width: 50px ;height:15px">--}}
                                                        {{--{!! Form::button('<i class="fa fa-times"></i> Delete for Board Meeting', array('type' => 'button', 'value'=> 'Delete for Board Meeting', 'class' => ' delete_for_board_meeting btn btn-danger btn-sm')) !!}--}}
                                                    {{--</div>--}}
                                                    {{--<div class="col-md-12"><br></div>--}}
                                                    @endif
                                                    {!! Form::close() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                @endif
                            </div>
                            <a class="btn btn-default" href="/board-meting/agenda/list/{{$board_meeting_id}}">Back</a>

                            <!-- Modal -->
                            <div id="myModal" class="modal fade" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">{!! trans('messages.all_remarks') !!}</h4>
                                        </div>
                                        <div class="modal-body">



                                            <div class="table-responsive">
                                                <table class="table table-striped display"
                                                       style="width: 100%" aria-label="Detailed Report Data Table">
                                                    <thead>
                                                    <tr>
                                                        <th>User Name</th>
                                                        <th>{!! trans('messages.remarks') !!}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="remarkView">
                                                    </tbody>
                                                </table>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="panel panel-danger hidden" id="help_info">
                                <div class="panel-heading"></div>
                                <div class="panel-body">
                                    <h4>  {!!Html::image('assets/images/warning.png','Logo') !!}
                                        If you want to add process under this agenda, please select a process type by <a
                                                href="{{'/board-meting/agenda/edit/'.$agendaId}}"> clicking here</a>.
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="list_of_process hidden">

                    </div>
                </div>
            </div>
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
            <div class="col-md-12"><br></div>
        </div>
    </section>


@endsection
@section('footer-script')
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>
    @include('partials.datatable-scripts')
    <script>
        $(document).ready(function () {
            $("#process").validate({
                errorPlacement: function () {
                    return false;
                }
            });
            process_list_array = [];
            board_meeting_array = [];
            $('body').on('click', '.checkbox_process', function (e) {
                var process_id = $(this).val();
                if ($(this).is(":checked")) {
                    process_list_array.push(process_id);
                } else {
                    process_list_array = jQuery.grep(process_list_array, function (value) {
                        return value != process_id;
                    });
                }
            });

            $('body').on('click', '.checkbox', function (e) {
                var process_id = $(this).val();
                if ($(this).is(":checked")) {
                    board_meeting_array.push(process_id);
                    console.log(board_meeting_array);
                } else {
                    board_meeting_array = jQuery.grep(board_meeting_array, function (value) {
                        return value != process_id;
                    });
                    console.log(board_meeting_array);
                }
            });


            var process_type_id = '{{$agendaInfo->process_type_id}}';
            if (process_type_id != 0) {

                $('.list_of_process').removeClass('hidden');
            } else {
                $('#help_info').removeClass('hidden');
            }
            $('#application_status').change(function () {
                var status = $(this).val();
                if (status == 3) {
                    var agenda_id = '{{$agendaId}}';
                    var board_meeting_id = '{{$board_meeting_id}}';
                    var _token = $('input[name="_token"]').val();
                    $(this).after('<span class="loading_data">Loading...</span>');
                    var self = $(this);
                    $.ajax({
                        type: "get",
                        url: "<?php echo url(); ?>/board-meting/agenda/get-roll-over-date",
                        data: {
                            _token: _token,
                            board_meeting_id: board_meeting_id,
                            agenda_id: agenda_id
                        },
                        success: function (response) {
                            $('.nextDate').removeClass('hidden');
                            if (response.responseCode == 1) {
                                $('.nextDateData').html(response.data);
                                if (response.status == false) {
                                    $(".send").prop("disabled", true);
                                }
                            }
                            $(self).next().hide();
                        }
                    });
                } else {
                    $('.nextDate').addClass('hidden');
                    $(".send").prop("disabled", false);
                }
            });


            //action status
//            $('.send').click(function () {
//                toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure to apply the status?',
//                    {
//                        closeButton: true,
//                        allowHtml: true,
//                        timeOut: 0,
//                        extendedTimeOut: 0,
//                        positionClass: "toast-top-center",
//                        onShown: function (toast) {
//                           if( $("#confirmationRevertYes").click(function () {
//                                $( "#process" ).submit();
//                            }));else{
//                                alert(4774);
//                               return false;
//                            };
//                        }
//                    });
//            });
            // show search div
            // $('body').on('click', '.processList', function (e) {
            //
            //     if ($('#listOfProcess').is(":visible")) {
            //
            //         $('.processList').find('i').removeClass("fa-arrow-up fa");
            //         $('.processList').find('i').addClass("fa fa-arrow-down");
            //         $(".processList").css("background-color", "");
            //         $(".processList").css("color", "");
            //     } else {
            //         $(this).find('i').removeClass("fa fa-arrow-down");
            //         $(this).find('i').addClass("fa fa-arrow-up");
            //         $(".processList").css("background-color", "#1abc9c");
            //         $(".processList").css("color", "white");
            //     }
            //     $('#listOfProcess').slideToggle();
            // });
            $('body').on('click', '.add_to_board', function () {
                var $this = $(this);
                $this.text('Already Added');
                $this.removeClass('add_to_board');

                var process_list_id = $(this).val();
                var agenda_id = '{{$agendaId}}';
                var productCard = $(this).parent().parent();
                var _token = $('input[name="_token"]').val();
                $.ajax({
                    type: "POST",
                    url: "<?php echo url(); ?>/board-meting/save-board-meting",
                    data: {
                        _token: _token,
                        process_list_id: process_list_id,
                        agenda_id: agenda_id
                    },
                    success: function (response) {
                        window.setTimeout(function () {
                            board_meting.ajax.reload();
                            table_desk.ajax.reload();
                        }, 300);
                        toastr.success('Successfully Added!!');
                    }
                });
                //process move related script
                var position = productCard.offset();
                $("body").append('<div class="floating-cart"></div>');
                var cart = $('div.floating-cart');
                productCard.clone().appendTo(cart);
                $(cart).css({
                    'top': position.top + 'px',
                    "left": position.left + 'px'
                }).fadeIn("slow").addClass('moveToCart');
                setTimeout(function () {
                    $("body").addClass("MakeFloatingCart");
                }, 800);
                setTimeout(function () {
                    $('div.floating-cart').remove();
                    $("body").removeClass("MakeFloatingCart");

                    setTimeout(function () {
                        $("#cart .cart-item").last().removeClass("flash");
                    }, 10);

                }, 1000);
            });


            //select all checkboxes
            $(".select_all").click(function () {  //"select all" change
                $(".checkbox").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
                if ($(this).is(":checked")) {
                    $('.checkbox:checked').each(function (i, obj) {
                        board_meeting_array.push(this.value);
                    });
                } else {
                    board_meeting_array = [];
                }
            });
//".checkbox" change
            $('.checkbox').change(function () {
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (false == $(this).prop("checked")) { //if this item is unchecked
                    $("#select_all").prop('checked', false); //change "select all" checked status to false
                }
                //check "select all" if all checkbox items are checked
                if ($('.checkbox:checked').length == $('.checkbox').length) {
                    $("#select_all").prop('checked', true);
                }
            });


            //select all checkboxes process list
            $(".select_all_process").click(function () {  //"select all" change
                $(".checkbox_process").prop('checked', $(this).prop("checked")); //change all ".checkbox" checked status
                if ($(this).is(":checked")) {
                    $('.checkbox_process:checked').each(function (i, obj) {
                        process_list_array.push(this.value);
                    });
                } else {
                    process_list_array = [];
                }

            });
//".checkbox" change
            $('.checkbox_process').change(function () {
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (false == $(this).prop("checked")) { //if this item is unchecked
                    $("#select_all_process").prop('checked', false); //change "select all" checked status to false
                }
                //check "select all" if all checkbox items are checked
                if ($('.select_all_process:checked').length == $('.checkbox').length) {
                    $("#select_all_process").prop('checked', true);
                }
            });

        });

        function deleteItem(process_list_board_id) {
            toastr.error("<br /><br /><button type='button' style='color:black' id='confirmationRevertYes' class='btn clear'>Yes</button> <button style='margin-left: 120px;color: black' class='btn clear' type='button'>No</button>", 'Are you sure you want to delete?',
                {
                    closeButton: true,
                    allowHtml: true,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    positionClass: "toast-top-center",
                    onShown: function (toast) {
                        $("#confirmationRevertYes").click(function () {
                            var _token = $('input[name="_token"]').val();

                            $.ajax({
                                type: "get",
                                url: "<?php echo url(); ?>/board-meting/agenda/deleteItem",
                                data: {
                                    _token: _token,
                                    process_list_board_id: process_list_board_id
                                },
                                success: function (response) {
                                    if (response.responseCode == 1) {
                                        board_meting.ajax.reload();
                                        table_desk.ajax.reload();
                                    }
                                }
                            });
                        });
                    }
                });
        }

        $('body').on('click', '.individual_action_save', function () {

            var process_list_id = $(this).val();
            var find_remarks_class = 'remark_' + process_list_id;
            var remarks = $('.'+find_remarks_class).val();

            var find_status_class = 'status_for_' + process_list_id;
            var status = $('.' + find_status_class).val();
            if(status == 8){
                if (remarks == '') {
                    toastr.error('Please enter your remarks !!');
                    return false;
                }
            }
            if(status == 13){
                if (remarks == '') {
                    toastr.error('Please enter your remarks !!');
                    return false;
                }
            }
            if(status == 17){ //Conditional Approved
                if (remarks == '') {
                    toastr.error('Please enter your remarks !!');
                    return false;
                }
            }
            // if (remarks == '') {
            //     toastr.error('Please enter your remarks !!');
            //     return false;
            // }

            var agenda_id = '{{$agendaId}}';
            var _token = $('input[name="_token"]').val();
            btn = $(this);
            btn_content = btn.html();
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
            $.ajax({
                type: "POST",
                url: "<?php echo url(); ?>/board-meting/agenda/process/save-individual-action",
                data: {
                    _token: _token,
                    process_list_id: process_list_id,
                    agenda_id: agenda_id,
                    remarks: remarks,
                    bm_status_id: status,
                    board_meeting_id: '{{$board_meeting_id}}',
                    agenda_name: '{{$agendaName}}'
                },
                success: function (response) {
                    btn.html(btn_content);
                    if (response.responseCode == 1 && response.is_complete == 0) {
                        toastr.success('Your remarks updated Successfully!!');
                        board_meting.ajax.reload();
                    }
                    if (response.is_complete == 1) {
                        toastr.success('Your board meeting approved successfully!!');
                        window.setTimeout(function () {
                            window.location.href = "/board-meting/lists";
                        }, 300);
                    }
                    if (response.is_complete == 2) {
                        toastr.success('Someting want to wrong!!    !!');
                    }
                }
            });
        });

        $('.save_remarks').on('click', function () {

            $("#update_remarks").submit(function () {
                console.log(board_meeting_array);
                if (board_meeting_array.length === 0) {
                    toastr.error('<b>Please select a process from process list</b>');
                    return false
                }
                var status = $('#bm_status_id').val();
                var remarksAll = $('#remarksAll').val();
                if(status == 8){
                    if (remarksAll == '') {
                        toastr.error('Please enter your remarks !!');
                        return false;
                    }

                }
                if(status == 13){
                    if (remarksAll == '') {
                        toastr.error('Please enter your remarks !!');
                        return false;
                    }
                }
                if(status == 17){ //Conditional Approved
                    if (remarksAll == '') {
                        toastr.error('Please enter your remarks !!');
                        return false;
                    }
                }
            });


        });

        function viewAgendaRemarks($agendaId) {
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "post",
                url: "<?php echo url(); ?>/board-meting/agenda-remarks",
                data: {
                    _token: _token,
                    agendaId: $agendaId
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        var html = '';
                        if (response.data.length > 0) {
                            $.each(response.data, function (id, value) {
                                var UserType = '';
                                if (value.chairman == 1) {
                                    UserType = 'Chairman';
                                } else {
                                    UserType = 'Member';
                                }
                                html += '<div class="row">' +
                                    '<div class="col-md-3">' +
                                    '<center class="m10">' +
                                    '<img src="/users/upload/' + value.user_pic + '" class="img-circle img-responsive" alt="User Image"><br>' +
                                    '<h6 class="label label-danger">' + UserType + '</h6><br>' +
                                    '<h6 class="label label-success">' + value.user_full_name + '</h6>' +
                                    '</center></div>' +
                                    '<div class="col-md-9"><blockquote>' +
                                    '<span>' + value.remarks + '</span>' +
                                    '<footer>' + value.user_email + '</footer></blockquote>' +
                                    '</div>' +
                                    '</div>';
                            });
                        } else {
                            html += "<center>No Remarks Found</center>";
                        }

                        $('.modal-body').html(html);
                    }
                }
            });
        }

        function viewRemarks(bm_process_id) {

            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "post",
                url: "<?php echo url(); ?>/board-meting/agenda-process-remarks",
                data: {
                    _token: _token,
                    bm_process_id: bm_process_id
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        var html = '';
                        if (response.data.length > 0) {
                            $.each(response.data, function (id, value) {
                                var UserType = '';
                                if (value.chairman == 1) {
                                    UserType = 'Chairman';
                                } else {
                                    UserType = 'Member';
                                }
                                html += '<div class="row">' +
                                    '<div class="col-md-3">' +
                                    '<center class="m10">' +
                                    '<img src="/users/upload/' + value.user_pic + '" class="img-circle img-responsive" alt="User Image">' +
                                    '<h6 class="label label-danger">' + UserType + '</h6><br>' +
                                    '<h6 class="label label-success">' + value.user_full_name + '</h6>' +
                                    '</center></div>' +
                                    '<div class="col-md-9"><blockquote>' +
                                    '<span>' + value.remarks + '</span>' +
                                    '<footer>' + value.user_email + '</footer></blockquote>' +
                                    '</div>' +
                                    '</div>';
                            });
                        } else {
                            html += "<center>No Remarks Found</center>";
                        }

                        $('.modal-body').html(html);
                    }
                }
            });
        }


        $(function () {
            var table = [];
            $('.ProcessType').change(function () {
                $.get('{{route("process.setProcessType")}}',
                    {
                        _token: $('input[name="_token"]').val(),
                        data: $(this).val()
                    }, function (data) {
                        if (data == 'success') {
                            table_desk.ajax.reload();
                            // It seems unnecessary, need to check
                            var len = table.length;
                            for (var i = 0; i < len; i++) {
                                table[i].ajax.reload();
                            }
                        }
                    });
            });
            $('.ProcessType').trigger('change');

            $('.mydesk').click(function () {
                board_meting.ajax.reload();
                // board_meting.ajax.reload();
            });


            /**
             * table desk script
             * @type {jQuery}
             */

            board_meting = $('#board_meting').DataTable({
                iDisplayLength: 10,
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: '{{route("board-meting.agendaWiseBoardMeting",['-1000','boardMeting'])}}',
                    method: 'get',
                    data: function (d) {
                        d._token = $('input[name="_token"]').val();
                        d.agenda_id = '{{$agendaId}}',
                            d.board_meeting_id = '{{Encryption::encodeId($boardMeetingInfo->id)}}'
                            d.agenda_name = '{{$agendaName}}'
                    }
                },
                columns: [
                    {data: 'desk', name: 'desk', orderable: false, searchable: false},
                    {data: 'tracking_no', name: 'tracking_no', searchable: false},
                    {data: 'json_object', name: 'json_object'},
                    {data: 'status_name', name: 'status_name', searchable: false},
                    {data: 'updated_at', name: 'updated_at', searchable: false},
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                "aaSorting": []
            });


        });

    </script>
    <script>
        //   tinymce.init({ selector:'#description_editor' });
        tinymce.init({
            selector: '#description_editor',
            height: 150,
            theme: 'modern',
            plugins: [
                'autosave advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools'
            ],
            toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            toolbar2: 'print preview media | forecolor backcolor emoticons',
            image_advtab: true,
            content_css: [
                // '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                '//www.tinymce.com/css/codepen.min.css'
            ]
        });
    </script>
    @yield('footer-script2')
@endsection