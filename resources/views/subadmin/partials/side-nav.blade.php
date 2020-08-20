<div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
    <div class="logo">
        <a href="#" class="simple-text logo-normal ">
            <img src="{!! asset('img/uploads/' . $site['logo']) !!}" alt="Instant Funnel Labs" class="img-fluid">
        </a>
    </div>
    <div class="sidebar-wrapper" id="style-1">
        <ul class="nav main-ul">
            <li class="nav-item {{ (Request::segment(2) == '') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('subadmin.dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item {{ (Request::segment(2) == 'members' && Request::segment(3) == '') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('subadmin.members.index') }}">
                    <i class="material-icons">account_circle</i>
                    <p>Members</p>
                </a>
            </li>
            <li class="nav-item d-xl-none d-md-block">
                <a data-toggle="collapse" data-target="#account" data-parent="#sidenav01" class="nav-link " href="#">
                    <i class="material-icons">account_box</i>
                    <p>Account <span class="caret pull-right"></span></p>
                </a>
                <div class="collapse" id="account">
                    <ul class="nav">
                        <li class="nav-item ">
                            <a class="nav-link collapsed" href="{{ route('subadmin.profile') }}">
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