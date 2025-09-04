<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjaman_alat', function (Blueprint $table) {
            $table->id('id_peminjaman');
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_alat');
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam')->nullable();
            $table->date('tanggal_harus_kembali')->nullable();
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak', 'dipinjam', 'dikembalikan']);
            $table->text('keterangan_admin')->nullable();
            $table->boolean('notifikasi_terlambat_dikirim')->default(false);
            $table->boolean('notifikasi_jatuh_tempo_dikirim')->default(false);
            $table->timestamps();

            // Relationship
            $table->foreign('id_users')->references('id_users')->on('users');
            $table->foreign('id_alat')->references('id_alat')->on('alat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat');
    }
};
