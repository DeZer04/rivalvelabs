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
        Schema::create('izin_types', function (Blueprint $table) {
            $table->id();

            $table->string('kode')->unique(); 
            // IZIN_TIDAK_MASUK, IZIN_DINAS, CUTI_NORMATIF, TIDAK_SCAN, LIBUR, DLL

            $table->string('nama');

            // Behaviour
            $table->boolean('perlu_jam')->default(false);
            $table->boolean('boleh_setengah_hari')->default(false);
            $table->boolean('boleh_multi_hari')->default(true);

            // Efek ke absensi
            $table->enum('hasil_status_absensi', [
                'hadir',
                'izin',
                'cuti',
                'sakit',
                'libur',
                'tidak_hadir'
            ]);

            $table->boolean('hitung_kerja')->default(false);
            $table->boolean('hitung_lembur')->default(false);
            $table->boolean('potong_jatah_cuti')->default(false);

            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_types');
    }
};
