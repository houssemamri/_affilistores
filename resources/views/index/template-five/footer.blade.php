<footer class="index-footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="row">
                    <div class="col-lg-12">
                        <h4 class="text-uppercase rokkitt">About {{ $store->name }}</h4>
                        
                        <p>
                            <img width="150" class="footer-logo" src="{{ asset('img/uploads/' . $store->subdomain . '/logo/' . $store->logo ) }}" alt="{{ $site['site_name'] }}" class="img-fluid">
                            {{ json_decode($store->storeTheme->footer_settings)->about }}
                        </p>
                    </div>
                    <div class="col-lg-12">
                        <ul class="stay-connected">
                            @foreach($store->smo->where('display_options', 1) as $social)
                                <li class="text-capitalize">
                                        <a href="{{ $social->page_url }}" target="_blank"><img src="{{ asset('img/options/option_' . $social->design_options . '/' . $social->name . '-logo.png') }}" alt="" width="30" class="img-fluid social-icons"></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <h4 class="text-uppercase rokkitt ">Categories</h4>
                
                <ul class="footer-categories">
                    @foreach($store->categoryMenu as $menu)
                        <li class="text-uppercase">
                            <a href="{{ route('index.category', ['subdomain' => $store->subdomain, 'permalink' => $menu->category->permalink]) }}">
                                {{ $menu->category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4 col-md-12">
                <h4 class=" text-uppercase rokkitt">{{ (json_decode($store->storeTheme->footer_settings)->newsletter->heading == '') ? 'Newsletter' : json_decode($store->storeTheme->footer_settings)->newsletter->heading  }}</h4>

                <p>{{ (json_decode($store->storeTheme->footer_settings)->newsletter->text == '') ? 'Subscribe now' : json_decode($store->storeTheme->footer_settings)->newsletter->text }}</p>
                <form action="{{ route('index.subscribe', Session::get('subdomain')) }}" method="post">
                    @csrf

                    <div class="form-group">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="email" name="email" class="form-control email-subscribe" required>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" id="btn-subscribe" type="submit">Subscribe</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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