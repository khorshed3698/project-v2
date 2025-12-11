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

        @foreach($attachment_list as $row)
            <tr>
                <td>
                    <div align="center">{!! $i !!}<span></span></div>
                </td>
                <td colspan="6">{!!  $row['doc_name'] !!}</td>
                <td colspan="2">
                    <?php
                    $doc_id=trim($row['upload_id']);
                    ?>
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $doc_id.'@'.$row['doc_name'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $doc_id; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$doc_id]['file']) ? $clrDocuments[$doc_id]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['doc_name'] !!}"
                           id="doc_name_<?php echo $doc_id; ?>"
                           name="doc_name_<?php echo $doc_id;?>"/>
                    <input name="doc_<?php echo $doc_id; ?>"
                           id="doc_<?php echo $doc_id; ?>" type="file"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $doc_id; ?>', this.id, 'validate_field_<?php echo $doc_id; ?>', '')"/>


                    @if(!empty($clrDocuments[$doc_id]))

                        <div class="save_file saved_file_{{$doc_id}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$doc_id]['file']) ?
                                                                    $clrDocuments[$doc_id]['file'] : ''))}}"
                               title="{{$row['doc_name']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$doc_id]['file']); echo end($file_name); ?>
                            </a>
                            @if(!empty($clrDocuments[$doc_id]['file']))
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $doc_id }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                          @endif

                        </div>
                    @endif


                    <div id="preview_<?php echo $doc_id; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$doc_id]['file']) ?
                                   $clrDocuments[$doc_id]['file'] : ''?>"
                               id="validate_field_<?php echo $doc_id; ?>"
                               name="validate_field_<?php echo $doc_id; ?>"
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