<div class="panel panel-info">
    <div class="panel-heading"><strong>Necessary documents to be attached here (Only PDF file to be attach
            here)</strong>
    </div>
    <div class="panel-body">

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('connectionType','Connection Type :', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('sex') ? 'has-error': ''}}">
                        {!! Form::select('connectionType',  ['P'=>'Permanent', 'T'=>'Temporary'], isset($appData->connectionType) ? $appData->connectionType : '', ['class' => 'form-control','id'=>'connectionType', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('connectionType','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('phase','Phase :', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('sex') ? 'has-error': ''}}">
                        {!! Form::select('phase',  [], '', ['class' => 'form-control','id'=>'phase', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('phase','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('category','Select Category :', ['class'=>'col-md-5 required-star']) !!}
                    <div class="col-md-7 {{$errors->has('sex') ? 'has-error': ''}}">
                        {!! Form::select('category',  [], '', ['class' => 'form-control','id'=>'category', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('category','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div id="showDocumentDiv">

        </div>

    </div>
</div>

<script src="{{ asset("assets/scripts/apicall.js") }}" type="text/javascript"></script>
<script>


    $(document).ready(function () {
        $(function () {
            token = "{{$token}}";
            tokenUrl = '/new-connection-bpdb/get-refresh-token';

            $('#phase').keydown();
            $('#category').keydown();
        });


        $('#phase').on('keydown', function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/phase-type";
            var selected_value = '{{isset($appData->phase) ? $appData->phase : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "phase"; //dynamic id for callback
            var element_name = "phase"; //dynamic name for callback
            var data = "";
            var errorLog = {logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackPhaseResponse, arrays);

        });

        $('#category').on('keydown', function () {
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$bpdb_service_url}}/category";
            var selected_value = '{{isset($appData->category) ? $appData->category : ''}}'; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "category"; //dynamic id for callback
            var element_name = "category"; //dynamic name for callback
            var data = "";
            var errorLog = {logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackcategoryResponse, arrays);

        });


        $("#category").on("change", function () {
            var phaseCheck = $("#phase").val().split("@")[0];
            var phaseType = phaseCheck.split("@")[0];
            var connectionType = $("#connectionType").val();
            var _token = $('input[name="_token"]').val();
            var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
            if (connectionType != '' && connectionType == 'P' && phaseType == '3') {
                $(this).after('<span class="loading_data">Loading...</span>');
                var category = $('#category').val();
                var categoryId = category.split("@")[0];
                if (categoryId) {
                    $.ajax({
                        type: "POST",
                        url: '/new-connection-bpdb/get-dynamic-doc',
                        dataType: "json",
                        data: {
                            _token: _token,
                            connectionType: connectionType,
                            phaseType: phaseType,
                            categoryId: categoryId,
                            appId: appId
                        },
                        success: function (result) {
                            console.log(result.responseCode);
                            $("#showDocumentDiv").html(result.data);

                            $("#category").next().hide();
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            //console.log(errorThrown);
                            // alert('Unknown error occured. Please, try again after reload');
                            $("#category").next().hide();
                            $("#showDocumentDiv").html('');
                        },
                    });
                } else {
                    $("#showDocumentDiv").html('');
                    $("#category").next().hide();
                }
            } else {
                $("#showDocumentDiv").html('');
                $("#category").next().hide();
            }
        });
        $("#category").trigger('change');


        $("#phase").on("change", function (e) {
            var phase = e.value;
            if (phase != '1') {
                $("#showDocumentDiv").html('');
                $("#category").val('');
            }
        });

        $("#connectionType").on("change", function (e) {
            $("#showDocumentDiv").html('');
            $("#category").val('');
        });


    });


    function callbackcategoryResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row['USAGE_CATEGORY_CODE'] + '@' + row['CODE_DESCR'];
                var value = row['USAGE_CATEGORY_CODE'] + ': ' + row['CODE_DESCR'];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $('#' + calling_id).trigger('change');
    }

    function callbackPhaseResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row['PHASE_TYPE_CODE'] + '@' + row['PHASE_TYPE_DESC'];
                var value = row['PHASE_TYPE_DESC'];
                if (selected_value == id) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $('#' + calling_id).trigger('change');
    }


</script>


