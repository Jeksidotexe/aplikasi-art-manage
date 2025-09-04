<?php

namespace App\Console\Commands;

use App\Models\Peminjaman_alat;
use App\Mail\PengingatKeterlambatanMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimEmailKeterlambatan extends Command
{
    protected $signature = 'app:kirim-email-keterlambatan';
    protected $description = 'Mencari peminjaman yang terlambat dan mengirim email pengingat.';

    public function handle()
    {
        $this->info('Mulai mencari peminjaman terlambat...');

        // Cari peminjaman yang statusnya 'dipinjam', sudah lewat jatuh tempo,
        // dan belum pernah dikirimi notifikasi.
        $peminjamanTerlambat = Peminjaman_alat::with('users', 'alat')
            ->where('status', 'dipinjam')
            ->where('tanggal_harus_kembali', '<', now())
            ->where('notifikasi_terlambat_dikirim', false)
            ->get();

        if ($peminjamanTerlambat->isEmpty()) {
            $this->info('Tidak ada peminjaman terlambat yang perlu notifikasi.');
            return;
        }

        $this->info("Ditemukan {$peminjamanTerlambat->count()} peminjaman terlambat. Memulai pengiriman email...");

        foreach ($peminjamanTerlambat as $peminjaman) {
            // Kirim email ke pengguna
            Mail::to($peminjaman->users->email)->send(new PengingatKeterlambatanMail($peminjaman));

            // Tandai bahwa notifikasi telah dikirim
            $peminjaman->notifikasi_terlambat_dikirim = true;
            $peminjaman->save();

            $this->info("Email pengingat dikirim ke: {$peminjaman->users->nama} ({$peminjaman->users->email})");
        }

        $this->info('Selesai.');
    }
}
