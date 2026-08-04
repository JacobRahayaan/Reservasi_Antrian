<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->unsignedInteger('estimasi_menit_min')
                ->nullable()
                ->after('deskripsi');

            $table->unsignedInteger('estimasi_menit_max')
                ->nullable()
                ->after('estimasi_menit_min');

            $table->softDeletes();

            // Untuk mempercepat pencarian
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('layanans', function (Blueprint $table) {

            $table->dropIndex(['is_active']);

            $table->dropColumn([
                'estimasi_menit_min',
                'estimasi_menit_max',
            ]);

            $table->dropSoftDeletes();
        });
    }
};