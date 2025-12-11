<?php
//$accessMode = ACL::getAccsessRight('OfficePermissionAmendment');
//if (!ACL::isAllowed($accessMode, $mode)) {
//    die('You have no access right! Please contact with system admin if you have any query.');
//}
?>
<style>
    .panel-body > .nav > li > a.active {
        text-decoration: none;
        background-color: white;
        color: #012233;
    }

    .userdefineBtn{
        color: #fff;background-color: #d9534f;border-color: #d43f3a;display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-top-color: transparent;border-right-color: transparent;border-bottom-color: transparent;border-left-color: transparent;border-radius: 4px;text-decoration: none;
    }

    .userdefineBtn2{
        color: #fff;background-color: #5bc0de;border-color: #d43f3a;display: inline-block;padding: 6px 12px;margin-bottom: 0;font-size: 14px;font-weight: 400;line-height: 1.42857143;text-align: center;white-space: nowrap;vertical-align: middle;-ms-touch-action: manipulation;touch-action: manipulation;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;background-image: none;border: 1px solid transparent;border-top-color: transparent;border-right-color: transparent;border-bottom-color: transparent;border-left-color: transparent;border-radius: 4px;text-decoration: none;
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
        @if($appInfo->sfp_payment_status == 3)
            <div class="pull-left">

            </div>

            <div class="pull-right">
                <a href="/spg/stack-holder/counter-payment-voucher/{{ Encryption::encodeId($appInfo->gf_payment_id)}}" target="_blank" rel="noopener" class="userdefineBtn">
                    <strong>  Download voucher</strong>
                </a>
                <a href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(1)}}" class="userdefineBtn2">
                    <strong> Confirm payment request</strong>
                </a>

                <a onclick = "if (! confirm('Are you sure?')) { return false; }" href="/spg/stack-holder/counter-payment-check/{{ Encryption::encodeId($appInfo->gf_payment_id)}}/{{Encryption::encodeId(0)}}" class="userdefineBtn">
                    <strong>  Cancel payment request</strong>
                </a>

            </div>
        @endif
        <div class="col-md-12 www" style="padding:0px;">

            <div class="box">
                <div class="box-body">
                    <div class="panel panel-info">
                        <div class="panel-heading">


                            <strong>Application</strong>

                            <div class="pull-right">
                                @if(!in_array($appInfo->status_id,[-1,5,6]))
                                @endif
                                @foreach($rjsc_nr_certificate as $certificate)
                                        @if($certificate->certificate_content !="" )
                                            <a href="{{URL::to('new-reg/downloadpdf/'.$certificate->id.'/'.Encryption::encodeId($applicationId))}}"
                                               class="btn btn-danger btn-xs documentUrl">
                                                <i class="fa fa-download"></i> <strong>Download Certificate {{$certificate->certificate_name}}</strong>
                                            </a>
                                        @endif
                                @endforeach
{{--                                    @if(!in_array($appInfo->status_id,[-1]))--}}
{{--                                        <a target="_blank"  rel="noopener" href="{{URL::to('new-reg/feedback/'.Encryption::encodeId($applicationId))}}"--}}
{{--                                           class="btn btn-danger btn-xs documentUrl">--}}
{{--                                            <i class="fa fa-comment"></i> <strong>Feedback </strong>--}}
{{--                                        </a>--}}
{{--                                    @endif--}}

                            </div>

                        </div>

                        <?php
                        $sequence = session()->get('current_app_id');
                        $sequence = session()->get('sequence');

