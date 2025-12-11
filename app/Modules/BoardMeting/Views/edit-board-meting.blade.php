@extends('layouts.admin')

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('BoardMeting');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact with system admin for more information.');
    }
    ?>

{{--    @include('BoardMeting::progress-bar')--}}

    <div class="col-lg-12">

        @include('partials.messages')

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>{!! trans('messages.new_boardmeeting_D') !!}</b>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/board-meting/update-meeting','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'entry-form',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

                <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12 form-group">
                        {!! Form::label('meeting_type','Meeting Type', ['class'=>'col-md-3 required-star']) !!}
                        <div class="col-md-5 {{$errors->has('type') ? 'has-error': ''}}">
                            {!! Form::select('meeting_type',$meetingType, $bm_data->meting_type, array('class'=>'form-control users required',
                            'placeholder' => 'Select One', 'id'=>"meeting_type")) !!}
                            {!! $errors->first('meeting_type','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <input type="hidden" value="{{\App\Libraries\Encryption::encodeId($bm_data->id)}}" name="bm_id">
                        <div class="col-md-12 form-group">
                            {!! Form::label('meting_number',trans('messages.meeting_no'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('name') ? 'has-error': ''}}">
                                {!! Form::text('meting_number', $bm_data->meting_number, ['class'=>'col-md-12 form-control onlyNumber input-sm required','id'=>'meeting_number']) !!}
                                <span class="number-error text-danger"></span>
                                {!! $errors->first('meting_number', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 form-group">
                            <?php
                            $newDate = date("d/M/Y h:i a", strtotime($bm_data->meting_date));
                            ?>
                            {!! Form::label('meting_date',trans('messages.meeting_date'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('meting_date') ? 'has-error': ''}}">
                                {!! $errors->first('meting_date','<span class="help-block">:message</span>') !!}
                                <div class=" input-group date " id="datetimepicker">
                                    {!! Form::text('meting_date', $newDate, ['class'=>'col-md-12 form-control input-sm bnEng required ','id'=>'datetimepicker','  autofocus']) !!}
                                    <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('location',trans('messages.meeting_places'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('location') ? 'has-error': ''}}">
                                {!! Form::text('location', $bm_data->location, ['class' => 'col-md-12 form-control bnEng input-sm required']) !!}
                                {!! $errors->first('location','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('meeting_subject',trans('messages.meeting_subject'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('meeting_subject') ? 'has-error': ''}}">
                                {!! Form::text('meeting_subject', $bm_data->meting_subject, ['class'=>'col-md-12 form-control input-sm bnEng required','id'=>'meeting_subject']) !!}
                                <span class="text-danger"></span>
                                {!! $errors->first('meeting_subject','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('organization',trans('messages.organization'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('organization') ? 'has-error': ''}}">
                                {!! Form::text('organization', $bm_data->org_name, ['class'=>'col-md-12 form-control input-sm bnEng required','id'=>'organization','maxlength'=>'200']) !!}
                                <span class=" text-danger"></span>
                                {!! $errors->first('organization','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('organization_address',trans('messages.organization_address'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('organization_address') ? 'has-error': ''}}">
                                {!! Form::text('organization_address', $bm_data->org_address, ['class'=>'col-md-12 form-control input-sm bnEng required','id'=>'organization_address','maxlength'=>'200']) !!}
                                <span class=" text-danger"></span>
                                {!! $errors->first('organization_address','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group">
                            {!! Form::label('notice_details',trans('messages.notice_details'), ['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-5 {{$errors->has('notice_details') ? 'has-error': ''}}">
                                {!! Form::textarea('notice_details', $bm_data->notice_details, ['class'=>'col-md-12 form-control input-sm bnEng required','id'=>'notice_details','maxlength'=>'5000']) !!}
                                <span class=" text-danger"></span>
                                {!! $errors->first('notice_details','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div>
                        <a href="{{ url('/board-meting/lists') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                        @if(ACL::getAccsessRight('BoardMeting','A'))
                            <button type="submit" class="btn btn-primary save pull-right">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>

                </div><!--/col-md-12-->

            {!! Form::close() !!}<!-- /.form end -->


                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
        </div>
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
        <div class="col-md-12"><br></div>
    </div>


@endsection
@section('footer-script')

    <script>
        $('#datetimepicker').datetimepicker({
            // inline: true,
//            sideBySide: true,
//             keepOpen: true,
            format: 'DD/MMM/YYYY hh:mm:a',
            // minDate: (new Date()),
            // debug: true
        });
       /* $('#datetimepickerEnd').datetimepicker({
            // inline: true,
//            sideBySide: true,
//             keepOpen: true,
            format: 'DD/MMM/YYYY hh:mm:a',
            // minDate: (new Date()),
            // debug: true
        });*/
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $('#datetimepicker').removeClass('engOnly');
            $('#meeting_number').removeClass('engOnly');
            $("#entry-form").validate({
                errorPlacement: function () {
                    return false;
                }
            });

            var today = new Date();
            var yyyy = today.getFullYear();
            var mm = today.getMonth();
            var dd = today.getDate();
            $("body").on('focus', '.datepicker', function () {
                $(this).datetimepicker({
                    viewMode: 'years',
                    format: 'DD-MMM-YYYY'
                });
            });
        });
    </script>
    <style>
        .datepicker {
            border: 1px solid #ada4a4;
        }
    </style>
@endsection <!--- footer script--->