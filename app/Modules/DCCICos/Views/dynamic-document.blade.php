<style>
    table {
        counter-reset: section;
    }

    .count:before {
        counter-increment: section;
        content: counter(section);
    }
</style>
<div class="table">
    <table class=" table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="3">Required Attachments</th>
            <th colspan="2">Required Number</th>
            <th colspan="3">Attached PDF file
                <span style="color:darkred; font-size:12px; ">PDF Size should be less than or equal to 1 MB</span>
            </th>

            <th colspan="3">Required Date</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @if(count($attachment_list) > 0)

            @foreach($attachment_list as $key=>$row)
                @if($key > 1 && $key < 6)
                    <tr>
                        <td>
                            <div align="center" class="count"></div>
                        </td>
                        <td colspan="3" class="required-star">{!!  $row['id'] !!}</td>
                        <td colspan="2">
                            <div align="center">
                                <input class="{{ empty($clrDocuments[$key]['doc_number']) ? 'form-control required' : 'form-control'}}"
                                       value="{{!empty($clrDocuments[$key]['doc_number']) ? $clrDocuments[$key]['doc_number'] : ''}}"
                                       type="text" name="number_{{$key}}"
                                       placeholder="{!!  $row['id'] !!} No">
                            </div>
                        </td>
                        <td colspan="3">
                            <input type="hidden" value="{{  $row['id'].'@'.$key.'@1'}}"
                                   name="dynamicDocumentsId[]"/>
                            <input name="document_id_{{$key}}"
                                   type="hidden"
                                   value="{{(!empty($clrDocuments[$key]['doucument_id']) ? $clrDocuments[$key]['doucument_id'] : '')}}">
                            <input type="hidden" value="{!!  $row['id'] !!}"
                                   id="doc_name_{{$key}}"
                                   name="doc_name_{{$key}}"/>
                            <input name="file{{$key}}"
                                   id="{{$key}}" type="file"
                                   class="form-control {{empty($clrDocuments[$key]['file']) ? ' required' : ''}}"
                                   size="20"
                                   onchange="uploadDocument('preview_{{$key}}', this.id, 'validate_field_{{$key}}', '1')"/>

                            @if(!empty($clrDocuments[$key]['file']))
                                <div class="save_file saved_file_{{$row['id']}}">
                                    <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$key]['file']) ?
                                                                    $clrDocuments[$key]['file'] : ''))}}"
                                       title="{{$row['id']}}">
                                        <i class="fa fa-file-pdf-o"
                                           aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$key]['file']); echo end($file_name); ?>
                                    </a>
                                </div>
                            @endif


                            <div id="preview_{{$key}}">
                                <input type="hidden"
                                       value="<?php echo !empty($clrDocuments[$key]['file']) ?
                                           $clrDocuments[$key]['file'] : ''?>"
                                       id="validate_field_{{$key}}"
                                       name="validate_field_{{$key}}"/>
                            </div>

                        </td>
                        <td colspan="3">
                            <div class="datepicker   input-group ">
                                <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                {!! Form::text('date_'.$key, !empty($clrDocuments[$key]['date']) ? \App\Libraries\CommonFunction::changeDateFormat($clrDocuments[$key]['date']) : '',['class' => empty($clrDocuments[$key]['date']) ? 'form-control required' : 'form-control','readonly','placeholder'=>$row['id'].' Date','id'=>'date_'.$key, 'style'=>'background:white;']) !!}
                            </div>
                        </td>
                    </tr>
                @endif
                <?php $i++; ?>
            @endforeach
        @else
            <tr>
                <td colspan="8" style="text-align: center"><span
                            class="label label-info">No Required Documents!</span></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<div class="col-md-4 col-md-offset-4">
    <button type="button" style="margin-bottom:15px;" class="col-md-6 btn btn-success" id="addOther">Add Other
        Files
    </button>
