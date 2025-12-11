<?php echo
//==========previous code=============
//header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
//header("Cache-Control: post-check=0, pre-check=0", false);
//header("Pragma: no-cache");
//header('Content-Type: text/html');

//==========new code=============
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Proxies.
?>
        <!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', config('app.project_name'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta
            name="keywords"
            content="BIDA Registration, Company Registration (RJSC), Office Permission, Outward Remittance Approval,
            Tax Identification Number, Visa Recommendation, Work Permit">
    <meta
            name="description"
            content="One Stop Service is an online platform integrating relevant Government agencies for providing efficient and transparent
            services to domestic and foreign investors">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Fav icon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset("assets/images/favicon1.ico") }}"/>

    <link rel="stylesheet" type="text/css" href="{{asset('vendor/bootstrap-3.4.1/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('3.3.7/css/sb-admin-2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/stylesheets/custom_v004.min.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/newsTicker/ticker-style.min.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/plugins/toastr.min.css") }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset("assets/fontawesome/css/all.min.css") }}"/>

    @if(Auth::user())
        <link rel="stylesheet" type="text/css" href="{{ asset("build/css/intlTelInput_v16.0.8.min.css") }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.css") }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.css") }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/stylesheets/bootstrap-datetimepicker.min.css") }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset("assets/plugins/bootstrap-toggle.min.css") }}"/>
    @endif

    @yield('style')

    <noscript>Sorry, your browser does not support JavaScript!</noscript>
    {{--    <script src="{{ asset("assets/scripts/jquery.min.js") }}" type="text/javascript"></script>--}}
    <script src="{{ asset("assets/scripts/jquery_v3.5.1.min.js") }}" type="text/javascript"></script>

    @if(Auth::user())
    <!-- Morris Charts JavaScript -->
        <script src="{{ asset("assets/amcharts/morris-0.4.1.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/scripts/Chart.min.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/amcharts.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/pie.js") }}" type="text/javascript"></script>
        <script src="{{ asset("assets/amcharts/serial.js") }}" type="text/javascript"></script>
    @endif

</head>

<body>

{{ csrf_field() }}

@yield('body')

<!-- Bootstrap Core JavaScript -->
{{--<script src="{{asset('3.3.7/js/bootstrap.min.js')}}"></script>--}}
<script src="{{asset('vendor/bootstrap-3.4.1/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('3.3.7/js/sb-admin-2.min.js')}}" type="text/javascript"></script>
<script src="{{ asset("assets/scripts/metis-menu.min.js") }}" type="text/javascript"></script>
<script async src="{{ asset("assets/plugins/toastr.min.js") }}" type="text/javascript"></script>
<script src="{{ asset('assets/scripts/token-manager-v1.js') }}"></script>

@if(Request::is('/') || Request::is('login'))
    <script async src="{{ asset("assets/newsTicker/jquery.ticker.min.js") }}" type="text/javascript"></script>
    <script async src="{{ asset("assets/scripts/home_page_v1.min.js") }}" type="text/javascript"></script>
@endif

@if(Auth::user())
    @if(!Request::is('/'))
        {{--        <script async src="{{ asset("assets/scripts/datatable/jquery.dataTables.min.js") }}" type="text/javascript"></script>--}}
        {{--        <script async src="{{ asset("assets/scripts/datatable/dataTables.bootstrap.min.js") }}" type="text/javascript"></script>--}}
        {{--        <script async src="{{ asset("assets/scripts/datatable/dataTables.responsive.min.js") }}" type="text/javascript"></script>--}}
        {{--        <script async src="{{ asset("assets/scripts/datatable/responsive.bootstrap.min.js") }}" type="text/javascript"></script>--}}
    @endif

    <script src="{{ asset("assets/plugins/bootstrap-toggle.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/jquery.validate_v01.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("vendor/moment_v2.26.0.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/bootstrap-datetimepicker_v001.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/sweetalert2.all.min.js") }}" type="text/javascript"></script>
    <script src="{{ asset("assets/scripts/custom_v08.min.js") }}" type="text/javascript"></script>
@endif
<script>
    // Add CSRF token for all types of ajax request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
@yield('footer-script')



