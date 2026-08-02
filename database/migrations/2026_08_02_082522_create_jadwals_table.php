<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')
                ->constrained('layanans')
                ->restrictOnDelete();
            $table->date('tanggal');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('kuota_maksimal')->default(0);
            $table->unsignedInteger('kuota_terpakai')->default(0);
            $table->timestamps();

            $table->unique(['layanan_id', 'tanggal', 'jam_mulai']);
            $table->index(['tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};