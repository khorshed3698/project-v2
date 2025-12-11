<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="2">Attached PDF file
                <span style="color:darkred; font-size:12px; ">(Each File Maximum size 2MB)</span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @if(count($document) > 0)
            @foreach($document as $row)
                <tr>
                    <td>
                        <div align="center">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                    </td>
                    <td colspan="6">{!!  $row->doc_name !!}</td>
                    <td colspan="2">
                        <input name="document_id_<?php echo $row->id; ?>"
                               type="hidden"
                               value="{{(!empty($clrDocuments[$row->id]['doucument_id']) ? $clrDocuments[$row->id]['doucument_id'] : '')}}">
                        <input type="hidden" value="{!!  $row->doc_name !!}"
                               id="doc_name_<?php echo $row->id; ?>"
                               name="doc_name_<?php echo $row->id; ?>"/>
                        <input name="file<?php echo $row->id; ?>"
                               <?php if (empty($clrDocuments[$row->id]['file']) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                   echo "class='required'";
                               } ?>
                               <?php if ($row->doc_priority == "1") {
                                   echo "data-required='required'";
                               } else {
                                   echo "data-required=''";
                               } ?>
                               id="<?php echo $row->id; ?>" type="file"
                               size="20"
                               onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id,
                                       'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>

                        @if($row->additional_field == 1)
                            <table>
                                <tr>
                                    <td>Other file Name :</td>
                                    <td><input maxlength="64"
                                               class="form-control input-sm <?php if ($row->doc_priority == "1") {
                                                   echo 'required';
                                               } ?>"
                                               name="other_doc_name_<?php echo $row->id; ?>"
                                               type="text"
                                               value="{{(!empty($clrDocuments[$row->id]['doc_name']) ? $clrDocuments[$row->id]['doc_name'] : '')}}">
                                    </td>
                                </tr>
                            </table>
                        @endif
                        @if(!empty($clrDocuments[$row->id]['file']))
                            <div class="save_file saved_file_{{$row->id}}">
                                <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                                    $clrDocuments[$row->id]['file'] : ''))}}"
                                   title="{{$row->doc_name}}">
                                    <i class="fa fa-file-pdf-o"
                                       aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                </a>
                            </div>
                        @endif


                        <div id="preview_<?php echo $row->id; ?>">
                            <input type="hidden"
                                   value="<?php echo !empty($clrDocuments[$row->id]['file']) ?
                                       $clrDocuments[$row->id]['file'] : ''?>"
                                   id="validate_field_<?php echo $row->id; ?>"
                                   name="validate_field_<?php echo $row->id; ?>"
                                   class="<?php echo $row->doc_priority == "1" ? "required" : '';  ?>"/>
                        </div>


                        @if(!empty($allRequestVal["file$row->id"]))
                            <label id="label_file{{$row->id}}"><b>File: {{$allRequestVal["file$row->id"]}}</b></label>
                            <input type="hidden" class="required"
                                   value="{{$allRequestVal["validate_field_".$row->id]}}"
                                   id="validate_field_{{$row->id}}"
                                   name="validate_field_{{$row->id}}">
                        @endif

                    </td>
                </tr>
                <?php $i++; ?>
            @endforeach
        @else
            <tr>
                <td colspan="8" style="text-align: center"><span
                            class="label label-info">No Required Documents!</span></td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
