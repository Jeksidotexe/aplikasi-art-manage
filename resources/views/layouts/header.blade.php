<div class="main-header">
    <div class="main-header-logo">
        <div class="logo-header" data-background-color="dark2">
            <a href="{{ route('dashboard') }}" class="logo">
                <img src="{{ asset('kaiadmin-lite-1.2.0') }}/assets/img/kaiadmin/logo_light.svg" alt="navbar brand"
                    class="navbar-brand" height="20" />
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
    </div>
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
        data-background-color="white">
        <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li class="nav-item topbar-icon dropdown hidden-caret">
                    <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button"
                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bell"></i>

                        {{-- [PERUBAHAN] Tampilkan badge hanya jika ada notifikasi --}}
                        @if(isset($total_notif) && $total_notif > 0)
                        <span class="notification">{{ $total_notif }}</span>
                        @endif

                    </a>
                    <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                        <li>
                            <div class="dropdown-title">
                                {{-- [PERUBAHAN] Judul dinamis --}}
                                @if(isset($total_notif) && $total_notif > 0)
                                Anda memiliki {{ $total_notif }} notifikasi baru
                                @else
                                Tidak ada notifikasi terbaru
                                @endif
                            </div>
                        </li>
                        <li>
                            <div class="notif-scroll scrollbar-outer">
                                <div class="notif-center">

                                    {{-- [PERUBAHAN] Loop untuk menampilkan notifikasi pendaftaran --}}
                                    @isset($list_pendaftaran_pending)
                                    @foreach ($list_pendaftaran_pending as $notif)
                                    <a href="{{ route('dashboard') }}"> {{-- Arahkan ke dashboard atau halaman anggota
                                        --}}
                                        <div class="notif-icon notif-primary">
                                            <i class="fa fa-user-plus"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block"> Pendaftar baru: {{ Str::limit($notif->nama, 20) }}
                                            </span>
                                            <span class="time">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                    @endforeach
                                    @endisset

                                    {{-- [PERUBAHAN] Loop untuk menampilkan notifikasi peminjaman --}}
                                    @isset($list_peminjaman_pending)
                                    @foreach ($list_peminjaman_pending as $notif)
                                    <a href="{{ route('peminjaman_alat.index') }}">
                                        <div class="notif-icon notif-success">
                                            <i class="fas fa-hand-holding"></i>
                                        </div>
                                        <div class="notif-content">
                                            <span class="block"> Pinjam alat: {{ Str::limit($notif->users->nama, 20) }}
                                            </span>
                                            <span class="time">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                    @endforeach
                                    @endisset

                                    {{-- [PERUBAHAN] Pesan jika tidak ada notifikasi sama sekali --}}
                                    @if(empty($total_notif) || $total_notif == 0)
                                    <div class="text-center p-3">
                                        <p class="text-muted mb-0">Semua notifikasi telah dilihat!</p>
                                    </div>
                                    @endif

                                </div>
                            </div>
                        </li>
                        {{-- <li>
                            <a class="see-all" href="javascript:void(0);">Lihat semua notifikasi<i
                                    class="fa fa-angle-right"></i>
                            </a>
                        </li> --}}
                    </ul>
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                    <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                        <div class="avatar-sm avatar avatar-online">
                            <img src="{{ url(auth()->user()->foto ?? asset('images/default-user.png')) }}"
                                alt="Profile Image" class="avatar-img rounded-circle" />
                        </div>
                        <span class="profile-username">
                            <span class="op-7">Hi,</span>
                            <span class="fw-bold">{{ auth()->user()->nama }}</span>
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated fadeIn">
                        <div class="dropdown-user-scroll scrollbar-outer">
                            <li>
                                <div class="user-box">
                                    <div class="avatar-lg avatar avatar-online">
                                        <img src="{{ url(auth()->user()->foto ?? asset('images/default-user.png')) }}"
                                            alt="Profile Image" class="avatar-img rounded" />
                                    </div>
                                    <div class="u-text">
                                        <h4>{{ auth()->user()->nama }}</h4>
                                        <p class="text-muted">{{ auth()->user()->email }}</p>
                                        <a href="{{ route('user.profil') }}"
                                            class="btn btn-xs btn-secondary btn-sm">Edit Profil</a>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="icon-logout"></i> Keluar</a>
                            </li>
                        </div>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</div>

<form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
    @csrf
</form>