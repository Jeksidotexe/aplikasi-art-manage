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
        Schema::create('agenda_kegiatan', function (Blueprint $table) {
            $table->id('id_agenda');
            $table->unsignedBigInteger('id_bidang');
            $table->string('nama_kegiatan');
            $table->dateTime('tanggal');
            $table->string('lokasi');
            $table->text('keterangan');
            $table->string('file_sk');
            $table->timestamps();

            // Relationship
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda_kegiatan');
    }
};
