<?php
$accessMode = ACL::getAccsessRight('CompanyAssociation');
$usreType = CommonFunction::getUserType();
if (!ACL::isAllowed($accessMode, '-V-'))
    die('no access right!');
?>

@extends('layouts.admin')
@section('content')
    @include('partials.messages')

    {{--Stakeholder modal--}}
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>Company Association Request</strong></h5>
            </div>

            {!! Form::open(array('url' => 'company-association/update', 'method' => 'post','id'=>'company_association_update')) !!}
            <div class="panel-body">
                <section class="content-header">
                    <ol class="breadcrumb">
                        <li><strong> Company type : </strong> {{ ucfirst($companyAssociationRequest->company_type)  }}
                        </li>
                        <li><strong> Request type : </strong> {{ $companyAssociationRequest->request_type  }} </li>
                        <li><strong> Name : </strong> {{ $companyAssociationRequest->full_name  }} </li>
                        <li><strong> Email : </strong> {{ $companyAssociationRequest->user_email  }} </li>
                        <li><strong> Application date
                                : </strong> {{ date('d-M-Y', strtotime($companyAssociationRequest->application_date))  }}
                        </li>
                        <li><strong>Status : </strong>
                            @if($companyAssociationRequest->status_id == 1)
                                {{ 'Submitted' }}
                            @elseif($companyAssociationRequest->status_id == 25)
                                {{ 'Approved' }}
                            @elseif($companyAssociationRequest->status_id == 6)
                                {{ 'Rejected' }}
                            @endif
                        </li>
                    </ol>
                </section>

                {!! Form::hidden('app_id', \App\Libraries\Encryption::encodeId($companyAssociationRequest->id)) !!}

                <div class="form-group col-md-12">
                    {!! Form::label('select_company', 'Previous company:', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        <?php $i = 1;?>
                        @foreach($previous_company_lists as $company)
                            <dd>{{$i++}}. {!!$company->company_name!!}</dd>
                        @endforeach
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('request_type', 'Request type:', ['class' => 'col-md-3 required-star']) !!}
                    <div class="col-md-9">
                        <label>{!! Form::radio('request_type', 'Add', ($companyAssociationRequest->request_type === 'Add'), ['class'=>'request_type required', 'onclick' => 'selectRequestType(this.value)', 'disabled']) !!}
                            Add</label>
                        <label>{!! Form::radio('request_type', 'Remove', ($companyAssociationRequest->request_type === 'Remove'), ['class'=>'request_type required', 'onclick' => 'selectRequestType(this.value)', 'disabled']) !!}
                            Remove</label>
                        {!! $errors->first('request_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                @if ($companyAssociationRequest->request_type === 'Add')
                    <div class="form-group col-sm-12">
                        <div class="col-sm-12">
                            <label for="company_types" class="btn btn-success disabled">New Company
                                {!! Form::checkbox('company_types', 'new' , ($companyAssociationRequest->company_type === 'new'), array('id'=>'company_types', 'class'=>'badgebox', 'onchange' => "ChangeCompanyType(this)", 'disabled')) !!}
                                <span class="badge">&check;</span></label>
                        </div>
                    </div>

                    @if ($companyAssociationRequest->company_type === 'new')
                        <div class="form-group col-md-12" id="business_category_div">
                            {!! Form::label('business_category','Business Category :',['class'=>'col-md-3 required-star']) !!}
                            <div class="col-md-9">
                                <label class="radio-inline">{!! Form::radio('business_category','1', ($companyAssociationRequest->business_category == '1' ? true : false), ['class'=>'required', 'id' => 'private']) !!}
                                    Private</label>
                                <label class="radio-inline">{!! Form::radio('business_category', '2', ($companyAssociationRequest->business_category == '2' ? true : false), ['class'=>'required', 'id' => 'government']) !!}
                                    Government</label>
                                {!! $errors->first('business_category','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group col-sm-12" id="company_name_en_div">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('company_name_en') ? 'has-error': ''}}">
                                    {!! Form::label('company_name_en', 'Company Name (English) :', ['class' => 'col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('company_name_en', $companyAssociationRequest->company_en, ['class' => 'form-control input-md']) !!}
                                        {!! $errors->first('company_name_en','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-12" id="company_name_bn_div">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                    {!! Form::label('company_name_bn', 'Company Name (Bangla) :', ['class' => 'col-md-3']) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('company_name_bn', $companyAssociationRequest->company_bn, ['class' => 'form-control input-md']) !!}
                                        {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif ($companyAssociationRequest->company_type === 'existing')
                        <div class="form-group col-md-12">
                            {!! Form::label('select_company', 'Requested company to add:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">
                                <dd>1. {{ $current_company->company_name }}</dd>
                            </div>
                        </div>
                    @endif
                    <div class="form-group col-sm-12" id="authorization_letter_div">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('authorization_letter') ? 'has-error': ''}}">
                                {!! Form::label('authorization_letter', 'Authorization letter :', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    <a target="_blank" rel="noopener"
                                       class="btn btn-xs btn-primary"
                                       href="{{URL::to('/users/upload/'. $companyAssociationRequest->authorization_letter)}}">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Open File
                                    </a>
                                    @if (ACL::isAllowed($accessMode, '-UP-'))
                                        <a class="addDistribution btn btn-success btn-xs" data-toggle="modal"
                                           data-target="#myModal" onclick="openModal(this)"
                                           data-action="{{ url('company-association/change-auth-letter/'. \App\Libraries\Encryption::encodeId($companyAssociationRequest->id)) }}">
                                            Change auth letter
                                        </a>
                                    @endif
                                    {!! $errors->first('authorization_letter','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($companyAssociationRequest->request_type === 'Remove')
                    <div class="form-group col-md-12">
                        {!! Form::label('select_company', 'Requested company to remove:', ['class' => 'col-md-3']) !!}
                        <div class="col-md-9">
                            <dd>1. {{ $current_company->company_name }}</dd>
                        </div>
                    </div>
                @endif

                <div class="form-group col-md-12">
                    {!! Form::label('user_remarks', 'User remarks:', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('user_remarks', $companyAssociationRequest->user_remarks,['class'=>'form-control','placeholder'=>'Remarks', 'size' => '3x2', 'readonly']) !!}
                        {!! $errors->first('user_remarks','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="form-group col-md-12">
                    {!! Form::label('desk_remarks', 'Desk user remarks:', ['class' => 'col-md-3']) !!}
                    <div class="col-md-9">
                        {!! Form::textarea('desk_remarks', $companyAssociationRequest->desk_remarks,['class'=>'form-control','placeholder'=>'Remarks', 'size' => '3x2']) !!}
                        {!! $errors->first('desk_remarks','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                @if ($companyAssociationRequest->request_type == 'Add')
                    <div class="form-group col-md-12">
                        {!! Form::label('approved_user_type', 'User type:', ['class' => 'col-md-3 required-star']) !!}
                        <div class="col-md-9">
                            <label class="radio-inline">{!! Form::radio('approved_user_type','Employee', ($companyAssociationRequest->approved_user_type == 'Employee' ? true : false), ['class'=>'required', 'id' => 'yes']) !!}
                                Employee</label>
                            <label class="radio-inline">{!! Form::radio('approved_user_type', 'Consultant', ($companyAssociationRequest->approved_user_type == 'Consultant' ? true : false), ['class'=>'required', 'id' => 'no']) !!}
                                Consultant</label>
                            {!! $errors->first('approved_user_type','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>
                @endif

                <div class="form-group col-md-12">
                    {!! Form::label('status_id', 'Status:', ['class' => 'col-md-3 required-star']) !!}
                    <div class="col-md-9">
                        <label class="radio-inline">{!! Form::radio('status_id','Reject', ($companyAssociationRequest->status_id == '6' ? true : false), ['class'=>'required', 'id' => 'reject']) !!}
                            Reject</label>
                        <label class="radio-inline">{!! Form::radio('status_id', 'Approved', ($companyAssociationRequest->status_id == '25' ? true : false), ['class'=>'required', 'id' => 'approved']) !!}
                            Approved</label>
                        {!! $errors->first('status_id','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                <div class="pull-left">
                    <a href="{{ url('company-association/list') }}" class="btn btn-default"><i class="fa fa-close"></i>
                        Close</a>
                </div>
                <div class="pull-right text-right">
                    @if(ACL::getAccsessRight('CompanyAssociation','-UP-'))
                        @if($companyAssociationRequest->status_id == 1 && in_array($usreType, ['1x101','2x202']))
                            {{--                            <a href="{{ url('company-association/reject/'.Encryption::encodeId($companyAssociationRequest->id)) }}"--}}
                            {{--                               class="btn btn-danger"><i class="fa fa-check-circle"></i> Reject</a>--}}
                            {{--                            <a href="{{ url('company-association/approve/'.Encryption::encodeId($companyAssociationRequest->id)) }}"--}}
                            {{--                               class="btn btn-primary"><i class="fa fa-check-circle"></i> Approve</a>--}}


                            <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update
                            </button>
                        @endif
                    @endif
                </div>
                <div class="clearfix"></div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $(document).ready(function () {
            $("#company_association_update").validate({
                errorPlacement: function () {
                    return false;
                }
            });


            @if($viewMode == 'on')
            $('#company_association_update :input').attr('disabled', true);
            @endif
        });

        function openModal(btn) {
            var this_action = btn.getAttribute('data-action');
            var modal_id = btn.getAttribute('data-target');
            if (this_action != '') {
                $.get(this_action, function (data, success) {
                    if (success === 'success') {
                        $(modal_id + ' .load_modal').html(data);
                    } else {
                        $(modal_id + ' .load_modal').html('Unknown Error!');
                    }
                    $(modal_id).modal('show', {backdrop: 'static'});
                });
            }
        }
    </script>
@endsection