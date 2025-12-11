
@if($user_type === '5x505' && count($irms_feedback_initiate_list) > 0)

    <div class="row"  style="padding: 0px 20px;">
        <div class="col-md-12">
            <p class="dash-box-heading" style="margin-bottom: 0;">Investment support list</p>
        </div>
    </div>

    @if(Session::has('irms_feedback_tracking_number') && !empty(Session::get('irms_feedback_tracking_number')))
        <div class="alert alert-danger" role="alert">
            You have an investment support form. Please submit your updated investment information. Unless submitting the form within the deadline, you may unable to submit others services.
        </div>
    @endif

    <div class="dash-box" style="padding: 15px 25px; !important;">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered" style="margin-bottom: 0 !important;">
                    <thead>
                    <tr>
                        <th scope="col" class="text-center">Tracking No</th>
                        <th scope="col" class="text-center">Service</th>
                        <th scope="col" class="text-center">Submit Before</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Application Info.</th>
                        <th scope="col" class="text-center">Remarks</th>
                        <th scope="col" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($irms_feedback_initiate_list as $value)

                        <tr class="{{ in_array($value->tracking_no, Session::get('irms_feedback_tracking_number')) ? 'alert-warning' : '' }}">
                            <td class="text-center">{{ $value->tracking_no }}</td>
                            <td class="text-center">BIDA Registration</td>
                            <td class="text-center">{{ ($value->feedback_deadline != '0000-00-00') ? date('Y-m-d', strtotime($value->feedback_deadline)) : '' }}</td>
                            <td class="text-center">
                            <span>
                                @if($value->irms_status_id == -1)
                                    Draft
                                @elseif($value->irms_status_id == 1)
                                    Submit
                                @elseif($value->irms_status_id == 5)
                                    Shortfall
                                @else
                                    Pending
                                @endif
                            </span>
                            </td>
                            <td>
                                {!! getListDataFromJson($value->json_object, CommonFunction::getCompanyNameById($value->company_id)) !!}
                            </td>
                            <td class="text-center">{{ $value->remarks }}</td>
                            <td class="text-center">
                                @if(in_array($value->irms_status_id, [0, -1, 5]))
                                    <a href="{{ url('irms-portal-login/'.Encryption::encode($value->tracking_no) ) }}" class="btn btn-xs btn-primary button-color"><i class="fa fa-folder-open"></i> Open</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-danger">No data found!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif