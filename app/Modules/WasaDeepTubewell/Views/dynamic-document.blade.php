
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Only *.jpg, *.pdf are allowed.
                <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                      title="Maximum size per document is 1024 Kb or 1 MB">
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
                <td colspan="6"
                    @if(isset($row['isMandatory'])?$row['isMandatory'] == 1 :'' ) class="required-star" @endif >{!!  $row['text'] !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['id'].'@'.$row['text'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['id']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['id']]['file']) ? $clrDocuments[$row['id']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['text'] !!}"
                           name="doc_name_<?php echo $row['id']; ?>"/>
                    <input name="document_<?php echo $row['id']; ?>"
                           @if((isset($row['isMandatory'])?$row['isMandatory'] == 1 :'') && empty($clrDocuments[$row['id']]['file'])) class="required"
                           @endif
                           id="document_<?php echo $row['id']; ?>" type="file"
                           size="20" @if($row['id'] == 'Photo') flag="img" @endif
                           onchange="uploadDocument('preview_<?php echo $row['id']; ?>', this.id, 'validate_field_<?php echo $row['id']; ?>', '')"/>
{{--                        @if($row['id'] == 'Photo')--}}
{{--                            <span style="color:#993333;">[N.B. Only image (JPG/JPEG/PNG)]</span>--}}
{{--                        @else--}}
{{--                            <span style="color:#993333;">[N.B. Only PDF]</span>--}}
{{--                        @endif--}}


                    @if(!empty($clrDocuments[$row['id']]))

                        <div class="save_file saved_file_{{$row['id']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['id']]['file']) ?
                                                                    $clrDocuments[$row['id']]['file'] : ''))}}"
                               title="{{$row['text']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['id']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['id'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['id']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['id']]['file']) ?
                                   $clrDocuments[$row['id']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['id']; ?>"
                               name="validate_field_<?php echo $row['id']; ?>"
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