//                                                    echo '<h2>'.$sequence.'</h2>';
                        ?>
                        {{--<input type ="hidden" name="app_id" value="{{(isset($alreadyExistApplicant->application_id) ? App\Libraries\Encryption::encodeId($alreadyExistApplicant->application_id) : '')}}">--}}
                        <input type="hidden" name="selected_file" id="selected_file" />
                        <input type="hidden" name="validateFieldName" id="validateFieldName" />
                        <input type="hidden" name="isRequired" id="isRequired" />
                        <div class="panel-body">
                            @if ($appInfo->status_id==25)
                                <div class="panel panel-info" style="padding: 5px;overflow-x: hidden;margin: 0px;">
                                    <strong style=" ">
                                        @if(count($payment_response)>0)
                                                <?php
                                                    $paymentdata = json_decode($payment_response->response);
                                                ?>
                                                    Company Name: {{$paymentdata->data->msg->company_name}}<br>
                                                    Reg Date :{{$paymentdata->data->msg->reg_date}}<br>
                                                    Reg No :{{$paymentdata->data->msg->reg_no}}<br>
                                                    Address :{{$paymentdata->data->msg->address}}
                                        @else
                                            <mark>
                                                <span style="display:inline;"></span>
                                            </mark>

                                        @endif
                                    </strong>
                                </div>



                            @endif
                        </div>


                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#control">Control</a></li>
                                <li @if(1 > $sequence)  class="disabled" @endif><a @if(1 <= $sequence) data-toggle="tab" @endif  href="#step1">General information</a></li>
                                <!-- <li @if(2 > $sequence)  class="disabled" @endif><a @if(2 <= $sequence) data-toggle="tab" @endif href="#step2">Particular</a></li> -->
                                <li @if(3 > $sequence)  class="disabled" @endif><a @if(3 <= $sequence) data-toggle="tab" @endif href="#step3">List subscriber</a></li>
                                <li @if(4 > $sequence)  class="disabled" @endif><a @if(4 <= $sequence) data-toggle="tab" @endif href="#step4">Witness</a></li>
                                <li @if(5 > $sequence)  class="disabled" @endif><a @if(5 <= $sequence) data-toggle="tab" @endif href="#step5">Declaration</a></li>
                                {{--<li @if(6 > $sequence)  class="disabled" @endif><a @if(6 <= $sequence) data-toggle="tab" @endif href="#step6">Form-I</a></li>--}}
                                {{--<li @if(7 > $sequence)  class="disabled" @endif><a @if(7 <= $sequence) data-toggle="tab" @endif href="#step7">Form-VI</a></li>--}}
                                {{--<li @if(8 > $sequence)  class="disabled" @endif><a @if(8 <= $sequence) data-toggle="tab" @endif href="#step8">Form-IX</a></li>--}}
                                {{--<li @if(9 > $sequence)  class="disabled" @endif><a @if(9 <= $sequence) data-toggle="tab" @endif href="#step9">Form-X</a></li>--}}
                                {{--<li @if(10 > $sequence)  class="disabled" @endif><a @if(10 <= $sequence) data-toggle="tab" @endif href="#step10">Form-XI</a></li>--}}
                                {{--<li @if(11 > $sequence)  class="disabled" @endif><a @if(11 <= $sequence) data-toggle="tab" @endif href="#step11">Form-XII</a></li>--}}
                                <!-- <li @if(12 > $sequence)  class="disabled" @endif><a @if(12 <= $sequence) data-toggle="tab" @endif href="#step12">Memorandum</a></li> -->
                                <!-- <li @if(13 > $sequence)  class="disabled" @endif><a @if(13 <= $sequence) data-toggle="tab" @endif href="#step13">Articles</a></li> -->
                                <li @if(14 > $sequence)  class="disabled" @endif><a @if(14 <= $sequence) data-toggle="tab" @endif href="#step14">Documents</a></li>
                            </ul>

                            <div class="tab-content">
                                <div id="control" class="tab-pane fade in active">
                                    @include('NewRegForeign::new-reg-edit.control-edit')
                                </div>
                                <div id="step1" class="tab-pane fade">
                                    @include('NewRegForeign::new-reg-edit.general-info-edit')
                                </div>
                                <div id="step2" class="tab-pane fade">
                                    @include('NewRegForeign::new-reg-edit.particular-edit')
                                </div>
                               <div id="step3" class="tab-pane fade">
                                    @include('NewRegForeign::new-reg-edit.list-subscriber-edit')
                                </div>

                                <div id="step4" class="tab-pane fade">
                                   @include('NewRegForeign::new-reg-edit.witness-document-edit')
                               </div>
                               
                               <div id="step5" class="tab-pane fade">
                                   @include('NewRegForeign::new-reg-edit.declaration-upload-edit')
                               </div>
                              
                               {{--<div id="step6" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.declaration-edit')--}}
                               {{--</div>--}}
                                {{--<div id="step7" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.notice-of-situation-edit')--}}
                               {{--</div>--}}
                                {{--<div id="step8" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.companies-act-edit')--}}
                               {{--</div>--}}
                                {{--<div id="step9" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.list-of-personal-edit')--}}
                               {{--</div>--}}
                                {{--<div id="step10" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.agreement-page-edit')--}}
                               {{--</div>--}}
                                {{--<div id="step11" class="tab-pane fade">--}}
                                   {{--@include('NewRegForeign::new-reg-edit.particulars-page-edit')--}}
                               {{--</div>--}}
                                <!-- <div id="step12" class="tab-pane fade">
                                   @include('NewRegForeign::new-reg-edit.memorandum-of-association-edit')
                               </div> -->
                                <div id="step13" class="tab-pane fade">
                                   @include('NewRegForeign::new-reg-edit.articles-of-association-edit')
                               </div>
                                <div id="step14" class="tab-pane fade">
                                    @include('NewRegForeign::new-reg-edit.documents-edit')
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
<script>
    $(document).ready(function () {
        var hashedElementArray  = window.location.hash.split('#');
        var lastElement = hashedElementArray.length-1;
        var activeTab = $(".nav-tabs a[href='#"+hashedElementArray[lastElement]+"']");
        activeTab && activeTab.tab('show');

        @if ($viewMode == 'on')
        $('body input').attr('disabled', true);
        $('body select').attr('disabled', true);
        $('body textarea').attr('disabled', true);
        $('body :not(".documentUrl").btn').addClass('hidden');
        $('body input[type=file]').hide();
        @endif // viewMode is on
    });
</script>
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

        });

    </script>

    <script>
        $(document).ready(function () {
            $('#aoaEditForm').validate();
        })
    </script>
@endsection