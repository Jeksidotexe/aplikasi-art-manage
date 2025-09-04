<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman_alat; // Sesuaikan nama model jika berbeda
use App\Models\Bidang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan dengan data yang sudah diolah.
     */
    public function index(Request $request)
    {
        // Tetapkan rentang tanggal default: awal bulan ini hingga hari ini
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());

        // 1. Data Total Alat (statis, tidak terpengaruh tanggal)
        $totalAlatPerBidang = Bidang::withSum('alat', 'jumlah')->get();
        $grandTotalAlat = $totalAlatPerBidang->sum('alat_sum_jumlah');

        // 2. Query berdasarkan rentang tanggal yang dipilih
        $totalPeminjaman = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])->sum('jumlah_pinjam');
        $totalPengembalian = Peminjaman_alat::whereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir])->sum('jumlah_pinjam');

        // 3. Data Alat Paling Sering Dipinjam dalam rentang tanggal
        $alatSeringDipinjam = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->join('alat', 'peminjaman_alat.id_alat', '=', 'alat.id_alat')
            ->select('alat.nama_alat', DB::raw('SUM(peminjaman_alat.jumlah_pinjam) as total_dipinjam'))
            ->groupBy('alat.nama_alat')
            ->orderByDesc('total_dipinjam')
            ->limit(10)
            ->get();

        // 4. Data Anggota Paling Aktif dalam rentang tanggal
        $anggotaSeringMeminjam = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->join('users', 'peminjaman_alat.id_users', '=', 'users.id_users')
            ->select('users.nama', 'users.foto', DB::raw('COUNT(peminjaman_alat.id_users) as jumlah_peminjaman'))
            ->groupBy('users.id_users', 'users.nama', 'users.foto')
            ->orderByDesc('jumlah_peminjaman')
            ->limit(10)
            ->get();

        // Kirim semua data yang dibutuhkan ke view
        return view('laporan.index', compact(
            'totalAlatPerBidang',
            'grandTotalAlat',
            'alatSeringDipinjam',
            'anggotaSeringMeminjam',
            'tanggalAwal',
            'tanggalAkhir',
            'totalPeminjaman',
            'totalPengembalian'
        ));
    }

    /**
     * Membuat dan mengekspor laporan dalam format PDF.
     */
    public function exportPDF(Request $request)
    {
        // Ambil tanggal dari request, gunakan default jika tidak ada
        $tanggalAwal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());

        // Logika pengambilan data sama persis dengan method index()
        $totalAlatPerBidang = Bidang::withSum('alat', 'jumlah')->get();
        $grandTotalAlat = $totalAlatPerBidang->sum('alat_sum_jumlah');
        $totalPeminjaman = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])->sum('jumlah_pinjam');
        $totalPengembalian = Peminjaman_alat::whereBetween('tanggal_kembali', [$tanggalAwal, $tanggalAkhir])->sum('jumlah_pinjam');

        $alatSeringDipinjam = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->join('alat', 'peminjaman_alat.id_alat', '=', 'alat.id_alat')
            ->select('alat.nama_alat', DB::raw('SUM(peminjaman_alat.jumlah_pinjam) as total_dipinjam'))
            ->groupBy('alat.nama_alat')->orderByDesc('total_dipinjam')->limit(10)->get();

        $anggotaSeringMeminjam = Peminjaman_alat::whereBetween('tanggal_pinjam', [$tanggalAwal, $tanggalAkhir])
            ->join('users', 'peminjaman_alat.id_users', '=', 'users.id_users')
            ->select('users.nama', 'users.foto', DB::raw('COUNT(peminjaman_alat.id_users) as jumlah_peminjaman'))
            ->groupBy('users.id_users', 'users.nama', 'users.foto')->orderByDesc('jumlah_peminjaman')->limit(10)->get();

        $data = compact(
            'totalAlatPerBidang',
            'grandTotalAlat',
            'alatSeringDipinjam',
            'anggotaSeringMeminjam',
            'tanggalAwal',
            'tanggalAkhir',
            'totalPeminjaman',
            'totalPengembalian'
        );

        $pdf = Pdf::loadView('laporan.pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Peminjaman-Alat-' . $tanggalAwal . '-' . $tanggalAkhir . '.pdf');
    }
}
