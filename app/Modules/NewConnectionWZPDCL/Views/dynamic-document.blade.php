
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached PDF file
                <span style="color:darkred; font-size:12px; ">(Each File Maximum size 300kb)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>

        @foreach($attachment_list as $row)
            <tr>
                <td>
                    <div align="center">{!! $i !!}<span
                                class=<?php if( $row['Required'] == true){?>'required-star' <?php } ?>></span></div>
                </td>
                <td colspan="6">{!!  $row['Name'] !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['ID'].'@'.$row['Name'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['Name']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['ID']]['file']) ? $clrDocuments[$row['ID']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['Name'] !!}"
                           id="doc_name_<?php echo $row['ID']; ?>"
                           name="doc_name_<?php echo $row['ID']; ?>"/>
                    <input name="upload_name<?php echo $row['ID']; ?>"
                           id="<?php echo $row['ID']; ?>" type="file"
                           class="<?php if(($row['Required'] == true) && (empty($clrDocuments[$row['ID']]['file']))){?> required <?php } ?>"
                           <?php if ($row['Required'] == true) {
                               echo "data-required='required'";
                           } else {
                               echo "data-required=''";
                           } ?>
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row['ID']; ?>', this.id, 'validate_field_<?php echo $row['ID']; ?>', '')"/>

                    @if(!empty($clrDocuments[$row['ID']]))

                        <div class="save_file saved_file_{{$row['ID']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['ID']]['file']) ?
                                                                    $clrDocuments[$row['ID']]['file'] : ''))}}"
                               title="{{$row['Name']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['ID']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['document']['id'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['ID']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['ID']]['file']) ?
                                   $clrDocuments[$row['ID']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['ID']; ?>"
                               name="validate_field_<?php echo $row['ID']; ?>"
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