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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_kriteria_id')
                ->constrained('group_kriterias')
                ->onDelete('cascade')
                ->comment('ID Group Kriteria');
            $table->string('nama_kriteria')->unique()->comment('Nama Kriteria');
            $table->boolean('is_benefit')->default(false)->comment('Apakah Kriteria Ini Menguntungkan?');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
