<nav class="navbar navbar-expand-lg navbar-dark bg-primary ">
    <div class="container container-fluid">
        <div class="navbar-wrapper nav-store-name">
            <a class="navbar-brand text-uppercase" href="{{ route('index', $store->subdomain) }}">
                <img width="100" class="store-logo" src="{{ asset('img/uploads/' . $store->subdomain . '/logo/' . $store->logo ) }}" alt="{{ $site['site_name'] }}" class="img-fluid">
                {{ $store->name }}
                </a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav navbar-right banner-nav mr-auto">
                @if($store->bannerAd->where('type', 'MenuBanner') !== null && count($store->bannerAd->where('type', 'MenuBanner')) > 0 && $store->bannerAd->where('type', 'MenuBanner')->first()->selected == 1)
                <li class="menu-banner">
                    <a href="{{ json_decode($store->bannerAd->where('type', 'MenuBanner')->first()->content)->link }}">
                        <img src="{{ asset('img/uploads/'.$store->subdomain.'/'.'bannerAd/' . json_decode($store->bannerAd->where('type', 'MenuBanner')->first()->content)->image ) }}" alt="" class="img-fluid">
                    </a>
                </li>
                @endif

                @if($store->bannerAd->where('type', 'MenuBannerAdSense') !== null && count($store->bannerAd->where('type', 'MenuBannerAdSense')) > 0 && $store->bannerAd->where('type', 'MenuBannerAdSense')->first()->selected == 1)
                <li class="menu-banner">
                {!! json_decode($store->bannerAd->where('type', 'MenuBannerAdSense')->first()->content)->code !!}
                </li>
                @endif

                @foreach($store->categoryMenu as $submenu)
                <li class="nav-item d-none">
                    <a class="nav-link" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $submenu->category->permalink ]) }}" >
                        <p class="d-lg-none d-md-block">
                            <span class="d-lg-none d-md-block">{{ $submenu->category->name }}</span>
                        </p>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</nav>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary nav-second">
    <div class="container container-fluid">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav navbar-right mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="categoryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="d-none d-lg-block d-xl-block"> All Categories</span>
                        <p class="d-lg-none d-md-block">
                            <span class="d-lg-none d-md-block">All Categories</span>
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="categoryDropdown">
                        @foreach($store->categoryMenu()->skip(6)->take(100000)->get() as $menu)
                            <a class="dropdown-item" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink ]) }}">{{ ucwords($menu->category->name) }}</a>
                        @endforeach
                    </div>
                </li>
                @foreach($store->categoryMenu->take(6) as $submenu)
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $submenu->category->permalink ]) }}" >
                        <span class="d-none d-lg-block d-xl-block"> {{ $submenu->category->name }}</span>
                        <p class="d-lg-none d-md-block">
                            <span class="d-lg-none d-md-block">{{ $submenu->category->name }}</span>
                        </p>
                    </a>
                </li>
                @endforeach
            </ul>

            <form class="navbar-form"  method="get" action="{{ route('index.product.search', ['subdomain' => $store->subdomain]) }}" >
                <div class="input-group no-border">
                    <div class="input-group">
                        <input class="form-control search-nav" type="text" id="store-search" placeholder="Search all products" name="keyword" required>

                        <div class="input-group-append">
                            <button class="btn btn-white btn-just-icon"  type="submit">
                                <i class="material-icons">search</i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</nav>