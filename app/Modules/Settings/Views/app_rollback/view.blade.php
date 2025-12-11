@extends('layouts.admin')

@section('page_heading',trans('messages.rollback'))

@section('content')

    <?php
    $accessMode = ACL::getAccsessRight('settings');
    if (!ACL::isAllowed($accessMode, 'ARB')) {
        die('You have no access right! For more information please contact system admin.');
    }
    ?>

    @include('partials.messages')

    <div class="col-lg-12">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="pull-right">
                    <div class="btn-group" role="group" aria-label="">
                        <a type="button" class="btn btn-primary" target="_blank" rel="noopener" href="{{ url($openAppRoute) }}">
                            Open Application
                        </a>
                        <a type="button" class="btn btn-success" target="_blank" rel="noopener" href="{{ url($BiRoute) }}">
                            View Corresponding Basic Information
                        </a>
                    </div>
                </div>
                <h5>
                    Application Rollback
                </h5>
            </div>

            <div class="panel-body">
                <section class="content-header">
                    <ol class="breadcrumb">
                        <li><strong>Tracking no. : </strong>{{ $rollbackAppInfo->tracking_no  }}</li>
                        <li><strong>App. Tracking no. : </strong>{{ $appInfo->tracking_no  }}</li>
                        <li><strong>Current Status : </strong>
                            @if(isset($appInfo) && $appInfo->status_id == -1) Draft
                            @else {!! $appInfo->status_name !!}
                            @endif
                        </li>
                        <li>
                            <strong>Current Desk :</strong>
                            @if($appInfo->desk_id != 0)
                                {{ \App\Libraries\CommonFunction::getDeskName($appInfo->desk_id)  }}
                            @else
                                Applicant
                            @endif
                        </li>
                        @if($appInfo->user_id)
                            <li>
                                <strong>Current User :</strong>
                                {!! $appInfo->desk_user_name !!}
                            </li>
                        @endif
                    </ol>
                </section>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5><strong> Company Information </strong></h5>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group col-md-6">
                                            <label class="col-md-4"><strong>Company:</strong></label>
                                            <div class="col-md-8">
                                                {{$appInfo->company_name}}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-md-4"><strong>Submission Date:</strong></label>
                                            <div class="col-md-8">
                                                {{ \App\Libraries\CommonFunction::formateDate($appInfo->submitted_at)  }}
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="col-md-4"><strong>Department:</strong></label>
                                            <div class="col-md-8">
                                                {{$appInfo->department_name}}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="col-md-4"><strong>Sub-Department:</strong></label>
                                            <div class="col-md-8">
                                                {{$appInfo->sub_department_name}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h5><b> View Application Rollback Information </b></h5>
                            </div>
                            <div class="panel-body">
                                <table aria-label="Detailed Report Data Table" class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Caption</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                    </tr>
                                    </thead>
                                    @foreach($data as $key => $data)
                                        <tr>
                                            <td width="33%">{{ $data->caption }}</td>
                                            <td width="33%">{{ $data->old_value }}</td>
                                            <td width="33%">{{ $data->new_value }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                <div>
                                    <label class="col-md-2"><strong>Rollback Remarks :</strong></label>
                                    <div class="col-md-10">
                                        {{$rollbackAppInfo->remarks}}
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div><!-- /.box -->
@endsection
