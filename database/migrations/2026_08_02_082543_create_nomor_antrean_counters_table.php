<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nomor_antrean_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')
                ->constrained('layanans')
                ->restrictOnDelete();
            $table->date('tanggal');
            $table->unsignedInteger('urutan_terakhir')->default(0);
            $table->timestamps();

            $table->unique(['layanan_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nomor_antrean_counters');
    }
};