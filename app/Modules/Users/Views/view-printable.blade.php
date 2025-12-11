@extends('layouts.admin')
@section('content')
    <style>
        fieldset.scheduler-border {
            border: 1px solid #afa3a3 !important;
            padding: 0 1.4em 1.1em 1.1em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow: 0px 0px 0px 0px #000;
            box-shadow: 0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width: auto;
            padding: 0 10px;
            border-bottom: none;
        }

        dt:before {
            content: "";
            display: block;
            padding: 5px 0px;
        }

        dt, dd {
            display: inline;
            padding: 5px 0px;
        }

    </style>
    <?php use App\Libraries\ACL;use App\Libraries\CommonFunction;use App\Libraries\Encryption;$accessMode = ACL::getAccsessRight('user');if (!ACL::isAllowed($accessMode, 'V')) {
        die('no access right!');
    };?>


    <div class="col-lg-12" xmlns="http://www.w3.org/1999/html">

    {!! Form::open(array('url' => 'users/reject/'.Request::segment(3),'method' => 'post', 'class' => 'form-horizontal', 'id' => 'rejectUser')) !!}
    <!-- Modal -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Reject User</h4>
                    </div>
                    <div class="modal-body">
                        <label class="required-star">Reject Reason : </label>
                        <textarea name="reject_reason" class="form-control" required></textarea>
                    </div>
                    <div class="modal-footer">
                        @if(ACL::getAccsessRight('user','E'))
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        {{--modal end--}}

        {!! Form::close() !!}

        <section class="col-md-12" id="printDiv">
            <div class="row"><!-- Horizontal Form -->
                {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
                {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}

                {!! Form::open(array('url' => '/users/approve/'.Encryption::encodeId($user->id),'method' => 'post', 'class' => 'form-horizontal',   'id'=> 'user_edit_form')) !!}
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="panel-title" style="font-size: large;">Profile of
                            : {!! $user->user_first_name .' '. $user->user_middle_name .' '. $user->user_last_name !!}</div>
                    </div> <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-md-3 text-center">
                            {{-- <img src="{{ \App\Libraries\UtilFunction::userProfileUrl($user->user_pic, 'users/upload/') }}" alt="Profile picture" class="profile-user-img img-responsive img-circle" witdh="200"/> --}}

                            <img src="{{ url('users/upload/'. $user->user_pic) }}"
                                alt="Profile picture" class="profile-user-img img-responsive img-circle" witdh="200"
                                onerror="this.src=`{{asset('/assets/images/default_profile.jpg')}}`" />

                        </div>
                        <div class="col-md-9">

                            <div class="row">
                                <dl class="dls-horizontal">
                                    <dt>Full name :</dt>
                                    <dd>{!!$user->user_first_name .' '. $user->user_middle_name .' '. $user->user_last_name!!}
                                        &nbsp;
                                    </dd>
                                    <dt>Type :</dt>
                                    <dd>{!!$user->type_name!!}&nbsp;</dd>

                                    @if($user->passport_no == '')
                                        <dt>NID :</dt>
                                        <dd>{!!$user->user_nid!!}&nbsp;</dd>
                                    @else
                                        <dt>Passport No :</dt>
                                        <dd>{!!$user->passport_no!!}&nbsp;</dd>
                                    @endif
                                    <dt>Mobile :</dt>
                                    <dd>{!!$user->user_phone!!}&nbsp;</dd>
                                    @if($user->user_number != '')
                                        <dt>Telephone :</dt>
                                        <dd>{!!$user->user_number!!}&nbsp;</dd>
                                    @endif
                                    <dt>Email :</dt>
                                    <dd>{!!$user->user_email!!}&nbsp;</dd>
                                    @if($user->district_name)
                                        <dt>District :</dt>
                                        <dd>{!!$user->district_name!!}&nbsp;</dd>
                                    @endif
                                    @if($user->thana_name)
                                        <dt>Thana :</dt>
                                        <dd>{!!$user->thana_name!!}&nbsp;</dd>
                                    @endif
                                    @if($user->user_DOB)
                                        <dt>Date of birth :</dt>
                                        <dd>
                                            {!!CommonFunction::changeDateFormat($user->user_DOB)!!}&nbsp;
                                        </dd>
                                    @endif
                                    <dd>
                                    @if ($user->is_approved != 1)
                                        <dt>Verification expire time :</dt>
                                        <dd>{!!$user->user_hash_expire_time!!}&nbsp;</dd>
                                    @endif
                                    {{--@if(in_array(Auth::user()->user_type,array("1x101")))--}}
                                    {{--@foreach($userMoreInfo as $key=>$info)--}}
                                    {{--<dt>{!!$key!!} :</dt>--}}
                                    {{--<dd>{!!$info!!}&nbsp;</dd>--}}
                                    {{--@endforeach--}}
                                    {{--@endif--}}
                                </dl>


                                @if($user->type_id=='4x404')
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Assigned desk</legend>
                                        <div class="control-group">
                                            <?php $i = 1;?>
                                            @foreach($desk as $desk_name)
                                                <dd>{{$i++}}. {!!$desk_name->desk_name!!}</dd>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Assigned department</legend>
                                        <div class="control-group">
                                            <?php $i = 1;?>
                                            @foreach($departments as $department)
                                                <dd>{{$i++}}. {!!$department->name!!}</dd>
                                            @endforeach
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Assigned sub department</legend>
                                        <div class="control-group">
                                            <?php $i = 1;?>
                                            @foreach($sub_departments as $department)
                                                <dd>{{$i++}}. {!!$department->name!!}</dd>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif

                                @if($user->type_id=='5x505')
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Associated company</legend>
                                        <div class="control-group">
                                            <table aria-label="Detailed Associated company" class="table">
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Company Name</th>
                                                    <th>Authorization Letter</th>
                                                </tr>
                                                <?php $i = 1;?>
                                                @foreach($company_list as $companyDetails)
                                                    <tr>
                                                        <td> {{$i++}} </td>
                                                        <td> <?php
                                                            $returnData = !empty($companyDetails->company_name_bn) ? $companyDetails->company_name . ' ( ' . $companyDetails->company_name_bn . ' )' : $companyDetails->company_name;
                                                            echo $returnData;
                                                            ?>
                                                        </td>
                                                        <td> <a type="button" class="btn btn-success btn-xs" type="submit" href="{{URL::to('/users/upload/'. $companyDetails->authorization_letter)}}"> Open </a> </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </div>
                                    </fieldset>
                                @endif

                                @if(($user->type_id=='4x404') && isset($delegateInfo) && $delegateInfo != '')
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">Delegated to</legend>
                                        <div class="control-group">
                                            <div style="text-align: left;">
                                                <b>Name : </b> {{ $delegateInfo->user_full_name }}<br/>
                                                <b>Designation : </b>{{ $delegateInfo->desk_name }}<br/>
                                                <b>Email : </b>{{ $delegateInfo->user_email }}<br/>
                                                <b>Mobile : </b>{{ $delegateInfo->user_phone }}<br/><br/>
                                                <b>Telephone : </b>{{ $delegateInfo->user_number }}<br/><br/>
                                            </div>
                                        </div>
                                    </fieldset>
                                @endif
                            </div>
                            <?php
                            $approval = '';
                            $type = explode('x', $user->user_type);
                            if (substr($type[1], 2, 2) == 0) {
                                echo Form::select('user_type', $user_types, $user->user_type, $attributes = array('class' => 'form-control required', 'required' => 'required',
                                    'placeholder' => 'Select One', 'id' => "user_type"));
                            }

                            if (ACL::getAccsessRight('user', '-APV-')) {
                                $approval .= '<button type="submit" class="btn btn-sm btn-success"> <i class="fa  fa-check "></i> Approve</button></form>';
                            }

                            if (ACL::getAccsessRight('user', '-REJ-')) {
                                $approval .= ' <a data-toggle="modal" data-target="#myModal2" class="btn btn-sm btn-danger addProjectModa2"><i class="fa fa-times"></i>&nbsp;Reject User</a> ';
                            }
                            ?>


                        </div>
                    </div><!-- /.box -->
                    <div class="panel-footer">
                        <div class="pull-left">
                            <a href="{{ url('users/lists') }}" class="btn btn-sm btn-default"><i
                                        class="fa fa-times"></i> Close</a>
                        </div>
                        <div class="pull-right">

                            @if(\Illuminate\Support\Facades\Auth::user()->user_type == '2x202' && $user->user_verification == 'no')
                                <a href="{{ url('users/resend-email-verification/'.Encryption::encode($user->user_email)) }}"
                                   class="btn btn-sm btn-primary"><i class="fa fa-paper-plane"></i> Resend Verification
                                    link</a>
                            @endif

                            <?php
                            $delegations = '';
                            if ($user->type_id == '4x404') {
                                $delegations = '<a href="' . url('users/delegations/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-primary"><i class="fa fa-paper-plane"></i> Delegation</a>';
                            }
                            $edit = '<a href="' . url('users/edit/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                            $reset_password = '<a href="' . url('users/reset-password/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-warning"'
                                . 'onclick="return confirm(\'Are you sure?\')">'
                                . '<i class="fa fa-refresh"></i> Reset password</a>';

                            $logged_in_user_type = Auth::user()->user_type;
                            $activate = '';
                            if ($logged_in_user_type == '1x101') {
                                if ($user->user_status == 'inactive') {
                                    $activate = '<a href="' . url('users/activate/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-success"><i class="fa fa-unlock"></i>  Activate</a>';
                                } else {
                                    $activate = '<a href="' . url('users/activate/' . Encryption::encodeId($user->id)) . '" class="btn btn-sm btn-danger"'
                                        . 'onclick="return confirm(\'Are you sure?\')">'
                                        . '<i class="fa fa-unlock-alt"></i> Deactivate</a>';
                                }
                            }
                            if ($user->is_approved == true) {
                                if (CommonFunction::isAdmin()) {
                                    if (ACL::getAccsessRight('user', 'E')) {
                                        echo $delegations . '&nbsp;' . $edit;
                                    }

//                                    if(ACL::getAccsessRight('user','R'))
//                                    {
//                                        if ($user->social_login != 1)
//                                            echo '&nbsp;' . $reset_password;
//                                    }

                                    if (ACL::getAccsessRight('user', 'E')) {
                                        echo '&nbsp;' . $activate;
                                    }
                                }
                            } else {
                                echo $approval;
                            }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </section>
    @endsection <!--content section-->