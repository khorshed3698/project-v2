<div class="panel-heading"><strong>K. INFORMATION ABOUT OWNERS/DIRECTORS/HEAD OF ENTITY</strong></div>
<div class="panel-body">
    <p id="ownerData" class="statictooltip">Please click <a>
            <button id="owner" type="button">here</button>
        </a> to provide Owners/Directors/Head of Entity Information.
    </p>

    <div id="ownerTable" style="display: none;">
        <table id="ownerInfo" class="table table-bordered table-hover"
               style="display: block;overflow-x: auto;white-space: nowrap;">
            <thead>
            <tr style="width: 100%;background: #f5f5f7">
                <th width="12%">e-TIN</th>
                <th width="12%">Full Name</th>
                <th width="12%">Designation</th>
                <th width="6%">Share (%)</th>
                <th width="11%">Identity Category</th>
                <th width="12%">NID</th>
                <th width="10%">Passport No</th>
                <th width="10%">Nationality</th>
                <th width="10%">BIN</th>
                <th width="10%">Action</th>


            </tr>
            </thead>
            <tbody id="owner_body">
            <?php $i = 1; ?>

            <tr id="ownerInfoRow">
                <td>
                    {!! Form::text('e_tin[0]','',['class' => 'col-md-7 form-control input-md onlyNumber required','id'=>'e_tin_1','placeholder' => '']) !!}
                    {!! $errors->first('e_tin','<span class="help-block">:message</span>') !!}
                </td>
                <td>
                    {!! Form::text('owner_name[0]','',['class' => 'col-md-7 form-control input-md required','id'=>'owner_name_1','placeholder' => '']) !!}
                </td>
                <td>
                    {!! Form::select('owner_designation[0]',[],null,['class' => 'form-control input-md owner_designation required','placeholder' => 'Select One','id'=>'owner_designation_1']) !!}
                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                </td>
                <td>
                    {!! Form::text('share[0]','',['class' => 'col-md-7 form-control input-md onlyNumber share required','id'=>'share_1','placeholder' => '']) !!}
                </td>
                <td>
                    {!! Form::select('identity_category_owner[0]',[],null,['class' => 'form-control input-md identity_category required','placeholder' => 'Select One', 'id'=>'identity_category_1']) !!}
                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                </td>
                <td>
                    {!! Form::text('owner_nid[0]','',['class' => 'col-md-7 form-control input-md owner_nid nid required','style' => 'display:none', 'id'=>'owner_nid_1']) !!}
                </td>
                <td>
                    {!! Form::text('owner_passport_no[0]','',['class' => 'col-md-7 form-control input-md owner_passport_no required','style' => 'display:none','placeholder' => '', 'id'=>'owner_passport_no_1']) !!}
                </td>
                <td>
                    {!! Form::select('owner_nationality[0]',[],null,['class' => 'form-control input-md owner_nationality required','style' => 'display:none','placeholder' => 'Select One', 'id'=>'owner_nationality_1']) !!}
                    {!! $errors->first('','<span class="help-block">:message</span>') !!}

                </td>
                <td>
                    {!! Form::text('owner_bin[0]','',['class' => 'col-md-7 form-control input-md owner_bin required','style' => 'display:none','placeholder' => '', 'id'=>'owner_bin_1']) !!}
                </td>
                <td style="vertical-align: middle; text-align: center">
                    <a class="btn btn-sm btn-primary addTableRows"
                       title="Add more"
                       onclick="addTableRowSectionK('owner_body', 'ownerInfoRow');">
                        <i class="fa fa-plus"></i></a>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
    <p id="ownerCloseData" style="display: none;"> Please click <a>
            <button id="ownerClose" type="button">here</button>
        </a> to hide Owners/Directors/Head of Entity Information.
    </p>
    <div class="clearfix">
    </div>
</div>

<script>

    // Add table Row script
    function addTableRowSectionK(tableID, templateRow) {
        $('.owner_nationality').select2();
        $('.owner_nationality').select2('destroy');
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

        $('.owner_nationality').select2();
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
        $(document).on('input', '.share', function () {
            var sum = 0;
            $.each($(".share"), function () {
                sum += +$(this).val();
            });
            if (sum > 100) {
                $(this).addClass('error');
                $(this).val('');
                alert('Total share cannot be more than 100.')
                return false;
            } else {
                $(this).removeClass('error');
                return true;
            }
        });
        $("#ownerTable").find("input,button,textarea,select").attr("disabled", "disabled");
        $('#owner').click(function () {
            $('#ownerTable').show();
            $('#ownerData').hide();
            $('#ownerCloseData').show();
            $("#ownerTable").find("input,button,textarea,select").removeAttr("disabled");
        });
        $('#ownerClose').click(function () {
            $('#ownerTable').hide();
            $('#ownerData').show();
            $('#ownerCloseData').hide();
            $("#ownerTable").find("input,button,textarea,select").attr("disabled", "disabled");
        });

        $(document).on('change', '.identity_category', function () {
            var row = $(this).closest("tr").index() + 1;
            var category = $(this).val();
            var categoryId = category.split("@")[0];
            $(this).data("id");
            // alert(row);
            if (categoryId == 1) {
                $('#owner_nid_' + row).show()
                $('#owner_nid_' + row).removeAttr('disabled')
                $('#owner_passport_no_' + row).hide()
                $('#owner_passport_no_' + row).attr('disabled', 'disabled')
                $('#owner_nationality_' + row).select2('destroy');
                $('#owner_nationality_' + row).css('display','none')
                $('#owner_nationality_' + row).attr('disabled', 'disabled')
                $('#owner_bin_' + row).hide()
                $('#owner_bin_' + row).attr('disabled', 'disabled')
            } else if (categoryId == 2) {
                $('#owner_nid_' + row).hide()
                $('#owner_nid_' + row).attr('disabled', 'disabled')
                $('#owner_passport_no_' + row).show()
                $('#owner_passport_no_' + row).removeAttr('disabled')
                $('#owner_nationality_' + row).select2();
                $('#owner_nationality_' + row).css('display','block')
                $('#owner_nationality_' + row).removeAttr('disabled')
                $('#owner_bin_' + row).hide()
                $('#owner_bin_' + row).attr('disabled', 'disabled')
            } else if (categoryId == 3) {
                $('#owner_nid_' + row).hide()
                $('#owner_nid_' + row).attr('disabled', 'disabled')
                $('#owner_passport_no_' + row).hide()
                $('#owner_passport_no_' + row).attr('disabled', 'disabled')
                $('#owner_nationality_' + row).select2('destroy');
                $('#owner_nationality_' + row).css('display','none')
                $('#owner_nationality_' + row).attr('disabled', 'disabled')
                $('#owner_bin_' + row).show()
                $('#owner_bin_' + row).removeAttr('disabled')
            } else {
                $('#owner_nid_' + row).hide()
                $('#owner_nid_' + row).attr('disabled', 'disabled')
                $('#owner_passport_no_' + row).hide()
                $('#owner_passport_no_' + row).attr('disabled', 'disabled')
                $('#owner_nationality_' + row).select2('destroy');
                $('#owner_nationality_' + row).css('display','none')
                $('#owner_nationality_' + row).attr('disabled', 'disabled')
                $('#owner_bin_' + row).hide()
                $('#owner_bin_' + row).attr('disabled', 'disabled')
            }
        });
        // $('.identity_category').trigger('change');

    });

</script>