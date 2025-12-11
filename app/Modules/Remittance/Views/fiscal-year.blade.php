<div class="panel panel-info">
    <div class="panel-heading"><strong>17. Other remittance made/to be made during the same Calendar/Fiscal year (If
            applicable)</strong></div>
    <div class="panel-body">
        <div class="table-responsive">
            <table aria-label="Detailed Report Data Table" id="remittancemadeTable" class="table table-bordered dt-responsive" cellspacing="0" width="100%">

                <thead>
                <tr>
                    <td>Type of fee</td>
                    <td>Taka (BDT)</td>
                    <td>USD</td>
                    <td>% of SI. 10 or 11(a)</td>
                    <td>Attachment <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum File size 3MB]</span>
                    </td>
                    <td>#</td>
                </tr>
                </thead>

                <tbody>
                @if(count($getRemittanceInfo) > 0)
                    <?php $inc = 0; ?>
                    @foreach($getRemittanceInfo as $remittance)
                        <tr id="remittancemadeTableRow{{$inc}}">
                            <td>
                                {!! Form::select('other_remittance_type_id[]', $remittanceType, $remittance->remittance_type_id,
                                ['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                                {!! $errors->first('other_remittance_type_id','<span class="help-block">:message</span>') !!}

                            </td>
                            <td>
                                {!! Form::text('other_remittance_bdt[]', $remittance->proposed_amount_bdt, ['class' => 'form-control input-md other_remittance_bdt', 'onkeyup' => "calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt')"]) !!}
                                {!! $errors->first('other_remittance_bdt','<span class="help-block">:message</span>') !!}

                            </td>
                            <td>
                                {!! Form::text('other_remittance_usd[]', $remittance->proposed_amount_usd, ['class' => 'form-control input-md other_remittance_usd', 'onkeyup' => "calculateOneNumber('other_remittance_usd', 'other_sub_total_usd')"]) !!}
                                {!! $errors->first('other_remittance_usd','<span class="help-block">:message</span>') !!}
                            </td>

                            <td>
                                {!! Form::text('other_remittance_percentage[]', $remittance->proposed_exp_percentage, ['class' => 'form-control input-md other_remittance_percentage', 'onkeyup' => "calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage')"]) !!}
                                {!! $errors->first('other_remittance_percentage','<span class="help-block">:message</span>') !!}
                            </td>

                            <td>
                                <input type="file" id="other_remittance_attachment" name="other_remittance_attachment[]"
                                       value="{{$remittance->other_remittance_attachment}}"
                                       class="form-control input-md"/>
                                {!! $errors->first('other_remittance_attachment','<span class="help-block">:message</span>') !!}

                                @if(!empty($remittance->other_remittance_attachment))
                                    <a target="_blank" rel="noopener" class="documentUrl"
                                       href="{{URL::to('/uploads/'.(!empty($remittance->other_remittance_attachment) ? $remittance->other_remittance_attachment : ''))}}"
                                       title="{{$remittance->other_remittance_attachment}}">
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                        <?php $file_name = explode('/', $remittance->other_remittance_attachment); echo end($file_name); ?>
                                    </a>
                                @endif
                            </td>
                            <td style="text-align: left;">
                                @if($inc==0)
                                    <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                                       onclick="addFiscalYearRow('remittancemadeTable', 'remittancemadeTableRow0');">
                                        <i class="fa fa-plus"></i></a>
                                @else
                                    @if($viewMode != 'on')
                                        <a href="javascript:void(0);" class="btn btn-sm btn-danger removeRow"
                                           onclick="removeFiscalYearRow('remittancemadeTable','remittancemadeTableRow{{$inc}}');">
                                            <i class="fa fa-times" aria-hidden="true"></i></a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <?php $inc++; ?>
                    @endforeach
                @else
                    <tr id="remittancemadeTableRow">
                        <td>
                            {!! Form::select('other_remittance_type_id[]', $remittanceType,['0' => 'Select One'],
                                    ['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                            {!! $errors->first('other_remittance_type_id','<span class="help-block">:message</span>') !!}

                        </td>
                        <td>
                            {!! Form::text('other_remittance_bdt[]', '', ['class' => 'form-control input-md other_remittance_bdt', 'onkeyup' => "calculateOneNumber('other_remittance_bdt', 'other_sub_total_bdt')"]) !!}
                            {!! $errors->first('other_remittance_bdt','<span class="help-block">:message</span>') !!}

                        </td>
                        <td>
                            {!! Form::text('other_remittance_usd[]', '', ['class' => 'form-control input-md other_remittance_usd', 'onkeyup' => "calculateOneNumber('other_remittance_usd', 'other_sub_total_usd')"]) !!}
                            {!! $errors->first('other_remittance_usd','<span class="help-block">:message</span>') !!}
                        </td>

                        <td>
                            {!! Form::text('other_remittance_percentage[]', '', ['class' => 'form-control input-md other_remittance_percentage', 'onkeyup' => "calculateRemittance('other_remittance_percentage', 'other_sub_total_percentage')"]) !!}
                            {!! $errors->first('other_remittance_percentage','<span class="help-block">:message</span>') !!}
                        </td>

                        <td>
                            <input type="file" id="other_remittance_attachment" name="other_remittance_attachment[]"
                                   class="form-control input-md"/>
                            {!! $errors->first('other_remittance_attachment','<span class="help-block">:message</span>') !!}
                        </td>

                        <td style="text-align: left;">
                            <a class="btn btn-sm btn-primary addTableRows" title="Add more"
                               onclick="addFiscalYearRow('remittancemadeTable', 'remittancemadeTableRow');">
                                <i class="fa fa-plus"></i></a>
                        </td>
                    </tr>
                @endif
                </tbody>

                <tfoot>
                <tr>
                    <th scope="col">Sub Total</th>
                    <td>
                        {!! Form::text('other_sub_total_bdt', '', ['class' => 'form-control input-md number', 'id' => 'other_sub_total_bdt','readonly']) !!}
                        {!! $errors->first('other_sub_total_bdt','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('other_sub_total_usd', '', ['class' => 'form-control input-md number', 'id' => 'other_sub_total_usd','readonly']) !!}
                        {!! $errors->first('other_sub_total_usd','<span class="help-block">:message</span>') !!}
                    </td>
                    <td>
                        {!! Form::text('other_sub_total_percentage', '', ['class' => 'form-control input-md number', 'id' => 'other_sub_total_percentage', 'readonly']) !!}
                        {!! $errors->first('other_sub_total_percentage','<span class="help-block">:message</span>') !!}
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                </tfoot>

            </table>
        </div>
    </div>
</div>