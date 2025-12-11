<?php
if (!ACL::getAccsessRight($aclName, $mode)) {
    die('You have no access right! Contact with system admin for more information.');
}
?>


{!! Form::open(array('url' => '/basic-information/store-change-dept','method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'changeCompanyForm',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span>
    </button>
    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-th-large"></i> Change Department and Sub-department</h4>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
    </div>

    {!! Form::hidden('app_id', $app_id) !!}
    {!! Form::hidden('company_id', $company_id) !!}

    <div class="form-group">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 {{$errors->has('service_type') ? 'has-error': ''}}">
                {!! Form::label('service_type','Desired Service from BIDA',['class'=>'col-md-3 text-left required-star', 'id'=> 'service_type_label']) !!}
                <div class="col-md-9">
                    {!! Form::select('service_type', $eaService, '',['class'=>'form-control required input-md', 'id' => 'service_type']) !!}
                    {!! $errors->first('service_type','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="form-group" id="RegCommercialOfficesDiv">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <label class="col-md-3"></label>
                <div class="col-md-9">
                    {!! Form::select('reg_commercial_office',$eaRegCommercialOffices, '',['class'=>'form-control input-md required', 'id' => 'reg_commercial_office', ]) !!}
                    {!! $errors->first('reg_commercial_office','<span class="help-block">:message</span>') !!}
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 {{$errors->has('change_dept_reason') ? 'has-error' : ''}}">
                {!! Form::label('change_dept_reason','Change reason',['class'=>'col-md-3']) !!}
                <div class="col-md-9">
                    {!! Form::textarea('change_dept_reason', '', ['class' => 'form-control input-md bigInputField maxTextCountDown', 'size' =>'5x2','data-charcount-maxlength'=>'200', 'placeholder' => 'Maximum 200 characters']) !!}
                    {!! $errors->first('change_dept_reason','<span class="help-block">:message</span>') !!}
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
        @if(ACL::getAccsessRight('BasicInformation','-CD-'))
            <button type="submit" class="btn btn-primary" id="action_btn">
                <i class="fa fa-chevron-circle-right"></i> Save
            </button>
        @endif
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


<script>
    $(document).ready(function () {

        $.getScript("/vendor/character-counter/jquery.character-counter_v1.0.min.js")
            .done(function (script, textStatus) {
                $('.maxTextCountDown').characterCounter();
            })
            .fail(function (jqxhr, settings, exception) {
                alert('Unknown error occurred while resource loading. Please try again');
            });

        $("#service_type").change(function (e) {
            var service_value = this.value;
            if (service_value == 5) { // 5 = Registered Commercial Offices
                $("#RegCommercialOfficesDiv").show();
            } else {
                $("#RegCommercialOfficesDiv").hide();
            }
        });
        $('#service_type').trigger('change');


        $("#changeCompanyForm").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        var form = $("#changeCompanyForm"); //Get Form ID
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
                        info_err.delay(8000).slideUp(1000, function () {
                            $("#action_btn").html('Submit');
                            $("#action_btn").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(8000).slideUp(800, function () {
                            location.reload();
                        });
                        form.trigger("reset");

                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(8000).slideUp(800, function () {
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