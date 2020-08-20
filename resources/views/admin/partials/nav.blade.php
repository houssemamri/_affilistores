<!-- Navbar -->
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
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">account_box</i>
                    </a>
                    <div class="dropdown-menu dropdown-left" aria-labelledby="accountDropdown">
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Logout </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->