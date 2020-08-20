<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ ucwords($site['site_name']) }} </title>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/new-style.css') !!}" />
</head>
<body>
    <div class="wrapper login-wrapper h-100vh">
        <div class="row">
            <div class="col-lg-4 login-left text-center">
                <div class="container">
                    @if(count($errors->all()) > 0)
                    <div class="container">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            @foreach ($errors->all() as $error)
                                <span><b> {{ $error }} </b></span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(Session::has('login_error'))
                    <div class="container">
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span><b> {{ Session::get('login_error') }} </b></span>
                        </div>
                    </div>
                    @endif

                    @if(Session::has('success_reset'))
                    <div class="container">
                        <div class="alert alert-info">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span><b> {{ Session::get('success_reset') }} </b></span>
                        </div>
                    </div>
                    @endif
                    
                    <img src="{!! asset('img/uploads/'. $site['logo']) !!}" class="img-fluid" width="80%" alt="">
                    <h3 class="card-title"><i class="material-icons">security</i> <strong>Admin Login </strong></h3>
                    <form action="{{ route('admin.login') }}" method="POST">
                        @csrf
                        <div class="col-lg-8 offset-lg-2">
                            <div class="form-group">
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Email">
                            </div>

                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Password">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Sign In</button>
                        </div>
                        
                    </form>
                </div>
            </div>
            <div class="col-lg-8 login-right">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                        {!! html_entity_decode($loginMsg->value) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.js') !!}"></script>
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