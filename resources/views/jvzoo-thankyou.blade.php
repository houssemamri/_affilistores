<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Instant Ecom Lab</title>
    @include('index.header')
</head>
<body>
    <div class="wrapper dashboard">
        <div class="container">
            <div class="jumbotron">
                <h3>{{ $message }}</h3>
                @if($ctransaction == 'SALE')
                <p>Below is your login details to Sign In at {{ route('login') }}, We also sent these informations to your email.</p>
                <h4><strong>Email: </strong> {{ $user->email }}</h4>
                <h4><strong>Password: </strong> {{ $password }}</h4>
                @endif
                <p>Click <a href="{{ route('login') }}">here</a> to redirect to login area.</p>
                <hr>
                <h4>Instant Ecom Lab</h4>
            </div>
        </div>
    </div>    
    
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script>
    <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script>
</body>
</html>