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
        Schema::create('penilaian_detail_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_karyawan_id')
                  ->constrained('penilaian_karyawans')
                  ->onDelete('cascade')
                  ->comment('ID penilaian karyawan yang terkait');
            $table->foreignId('kriteria_id')
                  ->constrained('kriterias')
                  ->onDelete('cascade')
                  ->comment('ID kriteria yang terkait');
            $table->decimal('nilai', 8, 2)->comment('Nilai yang diberikan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_detail_kriterias');
    }
};
