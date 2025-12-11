<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached file
                <span style="color:darkred; font-size:12px; ">(Attachment types: DOC, DOCX, XLS, XLSX, TXT, JPG, PNG and PDF. Each file can have size up to 4MB)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>

        {{--                {{dd($clrDocuments)}}--}}
        @foreach($attachment_list as $row)

            <tr>
                <td>
                    <div align="center">{!! $i !!}</div>
                </td>
                <td colspan="6">{!!  $row['NAME'] !!}</td>
                <td colspan="2">
                    <input type="hidden" value="{!!  $row['ATT_DOCTYPE'].'@'.$row['NAME'] !!}"
                           name="dynamicDocumentsId[]"/>

                    <input name="<?php echo $row['NAME']; ?>"
                           id="<?php echo $row['ATT_DOCTYPE']; ?>" type="file"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row['ATT_DOCTYPE']; ?>', this.id, 'validate_field_<?php echo $row['ATT_DOCTYPE']; ?>', 0)"/>

                    @if(!empty($clrDocuments[$row['ATT_DOCTYPE']]))
                        <div class="save_file saved_file_{{$row['ATT_DOCTYPE']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['ATT_DOCTYPE']]['file']) ?
                                                                    $clrDocuments[$row['ATT_DOCTYPE']]['file'] : ''))}}"
                               title="{{$row['NAME']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['ATT_DOCTYPE']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['NAME'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif
                    <div id="preview_<?php echo $row['ATT_DOCTYPE']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['ATT_DOCTYPE']]['file']) ?
                                   $clrDocuments[$row['ATT_DOCTYPE']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['ATT_DOCTYPE']; ?>"
                               name="validate_field_<?php echo $row['ATT_DOCTYPE']; ?>"/>
                    </div>

                </td>
            </tr>
            <?php $i++; ?>
        @endforeach
        </tbody>
    </table>
</div>