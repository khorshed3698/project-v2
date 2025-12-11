<style type="text/css">
    .top-buffer { margin-top:10px; }
    .advance{
        cursor: pointer;
    }
    .collapsed > .fa-arrow-up::before{
        content: "";
    }

    .widget-custom{
        border: 1px solid;
        border-color: #E3E3E3;
        padding: 10px 0;
    }
    .vk_ard::before {
        border-top: 16px solid #e5e5e5;
        top: 6px;
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        border-left: 32px solid rgba(229, 229, 229, 0);
        border-right: 32px solid rgba(229, 229, 229, 0);
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        content: " ";
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }
    .vk_ard::after {
        border-top: 16px solid #fff;
    }
    .vk_ard::after {
        top: 0;
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        border-left: 32px solid rgba(229, 229, 229, 0);
        border-right: 32px solid rgba(229, 229, 229, 0);
    }
    .vk_ard::after, .vk_ard::before, .vk_aru::after, .vk_aru::before {
        content: " ";
        height: 0;
        left: 0;
        position: absolute;
        width: 0;
    }
    ._LJ._qxg .vk_ard, ._LJ._qxg .vk_aru {
        margin-left: 15px;
    }
    .vk_ard, .vk_aru {
        height: 6px;
        width: 64px;
        margin-top: 10px;
    }
    .vk_ard, .vk_aru {
        background-color: #e5e5e5;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .vk_ard {
        top: -11px;
    }
</style>


<ul class="nav nav-tabs nav-justified homeTabs">
{{--    <li  class="{!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">--}}
{{--        <a data-toggle="tab"  href="#ApplicationTab" aria-expanded="true" class="btn btn-info">--}}
{{--            <i class="fa fa-folder-open"></i>--}}
{{--            Application--}}
{{--        </a>--}}
{{--    </li>--}}

    <li  class="{!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
        <a data-toggle="tab"  href="#list_1" aria-expanded="true"  class="btn btn-primary">
            <i class="fa fa-bell"></i>
            {!! trans('messages.latest_notice_title') !!}
        </a>
    </li>

    <li class="{!! ((Request::segment(2)=='trams-condition'))?'active':'' !!}">
        <a  data-toggle="tab" href="#list_4" aria-expanded="true"  class="btn btn-warning">
            <i class="fa fa-gear fa-fw"></i>
            {!! trans('messages.trams_and_condition') !!}
        </a>
    </li>
    <li class="{!! ((Request::segment(2)=='user-manual'))?'active':'' !!}">
        <a  data-toggle="tab" href="#list_5" aria-expanded="true"  class="btn btn-danger">
            <i class="fa fa-book"></i>
            {!! trans('messages.user_manual') !!}
        </a>
    </li>

    <li class="{!! ((Request::segment(2)=='user-manual'))?'active':'' !!}">
        <a  data-toggle="tab" href="#AgencyTab" aria-expanded="true" class="btn btn-success">
            <i class="fa fa-building"></i>
            Regulatory Agencies
        </a>
    </li>

</ul>

