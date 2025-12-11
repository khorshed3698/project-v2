<div class="panel-heading"><strong>E. LIST OF BRANCH UNITS YOU WISH TO BRING UNDER CENTRAL REGISTRATION</strong></div>
<div class="panel-body">
    <p id="branchData" style="{{isset($appData->branch_address) ? 'display:none;' : ''}}" class="statictooltip"> Please
        click <a>
            <button
                    id="branchInfo" type="button">here
            </button>
        </a> to provide Branch
        Information.
    </p>
    <div id="branchTable" style="{{isset($appData->branch_address) ? '' : 'display:none;'}}">
        <table id="branch" class="table table-bordered table-hover">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th width="20%">Branch Address</th>
                <th width="20%">Branch Name</th>
                <th width="20%">Branch Category</th>
                <th width="15%">Annual Turnover</th>
                <th width="10%">BIN</th>
                <th width="10%">Branch ID</th>
                <th width="10%">Action</th>
            </tr>
            </thead>
            <tbody id="branch_body">
            @if(isset($appData->branch_address))
                <?php $inc = 0; ?>
                @foreach($appData->branch_address as $key => $value)
                    <?php
                    $gkey = ($key + 1);
                    ?>
                    <tr id="branchInfoRow{{$inc}}">
                        <td>
                            {!! Form::text('branch_address['.$inc.']',isset($appData->branch_address[$key]) ? $appData->branch_address[$key] :'',['class' => 'col-md-7 form-control input-md required','id'=>"branch_address_$gkey"]) !!}
                            {!! $errors->first('branch_address','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::text('e_branch_name['.$inc.']',isset($appData->e_branch_name[$key]) ? $appData->e_branch_name[$key] :'',['class' => 'col-md-7 form-control input-md required','id'=>"e_branch_name_$gkey"]) !!}
                            {!! $errors->first('e_branch_name','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::select('branch_category['.$inc.']',[],isset($appData->branch_category[$key]) ? $appData->branch_category[$key]:'',['class' => 'form-control input-md required branch_category','placeholder' =>
                            'Select one','id'=>"branch_category_$gkey", "branch_category_$gkey"=>$appData->branch_category[$key]]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </td>
                        <td>
                            {!! Form::text('annual_turnover['.$inc.']',isset($appData->annual_turnover[$key]) ? $appData->annual_turnover[$key] :'',['class' => 'col-md-7 form-control input-md required
                            onlyNumber','placeholder' => '','id'=>"annual_turn_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::text('bin['.$inc.']',isset($appData->bin[$key]) ? $appData->bin[$key] :'',['class' => 'col-md-7 form-control input-md bin required','id'=>"bin_$gkey"]) !!}
                        </td>
                        <td>
                            {!! Form::text('branch_id['.$inc.']',isset($appData->branch_id[$key]) ? $appData->branch_id[$key] :'',['class' => 'col-md-7 form-control input-md sNo required','id'=>"branch_id_$gkey",'readonly'])
                            !!}
                        </td>
                        <td style="vertical-align: middle; text-align: center">
                            <?php if ($inc == 0) { ?>
                            <a class="btn btn-sm btn-primary addTableRows"
                               onclick="addTableRowSectionE('branch_body', 'branchInfoRow0');"><i
                                        class="fa fa-plus"></i></a>
                            <?php } else { ?>
                            {{--                            @if($viewMode != 'on')--}}
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-danger removeRow"
                               onclick="removeTableRow('branch_body','branchInfoRow{{$inc}}');">
                                <i class="fa fa-times" aria-hidden="true"></i></a>
                            {{--                            @endif--}}
                            <?php } ?>
                        </td>
                    </tr>
                    <?php $inc++; ?>
                @endforeach
            @else
                <tr id="branchInfoRow">
                    <td>
                        {!! Form::text('branch_address[0]','',['class' => 'col-md-7 form-control input-md required','id'=>'branch_address_1']) !!}
                        {!! $errors->first('branch_address','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('e_branch_name[0]','',['class' => 'col-md-7 form-control input-md required','id'=>'e_branch_name_1']) !!}
                        {!! $errors->first('e_branch_name','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::select('branch_category[0]',[],'',['class' => 'form-control input-md branch_category required','placeholder' =>
                        'Select one','id'=>'branch_category_1']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('annual_turnover[0]','',['class' => 'col-md-7 form-control input-md required
                        onlyNumber','placeholder' => '','id'=>'annual_turn_1']) !!}
                    </td>
                    <td>
                        {!! Form::text('bin[0]','',['class' => 'col-md-7 form-control input-md bin required','id'=>'bin_1']) !!}
                    </td>
                    <td>
                        {!! Form::text('branch_id[0]','0001',['class' => 'col-md-7 form-control input-md sNo required','id'=>'branch_id_1','readonly'])
                        !!}
                    </td>
                    <td style="vertical-align: middle; text-align: center">
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add more"
                           onclick="addTableRowSectionE('branch_body', 'branchInfoRow');">
                            <i class="fa fa-plus"></i></a>
                    </td>


                </tr>
            @endif

            </tbody>
        </table>
    </div>
    <p id="branchCloseData" style="{{isset($appData->branch_address) ? '' : 'display:none;'}}"> Please click <a>
            <button id="branchClose" type="button">here</button>
        </a> to hide Branch
        Information.
    </p>

    <div class="clearfix">
    </div>
</div>

<script>

    // Add table Row script
    function addTableRowSectionE(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length + 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var nameRo = rowCo - 1;
        var idText = 'rowCount' + tableID + rowCount;
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

        var attrData = $("#" + tableID).find('#' + idText).find(".sNo");
        attrData.val('000' + rowCo);
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
        <?php if(!isset($appData->branch_address)){?>
        $("#branchTable").find("input,button,textarea,select").attr("disabled", "disabled");
        <?php } ?>
        $('#branchInfo').click(function () {
            $('#branchTable').show();
            $('#branchData').hide();
            $('#branchCloseData').show();
            $("#branchTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#branchClose').click(function () {
            $('#branchTable').hide();
            $('#branchData').show();
            $('#branchCloseData').hide();
            $("#branchTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });


    });


</script>