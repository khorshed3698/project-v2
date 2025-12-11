<div class="panel panel-primary">
    <div class="panel-heading"><h5><strong>Resubmission Information</strong></h5></div>
    <div class="panel-body" style="margin:6px;">

        {!! Form::open(array('url' => 'cda-lspp/store-resubmission','method' => 'post', 'enctype' =>'multipart/form-data', 'files' => 'true', 'role'=>'form', 'id'=>'resubmitForm')) !!}

        {!! Form::hidden('app_id', Encryption::encodeId($appInfo->id) ,['class' => 'form-control input-sm required', 'id'=>'app_id']) !!}

        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('incoming_type','Incoming Type :',['class'=>'text-left col-md-6 required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::select('incoming_type',  $shortfallAttachments, '', ['placeholder' => 'Select one',
                        'class' => 'form-control required','id'=>'incoming_type']) !!}
                        {!! $errors->first('incoming_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('remarks','Remarks :',['class'=>'text-left col-md-6 required-star']) !!}
                    <div class="col-md-6">
                        {!! Form::textarea('remarks', '',['class' => 'form-control required','id'=>'remarks']) !!}
                        {!! $errors->first('incoming_type','<span class="help-block">:message</span>') !!}
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <div class="row">
            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="form-group">
                        <div class="row">
                            {!! Form::label('file_1','File 1 :',['class'=>'col-md-2 text-left']) !!}
                            <div class="col-md-4">
                                {!! Form::text('file_title_1', $appInfo->file_title_1,['class' => 'form-control', 'id' => 'file_title_1']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::file('file_link_1',['onchange'=>'uploadSingleDocument(this)','style'=>'width:100%', 'id'=>'file_link_1','class'=>'']) !!}
                                <span class="text-info" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {!! Form::label('file_2','File 2 :',['class'=>'text-left col-md-2']) !!}
                            <div class="col-md-4">
                                {!! Form::text('file_title_2', $appInfo->file_title_2,['class' => 'form-control', 'id' => 'file_title_2']) !!}
                            </div>

                            <div class="col-md-6 {{$errors->has('file_link_1') ? 'has-error': ''}}">
                                {!! Form::file('file_link_2',['onchange'=>'uploadSingleDocument(this)','style'=>'width:100%', 'id'=>'file_link_2','class'=>'']) !!}
                                <span class="text-info" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {!! Form::label('file_3','File 3 :',['class'=>'text-left col-md-2']) !!}
                            <div class="col-md-4">
                                {!! Form::text('file_title_3', $appInfo->file_title_3,['class' => 'form-control', 'id' => 'file_title_3']) !!}
                            </div>

                            <div class="col-md-6 {{$errors->has('file_link_3') ? 'has-error': ''}}">
                                {!! Form::file('file_link_3',['onchange'=>'uploadSingleDocument(this)','style'=>'width:100%', 'id'=>'file_link_3','class'=>'']) !!}
                                <span class="text-info" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {!! Form::label('file_4','File 4 :',['class'=>'text-left col-md-2']) !!}
                            <div class="col-md-4">
                                {!! Form::text('file_title_4', $appInfo->file_title_4,['class' => 'form-control', 'id' => 'file_title_4']) !!}
                            </div>

                            <div class="col-md-6 {{$errors->has('file_link_4') ? 'has-error': ''}}">
                                {!! Form::file('file_link_4',['onchange'=>'uploadSingleDocument(this)','style'=>'width:100%', 'id'=>'file_link_4','class'=>'']) !!}
                                <span class="text-info" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            {!! Form::label('file_5','File 5 :',['class'=>'text-left col-md-2']) !!}
                            <div class="col-md-4">
                                {!! Form::text('file_title_5', $appInfo->file_title_5,['class' => 'form-control', 'id' => 'file_title_5']) !!}
                            </div>

                            <div class="col-md-6 {{$errors->has('file_link_5') ? 'has-error': ''}}">
                                {!! Form::file('file_link_5',['onchange'=>'uploadSingleDocument(this)','style'=>'width:100%', 'id'=>'file_link_5','class'=>'']) !!}
                                <span class="text-info" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <span><b>Shortfall Reason: </b>{{ $appInfo->process_desc ? strip_tags($appInfo->process_desc) : ''  }}</span>
                </div>
            </div>
        </div>


        <button type="submit" id="resubmitForm" style="cursor: pointer;"
                class="btn btn-success btn-md"
                value="Re-Submit" name="actionBtn">Resubmit
        </button>
    {!! Form::close() !!}<!-- /.form end -->
    </div>
</div>