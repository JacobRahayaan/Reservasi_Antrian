<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi', 20)->unique();
            $table->string('token_akses', 64)->unique();
            $table->string('nomor_antrean', 20);
            $table->foreignId('layanan_id')
                ->constrained('layanans')
                ->restrictOnDelete();
            $table->foreignId('jadwal_id')
                ->constrained('jadwals')
                ->restrictOnDelete();
            $table->string('nama', 100);
            $table->string('nomor_hp', 20);
            $table->string('email', 150)->nullable();
            $table->text('keluhan');
            $table->enum('status', [
                'menunggu_review',
                'perlu_datang',
                'selesai_online',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_review');
            $table->timestamps();

            $table->unique(['nomor_antrean', 'jadwal_id']);
            $table->index(['nomor_antrean', 'nomor_hp']);
            $table->index(['status']);
            $table->index(['layanan_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};