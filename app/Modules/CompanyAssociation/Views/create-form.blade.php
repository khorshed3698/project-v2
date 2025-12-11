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
                    {!! Form::open(array('url' => 'company-association/store', 'method' => 'post','id'=>'formId', 'files' => true)) !!}

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
                            {!! Form::label('', 'Associated with Companies:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <?php $i = 1;?>
                                @foreach($current_company_lists as $company)
                                    <dd>{{$i++}}. {!! $company !!}</dd>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('request_type', 'Request type:', ['class' => 'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                <label>{!! Form::radio('request_type', 'Add', true, ['class'=>'request_type required', 'onclick' => 'selectRequestType(this.value)']) !!}
                                    Add</label>
                                <label>{!! Form::radio('request_type', 'Remove',false, ['class'=>'request_type required', 'onclick' => 'selectRequestType(this.value)']) !!}
                                    Remove</label>
                                {!! $errors->first('request_type','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>

                    <div id="add_request_section" hidden>
                        <div class="col-sm-12">
                            <div class="page-header">
                                <h4 class="text-primary">Request for Company Addition to my association</h4>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label for="company_types" class="btn btn-success">New Company
                                {!! Form::checkbox('company_types', 'new' , null, array('id'=>'company_types', 'class'=>'badgebox', 'onchange' => "ChangeCompanyType(this)")) !!}
                                <span class="badge">&check;</span></label>
                        </div>
                        {{-- Business Category --}}
                        <div class="form-group hidden has-feedback {{$errors->has('business_category') ? 'has-error': ''}}" id="business_category_div">
                            {!! Form::label('business_category','Business Category :',['class'=>'col-md-4 required-star']) !!}
                            <div class="col-md-8">
                                <label class="radio-inline">{!! Form::radio('business_category', '1', false, ['class'=>'business_category', 'id' => 'private']) !!}
                                    Private</label>
                                <label class="radio-inline">{!! Form::radio('business_category', '2', false, ['class'=>'business_category', 'id' => 'government']) !!}
                                    Government</label>
                                {!! $errors->first('business_category','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group hidden" id="company_name_en_div">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('company_name_en') ? 'has-error': ''}}">
                                    {!! Form::label('company_name_en', 'Organization name (English) :', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('company_name_en', '', ['class' => 'form-control input-md']) !!}
                                        {!! $errors->first('company_name_en','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group hidden" id="company_name_bn_div">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                    {!! Form::label('company_name_bn', 'Organization name (Bangla) :', ['class' => 'col-md-4  required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('company_name_bn', '', ['class' => 'form-control input-md']) !!}
                                        {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12" id="requested_company_id_div">
                            <div class="row">
                                {!! Form::label('select_company', 'Requested Company to associate:', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{$errors->has('requested_company_id') ? 'has-error': ''}}">
                                    <select name="requested_company_id"
                                            class="form-control required limitedNumbSelect2"
                                            id="requested_company_id"
                                            data-placeholder="Select company to request to associate"
                                            style="width: 100%;" required>
                                        <option value=""></option>
                                        @foreach($companyList as $company)
                                            @if(!in_array($company->id, $current_company_ids))
                                                <option value="{{ \App\Libraries\Encryption::encodeId($company->id) }}">{{ $company->company_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
{{--                                    <span class="text-danger" style="font-size: 10px; font-weight: bold">[Please enter 3 or more characters to search- please see from google docs.]</span>--}}
                                    {!! $errors->first('requested_company_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="authorization_letter_div">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('authorization_letter') ? 'has-error': ''}}">
                                    {!! Form::label('authorization_letter', 'Authorization letter :', ['class' => 'col-md-4 required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::file('authorization_letter', ['class' => 'form-control', 'onchange'=>"checkPdfDocumentType(this.id, 3)", 'accept'=>"application/pdf"]) !!}
                                        <small style="font-size: 9px; font-weight: bold; color: #666363; font-style: italic">[Format: *.PDF | Maximum 3 MB, Application with Name &amp; Signature] </small>
                                        {!! $errors->first('authorization_letter','<span class="help-block">:message</span>') !!}
                                        <br>
                                        <a target="_blank" rel="noopener" href="{{ url('assets/images/sample_auth_letter.png') }}"><i class="fa fa-file" aria-hidden="true"></i> <i>Sample Authorization letter</i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="remove_request_section" hidden>
                        <div class="col-sm-12">
                            <div class="page-header">
                                <h4 class="text-primary">Request for Company Removal from my association</h4>
                            </div>
                        </div>

                        <div class="form-group col-md-12" id="remove_req_company_id">
                            <div class="row">
                                {!! Form::label('select_company', 'Requested Company to remove:', ['class' => 'col-md-4 required-star']) !!}
                                <div class="col-md-8 {{$errors->has('remove_req_company_id') ? 'has-error': ''}}">
                                    <select name="remove_req_company_id" class="form-control limitedNumbSelect2"
                                            data-placeholder="Select company to remove from association"
                                            style="width: 100%;" required>
                                        <option value=""></option>
                                        @foreach($current_company_lists as $id => $company)
                                            <option value="{{ \App\Libraries\Encryption::encodeId($id) }}">{{ $company }}</option>
                                        @endforeach
                                    </select>
                                    <small style="font-size: 9px; font-weight: bold; color: #666363; font-style: italic">[All of your associated company in the list. you can select a company from list to remove.]</small>
                                    {!! $errors->first('remove_req_company_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('user_remarks', 'Remarks:', ['class' => 'col-md-4']) !!}
                            <div class="col-md-8">
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
                language: {
                    inputTooShort: function() {
                        return 'Please search by entering 03 or more characters.';
                    }
                }
            });

            $("#formId").validate({
                errorPlacement: function () {
                    return false;
                }
            });


            // Trigger Selected Company type
            var selected_company_type = document.querySelector('input[name="company_types"]:checked');
            if (selected_company_type) {
                selected_company_type.dispatchEvent(new Event("change"));
            }
            // End Trigger Selected Company type


            // Trigger for request type
            var set_trigger = document.querySelector("input[name='request_type']:checked");
            if (set_trigger) {
                set_trigger.dispatchEvent(new Event('click'));
            }
            // End Trigger for request type
        });

        function selectRequestType(request_type) {
            if (request_type === 'Add') {
                document.getElementById("add_request_section").style.display = 'block';
                document.getElementById("remove_request_section").style.display = 'none';
                $("#authorization_letter").addClass('required');
            } else if (request_type === 'Remove') {
                document.getElementById("remove_request_section").style.display = 'block';
                document.getElementById("add_request_section").style.display = 'none';
                $("#authorization_letter").removeClass('required');
            }
        }

        function ChangeCompanyType(change_value) {
            if ($(change_value).is(':checked')) {
                $("#business_category_div").removeClass('hidden');
                $("#private").addClass('required');
                $("#company_name_en_div").removeClass('hidden');
                $("#company_name_en").addClass('required');
                $("#company_name_bn_div").removeClass('hidden');
                $("#company_name_bn").addClass('required');
                $("#requested_company_id_div").addClass('hidden');
                $("#requested_company_id").attr('required', false);
            } else {
                $("#business_category_div").addClass('hidden');
                $("#private").removeClass('required');
                $("#company_name_en_div").addClass('hidden');
                $("#company_name_en").removeClass('required');
                $("#company_name_bn_div").addClass('hidden');
                $("#company_name_bn").removeClass('required');
                $("#requested_company_id_div").removeClass('hidden');
                $("#requested_company_id").attr('required', true);
            }
        }
    </script>
@endsection