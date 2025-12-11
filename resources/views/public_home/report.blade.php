<ul class="nav nav-tabs nav-justified homeTabs">
    <li class="{!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
        <a data-toggle="tab" href="#noticeTab" aria-expanded="true" class="btn btn-primary">
            <i class="fa fa-bell"></i>
            {!! trans('messages.latest_notice_title') !!}
        </a>
    </li>

    <li class="">
        <a data-toggle="tab" href="#availableServicesTab" aria-expanded="true" class="btn btn-warning" id="available_services">
            <i class="fas fa-cog"></i>
            {!! trans('messages.available_services') !!}
        </a>
    </li>
    <li class="">
        <a data-toggle="tab" href="#userManualTab" aria-expanded="true" class="btn btn-danger" id="user_manual">
            <i class="fa fa-book"></i>
            {!! trans('messages.user_manual') !!}
        </a>
    </li>

    <li class="">
        <a data-toggle="tab" href="#AgencyTab" aria-expanded="true" class="btn btn-success" id="ipaClpTabBtn">
            <i class="fa fa-building"></i>
            IPA and CLP Agency
        </a>
    </li>

    <li class="hidden">
        <a data-toggle="tab" href="#sectorInfoTab" aria-expanded="true" class="btn btn-success"
           id="checkHiddenTabButton">
            <i class="fa fa-building"></i>
            hidden
        </a>
    </li>
</ul>

<div class="nav-tabs-custom">
    <div class="panel with-nav-tabs panel-info" style="border-radius: 0 0 4px 4px; border-top: 0;">
        <div class="panel-body">
            <div class="tab-content">

                @if(count($dashboardObjecPieChart) > 0 || count($dashboardObjecBarChart) > 0)
                    <div id="ApplicationTab"
                         class="tab-pane {!! (Request::segment(2)=='index' OR Request::segment(2)=='')?'active':'' !!}">
                        {{--                        <img src="{{asset('assets/images/demo_chart.jpg')}}" alt="Demo chart" class="img-responsive"/>--}}
                        {{--                        Dashboard Object--}}
                        <div class="row">

                            {{--Bar Chart--}}
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
                                                element: 'bar-example' + '{{ $i }}',
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
                                                    } else {
                                                        return "green";
                                                    }
                                                }
                                            });
                                        </script>
                                    </div>
                                @endforeach
                            @endif

                            {{-- Pie Chart --}}
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
                                        <div id="<?php echo $div; ?>"
                                             style="width: 100%; height: 350px; text-align:center;"><br/><br/>Chart will
                                            be loading in 5 sec...
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
                                        <canvas style="width: 100%; height: 350px; " id="<?php echo $div; ?>"><br/><br/>Chart
                                            will be loading in 5 sec...
                                        </canvas>
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
                        {{-- Dashboard Object Dynamic --}}
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
                                    data: {
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

                <div id="availableServicesTab" class="tab-pane">
                    <div class="text-center" id="availableSericeLoading">
                        <i class="fa fa-spinner fa-pulse fa-3x"></i>
                    </div>
                </div>

                <div id="userManualTab" class="tab-pane">
                    <div class="text-center" id="userManualLoading">
                        <i class="fa fa-spinner fa-pulse fa-3x"></i>
                    </div>
                    <div id="showUserManual"></div>
                </div>

                <div id="AgencyTab" class="tab-pane {!! (Request::segment(2)=='govt_agency')?'active':'' !!}">
                    <div class="text-center" id="ipa_clp_agency_preloading">
                        <br/>
                        <br/>
                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                        <br/>
                        <br/>
                    </div>
                    <div id="ipa_clp_agency_content"></div>
                </div>

                <div id="sectorInfoTab" class="tab-pane {!! (Request::segment(2)=='get-bbs-code')?'active':'' !!}">
                    <div class="text-center" id="bbs_code_preloading">
                        <i class="fa fa-spinner fa-pulse fa-4x"></i>
                        <br/><br/>
                    </div>
                    <div id="bbsCode"></div>
                </div>
            </div>
        </div>
    </div>
</div>