<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {!! Form::open(array('url' => 'remittance-new/new-reg','method' => 'post','id' => 'RemittanceNewForm','enctype'=>'multipart/form-data',
                                'method' => 'post', 'files' => true, 'role'=>'form')) !!}

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <strong>A. General Information (as of Memorandum and Articles of Association, Form-VI) Help</strong>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('entry_name','1. Name of the Entity',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        <span>Code Orbit Engineering Limited</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! Form::label('entry_type','2. Entity Type',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-8">
                                        <span>Private Company</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('liability_type') ? 'has-error': ''}}">
                                    {!! Form::label('liability_type','3. Liability Type',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-3">
                                        {!! Form::select('liability_type',['Technical know-how' => 'Technical know-how','Technical Assistance' => 'Technical Assistance'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('liability_type','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-5"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('address_entity') ? 'has-error': ''}}">
                                    {!! Form::label('address_entity','4. Address of the Entity',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::textarea('address_entity', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                        {!! $errors->first('address_entity','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('address_district') ? 'has-error': ''}}">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-4">
                                                {!! Form::label('address_entity','District',['class'=>'col-md-4 text-left required-star']) !!}
                                            </div>
                                            <div class="col-md-8">
                                                {!! Form::select('address_district',['Dhaka' => 'Dhaka','Feni' => 'Feni'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                                {!! $errors->first('address_district','<span class="help-block">:message</span>') !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('entity_email') ? 'has-error': ''}}">
                                    {!! Form::label('entity_email','4. Entity Email Address',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::email('entity_email', '', ['class' => 'form-control input-md required','placeholder' => 'Entity Email']) !!}
                                        {!! $errors->first('entity_email','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('business_objective') ? 'has-error': ''}}">
                                    {!! Form::label('business_objective','5. Main Business objective',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::text('business_objective', '', ['class' => 'form-control required input-md','placeholder' => 'Business Objective']) !!}
                                        {!! $errors->first('business_objective','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('business_sector') ? 'has-error': ''}}">
                                    {!! Form::label('business_sector','6. Business Sector',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::select('business_sector',['Local' => 'Local','Foregin' => 'Foregin','Others' => 'Others'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('business_sector','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('business_sub_sector') ? 'has-error': ''}}">
                                    {!! Form::label('business_sub_sector','7. Business Sub-Sector',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-6">
                                        {!! Form::select('business_sub_sector',['Local Sub' => 'Local Sub','Foregin Sub' => 'Foregin Sub','Others Sub' => 'Others Sub'], null,['class' => 'form-control input-md required','placeholder' => 'Select One']) !!}
                                        {!! $errors->first('business_sub_sector','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('authorized_capital') ? 'has-error': ''}}">
                                    {!! Form::label('authorized_capital','8. Authorized Capital (BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('authorized_capital', '', ['class' => 'form-control required input-md','placeholder' => 'BDT']) !!}
                                        {!! $errors->first('authorized_capital','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4">
                                        <span>{Authorized Capital =<br/> {Shares No.} X {Value of each Share}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('share_number') ? 'has-error': ''}}">
                                    {!! Form::label('share_number','9. Number of shares',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('share_number', '', ['class' => 'form-control required input-md','placeholder' => 'Number of Share']) !!}
                                        {!! $errors->first('share_number','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('each_share') ? 'has-error': ''}}">
                                    {!! Form::label('each_share','10. Value of each share(BDT)',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('each_share', '', ['class' => 'form-control required input-md','placeholder' => 'Value of each share(BDT)']) !!}
                                        {!! $errors->first('each_share','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('minimum_no') ? 'has-error': ''}}">
                                    {!! Form::label('minimum_no','11. Minimum No of Directors',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('minimum_no', '', ['class' => 'form-control required input-md','placeholder' => 'Minimum No of Directors']) !!}
                                        {!! $errors->first('minimum_no','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4">
                                        <span>(Minimum Two{2})</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('maximum_no') ? 'has-error': ''}}">
                                    {!! Form::label('maximum_no','12. Maximum No of Directors',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('maximum_no', '', ['class' => 'form-control required input-md','placeholder' => 'Maximum No of Directors']) !!}
                                        {!! $errors->first('maximum_no','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4">
                                        <span>(Maximum fifty{50})</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('quorum_agm') ? 'has-error': ''}}">
                                    {!! Form::label('quorum_agm','13. Quorum of AGM/EGM',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('quorum_agm', '', ['class' => 'form-control required input-md','placeholder' => 'Maximum No of Directors']) !!}
                                        {!! $errors->first('quorum_agm','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <span>(Maximum tow{2})</span>
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::text('quorum_agm', '', ['class' => 'form-control required input-md','placeholder' => 'three']) !!}
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
                                <div class="col-md-12 {{$errors->has('quorum_agm') ? 'has-error': ''}}">
                                    {!! Form::label('quorum_agm','14. Quorum of Board of Directors Meeting',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('quorum_agm', '', ['class' => 'form-control required input-md','placeholder' => 'Maximum No of Directors']) !!}
                                        {!! $errors->first('quorum_agm','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <span>(Maximum tow{2})</span>
                                            </div>
                                            <div class="col-md-4">
                                                {!! Form::text('quorum_agm', '', ['class' => 'form-control required input-md','placeholder' => 'three']) !!}
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
                                <div class="col-md-12 {{$errors->has('duration_chairmanship') ? 'has-error': ''}}">
                                    {!! Form::label('duration_chairmanship','15. Duration for Chairmanship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('duration_chairmanship', '', ['class' => 'form-control required input-md','placeholder' => 'Duration for Chairmanship(year)']) !!}
                                        {!! $errors->first('duration_chairmanship','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 {{$errors->has('duration_chairmanship') ? 'has-error': ''}}">
                                    {!! Form::label('duration_chairmanship','16. Duration for Managing Directorship(year)',['class'=>'col-md-4 text-left required-star']) !!}
                                    <div class="col-md-4">
                                        {!! Form::number('duration_chairmanship', '', ['class' => 'form-control required input-md','placeholder' => 'Managing Directorship(year']) !!}
                                        {!! $errors->first('duration_chairmanship','<span class="help-block">:message</span>') !!}
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                            </div>
                        </div>

                        </div>
                    </div>


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; C. List of Subscribers/Directors/Managers/Managing Agents(as of Memorandum and Articles of Association, Form-IX, form-X, Form-XII)</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <strong>(Directors: Minimum-two{2}, Maximum-fifty{50})<br/>{Subscribers/Directors: Minimum-2, Maximum-50}</strong><br/><br/>
                                    <table aria-label="Detailed List of Subscribers/Directors" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th width="8%" class="text-center">SI.</th>
                                                <th width="30%" class="text-center">Name</th>
                                                <th width="27%" class="text-center">Position</th>
                                                <th width="35%" class="text-center">Number of Subscribed Shares</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!} &nbsp;&nbsp; 1 </label></td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!} &nbsp;&nbsp; 2 </label></td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                            </tr>
                                            <tr>
                                                <td><label class="checkbox-inline">  {!! Form::checkbox('') !!} &nbsp;&nbsp; 3 </label></td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Name']) !!}</td>
                                                <td>{!! Form::text('', '', ['class' => 'form-control required input-md','placeholder' => 'Position']) !!}</td>
                                                <td>{!! Form::number('', '', ['class' => 'form-control required input-md','placeholder' => 'Name of Subscribed shares']) !!}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div class="row text-center">
                                        <div class="col-md-12">
                                            <button class="btn btn-info btn-sm">Enter Information</button>
                                            <button class="btn btn-danger btn-sm">Remove Row</button>
                                            <button class="btn btn-primary btn-sm">Edit Information</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; D. Witnesses</strong>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4 class="text-center"><strong>Witness 1</strong></h4><br/>
                                    <div class="form-group row">
                                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('','2. Address',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('[', '', ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4 class="text-center"><strong>Witness 2</strong></h4><br/>
                                    <div class="form-group row">
                                        {!! Form::label('','1. Name',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        {!! Form::label('','2. Address',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::textarea('', '', ['class' => 'form-control input-sm required','placeholder' => 'Address of Entity', 'rows' => 2, 'cols' => 1]) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {!! Form::label('','3. Phone',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('', '', ['class' => 'form-control input-md required']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        {!! Form::label('','4. National ID',['class'=>'col-md-4 text-left required-star']) !!}
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
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; D. Forms/Documents Presented for Filing By</strong>
                        </div>
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
                                        {!! Form::label('','3. Address',['class'=>'col-md-4 text-left required-star']) !!}
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
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; Particulars of Individual Subscriber/Director/Manager/Managing Agen(as of Memorandum and Articles of Association, Form-IX, X, XII)</strong>
                        </div>
                        <br class="panel-body">
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
                                                    <label class="radio-inline">  {!! Form::radio('int_property_reg_in_bd', 'Yes',false, ['id' => 'yesCheck']) !!} Yes </label>
                                                    <label class="radio-inline">   {!! Form::radio('int_property_reg_in_bd', 'No',true,['id' => 'noCheck']) !!} No </label>
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

                            <strong style="margin-left: 15px;">(Subscribed in the Memorindum and Articles of Association)</strong><br/><br/>
                            <strong style="margin-left: 15px;">* Required information for complete submission</strong>

                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-info btn-sm">Edit</button>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; E. declaration on Registration of the Company Signed By (as if Form-I)</strong>
                        </div>
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
                                        {!! Form::label('','3. Organization(applicable for advocate only)',['class'=>'col-md-4 text-left required-star']) !!}
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
                        </div>
                    </div>

                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <strong><i class="fas fa-list"></i>&nbsp;&nbsp; F. Upload Softcopy of Documents</strong>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12 {{$errors->has('') ? 'has-error': ''}}">
                                        {!! Form::label('','1. Document Name',['class'=>'col-md-4 text-left required-star']) !!}
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
                                        {!! Form::label('','2. Scaned Copy(.ZIP {max size 200 KB})',['class'=>'col-md-4 text-left required-star']) !!}
                                        <div class="col-md-4">
                                            {!! Form::file('', ['class' => 'form-control input-md']) !!}
                                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                </div>
                            </div>

                            <strong style="margin-left: 15px;">* Steps :</strong><br/><br/>
                            <strong style="margin-left: 15px;">1. Enter and save all the information of original registration application page</strong><br/><br/>
                            <strong style="margin-left: 15px;">2. Enter memorandum of Association (MOA)</strong><br/><br/>
                            <strong style="margin-left: 15px;">3. Enter Articles of association AOA a) First (part-1) b) Then Part-2</strong><br/><br/>
                            <strong style="margin-left: 15px;">4. Print the subscriber page of MOA as directed and Form-IX and after signing, upload the signed scanned copy as .ZIP format.</strong><br/><br/>
                            <strong style="margin-left: 15px;">5. Check and confirm MOA AND AOA by viewing your entered information.</strong><br/><br/>
                            <strong style="margin-left: 15px;">6. Finally Submit the page and continue to get the acknowledgement of payment.</strong><br/><br/>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-5">
                                        <strong>3. Memorandum of Association (include top cover) pages (no.)</strong>
                                    </div>
                                    <div class="col-md-2">
                                        {!! Form::text('', '', ['class' => 'form-control required input-md']) !!}
                                    </div>
                                    <div class="col-md-5"></div>
                                </div>
                            </div><br/>

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
                            </div><br/>

                            <div class="row text-center">
                                <div class="col-md-12">
                                    <button class="btn btn-info btn-sm">Upload</button>
                                </div>
                            </div><br/>

                            <div class="row text-center">
                                <div class="col-md-12">
                                    <strong class="text-center">Softcopy is not uploaded succenssfully, please reduce file size as recommended</strong>
                                </div>
                            </div><br/>

                            <div class="row text-center">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <table aria-label="Detailed Report Data Table">
                                <thead>
                                <tr>
                                    <th class="text-center">Form-I</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <p style="width: 450px; display: table;">
                                            <span style="display: table-cell; width: 180px;">Name of the Company:</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 40px;">Declaration of compliance with the requirements of the companies act, 1994 made</span></td>
                                </tr>
                                <tr>
                                    <td> pursuant to section 25 (2) on behalf of a company proposed to be Registered as the</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 150px;">Presented for filing by</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 20px;"> I,</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            <span style="display: table-cell; width: 20px;"> of</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>do solemnly and sincerely declare that I am an Advocate* / Attorney/ Apleader entitled</td>
                                </tr>
                                <tr>
                                    <td>to appear before High Court who is engaged in the formation of the company/ a</td>
                                </tr>
                                <tr>
                                    <td>person named in the Articles as a Director/ Manager/ Secretary of the</td>
                                </tr>
                                <tr>

                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            <span style="display: table-cell; width: 230px;">and and that all the requirements of</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>the Companies Act, 1994 in respact of maters precedent to the registration of the said</td>
                                </tr>
                                <tr>
                                    <td>company and incidental there to have been complied with, save only the payment to</td>
                                </tr>
                                <tr>
                                    <td>the fees and sums payable on registration and I make the solemn declaration</td>
                                </tr>
                                <tr>
                                    <td>conscientiously believing the same to be true.</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="margin-top: 40px;margin-bottom: 30px;">
                                            <span style="margin-left: 600px;">Signature</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Node: The declaration need not to be-</td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 40px;">(a) Signed before a magistrate or an officer competent to administer others or</span></td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 40px;">(b) Stamps as a affidavit</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center">* Strike out the portion which does not apply</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6"></div>
                    </div>


                    <div class="row">
                        <div class="col-md-8">
                            <table aria-label="Detailed Report Data Table">
                                <thead>
                                <tr>
                                    <th class="text-center">Form VI</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 180px;">Name of the Company:</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 180px;">Presented for filing by</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Name Here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 30px;">To,</span></td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 30px;">The Register of Joint Stock Companies</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 35%; display: table;margin-left: 30px;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                            <span style="margin-left: 30px;display: table-cell; border-bottom: 1px solid black;"></span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>with Section 77 of the Companies Act, 1994 that the Registered office of the Company (a) is</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 80px;">situated</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; width: 130px;">was removed from</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>to</td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;">
                                            <span style="display: table-cell; border-bottom: 1px solid black;">Text write here</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 70%; display: table;margin-bottom: 60px;">
                                            <span style="display: table-cell; width: 50px;">on the</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            <span style="display: table-cell; width: 20px;">20</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 500px;">Signature</span></td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 495px;">Designation</span></td>
                                </tr>
                                <tr>
                                    <td><span style="margin-left: 400px;">(Sate whether Director, Manager or Secretary)</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <p style="width: 100%; display: table;margin-top: 40px;">
                                            <span style="display: table-cell; width: 20px;"> Date,</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            <span style="display: table-cell; width: 60px;">day of</span>
                                            <span style="display: table-cell; border-bottom: 1px solid black;"></span>
                                            <span style="display: table-cell; width: 20px;">20</span>
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>N.B. -- The notice must be filed with Registrar within 28 days of incorporation or of the</td>
                                </tr>
                                <tr>
                                    <td>Change, as the case may be.</td>
                                </tr>
                                <tr>
                                    <td>a) Strike out the portion which does not apply.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-4"></div>
                    </div>

                {!! Form::close() !!}<!-- /.form end -->
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</body>
</html>