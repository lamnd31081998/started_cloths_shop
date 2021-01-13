<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('be.index') }}" class="brand-link">
        <img src="{{ asset('images/backend_logo.png') }}" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">aStar</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset(\Illuminate\Support\Facades\Auth::user()->avatar) }}" class="img-circle elevation-2">
            </div>
            <div class="info">
                <a href="{{ route('be.index.edit_self_information') }}" class="d-block">{{ \Illuminate\Support\Facades\Auth::user()->name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <?php
                    $menus = config('menu');
                ?>

                @foreach($menus as $menu)
                    <?php $class = !empty($menu['items']) ? 'has-treeview' : '' ?>
                    <li class="nav-item {{ \Illuminate\Support\Str::slug($menu['name'], '-') }} {{ $class }}">
                        <a href="{{ ($menu['route'] == '#') ? '#' : route($menu['route']) }}" class="nav-link" id="{{ \Illuminate\Support\Str::slug($menu['name'], '-') }}">
                            <i class="nav-icon fas {{ $menu['icon'] }}"></i>
                            <p>{{ $menu['name'] }}</p>
                            @if (!empty($menu['items']))
                                <i class="right fas fa-angle-left"></i>
                            @endif
                        </a>
                        @if (!empty($menu['items']))
                            <ul class="nav nav-treeview">
                                @foreach ($menu['items'] as $item)
                                <li class="nav-item">
                                    <a href="{{ ($item['route'] == '#') ? '#' : route($item['route']) }}" class="nav-link" id="{{ \Illuminate\Support\Str::slug($item['name'], '-') }}">
                                        <i class="nav-icon {{ $item['icon'] }}"></i>
                                        <p>{{ $item['name'] }}</p>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
