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
</head>
<body>
    <div class="wrapper">
        <div class="container login-wrapper">
            <div class="row login-container">
                <div class="col-lg-6 col-md-12">
                    @if(count($errors->all()) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            @foreach ($errors->all() as $error)
                                <span><b> {{ $error }} </b></span>
                            @endforeach
                        </div>
                    @endif

                    @if(Session::has('reset_error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span><b> {{ Session::get('reset_error') }} </b></span>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header card-header-primary">
                            <img src="{{ asset('img/uploads/' . $site['logo']) }}" class="img-fluid" alt="">
                            <h4 class="card-title"><strong> Reset Password </strong></h4>
                            <p class="card-category">Enter your email below</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('resetPassword') }}" method="POST">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Email</label>
                                        <input type="email" name="email" value="" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-block btn-primary">Reset Now</button>
                                <div class="clearfix"></div>

                                <div class="row">
                                    <div class="container">
                                        <div class="form-group">
                                            <h4>Back to <a href="{{ route('login') }}"><strong>Login</strong></a></h4>
                                        </div>
                                    </div>
                                </div>
                            </form>
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