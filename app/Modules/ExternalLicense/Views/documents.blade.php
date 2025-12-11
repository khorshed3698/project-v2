<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
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
        @if(count($document)>0)
        <?php $i = 1;
        $attachment_list = $document['attachment_list'];
        $clrDocuments = $document['clrDocuments'];
        ?>

        @foreach($attachment_list as $row)
                <?php
                $docName = str_replace('_',' ',$row['name_en']);
                ?>
            <tr>
                <td>
                    <div align="center">{!! $i !!}<span></span></div>
                </td>
                <td colspan="6"
                    @if($row['is_required'] == 1 ) class="required-star" @endif
                >{!!  $docName !!}</td>
                <td colspan="2">
                    {{--                    {{dd($clrDocuments)}}--}}
                    <input type="hidden" value="{!!  $row['doc_id'].'@'.$docName !!}"
                           name="dynamicDocumentsId[]"/>
                    <!-- <input name="document_id_<?php echo $row['doc_id']; ?>" type="hidden"
                    value="{{(!empty($clrDocuments[$row['doc_id']]['file']) ? $clrDocuments[$row['doc_id']]['file'] : '')}}">-->
                    <input type="hidden" value="{!!  $docName !!}"
                           id="doc_name_<?php echo $row['doc_id']; ?>"
                           name="doc_name_<?php echo $row['doc_id']; ?>"/>
                    @if(isset($row['is_upload']))
                        <input name="<?php echo $row['doc_id']; ?>"
                               @if($row['is_required'] == 1 && empty($clrDocuments[$row['doc_id']]['file'])) class="required"
                               @endif
                               id="<?php echo $row['doc_id']; ?>" type="file"
                               size="20" @if($row['doc_id'] == 'Photo') flag="img" @endif
                               onchange="uploadDocument('preview_<?php echo $row['doc_id']; ?>', this.id, 'validate_field_<?php echo $row['doc_id']; ?>', '')"/>
                    @endif


{{--                    @if($row['doc_id'] == 'Photo')--}}
{{--                        <span style="color:#993333;">[N.B. Only image (JPG/JPEG/PNG)]</span>--}}
{{--                    @else--}}
{{--                        <span style="color:#993333;">[N.B. Only PDF]</span>--}}
{{--                    @endif--}}


                    @if(!empty($clrDocuments[$row['doc_id']]) && !empty($clrDocuments[$row['doc_id']]['file']))

                        <div class="save_file saved_file_{{$row['doc_id']}}">
                            <?php

                                $docUrl  = URL::to('/uploads/'.(!empty($clrDocuments[$row['doc_id']]['file']) ?
                                        $clrDocuments[$row['doc_id']]['file'] : ''));
                                if(substr($clrDocuments[$row['doc_id']]['file'],0,4) == 'http'){
                                    $docUrl  = $clrDocuments[$row['doc_id']]['file'];
                                }
                                ?>
                           <a target="_blank" class="documentUrl btn btn-primary btn-xs" href="{{$docUrl}}"
                               title="{{$docName}}">
                                 View
                            </a>


                            <a href="javascript:void(0)"
                               onclick="ConfirmDeleteFile('{{ $row['doc_id'] }}')">
                                                                    <span class="btn btn-xs btn-danger">Detach</span>
                            </a><br>
                            <span class="text-info">{{isset($clrDocuments[$row['doc_id']]['tracking_no'])?'Tracking No :'. $clrDocuments[$row['doc_id']]['tracking_no']:""}}
                                {{isset($clrDocuments[$row['doc_id']]['submission_date'])?'Submission Date: '.$clrDocuments[$row['doc_id']]['submission_date']:''}}</span>
                        </div>
                    @endif


                    <div id="preview_<?php echo $row['doc_id']; ?>">
                        <input type="hidden"
                               value="<?php echo !empty($clrDocuments[$row['doc_id']]['file']) ?
                                   $clrDocuments[$row['doc_id']]['file'] : ''?>"
                               id="validate_field_<?php echo $row['doc_id']; ?>"
                               name="validate_field_<?php echo $row['doc_id']; ?>"
                               class="required"/>
                    </div>

                </td>
            </tr>
                <?php $i++; ?>
        @endforeach
        @endif
        </tbody>
    </table>
</div><!-- /.table-responsive -->
{{--    </div><!-- /.panel-body -->--}}
{{--</div>--}}