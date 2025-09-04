<?php

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Middleware\CekRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlatController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\BidangController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AgendaKegiatanController;
use App\Http\Controllers\PeminjamanAlatController;
use App\Http\Controllers\KehadiranKegiatanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('register', [LoginController::class, 'showLoginForm']);

Route::post('login', [LoginController::class, 'login']);
Route::post('register', [RegisterController::class, 'register'])->name('register.post');
Route::get('/anggota/get-prodi', [AnggotaController::class, 'getProdi'])->name('anggota.get_prodi');
Route::get('/email/verified', [RegisterController::class, 'showVerifiedNotice'])->name('verification.verified_notice');

// --- GRUP RUTE VERIFIKASI EMAIL ---

// Rute untuk menampilkan halaman "Cek Email Anda" (memerlukan auth sesaat setelah registrasi)
Route::get('email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

// Rute yang dieksekusi dari link di email (TIDAK MEMERLUKAN AUTH)
Route::get('email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    // [PERUBAHAN] Logika verifikasi manual
    $user = User::findOrFail($id);

    // Cek jika hash valid (tanpa memerlukan login)
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    // Cek jika user belum pernah verifikasi sebelumnya
    if ($user->hasVerifiedEmail()) {
        return redirect()->route('login')->with('status', 'Email Anda sudah terverifikasi sebelumnya.');
    }

    // Lakukan verifikasi
    $user->markEmailAsVerified();

    // Arahkan ke halaman "Verifikasi Berhasil"
    return redirect()->route('verification.verified_notice')->with('status', 'Email berhasil diverifikasi! Mohon tunggu persetujuan admin.');
})->middleware(['signed'])->name('verification.verify');

