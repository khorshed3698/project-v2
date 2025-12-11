{{--<div class="panel panel-primary" style="margin: 4px;">--}}
{{--    <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>--}}
{{--    <div class="panel-body">--}}
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached PDF file<span
                        style="color:darkred; font-size:12px; ">(Each File Maximum size 3MB)</span>

            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        {{--                {{dd($clrDocuments)}}--}}
        @foreach($attachment_list as $row)

            <tr>
                <td>
                    <div align="center">{!! $i !!}<span class='required-star'></span></div>
                </td>
                <td colspan="6">{!!  $row['DOC_DESC'] !!}</td>
                <td colspan="2">
                    <input type="hidden" value="{!!  $row['DOC_CODE'].'@'.$row['DOC_DESC'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['DOC_CODE']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['DOC_CODE']]['doucument_id']) ? $clrDocuments[$row['DOC_CODE']]['doucument_id'] : '')}}">
                    <input type="hidden" value="{!!  $row['DOC_DESC'] !!}"
                           id="doc_name_<?php echo $row['DOC_CODE']; ?>"
                           name="doc_name_<?php echo $row['DOC_CODE']; ?>"/>
                    <input name="<?php echo $row['DOC_CODE']; ?>"
                           <?php if (empty($clrDocuments[$row['DOC_CODE']]['file'])) {
                               echo "class='required'";
                           } ?>
                           data-required="required"
                           id="<?php echo $row['DOC_CODE']; ?>" type="file"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row['DOC_CODE']; ?>', this.id, 'validate_field_<?php echo $row['DOC_CODE']; ?>', '')"/>

                    @if(!empty($clrDocuments[$row['DOC_CODE']]))
                        <div class="save_file saved_file_{{$row['DOC_CODE']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['DOC_CODE']]['file']) ?
                                                                    $clrDocuments[$row['DOC_CODE']]['file'] : ''))}}"
                               title="{{$row['DOC_DESC']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['DOC_CODE']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['DOC_CODE'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['DOC_CODE']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['DOC_CODE']]['file']) ?
                                   $clrDocuments[$row['DOC_CODE']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['DOC_CODE']; ?>"
                               name="validate_field_<?php echo $row['DOC_CODE']; ?>"
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