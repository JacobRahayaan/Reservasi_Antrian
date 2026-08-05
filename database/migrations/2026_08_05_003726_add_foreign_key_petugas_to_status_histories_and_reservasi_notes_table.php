<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->foreign('petugas_id')
                ->references('id')
                ->on('petugas')
                ->nullOnDelete();
        });

        Schema::table('reservasi_notes', function (Blueprint $table) {
            $table->foreign('petugas_id')
                ->references('id')
                ->on('petugas')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('status_histories', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
        });

        Schema::table('reservasi_notes', function (Blueprint $table) {
            $table->dropForeign(['petugas_id']);
        });
    }
};