// Rute untuk mengirim ulang link verifikasi (memerlukan auth)
Route::post('email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Link verifikasi baru telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Grup untuk semua user yang sudah terautentikasi
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/{user}/approve', [DashboardController::class, 'approve'])->name('dashboard.approve');
    Route::post('/dashboard/{user}/reject', [DashboardController::class, 'reject'])->name('dashboard.reject');

    // ===================================================================
    // ⭐️ GRUP 1: RUTE HANYA UNTUK ADMIN
    // ===================================================================
    Route::group(['middleware' => [CekRole::class . ':admin']], function () {

        // --- Data Master ---
        Route::get('/jurusan/data', [JurusanController::class, 'data'])->name('jurusan.data');
        Route::resource('/jurusan', JurusanController::class);

        Route::get('/prodi/data', [ProdiController::class, 'data'])->name('prodi.data');
        Route::resource('/prodi', ProdiController::class);

        Route::get('/bidang/data', [BidangController::class, 'data'])->name('bidang.data');
        Route::resource('/bidang', BidangController::class);

        Route::get('/alat/data', [AlatController::class, 'data'])->name('alat.data');
        Route::resource('/alat', AlatController::class);

        Route::get('/anggota/data', [AnggotaController::class, 'data'])->name('anggota.data');
        Route::post('/anggota/cetak-anggota', [AnggotaController::class, 'cetakAnggota'])->name('anggota.cetak_anggota');
        // Route::get('/anggota/get-prodi', [AnggotaController::class, 'getProdi'])->name('anggota.get_prodi');
        Route::resource('/anggota', AnggotaController::class);

        // --- Aksi Peminjaman Alat oleh Admin ---
        Route::post('/peminjaman_alat/approve/{id}', [PeminjamanAlatController::class, 'approve'])->name('peminjaman_alat.approve');
        Route::post('/peminjaman_alat/reject/{id}', [PeminjamanAlatController::class, 'reject'])->name('peminjaman_alat.reject');
        Route::post('/peminjaman_alat/confirm-pickup/{id}', [PeminjamanAlatController::class, 'confirmPickup'])->name('peminjaman_alat.confirm_pickup');
        Route::post('/peminjaman_alat/process-return/{id}', [PeminjamanAlatController::class, 'processReturn'])->name('peminjaman_alat.process_return');
        Route::delete('/peminjaman_alat/{peminjaman_alat}', [PeminjamanAlatController::class, 'destroy'])->name('peminjaman_alat.destroy');
        Route::post('/peminjaman_alat/cetak', [PeminjamanAlatController::class, 'cetakPeminjaman'])->name('peminjaman_alat.cetak_peminjaman');
        Route::get('/peminjaman/users', [PeminjamanAlatController::class, 'getUsers'])->name('peminjaman_alat.users');
        Route::get('/peminjaman/get-anggota/{nim}', [PeminjamanAlatController::class, 'getAnggotaByNim'])->name('peminjaman_alat.get_anggota');
        Route::resource('peminjaman_alat', PeminjamanAlatController::class);

        // --- Kehadiran & Agenda Kegiatan (Admin) ---
        Route::get('/agenda_kegiatan/data', [AgendaKegiatanController::class, 'data'])->name('agenda_kegiatan.data');
        Route::post('/agenda_kegiatan/cetak-agenda', [AgendaKegiatanController::class, 'cetakAgenda'])->name('agenda_kegiatan.cetak_agenda');
        Route::resource('/agenda_kegiatan', AgendaKegiatanController::class);
        Route::get('/preview/{filename}', [AgendaKegiatanController::class, 'preview'])->name('agenda_kegiatan.preview');

        Route::get('/get_anggota_by_bidang/{id_bidang}', [KehadiranKegiatanController::class, 'getAnggotaByBidang'])->name('kehadiran_kegiatan.get_anggota_by_bidang');
        Route::get('/anggota_per_bidang/{id_bidang}', [KehadiranKegiatanController::class, 'getAnggotaByBidang']);
        Route::get('/kehadiran_kegiatan/data', [KehadiranKegiatanController::class, 'data'])->name('kehadiran_kegiatan.data');
        Route::get('/get-agenda-detail/{id_agenda}', [KehadiranKegiatanController::class, 'getAgendaDetail'])->name('agenda_kegiatan.get_detail');
        Route::post('/kehadiran_kegiatan/cetak-kehadiran', [KehadiranKegiatanController::class, 'cetakKehadiran'])->name('kehadiran_kegiatan.cetak_kehadiran');
        Route::resource('/kehadiran_kegiatan', KehadiranKegiatanController::class);
        Route::get('/kehadiran_kegiatan/preview/{filename}', [KehadiranKegiatanController::class, 'preview'])->name('kehadiran_kegiatan.preview');

        // --- Laporan ---
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');
    });

    // ===================================================================
    // ⭐️ GRUP 2: RUTE UNTUK ADMIN DAN ANGGOTA (SHARED)
    // ===================================================================
    Route::group(['middleware' => [CekRole::class . ':admin,anggota']], function () {

        // --- Peminjaman Alat (Aksi Bersama) ---
        Route::get('/peminjaman_alat', [PeminjamanAlatController::class, 'index'])->name('peminjaman_alat.index');
        Route::post('/peminjaman_alat', [PeminjamanAlatController::class, 'store'])->name('peminjaman_alat.store');
        Route::get('/peminjaman_alat/{peminjaman_alat}', [PeminjamanAlatController::class, 'show'])->name('peminjaman_alat.show');
        Route::get('/peminjaman_alat_data', [PeminjamanAlatController::class, 'data'])->name('peminjaman_alat.data');
        Route::get('/peminjaman/users', [PeminjamanAlatController::class, 'getUsers'])->name('peminjaman_alat.users');
        Route::get('/peminjaman/get-anggota/{nim}', [PeminjamanAlatController::class, 'getAnggotaByNim'])->name('peminjaman_alat.get_anggota');
        Route::resource('peminjaman_alat', PeminjamanAlatController::class);

        // --- Helper untuk Form Peminjaman ---
        Route::get('/anggota/find-by-nim/{nim}', [PeminjamanAlatController::class, 'getAnggotaByNim'])->name('anggota.find_by_nim');
        Route::get('/alat/find-by-id/{id}', [PeminjamanAlatController::class, 'getAlatById'])->name('alat.find_by_id');

        // --- Agenda Kegiatan (Aksi Bersama) ---
        Route::get('/agenda_kegiatan/data', [AgendaKegiatanController::class, 'data'])->name('agenda_kegiatan.data');
        Route::post('/agenda_kegiatan/cetak-agenda', [AgendaKegiatanController::class, 'cetakAgenda'])->name('agenda_kegiatan.cetak_agenda');
        Route::resource('/agenda_kegiatan', AgendaKegiatanController::class);
        Route::get('/preview/{filename}', [AgendaKegiatanController::class, 'preview'])->name('agenda_kegiatan.preview');

        // --- Profil (Aksi Bersama) ---
        Route::get('/profil', [ProfilController::class, 'profil'])->name('user.profil');
        Route::post('/profil', [ProfilController::class, 'updateProfil'])->name('user.update_profil');
    });
});
