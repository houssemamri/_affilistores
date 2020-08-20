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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-seven/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-seven/schemes/' . $scheme . '.css') !!}" />
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
                @include('index.template-seven.sidenav')               
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-seven.nav')
            @if(count($sliders) > 0)
                @include('index.template-seven.slider')
            @endif
            <div class="container slider ">
                <div class="card-body">
                    <h3 class="oswald text-uppercase">Categories</h3>
                    <hr align="left" class="c-hr hr-red">
                    <div class="row">
                        @foreach($store->categoryMenu as $menu)
                            <div class="col-md-3 category-card">
                                <a href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink]) }}" >
                                    <div class="card">
                                        <div class="card-img">
                                            <img class="card-img-top" src="{{ (!isset($menu->category->image) || $menu->category->image == '') ? asset('img/uploads/category-default.jpg') : asset('img/uploads/'.$store->subdomain.'/categories/' . $menu->category->image) }}" alt="Card image cap">
                                        </div>
                                        <div class="card-body text-center">
                                            <h5 class="card-title text-capitalize">{{ $menu->category->name }}</h5>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>                    
                </div>

                @foreach($store->categoryMenu->take(3) as $submenu)
                    @if(count($submenu->category->productCategory->take(4)) > 0)
                        <div class="card-body ">
                            <h3 class="text-uppercase oswald">{{ $submenu->category->name }}</h3>
                            <hr align="left" class="c-hr hr-blue">
                            <div class="row">
                                @foreach($submenu->category->productCategory->take(4) as $product)
                                    <div class="col-md-3 col-sm-12 product-card">
                                        <a onclick="pageHit('{{ Crypt::encrypt($product->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->product->permalink]) }}">
                                            <div class="card">
                                                <div class="card-img">
                                                    <img class="card-img-top" src="{{ strpos( $product->product->image, 'https') !== false ? $product->product->image :  str_replace('http', 'https', $product->product->image) }}" alt="{{ $product->product->name }}">
                                                </div>
                                                <div class="card-body text-center">
                                                    <h5 class="card-title text-capitalize"> {!! strlen($product->product->name) >30 ? substr($product->product->name, 0, 25) . "..." : $product->product->name !!}</h5>
                                                    
                                                    @if($product->product->price > 0)
                                                    <p class="text-muted bold">{{ currency($product->product->currency) }} {{ $product->product->price }}</p>
                                                    @else
                                                    <p>
                                                        <a target="_blank" onclick="affiliateHit('{{ Crypt::encrypt($product->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->product->details_link }}" class=""><strong>Buy Now</strong></a>
                                                    </p>
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
                    <h3 class="text-uppercase oswald">Blogs</h3>
                    <hr align="left" class="c-hr hr-yellow">
                    @if(count($blogs) > 0)
                        <div class="row">
                            @foreach($blogs as $blog)
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body blog">
                                        <div class="row">
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center blog-date">
                                                <h3>{{ date_format(date_create($blog->created_at), 'd') }}</h3>
                                                <h4>{{ date_format(date_create($blog->created_at), 'F') }}</h4>
                                                <p>{{ date_format(date_create($blog->created_at), 'Y') }}</p>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 blog-title">
                                                <a href="{{ route('index.blog.view', [$store->subdomain, $blog->slug]) }}" target="_blank"><h3>{{ $blog->title }}</h3></a>
                                            </div>
                                        </div>
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
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="text-uppercase oswald">You Also Might Like</h3>
                            <hr align="left" class="c-hr hr-green">
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
                </div>
                @endif
            </div>
            @include('index.template-seven.footer')
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
    </script>
</html>