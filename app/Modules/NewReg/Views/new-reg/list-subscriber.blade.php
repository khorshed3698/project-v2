<div class="panel panel-info">
    <div class="panel-heading"><strong>3. List of Subscriber</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <strong>(Directors: Minimum-two{2}, Maximum-fifty{50})<br/>{Subscribers/Directors:
                    Minimum-2, Maximum-50}</strong><br/><br/>
                <table class="table table-bordered table-striped" id="list_of_subs">
                    <thead>
                    <tr>
                        <th width="8%" class="text-center">SI.</th>
                        <th width="30%" class="text-center">Name</th>
                        <th width="27%" class="text-center">Position</th>
                        <th width="35%" class="text-center">Number of Subscribed Shares</th>
                    </tr>
                    </thead>
                    <tbody id="list_of_subs_body">
                    <?php $count = 1; ?>
                    @foreach($subscriber as $subscriber)
                        <tr>
                            <td><label class="checkbox-inline">  {!! Form::checkbox('sub_id', $subscriber->id, '', ['class'=>'sub_id', 'id'=>'sub_id'.$subscriber->id, 'onclick'=>'check_checkbox('.$subscriber->id.')']) !!}
                                    &nbsp;&nbsp {{$count++}} </label></td>
                            <td>{!! Form::text('', $subscriber->corporation_body_name, ['class' => 'form-control required input-md','placeholder' => 'Name', 'readonly']) !!}</td>
                            <td>{!! Form::text('', $subscriber->title, ['class' => 'form-control required input-md','placeholder' => 'Position', 'readonly']) !!}</td>
                            <td>{!! Form::number('', $subscriber->no_of_subscribed_shares, ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares', 'readonly']) !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p>* please select check box and give additional interretion</p>
                <div class="row text-center">
                    <div class="col-md-12">
                        <button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#particulars_individual">Enter Information</button>
                        <button class="btn btn-danger btn-sm" type="button" id="remove_info" onclick="deleteRow()">Remove Row</button>
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#particulars_individual_edit" onclick="loadModal()">Edit Information</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="col-md-6">
            {{--<button class="btn btn-info" value="draft" type="submit">Save as Draft</button>--}}
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ url('/new-reg-delete-subscriber/next') }}" class="btn btn-info">Continue</a>
        </div>
    </div>

    
</div>

<input type="hidden" name="sub_id" value="" id="checkVal">

<div class="modal fade" id="particulars_individual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

