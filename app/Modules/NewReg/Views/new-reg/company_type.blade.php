
    <style>
        #app-form label.error {
            display: none !important;
        }
    </style>
    <section class="content" id="">
        <div class="col-md-12">
            <div class="col-md-12" style="padding:0px;">
                <div class="box">
                    <div class="box-body">
                        <div class="modal fade" id="particulars_individual" tabindex="-1" data-keyboard="false" data-backdrop="static"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    <strong class="text-center">Office of the Registrar of Joint Stock Companies and Firms</strong>
                                                </h5>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="{{url('/dashboard')}}" class="close pull-right"><span aria-hidden="true">&times;</span></a>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="modal-body" style="padding: 0;">
                                        <div class="col-md-12">
                                            <form action="{{ url('/new-reg/save-reg-form') }}" method="post" id="first_form">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="verification_id" id="verification_id">
                                                <h3 class="text-center"><b>Registration Application</b></h3>
                                                <p class="text-center"><b> Control Page</b></p>
                                                <div class="col-md-8 col-md-offset-2">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <strong>Select Company Type</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('entity_type_id') ? 'has-error': ''}}">
                                                                        {!! Form::label('entity_type_id','Company Type :',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-6">
                                                                            {!! Form::select('entity_type_id',$rjscOffice+['3' => 'Foreign Company'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                            {!! $errors->first('entity_type_id','<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                        <div class="col-md-5"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <strong>Enter Name Clearance Information</strong>
                                                        </div>
                                                        <div class="panel-body">
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('submission_no') ? 'has-error': ''}}">
                                                                        {!! Form::label('liability_type','Submission No :',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-6">
                                                                            {!! Form::number('submission_no','',['class' => 'col-md-7 form-control input-md required','id'=>'submission_no','placeholder' => '']) !!}
                                                                            {!! $errors->first('submission_no','<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                        <div class="col-md-5"></div>
                                                                    </div>
                                                                    <br>
                                                                    <br>
                                                                    <div class="col-md-8 col-md-offset-2 {{$errors->has('liability_type') ? 'has-error': ''}}">
                                                                        {!! Form::label('liability_type','Clearance Letter :',['class'=>'col-md-5 text-left']) !!}
                                                                        <div class="col-md-6">
                                                                            {!! Form::text('clearence_letter_no','',['class' => 'col-md-7 form-control input-md required','placeholder' => '','id'=>'clearence_letter_no']) !!}
                                                                            {!! $errors->first('clearence_letter_no','<span class="help-block">:message</span>') !!}
                                                                        </div>
                                                                        <div class="col-md-5"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <div class="row">
                                                                    <div class="col-md-4 col-md-offset-6">
                                                                        <button type="button" id="companytypeformsubmit" class="btn btn-info">Next <i class="fa fa-angle-double-right"></i></button>
                                                                       {{-- <input type="button" class="btn btn-success" value="Go" style="padding: 5px 50px 5px 50px;" id="companytypeformsubmit">--}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <a href="{{url('/dashboard')}}" class="btn btn-primary">Close</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="{{ asset("assets/scripts/jquery.validate.js") }}"></script>
    <script>
        $('#first_form').validate();
        $(document).ready(function () {
            function myfunction() {
                $('#particulars_individual').modal('show');
            }
            myfunction();
            $(document).on('click','#companytypeformsubmit',function () {
                btn = $(this);
                var companytype=$('#entity_type_id').val();
                var clearence_letter_no = $('#clearence_letter_no').val().trim();
                var submission_no = $('#submission_no').val().trim();
                $('#clearence_letter_no').prop('disabled', true);
                $('#submission_no').prop('disabled', true);
                if(companytype == ""){
                    alert('Please Select A Company Type');
                    return false;
                }
                if(submission_no == ""){
                    alert('Please input a submission  number');
                    return false;
                }
                if(clearence_letter_no == ""){
                    alert('Please input a clearance letter number');
                    return false;
                }

                if(companytype !=""){
                    if (companytype==1){
                        storesubmissionno(clearence_letter_no,submission_no,btn);
                        //$( "#first_form" ).submit();
                        // window.location.replace('/licence-applications/company-registration/add');
                    }else if(companytype==2){
                        storesubmissionno(clearence_letter_no,submission_no,btn);
                        //$( "#first_form" ).submit();
                        // window.location.replace('/licence-applications/company-registration/add');
                    }else if(companytype==3){
                        storesubmissionno(clearence_letter_no,submission_no,btn);
                        window.location.replace('/licence-applications/company-registration/foreign-company-add');
                    }else {
                        alert('Something Wrong Plese Contact With Administrator!!');
                    }
                }else {
                    alert('Please Select A Company Type');
                    return false;
                }
            });

            function storesubmissionno(clearence_letter_no,submission_no,btn) {
                $('#first_form').validate();
                btn.prop('disabled', true);
                btn_content = btn.html();
                btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);
                $.ajax({
                    url: '/new-reg/store-submission-number',
                    type: "POST",
                    data: {
                        clearence_letter_no: clearence_letter_no,
                        submission_no: submission_no
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // alert(response.responseCode);
                        if (response.responseCode == 1) {
                            $('#verification_id').val(response.submission_verify_id)
                            var verifydata=response.submission_verify_id;
                            checkgenerator(btn);
                        } else if (response.responseCode == 0) {
                            alert('something was wrong!')
                        }
                    },
                    error: function (response) {
                        alert('someThing Goes Wrong ,123');
                    }
                });
            }


        });

        function checkgenerator(btn) {
            var verifydata=$('#verification_id').val();
            $.ajax({
                url: '/new-reg/submission-number-response',
                type: "POST",
                data: {
                    verification_id: verifydata,
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        // alert(response.status);
                        if (response.status == 0 || response.status==""|| response.status==-1) {
                            myVar = setTimeout(checkgenerator, 5000);
                        }else if (response.status == 1) {
                            $( "#first_form" ).submit();
                            btn.prop('disabled', false);

                        }else if (response.status == -2){

                            alert('Not Found in Rjsc');
                            $('#companytypeformsubmit').removeAttr('disabled');
                            $('#clearence_letter_no').removeAttr('disabled');
                            $('#submission_no').removeAttr('disabled');
                            $('#companytypeformsubmit').html(btn_content);
                        } else{
                            // alert(response.message);
                            $('#clearence_letter_no').removeAttr('disabled');
                            $('#submission_no').removeAttr('disabled');
                            $('#companytypeformsubmit').html(btn_content);
                            $('#companytypeformsubmit').removeAttr('disabled');
                        }
                    } else {
                        alert(response.message);
                        $('#clearence_letter_no').removeAttr('disabled');
                        $('#submission_no').removeAttr('disabled');
                        $('#companytypeformsubmit').html(btn_content);
                        $('#companytypeformsubmit').removeAttr('disabled');
                        // window.location.reload();
                    }
                }
            });
            return false; // keeps the page from not refreshing
        }




    </script>