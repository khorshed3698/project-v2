<div class="panel panel-info">
    {!! Form::open(array('url' => '/company-registration-sf/rjsc-particular-save', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'particular_form')) !!}
    {{ csrf_field() }}
    <div class="panel-heading"><strong>3. Particular Subscriber </strong></div>
    <div class="panel-body">

        <div class="col-md-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <strong>B. Particulars of Body Corporate Subscribers ( if any, as of
                        Memorandum and Articles of association )</strong>
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
                        <?php $i=1; ?>

                        @if(count($particulars)>0)
                            @foreach($particulars as $par_value)

                            <tr>
                                <td>
                                    <input type="checkbox"> &nbsp<span class="sNo">{{$i}}</span>
                                </td>
                                <td>
                                    {!! Form::text('name_corporation_body['.$i.']',$par_value->name_corporation_body,['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                    {!! $errors->first('name_corporation_body','<span class="help-block">:message</span>') !!}
                                </td>
                                <td>
                                    {!! Form::text('represented_by['.$i.']',$par_value->represented_by ,['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                </td>
                                <td>
                                    {!! Form::textarea('address['.$i.']',$par_value->address,['class' => 'col-md-7 form-control input-md','placeholder' => '','rows' => 2,'cols' => 1]) !!}
                                    <div class="row">
                                        <div class="col-md-3">
                                            {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                        </div>
                                        <div class="col-md-9">
                                            {!! Form::select('district_id['.$i.']',[],$par_value->district_id,['class' => 'form-control input-md particular_district_id','id'=>"district_id_$i",'data-id'=>$par_value->district_id]) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {!! Form::number('no_subscribed_shares['.$i.']',$par_value->no_subscribed_shares,['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                </td>
                            </tr>
                                <?php $i++;?>
                            @endforeach
                            @else
                            <tr>
                                <td>
                                    <input type="checkbox"> &nbsp <span class="sNo">1</span>
                                </td>
                                <td>
                                    {!! Form::text('name_corporation_body[1]','',['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                    {!! $errors->first('name_corporation_body','<span class="help-block">:message</span>') !!}
                                </td>
                                <td>
                                    {!! Form::text('represented_by[1]','',['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                </td>
                                <td>
                                    {!! Form::textarea('address[1]','',['class' => 'col-md-7 form-control input-md','placeholder' => '','rows' => 2,'cols' => 1]) !!}
                                    <div class="row">
                                        <div class="col-md-3">
                                            {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                        </div>
                                        <div class="col-md-9">
                                            {!! Form::select('district_id[1]',[],'',['class' => 'form-control input-md particular_district_id','id'=>"district_id_1",]) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    {!! Form::number('no_subscribed_shares[1]','',['class' => 'col-md-7 form-control input-md','placeholder' => '']) !!}
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
                    @if($appInfo->rjsc_from_submit_status == 0)
                    <div class="col-md-4 col-md-offset-4">
                        <button class="btn btn-info btn-xs col-md-4" id="add_column" type="button">Add Row</button>
                        <button class="btn btn-danger btn-xs col-md-4" id="remove_column" type="button" style="margin-left:3px ">Remove Row</button>
                    </div>
                    @endif
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
                                    {!! Form::number('no_qualific_share',$appInfo->no_of_qualification_share,['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required']) !!}

                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-10 {{$errors->has('') ? 'has-error': ''}}">

                                {!! Form::label('','2. Value of each Share (BDT) :',['class'=>'col-md-5 text-left required-star']) !!}
                                <div class="col-md-6">

                                    {!! Form::number('value_of_each_share',$appInfo->value_of_each_share,['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required','readonly']) !!}

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
                                    {!! Form::text('agreement_witness_name',$appInfo->agreement_witness_name,['class' => 'col-md-5 form-control input-md required','placeholder' => '','required' => 'required','maxlength'=>'50']) !!}
                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}

                                </div>
                                <div class="col-md-5"></div>
                            </div>
                            <br>
                            <br>
                            <div class="col-md-8 col-md-offset-2 {{$errors->has('') ? 'has-error': ''}}">

                                {!! Form::label('','b. Address of Witness :',['class'=>'col-md-5 text-left required_star']) !!}
                                <div class="col-md-6">

                                    {!! Form::textarea('agreement_witness_address',$appInfo->agreement_witness_address,['class' => 'col-md-5 form-control input-md required','placeholder' => '', 'rows' => 2, 'cols' => 1,'required' => 'required','maxlength'=>'100']) !!}

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
                                                    {!! Form::select('agreement_district_id',[],$appInfo->agreement_witness_district_id,['class' => 'form-control input-md required ','placeholder' => 'Select One','required' => 'required','id'=>'agreement_witness_district_id']) !!}
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
        @if(ACL::getAccsessRight('NewReg','-E-') && $appInfo->rjsc_from_submit_status == 0)
            <div class="">
                <div class="col-md-6">
                    <button class="btn btn-info" name="actionBtn" id="draft" value="draft" type="submit">Save as Draft</button>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" name="actionBtn" id="save" value="save" type="submit">Save and Continue</button>
                </div>
            </div>
        @endif
    </div>

    {!! Form::close() !!}

</div>

<script>
    $(document).ready(function () {

        var addRowNum=0;
        $(document).on('click','#add_column',function () {
            addRowNum = addRowNum + 1;
            var rowCount = $('#particular tr').length;
            $('#particular_body').append('<tr>\n' +
                '                            <td>\n' +
                '                                <input type="checkbox"> &nbsp;<span class="sNo"> '+ rowCount +'</span>\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md" placeholder="" name="name_corporation_body['+rowCount+']" type="text" value="">\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md" placeholder="" name="represented_by['+rowCount+']" type="text" value="">\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <textarea class="col-md-7 form-control input-md" placeholder="" rows="2" cols="1" name="address['+rowCount+']"></textarea>\n' +
                '                                <div class="row">\n' +
                '                                    <div class="col-md-3">\n' +
                '                                        <label for="" class="col-md-4 text-left">District</label>\n' +
                '                                    </div>\n' +
                '                                    <div class="col-md-9">\n' +
                '                                        {!! Form::select("district_id[]",[],'',['class' => 'form-control input-md particular_district_id','placeholder' => '']) !!} \n' +
                '                                        \n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '                            </td>\n' +
                '                            <td>\n' +
                '                                <input class="col-md-7 form-control input-md" placeholder="" name="no_subscribed_shares['+rowCount+']" type="number" value="">\n' +
                '                            </td>\n' +
                '                        </tr>');

            var numItems = $('.particular_district_id').length;
            $(".particular_district_id").each(function(e){
                if((numItems-1) === parseInt(e)){
                    $(this).attr('id', 'addRow_'+addRowNum);
                }
            });

            // Customized api call using ID for the add row section
            $('#addRow_'+addRowNum).on('keydown', function(el){
                var key = el.which;
                if (typeof key !== "undefined") {
                    return false;
                }

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);

                var api_url = "{{$rjscBaseApi}}/info/district";
                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback

                var element_id = "id"; //dynamic id for callback
                var element_name = "name"; //dynamic name for callback
                var data = '';
                var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
                var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

                apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

            });

            // Calling the API using ID
            $('#addRow_'+addRowNum).keydown()

        });

        $(document).on('click','#remove_column',function () {
            if(jQuery('input:checkbox:checked').length>0){
                var rowCount = $('#particular tr').length;
                if(rowCount > 2) {
                    jQuery('input:checkbox:checked').parents("tr").remove();
                    arrangeSno();
                }

            }else{
                alert('Please Select A Checkbox!!');
            }


            function arrangeSno(){
                var i=0;
                $('#particular tr').each(function() {
                    $(this).find(".sNo").html(i);
                    i++;
                });

            }
        });

    });

</script>


<script>

    $(document).ready(function () {
        $(function(){
            $('.particular_district_id').keydown();
            $('#agreement_witness_district_id').keydown();
        });

        $('.particular_district_id').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/district";
            var selected_value = $(this).data('id'); // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });


        $('#agreement_witness_district_id').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/district";
            var selected_value = '{{ $appInfo->agreement_witness_district_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });
        $(document).on('click','#draft',function () {
            $('#particular_form').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $("#particular_form").validate();
        });
    });
</script>