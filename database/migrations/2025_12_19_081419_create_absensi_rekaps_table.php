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
        Schema::create('absensi_rekaps', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nip');

            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();

            $table->integer('terlambat')->default(0); //dalam menit
            $table->integer('pulang_awal')->default(0); //dalam menit

            $table->integer('durasi_kerja')->default(0); //dalam menit
            $table->integer('durasi_lembur')->default(0); //dalam

            $table->enum('status', [
                'hadir',
                'setengah_hari',
                'tidak_hadir',
                'libur'
            ]);

            $table->unique(['nip', 'tanggal']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_rekaps');
    }
};
