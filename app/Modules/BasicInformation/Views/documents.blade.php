<div class="panel panel-primary">
    <div class="panel-heading"><strong>Paper's/ documents needed for recommendation of Visa   in favor of the expatriate(s) to be employed in branch/ liaison/ representative office and other private and public enterprise.</strong>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" aria-label="Detailed Data Table">
                <thead>
                <tr>
                    <th>No.</th>
                    <th colspan="6">Required attachments</th>
                    <th colspan="2">Attached PDF file
                        {{--<span>--}}
                            {{--<i title="Attached PDF file (Each File Maximum size 1MB)!" data-toggle="tooltip" data-placement="right" class="fa fa-question-circle" aria-hidden="true"></i>--}}
                        {{--</span>--}}
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                @if(count($document) > 0)
                    @foreach($document as $row)
                        <tr>
                            <td>
                                <div align="left">{!! $i !!}<?php echo $row->doc_priority == "1" ? "<span class='required-star'></span>" : ""; ?></div>
                            </td>
                            <td colspan="6">{!!  $row->doc_name !!}</td>
                            <td colspan="2">
                                <input type="hidden" name="document_id_<?php echo $row->id; ?>" id="" value="{{(!empty($clrDocuments[$row->id]['document_id']) ? $clrDocuments[$row->id]['document_id'] : '')}}">
                                <input type="hidden" name="doc_name_<?php echo $row->id; ?>" id="doc_name_<?php echo $row->id; ?>" value="{!!  $row->doc_name !!}"/>
                                @if($viewMode != 'on')
                                    <input type="file" accept="application/pdf" name="file<?php echo $row->id; ?>" id="file<?php echo $row->id; ?>"
                                           <?php if (empty($clrDocuments[$row->id]['file']) && empty($allRequestVal["file$row->id"]) && $row->doc_priority == "1") {
                                               echo "class='form-control input-sm required'";
                                           } ?> class="form-control" onchange="uploadDocument('preview_<?php echo $row->id; ?>', this.id, 'validate_field_<?php echo $row->id; ?>', '<?php echo $row->doc_priority; ?>')"/>
                                @endif

                                @if($row->additional_field == 1)
                                    <table aria-label="Detailed Data Table">
                                        <tr>
                                            <th aria-hidden="true" scope="col"></th>
                                        </tr>
                                        <tr>
                                            <td>Other file Name :</td>
                                            <td><input maxlength="64" class="form-control input-sm <?php if ($row->doc_priority == "1") {
                                                    echo 'required';
                                                } ?>" name="other_doc_name_<?php echo $row->id; ?>"
                                                       type="text" value="{{(!empty($clrDocuments[$row->id]['doc_name']) ? $clrDocuments[$row->id]['doc_name'] : '')}}">
                                            </td>
                                        </tr>
                                    </table>
                                @endif

                                @if(!empty($clrDocuments[$row->id]['file']))
                                    <div class="save_file saved_file_{{$row->id}}">
                                        <a target="_blank" rel="noopener" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row->id]['file']) ?
                                                            $clrDocuments[$row->id]['file'] : ''))}}"
                                           title="{{$row->doc_name}}">
                                            <i class="fa fa-file-pdf-o"
                                               aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row->id]['file']); echo end($file_name); ?>
                                        </a>
                                    </div>
                                @elseif($viewMode == 'on' && empty($clrDocuments[$row->id]['file']))
                                    No File Found!
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
                                    <input type="hidden" class="required" value="{{$allRequestVal["validate_field_".$row->id]}}" id="validate_field_{{$row->id}}" name="validate_field_{{$row->id}}">
                                @endif

                            </td>
                        </tr>
                        <?php $i++; ?>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9" style="text-align: center"><span class="label label-info">No Required Documents!</span></td>
                    </tr>
                @endif
                <tr>
                    <td>N.B</td>
                    <td colspan="6">All documents shall have to be attested by the Chairman/ CEO / Managing dirctor/ Country Manager/ Chief executive of the Company/ firms.</td>
                    <td colspan="2">Document's must be submitted by an authorized person of the organization including the letter of authorization.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
    $('[data-toggle="tooltip"]').tooltip();
</script>