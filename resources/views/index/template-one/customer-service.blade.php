<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- //site icon -->
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ $store->name }} | 
        @if($policy == 'privacy')
            Privacy
        @elseif($policy == 'terms-condtions')
            Terms and Conditions
        @elseif($policy == 'contact-us')
            Contact Us
        @elseif($policy == 'gdpr-compliance')
            GDPR Compliance
        @elseif($policy == 'affiliate-disclosure')
            Affiliate Disclosure
        @elseif($policy == 'cookie-policy')
            Cookie Policy
        @endif
    </title>
    
    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/schemes/' . $scheme . '.css') !!}" />
</head>
<body>
    <div class="wrapper products">
        <div class="sidebar">
            <div class="logo">
                <a href="{{ route('index', $store->subdomain) }}" class="simple-text logo-normal">
                    {{ $store->name }}
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    @include('index.template-one.sidenav')               
                </ul>                
            </div>
        </div>

        <div class="main-panel">
            @include('index.template-one.nav')
        
            <div class="product-wrapper">
                <div class="custom-container contact-wrapper">
                    @if($policy == 'privacy')
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="text-uppercase">Privacy Policy</h1>
                            <hr>
                            <div class="card">
                                <div class="card-body">
                                    {!! htmlspecialchars_decode($customerService->privacy_policy) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($policy == 'gdpr-compliance')
                        <div class="col-lg-12">
                            <h1 class="text-uppercase">GDPR Compliance</h1>
                            <hr>
                        </div>    
                        <div class="card">
                            <div class="card-body">
                                {!! htmlspecialchars_decode($customerService->gdpr_compliance) !!}
                            </div>
                        </div>
                    @elseif($policy == 'affiliate-disclosure')
                        <div class="col-lg-12">
                            <h1 class="text-uppercase">Affiliate Disclosure</h1>
                            <hr>
                        </div>    
                        <div class="card">
                            <div class="card-body">
                                {!! htmlspecialchars_decode($customerService->affiliate_disclosure) !!}
                            </div>
                        </div>
                    @elseif($policy == 'cookie-policy')
                        <div class="col-lg-12">
                            <h1 class="text-uppercase">Cookie Policy</h1>
                            <hr>
                        </div>    
                        <div class="card">
                            <div class="card-body">
                                {!! htmlspecialchars_decode($customerService->cookie_policy) !!}
                            </div>
                        </div>
                    @elseif($policy == 'terms-condtions')
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="text-uppercase">Terms and Conditions</h1>
                            <hr>
                            <div class="card">
                                <div class="card-body">
                                    {!! htmlspecialchars_decode($customerService->terms_conditions) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @elseif($policy == 'contact-us')
                        <h1 class="text-uppercase text-center">Contact Us</h1>
                        <hr> 
                        <div class="card-body">
                            <div class="row">
                                {{-- <div class="col-lg-6 col-md-12 text-center">
                                    {!! htmlspecialchars_decode($customerService->contact_us) !!}
                                    <hr>
                                    <table class="table text-justify">
                                        <tbody>
                                            <tr>
                                                <td><i class="material-icons">contact_mail</i></td>
                                                <td>
                                                @if(isset($store->businessProfile->email) && $store->businessProfile->email !== "")
                                                    <h4> {{ $store->businessProfile->email }}</h4>
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="material-icons">contact_phone</i></td>
                                                <td>
                                                @if(isset($store->businessProfile->phone) && $store->businessProfile->phone !== "")
                                                    <h4> {{ $store->businessProfile->phone }}</h4>
                                                @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><i class="material-icons">location_on</i></td>
                                                <td>
                                                @if(isset($store->businessProfile->address) && $store->businessProfile->address !== "" || 
                                                    isset($store->businessProfile->city) && $store->businessProfile->city !== "" ||
                                                    isset($store->businessProfile->state) && $store->businessProfile->state !== "" ||
                                                    isset($store->businessProfile->country) && $store->businessProfile->country !== "")
                                                    <h4>{{ $store->businessProfile->address .' '. $store->businessProfile->city .' '. $store->businessProfile->state .' '. $store->businessProfile->country}}</h4>
                                                @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table> 
                                </div>--}}
                                <div class="col-lg-6 offset-lg-3 col-md-12 text-center">
                                    <form action="{{ route('index.send.message', ['subdomain' => $store->subdomain]) }}" method="post">
                                        {!! csrf_field() !!}
                                        <div class="row">
                                            <div class="card">
                                                <div class="card-header card-header-primary">
                                                    <h4 class="card-title"><strong> Send us a message </strong></h4>
                                                    <p class="card-category">Keep connected to us</p>
                                                </div>
                                                <div class="card-body">
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <input type="text" name="name" placeholder="Name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <input type="email" name="email" placeholder="Email" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <input type="text" name="subject" placeholder="Subject" class="form-control" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <textarea name="message" placeholder="Enter your message" class="form-control" id="" maxlength="160" cols="10" rows="5" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <button type="submit" class="btn btn-block btn-primary">Send</button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @include('index.template-one.footer')
        </div>
    </div>
</body>
    @include('index.footer-scripts')
</html>