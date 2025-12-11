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

        @if (ACL::getAccsessRight('Training-Desk', '-V-'))
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="pull-left">
                        <b> <i class="fa fa-list"></i> Participant's Info </b>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body" style="padding: 25px 0">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12 text-center">
                                            <img src="{{ asset('users/upload/'.$participant->image_path) }}"
                                            onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`"
                                                alt="..." style="width: 35%; border-radius: 50%; border: 1px solid grey">
                                        </div>
                                        <div class="col-md-12 col-xs-12  text-center">
                                            <label style="margin-top: 10px; color: black; font-weight: 600;">{{ $participant->full_name }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Cartificate's Name: </label>
                                        </div>
                                        <div class="col-md-7">
                                            <span><input name="participant_certificate_name" id="participant_certificate_name"
                                                    value="{{ $participant->full_name }}"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-5">
                                        </div>
                                        <div class="col-md-7">
                                            <a class="btn btn-success btn-sm  text-centert participantInfoUpdate"
                                                data-id="{{ Encryption::encodeId($participant->id) }}"
                                                href="javascript:void(0)" onclick="changeName(this)">Change Name</a>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- col-md-6 text-center -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">Mobile No</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>{{ $participant->moblie_no }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">Date of Birth</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>{{ $participant->dob }}</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">লিঙ্গ</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>Male</span>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">Eamil</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>{{ $participant->email }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">Profession</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>{{ $participant->profession }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-5">
                                            <label for="">Status</label>
                                        </div>
                                        <div class="col-md-6">
                                            : <span>{{ ucfirst($participant->status) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <br>

                                <div class="form-group">
                                    <div class="row">
                                        @if ($is_completed == 1 && $course->status == 'completed')
                                            <div class="col-md-6">
                                                <a class="btn btn-info btn-sm"
                                                    href="{{ url('/training/get-certificate/' . \App\Libraries\Encryption::encodeId($participant->id) . '/' . \App\Libraries\Encryption::encodeId($course->id)) }}">
                                                    Download Certificate</a>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-warning btn-sm"
                                                    href="{{ url('/training/regenerate-certificate/' . \App\Libraries\Encryption::encodeId($participant->id) . '/' . \App\Libraries\Encryption::encodeId($course->id)) }}">
                                                    Regenerate Certificate</a>
                                            </div>
                                        @endif
                                        {{-- <div class="col-md-6">
                                            <a class="btn btn-info btn-sm"
                                                href="{{ url('/training/view-schedule-details/' . \App\Libraries\Encryption::encodeId($course->id)) }}"><i
                                                    class="fa fa-arrow-left"></i> Back</a>
                                        </div> --}}
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>
                </div><!-- /.box -->
            </div>
        @endif
        </div>

    </section>

@endsection

@section('footer-script')
    <script src="{{ asset('assets/scripts/jquery.steps.js') }}"></script>
    <script src="{{ asset('assets/scripts/apicall.js?v=1') }}" type="text/javascript"></script>
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#select2_day").select2();
            $("#speaker_id").select2();
        });

        function changeName(element) {
            var id = element.getAttribute('data-id');
            console.log(id);
            var name = $('#participant_certificate_name').val();
            console.log(name);
            $.ajax({
                type: "POST",
                url: "<?php echo url('training/participants-data/update'); ?>",
                data: {
                    participantsId: id,
                    newName: name,
                    status: null
                },
                success: function(response) {
                    if (response.responseCode == 1) {
                        toastr.success(response.responseMessage);
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.responseMessage);
                    }
                }
            });
        }
    </script>
@endsection