{{--            {!! Form::open(['url' => '/subscriberStore','method' => 'post','files'=>true,'enctype'=> 'multipart/form-data']) !!}--}}
            {!! Form::open(array('url' => '/subscriberStore', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'subscriberStore')) !!}

            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                            Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                            Association, Form-IX, X, XII)</strong>
                    </div>
                    <input type ="hidden" name="app_id" value="{{Session::get('current_app_id')}}">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('corporation_body_name', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','2. Former Name(If any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('former_individual_name', '', ['class' => 'form-control required input-md','placeholder' => 'Former Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','3. Father Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('father_name', '', ['class' => 'form-control required input-md','placeholder' => 'Father Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','4. Mother Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('mother_name', '', ['class' => 'form-control required input-md','placeholder' => 'Mother Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('usual_residential_address','5. Usual Residential Address',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('usual_residential_address', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('usual_residential_district_id', $districts, '',['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','6. Permanent Address',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('permanent_address', '', ['class' => 'form-control input-sm required','placeholder' => 'Permanent Address', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('permanent_address_district_id', $districts, '',['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','7. Phone/ Mobile',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('mobile', '', ['class' => 'form-control required input-md','placeholder' => 'Phone/ Mobile']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','8. Email',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::email('email', '', ['class' => 'form-control input-md required','placeholder' => 'Email']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','9. Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('present_nationality_id', $nationality, '',['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','10. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','11. Date of Birth',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('dob', '', ['class' => 'form-control required input-md datepicker','placeholder' => 'Datepicker']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"><label>(DD/MM/YYYY)</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','12. TIN(XXXXXXXXXXXX)If Required',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('tin_no', '', ['class' => 'form-control required input-md','placeholder' => 'TIN(xxxxxxxx)If Required']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','13. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('position',$rjscCompanyPosition, null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','14. Signing the agreement of taking qualification shares',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="radio-inline">  {!! Form::radio('signing_qualification_share_agreement', 'Yes', false, ['id' => 'yesCheck']) !!}
                                                    Yes </label>
                                                <label class="radio-inline">   {!! Form::radio('signing_qualification_share_agreement', 'No', true,['id' => 'noCheck']) !!}
                                                    No </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('nominating_entity_id') ? 'has-error': ''}}">
                                    {!! Form::label('nominating_entity_id','15. Nominating Entity(if any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('nominating_entity_id', $nominationEntity, '',['class' => 'form-control input-md', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('nominating_entity_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','16. Date of Appointment(as director, manager, managing agent)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('appointment_date', '', ['class' => 'form-control input-md datepicker','placeholder' => 'Datepicker']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"><label>(DD/MM/YYYY)</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','17. Other Business Occupation',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('other_occupation', '', ['class' => 'form-control input-sm','placeholder' => 'Other Business Occupation', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','18. Directorship in other company(s) (if any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('directorship_in_other_company', '', ['class' => 'form-control input-sm','placeholder' => 'Directorship in other company', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','19. Number of Subscribed Shares',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('no_of_subscribed_shares', '', ['class' => 'form-control required input-md','placeholder' => 'Number of Subscribed Shares']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','20. National ID/Passport No',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control required input-md','placeholder' => 'National ID/Passport No']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <strong style="margin-left: 15px;">(Subscribed in the Memorindum and Articles of
                            Association)</strong><br/><br/>
                        <strong style="margin-left: 15px;">* Required information for complete
                            submission</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="particulars_individual_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => '/subscriberStore','method' => 'post','id'=>'subscriberEdit','files'=>true,'enctype='> 'multipart/form-data']) !!}

            <div class="modal-body">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                            Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                            Association, Form-IX, X, XII)</strong>
                    </div>
                    <input type="hidden" name="sub_id" value="" id="sub_id">
                    <input type ="hidden" name="app_id" value="{{Session::get('current_app_id')}}">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('corporation_body_name', '', ['class' => 'form-control required input-md', 'id'=>'corporation_body_name', 'placeholder' => 'Name',]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','2. Former Name(If any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('former_individual_name', '', ['class' => 'form-control required input-md', 'id'=>'former_individual_name', 'placeholder' => 'Former Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','3. Father Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('father_name', '', ['class' => 'form-control required input-md', 'id'=>'father_name', 'placeholder' => 'Father Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','4. Mother Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('mother_name', '', ['class' => 'form-control required input-md', 'id'=>'mother_name', 'placeholder' => 'Mother Name']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('usual_residential_address','5. Usual Residential Address',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('usual_residential_address', '', ['class' => 'form-control input-sm required','id'=>'usual_residential_address_edit', 'placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('usual_residential_district_id', $districts, '',['class' => 'form-control input-md required', 'id'=>'usual_residential_district_id', 'placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','6. Permanent Address',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('permanent_address', '', ['class' => 'form-control input-sm required', 'id'=>'permanent_address', 'placeholder' => 'Permanent Address', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('permanent_address_district_id', $districts, '',['class' => 'form-control input-md required', 'id'=>'permanent_address_district_id','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','7. Phone/ Mobile',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('mobile', '', ['class' => 'form-control required input-md', 'id'=>'mobile', 'placeholder' => 'Phone/ Mobile']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','8. Email',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::email('email', '', ['class' => 'form-control input-md required', 'id'=>'email', 'placeholder' => 'Email']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','9. Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('present_nationality_id', $nationality, '',['class' => 'form-control input-md required', 'id'=>'present_nationality_id', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','10. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md required', 'id'=>'original_nationality_id', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','11. Date of Birth',['class'=>'col-md-4  text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('dob', '', ['class' => 'form-control datepicker required input-md', 'id'=>'dob', 'placeholder' => 'Datepicker']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"><label>(DD/MM/YYYY)</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','12. TIN(XXXXXXXXXXXX)If Required',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('tin_no', '', ['class' => 'form-control required input-md', 'id'=>'tin_no', 'placeholder' => 'TIN(xxxxxxxx)If Required']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','13. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('position', $rjscCompanyPosition, null,['class' => 'form-control input-md required', 'id'=>'position', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','14. Signing the agreement of taking qualification shares',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="radio-inline">  {!! Form::radio('signing_qualification_share_agreement', 'Yes', false, ['id' => 'signing_qualification_share_agreement']) !!}
                                                    Yes </label>
                                                <label class="radio-inline">   {!! Form::radio('signing_qualification_share_agreement', 'No', true,['id' => 'signing_qualification_share_agreement']) !!}
                                                    No </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('nominating_entity_id') ? 'has-error': ''}}">
                                    {!! Form::label('nominating_entity_id','15. Nominating Entity(if any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('nominating_entity_id', $nominationEntity, '',['class' => 'form-control input-md','id'=>'nominating_entity_id_edit', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('nominating_entity_id','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','16. Date of Appointment(as director, manager, managing agent)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('appointment_date', '', ['class' => 'form-control datepicker input-md', 'id'=>'appointment_date', 'placeholder' => 'Datepicker']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"><label>(DD/MM/YYYY)</label></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','17. Other Business Occupation',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('other_occupation', '', ['class' => 'form-control input-sm', 'id'=>'other_occupation', 'placeholder' => 'Other Business Occupation', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','18. Directorship in other company(s) (if any)',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('directorship_in_other_company', '', ['class' => 'form-control input-sm', 'id'=>'directorship_in_other_company', 'placeholder' => 'Directorship in other company', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','19. Number of Subscribed Shares',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('no_of_subscribed_shares', '', ['class' => 'form-control required input-md', 'id'=>'no_of_subscribed_shares', 'placeholder' => 'Number of Subscribed Shares']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','20. National ID/Passport No',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control required input-md', 'id'=>'national_id_passport_no', 'placeholder' => 'National ID/Passport No']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <strong style="margin-left: 15px;">(Subscribed in the Memorindum and Articles of
                            Association)</strong><br/><br/>
                        <strong style="margin-left: 15px;">* Required information for complete
                            submission</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            <script>
                $(function () {

                    $('.datepicker').datetimepicker({
                        viewMode: 'years',
                        format: 'DD-MMM-YYYY'
                    });
                });
            </script>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@section('footer-script')

    <script>
        $(document).ready(function () {
            $("#subscriberStore").validate();
            $("#subscriberEdit").validate();
        });
        // $(document).ready(function () {
        //     $(document).on('click','#enter_info',function () {
        //         var rowCount = $('#list_of_subs tr').length;
        //         $('#list_of_subs_body').append('<tr><td><input type="checkbox">&nbsp &nbsp &nbsp' + rowCount +'</td> <td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input type="number" class="form-control"></td></tr>')
        //     })
        //     $(document).on('click','#remove_info',function () {
        //         var rowCount = $('#list_of_subs tr').length;
        //         if(rowCount > 0) {
        //             $('#list_of_subs tr:last').remove();
        //         }
        //     })
        // });

        $(':checkbox').on('change',function(){
            var tr = $(this), name = tr.prop('name');
            if(tr.is(':checked')){
                $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);
            }
        });

        function check_checkbox(id) {
            var sub_id=  $('#sub_id' + id).val();
            $('#checkVal').val(sub_id);
        }

        function loadModal(){
            var row_id= document.getElementById('checkVal').value;
            $.ajax({
                url: "<?php echo url('new-reg-get-subscriber/');?>",
                method: "GET",
                data: {
                    token: '<?php echo csrf_token() ?>',
                    id: row_id
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#sub_id').val(data['id']);
                    $('#corporation_body_name').val(data['corporation_body_name']);
                    $('#former_individual_name').val(data['former_individual_name']);
                    $('#father_name').val(data['father_name']);
                    $('#mother_name').val(data['mother_name']);
                    $('#usual_residential_address_edit').val(data['usual_residential_address']);
                    $('#usual_residential_district_id').val(data['usual_residential_district_id']);
                    $('#permanent_address').val(data['permanent_address']);
                    $('#permanent_address_district_id').val(data['permanent_address_district_id']);
                    $('#mobile').val(data['mobile']);
                    $('#email').val(data['email']);
                    $('#present_nationality_id').val(data['present_nationality_id']);
                    $('#original_nationality_id').val(data['original_nationality_id']);
                    $('#dob').val(data['dob']);
                    $('#tin_no').val(data['tin_no']);
                    $('#position').val(data['position']);
                    $('#signing_qualification_share_agreement').val(data['signing_qualification_share_agreement']);
                    $('#nominating_entity_id_edit').val(data['nominating_entity_id']);
                    $('#appointment_date').val(data['appointment_date']);
                    $('#other_occupation').val(data['other_occupation']);
                    $('#directorship_in_other_company').val(data['directorship_in_other_company']);
                    $('#no_of_subscribed_shares').val(data['no_of_subscribed_shares']);
                    $('#national_id_passport_no').val(data['national_id_passport_no']);
                },
                error: function () {
                    alert('someThing Goes Wrong');
                }
            });
        }

        function deleteRow(){
            if(confirm('Are you sure?')){
                var row_id= document.getElementById('checkVal').value;
                $.ajax({
                    url: "<?php echo url('new-reg-delete-subscriber/');?>",
                    method: "GET",
                    data: {
                        token: '<?php echo csrf_token() ?>',
                        id: row_id
                    },
                    dataType: "json",
                    success: function (response) {
                        if(response.responseCode == 0){
                            alert(response.html);
                        }
                        location.reload();
                    },
                    error: function (response) {
                        alert('someThing Goes Wrong');
                    }
                });
            }

        }
    </script>
@endsection
