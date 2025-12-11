<?php
$accessMode = ACL::getAccsessRight('NewConnectionDESCO');
?>
@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <strong>Solar Attachment Upload</strong>
                        </div>
                        <div class="panel-body">

                            {!! Form::open(array('url' => 'new-connection-desco/view/solar-documents','method' => 'post', 'class' =>
   'form-horizontal', 'id' => 'NewConnection',
   'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>
                            <input type="hidden" name="app_id" id="app_id" value="{{$application_id}}"/>

                            <div class="form-group">
                                <div class="row">
                                    <div class=" col-md-12">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th class="required-star">Solar Attachment Title</th>
                                                <th class="required-star">Solar Installation Date</th>
                                                <th class="required-star">Solar File Upload</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody id="loadDetails">
                                            <tr id="loadDetailsRow_1" data-number="1">
                                                <td>
                                                    {!! Form::text('solarDocTitle[0]', '',['class' => 'form-control input-md required','id'=>'solarDocTitle_1']) !!}
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        {!! Form::text('solarInstallationDate[0]', '',['class' => 'form-control input-md datepicker required','style'=>'background:white;','id'=>'solarInstallationDate_1']) !!}
                                                        <span class="input-group-addon">
                                                    <span class="fa fa-calendar"></span>
                                                </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="file" name="solarDoc[0]" id="solarDoc_1"
                                                           class="required form-control"
                                                           onchange="uploadDocument('previewPhoto_1', this.id, 'validate_field_1',1)">
                                                    <div id="previewPhoto_1">
                                                        <input type="hidden" value="" id="validate_field_1"
                                                               name="validate_field_1" class="required">
                                                    </div>
                                                </td>

                                                <td style="vertical-align: middle; text-align: center">
                                                    <a class="btn btn-sm btn-primary addTableRows"
                                                       title="Add more LOAD DETAILS"
                                                       onclick="addTableRowSection('loadDetails', 'loadDetailsRow_1');">
                                                        <i class="fa fa-plus"></i></a>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="pull-left" style="padding-left: 1em;">
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md" value="Submit" name="actionBtn">
                                    Upload Attachment
                                </button>
                            </div>
                            {!! Form::close() !!}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')
    <script>
        $(document).ready(function () {
            $("#NewConnection").validate({
                errorPlacement: function () {
                    return false;
                },
                submitHandler: function () {
                    this.form.submit();
                },

            });
            // Datepicker Plugin initialize
            var today = new Date();
            var yyyy = today.getFullYear();
            $('#solarInstallationDate_1').datetimepicker({
                viewMode: 'days',
                format: 'DD-MMM-YYYY',
                maxDate: '01/01/' + (yyyy + 100),
                minDate: '01/01/' + (yyyy - 100)
            });
        })

        // Add table Row script
        function addTableRowSection(tableID, templateRow) {
            //rowCount++;
            //Direct Copy a row to many times
            var x = document.getElementById(templateRow).cloneNode(true);
            x.id = "";
            x.style.display = "";
            var table = document.getElementById(tableID);
            var rowCount = $('#' + tableID).find('tr').length;

            //var rowCount = table.rows.length;
            //Increment id
            var rowCoo = rowCount + 1;
            var nameRo = rowCount;
            var idText = 'loadDetailsRow_' + rowCoo;
            x.id = idText;
            $("#" + tableID).append(x);

            $("#" + tableID).find('#' + idText).find('#label_solarDoc_' + rowCount).remove();
            $("#" + tableID).find('#' + idText).find('.span_validate_field_' + rowCount).remove();
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
                $('#' + repTextId).val('')

                if ($('#' + repTextId).hasClass('datepicker')) {
                    var today = new Date();
                    var yyyy = today.getFullYear();
                    $('#' + repTextId).datetimepicker({
                        viewMode: 'days',
                        format: 'DD-MMM-YYYY',
                        maxDate: '01/01/' + (yyyy + 100),
                        minDate: '01/01/' + (yyyy - 100)
                    });
                }

            }
            attrInput.val(''); //value reset

            var attrInputHidden = $("#" + tableID).find('#' + idText).find('input[type=hidden]');
            for (var i = 0; i < attrInputHidden.length; i++) {
                var nameAtt = attrInputHidden[i].name;
                var repText = nameAtt.replace('_1', '');
                var repTextId = repText + '_' + rowCoo;
                attrInputHidden[i].name = repTextId;
                $('#' + repTextId).val('')
            }
            attrInputHidden.val(''); //value reset

            var attrDiv = $("#" + tableID).find('#' + idText).find('div');
            for (var i = 0; i < attrDiv.length; i++) {
                var nameAtt = attrDiv[i].id;
                var repText = nameAtt.replace('_1', '');
                var repTextId = repText + '_' + rowCoo;
                attrDiv[i].id = repTextId;
                $('#' + repTextId).val('')
            }
            attrDiv.val(''); //value reset

            //Class change by btn-danger to btn-primary
            $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
                .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
            $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
            $('#' + tableID).find('tr').last().attr('data-number', rowCoo);
            $('#solarDoc_' + rowCoo).removeAttr('onchange').attr('onchange', "uploadDocument('previewPhoto_" + rowCoo + "','solarDoc_" + rowCoo + "', 'validate_field_" + rowCoo + "', 1)");
            //$('#' + tableID).find('datepicker').removeClass('datepicker').datepicker({dateFormat: 'dd/mm/yy'});

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
                        $(this).find('.addTableRows').attr('onclick', 'removeTableRow("' + tableID + '","' + trName + '_' + rowCo + '")');
                    }
                    index++;
                    rowCo++;

                }
            )

        }

        /*document upload start*/

        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == '') {
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length)
                    $('#label_' + id).remove();
                return false;
            }

            try {
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{URL::to('/new-connection-desco/upload-document')}}";
                //alert(action);
                $("#" + targets).html('Uploading....');
                var file_data = $("#" + id).prop('files')[0];
                var form_data = new FormData();
                form_data.append('selected_file', id);
                form_data.append('isRequired', isRequired);
                form_data.append('validateFieldName', vField);
                form_data.append('_token', "{{ csrf_token() }}");
                form_data.append(id, file_data);
                $.ajax({
                    target: '#' + targets,
                    url: action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function (response) {
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var doc_id = id;
                        var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        $('#' + id).removeClass('required');
                        //check valid data
                        document.getElementById(id).value = '';
                        var validate_field = $('#' + vField).val();
                        if (validate_field == '') {
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong." + err;
            }
        }

        $(document).on('click', '.filedelete', function () {
            let abc = $(this).attr('docid');
            let sure_del = confirm("Are you sure you want to delete this file?");
            if (sure_del) {
                let val = abc.split('_')[1];
                document.getElementById("validate_field_" + val).value = '';
                document.getElementById(abc).value = '';
                $('#saved_file_' + abc).html('');
                $('#label_' + abc).remove();
                $('.span_validate_field_' + val).html('');
            } else {
                return false;
            }
        });


    </script>
@endsection