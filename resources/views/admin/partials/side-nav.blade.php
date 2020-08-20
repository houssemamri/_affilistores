<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="#" class="simple-text logo-normal ">
            <img src="{!! asset('img/uploads/' . $site['logo']) !!}" alt="Instant Funnel Labs" class="img-fluid">
        </a>
    </div>
    <div class="sidebar-wrapper" id="style-1">
        <ul class="nav main-ul">
            <li class="nav-item {{ (Request::segment(2) == '') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'members' && Request::segment(3) == '') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.index') }}">
                    <i class="material-icons">account_circle</i>
                    <p>Members</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'stores' && Request::segment(3) == '') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.store.index') }}">
                    <i class="material-icons">store</i>
                    <p>Stores</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(3) == 'ipn') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('members.ipn') }}">
                    <i class="material-icons">account_balance_wallet</i>
                    <p>IPN Requests</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'memberships') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('memberships.index') }}">
                    <i class="material-icons">credit_card</i>
                    <p>Memberships</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'pages') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pages.index') }}">
                    <i class="material-icons">description</i>
                    <p>Pages</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'articles') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('articles.index') }}">
                    <i class="material-icons">library_books</i>
                    <p>Articles</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'bonuses') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('bonuses.index') }}">
                    <i class="material-icons">card_giftcard</i>
                    <p>Bonuses</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'polls') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('polls.index') }}">
                    <i class="material-icons">poll</i>
                    <p>Polls</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'instructions') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('instructions.index') }}">
                    <i class="material-icons">list</i>
                    <p>Instructions</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'import') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('import.index') }}">
                    <i class="material-icons">cloud_upload</i>
                    <p>Import Products</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'settings') ? 'active' : '' }}">
                <a data-toggle="collapse" data-target="#settings" data-parent="#sidenav01" class="nav-link {{ (Request::segment(2) == 'settings') ? 'collapsed' : '' }}" href="#">
                    <i class="material-icons">settings</i>
                    <p>Settings <span class="caret pull-right"></span></p>
                </a>
                <div class="collapse {{ (Request::segment(2) == 'settings') ? 'show' : '' }}" id="settings" >
                    <ul class="nav">
                        <li class="nav-item {{ (Request::segment(2) == 'settings' && Request::segment(3) == '') ? 'active' : '' }}">
                            <a class="nav-link collapsed" href="{{ route('settings.general') }}">
                                <p>General </p>
                            </a>
                        </li>
                        <li class="nav-item {{ (Request::segment(3) == 'menu') ? 'active' : '' }}">
                            <a class="nav-link collapsed" href="{{ route('settings.menu') }}">
                                <p>Member Menu </p>
                            </a>
                        </li>
                        <li class="nav-item {{ (Request::segment(3) == 'notification') ? 'active' : '' }}">
                            <a class="nav-link collapsed" href="{{ route('settings.notification.index') }}">
                                <p>Member Notifications </p>
                            </a>
                        </li>
                        <li class="nav-item {{ (Request::segment(3) == 'email-responder') ? 'active' : '' }}">
                            <a class="nav-link collapsed" href="{{ route('settings.emailResponder') }}">
                                <p>Email Responder </p>
                            </a>
                        </li>
                        <li class="nav-item {{ (Request::segment(3) == 'youtube-api-keys') ? 'active' : '' }}">
                            <a class="nav-link collapsed" href="{{ route('settings.youtubekeys') }}">
                                <p>YouTube API Keys </p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item d-xl-none d-md-block">
                <a data-toggle="collapse" data-target="#account" data-parent="#sidenav01" class="nav-link " href="#">
                    <i class="material-icons">account_box</i>
                    <p>Account <span class="caret pull-right"></span></p>
                </a>
                <div class="collapse" id="account">
                    <ul class="nav">
                        <li class="nav-item ">
                            <a class="nav-link collapsed" href="{{ route('admin.profile') }}">
                                <p>Profile </p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link collapsed" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> 
                                <p>Logout </p> 
                            </a>
                        </li>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>                        
                       
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>