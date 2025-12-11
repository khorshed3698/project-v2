<?php
$user_type = Auth::user()->user_type;
$type = explode('x', $user_type);
$user_desk_ids = \App\Libraries\CommonFunction::getUserDeskIds();
?>
{{--<div class="row">--}}
{{--<div class="col-sm-12">--}}
<!--Widget start-->
{{--@if($widgetsGroup)--}}
{{--@foreach($widgetsGroup as $widget)--}}
{{--<div class="col-lg-2 col-md-6">--}}
{{--<div class="panel panel-{{ !empty($widget->theme) ? $widget->theme :'default' }}">--}}
{{--<div class="panel-heading">--}}
{{--<div class="row">--}}
{{--<div class="col-xs-3">--}}
{{--<i class="fa fa-list-alt fa-2x"></i>--}}
{{--</div>--}}
{{--<div class="col-xs-9 text-right">--}}
{{--<div class="h4">{!! $widget->value !!}</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<div class="row">--}}
{{--<div class="col-xs-12 text-right">--}}
{{--<div>--}}
{{--{!!$widget->caption!!}--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--<a href="{{url(\App\Libraries\Encryption::encodeId($widget->process_type_id))}}" target="_blank" rel="noopener">--}}
{{--<a href="{{url($widget->url.'/list/'.encodeId($widget->process_type_id))}}" target="_blank" rel="noopener">--}}
{{--<div class="panel-footer" style="padding: 0px 10px;">--}}
{{--<span class="pull-left">{!!trans('messages.details')!!}</span>--}}
{{--<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>--}}
{{--<div class="clearfix"></div>--}}
{{--</div>--}}
{{--</a>--}}

{{--</div>--}}
{{--</div>--}}
{{--@endforeach--}}
{{--@endif--}}
<!--Widget End -->


{{--</div>--}}
{{--</div>--}}

<style>
    .autoProcessListTable > thead > tr > th,
    .autoProcessListTable > tbody > tr > th,
    .autoProcessListTable > tfoot > tr > th,
    .autoProcessListTable > thead > tr > td,
    .autoProcessListTable > tbody > tr > td,
    .autoProcessListTable > tfoot > tr > td{
        vertical-align: middle;
    }
    .autoProcessListTable > thead:first-child > tr:first-child > td{
        font-size: 14px;
    }
    .alert-blue{
        /*background: #6B7AE0;*/
        background: #31708f;
        color: #fff;
    }
    .in_list_style, .in_list_style li {
        list-style: inherit !important;
    }
</style>


{{-- Auto Process application List --}}
@if(in_array(\Illuminate\Support\Facades\Auth::user()->user_type, ['1x101']) || ($type[0] == 4 && !in_array(20, $user_desk_ids)))
    <div class="row">
        <div class="col-sm-12">

            <div class="alert alert-info" style="margin-bottom: 0; border: 2px solid #31708f">
                <h4 style="    margin: 15px 2px;border-bottom: 1px solid;padding-bottom: 15px;"><strong><i class="fa fa-x fa-exclamation-circle"></i> The following applications will be automatically processed immediately:</strong></h4>
                {{--<hr/>--}}
                <table class="table table-bordered table-hover autoProcessListTable" aria-label="Detailed applications processed">
                    <thead class="alert alert-blue">
                    <tr class="d-none">
                        <th aria-hidden="true"  scope="col"></th>
                    </tr>
                    <tr>
                        <td>SN#</td>
                        <td>Service name</td>
                        <td>Process by today</td>
                        <td>Process by tomorrow</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody style="background: #fff; color: #000">
                    <?php $sl = 1; ?>
                    @foreach($autoProcessList as $process)
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>{{ $process['process_type_name'] }}</td>
                            <td>{{ $process['process_by_today'] }}</td>
                            <td>{{ $process['process_by_tomorrow'] }}</td>
                            <td>
                                <a href="{{url('auto-process-list/' . \App\Libraries\Encryption::encodeId($process['process_type_id']))}}" class="btn btn-info btn-sm" target="_blank" rel="noopener">View list</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br/>
@endif


