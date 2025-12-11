@extends('layouts.admin')

@section('page_heading',trans('messages.area_form'))

@section('content')
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">Feedback for <strong> {{\App\Modules\NewReg\Controllers\GetCompanyForeignController::getCompanyNameBysubimmsionNo($appInfo->submission_no)}}({{$appInfo->tracking_no}})</strong></div>
            <div class="panel-body">

                <?php
                 $feedback_no =1;
                ?>

                @foreach($feedback as $value)
                    <div class="panel panel-info" style="padding: 10px;">
                        <p><strong>SL No :{{$feedback_no}}</strong></p>
                        <p>{{$value->feedback_text}}</p><br>

                        <?php

                            if($value->attachment != ""){
                                echo "<p>Attachments:</p><p>";
                                $attachment_no =1;
                                $attached_files = explode('@',$value->attachment);
                                foreach ($attached_files as $link){
                                    echo '<a style ="padding:7px;" href=" '.url('/').'/'.$link.'" class="badge badge-secondary"><i class="fas fa-file-pdf"></i> Attachment '.$attachment_no.'</a> &nbsp;';
                                    $attachment_no++;
                                }
                                echo '<span class="pull-right">'.\App\Modules\NewReg\Controllers\FeedbackController::getUserFullNameById($value->created_by).'<br><span class="pull-right"> '.\App\Libraries\CommonFunction::updatedOn($value->created_at).'</span></span></p><br>';
                            }else{
                                echo '<p><span class="pull-right">'.\App\Modules\NewReg\Controllers\FeedbackController::getUserFullNameById($value->created_by).'<br><span class="pull-right"> '.\App\Libraries\CommonFunction::updatedOn($value->created_at).'</span></span></p><br>';

                            }
                        ?>

                            <?php
                            $feedback_no++;
                            ?>
                    </div>

                @endforeach

                <div class="panel panel-danger" style="padding: 10px; background-color: powderblue;">
                    {!! Form::open(array('url' => '/new-reg/feedback-store','method' => 'post', 'class' => 'form-horizontal', 'id' => 'feedback-form','enctype' =>'multipart/form-data', 'files' => 'true', 'role' => 'form')) !!}
                    <input type="hidden" name="_token" value="{{csrf_token()}}"/>
                    <input type="hidden" name="app_id" value="{{\App\Libraries\Encryption::encodeId($appInfo->id)}}"/>

                    <div class="form-group row">
                        <div class="col-md-10 col-md-offset-1">
                            {!! Form::textarea('feedback_text','', ['class' => 'form-control input-sm required','placeholder' => 'Input feedback here', 'rows' => 5, 'cols' => 1]) !!}
                            {!! $errors->first('','<span class="help-block">:message</span>') !!}
                        </div>
                    </div>

                    <div class="form-group" style="">
                        <div class="col-md-6 col-md-offset-1">
                            <div class="col-md-8 row  {{ $errors->has('attachment') ? 'has-error' : '' }}">
                                {!! Form::file('attachment[]',['onchange'=>'uploadDocument(this)','class' => 'form-control input-sm' ,'multiple']) !!}
                                <span class="text-danger" style="font-size: 9px; font-weight: bold">[File Format: *.pdf | Maximum file size 3 MB]</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-11 col-md-offset-1" >
                        <button type="submit" class="btn btn-primary" >
                            <i class="fa fa-chevron-circle-right"></i> submit</button>
                    </div><!-- /.box-footer -->
                    <div class="clearfix"></div>

                    {!! Form::close() !!}<!-- /.form end -->
                </div>

            </div>
        </div>
    </div>
    @endsection

    @section('footer-script')
        <script type="text/javascript">
            $("#feedback-form").validate();
        </script>
    @endsection

