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
                <span style="color:darkred; font-size:12px; ">(Each File Maximum size 1MB)</span>
            </th>

            <th colspan="3">Required Date</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @if(count($document) > 0)
            @foreach($document as $row)
                <tr>
                    <td>
                        <div align="center">{!! $i !!}</div>
                    </td>
                    <td colspan="3">{!!  $row->doc_name !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></td>
                    <td colspan="2">
                        <div align="center">
                            <input class="{{($row->doc_priority == "1") ? 'required form-control':'form-control'}}"
                                   value="{{!empty($clrDocuments[$row->id]['doc_number']) ? $clrDocuments[$row->id]['doc_number'] : ''}}"
                                   type="text" name="{!!  $row->id !!}_no"
                                   placeholder="{!!  $row->doc_name !!} No">
                        </div>
                    </td>
                    <td colspan="3">
                        <input name="document_id_<?php echo $row->id; ?>"
                               type="hidden"
                               value="{{(!empty($clrDocuments[$row->id]['doucument_id']) ? $clrDocuments[$row->id]['doucument_id'] : '')}}">
                        <input type="hidden" value="{!!  $row->doc_name !!}"
                               id="doc_name_<?php echo $row->id; ?>"
                               name="doc_name_<?php echo $row->id; ?>"/>
                        <input name="file<?php echo $row->id; ?>"
                               <?php if (empty($clrDocuments[$row->id]['file']) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                   echo "class='required form-control'";
                               } ?>
                               <?php if ($row->doc_priority == "1") {
                                   echo "data-required='required'";
                               } else {
                                   echo "data-required=''";
                               } ?>
                               id="<?php echo $row->id; ?>" type="file"
                               size="20"
                               onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>

                        @if(!empty($clrDocuments[$row->id]['file']))
                            <div class="save_file saved_file_{{$row->id}}">
                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                                    $clrDocuments[$row->id]['file'] : ''))}}"
                                   title="{{$row->doc_name}}">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                </a>
                            </div>
                        @endif


                        <div id="preview_<?php echo $row->id; ?>">
                            <input type="hidden"
                                   value="<?php echo !empty($clrDocuments[$row->id]['file']) ?
                                       $clrDocuments[$row->id]['file'] : ''?>"
                                   id="validate_field_<?php echo $row->id; ?>"
                                   name="validate_field_<?php echo $row->id; ?>"
                                   class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                        </div>


                        @if(!empty($allRequestVal["file$row->id"]))
                            <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                            <input type="hidden" class="required"
                                   value="{{$allRequestVal["validate_field_".$row->id]}}"
                                   id="validate_field_{{$row->id}}"
                                   name="validate_field_{{$row->id}}">
                        @endif

                    </td>
                    <td colspan="3">
                        <div class="datepicker col-md-7 input-group ">
                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                            {!! Form::text($row->id.'_date', !empty($clrDocuments[$row->id]['doc_date']) ? \App\Libraries\CommonFunction::changeDateFormat($clrDocuments[$row->id]['doc_date']) : '',['class' => ($row->doc_priority == "1") ? 'required form-control input-md':'form-control input-md','readonly','placeholder'=>$row->doc_name.' Date','id'=>$row->id.'_date', 'style'=>'background:white;']) !!}
                        </div>
                    </td>
                </tr>
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
<div class="table-responsive col-md-8" @if(count($clrOtherDocuments) < 1)style="display:none;"@endif>
    <table class=" table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th colspan="2">No.</th>
            <th colspan="4">Attached PDF file
                <span style="color:darkred; font-size:12px; ">(Each File Maximum size 1MB)</span>
            </th>
            <th colspan="2">Action</th>
        </tr>
        </thead>
        <tbody id="otherFile">
        @if(count($clrOtherDocuments) > 0)
            @foreach($clrOtherDocuments as $key => $value)
                <?php
                $lkey = ($key - 1);
                ?>
                <tr id="rowCountotherFile_{{$lkey}}" data-number="{{$lkey}}">
                    <td colspan="2" class="count"></td>
                    <td colspan="4">
                        <input name="otherFile_{{$lkey}}" id="otherFile_{{$lkey}}" type="file" class="form-control doc"
                               size="20"
                               onchange="uploadDocument('preview_otherFile_{{$lkey}}', this.id, 'validate_field_otherFile[{{$lkey}}]',1)"/>
                        <div id="preview_otherFile_{{$lkey}}">
                            {!! Form::hidden("validate_field_otherFile[$lkey]",$clrOtherDocuments[$key]['file'], ['class'=>'form-control input-md doc', 'id' => 'validate_field_otherFile_'.$lkey]) !!}
                        </div>
                        @if(!empty($clrOtherDocuments[$key]['file']))
                            <div class="save_file saved_file_{{$lkey}}">
                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrOtherDocuments[$key]['file']) ?
                                                                    $clrOtherDocuments[$key]['file'] : ''))}}"
                                   title="{{$value['doc_name']}}">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrOtherDocuments[$key]['file']); echo end($file_name); ?>
                                </a>
                            </div>
                        @endif
                    </td>
                    <td colspan="2" style="vertical-align: middle; text-align: center">

                        <?php if ($lkey == 0) { ?>
                        <a class="btn btn-sm btn-primary addTableRows"
                           title="Add"
                           onclick="addTableRowDoc('otherFile', 'rowCountotherFile_{{$lkey}}');">
                            <i class="fa fa-plus"></i></a>

                        <?php } else { ?>
                        <a href="javascript:void(0);"
                           class="btn btn-sm btn-danger removeRow"
                           onclick="removeTableRowDoc('otherFile','rowCountotherFile_{{$lkey}}');">
                            <i class="fa fa-times" aria-hidden="true"></i></a>
                        <?php } ?>
                    </td>
                </tr>

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
        @if(empty($clrOtherDocuments))
        $(".doc").attr("disabled", true);
        @endif
        $("#addOther").click(function () {

            let display = $(".table-responsive").css('display')

            if (display === 'none') {
                $(".table-responsive").show()
                $(".doc").attr("disabled", false);
            } else {
                $(".table-responsive").hide()
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


    } // end of addTableRowTraHis() function

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

