<?php
$moduleName = Request::segment(1);
$proecss_type_id = Request::segment(3);

$tokenCacheKey = 'insightdb_api_token';
if (Cache::has($tokenCacheKey)) {
    $insightdb_api_token = Cache::get($tokenCacheKey);
}else {
    $insightdb_api_token = '';
}
$cacheToken = $insightdb_api_token;
?>
<style>
    input[type="radio"] {
        -webkit-appearance: checkbox;
        /* Chrome, Safari, Opera */
        -moz-appearance: checkbox;
        /* Firefox */
        -ms-appearance: checkbox;
        /* not currently supported */
    }

    input[type=file]:-moz-read-only {
        padding: 0;
    }
    /* #remarks {
           opacity: 0.5;
           position: relative;
           height: auto !important;
           z-index: 1;
       }*/

    #remarks {
        color: #adadad;
        /* height: auto !important; */
        z-index: 1;
        width: 100%;
    }
    #mainInput {
        opacity: 1;
        background: transparent;
        position: absolute;
        left: 0;
        /* height: auto !important; */
        width: 100%;
        z-index: 2;
    }
    #suggestion-list, #status-list {
        top: calc(100% + 5px);
        left: 0;
        width: 100%;
        border-top: none;
        border-radius: 5px;
        display: none;
        white-space: nowrap;
        overflow-x: auto;
        flex-wrap: wrap;
        transition: all 0.3s;
    }
    .suggestion-item, .status-item {
        display: inline-block;
        padding: 5px 10px;
        cursor: pointer;
        margin: 5px 5px 10px 5px;
        border-radius: 20px;
        background-color: #f1f1f1;
    }
    .suggestion-item:hover, .status-item:hover {
        background-color: #e9e9e9;
    }
    .remarksDiv{
        position: relative;
        display: grid;
        grid-column-gap: 10px;
    }
    .character-counter-indicator{
        text-align: end;
        margin: 0;
        grid-column: 2;
    }
    .suggestion-item, .status-item {
        /* border: 1px solid #a94442; */
        box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
    }
    /* Toggle Switch CSS */
    /* Custom Toggle Switch Styles */
    .toggle-container {
        display: inline-flex;
        align-items: center;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 2px;
        bottom: 2px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider {
        background-color: #32a9c2;
    }
    input:checked + .slider:before {
        transform: translateX(26px);
    }
    .on, .off {
        color: white;
        position: absolute;
        top: 50%;
        font-size: 10px;
        font-weight: bold;
        transform: translate(-50%, -50%);
        transition: opacity 0.4s;
    }
    .on {
        left: calc(100% - 12px);
    }
    .off {
        left: 12px;
    }
    input:checked + .slider .on {
        opacity: 1;
    }
    input:checked + .slider .off {
        opacity: 0;
    }
    #see-more-button{
        cursor: pointer;
        margin-bottom: 0px;
    }
    #see-less-button{
        cursor: pointer;
        margin-bottom: 0px;
    }
    #mainInput , #remarks {
        /* width: 100%;
        height: 50px; */
        resize: none;
        overflow: hidden;
        box-sizing: border-box;
    }
    .px-none {
        padding-left: 0px;
        padding-right: 0px;
    }
    .black-text {
        color: black;
    }
    #speechDiv {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    #resetDiv {
        position: absolute;
        bottom: 0;
        right: 0;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 10px;
        transform: translate(-60%, 20%);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
    #speakButton {
        font-size: 17px;
        color: #0069D9;
        cursor: pointer;
    }
    #recordButton i {
        font-size: 17px;
        /* color: #ff9933; */
        color: #04cd31;
        cursor: pointer;
    }
    #refresh_icon{
        font-size: 14px;
        color: #ccc;
        cursor: pointer;
        z-index: 500;
    }

    @keyframes blinkRed {
        0% { color: red; }
        50% { color: #ccc; }
        100% { color: red; }
    }

    .blinking-red {
        animation: blinkRed 3s infinite;
    }
    @keyframes blinkGreen {
        0% { color: #04cd31; }
        50% { color: #ccc; }
        100% { color: #04cd31; }
    }

    .blinking-green {
        animation: blinkGreen 3s infinite;
    }

</style>

{{-- @include('ProcessPath::remarks-history-modal') --}}

{{-- {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!} --}}
{{-- {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!} --}}
{!! Form::open(['url' => 'process-path/batch-process-update', 'method' => 'post', 'id' => 'batch-process-form', 'files' => true]) !!}
<div class="col-md-12">
    <div class="alert alert-info" style="border: 10px solid #32a9c2 !important; overflow: inherit">
        <div class="row">
            <div class="col-md-12">
                <div class="col-sm-6 px-none" style="display: flex;align-items: center;">
                    <h4 style="margin-bottom: 0; margin-right: 10px;">Process for {{ $appInfo->process_name }}:</h4>
                </div>
                @if (!empty($smart_remarks_switch))
                    <div class="text-end col-sm-6 px-none" style="display: flex; align-items: center; text-align: end; justify-content: flex-end;">
                        <div class="toggle-container">
                            <span id="toggle-ai-label" style="margin-right: 5px"><strong>AI Assistance </strong></span>
                            <label class="switch" style="margin-bottom: 0">
                                <input type="checkbox" id="toggle-ai-switch" autocomplete="off">
                                <span class="slider round"></span>
                                <span class="off">On</span>
                                <span class="on">Off</span>
                            </label>
                        </div>
                    </div>
                @endif
            </div>
            <hr style="border-top-color: #32a9c2;margin-top: 40px; margin-bottom: 5px !important;" />
        </div>

        @if (!empty($smart_remarks_switch))
            {{--Modal--}}
            <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center" id="confirmationModalLabel">Notice</h4>
                        </div>
                        @if(in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch))
                            <div class="modal-body">
                                Please keep in mind that our AI system is still in the learning phase. It may not provide the best suggestions. Please review the suggestions before submitting.
                            </div>
                        @else
                            <div class="modal-body">
                                Sorry, the AI Assistant is currently unavailable for this service. Please get in touch with the support team if you need assistance.
                            </div>
                        @endif
                        <div class="modal-footer"  style="border-top: 0;">
                            <button type="button" class="btn btn-warning" data-dismiss="modal" id="closedButton" style="float:left;">Close</button>
                            @if(in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch))
                                <button type="button" class="btn btn-primary" id="proceedButton" style="float:right;">Proceed</button>
                            @else
                                <button type="button" class="btn btn-primary" style="float:right;" disabled>Proceed</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- hidden data for data validation, update process --}}
        @if (isset($appInfo->ref_id))
            {!! Form::hidden('application_ids[0]', Encryption::encodeId($appInfo->ref_id), ['class' => 'form-control input-md required', 'id' => 'application_id']) !!}
        @endif
        {!! Form::hidden('status_from', Encryption::encodeId($appInfo->status_id)) !!}
        {!! Form::hidden('desk_from', Encryption::encodeId($appInfo->desk_id)) !!}
        {!! Form::hidden('process_list_id', Encryption::encodeId($appInfo->process_list_id), ['id' => 'process_list_id']) !!}
        {!! Form::hidden('cat_id', Encryption::encodeId($cat_id), ['id' => 'cat_id']) !!}
        {!! Form::hidden('data_verification', Encryption::encode(\App\Libraries\UtilFunction::processVerifyData($verificationData)), ['id' => 'data_verification']) !!}
        {!! Form::hidden('is_remarks_required', '', ['class' => 'form-control input-md ', 'id' => 'is_remarks_required']) !!}
        {!! Form::hidden('is_file_required', '', ['class' => 'form-control input-md ', 'id' => 'is_file_required']) !!}

        <div class="row">
            <div class="loading" style="display: none">
                <h2><i class="fa fa-spinner fa-spin"></i> &nbsp;</h2>
            </div>
            <div class="col-md-3 form-group {{ $errors->has('status_id') ? 'has-error' : '' }}">
                {!! Form::label('status_id', 'Apply Status') !!}
                {!! Form::select('status_id', [], null, ['class' => 'form-control required applyStausId', 'id' => 'application_status']) !!}
                {!! $errors->first('status_id', '<span class="help-block">:message</span>') !!}
            </div>

            <div id="resend_deadline_field" class="hidden">
                <div class="col-md-3 form-group {{ $errors->has('resend_deadline') ? 'has-error' : '' }}">
                    <label for="resend_deadline">Resend deadline</label>
                    <div class="datepicker input-group date">
                        {!! Form::text('resend_deadline', '', ['class' => 'form-control input-md', 'placeholder' => 'dd-mm-yyyy']) !!}
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span>
                        </span>
                    </div>
                    {!! $errors->first('resend_deadline', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div id="sendToDeskOfficer">
                <div class="col-md-3 form-group {{ $errors->has('desk_id') ? 'has-error' : '' }}">
                    {!! Form::label('desk_id', 'Send to Desk') !!}
                    {!! Form::select('desk_id', ['' => 'Select Below'], '', ['class' => 'form-control dd_id required', 'id' => 'desk_status']) !!}
                    {!! $errors->first('desk_id', '<span class="help-block">:message</span>') !!}
                </div>
                {{-- <span class="col-md-1 {{$errors->has('priority') ? 'has-error' : ''}}" style="width: 15%"> --}}
                {{-- {!! Form::label('priority','Priority') !!} --}}
                {{-- {!! Form::select('priority', [''=>'Select Below'], '', ['class' => 'form-control required', 'id' => 'priority']) !!} --}}
                {{-- {!! $errors->first('priority','<span class="help-block">:message</span>') !!} --}}
                {{-- </span> --}}
                <div class="is_user col-md-3 form-group hidden {{ $errors->has('is_user') ? 'has-error' : '' }}">
                    {!! Form::label('is_user', 'Select desk user') !!}<br>
                    {{-- <span id="is_user"></span> --}}
                    {!! Form::select('is_user', ['' => 'Select user'], '', ['class' => 'form-control', 'id' => 'is_user']) !!}
                    {!! $errors->first('is_user', '<span class="help-block">:message</span>') !!}
                </div>
            </div>

            <div class="col-md-3 form-group hidden {{ $errors->has('desk_id') ? 'has-error' : '' }}" id="is_meeting">
                {!! Form::label('Meeting Number', '') !!}
                {!! Form::select('board_meeting_id', ['' => 'Select Below'], '', ['class' => 'form-control required', 'id' => 'meeting_number']) !!}
            </div>

            <div class="col-md-3 form-group {{ $errors->has('desk_id') ? 'has-error' : '' }}">
                <label for="attach_file">Attach file
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title=""
                       data-original-title="To select multiple files, hold down the CTRL or SHIFT key while selecting."></i>
                    <span class="text-danger" style="font-size: 9px; font-weight: bold">[File: *.pdf | Maximum 2
                        MB]</span></label>
                {!! Form::file('attach_file[]', ['class' => 'form-control input-md', 'id' => 'attach_file_id', 'multiple' => true, 'accept' => 'application/pdf', 'onchange' => 'uploadDocumentProcess(this.id)']) !!}
                {!! $errors->first('attach_file', '<span class="help-block">:message</span>') !!}
            </div>

            <div class="col-md-3 form-group hidden {{ $errors->has('desk_id') ? 'has-error' : '' }}" id="pin_number">
                {!! Form::label('Enter Pin Number', '') !!}
                <input class="form-control input-md col-sm " type="text" name="pin_number">
                <span class="text-danger" style="font-size: 10px; font-weight: bold">Please check your email or phone
                    number</span>
            </div>

            <div class="col-md-3 form-group hidden" id="basic_salary">
                {!! Form::label('basic_salary', 'Minimum range of basic salary') !!}
                <input class="form-control required input-md col-sm onlyNumber" value="{{ $appInfo->basic_salary }}"
                       type="text" name="basic_salary">
                {!! $errors->first('basic_salary', '<span class="help-block">:message</span>') !!}
            </div>
        </div>



        <div class="col-md-3 form-group hidden {{ $errors->has('ref_no') ? 'has-error' : '' }}" id="is_ref_no">
            {!! Form::label('Reference Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('ref_no', '', ['class' => 'form-control required', 'id' => 'ref_no']) !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('is_incorporation') ? 'has-error' : '' }}"
             id="is_incorporation">
            {!! Form::label('Incorporation Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('incorporation_number', '', ['class' => 'form-control required', 'id' => 'incorporation_number']) !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('is_etin') ? 'has-error' : '' }}" id="is_etin">
            {!! Form::label('etin Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('etin_number', '', ['class' => 'form-control required', 'id' => 'etin_no']) !!}
        </div>


        <div class="col-md-3 form-group hidden {{ $errors->has('is_tl') ? 'has-error' : '' }}" id="is_tl">
            {!! Form::label('Trade License Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('tl_number', '', ['class' => 'form-control required', 'id' => 'is_tl']) !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('is_accno') ? 'has-error' : '' }}" id="is_accno">
            {!! Form::label('Account Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('acc_number', '', ['class' => 'form-control required', 'id' => 'is_accno']) !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('is_branch') ? 'has-error' : '' }}" id="is_branch">
            {!! Form::label('Branch Name', '', ['class' => 'required-star']) !!}
            {!! Form::text('branch_name', '', ['class' => 'form-control required', 'id' => 'is_branch']) !!}
        </div>

        <div class="col-md-3 form-group hidden {{ $errors->has('is_reg') ? 'has-error' : '' }}" id="is_reg">
            {!! Form::label('Registration Number', '', ['class' => 'required-star']) !!}
            {!! Form::text('reg_number', '', ['class' => 'form-control required', 'id' => 'is_reg']) !!}
        </div>

        <div class="loading-status" style="margin-top: 5px; display: none;">
            <p><i class="fa fa-spinner fa-spin"></i> &nbsp; Searching for Suggestions</p>
        </div>
        <div id="status-label" style="display: none; margin-top: 10px; margin-bottom: 10px;"><strong>Status Suggestions:</strong></div>
        <div id="status-list" style="display: none;"></div>

        {{-- AdD-on form div --}}
        <div class="row" style="margin-top: 30px">
            <div id="FormDiv"></div>
        </div>

        {{-- <span class="col-md-3" style="margin-top: 28px;"> --}}
        {{-- <button type="button" class="btn btn-warning" id="request_shadow_file">Request for shadow file</button> --}}
        {{-- </span> --}}

        <div id="approval_copy_remarks_area" class="hidden">
            <br />
            <div class="row">
                <div class="col-md-12 form-group {{ $errors->has('approval_copy_remarks') ? 'has-error' : '' }}">
                    <label for="approval_copy_remarks">Approval copy remarks <span class="text-danger"
                                                                                   style="font-size: 9px; font-weight: bold">(Maximum length 250)</span></label>
                    {!! Form::textarea('approval_copy_remarks', null, ['class' => 'form-control maxTextCountDown', 'id' => 'approval_copy_remarks', 'placeholder' => 'Enter approval copy remarks', 'data-charcount-maxlength' => '240', 'size' => '10x2']) !!}
                    {!! $errors->first('approval_copy_remarks', '<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-12 form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                <div class="col-md-12 px-none" style="margin-bottom: 10px;">
                    <div class="col-md-6 px-none" >
                        <label for="remarks" id="remarks-label">Remarks</label>
                    </div>
                    <div class="col-sm-6 px-none" style="display: flex; align-items: center; text-align: end; justify-content: flex-end;">
                        @if($appInfo->status_id != 1)
                            {{-- <a data-toggle="modal" data-target="#remarksHistoryModal" class="pull-right">
                                {!! Form::button('<i class="fa fa-eye"></i> <strong>Last Remarks</strong>', ['type' => 'button', 'class' => 'btn btn-md btn-info']) !!}
                            </a> --}}
                            <div class="toggle-container">
                                <span id="toggle-ai-label" style="margin-right: 5px"><strong>Previous Remarks </strong> </span>
                                <label class="switch" style="margin-bottom: 0px">
                                    <input type="checkbox" id="toggle-last-switch">
                                    <span class="slider round"></span>
                                    <span class="off" style="font-size: 8px">Show</span>
                                    <span class="on" style="font-size: 8px">Hide</span>
                                </label>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="remarksDiv col-md-12 px-none" style="margin-bottom: 15px;">
                    @if(in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch))
                        <div class="p-2" id="speechDiv">
                            @include('ProcessPath::speech', ['transcription_id' => 'remarks', 'second_transcription_id' => 'mainInput'])
                        </div>
                        <div class="p-2" id="resetDiv">
                            <button type="button" id="reset" class="btn-close m-0" aria-label="Close" style="position: absolute; bottom: 0; right: 8px; background: none; border: none; cursor: pointer; display: none; padding: 0;" onclick="clearRemarks()">
                                <i class="fas fa-sync-alt" id="refresh_icon"></i>
                            </button>
                        </div>
                        {!! Form::textarea('mainInput', isset($appInfo->process_desc) ? $appInfo->process_desc : '', ['class' => 'form-control','size' => '10x3', 'id' => 'mainInput']) !!}
                    @endif
                    {!! Form::textarea('remarks', isset($appInfo->process_desc) ? $appInfo->process_desc : '', ['class' => 'form-control maxTextCountDown','size' => '10x3', 'id' => 'remarks', 'data-charcounter-counterclass' =>'charCounter', 'placeholder' => in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch) && !empty(Auth::user()->ai_assistant)
                    ? '' : 'Enter Remarks', 'style' => in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch) && !empty(Auth::user()->ai_assistant) ? 'color: #adadad;' : 'color: black;' ]) !!}
                    {!! $errors->first('remarks', '<span class="help-block">:message</span>') !!}
                </div>
                <div class="loading-remarks" style="margin-top: 5px; display: none;">
                    <p><i class="fa fa-spinner fa-spin"></i> &nbsp; Searching for Suggestions</p>
                </div>
                <div id="suggestions-label" style="display: none; margin-top: 10px; margin-bottom: 10px;"><strong>Suggestions:</strong></div>
                <div id="suggestion-list" style="display: none;"></div>

                {{--                @if(in_array($appInfo->process_type_id, $smart_remarks_process) && $smart_remarks_switch)--}}
                {{--                    {!! Form::textarea('mainInput', !in_array($appInfo->status_id, [1]) ? $appInfo->process_desc : '', ['class' => 'form-control','size' => '10x1', 'id' => 'mainInput', 'oninput'=>'syncTextAreas()']) !!}--}}
                {{--                @endif--}}
                {{--                {!! Form::textarea('remarks', !in_array($appInfo->status_id, [1]) ? $appInfo->process_desc : '', ['class' => 'form-control maxTextCountDown','size' => '10x1', 'id' => 'remarks', 'data-charcounter-counterclass' =>'charCounter', 'oninput'=>'syncTextAreas()' ]) !!}--}}

                {{-- <div class="col-md-12" style="padding: 0px; display: none;" id="remarksHistoryMain">
                    @if($appInfo->process_desc)
                        <div class="remarksHistoryContent">
                            <div class="list-group">
                                <label for="remarks" id="remarks-label">Previous Remarks</label>

                                <span class="list-group-item" style="color: rgba(0,0,0,0.8);">
                                    @if ($appInfo->status_id != 1)
                                        <p class="list-group-item-text">{{ $appInfo->process_desc }}</p>
                                    @endif
                                </span>

                                <div class="attachmentArea" style="margin-top: 10px">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </div>
                            </div>
                        </div>
                    @else
                     <p style="text-align: center;">No Remarks Found</p>
                    @endif
                </div> --}}

                <div id="remarksHistoryMain" class="col-md-12" style="padding: 0px; display: none; margin: 20px 0px;" >
                    <strong>Previous Reamrks: </strong>
                    <div  style="margin-top: 10px;">
                        @if(!empty($nonDuplicateRemarks))
                            <div style="background-color: white; font-style: italic;">
                                <table class="table table-borderless">
                                    <tbody>
                                    @foreach($nonDuplicateRemarks as $remarks_history)
                                        <tr>
                                            <td style="padding-left: 10px; padding-right: 10px;">{{$remarks_history->process_desc}} -
                                                <span data-toggle="tooltip" data-placement="top" title="{{ $remarks_history->deskname }}">
                                                    <strong  style="text-decoration: underline;">{{ App\Libraries\CommonFunction::getUserFullnameById($remarks_history->updated_by) }}</strong>
                                                </span>
                                                <span>
                                                while {{$remarks_history->status_name}}
                                                </span>
                                                    <?php
                                                    $carbonDate = \Carbon\Carbon::parse($remarks_history->updated_at);
                                                    $formattedDate = $carbonDate->format('d F');
                                                    $currentYear = \Carbon\Carbon::now()->year;
                                                    $yearToShow = ($carbonDate->year !== $currentYear) ? $carbonDate->format(' Y') : '';
                                                    ?>
                                                on {{ $formattedDate . $yearToShow }}
                                                @if($remarks_history->files)
                                                    <a target="_blank" href="{{ url($remarks_history->files) }}" style="margin-left: 10px;" class="btn btn-primary btn-xs">
                                                        <i class="fa fa-save"></i> Download Attachment
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="col-md-12">No remarks found!</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-md-12 form-group {{ $errors->has('remarks') ? 'has-error' : '' }}">
                <label for="remarks">Remarks <span class="text-danger" style="font-size: 9px; font-weight: bold">(Maximum length 5000)</span></label>
                {!! Form::textarea('mainInput', '', ['class' => 'form-control', 'id' => 'mainInput', 'size' => '10x2']) !!}
                {!! Form::textarea('remarks', !in_array($appInfo->status_id, [1]) ? $appInfo->process_desc : '', ['class' => 'form-control maxTextCountDown', 'id' => 'remarks', 'placeholder' => 'Enter Remarks', 'data-charcount-maxlength' => '5000', 'size' => '10x2']) !!}
                {!! $errors->first('remarks', '<span class="help-block">:message</span>') !!}
            </div>
        </div> --}}

        <div class="row">
            <div class="col-sm-7">
                @if ($session_get == 'batch_update')

                    <div class="col-sm-10">
                        <i style="color: #a94442">
                            You are processing {{ $total_process_app }} of {{ $total_selected_app }} application
                            in
                            batch.
                            <br>
                            Tracking no. of next application is.{{ $next_app_info }}</i>
                    </div>
                @endif
            </div>

            @if ($session_get == 'batch_update')
                <div class="col-md-3">
                    <input name="is_batch_update" type="hidden"
                           value="{{ \App\Libraries\Encryption::encode('batch_update') }}">
                    <input name="single_process_id_encrypt" type="hidden" value="{{ $single_process_id_encrypt }}"
                           id="process_id">

                    <a id="prev-process" class="btn btn-info" @if ($total_process_app == 1) disabled="" data-id="1"
                       @else href="/process/batch-process-previous/{{ $single_process_id_encrypt }}" @endif><i class="fa fa-angle-double-left"></i> Previous</a>
                    <a id="next-process" style="padding: 6px 27px" class="btn btn-info " @if ($total_process_app == $total_selected_app) disabled="" data-id="1"
                       @else  href="/process/batch-process-skip/{{ $single_process_id_encrypt }}" @endif>Next <i class="fa fa-angle-double-right"></i></a>
                </div>
            @endif
            <div class="col-sm-2 <?php if ($session_get == null) {
                echo 'col-sm-offset-3';
            } ?>">
                <div class="form-group">
                    {!! Form::button('<i class="fa fa-save"></i> Process', ['type' => 'submit', 'value' => 'Submit', 'class' => 'btn btn-primary btn-block send', 'id' => 'process_btn_id']) !!}
                </div>
            </div>
        </div>


        <?php
        $ut = \App\Libraries\CommonFunction::getUserType();
        $getUserIDfromDepartmentSubdeptWisePermission = CommonFunction::getUserIdByhasDeskDepartmentWisePermission($appInfo->desk_id,$appInfo->approval_center_id, $appInfo->department_id, $appInfo->sub_department_id, $appInfo->process_type_id, $appInfo->user_id, $ut);

        if($getUserIDfromDepartmentSubdeptWisePermission != 0 && $is_delegation == 'is_delegation')
        {
            $DelegateUserInfo = CommonFunction::DelegateUserInfo($getUserIDfromDepartmentSubdeptWisePermission);
            //        dd($DelegateUserInfo);
            ?>
        <span class="col-md-6 col-sm-offset-2">
            <div class="form-group has-feedback">
                {!! Form::hidden('on_behalf_user_id', Encryption::encodeId($DelegateUserInfo->id), ['maxlength' => '500', 'class' => 'form-control input-md']) !!}
                <label class="col-lg-4 text-left"></label>
                <div class="col-lg-8">
                    <fieldset class="scheduler-border">
                        <legend class="scheduler-border">On-behalf of</legend>
                        <div class="control-group">
                            <span>Name: {{ $DelegateUserInfo->user_full_name }}</span><br>
                            <span>Designation: {{ $DelegateUserInfo->designation }}</span><br>
                            <span>User Image: <img style="width: 100px;"
                                                   src="{{ $userPic = url() . '/users/upload/' . $DelegateUserInfo->user_pic }}"
                                                   class="profile-user-img img-responsive" alt="Profile Picture" id="uploaded_pic"
                                                   width="150"></span>
                        </div>
                    </fieldset>
                </div>
            </div>
        </span>
            <?php
        }
        ?>
        <div class="clearfix"></div>
    </div>
</div>
{!! Form::close() !!}


<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

@if(!empty($smart_remarks_switch))
    <script>
        $(document).ready(function() {
            var initialStatus = {{ Auth::user()->ai_assistant }};
            var conditionCheck = "{{ in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch) }}";

            $('#toggle-ai-switch').prop('checked', false);
            $('#speechDiv, #resetDiv').hide();

            if (initialStatus == 1 && conditionCheck) {
                $('#toggle-ai-switch').prop('checked', true);
                $('#speechDiv, #resetDiv').fadeIn(300);
                $('#mainInput').show();
                $('#status-label').show();
                $('#status-list').show();
            } else {
                $('#toggle-ai-switch').prop('checked', false);
                $('#speechDiv, #resetDiv').hide();
                $('#mainInput').hide();
                $('#status-label').hide();
                $('#status-list').hide();
            }

             // Function to handle the AI Assistance toggle switch change
            $('#toggle-ai-switch').click(function() {
                if (this.checked) {
                    $('#confirmationModal').modal({
                        backdrop: 'static',
                        keyboard: false
                    });
                } else {
                    toggleSwitch();
                    $('#mainInput').hide();
                    $('#status-label').hide();
                    $('#status-list').hide();
                    $('#speechDiv, #resetDiv').hide();
                    if (socket && socket.readyState !== WebSocket.CLOSED) {
                        socket.close();
                        closeRecording();
                        $("#speakButton").css('display', 'none');
                    }
                }
            });

            // Function to toggle AI assistance and handle AJAX request
            function toggleSwitch() {
                syncTextAreas();
                var newStatus = $('#toggle-ai-switch').is(':checked') ? 1 : 0;
                $.ajax({
                    url: '{{ route('toggle-ai-assistance') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: { status: newStatus },
                    success: function(response) {
                        if (response.success) {
                            $('#mainInput').val('');
                            $('#remarks').val('');
                            $('#mainInput').show();
                            $('#status-label').show();
                            $('#status-list').show();
                            loadStatusList();
                            if($('#application_status').val() == ''){
                                $(".is_user").addClass('hidden');
                                $('#desk_status option:not(:first)').remove();
                            }
                        }
                        if(newStatus == 0){
                            document.getElementById('suggestions-label').style.display = 'none';
                            document.getElementById('suggestion-list').style.display = 'none';
                            document.getElementById('mainInput').style.display = 'none';
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(errorThrown);
                    }
                });
            }

            // Handle the "Proceed" button click in the confirmation modal
            $('#proceedButton').click(function() {
                // Close the modal and proceed with toggling the switch
                $('#confirmationModal').modal('hide');
                $('#toggle-ai-switch').prop('checked', true);
                $('#speechDiv, #resetDiv').fadeIn(300);
                toggleSwitch();

            });
            $('#closedButton').click(function() {
                // Close the modal and proceed with toggling the switch
                $('#confirmationModal').modal('hide');
                $('#toggle-ai-switch').prop('checked', false);
                $('#speechDiv, #resetDiv').hide();
                $('.loading-remarks').hide();
                if($('#application_status').val() != ''){
                    toggleSwitch();
                }
            });

            $('#toggle-last-switch').click(function() {
                if (this.checked) {
                    $('#remarksHistoryMain').slideDown();
                } else {
                    $('#remarksHistoryMain').slideUp();
                }
            });

            $('#toggle-last-switch').prop('checked', false);
        });
    </script>
@endif

@if(in_array($appInfo->process_type_id, $smart_remarks_process) && !empty($smart_remarks_switch))
    <script>
        let token = "{{ $cacheToken }}";
        $(document).ready(function(){
            syncTextAreas();
            let autocomplete = $("#remarks");
            let mainInput = $("#mainInput");
            let foundName = '';
            let predicted = '';
            let apiBusy = false;
            let suggestionList = document.getElementById('suggestion-list');
            let suggestions = [];

            mainInput.on('keyup', function(e) {
                if (mainInput.val() == '') {
                    autocomplete.val('');
                    return;
                }

                if (e.keyCode == 32) {
                    callMLDataSetAPI(e);
                    scrolltobototm();
                    syncTextAreas();
                    return;
                }

                if (e.key == 'Backspace'){
                    autocomplete.val(mainInput.val());
                    predicted = '';
                    apiBusy = true;
                    return;
                }

                if(e.key != 'ArrowRight'){
                    if (autocomplete.val() != '' && predicted){
                        if (Array.isArray(predicted)) {
                            predicted = predicted.toString();
                        }
                        let first_character = predicted.charAt(0);
                        if(e.key == first_character){
                            predicted = predicted.substr(1);
                            apiBusy = true;
                        }else{
                            apiBusy = false;
                        }
                    }else{
                        apiBusy = false;
                    }
                    return;
                } else {
                    if(predicted){
                        if (apiBusy == true){
                            apiBusy = false;
                        }
                        if (apiBusy == false){
                            mainInput.val(foundName);
                        }
                    }else{
                        return;
                    }
                }
                syncTextAreas();
            });

            mainInput.on('input', function(e) {
                autocomplete.val(mainInput.val());
                return;
            });

            function callMLDataSetAPI(event) {
                handleSmartSuggest('autocomplete', {
                    input_text: mainInput.val()
                })
                    .then(response => {
                        if(response.responseCode == 200){
                            let new_text = event.target.value + response.data;
                            autocomplete.val(new_text);
                            foundName = new_text;
                            predicted = response.data;
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }

            function scrolltobototm() {
                setInterval(function(){
                    autocomplete.scrollTop = mainInput.scrollHeight;
                }, 1000);
            }

            mainInput.keydown(function(e) {
                if (e.keyCode === 9) {
                    e.preventDefault();
                    presstabkey();
                }
            });

            function presstabkey() {
                if(predicted){
                    if (apiBusy == true){
                        apiBusy = false;
                    }
                    if (apiBusy == false){
                        mainInput.val(foundName);
                    }
                } else {
                    return;
                }
            }
           
            function displaySuggestions(visibleCount, showAll = false) {
                suggestionList.removeEventListener('click', suggestionClickHandler);
                suggestionList.innerHTML = "";

                let filteredSuggestions;
                if (showAll) {
                    filteredSuggestions = suggestions;
                } else {
                    filteredSuggestions = suggestions.filter(s => s.priority == '1').slice(0, visibleCount);
                }

                filteredSuggestions.forEach(function(suggestion) {
                    let suggestionItem = document.createElement("div");
                    suggestionItem.classList.add("suggestion-item");
                    suggestionItem.textContent = suggestion.message;
                    suggestionItem.style.display = "block";
                    suggestionList.appendChild(suggestionItem);
                });

                if (!showAll && suggestions.length > visibleCount) {
                    let seeMoreButton = document.createElement("p");
                    seeMoreButton.id = "see-more-button";
                    seeMoreButton.textContent = "More >>";
                    seeMoreButton.style.cursor = "pointer";
                    seeMoreButton.style.color = "#31708f";
                    suggestionList.appendChild(seeMoreButton);
                }

                suggestionList.addEventListener('click', suggestionClickHandler);
            }

            function suggestionClickHandler(event) {
                if (event.target.classList.contains('suggestion-item')) {
                    let selectedSuggestion = event.target.textContent;
                    let old_content = $("#mainInput").val().trim();
                    let newContent = old_content ? old_content + ' ' + selectedSuggestion : selectedSuggestion;
                    $("#mainInput").val(newContent);
                    $("#remarks").val('');
                    $("#remarks").val(newContent);
                }

                if (event.target.id === 'see-more-button') {
                    displaySuggestions(suggestions.length, true);
                }

                if (event.target.id === 'see-less-button') {
                    displaySuggestions(5);
                }
            }

            $("#application_status").change(function() {
                document.getElementById('suggestions-label').style.display = 'none';
                document.getElementById('suggestion-list').style.display = 'none';
                if (document.getElementById('mainInput')) {
                    document.getElementById('mainInput').style.display = 'none';
                }
                $('.loading-remarks').hide();

                if ($('#toggle-ai-switch').is(':checked')) {
                    $('#mainInput').show();
                    $('#status-label').show();
                    $('#status-list').show();
                    $('#speechDiv, #resetDiv').fadeIn(300);
                }

                if (this.value != '') {
                    $('.loading-remarks').show();
                    if ($('#toggle-ai-switch').is(':checked')) {
                        $('#mainInput').show();
                        $('#speechDiv, #resetDiv').fadeIn(300);

                        suggestionList.innerHTML = "";

                        handleSmartSuggest('remarks', {
                            app_ref_id: "{{ $appInfo->ref_id }}",
                            process_type_id: "{{ $appInfo->process_type_id }}",
                            status_id: $('#application_status').val()
                        })
                            .then(response => {
                                $('.loading-remarks').hide();
                                if (response.responseCode == 200 && response.data && response.data[0]) {
                                    document.getElementById('suggestions-label').style.display = 'block';
                                    document.getElementById('suggestion-list').style.display = 'flex';
                                    $('#mainInput').show();

                                    suggestions = [];
                                    if (response.data[0].priority_1_remarks) {
                                        response.data[0].priority_1_remarks.forEach(function(item) {
                                            suggestions.push({ message: item, priority: '1' });
                                        });
                                    }
                                    if (response.data[0].priority_2_remarks) {
                                        response.data[0].priority_2_remarks.slice(0, 5).forEach(function(item) {
                                            suggestions.push({ message: item, priority: '2' });
                                        });
                                    }

                                    displaySuggestions(5);

                                    if (suggestions.length > 0) {
                                        $('#suggestions-label').show();
                                        suggestionList.style.display = "flex";
                                        suggestionList.style.alignItems = "center";
                                    } else {
                                        $('#suggestions-label').hide();
                                        $('#status-label').hide();
                                        $('#status-list').hide();
                                        suggestionList.style.display = "none";
                                        document.getElementById('suggestions-label').style.display = 'none';
                                    }
                                } else {
                                    console.error("No data or invalid data received.");
                                    $('#suggestions-label').hide();
                                    $('#status-label').hide();
                                    $('#status-list').hide();
                                    suggestionList.style.display = "none";
                                    document.getElementById('suggestions-label').style.display = 'none';
                                }
                            })
                            .catch(error => {
                                $('.loading-remarks').hide();
                                $('#suggestions-label').hide();
                                $('#status-label').hide();
                                $('#status-list').hide();
                                suggestionList.style.display = "none";
                                document.getElementById('suggestions-label').style.display = 'none';
                                document.getElementById('mainInput').style.display = 'none';
                            });

                    } else {
                        $('#suggestions-label').hide();
                        $('#suggestion-list').slideUp();
                        $('#mainInput').hide();
                        $('.loading-remarks').hide();
                        $('#status-label').hide();
                        $('#status-list').hide();
                    }
                }
            });

            suggestionList.addEventListener('click', suggestionClickHandler);

        });

        function handleSmartSuggest(type, params) {
            // Ensure token exists before making API call
            if (!token) {
                return TokenManager.fetchToken("/bida-oss-landing/insightdb-token")
                    .then(newToken => {
                        token = newToken;
                        return makeApiCall(type, params);
                    })
                    .catch(error => {
                        console.error("Token fetch failed:", error);
                        throw error;
                    });
            }
            return makeApiCall(type, params);
        }

        function makeApiCall(type, params) {
            const base_url = "{{ config('app.insightdb_api_base_url') }}";
            const remarks_endpoint = "{{ config('app.ml_suggest_remarks') }}";
            const auto_endpoint = "{{ config('app.ml_text_auto_complete') }}";

            const api_url = type === 'remarks'
                ? `${base_url}${remarks_endpoint}`
                : `${base_url}${auto_endpoint}`;

            const queryParams = type === 'remarks'
                ? $.param({
                    app_ref_id: params.app_ref_id,
                    process_type_id: params.process_type_id,
                    status_id: params.status_id
                })
                : $.param({
                    input_text: params.input_text
                });

            return fetch(`${api_url}?${queryParams}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
            })
                .then(response => {
                    // if (response.status === 401) {
                    //     return TokenManager.fetchToken().then(() => makeApiCall(type, params));
                    // }
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.responseCode !== 200) {
                        throw new Error(data.message || 'Failed to fetch data');
                    }
                    return data;
                })
                .catch(error => {
                    console.error('API Error:', error);
                    throw error;
                });

        }
        window.onload = function () {
            TokenManager.initializeTokenRefresh("/bida-oss-landing/insightdb-token", 270000);
        };

        function syncTextAreas() {
            var mainInput = document.getElementById('mainInput'); // Top Text Area
            var remarks = document.getElementById('remarks'); // Botom Text Area

            // Enable manual resizing
            mainInput.style.resize = "vertical";
            remarks.style.resize = "vertical"; 

            // Add mousemove event for instant sync while dragging
            mainInput.addEventListener('mousemove', function(e) {
                if (e.buttons === 1) { // Check if mouse button is pressed
                    remarks.style.height = this.style.height;
                }
            });

            remarks.addEventListener('mousemove', function(e) {
                if (e.buttons === 1) {
                    mainInput.style.height = this.style.height;
                }
            });

            // Keep existing mouseup handlers for final sync
            mainInput.addEventListener('mouseup', function() {
                remarks.style.height = this.style.height;
            });

            remarks.addEventListener('mouseup', function() {
                mainInput.style.height = this.style.height;
            });

            // Reset heights to auto to ensure they can shrink
            mainInput.style.height = "auto";
            remarks.style.height = "auto";

            // Get the scroll height of each textarea
            var mainInputHeight = mainInput.scrollHeight;
            var remarksHeight = remarks.scrollHeight;

            // Set both textareas to the maximum scroll height plus some padding
            var newHeight = Math.max(mainInputHeight, remarksHeight);
            // mainInput.style.height = (newHeight) + "px";
            // remarks.style.height = (newHeight) + "px";

            // Set maximum height (e.g., 300px) and enable scrolling if content is larger
            var maxHeight = 300;
            if (newHeight > maxHeight) {
                mainInput.style.height = maxHeight + "px";
                remarks.style.height = maxHeight + "px";
                mainInput.style.overflowY = "auto";
                remarks.style.overflowY = "auto";
            } else {
                mainInput.style.height = newHeight + "px";
                remarks.style.height = newHeight + "px";
                mainInput.style.overflowY = "hidden";
                remarks.style.overflowY = "hidden";
            }

            // Sync scroll positions
            if (mainInput.scrollTop !== remarks.scrollTop) {
                remarks.scrollTop = mainInput.scrollTop;
            }

            // Auto scroll to bottom if near bottom
            if (mainInput.scrollHeight - mainInput.scrollTop <= mainInput.clientHeight + 50) {
                mainInput.scrollTop = mainInput.scrollHeight;
                remarks.scrollTop = remarks.scrollHeight;
            }
        }

        // Add scroll event listener to keep textareas in sync while scrolling
        document.getElementById('mainInput').addEventListener('scroll', function() {
            document.getElementById('remarks').scrollTop = this.scrollTop;
        });

        document.getElementById('remarks').addEventListener('scroll', function() {
            document.getElementById('mainInput').scrollTop = this.scrollTop;
        });

        // Initialize the textareas to ensure they start synchronized
        document.addEventListener('DOMContentLoaded', function() {
            syncTextAreas();
        });

    </script>
@else
    <script>
        $(document).ready(function() {
            $('#toggle-ai-switch').prop('checked', false);
            $('#speechDiv, #resetDiv').hide();
        });
        $('#toggle-ai-switch').prop('checked', false);
        $('#speechDiv, #resetDiv').hide();
    </script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const remarksTextarea = document.getElementById("remarks");
        const toggleSwitch = document.getElementById("toggle-ai-switch");

        if (event.key === " " && !toggleSwitch) {
            // Enable vertical resizing
            remarksTextarea.style.resize = "vertical";
        }
        else if(toggleSwitch && !toggleSwitch.checked){
            remarksTextarea.style.resize = "vertical";
        }

        remarksTextarea.addEventListener("keydown", function (event) {
            if (event.key === " " && !toggleSwitch) {
                setTimeout(() => {
                    adjustHeight(this);
                }, 0);
            }
            else if(toggleSwitch && !toggleSwitch.checked){
                setTimeout(() => {
                    adjustHeight(this);
                }, 0);
            }
        });

        function adjustHeight(textarea) {
            textarea.style.height = "auto";
            if (textarea.scrollHeight > textarea.clientHeight) {
                textarea.style.height = textarea.scrollHeight + "px";
            }
        }
    });
</script>

<script>
    function uploadDocumentProcess(id) {
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
    }

    $(document).ready(function() {
        function handleProcessClick($element, $otherElement, currentId, newId) {
            $element.one('click', function() {
                $element.addClass('disabled').attr('data-id', newId).html(`${$element.text().trim()} <i class="fa fa-spinner fa-spin"></i>`);
                $otherElement.addClass('disabled').attr('data-id', currentId);
                $otherElement.attr('disabled', true);
                $element.attr('disabled', true);
            });
        }

        const $prevProcess = $('#prev-process');
        const $nextProcess = $('#next-process');

        if ($prevProcess.data('id') != 1) {
            handleProcessClick($prevProcess, $nextProcess, 1, 1);
        }

        if ($nextProcess.data('id') != 1) {
            handleProcessClick($nextProcess, $prevProcess, 1, 1);
        }

        // Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        var mm = today.getMonth();
        var dd = today.getDate();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
            minDate: moment(),
        });

        $('.datepickerMemo').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 150),
        });

        /**
         * Batch Form Validate
         * @type {jQuery}
         */
        $("#batch-process-form").validate({
            errorPlacement: function() {
                return false;
            },
            submitHandler: function(form) {
                // This submitHandler() function will only work when the form is valid.
                var conditional_remarks = document.getElementsByName("approval_copy_remarks");
                var process_id = "{{ $appInfo->process_type_id }}";
                var status_id = $('#application_status').val();

                if (status_id == '25' && conditional_remarks.length > 0 && conditional_remarks[0]
                    .value.trim()) {
                    swal({
                        title: 'Are you sure regarding the issuing letter mentioning with the remarks?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            form.submit();
                        }
                    });
                } else if (process_id == '2' || process_id == '3' || process_id == '6' ||
                    process_id == '7') {
                    // Compare start_date and end_date
                    // WPN = 2, WPE = 3, OPN = 6, OPE = 7, valid form submit
                    var startDate = document.getElementById('start_date').value;
                    var endDate = document.getElementById('end_date').value;

                    var actualStartDate = new Date(startDate.replace(/-/g,
                        ' ')); // convert to actual date
                    var actualEndDate = new Date(endDate.replace(/-/g,
                        ' ')); // convert to actual date

                    if ((Date.parse(actualStartDate) > Date.parse(actualEndDate))) {
                        $('#end_date').addClass('error').removeClass('valid');
                        return false;
                    } else {
                        form.submit();
                    }
                } else {
                    form.submit();
                }
            }
        });

        $("#application_status").change(function() {
            var status_name = $("#application_status option:selected").text();
            if(status_name != 'Select Below')
            {
                $('#remarks-label').text('Remarks on ' + status_name);
            }
            else{
                $('#remarks-label').text('Remarks');
            }
            var self = $(this);
            var statusId = $('#application_status').val();
            var process_type_id = "{{ $appInfo->process_type_id }}";

            if ($('#status-list').children().length === 0) {
                $('#status-label').hide();
                $('#status-list').hide();
            }

            if (statusId == 25) {
                document.getElementById('approval_copy_remarks_area').classList.remove('hidden');
            } else {
                document.getElementById('approval_copy_remarks_area').classList.add('hidden');
            }

            if ((statusId == 5 || statusId == 15 || statusId == 32) && (process_type_id != 5 || process_type_id != 9)) { // 5 = WPC, 9 = OPC
                document.getElementById('resend_deadline_field').classList.remove('hidden');
            } else {
                document.getElementById('resend_deadline_field').classList.add('hidden');
            }
            if(statusId == ''){
                $(".is_user").addClass('hidden');
                $('#desk_status option:not(:first)').remove();
            }

            var cat_id = $('#cat_id').val();
            if (statusId !== '') {
                //process btn disable
                $('.loading_data').remove();
                $(this).after('<span class="loading_data">Loading...</span>');
                $.ajax({
                    type: "POST",
                    url: "{{ url('process-path/get-desk-by-status') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        process_list_id: $('input[name="process_list_id"]').val(),
                        status_from: $('input[name="status_from"]').val(),
                        cat_id: cat_id,
                        statusId: statusId
                    },
                    success: function(response) {
                        $('.loading_data').css('display', 'none');
                        var option = '<option value="">Select One</option>';

                        var countDesk = 0;

                        if (response.responseCode == 1) {
                            if (response.pin_number == 1) {
                                $('#pin_number').removeClass('hidden');
                                $('#pin_number').children('input').addClass('required');
                                $('#pin_number').children('input').attr('disabled', false);
                            } else {
                                $('#pin_number').addClass('hidden');
                                $('#pin_number').children('input').removeClass('required');
                                $('#pin_number').children('input').attr('disabled', true);

                            }

                            var process_type_id = '{{ $appInfo->process_type_id }}';
                            // alert(response.chk_sts);
                            if (process_type_id == 107 && response.chk_sts == 10) {
                                $('#is_ref_no').removeClass('hidden');
                                $('#is_incorporation').removeClass('hidden');
                                $('#is_incorporation').addClass('required');
                                $('#is_ref_no').addClass('required');
                            } else if (process_type_id == 106 && response.chk_sts == 10) {
                                $('#is_ref_no').removeClass('hidden');
                                $('#is_ref_no').addClass('required');
                                $('#is_etin').removeClass('hidden');
                                $('#is_etin').addClass('required');
                            } else if (process_type_id == 105 && response.chk_sts == 10) {
                                $('#is_ref_no').removeClass('hidden');
                                $('#is_ref_no').addClass('required');
                                $('#is_tl').removeClass('hidden');
                                $('#is_tl').addClass('required');
                            } else if (process_type_id == 103 && response.chk_sts == 10) {
                                $('#is_ref_no').removeClass('hidden');
                                $('#is_ref_no').addClass('required');
                                $('#is_accno').removeClass('hidden');
                                $('#is_accno').addClass('required');
                                $('#is_branch').removeClass('hidden');
                                $('#is_branch').addClass('required');
                            } else if (process_type_id == 104 && response.chk_sts == 10) {
                                $('#is_ref_no').removeClass('hidden');
                                $('#is_ref_no').addClass('required');
                                $('#is_reg').removeClass('hidden');
                                $('#is_reg').addClass('required');
                            } else {
                                $('#is_ref_no').addClass('hidden');
                                $('#is_incorporation').addClass('hidden');
                                $('#is_incorporation').removeClass('required');
                                $('#is_ref_no').removeClass('required');
                                $('#is_etin').addClass('hidden');
                                $('#is_etin').removeClass('required');
                                $('#is_tl').addClass('hidden');
                                $('#is_tl').removeClass('required');
                                $('#is_accno').addClass('hidden');
                                $('#is_accno').removeClass('required');
                                $('#is_branch').addClass('hidden');
                                $('#is_branch').removeClass('required');
                                $('#is_reg').addClass('hidden');
                                $('#is_reg').removeClass('required');
                            }

                            //meeting number showing if available
                            var optionMeetingNumber =
                                '<option value="">Select One</option>';
                            if (response.meeting_number.length > 0) {
                                $('#is_meeting').removeClass('hidden');
                                $('#is_meeting').children('input').addClass('required');
                                $('#is_meeting').children('input').attr('disabled', false);

                                $.each(response.meeting_number, function(id, value) {
                                    optionMeetingNumber += '<option value="' + value
                                            .id + '">' + value.meting_number + "(" +
                                        value.meting_date + ")" + '</option>';
                                });
                                $("#meeting_number").html(optionMeetingNumber);

                            } else {
                                if (response.chk_sts == 19) {
                                    $("#meeting_number").html(optionMeetingNumber);
                                    $('#is_meeting').removeClass('hidden');
                                    $('#is_meeting').children('input').addClass('required');
                                    $('#is_meeting').children('input').attr('disabled',
                                        false);
                                } else {
                                    $("#meeting_number").html('');
                                    $('#is_meeting').addClass('hidden');
                                    $('#is_meeting').children('input').removeClass(
                                        'required');
                                    $('#is_meeting').children('input').attr('disabled',
                                        true);
                                    //$('#basic_salary').children('input').removeClass('required');
                                    //$('#basic_salary').addClass('hidden');
                                }

                            }
                            // end meeting number showing if available

                            $('#FormDiv').html(response.html);
                            var option_selected = ((Object.keys(response.data).length ==
                                1) ? "selected" : "");
                            $.each(response.data, function(id, value) {
                                countDesk++;
                                option += '<option ' + option_selected +
                                    ' value="' + id + '">' + value + '</option>';
                            });
                            // Setup required field about remarks field
                            if (response.remarks == 1 || statusId == 5 || statusId == 6) {
                                $("#remarks").addClass('required');
                                $('#is_remarks_required').val(1);
                            } else {
                                $("#remarks").removeClass('required');
                                $('#is_remarks_required').val('');
                            }

                            // Conditional approved remarks
                            // if (response.conditional_approved_remarks.length > 0) {
                            //     $("#remarks").text(response.conditional_approved_remarks);
                            // }

                            // Setup required field about remarks field
                            if (response.file_attachment == 1) {
                                $("#attach_file").addClass('required');
                                $('#is_file_required').val(response.file_attachment);
                            } else {
                                $("#attach_file").removeClass('required');
                            }

                        }
                        $("#desk_status").html(option);

                        if (option_selected) {
                            $("#desk_status").trigger("change");
                        }

                        self.next().hide();
                        if (countDesk == 0) {
                            $('.dd_id').removeClass('required');
                            $('#sendToDeskOfficer').css('display', 'none');

                            //meeting date remove
                            // $("#meeting_date").val('');
                            // $("#meeting_date").removeClass('required');
                            // $("#is_calender").addClass('hidden');
                        } else {
                            $('.dd_id').addClass('required');
                            $('#sendToDeskOfficer').css('display', 'block');
                        }
                        //process btn Enable
                        $("#process_btn_id").prop("disabled", false);
                    }
                });
                $('.loading_data').css('display', 'none');
            }

            //Basic salary show for WPN, WPE
            var process_id = "{{ $appInfo->process_type_id }}";

            var basicStatus = ['8', '9', '15', '19'];
            var basicRequiredFlag = basicStatus.includes(statusId);

            var department_id = "{{ $appInfo->department_id }}";
            if (department_id == 1 && basicRequiredFlag == true && (process_id == 2 || process_id ==
                3)) {
                $("#basic_salary").removeClass('hidden');
                $('#basic_salary').children('input').addClass('required');
            } else {
                $("#basic_salary").addClass('hidden');
                $('#basic_salary').children('input').removeClass('required');
            }
        });

        {{-- $("#meeting_number").change(function () { --}}
        {{-- var meeting_id = $(this).val(); --}}
        {{-- var process_type_id = "{{$appInfo->process_type_id}}"; --}}
        {{-- var self = $(this); --}}
        {{-- if (meeting_id != '' && (process_type_id == 2 ||process_type_id == 3 || process_type_id == 4 )) { --}}
        {{-- $("#basic_salary").removeClass('hidden'); --}}
        {{-- }else{ --}}
        {{-- $("#basic_salary").addClass('hidden'); --}}
        {{-- } --}}
        {{-- }); --}}

        /**
         * load apply status list on load page
         * @type {jQuery}
         */

        loadStatusList();

        $("#desk_status").change(function() {
            var self = $(this);
            var desk_id = $(this).val();
            var cat_id = $("#cat_id").val();
            var application_status = $('#application_status').val();
            if (desk_id != '') {
                $(this).after('<span class="loading_data">Loading...</span>');
                //process btn disable
                $("#process_btn_id").prop("disabled", true);

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{ url('process-path/get-user-by-desk') }}",
                    data: {
                        _token: $('input[name="_token"]').val(),
                        desk_to: desk_id,
                        status_from: $('input[name="status_from"]').val(),
                        desk_from: $('input[name="desk_from"]').val(),
                        statusId: application_status,
                        cat_id: cat_id,
                        process_type_id: "{{ \App\Libraries\Encryption::encodeId($appInfo->process_type_id) }}",
                        department_id: "{{ \App\Libraries\Encryption::encodeId($appInfo->department_id) }}",
                        sub_department_id: "{{ \App\Libraries\Encryption::encodeId($appInfo->sub_department_id) }}",
                        approval_center_id: "{{ \App\Libraries\Encryption::encodeId($appInfo->approval_center_id) }}",
                        app_id: "{{ \App\Libraries\Encryption::encodeId($appInfo->ref_id) }}"
                    },
                    success: function(response) {
                        var option = '<option value="">Select One</option>';
                        //                        var option = '';
                        var countUser = 0;
                        var option_selected = ((Object.keys(response.data).length == 1) ?
                            "selected" : "");
                        $.each(response.data, function(id, value) {
                            countUser++;
                            //option += '<label><input type="radio" class="required" name="is_user" value="' + value.user_id + '">' + value.user_full_name + '</label><br>';
                            option += '<option ' + option_selected + ' value="' +
                                value.user_id + '">' + value.user_full_name +
                                '</option>'
                        });
                        self.next().hide();
                        if (countUser == 0) {
                            $('#is_user').removeClass('required');
                            $(".is_user").addClass('hidden');
                        } else {
                            $("#is_user").html(option);
                            $('#is_user').addClass('required');
                            $(".is_user").removeClass('hidden');
                        }
                        //process btn enable
                        $("#process_btn_id").prop("disabled", false);
                    }
                });
            }

        });
    });

    function loadStatusList() {
        var application_id = $("#application_id").val();
        var process_list_id = $("#process_list_id").val();
        var cat_id = $("#cat_id").val();
        var curr_process_status_id = $("#curr_process_status_id").val();

        $.ajaxSetup({
            async: false
        });
        var _token = $('input[name="_token"]').val();
        var delegate = '{{ @$delegated_desk }}';

        $.post('/process-path/ajax/load-status-list', {
            curr_process_status_id: curr_process_status_id,
            application_id: application_id,
            process_list_id: process_list_id,
            cat_id: cat_id,
            delegate: delegate,
            _token: _token,
            process_type_id: "{{$appInfo->process_type_id}}"
        }, function(response) {
            if (response.responseCode == 1) {
                var option = '';
                var statusList = document.getElementById("status-list");
                statusList.innerHTML = "";
                option += '<option selected="selected" value="">Select Below</option>';
                $.each(response.data, function(id, value) {
                    // select suggested desk
                    var selected = "";
                    if (response.suggested_status === parseInt(value.id)) {
                        selected = "selected";
                    }
                    option += '<option ' + selected + ' value="' + value.id + '">' + value.status_name + '</option>';
                });

                $("#application_status").html(option);
                $("#application_status").trigger("change");
                $("#application_status").focus();

                if (response.filteredStatusList.length > 0) {
                    document.getElementById('status-label').style.display = 'block';
                    document.getElementById('status-list').style.display = 'flex';
                    var status = [];
                    response.filteredStatusList.forEach(function(item) {
                        status.push({ name: item.status_name, id:item.id });
                    });

                    // Initial display with limited suggestions
                    displayStatus(statusList, status);

                    // Event listener for suggestion click
                    statusList.addEventListener('click', function(event) {
                        if (event.target && event.target.getAttribute('data-id')) {
                            var selectedId = event.target.getAttribute('data-id');
                            $('#application_status').val(selectedId);
                            $('#application_status').trigger('change');
                        }
                    });

                    if (statusList.children.length > 0) {
                        $('#status-label').show();
                        statusList.style.display = "flex";
                        statusList.style.alignItems = "center";
                    } else {
                        $('#status-label').hide();
                        statusList.style.display = "none";
                    }
                } else {
                    $('#status-label').hide();
                    $('#status-list').hide();
                }

                setTimeout(function() {
                    $("#desk_status").trigger("change"); // Trigger the change event
                }, 500);

            } else if (response.responseCode == 5) {
                alert('Without verification, application cannot be processed');
                option = '<option selected="selected" value="">Select Below</option>';
                $("#application_status").html(option);
                $("#application_status").trigger("change");
                return false;
            } else {
                $('#status_id').html('Please wait');
            }
        });

        $.ajaxSetup({
            async: true
        });
    }


    function displayStatus(statusList, status) {
        statusList.innerHTML = "";
        var filteredStatus = status;

        filteredStatus.forEach(function(suggestion) {
            var statusItem = document.createElement("div");
            statusItem.classList.add("status-item");
            statusItem.textContent = suggestion.name;
            statusItem.setAttribute('data-id', suggestion.id);
            statusItem.style.display = "block";
            statusList.appendChild(statusItem);
        });

        // if (status.length > visibleCount) {
        //     var seeMoreButton = document.createElement("p");
        //     seeMoreButton.id = "see-more-button";
        //     seeMoreButton.textContent = "More >>";
        //     seeMoreButton.style.cursor = "pointer";
        //     seeMoreButton.style.color = "#31708f";
        //     statusList.appendChild(seeMoreButton);
        // }

        // if (showAll && suggestions.length >= visibleCount) {
        //     var seeLessButton = document.createElement("p");
        //     seeLessButton.id = "see-less-button";
        //     seeLessButton.textContent = "See Less";
        //     seeLessButton.style.cursor = "pointer";
        //     seeLessButton.style.color = "#31708f";
        //     suggestionList.appendChild(seeLessButton);
        // }
    }

    /**
     * Check application verification and process time
     * if the user have process permission
     * @type {jQuery}
     */
    {{-- @if (\App\Libraries\CommonFunction::getUserType() == '4x404' && in_array($appInfo->desk_id, [1, 2, 3, 4, 5])) --}}
    @if ($hasDeskDepartmentWisePermission && \App\Libraries\CommonFunction::getUserType() == '4x404')

    function getVerificationSession() {
        var setVerificationSession = '';
        var data_verification = $("#data_verification").val();
        var process_list_id = $("#process_list_id").val();
        $.get("{{ url('process-path/check-process-validity') }}",
            {
                data_verification: data_verification,
                process_list_id: process_list_id
            },
            function (data, status) {
                if (data.responseCode == 1) {
                    setVerificationSession = setTimeout(getVerificationSession, 120000);
                } else {
                    alert('Sorry, Data has been updated by another user.');
                    window.location.href =
                        "{{ url($moduleName . '/list/' . \App\Libraries\Encryption::encodeId($appInfo->process_type_id)) }}";
                }
            });
    }

    setVerificationSession = setTimeout(getVerificationSession, 120000);
    @endif

    var setSession = '';

    // This function is call two times from here and plane.blade
    //    function getSession() {
    //        $.get("/users/get-user-session", function (data, status) {
    //            if (data.responseCode == 1) {
    //                setSession = setTimeout(getSession, 8000);
    //            } else {
    //                alert('Your session has been closed. Please login again');
    //                window.location.replace('/login');
    //            }
    //        });
    //    }
    //
    //    setSession = setTimeout(getSession, 10000);

    // $('.maxTextCountDown ').characterCounter();
    // $('#mainInput').characterCounter();
</script>

<script>
    $loadedHistoryData = false;
    $(document).ready(function() {
        if ($loadedHistoryData == false) {
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/process/get-last-remarks",
                data: {
                    process_type_id: '{{ $appInfo->process_type_id }}',
                    process_list_id: '{{ $appInfo->process_list_id }}',
                    status_id: '{{ $appInfo->status_id }}',
                },
                success: function(response) {
                    if (response.response.status_code == 200) {
                        $('.attachmentArea').html(response.response.data);
                        $loadedHistoryData = true;
                    } else {
                        $html =
                            '<span class="text-danger">Unknown error occured! Please reload this modal again</span>'
                        $('.attachmentArea').html($html);
                    }
                }
            });
        }
    });
</script>