
    <style>


        #agrementheader p,h4{
            margin:0px;
        }
        #directorlist{
            margin-top:20px;
            margin-bottom: 50px;
        }
        #agrementfooter{
            margin-bottom: 50px;
        }
        #signature p{
            margin:0px;
        }

    </style>

    <div class="col-md-12">
        <div class="box"  id="inputForm">
            <div class="box-body">
                {!! Session::has('success') ? '
                <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
                ' : '' !!}
                {!! Session::has('error') ? '
                <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
                ' : '' !!}
                <div class="panel panel-info">
                    <div class="panel-heading"><h5><b>Foreign Form XII</b></h5></div>
                    <div class="panel-body">
                        <fieldset>
                            {!! Form::open(['url' => '/new-reg-foreign/save-reg-objecive','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'objectiveform']) !!}
                            <input type="hidden" id="viewMode" name="viewMode" value="{{ $viewMode }}">
                            <input type="hidden" name="app_id" value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id" />
                            <input type="hidden" name="selected_file" id="selected_file" />
                            <input type="hidden" name="validateFieldName" id="validateFieldName" />
                            <input type="hidden" name="isRequired" id="isRequired" />
                            <input type="hidden" id="SERVICE_ID" name="SERVICE_ID" value="{{ $process_type_id }}">


                            <div class="panel panel-info">
                                <div class="panel-heading"><h5 class="text-center"><b>Memorandum of Association</b></h5></div>
                                <div class="panel-body">
                                    <p>
                                        *Required Information For Complete submission
                                    </p>
                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            A. General Infromation
                                        </div>
                                        <div class="panel-body">
                                            <span>1.Entity Name</span><span style="margin-left: 200px;">{{\App\Modules\NewRegForeign\Controllers\GetCompanyForeignController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}</span><br>
                                            <span>2.Entity Type</span><span style="margin-left: 200px;">{{$entityType}}</span>
                                        </div>
                                    </div>

                                    <div class="panel panel-info">
                                        <div class="panel-heading">
                                            B. Objectives
                                        </div>
                                        <div class="panel-body">
                                            <p>
                                                The Objects for which the company is established are all or any of the following
                                                (all the objects will be implemented after obtaining necessary permission form the
                                                Govemmment/concerned authority/competent authority before commencement of the business);
                                            </p>
                                            <strong class="text-success">NB: Each objective must be less than 1000(One Thousand) character. [ Declared by RJSC ]</strong>
                                            <br><br>

                                            <table class="table table-bordered" id="tableobjectives">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sl</th>
                                                        <th class="text-center">Objectives</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="objectives">
                                                @if(count($objectives)>0)
                                                    <?php $i = 1; ?>
                                                    @foreach($objectives as $key=>$value)
                                                        <tr>
                                                            <td  class="text-center">
                                                                <div class="form-group">
                                                                    <div class="checkbox">
                                                                        <label>
                                                                            {!! Form::checkbox('records',$i,null, null) !!}
                                                                            <span class="sNo">{{$i}}</span>
                                                                        </label>
                                                                    </div>
                                                            <td >
                                                                <div class="form-group clearfix">
                                                                    <div class="col-md-12">
                                                                        <div class="col-md-12">


                                                                            {!! Form::text('objective['.$i.']',$value->objective, ['maxlength'=>'1000', 'class' => 'form-control objective  required','required' => 'required']) !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                        </tr>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td  class="text-center">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        {!! Form::checkbox('records',1,null, null) !!}
                                                                        <span class="sNo">1</span>
                                                                    </label>
                                                                </div>
                                                        <td >
                                                            <div class="form-group clearfix">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12">


                                                                        {!! Form::text('objective[1]','', ['maxlength'=>'1000', 'class' => 'form-control  required','required' => 'required']) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endif

                                                </tbody>
                                            </table>

                                                <div class="col-md-6 col-md-offset-4">
                                                    <button type="button" id="btnaddobject" class="btn btn-info btn-xs col-md-4">Add Objectives</button>
                                                    <button type="button" id="btnremoveobject" style="margin-left: 3px;" class="btn btn-danger btn-xs col-md-4">Remove Objectives</button>
                                                </div>
                                        </div>
                                    </div>



                                </div>
                            </div>

                            <div>
                                <h5><b>Last and Fixed Objective</b></h5>
                                @foreach($moa_default_clause as $value)

                                <p>(*).{{$value->name}}</p>
                                @endforeach

                            </div>

                            @if(ACL::getAccsessRight('NewReg','-E-'))
                                <div class="">
                                    <div class="col-md-6">
                                        <button class="btn btn-info" name="actionBtn" id="draft" value="draft" type="submit">Save as Draft</button>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <button class="btn btn-success" name="actionBtn" id="save" value="submit" type="submit">Save and continue</button>
                                    </div>
                                </div>
                            @endif
                            {!! Form::close() !!}

                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {

            $('#objectiveform').validate();

            $('#btnaddobject').click(function () {
                var count = $('#tableobjectives tr').length;

                if (count === 8) {
                    alert('maximum added 7 rows');
                    return false
                }
                var html_code = "<tr id='row" + count + "'>";
                html_code += '<td  class="text-center"><div class="form-group">';
                html_code += '<div class="checkbox">';
                html_code += '<label>';
                html_code += '{!! Form::checkbox('records',1,null, null) !!}';
                html_code += '<span class="sNo">' + count + '</span>';
                html_code += '</div>';
                html_code += '<td ><div class="form-group clearfix"> <div class="col-md-12"><div class="col-md-12">';

                html_code += '<input type="text" class="form-control  required" required="required" name="objective['+count+']"/>';
                html_code += '{!! $errors->first('','<span class="help-block">:message</span>') !!}';
                html_code += '</div></div> </div> </td>';
                html_code += "</tr>";
                $('#objectives').append(html_code);
            });
            $(document).on('click', '#btnremoveobject', function () {
                if (jQuery('input:checkbox:checked').length > 0) {
                    var count = $('#tableobjectives tr').length;
                    if (count > 2) {
                        jQuery('input:checkbox:checked').parents("tr").remove();
                        arrangeSno();
                    }

                } else {
                    alert('Please Select A Checkbox!!');
                }

            });
            function arrangeSno() {
                var i = 0;
                $('#tableobjectives tr').each(function () {
                    $(this).find(".sNo").html(i);
                    i++;
                });

            }
        });
    </script>

    <script>

        $(document).ready(function () {
            $(document).on('click','#draft',function () {
                $('#objectiveform').validate().cancelSubmit = true;;
            });
            $('#objectiveform').validate();
        });
    </script>