{{--Basic information--}}
@if(in_array(\Illuminate\Support\Facades\Auth::user()->user_type, ['5x505']))
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary" style="margin-bottom: 20px; border: 2px solid #337ab7;">
                <div class="panel-heading">
                    <div class="pull-left" style="line-height: 35px;">
                        <strong>
                            <i class="fas fa-info-circle"></i>
                            Basic Information
                        </strong>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <!-- /.panel-heading -->

                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2">
                            @if($newBida == 1 && $existingBida == 1)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-center">
                                            <a type="button" class="btn btn-warning" style="margin-bottom: 15px;" data-toggle="modal" data-target="#youNeedToKnowModal">You need to know</a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                {{-- Start business category --}}
                                @if(Auth::user()->company->business_category != 2)
                                    @if($newStakeholder == 1)
                                        <div style="margin-bottom: 20px;" class="col-lg-6 col-md-6">
                                            <a href="{{ url('basic-information/form-stakeholder',Encryption::encodeId('NCR'))}}{{(!empty($appInfo) && $appInfo->is_new_for_stakeholders == 1) ? '/'.Encryption::encodeId(Auth::user()->company_ids) : ''}}">
                                                <div class="panel panel-primary text-center">
                                                    <div class="panel-heading">
                                            <span style="padding: 5px; display: block;">
                                                <i class="far fa-edit fa-3x"></i>
                                            </span>
                                                    </div>
                                                    <div class="panel-footer">
                                                        New Company Registration
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if($existingStakeholder == 1)
                                        <div style="margin-bottom: 20px;" class="col-lg-6 col-md-6">
                                            <a href="{{ url('basic-information/form-stakeholder',Encryption::encodeId('ECR')) }}{{(!empty($appInfo) && $appInfo->is_existing_for_stakeholders == 1) ? '/'.Encryption::encodeId(Auth::user()->company_ids) : ''}}">

                                                <div class="panel panel-primary text-center">
                                                    <div class="panel-heading">
                                            <span style="padding: 5px; display: block;">
                                                <i class="fas fa-edit fa-3x"></i>
                                            </span>
                                                    </div>
                                                    <div class="panel-footer">
                                                        Existing Company Registration
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif

                                    @if($newBida == 1)
                                        <div style="margin-bottom: 20px;" class="col-lg-6 col-md-6">
                                            <a href="{{ url('basic-information/form-bida',Encryption::encodeId('NUBS')) }}{{ (!empty($appInfo) && $appInfo->is_new_for_bida == 1) ? '/'.Encryption::encodeId(Auth::user()->company_ids) : '' }}">

                                                <div class="panel panel-primary text-center">
                                                    <div class="panel-heading">
                                            <span style="padding: 5px; display: block;">
                                                <i class="fas fa-user-plus fa-3x"></i>
                                            </span>
                                                    </div>
                                                    <div class="panel-footer">
                                                        New User for BIDA's Services
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                {{-- End business category --}}

                                @if($existingBida == 1)
                                    <div style="margin-bottom: 20px;" class="{{ Auth::user()->company->business_category != 2 ? 'col-lg-6 col-md-6' : 'col-lg-12' }}">
                                        <a href="{{ url('basic-information/form-bida',Encryption::encodeId('EUBS'))}}{{ (!empty($appInfo) && $appInfo->is_existing_for_bida == 1) ? '/'.Encryption::encodeId(Auth::user()->company_ids) : '' }}">

                                            <div class="panel panel-primary text-center">
                                                <div class="panel-heading">
                                            <span style="padding: 5px; display: block;">
                                                <i class="fas fa-user-check fa-3x"></i>
                                            </span>
                                                </div>
                                                <div class="panel-footer">
                                                    Existing User for BIDA's Services
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

