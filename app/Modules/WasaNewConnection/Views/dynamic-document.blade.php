
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
                    @if(isset($row['is_required'])?$row['is_required'] == 1 :'' ) class="required-star" @endif >{!!  $row['documentDescription'] !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['documentId'].'@'.$row['documentDescription'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['documentId']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['documentId']]['file']) ? $clrDocuments[$row['documentId']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['documentDescription'] !!}"
                           name="doc_name_<?php echo $row['documentId']; ?>"/>
                    <input name="document_<?php echo $row['documentId']; ?>"
                           @if((isset($row['is_required'])?$row['is_required'] == 1 :'') && empty($clrDocuments[$row['documentId']]['file'])) class="required"
                           @endif
                           id="document_<?php echo $row['documentId']; ?>" type="file"
                           size="20" @if($row['documentId'] == 'Photo') flag="img" @endif
                           onchange="uploadDocument('preview_<?php echo $row['documentId']; ?>', this.id, 'validate_field_<?php echo $row['documentId']; ?>', '')"/>
{{--                        @if($row['documentId'] == 'Photo')--}}
{{--                            <span style="color:#993333;">[N.B. Only image (JPG/JPEG/PNG)]</span>--}}
{{--                        @else--}}
{{--                            <span style="color:#993333;">[N.B. Only PDF]</span>--}}
{{--                        @endif--}}


                    @if(!empty($clrDocuments[$row['documentId']]))

                        <div class="save_file saved_file_{{$row['documentId']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['documentId']]['file']) ?
                                                                    $clrDocuments[$row['documentId']]['file'] : ''))}}"
                               title="{{$row['documentDescription']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['documentId']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['documentId'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['documentId']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['documentId']]['file']) ?
                                   $clrDocuments[$row['documentId']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['documentId']; ?>"
                               name="validate_field_<?php echo $row['documentId']; ?>"
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