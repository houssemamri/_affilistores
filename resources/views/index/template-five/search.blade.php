<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="max-age=200" />
    <!-- //site icon -->
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ $metaTitle }}</title>
    
    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-five/schemes/' . $scheme . '.css') !!}" />
</head>
<body>
    @include('index.facebook-chat-support')
    <div class="wrapper products">
        <div class="sidebar">
            <div class="logo">
                <a href="{{ route('index', $store->subdomain) }}" class="simple-text logo-normal">
                    {{ $store->name }}
                </a>
            </div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                </ul>                
            </div>
        </div>
        <div class="main-panel">
            @include('index.template-five.nav')
        
            <div class="product-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card categories-title">
                                <div class="card-body text-center">
                                    <strong>
                                        <h2 class="text-capitalize">{{ $keyword }}</h2>
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(count($products) == 0)
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h3>No results found.</h3>
                                    </div>
                                </div>
                            </div>
                        @else
                            @foreach($products as $product)
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                    <div class="card border-primary mb-3">
                                        <div class="card-header text-center">
                                            <img src="{{ strpos( $product->image, 'https') !== false ? $product->image :  str_replace('http', 'https', $product->image) }}" alt="" class="img-fluid">
                                        </div>
                                        <div class="card-body product-content">
                                            <h4 class="card-title text-uppercase">
                                                <strong>
                                                    <a onclick="pageHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->permalink]) }}">
                                                        {{ strlen($product->name) > 35 ? substr($product->name, 0, 30) . "..." : $product->name }}
                                                    </a>
                                                </strong>
                                            </h4>
                                            <h3 class="card-title text-success bold">
                                                <span>
                                                    @if(strcasecmp('amazon', $product->source) == 0)
                                                        <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $product->source }}" width="30" class="img-fluid">
                                                    @elseif(strcasecmp('aliexpress', $product->source) == 0)
                                                        <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $product->source }}" width="30" class="img-fluid">
                                                    @elseif(strcasecmp('ebay', $product->source) == 0)
                                                        <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $product->source }}" width="30" class="img-fluid">
                                                    @elseif(strcasecmp('walmart', $product->source) == 0)
                                                        <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $product->source }}" width="30" class="img-fluid">
                                                    @elseif(strcasecmp('shopcom', $product->source) == 0)
                                                        <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $product->source }}" width="80" class="img-fluid">
                                                    @elseif(strcasecmp('cjcom', $product->source) == 0)
                                                        <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                                    @elseif(strcasecmp('jvzoo', $product->source) == 0)
                                                        <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                                    @elseif(strcasecmp('clickbank', $product->source) == 0)
                                                        <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                                    @elseif(strcasecmp('warriorplus', $product->source) == 0)
                                                        <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                                    @elseif(strcasecmp('paydotcom', $product->source) == 0)
                                                        <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $product->source }}" width="40" class="img-fluid">
                                                    @endif
                                                </span>
                                                @if($product->price > 0)
                                                <strong>{{ currency($product->currency) }} {{ $product->price }}</strong>
                                                @else
                                                <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a>
                                                @endif
                                            </h3>
                                            {!! strlen(strip_tags($product->description)) > 90 ? substr(strip_tags(html_entity_decode($product->description)), 0, 87) . "..." : strip_tags(html_entity_decode($product->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    @if($products->hasPages())
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                {{ $products->links() }}
                                </div>
                            </div>
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
    <script>
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