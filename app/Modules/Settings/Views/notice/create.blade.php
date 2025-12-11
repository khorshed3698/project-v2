@extends('layouts.admin')

@section('page_heading','Notice Form')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'A'))
        die('no access right!');
    ?>
    <div class="col-lg-12">

        {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
        {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> {{trans('messages.new_notice_form_title')}}</b></h5>
            </div>
            <!-- /.panel-heading -->
            <div class="panel-body">
                {!! Form::open(array('url' => '/settings/store-notice','method' => 'patch', 'class' => 'form-horizontal smart-form', 'id' => 'notice-info',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}


                <div class="form-group col-md-12 {{$errors->has('heading') ? 'has-error' : ''}}">
                    {!! Form::label('heading','Heading: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::text('heading', '', ['class'=>'form-control bnEng required', 'size' => "10x5"]) !!}
                        {!! Form::hidden('board_meeting',Request::segment(3), ['class'=>'form-control']) !!}
                        {!! $errors->first('heading','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('details') ? 'has-error' : ''}}">
                    {!! Form::label('details','Details: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('details', '', ['class'=>'form-control bnEng required', 'size' => "10x5", 'id' => 'detail_editor']) !!}
                        {!! $errors->first('details','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <?php
                $importance_arr = array(
                    '' => 'Select One',
                    'danger' => 'Danger',
                    'info' => 'Info',
                    'top' => 'Top',
                    'warning' => 'Warning',
                );
                ?>
                <div class="form-group col-md-12 {{$errors->has('importance') ? 'has-error' : ''}}">
                    {!! Form::label('importance','Importance: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::select('importance',$importance_arr, null, array('class'=>'form-control required')) !!}
                        {!! $errors->first('importance','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                    {!! Form::label('status','Status: ',['class'=>'col-md-2  required-star']) !!}
                    <div class="col-md-10">
                        <label>{!! Form::radio('status', 'draft', true, ['class'=>'cursor required', 'id' => 'draft']) !!} Draft</label>
                        &nbsp;&nbsp;
                        <label>{!! Form::radio('status', 'private', false, ['class'=>'cursor required', 'id' => 'private']) !!} Private</label>
                        &nbsp;&nbsp;
                        <label>{!! Form::radio('status', 'unpublished', false, ['class'=>'cursor required', 'id' => 'unpublished']) !!} Unpublished</label>
                        @if(Auth::user()->user_type=='1x101' OR Auth::user()->user_type=='2x202')
                            &nbsp;&nbsp;
                            <label>{!! Form::radio('status', 'public', false, ['class'=>'cursor required', 'id' => 'public']) !!} Public</label>
                        @endif
                        {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div><!-- /.box -->
            <div class="panel-footer">
                <div class="col-md-12">
                    @if(Request::segment(3))
                        <?php
                        session()->put('board_meeting',Request::segment(3));
                        ?>
                        <a href="{{ url('/board-meting/lists') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @else
                        <a href="{{ url('/settings/notice') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-default')) !!}
                        </a>
                    @endif

                    @if(ACL::getAccsessRight('settings','A'))
                        <button type="submit" class="btn btn-primary pull-right">
                            <i class="fa fa-chevron-circle-right"></i> Save</button>
                    @endif
                </div><!-- /.box-footer -->
                <div class="clearfix"></div>
            </div>
        {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>

@endsection


@section('footer-script')
    <script src="{{asset('vendor/tinymce/tinymce.min.js')}}" type="text/javascript"></script>

    <script>
        var _token = $('input[name="_token"]').val();

        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            $("#notice-info").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        tinymce.init({
            selector: '#detail_editor',
            height: 200,
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
@endsection <!--- footer script--->