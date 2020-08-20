<div class="footer-separator"></div>
<nav class="navbar navbar-expand-lg nav-footer">
    <div class="container">
        <ul class="navbar-nav navbar-left mr-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" href="#" id="categoryDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-none d-lg-block d-xl-block"> All Categories</span>
                    <p class="d-lg-none d-md-block">
                        <span class="d-lg-none d-md-block">All Categories</span>
                    </p>
                </a>
                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="categoryDropdown">
                    @foreach($store->categoryMenu()->skip(4)->take(100000)->get() as $menu)
                        <a class="dropdown-item" href="{{ route('index.category', ['subdomain' => Session::get('subdomain'), 'permalink' => $menu->category->permalink ]) }}">{{ ucwords($menu->category->name) }}</a>
                    @endforeach
                </div>
            </li>
            @foreach($store->categoryMenu->take(4) as $submenu)
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
        <ul class="navbar-nav navbar-right mr-auto">
            @foreach($store->smo->where('display_options', 1) as $social)
                <li class="nav-item ">
                        <a href="{{ $social->page_url }}" class="nav-link" target="_blank"><img src="{{ asset('img/options/option_' . $social->design_options . '/' . $social->name . '-logo.png') }}" alt="" width="30" class="img-fluid social-icons"></a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>

<footer class="index-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <h5 class="text-uppercase">{{ (json_decode($store->storeTheme->footer_settings)->newsletter->heading == '') ? 'Newsletter' : json_decode($store->storeTheme->footer_settings)->newsletter->heading  }}</h5>

                <p class="text-muted">{{ (json_decode($store->storeTheme->footer_settings)->newsletter->text == '') ? 'Subscribe now' : json_decode($store->storeTheme->footer_settings)->newsletter->text }}</p>
                <form action="{{ route('index.subscribe', Session::get('subdomain')) }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <input type="email" name="email" placeholder="Your email address" class="form-control email-subscribe col-md-8 " required>
                        <button class="btn btn-primary btn-sm col-md-3 " id="btn-subscribe" type="submit">
                            <i class="material-icons">chevron_right</i>
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-12">
                <h5 class="text-uppercase ">About {{ $store->name }}</h5>
                <p class="text-muted">
                    <img width="150" class="footer-logo" src="{{ asset('img/uploads/' . $store->subdomain . '/logo/' . $store->logo ) }}" alt="{{ $site['site_name'] }}" class="img-fluid">
                    {{ json_decode($store->storeTheme->footer_settings)->about }}
                </p>
                
            </div>
            <div class="col-lg-4 col-md-12">
                <h5 class="text-uppercase">Categories</h5>
                <ul class="footer-categories">
                    @foreach($store->categoryMenu as $menu)
                        <li class="text-capitalize ">
                            <a href="{{ route('index.category', ['subdomain' => $store->subdomain, 'permalink' => $menu->category->permalink]) }}">
                                <strong class="text-muted">
                                {{ $menu->category->name }}
                                </strong> 
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>

<footer class="footer sub-footer">
    <nav>
        <div class="container container-fluid">
            <ul>
                <li  class="li-left">
                    <a href="#"><img src="{{ asset('img/uploads/' .  $site['logo'] ) }}" alt="" class="img-fluid sub-footer-logo"></a>
                </li>
                <li  class="li-left">
                    <a href="#">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ $site['site_name'] }}.
                    </a>
                <li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'privacy']) }}"><strong>Privacy</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'terms-condtions']) }}"><strong>Terms & Conditions</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'contact-us']) }}"><strong>Contact Us</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'gdpr-compliance']) }}"><strong>GDPR Compliance</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'affiliate-disclosure']) }}"><strong>Affiliate Disclosure</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'cookie-policy']) }}"><strong>Cookie Policy</strong></a></li>
                    <li class="li-right"><a href="{{ route('index.blogs.list', ['subdomain' => $store->subdomain]) }}"><strong>Blogs</strong></a></li>
            </ul>
        </div>
    </nav>
</footer>