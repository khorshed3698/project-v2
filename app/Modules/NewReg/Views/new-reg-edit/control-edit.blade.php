<div class="panel-body">
    <form action="{{ url('/new-reg/save-reg-form') }}" method="post" id="first_form">
        {{ csrf_field() }}
        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

        <h3 class="text-center"><b>Registration Application {{$rjscOffice[$appInfo->entity_type_id]}}</b></h3>
    <p class="text-center"><b> Control Page</b></p>
    <div class="col-md-8 col-md-offset-2">

        <div class="panel panel-info">
            <div class="panel-heading">
                <strong>Select Entity Type</strong>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 {{$errors->has('entity_type_id') ? 'has-error': ''}}">
                            {!! Form::label('entity_type_id','Entity Type :',['class'=>'col-md-4 text-left']) !!}
                            <div class="col-md-6">
                                {!! Form::select('entity_type_id',$rjscOffice, $appInfo->entity_type_id,['class' => 'form-control input-md required','placeholder' => 'Select One','readonly']) !!}
                                {!! $errors->first('entity_type_id','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <strong>Enter Name Clearance Information</strong>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 {{$errors->has('submission_no') ? 'has-error': ''}}">
                            {!! Form::label('submission_no','Submission No :',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-6">
                                {!! Form::text('submission_no', $appInfo->submission_no,['class' => 'col-md-7 form-control input-md required','placeholder' => '','readonly']) !!}
                                {!! $errors->first('submission_no','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-8 col-md-offset-2 {{$errors->has('clearence_letter_no') ? 'has-error': ''}}">
                            {!! Form::label('clearence_letter_no','Clearance Letter No :',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-6">
                                {!! Form::text('clearence_letter_no', $appInfo->clearence_letter_no,['class' => 'col-md-7 form-control input-md required','placeholder' => '','readonly']) !!}
                                {!! $errors->first('clearence_letter_no','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
        @if(ACL::getAccsessRight('NewReg','-E-'))
            <div class="">
                <div class="col-md-6">
                    <button class="btn btn-info" value="draft" name="actionBtn" id="draft" type="submit">Save as Draft</button>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" name="actionBtn" value="save" id="save" type="submit">Save and continue</button>
                </div>
            </div>
        @endif
    </form>
</div>

<script>

    $(document).ready(function () {
        $(document).on('click','#draft',function () {
            $('#first_form').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $('#first_form').validate();
        });
    });
</script>