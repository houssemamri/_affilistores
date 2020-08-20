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
                <p>Click <a href="{{ route('login') }}">here</a> to redirect to login area.</p>
            </div>
        </div>
    </div>    
    
    <script src="{!! asset('js/jquery.min.js') !!}"></script>
    <script src="{!! asset('js/popper.min.js') !!}"></script>
    <script src="{!! asset('js/bootstrap-material-design.min.js') !!}"></script>
    <script src="{!! asset('js/perfect-scrollbar.jquery.min.js') !!}"></script>
</body>
</html>