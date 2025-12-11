@extends('layouts.admin')

@section('page_heading',trans('messages.area_list'))

@section('content')

    <div class="col-lg-12">
        <form method="POST"  accept-charset="UTF-8" class="form-horizontal" id="user_edit_form">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5><strong>Company Details of : {{$companyDetails->company_name}}</strong></h5>
                </div> <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-9">
                        <dl class="dls-horizontal">

                            <dt>Company Name :</dt>
                            <dd>
                                <?php
                                $returnData = $companyDetails->company_name;
                                if($companyDetails->company_name_bn)
                                    $returnData .= " (". $companyDetails->company_name_bn.")";
                                if($companyDetails->divisionName)
                                    $returnData .= ", ". $companyDetails->divisionName;
                                if($companyDetails->districtName)
                                    $returnData .= ", ". $companyDetails->districtName;
                                echo $returnData;
                                ?>
                            </dd>
                            <dt>Status :</dt>
                            <dd>
                                @if($companyDetails->is_rejected == 'no')
                                    {{ $companyDetails->is_approved == 1 ? 'Approved' : 'Not Approved Yet' }}</dd>
                            @else
                                Rejected
                            @endif
                            <dt>Created By :</dt>
                            <dd>{{ $companyDetails->user_first_name . ' ' . $companyDetails->user_middle_name . ' ' . $companyDetails->user_last_name }}</dd>
                        </dl>
                    </div>
                </div><!-- /.box -->

                <div class="panel-footer">
                    <div class="pull-left">
                        <a href="{{ url('/settings/company-info') }}" class="btn btn-sm btn-default"><i class="fa fa-times"></i> Close</a>
                    </div>
                    @if($companyDetails->is_approved == '0' && $companyDetails->is_archive == '0' && $companyDetails->is_rejected == 'no')
                        <div class="pull-right">
                            <a href="{{URL::to('settings/approved-change-status/'.\App\Libraries\Encryption::encodeId($companyDetails->id))}}" class="btn btn-success"
                               onclick="return confirm('Are you sure?')"><i class="fa fa-unlock-alt"></i> Make Approved </a>

                            <a href="{{URL::to('settings/rejected-change-status/'.\App\Libraries\Encryption::encodeId($companyDetails->id))}}" class="btn btn-danger"
                               onclick="return confirm('Are you sure?')"><i class="fa fa-remove"></i> {{$companyDetails->is_approved == 0 ? 'Rejected ' : ''}}</a>
                        </div>
                    @endif
                    <div class="clearfix"></div>
                </div>
            </div>
        </form>
    </div>

@endsection