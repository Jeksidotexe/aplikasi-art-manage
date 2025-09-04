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
        Schema::create('alat', function (Blueprint $table) {
            $table->id('id_alat');
            $table->unsignedBigInteger('id_bidang');
            $table->string('nama_alat');
            $table->string('merk');
            $table->integer('jumlah');
            $table->date('tanggal_beli');
            $table->enum('kondisi', ['baik', 'rusak', 'perlu perbaikan']);
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
        Schema::dropIfExists('alat');
    }
};
