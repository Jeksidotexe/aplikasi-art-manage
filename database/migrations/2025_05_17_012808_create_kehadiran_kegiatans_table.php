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
        Schema::create('kehadiran_kegiatan', function (Blueprint $table) {
            $table->id('id_kehadiran');
            $table->unsignedBigInteger('id_agenda');
            $table->unsignedBigInteger('id_bidang');
            $table->string('file_absensi');
            $table->timestamps();

            // Relationship
            $table->foreign('id_agenda')->references('id_agenda')->on('agenda_kegiatan');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadiran_kegiatan');
    }
};
