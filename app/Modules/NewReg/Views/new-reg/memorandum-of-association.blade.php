
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
                    <div class="panel-heading"><h5><b>Form XII</b></h5></div>
                    <div class="panel-body">

                        <fieldset>
                            {!! Form::open(['url' => '/save-reg-objecive','method' => 'post','files'=>true,'id'=>'objectiveform','enctype='> 'multipart/form-data']) !!}
                            <input type ="hidden" name="app_id" value="{{Session::get('current_app_id')}}">
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
                                            <span>1.Entity Name</span><span style="margin-left: 200px;">{{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids)}}</span><br>
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
                                            <table class="table table-bordered" id="tableobjectives">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sl</th>
                                                        <th class="text-center">Objectives</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="objectives">
                                                    <tr>
                                                        <td  class="text-center">
                                                            <div class="form-group">
                                                                <div class="checkbox">
                                                                    <label>
                                                        {!! Form::checkbox('records',1,null, null) !!}
                                                                        <span>1</span>
                                                                    </label>
                                                        </div>
                                                        <td >
                                                            <div class="form-group clearfix">
                                                                <div class="col-md-12">
                                                                    <div class="col-md-12">
                                                                {!! Form::text('objective[]','', ['maxlength'=>'1000', 'class' => 'form-control  required','required'=>'required']) !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>

                                                    </tr>
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
                                <p>(*).To borrow or raise memory or secure the payment of money on such team as the company may consider
                                expedient,including by issue or sale of shares, stock ,bounds , debentures , other securities and obligations
                                perpetual or terminable and or redeemable or otherwise and to secure the same by mortgage,change or linen on
                                the undertaking and all or any of the real and personal property and assets,present or future and all or any of the
                                uncalled capital for the time being of the company, any to isssue and create  at par or at a permium per discount,
                                and for such consideration and win and subject to such rights, power privileges and conditions as may be thought
                                fit, mortgage, charges, memoranda or deposit, debentures or debenture stock, either permanent or redeemable or
                                repayable, and collaterally or future to secure an securities of the company by a trust deed or other assurance
                                </p>
                                <p>
                                    (*).To mortgage the property and assets of the company as securities for loans and/or  any credit
                                    facilities to be given to any associate company or companies or third party and also to give guarantee
                                    securing liabilities of such associate company or companies and/or third party.
                                </p>
                                <p>
                                    (*).This company can do any lawful business for making profit.
                                </p>
                            </div>


                            <div class="">
                                <div class="col-md-6">
                                    <button class="btn btn-info" name="actionBtn" id="draft" value="draft" type="submit">Save as Draft</button>
                                </div>
                                <div class="col-md-6 text-right">
                                    <button class="btn btn-success" name="actionBtn" id="save" value="submit" type="submit">Save and continue</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function(){
            $('#objectiveform').validate();

            $('#btnaddobject').click(function(){
                var count = $('#tableobjectives tr').length;
                if(count === 8){
                    alert('maximum added 7 rows');
                    return false
                }
                var html_code = "<tr id='row"+count+"'>";
                html_code += '<td  class="text-center"><div class="form-group">';
                html_code+='<div class="checkbox">';
                html_code+='<label>';
                html_code+='{!! Form::checkbox('records',1,null, null) !!}';
                    html_code+='<span>'+count+'</span>';
                html_code+='</div>';
                html_code += '<td ><div class="form-group clearfix"> <div class="col-md-12"><div class="col-md-12">';
                html_code +='{!! Form::text('objective[]','', ['maxlength'=>'1000', 'class' => 'form-control  required','required'=>'required']) !!}';
                html_code+='{!! $errors->first('','<span class="help-block">:message</span>') !!}';
                html_code+='</div></div> </div> </td>';
                html_code += "</tr>";
                $('#objectives').append(html_code);
            });
            $(document).on('click', '#btnremoveobject', function(){
                var count = $('#tableobjectives tr').length;
                if(count > 2) {
                    $('#tableobjectives tr:last').remove();
                }

            });

        });
    </script>

    <script>

        $(document).ready(function () {
            $(document).on('click','#draft',function () {
                $('#objectiveform').validate().cancelSubmit = true;;
            });
            $(document).on('click','#save',function () {
                $('#objectiveform').validate();
            });
        });
    </script>
