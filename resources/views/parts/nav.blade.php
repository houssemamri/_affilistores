<!-- Navbar -->

<!-- End Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent navbar-fixed fixed-top">
    <div class="container-fluid nav-header">
        <div class="navbar-wrapper">
            <a class="navbar-brand nav-logo" href="#"> <img src="{!! asset('img/uploads/'. $site['logo']) !!}" alt="" class="img-fluid"> </a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <ul class="navbar-nav">
                @foreach($topNavPages as $topNavPage)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('default.pages', ['subdomain' => Session::get('subdomain'), 'slug' => $topNavPage->slug]) }}">
                        <span class="d-none d-lg-block d-xl-block"> {{ $topNavPage->title }}</span>
                        <p class="d-lg-none d-md-block">
                            <span class="d-lg-none d-md-block">{{ $topNavPage->title }}</span>
                        </p>
                    </a>
                </li>
                @endforeach
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('listStore') }}">
                        <span class="d-none d-lg-block d-xl-block">Manage Stores</span>
                        <p class="d-lg-none d-md-block">
                            <span class="d-lg-none d-md-block">Manage Stores</span>
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="productsDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">settings</i> 
                    </a>
                    <div class="dropdown-menu dropdown-left" aria-labelledby="productsDropdown">
                        
                        <!-- <a class="dropdown-item" href="{{ route('teamManagement') }}">Team Management</a> -->
                    </div>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link" href="http://aiddesk.com">
                        <i class="material-icons">help_outline</i>
                        <p>
                            <span class="d-lg-none d-md-block">Help</span>
                        </p>
                    </a>
                </li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">account_box</i>
                    </a>
                    <div class="dropdown-menu dropdown-left" aria-labelledby="accountDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}">Profile</a>
                        <a class="dropdown-item" href="{{ route('index.notifications') }}">Notifications {!! ($newNotifications > 0 && Request::segment(1) !== 'notifications')  ? "<span class='badge badge-danger'> $newNotifications </span>" : "" !!}</a>
                        <a class="dropdown-item" href="{{ route('memberships', ['subdomain' => Session::get('subdomain')]) }}">Membership</a>
                        <div class="dropdown-divider"></div>
                        {{-- <a class="dropdown-item" href="{{ route('settings.businessProfile', ['subdomain' => Session::get('subdomain')]) }}">Business Profile</a> --}}
                        <a class="dropdown-item" href="{{ route('settings.contactMessages', ['subdomain' => Session::get('subdomain')]) }}">Contact Messages</a>
                        <a class="dropdown-item" href="{{ route('newsletters.index', ['subdomain' => Session::get('subdomain')]) }}">Manage Newsletters</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ Session::has('subdomain') ? route('affiliate', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">Affiliate Settings</a>
                        <a class="dropdown-item" href="{{ Session::has('subdomain') ? route('social', ['subdomain' => Session::get('subdomain')]) : route('redirectListStore') }}">Social Settings</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                </li>

                <li class="nav-item dropdown">
                </li>
            </ul>
            <form class="navbar-form">
            </form>
        </div>
    </div>
</nav>