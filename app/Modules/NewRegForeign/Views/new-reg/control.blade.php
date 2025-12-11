<div class="panel-body">
    <form action="{{ url('/new-reg/save-reg-form') }}" method="post" id="first_form">
        {{ csrf_field() }}
    <h3 class="text-center"><b>Registration Application</b></h3>
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
                                {!! Form::select('entity_type_id',$rjscOffice, null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                {!! Form::text('submission_no','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                {!! $errors->first('submission_no','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-8 col-md-offset-2 {{$errors->has('clearence_letter_no') ? 'has-error': ''}}">
                            {!! Form::label('clearence_letter_no','Clearance Letter No :',['class'=>'col-md-5 text-left']) !!}
                            <div class="col-md-6">
                                {!! Form::text('clearence_letter_no','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                {!! $errors->first('clearence_letter_no','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

        <div class="">
            <div class="col-md-6">
                <button class="btn btn-info" id="draft" name="actionBtn" value="draft" type="submit">Save as Draft</button>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-success" id="save" name="actionBtn" value="save" type="submit">Save and continue</button>
            </div>
        </div>
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