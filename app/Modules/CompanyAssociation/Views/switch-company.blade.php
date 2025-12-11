@extends('layouts.admin')
@section('content')
    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h5><strong>{!! trans('messages.switch_company') !!}</strong></h5>
            </div>
            {!! Form::open(array('url' => '/company-association/update-working-company', 'method' => 'post','id'=>'formId', 'files' => true)) !!}
            <div class="panel-body">
                <div class="col-lg-10 col-md-offset-1">
                    <div class="form-group col-md-12">
                        <div class="row">
                            {!! Form::label('', 'Current Company:', ['class' => 'col-md-3']) !!}
                            <div class="col-md-9">{{ CommonFunction::getCompanyNameById(Auth::user()->working_company_id) }}</div>
                        </div>
                    </div>

                    @if(isset($companyList) && $companyList->count()> 1)
                        <div class="control-group col-md-12">
                            <div class="row">
                                {!! Form::label('', 'Switch Company:', ['class' => 'col-md-3']) !!}
                                <div class="col-md-9">
                                    <select name="requested_company_id" class="form-control required limitedNumbSelect2" data-placeholder="Select a Company" style="width: 80%;">
                                        <option></option>
                                        @foreach($companyList as $company)
                                            <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('requested_company_id','<span class="help-block" style="color:#d63f3f">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-md-10 col-md-offset-1">
                    <div class="pull-left">
                        <a href="{{ url('/dashboard') }}" class="btn btn-danger"><i class="fa fa-close"></i> Close</a>
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit
                        </button>
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
        $(document).ready(function(){
            //Select2
            $(".limitedNumbSelect2").select2();

        });
    </script>
    @endsection