<div id="list_3" class="tab-pane">
    <div class="panel-body">
        <div class="row">

            @if(!empty($trainingData))
                <div class="col-md-12">
                    <table class="table table-striped" aria-label="Detailed Report Data Table">
                        <thead>
                        <tr>
                            <th>{{trans('messages.subject')}}</th>
                            <th> {{trans('messages.user_types')}}</th>
                            <th>{{trans('messages.Training_time')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($trainingData as $training)
                            <tr>
                                <td width="65%">
                                    <span class="text-info">{!! $training->title !!}</span><span><div style="clear: both;"></div>
                                        <button type='button' class='btn btn-info btn-xs training_heading'>{{trans('messages.details')}}</button></span>
                                    <span class="training_details" style="display:none;clear: both;">{!! $training->description !!}</span>
                                </td>
                                <td width="25%">{!! $training->public_user_types !!}</td>
                                <td width="10%">
								    <span class="schedule">
										<a href="#schedule-box" class="btn btn-xs btn-primary scheduleDetails" id="{{ \App\Libraries\Encryption::encodeId($training->id) }}"><i class="fa fa-clock-o"></i> {{trans('messages.schedule')}}</a>
															</span>
                                    <span class="schedule_details" style="display:none;"></span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="col-md-12">
                    <div class="well well-sm">
                        <span><p class="lead text-center text-info">বর্তমানে কোন প্রশিক্ষণ সূচি নেই।<br>নতুন সময়-সূচি প্রাপ্তি সাপেক্ষে  প্রকাশ করা হবে। </p></span>
                    </div>
                </div>
            @endif
            <div class="panel panel-body"></div>
        </div>
    </div>
    <div class="scheduleInfo" id="schedule-box">
    </div>
</div>