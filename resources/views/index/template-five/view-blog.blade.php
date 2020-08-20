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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/schemes/' . $scheme . '.css') !!}" />
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
                @include('index.template-five.sidenav')               
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-five.nav')
        
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

                    <h3>Related Products</h3>

                    <div class="row">
                        @foreach($blogProducts as $blogProduct)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="card border-primary mb-3">
                                    <div class="card-header text-center">
                                        <img src="{{ strpos( $blogProduct->product->image, 'https') !== false ? $blogProduct->product->image :  str_replace('http', 'https', $blogProduct->product->image) }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="card-body product-content">
                                        <h4 class="card-title bold text-uppercase">
                                            <strong>
                                                <a onclick="pageHit('{{ Crypt::encrypt($blogProduct->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $blogProduct->product->permalink]) }}">
                                                    {{ strlen($blogProduct->product->name) > 35 ? substr($blogProduct->product->name, 0, 30) . "..." : $blogProduct->product->name }}
                                                </a>
                                            </strong>
                                        </h4>
                                        <h3 class="card-title bold text-success">
                                            <span>
                                                @if(strcasecmp('amazon', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('aliexpress', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('ebay', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('walmart', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $blogProduct->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('shopcom', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="80" class="img-fluid">
                                                @elseif(strcasecmp('cjcom', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('jvzoo', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('clickbank', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('warriorplus', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('paydotcom', $blogProduct->product->source) == 0)
                                                    <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $blogProduct->product->source }}" width="40" class="img-fluid">
                                                @endif
                                            </span>
                                            @if($blogProduct->product->price > 0)
                                                <strong>{{ currency($blogProduct->product->currency) }} {{ $blogProduct->product->price }}</strong>
                                            @else
                                                <a onclick="affiliateHit('{{ Crypt::encrypt($blogProduct->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $blogProduct->product->details_link }}" class="btn btn-primary btn-check-price"><strong>Buy Now</strong></a>
                                            @endif
                                        </h3>
                                        {{ strlen(strip_tags($blogProduct->product->description)) > 90                                          ? html_entity_decode(substr(strip_tags(preg_replace('/<style>(.*?)<\/style>/s', '', html_entity_decode(htmlspecialchars_decode($blogProduct->product->description)))), 0, 87)) . "..."                                          : html_entity_decode(strip_tags(preg_replace('/<style>(.*?)<\/style>/s', '', html_entity_decode(htmlspecialchars_decode($blogProduct->product->description))))) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @include('index.template-five.footer')
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
</html>