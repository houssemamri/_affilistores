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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="{!! asset('css/fontawesome-stars.css') !!} ">

    <script src='https://www.google.com/recaptcha/api.js'></script>
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

                    @if(Session::has('profile_error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span><b> {{ Session::get('profile_error') }} </b></span>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('review') }}" method="POST">
                                {!! csrf_field() !!}
                                        
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Email</label>
                                        <input type="text" name="email" value="{{ old('email') }}" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Review</label>
                                        <input type="text" name="review" value="{{ old('review') }}" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <select id="example" name="ratings">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="g-recaptcha" data-sitekey="6LevDWAUAAAAAMzw-vrH-fgbw4UGie1NdqaBNsFl"></div>
                                        </div>
                                    </div>


                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-parimary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- <div class="card">
                        <div class="card-header card-header-primary">
                            <img src="{{ asset('img/logo.png') }}" class="img-fluid" alt="">
                            <h4 class="card-title"><strong> Register </strong></h4>
                            <p class="card-category">Enter your credentials below</p>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('register') }}" method="POST">
                                {!! csrf_field() !!}
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">First Name</label>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Password</label>
                                            <input type="password" name="password" class="form-control" >
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="bmd-label-floating">Confirm Password</label>
                                            <input type="password" name="confirm_password" class="form-control" >
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-block btn-primary">Register Now</button>
                                <div class="clearfix"></div>

                                <div class="row">
                                    <div class="container">
                                        <div class="form-group">
                                            <h4>Already have an account? <a href="{{ route('login') }}"><strong>Login</strong></a></h4>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div> --}}
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#example').barrating({
                theme: 'fontawesome-stars'
            });
        });
    </script>
</html>