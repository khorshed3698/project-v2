@extends('layouts.admin')
@section('content')
    <style>
        #app-form label.error {display: none !important; }
        .wizard > .steps > ul > li{
            width: 12.65% !important;
        }
        .wizard > .steps .number {
            font-size: 1.2em;
        }
    </style>
    <section class="content" id="inputForm">
        <div class="col-md-12">
            @if(Session::has('success'))
                <div class="alert alert-success">
                    {!!Session::get('success') !!}
                </div>
            @endif
            @if(Session::has('error'))
                <div class="alert alert-danger">
                    {!! Session::get('error') !!}
                </div>
            @endif
        </div>
              <div class="col-md-12" style="padding:0px;">
                    <div class="box">
                        <div class="box-body">
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <strong>Application</strong>
                                </div>
                                {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> 'app-form')) !!}
                                {{--<input type ="hidden" name="app_id" value="{{(isset($alreadyExistApplicant->application_id) ? App\Libraries\Encryption::encodeId($alreadyExistApplicant->application_id) : '')}}">--}}
                                <input type="hidden" name="selected_file" id="selected_file" />
                                <input type="hidden" name="validateFieldName" id="validateFieldName" />
                                <input type="hidden" name="isRequired" id="isRequired" />
                                <div class="panel-body">
                                    <h3 class="text-center">Step one</h3>
                                    <fieldset>
                                        @include('NewReg::new-reg.control')
                                    </fieldset>
                                    <h3 class="text-center">Step two</h3>
                                    <fieldset>
                                        @include('NewReg::new-reg.general-info')
                                    </fieldset>
                                    <h3 class="text-center">Step Three</h3>
                                    <fieldset>
                                        @include('NewReg::new-reg.particular')
                                    </fieldset>

                                    <h3 class="text-center">Step Four</h3>
                                    <fieldset>
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
                                                            <tr>
                                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!}
                                                                        &nbsp;&nbsp1 </label></td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!}
                                                                        &nbsp;&nbsp; 2 </label></td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                                            </tr>
                                                            <tr>
                                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!}
                                                                        &nbsp;&nbsp; 3 </label></td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                        <p>* please select check box and give additional interretion</p>

                                                        <div class="row text-center">
                                                            <div class="col-md-12">
                                                                <button class="btn btn-info btn-sm" type="button" id="enter_info">Enter Information</button>
                                                                <button class="btn btn-danger btn-sm" type="button" id="remove_info">Remove Row</button>
                                                                <button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#particulars_individual">Edit Information</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="particulars_individual" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="panel panel-info">
                                                                <div class="panel-heading">
                                                                    <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual
                                                                        Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of
                                                                        Association, Form-IX, X, XII)</strong>
                                                                </div>
                                                                <div class="panel-body">
                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-8">
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','2. Former Name(If any)',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-8">
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Former Name']) !!}
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
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Father Name']) !!}
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
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Mother Name']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','5. Usual Residential Address',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                                            {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                                                                    {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Permanent Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                                            {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                                                                    {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Phone/ Mobile']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                                <div class="col-md-4"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','4. Email',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::email('', '', ['class' => 'form-control input-md required','placeholder' => 'Email']) !!}
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
                                                                                    {!! Form::select('',['Bangladesi' => 'Bangladesi','Indian' => 'Indian'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                                                                    {!! Form::select('',['Canada' => 'Canada','USA' => 'USA'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Datepicker']) !!}
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
                                                                                    {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'TIN(xxxxxxxx)If Required']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                                <div class="col-md-4"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','10. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::select('',['PHP Trim leader' => 'PHP Trim leader','Java Trim leader' => 'Java Trim leader'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
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
                                                                                            <label class="radio-inline">  {!! Form::radio('int_property_reg_in_bd', 'Yes',false, ['id' => 'yesCheck']) !!}
                                                                                                Yes </label>
                                                                                            <label class="radio-inline">   {!! Form::radio('int_property_reg_in_bd', 'No',true,['id' => 'noCheck']) !!}
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
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','15. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::select('',['nomal entity' => 'nomal entity','High entity' => 'High entity'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                                <div class="col-md-4"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','16. Date of Appointment(as director, manager, managing agent)',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Datepicker']) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                                <div class="col-md-4"><label>(DD/MM/YYYY)</label></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','17. Other Business Occupation',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Other Business Occupation', 'rows' => 2, 'cols' => 1]) !!}
                                                                                    {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                                </div>
                                                                                <div class="col-md-4"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <div class="row">
                                                                            <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                                                {!! Form::label('','18. Directorship in other company(s) (if any)',['class'=>'col-md-4 text-left required-star']) !!}
                                                                                <div class="col-md-4">
                                                                                    {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Directorship in other company', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                                    {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Number of Subscribed Shares']) !!}
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
                                                                                    {!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'National ID/Passport No']) !!}
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

                                                                    <div class="row" style="margin-bottom: 10px;">
                                                                        <div class="col-md-12 text-center">
                                                                            <button class="btn btn-info btn-sm">Edit</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="button" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <h3 class="text-center">Step Five</h3>
                                    <fieldset>
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><strong>3. Witness </strong></div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <h4 class="text-center"><strong>Witness 1</strong></h4>
                                                        <div class="form-group row">
                                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('[', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4 class="text-center"><strong>Witness 2</strong></h4>
                                                        <div class="form-group row">
                                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            {!! Form::label('','2. Address',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>

                                                        <div class="form-group row">
                                                            {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-8">
                                                                {!! Form::text('[', '', ['class' => 'form-control input-md required']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><strong>3. Witness </strong></div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','2. Position',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::select('',['Chairman' => 'Chairman','CEO' => 'CEO','MD' => 'MD'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','3. Address',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                        {!! Form::label('','District',['class'=>'col-md-4 text-left ']) !!}
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </fieldset>
                                    <h3 class="text-center">Step Six</h3>
                                    <fieldset>
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><strong>2. Declaration upload</strong></div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','2. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::select('',['Senior Developer' => 'Senior Developer','Junior Developer' => 'Junior Developer'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','3. Organization(applicable for advocate only)',['class'=>'col-md-4 text-left']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Organization']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','4. Address',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                        {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <strong> F. Upload Softcopy of Documents</strong>
                                            </div>
                                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}

                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','1. Document Name',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::select('',['First Doc' => 'First Doc','Second Doc' => 'Second Doc'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','2. Scaned Copy(.ZIP {max size 200 KB})',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::file('', ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <strong style="margin-left: 15px;">* Steps :</strong><br/><br/>
                                                <strong style="margin-left: 15px;">1. Enter and save all the information of original
                                                    registration application page</strong><br/><br/>
                                                <strong style="margin-left: 15px;">2. Enter memorandum of Association
                                                    (MOA)</strong><br/><br/>
                                                <strong style="margin-left: 15px;">3. Enter Articles of association AOA a) First
                                                    (part-1) b) Then Part-2</strong><br/><br/>
                                                <strong style="margin-left: 15px;">4. Print the subscriber page of MOA as directed and
                                                    Form-IX and after signing, upload the signed scanned copy as .ZIP
                                                    format.</strong><br/><br/>
                                                <strong style="margin-left: 15px;">5. Check and confirm MOA AND AOA by viewing your
                                                    entered information.</strong><br/><br/>
                                                <strong style="margin-left: 15px;">6. Finally Submit the page and continue to get the
                                                    acknowledgement of payment.</strong><br/><br/>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <strong>3. Memorandum of Association (include top cover) pages
                                                                (no.)</strong>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {!! Form::text('', '', ['class' => 'form-control required input-md']) !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <strong>4. Article of Association (include top cover) pages (no.)</strong>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {!! Form::text('', '', ['class' => 'form-control required input-md']) !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-info btn-sm">Upload</button>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <strong class="text-center">Softcopy is not uploaded succenssfully, please
                                                            reduce file size as recommended</strong>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary btn-sm">Submit</button>
                                                    </div>
                                                </div>
                                            </div>

                                            {!! Form::close() !!}
                                        </div>
                                    </fieldset>
                                    <h3 class="text-center">Step Seven</h3>
                                    <fieldset>
                                        <div class="panel panel-info">
                                            <div class="panel-heading"><strong>2. Declaration</strong></div>
                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','2. Position',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::select('',['Senior Developer' => 'Senior Developer','Junior Developer' => 'Junior Developer'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','3. Organization(applicable for advocate only)',['class'=>'col-md-4 text-left']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Organization']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','4. Address',['class'=>'col-md-4 text-left required-star']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address', 'rows' => 2, 'cols' => 1]) !!}
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
                                                                        {!! Form::label('','District',['class'=>'col-md-4 text-left']) !!}
                                                                    </div>
                                                                    <div class="col-md-8">
                                                                        {!! Form::select('',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                        {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel panel-info">
                                            <div class="panel-heading">
                                                <strong> F. Upload Softcopy of Documents</strong>
                                            </div>
                                            {!! Form::open(array('url' => '', 'method' => 'post', 'files' => true, 'role' => 'form', 'id'=> '')) !!}

                                            <div class="panel-body">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','1. Document Name',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::select('',['First Doc' => 'First Doc','Second Doc' => 'Second Doc'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                                            {!! Form::label('','2. Scaned Copy(.ZIP {max size 200 KB})',['class'=>'col-md-4 text-left ']) !!}
                                                            <div class="col-md-4">
                                                                {!! Form::file('', ['class' => 'form-control input-md']) !!}
                                                                {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                                            </div>
                                                            <div class="col-md-4"></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <strong style="margin-left: 15px;">* Steps :</strong><br/><br/>
                                                <strong style="margin-left: 15px;">1. Enter and save all the information of original
                                                    registration application page</strong><br/><br/>
                                                <strong style="margin-left: 15px;">2. Enter memorandum of Association
                                                    (MOA)</strong><br/><br/>
                                                <strong style="margin-left: 15px;">3. Enter Articles of association AOA a) First
                                                    (part-1) b) Then Part-2</strong><br/><br/>
                                                <strong style="margin-left: 15px;">4. Print the subscriber page of MOA as directed and
                                                    Form-IX and after signing, upload the signed scanned copy as .ZIP
                                                    format.</strong><br/><br/>
                                                <strong style="margin-left: 15px;">5. Check and confirm MOA AND AOA by viewing your
                                                    entered information.</strong><br/><br/>
                                                <strong style="margin-left: 15px;">6. Finally Submit the page and continue to get the
                                                    acknowledgement of payment.</strong><br/><br/>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <strong>3. Memorandum of Association (include top cover) pages
                                                                (no.)</strong>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {!! Form::text('', '', ['class' => 'form-control required input-md']) !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="col-md-5">
                                                            <strong>4. Article of Association (include top cover) pages (no.)</strong>
                                                        </div>
                                                        <div class="col-md-2">
                                                            {!! Form::text('', '', ['class' => 'form-control required input-md']) !!}
                                                        </div>
                                                        <div class="col-md-5"></div>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-info btn-sm">Upload</button>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <strong class="text-center">Softcopy is not uploaded succenssfully, please
                                                            reduce file size as recommended</strong>
                                                    </div>
                                                </div>
                                                <br/>

                                                <div class="row text-center">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-primary btn-sm">Submit</button>
                                                    </div>
                                                </div>
                                            </div>

                                            {!! Form::close() !!}
                                        </div>
                                    </fieldset>

                                    <h3>Submit</h3>
                                    <fieldset>
                                        <input id="acceptTerms" name="acceptTerms" type="checkbox" class="required"> <label for="acceptTerms">I agree with the Terms and Conditions.</label>
                                    </fieldset>
                                    <input type="submit" class="btn btn-primary btn-md cancel" value="Save As Draft" name="sv_draft">
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
    </section>
@endsection
@section('footer-script')
    <link rel="stylesheet" href="{{ url('assets/css/jquery.steps.css') }}">
    <script src="{{ asset("assets/scripts/jquery.steps.js") }}"></script>
    <script>
        function uploadDocument(targets, id, vField, isRequired) {
            var inputFile = $("#" + id).val();
            if (inputFile == ''){
                $("#" + id).html('');
                document.getElementById("isRequired").value = '';
                document.getElementById("selected_file").value = '';
                document.getElementById("validateFieldName").value = '';
                document.getElementById(targets).innerHTML = '<input type="hidden" class="required" value="" id="' + vField + '" name="' + vField + '">';
                if ($('#label_' + id).length) $('#label_' + id).remove();
                return false;
            }

            try{
                document.getElementById("isRequired").value = isRequired;
                document.getElementById("selected_file").value = id;
                document.getElementById("validateFieldName").value = vField;
                document.getElementById(targets).style.color = "red";
                var action = "{{url('/application/upload-document')}}";
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
                    dataType: 'text', // what to expect back from the PHP script, if anything
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
                        var newInput = $('<label id="label_' + id + '"><br/><b>File: ' + fileNameArr[l - 1] + '</b></label>');
                        $("#" + id).after(newInput);
                        //check valid data
                        var validate_field = $('#' + vField).val();
                        if (validate_field == ''){
                            document.getElementById(id).value = '';
                        }
                    }
                });
            } catch (err) {
                document.getElementById(targets).innerHTML = "Sorry! Something Wrong.";
            }
        } // end of uploadDocument function
        $(document).ready(function () {
            var form = $("#app-form").show();
            form.validate({
                errorPlacement: function errorPlacement(error, element) { element.before(error); },
                rules: {

                }
            });
            form.children("div").steps({
                headerTag: "h3",
                bodyTag: "fieldset",
                transitionEffect: "slideLeft",
                onStepChanging: function (event, currentIndex, newIndex)
                {
                    form.validate().settings.ignore = ":disabled,:hidden";
                    // document.getElementById("first_form").submit(function(){
                    //     console.log(this.serialize());
                    // });
                    console.log(event,currentIndex,newIndex);
                    if(currentIndex === 0){

                       console.log(formdata)
                    }
                    return form.valid();

                },
                onFinishing: function (event, currentIndex)
                {
                    form.validate().settings.ignore = ":disabled";
                    return form.valid();
                },
                onFinished: function (event, currentIndex)
                {
                    //alert("Submitted!");
                }
            });
            var popupWindow = null;
            $('.finish').on('click', function (e) {
                if ($('#acceptTerms').is(":checked")){
                    $('#acceptTerms').removeClass('error');
                    $('#home').css({"display": "none"});
                    popupWindow = window.open('<?php echo URL::to('/application/preview'); ?>', 'Sample', '');
                } else {
                    $('#acceptTerms').addClass('error');
                    return false;
                }
            });
        });

        function toolTipFunction() {
            $('[data-toggle="tooltip"]').tooltip();
        }
    </script>

    <script>
        $(document).ready(function () {
            $(document).on('click','#add_column',function () {
                var rowCount = $('#particular tr').length;
                $('#particular_body').append('<tr><td><input type="checkbox">&nbsp &nbsp '+ rowCount +' </td> <td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td> <td><textarea class="form-control"></textarea></td> <td><input type="text" class="form-control"></td></tr>')
            })
            $(document).on('click','#remove_column',function () {
                var rowCount = $('#particular tr').length;
                if(rowCount > 2) {
                    $('#particular tr:last').remove();
                }
            })
        })
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('click','#enter_info',function () {
                var rowCount = $('#list_of_subs tr').length;
                $('#list_of_subs_body').append('<tr><td><input type="checkbox">&nbsp &nbsp &nbsp' + rowCount +'</td> <td><input class="form-control" type="text"></td><td><input class="form-control" type="text"></td><td><input type="number" class="form-control"></td></tr>')
            })
            $(document).on('click','#remove_info',function () {
                var rowCount = $('#list_of_subs tr').length;
                if(rowCount > 2) {
                    $('#list_of_subs tr:last').remove();
                }
            })
        })
    </script>
@endsection