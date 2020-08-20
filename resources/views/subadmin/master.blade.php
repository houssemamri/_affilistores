<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $site['site_name'] }}  @yield('subtitle')</title>
    <link rel="shortcut icon" href="{{ ($site['favicon'] !== '') ? asset('img/uploads/' . $site['favicon']) : asset('img/uploads/' . $site['logo']) }}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/summernote-bs4.css') }}">
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/new-style.css') !!}" />
</head>
<body>
    <div class="wrapper">
        @include('subadmin.partials.side-nav')
        <div class="main-panel">
            @include('subadmin.partials.nav')
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>
    @include('subadmin.partials.footer')
</body>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    {{-- <script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script> --}}
    {{-- <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script> --}}
    <!-- Library for adding dinamically elements -->
    <script src="{!! asset('js/arrive.min.js') !!}" type="text/javascript"></script>
    <!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
    <script src="{!! asset('js/bootstrap-notify.js') !!}"></script>
    <!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{!! asset('js/material-dashboard.js?v=2.0.0') !!}"></script>
    @yield('custom-scripts')
    @yield('alert')
</html>