<script type="text/javascript">

    /*
    * start of
    * PDF file validation with per page size and file size
    */

   
   function validateAndHandlePdfFile(event, MAX_SIZE_PER_PAGE_KB) 
   {
        // check MAX_SIZE_PER_PAGE_KB is empty, null, 0
        if (!MAX_SIZE_PER_PAGE_KB || MAX_SIZE_PER_PAGE_KB == 0) {
            console.log('MAX_SIZE_PER_PAGE_KB not defined');
            return;
        }
        const KILOBYTES = 1024;
        let reader = new FileReader();
        let selectedFile = event.target.files[0];

        // check selected file is pdf
        if (!selectedFile.type.match('application/pdf')) {
            swal({
                type: 'error',
                title: 'Oops...',
                text: 'File is not a valid PDF'
            })
            event.target.value = '';
            return;
        }

        reader.onloadend = (event) => {
            let pageCount = event.target.result.match(/\/Type[\s]*\/Page[^s]/g).length;
            let maxAllowedSizeKB = MAX_SIZE_PER_PAGE_KB  * pageCount; // 20 * 1024

            if ((selectedFile.size / KILOBYTES) > maxAllowedSizeKB) {
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'File size exceeds the limit of ' + maxAllowedSizeKB + ' KB'
                })
                event.target.value = '';
                return;
            }
            // necessary code
            console.log('File is valid');
        };
        reader.readAsBinaryString(selectedFile);


    }


    /*
    * end of
    * PDF file validation with per page size and file size
    */



</script>




<script type="text/javascript">
    // $("input[type=text]:not([class*='textOnly'],[class*='email'],[class*='exam'],[class*='number'],[class*='bnEng'],[class*='textOnlyEng'],[class*='datepicker'],[class*='mobile_number_validation'])").addClass('engOnly');
    // tooltip demo
    $('.tooltip-demo').tooltip({
        selector: "[data-toggle=tooltip]",
        container: "body"
    });
    // Bootstrap Tooltip initialize
    $('[data-toggle="tooltip"]').tooltip();

    // popover demo
    $("[data-toggle=popover]").popover();
</script>

@if(Request::segment(3) === 'view-app')
    <script type="text/javascript">
        const scrollBtn = document.querySelector('#animateScrollBtn');
        $(window).scroll(function(){
            if ($(this).scrollTop() > 0) {
                $('#animateScrollBtn').children().removeClass('fa-angle-down').addClass('fa-angle-up');
            } else {
                $('#animateScrollBtn').children().removeClass('fa-angle-up').addClass('fa-angle-down');
            }
        });

        scrollBtn.addEventListener('click', function () {
            if ($(document).scrollTop() > 0) {
                window.scrollTo({top: 0, behavior: 'smooth'});
            } else {
                window.scrollTo({top: $(document).height(), behavior: 'smooth'});
            }
        });
    </script>
@endif

@if(Auth::user())
    <script type="text/javascript">
        var setSession = '';

        function getSession() {
            $.get("/users/get-user-session", function (data, status) {
                if (data.responseCode == 1) {
                    setSession = setTimeout(getSession, 120000);
                } else {
                    // alert('Your session has been closed. Please login again');
                    // window.location.replace('/login');
                    swal({
                        type: 'warning',
                        title: 'Oops...',
                        text: 'Your session has been closed. Please login again',
                        footer: '<a href="/login">Login</a>'
                    }).then((result) => {
                        if (result.value) {
                            window.location.replace('/login')
                        }
                    })
                }
            });
        }

        setSession = setTimeout(getSession, 120000);
    </script>
@endif

{{-- for url web service --}}
<?php if (Auth::user()) {
    $user_id = Auth::user()->id;
} else {
    $user_id = 0;
}

if (isset($exception)) {
    $message = "Invalid Id! 401";
} else {
    $message = 'Ok';
}
?>

{{-- url store script --}}
@if(Auth::user())
    <script type="text/javascript">
        var ip_address = '<?php echo $_SERVER['REMOTE_ADDR'];?>';
        var user_id = '<?php echo $user_id;?>';
        var message = '<?php echo $message;?>';
        var project_name = "BIDA_OSS." + "<?php echo env('SERVER_TYPE', 'unknown');?>";
    </script>
    <script src="{{ url("/url_webservice/action_info.min.js") }}" type="text/javascript"></script>
@endif

{{-- Social widget and feedback system --}}
<div id="batworld"></div>
<script type='text/javascript'>
    window.ba_sw_id = '{{ config('app.SOCIAL_WIDGET_ID') }}';
    let social_widget_site_url = '{{ config('app.SOCIAL_WIDGET_SITE_URL') }}';
    setTimeout(() => {
        const s1 = document.createElement('script');
        s1.setAttribute('src', social_widget_site_url);
        document.body.appendChild(s1);
    }, 500);
</script>

</body>
</html>