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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-eight/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-eight/schemes/' . $scheme . '.css') !!}" />
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
                @include('index.template-eight.sidenav')               
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-eight.nav')
            @if(count($sliders) > 0)
                @include('index.template-eight.slider')
            @endif
            <div class="container">
                <div class="card-body text-center">
                    <h2 class="text-uppercase bold">Categories</h2>
                    <hr class="c-hr">
                    <div class="slider-categories">
                        @foreach($store->categoryMenu as $menu)
                            <div class="category-card">
                                {{-- <a href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink]) }}" > --}}
                                    <div class="card">
                                        <div class="card-img">
                                            <img class="card-img-top" src="{{ (!isset($menu->category->image) || $menu->category->image == '') ? asset('img/uploads/category-default.jpg') : asset('img/uploads/'.$store->subdomain.'/categories/' . $menu->category->image) }}" alt="Card image cap">
                                            <div class="card-body category-body text-center">
                                                {{-- <h5 class="card-title text-capitalize">{{ $menu->category->name }}</h5> --}}
                                                <a href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink]) }}" class="btn btn-primary ">
                                                    {{ $menu->category->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                {{-- </a> --}}
                            </div>
                        @endforeach
                    </div>     
                </div>

                <div class="latest-product">
                    <h2 class="text-uppercase text-center bold">Latest Products</h2>
                    <hr class="c-hr">
                    <div class="row">
                        <div class="col-lg-12 text-center" id="filters">
                            <button class="btn btn-primary" data-filter="*">All</button>
                            @foreach($store->categoryMenu->take(4) as $submenu)
                            <button class="button btn btn-primary" data-filter=".{{ $submenu->category->permalink }}">{{ $submenu->category->permalink }}</button>
                            @endforeach
                        </div>
                    </div>
                    <div class="row latest-products">
                       
                    @foreach($store->categoryMenu->take(4) as $submenu)
                        @if(count($submenu->category->productCategory->take(4)) > 0)
                            @foreach($submenu->category->productCategory()->orderBy('id', 'DESC')->get()->take(4) as $product)
                                <div class="col-lg-3 latest-product-item {{ $submenu->category->permalink }}">
                                    <div class="product-card">
                                        <div class="card-img">
                                            <img class="card-img-top" src="{{ strpos( $product->product->image, 'https') !== false ? $product->product->image :  str_replace('http', 'https', $product->product->image) }}" alt="{{ $product->product->name }}">
                                        </div>
                                        <div class="card-body text-center">
                                            <p class="card-title text-capitalize">  
                                                <a onclick="pageHit('{{ Crypt::encrypt($product->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->product->permalink]) }}">
                                                    {!! strlen($product->product->name) > 35 ? substr($product->product->name, 0, 30) . "..." : $product->product->name !!}
                                                </a>
                                            </p>
                                            @if($product->product->price > 0)
                                            <p class="card-title">{{ currency($product->product->currency) }} {{ $product->product->price}}</p>
                                            @else
                                            <p class="card-title"><a onclick="affiliateHit('{{ Crypt::encrypt($product->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a></p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    @endforeach
                    </div>
                </div>
                
            </div>
           
            <div class="category-wrapper">
                <div class="container blogs-container">
                    <h2 class="text-uppercase bold text-center">Blogs</h2>
                    <hr class="c-hr">
                    @if(count($blogs) > 0)
                        <div class="blog-slider">
                            @foreach($blogs as $blog)
                            <!-- <div class="col-lg-12"> -->
                                <div class="card">
                                    <div class="card-body blog">
                                        <a href="{{ route('index.blog.view', [$store->subdomain, $blog->slug]) }}" target="_blank"><h3>{{ $blog->title }}</h3></a>
                                        {{ strlen(strip_tags($blog->post)) > 250 ? (substr(strip_tags($blog->post), 0, 247)) . "..." : strip_tags($blog->post) }}
                                    </div>
                                </div>
                            <!-- </div> -->
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <div class="container">
                    @if(isset($bannerAd))
                    <div class="row">
                        <div class="col-lg-12">
                            <h2 class="text-uppercase bold text-center">You Also Might Like</h2>
                            <hr class="c-hr">
                        </div>
                        @if($bannerAd->type == 'ImageUpload')
                        <div class="card">
                            <div class="card-body text-center">
                                <a href="{{ json_decode($bannerAd->content)->banner_link }}">
                                    <img src="{{ asset('img/uploads/' . $store->subdomain . '/bannerAd/' . json_decode($bannerAd->content)->banner_image) }}" alt="" class="img-fluid">
                                </a>
                            </div>
                        </div>   
                        @else
                        <div class="col-lg-12">
                            {!! json_decode($bannerAd->content)->code !!}
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
            @include('index.template-eight.footer')
        </div>        
    </div>
    @include('index.exit-pop')
</body>
    @include('index.footer-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="https://unpkg.com/isotope-layout@3.0.6/dist/isotope.pkgd.min.js"></script>
    
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
    <script type="text/javascript">
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

        $(function() {
            $('#ratings').barrating({
                theme: 'fontawesome-stars'
            });

            $('#average').barrating({
                theme: 'fontawesome-stars'
            });

            $('.list-ratings').barrating({
                theme: 'fontawesome-stars'
            });
        });

        $('.slider-categories').slick({
            infinite: true,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    }
                },
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        $('.blog-slider').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 3,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 770,
                    settings: {
                        dots: false,
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

        // init Isotope
        var $grid = $('.latest-products').isotope({
            itemSelector: '.latest-product-item',
            layoutMode: 'fitRows',
        });
        
        $('#filters').on( 'click', 'button', function() {
            var filterValue = $( this ).attr('data-filter');
            // use filterFn if matches value
            $grid.isotope({ filter: filterValue });
        });
    </script>
</html>