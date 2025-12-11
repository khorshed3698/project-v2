<?php
$accessMode = ACL::getAccsessRight('WasaNewConnection');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any querysss.');
}
?>

<link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
<link rel="stylesheet" href="{{ url("assets/plugins/select2.min.css") }}">
<script src="{{ asset("assets/plugins/select2.min.js") }}"></script>
<style>
    .wizard > .content,
    .wizard,
    .tabcontrol {
        overflow: visible;
    }

    .form-group {
        margin-bottom: 2px;
        margin-top: 10px;

    }

    .wizard > .steps > ul > li {
        width: 33% !important;
    }

    .wizard > .steps .number {
        font-size: 1.2em;
    }

    .intl-tel-input .country-list {
        z-index: 5;
    }


    .col-md-7 {
        margin-bottom: 10px;
    }

    label {
        float: left !important;
    }

    .col-md-5 {
        position: relative;
        min-height: 1px;
        padding-right: 5px;
        padding-left: 8px;
    }

    form label {
        font-weight: normal;
        font-size: 16px;
    }

    .adhoc {
        margin-left: 15px;
    }

    .adhoc button {
        margin-top: 15px;
    }

    table thead {
        background-color: #ddd;
    }
    .panel-primary,.panel-orange {
        margin: 10px;
    }

    @media screen and (max-width: 550px) {
        .button_last {
            margin-top: 40px !important;
        }

    }
</style>


