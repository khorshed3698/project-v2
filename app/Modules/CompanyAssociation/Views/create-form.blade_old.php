<?php
$accessMode = ACL::getAccsessRight('CompanyAssociation');
if (!ACL::isAllowed($accessMode, '-V-'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('content')

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>Company Association Request</strong></h5>
            </div>
            <div class="panel-body">
                <div class="col-lg-10">
                    {!! Form::open(array('url' => 'company-association/store', 'method' => 'post','id'=>'formId')) !!}


                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('', 'Applicant Name:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {{ CommonFunction::getUserFullName() }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('', 'Current Company:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <?php $i = 1;?>
                                @foreach($current_company_lists as $company)
                                    <dd>{{$i++}}. {!! $company->company_name !!}</dd>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                {!! Form::label('', 'Company Type :', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    <label class="radio-inline">  {!! Form::radio('company_types', 'new', false,['class' => '', 'onchange' => "ChangeCompanyType(this.value)"]) !!} New </label>
                                    <label class="radio-inline">  {!! Form::radio('company_types', 'existing', false,['class' => '', 'onchange' => "ChangeCompanyType(this.value)"]) !!} Existing </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12 hidden" id="requested_company_ids">
                        <div class="row">
                            {!! Form::label('select_company', 'Requested Company to associate:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <select name="requested_company_ids[]" class="form-control limitedNumbSelect2"
                                        data-placeholder="Select company to request to associate" style="width: 100%;"
                                        multiple="multiple">
                                    @foreach($companyList as $company)
                                        @if(!in_array($company->id, $current_company_ids))
                                            <option value="{{ \App\Libraries\Encryption::encodeId($company->id) }}">{{ $company->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger" style="font-size: 10px; font-weight: bold">[All of your associated company are selected in the list. you can add a new company.]</span>
                                {!! $errors->first('requested_company_ids','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-12 hidden" id="remove_req_company_ids">
                        <div class="row">
                            {!! Form::label('select_company', 'Requested Company to remove:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <select name="remove_req_company_ids[]" class="form-control limitedNumbSelect2"
                                        data-placeholder="Select company to remove from association" style="width: 100%;"
                                        multiple="multiple">
                                    @foreach($companyList as $company)
                                        @if(in_array( $company->id, $current_company_ids))
                                            <option value="{{ \App\Libraries\Encryption::encodeId($company->id) }}">{{ $company->company_name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="text-danger" style="font-size: 10px; font-weight: bold">[All of your associated company in the list. you can select a company from list to remove.]</span>
                                {!! $errors->first('remove_req_company_ids','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group hidden" id="company_name_en">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('company_name_en') ? 'has-error': ''}}">
                                {!! Form::label('company_name_en', 'Company Name (English) :', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('company_name_en', '', ['class' => 'form-control input-md']) !!}
                                    {!! $errors->first('company_name_en','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hidden" id="company_name_bn">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                {!! Form::label('company_name_bn', 'Company Name (Bangla) :', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::text('company_name_bn', '', ['class' => 'form-control input-md']) !!}
                                    {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group hidden" id="authorize_latter">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('authorize_latter') ? 'has-error': ''}}">
                                {!! Form::label('authorize_latter', 'Authorize Latter :', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    {!! Form::file('authorize_latter') !!}
                                    {!! $errors->first('authorize_latter','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('user_remarks', 'Remarks:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                {!! Form::textarea('user_remarks','',['class'=>'form-control','placeholder'=>'Remarks', 'size' => '3x2']) !!}
                                {!! $errors->first('user_remarks','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-md-10">
                    <div class="pull-left">
                        <a href="{{ url('company-association/list') }}" class="btn btn-default"><i
                                    class="fa fa-close"></i> Close</a>
                    </div>
                    <div class="pull-right">
                        @if(ACL::getAccsessRight('CompanyAssociation','-A-'))
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit
                            </button>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ asset("assets/plugins/select2.min.css") }}">
    <script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
    <script>
        function matchCustom(params, data) {
            if (typeof data.text === 'undefined') {
                return null;
            }
        }
        $(document).ready(function () {
            //Select2
            $(".limitedNumbSelect2").select2({
                //maximumSelectionLength: 1
                minimumInputLength: 3,
                tags: false,
            });

            $("#formId").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

        function ChangeCompanyType(change_value) {
            if (change_value == "new") {
                $("#company_name_en").removeClass('hidden');
                $("#company_name_bn").removeClass('hidden');
                $("#authorize_latter").removeClass('hidden');
            }else {
                $("#company_name_en").addClass('hidden');
                $("#company_name_bn").addClass('hidden');
                $("#authorize_latter").addClass('hidden');
            }
            if (change_value == "existing"){
                $("#requested_company_ids").removeClass('hidden');
                $("#remove_req_company_ids").removeClass('hidden');
            }else {
                $("#requested_company_ids").addClass('hidden');
                $("#remove_req_company_ids").addClass('hidden');
            }
        }
    </script>
@endsection