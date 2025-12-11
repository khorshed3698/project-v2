

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>নং </th>
            <th colspan="5"> প্রয়োজনীয় সংযুক্তি </th>
            <th colspan="3"> ফাইল সংযুক্ত [সংযুক্ত পিডিএফ ফাইল,প্রতিটি ফাইল সর্বাধিক <br> ৩ এম.বি (MB) এবং ইমেজ ফাইল ১৫০ কে.বি (KB) আকারের]
            </th>

            @if (isset($attachment_list[0]['remarks']))
                <th>মন্তব্য</th>
            @endif
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        {{--                {{dd($clrDocuments)}}--}}
        @foreach($attachment_list as $row)

            <tr>
                <td>
                    <div align="center">{!! $i !!}<span
                                @if($row['is_required'] == 1) class='required-star' @endif></span></div>
                </td>
                <td colspan="5">{!!  $row['description'] !!}</td>
                <td colspan="3">
                    <input type="hidden" value="{!!  $row['fileTypeId'].'@'.$row['description'] !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_<?php echo $row['fileTypeId']; ?>"
                           type="hidden"
                           value="{{(!empty($clrDocuments[$row['fileTypeId']]['doucument_id']) ? $clrDocuments[$row['fileTypeId']]['doucument_id'] : '')}}">
                    <input type="hidden" value="{!!  $row['description'] !!}"
                           id="doc_name_<?php echo $row['fileTypeId']; ?>"
                           name="doc_name_<?php echo $row['fileTypeId']; ?>"/>
                    <input name="input_<?php echo $row['fileTypeId']; ?>"
                           @if($row['is_required'] == 1 && empty($clrDocuments[$row['fileTypeId']]['file']))
                           class="required"
                           @endif
                           id="input_<?php echo $row['fileTypeId']; ?>" type="file"
                           size="20" @if($row['fileFormatAllowed'] == 'JPEG/PNG') flag="img" @endif
                           onchange="uploadDocument('preview_<?php echo $row['fileTypeId']; ?>', this.id, 'validate_field_<?php echo $row['fileTypeId']; ?>', '')"/>
                        @if($row['fileFormatAllowed'] == 'JPEG/PNG')
                            <span style="color:#993333;">[বিঃদ্রঃ শুধুমাত্র ছবি (.JPG)]</span>
                        @else
                            <span style="color:#993333;">[বিঃদ্রঃ শুধুমাত্র পিডিএফ (PDF)]</span>
                        @endif

                    @if(!empty($clrDocuments[$row['fileTypeId']]))
                        <div class="save_file saved_file_{{$row['fileTypeId']}}">
                            <a target="_blank" class="documentUrl" href="{{URL::to('/uploads/'.(!empty($clrDocuments[$row['fileTypeId']]['file']) ?
                                                                    $clrDocuments[$row['fileTypeId']]['file'] : ''))}}"
                               title="{{$row['description']}}">
                                <i class="fa fa-file-pdf-o"
                                   aria-hidden="true"></i> <?php $file_name = explode('/', $clrDocuments[$row['fileTypeId']]['file']); echo end($file_name); ?>
                            </a>

                            <?php if(!empty($appInfo) && Auth::user()->id == $appInfo->created_by && $viewMode != 'on') {?>
                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile({{ $row['fileTypeId'] }})">
                                                                    <span class="btn btn-xs btn-danger"><i
                                                                                class="fa fa-times"></i></span>
                            </a>
                            <?php } ?>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['fileTypeId']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['fileTypeId']]['file']) ?
                                   $clrDocuments[$row['fileTypeId']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['fileTypeId']; ?>"
                               name="validate_field_<?php echo $row['fileTypeId']; ?>"
                               class="required"/>
                    </div>

                </td>
                @if (isset($row['remarks']))
                    <td>{{$row['remarks']}}</td>
                @endif
            </tr>
            <?php $i++; ?>
        @endforeach
        </tbody>
    </table>
</div><!-- /.table-responsive -->
{{--    </div><!-- /.panel-body -->--}}
{{--</div>--}}