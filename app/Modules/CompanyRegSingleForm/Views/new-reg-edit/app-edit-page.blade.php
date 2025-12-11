@extends('layouts.admin')
@section('content')
    <section class="content" id="inputForm">
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

        <div class="col-md-12" style="padding:0px;">
            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong>Application</strong>
                        </div>
                        {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'app-form')) !!}
                        {{--<input type ="hidden" name="app_id" value="{{(isset($alreadyExistApplicant->application_id) ? App\Libraries\Encryption::encodeId($alreadyExistApplicant->application_id) : '')}}">--}}
                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <div class="panel-body">

                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#control">Control</a></li>
                                <li><a data-toggle="tab" href="#step1">General information</a></li>
                                <li><a data-toggle="tab" href="#step2">Particular</a></li>
                                <li><a data-toggle="tab" href="#step3">List subscribe</a></li>
                                <li><a data-toggle="tab" href="#step4">Witness</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="control" class="tab-pane fade in active">
                                    @include('NewReg::new-reg.control')
                                </div>
                                <div id="step1" class="tab-pane fade">
                                    @include('NewReg::new-reg.general-info')
                                </div>
                                <div id="step2" class="tab-pane fade">
                                    @include('NewReg::new-reg-edit.particular-edit')
                                </div>
                                <div id="step3" class="tab-pane fade">
                                    @include('NewReg::new-reg.list-subscriber')
                                </div>
                                <div id="step4" class="tab-pane fade">
                                    @include('NewReg::new-reg.witness-document')
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')

    <script>
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == ''){
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length) $('#label_' + id).remove();
                return false;
            }

            try{
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/application/upload-document')}}";
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
                    url:action,
                    dataType: 'text', // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(response){
                        $('#' + targets).html(response);
                        var fileNameArr = inputFile.split("\\");
                        var l = fileNameArr.length;
                        if ($('#label_' + id).length)
                            $('#label_' + id).remove();
                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == ''){
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        } // end of uploadDocument function


        function toolTipFunction() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    </script>

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
                    '                                <input class="col-md-7 form-control input-md required" placeholder="" name="represented_by[]" type="text" value="">\n' +
                    '                            </td>\n' +
                    '                            <td>\n' +
                    '                                <textarea class="col-md-7 form-control input-md required" placeholder="" rows="2" cols="1" required="required" name="address[]"></textarea>\n' +
                    '                                <div class="row">\n' +
                    '                                    <div class="col-md-3">\n' +
                    '                                        <label for="" class="col-md-4 text-left">District</label>\n' +
                    '                                    </div>\n' +
                    '                                    <div class="col-md-9">\n' +
                    '                                        {!! Form::select('district_id[]',$districts,['class' => 'form-control input-md required','placeholder' => '']) !!} \n' +
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
        })
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('click','#enter_info',function () {
                var rowCount = $('#list_of_subs tr').length;
                $('#list_of_subs_body').append('<tr><td><input type="checkbox">&nbsp &nbsp &nbsp' + rowCount +'</td> <td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input type="number" class="form-control"></td></tr>')
            })
            $(document).on('click','#remove_info',function () {
                var rowCount = $('#list_of_subs tr').length;
                if(rowCount > 2) {
                    $('#list_of_subs tr:last').remove();
                }
            })
        })

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
        }

    </script>
@endsection