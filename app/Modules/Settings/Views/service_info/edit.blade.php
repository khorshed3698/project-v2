@extends('layouts.admin')
@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('user');
    if (!ACL::isAllowed($accessMode, 'V'))
        die('no access right!');
    ?>
    <div class="col-lg-12">
        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>{!! trans('messages.edit_service') !!} {!! $data->name !!} </strong></h5>
            </div>

        {!! Form::open(array('url' => 'settings/update-service-info-details/'.$encrypted_id, 'method' => 'patch','id'=>'reg_form1',  'enctype' => 'multipart/form-data')) !!}
        <!-- /.panel-heading -->
            <div class="panel-body">
                <br>
                    <div class="form-group col-md-12{{$errors->has('process_type_id') ? 'has-error' : ''}} ">
                        {!! Form::label('process_type_id', 'Process Type:', ['class' => 'col-md-2 required-star']) !!}
                        <div class="col-md-9">
                            {!! Form::select('process_type_id', $services, $data->process_type_id, ['class' => 'form-control required ','disabled']) !!}
                            {!! $errors->first('process_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name','Description:',['class'=>'col-md-2 control-label']) !!}
                        <div class="col-md-9 maxTextCountDown">
                            {!! Form::textarea('description', $data->description, ['placeholder'=>'Write Description Here', 'class' => 'form-control input-md',
                                'id'=>'description_editor','maxlength'=>'250']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-12 {{$errors->has('terms_and_conditions_bn') ? 'has-error' : ''}}">
                        {!! Form::label('Terms and Condition','Terms and Condition : ',['class'=>'col-md-2 required-star']) !!}
                        <div class="col-md-9">
                            <input type="file" name="terms_and_conditions" id="terms_and_conditions" class="form-control @if(empty($data->terms_and_conditions)) required @endif"/>
                            {!! $errors->first('terms_and_conditions_bn','<span class="help-block">:message</span>') !!}
                            <div style="position:relative;">
                                <a href="{{ (!empty($data->terms_and_conditions) ? url($data->terms_and_conditions) : '') }}" target="_blank" rel="noopener"><i class="fa fa-file-pdf-o"></i> Show attached file</a>
                                <input type="hidden" name="exist_pdf" value="{{$data->terms_and_conditions}}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('is_active','Active Status: ',['class'=>'col-md-2 required-star']) !!}
                        <div class="col-md-9 {{$errors->has('is_active') ? 'has-error' : ''}}">
                            <label>{!! Form::radio('is_active', '1', $data->status  == '1', ['class'=>'required', 'id' => 'yes']) !!}
                                Active</label>
                            <label>{!! Form::radio('is_active', '0', $data->status  == '0', ['class'=>' required', 'id' => 'no']) !!}
                                Inactive</label>
                            {!! $errors->first('is_active','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
            </div>

            <div class="panel-footer">
                <div class="pull-left">
                    {!! App\Libraries\CommonFunction::showAuditLog($data->updated_at, $data->updated_by) !!}
                </div>
                <div class="pull-right">
                    <a href="{{ url('/settings/service-info') }}" class="btn btn-default"><i class="fa fa-close"></i> Close</a>
                    @if(ACL::getAccsessRight('user','E'))
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i>
                            Save
                        </button>
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>

    <link rel="stylesheet" href="{{ asset("assets/stylesheets/bootstrap3-wysihtml5.min.css") }}">
    <script src="{{ asset("assets/scripts/bootstrap3-wysihtml5.all.min.js") }}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            $(function () {
                var _token = $('input[name="_token"]').val();
                $("#reg_form").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
                var _token = $('input[name="_token"]').val();
                $("#reg_form1").validate({
                    errorPlacement: function () {
                        return false;
                    }
                });
            });
            //Select2
        });

    </script>
    <script>

        $(document).ready(function () {
            $("#division").change(function () {
                var divisionId = $('#division').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-district-by-division",
                    data: {
                        divisionId: divisionId
                    },
                    success: function (response) {
                        var option = '<option value="">Select district</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#district").html(option);
                        $(self).next().hide();
                    }
                });
            });

            $("#district").change(function () {
                var districtId = $('#district').val();
                $(this).after('<span class="loading_data">Loading...</span>');
                var self = $(this);
                $.ajax({
                    type: "GET",
                    url: "<?php echo url(); ?>/users/get-thana-by-district-id",
                    data: {
                        districtId: districtId
                    },
                    success: function (response) {
                        var option = '<option value="">Select Thana</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                option += '<option value="' + id + '">' + value + '</option>';
                            });
                        }
                        $("#thana").html(option);
                        $(self).next().hide();
                    }
                });
            });
        });


    </script>

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#faq-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        $(function () {
            $(".wysihtml5-editor").wysihtml5();
        });
    </script>
    <style>
        ul, ol {
            list-style-type: none;
        }
    </style>

    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
    <script>
        //textarea count down
        $('.maxTextCountDown').maxlength();

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
@endsection