@extends('layouts.admin')

@section('content')
    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'A')) {
        die('You have no access right! Please contact system admin for more information');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <b>New Park</b>
            </div>

            <div class="panel-body">
                {{$json}}

                {!! Form::open(array('url' => '/settings/storeDe','method' => 'post', 'class' => 'form-horizontal smart-form', 'id' => 'formId',
            'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_tin',' Organization TIN ',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_tin', null,['class' => 'form-control bigInputField input-md','size'=>'5x1','maxlength'=>'200']) !!}
                                    {!! $errors->first('organization_tin','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('company_title',' Company Title ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('company_title', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('company_title','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('company_title',' Company Title ',['class'=>'text-left col-md-5']) !!}
                                <div class="col-md-7">
                                    {!! Form::select('l_director_nationality[]', [],'', ['class' => 'form-control input-md required']) !!}
                                    {!! $errors->first('company_title','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_bn',' Organization Name(Bangla) ',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_bn', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('organization_name_bn','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="col-md-12">
                                {!! Form::label('organization_email',' Organization Email',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_email', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('organization_email','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>
                            <div class="col-md-12">
                                {!! Form::label('organization_name_en',' Organization Name(English)',['class'=>'text-left col-md-5 required-star']) !!}
                                <div class="col-md-7">
                                    {!! Form::text('organization_name_en', null,['class' => 'form-control input-md oa_req_field']) !!}
                                    {!! $errors->first('organization_name_en','<span class="help-block">:message</span>') !!}
                                    <br>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <input type="submit"  class="btn btn-success pull-right" value="Save">
            {!! Form::close() !!}<!-- /.form end -->

                <div class="overlay" style="display: none;">
                    <i class="fa fa-refresh fa-spin"></i>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('footer-script')

    <script>

        var _token = $('input[name="_token"]').val();
        $(document).ready(function () {
            $('#formId :input').not('.btn,[name="_token"] {!! $editableFiled !!}').attr('disabled', true);
            // $("#formId").validate({
            //     errorPlacement: function () {
            //         return false;
            //     }
            // });
        });
    </script>
@endsection