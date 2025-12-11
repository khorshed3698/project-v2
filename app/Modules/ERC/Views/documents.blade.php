<div class="table-responsive">
    <h5><strong>(Attach Document (Maximum File Size will be 2 MB. Scan এর সময় DPI 100 এবং Color এ ফাইল Scan করতে হবে।
            ফাইল JPG/PDF/PNG/JPEG ফরম্যাট এ save করতে হবে)</strong></h5>
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached file
                <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                      title="Attached PDF file (Each File Maximum size 3MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @foreach($attachment_list as $row)
            @if(!in_array($row['document_id'],[219,136,129]))

            <tr>
                <td>
                    <div align="center">{!! $i !!}<span
                                class=<?php echo $row['is_required_doc'] == "1" ? "required-star" : '';  ?>></span>
                    </div>
                </td>
                <td colspan="6">{!!  $row['document_name_en'] !!}</td>
                <td colspan="2">
                    <input type="hidden" value="{!!  $row['document_id'].'@'.$row['document_name_en'] !!}"
                           name="dynamicDocumentsId[]"/>

                    <input name="<?php echo $row['document_name_en']; ?>"
                           id="<?php echo $row['document_id']; ?>" type="file"
                           class="<?php
                           if (empty($clrDocuments[$row['document_id']]['file'])) {
                               echo $row['is_required_doc'] == "1" ? "required" : '';
                           }
                           ?>"
                           data-required="<?php
                           echo $row['is_required_doc'] == "1" ? "required" : '';
                           ?>"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row['document_id']; ?>', this.id, 'validate_field_<?php echo $row['document_id']; ?>', <?php echo $row['is_required_doc']; ?>)"/>

                    @if(!empty($clrDocuments[$row['document_id']]))
                        <div class="save_file saved_file_{{$row['document_id']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['document_id']]['file']) ?
                                                                    $clrDocuments[$row['document_id']]['file'] : ''))}}"
                               title="{{$row['document_name_en']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['document_id']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['document_name_en'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif
                    <div id="preview_<?php echo $row['document_id']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['document_id']]['file']) ?
                                   $clrDocuments[$row['document_id']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['document_id']; ?>"
                               name="validate_field_<?php echo $row['document_id']; ?>"
                               class="<?php echo $row['is_required_doc'] == "1" ? "required" : '';  ?>"/>
                    </div>

                </td>
            </tr>
            <?php $i++; ?>
            @endif
        @endforeach
        </tbody>
    </table>
</div>