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
            <?php
            $row['is_required'] = 0;
            ?>
            <tr>
                <td>
                    <div align="center">{!! $i !!}<span></span></div>
                </td>
                <td colspan="6"
                    @if($row['is_required'] == 1 ) class="required-star" @endif >{!!  $row['type'] !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['code'].'@'.$row['type'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['code']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['code']]['file']) ? $clrDocuments[$row['code']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['type'] !!}"
                           id="doc_name_<?php echo $row['code']; ?>"
                           name="doc_name_<?php echo $row['code']; ?>"/>
                    <input name="<?php echo $row['code']; ?>"
                           @if($row['is_required'] == 1 && empty($clrDocuments[$row['code']]['file'])) class="required"
                           @endif
                           id="<?php echo $row['code']; ?>" type="file"
                           size="20" @if($row['code'] == 'Photo') flag="img" @endif
                           onchange="uploadDocument('preview_<?php echo $row['code']; ?>', this.id, 'validate_field_<?php echo $row['code']; ?>', '')"/>


                    @if(!empty($clrDocuments[$row['code']]))

                        <div class="save_file saved_file_{{$row['code']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['code']]['file']) ?
                                                                    $clrDocuments[$row['code']]['file'] : ''))}}"
                               title="{{$row['type']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['code']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['code'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['code']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['code']]['file']) ?
                                   $clrDocuments[$row['code']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['code']; ?>"
                               name="validate_field_<?php echo $row['code']; ?>"
                               class="required"/>
                    </div>

                </td>
            </tr>
            <?php $i++; ?>
        @endforeach
        </tbody>
    </table>
</div>