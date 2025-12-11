<script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
<script src="{{ asset("assets/scripts/apicall.js?v=1") }}" type="text/javascript"></script>


<script>
    {{----step js calling here---}}
    $(document).ready(function () {

        var form = $("#NOCexiting").show();
        form.find('#submitForm').css('display', 'none');
        form.steps({
            headerTag: "h3",
            bodyTag: "fieldset",
            transitionEffect: "slideLeft",
            onStepChanging: function (event, currentIndex, newIndex) {
                // bank challan fee

                // return true;

                // Always allow previous action even if the current form is not valid!
                if (currentIndex > newIndex) {
                    return true;
                }
                // return true;
                // Needed in some cases if the user went back (clean up)
                if (currentIndex < newIndex) {
                    // To remove error styles
                    form.find(".body:eq(" + newIndex + ") label.error").remove();
                    form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
                }
                form.validate().settings.ignore = ":disabled,:hidden";
                return form.valid();
            },
            onStepChanged: function (event, currentIndex, priorIndex) {
                if (currentIndex == 3) {
                    form.find('#submitForm').css('display', 'block');
                } else {
                    form.find('#submitForm').css('display', 'none');
                    form.find('#save_as_draft').css('display', 'block');
                }
            },
            onFinishing: function (event, currentIndex) {
                form.validate().settings.ignore = ":disabled";
                return form.valid();
            },
            onFinished: function (event, currentIndex) {
                errorPlacement: function errorPlacement(error, element) {
                    element.before(error);
                }
            }
        });

        var popupWindow = null;
        $('.finish').on('click', function (e) {
            form.validate().settings.ignore = ":disabled,:hidden";
            if (form.valid()) {
                $('body').css({"display": "none"});
                popupWindow = window.open('<?php echo URL::to('licence-application/preview?form=NOCexiting@3'); ?>');
            } else {
                return false;
            }
        });

    });

    {{----end step js---}}




    function ValidateEmail() {
        var email = document.getElementById("txtEmail").value;
        var lblError = document.getElementById("lblError");
        lblError.innerHTML = "";
        var expr = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        if (!expr.test(email)) {
            lblError.innerHTML = "Invalid email address.";
        }
    }

    $(document).ready(function () {
        $("#council").change(function (e) {
            let council = this.value.split('@')[0];
            if (council == 'city_corporation') {
                $("#city_corporation_div_id").show();
                $("#city_corporation").addClass('required');

                $("#town_council_div_id").hide();
                $("#town_council").val('');
                $("#union_council_div_id").hide();
                $("#union_council").val('');
                $("#town_council").removeClass('required');
                $("#union_council").removeClass('required');

            }else if (council == 'town_council') {
                $("#town_council_div_id").show();
                $("#town_council").addClass('required');

                $("#city_corporation_div_id").hide();
                $("#city_corporation").val('');
                $("#union_council_div_id").hide();
                $("#union_council").val('');
                $("#city_corporation").removeClass('required');
                $("#union_council").removeClass('required');
            }else if (council == 'union_council') {
                $("#union_council_div_id").show();
                $("#union_council").addClass('required');

                $("#city_corporation_div_id").hide();
                $("#city_corporation").val('');
                $("#town_council_div_id").hide();
                $("#town_council").val('');
                $("#city_corporation").removeClass('required');
                $("#town_council").removeClass('required');
            }else {
                $("#city_corporation_div_id").hide();
                $("#town_council_div_id").hide();
                $("#union_council_div_id").hide();
                $("#city_corporation").removeClass('required');
                $("#town_council").removeClass('required');
                $("#union_council").removeClass('required');
                $("#city_corporation").val('');
                $("#town_council").val('');
                $("#union_council").val('');
            }
        });

        $("form#NOCexiting").validate({
            errorPlacement: function () {
                return false;
            }
        });

        var today = new Date();
        var yyyy = today.getFullYear();
        $('.datepicker').datetimepicker({
            viewMode: 'days',
            format: 'DD-MMM-YYYY',
            widgetPositioning: {
                vertical: 'bottom',
                horizontal: 'left'
            }
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
        });

        $('#eleSubstation').hide();
        $('#electrical_station').click(function(){
            if($(this).prop("checked")) {
                $('#eleSubstation').show();
            }else{
                $('#eleSubstation').hide();
            }
        });


    });

    function uploadDocument(targets, id, vField, isRequired) {
        var inputFile = $("#" + id).val();
        if (inputFile == '') {
            $("#" + id).html('');
            document.getElementById("isRequired").value = '';
            document.getElementById("selected_file").value = '';
            document.getElementById("validateFieldName").value = '';
            document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
            if ($('#label_' + id).length)
                $('#label_' + id).remove();
            return false;
        }

        try {
            document.getElementById("isRequired").value = isRequired;
            document.getElementById("selected_file").value = id;
            document.getElementById("validateFieldName").value = vField;
            document.getElementById(targets).style.color = "red";
            var action = "{{URL::to('/doe/upload-document')}}";
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
                url: action,
                dataType: 'text', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function (response) {
                    $('#' + targets).html(response);
                    var fileNameArr = inputFile.split("\\");
                    var l = fileNameArr.length;
                    if ($('#label_' + id).length)
                        $('#label_' + id).remove();
                    var doc_id = id;
                    var newInput = $('<label class="saved_file_' + doc_id + '" id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + ' <a href="javascript:void(0)" class="filedelete" docid="' + id + '" ><span class="btn btn-xs btn-danger"><i class="fa fa-times"></i></span> </a></b></label>');
//                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                    $("#" + id).after(newInput);
                    $('#' + id).removeClass('required');
                    //check valid data
                    document.getElementById(id).value = '';
                    var validate_field = $('#' + vField).val();
                    if (validate_field == '') {
                        document.getElementById(id).value = '';
                    }
                }
            });
        } catch (err) {
            document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
        }
    }

    $(document).on('click', '.filedelete', function () {
        var abc = $(this).attr('docid');
        var sure_del = confirm("Are you sure you want to delete this file?");
        if (sure_del) {
            document.getElementById("validate_field_" + abc).value = '';
            document.getElementById(abc).value = '';
            $('.saved_file_' + abc).html('');
            $('.span_validate_field_' + abc).html('');
        } else {
            return false;
        }
    });
    var rowCount = 0;
    function addBuildingRow(tableID, templateRow) {
        rowCount++;
        var totalrow = $('#number_of_building').val();
        var currentrow = parseInt(totalrow)+1;

        //Direct Copy a row to many times
        var x = document.getElementById(templateRow).cloneNode(true);
        x.id = "";
        x.style.display = "";
        var table = document.getElementById(tableID);
        var rowCount = $('.' + templateRow).length;
        var final_total_row = rowCount + 1;
        var lastTr = $('#' + tableID).find('.' + templateRow).last().attr('data-number');
        if (lastTr != '' && typeof lastTr !== "undefined") {
            rowCount = parseInt(lastTr) + 1;
        }
        //var rowCount = table.rows.length;
        //Increment id
        var rowCo = rowCount;
        var idText = 'rowCount' + rowCount;
        x.id = idText;
        $("#" + tableID).append(x);
        //get select box elements
        var attrSel = $("#" + tableID).find('#' + idText).find('select');
        for (var i = 0; i < attrSel.length; i++) {
            var nameAtt = attrSel[i].name;
            var idAtt = attrSel[i].id;

            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('[0]', '[' + rowCo + ']');
            attrSel[i].name = repText;
            attrSel[i].id = repTextId;

        }
        $('#number_of_building').val(currentrow);
        // default USD selection for fob_usd
        if ((tableID === 'moreInfoImd' && (templateRow === 'templateImdFull'))) {
            attrSel.prop('selectedIndex', 0);
            $("#" + tableID).find('#' + idText).find('select.usd-def').val("107");  //selected index reset
            $(".convertedUSD").text('');
        } else {  //selected index reset
            attrSel.prop('selectedIndex', 0);  //selected index reset
        }



        //get input elements
        var attrImput = $("#" + tableID).find('#' + idText).find('input');
        for (var i = 0; i < attrImput.length; i++) {
            var nameAtt = attrImput[i].name;
            var idAtt = attrImput[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('[0]', '[' + rowCo + ']');
            attrImput[i].name = repText;
            attrImput[i].id = repTextId;
        }



        //get matarials logo elements

        //get textarea elements
        var attrTextarea = $("#" + tableID).find('#' + idText).find('textarea');
        for (var i = 0; i < attrTextarea.length; i++) {
            var nameAtt = attrTextarea[i].name;
            var idAtt = attrTextarea[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('[0]', '[' + rowCo + ']');
            attrTextarea[i].name = repText;
            attrTextarea[i].id = repTextId;
            $('#' + idText).find('.readonlyClass').prop('readonly', true);
        }
        attrTextarea.val(''); //value reset

        var attrLable = $("#" + tableID).find('#' + idText).find('label');
        for (var i = 0; i < attrLable.length; i++) {
            var htmlFor = attrLable[i].htmlFor;
            //increment all array element name
            var repTextFor = htmlFor.replace('[0]', '[' + rowCo + ']');
            attrLable[i].htmlFor = repTextFor;
        }

        var attrImReq = $("#" + tableID).find('#' + idText).find('#number_of_basement_0');
        for (var i = 0; i < attrImReq.length; i++) {
            var nameAtt = attrImReq[i].name;
            var idAtt = attrImReq[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrImReq[i].name = repText;
            attrImReq[i].id = repTextId;
            // $('#'+attrImReq[i].id).attr('disable','false');
            $('#'+attrImReq[i].id).removeAttr('readonly');
        }
        var attrch = $("#" + tableID).find('#' + idText).find('#number_of_basement_check_0');
        for (var i = 0; i < attrch.length; i++) {
            var nameAtt = attrch[i].name;
            var idAtt = attrch[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrch[i].name = repText;
            attrch[i].id = repTextId;
            attrch.parent().next('label').attr('for',repTextId)
            $('#'+attrch[i].id).prop("checked", false);
            $('#'+attrch[i].id).attr('onclick','requriedField("number_of_basement_check_'+rowCo+'", "number_of_basement_'+rowCo+'")');
        }

        var attrReqme = $("#" + tableID).find('#' + idText).find('#number_of_mezzanine_0');
        for (var i = 0; i < attrReqme.length; i++) {
            var nameAtt = attrReqme[i].name;
            var idAtt = attrReqme[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrReqme[i].name = repText;
            attrReqme[i].id = repTextId;
            // $('#'+attrReqme[i].id).attr('disable','false');
            $('#'+attrReqme[i].id).removeAttr('readonly');
        }
        var attrmech = $("#" + tableID).find('#' + idText).find('#number_of_mezzanine_check_0');
        for (var i = 0; i < attrmech.length; i++) {
            var nameAtt = attrmech[i].name;
            var idAtt = attrmech[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrmech[i].name = repText;
            attrmech[i].id = repTextId;
            attrmech.parent().next('label').attr('for',repTextId)
            $('#'+attrmech[i].id).prop("checked", false);
            $('#'+attrmech[i].id).attr('onclick','requriedField("number_of_mezzanine_check_'+rowCo+'", "number_of_mezzanine_'+rowCo+'")');
        }

        var attrsimi = $("#" + tableID).find('#' + idText).find('#number_of_simi_basement_0');
        for (var i = 0; i < attrsimi.length; i++) {
            var nameAtt = attrsimi[i].name;
            var idAtt = attrsimi[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrsimi[i].name = repText;
            attrsimi[i].id = repTextId;
            // $('#'+attrsimi[i].id).attr('disable','false');
            $('#'+attrsimi[i].id).removeAttr('readonly');
        }
        var attrsimich = $("#" + tableID).find('#' + idText).find('#number_of_simi_basement_check_0');
        for (var i = 0; i < attrsimich.length; i++) {
            var nameAtt = attrsimich[i].name;
            var idAtt = attrsimich[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrsimich[i].name = repText;
            attrsimich[i].id = repTextId;
            attrsimich.parent().next('label').attr('for',repTextId)
            $('#'+attrsimich[i].id).prop("checked", false);
            $('#'+attrsimich[i].id).attr('onclick','requriedField("number_of_simi_basement_check_'+rowCo+'", "number_of_simi_basement_'+rowCo+'")');
        }

        var attrsize = $("#" + tableID).find('#' + idText).find('#size_of_each_basement_0');
        for (var i = 0; i < attrsize.length; i++) {
            var nameAtt = attrsize[i].name;
            var idAtt = attrsize[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrsize[i].name = repText;
            attrsize[i].id = repTextId;
            // $('#'+attrsize[i].id).attr('disable','false');
            $('#'+attrsize[i].id).removeAttr('readonly');
        }
        var attrsizech = $("#" + tableID).find('#' + idText).find('#size_of_each_basement_check_0');
        for (var i = 0; i < attrsizech.length; i++) {
            var nameAtt = attrsizech[i].name;
            var idAtt = attrsizech[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrsizech[i].name = repText;
            attrsizech[i].id = repTextId;
            attrsizech.parent().next('label').attr('for',repTextId)
            $('#'+attrsizech[i].id).prop("checked", false);
            $('#'+attrsizech[i].id).attr('onclick','requriedField("size_of_each_basement_check_'+rowCo+'", "size_of_each_basement_'+rowCo+'")');
        }

        var attrEme = $("#" + tableID).find('#' + idText).find('#vol_each_mezzainine_0');
        for (var i = 0; i < attrEme.length; i++) {
            var nameAtt = attrEme[i].name;
            var idAtt = attrEme[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrEme[i].name = repText;
            attrEme[i].id = repTextId;
            // $('#'+attrEme[i].id).attr('disable','false');
            $('#'+attrEme[i].id).removeAttr('readonly');
        }
        var attrEmech = $("#" + tableID).find('#' + idText).find('#vol_each_mezzainine_check_0');
        for (var i = 0; i < attrEmech.length; i++) {
            var nameAtt = attrEmech[i].name;
            var idAtt = attrEmech[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrEmech[i].name = repText;
            attrEmech[i].id = repTextId;
            attrEmech.parent().next('label').attr('for',repTextId)
            $('#'+attrEmech[i].id).prop("checked", false);
            $('#'+attrEmech[i].id).attr('onclick','requriedField("vol_each_mezzainine_check_'+rowCo+'", "vol_each_mezzainine_'+rowCo+'")');
        }

        var attrbase = $("#" + tableID).find('#' + idText).find('#size_of_each_simi_basement_0');
        for (var i = 0; i < attrbase.length; i++) {
            var nameAtt = attrbase[i].name;
            var idAtt = attrbase[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrbase[i].name = repText;
            attrbase[i].id = repTextId;
            // $('#'+attrbase[i].id).attr('disable','false');
            $('#'+attrbase[i].id).removeAttr('readonly');
        }
        var attrbasech = $("#" + tableID).find('#' + idText).find('#size_of_each_simi_basement_check_0');
        for (var i = 0; i < attrbasech.length; i++) {
            var nameAtt = attrbasech[i].name;
            var idAtt = attrbasech[i].id;
            //increment all array element name
            var repText = nameAtt.replace('[0]', '[' + rowCo + ']');
            var repTextId = idAtt.replace('_0', '_' + rowCo);
            attrbasech[i].name = repText;
            attrbasech[i].id = repTextId;
            attrbasech.parent().next('label').attr('for',repTextId)
            $('#'+attrbasech[i].id).prop("checked", false);
            $('#'+attrbasech[i].id).attr('onclick','requriedField("size_of_each_simi_basement_check_'+rowCo+'", "size_of_each_simi_basement_'+rowCo+'")');
        }

        //value reset


        if(!$("input[type=checkbox]")){
            attrImput.val('');
        }

        //Class change by btn-danger to btn-primary
        $("#" + tableID).find('#' + idText).find('.topdata').css('display','block');
        $("#" + tableID).find('#' + idText).find('.topbutton').removeClass('btn-primary').addClass('btn-danger').attr('onclick', 'removeTableRow("' + tableID + '","' + idText + '")');
        $("#" + tableID).find('#' + idText).find('.topbutton > .fa').removeClass('fa-plus').addClass('fa-times');
        $('#' + tableID).find('.' + templateRow).last().attr('data-number', rowCount);


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
            }
            else {
                $(this).addClass('error');
                return false;
            }
        }).on('paste', function (e) {
            var $this = $(this);
            setTimeout(function () {
                $this.val($this.val().replace(/[^0-9]/g, ''));
            }, 5);
        });



    } //********************************* end of addTableRow() **************************************/

    function removeTableRow(tableID, removeNum) {
       var totalrow = $('#number_of_building').val();
       var currentrow = totalrow - 1;
        $('#' + tableID).find('#' + removeNum).remove();
        $('#number_of_building').val(currentrow);
    }

    function requriedField(id,fieldid){
        var checkdata = $('#'+id).prop("checked");
        if(checkdata == true) {
            $('#'+fieldid).val('');
            $('#'+fieldid).removeClass('required');
            $('#'+fieldid).removeClass('error');
            // $('#'+fieldid).attr('disabled','disabled');
            $('#'+fieldid).attr('readonly',true);
        }else{
            $('#'+fieldid).attr('readonly',false);
            $('#'+fieldid).addClass('required');
            // $('#'+fieldid).removeAttr('disabled');
        }

    }



    $(document).ready(function () {

        $('body').on('click', '.reCallApi', function () {
            var id = $(this).attr('data-id');
            $("#" + id).trigger('keydown');
            $(this).remove();
        });

        $(function () {
            token = "{{$token}}";
            tokenUrl = '/bfscd-noc-exiting/get-refresh-token';

            $('#safety_firm').keydown()
            $('#owner_division').keydown()
            $('#proposed_building_division').keydown()
            $('#city_corporation').keydown()
            $('.building_construction').keydown()
            $('.building_use').keydown()
            $('.building_use_type').keydown()
            $('.floor').keydown()
            $('.electric_line').keydown()
            $('#nearby_division').keydown()
            $('#council').keydown()
        });

        var apiHeaders = [
            {
                key: "Content-Type",
                value: 'application/json'
            },
            {
                key: "client-id",
                value: "{{ $client_id }}"
            },
            {
                key: "agent-id",
                value: "{{ $agent_id }}"
            },
        ]

        $('#safety_firm').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/safety-firms";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.floor').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/floors";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "bn_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.electric_line').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/overhead-electric-line";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#owner_division').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/divisions";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        })

        $("#owner_division").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#owner_district").html('<option value="">Please Wait...</option>')
            var owner_division_id =  $('#owner_division').val().split('@')[0];
            if (owner_division_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/districts/" + owner_division_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "owner_district";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "division_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#owner_district").html('<option value="">Select Division First</option>')
                $(e).next().hide()
            }

        });

        $("#owner_district").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#owner_thana").html('<option value="">Please Wait...</option>')
            var owner_district_id = $('#owner_district').val().split('@')[0];
            if (owner_district_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/thanas/" + owner_district_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "owner_thana";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "district_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#owner_thana").html('<option value="">Select District First</option>')
                $(e).next().hide()
            }

        });

        $('#proposed_building_division').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/divisions";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $("#proposed_building_division").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#proposed_building_district").html('<option value="">Please Wait...</option>')
            var proposed_building_division_id = $('#proposed_building_division').val().split('@')[0];
            if (proposed_building_division_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/districts/" + proposed_building_division_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "proposed_building_district";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "division_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#proposed_building_district").html('<option value="">Select Division First</option>')
                $(e).next().hide()
            }

        });

        $("#proposed_building_district").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#proposed_building_thana").html('<option value="">Please Wait...</option>')
            var proposed_building_district_id = $('#proposed_building_district').val().split('@')[0];
            if (proposed_building_district_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/thanas/" + proposed_building_district_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "proposed_building_thana";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "district_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#proposed_building_thana").html('<option value="">Select District First</option>')
                $(e).next().hide()
            }

        });

        $('#council').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/councils";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "Id";//dynamic id for callback
            let element_name = "Value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#city_corporation').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/city-corporations";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "bn_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.building_construction').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/building-creating-types";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "bn_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.building_use').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/building-classes";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "bn_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('.building_use_type').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/building-usages";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "bn_name";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $('#nearby_division').on('keydown', function (el) {
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }
            $(this).after('<span class="loading_data">Loading...</span>');
            let e = $(this);
            let api_url = "{{$bfscd_exiting_service_url}}/info/divisions";
            let selected_value = ''; // for callback
            let calling_id = $(this).attr('id');// for callback
            let element_id = "id";//dynamic id for callback
            let element_name = "value";//dynamic name for callback
            let element_calling_id = "";//dynamic name for callback
            let data = '';//Third option to make id
            let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
            let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id]; // for callback
            apiCallGet(e, options, apiHeaders, callbackResponse, arrays);
        });

        $("#nearby_division").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#nearby_district").html('<option value="">Please Wait...</option>')
            var nearby_division_id = $('#nearby_division').val().split('@')[0];
            if (nearby_division_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/districts/" + nearby_division_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "nearby_district";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "division_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#proposed_building_district").html('<option value="">Select Division First</option>')
                $(e).next().hide()
            }

        });

        $("#nearby_district").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#nearby_tahan").html('<option value="">Please Wait...</option>')
            var nearby_district_id = $('#nearby_district').val().split('@')[0];
            if (nearby_district_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/thanas/" + nearby_district_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "nearby_tahan";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "district_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#proposed_building_thana").html('<option value="">Select District First</option>')
                $(e).next().hide()
            }

        });

        $("#nearby_tahan").on("change", function (el) {
            let e = $(this);
            let key = el.which
            if (typeof key !== "undefined") {
                return false
            }

            $(e).next().hide()
            $(this).after('<span class="loading_data">Loading...</span>')
            $("#nearby_fire_station").html('<option value="">Please Wait...</option>')
            var nearby_district_id = $('#nearby_district').val().split('@')[0];
            var nearby_tahan_id = $('#nearby_tahan').val().split('@')[0];
            if (nearby_tahan_id) {
                let e = $(this);
                let api_url = "{{$bfscd_exiting_service_url}}/info/fire-stations/" + nearby_district_id + "/" + nearby_tahan_id;
                let selected_value = '';
                let calling_id = $(this).attr('id');// for callback
                let dependant_select_id = "nearby_fire_station";
                let element_id = "id";//dynamic id for callback
                let element_name = "bn_name";//dynamic name for callback
                let element_calling_id = "thana_id";//dynamic name for callback
                let data = '';//Third option to make id
                let options = {apiUrl: api_url, token: token, data: data, tokenUrl: tokenUrl};// for lib
                let arrays = [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]; // for callback

                apiCallGet(e, options, apiHeaders, dependantCallbackResponse, arrays);

            } else {
                $("#proposed_building_thana").html('<option value="">Select District First</option>')
                $(e).next().hide()
            }

        });

        $('.number_0_to_9').on('keyup', function (e) {
            var number_range = $(this).val();
            if (number_range <= 9 && number_range >= 0) {
                $(this).removeClass('error')
                $(this).next("span").remove();
            } else {
                $(this).addClass('error')
                $(this).after("<span style='color: darkred;'>Please enter number between 0 to 9.</span>");
                $(this).val('')
            }
        })

        $('.mobile_number_validation').on('blur', function(e){

            var mobile_validation_err = '';
            var mobile_number = $(this).val();
            var first_digit = mobile_number.substring(0, 1);
            var first_two_digit = mobile_number.substring(0, 2);
            var first_four_digit = mobile_number.substring(0, 5);
            var regexp = /^[+]*[(]{0,1}[0-9]{1,3}[)]{0,1}[-\s\./0-9]*$/g;
            // if first two digit is 01
            if(!mobile_number.match(regexp)){
                mobile_validation_err = 'Mobile number is invalid';
            }else if(mobile_number.length<11){
                mobile_validation_err = 'Mobile number should be minimum 11 digit';
            }else if(first_two_digit=='01'){
                if(mobile_number.length!=11){
                    mobile_validation_err = 'Mobile number should be 11 digit';
                }
            }
            // if first two digit is +880
            else if(first_four_digit=='+8801'){
                if(mobile_number.length!=14){
                    mobile_validation_err = 'Mobile number should be 14 digit';
                }
            }
            // if first digit is only
            else if(first_digit=='+'){
                // Mobile number will be ok
            } // matching pattern
            else{
                mobile_validation_err = 'Please enter valid Mobile number';
            }

            if(mobile_validation_err.length>0){
                $(this).addClass('error')
                $('.mobile_number_error').html(mobile_validation_err);
            }else{
                $(this).removeClass('error')
                $('.mobile_number_error').html('');
            }

        });
    });


    function dependantCallbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id, dependant_select_id]) {
        var option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name]
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + dependant_select_id).html(option)
        $("#" + calling_id).next().hide()
    }


    function callbackResponse(response, [calling_id, selected_value, element_id, element_name, element_calling_id]) {
        let option = '<option value="">Select One</option>';
        if (response.responseCode === 200) {
            $.each(response.data, function (key, row) {
                let id = row[element_id] + '@' + row[element_name];
                let value = row[element_name];
                if (selected_value.split('@')[0] == id.split('@')[0]) {
                    option += '<option selected="true" value="' + id + '">' + value + '</option>'
                } else {
                    option += '<option value="' + id + '">' + value + '</option>'
                }
            })
        } else {
            console.log(response.status)
        }
        $("#" + calling_id).html(option)
        $("#" + calling_id).next().hide()
    }

</script>