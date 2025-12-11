
    <fieldset>

        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {!!Session::get('success') !!}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {!! Session::get('error') !!}
                </div>
            @endif
        </div>
        {!! Form::open(array('url' => '/company-registration-sf/rjsc-witness-save/', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'witness_form_edit')) !!}

        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

        <div class="panel panel-info">
            <div class="panel-heading"><strong>3. Witness </strong></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="text-center"><strong>Witness 1</strong></h4>
                        <div class="form-group row">
                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::text('name[1]', (!empty($witnessData[0]['name'])) ?  $witnessData[0]['name']  : '', ['class' => 'form-control input-md required','maxlength'=>'200']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::textarea('address[1]', (!empty($witnessData[0]['address'])) ?  $witnessData[0]['address']  : '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1,'maxlength'=>'200']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::number('phone[1]', (!empty($witnessData[0]['phone'])) ?  $witnessData[0]['phone']  : '', ['class' => 'form-control input-md required','maxlength'=>'50']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::number('national_id[1]', (!empty($witnessData[0]['national_id'])) ?  $witnessData[0]['national_id']  : '', ['class' => 'form-control input-md required','maxlength'=>'50']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4 class="text-center"><strong>Witness 2</strong></h4>
                        <div class="form-group row">
                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::text('name[2]',(!empty($witnessData[1]['name'])) ?  $witnessData[1]['name']  : '', ['class' => 'form-control input-md required' ,'required' => 'required','maxlength'=>'200']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                        <div class="form-group row">
                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::textarea('address[2]', (!empty($witnessData[1]['address'])) ?  $witnessData[1]['address']  : '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1,'required' => 'required','maxlength'=>'200']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::number('phone[2]', (!empty($witnessData[1]['phone'])) ?  $witnessData[1]['phone']  : '', ['class' => 'form-control input-md required','required' => 'required','maxlength'=>'50']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group row">
                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-8">
                                {!! Form::number('national_id[2]', (!empty($witnessData[1]['national_id'])) ?  $witnessData[1]['national_id']  : '', ['class' => 'form-control input-md required','required' => 'required','maxlength'=>'50']) !!}
                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading"><strong>3. Document Presented for Filing By </strong></div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('name_document_by') ? 'has-error': ''}}">
                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-4">
                                {!! Form::text('name_document_by', (!empty($witnessDataFiled->name)) ? $witnessDataFiled->name : '' , ['class' => 'form-control required input-md','placeholder' => 'Name','maxlength'=>'100']) !!}
                                {!! $errors->first('name_document_by','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('position_id') ? 'has-error': ''}}">
                            {!! Form::label('position_id','2. Position',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-4">
                                {!! Form::select('position_id',[], (!empty($witnessDataFiled->position_id)) ? $witnessDataFiled->position_id : '',['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                {!! $errors->first('position_id','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('address_document_by') ? 'has-error': ''}}">
                            {!! Form::label('','3. Address',['class'=>'col-md-4 text-left required-star']) !!}
                            <div class="col-md-4">
                                {!! Form::textarea('address_document_by', (!empty($witnessDataFiled->address)) ? $witnessDataFiled->address : '' , ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1,'maxlength'=>'100']) !!}
                                {!! $errors->first('address_document_by','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('district_id') ? 'has-error': ''}}">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">

                                <div class="row">
                                    <div class="col-md-4">
                                        {!! Form::label('','District',['class'=>'col-md-8 required-star text-left']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::select('district_id',[],(!empty($witnessDataFiled->district_id) ? $witnessDataFiled->district_id : ''), ['class' => 'form-control input-md required','required' => 'required','id'=>'district_id']) !!}
                                        {!! $errors->first('district_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @if(ACL::getAccsessRight('NewReg','-E-') && $appInfo->rjsc_from_submit_status == 0)
            <div class="">
                <div class="col-md-6">
                    <button class="btn btn-info" name="actionBtn" value="draft" id="draft" type="submit">Save as Draft</button>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" name="actionBtn" value="save" id="save" type="submit">Save and Continue</button>
                </div>
            </div>
        @endif

        {!! Form::close() !!}
    </fieldset>




    <script>

        $(document).ready(function () {
            $(document).on('click','#draft',function () {
                $('#witness_form_edit').validate().cancelSubmit = true;;
            });
            $(document).on('click','#save',function () {
                $("#witness_form_edit").validate();
            });
        });

        $(function(){
            token = "{{$token}}";
            //tokenUrl = '/company-registration/get-refresh-token';
            tokenUrl = '/company-registration-sf/get-refresh-token';

            $('#position_id').keydown();
            $('#district_id').keydown();
        });
        $('#position_id').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var companyType = '{{$appInfo->entity_type_id}}';

            var e = $(this);
            var api_url = "{{$rjscBaseApi}}/position/"+companyType;
            var selected_value = '{{isset($witnessDataFiled->position_id) ? $witnessDataFiled->position_id : ""}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "positionId"; //dynamic id for callback
            var element_name = "positionTitle"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            apiCallGet(e, options, apiHeaders, callbackResponseWithSelectOne, arrays);

        });
        $('#district_id').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/district";
            var selected_value = '{{isset($witnessDataFiled->district_id) ? $witnessDataFiled->district_id : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });
    </script>
