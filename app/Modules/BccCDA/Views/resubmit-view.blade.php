<div class="panel panel-primary">
    <div class="panel-heading"><h5><strong>Resubmission Information</strong></h5></div>
    <div class="panel-body" style="margin:6px;">
        <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    {!! Form::label('incoming_type','Incoming Type :',['class'=>'text-left col-md-6 ']) !!}
                    <div class="col-md-6">
                        <span>{{ $resubmissionInfo->incoming_type_desc }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    {!! Form::label('remarks','Remarks :',['class'=>'text-left col-md-6 ']) !!}
                    <div class="col-md-6">
                        <span>{{ $resubmissionInfo->incoming_reason }}</span>
                    </div>
                </div>
            </div>
        </div>

        <br><br><br>

        <div class="form-group">
            <div class="row">
                {!! Form::label('file_1','File 1 :',['class'=>'text-left col-md-2']) !!}
                <div class="col-md-3">
                    {{ isset($resubmissionInfo->file_title_1) ? $resubmissionInfo->file_title_1 : "" }}
                </div>
                @if(!empty($resubmissionInfo->file_link_1))
                <div class="col-md-6">
                    <a target="_blank" class="btn btn-info" href="{{isset($resubmissionInfo->file_link_1) ? $resubmissionInfo->file_link_1 : "" }}">See File 1</a>
                </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                {!! Form::label('file_2','File 2 :',['class'=>'text-left col-md-2']) !!}
                <div class="col-md-3">
                    {{ isset($resubmissionInfo->file_title_2) ? $resubmissionInfo->file_title_2 : "" }}
                </div>
                @if(!empty($resubmissionInfo->file_link_2))
                <div class="col-md-6">
                    <a target="_blank" class="btn btn-info" href="{{isset($resubmissionInfo->file_link_2) ? $resubmissionInfo->file_link_2 : "" }}">See File 2</a>
                </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                {!! Form::label('file_3','File 3 :',['class'=>'text-left col-md-2']) !!}
                <div class="col-md-3">
                    {{ isset($resubmissionInfo->file_title_3) ? $resubmissionInfo->file_title_3 : "" }}
                </div>
                @if(!empty($resubmissionInfo->file_link_3))
                <div class="col-md-6">
                    <a target="_blank" class="btn btn-info" href="{{isset($resubmissionInfo->file_link_3) ? $resubmissionInfo->file_link_3 : "" }}">See File 3</a>
                </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                {!! Form::label('file_4','File 4 :',['class'=>'text-left col-md-2']) !!}
                <div class="col-md-3">
                    {{ isset($resubmissionInfo->file_title_4) ? $resubmissionInfo->file_title_4 : "" }}
                </div>
                @if(!empty($resubmissionInfo->file_link_4))
                <div class="col-md-6">
                    <a target="_blank" class="btn btn-info" href="{{isset($resubmissionInfo->file_link_4) ? $resubmissionInfo->file_link_4 : "" }}">See File 4</a>
                </div>
                @endif
            </div>
        </div>

        <div class="form-group">
            <div class="row">
                {!! Form::label('file_5','File 5 :',['class'=>'text-left col-md-2']) !!}
                <div class="col-md-3">
                    {{ isset($resubmissionInfo->file_title_5) ? $resubmissionInfo->file_title_5 : "" }}
                </div>
                @if(!empty($resubmissionInfo->file_link_5))
                <div class="col-md-6">
                    <a target="_blank" class="btn btn-info" href="{{isset($resubmissionInfo->file_link_5) ? $resubmissionInfo->file_link_5 : "" }}">See File 5</a>
                </div>
                @endif
            </div>
        </div>

    {!! Form::close() !!}<!-- /.form end -->
    </div>
</div>