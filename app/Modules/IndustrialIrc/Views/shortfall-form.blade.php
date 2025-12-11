<?php
$accessMode = ACL::getAccsessRight('industrialIRC');
?>
<style>
    .image-upload figure figcaption {
        position: absolute;
        bottom: 0;
        color: #fff;
        width: 100%;
        padding-left: 9px;
        padding-bottom: 5px;
        text-shadow: 0 0 10px #000;
    }

    .img-thumbnail {
        height: 100px;
        width: 100px;
    }
</style>
@extends('layouts.admin')
@section('content')
    @include('message.message')
    <section class="content">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading clearfix">
                            <strong>CCI&E Shortfall Form: </strong>
                            <div class="pull-right">
                                <a class="btn btn-sm btn-info"
                                   href="/process/licence-applications/ccie/view/{{\App\Libraries\Encryption::encodeId($data->ref_id)}}/{{\App\Libraries\Encryption::encodeId($process_type_id)}}"
                                   role="button" aria-expanded="false" aria-controls="collapseExample">
                                    View Application
                                </a>
                            </div>
                        </div>
                        {!! Form::open(array('url' => '/licence-applications/ccie/shortfall-form/store','enctype'=>'multipart/form-data','method' => 'post','id' => 'ccieshortfall','role'=>'form')) !!}
                        <div class="panel-body">
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>

                            <input type="hidden" name="app_id"
                                   value="{{ \App\Libraries\Encryption::encodeId($data->ref_id) }}"
                                   id="app_id"/>

                            <?php
                            function searchForId($id,$alldocuments) {

                                foreach ($alldocuments as $document) {
                                    if ($document->document_id == $id) {
                                        return $document->document_name_en;
                                    }
                                }
                                return null;
                            }
                            ?>

                            @if($shortfallData->data->resubmit_data != "")

                                @foreach($shortfallData->data->resubmit_data as $key=>$value)
                                    @if ($key != 'doc_info')
                                        <div class="panel panel-primary">
                                            <div class="panel-heading clearfix">
                                                <strong>{{ucfirst(str_replace('_',' ',$key))}} </strong>
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-6">

                                                            @foreach($value as $keys=>$data)
                                                                <div class="col-md-12">
                                                                    {!! Form::label($data->name,ucfirst(str_replace('_',' ',$data->name)),['class'=>'text-left col-md-5']) !!}
                                                                    <div class="col-md-7">
                                                                        @if(in_array($data->name, $field_map_array))
                                                                            <?php
                                                                            $apiUrl = array_search($data->name, $field_map_array);

                                                                            $exp = explode('/', $apiUrl);
                                                                            $exp = end($exp);
                                                                            $old_division_id = explode('@', $app_data->division)[0];
                                                                            $old_district_id = explode('@', $app_data->district)[0];
                                                                            $old_bank_id = explode('@', $app_data->bank_name)[0];

                                                                            if ($exp == 'district') {
                                                                                $apiUrl = $apiUrl . '/' . $old_division_id;
                                                                            }

                                                                            if ($exp == 'thana') {
                                                                                //
                                                                                $apiUrl = $apiUrl . '/' . $old_district_id;
                                                                            }

                                                                            if ($exp == 'bank-branch') {
                                                                                $apiUrl = $apiUrl . '/' . $old_bank_id;
                                                                            }

                                                                            $api_response = \App\Libraries\CommonFunction::getAreaForShortfall($apiUrl, $token);
                                                                            //                                                                        dump($apiUrl,   $api_response->data);
                                                                            ?>
                                                                            <select name="{{$data->name.'@'.$data->fieldset.'@'.$data->pid}}"
                                                                                    class="form-control required"
                                                                                    id="{{$data->name}}">
                                                                                <?php
                                                                                if (!empty($api_response->data)) {
                                                                                    $selectedValue = $data->value;
                                                                                    foreach ($api_response->data as $resp_data) {

                                                                                        $selected = '';
                                                                                        if ($exp == 'division') {
                                                                                            if ($data->value == $resp_data->division_id) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->division_name;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->division_id . '">' . $resp_data->division_name . '</option>';
                                                                                        }
                                                                                        if ($exp == 'district') {
                                                                                            if ($data->value == $resp_data->district_id) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->district_name;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->district_id . '">' . $resp_data->district_name . '</option>';
                                                                                        }
                                                                                        if ($exp == 'thana') {
                                                                                            if ($data->value == $resp_data->police_station_id) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->police_station_name_en;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->police_station_id . '">' . $resp_data->police_station_name_en . '</option>';
                                                                                        }
                                                                                        if ($exp == 'bank') {
                                                                                            if ($data->value == $resp_data->bank_id) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->bank_name_en;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->bank_id . '">' . $resp_data->bank_name_en . '</option>';
                                                                                        }
                                                                                        if ($exp == 'bank-branch') {
                                                                                            if ($data->value == $resp_data->branch_id) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->branch_name_en;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->branch_id . '">' . $resp_data->branch_name_en . '</option>';
                                                                                        }
                                                                                        if ($exp == 'share-type') {
                                                                                            if ($data->value == $resp_data->code) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->type;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->code . '">' . $resp_data->type . '</option>';
                                                                                        }
                                                                                        if ($exp == 'association') {
                                                                                            if ($data->value == $resp_data->code) {
                                                                                                $selected = 'selected';
                                                                                                $selectedValue = $resp_data->association_name;
                                                                                            }
                                                                                            echo '<option ' . $selected . ' value="' . $resp_data->code . '">' . $resp_data->association_name . '</option>';
                                                                                        }
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                            <span>Previous Value : {{$selectedValue}}
                                                                                @if($data->fieldset == 'ow_info')
                                                                                    <br>
                                                                                    For: {{ $data->ow_name }}
                                                                                @endif</span>
                                                                            <br><br>

                                                                        @elseif(array_key_exists($data->name, $dp_field_map_array)) {{--For datepicker field generation--}}
                                                                        <div class="datepicker input-group date">
                                                                            {!! Form::text($data->name.'@'.$data->fieldset.'@'.$data->pid, null,['class' => 'form-control input-md required','id'=>$data->name]) !!}
                                                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                                                        </div>
                                                                        <span>Previous Value : {{ !empty($data->value)? date('d-M-Y', strtotime($data->value)) : ''}}
                                                                            @if($data->fieldset == 'ow_info')
                                                                                <br>
                                                                                For: {{ $data->ow_name }}
                                                                            @endif
                                                                        </span>
                                                                        <br><br>
                                                                        @elseif(array_key_exists($data->name, $img_field_map_array))

                                                                            <input type="file"
                                                                                   class="form-control required"
                                                                                   id="{{$data->name.$data->pid}}"
                                                                                   onchange="uploadDocument('preview_photo_{{$data->pid}}', this.id, '{{$data->name.'@'.$data->fieldset.'@'.$data->pid}}',1)">
                                                                            <div id="preview_photo_{{$data->pid}}">
                                                                                <input type="hidden"
                                                                                       name="{{$data->name.'@'.$data->fieldset.'@'.$data->pid}}"
                                                                                       value=""
                                                                                       id="{{$data->name.'@'.$data->fieldset}}">
                                                                            </div>

                                                                            <span>Previous Value : {{$data->value}}
                                                                                @if($data->fieldset == 'ow_info')
                                                                                    <br>
                                                                                    For: {{ $data->ow_name }}
                                                                                @endif
                                                                            </span>
                                                                            <br><br>
                                                                        @else
                                                                            {!! Form::text($data->name.'@'.$data->fieldset.'@'.$data->pid, null,['class' => 'form-control input-md required','id'=>$data->name,'size'=>'5x1','maxlength'=>'200']) !!}
                                                                            <span>Previous Value : {{$data->value}},
                                                                            @if($data->fieldset == 'ow_info')
                                                                                    <br>
                                                                                    For: {{ $data->ow_name }}
                                                                                @endif
                                                                        </span>
                                                                            <br><br>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else

                                        {{--<div class="panel panel-primary" style="margin: 4px;">--}}
                                        {{--    <div class="panel-heading"><strong>3. Required Documents for attachment</strong></div>--}}
                                        {{--    <div class="panel-body">--}}
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover ">
                                                <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th colspan="6">Required Attachments</th>
                                                    <th colspan="2">Attached PDF file
                                                        <span onmouseover="toolTipFunction()" data-toggle="tooltip"
                                                              title="Attached PDF file (Each File Maximum size 2MB)!">
                                                        <i class="fa fa-question-circle" aria-hidden="true"></i></span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1;
                                                $attachment_list = $value;
                                                $clrDocuments = [];
                                                ?>
                                                {{--                {{dd($clrDocuments)}}--}}
                                                @foreach($attachment_list as $row)

                                                    <tr>
                                                        <td>
                                                            <div align="center">{!! $i !!}<span
                                                                        class="required-star"></span></div>
                                                        </td>
                                                        <?php   $alldocuments = $shortfallData->data->req_doc_detail; ?>
                                                        <td colspan="6">{!!  searchForId($row->value,$alldocuments) !!}</td>
                                                        <td colspan="2">
                                                            {{--                    {{dd($clrDocuments)}}--}}
                                                            <input type="hidden" value="{!!  $row->name !!}"
                                                                   name="dynamicDocumentsId[]"/>
                                                            <input name="document_id_<?php echo $row->name; ?>"
                                                                   type="hidden"
                                                                   value="">
                                                            <input type="hidden" value="{!!  $row->name !!}"
                                                                   id="doc_name_<?php echo $row->name; ?>"
                                                                   name="doc_name_<?php echo $row->name; ?>"/>
                                                            <input type="hidden" value="{!!  $row->doc_name !!}"
                                                                   id="doc_title_<?php echo $row->name; ?>"
                                                                   name="doc_title_<?php echo $row->name; ?>"/>
                                                            <input name="<?php echo $row->name; ?>"
                                                                   class="required"
                                                                   id="<?php echo $row->name; ?>" type="file"
                                                                   size="20"
                                                                   onchange="uploadDocument('preview_<?php echo $row->name; ?>', this.id, 'validate_field_<?php echo $row->name; ?>', '')"/>


                                                            <div id="preview_<?php echo $row->name; ?>">
                                                                <input type="hidden"
                                                                       value=""
                                                                       id="validate_field_<?php echo $row->name; ?>"
                                                                       name="validate_field_<?php echo $row->name; ?>"
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
                                    @endif
                                @endforeach
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    &nbsp;
                                </div>
                                @if($is_submit_shortfall->is_submit_shortfall == 0)
                                    <div class="col-md-6">
                                        <input type="submit" class="btn btn-primary  pull-right" name="actionBtn"
                                               value="Submit">
                                    </div>
                                @endif

                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>
@section('footer-script')
    <script>
        $(document).ready(function () {
            var today = new Date();
            var yyyy = today.getFullYear();
            $('.datepicker').datetimepicker({
                viewMode: 'years',
                format: 'DD-MMM-YYYY',
                maxDate: '01/01/' + (yyyy + 20),
                minDate: '01/01/' + (yyyy - 100),
                useCurrent: false
            });
        });
    </script>
    <script>
        $(function () {
            $("#ccieshortfall").validate({
                errorPlacement: function () {
                    return false;
                }
            });
        });

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

            // try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/industrial-IRC/upload-document')}}";
            //alert(action);
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
            // } catch (err) {
            //     document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            // }
        }
    </script>
    <script>
        $(document).ready(function () {

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "agent-id",
                    value: "{{$agent}}"
                },
            ];

            $(function () {
                token = "{{$token}}";
                tokenUrl = '/industrial-IRC/ccie/get-refresh-token';
                $('#division_id').keydown();
                $('#bank_name_id').keydown();
                // $('#organization_type').keydown();
                // $('#country').keydown();
                // $('#district_name').keydown();
                // $('#quantity_type').keydown();
                // $('#ypc_unit').keydown();
                // $('#hypc_unit').keydown();
                // $('#item_type').keydown();
                // $('#share_type').keydown();
                // $('#irc_slab').keydown();
                // $('#association_name').keydown();
            });


            $("#division_id").on("change", function () {
                // alert('ss');
                var self = $(this);
                $(self).next().hide();
                $(this).after('<span class="loading_data">Loading...</span>');
                var division = $('#division_id').val();
                var divisionId = division.split("@")[0];

                if (divisionId) {
                    var e = $(this);
                    var api_url = "{{$ccie_service_url}}/info/district" + '/' + divisionId;
                    var selected_value = ''; // for callback
                    var calling_id = $(this).attr('id');
                    var dependent_section_id = "district_id"; // for callback
                    var element_id = "district_id"; //dynamic id for callback
                    var element_name = "district_name"; //dynamic name for callback
                    var options = {apiUrl: api_url, token: "{{$token}}", tokenUrl: tokenUrl};
                    var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                    apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

                } else {
                    $("#district_id").html('<option value="">Select Division First</option>');
                    $(self).next().hide();
                }

            });

            $("#district_id").on("change", function () {
                // alert('ss');
                var self = $(this);
                $(self).next().hide();
                $(this).after('<span class="loading_data">Loading...</span>');
                var district = $('#district_id').val();
                var districtId = district.split("@")[0];
                if (districtId) {
                    var e = $(this);
                    var api_url = "{{$ccie_service_url}}/info/thana/" + districtId;
                    var selected_value = ''; // for callback
                    var calling_id = $(this).attr('id');
                    var dependent_section_id = "org_ps_id"; // for callback
                    var element_id = "police_station_id"; //dynamic id for callback
                    var element_name = "police_station_name_en"; //dynamic name for callback
                    var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                    var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                    apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

                } else {
                    $("#org_ps_id").html('<option value="">Select District First</option>');
                    $(self).next().hide();
                }

            });

            $("#bank_id").on("change", function () {
                // alert('ss');
                var self = $(this);
                $(self).next().hide();
                $(this).after('<span class="loading_data">Loading...</span>');
                var bank_name = $('#bank_id').val();
                var bank_name_id = bank_name.split("@")[0];
                if (bank_name_id) {
                    var e = $(this);
                    var api_url = "{{$ccie_service_url}}/info/bank-branch/" + bank_name_id;
                    var selected_value = ''; // for callback
                    var calling_id = $(this).attr('id');
                    var dependent_section_id = "branch_id"; // for callback
                    var element_id = "branch_id"; //dynamic id for callback
                    var element_name = "branch_name_en"; //dynamic name for callback
                    var options = {apiUrl: api_url, token: token, tokenUrl: tokenUrl};
                    var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];


                    apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);

                } else {
                    $("#branch_name").html('<option value="">Select Bank First</option>');
                    $(self).next().hide();
                }

            });

            $('#share_type').on("keydown", function (el) {
                //alert('kkk');
                var key = el.which;
                if (typeof key !== "undefined") {
                    return false;
                }

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/share-type";

                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "share_type"; //dynamic id for callback
                var element_name = "share_type"; //dynamic name for callback
                var data = '';
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


                apiCallGet(e, options, apiHeaders, shareTypecallbackResponse, arrays);


            })

            $('#chamber_id').on("keydown", function (el) {
                //alert('kkk');
                var key = el.which;
                if (typeof key !== "undefined") {
                    return false;
                }

                $(this).after('<span class="loading_data">Loading...</span>');
                var e = $(this);
                var api_url = "{{$ccie_service_url}}/info/association";

                var selected_value = ''; // for callback
                var calling_id = $(this).attr('id'); // for callback
                var element_id = "chamber_id"; //dynamic id for callback
                var element_name = "chamber_id"; //dynamic name for callback
                var data = '';
                var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl}; // for lib
                var arrays = [calling_id, selected_value, element_id, element_name]; // for callback


                apiCallGet(e, options, apiHeaders, independantcallbackResponse, arrays);

            })

            function independantcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
                var option = '<option value="">Select One</option>';
                //alert('dd');
                if (response.responseCode === 200) {
                    //console.log(response);
                    $.each(response.data, function (key, row) {
                        //console.log(response.data);
                        var id = row[element_id];
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

            }

            function shareTypecallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
                var option = '<option value="">Select One</option>';
                //alert('dd');
                if (response.responseCode === 200) {
                    //console.log(response);
                    $.each(response.data, function (key, row) {
                        //console.log(response.data);
                        var id = row[element_id];
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
                $("#share_type").trigger('change');

            }

            function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
                var option = '<option value="">Select One</option>';
                console.log(response.data);
                if (response.responseCode === 200) {
                    $.each(response.data, function (key, row) {
                        // console.log(response.data);
                        var id = row[element_id];
                        var value = row[element_name];
                        if (selected_value == id) {
                            option += '<option selected="true" value="' + id + '">' + value + '</option>';
                        } else {
                            option += '<option value="' + id + '">' + value + '</option>';
                        }
                    });
                } else {
                    console.log(response.status)
                }
                $("#" + dependent_section_id).html(option);
                //alert(dependent_section_id);
                $("#" + calling_id).next().hide();
            }
        })
    </script>

@endsection