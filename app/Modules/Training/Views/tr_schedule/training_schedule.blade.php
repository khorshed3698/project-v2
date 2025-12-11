<?php
if (!ACL::getAccsessRight('Training-Desk', '-V-') && !ACL::getAccsessRight('Training-Desk', '-DV-')) {
    die('You have no access right! Please contact system administration for more information');
}
?>
@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
    <style>
        /*.bootstrap-datetimepicker-widget{*/
        /*    position: relative !important;*/
        /*    top:0 !important;*/
        /*}*/
        .pe-none {
            pointer-events: none;
        }

        .course_image_thumbnail {
            height: 150px;
            width: 150px;
        }

        ul.image_checkbox_design {
            list-style-type: none;
        }

        ul.image_checkbox_design li {
            display: inline-block;
        }

        ul.image_checkbox_design li input[type="checkbox"][id^="course_thumbnail_base64"] {
            display: none;
        }

        ul.image_checkbox_design li label {
            border: 1px solid #fff;
            padding: 10px;
            display: block;
            position: relative;
            margin: 10px;
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        ul.image_checkbox_design li label::before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        ul.image_checkbox_design li label img {
            height: 100px;
            width: 100px;
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
        }

        ul.image_checkbox_design li :checked+label {
            border-color: #ddd;
        }

        ul.image_checkbox_design li :checked+label::before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        ul.image_checkbox_design li :checked+label img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }
        label{
            color: #4e7aa2;
            font-style: normal;
            font-weight: normal;
        }

        .downloadSection ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .downloadSection ul li {
            float: left;
        }
    </style>
    @include('partials.messages')
    <section class="content container-fluid">
        @include('Training::partials.schedule-details')
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="pull-left" style="padding-top: 7px">
                    <b> <i class="fa fa-list"></i> Participants List
                    </b>
                </div>
                <div class="pull-right downloadSection">
                    <ul>
                        <li style="margin-right: 5px;">
                            <select class="form-control input-md" id="participant_status" name="participant_status">
                                <option selected="selected" value="">Select Status</option>
                                <option value="draft">Draft</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Declined">Declined</option>
                            </select>
                        </li>
                        <li>
                            <a href="{{ url('training/schedule/download-participants/' . \App\Libraries\Encryption::encodeId($course->id)) }}"
                                id="download_participant" class="btn btn-default"><i
                                    class="fa fa-download"></i>Participents
                                List Download
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                <div class="col-lg-12">

                    <div class="table-responsive">
                        <div id="list_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="list"
                                        class="table table-striped table-bordered dt-responsive nowrap  no-footer dtr-inline"
                                        cellspacing="0" width="100%" role="grid" aria-describedby="list_info"
                                        style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Mobile Number
                                                </th>
                                                <th>Email</th>
                                                <th>
                                                    Payment Status</th>
                                                <th>Status
                                                </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.table-responsive -->

                </div>
            </div><!-- /.box -->
        </div>
        </div>
    </section>

@endsection

