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
        Schema::create('jam_kerja_groups', function (Blueprint $table) {
            $table->id();

            $table->string('nama');
            $table->string('kode')->unique();

            $table->time('jam_masuk');
            $table->time('jam_pulang');

            $table->enum('jenis', ['efektif', 'aktual'])->default('efektif');

            //scan window
            $table->integer('durasi_sebelum_masuk')->default(60);   //dalam menit
            $table->integer('durasi_setelah_masuk')->default(60);   //dalam menit
            $table->integer('durasi_sebelum_pulang')->default(60);
            $table->integer('durasi_setelah_pulang')->default(60);

            // Toleransi
            $table->integer('toleransi_terlambat')->default(0); //dalam menit
            $table->integer('toleransi_pulang_awal')->default(0); //dalam menit

            // Minimal durasi kerja
            $table->integer('min_half_day')->default(240); //dalam menit    
            $table->integer('min_full_day')->default(450); //dalam menit

            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jam_kerja_groups');
    }
};
