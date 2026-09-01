<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->enum('status_sinkron_fisik', [
                'tidak_perlu',
                'belum_disinkronkan',
                'sudah_disinkronkan',
            ])->default('tidak_perlu')->after('status');

            $table->timestamp('disinkronkan_pada')->nullable()->after('status_sinkron_fisik');

            $table->foreignId('disinkronkan_oleh_petugas_id')
                ->nullable()
                ->after('disinkronkan_pada')
                ->constrained('petugas')
                ->nullOnDelete();

            $table->index('status_sinkron_fisik');
        });
    }

    public function down(): void
    {
        Schema::table('reservasis', function (Blueprint $table) {
            $table->dropConstrainedForeignId('disinkronkan_oleh_petugas_id');
            $table->dropColumn(['status_sinkron_fisik', 'disinkronkan_pada']);
        });
    }
};