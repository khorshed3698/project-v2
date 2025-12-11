<style>

    .listMajor {
        width: 80%;
    }

    body.modal-open {
        overflow: visible;
    }

    #listMajor_filter {
        margin-left: 50%;
    }
</style>
<div class="col-md-6">
    {!! Form::label('','L6. Major Capital Machinery', ['class'=>'col-md-5
    required-star']) !!}
    <div class="col-md-7">
        <p id="MajorCapitalData" class="statictooltip">Please click <a>
                <button id="MajorCapital"
                        type="button">here
                </button>
            </a> to provide Major Capital Machinery
            Information.
        </p>
    </div>
</div>
<div class="panel-body">
    <div id="MajorCapitalTable" style="display: none;">
        <table id="MajorCapitalInfo" class="table table-bordered table-hover"
               style="display: block;overflow-x: auto;white-space: nowrap;">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th width="25%">Description</th>
                <th width="20%">HS/Service Code</th>
                <th width="15%">Value in BDT</th>
                <th width="25%">Production Capacity</th>
                <th width="10%">Physical Condition</th>
                <th width="10%">Action</th>
            </tr>
            </thead>
            <tbody id="MajorCapital_body">
            <tr id="MajorCapital_row">
                <td>
                    {!! Form::text('description[0]','',['class' => 'form-control input-md required','id'=>'description_1']) !!}
                    {!! $errors->first('description','<span class="help-block">:message</span>') !!}
                </td>
                <td>
                    {!! Form::hidden('hs_code_major_hidden[0]','',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>'hs_code_major_hidden_1']) !!}

                    <div class="input-group">
                        {!! Form::text('hs_code_major[0]','',['class' => 'col-md-7 form-control input-md','placeholder' => '', 'id'=>'hs_code_major_1','readonly']) !!}
                        <span class="input-group-addon serviceData" data-toggle="modal" data-target="#myModal"
                              data-type="majorCapital"
                              id="1"><i class="fa fa-bars"></i></span>
                    </div>
                </td>
                <td>
                    {!! Form::text('value_bdt[0]','',['class' => 'form-control input-md required onlyNumber','id'=>'value_bdt_1']) !!}
                </td>
                <td>
                    {!! Form::text('production_capacity[0]','',['class' => ' form-control input-md required','id'=>'production_capacity_1']) !!}
                </td>
                <td>{!! Form::select('physical_condition[0]',[],null,['class' =>'form-control input-md physical_condition required',
                      'id'=>'physical_condition_1']) !!}
                    {!! $errors->first('','<span class="help-block">:message</span>') !!}

                </td>
                <td style="vertical-align: middle; text-align: center">
                    <a class="btn btn-sm btn-primary addTableRows"
                       title="Add more"
                       onclick="addTableRowMajorCapital('MajorCapital_body', 'MajorCapital_row');">
                        <i class="fa fa-plus"></i></a>
                </td>

            </tr>

            </tbody>
        </table>
    </div>
    <p id="MajorCapitalCloseData" style="display: none;"> Please click <a>
            <button id="MajorCapitalClose" type="button">here</button>
        </a> to hide Major Capital Machinery
        Information.
    </p>
    <div class="clearfix">
    </div>
</div>

<script>

    function addTableRowMajorCapital(tableID, templateRow) {
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

        $("#MajorCapitalTable").find("input,button,textarea,select").attr("disabled", "disabled");
        $('#MajorCapital').click(function () {
            $('#MajorCapitalTable').show();
            $('#MajorCapitalData').hide();
            $('#MajorCapitalCloseData').show();
            $("#MajorCapitalTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#MajorCapitalClose').click(function () {
            $('#MajorCapitalTable').hide();
            $('#MajorCapitalData').show();
            $('#MajorCapitalCloseData').hide();
            $("#MajorCapitalTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });

    });


</script>