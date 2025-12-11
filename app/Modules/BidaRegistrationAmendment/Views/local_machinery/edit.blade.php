{!! Form::open(array('url' => '/bida-registration-amendment/local-machinery-update','method' => 'post', 'class' => 'form-horizontal smart-form','id'=>'machineryFormImport',
        'enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
    <h4 class="modal-title" id="myModalLabel"> Edit Local Machinery</h4>
</div>

<div class="modal-body">
    <div class="errorMsg alert alert-danger alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>
    <div class="successMsg alert alert-success alert-dismissible hidden">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button></div>


    <div class="table-responsive">
        <table id="directorTable" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%" aria-label="Detailed Report Data Table">
            <thead>
            <tr>
                <th scope="col" class="bg-yellow" colspan="4">Existing information (Latest BIDA Reg. Info.)</th>
                <th scope="col" class="bg-green" colspan="5">Proposed information</th>
            </tr>
            <tr>
                <th scope="col" class="light-yellow">Name of machineries</th>
                <th scope="col" class="light-yellow">Quantity</th>
                <th scope="col" class="light-yellow">Unit prices TK</th>
                <th scope="col" class="light-yellow">Total value (Million) TK</th>

                <th scope="col" class="light-green">Name of machineries</th>
                <th scope="col" class="light-green">Quantity</th>
                <th scope="col" class="light-green">Unit prices TK</th>
                <th scope="col" class="light-green">Total value (Million) TK</th>
                <th scope="col" class="light-green">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <input type="hidden" name="lm_id" value="{{ $getlocalMachinery->id }}">
                <td class="light-yellow">
                    {!! Form::text('l_machinery_local_name', $getlocalMachinery->l_machinery_local_name, ['class' => 'form-control input-md machinery_imported_name', 'id' => 'l_machinery_local_name']) !!}
                    {!! $errors->first('l_machinery_local_name','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-yellow">
                    {!! Form::text('l_machinery_local_qty', $getlocalMachinery->l_machinery_local_qty, ['class' => 'form-control input-md number', 'id' => 'l_machinery_local_qty']) !!}
                    {!! $errors->first('l_machinery_local_qty','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-yellow">
                    {!! Form::text('l_machinery_local_unit_price', $getlocalMachinery->l_machinery_local_unit_price, ['class' => 'form-control input-md number', 'id' => 'l_machinery_local_unit_price']) !!}
                    {!! $errors->first('l_machinery_local_unit_price','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-yellow">
                    {!! Form::text('l_machinery_local_total_value', $getlocalMachinery->l_machinery_local_total_value, ['class' => 'form-control input-md number', 'id' => 'l_machinery_local_total_value']) !!}
                    {!! $errors->first('l_machinery_local_total_value','<span class="help-block">:message</span>') !!}
                </td>

                <td class="light-green">
                    {!! Form::text('n_l_machinery_local_name', $getlocalMachinery->n_l_machinery_local_name, ['class' => 'form-control input-md machinery_imported_name', 'id' => 'n_l_machinery_local_name']) !!}
                    {!! $errors->first('n_l_machinery_local_name','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-green">
                    {!! Form::text('n_l_machinery_local_qty', $getlocalMachinery->n_l_machinery_local_qty, ['class' => 'form-control input-md number', 'id' => 'n_l_machinery_local_qty']) !!}
                    {!! $errors->first('n_l_machinery_local_qty','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-green">
                    {!! Form::text('n_l_machinery_local_unit_price', $getlocalMachinery->n_l_machinery_local_unit_price, ['class' => 'form-control input-md number', 'id' => 'n_l_machinery_local_unit_price']) !!}
                    {!! $errors->first('n_l_machinery_local_unit_price','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-green">
                    {!! Form::text('n_l_machinery_local_total_value', $getlocalMachinery->n_l_machinery_local_total_value, ['class' => 'form-control input-md number', 'id' => 'n_l_machinery_local_total_value']) !!}
                    {!! $errors->first('n_l_machinery_local_total_value','<span class="help-block">:message</span>') !!}
                </td>
                <td class="light-green">
                    {!! Form::select("amendment_type", $amendment_type, 'edit', ['class'=>'form-control input-md apc-action', 'id' => 'amendment_type0', 'onchange' => 'actionWiseFieldDisable(this, ["l_machinery_local_name", "l_machinery_local_qty", "l_machinery_local_unit_price", "l_machinery_local_total_value"], ["n_l_machinery_local_name", "n_l_machinery_local_qty", "n_l_machinery_local_unit_price", "n_l_machinery_local_total_value"])']) !!}
                    {!! $errors->first('action','<span class="help-block">:message</span>') !!}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="clearfix"></div>
</div>

<div class="modal-footer" style="text-align:left;">
    <div class="pull-left">
        {!! Form::button('<i class="fa fa-times"></i> Close', array('type' => 'button', 'class' => 'btn btn-danger', 'data-dismiss' => 'modal')) !!}
    </div>
    <div class="pull-right">
        <button type="submit" class="btn btn-primary" id="machinery_create_btn" name="actionBtn" value="draft">
            <i class="fa fa-chevron-circle-right"></i> Save
        </button>
    </div>
    <div class="clearfix"></div>
</div>
{!! Form::close() !!}


{{-- <script>
    $("#amendment_type0").trigger('change');

    $(document).ready(function () {
        $("#machineryFormImport").validate({
            errorPlacement: function () {
                return true;
            },
            submitHandler: formSubmit
        });

        var form = $("#machineryFormImport"); //Get Form ID
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
                    $("#Duplicated jQuery selector").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#Duplicated jQuery selector").prop('disabled', true); // disable button
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
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                    //==========if data is saved=============//
                    if (data.success == true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            $("#braModal").modal('hide');
                        });
                        form.trigger("reset");

                        loadLocalMachineryData(20, 'off');
                    }
                    //=========if data already submitted===========//
                    if (data.error == true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#Duplicated jQuery selector").prop('disabled', false);
                    console.log(errors);
                    alert('Sorry, an unknown Error has been occured! Please try again later.');
                }
            });
            return false;
        }
    });
</script> --}}

<script>
    $("#amendment_type0").trigger('change');

    $(document).ready(function () {
        $("#machineryFormImport").validate({
            errorPlacement: function () {
                return true;
            },
            rules: {
                'l_machinery_local_qty': {
                    min: 0.01
                },
                'l_machinery_local_unit_price': {
                    min: 0.01
                },
                'l_machinery_local_total_value': {
                    min: 0.01
                },
                'n_l_machinery_local_qty': {
                    min: 0.01
                },
                'n_l_machinery_local_unit_price': {
                    min: 0.01
                },
                'n_l_machinery_local_total_value': {
                    min: 0.01
                },
            },
            submitHandler: function () {
                if (validateRows()) {
                    formSubmit();
                }
            },
        });

        // Validate all rows with fields starting with `n_`
        function validateRows() {
            let isValid = true;
            let amendment_type = $('#amendment_type0').val();
            var is_bra_approval_manually = $("input[name='is_bra_approval_manually']").val() || '';

            if(amendment_type == 'remove') {
                return true;
            }

            $('#directorTable tbody tr').each(function () {
                let hasValue = false;
                let isRowComplete = true;
                let hasValueWithoutN = false;
                let isRowCompleteWithoutN = true;
                let isAllNotFilled = false;

                // Iterate over all input fields starting with 'n_'
                $(this).find('input[name^="n_"]').each(function () {
                    let value = $(this).val().trim();

                    if (value !== '') {
                        hasValue = true;
                    } else {
                        isRowComplete = false;
                    }
                });
                $(this).find('input:not([name^="n_"]):not([name="amendment_type"]):not([name="lm_id"])').each(function() {
                    let value = $(this).val().trim();
                    if (value !== '') {
                        hasValueWithoutN = true;
                    } else {
                        isRowCompleteWithoutN = false;
                    }
                });
                $(this).find('input:not([name="amendment_type"]):not([name="lm_id"])').each(function() {
                    let value = $(this).val().trim();
                    if (value !== '') {
                        isAllNotFilled = true;
                    }
                });
                if(!isAllNotFilled){
                    isValid = false;
                }

                // If any field is filled but the row is incomplete, mark as invalid
                if (hasValue && !isRowComplete) {
                    isValid = false;

                    // Highlight empty fields
                    $(this).find('input[name^="n_"]').each(function () {
                        if ($(this).val().trim() === '') {
                            $(this).addClass('error');
                        } else {
                            $(this).removeClass('error');
                        }
                    });
                } else if(!hasValue && !isRowComplete && is_bra_approval_manually == 'no') {
                    isValid = false;
                    $(this).find('input[name^="n_"]').addClass('error');
                }
                else {
                    $(this).find('input[name^="n_"]').removeClass('error');
                }
                if (hasValueWithoutN && !isRowCompleteWithoutN) {
                    isValid = false;
                    $(this).find('input:not([name^="n_"])').each(function() {
                        if ($(this).val().trim() === '') {
                            $(this).addClass('error');
                        } else {
                            $(this).removeClass('error');
                        }
                    });

                }else {
                    $(this).find('input:not([name^="n_"])').removeClass('error');
                }
            });
            let errorMsg = document.querySelector('.errorMsg');
            errorMsg.classList.add('hidden');
            errorMsg.innerHTML = '';

            // Alert user if the validation fails
            if (!isValid) {
                // alert('Please fill all required fields.');
                errorMsg.classList.remove('hidden');
                errorMsg.innerHTML = 'Please fill all required fields.';
            }

            return isValid;
        }

        // Existing AJAX form submission logic
        function formSubmit() {
            let form = $("#machineryFormImport");
            let url = form.attr("action");
            let type = form.attr("method");
            let info_err = $('.errorMsg');
            let info_suc = $('.successMsg');

            $.ajax({
                type: type,
                url: url,
                data: form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $("#Duplicated jQuery selector").html('<i class="fa fa-cog fa-spin"></i> Loading...');
                    $("#Duplicated jQuery selector").prop('disabled', true);
                },
                success: function (data) {
                    if (data.success === false) {
                        info_err.hide().empty();
                        $.each(data.error, function (index, error) {
                            info_err.removeClass('hidden').append('<li>' + error + '</li>');
                        });
                        info_err.slideDown('slow');
                        info_err.delay(2000).slideUp(1000, function () {
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                    if (data.success === true) {
                        info_suc.hide().empty();
                        info_suc.removeClass('hidden').html(data.status);
                        info_suc.slideDown('slow');
                        info_suc.delay(2000).slideUp(800, function () {
                            $("#braModal").modal('hide');
                        });
                        form.trigger("reset");
                        loadLocalMachineryData(20, 'off');
                    }
                    if (data.error === true) {
                        info_err.hide().empty();
                        info_err.removeClass('hidden').html(data.status);
                        info_err.slideDown('slow');
                        info_err.delay(1000).slideUp(800, function () {
                            $("#Duplicated jQuery selector").html('Submit');
                            $("#Duplicated jQuery selector").prop('disabled', false);
                        });
                    }
                },
                error: function (data) {
                    var errors = data.responseJSON;
                    $("#Duplicated jQuery selector").prop('disabled', false);
                    alert('Sorry, an unknown error has occurred! Please try again later.');
                    console.log(errors);
                }
            });
        }
    });

</script>
