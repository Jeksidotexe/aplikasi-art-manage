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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_users');
            $table->string('nim')->nullable()->unique();
            $table->string('nama');
            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('no_telepon');
            $table->unsignedBigInteger('id_bidang')->nullable();
            $table->date('tanggal_daftar')->nullable();
            $table->string('foto')->nullable();
            $table->enum('role', ['admin', 'anggota']);
            $table->enum('status', ['pending', 'active', 'rejected'])->default('pending');
            $table->rememberToken();
            $table->timestamps();

            // Relationship
            $table->foreign('id_jurusan')->references('id_jurusan')->on('jurusan');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_bidang')->references('id_bidang')->on('bidang');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
