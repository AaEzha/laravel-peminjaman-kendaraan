<nav class="navbar navbar-expand-lg main-navbar">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
    </form>
    <ul class="navbar-nav navbar-right">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">{{session('user')}}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{ asset('/logout') }}" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ asset('_admin') }}">PT PLN UP3 Kotabumi</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ asset('_admin') }}">PLN</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ Route::currentRouteNamed('dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ asset('_admin') }}"><i class="fa fa-fire"></i> <span>Dashboard</span></a></li>
            <li class="menu-header">Peminjaman Kendaraan</li>
            <li class="{{ Route::currentRouteNamed('data-peminjaman') ? 'active' : '' }}"><a class="nav-link" href="{{ asset('data-peminjaman') }}"><i class="fa fa-exchange-alt"></i> <span>Data Peminjaman</span></a></li>
            <li class="{{ Route::currentRouteNamed('data-history') ? 'active' : '' }}"><a class="nav-link" href="{{ asset('data-history') }}"><i class="fa fa-history"></i> <span>Riwayat Peminjaman</span></a></li>
            <li class="menu-header">Master Data</li>
            <li class="{{ Route::currentRouteNamed('data-pegawai') ? 'active' : '' }}"><a class="nav-link" href="{{ asset('data-pegawai') }}"><i class="fa fa-users"></i> <span>Data  Pegawai</span></a></li>
            <li class="{{ Route::currentRouteNamed('data-kendaraan') ? 'active' : '' }}"><a class="nav-link" href="{{ asset('data-kendaraan') }}"><i class="fa fa-car"></i> <span>Data  Kendaraan</span></a></li>
        </ul>
    </aside>
</div>
