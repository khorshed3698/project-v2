{{--<div class="panel panel-primary" style="margin: 4px;">--}}
{{--    <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>--}}
{{--    <div class="panel-body">--}}
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover ">
        <thead>
        <tr>
            <th>No.</th>
            <th colspan="6">Required Attachments</th>
            <th colspan="6">Remarks</th>
            <th colspan="2">Attached PDF file
                <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                      title="Attached PDF file (Each File Maximum size 3MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
            </th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        {{--                {{dd($clrDocuments)}}--}}
        @foreach($shortfallarr as $row)

            <tr>
                <td>
                    <div align="center">{!! $i !!}<span
                                class='required-star'></span></div>
                </td>
                <td colspan="6">{!!  $row->DOC_NAME !!}</td>
                <td colspan="6"><input type="text" name="remarks[]" class="form-control"></td>
                <td colspan="2">
                    <input type="hidden" value="{!!  $row->DOC_ID.'@'.$row->DOC_NAME !!}"
                           name="dynamicDocumentsId[]"/>
                    <input name="document_id_{!!  $row->DOC_ID !!}"
                           type="hidden"
                           value="{!!  $row->DOC_ID !!}">
                    <input type="hidden" value="{!!  $row->DOC_NAME !!}"
                           id="doc_name_<?php echo $row->DOC_ID; ?>"
                           name="doc_name_<?php echo $row->DOC_ID; ?>"/>
                    <input name="<?php echo $row->DOC_ID; ?>"
                           class="required"
                           id="<?php echo $row->DOC_ID; ?>" type="file"
                           size="20"
                           onchange="uploadDocument('preview_<?php echo $row->DOC_ID; ?>', this.id, 'validate_field_<?php echo $row->DOC_ID; ?>', '')"/>


                    <div id="preview_<?php echo $row->DOC_ID; ?>">
                        <input type="hidden"
                               value=""
                               id="validate_field_<?php echo $row->DOC_ID; ?>"
                               name="validate_field_<?php echo $row->DOC_ID; ?>"
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