{{--  Widget box --}}
@if($services && \Illuminate\Support\Facades\Auth::user()->first_login == 1 && ! in_array($type[0], [11,13]) ) <!-- Bank User -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" style="margin-bottom: 20px; border: 2px solid #337ab7;">
            <div class="panel-heading">
                <div class="pull-left" style="line-height: 35px;">
                    <strong>
                        {{--<i class="fa fa-dashboard" aria-hidden="true"></i>--}}
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </strong>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">


                @if($type[0]==5)
                    <div class="col-lg-3 col-md-3">
                        <div class="panel panel-primary" style="margin-bottom: 15px">
                            <div class="panel-heading" style="padding: 10px 15px; min-height: 90px">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-list"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">
                                            {{$pendingFeedbackApplication}}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div style="font-size: 13px;font-weight: bold">
                                            Process List Feedback
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="/process/list/feedback-list" target="&quot;_blank&quot;">
                                <div class="panel-footer">
                                    <span class="pull-left">View details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                @endif

                {{--<div class="col-lg-3 col-md-3">--}}
                {{--<div class="panel panel-default" style="margin-bottom: 15px">--}}
                {{--<div class="panel-heading" style="padding: 10px 15px; min-height: 90px">--}}
                {{--<div class="row">--}}
                {{--<div class="col-xs-3">--}}
                {{--<i class="fa fa-list"></i>--}}
                {{--</div>--}}
                {{--<div class="col-xs-9 text-right">--}}
                {{--<div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                {{--<div class="col-xs-12 text-right">--}}
                {{--<div style="font-size: 13px;font-weight: bold">--}}
                {{--Feedback List--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<a href="/dashboard/feedback-lists" target="&quot;_blank&quot;">--}}
                {{--<div class="panel-footer">--}}
                {{--<span class="pull-left">View details</span>--}}
                {{--<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>--}}
                {{--<div class="clearfix"></div>--}}
                {{--</div>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}

                @foreach($services as $service)
                    <div class="col-lg-3 col-md-3">
                        {{--<div class="panel panel-green">--}}
                        <div class="panel panel-{{ !empty($service['panel']) ? $service['panel'] :'default' }}" style="margin-bottom: 15px">
                            <div class="panel-heading" style="padding: 10px 15px; min-height: 90px">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa {{$service['icon']}}"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">
                                            {{ !empty($service['totalApplication']) ? $service['totalApplication'] :'0' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div style="font-size: 13px;font-weight: bold">
                                            {{ !empty($service['name']) ? $service['name'] :'N/A'}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ !empty($service['form_url']) && $service['form_url'] =='/#' ?
                'javascript:void(0)' : url($service['form_url'].'/list/'.\App\Libraries\Encryption::encodeId( $service['id'])) }}">
                                <div class="panel-footer">
                                    <span class="pull-left">{!!trans('messages.details')!!}</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    {{--<div class="col-lg-2 col-md-6">--}}
                    {{--<div class="col-lg-3 col-md-3">--}}
                    {{--<div class="panel panel-{{ !empty($service->panel) ? $service->panel :'default' }}">--}}
                    {{--<div class="panel-heading">--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-3">--}}
                    {{--<i style="font-size: 22px;" class="fa fa-list-alt fa-3x"></i>--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-9 text-right">--}}
                    {{--<div class="h3" style="margin-top:0;margin-bottom:0;font-size:20px;">--}}
                    {{--{{ !empty($service->totalApplication) ? $service->totalApplication :'0' }}</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="row">--}}
                    {{--<div class="col-xs-12 text-right">--}}
                    {{--<div style="font-size: 13px;">--}}
                    {{--{{ !empty($service->name) ? $service->name :'N/A'}}--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                    {{--<a href="{{ !empty($service->form_url) && $service->form_url =='/#' ?--}}
                    {{--'javascript:void(0)' : url($service->form_url.'/list/'.\App\Libraries\Encryption::encodeId( $service->id)) }}" {{ !empty($service->form_url) && $service->form_url !='/#' ? 'target="_blank"' :'' }}>--}}
                    {{--<div class="panel-footer" style="padding: 0px 10px;">--}}
                    {{--<span class="pull-left">{!!trans('messages.details')!!}</span>--}}
                    {{--<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>--}}
                    {{--<div class="clearfix"></div>--}}
                    {{--</div>--}}
                    {{--</a>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                @endforeach
            </div>
        </div>
    </div>
</div>
{{--<div class="row">--}}

{{--</div>--}}
{{--<br>--}}
@endif


{{-- Chart list --}}
<div class="row">
    <?php
    $desk_id_array = explode(',', \Session::get('user_desk_ids'));

    ?>
    @if((!empty($desk_id_array[0])) || Auth::user()->user_type=="1x101"|| Auth::user()->user_type=="5x505")

        <div class="text-center">
            <!-- Moris Chart -->
            <?php
            if (!empty($deshboardObject)) {
            foreach ($deshboardObject as $row) {
            $div = 'dbobj_' . $row->db_obj_id;
            ?>
            <div class="col-md-4">
                <?php
                $para1 = DB::select(DB::raw($row->db_obj_para1));
                switch ($row->db_obj_type) {
                case 'PIE_CHART':

                ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5><strong><?php echo $row->db_obj_title; ?></strong></h5>
                    </div>
                    <div class="panel-body">
                        <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;"><br/><br/>Chart will be loading in 5 sec...</div>
                    </div>
                </div>

                <?php

                $script = $row->db_obj_para2;
                //$datav['charttitle'] = $row->db_obj_title;
                $datav['charttitle'] = '';
                $datav['chartdata'] = json_encode($para1);
                $datav['baseurl'] = url();
                $datav['chartediv'] = $div;

                echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                break;
                case 'CANVAS':
                ?>
                <canvas style="width: 100%; height: 350px; " id="<?php echo $div; ?>"><br /><br />Chart will be loading in 5 sec...</canvas>
                <?php
                $script = $row->db_obj_para2;
                $datav['charttitle'] = $row->db_obj_title;
                $datav['chartdata'] = json_encode($para1);

                $datav['baseurl'] = url();
                $datav['chartediv'] = $div;
                echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                break;
                default:
                    break;
                }
                ?>
            </div>
            <?php
            }
            }
            ?>
        </div>

        <!-- Bar Chart -->
        @if(!empty($dashboardObjectBarChart))
            <?php
            $i=0;
            ?>
            @foreach($dashboardObjectBarChart as $record)
                <?php
                $i++;
                ?>
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5><strong>{!! $record->db_obj_title !!}</strong>
                        </div>
                        <div class="panel-body">
                            <?php
                            $barChartData = DB::select(DB::raw($record->db_obj_para1));
                            $barChartArray = array();

                            foreach ($barChartData as $data)
                            {
                                $barChartArray[$data->TITLE] = $data->VALUE;
                            }
                            ?>
                            <div id="bar-example<?php echo $i;?>"></div>

                            <script>
                                Morris.Bar({
                                    element: 'bar-example'+'{{ $i }}',
                                    data: [
                                            <?php
                                            foreach ($barChartArray as $key => $val) {?>
                                        {
                                            y: '<?php echo $key?>',
                                            a: '<?php echo $val?>'
                                        },
                                        <?php

                                        }?>
                                    ],
                                    xkey: 'y',
                                    ykeys: ['a'],
                                    labels: ['Series A'],
                                    barColors: function (row, series, type) {
                                        if (series.key == 'a') {
                                            if (row.y < 10)
                                                return "red";
                                            else if (row.y >= 10 && row.y <= 50)
                                                return "green";
                                            else
                                                return "blue";
                                        }
                                        else {
                                            return "green";
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="text-center">
                <!-- Canvas Chart -->
                <?php
                if (!empty($dashboardObjectCanvas)) {
                foreach ($dashboardObjectCanvas as $row) {
                $div = 'dbobj_' . $row->db_obj_id;
                ?>
                <div class="col-md-12">
                    <?php
                    $para1 = DB::select(DB::raw($row->db_obj_para1));
                    switch ($row->db_obj_type) {
                    case 'SCRIPT':
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5><strong><?php echo $row->db_obj_title; ?></strong></h5>
                        </div>
                        <div class="panel-body">
                            <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;"><br/><br/>Chart will be loading in 5 sec...</div>
                        </div>
                    </div>

                    <?php
                    $script = $row->db_obj_para2;
                    //$datav['charttitle'] = $row->db_obj_title;
                    $datav['charttitle'] = '';
                    $datav['chartdata'] = json_encode($para1);
                    $datav['baseurl'] = url();
                    $datav['chartediv'] = $div;
                    echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                    break;
                    case 'CANVAS':
                    ?>
                    <canvas style="width: 100%; height: 350px; " id="<?php echo $div; ?>"><br /><br />Chart will be loading in 5 sec...</canvas>
                    <?php
                    $script = $row->db_obj_para2;
                    $datav['charttitle'] = $row->db_obj_title;
                    $datav['chartdata'] = json_encode($para1);

                    $datav['baseurl'] = url();
                    $datav['chartediv'] = $div;
                    echo '<script type="text/javascript">' . CommonFunction::updateScriptPara($script, $datav) . '</script>';
                    break;
                    default:
                        break;
                    }
                    ?>
                </div>
                <?php
                }
                }
                ?>
            </div>
        @endif
    @endif
    <div class="col-md-12"><br><br></div>
</div>
<!-- /.row -->
<!-- charts -->


<!-- Notice & Instruction -->
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-info" style="border: 2px solid #bce8f1">
            <div class="panel-heading">
                <div class="pull-left" style="line-height: 35px;">
                    <strong><i class="far fa-newspaper" aria-hidden="true"></i> More Notice & Instructions:</strong>
                </div>
                <div class="clearfix"></div>
            </div>
            <!-- /.panel-heading -->

            <div class="panel-body" style=" max-height:200px; overflow-y: scroll">

                @foreach($notice as $boardNotice)
                    <a target="_blank" rel="noopener"
                       href="{{url('support/view-notice/'.\App\Libraries\Encryption::encodeId($boardNotice->id))}}"
                       class="hover-item" style="text-decoration: none">
                        <div class="panel panel-default hover-item"
                             style="margin-top: 2px; border: 1px solid #86bb86">
                            <div>
                                <div class="pull-right" style="margin: 8px 30px 0px 0px;">
                                    <button class="btn btn-{{$boardNotice->importance}} btn-xs">{{$boardNotice->importance}}
                                        <span><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
                                    </button>
                                </div>

                                {{--<div class="pull-right" style="margin: 15px 15px 0px 0px;"><i class="fa fa-chevron-right"></i></div>--}}
                                <div class="panel-heading" style="border-left: 5px solid #31708f">
                                    <div>{{$boardNotice->heading}}
                                        <br>{{date("d M Y", strtotime($boardNotice->Date))}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        </div>
    </div>
</div>
<div class="col-md-12"><br><br></div>

<style>
    .panel {
        margin: 0px;
    }
</style>

<div class="row">
{{--<h2>Modal Example</h2>--}}
<!-- Trigger the modal with a button -->
{{--<button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false">Open Modal</button>--}}

<!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog" style="margin-top:300;padding-top:0">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">

                    <style type="text/css">
                        h1 { font-size:16px; font-weight:bold; }
                        h2 { margin-top:10px; text-align:center; }
                        ol, ul, li { list-style:none; }
                        #touchSlider2 { width:570px; height:230px; margin:0 auto; background:#ccc;  overflow-x:hidden; }
                        #touchSlider2 ul { width:99999px; height:150px;  top:0; left:0; position: relative; overflow:hidden; }
                        #touchSlider2 ul li { overflow:scroll; float:left; width:500px; height:600px;  background:#7d868f; font-size:14px; color:#fff; }
                        .paging { background:#f5f5f5; text-align:center; overflow:hidden; }
                        .paging .btn_page { display:inline-block; width:10px; height:10px; margin:3px; font-size:0px; line-height:0; text-indent:-9999px; background:#3399CC; }
                        .paging .btn_page.on { background:#ff0000; }
                        .modal-footer{
                            padding: 5px;
                        }
                        .header {
                            width: 800%;
                            height: 50px;
                            background: skyblue;
                            position: fixed;
                            margin:0 auto;"
                        }
                    </style>

                    <!-- jQuery 1.7+, IE 7+ -->

                    <script src="{{ asset("assets/plugins/jquery.touchSlider.js") }}" type="text/javascript"></script>

                    <script type="text/javascript">
                        //<![CDATA[
                        $(document).ready(function() {
                            $("#touchSlider2").touchSlider({
                                roll : false,
                                page : 1,
                                speed : 300,
                                btn_prev : $("#touchSlider2").next().find(".btn_prev"),
                                btn_next : $("#touchSlider2").next().find(".btn_next")
                            });

                        });
                        //]]>
                    </script>

                    {{--<div id="touchSlider2">--}}
                    {{--<ul>--}}
                    {{--@foreach($SurveyFeatures as $surveyData)--}}
                    {{--<li>--}}
                    {{--<div class="" style="">--}}
                    {{--{!!  $surveyData->feature_description!!}--}}
                    {{--</div>--}}

                    {{--<div id="features_{{$surveyData->id}}" style="bottom: 0; font-weight: bold;color: rebeccapurple; position: fixed;margin: 0px; background: white;">Are you helpful for this features? <button class="btn btn-xs btn-warning feedback" value="yes#{{\App\Libraries\Encryption::encodeId($surveyData->id)}}">Yes</button> <button class="btn btn-xs btn-danger feedback" value="no#{{\App\Libraries\Encryption::encodeId($surveyData->id)}}">No</button>--}}
                    {{--</div>--}}
                    {{--</li>--}}

                    {{--@endforeach--}}
                    {{--</ul>--}}
                    {{--</div>--}}

                    <div class="btn_area modal-footer">


                        <button type="button" class="btn btn-default steps_modal" data-dismiss="modal" value="skip">Skip</button>
                        <button type="button" class="btn_prev btn btn-info"><i class="fa fa-angle-double-left"></i>prev</button>
                        <button type="button" class="btn btn-primary btn_next steps_modal" value="next">Next <i class="fa fa-angle-double-right"></i></button>

                    </div>
                </div>

            </div>

        </div>
    </div>

    <!--You need to know modal-->
    <div class="modal fade" id="youNeedToKnowModal" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Information regarding four categories of Basic Information</h4>
                </div>
                <div class="modal-body text-justify">
                    <span class="text-danger"><strong>Caution:</strong> It is needed to complete Basic information at first for getting any service. You may select any one category and it can’t be changed later. So, please know in details for selecting your desired category properly according to the following information:</span> <br> <br>

                    <strong>(i) New Company registration:</strong> The company is new in BIDA One Stop Service Portal and did not take any service from the BIDA E-serve (old portal of BIDA’ services https://eservice.bida.gov.bd) yet.<br> <br>
                    <strong>(ii) Existing Company registration:</strong> The company is new in BIDA One Stop Service Portal and took a few services from the BIDA E-serve (old portal of BIDA’s services https://eservice.bida.gov.bd).<br> <br>

                    <strong>The above categories (i) & (ii) can get only company registration services like:</strong>

                    <ul class="in_list_style">
                        <li>Registrar of Joint Stock Companies And Firms (RJSC)</li>
                        <li>National Board of Revenue</li>
                        <li>Chittagong Development Authority</li>
                        <li>Bangladesh Power Development Board</li>
                        <li>Department of Environment</li>
                    </ul>

                    <strong>(iii) New User of BIDA’s services:</strong> The company is new in BIDA One Stop Service Portal and did not take any service from the BIDA E-serve  (old portal of BIDA’s services https://eservice.bida.gov.bd) yet.<br> <br>
                    <strong>(iv) Existing User of BIDA’s services:</strong> The company is new in BIDA One Stop Service Portal and takes a few services from the BIDA E-serve  (old portal of BIDA’s services https://eservice.bida.gov.bd).<br> <br>

                    <strong>The above categories (iii) & (iv) can get  all the services of  BIDA including Company registration related services.</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<style>
    .modal {
        text-align: center;
        padding: 0!important;
    }

    .modal:before {
        content: '';
        display: inline-block;
        height: 100%;
        vertical-align: middle;
        margin-right: -4px;
    }

    .modal-dialog {
        display: inline-block;
        text-align: left;
        vertical-align: middle;
    }
</style>
<script>
    $(window).on('load',function(){

        {{--$.ajax({--}}
        {{--url: '{{ url("/dashboard-featureShow") }}',--}}
        {{--type: "get",--}}
        {{--data: {},--}}
        {{--success: function(data){--}}
        {{--if(data == "show"){--}}
        {{--$('#myModal').modal('show');--}}
        {{--}--}}

        {{--}--}}
        {{--})--}}
    });
    $(document).ready(function () {


        $('.feedback').click(function () {
            var the =  $(this).parent();
            var data = $(this).val();
            var value = data.split("#")[0];
            var id = data.split("#")[1];

            $.ajax({
                url: '{{ url("/dashboard/store-feedback") }}',
                type: "post",
                data: {
                    _token: $('input[name="_token"]').val(),
                    value: value,
                    id: id
                },
                success: function(data){
                    the.hide();
                }
            })
        })
        $('.steps_modal').click(function () {

            var link = $('.modal-footer');
            var offset = link.offset();

            var top = offset.top;
            var left = offset.left;

            var bottom = top + link.outerHeight();
            var right = left + link.outerWidth();
            console.log(bottom,right);
            $('#features_6').css({
                bottom:bottom+'px',
                right:right+'px'
            });


            var skip_id = $(this).val();

            $.ajax({
                url: '{{ url("/dashboard/steps-modal") }}',
                type: "post",
                data: {
                    _token: $('input[name="_token"]').val(),
                    value: skip_id
                },
                success: function(data){

                }
            })
        })
    })
</script>





