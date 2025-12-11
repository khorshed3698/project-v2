<div class="panel-heading"><strong>J. BANK ACCOUNT DETAILS</strong></div>
<div class="panel-body">
    <p id="bankaccountData" style="{{isset($appData->bank_name) ? 'display:none;' : ''}}" class="statictooltip">Please
        click
        <a>
            <button id="bankaccount" type="button">here</button>
        </a> to provide Bank Account Information.
    </p>
    <div id="bankaccountTable" style="{{isset($appData->bank_name) ? '' : 'display:none;'}}">
        <table id="bankaccountInfo" class="table table-bordered table-hover">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th width="25%">Account Name</th>
                <th width="25%">Account Number</th>
                <th width="25%">Bank Name</th>
                <th width="20%">Branch</th>
                <th width="5%">Action</th>
            </tr>
            </thead>
            <tbody id="bankaccount_body">

            @if(isset($appData->bank_name))
                <?php $inc = 0; ?>
                @foreach($appData->bank_name as $key => $value)
                    <?php
                    $gkey = ($key + 1);
                    ?>
                    <tr id="bankAccountRow{{$inc}}">
                        <td>
                            {!! Form::text('account_name['.$inc.']',isset($appData->account_name[$key]) ? $appData->account_name[$key] :'',['class' => 'form-control input-md required','placeholder'
                            => '','id'=>"account_name_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::text('account_number['.$inc.']',isset($appData->account_number[$key]) ? $appData->account_number[$key] :'',['class' => 'form-control required input-md','id'=>"account_number_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::select('bank_name['.$inc.']',[],isset($appData->bank_name[$key]) ? $appData->bank_name[$key] :'',['class' =>'form-control input-md bank_name required',
                              'id'=>"bank_name_$gkey", "bank_name_$gkey"=>$appData->bank_name[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::select('branch_name['.$inc.']',[],isset($appData->branch_name[$key]) ? $appData->branch_name[$key] :'',['class' => 'form-control input-md branch_name required','placeholder' =>
                            'Select One','id'=>"branch_name_$gkey", "branch_name_$gkey"=>$appData->branch_name[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td style="vertical-align: middle; text-align: center">
                            <?php if ($inc == 0) { ?>
                            <a class="btn btn-sm btn-primary addTableRows"
                               onclick="addTableRowSectionJ('bankaccount_body', 'bankAccountRow{{$inc}}');"><i
                                        class="fa fa-plus"></i></a>
                            <?php } else { ?>
                            {{--                            @if($viewMode != 'on')--}}
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-danger removeRow"
                               onclick="removeTableRow('bankaccount_body','bankAccountRow{{$inc}}');">
                                <i class="fa fa-times" aria-hidden="true"></i></a>
                            {{--                            @endif--}}
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $inc++; ?>
                @endforeach
            @else
                <tr id="bankAccountRow">
                    <td>
                        {!! Form::text('account_name[0]',null,['class' => 'form-control input-md required','placeholder'
                        => '','id'=>"account_name_1"]) !!}
                        {!! $errors->first('account_name','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('account_number[0]',null,['class' => 'form-control input-md required','placeholder'=> '','id'=>"account_number_1"]) !!}
                    </td>
                    <td>
                        {!! Form::select('bank_name[0]',[],null,['class' =>'form-control input-md bank_name required',
                          'id'=>'bank_name_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::select('branch_name[0]',[],null,['class' => 'form-control input-md branch_name required','placeholder' =>
                        'Select One','id'=>'branch_name_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td style="vertical-align: middle; text-align: center">
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add more"
                           onclick="addTableRowSectionJ('bankaccount_body', 'bankAccountRow');">
                            <i class="fa fa-plus"></i></a>
                    </td>

                </tr>
            @endif

            </tbody>
        </table>
    </div>
    <p id="bankaccountCloseData" style="{{isset($appData->bank_name) ? '' : 'display:none;'}}"> Please click <a>
            <button id="bankaccountClose" type="button">here</button>
        </a> to hide Bank Account
        Information.
    </p>

    <div class="clearfix">
    </div>
</div>

<script>
    // Add table Row script
    // Add table Row script
    function addTableRowSectionJ(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        $('.bank_name').select2('destroy');
        $('.branch_name').select2('destroy');
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

        $('.bank_name').select2();
        $('.branch_name').select2();
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
        <?php if(!isset($appData->bank_name)){?>
        $("#bankaccountTable").find("input,button,textarea,select").attr("disabled", "disabled");
        <?php } ?>
        $('#bankaccount').click(function () {
            $('#bankaccountTable').show();
            $('#bankaccountData').hide();
            $('#bankaccountCloseData').show();
            $("#bankaccountTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#bankaccountClose').click(function () {
            $('#bankaccountTable').hide();
            $('#bankaccountData').show();
            $('#bankaccountCloseData').hide();
            $("#bankaccountTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });

    });


</script>