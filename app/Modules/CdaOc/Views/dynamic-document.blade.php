<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
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
            <tr>
                <td>
                    <div align="center" class="required-star">{{ $i }}</div>
                </td>
                <td colspan="6">{!! $row['AttachmentTypeName']!!}</td>
                <td colspan="2">
                    <input type="hidden" value="{!! $row['AttachmentId'].'@'.$row['AttachmentTypeName'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_{{ $row['AttachmentId'] }}"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['AttachmentId']]['file']) ? $clrDocuments[$row['AttachmentId']]['file'] : '')}}">
                    <input type="hidden" value="{!!  $row['AttachmentTypeName'] !!}"
                           id="doc_name_{{ $row['AttachmentId'] }}"
                           name="doc_name_{{ $row['AttachmentId'] }}"/>
                    <input name="{{ $row['AttachmentId'] }}"
                           @if(empty($clrDocuments[$row['AttachmentId']]['file']))
                           class="required"
                           @endif
                           {{--                           @if($row['AttachmentId'] == 'Photo')--}}
                           {{--                           flag="img"--}}
                           {{--                           @endif--}}
                           id="doc_id_{{ $row['AttachmentId'] }}"
                           type="file"
                           accept="application/pdf"
                           size="20"
                           onchange="uploadDocument('preview_{{ $row['AttachmentId'] }}', this.id, 'validate_field_{{ $row['AttachmentId'] }}', '')"/>
                    {{--                    @if($row['AttachmentId'] == 'Photo')--}}
                    {{--                        <span style="color:#993333;">[N.B. Only image (JPG/JPEG/PNG)]</span>--}}
                    {{--                    @else--}}
                    {{--                    <span style="color:#993333;">[N.B. Only PDF]</span>--}}
                    {{--                    @endif--}}

                    {{-- if this document hav attachment then show it --}}
                    @if(!empty($clrDocuments[$row['AttachmentId']]['file']))
                        <div class="save_file saved_file_{{$row['AttachmentId']}}">
                            <a target="_blank"
                               class="documentUrl btn btn-xs btn-primary"
                               href="{{URL::to('/uploads/'.$clrDocuments[$row['AttachmentId']]['file'])}}"
                               title="{{$row['AttachmentTypeName']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> Open File
                            </a>

                            <a href="javascript:void(0)" onclick="ConfirmDeleteFile({{ $row['AttachmentId'] }})">
                                <span class="btn btn-xs btn-danger">
                                    <i class="fa fa-times"></i>
                                </span>
                            </a>
                        </div>
                    @endif


                    <div id="preview_{{ $row['AttachmentId'] }}">
                        <input type="hidden"
                               value="{{ !empty($clrDocuments[$row['AttachmentId']]['file']) ? $clrDocuments[$row['AttachmentId']]['file'] : '' }}"
                               id="validate_field_{{ $row['AttachmentId'] }}"
                               name="validate_field_{{ $row['AttachmentId'] }}"
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