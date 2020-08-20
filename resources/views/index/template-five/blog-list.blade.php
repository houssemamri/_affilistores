<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- //site icon -->
    <link rel="shortcut icon" href="{{ isset($store->storeTheme->favicon) ? asset('img/uploads/' .$store->subdomain. '/logo/' . $store->storeTheme->favicon) : asset('img/uploads/' . $site['logo']) }}">
    <title>{{ $store->name }} | Blogs</title>
    
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
                    <h3>Blogs</h3>
                    <hr>

                    @foreach($blogs as $blog)
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h3><a href="{{ route('index.blog.view', [$store->subdomain, $blog->slug]) }}">{{ $blog->title }}</a></h3>
                                    <p>{{ $blog->category->title }} | {{ date_format(date_create($blog->updated_at ), 'M d, Y') }}</p>
                                    
                                    {{ strlen(strip_tags($blog->post)) > 250 ? (substr(strip_tags($blog->post), 0, 247)) . "..." : strip_tags($blog->post) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    @if($blogs->hasPages())
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                    {{ $blogs->links() }}
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
</body>
    @include('index.footer-scripts')
</html>