
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
        {{--                {{dd($clrDocuments)}}--}}
        @foreach($attachment_list as $row)

            <tr>
                <td>
                    <div align="center">{!! $i !!}<span
                                class=<?php if( $row['OPTIONALYN'] == 'N'){?>'required-star' <?php } ?>></span></div>
                </td>
                <td colspan="6">{!!  $row['FILENAME'] !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['FILEID'].'@'.$row['FILENAME'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['FILEID']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['FILEID']]['file']) ? $clrDocuments[$row['FILEID']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['FILENAME'] !!}"
                           id="doc_name_<?php echo $row['FILEID']; ?>"
                           name="doc_name_<?php echo $row['FILEID']; ?>"/>
                    <input name="<?php echo $row['FILEID']; ?>"
                           class="<?php if( ($row['OPTIONALYN'] == 'N') && (empty($clrDocuments[$row['FILEID']]['file']))){?>required<?php } ?>"
                           id="<?php echo $row['FILEID']; ?>" type="file"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row['FILEID']; ?>', this.id, 'validate_field_<?php echo $row['FILEID']; ?>', '')"/>

                    @if(!empty($clrDocuments[$row['FILEID']]))
                        <div class="save_file saved_file_{{$row['FILEID']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['FILEID']]['file']) ?
                                                                    $clrDocuments[$row['FILEID']]['file'] : ''))}}"
                               title="{{$row['FILENAME']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['FILEID']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['FILEID'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['FILEID']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['FILEID']]['file']) ?
                                   $clrDocuments[$row['FILEID']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['FILEID']; ?>"
                               name="validate_field_<?php echo $row['FILEID']; ?>"
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