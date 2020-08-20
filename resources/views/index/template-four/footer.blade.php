<footer class="index-footer">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-4 col-md-12">
                <h6 class="bold text-uppercase">Categories</h6>
                <hr>
                <ul class="footer-categories">
                    @foreach($store->categoryMenu as $menu)
                        <li class="text-capitalize">
                            <a href="{{ route('index.category', ['subdomain' => $store->subdomain, 'permalink' => $menu->category->permalink]) }}">
                                <strong>
                                {{ $menu->category->name }}
                                </strong> 
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-4 col-md-12">
                <h6 class="bold text-uppercase">{{ (json_decode($store->storeTheme->footer_settings)->newsletter->heading == '') ? 'Newsletter' : json_decode($store->storeTheme->footer_settings)->newsletter->heading  }}</h6>
                <hr>

                <p>{{ (json_decode($store->storeTheme->footer_settings)->newsletter->text == '') ? 'Subscribe now' : json_decode($store->storeTheme->footer_settings)->newsletter->text }}</p>
                <form action="{{ route('index.subscribe', Session::get('subdomain')) }}" method="post">
                    @csrf
                    <div class="form-group row">
                        <input type="email" name="email" placeholder="Your email address" class="form-control email-subscribe col-md-7 " required>
                        <button class="btn btn-primary btn-sm col-md-4 " id="btn-subscribe" type="submit">Subscribe</button>
                    </div>
                </form>
            </div>
            <div class="col-lg-4 col-md-12">
                <h6 class="bold text-uppercase">About {{ $store->name }}</h6>
                <hr>
                <p>
                    <img width="150" class="footer-logo" src="{{ asset('img/uploads/' . $store->subdomain . '/logo/' . $store->logo ) }}" alt="{{ $site['site_name'] }}" class="img-fluid">
                    {{ json_decode($store->storeTheme->footer_settings)->about }}
                </p>
                
            </div>
        </div>
    </div>
</footer>

<footer class="footer sub-footer">
    <nav>
        <div class="container container-fluid">
            <ul class="stay-connected">
                @foreach($store->smo->where('display_options', 1) as $social)
                    <li class="text-capitalize">
                        <a href="{{ $social->page_url }}" target="_blank"><img src="{{ asset('img/options/option_' . $social->design_options . '/' . $social->name . '-logo.png') }}" alt="" width="30" class="img-fluid social-icons"></a>
                    </li>
                @endforeach
            </ul>
            <ul>
                <li  class="">
                    <a href="#"><img src="{{ asset('img/uploads/' .  $site['logo'] ) }}" alt="" class="img-fluid sub-footer-logo"></a>
                </li>
                <li  class="">
                    <a href="#">
                        &copy;
                        <script>
                            document.write(new Date().getFullYear())
                        </script> {{ $site['site_name'] }}.
                    </a>
                <li>
            </ul>
            <ul>
                <li class=""><a href="{{ route('index.blogs.list', ['subdomain' => $store->subdomain]) }}"><strong>Blogs</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'privacy']) }}"><strong>Privacy</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'terms-condtions']) }}"><strong>Terms & Conditions</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'contact-us']) }}"><strong>Contact Us</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'gdpr-compliance']) }}"><strong>GDPR Compliance</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'affiliate-disclosure']) }}"><strong>Affiliate Disclosure</strong></a></li>
                <li class=""><a href="{{ route('index.customerService', ['subdomain' => $store->subdomain, 'policy' => 'cookie-policy']) }}"><strong>Cookie Policy</strong></a></li>
            </ul>
        </div>
    </nav>
</footer>