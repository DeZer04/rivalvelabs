<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jam_kerja_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_kerja_group_id')
                ->constrained('jam_kerja_groups')
                ->onDelete('cascade');

            // Tidak scan masuk
            $table->enum('tanpa_scan_masuk', [
                'tidak_ada_hukuman',
                'terlambat',
                'setengah_hari',
                'tidak_hadir'
            ])->default('tidak_ada_hukuman');

            $table->integer('menit_terlambat')->nullable();

            // Tidak scan pulang
            $table->enum('tanpa_scan_pulang', [
                'tidak_ada_hukuman',
                'pulang_cepat',
                'setengah_hari',
                'tidak_hadir'
            ])->default('tidak_ada_hukuman');

            $table->integer('menit_pulang_cepat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_policies');
    }
};
