<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panggilan_antreans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->cascadeOnDelete();
            $table->string('kode_layanan', 10);
            $table->unsignedInteger('nomor_urut');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'gagal'])->default('pending');
            $table->text('pesan_error')->nullable();
            $table->timestamp('diproses_pada')->nullable();
            $table->timestamp('selesai_pada')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panggilan_antreans');
    }
};