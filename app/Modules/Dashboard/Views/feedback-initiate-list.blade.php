<div class="alert alert-danger" role="alert">
    You have an investment support form. Please submit your updated investment information. Unless submitting the form within the deadline, you may unable to submit others services.
</div>

<div class="dash-box" style="padding: 15px 25px; !important;">
    <div class="row">
        <div class="table-responsive">
            <table class="table table-bordered" style="margin-bottom: 0px !important;">
                <thead>
                <tr>
                    <th scope="col" class="text-center">Tracking No</th>
                    <th scope="col" class="text-center">Service</th>
                    <th scope="col" class="text-center">Submit Date</th>
                    <th scope="col" class="text-center">Last Status</th>
                    <th scope="col" class="text-center">Application Info</th>
                    <th scope="col" class="text-center">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($feedback_initiate_list as $value)
                <tr>
                    <td class="text-center">{{ $value->tracking_no }}</td>
                    <td class="text-center">BIDA Registration</td>
                    <td class="text-center">{{ ($value->irms_feedback_deadline != '0000-00-00') ? date('d-m-Y', strtotime($value->irms_feedback_deadline)) : '' }}</td>
                    <td class="text-center">
                        <span>{{ $value->status_name }}</span><br/>
                        <span>{{ ($value->completed_date != '0000-00-00 00:00:00') ? date('d-m-Y H:i:s A', strtotime($value->completed_date)) : '' }}</span>
                    </td>
                    <td>
                        {!! getListDataFromJson($value->json_object, CommonFunction::getCompanyNameById($value->company_id)) !!}
                    </td>
                    <td class="text-center">
                        <a href="{{ url('process/bida-registration/view-app/'. Encryption::encodeId($value->ref_id)). '/' . Encryption::encodeId(102) }}" class="btn btn-xs btn-primary button-color"><i class="fa fa-folder-open"></i> Open</a>
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