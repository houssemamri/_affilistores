<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="max-age=200" />
    <title>{{ $metaTitle }}</title>
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    @if(isset($category->meta_description))
    <meta name="description" content="{{ $category->meta_description }}">
    @endif
    @if(isset($category->meta_keywords))
    <meta name="keywords" content="{{ $category->meta_keywords }}">
    @endif
    {!! isset($category->robots_meta_no_index) && ($category->robots_meta_no_index == 1) ? "<meta name='robots' content='noindex'>" : "" !!}
    {!! isset($category->robots_meta_no_follow) && ($category->robots_meta_no_follow == 1) ? "<meta name='robots' content='nofollow'>" : "" !!}

    @include('index.header')
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/style.css') !!}" />
    <link rel="stylesheet" type="text/css" href="{!! asset('css/template-one/schemes/' . $scheme . '.css') !!}" />
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
                    @include('index.template-one.sidenav')               
                </ul>                
            </div>
        </div>

        <div class="main-panel">
            @include('index.template-one.nav')
        
            <div class="product-wrapper">
                <div class="container">
                    <h2 class="text-uppercase bold">{{ $category->name }}</h2>
                    <h5>{!! $category->description !!}</h5>
                    <hr>
                </div>
                <div class="container">
                    <div class="row">
                        @foreach($products as $product)
                            @if($product->product->status == 1)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                                <div class="card border-primary mb-3">
                                    <div class="card-header text-center">
                                        <img src="{{ strpos( $product->product->image, 'https') !== false ? $product->product->image :  str_replace('http', 'https', $product->product->image) }}" alt="" class="img-fluid">
                                    </div>
                                    <div class="card-body product-content">
                                        <h4 class="card-title text-uppercase">
                                            <strong>
                                                <a onclick="pageHit('{{ Crypt::encrypt($product->product_id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ route('index.product.show', ['subdomain' => $store->subdomain, 'permalink' => $product->product->permalink]) }}">
                                                    {{ strlen($product->product->name) > 35 ? substr($product->product->name, 0, 30) . "..." : $product->product->name }}
                                                </a>
                                            </strong>
                                        </h4>
                                        <h3 class="card-title text-success bold">
                                            <span>
                                                @if(strcasecmp('amazon', $product->product->source) == 0)
                                                    <img src="{{ asset('img/amazon-icon.png') }}" alt="{{ $product->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('aliexpress', $product->product->source) == 0)
                                                    <img src="{{ asset('img/aliexpress-icon.png') }}" alt="{{ $product->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('ebay', $product->product->source) == 0)
                                                    <img src="{{ asset('img/ebay-icon.png') }}" alt="{{ $product->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('walmart', $product->product->source) == 0)
                                                    <img src="{{ asset('img/walmart-icon.jpg') }}" alt="{{ $product->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('shopcom', $product->product->source) == 0)
                                                    <img src="{{ asset('img/shopcom-icon.png') }}" alt="{{ $product->product->source }}" width="80" class="img-fluid">
                                                @elseif(strcasecmp('cjcom', $product->product->source) == 0)
                                                    <img src="{{ asset('img/cjcom-icon.png') }}" alt="{{ $product->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('jvzoo', $product->product->source) == 0)
                                                    <img src="{{ asset('img/jvzoo-icon.png') }}" alt="{{ $product->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('clickbank', $product->product->source) == 0)
                                                    <img src="{{ asset('img/clickbank-icon.png') }}" alt="{{ $product->product->source }}" width="30" class="img-fluid">
                                                @elseif(strcasecmp('warriorplus', $product->product->source) == 0)
                                                    <img src="{{ asset('img/warriorplus-icon.png') }}" alt="{{ $product->product->source }}" width="40" class="img-fluid">
                                                @elseif(strcasecmp('paydotcom', $product->product->source) == 0)
                                                    <img src="{{ asset('img/paydotcom-icon.png') }}" alt="{{ $product->product->source }}" width="40" class="img-fluid">
                                                @endif
                                            </span>
                                            @if($product->product->price > 0)
                                            <strong>{{ currency($product->product->currency) }} {{ $product->product->price }}</strong>
                                            @else
                                            <a onclick="affiliateHit('{{ Crypt::encrypt($product->product->id) }}', '{{ Crypt::encrypt($store->id) }}')" href="{{ $product->product->details_link }}" class="btn btn-primary btn-sm btn-check-price"><strong>Buy Now</strong></a>
                                            @endif
                                        </h3>
                                        {!! strlen(strip_tags($product->product->description)) > 90 ? substr(strip_tags(html_entity_decode($product->product->description)), 0, 87) . "..." : strip_tags(html_entity_decode($product->product->description)) !!}
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
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