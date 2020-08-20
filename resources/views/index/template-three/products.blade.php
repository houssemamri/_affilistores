<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="max-age=200" />
    <title>{{ $metaTitle }}</title>
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    @if(isset($product->seoSettings->meta_description))
    <meta name="description" content="{{ $product->seoSettings->meta_description }}">
    @endif
    @if(isset($product->seoSettings->meta_keywords))
    <meta name="keywords" content="{{ $product->seoSettings->meta_keywords }}">
    @endif
    {!! isset($product->seoSettings->robots_meta_no_index) && ($product->seoSettings->robots_meta_no_index == 1) ? "<meta name='robots' content='noindex'>" : "" !!}
    {!! isset($product->seoSettings->robots_meta_no_follow) && ($product->seoSettings->robots_meta_no_follow == 1) ? "<meta name='robots' content='nofollow'>" : "" !!}
    @include('index.meta-social')
    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick-theme.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-three/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-three/schemes/' . $scheme . '.css') !!}" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.css" />
    <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials-theme-flat.css" />
    <link rel="stylesheet" href="{!! asset('css/fontawesome-stars.css') !!} ">
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
    @include('index.facebook-chat-support')
    @include('index.facebook-comment-plugin')

    <div class="wrapper products">
        <div class="sidebar">
            <div class="logo">
                <a href="{{ route('index', $store->subdomain) }}" class="simple-text logo-normal">
                    {{ $store->name }}
                </a>
            </div>
            <div class="sidebar-wrapper">
                @include('index.template-three.sidenav')              
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-three.nav')
      
            <div class="product-wrapper">
                <div class="section-name">
                    <div class="container">
                        <h3 class="text-uppercase bold">{{ $product->name }}</h3>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 product-details">
                            <small class="text-muted bold text-uppercase">{{ $product->source }}</small>
                            <h4 class="text-muted bold text-uppercase">
                                @if($product->price > 0)
                                <strong>{{ currency($product->currency) }} {{ $product->price }}</strong>
                                @endif
                            </h4>
                            <div class="row">
                                <div class="col-lg-12">
                                    @if($product->price == 0)
                                    <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->details_link }}" class="btn btn-primary btn-product"><strong>Check Price</strong></a>
                                    @endif
                                    <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ isset($addToCartLink) ? $addToCartLink : $product->details_link }}" class="btn btn-primary btn-product"><strong>Buy Now</strong></a>
                                    
                                    <div id="share"></div>
                                </div>
                            </div>
                            <hr>
                            <div class="description">
                                {!! htmlspecialchars_decode(html_entity_decode($product->description)) !!}
                            </div>

                            @if($product->show_tweets)
                            <h4>Related Tweets</h4>
                            <!-- tweets here -->
                            <div class="row">
                                @foreach($product->tweets as $tweet)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="card">
                                            <div class="tweet-wrapper">
                                                <div class="row vertical-center">
                                                    <div class="col-lg-12">
                                                        <div class="tweet-avatar">
                                                            <h3>  
                                                                <img src="{{ strpos( $tweet->user_profile_img, 'https') !== false ? $tweet->user_profile_img :  str_replace('http', 'https', $tweet->user_profile_img) }}" alt="" class="img-fluid" width="60px">
                                                                <strong>{{ $tweet->user }}</strong>
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="tweet-content">
                                                            <div class="tweet-text">
                                                                <p>{{ $tweet->content }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <!-- img here -->
                            <div class="item">
                                <div class="product-image">
                                    <img src="{{ strpos( $product->image, 'https') !== false ? $product->image :  str_replace('http', 'https', $product->image) }}" alt="" >
                                </div>
                                @foreach($product->images as $image)
                                    <div class="product-image">
                                        <img src="{{ strpos( $image->image, 'https') !== false ? $image->image :  str_replace('http', 'https', $image->image) }}" alt="" >
                                    </div>
                                @endforeach
                            </div>
                            <div class="gallery">
                                <div class="item-gallery">
                                    <img src="{{ strpos( $product->image, 'https') !== false ? $product->image :  str_replace('http', 'https', $product->image) }}" alt="" class="img-fluid">
                                </div>
                                @foreach($product->images as $image)
                                <div class="item-gallery">
                                    <img src="{{ strpos( $image->image, 'https') !== false ? $image->image :  str_replace('http', 'https', $image->image) }}" alt="" class="img-fluid">
                                </div>
                                @endforeach
                            </div>
                            @if(isset($store->facebookCommentPlugin) && ($store->facebookCommentPlugin->sdk_code !== '' && $store->facebookCommentPlugin->code_snippet !== "") && in_array('facebook comment plugin', $features))
                            {!! $store->facebookCommentPlugin->code_snippet !!}
                            @endif
                        </div>
                    </div>           
                </div>

                @if($product->source == 'amazon')
                <div class="section-name">
                    <div class="container">
                        <h3>Amazon Customer Reviews</h3>
                    </div>
                </div>
                <div class="row customer-reviews">
                   <div class="container">
                    <div class="col-lg-12">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="">
                                </iframe>
                            </div>
                        </div>
                   </div>
                </div>
                @endif
                
                @include('index.template-three.review')

                @if((isset($product->videos[0]) || isset($product->videos[1])) && ($product->videos[0]->video !== '' || $product->videos[1]->video !== ''))
                <div class="section-name">
                    <div class="container">
                        <h3>Related Videos</h3>
                    </div>
                </div>
                <div class="container">
                   <div class="row">
                        <!-- related video here -->
                        @if($product->videos[1]->video == '' && $product->videos[1]->status == 1)
                        <div class="col-lg-12">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="{{ str_replace('watch?v=', 'embed/', $product->videos->first()->video) }}" allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        @else
                            @foreach($product->videos->where('status', 1) as $video)
                                <div class="col-lg-{{ $product->videos->where('status', 1)->count() > 1 ? '6' : '12' }} col-md-{{ $product->videos->where('status', 1)->count() > 1 ? '6' : '12' }}">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{ str_replace('watch?v=', 'embed/', $video->video) }}" allowfullscreen>
                                        </iframe>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                   </div>
                </div>
                @endif

                @if(isset($bannerAd))
                    <div class="section-name">
                        <div class="container">
                            <h3>You Also Might Like</h3>
                        </div>
                    </div>
                    <div class="container">
                    @if($bannerAd->type == 'ImageUpload')
                    <div class="card-body text-center">
                        <a href="{{ json_decode($bannerAd->content)->banner_link }}">
                            <img src="{{ asset('img/uploads/' . $store->subdomain . '/bannerAd/' . json_decode($bannerAd->content)->banner_image) }}" alt="" class="img-fluid">
                        </a>
                    </div>
                    @else
                        {!! json_decode($bannerAd->content)->code !!}
                    @endif
                    </div>
                @endif

                @if(isset($product->blog) && $product->blog->published == 1)
                <div class="container">
                    <div class="card">
                        <div class="card-body">
                            <ul class="nav detail-heading">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#">
                                        {{ $product->blog->title }}
                                    </a>
                                </li>
                            </ul>
                            {!! htmlspecialchars_decode($product->blog->description) !!}
                        </div>
                    </div>    
                </div>
                @endif

                <div class="section-name">
                    <div class="container">
                        <h3>Related Products</h3>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                <div class="card border-primary mb-3">
                                    <div class="card-header text-center">
                                        <img src="{{ strpos( $relatedProduct->product->image, 'https') !== false ? $relatedProduct->product->image :  str_replace('http', 'https', $relatedProduct->product->image) }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="card-body product-content text-center">
                                        <p class="text-muted"><small class="bold text-uppercase">{{ $relatedProduct->product->source }}</small></p>
                                        <h4 class="card-title text-uppercase">
                                            <strong>
                                                <a onclick="pageHit('{{ Crypt::encrypt($relatedProduct->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $relatedProduct->product->permalink]) }}">
                                                    {!! strlen($relatedProduct->product->name) > 35 ? substr($relatedProduct->product->name, 0, 30) . "..." : $relatedProduct->product->name !!}
                                                </a>
                                            </strong>
                                        </h4>
                                        <p class="card-title text-muted bold">
                                            @if($relatedProduct->product->price > 0)
                                            {{ currency($relatedProduct->product->currency) }} {{ $relatedProduct->product->price }}
                                            @else
                                            <a onclick="affiliateHit('{{ Crypt::encrypt($relatedProduct->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $relatedProduct->product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                   </div>
                </div>
            </div>

            @include('index.template-three.footer')
        </div>
    </div>
    @include('index.exit-pop')
</body>
    @include('index.footer-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery.jssocials/1.4.0/jssocials.min.js"></script>
    <script>
        $("#share").jsSocials({
            shares: ["email", "twitter", "facebook", "googleplus", "linkedin", "pinterest"]
        });
    </script>
    @if(isset($countdownTimer) && $countdownTimer->countdown_date >= date('Y-m-d'))
    <script src="{!! asset('js/flipclock.min.js') !!}"></script>
    <script>
        var timer = new Date('{{ $countdownTimer->countdown_date }}');         
        var now = new Date();         
        var diff = (timer.getTime()/1000) - (now.getTime()/1000);

        var clock = $('.clock').FlipClock(diff,{
            clockFace: 'DailyCounter',
            countdown: true
        });

        function openInNewTab() {
            var win = window.open('{{ $countdownTimer->access_link }}', '_blank');
        }
    </script>
    @endif
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
      
            openInNewTab()
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
    <script>
        $('.item').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            asNavFor: '.gallery'
        });
        $('.gallery').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: '.item',
            dots: false,
            centerMode: true,
            focusOnSelect: true
        });
    </script>
    @include('index.amazon-reviews')
</html>