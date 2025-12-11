@extends('layouts.admin')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datepicker-oss/css/bootstrap-datetimepicker.min.css') }}" />
    <style>
        .help_widget {
            height: auto !important;
            background: inherit;
            background-color: rgba(255, 255, 255, 1);
            border: none;
            border-radius: 10px;
            box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.117647058823529);
            position: relative;
            margin: 14px 10px 14px 14px;
        }

        .help_widget:hover {
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529);
        }

        .help_widget_header img {
            width: 90%;
            margin-top: 15px;
            border-radius: 10px;
            height: auto;
            padding-top: 0 !important;
        }

        .help_widget_content {
            padding: 0 15px;
        }

        .help_widget_content h3 {
            font-weight: 600;
        }

        .help_widget_content p {
            font-size: 16px;
        }
    </style>
@endsection

@section('content')
    @include('partials.messages')
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content" style="padding: 10px;">
                <div class="modal-header">
                    <h4 class="modal-title">Registration Apply</h4>
                </div>

                {!! Form::open([
                    'url' => 'training/enroll-participants/' . \App\Libraries\Encryption::encodeId($course->id),
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'applyForm',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                    'role' => 'form',
                ]) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="isRequired" id="isRequired" />
                <input type="hidden" name="selected_file" id="selected_file" />
                <input type="hidden" name="validateFieldName" id="validateFieldName" />
                <input type="hidden" name="session_id" id="session_id" />
                <div class="modal-body">
                    <div class="modalContent">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('course_name', 'Course Name', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('course_name', $course->course->course_title, [
                                            'class' => 'form-control input-md bnEng',
                                            'id' => 'course_name',
                                            'readonly' => 'readonly',
                                        ]) !!}
                                        {!! $errors->first('course_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('course_location', 'Course Location', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('course_location', $course->training_office, [
                                            'class' => 'form-control input-md bnEng',
                                            'id' => 'course_location',
                                            'readonly' => 'readonly',
                                        ]) !!}
                                        {!! $errors->first('course_location', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('course_fee', 'Course Fee', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('course_fee', 0.00, [
                                            'class' => 'form-control input_ban input-md bnEng',
                                            'id' => 'course_fee',
                                            'readonly' => 'readonly',
                                        ]) !!}
                                        {!! $errors->first('course_fee', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('service_fee', 'Service Fee', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('service_fee', 575, [
                                            'class' => 'form-control input_ban input-md bnEng',
                                            'id' => 'service_fee',
                                            'readonly' => 'readonly',
                                        ]) !!}
                                        {!! $errors->first('service_fee', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('name', 'Full Name', ['class' => 'col-md-2 required-star']) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('name', null, ['class' => 'form-control input-md required engOnly', 'id' => 'name']) !!}
                                        {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('email', 'Eamil', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::email('email', null, ['class' => 'form-control input-md required', 'id' => 'email']) !!}
                                        {!! $errors->first('email', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('dob', 'DOB', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        <div class="datepicker input-group date">
                                            {!! Form::text('dob', null, ['class' => 'form-control input-md engOnly', 'id' => 'dob']) !!}
                                            <span class="input-group-addon"> 
                                                <span class="fa fa-calendar"></span>
                                            </span>
                                        </div>
                                        {!! $errors->first('dob', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('father_name', "Father's Name", ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('father_name', null, [
                                            'class' => 'form-control input-md required engOnly',
                                            'id' => 'father_name',
                                        ]) !!}
                                        {!! $errors->first('father_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('mother_name', "Mother's Name", ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('mother_name', null, [
                                            'class' => 'form-control input-md required engOnly',
                                            'id' => 'mother_name',
                                        ]) !!}
                                        {!! $errors->first('mother_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('mobile_no', 'Mobile No', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::number('mobile_no', null, ['class' => 'form-control input-md required engOnly', 'id' => 'mobile_no']) !!}
                                        <small id="error-msg" style="display: none; color: red;">Invalid phone number.</small>
                                        {!! $errors->first('mobile_no', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('office_address', 'Office Address', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('office_address', null, [
                                            'class' => 'form-control input-md required engOnly',
                                            'id' => 'office_address',
                                        ]) !!}
                                        {!! $errors->first('office_address', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    {!! Form::label('profession', 'Profession', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('profession', null, ['class' => 'form-control required input-md', 'id' => 'profession']) !!}
                                        {!! $errors->first('profession', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('org_name', 'Organization Name', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('org_name', null, ['class' => 'form-control required input-md bnEng', 'id' => 'org_name']) !!}
                                        {!! $errors->first('org_name', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="jobHolderDiv">
                            <div class="row">
                                <div class="col-md-6" id="designationDiv">
                                    {!! Form::label('designation', 'Designation', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('designation', null, ['class' => 'form-control input-md required bnEng', 'id' => 'designation']) !!}
                                        {!! $errors->first('designation', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('attachment', 'Attachment', ['class' => 'col-md-4']) !!}
                                    <div class="col-md-8">
                                        <input type="file" name="attachment" id="attachment" class=""
                                            onchange="uploadDocument('preview_attachment', this.id, 'validate_field_attachment',0)">
                                        <div id="preview_attachment">
                                            <input type="hidden" value="" id="validate_field_attachment"
                                                name="validate_field_attachment">
                                        </div>
                                        <small class="text-danger">N.B.: Only jpg, jpeg, png, pdf, doc type supported (max 2MB)</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 col-md-4 col-md-offset-4">
                                    <label class="center-block image-upload" for="applicant_photo">
                                        <figure>
                                            <img src="{{ asset('users/upload/' . Auth::user()->user_pic) }}"
                                                class="profile-user-img img-responsive img-circle" alt="Profile Picture"
                                                id="applicant_photo_preview" width="200"
                                                onerror="this.src=`{{ asset('/assets/images/no-image.png') }}`">
                                        </figure>
                                        <input type="hidden" id="applicant_photo_base64" name="applicant_photo_base64">
                                        <input type="hidden" id="applicant_photo_hidden" name="applicant_photo"
                                            value="{{ Auth::user()->user_pic }}">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="" style="float: left;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="width: max-content">Close</button>
                    </div>
                    <div class="" style="float: right;">
                        <button type="submit" class="btn btn-primary capacityContent" style="width: max-content">Submit</button>
                    </div>
                    
                    
                </div>
                
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="content container-fluid" style="box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.167647058823529); margin: 10px" id="">
                <div class="col-lg-4 col-md-12">
                    <div class="help_widget">
                        <div class="help_widget_header text-center">
                            <img alt="..." src="{{ asset('uploads/training/course/' . $course->course_thumbnail_path) }}"
                                onerror="this.src=`{{ asset('/assets/images/no-image.png') }}`">
                        </div>
                        <div class="help_widget_content text-left">
                            <h3 title="SM-202312000001">{{ $course->course->course_title }}</h3>
    
                            <div class="row" style="padding: 5px 15px">
    
                                <button class="btn btn-warning btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                        class="fa fa-calendar"></i></button>
                                <span>Class Start:</span><span class="input_ban"> {{ date('d F', strtotime($course->course_duration_start)) }} </span>
                            </div>
                            <div class="row" style="padding: 5px 15px">
                                <button class="btn btn-danger btn-xs" style="border-radius: 50%; font-size: 8px"><i
                                        class="fa fa-calendar"></i></button>
                                <?php
                                $enroll_deadline = strtotime($course->enroll_deadline);
                                $current_date = strtotime(date('Y-m-d'));
                                ?>
                                @if($enroll_deadline >= $current_date)
                                <span>Registration End:</span> <span class="input_ban">{{ date('d F', strtotime($course->enroll_deadline)) }}</span>
                                @else
                                    <span class="text-danger">Registration Closed</span>
                                @endif
                            </div>
                            <div class="row" style="padding: 5px 15px">
                                <span>Course Venue: </span> <span style="font-size: 16px">{{ mb_substr($course->venue, 0, 30, 'UTF-8') }}</span>
                            </div>
                            <br>
                            <div class="text-left" style="font-size: 18px">
                                {{-- <b>
                                    <span>Course Fee :</span>
                                    <span class="input_ban" style="color: #00a157;">
                                        {{ $course->fees_type == 'paid' ? $course->amount . ' Taka' : 'FREE' }}</span>
                                </b> --}}
                                {{-- <br>
                                <b>
                                    <span>সার্ভিস ফী :</span>
                                    <span class="input_ban" style="color: #00a157;"> 100</span> <span
                                        style="color: #00a157;">টাকা</span>
                                </b> --}}
                            </div>
                            @if ($participantinfo)
                            <br>
                            <div class="row text-center">
                                @if (($is_evaluated > 0 || $course->course_evaluation == 'no') && empty($participantinfo->certificate) && $course->status == 'completed')
                                    <a class="btn btn-info btn-lg btn-block"
                                        style="font-size: 18px; margin-bottom: 5px; border-radius: 25px">
                                        Course Completed
                                    </a>
                                    <a href="{{ url('/training/get-certificate/' . \App\Libraries\Encryption::encodeId($participant) . '/' . \App\Libraries\Encryption::encodeId($course->id)) }}" class="btn btn-success btn-lg btn-block"
                                        style="font-size: 18px; margin-bottom: 10px; border-radius: 25px">
                                        Download Certificate
                                    </a>
                                @elseif (($is_evaluated > 0 || $course->course_evaluation == 'no') && !empty($participantinfo->certificate) && $course->status == 'completed')
                                    <a class="btn btn-info btn-lg btn-block"
                                        style="font-size: 18px; margin-bottom: 10px; border-radius: 25px">
                                        Course Completed
                                    </a>
                                    <a href="{{ url($participantinfo->certificate) }}" class="btn btn-success btn-lg btn-block"
                                        style="font-size: 18px; margin-bottom: 10px; border-radius: 25px">
                                        View Certificate
                                    </a>
                                @else
                                    <a class="btn btn-info btn-lg btn-block"
                                        style="font-size: 18px; margin-bottom: 5px; border-radius: 25px">
                                        {{ $participantinfo->is_paid == 1 ? 'Applied' : 'Not Paid' }}
                                    </a>
                                    <small class="text-center" style="margin: auto; text-align:right; padding: 10px 0px; display: inline-block">
                                        @if($participantinfo->status == "Confirmed")
                                            "Enrollment Confirmed"
                                        @elseif($participantinfo->status == "Declined")
                                            "Enrollment Declined"
                                        @else
                                            "Waiting for approval"
                                        @endif
                                        
                                    </small>
                                @endif
    
                            </div>
                            @endif
                        </div>
    
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="help_widget" style="padding: 10px 10px 10px 25px; text-align: left">
                        <h3 style="color: #00a65a">Necessary Qualification
                        </h3>
                        <p>{!! $course->necessary_qualification_experience !!}</p>
                        <br>
                        <h3 style="color: #00a65a">Course Goal
                        </h3>
                        <p>{!! $course->objectives !!}</p>
                        <br>
                        <h3 style="color: #00a65a">Course Description</h3>
                        <p>{!! $course->course->course_description !!}</p>
                        <br>
                        <h3 style="color: #00a65a">Course Outline</h3>
                        <p>{!! $course->course_contents !!}</p>
                    </div>
                </div>
    
                @if (empty($participantinfo))
                    <div class="col-md-12" id="trainingtimeline">
                        <div class="help_widget" style="padding: 5px 15px">
                            <h3 style="color: #00a157;">Training schedule</h3>
    
                            <div class="table-responsive">
                                <table aria-label="Detailed Training schedule" class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Day</th>
                                            <th class="text-center">Time</th>
                                            <th class="text-center">Place</th>
                                            <th class="text-center">Seat</th>
                                            @if($course->enroll_deadline >= \Carbon\Carbon::now()->subDay() && !checkUserTrainingDesk() && Auth::user()->user_type != '1x101')
                                                <th class="text-center">Action</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                        @foreach ($scheduleSession as $row)
                                            <tr class="text-center">
                                                <td>{{ $row->session_days }}</td>
                                                <td>
                                                    <span>{{ date('h:i a', strtotime($row->session_start_time)) }}
                                                        -
                                                        {{ date('h:i a', strtotime($row->session_end_time)) }}</span>
                                                </td>
                                                <td>
                                                    {{ $course->venue }}
                                                </td>
                                                <td>
                                                    {{ $row->seat_capacity == 0 ? 'Undefined' : $row->seat_capacity }}
                                                </td>
                                                <td>
                                                    @if($course->enroll_deadline >= date('Y-m-d') && $course->status == 'upcoming' && !checkUserTrainingDesk() && Auth::user()->user_type != '1x101' )
                                                        @if(checkSeatAbility($course->id, $row->id))
                                                            <button type="button" class="btn btn-primary apply_course"
                                                                data-toggle="modal" data-target="#myModal" id="{{ \App\Libraries\Encryption::encodeId($row->id) }}" onclick="updateSessionID(this.id)">
                                                                Apply
                                                            </button>
                                                        @else
                                                            <p class="text-danger">Seat Full</p>
                                                        @endif
                                                    @endif
                                                    <!-- Button trigger modal -->
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
@endsection <!--content section-->

@section('footer-script')
    <script src="{{ asset('assets/scripts/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datepicker-oss/js/bootstrap-datetimepicker.min.js') }}"></script>  
    <script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datetimepicker({
                format: 'YYYY-MM-DD',
            });
            $("#applyForm").validate({
                errorPlacement: function() {
                    return false;
                }
            })

            $(document).ready(function() {
            var input = $("#mobile_no");
            var errorMsg = $("#error-msg");

            // Initialize the intlTelInput plugin
            var iti = input.intlTelInput({
                hiddenInput: "mobile_no",
                initialCountry: "BD",
                placeholderNumberType: "MOBILE",
                separateDialCode: true
            });

            input.on('blur', function() {
                if (input.intlTelInput("isValidNumber")) {
                    errorMsg.hide();
                    input.removeClass('error');
                } else {
                    errorMsg.show();
                    input.addClass('error');
                }
            });

            // Optionally, validate on keyup or change
            input.on('keyup change', function() {
                if (input.intlTelInput("isValidNumber")) {
                    errorMsg.hide();
                    input.removeClass('error');
                } else {
                    errorMsg.show();
                    input.addClass('error');
                }
            });

        });

            // $(".apply_course").click(function() {
            //     var session_id = $(this).attr('data-id');
            //     $("#session_id").val(session_id);

            //     $.ajax({
            //         method: 'GET',
            //         url: "/training/check-seat-capacity",
            //         data: {
            //             session_id: session_id
            //         },
            //         success: function(response) {
            //             if (response.responseCode == false) {
            //                 toastr.error('দুঃখিত। সেশনটিতে আর কোন আসন খালি নেই!');
            //             } else {
            //                 $('#applyModal').modal('show');
            //             }
            //         }
            //     });
            // });
        })

        function uploadDocument(targets, id, vField, isRequired) {

            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' +
                    vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{ url('/training/upload-document') }}";
            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response) {

                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id +
                        '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    //                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });

        }

        function updateSessionID(id) {
            document.getElementById('session_id').value = id;
        }
    </script>
@endsection <!--- footer-script--->
