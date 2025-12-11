<?php
$accessMode = ACL::getAccsessRight('NameClearance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

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

    #loading-image {
        position: absolute;
        top: 210px;
        width: 150px;
        height: 120px;
        left: 600px;
        z-index: 600;
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

    .title {
        font-weight: 800;
        font-size: medium;
        display: block;
    }

    .textSmall {
        font-size: smaller;
    }

    .heading_img img {
        width: 28px !important;
        height: auto;
    }

    .noBorder {
        border: none;
    }

    .redTextSmall {
        color: red;
        font-size: 14px;
    }

    .form-group {
        margin-bottom: 10px;
    }

    .img-thumbnail {
        height: 80px;
        width: 100px;
    }

    .heading_text {
        font-size: 23px;
        margin-top: 4px;
        font-weight: bold;
        margin-left: 10px;
    }

    .selectData {
        height: 30px;
        padding: 2px 12px;
        width: 73%;
    }

    .selectData2 {
        height: 30px;
        padding: 2px 12px;
        width: 35%;
    }

    input[type=radio].error,
    input[type=checkbox].error {
        outline: 1px solid red !important;
    }

    .wizard > .steps > ul > li {
        width: 25% !important;
    }

    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }

    .mt-lg {
        margin-top: 30px;
    }


    @media screen and (max-width: 520px) {
        .selectData2 {
            height: 30px;
            padding: 2px 12px;
            width: 70%;
        }

        .mt-lg {
            margin-top: 5px;
        }
    }

    .searchInput {
        border: 2px solid #337AB7;
        border-radius: 7px;
        height: 50px;
    }

    .searchBtn {
        height: 50px;
        border-radius: 7px;
        width: 140px;
    }

    .custom-legend {
        width: 19%;
        border-bottom: 0px;
        font-size: 16px;
        margin-left: 20px;
        padding-left: 10px;
    }

    .custom-fieldset {
        border: 1px solid rgba(212, 212, 212, 1);
        border-radius: 4px;
        margin-left: 20px;
        margin-right: 20px;
        padding: 15px;
    }
