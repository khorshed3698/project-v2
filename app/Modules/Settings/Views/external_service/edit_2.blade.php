@extends('layouts.admin')

@section('page_heading',trans('messages.stakeholder_form'))
@section('style')
    <style>
        input[type="radio"].error {
            outline: 1px solid red
        }

        #translate {
            cursor: pointer;
        }

        #json-display {
            border: 1px solid #000;
            margin: 0;
            padding: 10px 20px;
        }
    </style>
@endsection

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'E')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>
    @include('partials.messages')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><b> Stakeholder info edit </b></h5>
            </div><!-- /.panel-heading -->


            {!! Form::open(array('url' => '/settings/get-external-service-list/update','method' => 'post', 'class' => 'form-horizontal', 'id' => 'stakeholder',
                'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
            {!! Form::hidden('process_type_id', Encryption::encodeId($externalService->id),['class' => 'form-control input-md required', 'id'=>'process_type_id']) !!}
            <div class="panel-body">

                <div class="form-group col-md-12 {{$errors->has('external_service_config') ? 'has-error' : ''}}">
                    {!! Form::label('external_service_config','External Service Config: ',['class'=>'col-md-3 control-label required-star']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('external_service_config', $externalService->external_service_config, ['placeholder'=>'External Service Config', 'class' => 'form-control bigInputField input-lg','id' => 'external_service_config']) !!}
                        {!! $errors->first('external_service_config','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="pull-left">
                        <button id="translate" type="button" class="btn btn-md btn-success">Validate</button>
                    </div>
                    <div class="pull-right">
                        <a href="{{ url('/settings/external-service-list') }}">
                            {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-md btn-default')) !!}
                        </a>
                        @if(ACL::getAccsessRight('settings','E'))
                            <button type="submit" class="btn btn-md btn-primary">
                                <i class="fa fa-chevron-circle-right"></i> Save
                            </button>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-12" style="padding-top: 20px">
                    <pre id="json-display"></pre>
                    <div class="clearfix"></div>
                </div>
            </div><!-- /.box -->
        {!! Form::close() !!}<!-- /.form end -->
        </div>
    </div>

@endsection


@section('footer-script')

    <script src="{{ asset("assets/stakeholder-plugins/jquery.json-editor.min.js") }}"></script>
    <script>
        var _token = $('input[name="_token"]').val();
        var age = -1;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function () {
            $("#stakeholder").validate({
                errorPlacement: function () {
                    return false;
                }
            });
            $("#department").trigger('change');
        });
    </script>
    <script type="text/javascript">
        function getJson() {
            try {
                return JSON.parse($('#external_service_config').val());
            } catch (ex) {
                alert('Wrong JSON Format: ' + ex);
            }
        }// end -:- getJson()
        var editor = new JsonEditor('#json-display', getJson());
        $('#translate').on('click', function () {
            editor.load(getJson());
        });
    </script>
@endsection <!--- footer script--->