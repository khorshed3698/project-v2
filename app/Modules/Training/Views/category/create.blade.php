<?php
if (!ACL::getAccsessRight('Training', '-V-')) {
    die('You have no access right! Please contact system administration for more information');
}
?>


@extends('layouts.admin')

@section('page_heading', trans('messages.rollback'))

@section('content')
    <style>
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
                <b>Create Training Category</b>
            </div>
            <div class="panel-body">
                {!! Form::open([
                    'url' => '/training/store-category',
                    'method' => 'post',
                    'class' => 'form-horizontal smart-form',
                    'id' => 'currency-form',
                ]) !!}
                <div class="form-group col-md-12">
                    {!! Form::label('category_name', 'Category Name (English)', ['class' => 'col-md-4 required-star']) !!}
                    <div class="col-md-8 {{ $errors->has('category_name') ? 'has-error' : '' }}">
                        {!! Form::text('category_name', null, ['class' => 'form-control input-md required']) !!}
                        {!! $errors->first('category_name', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('category_name_bn', 'Category Name (Bangla)', ['class' => 'col-md-4 required-star']) !!}
                    <div class="col-md-8 {{ $errors->has('category_name_bn') ? 'has-error' : '' }}">
                        {!! Form::text('category_name_bn', null, ['class' => 'form-control input-md required']) !!}
                        {!! $errors->first('category_name_bn', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="form-group col-md-12">
                    {!! Form::label('is_active', 'Active Status: ', ['class' => 'col-md-4 required-star']) !!}
                    <div class="col-md-8 {{ $errors->has('is_active') ? 'has-error' : '' }}">
                        <label>{!! Form::radio('is_active', '1', ['class' => 'required', 'id' => 'yes']) !!} Active</label>
                        <label>{!! Form::radio('is_active', '0', ['class' => ' required', 'id' => 'no']) !!} Inactive</label>
                        {!! $errors->first('is_active', '<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <a href="{{ url('/training/category-list') }}">
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
    <script>
        $(document).ready(function() {
            $("#select2_day").select2();
            $("#speaker_id").select2();
        });
    </script>
@endsection
