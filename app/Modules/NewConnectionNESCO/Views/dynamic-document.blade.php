
@if(!empty($attachment_list))
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover ">
            <thead>
            <tr>
                <th>No.</th>
                <th colspan="6">Required Attachments</th>
                <th colspan="2">Attached PDF file
                    <span style="color:darkred; font-size:12px; ">(Each File Maximum size 300KB)</span>
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
                    <td colspan="6">{!!  $row['document']['description'] !!}</td>
                    <td colspan="2">
                        {{--                    {{dd($clrDocuments)}}--}}
                        <input type="hidden" value="{!!  $row['document']['id'].'@'.$row['document']['description'] !!}"
                               name="dynamicDocumentsId[]"/>
                        <input name="document_id_<?php echo $row['document']['id']; ?>"
                               type="hidden"
                               value="{{(!empty($clrDocuments[$row['document']['id']]['file']) ? $clrDocuments[$row['document']['id']]['file'] : '')}}">
                        <input type="hidden" value="{!!  $row['document']['description'] !!}"
                               id="doc_name_<?php echo $row['document']['id']; ?>"
                               name="doc_name_<?php echo $row['document']['id']; ?>"/>
                        <input name="upload_name<?php echo $row['document']['id']; ?>"
                               id="upload_id<?php echo $row['document']['id']; ?>" type="file"
                               size="20"
                               onchange="uploadDocument('preview_<?php echo $row['document']['id']; ?>', this.id, 'validate_field_<?php echo $row['document']['id']; ?>', '')"/>

                        @if(!empty($clrDocuments[$row['document']['id']]))

                            <div class="save_file saved_file_{{$row['document']['id']}}">
                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['document']['id']]['file']) ?
                                                                    $clrDocuments[$row['document']['id']]['file'] : ''))}}"
                                   title="{{$row['document']['description']}}">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['document']['id']]['file']); echo end($file_name); ?>
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


                        <div id="preview_<?php echo $row['document']['id']; ?>">
                            <input type="hidden"
                                   value="<?php echo !empty($clrDocuments[$row['document']['id']]['file']) ?
                                       $clrDocuments[$row['document']['id']]['file'] : ''?>"
                                   id="validate_field_<?php echo $row['document']['id']; ?>"
                                   name="validate_field_<?php echo $row['document']['id']; ?>"
                                   class="required"/>
                        </div>

                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach

            </tbody>
        </table>
    </div><!-- /.table-responsive -->
@else
    <div class="well"><strong>No document input combination is found.</strong></div>
@endif