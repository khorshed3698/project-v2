{!! Form::open(array('url' => '/settings/sector/store-sub-sector/'.\App\Libraries\Encryption::encodeId($subSectorInfo->id),'method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'subSectorForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> <i class="fa fa-th-large"></i> Edit Sub Sector</h4>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>

    <div class="row">
        <div class="col-lg-12">
            <input name="sector_id" type="hidden" value="{{ \App\Libraries\Encryption::encodeId($subSectorInfo->sector_id) }}">
            <div class="form-group col-md-12 {{$errors->has('name') ? 'has-error' : ''}}">
                {!! Form::label('name','Name: ',['class'=>'col-md-2  required-star']) !!}
                <div class="col-md-10">
                    {!! Form::text('name', $subSectorInfo->name, ['class'=>'form-control required input-sm','placeholder'=>'sub sector name']) !!}
                    {!! $errors->first('name','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="form-group col-md-12 {{$errors->has('division_id') ? 'has-error' : ''}}">
                {!! Form::label('division_id','Division : ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-4">
                    {!! Form::select('division_id', $division_list, $subSectorInfo->division_id, ['class'=>'form-control required input-sm','placeholder'=>'Select one']) !!}
                    {!! $errors->first('division_id','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group col-md-12 {{$errors->has('status') ? 'has-error' : ''}}">
                {!! Form::label('status','Status: ',['class'=>'col-md-2 required-star']) !!}
                <div class="col-md-4 {{$errors->has('status') ? 'has-error' : ''}}">
                    <label>{!! Form::radio('status', 1, ($subSectorInfo->status == 1), ['class'=>'required']) !!} Active</label>
                    <label>{!! Form::radio('status', 0, ($subSectorInfo->status == 0), ['class'=>' required']) !!} Inactive</label>
                    {!! $errors->first('status','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer" style="text-align:left;">
    <div class="pull-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="pull-right">
        @if(ACL::getAccsessRight('settings','A'))
            <button type="submit" class="btn btn-primary" id="action_btn" name="actionBtn" value="draft">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


<script>
    $(document).ready(function () {
        $("#subSectorForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        var form = $("#subSectorForm"); //Get Form ID
        var url = form.attr("action"); //Get Form action
        var type = form.attr("method"); //get form's data send method
        var info_err = $('.errorMsg'); //get error message div
        var info_suc = $('.successMsg'); //get success message div

        //============Ajax Setup===========//
        function formSubmit() {
            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function (msg) {
                    console.log("before send");
                    $("#action_btn").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#action_btn").prop('disabled', true); // disable button
                },
                success: function (data) {
                    //==========validation error===========//
                    if (data.success == false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#action_btn").html('Submit');
                            $("#action_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            window.location.href = data.link;
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#action_btn").html('Submit');
                            $("#action_btn").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#action_btn").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });
</script>
