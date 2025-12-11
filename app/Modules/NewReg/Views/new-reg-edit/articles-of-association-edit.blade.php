<div class="col-md-12">
    <div class="box" id="inputForm">
        <div class="box-body">
            {!! Session::has('success') ? '
            <div class="alert alert-info alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>
            ' : '' !!}
            {!! Session::has('error') ? '
            <div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>
            ' : '' !!}

            <div class="panel panel-info">
                <div class="panel-heading">
                    <h5><b>ARTICLES OF ASSOCIATION</b></h5>
                    {{--<div class="row">
                        <div class="col-md-6 pull-left">
                            <h5><b>ARTICLES OF ASSOCIATION</b></h5>
                        </div>
                        <div class="col-md-6 pull-right">
                            <a href="/new-reg/new-reg-form-article-pdf/{{ Encryption::encodeId($appInfo->id)}}"
                               target="_blank" class="btn btn-danger btn-xs documentUrl pull-right">
                                <i class="fa fa-download"></i> <strong> Application Download as PDF</strong>
                            </a>
                        </div>
                    </div>--}}
                </div>
                <div class="panel-body">
                    <fieldset>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>A. General information</strong></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody id="">
                                        <tr>
                                            <td>
                                                1. Name of the Entity
                                            </td>
                                            
                                            <td>
                                                : {{\App\Modules\NewReg\Controllers\GetCompanyController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                2. Entity Type
                                            </td>
                                            <td>
                                                : {{$entityType}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                3. Registration No
                                            </td>
                                            <td>
                                                :
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                4. RJSC Office
                                            </td>
                                            <td>
                                                : Dhaka
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {!! Form::open(array('url' => '/new-reg/save-aoa-clause','method' => 'post', 'id' => 'aoaEditForm','enctype'=>'multipart/form-data',
                           'files' => true, 'role'=>'form')) !!}

                        <input type="hidden" name="app_id"
                               value="{{ \App\Libraries\Encryption::encodeId($appInfo->id) }}" id="app_id"/>

                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>B. Please AOA information correctly.</strong></div>
                            <div class="panel-body">

                                <strong class="text-success">NB: Character Limit Declared by RJSC. </strong>
                                <br><br><br>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('clause_title_id') ? 'has-error': ''}}">
                                            {!! Form::label('','Clause Title', ['class' => 'col-md-4 text-left']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('clause_title_id',$nrClause, ['0'=>'Select One'], ['class' => 'form-control input-md required','placeholder' => 'Select One','required' => 'required' ,'id' => 'clause_title_id']) !!}
                                                {!! $errors->first('clause_title_id','<span class="help-block">:message</span>') !!}
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group hidden" id="articleShowSection">
                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('clause_title_id') ? 'has-error': ''}}">
                                            {!! Form::label('','', ['class' => 'col-md-2 text-left']) !!}
                                            <div class="col-md-8">
                                                {!! Form::textarea('clausewerwe', '', ['class' => 'form-control  input-md articleShow ', 'size' =>'3x3','readonly' => 'true']) !!}
                                                <br>
                                                <span id="input_warning" class="text-danger">You can not write the above text in the given input section.</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group col-md-6 {{$errors->has('clause') ? 'has-error' : ''}}">
                                            {!! Form::label('','Add clause',['class'=>'col-md-3']) !!}
                                            {{--<div class="col-md-1" id="clause_label_title_id"></div>--}}
                                            <div class="col-md-8 maxTextCountDown">
                                                {!! Form::textarea('clause', '', ['class' => 'form-control input-md bigInputField', 'size' =>'5x5','data-rule-maxlength'=>'980','maxlength'=>'980','required' => 'required','id'=>'clause_text']) !!}
                                                {!! $errors->first('clause','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if(ACL::getAccsessRight('NewReg','-E-'))
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-md-offset-3">
                                            <button type="submit" class="btn btn-info btn-md" id="add_clause">Add
                                                clause
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        {!! Form::close() !!}


                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Articles of Association</strong></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody id="">
                                        <?php $i = 1;
                                        $totalRec = count($nrAoaClause);
                                        ?>
                                        @if($totalRec > 0)
                                            @foreach($nrAoaClause as $key => $v_aoa)
                                                <tr>
                                                    <td style="text-align: center" colspan="2">
                                                        {{ $v_aoa->name }}
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td style="width: 10px">
                                                        <p>{{$i}}</p>
                                                        {{--<p>--}}
                                                            {{--<a href="#" row_id="{{ Encryption::encodeId($v_aoa->id) }}"--}}
                                                              {{--data-title-id="{{$v_aoa->clause_title_id}}"--}}
                                                              {{--data-cluase="{{$v_aoa->clause}}"--}}
                                                              {{--class="btn btn-primary  btn-xs updateAoaClauseData"--}}
                                                              {{--data-toggle="modal" data-target="#articleModalEdit"--}}
                                                              {{--id="updateAoaClauseData">EDIT</a></p>--}}
                                                        @if($totalRec == $key+1)
                                                            <a href="#" row_id="{{ Encryption::encodeId($v_aoa->id) }}"
                                                               class="btn btn-danger btn-xs" id="removeCluaseEdit">DELETE</a>
                                                        @endif

                                                    </td>
                                                    <td>
                                                        {!! $v_aoa->clause  !!}
                                                    </td>
                                                </tr>
                                                <?php $i++ ?>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {!! Form::open(array('url' => 'new-reg/new-reg-page/final-submit','method' => 'post', 'id' => 'd','role'=>'form')) !!}
                        @if(ACL::getAccsessRight('NewReg','-E-'))
                        <div class="pull-left">
                            <button type="submit" class="btn btn-info btn-md cancel"
                                    value="draft" name="actionBtn" id="save_as_draft">Save as Draft
                            </button>
                        </div>
                            <div class="pull-right" style="padding-left: 1em;">
                                <button class="btn btn-success" name="actionBtn" id="save" value="saveAndContinue" type="submit">Save and continue</button>
                                {{--@if($authorizeCapitalValidate->capital_validate == 1)--}}
                                    {{--<button type="submit" id="submitForm" style="cursor: pointer;"--}}
                                            {{--class="btn btn-success btn-md"--}}
                                            {{--value="Submit" name="actionBtn">Submit--}}
                                    {{--</button>--}}
                                {{--@else--}}
                                    {{--<div class="alert alert-danger">--}}
                                        {{--<span href="#" data-toggle="tooltip" title="Autorised Capital must equal or less than Value of each Share X Total Subscribed Shares!"><strong>Authorised Capital Not Valid !</strong></span>--}}
                                    {{--</div>--}}
                                {{--@endif--}}
                            </div>
                        @endif
                        {!! Form::close() !!}
                    </fieldset>
                </div>
            </div>
        </div>


        <div class="modal fade" id="articleModalEdit" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Please AOA information correctly</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {!! Form::open(array('url' => '/new-reg/update-aoa-clause','method' => 'put', 'id' => 'aosFormId','enctype'=>'multipart/form-data','files' => true, 'role'=>'form')) !!}
                        <div class="row">
                            <input type="hidden" name="row_id" id="record_id_edit">
                            <div class="col-md-12 {{$errors->has('clause_title_id') ? 'has-error': ''}}">
                                {!! Form::label('clause_title_id','Clause Title', ['class' => 'col-md-3 text-left']) !!}
                                <div class="col-md-9">
                                    {!! Form::select('clause_title_id', $nrClause, '', ['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'clause_title_id']) !!}
                                    {!! $errors->first('clause_title_id','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <br/>

                        <div class="row">
                            <div class="col-md-12 {{$errors->has('clause') ? 'has-error' : ''}}">
                                {!! Form::label('clause','Add clause',['class'=>'col-md-3']) !!}
                                <div class="col-md-9 maxTextCountDown">
                                    {!! Form::textarea('clause', '', ['class' => 'form-control input-md bigInputField required', 'size' =>'5x5','data-rule-maxlength'=>'980','maxlength'=>'980','id'=>'clause_text_edit']) !!}
                                    {!! $errors->first('clause','<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close
                            </button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {

        $('.updateAoaClauseData').click(function () {
            var title_id = $(this).attr('data-title-id');
            var cluase = $(this).attr('data-cluase');
            $('#record_id_edit').val($(this).attr('row_id'))
            $('#clause_text_edit').val(cluase);
            $("#clause_title_id option[value='" + title_id + "']").attr("selected", true);
        })

        $('#aosFormId').validate();
        $(document).on('click', '#add_clause', function () {
            $('#aoaEditForm').validate();
        });

        $(document).on('change', '#clause_title_id', function () {
            var title_id = $(this).val();
            var app_id = $('#app_id').val();


            $.ajax({
                type: "get",
                url: "/new-reg/article-show",
                data: {
                    clause_title_id: title_id,
                    app_id: app_id
                },
                dataType: "json",
                success: function (response) {
                    if (response.response == 1) {
                        $('#articleShowSection').removeClass('hidden');
                        $('.articleShow').val(response.data);
                        var alticledata=response.data.length;
                        if(alticledata>200){
                            $('#clause_text').prop('readonly',true);
                            $('#clause_text').prop('required',false);
                            /*$('#clause_text').val('.');*/
                        }else{
                            $('#clause_text').prop('readonly',false);
                            $('#clause_text').prop('required',true);
                        }
                    }else{
                        $('#clause_text').prop('required',false);
                        $('#clause_text').prop('readonly',false);
                        $('#articleShowSection').addClass('hidden');
                    }
                }
            });


            $('#clause_label_title_id').html('<p>  ' + title_id + ' </p>');
        })

        $('#removeCluaseEdit').click(function () {
            if (confirm('Are you sure to delete')) {
                var row_id = $(this).attr('row_id');
                $.ajax({
                    type: "POST",
                    url: "<?php echo url(); ?>/new-reg/delete-aoa-clause",
                    data: {
                        row_id: row_id,
                        _token: '<?php echo csrf_token() ?>',
                    },
                    dataType: "json",
                    success: function (response) {
                        if (response.responseCode === 1) {
                            window.location.href = response.redirectUrl;
                            location.reload();
                        }
                    }
                });
            } else {
                return false;
            }
        })
    })
</script>

<script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
<script>
    $('.maxTextCountDown').maxlength();
</script>
