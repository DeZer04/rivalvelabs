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
        Schema::create('ahp_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_kriteria_id')
            ->constrained('group_kriterias')
            ->onDelete('cascade');
            $table->text('original_matrix');
            $table->text('normalized_matrix');
            $table->text('weights');
            $table->decimal('lambda_max', 16, 8);
            $table->decimal('consistency_index', 16, 8);
            $table->decimal('random_index', 16, 8);
            $table->decimal('consistency_ratio', 16, 8);
            $table->boolean('is_consistent');
            $table->text('weighted_sum');
            $table->text('consistency_vector');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_results');
    }
};
