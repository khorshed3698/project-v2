<!DOCTYPE html>
<!--[if IE 8]>
<html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]>
<html lang="en" class="ie9 no-js"> <![endif]-->
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8"/>
    <title>@yield('app_name','OSS APP MIS')</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <?php $layout = 'advanced';
    if (isset($_GET['layout'])) {
        $layout = $_GET['layout'];
    }
    ?>
    @if($layout=='advanced')
        <link rel="stylesheet" href="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/stylesheets/styles.css"/>
        <link rel="stylesheet" href="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/dataTables.bootstrap.min.css"/>
        <link rel="stylesheet" href="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/responsive.bootstrap.min.css"/>
    @else
        <link rel="stylesheet" href="file:///android_asset/pilgrimguide/assets/css/styles.css"/>
        <link rel="stylesheet" href="file:///android_asset/pilgrimguide/assets/css/dataTables.bootstrap.min.css"/>
        <link rel="stylesheet" href="file:///android_asset/pilgrimguide/assets/css/responsive.bootstrap.min.css"/>
    @endif
</head>
<body>
<div class="container" style="padding:0px;">
    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
<!-- jQuery -->
@if($layout=='advanced')
    <script src="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/jquery.min.js" type="text/javascript"></script>
    <script src="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/jquery.dataTables.min.js"></script>
    <script src="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/dataTables.bootstrap.min.js"></script>
    <script src="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/dataTables.responsive.min.js"></script>
    <script src="{{env('HAJ_GUIDE_RESOURCE_PATH')}}/assets/scripts/datatable/responsive.bootstrap.min.js"></script>
@else
    <script src="file:///android_asset/pilgrimguide/assets/js/jquery.min.js" src=""
            type="text/javascript"></script>
    <script src="file:///android_asset/pilgrimguide/assets/js/datatable/jquery.dataTables.min.js"></script>
    <script src="file:///android_asset/pilgrimguide/assets/js/datatable/dataTables.bootstrap.min.js"></script>
    <script src="file:///android_asset/pilgrimguide/assets/js/datatable/dataTables.responsive.min.js"></script>
    <script src="file:///android_asset/pilgrimguide/assets/js/datatable/responsive.bootstrap.min.js"></script>
@endif
@yield('footer-script')
</body>
</html>