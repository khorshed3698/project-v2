<div class="panel panel-info">
    <div class="panel-heading"><strong>2. General Information</strong></div>
    <div class="panel-body">
        {!! Form::open(['url' => '/store','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'generalinfoform']) !!}

        <input type="hidden" value="{{Session::get('current_app_id')}}" name="app_id">

        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('entry_name','1. Name of the Entity',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-8">
                            <span> {{ \App\Libraries\CommonFunction::getCompanyNameById(Auth::user()->company_ids) }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    {!! Form::label('entry_type','2. Entity Type',['class'=>'col-md-4 text-left']) !!}
                    <div class="col-md-8">
                        <span>{{$entityType}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('liability_type_id') ? 'has-error': ''}}">
                    {!! Form::label('liability_type_id','3. Liability Type',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-3">
                        {!! Form::select('liability_type_id',$liabilitytypes, null,['class' => 'form-control input-md','required'=>'required','placeholder' => 'Select One']) !!}
                        {!! $errors->first('liability_type_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-5"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('address_entity') ? 'has-error': ''}}">
                    {!! Form::label('address_entity','4. Address of the Entity',['class'=>'col-md-4 text-left required-star','required'=>'required']) !!}
                    <div class="col-md-4">
                        {!! Form::textarea('address_entity', '', ['class' => 'form-control input-sm required','required'=>'required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                        {!! $errors->first('address_entity','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('entity_district_id') ? 'has-error': ''}}">

                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">

                                {!! Form::label('entity_district_id','District',['class'=>'col-md-8 text-left required-star']) !!}
                            </div>
                            <div class="col-md-7">
                                {!! Form::select('entity_district_id',$districts, null,['class' => 'form-control input-md required','required'=>'required','placeholder' => 'Select One']) !!}
                                {!! $errors->first('entity_district_id','<span class="help-block">:message</span>') !!}

                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('entity_email_address') ? 'has-error': ''}}">
                    {!! Form::label('entity_email_address','4.1 . Entity Email Address',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::email('entity_email_address', '', ['class' => 'form-control input-md required','required'=>'required','placeholder' => 'Entity Email']) !!}
                        {!! $errors->first('entity_email_address','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('main_business_objective') ? 'has-error': ''}}">
                    {!! Form::label('main_business_objective','5. Main Business objective',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::text('main_business_objective', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => 'Business Objective']) !!}
                        {!! $errors->first('main_business_objective','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('business_sector_id') ? 'has-error': ''}}">
                    {!! Form::label('','6. Business Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::select('business_sector_id',$rjscsector, null,['class' => 'form-control input-md required','id'=>'business-sector','required'=>'required']) !!}
                        {!! $errors->first('business_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('business_sub_sector_id') ? 'has-error': ''}}">
                    {!! Form::label('','7. Business Sub-Sector',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('business_sub_sector_id',[], null,['class' => 'form-control input-md required','required'=>'required', 'id'=>'business_sub_sector_id' ,'placeholder' => 'Select One']) !!}
                        {!! $errors->first('business_sub_sector_id','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('authorize_capital') ? 'has-error': ''}}">
                    {!! Form::label('','8. Authorized Capital (BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('authorize_capital', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => 'BDT']) !!}
                        {!! $errors->first('authorize_capital','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>{Authorized Capital =<br/> {Shares No.} X {Value of each Share}}</span>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('number_shares') ? 'has-error': ''}}">
                    {!! Form::label('number_shares','9. Number of shares',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('number_shares', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => 'Number of Share']) !!}
                        {!! $errors->first('number_shares','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('value_of_each_share') ? 'has-error': ''}}">
                    {!! Form::label('value_of_each_share','10. Value of each share(BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('value_of_each_share', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => 'Value of each share(BDT)']) !!}
                        {!! $errors->first('value_of_each_share','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('minimum_no_of_directors') ? 'has-error': ''}}">
                    {!! Form::label('','11. Minimum No of Directors',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('minimum_no_of_directors', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => '']) !!}
                        {!! $errors->first('minimum_no_of_directors','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>(Minimum Two{2})</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('maximum_no_of_directors') ? 'has-error': ''}}">
                    {!! Form::label('','12. Maximum No of Directors ',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('maximum_no_of_directors', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('maximum_no_of_directors','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <span>(Maximum fifty{50})</span>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('quorum_agm_egm_num') ? 'has-error': ''}}">
                    {!! Form::label('quorum_agm_egm_num','13. Quorum of AGM/EGM',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('quorum_agm_egm_num', '', ['class' => 'form-control required input-md','placeholder' => '']) !!}
                        {!! $errors->first('quorum_agm_egm_num','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">
                                <span>(Maximum tow{2})</span>
                            </div>
                            <div class="col-md-4">

                                {!! Form::text('quorum_agm_egm_word', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => 'Three']) !!}

                            </div>
                            <div class="col-md-3">
                                <span>In word</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('q_directors_meeting_num') ? 'has-error': ''}}">
                    {!! Form::label('','14. Quorum of Board of Directors Meeting',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('q_directors_meeting_num', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => '']) !!}
                        {!! $errors->first('q_directors_meeting_num','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-5">
                                <span>(Maximum tow{2})</span>
                            </div>
                            <div class="col-md-4">

                                {!! Form::text('q_directors_meeting_word', '', ['class' => 'form-control required input-md','placeholder' => 'Three']) !!}

                            </div>
                            <div class="col-md-3">
                                <span>In word</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <br>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('duration_of_chairmanship') ? 'has-error': ''}}">
                    {!! Form::label('duration_of_chairmanship','15. Duration for Chairmanship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('duration_of_chairmanship', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => '']) !!}
                        {!! $errors->first('duration_of_chairmanship','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="row">

                <div class="col-md-12 {{$errors->has('duration_managing_directorship') ? 'has-error': ''}}">
                    {!! Form::label('','16. Duration for Managing Directorship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                    <div class="col-md-4">
                        {!! Form::number('duration_managing_directorship', '', ['class' => 'form-control required input-md','required'=>'required','placeholder' => '']) !!}
                        {!! $errors->first('duration_managing_directorship','<span class="help-block">:message</span>') !!}

                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="col-md-6">
            <button class="btn btn-info" id="draft" name="actionBtn" value="draft" type="submit">Save as Draft</button>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-success" id="save"  name="actionBtn" value="submit" type="submit">Save and continue</button>
        </div>
    </div>
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>"/>

    {!! Form::close() !!}

</div>
<script>
    $(document).ready(function () {
        $(document).on('click','#draft',function () {
            $('#generalinfoform').validate().cancelSubmit = true;;
        });
        $(document).on('click','#save',function () {
            $('#generalinfoform').validate();
        });


        $( "#business-sector" ).change(function() {
            var sectorid=$(this).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({
                type: "get",
                url:'/load-rjsc-subsectors',
                data: {
                    sectorid: sectorid
                },
                success: function (response) {
                    var option = '<option value="">Select One</option>';
                    $.each(response.result, function (id, value) {
                        option += '<option value="'+ value.sub_sector_id +'">'+ value.name +'</option>'
                    });
                    $('#business_sub_sector_id').html(option);
                }
            });
        });


    });
</script>

