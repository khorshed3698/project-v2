<?php
if (!ACL::getAccsessRight('Training', '-V-')) {
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
    </style>

    <div class="col-lg-12">

        <div class="panel panel-primary">
            <div class="panel-heading" style="padding: 13px 10px;">
                <b>Edit Training Course</b>
            </div>

            <div class="panel-body">
                {!! Form::open([
                    'url' => '/training/store-course',
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'currency-form',
                    'enctype' => 'multipart/form-data',
                    'files' => 'true',
                    'role' => 'form',
                ]) !!}

                <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($tr_data->id) }}" />

                <div class="col-md-12">
                    <div class="form-group">
                        <div class="form-group col-md-12">
                            {!! Form::label('course_title', 'Course Name (English)', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('course_title') ? 'has-error' : '' }}">
                                {!! Form::text('course_title', $tr_data->course_title, ['class' => 'form-control input-md required']) !!}
                                {!! $errors->first('course_title', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            {!! Form::label('course_title_bn', 'Course Name (Bangla)', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('course_title_bn') ? 'has-error' : '' }}">
                                {!! Form::text('course_title_bn', $tr_data->course_title_bn, ['class' => 'form-control input-md required']) !!}
                                {!! $errors->first('course_title_bn', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('category_id', 'Course Category', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('category_id') ? 'has-error' : '' }}">
                                {!! Form::select('category_id', $trCategory, $tr_data->category_id, [
                                    'class' => 'form-control required imput-md',
                                    'id' => 'category_id',
                                ]) !!}
                                {!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="col-md-12 form-group">
                            {!! Form::label('course_slug', 'Course Slug', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('course_slug') ? 'has-error' : '' }}">
                                {!! Form::text('course_slug', $tr_data->course_slug, ['class' => 'form-control input-md required']) !!}
                                {!! $errors->first('course_slug', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('course_description', 'Course Description', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('course_description') ? 'has-error' : '' }}">
                                {!! Form::textarea('course_description', $tr_data->course_description, [
                                    'class' => 'form-control input-md required','id'=>'course_description',
                                ]) !!}
                                {!! $errors->first('course_description', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            {!! Form::label('course_image', 'Course image: ', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{ $errors->has('course_image') ? 'has-error' : '' }}">
                                {!! Form::file('course_image', [
                                    'class' => 'form-control input-md required',
                                    'id' => 'course_image',
                                    'accept' => 'image/jpeg, image/png, /image/jpg', 'onclick' => 'setupImagePreview("course_image", "course_thumbnail_preview")'
                                ]) !!}
                                <small class="text-danger">N.B.: Only jpg, jpeg, png type image supported and image size must be less then 2MB</small>
                                {!! $errors->first('course_image', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('/uploads/training/course/'.$tr_data->course_image) }}" alt="photo_default.png"
                                    class="img-responsive img-thumbnail course_image_thumbnail" id="course_thumbnail_preview"
                                    onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            {!! Form::label('course_image2', 'Course image 2: ', ['class' => 'col-md-3 ']) !!}
                            <div class="col-md-5 {{ $errors->has('course_image2') ? 'has-error' : '' }}">
                                {!! Form::file('course_image2', [
                                    'class' => 'form-control input-md',
                                    'id' => 'course_image2',
                                    'accept' => 'image/jpeg, image/png, /image/jpg', 'onclick' => 'setupImagePreview("course_image2", "course_thumbnail_preview2")',
                                ]) !!}
                                <small class="text-danger">N.B.: Only jpg, jpeg, png type image supported and image size must be less then 2MB</small>
                                {!! $errors->first('course_image2', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('/uploads/training/course/'.$tr_data->course_image2) }}" alt="photo_default.png"
                                    class="img-responsive img-thumbnail course_image_thumbnail" id="course_thumbnail_preview2"
                                    onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                            </div>
                        </div>
        
                        <div class="form-group col-md-12">
                            {!! Form::label('course_image3', 'Course image 3: ', ['class' => 'col-md-3 ']) !!}
                            <div class="col-md-5 {{ $errors->has('course_image3') ? 'has-error' : '' }}">
                                {!! Form::file('course_image3', [
                                    'class' => 'form-control input-md ',
                                    'id' => 'course_image3',
                                    'accept' => 'image/jpeg, image/png, /image/jpg','onclick' => 'setupImagePreview("course_image3", "course_thumbnail_preview3")',
                                ]) !!}
                                <small class="text-danger">N.B.: Only jpg, jpeg, png type image supported and image size must be less then 2MB</small>
                                {!! $errors->first('course_image3', '<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('/uploads/training/course/'.$tr_data->course_image3) }}" alt="photo_default.png"
                                    class="img-responsive img-thumbnail course_image_thumbnail" id="course_thumbnail_preview3"
                                    onerror="this.src=`{{ asset('/assets/images/photo_default.png') }}`">
                            </div>
                        </div>

                        <div class="col-md-12 form-group">
                            {!! Form::label('is_active', 'Active Status: ', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9 {{ $errors->has('is_active') ? 'has-error' : '' }}">
                                <label>{!! Form::radio('is_active', '1', $tr_data->is_active == 1 ? 'checked' : '', [
                                    'class' => 'required',
                                    'id' => 'yes',
                                ]) !!} Active</label>
                                <label>{!! Form::radio('is_active', '0', $tr_data->is_active == 0 ? 'checked' : '', [
                                    'class' => ' required',
                                    'id' => 'no',
                                ]) !!} Inactive</label>
                                {!! $errors->first('is_active', '<span class="help-block">:message</span>') !!}
                            </div>
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
                <div class="clearfix"></div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection

@section('footer-script')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2.min.css') }}">
    <script src="{{ asset('assets/plugins/select2.min.js') }}"></script>
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $("#select2_day").select2();
            $("#speaker_id").select2();
        });
        tinymce.init({
            selector: '#course_description'
        });
        function setupImagePreview(inputId, previewId) {
            const inputElement = document.getElementById(inputId);
            const previewElement = document.getElementById(previewId);

            inputElement.addEventListener('change', function() {
                const file = this.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        previewElement.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Please select a valid image file.');
                    inputElement.value = ''; // Clear the input
                }
            });
        }
    </script>
@endsection
