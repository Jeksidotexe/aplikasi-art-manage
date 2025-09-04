<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\User;
use App\Models\Bidang;
use App\Mail\MemberApproved;
use App\Mail\MemberRejected;
use Illuminate\Http\Request;
use App\Models\Peminjaman_alat;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        // --- DASHBOARD ADMIN ---
        if (Auth::user()->role == 'admin') {
            $anggota = User::where('role', 'anggota')->count();
            $bidang = Bidang::count();
            $alat = Alat::sum('jumlah'); // Menampilkan total stok alat yang tersedia
            $peminjaman_alat_aktif = Peminjaman_alat::where('status', 'dipinjam')->count();

            // Data untuk notifikasi admin
            $permintaan_pending = Peminjaman_alat::where('status', 'diajukan')->count();
            $list_permintaan = Peminjaman_alat::with('users', 'alat')
                ->where('status', 'diajukan')
                ->latest()
                ->take(5)
                ->get();

            // [PERUBAHAN] Mengambil data pendaftar baru yang pending
            $pendaftaran_pending_count = User::where('role', 'anggota')
                ->where('status', 'pending')
                ->whereNotNull('email_verified_at')
                ->count();

            $list_pendaftaran_pending = User::where('role', 'anggota')
                ->where('status', 'pending')
                ->whereNotNull('email_verified_at')
                ->latest()
                ->take(5) // Ambil 5 pendaftar terbaru untuk ditampilkan di dashboard
                ->get();

            return view('dashboard_admin.dashboard', compact(
                'anggota',
                'bidang',
                'alat',
                'peminjaman_alat_aktif',
                'pendaftaran_pending_count',
                'list_pendaftaran_pending',
                'permintaan_pending',
                'list_permintaan'
            ));
        }
        // --- DASHBOARD ANGGOTA ---
        else {

            // [LOGIKA BARU] Ambil semua notifikasi status (disetujui/ditolak)
            $notifikasi_status = Peminjaman_alat::with('alat')
                ->where('id_users', Auth::id())
                ->whereIn('status', ['disetujui', 'ditolak'])
                ->latest('updated_at')
                ->get();

            // [LOGIKA BARU] Cek peminjaman yang terlambat
            $peminjaman_terlambat = Peminjaman_alat::with('alat')
                ->where('id_users', Auth::id())
                ->where('status', 'dipinjam')
                ->where('tanggal_harus_kembali', '<', now())
                ->get();

            // Seluruh riwayat peminjaman anggota
            $peminjaman_saya = Peminjaman_alat::with('alat')
                ->where('id_users', Auth::id())
                ->latest()
                ->get();

            return view('dashboard_anggota.dashboard', compact('notifikasi_status', 'peminjaman_saya', 'peminjaman_terlambat'));
        }
    }


    /**
     * [BARU] Menyetujui pendaftaran anggota.
     */
    public function approve(User $user)
    {
        $user->status = 'active';
        $user->tanggal_daftar = now();
        $user->save();

        // Kirim email notifikasi
        Mail::to($user->email)->send(new MemberApproved($user));

        return back()->with('success', 'Pendaftaran anggota berhasil disetujui.');
    }

    /**
     * [BARU] Menolak pendaftaran anggota.
     */
    public function reject(Request $request, User $user)
    {
        $request->validate(['alasan_penolakan' => 'required|string|min:10']);

        $alasan = $request->alasan_penolakan;
        $namaUser = $user->nama;
        $emailUser = $user->email;

        // Kirim email notifikasi penolakan
        Mail::to($emailUser)->send(new MemberRejected($namaUser, $alasan));

        // Hapus data pengguna yang ditolak
        $user->delete();

        return back()->with('success', 'Pendaftaran anggota telah ditolak.');
    }
}
