<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ ($site['favicon'] !== '') ? asset('img/uploads/' . $site['favicon']) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ ucwords($site['site_name']) }} </title>
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/font-awesome.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/material-dashboard.min.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/style.css') !!}" />
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <p><a href="{{ route('listStore') }}"><i class="material-icons">arrow_back</i> Back to Manage Stores</a></p>
                    <h1><b>How to host your store to your own domain.</b></h3>
                    <div class="card">
                        <div class="card-body">
                            <h4>1.) Go to this link <a href="https://www.iframe-generator.com/" target="_blank">https://www.iframe-generator.com/</a></h4>
                            <h4>2.) Enter iFrame name and iFrame URL. iFrame URL must be your store URL. e.g https://scubba.affilistores.net</a></h4>
                            <h4>3.) Set the width and height to 100% both.</h4>
                            <p class="text-center m-5"><img src="{{ asset('img/iframe-step1.png') }}" alt="step 1" class="img-fluid"></p>
                            <h4>4.) Click the Preview to see how it would look. Click Generate button then copy the generated iFrame code.</h4>
                            <p class="text-center m-5"><img src="{{ asset('img/iframe-step2-1.png') }}" alt="step 2" class="img-fluid"></p>
                            <p class="text-center m-5"><img src="{{ asset('img/iframe-step2.png') }}" alt="step 2" class="img-fluid"></p>
                            <hr>
                            <h3><b>Optional</b></h3>
                            <h4>5.) Download this empty <a href="{{ asset('img/index.html') }}" download><u>html</u></a> file. Then open / edit it with any file editor you have and paste the iFrame code you copied.</h4>
                            <p class="text-center m-5"><img src="{{ asset('img/iframe-step3.png') }}" alt="step 3" class="img-fluid"></p>
                            <h4>6.) Upload the html file on your own domain.</h4>
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

    @include('extra.alerts')
</html>

