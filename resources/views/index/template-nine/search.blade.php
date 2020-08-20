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
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-nine/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-nine/schemes/' . $scheme . '.css') !!}" />
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
            @include('index.template-nine.nav')
        
            <div class="product-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card categories-title">
                                <div class="card-body text-center">
                                    <h3 class="text-capitalize text-center section-title"><span> {{ $keyword }} </span></h3>
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
                                            <div class="store">
                                                <p class="text-muted"><small class="bold text-uppercase">{{ $product->source }}</small></p>
                                            </div>
                                        </div>
                                        <div class="card-body text-left product-content">
                                            <h4 class="card-title text-capitalize">
                                                <strong>
                                                    <a class="text-muted" onclick="pageHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->permalink]) }}">
                                                        {!! strlen($product->name) > 35 ? substr($product->name, 0, 30) . "..." : $product->name !!}
                                                    </a>
                                                </strong>
                                            </h4>
                                            <hr>
                                            <p class="card-title text-success bold">
                                                @if($product->price > 0)
                                                {{ currency($product->currency) }} {{ $product->price }}
                                                @else
                                                <a onclick="affiliateHit('{{ Crypt::encrypt($product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @if(isset($products) && count($products) > 0)
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
            
            @include('index.template-nine.footer')
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