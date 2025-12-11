<?php
$accessMode = ACL::getAccsessRight('NameClearance');
if (!ACL::isAllowed($accessMode, $mode)) {
    die('You have no access right! Please contact with system admin if you have any query.');
}
?>
@extends('layouts.admin')
@section('content')
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
                        <h5><strong> Apply for Name Clearance to Bangladesh </strong></h5>
                    </div>

                    <div class="panel-body">
                        {!! Form::open(array('url' => '/single-licence/name-clearance/add','method' => 'post','id' => 'NameClearanceForm','role'=>'form','enctype'=>'multipart/form-data')) !!}
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

                                      1.  Click the checkbox confirming agreement to the aforementioned conditions <input type="checkbox" name="is_accept" class="input-md required">
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
                                                  <input name="company_name" type="text" class="form-control input-md required" value="{{$basicAppInfo->company_name}}">
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

                                      Name              : <input type="text" name="applicant_name" class="noBorder" ><br>
                                      Position          : <input type="text" name="designation" class="noBorder"><br>
                                      Mobile Phone      : <input type="text" name="mobile_number" class="noBorder"><br>
                                      E-mail            : <input type="text" name="email" class="noBorder"><br>
                                      Address           : <input type="text" name="address" class="noBorder"><br>


                                      <br>
                                      <br>
                                      <p><span class="redTextSmall">Please do not tick if you do not have digital signature certificate from an authorized certifying authority</span></p>
                                      <br>
                                      <input type="checkbox" name="is_signature" class="input-md showBrowse ">Apply Digital Signature
                                      <br>
                                      <p><span class="textSmall">(If you want to insert digital signature please ensure java version 1.6.0_45 is installed in your computer and set security level medium in browser)</span></p>
                                      <div class="col-md-8 upFile">
                                          <input type="file" name="digital_signature_file"
                                                 class='form-control input-md '
                                                 onchange="uploadSinatureDocument('digital_signature_file_preview', this.id, 'digital_signature', '0')"
                                                 id='digital_signature_file'>
                                          <small class="text-muted">Max file size 2MB.</small>
                                          {!! $errors->first('tin_file','<span class="help-block">:message</span>') !!}
                                          <div id="digital_signature_file_preview" class="uploadbox">
                                              <input type="hidden" class="" id="digital_signature"
                                                     name="digital_signature">
                                          </div>
                                      </div>
                                      {{--<input type="file" class="upFile">--}}

                                  </div>
                              </div>
                            </fieldset>
                            </div>
                            </div>

                            @if(ACL::getAccsessRight('NameClearance','-E-'))

                                <button type="submit" class="btn btn-info btn-md submit pull-right"
                                        value="Submit" name="actionBtn">Submit
                                </button>
                            @endif

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



<script  type="text/javascript">

    function uploadSinatureDocument(targets, id, vField, isRequired) {
        var file_id = document.getElementById(id);
        var file = file_id.files;
        if (file && file[0]) {
            if (!(file[0].type == 'application/pdf')) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'The file format is not valid! Please upload in pdf format.'
                });
                file_id.value = '';
                return false;
            }

            var file_size = parseFloat((file[0].size) / (1024 * 1024)).toFixed(1); //MB Calculation
            if (!(file_size <= 2)) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Max file size 2MB. You have uploaded ' + file_size + 'MB'
                });
                file_id.value = '';
                return false;
            }
        }
        var inputFile =  $("#" + id).val();
        if(inputFile == ''){
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="'+vField+'" name="'+vField+'">';
            if ($('#label_' + id).length) $('#label_' + id).remove();
            return false;
        }

        try{
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{url('/licence-application/name-clearance/upload-document')}}";

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
                dataType: 'text',  // what to expect back from the PHP script, if anything
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
                    var doc_id = parseInt(id.substring(4));
                    var newInput = $('<label class="saved_file_'+doc_id+'" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                        ' <a href="javascript:void(0)" onclick="EmptyDocFiles('+ id
                        +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    //check valid data
                    var validate_field = $('#'+vField).val();
                    if(validate_field ==''){
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }
    function makeBlank_Filevalues(id) {
        var file_id = id;
//        alert(file_id);
        document.getElementById(file_id +'_file').value = '';
        document.getElementById(file_id).value = '';
       var label= document.getElementById("label_" + file_id);
       label.remove();
        var list = document.getElementById(file_id+'_file_preview');
        list.removeChild(list.childNodes[0]);
//        $('.saved_file_' + id).html('');
//        $('.span_validate_field_' + id).html('');
    }

    function EmptyDocFiles(id) {
        var file_id = id.id;
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
            makeBlank_Filevalues(file_id);
            swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            )
        } else {
            return false;
        }
    })

        // var sure_del = confirm("Are you sure you want to delete this file?");
        // if (sure_del) {
        //     makeBlank_value(id);
        // } else {
        //     return false;
        // }
    }

    $(document).ready(function(){
        $('.upFile').hide();
        $('.showBrowse').on('click',function(){

            $('.upFile').slideToggle(100);
        });

    $('#NameClearanceForm').validate();
//    $('.readOnlyCl input,.readOnlyCl select,.readOnlyCl textarea,.readOnly').attr('readonly',true);

});


</script>
    @endsection