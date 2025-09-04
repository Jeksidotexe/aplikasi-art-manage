<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Peminjaman_alat;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Mengarahkan 'path.storage' ke direktori /tmp yang bisa ditulisi
        $this->app->bind('path.storage', function () {
            return '/tmp/storage';
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('id');

        // Membuat View Composer untuk view yang berisi header
        // Ganti 'layouts.header' dengan path view header Anda jika berbeda
        View::composer(['layouts.header', 'layouts.master'], function ($view) {
            if (Auth::check() && Auth::user()->role == 'admin') {
                // 1. Ambil data notifikasi pendaftaran pending
                $list_pendaftaran_pending = User::where('status', 'pending')
                    ->whereNotNull('email_verified_at')
                    ->latest()
                    ->take(3) // Ambil 3 notif terbaru
                    ->get();
                $count_pendaftaran = $list_pendaftaran_pending->count();

                // 2. Ambil data notifikasi peminjaman alat yang diajukan
                $list_peminjaman_pending = Peminjaman_alat::with('users')
                    ->where('status', 'diajukan')
                    ->latest()
                    ->take(3) // Ambil 3 notif terbaru
                    ->get();
                $count_peminjaman = $list_peminjaman_pending->count();

                // 3. Hitung total notifikasi
                $total_notif = $count_pendaftaran + $count_peminjaman;

                // 4. Kirim data ke view
                $view->with(compact(
                    'total_notif',
                    'list_pendaftaran_pending',
                    'list_peminjaman_pending'
                ));
            }
        });
    }
}
