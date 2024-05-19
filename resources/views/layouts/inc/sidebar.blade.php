<ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    {{-- <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html"> --}}
        {{-- <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div> --}}
        {{-- <div class="sidebar-brand-text mx-3">Sistem Informasi Penggajian<sup>Notaris Indah Khairunisa</sup></div> --}}
        {{-- <div class="sidebar-brand-text mx-3">Sistem Informasi Penggajian</div> --}}
    {{-- </a> --}}

    <hr class="sidebar-divider">

    @php
        $privilage = Auth()->user();
    @endphp
    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    
    @if ($privilage->role === 'admin')
        <li class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() === 'products.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('products.index')}}">
                <i class="fas fa-fw fa-tag"></i>
                <span>Barang</span></a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() === 'laporan.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laporan.index')}}">
                <i class="fas fa-fw fa-file"></i>
                <span>Laporan</span></a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() === 'user-profile.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user-profile.index')}}">
                <i class="fas fa-fw fa-user"></i>
                <span>User Profile</span></a>
        </li>
    @else
        <li class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

         <li class="nav-item {{ Route::currentRouteName() === 'products.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('products.index')}}">
                <i class="fas fa-fw fa-tag"></i>
                <span>Barang</span></a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() === 'transaksi.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('transaksi.index')}}">
                <i class="fas fa-fw fa-tag"></i>
                <span>Transaksi</span></a>
        </li>
        <li class="nav-item {{ Route::currentRouteName() === 'user-profile.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user-profile.index')}}">
                <i class="fas fa-fw fa-user"></i>
                <span>User Profile</span></a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>