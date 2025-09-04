@extends('layouts.master')
@section('title')
Dashboard Admin
@endsection

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-warning bubble-shadow-small"><i
                                    class="fas fa-bell"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Permintaan Pending</p>
                                <h4 class="card-title">{{ $permintaan_pending }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small"><i
                                    class="fas fa-users"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Anggota</p>
                                <h4 class="card-title">{{ $anggota }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-success bubble-shadow-small"><i
                                    class="fas fa-wrench"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Total Stok Alat</p>
                                <h4 class="card-title">{{ $alat }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-secondary bubble-shadow-small"><i
                                    class="fas fa-hand-holding"></i></div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Alat Dipinjam</p>
                                <h4 class="card-title">{{ $peminjaman_alat_aktif }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- [BARU] KARTU PERSETUJUAN ANGGOTA BARU --}}
    @if ($list_pendaftaran_pending->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-user-clock" style="margin-right: 8px;"></i>Persetujuan
                        Anggota Baru</div>
                    <div class="card-category">Terdapat {{ $pendaftaran_pending_count }} pendaftar yang menunggu
                        persetujuan Anda.</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Pendaftar</th>
                                    <th>Email</th>
                                    <th>Waktu Daftar</th>
                                    <th style="width: 20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($list_pendaftaran_pending as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->foto ?? asset('images/default-profile.jpg') }}"
                                                alt="foto" class="avatar-sm rounded-circle me-3">
                                            <div>
                                                <div class="fw-bold">{{ $user->nama }}</div>
                                                <div class="text-muted" style="font-size: 12px;">{{ $user->nim }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span title="{{ $user->created_at->format('d M Y, H:i') }}">
                                            {{ $user->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('dashboard.approve', $user->id_users) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-xs">Setujui</button>
                                        </form>
                                        <button class="btn btn-danger btn-xs"
                                            onclick="showRejectModal({{ $user->id_users }}, '{{ $user->nama }}')">Tolak</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($pendaftaran_pending_count > 5)
                    <div class="card-action text-center">
                        <a href="{{ route('dashboard.index') }}">Lihat Semua Pendaftar &raquo;</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-bell" style="margin-right: 8px;"></i> Tinjauan Permintaan
                        Peminjaman</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">Detail Permintaan</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Waktu Pengajuan</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($list_permintaan as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ url($item->users->foto ?? '/images/default-user.png') }}"
                                                    alt="Foto" class="avatar-img rounded-circle">
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $item->users->nama ?? 'N/A' }}</div>
                                                <div class="text-muted" style="font-size: 12px;">Meminjam: {{
                                                    $item->alat->nama_alat ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->jumlah_pinjam }} unit</td>
                                    <td>
                                        <span title="{{ $item->created_at->format('d M Y, H:i') }}">
                                            {{ $item->created_at->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('peminjaman_alat.index') }}"
                                            class="btn btn-primary btn-xs">Proses Sekarang</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center p-4">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <p>Tidak ada permintaan baru saat ini.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- [BARU] MODAL UNTUK ALASAN PENOLAKAN --}}
<div class="modal fade" id="reject-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="reject-form" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="alasan_penolakan">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" rows="3" class="form-control"
                            minlength="10" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Kirim Penolakan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

<script>
    function showRejectModal(userId, userName) {
        let modal = $('#reject-modal');
        let form = $('#reject-form');
        // URL ini mengasumsikan Anda memiliki route resource untuk 'dashboard' dengan metode 'reject'
        let actionUrl = "{{ url('dashboard') }}/" + userId + "/reject";

        form.attr('action', actionUrl);
        modal.find('.modal-title').text(`Tolak Pendaftaran: ${userName}`);
        modal.find('#alasan_penolakan').val('');
        modal.modal('show');
    }
</script>