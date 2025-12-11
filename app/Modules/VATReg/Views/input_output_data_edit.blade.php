<style>

    .listOutput {
        width: 80%;
    }

    body.modal-open {
        overflow: visible;
    }

    #listOutput_filter {
        margin-left: 50%;
    }
</style>

<div class="col-md-6">
    {!! Form::label('','L7. Input-Output Data', ['class'=>'col-md-5 required-star']) !!}
    <div class="col-md-7" style="{{isset($appData->commercial_description_output) ? 'display:none;' : ''}}">
        <p id="InputOutputData" class="statictooltip">Please click <a>
                <button id="InputOutput"
                        type="button">here
                </button>
            </a> to provide Input-Output Data
            Information.
        </p>
    </div>
</div>

<div class="panel-body">
    <div id="InputOutputTable"
         style="{{isset($appData->commercial_description_output) ? '' : 'display:none;'}}">
        <table id="InputOutputInfo" class="table table-bordered table-hover"
               style="display: block;overflow-x: auto;white-space: nowrap;">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th width="20%">Commercial Description of Output</th>
                <th width="13%">HS/Service Code Output</th>
                <th width="15%">Selling Unit</th>
                <th width="19%">Description of Major Inputs</th>
                <th width="13%">HS/Service Code Input</th>
                <th width="15%">Quantity of Input used in per Unit of Output</th>
                <th width="15%">Action</th>
            </tr>
            </thead>
            <tbody id="InputOutput_body">
            @if(isset($appData->commercial_description_output))
                <?php $inc = 0; ?>
                @foreach($appData->commercial_description_output as $key => $value)
                    <?php
                    $gkey = ($key + 1);
                    ?>
                    <tr id="InputOutput_row{{$inc}}">
                        <td>
                            {!! Form::text('commercial_description_output['.$inc.']',isset($appData->commercial_description_output[$key]) ? $appData->commercial_description_output[$key] :'',['class' => ' form-control input-md required','id' => "commercial_description_output_$gkey"]) !!}
                            {!! $errors->first('commercial_description_output','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::hidden('hs_code_output_hidden['.$inc.']',!empty($appData->hs_code_output_hidden[$key]) ? $appData->hs_code_output_hidden[$key] :'',[ 'id'=>"hs_code_output_hidden_$gkey"]) !!}
                            <div class="input-group">
                                {!! Form::text('hs_code_output['.$inc.']',!empty($appData->hs_code_output[$key]) ? $appData->hs_code_output[$key] :'',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>"hs_code_output_$gkey",'readonly']) !!}
                                <span class="input-group-addon serviceData" data-toggle="modal" data-target="#myModal"
                                      data-type="dataOutput"
                                      id="{{$gkey}}"><i class="fa fa-bars"></i></span>
                            </div>
                        </td>
                        <td>
                            {!! Form::text('selling_unit['.$inc.']',isset($appData->selling_unit[$key]) ? $appData->selling_unit[$key] :'',['class' => ' form-control input-md required','id' => "selling_unit_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::text('description_major_inputs['.$inc.']',isset($appData->description_major_inputs[$key]) ? $appData->description_major_inputs[$key] :'',['class' => ' form-control input-md required','id' => "description_major_inputs_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::hidden('hs_code_input_hidden['.$inc.']',!empty($appData->hs_code_input_hidden[$key]) ? $appData->hs_code_input_hidden[$key] :'',[ 'id'=>"hs_code_input_hidden_$gkey"]) !!}
                            <div class="input-group">
                                {!! Form::text('hs_code_input['.$inc.']',!empty($appData->hs_code_input[$key]) ? $appData->hs_code_input[$key] :'',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>"hs_code_input_$gkey",'readonly']) !!}
                                <span class="input-group-addon serviceData" data-toggle="modal" data-target="#myModal"
                                      data-type="dataInput"
                                      id="{{$gkey}}"><i class="fa fa-bars"></i></span>
                            </div>
                        </td>

                        <td>
                            {!! Form::text('quantity_used['.$inc.']',isset($appData->quantity_used[$key]) ? $appData->quantity_used[$key] :'',['class' => ' form-control input-md required','id' => "quantity_used_$gkey"]) !!}
                        </td>
                        <td style="vertical-align: middle; text-align: center">
                            <?php if ($inc == 0) { ?>
                            <a class="btn btn-sm btn-primary addTableRows"
                               onclick="addTableRowInputOutput('InputOutput_body', 'InputOutput_row{{$inc}}');"><i
                                        class="fa fa-plus"></i></a>
                            <?php } else { ?>
                            {{--                            @if($viewMode != 'on')--}}
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-danger removeRow"
                               onclick="removeTableRow('InputOutput_body','InputOutput_row{{$inc}}');">
                                <i class="fa fa-times" aria-hidden="true"></i></a>
                            {{--                            @endif--}}
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $inc++; ?>
                @endforeach
            @else
                <tr id="InputOutput_row">
                    <td>
                        {!! Form::text('commercial_description_output[0]','',['class' => ' form-control input-md required','id' => 'commercial_description_output_1']) !!}
                        {!! $errors->first('commercial_description_output','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::hidden('hs_code_output_hidden[0]','',[ 'id'=>'hs_code_output_hidden_1']) !!}
                        <div class="input-group">
                            {!! Form::text('hs_code_output[0]','',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>'hs_code_output_1','readonly']) !!}
                            <span class="input-group-addon serviceData" data-toggle="modal" data-target="#myModal"
                                  data-type="dataOutput"
                                  id="1"><i class="fa fa-bars"></i></span>
                        </div>
                    </td>
                    <td>
                        {!! Form::text('selling_unit[0]','',['class' => ' form-control input-md required','id' => 'selling_unit']) !!}
                    </td>
                    <td>
                        {!! Form::text('description_major_inputs[0]','',['class' => ' form-control input-md required','id' => 'description_major_inputs']) !!}
                    </td>
                    <td>
                        {!! Form::hidden('hs_code_input_hidden[0]','',[ 'id'=>'hs_code_input_hidden_1']) !!}

                        <div class="input-group">
                            {!! Form::text('hs_code_input[0]','',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>'hs_code_input_1','readonly']) !!}
                            <span class="input-group-addon serviceData" data-toggle="modal" data-target="#myModal"
                                  data-type="dataInput"
                                  id="1"><i class="fa fa-bars"></i></span>
                        </div>
                    </td>

                    <td>
                        {!! Form::text('quantity_used[0]','',['class' => ' form-control input-md required','id' => 'quantity_used']) !!}
                    </td>
                    <td style="vertical-align: middle; text-align: center">
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add more"
                           onclick="addTableRowInputOutput('InputOutput_body', 'InputOutput_row');">
                            <i class="fa fa-plus"></i></a>
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <p id="InputOutputCloseData" style="{{isset($appData->commercial_description_output) ? '' : 'display:none;'}}">
        Please click <a>
            <button id="InputOutputClose" type="button">here</button>
        </a> to hide Input-Output Data
        Information.
    </p>

    <div class="clearfix">
    </div>
</div>
<script>
    // Add table Row script
    function addTableRowInputOutput(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        // var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount + 2;
        var nameRo = rowCo - 1;
        var idText = 'rowCount' + tableID + rowCo;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');


        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var selectId = attrSel[i].id;
            var repText = nameAtt.replace('[0]', '[' + nameRo + ']'); //increment all array element name
            var ret = selectId.replace('_1', '');
            var repTextId = ret + '_' + rowCo;
            attrSel[i].id = repTextId;
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset
        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            var inputId = attrInput[i].id;
            var repText = nameAtt.replace('[0]', '[' + nameRo + ']'); //increment all array element name
            var ret = inputId.replace('_1', '');
            var repTextId = ret + '_' + rowCo;
            attrInput[i].id = repTextId;
            attrInput[i].name = repText;
        }
        attrInput.val(''); //value reset

        //get input elements
        var attrSpan = $("#" + tableID).find('#' + idText).find('span');
        for (var i = 0; i < attrSpan.length; i++) {
            var spanId = attrSpan[i].id;
            var ret = spanId.replace('1', '');
            var repTextId = rowCo;
            attrSpan[i].id = repTextId;
        }
        attrSpan.val(''); //value reset

        //edited by ishrat to solve textarea id auto increment related bug
        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {
            var nameAtt = attrTextarea[i].name;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + nameRo + ']');
            attrTextarea[i].name = repText;
            $('#' + idText).find('.readonlyClass').prop('readonly', true);
        }
        attrTextarea.val(''); //value reset
        // end of  solving issue related textarea id auto increment related bug by ishrat
        attrSel.prop('selectedIndex', 0);
        if ((tableID === 'machinaryTbl' && templateRow === 'rowMachineCount0') || (tableID === 'machinaryTbl' && templateRow === 'rowMachineCount')) {
            $("#" + tableID).find('#' + idText).find('select.m_currency').val("107");  //selected index reset
        } else {
            attrSel.prop('selectedIndex', 0);  //selected index reset
        }
        //$('.m_currency ').prop('selectedIndex', 102);
        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCount);

        $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
            //period decimal
            if ((e.which >= 48 && e.which <= 57)
                //numpad decimal
                || (e.which >= 96 && e.which <= 105)
                // Allow: backspace, delete, tab, escape, enter and .
                || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
                // Allow: Ctrl+A
                || (e.keyCode == 65 && e.ctrlKey === true)
                // Allow: Ctrl+C
                || (e.keyCode == 67 && e.ctrlKey === true)
                // Allow: Ctrl+V
                || (e.keyCode == 86 && e.ctrlKey === true)
                // Allow: Ctrl+X
                || (e.keyCode == 88 && e.ctrlKey === true)
                // Allow: home, end, left, right
                || (e.keyCode >= 35 && e.keyCode <= 39)) {
                var $this = $(this);
                setTimeout(function () {
                    $this.val($this.val().replace(/[^0-9.]/g, ''));
                }, 4);
                var thisVal = $(this).val();
                if (thisVal.indexOf(".") != -1 && e.key == '.') {
                    return false;
                }
                $(this).removeClass('error');
                return true;
            } else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^.0-9]/g, ''));
            }, 4);
        });


    } // end of addTableRowTraHis() function
    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
    }

    $(document).ready(function () {
        <?php if(!isset($appData->commercial_description_output)){?>
        $("#InputOutputTable").find("input,button,textarea,select").attr("disabled", "disabled");
        <?php } ?>
        $('#InputOutput').click(function () {
            $('#InputOutputTable').show();
            $('#InputOutputData').hide();
            $('#InputOutputCloseData').show();
            $("#InputOutputTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#InputOutputClose').click(function () {
            $('#InputOutputTable').hide();
            $('#InputOutputData').show();
            $('#InputOutputCloseData').hide();
            $("#InputOutputTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });
    });

</script>