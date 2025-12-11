<div class="col-sm-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h4><strong><i class="fa fa-bell"></i> Attention here please !!!</strong></h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <strong class="text-danger">
                        <br/>
                        Dear {!! \App\Libraries\CommonFunction::getUserFullName() !!},
                        <br/>
                        <br/>
                        <p>
                            Your account is awaiting approval by the {!! config('app.project_name') !!} system administrator. You will
                            not be able to fully interact
                            with all features of this system until your account is approved.
                            <br/>
                            Kindly contact to System Administrator or IT Help Desk officer to approve your account.
                            Once approved or denied you will received an email notice.
                            <br/>
                            You will get all the available functionality once your account is approved!
                        </p>
                        <br/>
                        Thank you!<br/>
                        {{config('app.project_name')}}
                    </strong>
                </div>
                <div class="col-md-2">
                    <img class="img-responsive" src="{{ url('assets/images/alarm_clock_time_bell_wait-512.png') }}" alt="alarm_clock_time_bell_wait-512.png">
                </div>
            </div>
        </div>
    </div>
</div>