
    <style>
        #loading1 {
            width: 100%;
            height: 100%;
            top: 0px;
            left: 200px;
            position: fixed;
            display: none;
            /*opacity: .9;*/
            z-index: 99999;
            text-align: center;
            background-color: rgba(192,192,192,0.3);
        }
        #loding-msg {

            position: absolute;
            top: 48%;
            left: 340px;
            font-size: 24px;
            font-weight: bold;
            width: 30%;
            z-index: 600;
            padding: 10px;
        }
        #app-form label.error {
            display: none !important;
        }
        .searchBtn {
            height: 50px;
            border-radius: 7px;
            width: 140px;
        }
        .searchInput {
            border: 2px solid #337AB7;
            border-radius: 7px;
            height: 50px;
        }
        .custom-legend {
            width: 19%;
            border-bottom: 0px;
            font-size: 16px;
            margin-left: 20px;
            padding-left: 10px;
        }
    </style>
    <section class="content" id="">

        <div class="col-md-12">
            <div id="loading1">

                <?php $userPic = URL::to('/assets/images/loading.gif'); ?>
                <Span class="alert alert-success" id="loding-msg"><i class="fa fa-spinner fa-spin"></i>  <span
                            id="loding-msg-text">Connecting to RJSC server.</span></Span>
            </div>
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
                                            <form action="{{ url('/company-registration-sf/save-reg-form') }}" method="post" id="first_form">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="verification_id" id="verification_id">
                                                <h3 class="text-center"><b>Registration Application</b></h3>
                                                <p class="text-center"><b> Control Page</b></p>
                                                <div class="col-md-8 col-md-offset-2 text-center">
                                                    <input name="enc_id" id="enc_id" type="hidden" class="" value="">
                                                    <div class="col-md-12" style="color:#337AB7;">
                                                        <h1 style="font-weight: bold;">Search Company Name</h1>

                                                        <div class="input-group" style="margin-top: 30px;margin-bottom: 8px;">
                                                            <input type="text" class="form-control searchInput" placeholder="Find Company Name Limited / Ltd / Ltd."
                                                                   name="companySearch" value="{{$basicAppInfo->company_name}}"
                                                                   id="companySearch"/>
                                                            <div class="input-group-btn">
                                                                <button class="btn btn-primary searchBtn" id="searchBtn" type="submit">
                                                                    <span class="glyphicon glyphicon-search"></span> Search
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <span id="ltdMessage">Note: "Limited/Ltd/Ltd." any one of three words must be written at end of the company name</span>
                                                    </div>
                                                    <div class="col-md-12 " id="availableMsg" style="margin-top:40px;display:none;margin-bottom:10px;">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body" >
                                                                <h3 class="text-green"><strong><span id="verifiedName"></span></strong> is available!</h3>
                                                                <h5 style="color:#686868">If you want to reserve this name please click on the next button.</h5>
                                                                <button class="btn btn-primary" style="width:80px;padding: 1px;margin-bottom: 10px;" id="nextForm">
                                                                    Next
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-8 col-md-offset-2" id="unavailableMsg" style="margin-top:40px;display:none;">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body" style="margin-bottom:10px;">
                                                                <h3 class="text-danger">Sorry, <strong><span id="requestName"></span></strong> is already reserved! Please try another name</h3>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-8 col-md-offset-2" id="actualMsg" style="margin-top:40px;display:none;">
                                                        <div class="panel panel-default">
                                                            <div class="panel-body" style="margin-bottom:10px;">
                                                                <h3 class="text-danger" id="actualMsgText"></h3>
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
            $(function () {
                token = "{{$token}}";
                //tokenUrl = '/name-clearance/get-refresh-token';
                tokenUrl = '/company-registration-sf/get-refresh-token';

                // $('#rjsc_office').keydown();
                // $('#district').keydown();
                // $('#company_type').keydown();
                // $('#designation').keydown();
            });
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

        $('#searchBtn').on('click', function () {
            var company_srch = $('#companySearch').val();
            var arr = company_srch.split(' ');
            var strFile = arr[arr.length - 1].toLowerCase();
            if (arr.length == 1 || strFile != 'limited' && strFile != 'ltd' && strFile != 'ltd.') {
                $('#searchBtn').addClass('btn-danger');
                $('#companySearch').css('color', 'red');
                $('#ltdMessage').css('color', 'red').css('font-size', '18px');
                $('.searchInput').css('border', '2px solid red');
                return false;
            } else {
                $('#searchBtn').removeClass('btn-danger');
                $('#companySearch').css('color', '#337AB7');
                $('#ltdMessage').css('color', '#337AB7').css('font-size', '14px');
                $('.searchInput').css('border', '2px solid #337AB7');
            }



        })

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

        $(document).on('click','#searchBtn',function() {
            $(this).attr("disabled", "disabled");
            $('#loading1').show();
            var company_name = $('#companySearch').val().trim();

            $.ajax({
                url: '/licence-applications/name-clearance/check-company',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data:{
                    company_name: company_name
                },
                success: function (response) {
                    console.log(response);
                    if (response.responseCode == 1) {
                        $('#enc_id').val(response.enc_id);
                        $('#status_code').val(response.enc_status);
                        checkgenerator();
                    } else if (response.responseCode == 0) {
                        $('#company_name').removeAttrs("disabled");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                beforeSend: function(xhr) {

                }
            });
            return false;
        });


        function checkgenerator() {
            var company_name = $('#companySearch').val();
            var enc_id = $('#enc_id').val();

            $.ajax({
                url: '/licence-applications/name-clearance/rjsc-response',
                type: "POST",
                data: {
                    company_name: company_name,
                    enc_id: enc_id
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    if (response.responseCode == 1) {
                        $('#enc_id').val(response.enc_id);
                        $('#status_code').val(response.enc_status);
                        if(response.status == -2) {
                            $('#searchBtn').removeAttr("disabled");
                            $('#companySearch').removeAttr("readonly");
                            $('#loading1').hide();
                            $('#requestName').html(response.name);
                            $('#actualMsg').hide();
                            $('#unavailableMsg').show();
                            alert(response.message);
                        }else if(response.status == -3) {
                            $('#searchBtn').removeAttr("disabled");
                            $('#companySearch').removeAttr("readonly");
                            $('#loading1').hide();
                            $('#actualMsgText').html(response.message);
                            $('#unavailableMsg').hide();
                            $('#actualMsg').show();
                            alert(response.message);
                        }else if (response.status == 0) {
                            myVar = setTimeout(checkgenerator, 5000);
                        }else if (response.status == -1) {
//                            $('.msg1').html('সার্ভার থেকে প্রতিক্রিয়ার জন্য অপেক্ষা করছি। অনুগ্রহ করে অপেক্ষা করুন...');
                            $('#loding-msg-text').text(response.message)
                            myVar = setTimeout(checkgenerator, 5000);
                        }else if (response.status == 1) {
                            $('#searchBtn').attr('disabled','disabled');
                            $('#companySearch').attr('readonly', true);
                            $('#loading1').hide();
                            $('#verifiedName').html(response.name);
                            $('#availableMsg').show();
                            $('#unavailableMsg').hide();
                            alert(response.message);
                        }
                    } else {
                        alert('Whoops there was some problem please contact with system admin.');
                        window.location.reload();
                    }
                },error: function(jqXHR, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                },
                beforeSend: function(xhr) {

                }
            });
            return false; // keeps the page from not refreshing
        }




    </script>