{{--<div class="btn-group btn-group-justified" id="homeTabs">--}}
{{--<a class="btn btn-info" data-toggle="tab"  href="#ApplicationTab" aria-expanded="true">--}}
{{--<i class="fa fa-folder-open"></i>--}}
{{--Application--}}
{{--</a>--}}
{{--<a class="btn btn-primary" data-toggle="tab"  href="#list_1" aria-expanded="true">--}}
{{--<i class="fa fa-bell"></i>--}}
{{--{!! trans('messages.latest_notice_title') !!}--}}
{{--</a>--}}
{{--<a class="btn btn-warning" data-toggle="tab" href="#list_4" aria-expanded="true">--}}
{{--<i class="fa fa-gear fa-fw"></i>--}}
{{--{!! trans('messages.trams_and_condition') !!}--}}
{{--</a>--}}
{{--<a class="btn btn-danger" data-toggle="tab" href="#list_5" aria-expanded="true">--}}
{{--<i class="fa fa-book"></i>--}}
{{--{!! trans('messages.user_manual') !!}--}}
{{--</a>--}}
{{--<a class="btn btn-success" data-toggle="tab" href="#AgencyTab" aria-expanded="true">--}}
{{--<i class="fa fa-building"></i>--}}
{{--Govt Agencies--}}
{{--</a>--}}
{{--</div>--}}
<div class="nav-tabs-custom">
    <div class="panel with-nav-tabs panel-info" style="border-radius: 0 0 4px 4px; border-top: 0;">
        {{--<div class="panel-heading" >--}}
        {{--<ul class="nav nav-tabs">--}}
        {{--<li  class=" active {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">--}}
        {{--<a data-toggle="tab"  href="#list_1" aria-expanded="true">--}}
        {{--<i class="fa fa-clock-o"></i>--}}
        {{--{!! trans('messages.latest_notice_title') !!}--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li class="{!! ((Request::segment(2)=='training'))?'active':'' !!}"   >--}}
        {{--<a data-toggle="tab" href="#list_3" aria-expanded="true" id="training">--}}
        {{--<i class="fa fa-pencil-square-o"></i>--}}
        {{--{!! trans('messages.training_tab_text') !!}--}}
        {{--</a>--}}
        {{--</li>--}}

        {{--<li class="{!! ((Request::segment(2)=='trams-condition'))?'active':'' !!}"   >--}}
        {{--<a  data-toggle="tab" href="#list_4" aria-expanded="true" id="training">--}}
        {{--<i class="fa fa-gear fa-fw"></i>--}}
        {{--{!! trans('messages.trams_and_condition') !!}--}}
        {{--</a>--}}
        {{--</li>--}}
        {{--<li class="{!! ((Request::segment(2)=='user-manual'))?'active':'' !!}"   >--}}
        {{--<a  data-toggle="tab" href="#list_5" aria-expanded="true" id="training">--}}
        {{--<i class="fa fa-book"></i>--}}
        {{--{!! trans('messages.user_manual') !!}--}}
        {{--</a>--}}
        {{--</li>--}}

        {{--</ul>--}}
        {{--</div>--}}
        <div class="panel-body">
            <div class="tab-content">
                <?php $at = 0; ?>
                @if($at>0)
                <div id="ApplicationTab" class="tab-pane {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
                    <img src="{{asset('assets/images/demo_chart.jpg')}}" alt="" class="img-responsive"/>

                     Dashboard Object
                    <div class="row">

                         Bar Chart
                        @if(!empty($dashboardObjecBarChart))
                            <?php $i = 1; ?>
                            @foreach($dashboardObjecBarChart as $record)
                                <?php
                                $i++;
                                ?>
                                <div class="col-md-4">
                                    <h5 class="text-center"><strong>{!! $record->db_obj_title !!}</strong></h5>
                                    <?php
                                    $barChartData = DB::select(DB::raw($record->db_obj_para1));
                                    $barChartArray = array();
                                    foreach ($barChartData as $data) {
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
                                            labels: ['Total application'],
                                            resize: true,
                                            gridTextSize: 10,
                                            gridTextColor: '#000',
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
                            @endforeach
                        @endif

                         Pie Chart
                        @if (!empty($dashboardObjecPieChart))
                            @foreach ($dashboardObjecPieChart as $row)
                                <?php
                                $div = 'dbobj_' . $row->db_obj_id;
                                ?>
                                <div class="col-md-8">
                                    <?php
                                    $para1 = DB::select(DB::raw($row->db_obj_para1));
                                    switch ($row->db_obj_type) {
                                    case 'PIE_CHART_HOME':
                                    ?>
                                    <h5 class="text-center"><strong><?php echo $row->db_obj_title; ?></strong></h5>
                                    <div id="<?php echo $div; ?>" style="width: 100%; height: 350px; text-align:center;"><br/><br/>Chart will be loading in 5 sec...</div>
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
                            @endforeach
                        @endif

                    </div>
                    <div class="clearfix"></div>
                    <div class="dod1 dod_notice"
                         key="AP_DIVISION_DISTRICT">

                    </div>
                     Dashboard Object Dynamic
                    <?php
                    echo dodObject('notice');
                    ?>
                    <script>
                        $('.dod1').each(function () {
                            var obj = $(this);
                            obj.html('Loading ...');
                            $.ajax({
                                type: "GET",
                                data: {
                                    // agency_id: agency_id,
                                    // session_id: session_id,
                                    key: $(this).attr('key')
                                },
                                url: '/web/get-HomePage-dod-object',
                                success: function (response) {
                                    if (response.responseCode === 1) {
                                        obj.html(response.data);
                                    }
                                }
                            });
                        });

                        $('.dod').each(function () {
                            var obj = $(this);
                            $.ajax({
                                type: "GET",
                                data:{
                                    key: $(this).attr('id')
                                },
                                url: "<?php echo url(); ?>/web/page-object",
                                success: function (response) {
                                    if (response.responseCode == 1) {
                                        obj.html(response.data);
                                    } else if (response.responseCode == 0) {
                                        obj.html(response.data);
                                    }
                                }
                            });
                        });
                    </script>
                </div>
                @endif
                @include('public_home.notice')
                @include('public_home.report_dashboard')
                {{--                @include('public_home.training')--}}
                @include('public_home.trams_and_condition')
                @include('public_home.user_manual')

                <div id="AgencyTab" class="tab-pane {!! (Request::segment(2)=='govt_agency')?'active':'' !!}">
                    <table class="table table-hover table-bordered">
                        <tbody style="font-weight: bold">
                            <?php $ra = 1; ?>
                            @forelse($regulatory_agencies as $regulatory_agency)
                                <tr class="{{ ($ra % 2) == 1 ? 'success' : 'info' }}">
                                    <td>{{ $regulatory_agency->name }}</td>
                                </tr>
                                <?php $ra++; ?>
                            @empty
                                <tr class="warning">
                                    <td>No regulatory agency found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" value="{{ csrf_token() }}">