<?php
$accessMode = ACL::getAccsessRight('NameClearance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>

<style>
    .title{
        font-weight: 800;
        font-size: medium;
        display: block;
    }
    .textSmall{
        font-size: smaller;
    }
    .noBorder{
        border:none;
    }
    .redTextSmall{
        color:red;
        font-size: 14px;
    }
    .form-group{
        margin-bottom: 2px;
    }
    .img-thumbnail{
        height: 80px;
        width: 100px;
    }
    input[type=radio].error,
    input[type=checkbox].error{
        outline: 1px solid red !important;
    }
    .wizard>.steps>ul>li{
        width: 25% !important;
    }
    .table-striped > tbody#manpower > tr > td, .table-striped > tbody#manpower > tr > th {
        text-align: center;
    }
</style>
<section class="content">

    <div class="col-md-12">
        <div class="box">
            <div class="box-body" id="inputForm">
                {{--start application form with wizard--}}
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h5><strong>  Apply for Name Clearance to Bangladesh </strong></h5>
                        </div>
                        <div class="pull-right">
                            @if(!in_array($appInfo->status_id,[-1,5,6]))
                                <a href="#" target="_blank"
                                   class="btn btn-danger btn-md">
                                    <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                                </a>
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    {{-- Breadcumb bar --}}
                    @if ($viewMode == 'on' || (isset($appInfo->status_id) && $appInfo->status_id == 5))
                        <section class="content-header">
                            <ol class="breadcrumb">
                                <li><strong>Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                                <li><strong> Date of Submission: </strong> {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }} </li>
                                <li><strong>Current Status : </strong>
                                    @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                                    @else {!! $appInfo->status_name !!}
                                    @endif
                                </li>
                                <li>
                                    @if($appInfo->desk_id != 0) <strong>Current Desk :</strong>
                                    {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                                    @else
                                        <strong>Current Desk :</strong> Applicant
                                    @endif
                                </li>
                                {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 5)--}}
                                {{--<li>--}}
                                {{--<strong>Shortfall Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                {{--</li>--}}
                                {{--@endif--}}
                                {{--@if(isset($appInfo->status_id) && $appInfo->status_id == 6)--}}
                                {{--<li>--}}
                                {{--<strong>Discard Reason :</strong> {{ !empty($appInfo->process_desc)? $appInfo->process_desc : 'N/A' }}--}}
                                {{--</li>--}}
                                {{--@endif--}}
                                @if (isset($appInfo) && $appInfo->status_id == 25 && isset($appInfo->certificate_link))
                                    <li>
                                        <a href="{{ url($appInfo->certificate_link) }}" class="btn show-in-view btn-xs btn-info"
                                           title="Download Approval Letter" target="_blank"> <i class="fa  fa-file-pdf-o"></i> <b>Download Certificate</b></a>
                                    </li>
                                @endif
                            </ol>
                        </section>
                    @endif
                    {{-- End of Breadcumb bar --}}




                    <div class="panel-body">
                        {!! Form::open(array('url' => '/licence-applications/name-clearance/add','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
                        {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-md required', 'id'=>'app_id']) !!}



                    <div class="form-body">

                        <div class="row" style="margin:15px 0 15px 0">
                            <div class="col-md-12">
                                <div class="heading_img">
                                    <img class="img-responsive pull-left"
                                         src="{{ asset('assets/images/u34.png') }}"/>
                                </div>
                                <div class="heading_text pull-left">
                                    Registrar of Joint Stock Companies And Firms (RJSC)
                                </div>
                            </div>
                        </div>

                        <div>
                            <input type="hidden" name="selected_file" id="selected_file"/>
                            <input type="hidden" name="validateFieldName" id="validateFieldName"/>
                            <input type="hidden" name="isRequired" id="isRequired"/>
                            <br/>
                            <div class="panel panel-info">
                            <div class="panel-body">
                            <fieldset class="col-md-11">
                              <div class="row">
                                  <div class="col-md-12">
                                      RJSC Office: Dhaka
                                      <br>
                                      <br>
                                      To,
                                      <br>
                                      The Registrar,
                                      <br>
                                      Joint Stock Companies & Firms,
                                      <br>
                                      TCB Bhaban (6th Floor),1 Kawran Bazar, Dhaka - 1215.
                                      <br>
                                      <br>
                                      <br>
                                      Sub :- Clearance of Name for formation of a new Company
                                      <br>
                                      <br>
                                      <br>
                                      Dear Sir,
                                      <br>
                                      <br>
                                      <br>
                                      I have gone through the conditions of name clearance and in full agreement with those conditions ,I, the undersigned, on behalf of the promoters request to examine and clear one of the names specified hereunder in order of priority for registration.

                                      <br>
                                      <br>
                                      <br>
                                      <span class="title"> RISC Name Clearance Certificate Terms and Condition as follows:</span>
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
                                      <br>
                                      <br>

                                      1.  Click the checkbox confirming agreement to the aforementioned conditions <input type="checkbox" name="is_accept" class="input-md required" {{$appInfo->is_accept == 1?" checked ":'' }} disabled>
                                      <table class="table table-bordered ">
                                          <thead >
                                          <tr >
                                              <th class="text-center">SL No.</th>
                                              <th class="text-center">Name</th>
                                          </tr>
                                          </thead>
                                          <tbody>
                                          <tr>
                                              <td class="text-center">1</td>
                                              <td>
                                                  <div class="col-md-11">
                                                  <input name="company_name" type="text" class="form-control input-md required" value="{{$appInfo->company_name}}" disabled>
                                                  </div>
                                                  <div class="col-md-1">
                                                      <button class="btn btn-info pull-right">Search</button>
                                                  </div>
                                              </td>
                                          </tr>
                                          </tbody>
                                      </table>

                                      <br>
                                      <br>
                                      <br>
                                      Thanking you and with best regards.
                                      <br>
                                      <br>

                                      Yours Sincerely,
                                      <br>

                                      Name              : <input type="text" name="applicant_name" class="noBorder" value="{{$appInfo->applicant_name}}" disabled><br>
                                      Position          : <input type="text" name="designation" class="noBorder" value="{{$appInfo->designation}}" disabled><br>
                                      Mobile Phone      : <input type="text" name="mobile_number" class="noBorder" value="{{$appInfo->mobile_number}}" disabled><br>
                                      E-mail            : <input type="text" name="email" class="noBorder" value="{{$appInfo->email}}" disabled><br>
                                      Address           : <input type="text" name="address" class="noBorder" value="{{$appInfo->address}}" disabled><br>


                                      <br>
                                      <br>
                                      <p><span class="redTextSmall">Please do not tick if you do not have digital signature certificate from an authorized certifying authority</span></p>
                                      <br>
                                      <input type="checkbox" name="is_signature" class="input-md showBrowse " {{$appInfo->is_signature == 1?" checked ":'' }} disabled>Apply Digital Signature
                                      <br>
                                      <p><span class="textSmall">(If you want to insert digital signature please ensure java version 1.6.0_45 is installed in your computer and set security level medium in browser)</span></p>
                                      <div class="col-md-8 upFile">
                                          @if($appInfo->is_signature == 1 && $appInfo->digital_signature !='')
                                          <a href="/uploads/{{$appInfo->digital_signature}}" class="btn btn-success btn-small">Download</a>
                                          @else
                                          No file is found!
                                          @endif
                                      </div>
                                      {{--<input type="file" class="upFile">--}}

                                  </div>
                              </div>
                            </fieldset>
                            </div>
                            </div>



                        </div>

                    </div>
                        {!! Form::close() !!}
                </div>
                </div>
                {{--End application form with wizard--}}

            </div>
        </div>
    </div>
</section>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.css">



