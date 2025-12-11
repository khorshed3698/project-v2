<div class="panel panel-primary">
    <div class="panel-heading"><strong>Description of Connection</strong>
    </div>
    <div class="panel-body">

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('connectionType','Connection Type :', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('connectionType') ? 'has-error': ''}}">
                        {!! Form::select('connectionType',  ['P'=>'Permanent', 'T'=>'Temporary'], isset($appData->connectionType) ? $appData->connectionType : '', ['class' => 'form-control','id'=>'connectionType', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('connectionType','<span class="help-block">:message</span>') !!}
                    </div>
                </div>

                <div class="col-md-6">
                    {!! Form::label('phase','Phase :', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('phase') ? 'has-error': ''}}">
                        {!! Form::select('phase',  ['1'=>'Single', '3'=>'Three'],  isset($appData->phase) ? $appData->phase : '', ['class' => 'form-control','id'=>'phase', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('phase','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('category','Tariff Category :', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('category') ? 'has-error': ''}}">
                        {!! Form::select('category',  [], '', ['class' => 'form-control search-box','id'=>'category', 'placeholder'=>'Select One']) !!}
                        {!! $errors->first('category','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>


        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('demand_meter','Demand Meter', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('demand_meter') ? 'has-error': ''}}">
                        {!! Form::text('demand_meter', isset($appData->demand_meter) ? $appData->demand_meter : '',['class' => 'form-control input-sm','id'=>'demand_meter']) !!}
                        {!! $errors->first('demand_meter','<span
                            class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('demand_load','Total Demand Load', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('demand_load') ? 'has-error': ''}}">
                        {!! Form::text('demand_load', isset($appData->demand_load) ? $appData->demand_load : '',['class' => 'form-control input-sm','placeholder'=>'IN KILOWATT']) !!}
                        {!! $errors->first('demand_load','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>

            </div>
        </div>

        <div class="form-group" style="">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('existing_meter','No. of Existing Meter', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('existing_meter') ? 'has-error': ''}}">
                        {!! Form::text('existing_meter', isset($appData->existing_meter) ? $appData->existing_meter : '',['class' => 'form-control input-sm ']) !!}
                        {!! $errors->first('existing_meter','<span
                            class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('existing_load','Total Existing Load', ['class'=>'col-md-5']) !!}
                    <div class="col-md-7 {{$errors->has('existing_load') ? 'has-error': ''}}">
                        {!! Form::text('existing_load', isset($appData->existing_load) ? $appData->existing_load : '',['class' => 'form-control input-sm','placeholder'=>'IN KILOWATT']) !!}
                        {!! $errors->first('existing_load','<span class="help-block">:message</span>')
                        !!}
                    </div>
                </div>

            </div>
        </div>


        <div class="form-group" id="meter_owner"
             style="{{(isset($appData->demand_meter) && $appData->demand_meter >= 2) ? '' : 'display:none;'}}">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('diff_meter_owner','Meter owners are different person?', ['class'=>'col-md-7', 'style'=>'margin-top:6px;']) !!}
                    <div class="col-md-5 {{$errors->has('meter_owner') ? 'has-error': ''}}">
                        <label class="radio-inline">{!! Form::radio('diff_meter_owner',1,
                        isset($appData->diff_meter_owner) && ($appData->diff_meter_owner == 1) ? true : false  ) !!}
                            Yes</label>
                        <label class="radio-inline">{!! Form::radio('diff_meter_owner', -1,
                                isset($appData->diff_meter_owner) && ($appData->diff_meter_owner == -1) ? true : false ) !!}
                            No</label>
                        {!! $errors->first('diff_meter_owner','<span class="help-block">:message</span>')
                        !!}
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
            $('#category').keydown();
            //$("#meter_owner").hide();
        });


        $('#demand_meter').on('input', function () {
            var val = $('#demand_meter').val();
            if (val > 1) {
                $("#meter_owner").show();
            } else if (val <= 1) {
                $("#meter_owner").hide();
            }
        });

        $('#category').on('keydown', function () {
            // $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);
            var api_url = "{{$dpdc_service_url}}/tariff";
            var selected_value = '{{isset($appData->category) ? $appData->category : ''}}'; // for callback
            //alert(selected_value);
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "TARIFF"; //dynamic id for callback
            var element_name = "NAME"; //dynamic name for callback
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
            var _token = $('input[name="_token"]').val();
            var appId = '{{isset($appInfo->id) ? $appInfo->id : ''}}';
            // $(this).after('<span class="loading_data">Loading...</span>');
            var category = $('#category').val();
            var categoryId = category.split("@")[0];
            if (categoryId) {
                $.ajax({
                    type: "POST",
                    url: '/new-connection-dpdc/get-dynamic-doc',
                    dataType: "json",
                    data: {
                        _token: _token,
                        categoryId: categoryId,
                        appId: appId
                    },
                    success: function (result) {
                        $("#showDocumentDiv").html(result.data);
                        $("#category").next().hide();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $("#category").next().hide();
                        $("#showDocumentDiv").html('');
                    },
                });
            } else {
                $("#showDocumentDiv").html('');
                $("#category").next().hide();
            }
        });
        $("#category").trigger('change');

        $("#connectionType").on("change", function (e) {
            $("#showDocumentDiv").html('');
            $("#category").val('');
        });

    })
    ;


    function callbackcategoryResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                //console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
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
        $('.search-box').select2();
    }

    function callBackDocument(response, [calling_id, selected_value, element_id, element_name]) {

    }

    function uploadDocument(targets, id, vField, isRequired) {
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/new-connection-dpdc/upload-document')}}";
            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            var form_data = new FormData();
            form_data.append('selected_file', id);
            form_data.append('isRequired', isRequired);
            form_data.append('validateFieldName', vField);
            form_data.append('_token', "{{ csrf_token() }}");
            form_data.append(id, file_data);
            $.ajax({
                target: '#' + targets,
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }


</script>