<section class="content" id="applicationForm">
    <div class="col-md-12">
        <div class="box" id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert"
                        class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong><A></A>APPLICATION FOR DEEP TUBEWELL</strong></h5>
                        </div>
                        <div class="pull-right">

                            <a class="btn btn-md btn-success" data-toggle="collapse" href="#paymentInfo" role="button"
                               aria-expanded="false" aria-controls="collapseExample">
                                <i class="far fa-money-bill-alt"></i>
                                Payment Info
                            </a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <ol class="breadcrumb">
                        <li><strong>OSS Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                        {{--                    <li><strong>DPDC Tracking no. : </strong>{{$appInfo->dpdc_tracking_no}}</li>--}}
                        <li class="highttext"><strong> Date of Submission:
                                {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at) }}</strong>
                        </li>
                        @if(!empty($appInfo->dwasa_tracking_no))
                            <li><strong>DWASA Tracking no. : </strong>{{ $appInfo->dwasa_tracking_no  }}</li>
                        @endif
                        <li><strong>Current Status : </strong> {{ $appInfo->status_name }}</li>
                        @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                            <li>
                                <a href="{{ url($appInfo->certificate_link) }}"
                                   class="btn show-in-view btn-xs btn-info"
                                   title="Download Approval Letter" target="_blank"> <i
                                            class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                            </li>
                        @endif
                    </ol>

                    {{--Payment information--}}
                    @include('SonaliPaymentStackHolder::payment-information')

                    {!! Form::open(array('url' => 'wasa-dt/store','method' => 'post', 'class' =>
                    'form-horizontal', 'id' => 'NewConnection',
                    'enctype' =>'multipart/form-data', 'files' => 'true')) !!}
                    <input type="hidden" name="selected_file" id="selected_file"/>
                    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                    <input type="hidden" name="isRequired" id="isRequired"/>

                    <fieldset>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Application Type :</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('application_type','Application Type :',['class'=>'col-md-5 text-left ']) !!}

                                        <div class="col-md-6">
                                            @if($appData->application_type =='1@Individual Application')
                                                Individual Application
                                            @else
                                                Partnarship Institution/Limited Company
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Basic Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">

                                    <div class="col-md-6">
                                        {!! Form::label('wasa_zone','WASA Zone :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            <?php
                                            $wasazone  = explode("@", $appData->wasa_zone);
                                            ?>
                                            {{!empty($appData->wasa_zone) ?$wasazone[1] : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('applicant_name','Applicants Name :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->applicant_name) ?$appData->applicant_name : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('mobile_number','Mobile Number :',['class'=>'col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->mobile_number) ?$appData->mobile_number : ''}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        {!! Form::label('email','Email Number :',['class'=>'col-md-6']) !!}
                                        <div class="col-md-6">
                                              {{!empty($appData->email) ?$appData->email : ''}}  </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6" id="application_date">
                                        {!! Form::label('application_date','Application Date :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->application_date) ?$appData->application_date : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Applicant Information</strong></div>
                            <div class="panel-body">

                                @if($appData->application_type =='61@Partnarship Institution/Limited Company')
                                    <div class="" id="InstituteApplication">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('institute_name','Institutes Name :',['class'=>'text-left col-md-6 ']) !!}
                                                <div class="col-md-6">
                                                    {{!empty($appData->institute_name) ?$appData->institute_name : ''}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('telephone_number','Telephone Number :',['class'=>'col-md-6 ']) !!}
                                                <div class="col-md-6">
                                                    {{!empty($appData->telephone_number) ?$appData->telephone_number : ''}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                {!! Form::label('telephone_number','Institution Owner\'s Name :',['class'=>'col-md-6 ']) !!}
                                                <div class="col-md-6">
                                                    {{!empty($appData->institute_owner_name) ?$appData->institute_owner_name : ''}}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('institute_name','Head of Institute :',['class'=>'text-left col-md-6 ']) !!}
                                                <div class="col-md-6">
                                                    {{!empty($appData->head_of_institute) ?$appData->head_of_institute : ''}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif

                                @if($appData->application_type =='60@Individual Application')
                                        <div class="" id="IndividualApplication">
                                            <div class="form-group">
                                                <div class="col-md-6">
                                                    {!! Form::label('father_name','Fathers Name :',['class'=>'text-left col-md-6 ']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->father_name) ?$appData->father_name : ''}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('mother_name','Mothers Name :',['class'=>'text-left col-md-6 ']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->mother_name) ?$appData->mother_name : ''}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">

                                                <div class="col-md-6">
                                                    {!! Form::label('spouse_name','Spouses Name :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->spouse_name) ?$appData->spouse_name : ''}}
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    {!! Form::label('telephone','Telephone :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->telephone) ?$appData->telephone : ''}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-md-6" id="date_of_birth">
                                                    {!! Form::label('date_of_birth','Date of Birth :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->date_of_birth) ?$appData->date_of_birth : ''}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('gender','Gender :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->gender) ?explode("@", $appData->gender)[0] : ''}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6" id="date_of_birth">
                                                    {!! Form::label('date_of_birth','NID / Date of Birth / Passport :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->niddobpassport) ?$appData->niddobpassport : ''}}
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    {!! Form::label('freedomfighter_status','Freedom Fighter Status :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        {{!empty($appData->freedomfighter_status) ?$appData->freedomfighter_status : ''}}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-md-6" id="date_of_birth">
                                                    {!! Form::label('photo','Applicant Photo :',['class'=>'text-left col-md-6']) !!}
                                                    <div class="col-md-6">
                                                        <img src="{{  (!empty($appData->validate_field_photo)? url('/uploads/'.$appData->validate_field_photo) : url('assets/images/photo_default.png')) }}"
                                                             class="img-responsive img-thumbnail"
                                                             id="correspondent_signature_preview" width="100px"/>
                                                    </div>
                                                </div>


                                                @if($appData->freedomfighter_status =='yes')
                                                    <div class="col-md-6" id="freedomfighter_photo">
                                                        {!! Form::label('freedomfighter_photo','Freedom Fighter Photo :',['class'=>'text-left col-md-6']) !!}
                                                        <div class="col-md-6">
                                                            <img src="{{  (!empty($appData->validate_field_freedom_photo)? url('/uploads/'.$appData->validate_field_freedom_photo) : url('assets/images/photo_default.png')) }}"
                                                                 class="img-responsive img-thumbnail"
                                                                 id="correspondent_signature_preview" width="100px"/>
                                                        </div>
                                                    </div>
                                                @endif




                                            </div>

                                        </div>
                                @endif

                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Address of Deep Tubewell (Installation)</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('install_address','Address :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->install_address) ?$appData->install_address : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <strong>Location of the Deep Tubewell </strong>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('structure_of_home','Mouza Name :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->mouza_name) ?$appData->mouza_name : ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('number_of_floor','R. S. Book No. :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->rs_book_no) ?$appData->rs_book_no : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('landsize','C. S. Book No  :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {{!empty($appData->cs_book_no) ?$appData->cs_book_no : ''}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('sa_book_no','S. A. Book No. :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->sa_book_no) ?$appData->sa_book_no : ''}}
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row" id="presentaddresschecked">
                                    <div class="col-md-3"><strong>Present Address  </strong></div>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('present_address','Address :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->present_address) ?$appData->present_address : ''}}
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>Connection Information</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('average_demand','Average Demand of Water (Litre/Day) :',['class'=>'text-left col-md-6 ']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->average_demand) ?$appData->average_demand : ''}}
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('usage_type','Type of Usage (in accordance with class of consumers) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->usage_type) ?explode('@',$appData->usage_type)[1] : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('landsize','Screen Diameter (mm/inch) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {{!empty($appData->screen_diameter) ?explode('@',$appData->screen_diameter)[1] : ''}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('power_of_pump','Power of Pump (kw/hp) :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->power_of_pump) ?$appData->power_of_pump : ''}}
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                        {!! Form::label('daily_running_time','Daily Pump Running Time (h):',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            <div class="col-md-6" style="margin: 0px; padding: 0px;">
                                                {{!empty($appData->daily_running_time) ?$appData->daily_running_time : ''}}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        {!! Form::label('depth','Depth of the Deep-Tubewell (feet)  :',['class'=>'text-left col-md-6']) !!}
                                        <div class="col-md-6">
                                            {{!empty($appData->depth) ?$appData->depth : ''}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>

                        <div class="panel panel-primary">
                            <div class="panel-heading"><strong>File Attachment</strong></div>
                            <div class="panel-body">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-striped table-bordered table-hover ">
                                        <tbody>
                                        <?php $i = 1; ?>
                                        @foreach($document as $row)
                                            @if($row['doc_path'] != null)
                                            <tr>
                                                <td>{!! $i !!} .</td>
                                                <td colspan="6"> {{$row->doc_name}}</td>
                                                <td colspan="2">
                                                    @if($row['doc_path'] !='' &&$row['doc_path'] !=null)
                                                        <a target="_blank" class="btn btn-xs btn-primary"
                                                           href="{{URL::to('/uploads/'.$row['doc_path'])}}"
                                                           title="{{$row['document_name_en']}}">
                                                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                                            Open File
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endif
                                            <?php $i++; ?>

                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </fieldset>

                    <fieldset>
                    </fieldset>



                </div>


                {!! Form::close() !!}
            </div>
        </div>
    </div>
</section>


<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset('assets/scripts/jquery.validate.js') }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>

<script>

    /* Get information from API for Water ConnectionSize start here*/
    $(document).ready(function () {

        $(function () {
            token = "{{$token}}"
            tokenUrl = '/wasa-dt/get-refresh-token'
        });


    })

    function waterConnSizeCallBackRes(response, [calling_id, selected_value, element_id, element_name, data]) {
        var option = '';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                {{--var id = row[element_id] + '@' + row[element_name];--}}
                var id = row[element_id];
                var value = row[element_name];
                if (selected_value == id) {
                    option += value;
                }
            });
        }

        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
        $(".app_type").trigger('change')
    }
    /* Get information from API for Water ConnectionSize end here*/

</script>
