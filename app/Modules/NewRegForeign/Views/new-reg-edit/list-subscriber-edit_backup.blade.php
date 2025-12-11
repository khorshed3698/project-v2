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
                        <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#particulars_individual_edit" onclick="loadModal(event)">Edit Information</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="col-md-6">
            {{--<button class="btn btn-info" name="action_btn" value="draft" type="submit">Save as Draft</button>--}}
        </div>
        <div class="col-md-6 text-right">

            {{--<button class="btn btn-success" name="action_btn" value="save" type="submit">Save and Continue</button>--}}
            <a href="{{ url('/new-reg-delete-subscriber/next/edit') }}" class="btn btn-info">Save and Continue</a>

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

            <!-- SECTION ONE ENTRY MODAL -->
            {!! Form::open(['url' => '/subscriberStore','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'subscriberStore','name'=>'section_one_form']) !!}
            <div class="modal-body">
                <input type ="hidden" name="app_id" value="{{\App\Libraries\Encryption::encodeId($appInfo->id)}}" id="enc_app_id">


                <div id="section_one">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                                Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                                Association, Form-IX, X, XII)</strong>
                        </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                {!! Form::label('','1. Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                <div class="col-md-4">
                                    {!! Form::select('present_nationality_id', $nationality, '',['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'nationality_section_one']) !!}
                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                        <div class="form-group" id="nationalId">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','2. National ID',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control input-md','placeholder' => 'National ID/Passport No','id'=>'national_id_section_one']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="passsPort">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','3. Passport',['class'=>'col-md-4 text-left ']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control  input-md','placeholder' => 'Passport No','id'=>'passport_id_section_one']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','4. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('position', $rjscCompanyPosition,1,['class' => 'form-control input-md required', 'id'=>'position', 'placeholder' => 'Select One','id'=>'position_sectjion_one']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','5. TIN(XXXXXXXXXXXX)If Required',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('tin_no', '', ['class' => 'form-control  input-md','placeholder' => 'TIN(xxxxxxxx)If Required','id'=>'tin_no_section_one']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        <input name="tin_id" id="tin_id" type="hidden" class="" value="">
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                {!! Form::label('','6. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left']) !!}
                                <div class="col-md-4">
                                    {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md','placeholder' => 'Select One']) !!}
                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>




                <div class="hidden" id="section_two">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                                Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                                Association, Form-IX, X, XII)</strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <input type="hidden" name="saveMode" value="editPage">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('corporation_body_name', '', ['class' => 'form-control required input-md','placeholder' => 'Name','readonly'=>true,'id'=>'section_one_user_name']) !!}
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
                                            {!! Form::text('former_individual_name', '', ['class' => 'form-control input-md','placeholder' => 'Former Name']) !!}
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
                                            {!! Form::select('show', $nationality, '',['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'section_two_nationality']) !!}
                                            {!! Form::hidden('present_nationality_id', '',['class' => 'form-control  input-md required','placeholder' => 'Select One','id'=>'section_two_nationality1']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                        {!! Form::label('','10. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left']) !!}
                                        <div class="col-md-4">
                                            {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md','placeholder' => 'Select One']) !!}
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
                                            {!! Form::number('tin_no', '', ['class' => 'form-control required input-md','placeholder' => 'TIN(xxxxxxxx)If Required','id'=> 'section_two_tin']) !!}
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
                                            {!! Form::select('position', $rjscCompanyPosition, 1,['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'section_two_position']) !!}
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
                                                    <label class="radio-inline">  {!! Form::radio('signing_qualification_share_agreement', '1', false) !!}
                                                        Yes </label>
                                                    <label class="radio-inline">   {!! Form::radio('signing_qualification_share_agreement', '0', true) !!}
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
                                            {!! Form::number('no_of_subscribed_shares', '', ['class' => 'form-control required input-md','placeholder' => 'Number of Subscribed Shares']) !!}
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
                                            {!! Form::text('national_id_passport_no', '', ['class' => 'form-control required input-md','placeholder' => 'National ID/Passport No','id'=>'section_two_nid_passport']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('digital_signature') ? 'has-error': ''}}">
                                        {!! Form::label('digital_signature','21. Digital Signature',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::file('digital_signature', null, ['class' => 'form-control required input-md', 'id'=>'', 'placeholder' => 'National ID/Passport No']) !!}
                                            {!! $errors->first('digital_signature','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('subscriber_photo') ? 'has-error': ''}}">
                                        {!! Form::label('subscriber_photo','22. Subscriber Photo',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::file('subscriber_photo', null, ['class' => 'form-control required input-md', 'id'=>'subscriber_photo','required'=>'required']) !!}
                                            {!! $errors->first('subscriber_photo','<span class="help-block">:message</span>') !!}
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



            </div>

            <div class="modal-footer">
                <div id="section_one_button">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="next_content" class="btn btn-info">Next <i class="fa fa-angle-double-right"></i></button>

                </div>
                <div id="section_two_button" class="hidden">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>



<!-- for EDIT INFO MODAL -->
<div class="modal fade" id="particulars_individual_edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => '/subscriberStore','method' => 'post','files'=>true,'enctype='> 'multipart/form-data','id'=>'subscriberEdit']) !!}
            <div class="modal-body">


                <input type ="hidden" name="app_id_edit" value="{{\App\Libraries\Encryption::encodeId($appInfo->id)}}" id="enc_app_id_edit">
                <div id="section_one_edit">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                                Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                                Association, Form-IX, X, XII)</strong>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','1. Nationality',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('present_nationality_id', $nationality, '',['class' => 'form-control input-md required','placeholder' => 'Select One','id'=>'nationality_section_one_edit']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="nationalId_edit">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','2. National ID',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control input-md','placeholder' => 'National ID/Passport No','id'=>'national_id_section_one_edit']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="passsPort_edit">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','3. Passport',['class'=>'col-md-4 text-left ']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'form-control  input-md','placeholder' => 'Passport No','id'=>'passport_id_section_one_edit']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="position_edit">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','4. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('position', $rjscCompanyPosition, 1,['class' => 'form-control input-md required', 'id'=>'position_section_one_edit', 'placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','5. TIN(XXXXXXXXXXXX)If Required',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('tin_no', '', ['class' => 'form-control  input-md','placeholder' => 'TIN(xxxxxxxx)If Required','id'=>'tin_no_section_one_edit']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        <input name="tin_id" id="tin_id_edit" type="hidden" class="" value="">
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','6. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="hidden" id="section_two_edit">
                    <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                            Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                            Association, Form-IX, X, XII)</strong>
                    </div>
                    <input type="hidden" name="sub_id" value="" id="sub_id">
                    <input type ="hidden" name="app_id" value="{{\App\Libraries\Encryption::encodeId($appInfo->id)}}">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('corporation_body_name', '', ['class' => 'form-control required input-md corporation_body_name', 'id'=>'corporation_body_name', 'placeholder' => 'Name','readonly'=>true]) !!}
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
                                        {!! Form::text('former_individual_name', '', ['class' => 'form-control input-md', 'id'=>'former_individual_name', 'placeholder' => 'Former Name']) !!}
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
                                        {!! Form::textarea('usual_residential_address', '', ['class' => 'form-control input-sm required', 'id'=>'usual_residential_address_edit', 'placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}

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
                                            {!! Form::label('','District',['class'=>'col-md-4 text-left required-star']) !!}
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
                                        {!! Form::select('', $nationality, '',['class' => 'form-control input-md required section_two_nationality1_edit', 'id'=>'present_nationality_id', 'placeholder' => 'Select One','disabled'=>true]) !!}
                                        {!! Form::hidden('present_nationality_id', '',['class' => 'form-control  input-md required section_two_nationality1_edit','placeholder' => 'Select One','id'=>'section_two_nationality1_edit']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                    {!! Form::label('','10. Original Nationality other than the present Nationality',['class'=>'col-md-4 text-left']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('original_nationality_id', $nationality, '',['class' => 'form-control input-md', 'id'=>'original_nationality_id', 'placeholder' => 'Select One']) !!}
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
                                        {!! Form::number('tin_no', '', ['class' => 'form-control required input-md section_two_tin_edit', 'id'=>'tin_no', 'placeholder' => 'TIN(xxxxxxxx)If Required','readonly'=>true]) !!}
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
                                        {!! Form::select('position', $rjscCompanyPosition, 1,['class' => 'form-control input-md required', 'id'=>'section_two_position_edit', 'placeholder' => 'Select One']) !!}
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
                                                <label class="radio-inline">  {!! Form::radio('signing_qualification_share_agreement', '1', false, ['id' => 'signing_qualification_share_agreement']) !!}
                                                    Yes </label>
                                                <label class="radio-inline">   {!! Form::radio('signing_qualification_share_agreement', '0', true,['id' => 'signing_qualification_share_agreement']) !!}
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
                                        {!! Form::text('appointment_date', '', ['class' => 'form-control input-md datepicker', 'id'=>'appointment_date', 'placeholder' => 'Datepicker']) !!}
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
                                        {!! Form::number('no_of_subscribed_shares', '', ['class' => 'form-control required input-md', 'id'=>'no_of_subscribed_shares', 'placeholder' => 'Number of Subscribed Shares']) !!}
                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('national_id_passport_no') ? 'has-error': ''}}">
                                    {!! Form::label('national_id_passport_no','20. National ID/Passport No',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('national_id_passport_no', '', ['class' => 'section_two_nid_passport_edit form-control required input-md', 'id'=>'national_id_passport_no', 'placeholder' => 'National ID/Passport No','readonly'=>true]) !!}
                                        {!! $errors->first('national_id_passport_no','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('digital_signature') ? 'has-error': ''}}">
                                    {!! Form::label('digital_signature','21. Digital Signature',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::file('digital_signature', null, ['class' => 'form-control required input-md', 'id'=>'digital_signature', 'required' => 'required']) !!}
                                        {!! $errors->first('digital_signature','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('subscriber_photo') ? 'has-error': ''}}">
                                    {!! Form::label('subscriber_photo','22. Subscriber Photo',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::file('subscriber_photo', null, ['class' => 'form-control required input-md', 'id'=>'subscriber_photo','required'=>'required']) !!}
                                        {!! $errors->first('subscriber_photo','<span class="help-block">:message</span>') !!}
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
            </div>
            <div class="modal-footer">
                <div id="section_one_button_edit">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="next_content_edit" class="btn btn-info">Next <i class="fa fa-angle-double-right"></i></button>
                </div>

                <div id="section_two_button_edit" class="hidden">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
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
        $(document).ready(function () {

            $(document).on('submit','#particulars_individual',function () {
                var formid=$(document).getElementById('particulars_individual');
                formid.validate();
            });
        });


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

        function loadModal(event){
            if(jQuery('input:checkbox:checked').length>0){
                var row_id= document.getElementById('checkVal').value;
                $.ajax({
                    url: "<?php echo url('new-reg-get-subscriber/');?>",
                    method: "GET",
                    data: {
                        token: '<?php echo csrf_token() ?>',
                        id: row_id
                    },
                    dataType: "json",
                    success: function (response) {
                        var data = response.subscribeData;
                        var rjsc_verify_status = response.rjsc_verify_status;
                        console.log(rjsc_verify_status)
                        if(rjsc_verify_status == 1){
                            $('#section_one_edit').addClass('hidden');
                            $('#section_one_button_edit').addClass('hidden');

                            $('#section_two_button_edit').removeClass('hidden');
                            $('#section_two_edit').removeClass('hidden');
                        }else{
                            $('#section_one_edit').removeClass('hidden');
                            $('#section_one_button_edit').removeClass('hidden');

                            $('#section_two_button_edit').addClass('hidden');
                            $('#section_two_edit').addClass('hidden');
                        }
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
                        $('.section_two_nationality1_edit').val(data['present_nationality_id']);
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
            }else{

                alert('Please Select A check Box!!');
                event.preventDefault();
            }

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

        $(document).ready(function () {

            $('#nationality_section_one').on('change',function () {
                var nationality_id = $(this).val();
                if(nationality_id == 13){
                    $('#nationalId').removeClass('hidden');
                    $('#passsPort').addClass('hidden');
                    $('#tin_no_section_one').addClass('required');
                }else{
                    $('#nationalId').addClass('hidden');
                    $('#tin_no_section_one').removeClass('required');
                    $('#passsPort').removeClass('hidden');
                }
            })

            $('#nationality_section_one_edit').on('change',function () {
                var nationality_id = $(this).val();
                if(nationality_id == 13){
                    $('#nationalId_edit').removeClass('hidden');
                    $('#passsPort_edit').addClass('hidden');
                    $('#tin_no_section_one_edit').addClass('required');
                }else{
                    $('#nationalId_edit').addClass('hidden');
                    $('#tin_no_section_one_edit').removeClass('required');
                    $('#passsPort_edit').removeClass('hidden');
                }
            })

            $('#next_content').on('click',function () {

                var tin_no_section_one = $('#tin_no_section_one').val().trim();
                var nationality_id = $('#national_id_section_one').val();
                var passport_no_section_one = $('#passport_id_section_one').val();
                var nationality_section_one = $('#nationality_section_one').val();
                var position_sectjion_one = $('#position_sectjion_one').val();
                if(nationality_section_one == ''){
                    alert('Your nationality is required');
                    return false;
                }else{
                    $('#section_two_nid_passport').val(nationality_section_one);
                }
                if(nationality_section_one == 13){
                    if(nationality_id ==''){
                        alert('Your national id is required');
                        return false;
                    }

                }else{
                    if(passport_no_section_one == ''){
                        alert('your passport id is required');
                        return false;
                    }else{
                        $('#section_two_nid_passport').val(passport_no_section_one);
                    }
                }
                if(position_sectjion_one == ''){
                    alert('Your postion is required');
                    return false;
                }else{
                    if(position_sectjion_one == 2) {
                        if (tin_no_section_one == '') {
                            alert('need to add tin number');
                            return false;
                        }
                    }
                }


                $('#section_two_nationality').val(nationality_section_one);
                $('#section_two_position').val(position_sectjion_one);
                $('#section_two_nationality1').val(nationality_section_one);
                $('#section_two_tin').val(tin_no_section_one);
                $('#section_two_nationality').prop('disabled', true);

                $('#section_two_nid_passport').prop('readonly', true);
                $('#section_two_tin').prop('readonly', true);
                var app_id = $('#enc_app_id').val();
                if(tin_no_section_one !=""){
                    btn = $(this);
                    $(this).prop('disabled', true);
                    btn_content = btn.html();
                    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

                    $.ajax({
                        url: '/new-reg/tin-store',
                        type: "POST",
                        data: {
                            tin_no: tin_no_section_one,
                            app_id: app_id
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.responseCode == 1) {
                                $('#tin_id').val(response.tin_id);
                                checkgenerator();
                            } else if (response.responseCode == 0) {

                                alert('something was wrong!')
                            }
                        },
                        error: function (response) {
                            alert('someThing Goes Wrong');
                        }
                    });

                }else{
                    $('#section_one').addClass('hidden');
                    $('#present_nationality_id').prop('disable', true);
                    $('#section_one_button').addClass('hidden');
                    $('#section_two_button').removeClass('hidden');
                    $('#section_two').removeClass('hidden');
                }



            });


            $('#next_content_edit').on('click',function () {

                var tin_no_section_one = $('#tin_no_section_one_edit').val().trim();
                var nationality_id = $('#national_id_section_one_edit').val();
                var passport_no_section_one = $('#passport_id_section_one_edit').val();
                var nationality_section_one = $('#nationality_section_one_edit').val();
                var position_section_one_edit = $('#position_section_one_edit').val();

                if(nationality_section_one == ''){
                    alert('Your nationality is required');
                    return false;
                }else{

                    $('.section_two_nid_passport_edit').val(nationality_section_one);
                }
                if(nationality_section_one == 13){
                    if(nationality_id ==''){
                        alert('Your national id is required');
                        return false;
                    }

                }else{
                    if(passport_no_section_one == ''){
                        alert('your passport id is required');
                        return false;
                    }else{
                        $('.section_two_nid_passport_edit').val(passport_no_section_one);
                    }
                }
                if(position_section_one_edit == ''){
                    alert('Your postion is required');
                    return false;
                }else{
                    if(position_section_one_edit == 2) {
                        if (tin_no_section_one == '') {
                            alert('need to add tin number');
                            return false;
                        }
                    }
                }

                $('#present_nationality_id').val(nationality_section_one);
                $('#section_two_nationality1_edit').val(nationality_section_one);
                $('#section_two_position_edit').val(position_section_one_edit);
                $('.section_two_tin_edit').val(tin_no_section_one);
                $('#present_nationality_id').prop('disabled', true);
                $('.section_two_nid_passport_edit').prop('readonly', true);
                $('.section_two_tin_edit').prop('readonly', true);
                var app_id = $('#enc_app_id_edit').val();
                if(tin_no_section_one!=""){
                    btn = $(this);
                    $(this).prop('disabled', true);
                    btn_content = btn.html();
                    btn.html('<i class="fa fa-spinner fa-spin"></i> &nbsp;' + btn_content);

                    $.ajax({
                        url: '/new-reg/tin-store',
                        type: "POST",
                        data: {
                            tin_no: tin_no_section_one,
                            app_id: app_id
                        },
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.responseCode == 1) {
                                $('#tin_id_edit').val(response.tin_id);
                                checkgenerator_edit();
                            } else if (response.responseCode == 0) {

                                alert('something was wrong!')
                            }
                        },
                        error: function (response) {
                            alert('someThing Goes Wrong');
                        }
                    });
                }else{
                    $('#section_one_edit').addClass('hidden');
                    $('#section_one_button_edit').addClass('hidden');
                    $('#section_two_button_edit').removeClass('hidden');
                    $('#section_two_edit').removeClass('hidden');
                }


            });

            function checkgenerator()
            {
                var tin_no_section_one = $('#tin_no_section_one').val().trim();
                var tin_id = $('#tin_id').val().trim();

                $.ajax({
                    url: '/licence-applications/tin/tin-response',
                    type: "POST",
                    data: {
                        tin_no: tin_no_section_one,
                        tin_id: tin_id,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            $('#enc_id').val(response.enc_id);
                            if (response.status == 0 || response.status == -1) {
                                myVar = setTimeout(checkgenerator, 5000);

                            }else if (response.status == 1) {
                                var obj = JSON.parse(response.jsonData.response);
                                $('#section_one_user_name').val(obj.assesName);
                                btn.html(btn_content);
                                $('#section_one').addClass('hidden');
                                $('#present_nationality_id').prop('disable', true);
                                $('#section_one_button').addClass('hidden');
                                $('#section_two_button').removeClass('hidden');
                                $('#section_two').removeClass('hidden');

                            }else{
                                alert('Whoops there was some problem please contact with system admin. '+response.message);

                            }
                        } else {
                            alert('Whoops there was some problem please contact with system admin.');
                            // window.location.reload();
                        }
                    }
                });
                return false; // keeps the page from not refreshing
            }

            function checkgenerator_edit()
            {
                var tin_no_section_one = $('#tin_no_section_one_edit').val().trim();
                var tin_id = $('#tin_id_edit').val().trim();

                $.ajax({
                    url: '/licence-applications/tin/tin-response',
                    type: "POST",
                    data: {
                        tin_no: tin_no_section_one,
                        tin_id: tin_id,
                    },
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        if (response.responseCode == 1) {
                            $('#enc_id').val(response.enc_id);
                            if (response.status == 0 || response.status == -1) {
                                myVar = setTimeout(checkgenerator_edit, 5000);

                            }else if (response.status == 1) {
                                btn.html(btn_content);
                                var obj = JSON.parse(response.jsonData.response);
                                $('.corporation_body_name').val(obj.assesName);
                                $('#next_content_edit').prop('disabled', false);
                                $('#section_one_edit').addClass('hidden');
                                $('#section_one_button_edit').addClass('hidden');
                                $('#section_two_button_edit').removeClass('hidden');
                                $('#section_two_edit').removeClass('hidden');

                            }else{
                                alert('Whoops there was some problem please contact with system admin. '+response.message);

                            }
                        } else {
                            alert('Whoops there was some problem please contact with system admin.');
                            // window.location.reload();
                        }
                    }
                });
                return false; // keeps the page from not refreshing
            }

        })


    </script>

