<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_reservasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                ->constrained('reservasis')
                ->cascadeOnDelete();
            $table->string('nama_file_asli', 255);
            $table->string('nama_file_sistem', 255);
            $table->string('path_file', 255);
            $table->string('mime_type', 100);
            $table->unsignedInteger('ukuran_file');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['reservasi_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_reservasis');
    }
};