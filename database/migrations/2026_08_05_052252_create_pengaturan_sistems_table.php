<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistems', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi', 100);
            $table->string('nomor_contact_center', 20);
            $table->string('email_contact_center', 150)->nullable();
            $table->string('alamat_kantor', 255)->nullable();
            $table->time('jam_buka_default');
            $table->time('jam_tutup_default');
            $table->unsignedInteger('maksimal_ukuran_dokumen_mb')->default(2);
            $table->unsignedInteger('maksimal_jumlah_dokumen')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistems');
    }
};