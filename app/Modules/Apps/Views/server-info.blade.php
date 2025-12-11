@extends('layouts.front')

<style>
    #serverInfoDiv p {
        margin-bottom: 10px;
        font-size: 16px;
        font-weight: 200;
    }
</style>

@section("content")

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="box-div">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="jumbotron" id="serverInfoDiv">
                            <h2>Server Information:</h2>
                            <hr/>

{{--                            <div class="progress" style="height: 35px">--}}
{{--                                <div class="progress-bar progress-bar-striped {{ $text_class }}" role="progressbar"--}}
{{--                                     style="width:100%; font-size: 22px; line-height: 35px;">--}}
{{--                                    Health Status--}}
{{--                                </div>--}}
{{--                            </div>--}}
                            <p><strong>RAM Usage:</strong> {{ $total_ram_usage }}%</p>
                            <p><strong>CPU Usage:</strong> {{ $cpu_load }}%</p>
                            <p><strong>Hard Disk Usage: </strong> {{ $disk_usage_percentage }}%</p>
                            {{--                            <p><strong>Established Connections: </strong> {{ $connections }}</p>--}}
                            {{--                            <p><strong>Total Connections: </strong> {{ $totalconnections }}</p>--}}

                            <hr/>

                            <p><strong>RAM Total: </strong> {{ $total_ram_size }} GB</p>
                            <p><strong>RAM Free: </strong> {{ $free_ram_size }} GB</p>
                            <p><strong>RAM Used: </strong> {{ $used_ram_size }} GB</p>
                            <p><strong>RAM Buff/cache: </strong> {{ $buffer_cache_memory_size }} GB</p>

                            <hr/>
                            <p><strong>Hard Disk Total: </strong> {{ $total_disk_size }} GB</p>
                            <p><strong>Hard Disk Used: </strong> {{ $used_disk_size }} GB</p>
                            <p><strong>Hard Disk Free: </strong> {{ $free_disk_size }} GB</p>

                            <hr/>
                            <p><strong>Server Name: </strong> {{ $_SERVER['SERVER_NAME'] }}</p>
                            <p><strong>Server Address: </strong> {{ $_SERVER['SERVER_ADDR'] }}</p>
                            <p><strong>Server Port: </strong> {{ $_SERVER['SERVER_PORT'] }}</p>
                            <p><strong>Server Software: </strong> {{ $_SERVER['SERVER_SOFTWARE'] }}</p>
                            <p><strong>PHP Version: </strong> {{ phpversion() }}</p>
                            <p><strong>Database : </strong> {{ $db_version }}</p>
                            <p><strong>Load Time : </strong> {{ $total_time_of_loading }} sec</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        <a href="/login"><input type="button" class="btn btn-lg btn-success" value="Go Back to Login"/></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section ('footer-script')
@endsection