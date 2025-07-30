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
        Schema::create('penilaian_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')
                  ->constrained('penilaians')
                  ->onDelete('cascade')
                  ->comment('ID penilaian yang terkait');
            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->onDelete('cascade')
                  ->comment('ID karyawan yang terkait');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_karyawans');
    }
};