</div>
<div class="table-responsive othertable col-md-8">
    <table class=" table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th colspan="2">No.</th>
            <th colspan="4">Attached PDF file
                <span style="color:darkred; font-size:12px; ">PDF Size should be less than or equal to 1MB</span>
            </th>
            <th colspan="2">Action</th>
        </tr>
        </thead>
        <tbody id="otherFile">
        @if(count($clrOtherDocuments) > 0)
            <?php
            $i = 0;
            ?>
            @foreach($clrOtherDocuments as $key => $val)
                <tr id="rowCountotherFile_{{$i}}" data-number="{{$i}}">
                    <td colspan="2" class="count"></td>
                    <td colspan="4">
                        <input name="otherFile_{{$i}}" id="otherFile_{{$i}}" type="file" class="form-control doc"
                               size="20"
                               onchange="uploadDocument('preview_otherFile_{{$i}}', this.id, 'validate_field_otherFile[{{$i}}]',1)"/>
                        <div id="preview_otherFile_{{$i}}">
                            {!! Form::hidden("validate_field_otherFile[$i]",$clrOtherDocuments[$key]['file'], ['class'=>'form-control input-md doc', 'id' => 'validate_field_otherFile_'.$i]) !!}
                        </div>
                        @if(!empty($clrOtherDocuments[$key]['file']))
                            <div class="save_file saved_file_{{$i}}">
                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrOtherDocuments[$key]['file']) ?
                                                                    $clrOtherDocuments[$key]['file'] : ''))}}"
                                   title="{{$val['doc_name']}}">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrOtherDocuments[$key]['file']); echo end($file_name); ?>
                                </a>
                            </div>
                        @endif
                    </td>
                    <td colspan="2" style="vertical-align: middle; text-align: center">

                        <?php if ($i == 0) { ?>
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add"
                           onclick="addTableRowDoc('otherFile', 'rowCountotherFile_{{$i}}');">
                            <i class="fa fa-plus"></i></a>

                        <?php } else { ?>
                        <a href="javascript:void(0);"
                           class="btn btn-sm btn-danger removeRow"
                           onclick="removeTableRowDoc('otherFile','rowCountotherFile_{{$i}}');">
                            <i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                </tr>
                <?php
                $i = $i + 1;
                ?>
            @endforeach
        @else
            <tr id="rowCountotherFile_0" data-number="0">
                <td colspan="2" class="count"></td>
                <td colspan="4">
                    <input name="otherFile_0" id="otherFile_0" type="file" class="form-control doc" size="20"
                           onchange="uploadDocument('preview_otherFile_0', this.id, 'validate_field_otherFile[0]',1)"/>
                    <div id="preview_otherFile_0">
                        {!! Form::hidden('validate_field_otherFile[0]','', ['class'=>'form-control input-md doc', 'id' => 'validate_field_otherFile_0']) !!}
                    </div>
                </td>
                <td colspan="2" style="vertical-align: middle; text-align: center">
                    <a class="btn btn-sm btn-primary addTableRows"
                       title="Add more business type"
                       onclick="addTableRowDoc('otherFile', 'rowCountotherFile_0');">
                        <i class="fa fa-plus"></i></a>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function () {// Datepicker Plugin initialize
        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            maxDate: '01/01/' + (yyyy + 100),
            minDate: '01/01/' + (yyyy - 100),
            ignoreReadonly: true
        });
        @if(count($clrOtherDocuments) < 1)
        $(".doc").attr("disabled", true);
        $(".othertable").css("display", "none");
        @endif
        $("#addOther").click(function () {
            let display = $(".othertable").css('display')
            if (display === 'none') {
                $(".othertable").show()
                $(".doc").attr("disabled", false);
            } else {
                $(".othertable").hide()
                $(".doc").attr("disabled", true);
            }
        });
    });

    // Add table Row script
    function addTableRowDoc(tableID, templateRow) {

        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";

        var rowCount = $('#' + tableID).find('tr').length - 1;
        if (rowCount < 4) {
            var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
            if (lastTr != '' && typeof lastTr !== "undefined") {
                rowCount = parseInt(lastTr) + 1;
            }

            var rowCo = rowCount;
            var idText = 'rowCount' + tableID + '_' + rowCount;
            x.id = idText;
            $("#" + tableID).append(x);

            var attrSel = $("#" + tableID).find('#' + idText).find('input:not(:hidden)');
            for (var i = 0; i < attrSel.length; i++) {

                var nameAtt = attrSel[i].name;
                var selectId = attrSel[i].id;
                var repText = nameAtt.split('_')[0]; //increment all array element name
                var ret = selectId.split('_')[0];
                var repTextId = ret + '_' + rowCo;
                var repTextName = repText + '_' + rowCo;
                attrSel[i].id = repTextId;
                attrSel[i].name = repTextName;
                $('#' + repTextId).removeAttr('onchange')
                $('#' + repTextId).attr("onchange", "uploadDocument('preview_otherFile_" + rowCo + "', this.id, 'validate_field_otherFile[" + rowCo + "]',1)")
            }
            attrSel.val(''); //value reset

            var attrDiv = $("#" + tableID).find('#' + idText).find('div');
            for (var i = 0; i < attrDiv.length; i++) {

                var selectId = attrDiv[i].id;
                var ret = selectId.split('_');
                var repTextId = ret[0] + '_' + ret[1] + '_' + rowCo;
                attrDiv[i].id = repTextId;
            }
            attrDiv.val(''); //value reset

            var attrHid = $("#" + tableID).find('#' + idText).find('input[type=hidden]');
            for (var i = 0; i < attrHid.length; i++) {
                var nameAtt = attrHid[i].name;
                var selectId = attrHid[i].id;
                var selectIds = selectId.replace('[0]', '');
                var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
                var ret = selectIds.split('_');
                var repTextId = ret[0] + '_' + ret[1] + '_' + ret[2] + '_' + rowCo;
                attrHid[i].id = repTextId;
                attrHid[i].name = repText;
            }
            attrHid.val(''); //value reset

            $('#otherFile_' + rowCo).next('label').remove();
            $('.saved_file_0').children().last().remove();
            $('#preview_otherFile_' + rowCo).find('font').remove();

            attrSel.prop('selectedIndex', 0);

            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRowDoc("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            $('#' + tableID).find('tr').last().attr('data-number', rowCount);
        } else {
            alert('You can add maximum 5 other files')

        }
    }
        // end of addTableRowTraHis() function

        // Remove Table row script
        function removeTableRowDoc(tableID, removeNum) {
            $('#' + tableID).find('#' + removeNum).remove();
            var index = 0
            var rowCo = 0
            $('#' + tableID + ' tr').each(function () {
                var trId = $(this).attr("id")
                var id = trId.split("_").pop();
                var trName = trId.split("_").shift();
                var nameIndex = id;

                var attrSel = $("#" + tableID).find('#' + trId).find('input:not(:hidden)');
                //edited by ishrat to solve select box id auto increment related bug
                for (var i = 0; i < attrSel.length; i++) {

                    var nameAtt = attrSel[i].name;
                    var selectId = attrSel[i].id;
                    var repText = nameAtt.split('_')[0]; //increment all array element name
                    var ret = selectId.split('_')[0];
                    var repTextId = ret + '_' + rowCo;
                    var repTextName = repText + '_' + rowCo;
                    attrSel[i].id = repTextId;
                    attrSel[i].name = repTextName;
                    $('#' + repTextId).removeAttr('onchange')
                    $('#' + repTextId).attr("onchange", "uploadDocument('preview_otherFile_" + rowCo + "', this.id, 'validate_field_otherFile[" + rowCo + "]',1)")
                }
                attrSel.val(''); //value reset

                var attrDiv = $("#" + tableID).find('#' + trId).find('div');
                for (var i = 0; i < attrDiv.length; i++) {

                    var selectId = attrDiv[i].id;
                    var ret = selectId.split('_');
                    var repTextId = ret[0] + '_' + ret[1] + '_' + rowCo;
                    attrDiv[i].id = repTextId;
                }
                attrDiv.val(''); //value reset

                var attrHid = $("#" + tableID).find('#' + trId).find('input[type=hidden]');
                //edited by ishrat to solve select box id auto increment related bug
                for (var i = 0; i < attrHid.length; i++) {

                    var nameAtt = attrHid[i].name;
                    var selectId = attrHid[i].id;
                    var repText = nameAtt.replace('[' + nameIndex + ']', '[' + index + ']'); //increment all array element name
                    var ret = selectId.split('_');
                    var repTextId = ret[0] + '_' + ret[1] + '_' + ret[2] + '_' + rowCo;
                    attrHid[i].id = repTextId;
                    attrHid[i].name = repText;

                }

                var ret = trId.replace('_' + id, '');
                var repTextId = ret + '_' + rowCo;
                $(this).removeAttr("id")
                $(this).attr("id", repTextId)
                $(this).removeAttr("data-number")
                $(this).attr("data-number", rowCo)

                if (rowCo != 0) {
                    $(this).find('.addTableRows').removeAttr('onclick');
                    $(this).find('.addTableRows').attr('onclick', 'removeTableRowDoc("' + tableID + '","' + trName + '_' + rowCo + '")');
                }
                index++;
                rowCo++;
            })
        }

</script>
