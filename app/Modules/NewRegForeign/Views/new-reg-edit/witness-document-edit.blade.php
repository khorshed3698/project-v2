
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
        {!! Form::open(array('url' => '/new-reg-foreign/rjsc-witness-save/', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'witness_form_edit')) !!}

        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">
        

        <div class="panel panel-info">
            <div class="panel-heading"><strong>C. Forms/Document Presented for Filing By </strong></div>
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
                                {!! Form::select('position_id', [],isset($witnessDataFiled->position_id)?$witnessDataFiled->position_id: '', ['class' => 'form-control input-md required', 'placeholder' => 'Select One','id'=>'position_id']) !!}
                                {!! $errors->first('position_id','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('organization') ? 'has-error': ''}}">
                            {!! Form::label('','3. Organization',['class'=>'col-md-4 text-left ']) !!}
                            <div class="col-md-4">
                                {!! Form::text('organization', (!empty($witnessDataFiled->organization)) ? $witnessDataFiled->organization : '' , ['class' => 'form-control  input-md','placeholder' => 'Organization','maxlength'=>'100']) !!}
                                {!! $errors->first('organization','<span class="help-block">:message</span>') !!}
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 {{$errors->has('address_document_by') ? 'has-error': ''}}">
                            {!! Form::label('','4. Address',['class'=>'col-md-4 text-left required-star']) !!}
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
                        <div class="col-md-12 {{$errors->has('district') ? 'has-error': ''}}">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">

                                <div class="row">
                                    <div class="col-md-4">
                                        {!! Form::label('','District',['class'=>'col-md-8 required-star text-left']) !!}
                                    </div>
                                    <div class="col-md-8">
                                        {!! Form::select('district', [],isset($witnessDataFiled->district_id)?$witnessDataFiled->district_id: '',['class' => 'form-control input-md required','placeholder' => 'Select One', 'id'=>'district']) !!}
                                        {!! $errors->first('district','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        @if(ACL::getAccsessRight('NewReg','-E-'))
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
            $('#district').click();
            $('#position_id').click();

        });

        // Get District office List
        $("#district").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var distId = '{{isset($witnessDataFiled->district_id) ? $witnessDataFiled->district_id: ''}}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-district-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(distId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#district").trigger('change');
                }
            });
        });

        // Get Registration Offices List
                $("#position_id").one("click", function () {
                        $(this).after('<span class="loading_data">Loading...</span>');
                        var self = $(this);
                        var posId = '{{isset($witnessDataFiled->position_id)?$witnessDataFiled->position_id : ''}}';
                        var entityTypeId = '{{isset($appInfo->entity_type_id) ? $appInfo->entity_type_id : ''}}';
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                                type: "POST",
                                url: "<?php echo url('/new-reg-foreign/get-position-by-entity-type-id'); ?>",
                                data: {
                                    _token: _token,
                                        entityTypeId: entityTypeId
                                },
                            success: function (response) {
                                    var option = '<option value="">Select One</option>';
                                    if (response.responseCode == 1) {
                                            $.each(response.data, function (id, value) {
                                                if(posId == id.split('@')[0]){
                                                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                                                }else{
                                                    option += '<option value="' + id + '">' + value + '</option>';
                                                }
                                                });
                                        }
                                    $(self).html(option);
                                    $(self).next().hide();
                                    $("#position_id").trigger('change');
                                }
                        });
                    });
    </script>
