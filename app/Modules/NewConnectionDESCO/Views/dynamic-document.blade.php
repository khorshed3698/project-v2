
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached PDF file <span
                        style="color:darkred; font-size:12px; ">(Each File Maximum size 2MB)</span></th>
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
                           @if($row->isRequired == 1 && empty($clrDocuments[$row->id]['file'])) class="required" @endif
                           id="<?php echo $row->id; ?>" type="file"
                           <?php if ($row->isRequired == 1) {
                               echo "data-required='required'";
                           } else {
                               echo "data-required=''";
                           } ?>
                           size="20"
                           onchange="commonUploadImagePDF('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '')"/>

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