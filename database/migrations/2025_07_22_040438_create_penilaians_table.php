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
        Schema::create('penilaians', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penilaian')->comment('Nama penilaian');
            $table->year('tahun')->comment('Tahun penilaian');
            $table->enum('periode', ['Januari-Juni', 'Juli-Desember'])->comment('Periode penilaian');
            $table->foreignId('group_kriteria_id')
                  ->constrained('group_kriterias')
                  ->onDelete('cascade')
                  ->comment('ID grup kriteria yang terkait');
            $table->foreignId('penilai_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('ID penilai yang terkait');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
