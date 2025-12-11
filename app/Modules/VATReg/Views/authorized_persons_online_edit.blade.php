<div class="panel-heading"><strong>M. AUTHORISED PERSONS INFORMATION FOR ONLINE ACTIVITY</strong></div>
<div class="panel-body">

    <p id="authorizedData" style="{{isset($appData->identity_category_authorized) ? 'display:none;' : ''}}"
       class="statictooltip"> Please
        click <a>
            <button id="authorized" type="button">here</button>
        </a> to provide Authorized Persons Information for Online Activity Information.
    </p>

    <div id="authorizedTable" style="{{isset($appData->identity_category_authorized) ? '' : 'display:none;'}}">
        <table id="authorizedInfo" class="table table-bordered table-hover"
               style="display: block;overflow-x: auto;white-space: nowrap;">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th>Full Name</th>
                <th>Designation</th>
                <th>Identity Category</th>
                <th>NID</th>
                <th>Passport No</th>
                <th>Nationality</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>purpose</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody id="authorized_body">
            @if(isset($appData->identity_category_authorized))
                <?php $inc = 0; ?>
                @foreach($appData->identity_category_authorized as $key => $value)
                    <?php
                    $gkey = ($key + 1);
                    ?>
                    <tr id="authorizedInfoRow{{$inc}}">

                        <td>
                            {!! Form::text('full_name_authorized['.$inc.']',isset($appData->full_name_authorized[$key]) ? $appData->full_name_authorized[$key] :'',['class' => ' form-control input-md required','id'=>"full_name_authorized_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::select('authorized_designation['.$inc.']',[],isset($appData->authorized_designation[$key]) ? $appData->authorized_designation[$key] :'',['class' => 'form-control input-md authorized_designation required','placeholder' => 'Select One','id'=>"authorized_designation_$gkey","authorized_designation_$gkey"=>$appData->authorized_designation[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::select('identity_category_authorized['.$inc.']',[],isset($appData->identity_category_authorized[$key]) ? $appData->identity_category_authorized[$key] :'',['class' => 'form-control input-md identity_category_authorized required' ,'placeholder' => 'Select One','id'=>"identity_category_authorized_$gkey","identity_category_authorized_$gkey"=>$appData->identity_category_authorized[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::text('authorized_nid['.$inc.']',isset($appData->authorized_nid[$key]) ? $appData->authorized_nid[$key] :'',['class' => ' form-control input-md authorized_nid nid required','placeholder' => '', 'id'=>"authorized_nid_$gkey",'style'=>'display:none']) !!}
                        </td>
                        <td>
                            {!! Form::text('authorized_passport_no['.$inc.']',isset($appData->authorized_passport_no[$key]) ? $appData->authorized_passport_no[$key] :'',['class' => ' form-control input-md authorized_passport_no required','placeholder' => '', 'id'=>"authorized_passport_no_$gkey",'style'=>'display:none']) !!}
                        </td>
                        <td>
                            {!! Form::select('authorized_nationality['.$inc.']',[],isset($appData->authorized_nationality[$key]) ? $appData->authorized_nationality[$key] :'',['class' => 'form-control input-md authorized_nationality required','style' => 'display:none','placeholder' => 'Select One', 'id'=>"authorized_nationality_$gkey","authorized_nationality_$gkey"=> isset($appData->authorized_nationality[$key]) ? $appData->authorized_nationality[$key] :'']) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}

                        </td>
                        <td>
                            {!! Form::text('authorized_mobile['.$inc.']',isset($appData->authorized_mobile[$key]) ? $appData->authorized_mobile[$key] :'',['class' => ' form-control input-md authorized_mobile onlyNumber mobile required',"maxlength"=>"11",'placeholder' => '','id'=>"authorized_mobile_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::text('authorized_email['.$inc.']',isset($appData->authorized_email[$key]) ? $appData->authorized_email[$key] :'',['class' => ' form-control input-md authorized_email required email','placeholder' => '','id'=>"authorized_email_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::select('purpose['.$inc.']',[],isset($appData->purpose[$key]) ? $appData->purpose[$key] :'',['class' => 'form-control input-md purpose required','placeholder' => 'Select One','id'=>"purpose_$gkey","purpose_$gkey"=>$appData->purpose[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td style="vertical-align: middle; text-align: center">
                            <?php if ($inc == 0) { ?>
                            <a class="btn btn-sm btn-primary addTableRows"
                               onclick="addTableRowSectionM('authorized_body', 'authorizedInfoRow{{$inc}}');"><i
                                        class="fa fa-plus"></i></a>
                            <?php } else { ?>
                            {{--                            @if($viewMode != 'on')--}}
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-danger removeRow"
                               onclick="removeTableRow('authorized_body','authorizedInfoRow{{$inc}}');">
                                <i class="fa fa-times" aria-hidden="true"></i></a>
                            {{--                            @endif--}}
                            <?php } ?>
                        </td>

                    </tr>
                    <?php $inc++; ?>
                @endforeach
            @else
                <tr id="authorizedInfoRow">

                    <td>
                        {!! Form::text('full_name_authorized[0]','',['class' => ' form-control input-md required','id'=>'full_name_authorized']) !!}
                    </td>
                    <td>
                        {!! Form::select('authorized_designation[0]',[],null,['class' => 'form-control input-md required authorized_designation','placeholder' => 'Select One','id'=>'authorized_designation_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::select('identity_category_authorized[0]',[],null,['class' => 'form-control input-md required identity_category_authorized','placeholder' => 'Select One','id'=>'identity_category_authorized_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('authorized_nid[0]','',['class' => ' form-control input-md required authorized_nid','placeholder' => '', 'id'=>'authorized_nid_1','style'=>'display:none']) !!}
                    </td>
                    <td>
                        {!! Form::text('authorized_passport_no[0]','',['class' => ' form-control input-md required authorized_passport_no','placeholder' => '', 'id'=>'authorized_passport_no_1','style'=>'display:none']) !!}
                    </td>
                    <td>
                        {!! Form::select('authorized_nationality[0]',[],null,['class' => 'form-control input-md required authorized_nationality','style' => 'display:none','placeholder' => 'Select One', 'id'=>'authorized_nationality_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}

                    </td>
                    <td>
                        {!! Form::text('authorized_mobile[0]','',['class' => ' form-control input-md required authorized_mobile mobile onlyNumber',"maxlength"=>"11",'placeholder' => '','id'=>'authorized_mobile_1']) !!}
                    </td>
                    <td>
                        {!! Form::text('authorized_email[0]','',['class' => ' form-control input-md required authorized_email email','placeholder' => '','id'=>'authorized_email_1']) !!}
                    </td>
                    <td>
                        {!! Form::select('purpose[0]',[],null,['class' => 'form-control input-md required purpose','placeholder' => 'Select One','id'=>'purpose_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td style="vertical-align: middle; text-align: center">
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add more"
                           onclick="addTableRowSectionM('authorized_body', 'authorizedInfoRow');">
                            <i class="fa fa-plus"></i></a>
                    </td>

                </tr>
            @endif
            </tbody>
        </table>
    </div>
    <p id="authorizedCloseData" style="{{isset($appData->identity_category_authorized) ? '' : 'display:none;'}}"> Please
        click <a>
            <button id="authorizedClose" type="button">here</button>
        </a> to hide Authorized Persons Information for Online Activity Information.
    </p>

    <div class="clearfix">
    </div>
</div>

<script>

    // Add table Row script
    function addTableRowSectionM(tableID, templateRow) {
        $('.authorized_nationality').select2('destroy');
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

        $('.authorized_nationality').select2();
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
        <?php if(!isset($appData->identity_category_authorized)){?>
        $("#authorizedTable").find("input,button,textarea,select").attr("disabled", "disabled");
        <?php } ?>
        $('#authorized').click(function () {
            $('#authorizedTable').show();
            $('#authorizedData').hide();
            $('#authorizedCloseData').show();
            $("#authorizedTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#authorizedClose').click(function () {
            $('#authorizedTable').hide();
            $('#authorizedData').show();
            $('#authorizedCloseData').hide();
            $("#authorizedTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });
    });

    $(document).on('change', '.identity_category_authorized', function () {
        var row = $(this).closest("tr").index() + 1;
        var category = $(this).val();
        var categoryId = category.split("@")[0];
        if (categoryId == 1) {
            $('#authorized_nid_' + row).show()
            $('#authorized_nid_' + row).removeAttr('disabled')
            $('#authorized_passport_no_' + row).hide()
            $('#authorized_passport_no_' + row).attr('disabled', 'disabled')
            $('#authorized_nationality_' + row).hide()
            $('#authorized_nationality_' + row).attr('disabled', 'disabled')
        } else if (categoryId == 2) {
            $('#authorized_nid_' + row).hide()
            $('#authorized_nid_' + row).attr('disabled', 'disabled')
            $('#authorized_passport_no_' + row).show()
            $('#authorized_passport_no_' + row).removeAttr('disabled')
            $('#authorized_nationality_' + row).show()
            $('#authorized_nationality_' + row).removeAttr('disabled')
        } else {
            $('#authorized_nid_' + row).hide()
            $('#authorized_nid_' + row).attr('disabled', 'disabled');
            $('#authorized_passport_no_' + row).hide()
            $('#authorized_passport_no_' + row).attr('disabled', 'disabled')
            $('#authorized_nationality_' + row).hide()
            $('#authorized_nationality_' + row).attr('disabled', 'disabled')

        }
    });


</script>