<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
    <meta http-equiv="Cache-Control" content="max-age=200" />
    <!-- //site icon -->
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    <!-- //meta description -->
    @if(isset($store->homePage->meta_description))
    <meta name="description" content="{{ $store->homePage->meta_description }}">
    @endif
    <!-- //meta keywords -->
    @if(isset($store->homePage->meta_keywords))
    <meta name="keywords" content="{{ $store->homePage->meta_keywords }}">
    @endif
    <!-- //meta robots -->
    {!! isset($store->homePage->robots_meta_no_index) && ($store->homePage->robots_meta_no_index == 1) ? "<meta name='robots' content='noindex'>" : "" !!}
    {!! isset($store->homePage->robots_meta_no_follow) && ($store->homePage->robots_meta_no_follow == 1) ? "<meta name='robots' content='nofollow'>" : "" !!}
    <!-- //meta verification -->
    {!! isset($store->webmasterSettings->google_verification_code) ? $store->webmasterSettings->google_verification_code : "" !!}
    {!! isset($store->webmasterSettings->bing_verification_code) ? $store->webmasterSettings->bing_verification_code : "" !!}
    {!! isset($store->webmasterSettings->pinterest_verification_code) ? $store->webmasterSettings->pinterest_verification_code : "" !!}
    
    <!-- //title -->
    <title> {{ isset($store->homePage->meta_title) ? $store->homePage->meta_title : $site['site_name'] }}</title>

    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/schemes/' . $scheme . '.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick.css') !!}" />
    
</head>
<body>
    @include('index.facebook-chat-support')
    <div class="wrapper dashboard">
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
            <div class="custom-container slider">
                @if(count($sliders) > 0)
                    @include('index.slider')
                @endif

                <div class="card-body">
                    <h2 class="text-uppercase bold">Categories</h4>
                    <hr>
                    <div class="row">
                        @foreach($store->categoryMenu as $menu)
                            <div class="col-lg-2 col-md-4 col-sm-12 category-card">
                                <a href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink]) }}" >
                                    <div class="card">
                                        <div class="card-img">
                                            <img class="card-img-top" src="{{ (!isset($menu->category->image) || $menu->category->image == '') ? asset('img/uploads/category-default.jpg') : asset('img/uploads/'.$store->subdomain.'/categories/' . $menu->category->image) }}" alt="Card image cap">
                                        </div>
                                        <div class="card-body text-center">
                                            <p class="card-title text-capitalize">{{ $menu->category->name }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>                    
                </div>

                @foreach($store->categoryMenu->take(4) as $submenu)
                    @if(count($submenu->category->productCategory->take(6)) > 0)
                        <div class="card-body">
                            <h2 class="text-uppercase bold">{{ $submenu->category->name }}</h4>
                            <hr>
                            <div class="row">
                                @foreach($submenu->category->productCategory->take(6) as $product)
                                    <div class="col-lg-2 col-md-4 col-sm-12 product-card">
                                        <a onclick="pageHit('{{ Crypt::encrypt($product->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->product->permalink]) }}">
                                            <div class="card">
                                                <div class="card-img">
                                                    <img class="card-img-top" src="{{ strpos( $product->product->image, 'https') !== false ? $product->product->image :  str_replace('http', 'https', $product->product->image) }}" alt="{{ $product->product->name }}">
                                                </div>
                                                <div class="card-body text-center">
                                                    <p class="card-title text-capitalize"> {!! strlen($product->product->name) > 35 ? substr($product->product->name, 0, 30) . "..." : $product->product->name !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>   
                            <div class="more">
                                <div class="pull-right">
                                    <a class="btn btn-primary" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $submenu->category->permalink]) }}" >
                                        More {{ ucwords($submenu->category->name) }} ...
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
           
            <div class="category-wrapper">
                <div class="custom-container blogs-container">
                    <hr>
                    <h2 class="text-uppercase bold">Blogs</h4>
                    @if(count($blogs) > 0)
                        <div class="row">
                            @foreach($blogs as $blog)
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body blog">
                                        <a href="{{ route('index.blog.view', [$store->subdomain, $blog->slug]) }}" target="_blank"><h3>{{ $blog->title }}</h3></a>
                                        <hr>
                                        {{ strlen(strip_tags($blog->post)) > 250 ? (substr(strip_tags($blog->post), 0, 247)) . "..." : strip_tags($blog->post) }}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                @if(isset($bannerAd))
                <div class="custom-container">
                    <div class="row">
                        <h3 class="bold text-uppercase">You Also Might Like</h3>
                        @if($bannerAd->type == 'ImageUpload')
                        <div class="card">
                            <div class="card-body text-center">
                                <a href="{{ json_decode($bannerAd->content)->banner_link }}">
                                    <img src="{{ asset('img/uploads/' . $store->subdomain . '/bannerAd/' . json_decode($bannerAd->content)->banner_image) }}" alt="" class="img-fluid">
                                </a>
                            </div>
                        </div>   
                        @else
                        <div class="container">
                        {!! json_decode($bannerAd->content)->code !!}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @include('index.template-one.footer')
        </div>        
    </div>
    @include('index.exit-pop')
</body>
    @include('index.footer-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script>
        function pageHit(productId, storeId){
            var URL = "{{ route('reports.pagehit', $store->subdomain) }}"

            axios.post(URL,  {
                productId: productId,
                storeId: storeId,
            })
            .then(function(response){
                console.log(response.data.success)
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    </script>
</html>