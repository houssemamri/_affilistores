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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/schemes/' . $scheme . '.css') !!}" />
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
                @include('index.template-five.sidenav')              
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-five.nav')
      
            <div class="product-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 product-slider">
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
                        <div class="col-lg-6 col-md-12 product-details">
                            <h2 class="text-uppercase">{{ $product->name }}</h2>
                            <h2 class="text-success bold">
                                <span>
                                    @if(strcasecmp('amazon', $product->source) == 0)
                                        <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $product->source }}" width="50" class="img-fluid">
                                    @elseif(strcasecmp('aliexpress', $product->source) == 0)
                                        <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $product->source }}" width="50" class="img-fluid">
                                    @elseif(strcasecmp('ebay', $product->source) == 0)
                                        <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $product->source }}" width="50" class="img-fluid">
                                    @elseif(strcasecmp('walmart', $product->source) == 0)
                                        <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $product->source }}" width="50" class="img-fluid">
                                    @elseif(strcasecmp('shopcom', $product->source) == 0)
                                        <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $product->source }}" width="100" class="img-fluid">
                                    @elseif(strcasecmp('cjcom', $product->source) == 0)
                                        <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                    @elseif(strcasecmp('jvzoo',$product->source) == 0)
                                        <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{$product->source }}" width="40" class="img-fluid">
                                    @elseif(strcasecmp('clickbank',$product->source) == 0)
                                        <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{$product->source }}" width="40" class="img-fluid">
                                    @elseif(strcasecmp('warriorplus',$product->source) == 0)
                                        <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{$product->source }}" width="40" class="img-fluid">
                                    @elseif(strcasecmp('paydotcom',$product->source) == 0)
                                        <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{$product->source }}" width="40" class="img-fluid">
                                    @endif
                                </span>
                              
                            </h2>

                            @if($product->price > 0)
                              <h3>{{ currency($product->currency) }} {{ $product->price }}</h3>
                            @endif

                            <div class="row">
                                <div class="col-lg-12">
                                    @if($product->price == 0)
                                    <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->details_link }}" class="btn btn-primary btn-product"><strong>Check Price</strong></a>
                                    @endif
                                    <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ isset($addToCartLink) ? $addToCartLink : $product->details_link }}" class="btn btn-primary btn-product"><strong>Buy Now</strong></a>
                
                                    <div id="share"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                 
                 <div class="information">
                        <ul class="nav nav-pills">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#description">Description</a>
                            </li>
                            @if($product->show_tweets)
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#tweet">Related Tweets</a>
                            </li>
                            @endif

                            @if($product->source == 'amazon')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#amazon">Amazon Customer Reviews</a>
                            </li>
                            @endif

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#customer-reviews">Customer Reviews</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#videos">Related Videos</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#relatedProducts">Related Products</a>
                            </li>
                        </ul>
                        <div id="myTabContent" class="tab-content">
                            <div class="tab-pane fade show active" id="description">
                                <div class="description">
                                    {!! htmlspecialchars_decode(html_entity_decode($product->description)) !!}
                                </div>
                            </div>
                            @if($product->show_tweets)
                            <div class="tab-pane fade" id="tweet">
                                <!-- tweets here -->
                                <div class="row">
                                    @foreach($product->tweets as $tweet)
                                    <div class="col-lg-6 col-md-12">
                                        <div class="card">
                                            <div class="tweet-wrapper">
                                                <div class="row vertical-center">
                                                    <div class="col-lg-12">
                                                        <div class="tweet-avatar">
                                                            <h4>  
                                                                <img src="{{ strpos( $tweet->user_profile_img, 'https') !== false ? $tweet->user_profile_img :  str_replace('http', 'https', $tweet->user_profile_img) }}" alt="" class="img-fluid" width="60px">
                                                                <strong>{{ $tweet->user }}</strong>
                                                            </h4>
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
                            </div>
                            @endif
                            @if($product->source == 'amazon')
                                <div class="tab-pane fade" id="amazon">
                                    <div class="row customer-reviews">
                                        <div class="col-lg-12">
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item" src="">
                                                </iframe>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="tab-pane fade" id="customer-reviews">
                                @include('index.template-five.review')
                            </div>
                            <div class="tab-pane fade" id="videos">
                                @if((isset($product->videos[0]) || isset($product->videos[1])) && ($product->videos[0]->video !== '' || $product->videos[1]->video !== ''))
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
                                @endif
                            </div>
                            <div class="tab-pane fade" id="relatedProducts">
                                <div class="row">
                                @foreach($relatedProducts as $relatedProduct)
                                    <div class="col-lg-3 col-md-6 col-sm-12">
                                        <div class="card border-primary mb-3">
                                            <div class="card-header text-center">
                                                <img src="{{ strpos( $relatedProduct->product->image, 'https') !== false ? $relatedProduct->product->image :  str_replace('http', 'https', $relatedProduct->product->image) }}" alt="" class="img-fluid">
                                            </div>
                                            <div class="card-body product-content">
                                                <h4 class="card-title bold text-uppercase">
                                                    <strong>
                                                        <a onclick="pageHit('{{ Crypt::encrypt($relatedProduct->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $relatedProduct->product->permalink]) }}">
                                                            {{ strlen($relatedProduct->product->name) > 35 ? substr($relatedProduct->product->name, 0, 30) . "..." : $relatedProduct->product->name }}
                                                        </a>
                                                    </strong>
                                                </h4>
                                                <h3 class="card-title bold text-success">
                                                    <span>
                                                        @if(strcasecmp('amazon', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="30" class="img-fluid">
                                                        @elseif(strcasecmp('aliexpress', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="30" class="img-fluid">
                                                        @elseif(strcasecmp('ebay', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="30" class="img-fluid">
                                                        @elseif(strcasecmp('walmart', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $relatedProduct->product->source }}" width="30" class="img-fluid">
                                                        @elseif(strcasecmp('shopcom', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="80" class="img-fluid">
                                                        @elseif(strcasecmp('cjcom', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="30" class="img-fluid">
                                                        @elseif(strcasecmp('jvzoo', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="40" class="img-fluid">
                                                        @elseif(strcasecmp('clickbank', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="40" class="img-fluid">
                                                        @elseif(strcasecmp('warriorplus', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="40" class="img-fluid">
                                                        @elseif(strcasecmp('paydotcom', $relatedProduct->product->source) == 0)
                                                            <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $relatedProduct->product->source }}" width="40" class="img-fluid">
                                                        @endif
                                                    </span>
                                                    @if($relatedProduct->product->price > 0)
                                                        <strong>{{ currency($relatedProduct->product->currency) }} {{ $relatedProduct->product->price }}</strong>
                                                    @else
                                                        <a onclick="affiliateHit('{{ Crypt::encrypt($relatedProduct->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $relatedProduct->product->details_link }}" class="btn btn-primary btn-check-price"><strong>Buy Now</strong></a>
                                                    @endif
                                                </h3>
                                                {!! strlen(strip_tags($relatedProduct->product->description)) > 90 ? substr(strip_tags($relatedProduct->product->description), 0, 87) . "..." : $relatedProduct->product->description !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($bannerAd))
                        <h3 class="bold text-uppercase">You Also Might Like</h3>
                        <hr>
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
                    <div class="card">
                        <div class="card-body">
                            <h3 class="text-uppercase bold">{{ $product->blog->title }}</h3>
                            {!! htmlspecialchars_decode($product->blog->description) !!}
                        </div>
                    </div>    
                    @endif
                </div>
            </div>
            @include('index.template-five.footer')
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
        var diff = ((now.getTime()/1000) - (timer.getTime()/1000));

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