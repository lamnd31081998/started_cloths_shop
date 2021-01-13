<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    @yield('searchbar')

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                <img class="img-circle elevation-2" style="margin-top:-8px" width="35px" height="35px" src="{{ asset(\Illuminate\Support\Facades\Auth::user()->avatar) }}">
                {{ \Illuminate\Support\Facades\Auth::user()->name }}
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0;">
                <div class="dropdown-divider"></div>
                <div class="dropdown-item" style="text-align: center">
                    <img class="img-circle elevation-2" width="90px" height="90px" src="{{ asset(\Illuminate\Support\Facades\Auth::user()->avatar) }}">
                    <p style="margin-top: 15px;">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
                    <p>Quản lý của {{ DB::table('generals')->where('type', '=', 'contact')->first()->name }} kể từ {{ date('m/Y', strtotime(\Illuminate\Support\Facades\Auth::user()->created_at)) }}</p>
                </div>
                <div class="dropdown-divider"></div>
                <a href="{{ route('be.index.edit_self_information') }}" class="dropdown-item dropdown-footer">Thông tin cá nhân</a>
                <a href="{{ route('be.logout') }}" class="dropdown-item dropdown-footer">Đăng xuất</a>
            </div>
        </li>
    </ul>
</nav>

