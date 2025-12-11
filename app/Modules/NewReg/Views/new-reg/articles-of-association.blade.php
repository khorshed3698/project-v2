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
                <div class="panel-heading"><h5><b>ARTICLES OF ASSOCIATION</b></h5></div>
                <div class="panel-body">
                    {!! Form::open(array('url' => '/save-aoa-clause','method' => 'post', 'id' => 'aoaformSave','enctype'=>'multipart/form-data',
                            'files' => true, 'role'=>'form')) !!}
                    <input type="hidden" value="{{Session::get('current_app_id')}}" name="app_id">
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
                                                : {{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids)}}
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


                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>B. Please AOA information correctly</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 {{$errors->has('clause_title_id') ? 'has-error': ''}}">
                                            {!! Form::label('clause_title_id','Clause Title', ['class' => 'col-md-4 text-left']) !!}
                                            <div class="col-md-6">
                                                {!! Form::select('clause_title_id', $nrClause, ['0'=>'Select One'], ['class' => 'form-control input-md required clause_title_id','placeholder' => 'Select One','required' => 'required', 'id' => 'clause_title_id' ]) !!}
                                                {!! $errors->first('clause_title_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group col-md-6 {{$errors->has('clause') ? 'has-error' : ''}}">
                                            {!! Form::label('clause','Add clause',['class'=>'col-md-3']) !!}
                                            <div class="col-md-1 clause_label_title_id" id="clause_label_title_id"></div>
                                            <div class="col-md-8 maxTextCountDown">
                                                {!! Form::textarea('clause', '', ['class' => 'form-control input-md bigInputField required', 'size' =>'5x5','data-rule-maxlength'=>'1000','maxlength'=>'1000','required' => 'required']) !!}
                                                {!! $errors->first('clause','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="form-group col-md-6 col-md-offset-3">
                                            <button type="submit" class="btn btn-info btn-md" id="add_clause">Add clause</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-info">
                            <div class="panel-heading"><strong>Articles of Association</strong></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                        <tbody id="">
                                        <?php
                                        $i = 1;
                                        $totalRec = count($nrAoaClause);
                                        ?>
                                        @if( $totalRec > 0)
                                            @foreach($nrAoaClause as $key => $v_aoa)
                                                <tr>
                                                    <td style="text-align: center" colspan="2">
                                                        {{ $v_aoa->name }}
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td style="width: 10px">
                                                        <p>{{$i}}</p>
                                                        <a href="#" row_id="{{ Encryption::encodeId($v_aoa->id) }}"
                                                           data-title-id="{{$v_aoa->clause_title_id}}"
                                                           data-cluase="{{$v_aoa->clause}}"
                                                           class="btn btn-primary updateAoaClauseData"
                                                           data-toggle="modal" data-target="#articleModal"
                                                           id="updateAoaClauseData">Edit</a>
                                                        @if($totalRec == $key+1)
                                                        <a href="#" row_id="{{ Encryption::encodeId($v_aoa->id) }}"
                                                           class="btn btn-danger" id="removeCluase">Delete</a>
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

                    </fieldset>



                {!! Form::close() !!}<!-- /.form end -->

                    {!! Form::open(array('url' => 'new-reg-page/final-submit','method' => 'post', 'id' => 'd','role'=>'form')) !!}
                    <div class="pull-left">
                        {{--<button type="submit" class="btn btn-info btn-md cancel"--}}
                                {{--value="draft" name="actionBtn" id="save_as_draft">Save as Draft--}}
                        {{--</button>--}}
                    </div>

                    <div class="pull-right" style="padding-left: 1em;">
                        @if($authorizeCapitalValidate->capital_validate == 1)
                            <button type="submit" id="submitForm" style="cursor: pointer;" class="btn btn-success btn-md"
                                    value="Submit" name="actionBtn">Submit
                            </button>
                        @else
                            <div class="alert alert-danger">
                                <span href="#" data-toggle="tooltip" title="Autorised Capital must equal or less than Value of each Share X Total !"><strong>Authorised Capital Not Valid !</strong></span>
                            </div>
                        @endif
                    </div>
                    {!! Form::close() !!}

                    <!-- Modal -->
                    <div class="modal fade" id="articleModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Please AOA information correctly</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">{{--'.$editAoaClause->id--}}
                                    {!! Form::open(array('url' => '/update-aoa-clause','method' => 'put', 'id' => 'aosFormId','enctype'=>'multipart/form-data','files' => true, 'role'=>'form')) !!}
                                    <div class="row">
                                        <input type="hidden" name="row_id" id="record_id">
                                        <div class="col-md-12 {{$errors->has('clause_title_id') ? 'has-error': ''}}">
                                            {!! Form::label('clause_title_id','Clause Title', ['class' => 'col-md-3 text-left']) !!}
                                            <div class="col-md-9">
                                                {!! Form::select('clause_title_id', $nrClause, '', ['class' => 'form-control input-md required clause_title_id','placeholder' => 'Select One','id'=>'clause_title_id','required' => 'required']) !!}
                                                {!! $errors->first('clause_title_id','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <br/>

                                    <div class="row">
                                        <div class="col-md-12 {{$errors->has('clause') ? 'has-error' : ''}}">
                                            {!! Form::label('clause','Add clause',['class'=>'col-md-3']) !!}
                                            <div class="col-md-1 clause_label_title_id"></div>
                                            <div class="col-md-9 maxTextCountDown">
                                                {!! Form::textarea('clause', '', ['class' => 'form-control input-md bigInputField required', 'size' =>'5x5','data-rule-maxlength'=>'200','maxlength'=>'200','id'=>'clause_text','required' => 'required']) !!}
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
                    <!-- Modal -->
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
            $('#record_id').val($(this).attr('row_id'))
            $('#clause_text').val(cluase);
            $("#clause_title_id option[value='" + title_id + "']").attr("selected", true);
        })
        $('#aoaformSave').validate();
        $('#aosFormId').validate();


        $(document).on('change','#clause_title_id',function () {
            var title_id = $(this).val();
            $('#clause_label_title_id').html('<p>  '+title_id+' </p>');
        })

        $('#removeCluase').click(function () {
            if(confirm('Are you sure to delete')){
                var row_id = $(this).attr('row_id');
                $.ajax({
                    type: "POST",
                    url: "<?php echo url(); ?>/new-reg/delete-aoa-clause",
                    data: {
                        row_id: row_id,
                        _token : '<?php echo csrf_token() ?>',
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.responseCode === 1){
                           window.location.href = "<?php echo url(); ?>/licence-applications/company-registration/add#step13";
                            location.reload();
                        }
                    }
                });
            }else{
                return false;
            }
        })

    })

</script>
<script src="{{ asset("assets/scripts/jQuery.maxlength.js") }}" src="" type="text/javascript"></script>
<script>
    $('.maxTextCountDown').maxlength();
</script>
