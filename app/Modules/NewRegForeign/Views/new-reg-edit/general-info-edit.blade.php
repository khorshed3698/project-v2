<div class="panel panel-info">
    <div class="panel-heading"><strong>A. General Information (as of Form - XXXVI,XXXVII,XLII)</strong></div>
    <div class="panel-body">
        {!! Form::open(['url' => '/new-reg-foreign/store','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'generalinfoform']) !!}

        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('registration_office') ? 'has-error': ''}}">
                    {!! Form::label('registration_office','Registration Office',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-3">
                        {!! Form::select('registration_office', [$appInfo->reg_office_id=>$appInfo->reg_office_name], $appInfo->reg_office_id ,['class' => 'form-control input-md required','placeholder' => 'Select One', 'id'=>'registration_office']) !!}
                        {!! $errors->first('registration_office','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('name_of_entity','1. Name of the Entity',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-3">
                        {!! Form::text('name_of_entity', !empty($appInfo->name_of_entity)?$appInfo->name_of_entity:$OfficePermissionNew->local_company_name, ['class' => 'form-control required input-md','placeholder' => 'Entity Name','maxlength'=>'200']) !!}
                        {!! $errors->first('name_of_entity','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('entry_type','2. Entity Type',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-8">
                        <span>{{$entityType}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('entity_sub_type_id') ? 'has-error': ''}}">
                    {!! Form::label('entity_sub_type_id','3. Entity Sub Type',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-3">
                        {!! Form::select('entity_sub_type_id', [$appInfo->entity_sub_type_id=>$appInfo->entity_sub_type_name],$appInfo->entity_sub_type_id ,['class' => 'form-control input-md required','placeholder' => 'Select One', 'id'=>'entity_sub_type_id']) !!}
                        {!! $errors->first('entity_sub_type_id','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('country_origin') ? 'has-error': ''}}">
                    {!! Form::label('country_origin','4. Country of the Origin',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::select('country_origin', [$appInfo->country_origin_id => $appInfo->country_origin_name],$appInfo->country_origin_id ,['class' => 'form-control input-md required','placeholder' => 'Select One', 'id'=>'country_origin']) !!}
                        {!! $errors->first('country_origin','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('address_entity_origin') ? 'has-error': ''}}">
                    {!! Form::label('address_entity_origin','5. Address of the registered or principle office in the country of the origin',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::textarea('address_entity_origin', $appInfo->address_entity_origin, ['class' => 'form-control input-sm required','placeholder' => 'Address of the registered or principle office in the country of the ogigin', 'rows' => 2, 'cols' => 1,'maxlength'=>'200']) !!}
                        {!! $errors->first('address_entity_origin','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('address_entity') ? 'has-error': ''}}">
                    {!! Form::label('address_entity','6. Address of the Principle place in the Bangladesh',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::textarea('address_entity', $appInfo->address_entity, ['class' => 'form-control input-sm required','placeholder' => 'Address of the Principle place in the Bangladesh', 'rows' => 2, 'cols' => 1,'maxlength'=>'200']) !!}
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
                                {!! Form::select('entity_district_id',[$appInfo->entity_district_id => $appInfo->entity_district_name], $appInfo->entity_district_id,['class' => 'form-control input-md required','placeholder' => 'Select One', 'id'=>'entity_district_id']) !!}
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
                <div class="col-md-12 {{$errors->has('main_business_objective') ? 'has-error': ''}}">
                    {!! Form::label('main_business_objective','7. Main Business objective',['class'=>'col-md-4 text-left required-star']) !!}
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
                    {!! Form::label('','8. Business Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::select('business_sector_id',[$appInfo->business_sector_id => $appInfo->business_sector_name], $appInfo->business_sector_id,['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'business_sector_id']) !!}
                        {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                    {!! Form::label('','9. Business Sub-Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('business_sub_sector_id',[$appInfo->business_sub_sector_id => $appInfo->business_sub_sector_name], $appInfo->business_sub_sector_id,['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'business_sub_sector_id']) !!}
                        {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('name_constitution_instrument') ? 'has-error': ''}}">
                    {!! Form::label('','10. Name of the Constitution Instrument',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-6">
                        {!! Form::select('name_constitution_instrument',[$appInfo->name_constitution_instrument_id => $appInfo->name_constitution_instrument_name], $appInfo->name_constitution_instrument_id,['class' => 'form-control input-md','id'=>'name_constitution_instrument']) !!}
                        {!! $errors->first('name_constitution_instrument','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('constitution_documents_in_english') ? 'has-error': ''}}">
                    <div class="col-md-1"></div>
                    {!! Form::label('','10.1 Constitution Document in English ?',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-6">
                        <label class="radio-inline">{!! Form::radio('constitution_documents_in_english', '1', $appInfo->constitution_documents_in_english  == '1') !!} Yes</label>
                        <label class="radio-inline">{!! Form::radio('constitution_documents_in_english', '0', $appInfo->constitution_documents_in_english  == '0') !!} No</label>
                        {!! $errors->first('constitution_documents_in_english','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('constitution_documents_in_english_translation') ? 'has-error': ''}}">
                    <div class="col-md-1"></div>
                    {!! Form::label('','10.2 If Not, providing English Translation ?',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-6">
                        <label class="radio-inline">{!! Form::radio('constitution_documents_in_english_translation', '1', $appInfo->constitution_documents_in_english_translation  == '1') !!} Yes</label>
                        <label class="radio-inline">{!! Form::radio('constitution_documents_in_english_translation', '0', $appInfo->constitution_documents_in_english_translation  == '0') !!} No</label>
                        {!! $errors->first('constitution_documents_in_english_translation','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('name_constitution_instrument') ? 'has-error': ''}}">
                    {!! Form::label('','11. BIDA Permission ref',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-6">
                        {!! Form::text('bida_permission_ref',  !empty($appInfo->bida_permission_ref)?$appInfo->bida_permission_ref:$OfficePermissionNew->tracking_no, ['class' => 'form-control required input-md','placeholder' => 'BIDA Permission ref','maxlength'=>'200']) !!}
                        {!! $errors->first('bida_permission_ref','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('bida_permission_date') ? 'has-error': ''}}">
                    {!! Form::label('','BIDA Permission Date ',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('bida_permission_date', !empty($appInfo->bida_permission_date)?date('d-M-Y', strtotime($appInfo->bida_permission_date)):date('d-M-Y', strtotime($OfficePermissionNew->completed_date)), ['class' => 'form-control input-md required datepicker','placeholder' => 'Datepicker']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('business_start_date') ? 'has-error': ''}}">
                    {!! Form::label('','BIDA Permission Effect Date to Start Business',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('business_start_date', !empty($appInfo->business_start_date)?date('d-M-Y', strtotime($appInfo->business_start_date)):date('d-M-Y', strtotime($OfficePermissionNew->approved_duration_start_date)), ['class' => 'form-control input-md required datepicker','placeholder' => 'Datepicker']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col-md-12 {{$errors->has('business_establish_date') ? 'has-error': ''}}">
                    {!! Form::label('','Business establishment date in Bangladesh',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-5">
                        {!! Form::text('business_establish_date', !empty($appInfo->business_establish_date)?date('d-M-Y', strtotime($appInfo->business_establish_date)):date('d-M-Y', strtotime($OfficePermissionNew->operation_target_date)), ['class' => 'form-control input-md required datepicker','placeholder' => 'Datepicker']) !!}
                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                    </div>
                    <div class="col-md-1"></div>
                </div>
            </div>
        </div>

        @if(ACL::getAccsessRight('NewReg','-E-'))
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
    $(function () {

        $('.datepicker').datetimepicker({
            viewMode: 'years',
            format: 'DD-MMM-YYYY'
        });
    });
</script>
<script>
    $(document).ready(function () {
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
                        }
                    }
                }
            );

            function calculateAuthorizedCapital() {
                alert('success');
            }
        });

        $('input[type=radio][name=constitution_documents_in_english]').change(function() {
            constitutionDocumentsTrueOrFalse();
        });
        function constitutionDocumentsTrueOrFalse(){
            if($("input[name=constitution_documents_in_english]:checked").val()==1){
                $('input[type=radio][name=constitution_documents_in_english_translation][value=1]').prop('checked', true);
                $('input[type=radio][name=constitution_documents_in_english_translation]:not(:checked)').attr('disabled', true);
            }else{
                $('input[type=radio][name=constitution_documents_in_english_translation]:not(:checked)').attr('disabled', false);
            }
        }
        constitutionDocumentsTrueOrFalse();





        $(function(){
            $('#registration_office').click();
            $('#entity_sub_type_id').click();
            $('#country_origin').click();
            $('#business_sector_id').click();
            $('#name_constitution_instrument').click();
        });

        // Get Registration Offices List
        $("#registration_office").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var RegOfficeId = '{{ $appInfo->reg_office_id }}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-registration-offices'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(RegOfficeId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#registration_office").trigger('change');
                }
            });
        });

        // Get District List by office id
        $("#registration_office").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');

            var entityDistrictId = '<?php echo $appInfo->entity_district_id; ?>';
            var regOffice = $('#registration_office').val();
            var regOfficeId = regOffice.split("@")[0];
            if(regOffice){
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/new-reg-foreign/get-district-list-by-office-id'); ?>"+'/'+regOfficeId,
                    data: {},
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                if(entityDistrictId == id.split('@')[0]){
                                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                                }else{
                                    option += '<option value="' + id + '">' + value + '</option>';
                                }
                            });
                        }
                        $("#entity_district_id").html(option);
                        $(self).next().hide();
                    }
                });
            }else{
                $("#entity_district_id").html('<option value="">Select Reg Office First</option>');
                $(self).next().hide();
            }

        });

        // Get Entity Sub Type List
        $("#entity_sub_type_id").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var entitySubTypeId = '{{ $appInfo->entity_sub_type_id }}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-entity-sub-type-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(entitySubTypeId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#entity_sub_type_id").trigger('change');
                }
            });
        });

        // Get Country Origin List
        $("#country_origin").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var countryOriginId = '{{ $appInfo->country_origin_id }}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-country-origin-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(countryOriginId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#country_origin").trigger('change');
                }
            });
        });

        // Get Business Sector List
        $("#business_sector_id").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var businessSectorId = '{{ $appInfo->business_sector_id }}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-business-sector-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(businessSectorId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#business_sector_id").trigger('change');
                }
            });
        });

        // Get District List by office id
        $("#business_sector_id").on("change", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');

            var subSectorId = '<?php echo $appInfo->business_sub_sector_id; ?>';
            var sector = $('#business_sector_id').val();
            var sectorId = sector.split("@")[0];
            if(sector){
                $.ajax({
                    type: "GET",
                    url: "<?php echo url('/new-reg-foreign/get-business-sub-sector-by-sector-id'); ?>"+'/'+sectorId,
                    data: {},
                    success: function (response) {
                        var option = '<option value="">Select One</option>';
                        if (response.responseCode == 1) {
                            $.each(response.data, function (id, value) {
                                if(subSectorId == id.split('@')[0]){
                                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                                }else{
                                    option += '<option value="' + id + '">' + value + '</option>';
                                }
                            });
                        }
                        $("#business_sub_sector_id").html(option);
                        $(self).next().hide();
                    }
                });
            }else{
                $("#business_sub_sector_id").html('<option value="">Select Sector First</option>');
                $(self).next().hide();
            }

        });

        // Get Constitution Instrument List
        $("#name_constitution_instrument").one("click", function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var self = $(this);
            var constitutionInstrumentId = '{{ $appInfo->name_constitution_instrument_id }}';
            $.ajax({
                type: "GET",
                url: "<?php echo url('/new-reg-foreign/get-constitution-instrument-list'); ?>",
                data: {},
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        $.each(response.data, function (id, value) {
                            if(constitutionInstrumentId == id.split('@')[0]){
                                option += '<option selected="true" value="' + id + '">' + value + '</option>';
                            }else{
                                option += '<option value="' + id + '">' + value + '</option>';
                            }
                        });
                    }
                    $(self).html(option);
                    $(self).next().hide();
                    $("#name_constitution_instrument").trigger('change');
                }
            });
        });

    });
</script>