@section('footer-script')
    @include('partials.datatable-scripts')
    <script src="{{ asset('assets/scripts/apicall.js?v=1') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#select2_day").select2();
            $("#speaker_id").select2();
        });
    </script>
    <script>
        table = $('#list').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            responsive: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                url: "{{ url('/training/schedule/get-user-list') }}",
                method: 'get',
                data: {
                    session_id: '{{ \App\Libraries\Encryption::encodeId($course->id) }}'
                },
            },
            columns: [{
                    data: 'sl',
                    name: 'sl'
                },
                {
                    data: 'user_first_name',
                    name: 'user_first_name'
                },
                {
                    data: 'user_mobile',
                    name: 'user_mobile'
                },
                {
                    data: 'user_email',
                    name: 'user_email'
                },
                {
                    data: 'payment',
                    name: 'payment',
                    searchable: true
                },
                {
                    data: 'status',
                    name: 'status',
                    searchable: true
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            "aaSorting": []
        });


        $(document).on('change', '#participant_status', function() {
            var participant_status = $('#participant_status').val();
            console.log(participant_status);

            $('#list').DataTable().clear();
            $('#list').DataTable().destroy();

            //table.destroy();
            // $('#download_participant').attr('href', "/training/schedule/download-participants/{{ \App\Libraries\Encryption::encodeId($course->id) }}/" + participant_status);
            table = $('#list').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    url: '{{ url('/training/schedule/get-status-wise-user-list') }}',
                    method: 'get',
                    data: {
                        session_id: '{{ \App\Libraries\Encryption::encodeId($course->id) }}',
                        participant_status: participant_status
                    },
                },
                columns: [{
                        data: 'sl',
                        name: 'sl'
                    },
                    {
                        data: 'user_first_name',
                        name: 'user_first_name'
                    },
                    {
                        data: 'user_mobile',
                        name: 'user_mobile'
                    },
                    {
                        data: 'user_email',
                        name: 'user_email'
                    },
                    {
                        data: 'payment',
                        name: 'payment',
                        searchable: true
                    },
                    {
                        data: 'status',
                        name: 'status',
                        searchable: true
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "aaSorting": []
            });
        })

        $(document).on('click', '.actionActivates', function() {
            var participant_id = $(this).attr('data-id');
            var data_action = $(this).attr('data-action');
            if (data_action == 'Decline' || data_action == 'Confirm') {
                var check = confirm("Are you sure?");
                if (!check) {
                    return false;
                }
            }
            let btn = $(this);
            let app = $(this);
            let btn_content = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

            $.ajax({
                type: "get",
                url: "/training/schedule/participant-activates",
                data: {
                    participant_id: participant_id,
                    data_action: data_action
                },
                success: function(response) {
                    if (response.responseCode == 1) {
                        btn.prop('disabled', false);
                        btn.html(btn_content);
                        toastr.success('Your status updated successfully!!');
                        table.ajax.reload();
                    }
                },
                error: function(jqHR, textStatus, errorThrown) {
                    toastr.error(errorThrown);
                    btn.prop('disabled', false);
                    btn.html(btn_content);
                }
            });
        });

        $(document).on('click', '.actionActivatesAll', function() {
            var session_id = $(this).attr('data-id');
            var data_action = $(this).attr('data-action');
            if (data_action == 'Decline' || data_action == 'Confirm') {
                var check = confirm("Are you sure?");
                if (!check) {
                    return false;
                }
            }
            let btn = $(this);
            let app = $(this);
            let btn_content = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

            $.ajax({
                type: "get",
                url: "/training/schedule/participant-activates-all",
                data: {
                    session_id: session_id,
                    data_action: data_action
                },
                success: function(response) {
                    if (response.responseCode == 1) {
                        btn.prop('disabled', false);
                        btn.html(btn_content);
                        toastr.success('Your status updated successfully!!');
                        table.ajax.reload();
                    }
                },
                error: function(jqHR, textStatus, errorThrown) {
                    toastr.error(errorThrown);
                    btn.prop('disabled', false);
                    btn.html(btn_content);
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.participantInfoUpdate', function() {
            var participant_id = $(this).attr('data-id');
            var participant_certificate_name = $('#participant_certificate_name').val();
            if (participant_certificate_name == '') {
                toastr.error('Certificate name should not be empty !');
                return false;
            }
            let btn = $(this);
            let btn_content = btn.html();
            btn.prop('disabled', true);
            btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
            if (confirm("Are you want to sure for Update Participant Information ?")) {
                $.ajax({
                    type: "post",
                    url: "/training/schedule/participant-info-update",
                    data: {
                        participant_id: participant_id,
                        participant_certificate_name: participant_certificate_name,
                        csrfmiddlewaretoken: '{{ csrf_field() }}'
                    },
                    success: function(response) {
                        if (response.responseCode == 1) {
                            btn.prop('disabled', false);
                            btn.html(btn_content);
                            toastr.success('Information updated successfully!!');
                            setTimeout(() => window.location.reload(), 1500);
                        }
                    },
                    error: function(jqHR, textStatus, errorThrown) {
                        toastr.error(errorThrown);
                        btn.prop('disabled', false);
                        btn.html(btn_content);
                    }
                });
            } else {
                return false;
            }
        }); // Participant Info Update
    </script>
@endsection
