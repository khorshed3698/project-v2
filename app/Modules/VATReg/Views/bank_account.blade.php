<div class="panel-heading"><strong>J. BANK ACCOUNT DETAILS</strong></div>
<div class="panel-body">
    <p id="bankaccountData" class="statictooltip">Please click <a>
            <button id="bankaccount" type="button">here</button>
        </a> to provide Bank
        Account Information.
    </p>

    <div id="bankaccountTable" style="display: none;">
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
            <tbody id="bankaccount_section">
            <?php $i = 1; ?>

            <tr id="bankAccountRow_1" data-number="1">

                <td>
                    {!! Form::text('account_name[0]',null,['class' => 'col-md-7 form-control input-md required','placeholder'
                    => '','id'=>'account_name_1']) !!}
                    {!! $errors->first('account_name','<span class="help-block">:message</span>') !!}
                </td>
                <td>
                    {!! Form::text('account_number[0]',null,['class' => 'col-md-7 form-control required
                    input-md','placeholder'
                    => '']) !!}
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
                       onclick="addTableRowSectionJ('bankaccount_section', 'bankAccountRow_1');">
                        <i class="fa fa-plus"></i></a>
                </td>

            </tr>

            </tbody>
        </table>
    </div>
    <p id="bankaccountCloseData" style="display: none;"> Please click <a>
            <button id="bankaccountClose" type="button">here</button>
        </a> to hide Bank Account
        Information.
    </p>

    <div class="clearfix">
    </div>
</div>

<script>

    // Add table Row script
    function addTableRowSectionJ(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        $(".bank_name").select2('destroy');
        $(".branch_name").select2('destroy');
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length;

        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount + 2;
        var rowCoo = rowCount + 1;
        var nameRo = rowCount;
        var idText = 'bankAccountRow_' + rowCoo;
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
            var repTextId = ret + '_' + rowCoo;
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
            var repTextId = ret + '_' + rowCoo;
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

        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRowJ("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCoo);

        $('.bank_name').select2();
        $('.branch_name').select2();
        // $('.bank_name').each(function (index) {
        //     console.log('#bank_name_' + index);
        // });
        // $('.branch_name').each(function (index) {
        //     $('#branch_name_' + index).select2();
        // });

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
    function removeTableRowJ(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        var index = 0;
        var rowCo = 1;
        $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop();
                var trName = trId.split("_").shift();
                var nameIndex = id - 1;

                var attrInput = $("#" + tableID).find('#' + trId).find('input');
                for (var i = 0; i < attrInput.length; i++) {
                    var nameAtt = attrInput[i].name;
                    var inputId = attrInput[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrInput[i].id = repTextId;
                    attrInput[i].name = repText;
                }


                var attrSel = $("#" + tableID).find('#' + trId).find('select');
                for (var i = 0; i < attrSel.length; i++) {
                    var nameAtt = attrSel[i].name;
                    var inputId = attrSel[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    var ret = inputId.replace('_' + id, '');
                    var repTextId = ret + '_' + rowCo;
                    attrSel[i].id = repTextId;
                    attrSel[i].name = repText;
                }
                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)

                if (rowCo != 1) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRowJ("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;

            }
        )

    }

    $(document).ready(function () {
        $("#bankaccountTable").find("input,button,textarea,select").attr("disabled", "disabled");
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