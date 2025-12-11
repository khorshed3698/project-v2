<div class="panel panel-info">
    {!! Form::open(array('url' => '/rjsc-particular-save', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'particular_form')) !!}
    {{ csrf_field() }}
    <div class="panel-heading"><strong>3. Particular Subscriber </strong></div>
    <div class="panel-body">

        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong>B. Particulars of Body Corporate Subscribers ( if any, as of
                        Memorandum and Aricles of associatio )</strong>
                </div>
                <div class="panel-body">
                    <table id="particular" class="table table-bordered table-hover">
                        <thead>
                        <tr style="width: 100%;background: #f5f5f7">
                            <th width="10%">SL</th>
                            <th width="25%">Name (of the corporation body)</th>
                            <th width="25%">Represented By (name of the representative)</th>
                            <th width="30%">Address (of the body corporate )</th>
                            <th width="10%">Number of Subscribed Shares</th>
                        </tr>
                        </thead>
                        <tbody id="particular_body">
                        <tr>
                            <td>
                                <input type="checkbox"> &nbsp 1
                            </td>
                            <td>
                                {!! Form::text('name_corporation_body[]','',['class' => 'col-md-7 form-control input-md required','placeholder' => '']) !!}
                                {!! $errors->first('name_corporation_body','<span class="help-block">:message</span>') !!}
                            </td>
                            <td>
                                {!! Form::text('represented_by[]','',['class' => 'col-md-7 form-control input-md required','placeholder' => '', 'required' => 'required']) !!}
                            </td>
                            <td>
                                {!! Form::textarea('address[]','',['class' => 'col-md-7 form-control input-md required','placeholder' => '','rows' => 2,'cols' => 1,'required' => 'required']) !!}
                                <div class="row">
                                    <div class="col-md-3">
                                        {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                    </div>
                                    <div class="col-md-9">
                                        {!! Form::select('district_id[]',$districts,['class' => 'form-control input-md required','placeholder' => 'Select One','required' => 'required']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </td>
                            <td>
                                {!! Form::number('no_subscribed_shares[]','',['class' => 'col-md-7 form-control input-md required','placeholder' => '','required' => 'required']) !!}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="col-md-4 col-md-offset-4">
                        <button class="btn btn-info btn-xs col-md-4" id="add_column" type="button">Add Row</button>
                        <button class="btn btn-danger btn-xs col-md-4" id="remove_column" type="button" style="margin-left:3px ">Remove Row</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-12">
            <div class="panel panel-info">

                <div class="panel-heading">
                    <strong>B. Qualification Shares of Each Director (as of Articles of
                        Association)</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">
                                {!! Form::label('no_qualific_share','1. Number of Qualification Shares :',['class'=>'col-md-5 text-left required-star','required' => 'required']) !!}
                                <div class="col-md-6">

                                    {!! Form::number('no_qualific_share','',['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required']) !!}

                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">

                                {!! Form::label('','2. Value of each Share (BDT) :',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-6">

                                    {!! Form::number('value_of_each_share','',['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required']) !!}

                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">
                                {!! Form::label('','3. Witness to the agreement of taking qualification Shares',['class'=>'col-md-10 text-left required_star','required' => 'required']) !!}
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-8 col-md-offset-2 {{$errors->has('') ? 'has-error': ''}}">

                                {!! Form::label('','a. Name of the Witness :',['class'=>'col-md-5 text-left required_star']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('agreement_witness_name','',['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required']) !!}
                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}

                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-8 col-md-offset-2 {{$errors->has('') ? 'has-error': ''}}">

                                {!! Form::label('','b. Address of Witness :',['class'=>'col-md-5 text-left required_star']) !!}
                                <div class="col-md-6">

                                    {!! Form::textarea('agreement_witness_address','',['class' => 'col-md-5 form-control input-md required','placeholder' => '', 'rows' => 2, 'cols' => 1,'required' => 'required']) !!}

                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-4">

                                                </div>
                                                <div class="col-md-3">
                                                    {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                                </div>
                                                <div class="col-md-5">
                                                    {!! Form::select('agreement_district_id',$districts,['class' => 'form-control input-md required','placeholder' => 'Select One','required' => 'required']) !!}
                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        </div>
        <div class="">
            <div class="col-md-6">
                <button class="btn btn-info" name="action_btn" id="draft" value="draft" type="submit">Save as Draft</button>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-success" name="action_btn" id="save" value="save" type="submit">Save and Continue</button>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

</div>

<script>
    $(document).ready(function () {
        $(document).on('click','#add_column',function () {
            var rowCount = $('#particular tr').length;
            $('#particular_body').append('<tr>\n' +
                '                            <td>\n' +
                '                                <input type="checkbox"> &nbsp; '+ rowCount +'\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md required" placeholder="" required="required" name="name_corporation_body[]" type="text" value="">\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md required" placeholder="" required="required" name="represented_by[]" type="text" value="">\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <textarea class="col-md-7 form-control input-md required" placeholder="" rows="2" cols="1" required="required" name="address[]"></textarea>\n' +
                '                                <div class="row">\n' +
                '                                    <div class="col-md-3">\n' +
                '                                        <label for="" class="col-md-4 text-left">District</label>\n' +
                '                                    </div>\n' +
                '                                    <div class="col-md-9">\n' +
                '                                        {!! Form::select('district_id[]',$districts,['class' => 'form-control input-md required','required' =>"required" ,'placeholder' => '']) !!} \n' +
                '                                        \n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md required" placeholder="" required="required" name="no_subscribed_shares[]" type="number" value="">\n' +
                '                            </td>\n' +
                '                        </tr>')
        })
        $(document).on('click','#remove_column',function () {
            var rowCount = $('#particular tr').length;
            if(rowCount > 2) {
                $('#particular tr:last').remove();
            }
        })

        $("#particular_form").validate();
    })


</script>

<script>

    $(document).ready(function () {
        $(document).on('click','#draft',function () {
            $('#particular_form').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $("#particular_form").validate();
        });
    });
</script>