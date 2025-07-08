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
        Schema::create('pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_kriteria_id')
                ->constrained('group_kriterias')
                ->onDelete('cascade')
                ->comment('ID Group Kriteria');
            $table->foreignId('kriteria_1_id')
                ->constrained('kriterias')
                ->onDelete('cascade')
                ->comment('ID Kriteria');
            $table->foreignId('kriteria_2_id')
                ->constrained('kriterias')
                ->onDelete('cascade')
                ->comment('ID Kriteria');
            $table->decimal('nilai_perbandingan', 5, 2)
                ->comment('Nilai Perbandingan antara Kriteria 1 dan Kriteria 2');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
    }
};
