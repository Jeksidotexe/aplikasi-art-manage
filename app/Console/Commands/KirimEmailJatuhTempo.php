<?php

namespace App\Console\Commands;

use App\Models\Peminjaman_alat;
use App\Mail\PengingatJatuhTempoMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class KirimEmailJatuhTempo extends Command
{
    protected $signature = 'app:kirim-email-jatuh-tempo';
    protected $description = 'Mengirim email pengingat untuk peminjaman yang jatuh tempo hari ini.';

    public function handle()
    {
        $this->info('Mulai mencari peminjaman yang jatuh tempo hari ini...');

        // Cari peminjaman yang statusnya 'dipinjam', jatuh tempo hari ini,
        // dan belum pernah dikirimi notifikasi jatuh tempo.
        $peminjamanJatuhTempo = Peminjaman_alat::with('users', 'alat')
            ->where('status', 'dipinjam')
            ->whereDate('tanggal_harus_kembali', now()) // Kunci logikanya ada di sini
            ->where('notifikasi_jatuh_tempo_dikirim', false)
            ->get();

        if ($peminjamanJatuhTempo->isEmpty()) {
            $this->info('Tidak ada peminjaman yang jatuh tempo hari ini.');
            return;
        }

        $this->info("Ditemukan {$peminjamanJatuhTempo->count()} peminjaman. Memulai pengiriman email...");

        foreach ($peminjamanJatuhTempo as $peminjaman) {
            // Kirim email ke pengguna
            Mail::to($peminjaman->users->email)->send(new PengingatJatuhTempoMail($peminjaman));

            // Tandai bahwa notifikasi telah dikirim
            $peminjaman->notifikasi_jatuh_tempo_dikirim = true;
            $peminjaman->save();

            $this->info("Email jatuh tempo dikirim ke: {$peminjaman->users->nama} ({$peminjaman->users->email})");
        }

        $this->info('Selesai.');
    }
}
