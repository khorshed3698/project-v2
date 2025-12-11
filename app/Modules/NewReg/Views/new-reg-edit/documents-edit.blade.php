<section class="content">
    <input type="hidden" name="selected_file" id="selected_file"/>
    <input type="hidden" name="validateFieldName" id="validateFieldName"/>
    <input type="hidden" name="isRequired" id="isRequired"/>
    <div class="row">
        {!!session()->has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('error') .'</div>' : '' !!}
    </div>

    <div class="row">
        {!!session()->has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. session('success') .'</div>' : '' !!}
    </div>

    <div class="panel panel-info">
        <div class="panel-heading">
            <span>Documents Details</span>
            <span class="pull-right">
                {{--{{$applicationId}}--}}

            </span>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-bordered" width="100%">
                        <thead class="bg-primary">
                        <tr>
                            <th scope="col" width="15%" rowspan="2" class="text-center">Form name</th>
                            <th scope="col" width="35%" rowspan="2" class="text-center">Description</th>
                            <th scope="col" width="40%" colspan="3" class="text-center">Action</th>
                        </tr>
                        <tr>
                            <th scope="col" width="10%" class="text-center"></th>
                            <th scope="col" width="22%" class="text-center">Upload</th>
                            <th scope="col" width="8%" class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php $i = 0;?>

                        @foreach($Rjsc_NrForms as $info)
                            @if($info->is_extra == 0)

                                <tr>
                                    <td>{!! $info->name !!}</td>
                                    <td>{!! $info->description !!}</td>
                                    <td style="">
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileStatus($info->id, $applicationId))
                                                <a form_id="{{ Encryption::encodeId($info->id)}}"
                                                   href="/new-reg/get-pdf/{{ Encryption::encodeId($info->id)}}/{{ Encryption::encodeId($applicationId)}}"
                                                   class="btn btn-danger btn-xs documentUrl">
                                                    <i class="fa fa-genderless"></i> <strong>Regenerate</strong>
                                                </a>
                                            @else
                                                <a form_id="{{ Encryption::encodeId($info->id)}}"
                                                   href="/new-reg/get-pdf/{{ Encryption::encodeId($info->id)}}/{{ Encryption::encodeId($applicationId)}}"
                                                   class="btn btn-danger btn-xs documentUrl">
                                                    <i class="fa fa-genderless"></i> <strong>Generate</strong>
                                                </a>

                                            @endif
                                        @else
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileStatus($info->id, $applicationId))
                                                <input type="file"
                                                       data-app_id="{{Encryption::encodeId($applicationId)}}"
                                                       data-ref_id="{{Encryption::encodeId($info->id)}}"
                                                       data-form_name="{{$info->name}}"
                                                       onchange="readFiles(this)" id="file" class="file">
                                            @else
                                            @endif
                                        @else
                                            <center><b>Uploaded and saved successfully</b></center>
                                        @endif
                                    </td>
                                    <td>
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileUploadedStatus($info->id, $applicationId))
                                                <center>
                                                    <b> Uploaded</b>
                                                </center>
                                            @endif
                                        @endif
                                        <center><b class="upload_status"></b></center>
                                    </td>
                                </tr>

                            @endif

                            <?php $i++;?>

                        @endforeach

                        @foreach($Rjsc_NrForms as $info)
                            @if($info->is_extra == 1)

                                <tr>
                                    <td>{!! $info->name !!}</td>
                                    <td>{!! $info->description !!}</td>
                                    <td style="">

                                    </td>
                                    <td>
                                        <input type="file" data-app_id="{{Encryption::encodeId($applicationId)}}"
                                               data-ref_id="{{Encryption::encodeId($info->id)}}"
                                               data-form_name="{{$info->name}}"
                                               onchange="readFiles(this)" id="file" class="file">
                                    </td>
                                    <td>
                                        @if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))
                                            @if(\App\Libraries\CommonFunction::getFileUploadedStatus($info->id, $applicationId))
                                                <center>
                                                    <b> Uploaded</b>
                                                </center>
                                            @endif
                                        @endif
                                        <center><b class="upload_status"></b></center>
                                    </td>
                                </tr>

                            @endif

                            <?php $i++;?>

                        @endforeach

                        </tbody>
                    </table>
                    {!! Form::open(array('url' => '/new-reg/new-reg-page/final-submit','method' => 'post', 'id' => 'd','role'=>'form')) !!}
                    {{--  need to add after --}}



                    <div class="pull-left">
                        <button type="submit" class="btn btn-info btn-md cancel"
                                value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                        </button>
                    </div>

                    <div class="pull-right" style="padding-left: 1em;">
                          
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md"
                                        value="Submit" name="actionBtn">Submit
                                </button>
                          
                        </div>

                    @if(ACL::getAccsessRight('NewReg','-E-'))
                        <div class="pull-right" style="padding-left: 1em;">
                            <!-- @if($authorizeCapitalValidate->capital_validate != 1) -->
                                <button type="submit" id="submitForm" style="cursor: pointer;"
                                        class="btn btn-success btn-md"
                                        value="Submit" name="actionBtn">Submit
                                </button>
                          <!--   @else
                                <div class="alert alert-danger">
                                    <span href="#" data-toggle="tooltip"
                                          title="Autorised Capital must equal or less than Value of each Share X Total Subscribed Shares!"><strong>Authorised Capital Not Valid !</strong></span>
                                </div>
                            @endif -->
                        </div>
                    @endif
                    {!! Form::close() !!}


                    {{--@if(\App\Libraries\CommonFunction::checkFileSubmission($applicationId))--}}
                    {{--<a style="margin-right: 120px; " href="/new-reg/get-pdf/check-all-pdf-files-up/{{ Encryption::encodeId($applicationId)}}" class="pull-right btn btn-success btn-sm documentUrl">--}}
                    {{--<i class="fa fa-save"></i> <strong>Save</strong>--}}
                    {{--</a><br><br>--}}
                    {{--@endif--}}


                </div>

                @include('NewReg::preview.doc-tab')

            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function () {
        $('#is_additional_attachment').click(function () {
            if($(this).is(':checked')){
                $('#additional_attachment').removeClass('hidden')
            }else{
                $('#additional_attachment').addClass('hidden')
            }
        })

        @if($appInfo->is_additional_attachment == 1)
        $('#additional_attachment').removeClass('hidden')
        @endif
    })

    function readFiles(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                uploadFilesUpload(e.target.result, input);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // to save uploaded files in DB
    function uploadFilesUpload(base64_img, input, target) {

        var _token = $('input[name="_token"]').val();
        var ref_id = $(input).data("ref_id");
        var form_name = $(input).data("form_name");
        var app_id = $(input).data("app_id");


        $("#upload_progress").removeClass('hidden');

        $.ajax({
            url: '{{url('new-reg/new-store/upload')}}',
            type: 'post',
            data: {
                _token: _token,
                app_id: app_id,
                ref_id: ref_id,
                form_name: form_name,
                base64_img: base64_img
            },
            dataType: 'json',
            success: function (response) {
                // success
                if (response.responseCode === 1) {

                    alert('File Upload successfully');
                    // input.remove();
                    //$(input).after(" Uploaded");
                    $(input).parent().parent().find('.upload_status').empty().html('<b>Uploaded</b>');
                } else {
                    // $("#" + type + "_err").html(response.data);
                    // $('button[type="submit"]').addClass('disabled');
                    // $(input).parent().find('.upload_flags').val(0);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);

            },
            beforeSend: function (xhr) {
                console.log('before send');
            },
            complete: function () {
                // $("#upload_progress").addClass('hidden');
            }
        });
    }

    // Add table Row script
    function addTableRow1(tableID, templateRow) {
        //rowCount++;
        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('#' + tableID).find('tr').length - 1;
        var lastTr = $('#' + tableID).find('tr').last().attr('data-number');
        var production_desc_val = $('#' + tableID).find('tr').last().find('.production_desc_1st').val();
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + tableID + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        //edited by ishrat to solve select box id auto increment related bug
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']'); //increment all array element name
            attrSel[i].name = repText;
        }
        attrSel.val(''); //value reset
        // end of  solving issue related select box id auto increment related bug by ishrat

        //get input elements
        var attrInput = $("#" + tableID).find('#' + idText).find('input');
        // var attrInputFile = $("#" + tableID).find('#' + idText).find('input:file');
        var attrInputw = $("#" + tableID).find('#' + idText).find('.preview_0');
        $("#" + tableID).find('#' + idText).find('.span_validate_field_0').html('');
        for (var i = 0; i < attrInputw.length; i++) {
            attrInputw[i].setAttribute('id','preview_'+rowCo+'');

        }
        for (var i = 0; i < attrInput.length; i++) {
            var nameAtt = attrInput[i].name;
            var nameAtt1 = attrInput[i].id;
            var attrC = "uploadDocument('preview_"+rowCo+"', this.id, 'validate_field_"+rowCo+"')";
            attrInput[i].setAttribute('onchange',attrC);
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repText1 = nameAtt.replace('[0]', '_' + rowCo);
            var repText11 = nameAtt.replace('validate_field_0', 'validate_field_' + rowCo);
            attrInput[i].name = repText;
            attrInput[i].id = repText1;
        }
        var attrInputText = $("#" + tableID).find('#' + idText).find('input:text');
        for (var i = 0; i < attrInputText.length; i++) {
            attrInputText[i].setAttribute('onchange','');

        }
        // var attrInputw = $("#" + tableID).find('#' + idText).find('.preview_0');

        attrInput.val(''); //value reset
        var attrInputHiden = $("#" + tableID).find('#' + idText).find('input:hidden');
        for (var i = 0; i < attrInputHiden.length; i++) {
            attrInputHiden[i].setAttribute('id','validate_field_'+rowCo+'');
            attrInputHiden[i].setAttribute('name','validate_field_'+rowCo+'');
        }

        $("#" + tableID).find('#' + idText).find('.addTableRows').removeClass('btn-primary').addClass('btn-danger')
            .attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.addTableRows > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('tr').last().attr('data-number', rowCount);

        $("#" + tableID).find('#' + idText).find('.onlyNumber').on('keydown', function (e) {
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
                $this.val($this.val().replace(/[^.0-9]/g, ''));
            }, 4);
        });




    } // end of addTableRow() function



    // Remove Table row script
    function removeTableRow(tableID, removeNum) {
        $('#' + tableID).find('#' + removeNum).remove();
        calculateListOfMachineryTotal('machinery_imported_total_value', 'machinery_imported_total_amount');
        calculateListOfMachineryTotal('machinery_local_total_value', 'machinery_local_total_amount');
    }

    //--------File Upload Script Start----------//
    function uploadDocument(targets, id, vField, isRequired) {
        console.log(targets);
        // return false;
        var inputFile =  $("#" + id).val();


        // try{

            // document.getElementById(targets).style.color = "red";
            var action = "{{url('/new-reg/upload-document')}}";

            $("#" + targets).html('Uploading....');
            var file_data = $("#" + id).prop('files')[0];
            console.log(file_data);
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
                    //console.log(response);
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    // var doc_id = parseInt(id.substring(5));
                    // var newInput = $('<label class="saved_file_'+doc_id+'" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] +
                    //     ' <a href="javascript:void(0)" onclick="EmptyFile('+ doc_id
                    //     +')"><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
                    //var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    // $("#" + id).after(newInput);
                    //check valid data
                    var validate_field = $('#'+vField).val();
                    if(validate_field ==''){
                        document.getElementById(id).value = '';
                    }
                }
            });
        // } catch (err) {
        //     alert('wrong');
        //     // document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        // }
    }
    //--------File Upload Script End----------//

    var i = 1;
    $('input[type="file"]').each(function(){
        $(this).addClass('file'+i);
        $(this).removeClass('file');
        i++;
    });


    $(document).ready(function(){
        $('#file').click(function(){
            $('input[type="file"]').each(function(){
                var thisFile = $(this);
                if(thisFile[0].files[0].size >3000000){
                    alert('File large!');
                }
            });
        });
    });
</script>
