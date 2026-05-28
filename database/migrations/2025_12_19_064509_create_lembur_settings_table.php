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
        Schema::create('lembur_settings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jam_kerja_group_id')
                ->constrained('jam_kerja_groups')
                ->onDelete('cascade');

            $table->integer('minimal_lembur')->default(120);
            $table->integer('maksimal_lembur')->default(240);

            $table->boolean('lembur_libur_rutin')->default(true);
            $table->boolean('lembur_libur_nasional')->default(true);

            $table->boolean('hitung_pakai_index')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lembur_settings');
    }
};
