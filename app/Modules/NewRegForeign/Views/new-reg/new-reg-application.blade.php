@extends('layouts.admin')
@section('content')
    <style>
        .panel-body > .nav > li > a.active {
            text-decoration: none;
            background-color: white;
            color: #012233;
        }
    </style>
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
                        <input type ="hidden" name="app_id" value="{{Session::get('current_app_id')}}">
                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />



                            @if($appInfo->sfp_payment_status == 3)
                                <div class="pull-left">
                                    <a href="/spg/stack-holder/counter-payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}" target="_blank" class="btn btn-info btn-md">
                                        <strong>  Download voucher</strong>
                                    </a>
                                </div>

                                <div class="pull-right">
                                    <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(0)}}" class="btn btn-danger btn-md">
                                        <strong>  Cancel payment request</strong>
                                    </a>
                                    <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(1)}}" class="btn btn-primary btn-md">
                                        <strong> Confirm payment request</strong>
                                    </a>
                                </div>
                            @endif



                        <div class="panel-body">
                            <?php
                            $sequence = session()->get('sequence');

//                            echo '<h2>'.$sequence.'</h2>';
                            ?>
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#control">Control</a></li>
                                <li @if(1 > $sequence)  class="disabled" @endif><a @if(1 <= $sequence) data-toggle="tab" @endif  href="#step1">General information</a></li>
                                <li @if(2 > $sequence)  class="disabled" @endif><a @if(2 <= $sequence) data-toggle="tab" @endif href="#step2">Particular</a></li>
                                <li @if(3 > $sequence)  class="disabled" @endif><a @if(3 <= $sequence) data-toggle="tab" @endif href="#step3">List subscriber</a></li>
                                <li @if(4 > $sequence)  class="disabled" @endif><a @if(4 <= $sequence) data-toggle="tab" @endif href="#step4">Witness</a></li>
                                <li @if(5 > $sequence)  class="disabled" @endif><a @if(5 <= $sequence) data-toggle="tab" @endif href="#step5">Declaration</a></li>
                                <li @if(6 > $sequence)  class="disabled" @endif><a @if(6 <= $sequence) data-toggle="tab" @endif href="#step6">Form-I</a></li>
                                <li @if(7 > $sequence)  class="disabled" @endif><a @if(7 <= $sequence) data-toggle="tab" @endif href="#step7">Form-VI</a></li>
                                <li @if(8 > $sequence)  class="disabled" @endif><a @if(8 <= $sequence) data-toggle="tab" @endif href="#step8">Form-IX</a></li>
                                <li @if(9 > $sequence)  class="disabled" @endif><a @if(9 <= $sequence) data-toggle="tab" @endif href="#step9">Form-X</a></li>
                                <li @if(10 > $sequence)  class="disabled" @endif><a @if(10 <= $sequence) data-toggle="tab" @endif href="#step10">Form-XI</a></li>
                                <li @if(11 > $sequence)  class="disabled" @endif><a @if(11 <= $sequence) data-toggle="tab" @endif href="#step11">Form-XII</a></li>
                                <li @if(12 > $sequence)  class="disabled" @endif><a @if(12 <= $sequence) data-toggle="tab" @endif href="#step12">Memorandum</a></li>
                                <li @if(13 > $sequence)  class="disabled" @endif><a @if(13 <= $sequence) data-toggle="tab" @endif href="#step13">Articles</a></li>
                            </ul>
                            <div class="tab-content">
                                <div id="control" class="tab-pane fade in active">
                                    @include('NewReg::new-reg.control')
                                </div>
                                <div id="step1" class="tab-pane fade">
                                    @include('NewReg::new-reg.general-info')
                                </div>
                                <div id="step2" class="tab-pane fade">
                                    @include('NewReg::new-reg.particular')
                                </div>

                               <div id="step3" class="tab-pane fade">
                                   @include('NewReg::new-reg.list-subscriber')

                                </div>
                                <div id="step4" class="tab-pane fade">
                                    @include('NewReg::new-reg.witness-document')
                                </div>
                                <div id="step5" class="tab-pane fade">
                                   @include('NewReg::new-reg.declaration-upload')
                               </div>

                                <div id="step6" class="tab-pane fade">
                                    @include('NewReg::new-reg.declaration')
                                </div>
                                <div id="step7" class="tab-pane fade">
                                    @include('NewReg::new-reg.notice-of-situation')
                                </div>
                                <div id="step8" class="tab-pane fade">
                                    @include('NewReg::new-reg.companies-act')
                                </div>
                                <div id="step9" class="tab-pane fade">
                                    @include('NewReg::new-reg.list-of-personal')
                                </div>
                                <div id="step10" class="tab-pane fade">
                                    @include('NewReg::new-reg.agreement-page')
                                </div>
                                <div id="step11" class="tab-pane fade">
                                    @include('NewReg::new-reg.particulars-page')
                                </div>
                                <div id="step12" class="tab-pane fade">
                                    @include('NewReg::new-reg.memorandum-of-association')
                                </div>
                                <div id="step13" class="tab-pane fade">
                                    @include('NewReg::new-reg.articles-of-association')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

    <script>

        window.onload = function () {
            var hashedElementArray  = window.location.hash.split('#');
            var lastElement = hashedElementArray.length-1;
            var activeTab = $('.nav-tabs a[data-toggle="tab"][href=#' + hashedElementArray[lastElement] + ']');
            activeTab && activeTab.tab('show');
        };

        // $(function () {
            // var url = document.location.toString();
            // $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');

            // $(".nav-tabs a[data-toggle=tab]").on("click", function(e) {
            //     alert()
            //     if ($(this).hasClass("disabled")) {
            //         e.preventDefault();
            //         return false;
            //     }
            // });
        // });


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


