<?php
$accessMode = ACL::getAccsessRight('NewConnectionDESCO');
?>
@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    @if($appInfo->shortfall_message!= null || $appInfo->shortfall_message!='')
                        <div class="alert alert-warning ">
                            <strong>Shortfall Message:</strong> {{$appInfo->shortfall_message}}
                        </div>
                    @endif
                    <div class=" panel panel-info">
                        <div class="panel-heading"><strong>
                                SHORTFALL DOCUMENTS
                            </strong>
                        </div>
                        <div class="panel-body">
                            {!! Form::open(array('url' => 'new-connection-desco/shortfall','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'desco-view','enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                            <input type="hidden" name="selected_file"
                                   id="selected_file"/>
                            <input type="hidden" name="validateFieldName"
                                   id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>
                            <input type="hidden" value="{{ request()->route('id') }}"
                                   name="ref_id">
                            <div class="form-group" style="">
                                {{--<div class="panel panel-primary" style="margin: 4px;">--}}
                                {{--    <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>--}}
                                {{--    <div class="panel-body">--}}
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover ">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th colspan="6">Required Attachments</th>
                                            <th colspan="2">Attached PDF file
                                                <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                                                      title="Attached PDF file (Each File Maximum size 2MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>

                                        @foreach($shortfallarr as $row)
                                            <?php
                                            if(!in_array($row->id,$shortfallDocumentsIds)){
                                                continue;
                                            }
                                            ?>



                                            <tr>
                                                <td>
                                                    <div align="center">{!! $i !!}<span></span></div>
                                                </td>
                                                <td colspan="6"
                                                    @if($row->isRequired == 1 ) class="required-star" @endif >{!!  $row->title !!}</td>
                                                <td colspan="2">
                                                    {{--                    {{dd($clrDocuments)}}--}}
                                                    <input type="hidden" value="{!!  $row->id.'@'.$row->title !!}"
                                                           name="dynamicDocumentsId[]"/>
                                                    <input name="document_id_<?php echo $row->id; ?>"
                                                           type="hidden"
                                                           value="{{(!empty($clrDocuments[$row->id][$row->title]) ? $clrDocuments[$row->id][$row->title] : '')}}">
                                                    <input type="hidden" value="{!!  $row->title !!}"
                                                           id="doc_name_<?php echo $row->id; ?>"
                                                           name="doc_name_<?php echo $row->id; ?>"/>
                                                    <input name="<?php echo $row->id; ?>"
                                                           @if($row->isRequired == 1) class="required"
                                                           @endif
                                                           id="<?php echo $row->id; ?>" type="file"
                                                           size="20"
                                                           onchange="uploadShortfallDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '')"/>

                                                    @if(!empty($clrDocuments[$row->id]))


                                                        <div class="save_file saved_file_{{$row->id}}">
                                                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                                    $clrDocuments[$row->id]['file'] : ''))}}"
                                                               title="{{$clrDocuments[$row->id]['doc_name']}}">
                                                                <i class="fa fa-file-pdf-o"
                                                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                                            </a>

                                                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                                                            <a href="javascript:void(0)"
                                                               onclick="ConfirmDeleteFile({{ $row->id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                                                            </a>
                                                            <?php } ?>
                                                        </div>
                                                    @endif


                                                    <div id="preview_<?php echo $row->id; ?>">
                                                        <input type="hidden"
                                                               value="<?php echo !empty($clrDocuments[$row->id]['file']) ?
                                                                   $clrDocuments[$row->id]['file'] : ''?>"
                                                               id="validate_field_<?php echo $row->id; ?>"
                                                               name="validate_field_<?php echo $row->id; ?>"
                                                               class="required"/>
                                                    </div>

                                                </td>
                                            </tr>
                                            <?php $i++; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div><!-- /.table-responsive -->
                                {{--    </div><!-- /.panel-body -->--}}
                                {{--</div>--}}
                            </div>
                            <button type="submit" id="shortfallbtn"
                                    class="btn btn-success pull-right">Submit
                            </button>
                            {!! Form::close() !!}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('footer-script')
    <script>

        /*document upload start*/
        function uploadShortfallDocument(targets, id, vField, isRequired) {
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
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        }

        $(document).on('click', '.filedelete', function () {
            var abc = $(this).attr('docid');
            var sure_del = confirm("Are you sure you want to delete this file?");
            if (sure_del) {
                document.getElementById("validate_field_" + abc).value = '';
                document.getElementById(abc).value = '';
                $('.saved_file_' + abc).html('');
                $('.span_validate_field_' + abc).html('');
            } else {
                return false;
            }
        });

        $(document).ready(function () {
            $('#shortfallbtn').on('click', function (e) {
                $("form#desco-view").validate();
            });
        });
    </script>
@endsection
