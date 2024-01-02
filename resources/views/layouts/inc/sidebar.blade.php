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

        <li class="nav-item {{ Route::currentRouteName() === 'kehadiran.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kehadiran.index')}}">
                <i class="fas fa-fw fa-tag"></i>
                <span>Product</span></a>
        </li>
        {{-- <li class="nav-item {{ Route::currentRouteName() === 'users.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('users.index')}}">
                <i class="fas fa-fw fa-users"></i>
                <span>Karyawan</span></a>
        </li> --}}

        {{-- <li class="nav-item {{ Route::currentRouteName() === 'kelolagaji.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kelolagaji.index')}}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Kelola Gaji</span></a>
        </li>

        <li class="nav-item {{ Route::currentRouteName() === 'laporan.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laporan.index')}}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Laporan</span></a>
        </li> --}}
    @else
        <li class="nav-item {{ Route::currentRouteName() === 'dashboard' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard')}}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        {{-- <li class="nav-item {{ Route::currentRouteName() === 'kehadiran.index' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kehadiran.index')}}">
                <i class="fas fa-fw fa-book-open"></i>
                <span>Kehadiran</span></a>
        </li> --}}
        <li class="nav-item {{ Route::currentRouteName() === 'laporan.detail-slipgaji' ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('laporan.detail-slipgaji')}}">
                <i class="fas fa-fw fa-cogs"></i>
                <span>Laporan</span></a>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>