<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->cascadeOnDelete();

            // Kolom petugas_id belum diberi foreign key constraint karena tabel
            // `petugas` (modul Customer Service) belum dibangun pada sprint ini.
            // Constraint ditambahkan lewat migration terpisah saat modul CS dibuat.
            $table->unsignedBigInteger('petugas_id')->nullable();

            $table->enum('status_sebelum', [
                'menunggu_review',
                'perlu_datang',
                'selesai_online',
                'selesai',
                'dibatalkan',
            ])->nullable();
            $table->enum('status_sesudah', [
                'menunggu_review',
                'perlu_datang',
                'selesai_online',
                'selesai',
                'dibatalkan',
            ]);
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('changed_at')->useCurrent();

            $table->index(['reservasi_id', 'changed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};