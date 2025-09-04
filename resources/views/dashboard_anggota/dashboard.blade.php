@extends('layouts.master')
@section('title')
Dashboard Anggota
@endsection

@push('css')
<style>
    .icon-circle {
        height: 2.5rem;
        width: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .alert-card {
        border-left: 5px solid #dc3545;
    }
</style>
@endpush

@section('container')
<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Selamat Datang, {{ Auth::user()->nama }}!</h3>
        </div>
    </div>

    {{-- Notifikasi Peminjaman Terlambat --}}
    @if($peminjaman_terlambat->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card modern-card alert-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-circle bg-danger text-white me-3">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold text-danger">Perhatian: Peminjaman Terlambat</h5>
                                    <p class="card-text mb-0">Terdapat alat yang telah melewati batas waktu
                                        pengembalian. Mohon untuk segera mengembalikannya.</p>
                                </div>
                                <button
                                    onclick="markAsRead('terlambat-{{ $peminjaman_terlambat->first()->id_peminjaman }}', this)"
                                    class="btn btn-outline-secondary btn-sm align-self-start">
                                    Tandai telah dibaca
                                </button>
                            </div>
                            <ul class="list-unstyled mb-0 mt-2">
                                @foreach($peminjaman_terlambat as $item)
                                <li>
                                    <i class="fas fa-wrench text-muted me-2"></i>
                                    <strong>{{ $item->alat->nama_alat ?? 'N/A' }}</strong> - Jatuh tempo pada
                                    <strong>{{ \Carbon\Carbon::parse($item->tanggal_harus_kembali)->translatedFormat('d
                                        F Y') }}</strong>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Notifikasi Persetujuan/Penolakan --}}
    @if($notifikasi_status->isNotEmpty())
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fas fa-inbox" style="margin-right: 8px;"></i> Notifikasi Untuk
                        Anda</div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush" id="notification-list">
                        @foreach($notifikasi_status as $notif)
                        <li class="list-group-item notif-item" data-notif-id="status-{{ $notif->id_peminjaman }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    @if($notif->status == 'disetujui')
                                    <div class="icon-circle bg-success text-white me-3"><i
                                            class="fas fa-check-circle"></i></div>
                                    <div>
                                        <div class="fw-bold">Pengajuan Disetujui</div>
                                        <p class="mb-1">Peminjaman <strong>{{ $notif->alat->nama_alat ?? '' }} ({{
                                                $notif->jumlah_pinjam }} unit)</strong> dikonfirmasi. <span
                                                class="text-primary">{{ $notif->keterangan_admin }}</span></p>
                                    </div>
                                    @else
                                    <div class="icon-circle bg-danger text-white me-3"><i
                                            class="fas fa-times-circle"></i></div>
                                    <div>
                                        <div class="fw-bold">Pengajuan Ditolak</div>
                                        <p class="mb-1">Peminjaman <strong>{{ $notif->alat->nama_alat ?? '' }}</strong>
                                            tidak dapat diproses. Alasan: "{{ $notif->keterangan_admin }}"</p>
                                    </div>
                                    @endif
                                </div>
                                {{-- PERUBAHAN: Tombol diubah menjadi teks --}}
                                <button onclick="markAsRead('status-{{ $notif->id_peminjaman }}', this)"
                                    class="btn btn-outline-secondary btn-sm">
                                    Tandai telah dibaca
                                </button>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    <div id="no-notification-message" class="text-center p-4" style="display: none;">
                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                        <p>Tidak ada pemberitahuan baru.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Riwayat Peminjaman Alat Anda</h4>
                        <a href="{{ route('peminjaman_alat.index') }}" class="btn btn-sm btn-primary">
                            <i class="fa fa-plus"></i> Ajukan Peminjaman Baru
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Alat</th>
                                    <th>Jumlah</th>
                                    <th>Tgl. Pinjam</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjaman_saya as $item)
                                <tr>
                                    <td>{{ $item->alat->nama_alat ?? 'N/A' }}</td>
                                    <td>{{ $item->jumlah_pinjam }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}
                                    </td>
                                    <td>
                                        @php
                                        $badgeClass = match (strtolower($item->status)) {
                                        'diajukan' => 'badge badge-info',
                                        'disetujui' => 'badge badge-primary',
                                        'dipinjam' => 'badge badge-warning',
                                        'dikembalikan' => 'badge badge-success',
                                        'ditolak' => 'badge badge-danger',
                                        default => 'badge bg-black'
                                        };
                                        @endphp
                                        <span class="{{ $badgeClass }}">{{ ucfirst($item->status) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Anda belum pernah melakukan peminjaman.</td>
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
@endsection

@push('scripts')
{{-- JavaScript Anda tidak perlu diubah sama sekali --}}
<script>
    function markAsRead(notificationId, element) {
    let readNotifications = JSON.parse(localStorage.getItem('readNotifications')) || [];
    if (!readNotifications.includes(notificationId)) {
        readNotifications.push(notificationId);
    }
    localStorage.setItem('readNotifications', JSON.stringify(readNotifications));

    // Untuk notifikasi biasa
    $(element).closest('.notif-item').fadeOut(300, function() {
        $(this).remove();
        checkIfEmpty();
    });

    // Untuk notifikasi keterlambatan (yang berada di luar list)
    $(element).closest('.alert-card').fadeOut(300);
}

function checkIfEmpty() {
    if ($('#notification-list .notif-item').length === 0) {
        $('#no-notification-message').show();
    }
}

$(document).ready(function() {
    let readNotifications = JSON.parse(localStorage.getItem('readNotifications')) || [];

    // Sembunyikan notifikasi yang sudah dibaca
    readNotifications.forEach(function(notifId) {
        $(`.notif-item[data-notif-id="${notifId}"]`).remove();
        if (notifId.startsWith('terlambat')) {
            $('.alert-card').remove();
        }
    });

    checkIfEmpty();
});
</script>
@endpush