<style>.err_msg_hide {
        display: none;
    }
    .col-md-12 {
        padding: 0px !important;
    }

    #font_plus{
        margin-left: 2px;
        width: 40px;
        margin-top: -2px;
    }
    #font_minus{
        margin-right: 2px;
        width: 40px;
        margin-top: -2px;
    }
    .basicDataTable{
        margin: 0px !important;
        border: none !important;
    }
    .col-sm-12{
        padding-right:0px ;
    }

</style>
@extends('API::layouts.dod-layout')
@section('content')
    {!! Session::has('success') ? '<div class="alert alert-success alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("success") .'</div>' : '' !!}
    {!! Session::has('error') ? '<div class="alert alert-danger alert-dismissible"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>'. Session::get("error") .'</div>' : '' !!}
    <div class="alert alert-danger alert-dismissible err_msg_hide">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <span id="err_msg"></span>
    </div>
    <div class="panel" style="padding-left: 4px">
        <div class="panel-body" style="padding: 0px !important;">
            {!! $reportResult !!}
        </div>

    </div>

@endsection

@section('footer-script')

    <script type="text/javascript">
        $(document).ready(function () {

            var fontSize = 15;
            const minFontSize = 12;
            const maxFontSize = 24;

            var table = $('.basicDataTable').DataTable({
                iDisplayLength: 20,
                "lengthChange": false,
                pageLength: 15,
                responsive: true,
                columnDefs: [
                    { responsivePriority: 1, targets: 0 },
                    { responsivePriority: 2, targets: 1 },
                    { responsivePriority: 3, targets: 2 }
                ],

                drawCallback:function(){

                    if(this.fnSettings().fnRecordsDisplay() <= 15){
                        $('.dataTables_filter').closest("div.row").hide();
                        $('.dataTables_paginate').closest("div.row").hide();
                    }
                }

            });

            // $('#font_plus').on('click', function (event) {
            //     if(fontSize < maxFontSize)fontSize++;
            //     changeFont(table, fontSize);
            // });
            //
            // $('#font_minus').on('click', function (event) {
            //     if(fontSize > minFontSize)fontSize--;
            //     changeFont(table, fontSize);
            // });
        })

    </script>

@endsection