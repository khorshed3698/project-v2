@extends('layouts.admin')
@section('content')
    <div class="col-lg-12">
        <div class="col-md-12">
            <div class="with-border">
                <?php
                $desk_id_array = explode(',', \Session::get('user_desk_ids'));
                $delegation_desks_ids = explode(",","2");

                ?>
                @if(
                (in_array($appInfo->desk_id, $desk_id_array) || in_array($appInfo->desk_id, $delegation_desks_ids))
                && $appInfo->desk_id > 0)
                    @include('ProcessPath::batch-process')
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <table aria-label="Detailed Report Data Table" class="table table-striped resultTable display" role="grid">
                <thead>
                    <tr class="d-none">
                        <th aria-hidden="true"  scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                <td>
                    @if(View::exists('jsondata.'. $process_info->type_key))
                        @include('jsondata.'. $process_info->type_key)
                    @else
                        @include('jsondata.default')
                    @endif
                </td>
                </tbody>
            </table>
        </div>
        <div class="col-md-12">
            @if((!empty($desk_id_array[0])) || Auth::user()->user_type=="1x101")
            @include('ProcessPath::application-history')
            @endif
        </div>
    </div>
@endsection

@section('footer-script')
    @yield('footer-script2')
@endsection