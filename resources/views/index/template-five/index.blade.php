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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/schemes/' . $scheme . '.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick-theme.css') !!}" />
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
                    @include('index.template-five.sidenav')               
                </ul>                
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-five.nav')
            <div class="slider">
                @if(count($sliders) > 0)
                    @include('index.template-five.slider')
                @endif
            </div>

            <div class="container">
                <div class="card-body category-body">
                    <h2 class="text-uppercase text-center bold">Categories</h4>
                    <div class="row">
                        @foreach($store->categoryMenu as $menu)
                            <div class="col-lg-3 col-md-6 col-sm-12 category-card">
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

                <h2 class="text-uppercase text-center bold">Latest Products</h4>

                @foreach($store->categoryMenu->take(4) as $submenu)
                    @if(count($submenu->category->productCategory->take(4)) > 0)
                        <div class="card-body product-body">
                            <div class="row">
                                @foreach($submenu->category->productCategory()->orderBy('id', 'DESC')->get()->take(4) as $product)
                                    <div class="col-lg-3 col-md-6 col-sm-12 product-card">
                                        <a onclick="pageHit('{{ Crypt::encrypt($product->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->product->permalink]) }}">
                                            <div class="card">
                                                <div class="card-img">
                                                    <img class="card-img-top" src="{{ strpos( $product->product->image, 'https') !== false ? $product->product->image :  str_replace('http', 'https', $product->product->image) }}" alt="{{ $product->product->name }}">
                                                </div>
                                                <div class="card-body text-center">
                                                    <p class="card-title text-capitalize"> {!! strlen($product->product->name) > 35 ? substr($product->product->name, 0, 30) . "..." : $product->product->name !!}</p>
                                                    @if($product->product->price > 0)
                                                    <p class="card-title">{{ currency($product->product->currency) }} {{ $product->product->price}}</p>
                                                    @else
                                                    <p class="card-title"><a onclick="affiliateHit('{{ Crypt::encrypt($product->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a></p>
                                                    @endif
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>   
                        </div>
                    @endif
                @endforeach
            </div>
           
            <div class="category-wrapper">
                <div class="container blogs-container">
                    <h2 class="text-uppercase text-center bold">Blogs</h4>
                    @if(count($blogs) > 0)
                        <div class="blogs-slider">
                            @foreach($blogs as $blog)
                                <div class="card">
                                    <div class="card-body blog">
                                        <a href="{{ route('index.blog.view', [$store->subdomain, $blog->slug]) }}" target="_blank"><h3>{{ $blog->title }}</h3></a>
                                        
                                        <div class="blog-content">
                                            {{ strlen(strip_tags($blog->post)) > 250 ? (substr(strip_tags($blog->post), 0, 247)) . "..." : strip_tags($blog->post) }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                @if(isset($bannerAd))
                <div class="container">
                    <h2 class="bold text-center text-uppercase">You Also Might Like</h2>
                    @if($bannerAd->type == 'ImageUpload')
                    <div class="card">
                        <div class="card-body text-center">
                            <a href="{{ json_decode($bannerAd->content)->banner_link }}">
                                <img src="{{ asset('img/uploads/' . $store->subdomain . '/bannerAd/' . json_decode($bannerAd->content)->banner_image) }}" alt="" class="img-fluid">
                            </a>
                        </div>
                    </div>   
                    @else
                    {!! json_decode($bannerAd->content)->code !!}
                    @endif
                </div>
                @endif
            </div>
            @include('index.template-five.footer')
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

        function affiliateHit(productId, storeId){
            var URL = "{{ route('reports.affiliatehit', $store->subdomain) }}"

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

        $('.blogs-slider').slick({
            infinite: true,
            speed: 500,
            slidesToShow: 3,
            adaptiveHeight: true,
            dots: false,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2
                    },
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 1
                    },
                }
            ]
        });
    </script>
</html>