<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->cascadeOnDelete();

            // Sama seperti status_histories, FK ke `petugas` ditunda sampai modul CS dibuat.
            $table->unsignedBigInteger('petugas_id')->nullable();

            $table->text('isi_catatan');
            $table->timestamps();

            $table->index(['reservasi_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi_notes');
    }
};