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
        background-color: #fff;
        z-index: 99;
        text-align: center;
        background-color: rgba(192,192,192,0.3);
    }

    .radio_hover {
        cursor: pointer;
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
        top: 48%;
        left: 340px;
        font-size: 24px;
        font-weight: bold;
        width: 30%;
        z-index: 600;
        padding: 10px;
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

    <div class="text-center" id="searchForm" style="display:block;">
        <div class="col-md-8 col-md-offset-2" style="margin-top: 70px;color:#337AB7;">
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
        <div class="col-md-8 col-md-offset-2" id="availableMsg" style="margin-top:40px;display:none;margin-bottom:10px;">
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

    <div class="col-md-12" id="inputForm" style="display:none;">

        {{--start application form with wizard--}}
        {!! Session::has('success') ? '
        <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
        ' : '' !!}
        {!! Session::has('error') ? '
        <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
        ' : '' !!}

        <div class="panel panel-info">
            <div class="panel-heading">
                <h5><strong> Apply for Name Clearance to Bangladesh </strong></h5>
            </div>

            <div class="panel-body">
                {!! Form::open(array('url' => '/licence-applications/name-clearance/add','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}

                <div class="form-body mt-lg">
                    <div class="form-group clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            Company Name
                        </div>
                        <div class="col-md-10">
                            {!! Form::text('company_name',null,['class' =>'form-control input-md','id'=>'company_name','readonly']) !!}
                            <input name="enc_id" id="enc_id" type="hidden" class="" value="">
                            <input name="status" id="status_code" type="hidden" class="" value="">
                        </div>
                    </div>
                    <div class="form-group clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            RJSC Office
                        </div>
                        <div class="col-md-10">
                            {!! Form::select('rjsc_office',$rjscOffice,null,['class' =>'form-control input-md','id'=>'rjsc_office','placeholder'=>'Select from here']) !!}
                        </div>
                    </div>
                    <div class="form-group clearfix col-md-11 col-md-offset-1 col-xs-12">
                        <div class="col-md-2">
                            Company Type
                        </div>
                        <div class="col-md-10">
                            {!! Form::select('company_type',$rjscCompanyType,null,['class' =>'form-control input-md','id'=>'company_type','placeholder'=>'Select from here','readonly']) !!}
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
                                    {!! Form::text('applicant_name',$basicAppInfo->ceo_full_name,['class' =>'form-control input-md','id'=>'applicant_name']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    Position
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('designation',[],null,['class' =>'form-control input-md','id'=>'designation','placeholder'=>'Select Company type First']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-right:6px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    Mobile Phone
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('mobile_number',$basicAppInfo->ceo_mobile_no,['class' =>'form-control input-md mobile_number_validation','id'=>'mobile_number']) !!}
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    E-mail
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('email',$basicAppInfo->ceo_email,['class' =>'form-control input-md email','id'=>'email']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-right:6px; margin-bottom:10px;">
                            <div class="col-md-6">
                                <div class="col-md-4">
                                    District
                                </div>
                                <div class="col-md-8">
                                    {!! Form::select('district',[],null,['class' =>'form-control input-md','id'=>'district','placeholder'=>'Select from here']) !!}

                                </div>
                            </div>
                            <?php
                            $add=$basicAppInfo->ceo_address.', ';


                            if ($basicAppInfo->ceo_thana_id > 0){
                                $add .= $thana[$basicAppInfo->ceo_thana_id].', ';
                            }

                            if ($basicAppInfo->ceo_district_id > 0){
                                $add .= $districts_basic_info[$basicAppInfo->ceo_district_id].', ';
                            }




                            $add .=$basicAppInfo->ceo_post_code;


                            ?>
                            <div class="col-md-6 ">
                                <div class="col-md-4">
                                    Address
                                </div>
                                <div class="col-md-8">
                                    {!! Form::text('address','',['class' =>'form-control input-md','id'=>'address']) !!}
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
                <div class="col-md-12" style="margin-left: 10px;margin-right: 30px;">
                    <input id="acceptTerms" type="checkbox" name="is_accept" class="col-md-1 text-left required"
                           style="width:3%;">
                    <label class="col-md-11 text-left">I agree with the <a data-toggle="modal" data-target="#myModal"
                                                                           style="cursor: grab;">Terms and
                            Conditions.</a> </label>
                </div>

                <div class="col-md-12" style="margin-top: 25px;;">
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-md" id="back">Back
                        </button>
                    </div>
                    <div class="col-md-8">
                        <button type="submit" id="submit_btn" style="cursor: pointer; margin-left:10px;"
                                class="btn btn-success btn-md submit pull-right"
                                value="Submit" name="actionBtn">Submit
                        </button>

                        <button type="submit" class="btn btn-info btn-md cancel pull-right"
                                value="draft" name="actionBtn">Save as Draft
                        </button>

                    </div>

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

    });

    // Get Land Use List
    $(document).ready(function () {

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/name-clearance/get-refresh-token';

            // $('#rjsc_office').keydown();
            // $('#district').keydown();
            // $('#company_type').keydown();
            // $('#designation').keydown();
        });

        $('#rjsc_office').on('keydown', function (el) {

            var key = el.which;
            if (typeof key !== "undefined") {
                return false;
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            var e = $(this);

            var api_url = "{{$rjsc_nc_api_url}}/info/office";
            var selected_value = ''; // for callback
            var calling_id = $(this).attr('id'); // for callback
            var element_id = "officeId"; //dynamic id for callback
            var element_name = "name"; //dynamic name for callback
            var data = '';
            var apiendpoint = 'info/office'

            // var data = JSON.stringify({name: 'getLandUseList'});
            var errorLog = {logUrl: '/log/api', method: 'get'};
            var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog,apiendpoint:apiendpoint}; // for lib
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
        {{--    var selected_value = "{{$userdistrict_name}}"; // for callback--}}
        {{--    var calling_id = "district"; // for callback--}}
        {{--    var element_id = "id"; //dynamic id for callback--}}
        {{--    var element_name = "name"; //dynamic name for callback--}}
        {{--    var data = '';--}}
        {{--    var apiendpoint = 'info/distric'--}}
        {{--    // var data = JSON.stringify({name: 'getLandUseList'});--}}
        {{--    var errorLog = {logUrl: '/log/api', method: 'get'};--}}
        {{--    var options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl, errorLog: errorLog,apiendpoint:apiendpoint}; // for lib--}}
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

        {{--    apiCallGet(e, options, apiHeaders, districtcallbackResponse, arrays);--}}

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
                            var idvalue = id+ '@' +value;
                            option += '<option value="' + idvalue + '">' + value + '</option>';
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
            var selected_value = 1; // for callback
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

            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);

        });

        $("#designation").on("keydown", function () {
            var self = $(this);
            $(self).next().hide();
            $(this).after('<span class="loading_data">Loading...</span>');
            var companyType = "1@Private Company";
            // var companyType = $('#company_type').val();
            var companyTypeId = companyType.split('@')[0];
            if(companyTypeId){
                var e = $(this);
                var api_url = "{{$rjsc_nc_api_url}}/position/"+companyTypeId;
                var selected_value = "{{\Illuminate\Support\Facades\Auth::user()->designation}}"; // for callback
                var calling_id = $(this).attr('id');
                var dependent_section_id = "designation"; // for callback
                var element_id = "positionId"; //dynamic id for callback
                var element_name = "positionTitle"; //dynamic name for callback
                var errorLog={logUrl: '/log/api', method: 'get'};
                var options ={apiUrl: api_url, token: token, tokenUrl:tokenUrl, errorLog:errorLog};
                var arrays = [calling_id, selected_value, element_id, element_name, dependent_section_id];

                var apiHeaders = [
                    {
                        key:"Content-Type",
                        value: 'application/json'
                    },
                    {
                        key:"client-id",
                        value: 'OSS_BIDA'
                    },
                ];
                apiCallGet(e, options, apiHeaders, callbackPosition, arrays);

            }else{
                $("#designation").html('<option value="">Select Company Type First</option>');
                $(self).next().hide();
            }

        });

        $("#company_type").on("change", function () {
            var company_type = $('#company_type').val();
            var company_type_id = company_type.split('@')[0];
            $(this).after('<span class="loading_data">Loading...</span>');
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
                            option += '<option value="' + idvalue + '">' + value + '</option>';
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
        // $('#'+calling_id).trigger('change');
    }

    function districtcallbackResponse(response, [calling_id, selected_value, element_id, element_name]) {


        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value.toLowerCase() == value.toLowerCase()) {
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
        console.log(response.data);
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

    function callbackPosition(response, [calling_id, selected_value, element_id, element_name, dependent_section_id]) {
        var option = '<option value="">Select One</option>';
        console.log(response.data);
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                var id = row[element_id] + '@' + row[element_name];
                var value = row[element_name];
                if (selected_value.toLowerCase() == value.toLowerCase()) {
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


</script>

