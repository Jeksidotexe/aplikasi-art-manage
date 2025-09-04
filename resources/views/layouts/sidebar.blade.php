<div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header" data-background-color="dark">
        <a href="{{ url('/dashboard') }}" class="logo">
            <img src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/img/logo-usb1.png" alt="navbar brand"
                class="navbar-brand" height="45" />
        </a>
        <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
            </button>
        </div>
        <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
        </button>
    </div>
    <!-- End Logo Header -->
</div>
<div class="sidebar-wrapper scrollbar scrollbar-inner">
    <div class="sidebar-content">
        <ul class="nav nav-secondary">
            <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            @if (auth()->user()->role == 'admin')
            <li class="nav-section">
                <h4 class="text-section">DATA MASTER</h4>
            </li>
            <li class="nav-item {{ request()->routeIs('jurusan.*') ? 'active' : '' }}">
                <a href="{{ route('jurusan.index') }}">
                    <i class="fas fa-building"></i>
                    <p>Jurusan</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('prodi.*') ? 'active' : '' }}">
                <a href="{{ route('prodi.index') }}">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <p>Prodi</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('bidang.*') ? 'active' : '' }}">
                <a href="{{ route('bidang.index') }}">
                    <i class="fas fa-theater-masks"></i>
                    <p>Bidang</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('alat.*') ? 'active' : '' }}">
                <a href="{{ route('alat.index') }}">
                    <i class="fas fa-wrench"></i>
                    <p>Alat</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('anggota.*') ? 'active' : '' }}">
                <a href="{{ route('anggota.index') }}">
                    <i class="fa fa-id-card"></i>
                    <p>Anggota</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('peminjaman_alat.*') ? 'active' : '' }}">
                <a href="{{ route('peminjaman_alat.index') }}">
                    <i class="fas fa-hand-holding"></i>
                    <p>Peminjaman</p>
                </a>
            </li>
            <li class="nav-section">
                <h4 class="text-section">KEGIATAN</h4>
            </li>
            <li class="nav-item {{ request()->routeIs('agenda_kegiatan.*') ? 'active' : '' }}">
                <a href="{{ route('agenda_kegiatan.index') }}">
                    <i class="fas fa-calendar-check"></i>
                    <p>Agenda</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('kehadiran_kegiatan.*') ? 'active' : '' }}">
                <a href="{{ route('kehadiran_kegiatan.index') }}">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Kehadiran</p>
                </a>
            </li>
            <li class="nav-section">
                <h4 class="text-section">SISTEM</h4>
            </li>
            <li class="nav-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <a href="{{ route('laporan.index') }}">
                    <i class="fas fa-chart-bar"></i>
                    <p>Laporan</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('user.profil') ? 'active' : '' }}">
                <a href="{{ route('user.profil') }}">
                    <i class="fa fa-user-cog"></i>
                    <p>Profil</p>
                </a>
            </li>
            @else
            <li class="nav-item {{ request()->routeIs('agenda_kegiatan.*') ? 'active' : '' }}">
                <a href="{{ route('agenda_kegiatan.index') }}">
                    <i class="fas fa-calendar-check"></i>
                    <p>Agenda</p>
                </a>
            </li>
            <li class="nav-item {{ request()->routeIs('peminjaman_alat.*') ? 'active' : '' }}">
                <a href="{{ route('peminjaman_alat.index') }}">
                    <i class="fas fa-hand-holding"></i>
                    <p>Peminjaman Alat</p>
                </a>
            </li>
            <li class="nav-section">
                <h4 class="text-section">SISTEM</h4>
            </li>
            <li class="nav-item {{ request()->routeIs('user.profil') ? 'active' : '' }}">
                <a href="{{ route('user.profil') }}">
                    <i class="fa fa-user-cog"></i>
                    <p>Profil</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>