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
    </style>

    <div class="row" style="padding: 10px">
        <div class="col-lg-12">

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4>Training Course</h4>
                </div>

                {!! Form::open([
                    'url' => '/training/store-course',
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'currency-form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                    'role' => 'form',
                ]) !!}

                <div class="panel-body" style="padding: 40px">

                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('course_title', 'Course Name (English)', ['class' => 'col-md-2 required-star']) !!}
                                        <div class="col-md-10 {{ $errors->has('course_title') ? 'has-error' : '' }}">
                                            {!! Form::text('course_title', null, ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('course_title', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('course_title_bn', 'Course Name (Bangla)', ['class' => 'col-md-2 required-star']) !!}
                                        <div class="col-md-10 {{ $errors->has('course_title_bn') ? 'has-error' : '' }}">
                                            {!! Form::text('course_title_bn', null, ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('course_title_bn', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('category_id', 'Course Category', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                            {!! Form::select('category_id', $trCategory, null, [
                                                'class' => 'form-control required imput-md',
                                                'id' => 'category_id',
                                            ]) !!}
                                            {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                          {!! Form::label('course_slug', 'Course Slug', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('course_slug') ? 'has-error' : '' }}">
                                            {!! Form::text('course_slug', null, ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('course_slug', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('course_description', 'Course Description', ['class' => 'col-md-2 required-star']) !!}
                                        <div class="col-md-10 {{ $errors->has('course_description') ? 'has-error' : '' }}">
                                            {!! Form::textarea('course_description', null, ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('course_description', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('course_image', 'Course image: ', ['class' => 'col-md-4 required-star']) !!}
                                        <div class="col-md-8 {{ $errors->has('course_image') ? 'has-error' : '' }}">
                                            {!! Form::file('course_image', ['class' => 'form-control input-md required','accept' => 'image/jpeg, image/png, /image/jpg']) !!}
                                            {!! $errors->first('course_image', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('course_image2', 'Course image 2: ', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('course_image2') ? 'has-error' : '' }}">
                                            {!! Form::file('course_image2',  ['class' => 'form-control input-md', 'accept' => 'image/jpeg, image/png, /image/jpg']) !!}
                                            {!! $errors->first('course_image2', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('course_image3', 'Course image 3: ', ['class' => 'col-md-4 ']) !!}
                                        <div class="col-md-8 {{ $errors->has('course_image3') ? 'has-error' : '' }}">
                                            {!! Form::file('course_image3', ['class' => 'form-control input-md ', 'accept' => 'image/jpeg, image/png, /image/jpg']) !!}
                                            {!! $errors->first('course_image3', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <div class="col-md-12">
                                        {!! Form::label('is_active', 'Active Status: ', ['class' => 'col-md-2 required-star']) !!}
                                        <div class="col-md-10 {{ $errors->has('is_active') ? 'has-error' : '' }}">
                                            <label>{!! Form::radio('is_active', '1', ['class' => 'required', 'id' => 'yes']) !!} Active</label>
                                            <label>{!! Form::radio('is_active', '0', ['class' => ' required', 'id' => 'no']) !!} Inactive</label>
                                            {!! $errors->first('is_active', '<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                
                            </div>


                            <div class="panel-footer">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <a href="{{ url('/training/course-list') }}">
                                                {!! Form::button('<i class="fa fa-times"></i> Close', ['type' => 'button', 'class' => 'btn btn-default']) !!}
                                            </a>
                                        </div>

                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-primary pull-right">
                                                <i class="fa fa-chevron-circle-right"></i> Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div><!--/.col-lg-12-->
        </div>

    @endsection

    @section('footer-script')
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
        <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                $("#select2_day").select2();
                $("#speaker_id").select2();
            });
        </script>
    @endsection
