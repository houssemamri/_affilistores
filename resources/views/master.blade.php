<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ ($site['favicon'] !== '') ? asset('img/uploads/' . $site['favicon']) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ ucwords($site['site_name']) }} {{ (Session::has('subdomain') ? '| ' . ucwords(Session::get('subdomain')) : '') }}</title>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/perfect-scrollbar.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/summernote-bs4.css') }}">
    @yield('custom-css')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/new-style.css') !!}" />
</head>
<body>
    <div class="wrapper">
        @include('parts.side-nav')
        <div class="main-panel">
            @include('parts.nav')
            <div class="content">
                @yield('content')

                @include('extra.switch-store-modal')
            </div>
        </div>
    </div>
    @include('parts.footer')
    
     <script src="{!! asset('js/jquery.min.js') !!}"></script> 
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script>

    {{-- <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script> --}}
    
    <!--  Charts Plugin, full documentation here: https://gionkunz.github.io/chartist-js/ -->
    <script src="{!! asset('js/chartist.min.js') !!}"></script>
    <!-- Library for adding dinamically elements -->
    <script src="{!! asset('js/arrive.min.js') !!}" type="text/javascript"></script>
    <!--  Notifications Plugin, full documentation here: http://bootstrap-notify.remabledesigns.com/    -->
    <script src="{!! asset('js/bootstrap-notify.js') !!}"></script>
    <!-- Material Dashboard Core initialisations of plugins and Bootstrap Material Design Library -->
    <script src="{!! asset('js/material-dashboard.js') !!}"></script>
    @yield('custom-scripts')
    @yield('alert')
</body>
</html>