<?php
$accessMode = ACL::getAccsessRight('BasicInformation');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>


<style>
    .form-group {
        margin-bottom: 2px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }

    input[type=radio].error,
    input[type=checkbox].error {
        outline: 1px solid red !important;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }
</style>

<section class="content" id="applicationForm">
    @include('ProcessPath::remarks-modal')


    <div class="modal fade" id="changeCompanyModal" tabindex="-1" role="dialog"
         aria-labelledby="changeCompanyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content load_modal"></div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                {{--start application form with wizard--}}
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong> Application for Basic Information </strong></h5>
                        </div>
                        <div class="pull-right">
                            {{--Company info change in modal--}}
                            @if(in_array($appInfo->process_type_id, [100]) && !in_array($appInfo->status_id,[25]) && \App\Libraries\ACL::getAccsessRight('processPath', '-CC-'))
                                <a class="btn btn-warning" data-toggle="modal" data-target="#changeCompanyModal"
                                   onclick="openChangeCompanyModal(this)"
                                   data-action="{{ url('process/change-company/'.\App\Libraries\Encryption::encodeId($appInfo->company_id)) }}">
                                    <strong>Change organization</strong>
                                </a>
                            @endif

                            @if(!in_array($appInfo->status_id,[-1,5,6]))
                                <a href="/basic-information/app-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                                   target="_blank" rel="noopener" 
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                </a>
                            @endif
                            @if(in_array($appInfo->status_id,[5,6,22]))
                                <a data-toggle="modal" data-target="#remarksModal">
                                    {!! Form::button('<i class="fa fa-eye"></i> <b>Reason of '.$appInfo->status_name.' </b>', array('type' => 'button', 'class' => 'btn btn-md btn-danger')) !!}
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-body">
                        {{-- Breadcumb bar --}}
                        @if ($viewMode == 'on' || (isset($appInfo->status_id) && $appInfo->status_id == 5))
                            <section class="content-header">
                                <ol class="breadcrumb">
                                    <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                    <li><strong> Date of
                                            Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                                    </li>
                                    <li><strong>Current Status : </strong>
                                        @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                        @else {!! $appInfo->status_name !!}
                                        @endif
                                    </li>
                                    <li>
                                        @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                        {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                        @else
                                            <strong>Current Desk :</strong> Applicant
                                        @endif
                                    </li>
                                    {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 5)--}}
                                    {{--<li>--}}
                                    {{--<strong>Shortfall Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                    {{--</li>--}}
                                    {{--@endif--}}
                                    {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 6)--}}
                                    {{--<li>--}}
                                    {{--<strong>Discard Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                    {{--</li>--}}
                                    {{--@endif--}}
                                    @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                                        <li>
                                            <a href="{{ url($appInfo->certificate_link) }}"
                                               class="btn show-in-view btn-xs btn-info"
                                               title="Download Approval Letter" target="_blank" rel="noopener"> <i
                                                        class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                        </li>
                                    @endif
                                </ol>
                            </section>
                        @endif
                        {{-- End of Breadcumb bar --}}

                        <div>
                            {!! Form::open(array('url' => '/basic-information/add','method' => 'post','id' => 'basicInformationForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                            {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}
                            {!! Form::hidden('curr_process_status_id', $appInfo->status_id,['class' => 'form-control input-md required', 'id'=>'process_status_id']) !!}

                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>

                            <h3 class="text-center stepHeader"> Application Information</h3>
                            <fieldset>
                                <legend class="d-none">Application Information</legend>
                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>A. Company
                                            Information</strong></div>
                                    <div class="panel-body">
                                        <div id="validationError"></div>
                                        @if(!in_array($appInfo->status_id, [0,-1,1,2,5,6]) && $appInfo->department_id != '')
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12 {{$errors->has('department_id') ? 'has-error': ''}}">
                                                        {!! Form::label('department_id','Department',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            {!! Form::select('department_id', $departmentList, $appInfo->department_id, ['class' => 'form-control required input-md ','id'=>'department_id']) !!}
                                                            {!! $errors->first('department_id','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 form-group {{$errors->has('company_name') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name','Name of Organization/ Company/ Industrial Project',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('company_name', $appInfo->company_name, ['class' => 'form-control input-md required', 'readonly']) !!}
                                                        {!! $errors->first('company_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div class="col-md-12 form-group {{$errors->has('company_name_bn') ? 'has-error': ''}}">
                                                    {!! Form::label('company_name_bn','Name of Organization/ Company/ Industrial Project (বাংলা)',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('company_name_bn', $appInfo->company_name_bn, ['class' => 'form-control input-md', 'readonly']) !!}
                                                        {!! $errors->first('company_name_bn','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    {!! Form::label('country_of_origin_id','Country of Origin',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('country_of_origin_id',$countries, $appInfo->country_of_origin_id,['class'=>'form-control input-md required', 'id' => 'country_of_origin_id']) !!}
                                                        {!! $errors->first('country_of_origin_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('organization_type_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_type_id','Type of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('organization_type_id', $eaOrganizationType, $appInfo->organization_type_id, ['class' => 'form-control required input-md ','id'=>'organization_type_id']) !!}
                                                        {!! $errors->first('organization_type_id','<span class="help-block">:message</span>') !!}
                                                    </div>

                                                    <div style="margin-top: 10px;" class="col-md-12"
                                                         id="organization_type_other_div" hidden>
                                                        {!! Form::text('organization_type_other', $appInfo->organization_type_other, ['class'=>'form-control input-md', 'id' => 'organization_type_other', 'placeholder'=>'Specify others type']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('organization_status_id') ? 'has-error': ''}}">
                                                    {!! Form::label('organization_status_id','Status of the organization',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('organization_status_id', $eaOrganizationStatus, $appInfo->organization_status_id, ['class' => 'form-control required input-md ','id'=>'organization_status_id']) !!}
                                                        {!! $errors->first('organization_status_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ownership_status_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ownership_status_id','Ownership status',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ownership_status_id', $eaOwnershipStatus, $appInfo->ownership_status_id, ['class' => 'form-control required input-md ','id'=>'ownership_status_id']) !!}
                                                        {!! $errors->first('ownership_status_id','<span class="help-block">:message</span>') !!}
                                                    </div>

                                                    <div style="margin-top: 10px;" class="col-md-12"
                                                         id="ownership_status_other_div" hidden>
                                                        {!! Form::text('ownership_status_other', $appInfo->ownership_status_other, ['class'=>'form-control input-md', 'id' => 'ownership_status_other', 'placeholder'=>'Specify others status']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                                                    {!! Form::label('business_sector_id','Business sector',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {{--@if($viewMode == 'on')--}}
                                                        {{--<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">--}}
                                                        {{--{{$appInfo->sec_name }}--}}
                                                        {{--</span>--}}
                                                        {{--@else--}}
                                                        {!! Form::select('business_sector_id', $sectors, $appInfo->business_sector_id, ['class' => 'form-control bigInputField input-md','id'=>'business_sector_id', 'onchange'=>"LoadSubSector(this.value, 'SECTOR_OTHERS', 'business_sector_others', 'business_sub_sector_id',". $appInfo->business_sub_sector_id .")"]) !!}
                                                        {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}
                                                        {{--@endif--}}

                                                    </div>
                                                    <div style="margin-top: 10px;" class="col-md-12 maxTextCountDown"
                                                         id="SECTOR_OTHERS" hidden>
                                                        {!! Form::textarea('business_sector_others', $appInfo->business_sector_others, ['placeholder'=>'Specify others sector', 'class' => 'form-control bigInputField input-md',
                                                            'size'=>'5x1','maxlength'=>'200']) !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                                                    {!! Form::label('business_sub_sector_id','Sub sector',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {{--@if($viewMode == 'on')--}}
                                                        {{--<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">--}}
                                                        {{--{{$appInfo->sub_sec_name }}--}}
                                                        {{--</span>--}}
                                                        {{--@else--}}
                                                        {!! Form::select('business_sub_sector_id', $sub_sectors, $appInfo->business_sub_sector_id, ['class' => 'form-control bigInputField required input-md','id'=>'business_sub_sector_id', 'onchange'=>"SubSectorOthersDiv(this.value, 'SUB_SECTOR_OTHERS', 'business_sub_sector_others')"]) !!}
                                                        {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}
                                                        {{--@endif--}}

                                                    </div>
                                                    <div style="margin-top: 10px;" class="col-md-12 maxTextCountDown"
                                                         id="SUB_SECTOR_OTHERS" hidden>
                                                        {!! Form::textarea('business_sub_sector_others', $appInfo->business_sub_sector_others, ['placeholder'=>'Specify others sub-sector', 'class' => 'form-control bigInputField input-md',
                                                            'size'=>'5x1','maxlength'=>'200']) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group col-md-12 {{$errors->has('major_activities') ? 'has-error' : ''}}">
                                                    {!! Form::label('major_activities','Major activities in brief',['class'=>'col-md-3']) !!}
                                                    <div class="col-md-9 maxTextCountDown">
                                                        {!! Form::textarea('major_activities', $appInfo->major_activities, ['class' => 'bigInputField form-control input-md', 'size' =>'5x2','data-rule-maxlength'=>'200', 'placeholder' => 'Maximum 200 characters', 'maxlength' => '200']) !!}
                                                        {!! $errors->first('major_activities','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading margin-for-preview"><strong>B. Registration
                                            Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12 text-center {{$errors->has('is_registered') ? 'has-error': ''}}">
                                                    <label class="radio-inline">{!! Form::radio('is_registered','yes', ($appInfo->is_registered == 'yes' ? true : false), ['class'=>' required', 'onclick' => 'companyIsRegistered(this.value)']) !!}
                                                        Registered</label>
                                                    <label class="radio-inline">{!! Form::radio('is_registered', 'no', ($appInfo->is_registered == 'no' ? true : false), ['class'=>' required', 'onclick' => 'companyIsRegistered(this.value)']) !!}
                                                        Non-registered</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div id="registered_by_div">
                                                    <div class="col-md-6 {{$errors->has('registered_by_id') ? 'has-error': ''}}">
                                                        {!! Form::label('registered_by_id','Registered by',['class'=>'col-md-5 text-left required-star']) !!}
                                                        <div class="col-md-7">
                                                            @if($viewMode == 'on')
                                                                <span class="form-control input-md"
                                                                      style="background:#eee; height: auto;min-height: 30px;">
                                                                    <input type="hidden"
                                                                           value="{{$appInfo->registered_by_id}}"
                                                                           id="registered_by_id"/>
                                                                    {{$appInfo->reg_t_name }}
                                                                </span>
                                                            @else
                                                                {!! Form::select('registered_by_id', $eaRegistrationType, $appInfo->registered_by_id, ['class' => 'form-control required input-md ','id'=>'registered_by_id']) !!}
                                                                {!! $errors->first('registered_by_id','<span class="help-block">:message</span>') !!}
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6 {{$errors->has('registration_date') ? 'has-error' : ''}}">
                                                        {!! Form::label('registration_date','Date',['class'=>'col-md-5', 'id' => 'registration_date_label']) !!}
                                                        <div class=" col-md-7">
                                                            <div class="datepicker_registration_date input-group date"
                                                                 data-date-format="dd-mm-yyyy">
                                                                {!! Form::text('registration_date', $registration_date = (empty($appInfo->registration_date) ? '' : date('d-M-Y', strtotime($appInfo->registration_date))), ['class'=>'form-control input-md', 'id' => 'registration_date', 'placeholder'=>'Pick from datepicker']) !!}
                                                                <span class="input-group-addon"><span
                                                                            class="fa fa-calendar"></span></span>
                                                            </div>
                                                            {!! $errors->first('registration_date','<span class="help-block">:message</span>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div id="registration_no_div"
                                                     class="col-md-6 {{$errors->has('registration_no') ? 'has-error': ''}}"
                                                     hidden>
                                                    {!! Form::label('registration_no','Incorporation Certificate Number',['class'=>'col-md-5 text-left required-star', 'id' => 'registration_no_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('registration_no', $appInfo->registration_no, ['maxlength'=>'80',
                                                        'class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('registration_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="registered_by_other_div"
                                                     class="col-md-6 {{$errors->has('registered_by_other') ? 'has-error': ''}}">
                                                    {!! Form::label('registered_by_other','Registered by others',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7 maxTextCountDown">
                                                        {!! Form::textarea('registered_by_other',$appInfo->registered_by_other, ['class' => 'form-control input-md ','id'=>'registered_by_other','size'=>'7x3','maxlength' => '200']) !!}
                                                        {!! $errors->first('registered_by_other','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                                <div id="registration_copy_div"
                                                     class="form-group col-md-6 {{$errors->has('registration_copy') ? 'has-error' : ''}}">
                                                    {!! Form::label('registration_copy','Attach the copy of Registration/ Permission No.',['class'=>'col-md-5', 'id'=>'registration_copy_label']) !!}
                                                    <div class="col-md-7">
                                                        <input type="file" name="registration_copy"
                                                               class="form-control input-md" id="registration_copy"/>

                                                        {!! $errors->first('registration_copy','<span class="help-block">:message</span>') !!}
                                                        @if($viewMode != 'on')
                                                            <span class="text-danger"
                                                                  style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                                                        @endif

                                                        <div class="save_file" style="margin-top: 5px">
                                                            <?php if(!empty($appInfo->registration_copy)){ ?>
                                                            <a target="_blank" rel="noopener"
                                                               class="btn btn-xs btn-primary show-in-view"
                                                               title="Registration Copy"
                                                               href="{{URL::to('uploads/'.$appInfo->registration_copy)}}"><i
                                                                        class="fa fa-file-pdf-o"></i> Open File</a>
                                                            <input type="hidden" value="<?php
                                                            if ($appInfo->registration_copy != '') {
                                                                echo $appInfo->registration_copy;
                                                            }
                                                            ?>" id="registration_copy"
                                                                   name="registration_copy"/>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group" id="registration_other_div" hidden>
                                            <div class="row">
                                                <div class="form-group col-md-6 {{$errors->has('registration_other') ? 'has-error' : ''}}">
                                                    <div class="col-md-7 col-md-offset-5 maxTextCountDown">
                                                        {!! Form::textarea('registration_other', $appInfo->registration_other, ['class' => 'form-control input-md bigInputField', 'size' =>'5x2','data-rule-maxlength'=>'240', 'placeholder' => 'Maximum 240 characters', 'maxlength' => '200']) !!}
                                                        {!! $errors->first('registration_other','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div id="incorporation_certificate_number_div"
                                                     class="col-md-6 {{$errors->has('incorporation_certificate_number') ? 'has-error': ''}}">
                                                    {!! Form::label('incorporation_certificate_number','Incorporation Certificate Number',['class'=>'col-md-5 text-left', 'id' => 'incorporation_certificate_number']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('incorporation_certificate_number',$appInfo->incorporation_certificate_number, ['maxlength'=>'80',
                                                        'class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('incorporation_certificate_number','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="incorporation_certificate_date_div"
                                                     class="form-group col-md-6 {{$errors->has('incorporation_certificate_date') ? 'has-error' : ''}}">
                                                    {!! Form::label('incorporation_certificate_date','Date',['class'=>'col-md-5']) !!}
                                                    <div class=" col-md-7">
                                                        <div class="datepicker input-group date"
                                                             data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('incorporation_certificate_date',$registration_date = (empty($appInfo->incorporation_certificate_date) ? '' : date('d-M-Y', strtotime($appInfo->incorporation_certificate_date))), ['class'=>'form-control input-md', 'id' => 'incorporation_certificate_date', 'placeholder'=>'Pick from datepicker']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('incorporation_certificate_date','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>C. Information of Principal Promoter/ Chairman/
                                            Managing Director/ State CEO</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_country_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_country_id','Country ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_country_id', $countries, $appInfo->ceo_country_id, ['class' => 'form-control required input-md ','id'=>'ceo_country_id']) !!}
                                                        {!! $errors->first('ceo_country_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_dob') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_dob','Date of Birth',['class'=>'col-md-5']) !!}
                                                    <div class=" col-md-7">
                                                        <div class="datepicker input-group date"
                                                             data-date-format="dd-mm-yyyy">
                                                            {!! Form::text('ceo_dob', (empty($appInfo->ceo_dob) ? '' : date('d-M-Y', strtotime($appInfo->ceo_dob))), ['class'=>'form-control input-md', 'id' => 'ceo_dob', 'placeholder'=>'Pick from datepicker']) !!}
                                                            <span class="input-group-addon"><span
                                                                        class="fa fa-calendar"></span></span>
                                                        </div>
                                                        {!! $errors->first('ceo_dob','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div id="ceo_passport_div"
                                                     class="col-md-6 hidden {{$errors->has('ceo_passport_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_passport_no','Passport No.',['class'=>'col-md-5 ']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_passport_no', $appInfo->ceo_passport_no, ['maxlength'=>'20',
                                                        'class' => 'form-control input-md', 'id'=>'ceo_passport_no']) !!}
                                                        {!! $errors->first('ceo_passport_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_nid_div"
                                                     class="col-md-6 {{$errors->has('ceo_nid') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_nid','NID No.',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_nid', $appInfo->ceo_nid, ['maxlength'=>'20',
                                                        'class' => 'form-control input-md required bd_nid','id'=>'ceo_nid']) !!}
                                                        {!! $errors->first('ceo_nid','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_designation','Designation', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_designation', $appInfo->ceo_designation,
                                                        ['maxlength'=>'80','class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_full_name','Full Name',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_full_name', $appInfo->ceo_full_name, ['maxlength'=>'80',
                                                        'class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_district_div"
                                                     class="col-md-6 {{$errors->has('ceo_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_district_id','District/ City/ State ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_district_id', $districts, $appInfo->ceo_district_id, ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('ceo_district_id', this.value, 'ceo_thana_id', ". $appInfo->ceo_thana_id .")"]) !!}
                                                        {!! $errors->first('ceo_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_city_div"
                                                     class="col-md-6 hidden {{$errors->has('ceo_city') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_city','District/ City/ State',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_city', $appInfo->ceo_city,['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_city','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div id="ceo_state_div"
                                                     class="col-md-6 hidden {{$errors->has('ceo_state') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_state','State/ Province',['class'=>'text-left  col-md-5 required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_state', $appInfo->ceo_state,['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_state','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div id="ceo_thana_div"
                                                     class="col-md-6 {{$errors->has('ceo_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_thana_id','Police Station/ Town ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('ceo_thana_id', [''], $appInfo->ceo_thana_id, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_post_code','Post/ Zip Code ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_post_code', $appInfo->ceo_post_code, ['maxlength'=>'80','class' => 'form-control input-md required engOnly']) !!}
                                                        {!! $errors->first('ceo_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_address') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_address', $appInfo->ceo_address, ['maxlength'=>'150','class' => 'bigInputField form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_telephone_no', $appInfo->ceo_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('ceo_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mobile_no', $appInfo->ceo_mobile_no, ['class' => 'form-control input-md required phone_or_mobile','id' => 'ceo_mobile_no']) !!}
                                                        {!! $errors->first('ceo_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_father_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_father_name','Father\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_father_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_father_name', $appInfo->ceo_father_name, ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('ceo_father_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_email') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('ceo_email', $appInfo->ceo_email, ['class' => 'form-control email input-md required']) !!}
                                                        {!! $errors->first('ceo_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_mother_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_mother_name','Mother\'s Name',['class'=>'col-md-5 text-left required-star', 'id' => 'ceo_mother_label']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_mother_name', $appInfo->ceo_mother_name, ['class' => 'form-control required input-md']) !!}
                                                        {!! $errors->first('ceo_mother_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('ceo_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_fax_no', $appInfo->ceo_fax_no, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('ceo_spouse_name') ? 'has-error': ''}}">
                                                    {!! Form::label('ceo_spouse_name','Spouse name',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('ceo_spouse_name', $appInfo->ceo_spouse_name, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('ceo_spouse_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h3 class="text-center stepHeader">Organization information</h3>
                            <fieldset>
                                <legend class="d-none">Organization information</legend>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>D. Office Address</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_division_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_division_id','Division',['class'=>'text-left required-star col-md-5']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_division_id', $divisions, $appInfo->office_division_id, ['class' => 'form-control required input-md', 'id' => 'office_division_id', 'onchange'=>"getDistrictByDivisionId('office_division_id', this.value, 'office_district_id', ". $appInfo->office_district_id .")"]) !!}
                                                        {!! $errors->first('office_division_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_district_id','District',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_district_id', $districts, $appInfo->office_district_id, ['class' => 'form-control input-md required','placeholder' => 'Select division first', 'onchange'=>"getThanaByDistrictId('office_district_id', this.value, 'office_thana_id', ". $appInfo->office_thana_id .")"]) !!}
                                                        {!! $errors->first('office_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('office_thana_id','Police Station', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('office_thana_id',[''], $appInfo->office_thana_id, ['class' => 'form-control input-md required']) !!}
                                                        {!! $errors->first('office_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_office','Post Office',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_office', $appInfo->office_post_office, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('office_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('office_post_code','Post Code', ['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_post_code', $appInfo->office_post_code, ['class' => 'form-control input-md required post_code_bd']) !!}
                                                        {!! $errors->first('office_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_address') ? 'has-error': ''}}">
                                                    {!! Form::label('office_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_address', $appInfo->office_address, ['maxlength'=>'150','class' => 'bigInputField form-control input-md required']) !!}
                                                        {!! $errors->first('office_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('office_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_telephone_no', $appInfo->office_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('office_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('office_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_mobile_no', $appInfo->office_mobile_no, ['class' => 'form-control input-md required phone_or_mobile','id' => 'office_mobile_no']) !!}
                                                        {!! $errors->first('office_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('office_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('office_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('office_fax_no', $appInfo->office_fax_no, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('office_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('office_email') ? 'has-error': ''}}">
                                                    {!! Form::label('office_email','Email ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('office_email', $appInfo->office_email, ['class' => 'form-control email input-md required']) !!}
                                                        {!! $errors->first('office_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>E. Factory Address (Optional)</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_district_id') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_district_id','District',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('factory_district_id', $districts, $appInfo->factory_district_id, ['class' => 'form-control input-md', 'onchange'=>"getThanaByDistrictId('factory_district_id', this.value, 'factory_thana_id', ". $appInfo->factory_thana_id .")"]) !!}
                                                        {!! $errors->first('factory_district_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_thana_id') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_thana_id','Police Station', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::select('factory_thana_id', [''], $appInfo->factory_thana_id, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_thana_id','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_post_office') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_post_office','Post Office',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_post_office', $appInfo->factory_post_office, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_post_office','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_post_code') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_post_code','Post Code', ['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_post_code', $appInfo->factory_post_code, ['class' => 'form-control input-md number']) !!}
                                                        {!! $errors->first('factory_post_code','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_address') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_address','House, Flat/ Apartment, Road ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_address', $appInfo->factory_address, ['maxlength'=>'150','class' => 'bigInputField form-control input-md']) !!}
                                                        {!! $errors->first('factory_address','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_telephone_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_telephone_no','Telephone No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_telephone_no', $appInfo->factory_telephone_no, ['maxlength'=>'20','class' => 'form-control input-md phone_or_mobile']) !!}
                                                        {!! $errors->first('factory_telephone_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_mobile_no', $appInfo->factory_mobile_no, ['class' => 'form-control input-md phone_or_mobile','id' => 'factory_mobile_no']) !!}
                                                        {!! $errors->first('factory_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_fax_no') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_fax_no','Fax No. ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_fax_no', $appInfo->factory_fax_no, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_fax_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('factory_email') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_email','Email ',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_email', $appInfo->factory_email, ['class' => 'form-control email input-md']) !!}
                                                        {!! $errors->first('factory_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('factory_mouja') ? 'has-error': ''}}">
                                                    {!! Form::label('factory_mouja','Mouja No.',['class'=>'col-md-5 text-left']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('factory_mouja', $appInfo->factory_mouja, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('factory_mouja','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-striped table-bordered"
                                                                width="100%" aria-label="Detailed Manpower
                                                                    of the organization Data Table">
                                                            <thead class="alert alert-info">
                                                            <tr>
                                                                <th class="text-center text-title" colspan="9" scope="col">Manpower
                                                                    of the organization
                                                                </th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="manpower">
                                                            <tr>
                                                                <th class="alert alert-info" colspan="3" scope="col">Local
                                                                    (Bangladesh only)
                                                                </th>
                                                                <th class="alert alert-info" colspan="3" scope="col">Foreign (Abroad
                                                                    country)
                                                                </th>
                                                                <th class="alert alert-info" colspan="1" scope="col">Grand total
                                                                </th>
                                                                <th class="alert alert-info" colspan="2" scope="col">Ratio</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="alert alert-info" scope="col">Executive</th>
                                                                <th class="alert alert-info" scope="col">Supporting staff</th>
                                                                <th class="alert alert-info" scope="col">Total (a)</th>
                                                                <th class="alert alert-info" scope="col">Executive</th>
                                                                <th class="alert alert-info" scope="col">Supporting staff</th>
                                                                <th class="alert alert-info" scope="col">Total (b)</th>
                                                                <th class="alert alert-info" scope="col"> (a+b)</th>
                                                                <th class="alert alert-info" scope="col">Local</th>
                                                                <th class="alert alert-info" scope="col">Foreign</th>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    {!! Form::text('local_executive', $appInfo->local_executive, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_executive']) !!}
                                                                    {!! $errors->first('local_executive','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_stuff', $appInfo->local_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_stuff']) !!}
                                                                    {!! $errors->first('local_stuff','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('local_total', $appInfo->local_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'local_total','readonly']) !!}
                                                                    {!! $errors->first('local_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_executive', $appInfo->foreign_executive, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_executive']) !!}
                                                                    {!! $errors->first('foreign_executive','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_stuff', $appInfo->foreign_stuff, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_stuff']) !!}
                                                                    {!! $errors->first('foreign_stuff','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('foreign_total', $appInfo->foreign_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'foreign_total','readonly']) !!}
                                                                    {!! $errors->first('foreign_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_total', $appInfo->manpower_total, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_total','readonly']) !!}
                                                                    {!! $errors->first('manpower_total','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_local_ratio', $appInfo->manpower_local_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_local','readonly']) !!}
                                                                    {!! $errors->first('manpower_local_ratio','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                                <td>
                                                                    {!! Form::text('manpower_foreign_ratio', $appInfo->manpower_foreign_ratio, ['data-rule-maxlength'=>'40','class' => 'form-control input-md mp_req_field number','id'=>'mp_ratio_foreign','readonly']) !!}
                                                                    {!! $errors->first('manpower_foreign_ratio','<span class="help-block">:message</span>') !!}
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            <h3 class="text-center stepHeader">Attachments</h3>
                            <fieldset>
                                <legend class="d-none">Attachments</legend>
                                <div id="docListDiv">
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><strong>F. Necessary documents to be attached here
                                                (Only PDF file to be attach here)</strong>
                                        </div>
                                        <div class="panel-body">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-hover" aria-label="Detailed Attachments Data Table">
                                                    <thead>
                                                    <tr>
                                                        <th>No.</th>
                                                        <th colspan="6">Required attachments</th>
                                                        <th colspan="2">Attached PDF file (Each File Maximum size 2MB)
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $i = 1; ?>
                                                    @if(count($document) > 0)
                                                        @foreach($document as $row)
                                                            <tr>
                                                                <td>
                                                                    <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                                                                </td>
                                                                <td colspan="6">{!!  $row->doc_name !!}</td>
                                                                <td colspan="2">
                                                                    <input name="document_id_<?php echo $row->id; ?>"
                                                                           type="hidden"
                                                                           value="{{(!empty($row->document_id) ? $row->document_id : '')}}">
                                                                    <input type="hidden" value="{!!  $row->doc_name !!}"
                                                                           id="doc_name_<?php echo $row->id; ?>"
                                                                           name="doc_name_<?php echo $row->id; ?>"/>


                                                                    <input name="is_old_file_<?php echo $row->id; ?>"
                                                                           value="{{$row->is_old_file}}" type="hidden"
                                                                           id="is_old_file_<?php echo $row->id; ?>"/>
                                                                    @if($row->is_old_file == 0 && $viewMode != 'on')
                                                                        <input name="file<?php echo $row->id; ?>"
                                                                               <?php if (empty($row->doc_file_path) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                                                                   echo "class='required'";
                                                                               } ?>
                                                                               id="file<?php echo $row->id; ?>"
                                                                               type="file" size="20"
                                                                               onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>
                                                                    @endif

                                                                    {{--additional field area--}}
                                                                    @if($row->additional_field == 1)
                                                                        <table aria-label="Detailed Data Table">
                                                                            <tr>
                                                                                <th aria-hidden="true" scope="col"></th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Other file Name :</td>
                                                                                <td><input maxlength="64"
                                                                                           class="form-control input-sm <?php if ($row->doc_priority == "1") {
                                                                                               echo 'required';
                                                                                           } ?>"
                                                                                           name="other_doc_name_<?php echo $row->id; ?>"
                                                                                           type="text"
                                                                                           value="{{(!empty($row->doc_name) ? $row->doc_name : '')}}">
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    @endif


                                                                    {{-- if this document hav attachment then show it --}}
                                                                    @if(!empty($row->doc_file_path))
                                                                        <div class="save_file saved_file_{{$row->id}}">
                                                                            <a target="_blank" rel="noopener"
                                                                               class="btn btn-xs btn-primary documentUrl"
                                                                               href="{{URL::to('/uploads/'.(!empty($row->doc_file_path) ? $row->doc_file_path : ''))}}"
                                                                               title="{{$row->doc_name}}">
                                                                                <i class="fa fa-file-pdf-o"
                                                                                   aria-hidden="true"></i>
                                                                                Open File
                                                                            </a>
                                                                            @if($row->is_old_file == 1)
                                                                                <small class="text-danger"
                                                                                       style="display: block"><i
                                                                                            class="fa fa-info-circle"></i>
                                                                                    The document has come from Basic
                                                                                    information and is not changeable.
                                                                                </small>
                                                                            @endif

                                                                            <?php if($viewMode != 'on' && $row->is_old_file == 0) {?>
                                                                            <a href="javascript:void(0)"
                                                                               onclick="removeAttachedFile({!! $row->id !!}, {!! $row->doc_priority !!})"><span
                                                                                        class="btn btn-xs btn-danger"><i
                                                                                            class="fa fa-times"></i></span>
                                                                            </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    @endif

                                                                    <div id="preview_<?php echo $row->id; ?>">
                                                                        <input type="hidden"
                                                                               value="<?php echo !empty($row->doc_file_path) ?
                                                                                   $row->doc_file_path : ''?>"
                                                                               id="validate_field_<?php echo $row->id; ?>"
                                                                               name="validate_field_<?php echo $row->id; ?>"
                                                                               class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                                                                    </div>

                                                                    @if(!empty($allRequestVal["file$row->id"]))
                                                                        <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                                                                        <input type="hidden" class="required"
                                                                               value="{{$allRequestVal["validate_field_".$row->id]}}"
                                                                               id="validate_field_{{$row->id}}"
                                                                               name="validate_field_{{$row->id}}">
                                                                    @endif

                                                                </td>
                                                            </tr>
                                                            <?php $i++; ?>
                                                        @endforeach
                                                    @else
                                                        <tr>
                                                            <td colspan="9" style="text-align: center"><span
                                                                        class="label label-info">No Required Documents!</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    <tr>
                                                        <td>N.B</td>
                                                        <td colspan="6">All documents shall have to be attested by the
                                                            Chairman/ CEO / Managing dirctor/ Country Manager/ Chief
                                                            executive of the Company/ firms.
                                                        </td>
                                                        <td colspan="2">Document's must be submitted by an authorized
                                                            person of the organization including the letter of
                                                            authorization.
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($viewMode != 'off')
                                    @include('BasicInformation::doc-tab')
                                @endif
                            </fieldset>


                            <h3>Declaration & Submit</h3>
                            <fieldset>
                                <legend class="d-none">Declaration & Submit</legend>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Authorized Persons Information</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('auth_full_name') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_full_name','Full Name ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_full_name', $appInfo->auth_full_name, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('auth_full_name','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('auth_designation') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_designation','Designation ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_designation', $appInfo->auth_designation, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('auth_designation','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-6 {{$errors->has('auth_mobile_no') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_mobile_no','Mobile No. ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::text('auth_mobile_no', $appInfo->auth_mobile_no, ['class' => 'form-control input-md phone_or_mobile', 'id' => 'auth_mobile_no']) !!}
                                                        {!! $errors->first('auth_mobile_no','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                                <div class="col-md-6 {{$errors->has('auth_email') ? 'has-error': ''}}">
                                                    {!! Form::label('auth_email','Email address ',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">
                                                        {!! Form::email('auth_email', $appInfo->auth_email, ['class' => 'form-control input-md']) !!}
                                                        {!! $errors->first('auth_email','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="form-group col-md-6 {{$errors->has('auth_letter') ? 'has-error' : ''}}">
                                                    {!! Form::label('auth_letter','Authorization Letter',['class'=>'col-md-5 text-left required-star']) !!}
                                                    <div class="col-md-7">

                                                        {{--<input type="hidden" name="auth_letter" value="{{ Auth::user()->authorization_file }}">--}}
                                                        {{--<input type="file" name="auth_letter" class="form-control input-md {{ (empty($appInfo->auth_letter) ? 'required' : '') }}"/>--}}
                                                        <input type="file" name="auth_letter"
                                                               class="form-control input-md"/>
                                                        {!! $errors->first('auth_letter','<span class="help-block">:message</span>') !!}

                                                        {{--<a href="/assets/images/sample_auth_letter.png" target="_blank"> <i class="fa  fa-file-pdf-o"></i> Sample Authorization Letter </a><br/>--}}

                                                        @if($viewMode != 'on')
                                                            <span class="text-danger"
                                                                  style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                                                        @endif

                                                        @if(!empty($appInfo->auth_letter))

                                                            <div class="save_file" style="margin-top: 5px">
                                                                <?php if(!empty($appInfo->auth_letter)){ ?>
                                                                <a target="_blank" rel="noopener"
                                                                   class="btn btn-xs btn-primary show-in-view"
                                                                   title="Authorization Letter"
                                                                   href="{{URL::to('users/upload/'.$appInfo->auth_letter)}}"><i
                                                                            class="fa fa-file-pdf-o"></i> Authorization
                                                                    Letter</a>
                                                                <input type="hidden" value="<?php
                                                                if ($appInfo->auth_letter != '') {
                                                                    echo $appInfo->auth_letter;
                                                                }
                                                                ?>" id="auth_letter"
                                                                       name="auth_letter"/>
                                                                <?php } ?>
                                                            </div>

                                                        @else
                                                            {{--Old Authorization letter--}}
                                                            @if(!empty(Auth::user()->authorization_file))
                                                                <input type="hidden" name="old_auth_letter"
                                                                       value="{{ Auth::user()->authorization_file }}">
                                                                <a href="{{ URL::to('users/upload/'.Auth::user()->authorization_file) }}"
                                                                   target="_blank" rel="noopener"
                                                                   class="btn btn-xs btn-primary show-in-view"
                                                                   title="Old Authorization Letter"
                                                                   style="margin-top: 5px;">
                                                                    <i class="fa  fa-file-pdf-o"></i>
                                                                    Old Authorization Letter
                                                                </a><br/>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-6 {{$errors->has('auth_image') ? 'has-error': ''}}">
                                                    <div class="col-sm-9">
                                                        {!! Form::label('auth_image','Profile Picture', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        {!! $errors->first('auth_image','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <img class="img-thumbnail" id="authImageViewer"
                                                             src="{{ $appInfo->auth_image != '' ? url('users/upload/'.$appInfo->auth_image) : url('assets/images/photo_default.png') }}"
                                                             alt="Auth Image">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">

                                                <div class="col-md-6 {{$errors->has('auth_signature') ? 'has-error': ''}}">
                                                    <div class="col-sm-9">
                                                        {!! Form::label('auth_signature','Signature', ['class'=>'text-left required-star','style'=>'']) !!}
                                                        {!! $errors->first('auth_signature','<span class="help-block">:message</span>') !!}
                                                    </div>
                                                    <div class="col-md-3">
                                                        <img class="img-thumbnail" id="authSignatureViewer"
                                                             src="{{ $appInfo->auth_signature != '' ? url('users/signature/'.$appInfo->auth_signature) : url('assets/images/photo_default.png') }}"
                                                             alt="Auth Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div><!-- /.panel-body -->
                                </div>
                                <div class="panel panel-info">
                                    <div class="panel-heading"><strong>Terms and Conditions</strong></div>
                                    <div class="panel-body">
                                        <div class="form-group">
                                            <div class="col-md-12 {{$errors->has('acceptTerms') ? 'has-error' : ''}}">
                                                {!! Form::checkbox('acceptTerms', 'yes', $appInfo->acceptTerms, array('class'=>'required col-md-1 col-xs-1 col-sm-1 text-left','id'=>'acceptTerms-2','style'=>'width:3%;margin-left: 2px;')) !!}
                                                <label for="acceptTerms-2"
                                                       class="col-md-11 col-xs-11 col-sm-11 text-left required-star">I
                                                    do here by declare that the information given above is true to the
                                                    best of my knowledge and I shall be liable for any false
                                                    information/ statement is given. </label>
                                                <div class="clearfix"></div>
                                                {!! $errors->first('acceptTerms','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>

                            @if(ACL::getAccsessRight('BasicInformation','-E-') && $viewMode != "on" && $appInfo->status_id != 6 && Auth::user()->user_type == '5x505')
                                @if(!in_array($appInfo->status_id,[5]))
                                    <button type="submit" class="btn btn-info btn-md cancel"
                                            value="draft" name="actionBtn">Save as Draft
                                    </button>
                                @endif
                                @if($appInfo->status_id == 5)
                                    <div class="pull-left">
                                        <button type="submit" id="" style="cursor: pointer;" class="btn btn-info btn-md"
                                                value="Submit" name="actionBtn">Re-submit
                                        </button>
                                    </div>
                                @endif
                            @else
                                <style>
                                    .wizard > .actions {
                                        top: -15px !important;
                                    }
                                </style>
                            @endif
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                {{--End application form with wizard--}}
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<script src="{{ asset('assets/scripts/jquery.steps.js') }}"></script>

<script type="text/javascript">

    @if($viewMode != 'on')
    //--------Step Form init+validation Start----------//
    var form = $("#basicInformationForm").show();
    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function (event, currentIndex, newIndex) {
            if (newIndex == 1) {
            }
            // Always allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }
            // Forbid next action on "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                return false;
            }
            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function (event, currentIndex, priorIndex) {

        },
        onFinishing: function (event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            console.log(form.validate().errors()); // show hidden errors in last step
            return form.valid();
        },
        onFinished: function (event, currentIndex) {
            errorPlacement: function errorPlacement(error, element) {
                element.before(error);
            }
        }
    });
    //--------Step Form init+validation End----------//
            @endif

    var popupWindow = null;
    $('.finish').on('click', function (e) {
        if (form.valid()) {
            $('body').css({"display": "none"});
            popupWindow = window.open('<?php echo URL::to('/basic-information/preview'); ?>', 'Sample', '');
        } else {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        }
    });


    //--------File Upload Script Start----------//
    function uploadDocument(targets, id, vField, isRequired) {
        var file_id = document.getElementById(id);
        var file = file_id.files;
        if (file && file[0]) {
            if (!(file[0].type == 'application/pdf')) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_id.value = '';
                return false;
            }

            var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 2)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                });
                file_id.value = '';
                return false;
            }
        }

        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/basic-information/upload-document')}}";

            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    //console.log(response);
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyFile(' + doc_id
                        + ', '+ isRequired +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field != '') {
                        $("#"+id).removeClass('required');
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    //--------File Upload Script End----------//

    //------- Manpower start -------//
    $('#manpower').find('input').keyup(function () {
        var local_executive = $('#local_executive').val() ? parseFloat($('#local_executive').val()) : 0;
        var local_stuff = $('#local_stuff').val() ? parseFloat($('#local_stuff').val()) : 0;
        var local_total = parseInt(local_executive + local_stuff);
        $('#local_total').val(local_total);


        var foreign_executive = $('#foreign_executive').val() ? parseFloat($('#foreign_executive').val()) : 0;
        var foreign_stuff = $('#foreign_stuff').val() ? parseFloat($('#foreign_stuff').val()) : 0;
        var foreign_total = parseInt(foreign_executive + foreign_stuff);
        $('#foreign_total').val(foreign_total);

        var mp_total = parseInt(local_total + foreign_total);
        $('#mp_total').val(mp_total);

        var mp_ratio_local = parseFloat(local_total / mp_total);
        var mp_ratio_foreign = parseFloat(foreign_total / mp_total);

//            mp_ratio_local = Number((mp_ratio_local).toFixed(3));
//            mp_ratio_foreign = Number((mp_ratio_foreign).toFixed(3));

        //---------- code from bida old
        mp_ratio_local = ((local_total / mp_total) * 100).toFixed(2);
        mp_ratio_foreign = ((foreign_total / mp_total) * 100).toFixed(2);
        if (foreign_total == 0) {
            mp_ratio_local = local_total;
        } else {
            mp_ratio_local = Math.round(parseFloat(local_total / foreign_total) * 100) / 100;
        }
        mp_ratio_foreign = (foreign_total != 0) ? 1 : 0;
        // End of code from bida old -------------

        $('#mp_ratio_local').val(mp_ratio_local);
        $('#mp_ratio_foreign').val(mp_ratio_foreign);
    });

    function companyIsRegistered(is_registered) {
        if (is_registered == 'yes') {
            $("#registered_by_div").show('slow');

            $("#registered_by_other_div").hide('slow');
            $("#registration_copy_div").hide('slow');
            $("#incorporation_certificate_number_div").hide('slow');
            $("#incorporation_certificate_date_div").hide('slow');

            $('#registered_by_id').addClass('required');
            $('#registered_by_id').trigger('change');
//            $("#incorporation_certificate_date_div").show('slow');
        } else if (is_registered == 'no') {
            $("#registered_by_other_div").show('slow');
            $("#registration_copy_div").show('slow');
            $("#registration_copy_label").removeClass('required-star');
            $("#registration_copy").removeClass('required');
            $("#registration_copy").removeClass('error');
            $("#registration_date").removeClass('required');
            $('#registered_by_id').removeClass('required');
            $('#registration_no').removeClass('required');
            $("#incorporation_certificate_number_div").hide('slow');
            $("#incorporation_certificate_date_div").hide('slow');

            $("#registered_by_div").hide();
            $("#registration_no_div").hide();
            $("#registration_other_div").hide();
        } else {
            $("#registered_by_div").hide();
            $("#registered_by_other_div").hide();
            $("#registration_copy_div").hide();
            $("#incorporation_certificate_number_div").hide();
            $("#incorporation_certificate_date_div").hide();
            $("#registration_no_div").hide();
            $("#registration_other_div").hide();
        }
    }

    // 1 = BIDA, 3 = RJSC, 4 = Branch/Liaison/Representative Offices, 12 = Others
    $('#registered_by_id').change(function (e) {
        var type = this.value;

        if (type == 1) {
            $("#registration_no_label").html('Registration Number');
        } else {
            $("#registration_no_label").html('Incorporation Certificate Number');
        }

        if (type == 1 || type == 3) {
            $('#registration_date').addClass('required');
            $('#registration_date_label').addClass('required-star');
        } else {
            $('#registration_date').removeClass('required');
            $('#registration_date_label').removeClass('required-star');
        }


        if (type == 1 || type == 3 || type == 4) {
            $('#registration_no_div').show('slow');
            $('#registration_no').addClass('required');
            $('#registration_copy_div').show('slow');
            $("#registration_copy_label").addClass('required-star');
            $("#registration_copy_label").html('Attach the copy of Certificate');

            var registration_copy_is_required = '{{ (empty($appInfo->registration_copy)) ? 'required' : '' }}';
            $('#registration_copy').addClass(registration_copy_is_required);

            $('#registration_other_div').hide('slow');
            //$('.registrationFieldRequired').addClass('required');
        } else if (type == 12) {
            $('#registration_other_div').show('slow');
            $('#registration_no_div').hide('slow');
            $('#registration_no').removeClass('required');
            $('#registration_copy_div').hide('slow');
            $("#registration_copy_label").removeClass('required-star');
            $("#registration_copy_label").html('Attach the copy of Registration/Permission No.');
            $('#registration_copy').removeClass('required');
        } else {
            $('#registration_no_div').hide('slow');
            $('#registration_no').removeClass('required');
            $('#registration_copy_div').hide('slow');
            $("#registration_copy_label").removeClass('required-star');
            $("#registration_copy_label").html('Attach the copy of Registration/Permission No.');
            $('#registration_copy').removeClass('required');
            $('#registration_other_div').hide('slow');
            //$('.registrationFieldRequired').removeClass('required');
        }
    });

    $(document).ready(function () {
        // Registration Information function
        companyIsRegistered();
        $('input[name=registered_by_id]:checked').trigger('change');

        $("#business_sector_id").trigger('change');
        //$("#business_sub_sector_id").trigger('change');
        // BD Office address
        $("#office_division_id").trigger('change');
        $("#office_district_id").trigger('change');
        $("#factory_district_id").trigger('change');
        $("#ceo_district_id").trigger('change');
        $('input[name=is_registered]:checked').trigger('click');

        // ceo country, city district, state thana, father, mother
        $('#ceo_country_id').change(function (e) {
            var country_id = this.value;
            if (country_id == '18') {
                $("#ceo_city_div").addClass('hidden');
                $("#ceo_city").removeClass('required');
                $("#ceo_state_div").addClass('hidden');
                $("#ceo_state").removeClass('required');
                $("#ceo_passport_div").addClass('hidden');
//                $("#ceo_passport_no").removeClass('required');


                $("#ceo_district_div").removeClass('hidden');
                $("#ceo_district_id").addClass('required');
                $("#ceo_thana_div").removeClass('hidden');
                $("#ceo_thana_id").addClass('required');
                $("#ceo_nid_div").removeClass('hidden');
                $("#ceo_nid").addClass('required');

                $("#ceo_father_label").addClass('required-star');
                $("#ceo_father_name").addClass('required');
                $("#ceo_mother_label").addClass('required-star');
                $("#ceo_mother_name").addClass('required');
            } else {
                $("#ceo_city_div").removeClass('hidden');
                $("#ceo_city").addClass('required');
                $("#ceo_state_div").removeClass('hidden');
                $("#ceo_state").addClass('required');
                $("#ceo_passport_div").removeClass('hidden');
//                $("#ceo_passport_no").addClass('required');

                $("#ceo_district_div").addClass('hidden');
                $("#ceo_district_id").removeClass('required');
                $("#ceo_thana_div").addClass('hidden');
                $("#ceo_thana_id").removeClass('required');
                $("#ceo_nid_div").addClass('hidden');
                $("#ceo_nid").removeClass('required');

                $("#ceo_father_label").removeClass('required-star');
                $("#ceo_father_name").removeClass('required');
                $("#ceo_mother_label").removeClass('required-star');
                $("#ceo_mother_name").removeClass('required');
            }
        });
        $('#ceo_country_id').trigger('change');

        // Type of the organization
        $("#organization_type_id").change(function (e) {
            var org_type_id = this.value;
            if (org_type_id == '0') {
                $("#organization_type_other_div").show();
                $("#organization_type_other").addClass('required');
            } else {
                $("#organization_type_other_div").hide();
                $("#organization_type_other").removeClass('required');
            }
        });
        $('#organization_type_id').trigger('change');

        // Ownership status
        $("#ownership_status_id").change(function (e) {
            var ownership_id = this.value;
            if (ownership_id == '0') {
                $("#ownership_status_other_div").show();
                $("#ownership_status_other").addClass('required');
            } else {
                $("#ownership_status_other_div").hide();
                $("#ownership_status_other").removeClass('required');
            }
        });
        $('#ownership_status_id').trigger('change');


        var today = new Date();
        var yyyy = today.getFullYear();

        $('.datepicker_registration_date').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
            minDate: '01/01/' + (yyyy - 150),
            maxDate: today,
        });

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY',
//            minDate: '01/01/'+(yyyy-10),
//            maxDate: '01/01/'+(yyyy+10)
            maxDate: 'now'
        });

    });


    @if ($viewMode == 'on')
    $('#basicInformationForm :input').attr('disabled', true);
    $('#basicInformationForm h3').hide();
    // for those field which have huge content, e.g. Address Line 1
    $('.bigInputField').each(function () {
        //console.log($(this)[0]['localName']);
        if ($(this)[0]['localName'] == 'select') {
            //var text = $(this).find('option:selected').text();
            //var val = jQuery(this).val();
            //$(this).find('option:selected').replaceWith("<option value='" + val + "' selected>" + text + "</option>");

            // This style will not work in mozila firefox, it's bug in firefox, maybe they will update it in next version
            $(this).attr('style', '-webkit-appearance: button; -moz-appearance: button; -webkit-user-select: none; -moz-user-select: none; text-overflow: ellipsis; white-space: pre-wrap; height: auto;');
        } else {
            $(this).replaceWith('<span class="form-control input-md" style="background:#eee; height: auto;min-height: 30px;">' + this.value + '</span>');
        }
    });
    // all radio or checkbox which is not checked, that will be hide
    $('#basicInformationForm :input[type=radio], input[type=checkbox]').each(function () {
        if (!$(this).is(":checked")) {
            //alert($(this).attr('name'));
            $(this).parent().replaceWith('');
            $(this).replaceWith('');
        }
    });
    $('#basicInformationForm :input[type=file]').hide();
    $('.addTableRows').hide();
    @endif // viewMode is on

</script>
<script src="{{ asset("assets/scripts/custom.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/intlTelInput-jquery_v16.0.8.min.js") }}" type="text/javascript"></script>
<script src="{{ asset("build/js/utils_v16.0.8.js") }}" type="text/javascript"></script>
<script>

    {{--initail -input plugin script start--}}
    $(function () {
        $("#ceo_telephone_no").intlTelInput({
            hiddenInput: "ceo_telephone_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#auth_mobile_no").intlTelInput({
            hiddenInput: "auth_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#office_mobile_no").intlTelInput({
            hiddenInput: "office_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });

        $("#factory_mobile_no").intlTelInput({
            hiddenInput: "factory_mobile_no",
            initialCountry: "BD",
            placeholderNumberType: "MOBILE",
            separateDialCode: true,
        });
    });
    {{--initail -input plugin script end--}}
</script>

{{--//textarea coun down--}}
@if ($viewMode != 'on')
    <script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
    <script>
        $('.maxTextCountDown').maxlength();
    </script>
@endif