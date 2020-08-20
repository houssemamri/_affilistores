<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="#" class="simple-text logo-normal ">
            <img src="{!! asset('img/uploads/'. $site['logo']) !!}" alt="Instant Funnel Labs" class="img-fluid">
        </a>
    </div>
    <div class="sidebar-wrapper" id="style-1">
        <ul class="nav main-ul">
            @foreach($sideMenus as $sideMenu)
                @if($sideMenu->slug == 'dashboard')
                <li class="nav-item {{ (Request::segment(2) == '' || Request::segment(2) == 'pages') ? 'active' : '' }}">
                    @if(count($sidenavPages['dashboard']) > 0)
                        <a  data-toggle="collapse" data-target="#dashboard" data-parent="#sidenav01" onclick="window.location.replace('{{ Session::has('subdomain') ? route('dashboard', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}')" href="#"  class="nav-link {{ (Request::segment(2) == '' || Request::segment(2) == 'pages') ? 'collapsed' : '' }}" >
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p >Dashboard <span class="caret pull-right" ></span></p>
                        </a>
                        <div class="collapse {{ (Request::segment(2) == '' || (Request::segment(2) == '' && Request::segment(2) == 'pages')) ? 'show' : '' }}" id="dashboard" >
                        <ul class="nav">
                            @foreach($sidenavPages['dashboard'] as $dashboardPage)
                                <li class="nav-item {{ (Request::segment(3) == $dashboardPage->slug) ? 'active' : '' }}">
                                    <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('default.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $dashboardPage->slug]) : route('redirectListStore') }}">
                                        <p>{{ $dashboardPage->title }} </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul
                    @else
                        <a class="nav-link" href="{{ Session::has('subdomain') ? route('dashboard', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Dashboard</p>
                        </a>
                    @endif
                </li>
                @elseif($sideMenu->slug == 'reports')
                <li class="nav-item {{ (Request::segment(2) ==  'reports') ? 'active' : '' }}">
                    @if(count($sidenavPages['reports']) > 0)
                        <a data-toggle="collapse" data-target="#reports" data-parent="#sidenav01" class="nav-link collapsed" onclick="window.location.replace('{{ Session::has('subdomain') ? route('reports.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}')" href="#">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Reports <span class="caret pull-right"></span></p>
                        </a>
                        <div class="collapse {{ (Request::segment(2) == 'reports' || (Request::segment(2) == 'reports' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="reports" >
                        <ul class="nav">
                            @foreach($sidenavPages['reports'] as $report)
                                <li class="nav-item {{ (Request::segment(4) ==  $report->slug) ? 'show' : '' }}">
                                    <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('reports.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $report->slug]) : route('redirectListStore') }}">
                                        <p>{{ $report->title }} </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul
                    @else
                        <a class="nav-link" href="{{ Session::has('subdomain') ? route('reports.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Reports</p>
                        </a>
                    @endif
                </li>
                @elseif($sideMenu->slug == 'store-design')
                <li class="nav-item {{ (Request::segment(2) == 'store-design') ? 'active' : '' }}">
                    <a data-toggle="collapse" data-target="#storedesign" data-parent="#sidenav01" class="nav-link collapsed" href="#">
                        <i class="material-icons">{{ $sideMenu->icon }}</i>
                        <p>My Store<span class="caret pull-right"></span></p>
                    </a>
                    <div class="collapse {{ (Request::segment(2) == 'store-design' || (Request::segment(2) == 'store-design' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="storedesign" >
                        <ul class="nav">
                            <li class="nav-item {{ (Request::segment(3) == 'theme') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('theme', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Choose Theme </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'slider') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('slider', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Enable Slider </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'banner-ad') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('bannerAd', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Enable Banners</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'legal-pages') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('legalPages', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Legal Pages</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'categories' && Request::segment(2) == 'store-design') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('categories.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Create Categories </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'category-menu') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('categoryMenu', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Create Menu</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'footer-settings') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('footerSettings', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Footer Settings</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'smo-settings') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('smo', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Social Media Links</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item {{ (Request::segment(3) == 'clone') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('clone.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Clone Store</p>
                                </a>
                            </li> --}}
                            @if(count($sidenavPages['store-design']) > 0))
                                    @foreach($sidenavPages['store-design'] as $storeDesignPage)
                                        <li class="nav-item {{ (Request::segment(4) == $storeDesignPage->slug) ? 'active' : '' }}">
                                            <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('storedesign.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $storeDesignPage->slug]) : route('redirectListStore') }}">
                                                <p>{{ $storeDesignPage->title }} </p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul
                            @endif
                        </ul>
                    </div>
                </li>
                @elseif($sideMenu->slug == 'products')
                <li class="nav-item {{ (Request::segment(2) == 'products') ? 'active' : '' }}">
                    <a data-toggle="collapse" data-target="#products" data-parent="#sidenav01" class="nav-link collapsed" href="#">
                        <i class="material-icons">{{ $sideMenu->icon }}</i>
                        <p>Manage Products<span class="caret pull-right"></span></p>
                    </a>
                    <div class="collapse {{ (Request::segment(2) == 'products' || (Request::segment(2) == 'products' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="products" >
                        <ul class="nav">
                            <li class="nav-item {{ (Request::segment(2) == 'products' && Request::segment(3) == '') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('products.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>All Products </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'tags') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('tags.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Tags</p>
                                </a>
                            </li>

                            <li class="nav-item {{ (Request::segment(3) == 'automation') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('automation.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}" disabled>
                                    <p>Automation</p>
                                </a>
                            </li>
                            @if(count($sidenavPages['products']) > 0)
                                @foreach($sidenavPages['products'] as $productPage)
                                    <li class="nav-item {{ (Request::segment(4) == $productPage->slug) ? 'active' : '' }}">
                                        <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('products.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $productPage->slug]) : route('redirectListStore') }}">
                                            <p>{{ $productPage->title }} </p>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </li>
                @elseif($sideMenu->slug == 'blogs')
                {{-- <li class="nav-item {{ (Request::segment(2) == 'blogs') ? 'active' : '' }}">
                    <a data-toggle="collapse" data-target="#blogs" data-parent="#sidenav01" class="nav-link collapsed" href="#">
                        <i class="material-icons">art_track</i>
                        <p>Blogs<span class="caret pull-right"></span></p>
                    </a>
                    <div class="collapse {{ (Request::segment(2) == 'blogs' || (Request::segment(2) == 'blogs' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="blogs" >
                        <ul class="nav">
                            <li class="nav-item {{ (Request::segment(2) == 'blogs' && Request::segment(3) == '') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>My Blogs </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'feeds') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.feeds.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Blog Feeds</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(3) == 'categories' && Request::segment(2) == 'blogs') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.categories.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Blog Category </p>
                                </a>
                            </li>
                            @if(count($sidenavPages['blogs']) > 0)
                                @foreach($sidenavPages['blogs'] as $blogPage)
                                    <li class="nav-item {{ (Request::segment(4) == $blogPage->slug) ? 'active' : '' }}">
                                        <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $blogPage->slug]) : route('redirectListStore') }}">
                                            <p>{{ $blogPage->title }} </p>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </li> --}}
                @elseif($sideMenu->slug == 'articles')
                <li class="nav-item {{ (Request::segment(2) == 'articles') ? 'active' : '' }}">
                    @if(count($sidenavPages['articles']) > 0)
                        <a data-toggle="collapse" data-target="#articles" data-parent="#sidenav01" class="nav-link collapsed"  onclick="window.location.replace('{{ Session::has('subdomain') ? route('articles.list', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}')" href="#">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Articles <span class="caret pull-right"></span></p>
                        </a>
                        <div class="collapse {{ (Request::segment(2) == 'articles' || (Request::segment(2) == 'articles' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="articles" >
                        <ul class="nav">
                            @foreach($sidenavPages['articles'] as $article)
                                <li class="nav-item {{ (Request::segment(4) == $article->slug) ? 'active' : '' }}">
                                    <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('articles.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $article->slug]) : route('redirectListStore') }}">
                                        <p>{{ $article->title }} </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul
                    @else
                        <a class="nav-link" href="{{ Session::has('subdomain') ? route('articles.list', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Articles</p>
                        </a>
                    @endif
                </li>
                @elseif($sideMenu->slug == 'increase-conversions')
                <li class="nav-item {{ (Request::segment(2) == 'increase-conversions') ? 'active' : '' }}">
                    <a data-toggle="collapse" data-target="#conversions" data-parent="#sidenav01" class="nav-link collapsed" href="#">
                        <i class="material-icons">{{ $sideMenu->icon }}</i>
                        <p>Increase Conversions<span class="caret pull-right"></span></p>
                    </a>
                    <div class="collapse {{ (Request::segment(2) == 'increase-conversions' || (Request::segment(2) == 'increase-conversions' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="conversions" >
                        <ul class="nav">
                            @if(in_array('social proofs', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'social-proof') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('socialProof.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Social Proof </p>
                                </a>
                            </li>
                            @endif
                            @if(in_array('exit pops', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'exit-pops') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('exitpops.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Exit Pops </p>
                                </a>
                            </li>
                            @endif
                            @if(in_array('facebook customer messenger bot', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'facebook-customer-chat') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('conversions.customerChat', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Facebook Messenger</p>
                                </a>
                            </li>
                            @endif
                            @if(in_array('facebook comment plugin', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'facebook-comment-plugin') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('conversions.commentPlugin', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Facebook Comment Plugin</p>
                                </a>
                            </li>
                            @endif

                            <li class="nav-item {{ (Request::segment(3) == 'countdown-timers') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('countdowns.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Countdown Timers</p>
                                </a>
                            </li>

                            @if(count($sidenavPages['increase-conversions']) > 0)
                                @foreach($sidenavPages['increase-conversions'] as $conversion)
                                    <li class="nav-item {{ (Request::segment(4) == $conversion->slug) ? 'active' : '' }}">
                                        <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('conversions.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $conversion->slug]) : route('redirectListStore') }}">
                                            <p>{{ $conversion->title }} </p>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </li>
                @elseif($sideMenu->slug == 'get-traffic')
                <li class="nav-item {{ (Request::segment(2) == 'get-traffic') ? 'active' : '' }}">
                    <a data-toggle="collapse" data-target="#gettraffic" data-parent="#sidenav01" class="nav-link collapsed" href="#">
                        <i class="material-icons">{{ $sideMenu->icon }}</i>
                        <p>Traffic<span class="caret pull-right"></span></p>
                    </a>
                    <div class="collapse {{ (Request::segment(2) == 'get-traffic' || (Request::segment(2) == 'get-traffic' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="gettraffic" >
                        <ul class="nav">
                            <li class="nav-item {{ (Request::segment(3) == 'seo') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ route('seo.index', ['subdomain' => Session::get('subdomain')]) }}">
                                    <p>SEO </p>
                                </a>
                            </li>
                            
                            <li class="nav-item {{ (Request::segment(3) == 'blogs' && Request::segment(4) == '') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>My Blogs </p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(4) == 'feeds' && Request::segment(2) == 'get-traffic') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.feeds.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Blog Feeds</p>
                                </a>
                            </li>
                            <li class="nav-item {{ (Request::segment(4) == 'categories' && Request::segment(2) == 'get-traffic') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('blogs.categories.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                                    <p>Blog Category </p>
                                </a>
                            </li>

                            {{-- @if(in_array('fb group finder', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'facebook-group-finder') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ route('fbgroupfinder.index', ['subdomain' => Session::get('subdomain')]) }}">
                                    <p>FB Group Finder</p>
                                </a>
                            </li>
                            @endif --}}

                            @if(in_array('sharing to social media platforms', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'social') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ route('social.index', ['subdomain' => Session::get('subdomain')]) }}">
                                    <p>Social </p>
                                </a>
                            </li>
                            @endif
                            
                            @if(in_array('pinger service', $accessFeatures))
                            <li class="nav-item {{ (Request::segment(3) == 'pinger-service') ? 'active' : '' }}">
                                <a class="nav-link collapsed" href="{{ route('pinger.index', ['subdomain' => Session::get('subdomain')]) }}">
                                    <p>Pinger Service</p>
                                </a>
                            </li>
                            @endif

                            @if(count($sidenavPages['get-traffic']) > 0)
                                    @foreach($sidenavPages['get-traffic'] as $getTrafficPage)
                                        <li class="nav-item {{ (Request::segment(4) == $getTrafficPage->slug) ? 'active' : '' }}">
                                            <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('gettraffic.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $getTrafficPage->slug]) : route('redirectListStore') }}">
                                                <p>{{ $getTrafficPage->title }} </p>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul
                            @endif
                        </ul>
                    </div>
                </li>
                @elseif($sideMenu->slug == 'bonuses')
                <li class="nav-item {{ (Request::segment(2) == 'bonuses') ? 'active' : '' }}">
                    @if(count($sidenavPages['bonuses']) > 0)
                        <a data-toggle="collapse" data-target="#bonuses" data-parent="#sidenav01"  class="nav-link collapsed" onclick="window.location.replace('{{ Session::has('subdomain') ? route('bonus.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}')" href="#">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Bonuses <span class="caret pull-right"></span></p>
                        </a>
                        <div class="collapse {{ (Request::segment(2) == 'bonuses' || (Request::segment(2) == 'bonuses' && Request::segment(3) ==  'pages')) ? 'show' : '' }}" id="bonuses" >
                        <ul class="nav">
                            @foreach($sidenavPages['bonuses'] as $bonus)
                                <li class="nav-item {{ (Request::segment(4) ==  $bonus->slug) ? 'active' : '' }}">
                                    <a class="nav-link collapsed" href="{{ Session::has('subdomain') ? route('bonuses.custom.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $bonus->slug]) : route('redirectListStore') }}">
                                        <p>{{ $bonus->title }} </p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <a class="nav-link" href="{{ Session::has('subdomain') ? route('bonus.index', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">
                            <i class="material-icons">{{ $sideMenu->icon }}</i>
                            <p>Bonuses</p>
                        </a>
                    @endif
                </li>
                @endif
            @endforeach
            
            @if(count($sidenavNoParentPages) > 0)
                @foreach($sidenavNoParentPages as $noParentPage)
                    <li class="nav-item {{ (Request::segment(2) == $noParentPage->slug) ? 'active' : '' }}">
                        <a class="nav-link" href="{{ Session::has('subdomain') ? route('noparent.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $noParentPage->slug]) : route('redirectListStore') }}">
                            @if(isset($noParentPage->icon))
                            <i class="material-icons">{{ $noParentPage->icon }}</i>
                            @endif
                            <p>{{ $noParentPage->title }} </p>
                        </a>
                    </li>
                @endforeach
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('default.pages', ['subdomain' => Session::get('subdomain'), 'slug' => 'training']) }}">
                    <i class="material-icons">video_library</i>
                    <p>Training</p>
                </a>
            </li>
        </ul>
    </div>
</div>