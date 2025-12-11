<style>
    #loading1 {
        width: 100%;
        height: 100%;
        top: 0px;
        left: 200px;
        position: fixed;
        display: none;
        /*opacity: .9;*/
        z-index: 99;
        text-align: center;
        background-color: rgba(192,192,192,0.3);
    }
    #loding-msg {

        position: absolute;
        top: 37%;
        left: 340px;
        font-size: 24px;
        font-weight: bold;
        width: 30%;
        z-index: 600;
        padding: 20px 10px 20px 10px;
    }
</style>

<div id="loading1">

    <?php $userPic = URL::to('/assets/images/loading.gif'); ?>
    <Span class="alert alert-success" id="loding-msg"><i class="fa fa-spinner fa-spin"></i>  <span
                id="loding-msg-text">Connecting to RJSC server.</span></Span>
</div>

<div class="panel-body">
    <form action="{{ url('/company-registration-sf/save-reg-form') }}" method="post" id="first_form">
        {{ csrf_field() }}
        <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
        <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
        <input type="hidden" name="selected_file" id="selected_file" />
        <input type="hidden" name="validateFieldName" id="validateFieldName" />
        <input type="hidden" name="isRequired" id="isRequired" />
        <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">

    <p class="text-center"><b> Control Page</b></p>
    <div class="col-md-8 col-md-offset-2">
        @if($appInfo->verified_company_name == -1)
            <strong class="text-danger">{{!empty($appInfo->process_desc)?$appInfo->process_desc:'This Name already taken.'}}</strong>
        @endif
        <div class="form-group well clearfix col-md-11 col-md-offset-1 col-xs-12">
            <div class="col-md-2">
                Company Name
            </div>
            <div class=" col-md-10">
                @if($appInfo->company_verification_status !=1)
                <div class="input-group">
                    {!! Form::text('companySearch',$appInfo->verified_company_name,['class' =>'form-control input-md','id'=>'company_name']) !!}

                        <div class="input-group-btn">
                            <button class="btn btn-primary" id="searchBtn" type="submit">
                                <span class="glyphicon glyphicon-search"></span> Search
                            </button>
                        </div>
                </div>
                @else
                    {!! Form::text('companySearch',$appInfo->verified_company_name,['class' =>'form-control input-md','id'=>'company_name','readonly']) !!}
                @endif
                <span id="ltdMessage" style="color:#337AB7;">Note: "Limited/Ltd/Ltd." any one of three words must be written at end of the company name</span>
            </div>
            <div class="col-md-8 col-md-offset-2" id="availableMsg" style="display:none;">
                <h4 class="text-green"><strong><span id="availableName"></span></strong> is available!</h4>
            </div>
            <div class="col-md-8 col-md-offset-2" id="unavailableMsg" style="display:none;">
                <h4 class="text-danger">Sorry, <strong><span id="requestName"></span></strong> is already reserved! Please try another name</h4>
            </div>
            <input name="enc_id" id="enc_id" type="hidden" class="" value="">
            <input name="status" id="status_code" type="hidden" class="" value="">
        </div>

    </div>
        @if(ACL::getAccsessRight('NewReg','-E-'))
            <div class="">
                <div class="col-md-6">
                    <button class="btn btn-info" value="draft" name="actionBtn" id="draft" disabled type="submit">Save as Draft</button>
                </div>
                <div class="col-md-6 text-right">
                    <button class="btn btn-success" name="actionBtn" value="save" id="save" disabled type="submit">Save and continue</button>
                </div>
            </div>
        @endif
    </form>
</div>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>

<script>
    var apiHeaders = [
        {
            key:"Content-Type",
            value: 'application/json'
        },
        {
            key:"client-id",
            value: '{{$rjscClientId}}'
        },
    ];
    $(document).ready(function () {


        $(document).on('click','#draft',function () {
            $('#first_form').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $('#first_form').validate();
        });
    });

    function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $('#'+calling_id).trigger('change');
    }


    function callbackResponseBasedOnClass(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("." + calling_id).html(option);
        $("." + calling_id).next().hide();
        // $('#'+calling_id).trigger('change');
    }


    function callbackResponseWithSelectOne(response, [calling_id, selected_value, element_id, element_name]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        }

        $("#" + calling_id).html(option);
        $("#" + calling_id).next().hide();
        $('#'+calling_id).trigger('change');
    }

    function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        var changeStatus = 0;
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == id.split('@')[0]) {
                    changeStatus = 1;
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        if(changeStatus == 1){
            $("#" + dependent_section_id).trigger('change');
        }
        $("#" + calling_id).next().hide();
    }

    function callbackResponsePosition(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        var changeStatus = 0;
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                // console.log(response.data);
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value == value) {
                    changeStatus = 1;
                    option += '<option selected="true" value="' + id + '">' + value + '</option>';
                } else {
                    option += '<option value="' + id + '">' + value + '</option>';
                }
            });
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        if(changeStatus == 1){
            $("#" + dependent_section_id).trigger('change');
        }
        $("#" + calling_id).next().hide();
        $("#section_two_position").html(option);
        $("#position_sectjion_one").html(option);
    }
    $('#searchBtn').on('click', function () {
        var company_srch = $('#company_name').val();
        var arr = company_srch.split(' ');
        var strFile = arr[arr.length - 1].toLowerCase();
        if (arr.length == 1 || strFile != 'limited' && strFile != 'ltd' && strFile != 'ltd.') {
            $('#searchBtn').addClass('btn-danger');
            $('#company_name').css('color', 'red').css('border', '1px solid red');
            $('#ltdMessage').css('color', 'red').css('font-size', '16px');
            return false;
        } else {
            $('#searchBtn').removeClass('btn-danger');
            $('#company_name').css('color', '#337AB7').css('border', '1px solid #337AB7');
            $('#ltdMessage').css('color', '#337AB7').css('font-size', '14px');
        }

    })

    $(document).on('click','#searchBtn',function() {
        $(this).attr("disabled", "disabled");
        $('#loading1').show();
        var company_name = $('#company_name').val().trim();

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
                    chekResponseFromRjsc();
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

    function chekResponseFromRjsc() {
        var company_name = $('#company_name').val();
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
                        $('#save').attr('disabled','disabled');
                        $('#draft').attr('disabled','disabled');
                        $('#loading1').hide();
                        $('#requestName').html(response.name);
                        $('#unavailableMsg').show();
                        alert(response.message);
                    }else if (response.status == 0) {
                        myVar = setTimeout(chekResponseFromRjsc, 5000);
                    }else if (response.status == -1) {
//                            $('.msg1').html('সার্ভার থেকে প্রতিক্রিয়ার জন্য অপেক্ষা করছি। অনুগ্রহ করে অপেক্ষা করুন...');
                        $('#loding-msg-text').text(response.message)
                        myVar = setTimeout(chekResponseFromRjsc, 5000);
                    }else if (response.status == 1) {
                        $('#searchBtn').attr('disabled','disabled');
                        $('#loading1').hide();
                        $('#availableName').html(response.name);
                        $('#save').removeAttr('disabled');
                        $('#draft').removeAttr('disabled');
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