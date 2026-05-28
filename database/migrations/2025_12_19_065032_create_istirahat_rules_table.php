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
        Schema::create('istirahat_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jam_kerja_group_id')
                ->constrained('jam_kerja_groups')
                ->onDelete('cascade');

            $table->integer('durasi_kerja_min')->default(0);
            $table->integer('potong_istirahat')->default(0);

            $table->boolean('tidak_istirahat_jadi_lembur')->default(false);
            $table->integer('batas_istirahat')->default(60);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('istirahat_rules');
    }
};
