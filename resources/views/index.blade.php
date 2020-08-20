<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $site['site_name'] }}</title>
    <link rel="shortcut icon" href="{{ asset('img/uploads/' . $site['favicon']) }}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
    <style>
        html, body{
            height: 100vh;
            background: #fff;
        }
    </style>
</head>
<body>
    <div class="flex-container">
        <img src="{{ asset('img/uploads/' . $site['logo']) }}" width="80%" alt="" class="img-fluid">
        <div class="container">
            <div class="text-center">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="btn-group">
                            <a href="http://www.affilistores.com " class="btn btn-lg btn-default"><b>Not Yet a member?</b></a>
                            <a href="{{ route('login') }}" class="btn btn-lg btn-primary"><b>Already a Member?</b></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
</body>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script>
    <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script>
    <!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
    <script src="{!! asset('js/chartist.min.js') !!}"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{!! asset('js/arrive.min.js') !!}" type="text/javascript"></script>
    <!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
    <script src="{!! asset('js/bootstrap-notify.js') !!}"></script>
    <!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{!! asset('js/material-dashboard.js?v=2.0.0') !!}"></script>
</html>