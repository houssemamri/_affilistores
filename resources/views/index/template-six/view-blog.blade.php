<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- //site icon -->
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ $store->name }} | {{ $blog->title }}</title>
    
    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-six/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-six/schemes/' . $scheme . '.css') !!}" />

    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/slick/slick-theme.css') !!}" />
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
                @include('index.template-six.sidenav')               
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-six.nav')
        
            <div class="product-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3>{{ $blog->title }}</h3>
                                    <em><p>Source: <a href="{{ $blog->url }}">{{ $blog->url }}</a></p></em>
                                    {!! $blog->post !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="information-container">
                        <h2 class="bold text-center text-uppercase">Related Products</h2>
                        <div class="slider-related-products">
                            @foreach($blogProducts as $blogProduct)
                                <div class="card border-primary mb-3">
                                    <div class="card-header text-center">
                                        <img src="{{ strpos( $blogProduct->product->image, 'https') !== false ? $blogProduct->product->image :  str_replace('http', 'https', $blogProduct->product->image) }}" alt="" class="img-fluid">
                                        <div class="store">
                                            <p class="text-muted"><small class="bold text-uppercase">{{ $blogProduct->product->source }}</small></p>
                                        </div>
                                    </div>
                                    <div class="card-body product-content">
                                        <h4 class="card-title text-uppercase">
                                            <strong>
                                                <a onclick="pageHit('{{ Crypt::encrypt($blogProduct->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $blogProduct->product->permalink]) }}">
                                                    {!! strlen($blogProduct->product->name) > 35 ? substr($blogProduct->product->name, 0, 30) . "..." : $blogProduct->product->name !!}
                                                </a>
                                            </strong>
                                        </h4>
                                        <p class="card-title text-muted bold">
                                            @if($blogProduct->product->price > 0)
                                            {{ currency($blogProduct->product->currency) }} {{ $blogProduct->product->price }}
                                            @else
                                            <a onclick="affiliateHit('{{ Crypt::encrypt($blogProduct->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $blogProduct->product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>                        
                    </div>
                </div>
            </div>

            @include('index.template-six.footer')
        </div>
    </div>
</body>
    @include('index.footer-scripts')
    <script src="{!! asset('js/axios.min.js') !!}"></script>

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
            focusOnSelect: true,
            responsive: [
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                }
            ]
        });

        $('.slider-related-products').slick({
            infinite: true,
            autoplay: true,
            dots: false,
            slidesToShow: 4,
            slidesToScroll: 4,
            responsive: [
                {
                    breakpoint: 770,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
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

        $('.collapse').on('shown.bs.collapse', function(){
            $(this).parent().find(".material-icons").text('remove');
        }).on('hidden.bs.collapse', function(){
            $(this).parent().find(".material-icons").text('add');
        });
    </script>
</html>