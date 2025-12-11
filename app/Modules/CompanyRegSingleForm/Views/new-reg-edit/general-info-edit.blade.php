<div class="panel panel-info">
    <div class="panel-heading"><strong>2. General Information</strong></div>
    <div class="panel-body">
        {!! Form::open(['url' => '/company-registration-sf/store','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'generalinfoform']) !!}

        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('entry_name','1. Name of the Entity',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-3">
                        {!! Form::text('name_of_entity', $appInfo->verified_company_name, ['class' => 'form-control required input-md','placeholder' => 'Business Objective','maxlength'=>'200','readonly'=>'true']) !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('rjsc_office_name','2. Rjsc Office',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-3">
                        {!! Form::select('rjsc_office_name',[], $appInfo->rjsc_office_name,['class' => 'form-control input-md required','placeholder' => 'Select One','id' => 'rjsc_office_name']) !!}
                        {!! $errors->first('rjsc_office_name','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('entity_type','2. Entity Type',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-3">
                        {!! Form::select('entity_type',[], $appInfo->entity_type,['class' => 'form-control input-md required','placeholder' => 'Select One','id' => 'entity_type']) !!}
                        {!! $errors->first('entity_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('liability_type_id') ? 'has-error': ''}}">
                    {!! Form::label('liability_type_id','3. Liability Type',['class'=>'col-md-4 text-left required-star']) !!}

                    @if($appInfo->liability_type_id !="" &&  $appInfo->liability_type_id !=0)

                        <div class="col-md-3">
                            {!! Form::select('liability_type_id',[], $appInfo->liability_type_id,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                            {!! $errors->first('liability_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    @else
                        <div class="col-md-3">
                            {!! Form::select('liability_type_id',[],1,['class' => 'form-control input-md required']) !!}
                            {!! $errors->first('liability_type_id','<span class="help-block">:message</span>') !!}
                        </div>
                    @endif

                    <div class="col-md-5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('address_entity') ? 'has-error': ''}}">
                    {!! Form::label('address_entity','4. Address of the Entity',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::textarea('address_entity', $appInfo->address_entity, ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1,'maxlength'=>'200']) !!}
                        {!! $errors->first('address_entity','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('entity_district_id') ? 'has-error': ''}}">

                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">

                                {!! Form::label('entity_district_id','District',['class'=>'col-md-8 text-left required-star required']) !!}
                            </div>
                            <div class="col-md-7">
                                {!! Form::select('entity_district_id',[], $appInfo->entity_district_id,['class' => 'form-control input-md required',]) !!}
                                {!! $errors->first('entity_district_id','<span class="help-block">:message</span>') !!}

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('entity_email_address') ? 'has-error': ''}}">
                    {!! Form::label('entity_email_address','4.1 . Entity Email Address',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::email('entity_email_address', $appInfo->entity_email_address, ['class' => 'form-control input-md required','placeholder' => 'Entity Email','maxlength'=>'200']) !!}
                        {!! $errors->first('entity_email_address','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('main_business_objective') ? 'has-error': ''}}">
                    {!! Form::label('main_business_objective','5. Main Business objective',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::text('main_business_objective', $appInfo->main_business_objective, ['class' => 'form-control required input-md','placeholder' => 'Business Objective','maxlength'=>'200']) !!}
                        {!! $errors->first('main_business_objective','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                    {!! Form::label('','6. Business Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::select('business_sector_id',[], $appInfo->business_sector_id,['class' => 'form-control input-md required','id'=>'business-sector']) !!}
                        {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                    {!! Form::label('','7. Business Sub-Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('business_sub_sector_id',[], $appInfo->business_sub_sector_id,['class' => 'form-control input-md required','id'=>'business_sub_sector_id']) !!}
                        {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('authorize_capital') ? 'has-error': ''}}">
                    {!! Form::label('','8. Authorized Capital (BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">

                        {!! Form::number('authorize_capital', $appInfo->authorize_capital, ['class' => 'form-control required input-md','placeholder' => 'BDT','id'=>'authorized_capital','readonly']) !!}

                        {!! $errors->first('authorize_capital','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>{Authorized Capital =<br/> {Shares No.} X {Value of each Share}}</span>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('number_shares') ? 'has-error': ''}}">
                    {!! Form::label('number_shares','9. Number of shares',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('number_shares',$appInfo->number_shares, ['class' => 'form-control required input-md','placeholder' => 'Number of Share','onchange'=>"calculateAuthorizedCapital()" ]) !!}
                        {!! $errors->first('number_shares','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('value_of_each_share') ? 'has-error': ''}}">
                    {!! Form::label('value_of_each_share','10. Value of each share(BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('value_of_each_share', $appInfo->value_of_each_share, ['class' => 'form-control required input-md','placeholder' => 'Value of each share(BDT)']) !!}
                        {!! $errors->first('value_of_each_share','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('minimum_no_of_directors') ? 'has-error': ''}}">
                    {!! Form::label('','11. Minimum No of Directors',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('minimum_no_of_directors',$appInfo->minimum_no_of_directors, ['class' => 'form-control required input-md','id'=>'minimum_no_of_directors','placeholder' => '']) !!}
                        {!! $errors->first('minimum_no_of_directors','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>(Minimum Two{2})</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('maximum_no_of_directors') ? 'has-error': ''}}">
                    {!! Form::label('','12. Maximum No of Directors  ',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('maximum_no_of_directors',$appInfo->maximum_no_of_directors, ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('maximum_no_of_directors','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>(Maximum fifty{50})</span>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('quorum_agm_egm_num') ? 'has-error': ''}}">
                    {!! Form::label('quorum_agm_egm_num','13. Quorum of AGM/EGM',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('quorum_agm_egm_num',$appInfo->quorum_agm_egm_num, ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('quorum_agm_egm_num','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">
                                <span>(Minimum  tow{2})</span>
                            </div>
                            <div class="col-md-4">

                                {!! Form::text('quorum_agm_egm_word', $appInfo->quorum_agm_egm_word, ['class' => 'form-control required input-md','placeholder' => 'Three']) !!}

                            </div>
                            <div class="col-md-3">
                                <span>In word</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('q_directors_meeting_num') ? 'has-error': ''}}">
                    {!! Form::label('','14. Quorum of Board of Directors Meeting',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('q_directors_meeting_num', $appInfo->q_directors_meeting_num, ['class' => 'form-control required input-md','placeholder' => '','id'=>'q_directors_meeting_num']) !!}
                        {!! $errors->first('q_directors_meeting_num','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">
                                <span>(Minimum tow{2})</span>
                            </div>
                            <div class="col-md-4">

                                {!! Form::text('q_directors_meeting_word', $appInfo->q_directors_meeting_word, ['class' => 'form-control required input-md','placeholder' => 'Three']) !!}

                            </div>
                            <div class="col-md-3">
                                <span>In word</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('duration_of_chairmanship') ? 'has-error': ''}}">
                    {!! Form::label('duration_of_chairmanship','15. Duration for Chairmanship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('duration_of_chairmanship',$appInfo->duration_of_chairmanship, ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('duration_of_chairmanship','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('duration_managing_directorship') ? 'has-error': ''}}">
                    {!! Form::label('','16. Duration for Managing Directorship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('duration_managing_directorship',$appInfo->duration_managing_directorship, ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('duration_managing_directorship','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        @if(ACL::getAccsessRight('NewReg','-E-') && $appInfo->rjsc_from_submit_status == 0)
            <div class="">
                <div class="col-md-6">
                    <button class="btn btn-info" name="actionBtn" value="draft" id="draft" type="submit">Save as Draft</button>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" name="actionBtn" value="submit" id="save" type="submit">Save and continue</button>
                </div>
            </div>
        @endif
        {!! Form::close() !!}
    </div>
</div>

<script>
    $(document).ready(function () {
        $(function(){
            token = "{{$token}}";
            //tokenUrl = '/company-registration/get-refresh-token';
            tokenUrl = '/company-registration-sf/get-refresh-token';

            $('#liability_type_id').keydown();
            $('#business-sector').keydown();
            $('#entity_district_id').keydown();
            $('#entity_type').keydown();
            $('#rjsc_office_name').keydown();

        });
        $(document).on('keyup change','#number_shares',function () {
            var noofshare=$(this).val();
            var valueofeachshare=$('#value_of_each_share').val();
            var capital=noofshare*valueofeachshare;
            $('#authorized_capital').val(capital);
        });
        $(document).on('keyup change','#value_of_each_share',function () {
            var noofshare=$('#number_shares').val();
            var valueofeachshare=$(this).val();
            var capital=noofshare*valueofeachshare;
            $('#authorized_capital').val(capital);

        });

        $('#liability_type_id').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/liability-type";
            var selected_value = '{{ $appInfo->liability_type_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "title"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });
        $("#rjsc_office_name").on("change", function () {

            var self = $(this);
            $(self).next().hide();
            var entity_name = $('#rjsc_office_name').val();
            var enitity_id = entity_name.split("@")[0];
            if(enitity_id == ''){
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/district/office/"+enitity_id;
            var selected_value = '{{ $appInfo->entity_district_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var dependent_section_id = "entity_district_id"; // for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name,dependent_section_id]; // for callback



            apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

        });
        $('#entity_type').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/entity-type";
            var selected_value = '{{ $appInfo->entity_type_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, callbackResponseWithSelectOne, arrays);

        });

        $('#business-sector').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/sector";
            var selected_value = '{{ $appInfo->business_sector_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "sector_name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        $('#rjsc_office_name').on('keydown', function(el){
            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }

            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjscBaseApi}}/info/office";
            var selected_value = '{{ $appInfo->rjsc_office_id }}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "officeId"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            var options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback



            apiCallGet(e, options, apiHeaders, callbackResponseWithSelectOne, arrays);

        });
        $("#business-sector").on("change", function () {
            let self = $(this);
            $(self).next().hide();
            let business_sector_name = $('#business-sector').val();
            let business_sector_id = business_sector_name.split("@")[0];
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);

            let api_url = "{{$rjscBaseApi}}/sub-sector/"+business_sector_id;
            let selected_value = '{{ $appInfo->business_sub_sector_id }}'; // for callback
            let calling_id = $(this).attr('id'); // for callback
            let element_id = "sub_sector_id"; //dynamic id for callback
            let element_name = "sub_sector_name"; //dynamic name for callback
            let dependent_section_id = "business_sub_sector_id"; // for callback
            let data = '';
            let errorLog={logUrl: '{{$logUrl}}', method: 'get'};
            let options ={apiUrl: api_url, token: token, data: data,tokenUrl:tokenUrl, errorLog:errorLog}; // for lib
            let arrays = [calling_id, selected_value, element_id, element_name,dependent_section_id]; // for callback



            apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

        });

        $(document).on('click','#draft',function () {
            $('#generalinfoform').validate().cancelSubmit = true;
        });
        $(document).on('click','#save',function () {
            var val_min_no_of_dir = $('#minimum_no_of_directors').val();

            jQuery.validator.addMethod("accept", function(value, element, param) {
                return value.match(new RegExp("." + param + "$"));
            });

            $('#generalinfoform').validate(
                {
                    rules: {
                        liability_type_id: {
                            required:true
                        },
                        address_entity: {
                            required:true
                        },
                        entity_district_id: {
                            required:true
                        },
                        minimum_no_of_directors: {
                            min:2,
                            max:50
                        },
                        maximum_no_of_directors: {
                            min:2,
                            max:50
                        },
                        quorum_agm_egm_num: {
                            min:val_min_no_of_dir
                        },
                        q_directors_meeting_num: {
                            min:val_min_no_of_dir
                        },
                        quorum_agm_egm_word:{
                            accept: "[a-zA-Z]+"
                        },
                        q_directors_meeting_word:{
                            accept: "[a-zA-Z]+"
                        }
                    }
                }
            );

            function calculateAuthorizedCapital() {
                alert('success');
            }
        });


    });
</script>