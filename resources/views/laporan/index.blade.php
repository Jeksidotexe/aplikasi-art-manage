@extends('layouts.master')

@section('title')
Laporan Peminjaman Alat
@endsection

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
{{-- CSS Kustom untuk Tampilan Modern --}}
<style>
    :root {
        --primary-gradient: linear-gradient(45deg, #0d6efd, #0d95fd);
        --success-gradient: linear-gradient(45deg, #198754, #1f9d63);
        --secondary-gradient: linear-gradient(45deg, #6c757d, #868e96);
        --text-light: #f8f9fa;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --card-border-radius: 0.75rem;
    }

    .page-inner {
        background-color: #f4f6f9;
    }

    .modern-card {
        border: none;
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        transition: transform 0.2s ease-in-out;
        margin-bottom: 1.5rem;
    }

    .modern-card:hover {
        transform: translateY(-5px);
    }

    .stat-card {
        color: var(--text-light);
        position: relative;
        overflow: hidden;
    }

    .stat-card .card-body {
        position: relative;
        z-index: 2;
    }

    .stat-card .stat-icon {
        position: absolute;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 4rem;
        opacity: 0.2;
        z-index: 1;
    }

    .stat-card .stat-title {
        font-size: 1rem;
        font-weight: 500;
    }

    .stat-card .stat-value {
        font-size: 2.25rem;
        font-weight: 700;
    }

    .bg-gradient-secondary {
        background: var(--secondary-gradient);
    }

    .bg-gradient-primary {
        background: var(--primary-gradient);
    }

    .bg-gradient-success {
        background: var(--success-gradient);
    }

    .bidang-card {
        background-color: #fff;
        padding: 1.5rem;
        text-align: center;
    }

    .bidang-card .bidang-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        color: #0d6efd;
    }

    .bidang-card .bidang-name {
        font-weight: 600;
    }

    .bidang-card .bidang-count {
        font-size: 1.5rem;
        font-weight: 700;
        color: #343a40;
    }

    .ranking-table {
        width: 100%;
    }

    .ranking-table td,
    .ranking-table th {
        padding: 1rem 0.5rem;
        border-bottom: 1px solid #dee2e6;
        vertical-align: middle;
    }

    .ranking-table tr:last-child td {
        border-bottom: none;
    }

    .ranking-table .item-name {
        font-weight: 500;
    }

    .ranking-table .item-count {
        font-size: 0.9rem;
        font-weight: 700;
        text-align: center;
        color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50rem;
        padding: 0.25rem 0.75rem;
        min-width: 40px;
        display: inline-block;
    }

    .ranking-table .item-count.success {
        color: #198754;
        background-color: rgba(25, 135, 84, 0.1);
    }

    .ranking-table .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-weight: 600;
        color: #495057;
        object-fit: cover;
    }
</style>
@endpush

@section('container')
<div class="page-inner">
    {{-- HEADER HALAMAN --}}
    <div class="d-flex align-items-center justify-content-between pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-1">Laporan Peminjaman</h3>
            <span class="text-muted">Analisis data peminjaman inventaris.</span>
        </div>
        <div class="ms-md-auto">
            <ul class="breadcrumbs">
                <li class="nav-home"><a href="{{ url('/dashboard') }}"><i class="icon-home"></i></a></li>
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">Laporan</li>
            </ul>
        </div>
    </div>

    <div class="row">
        {{-- FORM FILTER --}}
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-body p-2">
                    {{-- Menggunakan d-flex untuk membuat semua elemen inline dan justify-content-end untuk
                    menempatkannya di kanan --}}
                    <form action="{{ route('laporan.index') }}" method="GET"
                        class="d-flex justify-content-end align-items-center gap-2">

                        <span class="text-muted fw-semibold me-2">Periode:</span>

                        {{-- Input Tanggal Awal yang lebih kecil --}}
                        <input type="text" name="tanggal_awal" class="form-control form-control-sm datepicker"
                            placeholder="Tanggal Awal" value="{{ $tanggalAwal }}" style="width: 150px;">

                        <span class="text-muted">-</span>

                        {{-- Input Tanggal Akhir yang lebih kecil --}}
                        <input type="text" name="tanggal_akhir" class="form-control form-control-sm datepicker"
                            placeholder="Tanggal Akhir" value="{{ $tanggalAkhir }}" style="width: 150px;">

                        {{-- Tombol Aksi yang digabung dan diperkecil --}}
                        <div class="btn-group gap-1">
                            <button type="submit" class="btn btn-primary btn-sm" title="Terapkan Filter">
                                <i class="fa fa-check"></i>
                            </button>
                            <a href="{{ route('laporan.export_pdf', ['tanggal_awal' => $tanggalAwal, 'tanggal_akhir' => $tanggalAkhir]) }}"
                                class="btn btn-outline-secondary btn-sm" target="_blank" title="Cetak PDF">
                                <i class="fa fa-print"></i>
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- KARTU STATISTIK UTAMA --}}
        <div class="col-md-4">
            <div class="card modern-card stat-card bg-gradient-secondary">
                <div class="card-body">
                    <h5 class="stat-title">Total Alat</h5>
                    <h2 class="stat-value">{{ $grandTotalAlat ?? 0 }}</h2><span class="text-white-50">Unit
                        Terdaftar</span>
                </div><i class="fa fa-archive stat-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card modern-card stat-card bg-gradient-primary">
                <div class="card-body">
                    <h5 class="stat-title">Total Peminjaman</h5>
                    <h2 class="stat-value">{{ $totalPeminjaman ?? 0 }}</h2><span class="text-white-50">Unit
                        Dipinjam</span>
                </div><i class="fa fa-arrow-up stat-icon"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card modern-card stat-card bg-gradient-success">
                <div class="card-body">
                    <h5 class="stat-title">Total Pengembalian</h5>
                    <h2 class="stat-value">{{ $totalPengembalian ?? 0 }}</h2><span class="text-white-50">Unit
                        Kembali</span>
                </div><i class="fa fa-arrow-down stat-icon"></i>
            </div>
        </div>

        {{-- KARTU TOTAL ALAT PER BIDANG --}}
        <div class="col-12">
            <div class="card modern-card">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title fw-bold">Jumlah Alat per Bidang</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse ($totalAlatPerBidang as $bidang)
                        <div class="col-md-4">
                            <div class="card modern-card bidang-card">
                                <i class="fa fa-layer-group bidang-icon"></i>
                                <h6 class="bidang-name">{{ $bidang->nama_bidang }}</h6>
                                <p class="bidang-count mb-0">{{ $bidang->alat_sum_jumlah ?? 0 }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted">Data bidang tidak ditemukan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR PERINGKAT --}}
        <div class="col-md-6">
            <div class="card modern-card">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title fw-bold">Alat Terpopuler</h5>
                </div>
                <div class="card-body pt-0">
                    <table class="ranking-table">
                        <tbody>
                            @forelse ($alatSeringDipinjam as $alat)
                            <tr>
                                <td><span class="item-name">{{ $alat->nama_alat }}</span></td>
                                <td class="text-end"><span class="item-count">{{ $alat->total_dipinjam }}x</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-5">Tidak ada data peminjaman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card modern-card">
                <div class="card-header bg-transparent border-0 pt-3">
                    <h5 class="card-title fw-bold">Anggota Teraktif</h5>
                </div>
                <div class="card-body pt-0">
                    <table class="ranking-table">
                        <tbody>
                            @forelse ($anggotaSeringMeminjam as $anggota)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($anggota->foto)
                                        <img src="{{ asset($anggota->foto) }}" alt="Foto" class="avatar">
                                        @else
                                        <div class="avatar">{{ substr($anggota->nama, 0, 1) }}</div>
                                        @endif
                                        <span class="item-name">{{ $anggota->nama }}</span>
                                    </div>
                                </td>
                                <td class="text-end"><span class="item-count success">{{ $anggota->jumlah_peminjaman
                                        }}x</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-5">Tidak ada anggota yang meminjam.
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d M Y", // Format lebih mudah dibaca: 01 Jul 2025
        allowInput: true,
    });
</script>
@endpush