</style>
<section>
    <div id="loading1">

        <?php $userPic = URL::to('/assets/images/loading.gif'); ?>
        <Span class="alert alert-success" id="loding-msg"><i class="fa fa-spinner fa-spin"></i>  <span
                    id="loding-msg-text">Connecting to RJSC server.</span></Span>
    </div>


    <div class="col-md-12" id="inputForm" >

        {{--start application form with wizard--}}
        {!! Session::has('success') ? '
        <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
        ' : '' !!}
        {!! Session::has('error') ? '
        <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
        ' : '' !!}
        @if(in_array(Auth::user()->user_type,['5x505']) && $appInfo->status_id == 9)
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h5><strong>Government Fee Payment</strong></h5>
                </div>
                <div class="panel-body">
                    {!! Form::open(array('url' => 'licence-applications/name-clearance/payment','method' => 'post','id' => 'VRAPayment','enctype'=>'multipart/form-data',
                         'files' => true, 'role'=>'form')) !!}

                    <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                    <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />


                    <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md pull-right"
                            value="Submit" name="actionBtn">Payment Submit
                    </button>
                {!! Form::close() !!}<!-- /.form end -->
                </div>
            </div>
        @endif
        <div class="panel panel-info">
            <div class="panel-heading">
                <h5><strong> Apply for Name Clearance to Bangladesh </strong></h5>
            </div>

            <div class="panel-body">
                {!! Form::open(array('url' => '/licence-applications/name-clearance/add','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => ' required', 'id'=>'app_id']) !!}
                {!! Form::hidden('verifiedName', $appInfo->company_name ,['class' => 'required', 'id'=>'verifiedName']) !!}

                <div class="form-body mt-lg">
                    <div class="form-group well clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            Company Name
                        </div>
                        <div class=" col-md-10">
                            <div class="input-group">
                                {!! Form::text('company_name',$appInfo->company_name,['class' =>'form-control input-md','id'=>'company_name']) !!}

                                <div class="input-group-btn">
                                    <button class="btn btn-primary" id="searchBtn" type="submit">
                                        <span class="glyphicon glyphicon-search"></span> Search
                                    </button>
                                </div>
                            </div>
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
                    <div class="form-group clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            RJSC Office
                        </div>
                        <div class="col-md-10">
                            {!! Form::select('rjsc_office',$rjscOffice,$appInfo->rjsc_office.'@'.$appInfo->rjsc_office_name,['class' =>'form-control input-md','id'=>'rjsc_office','placeholder'=>'Select from here']) !!}
                        </div>
                    </div>
                    <div class="form-group clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            Company Type
                        </div>
                        <div class="col-md-10">
                            {!! Form::select('company_type',$rjscCompanyType,$appInfo->company_type.'@'.$appInfo->company_type_name,['class' =>'form-control input-md','id'=>'company_type','placeholder'=>'Select from here','readonly']) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <fieldset class="custom-fieldset">
                        <legend class="custom-legend">Personal Information</legend>

                        <div class="row" style="margin-right:6px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    Name
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('applicant_name',$appInfo->applicant_name,['class' =>'form-control input-md','id'=>'applicant_name']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    Position
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('designation',[],null,['class' =>'form-control input-md','id'=>'designation','placeholder'=>'Select Company Name First']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-right:6px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    Mobile Phone
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('mobile_number',$appInfo->mobile_number,['class' =>'form-control input-md mobile_number_validation','id'=>'mobile_number']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    E-mail
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('email',$appInfo->email,['class' =>'form-control input-md email','id'=>'email']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-right:6px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    District
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('district',[],$appInfo->district_id.'@'.$appInfo->district_name,['class' =>'form-control input-md','id'=>'district','placeholder'=>'Select RJSC office']) !!}

                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    Address
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('address',$appInfo->address,['class' =>'form-control input-md','id'=>'address']) !!}
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="col-md-12" style="margin-left: 10px;margin-right: 30px;">
                    <input id="acceptTerms" type="checkbox" name="is_accept" class="col-md-1 text-left required" style="width:3%;">
                    <label class="col-md-11 text-left">I agree with the <a data-toggle="modal" data-target="#myModal" style="cursor: grab;">Terms and Conditions.</a> </label>
                </div>

                <div class="col-md-12" style="margin-top: 25px;;">
                    <button type="submit" id="submit_btn" disabled style="cursor: pointer; margin-left:10px;"
                            class="btn btn-success btn-md submit pull-right"
                            value="Submit" name="actionBtn">Submit
                    </button>

                    <button type="submit" disabled id="draft_btn" class="btn btn-info btn-md cancel pull-right"
                            value="draft" name="actionBtn">Save as Draft
                    </button>
                </div>


                {!! Form::close() !!}
            </div>

        </div>
        {{--End application form with wizard--}}
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">RISC Name Clearance Certificate Terms and Condition as follows:</h4>
                    </div>
                    <div class="modal-body">
                        <ol>
                            <li> Same company name is not acceptable, its hearing sound, written style
                                etc.
                            </li>
                            <li> Similar name of international company, organization, social & Cultural
                                organization are not acceptable.
                            </li>
                            <li> Not acceptable existing company, business body, Social, Cultural,
                                Entertainment & Sporting organization's name.
                            </li>
                            <li> Name could not same Govt. Organization or Company.</li>
                            <li> Nationally fame person's name or famous family's name need to permission
                                from particular person and take permission to Government.
                            </li>
                            <li> To take freedom fighter related name for your company must be essential
                                approval of Freedom Fighter Ministry.
                            </li>
                            <li> Not acceptable similar of Govt. development program or development
                                organization.
                            </li>
                            <li> Existing political party's slogan, name and program not acceptable.</li>
                            <li> Must avoid Rebuke, Slang word ....</li>
                            <li> Name could not harm Social, Religious and national harmony.</li>
                            <li> In case of long established (at least 10 years) Social institutions, if
                                they want to register after their current name, they have to apply for
                                name clearance appearing personally along with board of committee's
                                resolution.
                            </li>
                            <li> Must be taken Ministry permission of Social, cultural & sporting
                                Organization for Limited company.
                            </li>
                            <li> Name clearance is not final name for Company Registration, RISC holds
                                power to change.
                            </li>
                        </ol>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<script src="{{ asset("assets/scripts/apicall.js") }}" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#NameClearanceForm').validate();

        $('#nextForm').on('click', function () {
            $('#inputForm').show();
            $('#searchForm').hide();
            var company_name = $('#verifiedName').text();
            $('#company_name').val(company_name);
        })
        $('#back').on('click', function (e) {
            e.preventDefault();
            $('#inputForm').hide();
            $('#searchForm').show();
        })


        $('#company_name').on('input', function () {
            var company = $('#company_name').val();
            var verified = $('#verifiedName').val();
            if(company != verified){
                $('#submit_btn').attr('disabled','disabled');
                $('#draft_btn').attr('disabled','disabled');
                $('#searchBtn').removeAttr('disabled');
            }else{
                $('#submit_btn').removeAttr('disabled');
                $('#draft_btn').removeAttr('disabled');
                $('#searchBtn').attr('disabled','disabled');
                $('#availableMsg').hide();
                $('#unavailableMsg').hide();
            }

        })
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

    });

    // Get Land Use List
    $(document).ready(function () {

        $(function () {
            token = "{{$token}}";
            //tokenUrl = '/company-registration/get-refresh-token';
            tokenUrl = '/company-registration-sf/get-refresh-token';

            // $('#rjsc_office').keydown();
            // $('#company_type').keydown();
            $("#company_type").trigger("change");
            $('#rjsc_office').trigger("change");
        });

        $('#rjsc_office').on('keydown', function (el) {

            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjsc_nc_api_url}}/info/office";
            var selected_value = "{{$appInfo->rjsc_office}}"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "officeId"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            var errorLog = {logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        {{--$('#rjsc_office').on('change', function (el) {--}}

        {{--    var key = el.which;--}}
        {{--    if (typeof key !== "undefined") {--}}
        {{--        return false;--}}
        {{--    }--}}
        {{--    $("#district").after('<span class="loading_data">Loading...</span>');--}}
        {{--    var e = $(this);--}}

        {{--    var office = $(this).val()--}}
        {{--    var office_id = office.split('@')[0]--}}
        {{--    var api_url = "{{$rjsc_nc_api_url}}/info/district/office/"+office_id;--}}
        {{--    var selected_value = "{{$appInfo->district_id}}"; // for callback--}}
        {{--    var calling_id = "district"; // for callback--}}
        {{--    var element_id = "id"; //dynamic id for callback--}}
        {{--    var element_name = "name"; //dynamic name for callback--}}
        {{--    var data = '';--}}
        {{--    // var data = JSON.stringify({name: 'getLandUseList'});--}}
        {{--    var errorLog = {logUrl: '/log/api', method: 'get'};--}}
        {{--    var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog}; // for lib--}}
        {{--    var arrays = [calling_id, selected_value, element_id, element_name]; // for callback--}}

        {{--    var apiHeaders = [--}}
        {{--        {--}}
        {{--            key: "Content-Type",--}}
        {{--            value: 'application/json'--}}
        {{--        },--}}
        {{--        {--}}
        {{--            key: "client-id",--}}
        {{--            value: 'OSS_BIDA'--}}
        {{--        },--}}
        {{--    ];--}}

        {{--    apiCallGet(e, options, apiHeaders, callbackResponse, arrays);--}}

        {{--});--}}

        $('#rjsc_office').on('change', function (el) {

            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            var e = $(this);
            var office = $(this).val()
            var office_id = office.split('@')[0]
            $(this).after('<span class="loading_data">Loading...</span>');
            var selectedDistrict = "{{$appInfo->district_id}}";
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-applications/name-clearance/rjsc-district-by-office/"+office_id,
                data: {},
                success: function (response) {
                    // console.log(response);
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        // console.log(response.data);
                        $.each(response.data, function (id, value) {
                            // console.log(id);
                            // console.log(value);
                            var idvalue = id+ '@' +value;
                            if (id == selectedDistrict) {
                                option += '<option value="' + idvalue + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + idvalue + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#district").html(option);
                    $(self).next().hide();
                }
            })

        });

        $('#company_type').on('keydown', function (el) {

            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjsc_nc_api_url}}/entity-type";
            var selected_value = "{{$appInfo->company_type}}"; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "id"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            // var data = JSON.stringify({name: 'getLandUseList'});
            var errorLog = {logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog}; // for lib
            var arrays = [calling_id, selected_value, element_id, element_name]; // for callback

            var apiHeaders = [
                {
                    key: "Content-Type",
                    value: 'application/json'
                },
                {
                    key: "client-id",
                    value: 'OSS_BIDA'
                },
            ];

            apiCallGet(e, options, apiHeaders, companyTypeCallbackResponse, arrays);

        });

        {{--$("#company_type").on("change", function () {--}}
        {{--    var self = $(this);--}}
        {{--    $(self).next().hide();--}}
        {{--    $(this).after('<span class="loading_data">Loading...</span>');--}}
        {{--    var companyType = $('#company_type').val();--}}
        {{--    var companyTypeId = companyType.split('@')[0];--}}
        {{--    if(companyTypeId){--}}
        {{--        var e = $(this);--}}
        {{--        var api_url = "{{$rjsc_nc_api_url}}/position/"+companyTypeId;--}}
        {{--        var selected_value = "{{$appInfo->designation_id}}"; // for callback--}}
        {{--        var calling_id = $(this).attr('id');--}}
        {{--        var dependent_section_id = "designation"; // for callback--}}
        {{--        var element_id = "positionId"; //dynamic id for callback--}}
        {{--        var element_name = "positionTitle"; //dynamic name for callback--}}
        {{--        var errorLog={logUrl: '/log/api', method: 'get'};--}}
        {{--        var options ={apiUrl: api_url, token: token, tokenUrl:tokenUrl, errorLog:errorLog};--}}
        {{--        var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];--}}

        {{--        var apiHeaders = [--}}
        {{--            {--}}
        {{--                key:"Content-Type",--}}
        {{--                value: 'application/json'--}}
        {{--            },--}}
        {{--            {--}}
        {{--                key:"client-id",--}}
        {{--                value: 'OSS_BIDA'--}}
        {{--            },--}}
        {{--        ];--}}
        {{--        apiCallGet(e, options, apiHeaders, callbackResponseDependentSelect, arrays);--}}

        {{--    }else{--}}
        {{--        $("#designation").html('<option value="">Select Company Type First</option>');--}}
        {{--        $(self).next().hide();--}}
        {{--    }--}}

        {{--});--}}

        $("#company_type").on("change", function () {
            var company_type = $('#company_type').val();
            var company_type_id = company_type.split('@')[0];
            $(this).after('<span class="loading_data">Loading...</span>');
            var selecteddesignation = "{{$appInfo->designation_id}}";
            var self = $(this);
            $.ajax({
                type: "GET",
                url: "<?php echo url(); ?>/licence-applications/name-clearance/company-type",
                data: {
                    company_type: company_type_id
                },
                success: function (response) {
                    // console.log(response);
                    var option = '<option value="">Select One</option>';
                    if (response.responseCode == 1) {
                        // console.log(response.data);
                        $.each(response.data, function (id, value) {
                            // console.log(id);
                            // console.log(value);
                            var idvalue = id+ '@' +value;
                            if (id == selecteddesignation) {
                                option += '<option value="' + idvalue + '" selected>' + value + '</option>';
                            } else {
                                option += '<option value="' + idvalue + '">' + value + '</option>';
                            }
                        });
                    }
                    $("#designation").html(option);
                    $(self).next().hide();
                }
            });
        });

    });


    function callbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
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

    function companyTypeCallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {
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

    function districtcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {


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
        // $('#'+calling_id).trigger('change');
    }

    function callbackResponseDependentSelect(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        // console.log(response.data);
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
        } else {
            console.log(response.status)
        }
        $("#" + dependent_section_id).html(option);
        $("#" + calling_id).next().hide();
    }

    $('body').on('click', '.reCallApi', function () {

        var id = $(this).attr('data-id');
        $("#" + id).trigger('keydown');
        $(this).remove();
    });

    $('.onlyNumber').on('keydown', function (e) {
        //period decimal
        if ((e.which >= 48 && e.which <= 57)
            //numpad decimal
            || (e.which >= 96 && e.which <= 105)
            // Allow: backspace, delete, tab, escape, enter and .
            || $.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1
            // Allow: Ctrl+A
            || (e.keyCode == 65 && e.ctrlKey === true)
            // Allow: Ctrl+C
            || (e.keyCode == 67 && e.ctrlKey === true)
            // Allow: Ctrl+V
            || (e.keyCode == 86 && e.ctrlKey === true)
            // Allow: Ctrl+X
            || (e.keyCode == 88 && e.ctrlKey === true)
            // Allow: home, end, left, right
            || (e.keyCode >= 35 && e.keyCode <= 39)) {

            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9.]/g, ''));
            }, 4);

            var thisVal = $(this).val();
            if (thisVal.indexOf(".") != -1 && e.key == '.') {
                return false;
            }
            $(this).removeClass('error');
            return true;
        } else {
            $(this).addClass('error');
            return false;
        }
    }).on('paste', function (e) {
        var $this = $(this);
        setTimeout(function () {
            $this.val($this.val().replace(/[^0-9]/g, ''));
        }, 5);
    });

    function checkgenerator() {
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
                        $('#submit_btn').attr('disabled','disabled');
                        $('#draft_btn').attr('disabled','disabled');
                        $('#loading1').hide();
                        $('#requestName').html(response.name);
                        $('#unavailableMsg').show();
                        alert(response.message);
                    }else if (response.status == 0) {
                        myVar = setTimeout(checkgenerator, 5000);
                    }else if (response.status == -1) {
//                            $('.msg1').html('সার্ভার থেকে প্রতিক্রিয়ার জন্য অপেক্ষা করছি। অনুগ্রহ করে অপেক্ষা করুন...');
                        $('#loding-msg-text').text(response.message)
                        myVar = setTimeout(checkgenerator, 5000);
                    }else if (response.status == 1) {
                        $('#searchBtn').attr('disabled','disabled');
                        $('#loading1').hide();
                        $('#availableName').html(response.name);
                        $('#submit_btn').removeAttr('disabled');
                        $('#draft_btn').removeAttr('disabled');
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


</script>
